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
</head>
<body>
   <!-- Navbar -->
  @include('components.navbar')

    <div class="container py-5">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <img src="{{ asset('asset/images/default-profile.png') }}" alt="User Image" 
                             class="rounded-circle mb-3" width="120" height="120" 
                             style="object-fit: cover; border: 3px solid #FFB6C1;">
                        <h5>{{ Auth::user()->name }}</h5>
                        <p class="text-muted small">{{ Auth::user()->email }}</p>
                    </div>

                    <div class="list-group list-group-flush">
                        <a href="{{ route('profile.index') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-user me-2"></i> My Account
                        </a>
                        <a href="{{ route('profile.purchase-history') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-history me-2"></i> Purchase History
                        </a>
                        <a href="{{ route('profile.track-order') }}" class="list-group-item list-group-item-action active">
                            <i class="fas fa-truck me-2"></i> Track Order
                        </a>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9">
                <div class="card shadow-sm">
                    <div class="card-header" style="background-color: #FFB6C1;">
                        <h5 class="mb-0 text-white"><i class="fas fa-truck me-2"></i> Track Your Orders</h5>
                    </div>
                    <div class="card-body">
                        @if($orders->count() > 0)
                            @foreach($orders as $order)
                                <div class="card mb-3 border">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <h6 class="mb-2">Order #{{ $order->id }}</h6>
                                                <p class="text-muted small mb-2">
                                                    <i class="fas fa-calendar me-1"></i> 
                                                    Placed on {{ \Carbon\Carbon::parse($order->created_at)->format('M d, Y h:i A') }}
                                                </p>
                                                <p class="mb-2">
                                                    <strong>Customer:</strong> {{ $order->customer_name }}<br>
                                                    <strong>Phone:</strong> {{ $order->phone }}<br>
                                                    <strong>Address:</strong> {{ $order->address }}
                                                </p>
                                                <p class="mb-0">
                                                    <strong>Total:</strong> <span class="text-success">₱{{ number_format($order->total, 2) }}</span>
                                                </p>
                                            </div>
                                            <div class="col-md-4 text-end">
                                                <!-- Payment Status -->
                                                <div class="mb-2">
                                                    <small class="text-muted">Payment Status</small><br>
                                                    @if($order->payment_status == 'Paid')
                                                        <span class="badge bg-success">Paid</span>
                                                    @elseif($order->payment_status == 'Pending')
                                                        <span class="badge bg-warning text-dark">Pending</span>
                                                    @elseif($order->payment_status == 'Unsuccessful')
                                                        <span class="badge bg-danger">Unsuccessful</span>
                                                    @else
                                                        <span class="badge bg-secondary">{{ $order->payment_status }}</span>
                                                    @endif
                                                </div>

                                                <!-- Delivery Status -->
                                                <div class="mb-3">
                                                    <small class="text-muted">Delivery Status</small><br>
                                                    @if($order->delivery_status == 'Delivered')
                                                        <span class="badge bg-success">
                                                            <i class="fas fa-check-circle me-1"></i> Delivered
                                                        </span>
                                                    @elseif($order->delivery_status == 'Out for Delivery')
                                                        <span class="badge bg-info text-dark">
                                                            <i class="fas fa-shipping-fast me-1"></i> Out for Delivery
                                                        </span>
                                                    @elseif($order->delivery_status == 'Cancelled')
                                                        <span class="badge bg-danger">
                                                            <i class="fas fa-times-circle me-1"></i> Cancelled
                                                        </span>
                                                    @else
                                                        <span class="badge bg-warning text-dark">
                                                            <i class="fas fa-clock me-1"></i> Pending
                                                        </span>
                                                    @endif
                                                </div>

                                                @if($order->delivery_status === 'Pending')
                                                    <form method="POST" action="{{ route('orders.cancel', $order->id) }}" class="d-inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to cancel this order?');">
                                                            <i class="fas fa-times me-1"></i> Cancel Order
                                                        </button>
                                                    </form>
                                                @elseif(!in_array($order->delivery_status, ['Delivered', 'Cancelled']))
                                                    <button class="btn btn-sm btn-danger" disabled>
                                                        <i class="fas fa-times me-1"></i> Cancel Order
                                                    </button>
                                                @endif
                                                <button class="btn btn-sm btn-pink" data-bs-toggle="modal" data-bs-target="#trackModal{{ $order->id }}">
                                                    <i class="fas fa-map-marker-alt me-1"></i> Track
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Progress Bar -->
                                        <div class="mt-3">
                                            <div class="progress" style="height: 25px;">
                                                @php
                                                    $progress = 0;
                                                    $progressColor = 'bg-warning';
                                                    if($order->delivery_status == 'Pending') {
                                                        $progress = 25;
                                                        $progressColor = 'bg-warning';
                                                    } elseif($order->delivery_status == 'Out for Delivery') {
                                                        $progress = 75;
                                                        $progressColor = 'bg-info';
                                                    } elseif($order->delivery_status == 'Delivered') {
                                                        $progress = 100;
                                                        $progressColor = 'bg-success';
                                                    } elseif($order->delivery_status == 'Cancelled') {
                                                        $progress = 100;
                                                        $progressColor = 'bg-danger';
                                                    }
                                                @endphp
                                                <div class="progress-bar {{ $progressColor }}" role="progressbar" 
                                                     style="width: {{ $progress }}%">
                                                    {{ $order->delivery_status }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Track Order Modal -->
                                <div class="modal fade" id="trackModal{{ $order->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header" style="background-color: #FFB6C1;">
                                                <h5 class="modal-title text-white">Track Order #{{ $order->id }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <!-- Order Timeline -->
                                                <div class="timeline">
                                                    <div class="timeline-item {{ $order->delivery_status != 'Cancelled' ? 'completed' : 'cancelled' }}">
                                                        <div class="timeline-marker"></div>
                                                        <div class="timeline-content">
                                                            <h6>Order Placed</h6>
                                                            <small class="text-muted">{{ \Carbon\Carbon::parse($order->created_at)->format('M d, Y h:i A') }}</small>
                                                        </div>
                                                    </div>

                                                    <div class="timeline-item {{ in_array($order->delivery_status, ['Out for Delivery', 'Delivered']) ? 'completed' : ($order->delivery_status == 'Cancelled' ? 'cancelled' : '') }}">
                                                        <div class="timeline-marker"></div>
                                                        <div class="timeline-content">
                                                            <h6>Processing</h6>
                                                            <small class="text-muted">Your order is being prepared</small>
                                                        </div>
                                                    </div>

                                                    <div class="timeline-item {{ in_array($order->delivery_status, ['Out for Delivery', 'Delivered']) ? 'completed' : ($order->delivery_status == 'Cancelled' ? 'cancelled' : '') }}">
                                                        <div class="timeline-marker"></div>
                                                        <div class="timeline-content">
                                                            <h6>Out for Delivery</h6>
                                                            <small class="text-muted">Your order is on the way</small>
                                                        </div>
                                                    </div>

                                                    <div class="timeline-item {{ $order->delivery_status == 'Delivered' ? 'completed' : ($order->delivery_status == 'Cancelled' ? 'cancelled' : '') }}">
                                                        <div class="timeline-marker"></div>
                                                        <div class="timeline-content">
                                                            <h6>{{ $order->delivery_status == 'Cancelled' ? 'Cancelled' : 'Delivered' }}</h6>
                                                            <small class="text-muted">
                                                                {{ $order->delivery_status == 'Delivered' ? 'Order has been delivered' : 
                                                                   ($order->delivery_status == 'Cancelled' ? 'Order has been cancelled' : 'Estimated delivery soon') }}
                                                            </small>
                                                        </div>
                                                    </div>
                                                </div>

                                                <hr>

                                                <h6>Delivery Information</h6>
                                                <p class="mb-1"><strong>Name:</strong> {{ $order->customer_name }}</p>
                                                <p class="mb-1"><strong>Phone:</strong> {{ $order->phone }}</p>
                                                <p class="mb-1"><strong>Address:</strong> {{ $order->address }}</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-box-open fa-4x text-muted mb-3"></i>
                                <h5 class="text-muted">No Orders to Track</h5>
                                <p class="text-muted">You don't have any orders yet.</p>
                                <a href="{{ route('shop') }}" class="btn btn-pink">Start Shopping</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <style>
    .btn-pink {
        background-color: #FFB6C1;
        color: white;
        border: none;
    }
    .btn-pink:hover {
        background-color: #FF9EAD;
        color: white;
    }
    .list-group-item.active {
        background-color: #FFB6C1;
        border-color: #FFB6C1;
    }

    /* Timeline Styles */
    .timeline {
        position: relative;
        padding: 20px 0;
    }
    .timeline-item {
        position: relative;
        padding-left: 50px;
        padding-bottom: 30px;
    }
    .timeline-item::before {
        content: '';
        position: absolute;
        left: 15px;
        top: 30px;
        width: 2px;
        height: calc(100% - 10px);
        background: #ddd;
    }
    .timeline-item:last-child::before {
        display: none;
    }
    .timeline-marker {
        position: absolute;
        left: 8px;
        top: 5px;
        width: 16px;
        height: 16px;
        border-radius: 50%;
        background: #ddd;
        border: 3px solid #fff;
        box-shadow: 0 0 0 2px #ddd;
    }
    .timeline-item.completed .timeline-marker {
        background: #28a745;
        box-shadow: 0 0 0 2px #28a745;
    }
    .timeline-item.completed::before {
        background: #28a745;
    }
    .timeline-item.cancelled .timeline-marker {
        background: #dc3545;
        box-shadow: 0 0 0 2px #dc3545;
    }
    .timeline-item.cancelled::before {
        background: #dc3545;
    }
    </style>
    @include('components.footer')
</body>
</html>