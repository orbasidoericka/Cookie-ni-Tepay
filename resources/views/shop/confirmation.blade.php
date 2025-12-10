@extends('layouts.app')

@section('title', 'Order Confirmed - ShopApp')

@section('content')
    <div class="confirmation-container">
        <div class="confirmation-card">
            <div class="success-icon">✓</div>
            <h1>Order Confirmed!</h1>
            <p class="order-number">Order #{{ $order->order_number }}</p>

            <!-- ACID Success Indicators -->
            <div class="acid-success">
                <h3>Transaction Completed Successfully</h3>
                <div class="acid-checks">
                    <div class="acid-check">
                        <span class="check-icon">✓</span>
                        <div>
                            <strong>Atomicity</strong>
                            <p>All operations completed as a single unit</p>
                        </div>
                    </div>
                    <div class="acid-check">
                        <span class="check-icon">✓</span>
                        <div>
                            <strong>Consistency</strong>
                            <p>Stock levels verified and updated correctly</p>
                        </div>
                    </div>
                    <div class="acid-check">
                        <span class="check-icon">✓</span>
                        <div>
                            <strong>Isolation</strong>
                            <p>Transaction processed without interference</p>
                        </div>
                    </div>
                    <div class="acid-check">
                        <span class="check-icon">✓</span>
                        <div>
                            <strong>Durability</strong>
                            <p>Order permanently saved to database</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="order-details">
                <h2>Order Details</h2>
                
                <div class="customer-info">
                    <p><strong>Customer:</strong> {{ $order->customer_name }}</p>
                    <p><strong>Email:</strong> {{ $order->customer_email }}</p>
                    <p><strong>Date:</strong> {{ $order->created_at->format('F j, Y \a\t g:i A') }}</p>
                    <p><strong>Status:</strong> <span class="status-badge status-{{ $order->status }}">{{ ucfirst($order->status) }}</span></p>
                    @if($order->notes)
                        <p><strong>Notes:</strong> {{ $order->notes }}</p>
                    @endif
                </div>

                <div class="order-items">
                    <h3>Items Ordered</h3>
                    <table class="items-table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Qty</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                                <tr>
                                    <td>{{ $item->product_name }}</td>
                                    <td>${{ number_format($item->price, 2) }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>${{ number_format($item->subtotal, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3"><strong>Total</strong></td>
                                <td><strong>${{ number_format($order->total_amount, 2) }}</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <div class="confirmation-actions">
                <a href="{{ route('shop.index') }}" class="btn btn-primary">Continue Shopping</a>
                <a href="{{ route('orders.history') }}" class="btn btn-secondary">View All Orders</a>
            </div>
        </div>
    </div>

    <style>
        .confirmation-container {
            max-width: 800px;
            margin: 0 auto;
        }

        .confirmation-card {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            text-align: center;
        }

        .success-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            font-size: 3rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            animation: scaleIn 0.5s ease;
        }

        @keyframes scaleIn {
            from {
                transform: scale(0);
                opacity: 0;
            }
            to {
                transform: scale(1);
                opacity: 1;
            }
        }

        .confirmation-card h1 {
            color: #28a745;
            margin-bottom: 0.5rem;
        }

        .order-number {
            color: #666;
            font-size: 1.1rem;
            margin-bottom: 2rem;
        }

        .acid-success {
            background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            text-align: left;
        }

        .acid-success h3 {
            color: #2e7d32;
            margin-bottom: 1rem;
            text-align: center;
        }

        .acid-checks {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }

        @media (max-width: 600px) {
            .acid-checks {
                grid-template-columns: 1fr;
            }
        }

        .acid-check {
            display: flex;
            gap: 0.75rem;
            align-items: flex-start;
        }

        .check-icon {
            width: 24px;
            height: 24px;
            background: #28a745;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            flex-shrink: 0;
        }

        .acid-check strong {
            color: #1b5e20;
            display: block;
            font-size: 0.9rem;
        }

        .acid-check p {
            color: #2e7d32;
            font-size: 0.8rem;
            margin: 0;
        }

        .order-details {
            text-align: left;
            margin-top: 2rem;
        }

        .order-details h2 {
            border-bottom: 2px solid #eee;
            padding-bottom: 0.75rem;
            margin-bottom: 1.5rem;
        }

        .customer-info {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
        }

        .customer-info p {
            margin: 0.5rem 0;
        }

        .status-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .status-completed {
            background: #d4edda;
            color: #155724;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-processing {
            background: #cce5ff;
            color: #004085;
        }

        .status-cancelled {
            background: #f8d7da;
            color: #721c24;
        }

        .order-items h3 {
            margin-bottom: 1rem;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
        }

        .items-table th,
        .items-table td {
            padding: 0.75rem;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        .items-table th {
            background: #f8f9fa;
            font-weight: 600;
        }

        .items-table tfoot td {
            background: #f8f9fa;
            font-size: 1.1rem;
        }

        .confirmation-actions {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-top: 2rem;
        }
    </style>
@endsection
