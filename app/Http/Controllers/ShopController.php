<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index()
    {
        // SQL: SELECT * FROM products
        $products = Product::all();
        return view('shop.index', compact('products'));
    }

    public function cart()
    {
        $cart = session()->get('cart', []);
        $products = [];
        $total = 0;
        $hasStockIssues = false;

        foreach ($cart as $id => $quantity) {
            // SQL: SELECT * FROM products WHERE id = ? LIMIT 1
            $product = Product::find($id);
            if ($product) {
                // Check if quantity exceeds available stock
                $availableQty = min($quantity, $product->stock);
                $hasIssue = $quantity > $product->stock;
                
                if ($hasIssue) {
                    $hasStockIssues = true;
                }
                
                $products[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'available_quantity' => $availableQty,
                    'has_stock_issue' => $hasIssue,
                    'subtotal' => $product->price * $availableQty
                ];
                $total += $product->price * $availableQty;
            }
        }

        return view('shop.cart', compact('products', 'total', 'hasStockIssues'));
    }

    public function addToCart(Request $request, Product $product)
    {
        // Validate quantity
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $quantity = $request->input('quantity', 1);

        // Check if product has stock
        if ($product->stock <= 0) {
            if ($request->expectsJson()) {
                return response()->json(['error' => $product->name . ' is currently out of stock!'], 400);
            }
            return redirect()->back()->with('error', $product->name . ' is currently out of stock!');
        }

        $cart = session()->get('cart', []);
        
        // Calculate new quantity
        $currentQty = isset($cart[$product->id]) ? $cart[$product->id] : 0;
        $newQuantity = $currentQty + $quantity;
        
        // Check if new quantity exceeds available stock
        if ($newQuantity > $product->stock) {
            // If the cart already has the maximum, do not show an error — user already has the available items.
            if ($currentQty >= $product->stock) {
                if ($request->expectsJson()) {
                    return response()->json(['success' => false, 'message' => 'You already have the maximum quantity of ' . $product->name . ' in your cart.'], 200);
                }

                return redirect()->back()->with('success', 'You already have the maximum quantity of ' . $product->name . ' in your cart.');
            }

            // Otherwise, cap the quantity to the available stock and add whatever remains.
            $added = $product->stock - $currentQty;
            $cart[$product->id] = $product->stock;
            session()->put('cart', $cart);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $added . ' × ' . $product->name . ' added to cart (quantity adjusted to available stock).'
                ]);
            }

            return redirect()->back()->with('success', $added . ' × ' . $product->name . ' added to cart (quantity adjusted to available stock).');
        }

        $cart[$product->id] = $newQuantity;

        session()->put('cart', $cart);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true, 
                'message' => $quantity . ' × ' . $product->name . ' added to cart!'
            ]);
        }

        return redirect()->back()->with('success', $quantity . ' × ' . $product->name . ' added to cart!');
    }

    public function removeFromCart(Product $product)
    {
        $cart = session()->get('cart', []);
        
        if (isset($cart[$product->id])) {
            unset($cart[$product->id]);
            session()->put('cart', $cart);
        }

        return redirect()->back()->with('success', $product->name . ' removed from cart!');
    }

    public function updateCart(Request $request, Product $product)
    {
        $cart = session()->get('cart', []);
        $quantity = max(1, (int) $request->quantity);
        
        // If requested quantity exceeds available stock, cap it and notify user.
        if ($quantity > $product->stock) {
            $old = isset($cart[$product->id]) ? $cart[$product->id] : 0;
            $cart[$product->id] = $product->stock;
            session()->put('cart', $cart);

            $message = 'Requested quantity exceeds available stock. Quantity adjusted to ' . $product->stock . '.';
            return redirect()->back()->with('success', $message);
        }

        $cart[$product->id] = $quantity;
        session()->put('cart', $cart);

        return redirect()->back()->with('success', 'Cart updated!');
    }

    public function clearCart()
    {
        session()->forget('cart');
        return redirect()->back()->with('success', 'Cart cleared!');
    }

    public function getProductStock(Product $product)
    {
        return response()->json([
            'stock' => $product->stock,
            'name' => $product->name
        ]);
    }
}
