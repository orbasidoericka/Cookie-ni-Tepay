@extends('layouts.app')

@section('title', 'Order History - ShopApp')

@section('content')
    <h1 class="page-title">Order History</h1>

    <div class="orders-container">
        @if($orders->isEmpty())
            <div class="empty-orders">
                <div class="empty-icon">📋</div>
                <h2>No orders yet</h2>
                <p>Your order history will appear here once you make a purchase.</p>
                <a href="{{ route('shop.index') }}" class="btn btn-primary" style="width: auto;">Start Shopping</a>
            </div>
        @else
            <div class="acid-notice">
                <strong>🔒 Data Integrity Guaranteed:</strong> 
                All orders below were processed using ACID-compliant transactions, 
                ensuring complete data integrity and consistency.
            </div>

            @foreach($orders as $order)
                <div class="order-card">
                    <div class="order-header">
                        <div class="order-info">
                            <h3>{{ $order->order_number }}</h3>
                            <span class="order-date">{{ $order->created_at->format('M j, Y \a\t g:i A') }}</span>
                        </div>
                        <div class="order-status">
                            <span class="status-badge status-{{ $order->status }}">{{ ucfirst($order->status) }}</span>
                        </div>
                    </div>
                    
                    <div class="order-body">
                        <div class="order-customer">
                            <p><strong>Customer:</strong> {{ $order->customer_name }}</p>
                            <p><strong>Email:</strong> {{ $order->customer_email }}</p>
                        </div>
                        
                        <div class="order-items-summary">
                            <strong>Items:</strong>
                            <ul>
                                @foreach($order->items as $item)
                                    <li>{{ $item->product_name }} × {{ $item->quantity }} - ${{ number_format($item->subtotal, 2) }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    
                    <div class="order-footer">
                        <div class="order-total">
                            <strong>Total: ${{ number_format($order->total_amount, 2) }}</strong>
                        </div>
                        <a href="{{ route('order.confirmation', $order) }}" class="btn btn-secondary btn-sm">View Details</a>
                    </div>
                </div>
            @endforeach

            <div class="pagination-container">
                {{ $orders->links() }}
            </div>
        @endif
    </div>

    <style>
        .orders-container {
            max-width: 900px;
            margin: 0 auto;
        }

        .empty-orders {
            background: white;
            border-radius: 12px;
            padding: 3rem;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .empty-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
        }

        .empty-orders h2 {
            color: #666;
            margin-bottom: 0.5rem;
        }

        .empty-orders p {
            color: #888;
            margin-bottom: 1.5rem;
        }

        .acid-notice {
            background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);
            border: 1px solid #a5d6a7;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            color: #1b5e20;
        }

        .order-card {
            background: white;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 1.5rem;
            background: #f8f9fa;
            border-bottom: 1px solid #eee;
        }

        .order-info h3 {
            margin: 0;
            color: #667eea;
            font-size: 1rem;
        }

        .order-date {
            color: #666;
            font-size: 0.85rem;
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

        .order-body {
            padding: 1.5rem;
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 1.5rem;
        }

        @media (max-width: 600px) {
            .order-body {
                grid-template-columns: 1fr;
            }
        }

        .order-customer p {
            margin: 0.25rem 0;
            font-size: 0.9rem;
        }

        .order-items-summary ul {
            margin: 0.5rem 0 0 0;
            padding-left: 1.25rem;
            font-size: 0.9rem;
            color: #555;
        }

        .order-items-summary li {
            margin-bottom: 0.25rem;
        }

        .order-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 1.5rem;
            background: #f8f9fa;
            border-top: 1px solid #eee;
        }

        .order-total {
            font-size: 1.1rem;
            color: #667eea;
        }

        .pagination-container {
            display: flex;
            justify-content: center;
            margin-top: 2rem;
        }
    </style>
@endsection
