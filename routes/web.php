<?php

use App\Http\Controllers\ShopController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\SecurityHeaders;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', SecurityHeaders::class])->group(function () {
	Route::get('/', [ShopController::class, 'index'])->name('shop.index');
	Route::get('/cart', [ShopController::class, 'cart'])->name('shop.cart');
	Route::post('/cart/add/{product}', [ShopController::class, 'addToCart'])->name('cart.add');
	// Safeguard: handle accidental GETs to add-to-cart to avoid 405 errors
	Route::get('/cart/add/{product}', function ($product) {
		// Redirect back rather than throwing method not allowed
		return redirect()->back()->with('error', 'Please use the Add to Cart button to add items to your cart.');
	});
	Route::delete('/cart/remove/{product}', [ShopController::class, 'removeFromCart'])->name('cart.remove');
	Route::patch('/cart/update/{product}', [ShopController::class, 'updateCart'])->name('cart.update');
	Route::delete('/cart/clear', [ShopController::class, 'clearCart'])->name('cart.clear');

	// Checkout routes (ACID-compliant transactions) — require authentication
	Route::middleware('auth')->group(function () {
		Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
		Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');
		Route::get('/order/{order}/confirmation', [CheckoutController::class, 'confirmation'])->name('order.confirmation');
		Route::get('/orders', [CheckoutController::class, 'history'])->name('orders.history');
	});

	// Authentication
	Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
	Route::post('/login', [AuthController::class, 'login']);
	Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
	Route::post('/register', [AuthController::class, 'register']);
	Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

	// API endpoint for testing concurrent transactions
	Route::post('/api/simulate-concurrency', [CheckoutController::class, 'simulateConcurrency'])->name('api.simulate');
});
