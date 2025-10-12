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
   
  <nav class="navbar navbar-expand-lg navbar-light sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold" href="{{ route('home') }}">
            <img src="{{ asset('asset/images/logo.jpg') }}" alt="Logo" width="40" height="40" class="rounded-circle me-2">
            HookcraftAvenue
        </a>
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarContent">
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('shop') }}">Shop</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('about') }}">About</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('gallery') }}">Gallery</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('contact') }}">Contact</a></li>
            </ul>

            @php
                $isLoggedIn = Auth::check();
                $user = Auth::user();
            @endphp

            <ul class="navbar-nav flex-row align-items-center">
                <!-- Cart -->
                <li class="nav-item me-3">
                    <a class="nav-link position-relative" href="{{ $isLoggedIn ? route('cart.index') : '#' }}">
                        <i class="bi bi-cart fs-5"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger cart-badge">
                            {{ $cartCount }}
                        </span>
                    </a>
                </li>

                <!-- User -->
                @if($isLoggedIn)
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle p-0 border-0 bg-transparent d-flex align-items-center" 
                           href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="{{ asset('asset/images/default-profile.png') }}" alt="Profile" 
                                 width="40" height="40" class="rounded-circle" style="object-fit: cover; border: 2px solid #FFB6C1;">
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end mt-2 shadow" style="min-width: 250px;">
                            <!-- User Info Header -->
                            <li class="px-3 py-3 border-bottom">
                                <div class="d-flex align-items-center">
                                    <img src="{{ asset('asset/images/default-profile.png') }}" alt="Profile" 
                                         width="50" height="50" class="rounded-circle me-3" style="border: 2px solid #FFB6C1;">
                                    <div>
                                        <div class="fw-bold">{{ $user->name }}</div>
                                        <small class="text-muted">{{ $user->email }}</small>
                                    </div>
                                </div>
                            </li>

                            <!-- Menu Items -->
                            <li>
                                <a class="dropdown-item py-2" href="{{ route('profile.index') }}">
                                    <i class="bi bi-person-circle me-2"></i>My Account
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item py-2" href="{{ route('profile.purchase-history') }}">
                                    <i class="bi bi-clock-history me-2"></i>Purchase History
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item py-2" href="{{ route('profile.track-order') }}">
                                    <i class="bi bi-truck me-2"></i>Track Order
                                </a>
                            </li>
                            <li><hr class="dropdown-divider my-2"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button class="dropdown-item text-danger py-2" type="submit">
                                        <i class="bi bi-box-arrow-right me-2"></i>Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @else
                    <li class="nav-item me-2">
                        <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#loginModal">Login</a>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>
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
</body>
</html>