<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index()
    {
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
        // Check if product has stock
        if ($product->stock <= 0) {
            return redirect()->back()->with('error', $product->name . ' is currently out of stock!');
        }

        $cart = session()->get('cart', []);
        
        // Calculate new quantity
        $newQuantity = isset($cart[$product->id]) ? $cart[$product->id] + 1 : 1;
        
        // Check if new quantity exceeds available stock
        if ($newQuantity > $product->stock) {
            return redirect()->back()->with('error', 'Only ' . $product->stock . ' ' . $product->name . ' available in stock!');
        }
        
        if (isset($cart[$product->id])) {
            $cart[$product->id]++;
        } else {
            $cart[$product->id] = 1;
        }

        session()->put('cart', $cart);

        return redirect()->back()->with('success', $product->name . ' added to cart!');
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
