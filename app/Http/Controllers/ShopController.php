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

        foreach ($cart as $id => $quantity) {
            $product = Product::find($id);
            if ($product) {
                $products[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'subtotal' => $product->price * $quantity
                ];
                $total += $product->price * $quantity;
            }
        }

        return view('shop.cart', compact('products', 'total'));
    }

    public function addToCart(Request $request, Product $product)
    {
        $cart = session()->get('cart', []);
        
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
