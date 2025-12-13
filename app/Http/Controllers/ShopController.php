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
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Only ' . $product->stock . ' ' . $product->name . ' available in stock!'], 400);
            }
            return redirect()->back()->with('error', 'Only ' . $product->stock . ' ' . $product->name . ' available in stock!');
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
        
        // Check if requested quantity exceeds available stock
        if ($quantity > $product->stock) {
            return redirect()->back()->with('error', 'Only ' . $product->stock . ' ' . $product->name . ' available in stock!');
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
}
