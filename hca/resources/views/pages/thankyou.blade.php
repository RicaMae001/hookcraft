<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hookcraft Avenue - Thank You</title>
    <link rel="icon" href="{{ asset('asset/images/logo.jpg') }}" type="image/png">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('asset/stylesthankyou.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/stylesnav.css') }}">
    <style>
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background: white;
            }
            .card {
                box-shadow: none !important;
                border: 1px solid #dee2e6;
            }
        }
        
        .order-details {
            margin-top: 2rem;
        }
        
        .detail-section {
            margin-bottom: 2rem;
            padding: 1.5rem;
            background: #f8f9fa;
            border-radius: 10px;
        }
        
        .detail-section h5 {
            color: #0d6efd;
            margin-bottom: 1rem;
            font-weight: 600;
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem 0;
            border-bottom: 1px solid #e9ecef;
        }
        
        .info-row:last-child {
            border-bottom: none;
        }
        
        .info-label {
            font-weight: 600;
            color: #6c757d;
        }
        
        .info-value {
            color: #212529;
        }
        
        .product-item {
            display: flex;
            align-items: center;
            padding: 1rem;
            background: white;
            border-radius: 8px;
            margin-bottom: 1rem;
            border: 1px solid #e9ecef;
        }
        
        .product-image {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 5px;
            margin-right: 1rem;
        }
        
        .product-details {
            flex: 1;
        }
        
        .product-name {
            font-weight: 600;
            margin-bottom: 0.25rem;
        }
        
        .product-meta {
            font-size: 0.9rem;
            color: #6c757d;
        }
        
        .product-price {
            font-weight: 700;
            color: #0d6efd;
        }
        
        .total-section {
            background: #e7f1ff;
            padding: 1.5rem;
            border-radius: 10px;
            margin-top: 1.5rem;
        }
        
        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem 0;
        }
        
        .total-amount {
            font-size: 1.5rem;
            font-weight: 700;
            color: #0d6efd;
            border-top: 2px solid #0d6efd;
            padding-top: 1rem;
            margin-top: 1rem;
        }
        
        .status-badge {
            padding: 0.35rem 0.75rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        
        .badge-pending {
            background: #fff3cd;
            color: #856404;
        }
        
        .badge-paid {
            background: #d4edda;
            color: #155724;
        }
    </style>
</head>
<body>

@include('components.login_modal')
@include('components.signup_modal')

<!-- Navbar -->
@include('components.navbar')

<!-- Thank You Section -->
<section class="thankyou py-5">
    <div class="container text-center">
        <div class="card shadow-lg p-5 rounded-4">
            <div class="mb-4 text-success">
                <i class="bi bi-check-circle-fill display-1"></i>
            </div>
            <h1 class="mb-3">Thank You for Your Order!</h1>
            <p class="lead">Your order <strong>#{{ $order_id ?? '' }}</strong> has been placed successfully.</p>
            <p class="text-muted">We'll contact you soon to confirm your delivery details.</p>
            
            @if(isset($order))
            <!-- Order Details Section -->
            <div class="order-details text-start">
                <!-- Order Information -->
                <div class="detail-section">
                    <h5><i class="bi bi-receipt me-2"></i>Order Information</h5>
                    <div class="info-row">
                        <span class="info-label">Order Number:</span>
                        <span class="info-value">#{{ $order->id }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Order Date:</span>
                        <span class="info-value">{{ $order->created_at->format('F d, Y h:i A') }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Payment Method:</span>
                        <span class="info-value">{{ $order->payment_method ?? 'N/A' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Payment Status:</span>
                        <span class="status-badge badge-{{ strtolower($order->payment_status ?? 'pending') }}">
                            {{ $order->payment_status ?? 'Pending' }}
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Delivery Status:</span>
                        <span class="status-badge badge-pending">
                            {{ $order->delivery_status ?? 'Pending' }}
                        </span>
                    </div>
                </div>

                <!-- Customer Information -->
                <div class="detail-section">
                    <h5><i class="bi bi-person-circle me-2"></i>Customer Information</h5>
                    <div class="info-row">
                        <span class="info-label">Full Name:</span>
                        <span class="info-value">{{ $order->customer_name }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Phone Number:</span>
                        <span class="info-value">{{ $order->phone }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Delivery Address:</span>
                        <span class="info-value">{{ $order->address }}</span>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="detail-section">
                    <h5><i class="bi bi-bag-check me-2"></i>Order Items</h5>
                    @if($order->orderItems && $order->orderItems->count() > 0)
                        @foreach($order->orderItems as $item)
                        <div class="product-item">
                            @if($item->product)
                            <img src="{{ asset('uploads/products/' . $item->product->image) }}" 
                                 alt="{{ $item->product->name }}" 
                                 class="product-image"
                                 onerror="this.src='{{ asset('asset/images/placeholder.jpg') }}'">
                            @endif
                            <div class="product-details">
                                <div class="product-name">{{ $item->product->name ?? 'Product' }}</div>
                                <div class="product-meta">
                                    Quantity: {{ $item->quantity }} × ₱{{ number_format($item->price, 2) }}
                                </div>
                                @if($item->category)
                                <div class="product-meta">
                                    <small class="text-muted">Category: {{ $item->category->name }}</small>
                                </div>
                                @endif
                            </div>
                            <div class="product-price">
                                ₱{{ number_format($item->price * $item->quantity, 2) }}
                            </div>
                        </div>
                        @endforeach
                    @else
                        <p class="text-muted">No items found in this order.</p>
                    @endif
                </div>

                <!-- Order Summary -->
                <div class="total-section">
                    <div class="total-row">
                        <span>Subtotal:</span>
                        <span>₱{{ number_format($order->total, 2) }}</span>
                    </div>
                    <div class="total-row">
                        <span>Delivery Fee:</span>
                        <span>₱0.00</span>
                    </div>
                    <div class="total-row total-amount">
                        <span>Total Amount:</span>
                        <span>₱{{ number_format($order->total, 2) }}</span>
                    </div>
                </div>
            </div>
            @endif

            <!-- Action Buttons -->
            <div class="mt-4 d-flex gap-3 justify-content-center no-print">
                <button onclick="window.print()" class="btn btn-outline-primary">
                    <i class="bi bi-printer me-2"></i>Print Receipt
                </button>
                <a href="{{ route('shop') }}" class="btn btn-primary">
                    <i class="bi bi-shop me-2"></i>Continue Shopping
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Footer -->
@include('components.footer')

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>