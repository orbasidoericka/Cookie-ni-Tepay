@extends('layouts.app')

@section('title', 'Fresh Pastries - Buttercloud Bakery')

@section('content')
    <h1 class="page-title">Fresh Baked Pastries</h1>

    @if($products->isEmpty())
        <div class="empty-cart">
            <div class="empty-cart-icon">📦</div>
            <h2>No products available</h2>
            <p>Check back later for new items!</p>
        </div>
    @else
        <div class="products-grid">
            @foreach($products as $product)
                <div class="product-card">
                    <div class="product-image">
                        @if($product->image)
                            <img src="{{ asset('images/products/' . $product->image) }}" alt="{{ $product->name }}">
                        @else
                            <img src="{{ asset('images/products/placeholder.jpg') }}" alt="{{ $product->name }}">
                        @endif
                    </div>
                    <div class="product-info">
                        <h3 class="product-name">{{ $product->name }}</h3>
                        <p class="product-description">{{ $product->description }}</p>
                        <div class="product-price">₱{{ number_format($product->price, 2) }}</div>
                        <div class="product-stock {{ $product->stock < 5 ? 'low' : '' }}">
                            {{ $product->stock > 0 ? $product->stock . ' in stock' : 'Out of stock' }}
                        </div>
                        @if($product->stock > 0)
                            <form action="{{ route('cart.add', $product) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-primary">Add to Cart</button>
                            </form>
                        @else
                            <button class="btn btn-secondary" disabled>Out of Stock</button>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endsection
