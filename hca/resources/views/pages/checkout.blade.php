<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hookcraft Avenue - Checkout</title>
    <link rel="icon" href="{{ asset('asset/images/logo.jpg') }}" type="image/png">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('asset/stylescheckout.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/stylesnav.css') }}">
    <style>
        .product-image { width: 60px; height: 60px; object-fit: cover; border-radius: 8px; }
        body { background-color: #f8f9fa; }
        .card { border: none; box-shadow: 0 4px 6px rgba(0,0,0,0.1); border-radius: 12px; }
        .card-header { background: linear-gradient(135deg, #007bff, #0056b3); color: white; border-radius: 12px 12px 0 0 !important; }
        .form-control { border-radius: 8px; border: 2px solid #e9ecef; }
        .form-control:focus { border-color: #007bff; box-shadow: 0 0 0 0.2rem rgba(0,123,255,0.25); }
        .btn { border-radius: 8px; }
        .product-item { background: #f8f9fa; padding: 15px; border-radius: 10px; margin-bottom: 10px; }
        .total-highlight { background: linear-gradient(135deg, #28a745, #20c997); color: white; padding: 20px; border-radius: 10px; }
    </style>
</head>
<body>

@include('components.login_modal')
@include('components.signup_modal')

<!-- Navbar -->
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
                        <a class="nav-link dropdown-toggle p-0 border-0 bg-transparent" href="#" data-bs-toggle="dropdown">
                            <img src="{{ asset('asset/images/default-profile.png') }}" alt="Profile" width="40" height="40" class="rounded-circle">
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end mt-2 p-3 text-center" style="min-width:220px;">
                            <li class="mb-3">
                                <img src="{{ asset('asset/images/default-profile.png') }}" alt="Profile" width="60" height="60" class="rounded-circle shadow">
                            </li>
                            <li class="fw-bold mb-2">{{ $user->name }}</li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button class="dropdown-item text-danger fw-medium">
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
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#signupModal">Sign Up</a>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>

<!-- Checkout Section -->
<div class="container my-5">
    <div class="d-flex align-items-center mb-4">
        <i class="bi bi-credit-card text-primary fs-3 me-3"></i>
        <h2 class="mb-0">Checkout</h2>
    </div>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($cartItems->isEmpty())
        <div class="alert alert-info text-center p-4">
            <i class="bi bi-cart-x fs-1 text-muted mb-3"></i>
            <h5>Your cart is empty</h5>
            <a href="{{ route('shop') }}" class="btn btn-primary mt-2">Continue Shopping</a>
        </div>
    @else
    <div class="row">
        <!-- Customer Form -->
        <div class="col-lg-7">
            <form method="POST" action="{{ route('checkout.store') }}">
                @csrf
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-person-circle me-2"></i>Customer Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Full Name *</label>
                            <input type="text" class="form-control" name="name" value="{{ old('name') }}" placeholder="Enter your full name" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold"><i class="bi bi-geo-alt me-1"></i>Delivery Address *</label>
                            <div class="row g-2">
                                <div class="col-md-4"><input type="text" class="form-control" name="region" placeholder="Region" required></div>
                                <div class="col-md-4"><input type="text" class="form-control" name="province" placeholder="Province" required></div>
                                <div class="col-md-4"><input type="text" class="form-control" name="city" placeholder="City" required></div>
                                <div class="col-md-6"><input type="text" class="form-control" name="barangay" placeholder="Barangay" required></div>
                                <div class="col-md-6"><input type="text" class="form-control" name="street" placeholder="Street / House No." required></div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold"><i class="bi bi-phone me-1"></i>Phone Number *</label>
                            <input type="tel" class="form-control" name="phone" value="{{ old('phone') }}" placeholder="09123456789" pattern="[0-9]{11}" maxlength="11" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold"><i class="bi bi-wallet2 me-1"></i>Payment Method</label>
                            <div class="card p-3" style="background: #e3f2fd;">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="payment_method" value="GCash" checked>
                                    <label class="form-check-label">
                                        <strong>GCash (Manual Payment)</strong>
                                        <small class="d-block text-muted">Pay via GCash transfer</small>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </div>

        <!-- Order Summary -->
        <div class="col-lg-5">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-receipt me-2"></i>Order Summary</h5>
                </div>
                <div class="card-body">
                    @foreach($cartItems as $item)
                        <div class="product-item">
                            <div class="d-flex align-items-center">
                                <img src="{{ asset('asset/images/' . $item->product->image) }}" 
                                     alt="{{ $item->product->name }}" 
                                     class="product-image me-3">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">{{ $item->product->name }}</h6>
                                    <small class="text-muted">Qty: {{ $item->quantity }} × ₱{{ number_format($item->product->price, 2) }}</small>
                                </div>
                                <div><strong class="text-success">₱{{ number_format($item->quantity * $item->product->price, 2) }}</strong></div>
                            </div>
                        </div>
                    @endforeach

                    <div class="total-highlight text-center mt-3">
                        <h5 class="mb-0">Total: ₱{{ number_format($total, 2) }}</h5>
                    </div>

                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" class="btn btn-success btn-lg fw-semibold">
                            <i class="bi bi-shield-check me-2"></i>Place Order
                        </button>
                        <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-2"></i>Back to Cart
                        </a>
                    </div>
                </div>
            </div>
        </div>
        </form>
    </div>
    @endif
</div>

<!-- Footer -->
<footer class="footer py-4 text-center bg-dark text-white">
    <div class="container">
        <div class="row">
            <div class="col-md-6 text-md-start">
                <p class="mb-0">&copy; 2025 Hookcraft Avenue. All rights reserved.</p>
            </div>
            <div class="col-md-6 text-md-end">
                <div class="social-links">
                    <a href="#" class="text-white me-3"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="text-white me-3"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="text-white"><i class="bi bi-twitter"></i></a>
                </div>
            </div>
        </div>
    </div>
</footer>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>