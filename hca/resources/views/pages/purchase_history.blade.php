<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Purchase History - HookcraftAvenue</title>

<!-- Bootstrap & Icons -->
    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- ADD THIS LINE - Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Font Awesome for other icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
<link rel="stylesheet" href="{{ asset('asset/stylesnav.css') }}">

<style>
      :root {
        --primary-pink: #FFB6C1;
        --primary-pink-dark: #FF9EAD;
        --gradient-start: #FFB6C1;
        --gradient-end: #FF9EAD;
        --success: #10b981;
        --info: #3b82f6;
        --warning: #f59e0b;
        --danger: #ef4444;
        --light-bg: #f8f9fa;
    }

    body {
        background: linear-gradient(135deg, #fef5f7 0%, #fff 100%);
        min-height: 100vh;
    }

    /* Profile Card Styles */
    .profile-card {
        border-radius: 0;
        overflow: hidden;
        transition: transform 0.3s ease;
        max-width: 280px;
    }

    .profile-card:hover {
        transform: translateY(-5px);
    }

    .profile-image-wrapper {
        position: relative;
        display: inline-block;
    }

    .profile-image {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border: 4px solid var(--primary-pink);
        box-shadow: 0 4px 15px rgba(255, 182, 193, 0.3);
    }

    .profile-badge {
        position: absolute;
        bottom: 5px;
        right: 5px;
        background: var(--success);
        color: white;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 3px solid white;
        font-size: 12px;
    }

    .list-group-item {
        transition: all 0.3s ease;
        font-weight: 500;
    }

    .list-group-item:hover:not(.active) {
        background-color: #fff5f7;
        color: var(--primary-pink);
    }

    .list-group-item.active {
        background: linear-gradient(135deg, var(--gradient-start), var(--gradient-end));
        color: white;
        font-weight: 600;
    }

    /* Main Card Styles */
    .main-card {
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 4px 16px rgba(0,0,0,0.08);
    }

    .gradient-header {
        background: linear-gradient(135deg, var(--gradient-start), var(--gradient-end));
        color: white;
        padding: 1.5rem;
    }

    /* Order Card Styles (similar to track order) */
    .order-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        margin-bottom: 1.5rem;
    }

    .order-card:hover {
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
        transform: translateY(-2px);
    }

    .order-header {
        padding: 1.5rem;
        background: linear-gradient(135deg, #fff5f7, #ffffff);
        border-bottom: 1px solid #f0f0f0;
    }

    .order-icon {
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, var(--gradient-start), var(--gradient-end));
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 20px;
        margin-right: 1rem;
    }

    .order-body {
        padding: 1.5rem;
    }

    /* Status Badges */
    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-size: 0.875rem;
        font-weight: 600;
    }

    .status-success {
        background: #d1fae5;
        color: #065f46;
    }

    .status-warning {
        background: #fef3c7;
        color: #92400e;
    }

    .status-danger {
        background: #fee2e2;
        color: #991b1b;
    }

    .status-info {
        background: #dbeafe;
        color: #1e40af;
    }

    .status-secondary {
        background: #e5e7eb;
        color: #374151;
    }

    /* Button Styles */
    .btn-gradient {
        background: linear-gradient(135deg, var(--gradient-start), var(--gradient-end));
        color: white;
        border: none;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-gradient:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(255, 182, 193, 0.4);
        color: white;
    }

    .btn-action {
        padding: 0.5rem 1.25rem;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    /* Info Grid */
    .info-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }

    .info-item {
        display: flex;
        gap: 0.75rem;
        align-items: start;
    }

    .info-item.full-width {
        grid-column: 1 / -1;
    }

    /* Modal Styles */
    .modal-content {
        border-radius: 20px;
        overflow: hidden;
    }

    .modal-header.gradient-header {
        border: none;
    }

    /* Table Styles */
    .product-table {
        background: #f8f9fa;
        border-radius: 12px;
        overflow: hidden;
    }

    .product-table thead {
        background: linear-gradient(135deg, var(--gradient-start), var(--gradient-end));
        color: white;
    }

    .product-table th {
        font-weight: 600;
        padding: 1rem;
    }

    .product-table td {
        padding: 1rem;
        vertical-align: middle;
    }

    .product-table tbody tr {
        transition: all 0.3s ease;
    }

    .product-table tbody tr:hover {
        background: rgba(255, 182, 193, 0.1);
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
    }

    .empty-icon {
        width: 120px;
        height: 120px;
        background: linear-gradient(135deg, #fef5f7, #fff5f7);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 2rem;
    }

    .empty-icon i {
        font-size: 60px;
        color: var(--primary-pink);
        opacity: 0.6;
    }

    /* Toast Notifications */
    .toast-notification {
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 1rem 1.5rem;
        border-radius: 12px;
        color: white;
        font-weight: 500;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        z-index: 9999;
        animation: slideIn 0.3s ease;
    }

    .toast-notification.success {
        background: linear-gradient(135deg, #34d399, #10b981);
    }

    .toast-notification.error {
        background: linear-gradient(135deg, #f87171, #ef4444);
    }

    @keyframes slideIn {
        from {
            transform: translateX(400px);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .container {
            padding-left: 15px;
            padding-right: 15px;
        }
        
        .profile-card {
            max-width: 100%;
            margin-bottom: 1.5rem;
        }
        
        .profile-image {
            width: 80px;
            height: 80px;
        }
        
        .list-group-item {
            padding: 0.75rem 1rem;
            font-size: 0.9rem;
        }
        
        .order-header,
        .order-body {
            padding: 1rem;
        }
        
        .order-icon {
            width: 40px;
            height: 40px;
            font-size: 16px;
        }
        
        .info-grid {
            grid-template-columns: 1fr;
        }
        
        .status-badge {
            padding: 0.4rem 0.75rem;
            font-size: 0.8rem;
        }
        
        .order-footer .d-flex {
            flex-direction: column;
            width: 100%;
        }
        
        .btn-action {
            width: 100%;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }
        
        .modal-dialog {
            margin: 10px;
        }
        
        .modal-body {
            padding: 1rem;
        }
        
        .product-table {
            font-size: 0.9rem;
        }
        
        .product-table th,
        .product-table td {
            padding: 0.75rem 0.5rem;
        }
    }

    /* Ultra Mobile Optimization */
    @media (max-width: 480px) {
        .container {
            padding-left: 10px;
            padding-right: 10px;
        }
        
        .profile-image {
            width: 70px;
            height: 70px;
        }
        
        .gradient-header {
            padding: 1rem;
        }
        
        .gradient-header h5 {
            font-size: 1rem;
        }
        
        .order-header .row {
            flex-direction: column;
            align-items: flex-start !important;
        }
        
        .order-header .text-md-end {
            margin-top: 1rem;
            align-self: flex-start;
        }
        
        .btn-gradient span {
            display: none;
        }
        
        .btn-gradient i {
            font-size: 0.9rem;
        }
        
        .product-table {
            font-size: 0.8rem;
        }
        
        .product-table th,
        .product-table td {
            padding: 0.5rem 0.25rem;
        }
        
        .toast-notification {
            left: 10px;
            right: 10px;
            top: 10px;
            text-align: center;
            animation: slideDown 0.3s ease;
        }
        
        @keyframes slideDown {
            from {
                transform: translateY(-100px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
    }
</style>
</head>

<body>

@include('components.navbar')

<div class="container py-5">
<div class="row g-4">

<!-- Sidebar -->
<div class="col-lg-3">
    <div class="card shadow-sm border-0 profile-card">
        <div class="card-body text-center p-4">
            <div class="profile-image-wrapper mb-3">
                <img src="{{ asset('asset/images/default-profile.png') }}" alt="User Image" 
                     class="rounded-circle profile-image">
                <div class="profile-badge">
                    <i class="fas fa-check"></i>
                </div>
            </div>
            <h5 class="mb-1 fw-bold">{{ Auth::user()->name }}</h5>
            <p class="text-muted small mb-0">{{ Auth::user()->email }}</p>
        </div>

        <div class="list-group list-group-flush">
            <a href="{{ route('profile.index') }}" class="list-group-item list-group-item-action border-0 py-3">
                <i class="fas fa-user me-3"></i> My Account
            </a>
            <a href="{{ route('profile.purchase-history') }}" class="list-group-item list-group-item-action active border-0 py-3">
                <i class="fas fa-history me-3"></i> Purchase History
            </a>
            <a href="{{ route('profile.track-order') }}" class="list-group-item list-group-item-action border-0 py-3">
                <i class="fas fa-truck me-3"></i> Track Order
            </a>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="col-lg-9">
    <div class="card shadow-sm border-0 main-card">
        <div class="card-header border-0 p-4 gradient-header">
            <h4 class="mb-0 text-white fw-bold">
                <i class="fas fa-history me-2"></i> Purchase History
            </h4>
            <p class="mb-0 text-white opacity-75 small mt-1">View all your past orders and receipts</p>
        </div>
        <div class="card-body p-4">
            @if($orders->count() > 0)
                @foreach($orders as $order)
                    @php
                        $orderItems = DB::table('order_item')
                            ->join('products','order_item.product_id','=','products.id')
                            ->where('order_item.order_id',$order->id)
                            ->select('products.name','order_item.quantity','order_item.price')
                            ->get();
                    @endphp

                    <div class="order-card">
                        <div class="order-header">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center">
                                        <div class="order-icon">
                                            <i class="fas fa-receipt"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-1 fw-bold">Order #{{ $order->id }}</h6>
                                            <p class="text-muted small mb-0">
                                                <i class="fas fa-calendar-alt me-1"></i> 
                                                {{ \Carbon\Carbon::parse($order->created_at)->format('M d, Y • h:i A') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 text-md-end mt-3 mt-md-0">
                                    <h5 class="mb-0 text-success fw-bold">₱{{ number_format($order->total, 2) }}</h5>
                                </div>
                            </div>
                        </div>

                        <div class="order-body">
                            <div class="row g-3">
                                <div class="col-md-8">
                                    <div class="info-section">
                                        <h6 class="section-title mb-3">
                                            <i class="fas fa-info-circle me-2"></i>Order Information
                                        </h6>
                                        <div class="info-grid">
                                            <div class="info-item">
                                                <i class="fas fa-user text-muted"></i>
                                                <div>
                                                    <small class="text-muted d-block">Customer</small>
                                                    <span class="fw-medium">{{ $order->customer_name }}</span>
                                                </div>
                                            </div>
                                            <div class="info-item">
                                                <i class="fas fa-map-marker-alt text-muted"></i>
                                                <div>
                                                    <small class="text-muted d-block">Address</small>
                                                    <span class="fw-medium">{{ $order->address }}</span>
                                                </div>
                                            </div>
                                            <div class="info-item">
                                                <i class="fas fa-box text-muted"></i>
                                                <div>
                                                    <small class="text-muted d-block">Items</small>
                                                    <span class="fw-medium">{{ $orderItems->count() }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="status-section">
                                        <div class="status-item mb-3">
                                            <small class="text-muted d-block mb-2">Payment Status</small>
                                            @if($order->payment_status == 'Paid')
                                                <span class="status-badge status-success">
                                                    <i class="fas fa-check-circle me-1"></i> Paid
                                                </span>
                                            @elseif($order->payment_status == 'Pending')
                                                <span class="status-badge status-warning">
                                                    <i class="fas fa-clock me-1"></i> Pending
                                                </span>
                                            @elseif($order->payment_status == 'Unsuccessful')
                                                <span class="status-badge status-danger">
                                                    <i class="fas fa-times-circle me-1"></i> Unsuccessful
                                                </span>
                                            @else
                                                <span class="status-badge status-secondary">{{ $order->payment_status }}</span>
                                            @endif
                                        </div>

                                        <div class="status-item">
                                            <small class="text-muted d-block mb-2">Delivery Status</small>
                                            @if($order->delivery_status == 'Delivered')
                                                <span class="status-badge status-success">
                                                    <i class="fas fa-check-double me-1"></i> Delivered
                                                </span>
                                            @elseif($order->delivery_status == 'Out for Delivery')
                                                <span class="status-badge status-info">
                                                    <i class="fas fa-shipping-fast me-1"></i> Out for Delivery
                                                </span>
                                            @elseif($order->delivery_status == 'Cancelled')
                                                <span class="status-badge status-danger">
                                                    <i class="fas fa-ban me-1"></i> Cancelled
                                                </span>
                                            @else
                                                <span class="status-badge status-warning">
                                                    <i class="fas fa-hourglass-half me-1"></i> Pending
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Order Items Preview -->
                            <div class="items-preview mt-4">
                                <h6 class="section-title mb-3">
                                    <i class="fas fa-shopping-bag me-2"></i>Order Items
                                </h6>
                                <div class="row g-2">
                                    @foreach($orderItems->take(2) as $item)
                                        <div class="col-12 col-md-6">
                                            <div class="item-card d-flex align-items-center p-2 bg-light rounded">
                                                <div class="item-info flex-grow-1">
                                                    <small class="text-muted d-block">Product</small>
                                                    <span class="fw-medium">{{ Str::limit($item->name, 30) }}</span>
                                                </div>
                                                <div class="item-qty ms-3">
                                                    <small class="text-muted d-block">Qty</small>
                                                    <span class="fw-medium">{{ $item->quantity }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                    @if($orderItems->count() > 2)
                                        <div class="col-12">
                                            <div class="text-center mt-2">
                                                <small class="text-muted">
                                                    +{{ $orderItems->count() - 2 }} more items
                                                </small>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="order-footer p-3 bg-light border-top">
                            <div class="d-flex gap-2 justify-content-end">
                                <button class="btn btn-gradient btn-action" data-bs-toggle="modal" data-bs-target="#orderModal{{ $order->id }}">
                                    <i class="fas fa-eye me-1"></i> View Details
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Enhanced Order Details Modal -->
                    <div class="modal fade" id="orderModal{{ $order->id }}" tabindex="-1">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content border-0 shadow-lg">
                                <div class="modal-header gradient-header border-0">
                                    <div>
                                        <h5 class="modal-title text-white fw-bold mb-1">
                                            <i class="fas fa-receipt me-2"></i>Order Details #{{ $order->id }}
                                        </h5>
                                        <p class="text-white opacity-75 small mb-0">Complete order information</p>
                                    </div>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body p-4">
                                    <!-- Order Info -->
                                    <div class="delivery-info-card mb-4">
                                        <h6 class="fw-bold mb-3">
                                            <i class="fas fa-info-circle me-2"></i>Order Information
                                        </h6>
                                        <div class="info-grid">
                                            <div class="info-item">
                                                <i class="fas fa-user text-muted"></i>
                                                <div>
                                                    <small class="text-muted d-block">Customer Name</small>
                                                    <span class="fw-medium">{{ $order->customer_name }}</span>
                                                </div>
                                            </div>
                                            <div class="info-item">
                                                <i class="fas fa-phone text-muted"></i>
                                                <div>
                                                    <small class="text-muted d-block">Contact Number</small>
                                                    <span class="fw-medium">{{ $order->phone }}</span>
                                                </div>
                                            </div>
                                            <div class="info-item">
                                                <i class="fas fa-calendar text-muted"></i>
                                                <div>
                                                    <small class="text-muted d-block">Order Date</small>
                                                    <span class="fw-medium">{{ \Carbon\Carbon::parse($order->created_at)->format('M d, Y • h:i A') }}</span>
                                                </div>
                                            </div>
                                            <div class="info-item full-width">
                                                <i class="fas fa-map-marker-alt text-muted"></i>
                                                <div>
                                                    <small class="text-muted d-block">Delivery Address</small>
                                                    <span class="fw-medium">{{ $order->address }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Status Summary -->
                                    <div class="row mb-4">
                                        <div class="col-md-6">
                                            <div class="status-card p-3 rounded">
                                                <small class="text-muted d-block mb-1">Payment Status</small>
                                                @if($order->payment_status == 'Paid')
                                                    <span class="status-badge status-success">
                                                        <i class="fas fa-check-circle me-1"></i> Paid
                                                    </span>
                                                @elseif($order->payment_status == 'Pending')
                                                    <span class="status-badge status-warning">
                                                        <i class="fas fa-clock me-1"></i> Pending
                                                    </span>
                                                @elseif($order->payment_status == 'Unsuccessful')
                                                    <span class="status-badge status-danger">
                                                        <i class="fas fa-times-circle me-1"></i> Unsuccessful
                                                    </span>
                                                @else
                                                    <span class="status-badge status-secondary">{{ $order->payment_status }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="status-card p-3 rounded">
                                                <small class="text-muted d-block mb-1">Delivery Status</small>
                                                @if($order->delivery_status == 'Delivered')
                                                    <span class="status-badge status-success">
                                                        <i class="fas fa-check-double me-1"></i> Delivered
                                                    </span>
                                                @elseif($order->delivery_status == 'Out for Delivery')
                                                    <span class="status-badge status-info">
                                                        <i class="fas fa-shipping-fast me-1"></i> Out for Delivery
                                                    </span>
                                                @elseif($order->delivery_status == 'Cancelled')
                                                    <span class="status-badge status-danger">
                                                        <i class="fas fa-ban me-1"></i> Cancelled
                                                    </span>
                                                @else
                                                    <span class="status-badge status-warning">
                                                        <i class="fas fa-hourglass-half me-1"></i> Pending
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Order Items Table -->
                                    <div class="order-items-section">
                                        <h6 class="fw-bold mb-3">
                                            <i class="fas fa-shopping-bag me-2"></i>Order Items
                                        </h6>
                                        <div class="table-responsive">
                                            <table class="table product-table">
                                                <thead>
                                                    <tr>
                                                        <th>Product</th>
                                                        <th>Quantity</th>
                                                        <th>Price</th>
                                                        <th>Subtotal</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($orderItems as $item)
                                                        <tr>
                                                            <td>{{ $item->name }}</td>
                                                            <td>{{ $item->quantity }}</td>
                                                            <td>₱{{ number_format($item->price, 2) }}</td>
                                                            <td>₱{{ number_format($item->price * $item->quantity, 2) }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <td colspan="3" class="text-end fw-bold">Total Amount:</td>
                                                        <td class="fw-bold text-success">₱{{ number_format($order->total, 2) }}</td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer border-0 bg-light">
                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                                        <i class="fas fa-times me-1"></i> Close
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-receipt"></i>
                    </div>
                    <h5 class="fw-bold mb-2">No Purchase History</h5>
                    <p class="text-muted mb-4">You haven't placed any orders yet. Start shopping to see your history here!</p>
                    <a href="{{ route('shop') }}" class="btn btn-gradient btn-lg">
                        <i class="fas fa-shopping-bag me-2"></i> Start Shopping
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

</div>
</div>

@if(session('success'))
    <div class="toast-notification success">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div class="toast-notification error">
        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
    </div>
@endif

@include('components.footer')

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
// Auto-hide toast notifications after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    const toasts = document.querySelectorAll('.toast-notification');
    
    toasts.forEach(toast => {
        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateX(400px)';
            setTimeout(() => {
                toast.remove();
            }, 300);
        }, 5000);
    });
    
    // Add click to dismiss functionality
    toasts.forEach(toast => {
        toast.addEventListener('click', function() {
            this.style.opacity = '0';
            this.style.transform = 'translateX(400px)';
            setTimeout(() => {
                this.remove();
            }, 300);
        });
    });
    
    // Enhanced modal interactions
    const modals = document.querySelectorAll('.modal');
    modals.forEach(modal => {
        modal.addEventListener('shown.bs.modal', function() {
            // Add animation to modal content
            const modalContent = this.querySelector('.modal-content');
            modalContent.style.transform = 'scale(0.9)';
            modalContent.style.opacity = '0';
            
            setTimeout(() => {
                modalContent.style.transition = 'all 0.3s ease';
                modalContent.style.transform = 'scale(1)';
                modalContent.style.opacity = '1';
            }, 10);
        });
        
        modal.addEventListener('hidden.bs.modal', function() {
            const modalContent = this.querySelector('.modal-content');
            modalContent.style.transform = 'scale(0.9)';
            modalContent.style.opacity = '0';
        });
    });
    
    // Order card click interaction for mobile
    if (window.innerWidth <= 768) {
        const orderCards = document.querySelectorAll('.order-card');
        orderCards.forEach(card => {
            card.addEventListener('click', function(e) {
                if (!e.target.closest('button')) {
                    const viewBtn = this.querySelector('button[data-bs-toggle="modal"]');
                    if (viewBtn) viewBtn.click();
                }
            });
        });
    }
});
</script>

</body>
</html>