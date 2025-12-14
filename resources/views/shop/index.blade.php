@extends('layouts.app')

@section('title', 'Fresh Pastries - ButterCloud Bakery')

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
                            <div class="product-actions">
                                <button type="button" class="btn btn-primary btn-buy-now" onclick="openBuyNowModal({{ $product->id }}, '{{ $product->name }}', {{ $product->stock }}, {{ $product->price }})">Buy Now</button>
                                <button type="button" class="btn btn-sm btn-add-cart" onclick="openAddToCartModal({{ $product->id }}, '{{ $product->name }}', {{ $product->stock }}, {{ $product->price }})">🛒</button>
                            </div>
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
            <span class="close" onclick="closeCartModal()">&times;</span>
            <h2 id="cartModalProductName">Product Name</h2>
            <p class="modal-price">₱<span id="cartModalProductPrice">0.00</span></p>
            <p class="modal-stock">Available: <span id="cartModalProductStock">0</span></p>
            
            <form id="addToCartForm" method="POST">
                @csrf
                <div class="quantity-selector">
                    <label for="cartQuantity">Quantity:</label>
                    <div class="quantity-controls">
                        <button type="button" class="qty-btn" onclick="decrementCartQty()">−</button>
                        <input type="number" id="cartQuantity" name="quantity" value="1" min="1" max="1" readonly>
                        <button type="button" class="qty-btn" onclick="incrementCartQty()">+</button>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Add to Cart</button>
            </form>
        </div>
    </div>

    <!-- Buy Now Modal -->
    <div id="buyNowModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeBuyNowModal()">&times;</span>
            <h2 id="buyNowModalProductName">Product Name</h2>
            <p class="modal-price">₱<span id="buyNowModalProductPrice">0.00</span></p>
            <p class="modal-stock">Available: <span id="buyNowModalProductStock">0</span></p>
            
            <form id="buyNowForm" method="POST">
                @csrf
                <div class="quantity-selector">
                    <label for="buyNowQuantity">Quantity:</label>
                    <div class="quantity-controls">
                        <button type="button" class="qty-btn" onclick="decrementBuyNowQty()">−</button>
                        <input type="number" id="buyNowQuantity" name="quantity" value="1" min="1" max="1" readonly>
                        <button type="button" class="qty-btn" onclick="incrementBuyNowQty()">+</button>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Buy Now</button>
            </form>
        </div>
    </div>

    <script>
        let cartMaxStock = 1;
        let buyNowMaxStock = 1;

        // Add to Cart Modal Functions
        function openAddToCartModal(productId, productName, stock, price) {
            document.getElementById('cartModal').style.display = 'block';
            document.getElementById('cartModalProductName').textContent = productName;
            document.getElementById('cartModalProductPrice').textContent = parseFloat(price).toFixed(2);
            document.getElementById('cartModalProductStock').textContent = stock;
            document.getElementById('cartQuantity').value = 1;
            document.getElementById('cartQuantity').max = stock;
            document.getElementById('addToCartForm').action = '/cart/add/' + productId;
            cartMaxStock = stock;
        }

        function closeCartModal() {
            document.getElementById('cartModal').style.display = 'none';
        }

        function incrementCartQty() {
            const qtyInput = document.getElementById('cartQuantity');
            let currentValue = parseInt(qtyInput.value);
            if (currentValue < cartMaxStock) {
                qtyInput.value = currentValue + 1;
            }
        }

        function decrementCartQty() {
            const qtyInput = document.getElementById('cartQuantity');
            let currentValue = parseInt(qtyInput.value);
            if (currentValue > 1) {
                qtyInput.value = currentValue - 1;
            }
        }

        // Buy Now Modal Functions
        function openBuyNowModal(productId, productName, stock, price) {
            // First, check current stock via AJAX
            fetch('/product/' + productId + '/stock')
                .then(response => response.json())
                .then(stockData => {
                    const currentStock = stockData.stock || stock;
                    
                    if (currentStock <= 0) {
                        alert(productName + ' is currently out of stock!');
                        return;
                    }

                    document.getElementById('buyNowModal').style.display = 'block';
                    document.getElementById('buyNowModalProductName').textContent = productName;
                    document.getElementById('buyNowModalProductPrice').textContent = parseFloat(price).toFixed(2);
                    document.getElementById('buyNowModalProductStock').textContent = currentStock;
                    document.getElementById('buyNowQuantity').value = 1;
                    document.getElementById('buyNowQuantity').max = currentStock;
                    
                    // Buy Now form submits to add to cart then redirects to checkout
                    document.getElementById('buyNowForm').onsubmit = function(e) {
                        e.preventDefault();
                        const quantity = document.getElementById('buyNowQuantity').value;
                        const formData = new FormData();
                        formData.append('quantity', quantity);
                        formData.append('_token', '{{ csrf_token() }}');
                        
                        // Add to cart via fetch
                        fetch('/cart/add/' + productId, {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: formData
                        })
                        .then(response => {
                            return response.json().then(data => {
                                if (!response.ok) {
                                    throw new Error(data.error || 'Server error occurred');
                                }
                                return data;
                            });
                        })
                        .then(data => {
                            if (data.success) {
                                // Close modal and redirect to checkout page
                                closeBuyNowModal();
                                window.location.href = '/checkout';
                            } else {
                                // If server returned an informational message (e.g., already at max quantity),
                                // close modal and send user to cart instead of showing a blocking alert.
                                if (data.message) {
                                    closeBuyNowModal();
                                    window.location.href = '/cart';
                                } else {
                                    alert(data.error || 'An error occurred. Please try again.');
                                }
                            }
                        })
                        .catch(error => {
                            console.error('Buy Now Error:', error);
                            alert(error.message || 'An error occurred. Please try again.');
                        });
                    };
                    
                    buyNowMaxStock = currentStock;
                })
                .catch(error => {
                    console.error('Stock check error:', error);
                    // Fallback to original behavior
                    document.getElementById('buyNowModal').style.display = 'block';
                    document.getElementById('buyNowModalProductName').textContent = productName;
                    document.getElementById('buyNowModalProductPrice').textContent = parseFloat(price).toFixed(2);
                    document.getElementById('buyNowModalProductStock').textContent = stock;
                    document.getElementById('buyNowQuantity').value = 1;
                    document.getElementById('buyNowQuantity').max = stock;
                    buyNowMaxStock = stock;
                });
        }

        function closeBuyNowModal() {
            document.getElementById('buyNowModal').style.display = 'none';
        }

        function incrementBuyNowQty() {
            const qtyInput = document.getElementById('buyNowQuantity');
            let currentValue = parseInt(qtyInput.value);
            if (currentValue < buyNowMaxStock) {
                qtyInput.value = currentValue + 1;
            }
        }

        function decrementBuyNowQty() {
            const qtyInput = document.getElementById('buyNowQuantity');
            let currentValue = parseInt(qtyInput.value);
            if (currentValue > 1) {
                qtyInput.value = currentValue - 1;
            }
        }

        // Close modals when clicking outside
        window.onclick = function(event) {
            const cartModal = document.getElementById('cartModal');
            const buyNowModal = document.getElementById('buyNowModal');
            if (event.target == cartModal) {
                closeCartModal();
            }
            if (event.target == buyNowModal) {
                closeBuyNowModal();
            }
        }
    </script>
@endsection
