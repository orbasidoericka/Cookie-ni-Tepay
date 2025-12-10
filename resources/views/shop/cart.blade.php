@extends('layouts.app')

@section('title', 'Your Order - Buttercloud Bakery')

@section('content')
    <h1 class="page-title">Shopping Cart</h1>

    <div class="cart-container">
        @if(empty($products))
            <div class="empty-cart">
                <div class="empty-cart-icon">🛒</div>
                <h2>Your cart is empty</h2>
                <p>Add some products to get started!</p>
                <br>
                <a href="{{ route('shop.index') }}" class="btn btn-primary" style="width: auto;">Browse Products</a>
            </div>
        @else
            @if($hasStockIssues)
                <div class="alert alert-error" style="margin-bottom: 1rem;">
                    ⚠️ Some items in your cart exceed available stock. Quantities have been adjusted.
                </div>
            @endif

            @foreach($products as $item)
                <div class="cart-item {{ $item['has_stock_issue'] ? 'stock-warning' : '' }}">
                    <div class="cart-item-image">
                        @if($item['product']->image)
                            <img src="{{ $item['product']->image }}" alt="{{ $item['product']->name }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 8px;">
                        @else
                            📦
                        @endif
                    </div>
                    <div class="cart-item-info">
                        <div class="cart-item-name">
                            {{ $item['product']->name }}
                            @if($item['product']->stock <= 0)
                                <span class="stock-badge out-of-stock">Out of Stock</span>
                            @elseif($item['product']->stock < 5)
                                <span class="stock-badge low-stock">Only {{ $item['product']->stock }} left</span>
                            @endif
                        </div>
                        <div class="cart-item-price">₱{{ number_format($item['product']->price, 2) }} each</div>
                        @if($item['has_stock_issue'])
                            <div style="color: #dc3545; font-size: 0.85rem; margin-top: 0.25rem;">
                                ⚠️ Only {{ $item['product']->stock }} available
                            </div>
                        @endif
                    </div>
                    <div class="cart-item-quantity">
                        @if($item['product']->stock > 0)
                            <form action="{{ route('cart.update', $item['product']) }}" method="POST" style="display: flex; align-items: center; gap: 0.5rem;">
                                @csrf
                                @method('PATCH')
                                <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" max="{{ $item['product']->stock }}" class="quantity-input">
                                <button type="submit" class="btn btn-secondary btn-sm">Update</button>
                            </form>
                        @else
                            <span style="color: #dc3545; font-weight: 500;">Unavailable</span>
                        @endif
                    </div>
                    <div class="cart-item-subtotal">
                        ₱{{ number_format($item['subtotal'], 2) }}
                    </div>
                    <div class="cart-item-actions">
                        <form action="{{ route('cart.remove', $item['product']) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Remove</button>
                        </form>
                    </div>
                </div>
            @endforeach

            <div class="cart-summary">
                <div class="cart-total">
                    Total: <span>₱{{ number_format($total, 2) }}</span>
                </div>
                <div class="cart-actions">
                    <form action="{{ route('cart.clear') }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-secondary">Clear Cart</button>
                    </form>
                    <a href="{{ route('checkout.index') }}" class="btn btn-primary">Proceed to Checkout</a>
                </div>
            </div>
        @endif
    </div>
@endsection
