<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase History - HookcraftAvenue</title>
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
                        <a href="{{ route('profile.purchase-history') }}" class="list-group-item list-group-item-action active">
                            <i class="fas fa-history me-2"></i> Purchase History
                        </a>
                        <a href="{{ route('profile.track-order') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-truck me-2"></i> Track Order
                        </a>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9">
                <div class="card shadow-sm">
                    <div class="card-header" style="background-color: #FFB6C1;">
                        <h5 class="mb-0 text-white"><i class="fas fa-history me-2"></i> Purchase History</h5>
                    </div>
                    <div class="card-body">
                        @if($orders->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Order ID</th>
                                            <th>Date</th>
                                            <th>Customer Name</th>
                                            <th>Total</th>
                                            <th>Payment Status</th>
                                            <th>Delivery Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($orders as $order)
                                            <tr>
                                                <td><strong>#{{ $order->id }}</strong></td>
                                                <td>{{ \Carbon\Carbon::parse($order->created_at)->format('M d, Y') }}</td>
                                                <td>{{ $order->customer_name }}</td>
                                                <td>₱{{ number_format($order->total, 2) }}</td>
                                                <td>
                                                    @if($order->payment_status == 'Paid')
                                                        <span class="badge bg-success">Paid</span>
                                                    @elseif($order->payment_status == 'Pending')
                                                        <span class="badge bg-warning text-dark">Pending</span>
                                                    @elseif($order->payment_status == 'Unsuccessful')
                                                        <span class="badge bg-danger">Unsuccessful</span>
                                                    @else
                                                        <span class="badge bg-secondary">{{ $order->payment_status }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($order->delivery_status == 'Delivered')
                                                        <span class="badge bg-success">Delivered</span>
                                                    @elseif($order->delivery_status == 'Out for Delivery')
                                                        <span class="badge bg-info text-dark">Out for Delivery</span>
                                                    @elseif($order->delivery_status == 'Cancelled')
                                                        <span class="badge bg-danger">Cancelled</span>
                                                    @else
                                                        <span class="badge bg-warning text-dark">Pending</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <button class="btn btn-sm btn-pink" data-bs-toggle="modal" data-bs-target="#orderModal{{ $order->id }}">
                                                        <i class="fas fa-eye"></i> View
                                                    </button>
                                                </td>
                                            </tr>

                                            <!-- Order Details Modal -->
                                            <div class="modal fade" id="orderModal{{ $order->id }}" tabindex="-1">
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">
                                                        <div class="modal-header" style="background-color: #FFB6C1;">
                                                            <h5 class="modal-title text-white">Order #{{ $order->id }} Details</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row mb-3">
                                                                <div class="col-md-6">
                                                                    <strong>Customer Name:</strong> {{ $order->customer_name }}<br>
                                                                    <strong>Phone:</strong> {{ $order->phone }}<br>
                                                                    <strong>Address:</strong> {{ $order->address }}
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <strong>Order Date:</strong> {{ \Carbon\Carbon::parse($order->created_at)->format('M d, Y h:i A') }}<br>
                                                                    <strong>Payment Status:</strong> 
                                                                    @if($order->payment_status == 'Paid')
                                                                        <span class="badge bg-success">Paid</span>
                                                                    @else
                                                                        <span class="badge bg-warning text-dark">{{ $order->payment_status }}</span>
                                                                    @endif
                                                                    <br>
                                                                    <strong>Delivery Status:</strong> 
                                                                    <span class="badge bg-info text-dark">{{ $order->delivery_status }}</span>
                                                                </div>
                                                            </div>
                                                            
                                                            <h6 class="border-bottom pb-2">Order Items</h6>
                                                            @php
                                                                $orderItems = DB::table('order_item')
                                                                    ->join('products', 'order_item.product_id', '=', 'products.id')
                                                                    ->where('order_item.order_id', $order->id)
                                                                    ->select('products.name', 'order_item.quantity', 'order_item.price')
                                                                    ->get();
                                                            @endphp
                                                            
                                                            <table class="table table-sm">
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
                                                                        <th colspan="3" class="text-end">Total:</th>
                                                                        <th>₱{{ number_format($order->total, 2) }}</th>
                                                                    </tr>
                                                                </tfoot>
                                                            </table>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-shopping-bag fa-4x text-muted mb-3"></i>
                                <h5 class="text-muted">No Purchase History</h5>
                                <p class="text-muted">You haven't made any purchases yet.</p>
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
    </style>
</body>
</html>