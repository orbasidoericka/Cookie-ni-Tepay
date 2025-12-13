<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class CheckoutController extends Controller
{
    /**
     * Display the checkout form.
     */
    public function index()
    {
        $cart = session()->get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('shop.index')
                ->with('error', 'Your cart is empty.');
        }

        $products = [];
        $total = 0;
        $hasStockIssues = false;

        foreach ($cart as $id => $quantity) {
            // SQL: SELECT * FROM products WHERE id = ? LIMIT 1
            $product = Product::find($id);
            if ($product) {
                // Check stock availability
                if ($product->stock <= 0) {
                    $hasStockIssues = true;
                    continue; // Skip out of stock items
                }
                
                if ($product->stock < $quantity) {
                    $hasStockIssues = true;
                    // Adjust quantity to available stock
                    $quantity = $product->stock;
                }
                
                $products[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'subtotal' => $product->price * $quantity
                ];
                $total += $product->price * $quantity;
            }
        }

        if (empty($products)) {
            return redirect()->route('shop.cart')
                ->with('error', 'All items in your cart are out of stock.');
        }

        if ($hasStockIssues) {
            return redirect()->route('shop.cart')
                ->with('error', 'Some items have stock issues. Please review your cart.');
        }

        return view('shop.checkout', compact('products', 'total'));
    }

    /**
     * Process the checkout with ACID properties.
     * 
     * ATOMICITY: All operations (stock reduction, order creation, item creation) 
     *            are wrapped in a single transaction. If any fails, all are rolled back.
     * 
     * CONSISTENCY: Stock validation ensures we never oversell. Foreign key constraints
     *              maintain referential integrity.
     * 
     * ISOLATION: Using lockForUpdate() prevents concurrent transactions from reading
     *            the same stock values (prevents race conditions).
     * 
     * DURABILITY: Once committed, the transaction is persisted to the database
     *             and survives system failures.
     */
    public function process(Request $request)
    {
        // Validate customer information
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'contact_number' => ['required', 'regex:/^09\d{9}$/', 'digits:11'],
            'address' => 'nullable|string|max:500',
            'notes' => 'nullable|string|max:1000',
        ], [
            'contact_number.regex' => 'Contact number must start with 09 and be exactly 11 digits.',
            'contact_number.digits' => 'Contact number must be exactly 11 digits.',
        ]);

        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('shop.index')
                ->with('error', 'Your cart is empty.');
        }

        try {
            // ============================================================
            // ACID TRANSACTION START
            // ============================================================
            // 
            // SQL: BEGIN; (or START TRANSACTION;)
            // 
            // Using DB::transaction() ensures:
            // - ATOMICITY: All or nothing - if any operation fails, everything rolls back
            // - DURABILITY: Once committed, data persists even on system failure
            //
            // The closure runs within a database transaction. If an exception
            // is thrown, Laravel automatically rolls back all changes.
            // ============================================================
            
            $order = DB::transaction(function () use ($cart, $validated) {
                $orderItems = [];
                $totalAmount = 0;

                // ============================================================
                // ISOLATION: Pessimistic Locking with lockForUpdate()
                // ============================================================
                // 
                // lockForUpdate() implements "SELECT ... FOR UPDATE" which:
                // - Acquires an exclusive lock on the selected rows
                // - Prevents other transactions from reading/modifying these rows
                // - Ensures no two customers can checkout the same last item
                // - Protects against race conditions in concurrent environments
                // ============================================================

                foreach ($cart as $productId => $quantity) {
                    // Lock the product row for this transaction
                    // Other concurrent transactions will wait until this completes
                    // SQL: SELECT * FROM products WHERE id = ? FOR UPDATE
                    $product = Product::lockForUpdate()->find($productId);

                    if (!$product) {
                        throw new Exception("Product #{$productId} no longer exists.");
                    }

                    // ============================================================
                    // CONSISTENCY: Stock Validation
                    // ============================================================
                    // 
                    // Ensures database moves from one valid state to another:
                    // - Stock can never go negative
                    // - Order totals must match item subtotals
                    // - All business rules are enforced within the transaction
                    // ============================================================

                    if ($product->stock < $quantity) {
                        throw new Exception(
                            "Insufficient stock for '{$product->name}'. " .
                            "Available: {$product->stock}, Requested: {$quantity}"
                        );
                    }

                    // Calculate subtotal for this item
                    $subtotal = $product->price * $quantity;
                    $totalAmount += $subtotal;

                    // Store order item data (snapshot of current product state)
                    $orderItems[] = [
                        'product_id' => $product->id,
                        'product_name' => $product->name, // Snapshot for historical record
                        'price' => $product->price,       // Price at time of purchase
                        'quantity' => $quantity,
                        'subtotal' => $subtotal,
                    ];

                    // ============================================================
                    // ATOMIC STOCK REDUCTION
                    // ============================================================
                    // 
                    // Using decrement() is atomic at the database level.
                    // Combined with the transaction, this ensures stock is only
                    // reduced if the entire checkout succeeds.
                    // 
                    // SQL: UPDATE products SET stock = stock - ?, updated_at = ? WHERE id = ?
                    // ============================================================

                    $product->decrement('stock', $quantity);
                    
                    // Log for debugging/audit
                    Log::info("Stock reduced", [
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'quantity_sold' => $quantity,
                        'remaining_stock' => $product->stock - $quantity
                    ]);
                }

                // ============================================================
                // CREATE ORDER (within transaction)
                // 
                // SQL: INSERT INTO orders (order_number, customer_name, contact_number, 
                //       total_amount, status, notes, created_at, updated_at) 
                //       VALUES (?, ?, ?, ?, ?, ?, ?, ?)
                // ============================================================
                
                $order = Order::create([
                    'order_number' => Order::generateOrderNumber(),
                    'user_id' => auth()->id(),
                    'customer_name' => $validated['customer_name'],
                    'contact_number' => $validated['contact_number'],
                    'address' => $validated['address'] ?? null,
                    'total_amount' => $totalAmount,
                    'status' => 'completed',
                    'notes' => $validated['notes'] ?? null,
                ]);

                // ============================================================
                // CREATE ORDER ITEMS (within transaction)
                // 
                // SQL: INSERT INTO order_items (order_id, product_id, product_name, 
                //       price, quantity, subtotal, created_at, updated_at)
                //       VALUES (?, ?, ?, ?, ?, ?, ?, ?)
                // ============================================================
                
                foreach ($orderItems as $item) {
                    $order->items()->create($item);
                }

                Log::info("Order completed successfully", [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'total_amount' => $totalAmount,
                    'items_count' => count($orderItems)
                ]);

                return $order;

            }); // Transaction commits here if no exceptions were thrown
            
            // ============================================================
            // ACID TRANSACTION END - SUCCESS
            // ============================================================
            // 
            // SQL: COMMIT;
            // 
            // If we reach this point:
            // - All stock has been reduced
            // - Order has been created
            // - Order items have been created
            // - Transaction has been COMMITTED (DURABILITY)
            // ============================================================

            // Clear the cart only after successful transaction
            session()->forget('cart');

            return redirect()->route('order.confirmation', $order)
                ->with('success', 'Order placed successfully!');

        } catch (Exception $e) {
            // ============================================================
            // ACID TRANSACTION - ROLLBACK
            // ============================================================
            // 
            // SQL: ROLLBACK;
            // 
            // If any exception was thrown:
            // - All database changes are ROLLED BACK (ATOMICITY)
            // - Stock levels remain unchanged
            // - No order is created
            // - Database remains in its previous valid state (CONSISTENCY)
            // ============================================================

            Log::error("Checkout failed", [
                'error' => $e->getMessage(),
                'cart' => $cart
            ]);

            return redirect()->route('shop.cart')
                ->with('error', 'Checkout failed: ' . $e->getMessage());
        }
    }

    /**
     * Display order confirmation.
     */
    public function confirmation(Order $order)
    {
        $order->load('items'); // Eager load items
        return view('shop.confirmation', compact('order'));
    }

    /**
     * Display order history (demonstrates ACID query isolation).
     */
    public function history()
    {
        // SQL: SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC LIMIT 10 OFFSET 0
        // SQL: SELECT * FROM order_items WHERE order_id IN (?, ?, ...)
        $orders = Order::with('items')
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('shop.orders', compact('orders'));
    }

    /**
     * Demonstrate a simulated concurrent checkout scenario.
     * This method shows how isolation prevents race conditions.
     */
    public function simulateConcurrency(Request $request)
    {
        $productId = $request->input('product_id', 1);
        $quantity = $request->input('quantity', 1);

        try {
            $result = DB::transaction(function () use ($productId, $quantity) {
                // Simulate delay to allow concurrent requests
                // In production, remove this - it's just for demonstration
                usleep(100000); // 100ms delay

                $product = Product::lockForUpdate()->findOrFail($productId);

                if ($product->stock < $quantity) {
                    throw new Exception("Race condition prevented! Stock: {$product->stock}");
                }

                $product->decrement('stock', $quantity);

                return [
                    'success' => true,
                    'product' => $product->name,
                    'remaining_stock' => $product->fresh()->stock
                ];
            });

            return response()->json($result);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 400);
        }
    }
}
