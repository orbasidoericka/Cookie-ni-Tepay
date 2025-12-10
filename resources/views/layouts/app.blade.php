<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
            color: #333;
            line-height: 1.6;
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
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        /* Page Title */
        .page-title {
            font-size: 2rem;
            margin-bottom: 2rem;
            color: #333;
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
            object-fit: cover;
            background: linear-gradient(135deg, #ECEEDF 0%, #BBDCE5 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #D9C4B0;
            font-size: 3rem;
        }

        .product-info {
            padding: 1.5rem;
        }

        .product-name {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #333;
        }

        .product-description {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 1rem;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .product-price {
            font-size: 1.5rem;
            font-weight: bold;
            color: #D9C4B0;
            margin-bottom: 1rem;
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
        }

        .btn-primary:hover {
            opacity: 0.9;
            transform: scale(1.02);
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
        }

        .cart-item-price {
            color: #D9C4B0;
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
        }

        .cart-total span {
            color: #D9C4B0;
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
