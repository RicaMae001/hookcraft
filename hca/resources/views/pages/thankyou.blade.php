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
                            {{ $cartCount ?? 0 }}
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

<!-- Thank You Section -->
<section class="thankyou py-5">
    <div class="container text-center">
        <div class="card shadow-lg p-5 rounded-4">
            <div class="mb-4 text-success">
                <i class="bi bi-check-circle-fill display-1"></i>
            </div>
            <h1 class="mb-3">Thank You for Your Order!</h1>
            <p class="lead">Your order <strong>#{{ $order_id ?? '' }}</strong> has been placed successfully.</p>
            <p class="text-muted">We’ll contact you soon to confirm your delivery details.</p>
            <a href="{{ route('shop') }}" class="btn btn-primary mt-4">
                <i class="bi bi-shop me-2"></i>Continue Shopping
            </a>
        </div>
    </div>
</section>

<!-- Footer -->
@include('components.footer')

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
