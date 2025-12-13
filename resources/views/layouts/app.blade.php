<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'ShopApp')</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            color: #2c2c2c;
            line-height: 1.7;
            font-size: 16px;
        }

        /* Navigation */
        nav {
            background: linear-gradient(135deg, #BBDCE5 0%, #D9C4B0 100%);
            padding: 1rem 2rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 1.5rem;
            font-weight: bold;
            color: white;
            text-decoration: none;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        .logo:hover {
            opacity: 0.9;
        }

        .nav-links {
            display: flex;
            gap: 2rem;
            align-items: center;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            font-weight: 500;
            transition: opacity 0.3s;
            text-shadow: 1px 1px 3px rgba(0,0,0,0.3);
        }

        .nav-links a:hover {
            opacity: 0.8;
        }

        .cart-link {
            background: rgba(255,255,255,0.2);
            padding: 0.5rem 1rem;
            border-radius: 25px;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .cart-count {
            background: #ff6b6b;
            color: white;
            padding: 0.2rem 0.6rem;
            border-radius: 50%;
            font-size: 0.8rem;
        }

        .auth-buttons {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .btn-logout {
            background: rgba(255,255,255,0.2);
            border-radius: 20px;
            color: white;
            padding: 0.5rem 1rem;
            border: none;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s;
        }

        .btn-logout:hover {
            background: rgba(255,255,255,0.3);
        }

        .user-welcome {
            color: white;
            font-weight: 500;
            text-shadow: 1px 1px 3px rgba(0,0,0,0.3);
        }

        /* Main Container */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }

        /* Alerts */
        .alert {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            animation: slideIn 0.3s ease;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            font-weight: 500;
            text-shadow: 1px 1px 1px rgba(255,255,255,0.5);
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            font-weight: 500;
            text-shadow: 1px 1px 1px rgba(255,255,255,0.5);
        }

        /* Page Title */
        .page-title {
            font-size: 2rem;
            margin-bottom: 2rem;
            color: #333;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
            font-weight: 600;
        }

        /* Product Grid */
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 2rem;
        }

        .product-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }

        .product-image {
            width: 100%;
            height: 200px;
            background: linear-gradient(135deg, #ECEEDF 0%, #BBDCE5 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #D9C4B0;
            font-size: 3rem;
            overflow: hidden;
        }

        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .product-info {
            padding: 1.5rem;
        }

        .product-name {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #333;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
        }

        .product-description {
            color: #555;
            font-size: 0.95rem;
            margin-bottom: 1rem;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            line-height: 1.5;
        }

        .product-price {
            font-size: 1.5rem;
            font-weight: bold;
            color: #D9C4B0;
            margin-bottom: 1rem;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.15);
        }

        .product-stock {
            font-size: 0.85rem;
            color: #28a745;
            margin-bottom: 1rem;
        }

        .product-stock.low {
            color: #dc3545;
        }

        /* Buttons */
        .btn {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.3s;
            text-align: center;
        }

        .btn-primary {
            background: linear-gradient(135deg, #BBDCE5 0%, #CFAB8D 100%);
            color: #5a4a3a;
            width: 100%;
            font-weight: 600;
            text-shadow: 1px 1px 2px rgba(255,255,255,0.5);
        }

        .btn-primary:hover {
            opacity: 0.9;
            transform: scale(1.02);
        }

        /* Product Action Buttons Hover Effect */
        .product-actions {
            display: flex;
            gap: 0.5rem;
            opacity: 0;
            transform: translateY(10px);
            transition: all 0.3s ease;
            pointer-events: none;
        }

        .product-card:hover .product-actions {
            opacity: 1;
            transform: translateY(0);
            pointer-events: auto;
        }

        .btn-buy-now {
            flex: 1;
            background: linear-gradient(135deg, #BBDCE5 0%, #CFAB8D 100%);
            color: #5a4a3a;
            font-weight: 600;
            text-shadow: 1px 1px 2px rgba(255,255,255,0.5);
        }

        .btn-add-cart {
            background: rgba(187, 220, 229, 0.3);
            color: #5a4a3a;
            padding: 0.5rem 0.75rem;
            font-size: 1.2rem;
            min-width: 45px;
            border: 2px solid rgba(187, 220, 229, 0.5);
        }

        .btn-add-cart:hover {
            background: rgba(187, 220, 229, 0.5);
            border-color: rgba(187, 220, 229, 0.8);
        }

        .btn-sm {
            padding: 0.5rem 0.75rem;
            font-size: 0.875rem;
        }

        .btn-danger {
            background: #dc3545;
            color: white;
        }

        .btn-danger:hover {
            background: #c82333;
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-block {
            width: 100%;
            margin-top: 1rem;
        }

        .btn-secondary:hover {
            background: #5a6268;
        }

        .btn-sm {
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
        }

        /* Cart Page Styles */
        .cart-container {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .cart-item {
            display: flex;
            align-items: center;
            padding: 1.5rem 0;
            border-bottom: 1px solid #eee;
            gap: 1.5rem;
        }

        .cart-item:last-child {
            border-bottom: none;
        }

        .cart-item-image {
            width: 80px;
            height: 80px;
            border-radius: 8px;
            background: linear-gradient(135deg, #ECEEDF 0%, #BBDCE5 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #D9C4B0;
            font-size: 1.5rem;
            flex-shrink: 0;
        }

        .cart-item-info {
            flex: 1;
        }

        .cart-item-name {
            font-weight: 600;
            margin-bottom: 0.25rem;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
            font-size: 1.1rem;
            color: #2c2c2c;
        }

        .cart-item-price {
            color: #C19A6B;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
            font-weight: 600;
            font-weight: 500;
        }

        .cart-item-quantity {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .quantity-input {
            width: 60px;
            padding: 0.5rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            text-align: center;
        }

        .quantity-display {
            font-size: 1.1rem;
            font-weight: 600;
            color: #333;
            padding: 0.5rem 1rem;
            background: #f8f9fa;
            border-radius: 8px;
            display: inline-block;
        }

        .cart-item-subtotal {
            font-weight: bold;
            font-size: 1.1rem;
            min-width: 100px;
            text-align: right;
        }

        .cart-item-actions {
            display: flex;
            gap: 0.5rem;
        }

        .cart-summary {
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 2px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .cart-total {
            font-size: 1.5rem;
            font-weight: bold;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
        }

        .cart-total span {
            color: #D9C4B0;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.15);
        }

        .cart-actions {
            display: flex;
            gap: 1rem;
        }

        .empty-cart {
            text-align: center;
            padding: 3rem;
            color: #666;
        }

        .empty-cart-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
        }

        /* Footer */
        footer {
            text-align: center;
            padding: 2rem;
            color: #666;
            margin-top: 3rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .nav-container {
                flex-direction: column;
                gap: 1rem;
            }

            .cart-item {
                flex-direction: column;
                text-align: center;
            }

            .cart-item-subtotal {
                text-align: center;
            }

            .cart-summary {
                flex-direction: column;
                gap: 1rem;
            }
        }

        /* Stock Status Badges */
        .stock-badge {
            display: inline-block;
            padding: 0.2rem 0.5rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-left: 0.5rem;
        }

        .out-of-stock {
            background: #f8d7da;
            color: #721c24;
        }

        .low-stock {
            background: #fff3cd;
            color: #856404;
        }

        .in-stock {
            background: #d4edda;
            color: #155724;
        }

        .stock-warning {
            border-left: 3px solid #ffc107 !important;
            background: #fff9e6 !important;
        }

        .cart-item.stock-warning .cart-item-info {
            position: relative;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            animation: fadeIn 0.3s;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .modal-content {
            background-color: white;
            margin: 10% auto;
            padding: 2rem;
            border-radius: 12px;
            width: 90%;
            max-width: 400px;
            position: relative;
            animation: slideDown 0.3s;
            box-shadow: 0 10px 40px rgba(0,0,0,0.3);
        }

        @keyframes slideDown {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .modal-content h2 {
            margin-bottom: 0.5rem;
            color: #333;
            font-size: 1.5rem;
        }

        .modal-price {
            font-size: 1.3rem;
            color: #D9C4B0;
            font-weight: bold;
            margin: 0.5rem 0;
        }

        .modal-stock {
            color: #666;
            margin-bottom: 1.5rem;
        }

        .close {
            position: absolute;
            right: 1.5rem;
            top: 1rem;
            font-size: 2rem;
            font-weight: bold;
            color: #aaa;
            cursor: pointer;
            line-height: 1;
        }

        .close:hover {
            color: #000;
        }

        .quantity-selector {
            margin: 1.5rem 0;
        }

        .quantity-selector label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #333;
        }

        .quantity-controls {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1rem;
        }

        .quantity-controls input {
            width: 80px;
            text-align: center;
            font-size: 1.2rem;
            padding: 0.5rem;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-weight: bold;
        }

        .qty-btn {
            width: 40px;
            height: 40px;
            border: none;
            background: linear-gradient(135deg, #BBDCE5 0%, #CFAB8D 100%);
            color: #5a4a3a;
            font-size: 1.5rem;
            border-radius: 50%;
            cursor: pointer;
            font-weight: bold;
            transition: all 0.3s;
        }

        .qty-btn:hover {
            transform: scale(1.1);
            opacity: 0.9;
        }

        .qty-btn:active {
            transform: scale(0.95);
        }
    </style>
</head>
<body>
    <nav>
        <div class="nav-container">
            <a href="{{ route('shop.index') }}" class="logo">🧁 Buttercloud Bakery</a>
            <div class="nav-links">
                <a href="{{ route('shop.index') }}">Products</a>
                <a href="{{ route('orders.history') }}">Orders</a>
                <a href="{{ route('shop.cart') }}" class="cart-link">
                    🛒 Cart
                    @php
                        $cartCount = array_sum(session()->get('cart', []));
                    @endphp
                    @if($cartCount > 0)
                        <span class="cart-count">{{ $cartCount }}</span>
                    @endif
                </a>
                @auth
                    <span class="user-welcome">Hi, {{ Auth::user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn-logout">Logout</button>
                    </form>
                @endauth
                @guest
                    <a href="{{ route('login') }}">Login</a>
                    <a href="{{ route('register') }}">Register</a>
                @endguest
            </div>
        </div>
    </nav>

    <div class="container">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </div>

    <footer>
        <p>&copy; {{ date('Y') }} Buttercloud Bakery. Freshly baked with love.</p>
    </footer>
</body>
</html>
