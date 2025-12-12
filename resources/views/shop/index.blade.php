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
                            <button type="button" class="btn btn-primary" onclick="openModal({{ $product->id }}, '{{ $product->name }}', {{ $product->stock }}, {{ $product->price }})">Add to Cart</button>
                        @else
                            <button class="btn btn-secondary" disabled>Out of Stock</button>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <!-- Add to Cart Modal -->
    <div id="cartModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2 id="modalProductName">Product Name</h2>
            <p class="modal-price">₱<span id="modalProductPrice">0.00</span></p>
            <p class="modal-stock">Available: <span id="modalProductStock">0</span></p>
            
            <form id="addToCartForm" method="POST">
                @csrf
                <div class="quantity-selector">
                    <label for="quantity">Quantity:</label>
                    <div class="quantity-controls">
                        <button type="button" class="qty-btn" onclick="decrementQty()">−</button>
                        <input type="number" id="quantity" name="quantity" value="1" min="1" max="1" readonly>
                        <button type="button" class="qty-btn" onclick="incrementQty()">+</button>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Add to Cart</button>
            </form>
        </div>
    </div>

    <script>
        let maxStock = 1;

        function openModal(productId, productName, stock, price) {
            document.getElementById('cartModal').style.display = 'block';
            document.getElementById('modalProductName').textContent = productName;
            document.getElementById('modalProductPrice').textContent = parseFloat(price).toFixed(2);
            document.getElementById('modalProductStock').textContent = stock;
            document.getElementById('quantity').value = 1;
            document.getElementById('quantity').max = stock;
            document.getElementById('addToCartForm').action = '/cart/add/' + productId;
            maxStock = stock;
        }

        function closeModal() {
            document.getElementById('cartModal').style.display = 'none';
        }

        function incrementQty() {
            const qtyInput = document.getElementById('quantity');
            let currentValue = parseInt(qtyInput.value);
            if (currentValue < maxStock) {
                qtyInput.value = currentValue + 1;
            }
        }

        function decrementQty() {
            const qtyInput = document.getElementById('quantity');
            let currentValue = parseInt(qtyInput.value);
            if (currentValue > 1) {
                qtyInput.value = currentValue - 1;
            }
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('cartModal');
            if (event.target == modal) {
                closeModal();
            }
        }
    </script>
@endsection
