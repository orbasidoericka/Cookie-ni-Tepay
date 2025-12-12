@extends('layouts.app')

@section('title', 'Checkout - Buttercloud Bakery')

@section('content')
    <h1 class="page-title">Checkout</h1>

    <div class="checkout-container">
        <div class="checkout-grid">
            <!-- Order Summary -->
            <div class="order-summary">
                <h2>Order Summary</h2>
                <div class="summary-items">
                    @foreach($products as $item)
                        <div class="summary-item">
                            <div class="item-details">
                                <span class="item-name">{{ $item['product']->name }}</span>
                                <span class="item-qty">x{{ $item['quantity'] }}</span>
                            </div>
                            <span class="item-price">₱{{ number_format($item['subtotal'], 2) }}</span>
                        </div>
                    @endforeach
                </div>
                <div class="summary-total">
                    <span>Total</span>
                    <span class="total-price">₱{{ number_format($total, 2) }}</span>
                </div>
            </div>

            <!-- Checkout Form -->
            <div class="checkout-form-container">
                <h2>Customer Information</h2>

                <form action="{{ route('checkout.process') }}" method="POST" class="checkout-form">
                    @csrf
                    
                    <div class="form-group">
                        <label for="customer_name">Full Name *</label>
                        <input type="text" 
                               id="customer_name" 
                               name="customer_name" 
                               value="{{ old('customer_name') }}" 
                               required
                               placeholder="Enter your full name">
                        @error('customer_name')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="contact_number">Contact Number *</label>
                        <input type="tel" 
                               id="contact_number" 
                               name="contact_number" 
                               value="{{ old('contact_number') }}" 
                               required
                               placeholder="09XXXXXXXXX"
                               maxlength="11">
                        @error('contact_number')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="notes">Order Notes (Optional)</label>
                        <textarea id="notes" 
                                  name="notes" 
                                  rows="3"
                                  placeholder="Any special instructions...">{{ old('notes') }}</textarea>
                        @error('notes')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="checkout-actions">
                        <a href="{{ route('shop.cart') }}" class="btn btn-secondary">← Back to Cart</a>
                        <button type="submit" class="btn btn-primary btn-checkout">
                            🔒 Place Order - ₱{{ number_format($total, 2) }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .checkout-container {
            max-width: 1000px;
            margin: 0 auto;
        }

        .checkout-grid {
            display: grid;
            grid-template-columns: 1fr 1.5fr;
            gap: 2rem;
        }

        @media (max-width: 768px) {
            .checkout-grid {
                grid-template-columns: 1fr;
            }
        }

        .order-summary, .checkout-form-container {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .order-summary h2, .checkout-form-container h2 {
            margin-bottom: 1.5rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid #eee;
        }

        .summary-items {
            margin-bottom: 1.5rem;
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            padding: 0.75rem 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .item-details {
            display: flex;
            gap: 0.5rem;
        }

        .item-name {
            font-weight: 500;
        }

        .item-qty {
            color: #666;
        }

        .item-price {
            font-weight: 500;
            color: #D9C4B0;
        }

        .summary-total {
            display: flex;
            justify-content: space-between;
            padding-top: 1rem;
            font-size: 1.25rem;
            font-weight: bold;
        }

        .total-price {
            color: #D9C4B0;
        }

        .acid-info {
            background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);
            border: 1px solid #a5d6a7;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }

        .acid-info h3 {
            color: #2e7d32;
            margin-bottom: 0.75rem;
            font-size: 1rem;
        }

        .acid-info ul {
            margin: 0;
            padding-left: 1.25rem;
            font-size: 0.85rem;
            color: #1b5e20;
        }

        .acid-info li {
            margin-bottom: 0.25rem;
        }

        .checkout-form {
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .form-group label {
            font-weight: 500;
            color: #333;
        }

        .form-group input,
        .form-group textarea {
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s, box-shadow 0.3s;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .error-message {
            color: #dc3545;
            font-size: 0.85rem;
        }

        .checkout-actions {
            display: flex;
            justify-content: space-between;
            gap: 1rem;
            margin-top: 1rem;
        }

        .btn-checkout {
            flex: 1;
            padding: 1rem 2rem;
            font-size: 1.1rem;
            background: linear-gradient(135deg, #BBDCE5 0%, #CFAB8D 100%);
        }
    </style>
@endsection
