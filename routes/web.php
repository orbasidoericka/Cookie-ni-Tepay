<?php

use App\Http\Controllers\ShopController;
use App\Http\Controllers\CheckoutController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ShopController::class, 'index'])->name('shop.index');
Route::get('/cart', [ShopController::class, 'cart'])->name('shop.cart');
Route::post('/cart/add/{product}', [ShopController::class, 'addToCart'])->name('cart.add');
Route::delete('/cart/remove/{product}', [ShopController::class, 'removeFromCart'])->name('cart.remove');
Route::patch('/cart/update/{product}', [ShopController::class, 'updateCart'])->name('cart.update');
Route::delete('/cart/clear', [ShopController::class, 'clearCart'])->name('cart.clear');

// Checkout routes (ACID-compliant transactions)
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');
Route::get('/order/{order}/confirmation', [CheckoutController::class, 'confirmation'])->name('order.confirmation');
Route::get('/orders', [CheckoutController::class, 'history'])->name('orders.history');

// API endpoint for testing concurrent transactions
Route::post('/api/simulate-concurrency', [CheckoutController::class, 'simulateConcurrency'])->name('api.simulate');
