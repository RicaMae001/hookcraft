<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Order - HookcraftAvenue</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('asset/stylesnav.css') }}">
    
    <!-- Add CSRF Token Meta Tag -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
   <!-- Navbar -->
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
                        <a href="{{ route('profile.purchase-history') }}" class="list-group-item list-group-item-action border-0 py-3">
                            <i class="fas fa-history me-3"></i> Purchase History
                        </a>
                        <a href="{{ route('profile.track-order') }}" class="list-group-item list-group-item-action active border-0 py-3">
                            <i class="fas fa-truck me-3"></i> Track Order
                        </a>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-lg-9">
                <div class="card shadow-sm border-0 main-card">
                    <div class="card-header border-0 p-4 gradient-header">
                        <h4 class="mb-0 text-black fw-bold">
                            <i class="fas fa-map-marked-alt me-2"></i> Track Your Orders
                        </h4>
                        <p class="mb-0 text-black opacity-75 small mt-1">Monitor your order status in real-time</p>
                    </div>
                    <div class="card-body p-4">
                        @if($orders->count() > 0)
                            @foreach($orders as $order)
                                <div class="order-card mb-4">
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
                                                        <i class="fas fa-info-circle me-2"></i>Delivery Information
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
                                                            <i class="fas fa-phone text-muted"></i>
                                                            <div>
                                                                <small class="text-muted d-block">Phone</small>
                                                                <span class="fw-medium">{{ $order->phone }}</span>
                                                            </div>
                                                        </div>
                                                        <div class="info-item full-width">
                                                            <i class="fas fa-map-marker-alt text-muted"></i>
                                                            <div>
                                                                <small class="text-muted d-block">Address</small>
                                                                <span class="fw-medium">{{ $order->address }}</span>
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

                                        <!-- Enhanced Progress Bar -->
                                        <div class="progress-section mt-4">
                                            @php
                                                $progress = 0;
                                                $progressClass = 'progress-warning';
                                                $statusText = 'Order Placed';
                                                
                                                if($order->delivery_status == 'Pending') {
                                                    $progress = 25;
                                                    $progressClass = 'progress-warning';
                                                    $statusText = 'Processing';
                                                } elseif($order->delivery_status == 'Out for Delivery') {
                                                    $progress = 75;
                                                    $progressClass = 'progress-info';
                                                    $statusText = 'Out for Delivery';
                                                } elseif($order->delivery_status == 'Delivered') {
                                                    $progress = 100;
                                                    $progressClass = 'progress-success';
                                                    $statusText = 'Delivered';
                                                } elseif($order->delivery_status == 'Cancelled') {
                                                    $progress = 100;
                                                    $progressClass = 'progress-danger';
                                                    $statusText = 'Cancelled';
                                                }
                                            @endphp
                                            
                                            <div class="custom-progress">
                                                <div class="progress-bar-custom {{ $progressClass }}" style="width: {{ $progress }}%">
                                                    <span class="progress-text">{{ $statusText }} • {{ $progress }}%</span>
                                                </div>
                                            </div>

                                            <div class="progress-milestones">
                                                <div class="milestone {{ $progress >= 25 ? 'active' : '' }}">
                                                    <i class="fas fa-box"></i>
                                                    <span>Placed</span>
                                                </div>
                                                <div class="milestone {{ $progress >= 50 ? 'active' : '' }}">
                                                    <i class="fas fa-cog"></i>
                                                    <span>Processing</span>
                                                </div>
                                                <div class="milestone {{ $progress >= 75 ? 'active' : '' }}">
                                                    <i class="fas fa-truck"></i>
                                                    <span>Shipping</span>
                                                </div>
                                                <div class="milestone {{ $progress >= 100 ? 'active' : '' }}">
                                                    <i class="fas {{ $order->delivery_status == 'Cancelled' ? 'fa-times' : 'fa-home' }}"></i>
                                                    <span>{{ $order->delivery_status == 'Cancelled' ? 'Cancelled' : 'Delivered' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="order-footer">
                                        <div class="d-flex gap-2 justify-content-end">
                                            @if($order->delivery_status === 'Pending')
                                                <!-- SIMPLIFIED CANCEL FORM - Using traditional form submission -->
                                                <form method="POST" action="{{ route('orders.cancel', ['order' => $order->id]) }}" 
                                                      id="cancelForm{{ $order->id }}" class="d-inline"
                                                      onsubmit="return confirmCancel('{{ $order->id }}')">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-outline-danger btn-action" 
                                                            id="cancelBtn{{ $order->id }}">
                                                        <i class="fas fa-times me-1"></i> Cancel Order
                                                    </button>
                                                </form>
                                            @elseif(!in_array($order->delivery_status, ['Delivered', 'Cancelled']))
                                                <button class="btn btn-outline-danger btn-action" disabled>
                                                    <i class="fas fa-times me-1"></i> Cancel Order
                                                </button>
                                            @endif
                                            <button class="btn btn-gradient btn-action" data-bs-toggle="modal" data-bs-target="#trackModal{{ $order->id }}">
                                                <i class="fas fa-route me-1"></i> View Details
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Enhanced Track Order Modal -->
                                <div class="modal fade" id="trackModal{{ $order->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-lg modal-dialog-centered">
                                        <div class="modal-content border-0 shadow-lg">
                                            <div class="modal-header gradient-header border-0">
                                                <div>
                                                    <h5 class="modal-title text-white fw-bold mb-1">
                                                        <i class="fas fa-map-marked-alt me-2"></i>Track Order #{{ $order->id }}
                                                    </h5>
                                                    <p class="text-white opacity-75 small mb-0">Real-time tracking information</p>
                                                </div>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body p-4">
                                                <!-- Enhanced Timeline -->
                                                <div class="timeline-modern">
                                                    <div class="timeline-item-modern {{ $order->delivery_status != 'Cancelled' ? 'completed' : 'cancelled' }}">
                                                        <div class="timeline-marker-modern">
                                                            <i class="fas fa-check"></i>
                                                        </div>
                                                        <div class="timeline-content-modern">
                                                            <div class="timeline-badge">
                                                                <i class="fas fa-shopping-cart"></i>
                                                            </div>
                                                            <h6 class="fw-bold mb-1">Order Placed</h6>
                                                            <small class="text-muted">{{ \Carbon\Carbon::parse($order->created_at)->format('M d, Y • h:i A') }}</small>
                                                            <p class="small text-muted mb-0 mt-1">Your order has been received and confirmed</p>
                                                        </div>
                                                    </div>

                                                    <div class="timeline-item-modern {{ in_array($order->delivery_status, ['Out for Delivery', 'Delivered']) ? 'completed' : ($order->delivery_status == 'Cancelled' ? 'cancelled' : '') }}">
                                                        <div class="timeline-marker-modern">
                                                            <i class="fas fa-check"></i>
                                                        </div>
                                                        <div class="timeline-content-modern">
                                                            <div class="timeline-badge">
                                                                <i class="fas fa-cogs"></i>
                                                            </div>
                                                            <h6 class="fw-bold mb-1">Processing</h6>
                                                            <small class="text-muted">Order preparation in progress</small>
                                                            <p class="small text-muted mb-0 mt-1">Your items are being carefully prepared</p>
                                                        </div>
                                                    </div>

                                                    <div class="timeline-item-modern {{ in_array($order->delivery_status, ['Out for Delivery', 'Delivered']) ? 'completed' : ($order->delivery_status == 'Cancelled' ? 'cancelled' : '') }}">
                                                        <div class="timeline-marker-modern">
                                                            <i class="fas fa-check"></i>
                                                        </div>
                                                        <div class="timeline-content-modern">
                                                            <div class="timeline-badge">
                                                                <i class="fas fa-shipping-fast"></i>
                                                            </div>
                                                            <h6 class="fw-bold mb-1">Out for Delivery</h6>
                                                            <small class="text-muted">Package is on the way</small>
                                                            <p class="small text-muted mb-0 mt-1">Your order is being delivered to your address</p>
                                                        </div>
                                                    </div>

                                                    <div class="timeline-item-modern {{ $order->delivery_status == 'Delivered' ? 'completed' : ($order->delivery_status == 'Cancelled' ? 'cancelled' : '') }}">
                                                        <div class="timeline-marker-modern">
                                                            <i class="fas {{ $order->delivery_status == 'Delivered' ? 'fa-check' : ($order->delivery_status == 'Cancelled' ? 'fa-times' : 'fa-clock') }}"></i>
                                                        </div>
                                                        <div class="timeline-content-modern">
                                                            <div class="timeline-badge">
                                                                <i class="fas {{ $order->delivery_status == 'Cancelled' ? 'fa-ban' : 'fa-home' }}"></i>
                                                            </div>
                                                            <h6 class="fw-bold mb-1">{{ $order->delivery_status == 'Cancelled' ? 'Order Cancelled' : 'Delivered' }}</h6>
                                                            <small class="text-muted">
                                                                {{ $order->delivery_status == 'Delivered' ? 'Order successfully delivered' : 
                                                                   ($order->delivery_status == 'Cancelled' ? 'Order has been cancelled' : 'Awaiting delivery') }}
                                                            </small>
                                                            <p class="small text-muted mb-0 mt-1">
                                                                {{ $order->delivery_status == 'Delivered' ? 'Thank you for your purchase!' : 
                                                                   ($order->delivery_status == 'Cancelled' ? 'We apologize for any inconvenience' : 'Your order will arrive soon') }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="delivery-info-card mt-4">
                                                    <h6 class="fw-bold mb-3">
                                                        <i class="fas fa-map-marker-alt me-2"></i>Delivery Information
                                                    </h6>
                                                    <div class="info-grid">
                                                        <div class="info-item">
                                                            <i class="fas fa-user text-muted"></i>
                                                            <div>
                                                                <small class="text-muted d-block">Full Name</small>
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
                                                        <div class="info-item full-width">
                                                            <i class="fas fa-map-marker-alt text-muted"></i>
                                                            <div>
                                                                <small class="text-muted d-block">Delivery Address</small>
                                                                <span class="fw-medium">{{ $order->address }}</span>
                                                            </div>
                                                        </div>
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
                                    <i class="fas fa-box-open"></i>
                                </div>
                                <h5 class="fw-bold mb-2">No Orders to Track</h5>
                                <p class="text-muted mb-4">You haven't placed any orders yet. Start shopping to see your orders here!</p>
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
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

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
        background: linear-gradient(135deg, #e7c9cf 50%, #beb2b2 100%);
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
    }

    .gradient-header {
        background: linear-gradient(135deg, var(--gradient-start), var(--gradient-end));
    }

    /* Order Card Styles */
    .order-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
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

    .order-footer {
        padding: 1rem 1.5rem;
        background: #f8f9fa;
        border-top: 1px solid #f0f0f0;
    }

    /* Info Section */
    .info-section {
        background: #f8f9fa;
        padding: 1.5rem;
        border-radius: 12px;
    }

    .section-title {
        color: #333;
        font-size: 0.95rem;
        font-weight: 600;
    }

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

    .info-item i {
        font-size: 18px;
        margin-top: 2px;
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

    /* Enhanced Progress Bar */
    .progress-section {
        background: #f8f9fa;
        padding: 1.5rem;
        border-radius: 12px;
    }

    .custom-progress {
        height: 40px;
        background: #e5e7eb;
        border-radius: 50px;
        overflow: hidden;
        box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1);
        margin-bottom: 1.5rem;
    }

    .progress-bar-custom {
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: width 0.6s ease;
        position: relative;
        overflow: hidden;
    }

    .progress-bar-custom::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
        animation: shimmer 2s infinite;
    }

    @keyframes shimmer {
        0% { left: -100%; }
        100% { left: 100%; }
    }

    .progress-warning {
        background: linear-gradient(135deg, #fbbf24, #f59e0b);
    }

    .progress-info {
        background: linear-gradient(135deg, #60a5fa, #3b82f6);
    }

    .progress-success {
        background: linear-gradient(135deg, #34d399, #10b981);
    }

    .progress-danger {
        background: linear-gradient(135deg, #f87171, #ef4444);
    }

    .progress-text {
        color: white;
        font-weight: 600;
        font-size: 0.875rem;
        z-index: 1;
    }

    /* Progress Milestones */
    .progress-milestones {
        display: flex;
        justify-content: space-between;
    }

    .milestone {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.5rem;
        opacity: 0.4;
        transition: all 0.3s ease;
    }

    .milestone.active {
        opacity: 1;
    }

    .milestone i {
        width: 40px;
        height: 40px;
        background: #e5e7eb;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        transition: all 0.3s ease;
    }

    .milestone.active i {
        background: linear-gradient(135deg, var(--gradient-start), var(--gradient-end));
        color: white;
        box-shadow: 0 4px 12px rgba(255, 182, 193, 0.4);
    }

    .milestone span {
        font-size: 0.75rem;
        font-weight: 500;
        color: #6b7280;
    }

    .milestone.active span {
        color: #111827;
        font-weight: 600;
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

    /* Modal Timeline Styles */
    .timeline-modern {
        position: relative;
        padding: 1rem 0;
    }

    .timeline-item-modern {
        position: relative;
        padding-left: 80px;
        padding-bottom: 2rem;
    }

    .timeline-item-modern::before {
        content: '';
        position: absolute;
        left: 30px;
        top: 50px;
        width: 3px;
        height: calc(100% - 30px);
        background: linear-gradient(to bottom, #e5e7eb, #f3f4f6);
    }

    .timeline-item-modern:last-child::before {
        display: none;
    }

    .timeline-marker-modern {
        position: absolute;
        left: 18px;
        top: 15px;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: white;
        border: 3px solid #e5e7eb;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        color: #9ca3af;
        z-index: 1;
        transition: all 0.3s ease;
    }

    .timeline-item-modern.completed .timeline-marker-modern {
        background: var(--success);
        border-color: var(--success);
        color: white;
        box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.2);
    }

    .timeline-item-modern.cancelled .timeline-marker-modern {
        background: var(--danger);
        border-color: var(--danger);
        color: white;
        box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.2);
    }

    .timeline-item-modern.completed::before {
        background: linear-gradient(to bottom, var(--success), #86efac);
    }

    .timeline-item-modern.cancelled::before {
        background: linear-gradient(to bottom, var(--danger), #fca5a5);
    }

    .timeline-content-modern {
        background: white;
        padding: 1.25rem;
        border-radius: 12px;
        border: 2px solid #f3f4f6;
        transition: all 0.3s ease;
    }

    .timeline-item-modern.completed .timeline-content-modern {
        border-color: #d1fae5;
        background: #f0fdf4;
    }

    .timeline-item-modern.cancelled .timeline-content-modern {
        border-color: #fee2e2;
        background: #fef2f2;
    }

    .timeline-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        background: linear-gradient(135deg, var(--gradient-start), var(--gradient-end));
        border-radius: 10px;
        color: white;
        margin-bottom: 0.75rem;
    }

    /* Delivery Info Card */
    .delivery-info-card {
        background: #f8f9fa;
        padding: 1.5rem;
        border-radius: 12px;
        border: 2px solid #f0f0f0;
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

    /* Responsive - Original */
    @media (max-width: 768px) {
        .info-grid {
            grid-template-columns: 1fr;
        }

        .progress-milestones {
            flex-wrap: wrap;
            gap: 1rem;
        }

        .milestone {
            flex: 0 0 calc(50% - 0.5rem);
        }

        .order-icon {
            width: 40px;
            height: 40px;
            font-size: 16px;
        }

        .timeline-item-modern {
            padding-left: 60px;
        }
    }
    
    /* Mobile Responsive Enhancements */
    @media (max-width: 768px) {
        /* Container adjustments */
        .container {
            padding-left: 15px;
            padding-right: 15px;
        }
        
        /* Profile sidebar mobile optimization */
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
        
        /* Order card mobile optimization */
        .order-card {
            margin-bottom: 1rem;
        }
        
        .order-header,
        .order-body,
        .order-footer {
            padding: 1rem;
        }
        
        /* Better mobile grid for order info */
        .info-grid {
            grid-template-columns: 1fr;
            gap: 0.75rem;
        }
        
        .info-item {
            padding: 0.5rem;
            background: white;
            border-radius: 8px;
            border: 1px solid #f0f0f0;
        }
        
        /* Progress milestones mobile */
        .progress-milestones {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 0.5rem;
        }
        
        .milestone {
            text-align: center;
        }
        
        .milestone i {
            width: 32px;
            height: 32px;
            font-size: 14px;
        }
        
        .milestone span {
            font-size: 0.7rem;
            white-space: nowrap;
        }
        
        /* Buttons mobile optimization */
        .order-footer .d-flex {
            flex-direction: column;
            width: 100%;
        }
        
        .btn-action {
            width: 100%;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }
        
        /* Modal mobile optimization */
        .modal-dialog {
            margin: 10px;
        }
        
        .modal-body {
            padding: 1rem;
        }
        
        /* Timeline mobile adjustments */
        .timeline-item-modern {
            padding-left: 50px;
            padding-bottom: 1.5rem;
        }
        
        .timeline-marker-modern {
            left: 10px;
            width: 24px;
            height: 24px;
            font-size: 10px;
        }
        
        .timeline-content-modern {
            padding: 1rem;
        }
        
        .timeline-badge {
            width: 30px;
            height: 30px;
            font-size: 14px;
        }
        
        /* Status badges mobile */
        .status-badge {
            padding: 0.4rem 0.75rem;
            font-size: 0.8rem;
        }
    }

    /* Ultra Mobile Optimization for screens below 480px */
    @media (max-width: 480px) {
        /* Container adjustments */
        .container {
            padding-left: 10px;
            padding-right: 10px;
        }
        
        /* Profile card adjustments */
        .profile-card .card-body {
            padding: 1.5rem 1rem;
        }
        
        .profile-image {
            width: 70px;
            height: 70px;
        }
        
        /* Order header mobile optimization */
        .order-header .row {
            flex-direction: column;
            align-items: flex-start !important;
        }
        
        .order-header .col-md-6.text-md-end {
            margin-top: 1rem;
            align-self: flex-start;
        }
        
        /* Progress section mobile */
        .progress-section {
            padding: 1rem;
        }
        
        .custom-progress {
            height: 35px;
            margin-bottom: 1rem;
        }
        
        .progress-text {
            font-size: 0.75rem;
        }
        
        /* Progress milestones ultra mobile */
        .progress-milestones {
            grid-template-columns: repeat(2, 1fr);
            row-gap: 1rem;
        }
        
        .milestone:nth-child(3),
        .milestone:nth-child(4) {
            margin-top: 0.5rem;
        }
        
        /* Modal adjustments */
        .modal-content {
            margin: 5px;
        }
        
        .modal-body {
            padding: 0.75rem;
        }
        
        /* Timeline ultra mobile */
        .timeline-item-modern {
            padding-left: 40px;
            padding-bottom: 1rem;
        }
        
        .timeline-content-modern {
            padding: 0.75rem;
        }
        
        .timeline-content-modern h6 {
            font-size: 0.9rem;
        }
        
        .timeline-content-modern small,
        .timeline-content-modern p {
            font-size: 0.8rem;
        }
        
        /* Toast notification mobile */
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
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-hide toast notifications after 5 seconds
        const toasts = document.querySelectorAll('.toast-notification');
        
        toasts.forEach(toast => {
            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transform = 'translateX(400px)';
                setTimeout(() => {
                    toast.remove();
                }, 300);
            }, 5000);
            
            // Add click to dismiss functionality
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
                // Animate timeline items when modal opens
                const timelineItems = this.querySelectorAll('.timeline-item-modern');
                timelineItems.forEach((item, index) => {
                    setTimeout(() => {
                        item.style.opacity = '1';
                        item.style.transform = 'translateX(0)';
                    }, index * 200);
                });
            });
        });
    });
    
    // Simple confirmation and loading state for cancel order
    function confirmCancel(orderId) {
        if (!confirm('Are you sure you want to cancel this order?\n\nThis action cannot be undone.')) {
            return false;
        }
        
        // Show loading state
        const button = document.getElementById('cancelBtn' + orderId);
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Cancelling...';
        button.disabled = true;
        
        // Re-enable button after 10 seconds in case submission fails
        setTimeout(() => {
            button.innerHTML = originalText;
            button.disabled = false;
        }, 10000);
        
        return true;
    }
    </script>
</body>
</html>