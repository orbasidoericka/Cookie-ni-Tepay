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

	// API for stock checking
	Route::get('/product/{product}/stock', [ShopController::class, 'getProductStock'])->name('product.stock');

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
		// Debug route (only enabled in APP_DEBUG=true) - shows auth & session status for troubleshooting
		if (config('app.debug')) {
			Route::get('/debug/status', function () {
				return response()->json([
					'app_key_set' => (bool) config('app.key'),
					'session_driver' => config('session.driver'),
					'session_cookie' => env('SESSION_COOKIE', null),
					'session_secure' => config('session.secure'),
					'auth_check' => Auth::check(),
					'auth_user_id' => Auth::id(),
					'cookies' => request()->cookies->all(),
				], 200);
			});
		}
});
