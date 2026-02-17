<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Hookcraft Avenue - Cart</title>
    <link rel="icon" href="{{ asset('asset/images/logo.jpg') }}" type="image/png">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('asset/stylescart.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/stylesnav.css') }}">
    <style>
        :root {
            --primary-pink: #ec4899;
            --primary-pink-dark: #db2777;
            --primary-pink-light: #f9a8d4;
            --accent-rose: #fb7185;
            --secondary-gray: #64748b;
            --dark-navy: #1e293b;
            --light-gray: #f1f5f9;
            --border-color: #e5e7eb;
            --success-green: #10b981;
            --danger-red: #ef4444;
            --white: #ffffff;
            --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.08);
            --shadow-md: 0 4px 12px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 30px rgba(0, 0, 0, 0.12);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #fdf2f8 0%, #f8fafc 100%);
            color: var(--dark-navy);
            line-height: 1.6;
            min-height: 100vh;
        }

        /* Container */
        .cart-wrapper {
            max-width: 1200px;
            margin: 0 auto;
            padding: 1.5rem 0.5rem;
        }

        /* Page Header */
        .page-header {
            text-align: center;
            margin-bottom: 10px;
            padding: 0.5rem 0;
        }

        .page-header h1 {
            font-size: clamp(1.75rem, 4vw, 2.5rem);
            font-weight: 700;
            color: var(--dark-navy);
            margin-bottom: 10px;
            letter-spacing: -0.02em;
        }

        .page-header .subtitle {
            color: var(--secondary-gray);
            font-size: 1rem;
            font-weight: 400;
        }

        .item-count {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: linear-gradient(135deg, var(--primary-pink), var(--accent-rose));
            color: white;
            padding: 0.4rem 1rem;
            border-radius: 50px;
            font-size: 0.875rem;
            font-weight: 600;
            margin-top: 0.75rem;
        }

        /* Alert Messages */
        .alert-success {
            background: linear-gradient(135deg, #d1fae5, #a7f3d0);
            border: none;
            border-left: 4px solid var(--success-green);
            border-radius: 12px;
            color: #065f46;
            padding: 1rem 1.25rem;
            margin-bottom: 1.5rem;
            box-shadow: var(--shadow-sm);
        }

        /* Main Cart Layout */
        .cart-layout {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }

        @media (min-width: 992px) {
            .cart-layout {
                grid-template-columns: 1fr 400px;
                gap: 2rem;
            }
        }

        /* Cart Items Container */
        .cart-items-container {
            background: white;
            border-radius: 16px;
            box-shadow: var(--shadow-md);
            overflow: hidden;
        }

        .cart-items-header {
            background: var(--light-gray);
            padding: 1.25rem 1.5rem;
            border-bottom: 2px solid var(--border-color);
        }

        .cart-items-header h2 {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--dark-navy);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* Individual Cart Item */
        .cart-item {
            padding: 1.5rem;
            border-bottom: 1px solid var(--border-color);
            transition: var(--transition);
        }

        .cart-item:hover {
            background: #fafafa;
        }

        .cart-item:last-child {
            border-bottom: none;
        }

        .item-content {
            display: grid;
            grid-template-columns: 80px 1fr;
            gap: 1.25rem;
            align-items: start;
        }

        @media (min-width: 768px) {
            .item-content {
                grid-template-columns: 100px 1fr auto;
                align-items: center;
            }
        }

        .product-image-wrapper {
            position: relative;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: var(--shadow-sm);
            aspect-ratio: 1;
        }

        .product-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.4s ease;
        }

        .product-image:hover {
            transform: scale(1.08);
        }

        .item-details {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .product-name {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--dark-navy);
            margin: 0;
            line-height: 1.4;
        }

        .product-category {
            display: inline-flex;
            align-items: center;
            background: linear-gradient(135deg, #fce7f3, #fbcfe8);
            color: var(--primary-pink-dark);
            padding: 0.25rem 0.75rem;
            border-radius: 6px;
            font-size: 0.8rem;
            font-weight: 500;
            width: fit-content;
        }

        .price-info {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-top: 0.5rem;
        }

        .unit-price {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--primary-pink);
        }

        /* Quantity Controls */
        .item-actions {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            align-items: flex-end;
            margin-top: 1rem;
        }

        @media (min-width: 768px) {
            .item-actions {
                margin-top: 0;
                align-items: flex-end;
            }
        }

        .quantity-control {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            background: var(--light-gray);
            padding: 0.4rem;
            border-radius: 10px;
            border: 1px solid var(--border-color);
        }

        .quantity-btn {
            width: 32px;
            height: 32px;
            border: none;
            background: white;
            color: var(--dark-navy);
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: var(--transition);
            font-size: 1rem;
            font-weight: 600;
        }

        .quantity-btn:hover {
            background: var(--primary-pink);
            color: white;
            transform: scale(1.05);
        }

        .quantity-btn:active {
            transform: scale(0.95);
        }

        .quantity-input {
            width: 50px;
            text-align: center;
            border: none;
            background: transparent;
            font-weight: 600;
            font-size: 1rem;
            color: var(--dark-navy);
        }

        .quantity-input:focus {
            outline: none;
        }

        .item-subtotal {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--dark-navy);
            text-align: right;
        }

        .remove-btn {
            background: transparent;
            border: 1px solid var(--danger-red);
            color: var(--danger-red);
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: var(--transition);
        }

        .remove-btn:hover {
            background: var(--danger-red);
            color: white;
            transform: scale(1.05);
        }

        /* Order Summary */
        .order-summary {
            background: white;
            border-radius: 16px;
            box-shadow: var(--shadow-md);
            padding: 1.75rem;
            position: sticky;
            top: 2rem;
            height: fit-content;
        }

        .summary-header {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--dark-navy);
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid var(--border-color);
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0;
            font-size: 0.95rem;
        }

        .summary-row.total {
            margin-top: 1rem;
            padding-top: 1.5rem;
            border-top: 2px solid var(--border-color);
        }

        .summary-label {
            color: var(--secondary-gray);
            font-weight: 500;
        }

        .summary-value {
            font-weight: 600;
            color: var(--dark-navy);
        }

        .summary-value.free {
            color: var(--success-green);
        }

        .total-label {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--dark-navy);
        }

        .total-amount {
            font-size: 1.75rem;
            font-weight: 800;
            background: linear-gradient(135deg, var(--primary-pink), var(--accent-rose));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Buttons */
        .btn-checkout {
            width: 100%;
            background: linear-gradient(135deg, var(--primary-pink), var(--accent-rose));
            color: white;
            border: none;
            padding: 1rem 1.5rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 1.5rem;
            cursor: pointer;
            transition: var(--transition);
            box-shadow: 0 4px 14px rgba(236, 72, 153, 0.3);
        }

        .btn-checkout:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(236, 72, 153, 0.4);
        }

        .btn-checkout:active {
            transform: translateY(0);
        }

        .btn-continue {
            width: 100%;
            background: transparent;
            color: var(--secondary-gray);
            border: 2px solid var(--border-color);
            padding: 0.875rem 1.5rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 1rem;
            cursor: pointer;
            transition: var(--transition);
        }

        .btn-continue:hover {
            background: var(--light-gray);
            border-color: var(--secondary-gray);
            color: var(--dark-navy);
        }

        /* Security Badges */
        .security-badges {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--border-color);
        }

        .badge-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--secondary-gray);
            font-size: 0.875rem;
        }

        .badge-item i {
            color: var(--success-green);
            font-size: 1.1rem;
        }

        /* Empty Cart */
        .empty-cart {
            background: white;
            border-radius: 16px;
            padding: 4rem 2rem;
            text-align: center;
            box-shadow: var(--shadow-md);
        }

        .empty-cart-icon {
            width: 120px;
            height: 120px;
            margin: 0 auto 1.5rem;
            background: linear-gradient(135deg, #fce7f3, #fbcfe8);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .empty-cart-icon i {
            font-size: 3.5rem;
            color: var(--primary-pink);
        }

        .empty-cart h2 {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--dark-navy);
            margin-bottom: 0.75rem;
        }

        .empty-cart p {
            color: var(--secondary-gray);
            font-size: 1rem;
            margin-bottom: 2rem;
            max-width: 400px;
            margin-left: auto;
            margin-right: auto;
        }

        .btn-shop {
            background: linear-gradient(135deg, var(--primary-pink), var(--accent-rose));
            color: white;
            border: none;
            padding: 1rem 2.5rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            transition: var(--transition);
            box-shadow: 0 4px 14px rgba(236, 72, 153, 0.3);
        }

        .btn-shop:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(236, 72, 153, 0.4);
            color: white;
            text-decoration: none;
        }

        /* Loading Overlay */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(30, 41, 59, 0.7);
            backdrop-filter: blur(4px);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }

        .loading-spinner {
            width: 50px;
            height: 50px;
            border: 4px solid rgba(255, 255, 255, 0.3);
            border-top-color: var(--primary-pink);
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Mobile Responsive */
        @media (max-width: 767px) {
            .cart-wrapper {
                padding: 1rem 0.75rem;
            }

            .page-header {
                margin-bottom: 1.5rem;
            }

            .cart-item {
                padding: 1rem;
            }

            .item-content {
                grid-template-columns: 70px 1fr;
                gap: 1rem;
            }

            .product-name {
                font-size: 1rem;
            }

            .item-actions {
                grid-column: 1 / -1;
                flex-direction: row;
                justify-content: space-between;
                align-items: center;
                width: 100%;
            }

            .item-subtotal {
                text-align: left;
                font-size: 1.15rem;
            }

            .order-summary {
                position: static;
                margin-top: 1.5rem;
            }

            .empty-cart {
                padding: 3rem 1.5rem;
            }

            .empty-cart-icon {
                width: 100px;
                height: 100px;
            }
        }

        /* Print Styles */
        @media print {
            body {
                background: white;
            }

            .btn-checkout,
            .btn-continue,
            .remove-btn,
            .quantity-control {
                display: none;
            }
        }
    </style>
</head>
<body>

@include('components.login_modal')
@include('components.signup_modal')

<!-- Navbar -->
@include('components.navbar')

<!-- Cart Section -->
<div class="cart-wrapper">
    <!-- Page Header -->
    <div class="page-header">
        <h1>Shopping Cart</h1>
        @if(!$cartItems->isEmpty())
            <span class="item-count">
                <i class="bi bi-bag-check-fill"></i>
                {{ $cartItems->count() }} {{ $cartItems->count() === 1 ? 'item' : 'items' }}
            </span>
        @endif
    </div>

    <!-- Success Alert -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($cartItems->isEmpty())
        <!-- Empty Cart State -->
        <div class="empty-cart">
            <div class="empty-cart-icon">
                <i class="bi bi-cart-x"></i>
            </div>
            <h2>Your cart is empty</h2>
            <p>Looks like you haven't added anything to your cart yet. Start shopping to fill it up!</p>
            <a href="{{ route('shop') }}" class="btn btn-shop">
                <i class="bi bi-bag-heart-fill"></i>
                Start Shopping
            </a>
        </div>
    @else
        <!-- Cart Layout -->
        <div class="cart-layout">
            <!-- Cart Items -->
            <div class="cart-items-container">
                <div class="cart-items-header">
                    <h2>
                        <i class="bi bi-cart3"></i>
                        Your Items
                    </h2>
                </div>

                @foreach($cartItems as $item)
                    <div class="cart-item" id="cart-item-{{ $item->id }}">
                        <div class="item-content">
                            <!-- Product Image -->
                            <div class="product-image-wrapper">
                                <img src="{{ asset('asset/images/' . $item->product->image) }}" 
                                     alt="{{ $item->product->name }}" 
                                     class="product-image">
                            </div>

                            <!-- Product Details -->
                            <div class="item-details">
                                <h3 class="product-name">{{ $item->product->name }}</h3>
                                <span class="product-category">
                                    <i class="bi bi-tag-fill" style="font-size: 0.7rem;"></i>
                                    {{ $item->product->category->name }}
                                </span>
                                <div class="price-info">
                                    <span class="unit-price">₱{{ number_format($item->price, 2) }}</span>
                                    <span style="color: var(--secondary-gray); font-size: 0.875rem;">per item</span>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="item-actions">
                                <!-- Quantity Control -->
                                <div class="quantity-control">
                                    <button class="quantity-btn" onclick="updateQuantity({{ $item->id }}, {{ $item->quantity - 1 }})" type="button">
                                        <i class="bi bi-dash"></i>
                                    </button>
                                    <!-- FIXED: Removed @method('POST') and added onsubmit event -->
                                    <form action="{{ route('cart.update', $item->id) }}" method="POST" class="d-inline quantity-form-{{ $item->id }}" onsubmit="event.preventDefault(); updateQuantityByForm({{ $item->id }})">
                                        @csrf
                                        <input type="number" 
                                               name="quantity" 
                                               value="{{ $item->quantity }}" 
                                               min="1" 
                                               class="quantity-input"
                                               onchange="updateQuantityByInput({{ $item->id }})"
                                               readonly>
                                    </form>
                                    <button class="quantity-btn" onclick="updateQuantity({{ $item->id }}, {{ $item->quantity + 1 }})" type="button">
                                        <i class="bi bi-plus"></i>
                                    </button>
                                </div>

                                <!-- Subtotal & Remove -->
                                <div class="d-flex align-items-center gap-3">
                                    <div class="item-subtotal">₱{{ number_format($item->subtotal, 2) }}</div>
                                    <form action="{{ route('cart.delete', $item->id) }}" method="POST" class="remove-form">
                                        @csrf @method('DELETE')
                                        <button class="remove-btn" type="button" onclick="confirmRemove(this)" title="Remove item">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Order Summary -->
            <div class="order-summary">
                <h2 class="summary-header">Order Summary</h2>

                <div class="summary-row">
                    <span class="summary-label">Subtotal ({{ $cartItems->sum('quantity') }} items)</span>
                    <span class="summary-value">₱{{ number_format($cartItems->sum('subtotal'), 2) }}</span>
                </div>

                <div class="summary-row">
                    <span class="summary-label">Shipping</span>
                    <span class="summary-value free">Free</span>
                </div>

                <div class="summary-row">
                    <span class="summary-label">Tax</span>
                    <span class="summary-value">₱0.00</span>
                </div>

                <div class="summary-row total">
                    <span class="total-label">Total</span>
                    <span class="total-amount">₱{{ number_format($cartItems->sum('subtotal'), 2) }}</span>
                </div>

                <a href="{{ route('checkout.index') }}" class="btn btn-checkout">
                    <i class="bi bi-lock-fill"></i>
                    Proceed to Checkout
                    <i class="bi bi-arrow-right"></i>
                </a>

                <a href="{{ route('shop') }}" class="btn btn-continue">
                    <i class="bi bi-arrow-left"></i>
                    Continue Shopping
                </a>

                <div class="security-badges">
                    <div class="badge-item">
                        <i class="bi bi-shield-fill-check"></i>
                        <span>Secure checkout guaranteed</span>
                    </div>
                    <div class="badge-item">
                        <i class="bi bi-truck"></i>
                        <span>Free shipping nationwide</span>
                    </div>
                    <div class="badge-item">
                        <i class="bi bi-arrow-return-left"></i>
                        <span>Easy 30-day returns</span>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Footer -->
@include('components.footer')

<!-- Loading Overlay -->
<div class="loading-overlay" id="loadingOverlay">
    <div class="loading-spinner"></div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script>
    // Update quantity using plus/minus buttons
    function updateQuantity(itemId, newQuantity) {
        if (newQuantity < 1) return;
        
        const form = document.querySelector(`.quantity-form-${itemId}`);
        const input = form.querySelector('input[name="quantity"]');
        input.value = newQuantity;
        
        // Show loading overlay
        document.getElementById('loadingOverlay').style.display = 'flex';
        
        // Submit the form via AJAX
        fetch(form.action, {
            method: 'POST',
            body: new FormData(form),
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            // Hide loading overlay
            document.getElementById('loadingOverlay').style.display = 'none';
            
            if (data.success) {
                // Reload the page to show updated quantities and totals
                window.location.reload();
            } else {
                alert('Error updating quantity: ' + (data.message || 'Unknown error'));
                window.location.reload();
            }
        })
        .catch(error => {
            document.getElementById('loadingOverlay').style.display = 'none';
            // console.error('Error:', error); // Removed console.log
            alert('Error updating quantity. Please try again.');
            window.location.reload();
        });
    }

    // Update quantity when input is changed directly
    function updateQuantityByInput(itemId) {
        const form = document.querySelector(`.quantity-form-${itemId}`);
        const input = form.querySelector('input[name="quantity"]');
        const newQuantity = parseInt(input.value);
        
        if (newQuantity < 1) {
            input.value = 1;
            return;
        }
        
        // Show loading overlay
        document.getElementById('loadingOverlay').style.display = 'flex';
        
        // Submit the form via AJAX
        fetch(form.action, {
            method: 'POST',
            body: new FormData(form),
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            // Hide loading overlay
            document.getElementById('loadingOverlay').style.display = 'none';
            
            if (data.success) {
                // Reload the page to show updated quantities and totals
                window.location.reload();
            } else {
                alert('Error updating quantity: ' + (data.message || 'Unknown error'));
                window.location.reload();
            }
        })
        .catch(error => {
            document.getElementById('loadingOverlay').style.display = 'none';
            // console.error('Error:', error); // Removed console.log
            alert('Error updating quantity. Please try again.');
            window.location.reload();
        });
    }

    // Update quantity when form is submitted
    function updateQuantityByForm(itemId) {
        const form = document.querySelector(`.quantity-form-${itemId}`);
        const input = form.querySelector('input[name="quantity"]');
        const newQuantity = parseInt(input.value);
        
        if (newQuantity < 1) {
            input.value = 1;
            return;
        }
        
        // Show loading overlay
        document.getElementById('loadingOverlay').style.display = 'flex';
        
        // Submit the form via AJAX
        fetch(form.action, {
            method: 'POST',
            body: new FormData(form),
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            // Hide loading overlay
            document.getElementById('loadingOverlay').style.display = 'none';
            
            if (data.success) {
                // Reload the page to show updated quantities and totals
                window.location.reload();
            } else {
                alert('Error updating quantity: ' + (data.message || 'Unknown error'));
                window.location.reload();
            }
        })
        .catch(error => {
            document.getElementById('loadingOverlay').style.display = 'none';
            // console.error('Error:', error); // Removed console.log
            alert('Error updating quantity. Please try again.');
            window.location.reload();
        });
    }

    // Confirm remove with confirmation
    function confirmRemove(button) {
        const form = button.closest('.remove-form');
        const productName = button.closest('.cart-item').querySelector('.product-name').textContent;
        
        if (confirm(`Remove "${productName}" from your cart?`)) {
            button.innerHTML = '<i class="bi bi-hourglass-split"></i>';
            button.disabled = true;
            
            // Show loading overlay
            document.getElementById('loadingOverlay').style.display = 'flex';
            
            // Submit via AJAX
            fetch(form.action, {
                method: 'POST',
                body: new FormData(form),
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.text();
            })
            .then(html => {
                // Hide loading overlay and reload page
                document.getElementById('loadingOverlay').style.display = 'none';
                window.location.reload();
            })
            .catch(error => {
                document.getElementById('loadingOverlay').style.display = 'none';
                // console.error('Error:', error); // Removed console.log
                alert('Error removing item. Please try again.');
                window.location.reload();
            });
        }
    }

    // Auto-dismiss alerts
    document.addEventListener('DOMContentLoaded', function() {
        const alerts = document.querySelectorAll('.alert-dismissible');
        alerts.forEach(alert => {
            setTimeout(() => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }, 5000);
        });

        // Smooth scroll to top on page load
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });

    // Prevent double submission
    let isSubmitting = false;
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function(e) {
            if (isSubmitting) {
                e.preventDefault();
                return false;
            }
            isSubmitting = true;
            document.getElementById('loadingOverlay').style.display = 'flex';
            
            setTimeout(() => {
                isSubmitting = false;
            }, 2000);
        });
    });
</script>
</body>
</html>