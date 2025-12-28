<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hookcraft Avenue - Cart</title>
    <link rel="icon" href="{{ asset('asset/images/logo.jpg') }}" type="image/png">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('asset/stylescart.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/stylesnav.css') }}">
    <style>
        :root {
            --primary-color: #2563eb;
            --primary-hover: #1d4ed8;
            --secondary-color: #64748b;
            --success-color: #10b981;
            --danger-color: #ef4444;
            --warning-color: #f59e0b;
            --light-bg: #f8fafc;
            --border-color: #e2e8f0;
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
        }

        body {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            min-height: 100vh;
        }

        .cart-container {
            background: white;
            border-radius: 16px;
            box-shadow: var(--shadow-lg);
            border: 1px solid var(--border-color);
            overflow: hidden;
            margin-top: 2rem;
        }

        .cart-header {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-hover));
            color: white;
            padding: 2rem;
            text-align: center;
        }

        .cart-header h2 {
            margin: 0;
            font-weight: 700;
            font-size: 2rem;
        }

        .cart-item {
            border-bottom: 1px solid var(--border-color);
            padding: 1.5rem;
            transition: all 0.3s ease;
        }

        .cart-item:hover {
            background-color: #f8fafc;
            transform: translateY(-1px);
        }

        .cart-item:last-child {
            border-bottom: none;
        }

        .product-image {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 12px;
            box-shadow: var(--shadow-md);
            transition: transform 0.3s ease;
        }

        .product-image:hover {
            transform: scale(1.05);
        }

        .product-info h6 {
            color: #1e293b;
            font-weight: 600;
            margin-bottom: 0.25rem;
        }

        .product-category {
            color: var(--secondary-color);
            font-size: 0.875rem;
            background: #f1f5f9;
            padding: 0.25rem 0.5rem;
            border-radius: 6px;
            display: inline-block;
        }

        .price-display {
            font-weight: 600;
            font-size: 1.1rem;
            color: var(--primary-color);
        }

        .quantity-input {
            border: 2px solid var(--border-color);
            border-radius: 8px;
            width: 80px;
            text-align: center;
            font-weight: 600;
            transition: border-color 0.3s ease;
        }

        .quantity-input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .subtotal {
            font-weight: 700;
            font-size: 1.2rem;
            color: #1e293b;
        }

        .remove-btn {
            border: 2px solid var(--danger-color);
            color: var(--danger-color);
            border-radius: 8px;
            padding: 0.5rem;
            transition: all 0.3s ease;
        }

        .remove-btn:hover {
            background-color: var(--danger-color);
            color: white;
            transform: scale(1.05);
        }

        .cart-total {
            background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
            padding: 2rem;
            border-top: 3px solid var(--primary-color);
        }

        .total-amount {
            font-size: 2rem;
            font-weight: 800;
            color: var(--primary-color);
        }

        .action-buttons {
            padding: 2rem;
            background: white;
        }

        .btn-continue {
            background: white;
            border: 2px solid var(--secondary-color);
            color: var(--secondary-color);
            padding: 0.75rem 2rem;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-continue:hover {
            background: var(--secondary-color);
            color: white;
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .btn-checkout {
            background: linear-gradient(135deg, var(--success-color), #059669);
            border: none;
            color: white;
            padding: 0.75rem 2rem;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-checkout:hover {
            background: linear-gradient(135deg, #059669, #047857);
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
            color: white;
        }

        .empty-cart {
            text-align: center;
            padding: 4rem 2rem;
        }

        .empty-cart-icon {
            font-size: 4rem;
            color: var(--secondary-color);
            margin-bottom: 1rem;
        }

        .empty-cart h4 {
            color: var(--secondary-color);
            margin-bottom: 1rem;
        }

        .empty-cart p {
            color: #64748b;
            margin-bottom: 2rem;
        }

        .btn-shop {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-hover));
            border: none;
            color: white;
            padding: 1rem 2rem;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-shop:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
            color: white;
        }

        /* Mobile optimizations */
        @media (max-width: 768px) {
            .cart-item {
                padding: 1rem;
            }
            
            .product-image {
                width: 60px;
                height: 60px;
            }
            
            .cart-header h2 {
                font-size: 1.5rem;
            }
            
            .total-amount {
                font-size: 1.5rem;
            }
            
            .action-buttons {
                flex-direction: column;
                gap: 1rem;
            }
            
            .btn-continue, .btn-checkout {
                width: 100%;
            }
        }

        /* Loading animation for quantity updates */
        .quantity-loading {
            position: relative;
            opacity: 0.7;
        }

        .quantity-loading::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 20px;
            height: 20px;
            margin: -10px 0 0 -10px;
            border: 2px solid var(--primary-color);
            border-radius: 50%;
            border-top-color: transparent;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Success message styling */
        .alert-success {
            border: none;
            background: linear-gradient(135deg, #dcfce7, #bbf7d0);
            color: #166534;
            border-radius: 12px;
            border-left: 4px solid var(--success-color);
        }

        /* Cart badge enhancement */
        .cart-badge {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: translate(-50%, -50%) scale(1); }
            50% { transform: translate(-50%, -50%) scale(1.1); }
        }
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

<!-- Cart Section -->
<div class="container py-4">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($cartItems->isEmpty())
        <div class="cart-container">
            <div class="empty-cart">
                <div class="empty-cart-icon">
                    <i class="bi bi-cart-x"></i>
                </div>
                <h4>Your cart is empty</h4>
                <p class="text-muted">Looks like you haven't added anything to your cart yet. Start shopping to fill it up!</p>
                <a href="{{ route('shop') }}" class="btn btn-shop">
                    <i class="bi bi-bag me-2"></i>Start Shopping
                </a>
            </div>
        </div>
    @else
        <div class="cart-container">
            <!-- Cart Header -->
            <div class="cart-header">
                <h2><i class="bi bi-cart3 me-2"></i>Shopping Cart</h2>
                <p class="mb-0 opacity-90">{{ $cartItems->count() }} {{ $cartItems->count() === 1 ? 'item' : 'items' }} in your cart</p>
            </div>

            <!-- Cart Items -->
            <div class="cart-items">
                @foreach($cartItems as $item)
                    <div class="cart-item" id="cart-item-{{ $item->id }}">
                        <div class="row align-items-center">
                            <!-- Product Image & Info -->
                            <div class="col-md-5">
                                <div class="d-flex align-items-center">
                                <img src="{{ asset('asset/images/' . $item->product->image) }}" 
     alt="{{ $item->product->name }}" 
     class="product-image me-3" width="60" height="60">


                                    <div class="product-info">
                                        <h6>{{ $item->product->name }}</h6>
                                        <span class="product-category">{{ $item->product->category->name }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Price -->
                            <div class="col-md-2 text-center">
                                <div class="price-display">₱{{ number_format($item->price, 2) }}</div>
                            </div>

                            <!-- Quantity -->
                            <div class="col-md-2 text-center">
                                <div class="d-flex align-items-center justify-content-center">
                                    <button class="btn btn-sm btn-outline-secondary me-2" onclick="updateQuantity({{ $item->id }}, {{ $item->quantity - 1 }})">
                                        <i class="bi bi-dash"></i>
                                    </button>
                                    <form action="{{ route('cart.update', $item->id) }}" method="POST" class="d-inline quantity-form-{{ $item->id }}">
                                        @csrf @method('PUT')
                                        <input type="number" 
                                               name="quantity" 
                                               value="{{ $item->quantity }}" 
                                               min="1" 
                                               class="form-control quantity-input"
                                               onchange="this.form.submit()">
                                    </form>
                                    <button class="btn btn-sm btn-outline-secondary ms-2" onclick="updateQuantity({{ $item->id }}, {{ $item->quantity + 1 }})">
                                        <i class="bi bi-plus"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Subtotal -->
                            <div class="col-md-2 text-center">
                                <div class="subtotal">₱{{ number_format($item->subtotal, 2) }}</div>
                            </div>

                            <!-- Remove Button -->
                            <div class="col-md-1 text-center">
                                <form action="{{ route('cart.delete', $item->id) }}" method="POST" class="remove-form">
                                    @csrf @method('DELETE')
                                    <button class="btn remove-btn" type="button" onclick="confirmRemove(this)" title="Remove item">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Cart Total -->
            <div class="cart-total">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal ({{ $cartItems->sum('quantity') }} items):</span>
                            <span class="fw-bold">₱{{ number_format($cartItems->sum('subtotal'), 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Shipping:</span>
                            <span class="text-success fw-bold">Free</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <span class="h5 fw-bold">Total:</span>
                            <span class="total-amount">₱{{ number_format($cartItems->sum('subtotal'), 2) }}</span>
                        </div>
                    </div>
                    <div class="col-md-4 text-end">
                        <div class="text-muted small mb-2">
                            <i class="bi bi-shield-check me-1"></i>Secure checkout
                        </div>
                        <div class="text-muted small">
                            <i class="bi bi-truck me-1"></i>Free shipping nationwide
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="action-buttons">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <a href="{{ route('shop') }}" class="btn btn-continue">
                        <i class="bi bi-arrow-left me-2"></i>Continue Shopping
                    </a>
                       </a>
                    <a href="{{ route('checkout.index') }}" class="btn btn-checkout">
                        <i class="bi bi-credit-card me-2"></i>Proceed to Checkout
                        <i class="bi bi-arrow-right ms-1"></i>
    </a> 
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Footer -->
@include('components.footer')

<!-- Loading overlay for better UX -->
<div id="loadingOverlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999;">
    <div class="d-flex justify-content-center align-items-center h-100">
        <div class="spinner-border text-light" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script>
    // Enhanced quantity update function
    function updateQuantity(itemId, newQuantity) {
        if (newQuantity < 1) return;
        
        const form = document.querySelector(`.quantity-form-${itemId}`);
        const input = form.querySelector('input[name="quantity"]');
        input.value = newQuantity;
        
        // Add loading state
        const cartItem = document.getElementById(`cart-item-${itemId}`);
        cartItem.classList.add('quantity-loading');
        
        form.submit();
    }

    // Confirm remove with better UX
    function confirmRemove(button) {
        const form = button.closest('.remove-form');
        const productName = button.closest('.cart-item').querySelector('h6').textContent;
        
        if (confirm(`Are you sure you want to remove "${productName}" from your cart?`)) {
            // Show loading
            button.innerHTML = '<i class="bi bi-hourglass-split"></i>';
            button.disabled = true;
            
            setTimeout(() => {
                form.submit();
            }, 300);
        }
    }

    // Auto-dismiss alerts after 5 seconds
    document.addEventListener('DOMContentLoaded', function() {
        const alerts = document.querySelectorAll('.alert-dismissible');
        alerts.forEach(alert => {
            setTimeout(() => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }, 5000);
        });
    });

    // Add loading overlay for form submissions
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function() {
            document.getElementById('loadingOverlay').style.display = 'block';
        });
    });

    // Prevent multiple rapid submissions
    let isSubmitting = false;
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function(e) {
            if (isSubmitting) {
                e.preventDefault();
                return false;
            }
            isSubmitting = true;
            
            setTimeout(() => {
                isSubmitting = false;
            }, 2000);
        });
    });
</script>
</body>
</html>