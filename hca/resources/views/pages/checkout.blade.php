<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hookcraft Avenue - Secure Checkout</title>
    <link rel="icon" href="{{ asset('asset/images/logo.jpg') }}" type="image/png">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <!-- <link rel="stylesheet" href="{{ asset('asset/stylesnav.css') }}"> -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        :root {
            --primary-pink: #d63384;
            --primary-pink-dark: #c2185b;
            --primary-pink-light: #f8bbd9;
            --accent-rose: #e91e63;
            --secondary-gray: #6c757d;
            --dark-navy: #212529;
            --light-gray: #f8f9fa;
            --border-color: #dee2e6;
            --success-green: #198754;
            --danger-red: #dc3545;
            --white: #ffffff;
            --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.07);
            --shadow-lg: 0 10px 25px rgba(0, 0, 0, 0.1);
            --transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            --border-radius: 12px;
        }

        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            background-color: #f9f9f9;
            color: var(--dark-navy);
            line-height: 1.6;
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            }

        /* Page Header */
        .page-header {
            text-align: center;
            margin-bottom: 2rem;
            padding-top: 1rem;
        }

        .page-header h2 {
            font-size: 2rem;
            font-weight: 700;
            color: var(--dark-navy);
            margin-bottom: 0.75rem;
            letter-spacing: -0.025em;
        }

        .page-header .subtitle {
            color: var(--secondary-gray);
            font-size: 1rem;
            font-weight: 400;
            max-width: 600px;
            margin: 0 auto;
        }

        /* Cards */
        .card {
            border: none;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-md);
            overflow: hidden;
            background: var(--white);
            transition: var(--transition);
            margin-bottom: 1rem;
        }

        .card:hover {
            box-shadow: var(--shadow-lg);
        }

        .card-header {
            background: linear-gradient(135deg, #f8f9fa, #ffffff);
            color: var(--dark-navy);
            padding: 1.25rem 1.5rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            border-bottom: 1px solid var(--border-color);
            font-size: 1rem;
        }

        .card-header i {
            color: var(--primary-pink);
        }

        .card-body {
            padding: 1.5rem;
        }

        /* User Info - ONE LINE WITH LABELS */
        .user-info-one-line {
            background: linear-gradient(135deg, rgba(248, 249, 250, 0.8), rgba(255, 255, 255, 0.9));
            border: 1px solid rgba(0, 0, 0, 0.08);
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 1rem;
        }

        .user-info-one-line-content {
            display: flex;
            align-items: center;
            gap: 2rem;
        }

        .user-info-item {
            display: flex;
            align-items: baseline;
            gap: 0.5rem;
        }

        .user-info-label {
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--secondary-gray);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            min-width: 80px;
        }

        .user-info-value {
            font-size: 0.95rem;
            font-weight: 500;
            color: var(--dark-navy);
        }

        /* ============ FIXED RIGHT COLUMN STYLES ============ */
        .right-column {
            position: sticky;
            top: 2rem;
        }

        /* Order Summary - COMPACT AND TOGETHER */
        .order-summary-wrapper {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        /* Payment Options - COMPACT */
        .payment-options {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 0.5rem;
            margin-top: 0.25rem;
        }

        .payment-option {
            border: 2px solid var(--border-color);
            border-radius: 8px;
            padding: 0.75rem;
            cursor: pointer;
            transition: var(--transition);
            background: var(--white);
        }

        .payment-option:hover {
            border-color: var(--primary-pink);
            transform: translateY(-2px);
        }

        .payment-option.selected {
            border-color: var(--primary-pink);
            background: linear-gradient(135deg, rgba(214, 51, 132, 0.05), rgba(233, 30, 99, 0.05));
            box-shadow: 0 0 0 1px var(--primary-pink);
        }

        .payment-option .icon {
            width: 35px;
            height: 35px;
            border-radius: 8px;
            background: linear-gradient(135deg, var(--primary-pink), var(--accent-rose));
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            margin-bottom: 0.5rem;
        }

        .payment-option h6 {
            font-weight: 600;
            margin-bottom: 0.125rem;
            color: var(--dark-navy);
            font-size: 0.9rem;
        }

        .payment-option p {
            color: var(--secondary-gray);
            font-size: 0.75rem;
            margin: 0;
            line-height: 1.2;
        }

        /* Product Items - MORE COMPACT */
        .product-item {
            display: flex;
            align-items: center;
            padding: 0.5rem;
            border-bottom: 1px solid var(--border-color);
            transition: var(--transition);
        }

        .product-item:last-child {
            border-bottom: none;
        }

        .product-item:hover {
            background-color: #f8f9fa;
        }

        .product-image {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 6px;
            margin-right: 0.5rem;
            border: 1px solid var(--border-color);
        }

        .product-details {
            flex: 1;
        }

        .product-name {
            font-weight: 600;
            color: var(--dark-navy);
            margin-bottom: 0.25rem;
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            margin-bottom: 0.125rem;
            font-size: 0.85rem;
        }

        .product-meta {
            color: var(--secondary-gray);
            font-size: 0.75rem;
            margin-bottom: 0.125rem;
        }

        .product-price {
            font-weight: 700;
            color: var(--primary-pink);
            font-size: 0.9rem;
        }

        /* Category Badge */
        .category-badge {
            display: inline-block;
            background: linear-gradient(135deg, #e3f2fd, #bbdefb);
            color: #1565c0;
            padding: 3px 10px;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 500;
            margin-left: 8px;
            border: 1px solid #90caf9;
        }

        /* Custom Product Badge */
        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.75em;
            font-weight: 600;
            margin-left: 8px;
        }

        .badge.bg-warning {
            background: linear-gradient(135deg, #fef3c7, #fde68a) !important;
            color: #92400e !important;
            border: 1px solid #f59e0b;
        }

        /* Order Summary Sections - MORE COMPACT */
        .summary-section {
            padding: 0.5rem 0;
            border-bottom: 1px solid var(--border-color);
        }

        .summary-section:last-child {
            border-bottom: none;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.25rem;
            font-size: 0.85rem;
        }

        .summary-row.total {
            font-size: 1rem;
            font-weight: 700;
            color: var(--primary-pink);
            margin-top: 0.5rem;
            padding-top: 0.5rem;
            border-top: 2px solid var(--border-color);
        }

        /* Security Badge - COMPACT */
        .security-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            background: #e8f5e9;
            color: #2e7d32;
            padding: 0.25rem 0.5rem;
            border-radius: 12px;
            font-size: 0.7rem;
            margin-right: 0.25rem;
            margin-bottom: 0.25rem;
        }

        /* Buttons */
        .btn {
            border-radius: 8px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: var(--transition);
            border: none;
            font-size: 0.95rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-pink), var(--accent-rose));
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 20px rgba(214, 51, 132, 0.3);
        }

        .btn-lg {
            padding: 0.875rem 1.75rem;
            font-size: 1rem;
        }

        .btn-block {
            width: 100%;
            display: block;
        }

        /* COMPACT ORDER BUTTON */
        .compact-btn {
            padding: 0.625rem 1.25rem;
            font-size: 0.9rem;
        }

        /* Map - SMALLER */
        #map {
            height: 300px;
            width: 100%;
            border-radius: 8px;
            margin-bottom: 10px;
            border: 2px solid var(--border-color);
        }

        /* User Info - COMPACT */
        .user-info-display {
            background: linear-gradient(135deg, rgba(248, 231, 243, 0.4), rgba(248, 231, 243, 0.2));
            border: 1px solid rgba(214, 51, 132, 0.2);
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 1rem;
        }

        .user-info-title {
            font-size: 1rem;
            font-weight: 600;
            color: var(--dark-navy);
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* Form Elements - COMPACT */
        .form-group {
            margin-bottom: 1rem;
        }

        .form-label {
            font-weight: 600;
            color: var(--dark-navy);
            margin-bottom: 0.25rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.9rem;
        }

        .form-control, .form-select {
            border-radius: 8px;
            border: 1px solid var(--border-color);
            padding: 0.625rem 0.875rem;
            font-size: 0.9rem;
            transition: var(--transition);
            background: var(--white);
            color: var(--dark-navy);
        }

        /* ============ RESPONSIVE FIXES ============ */
        @media (max-width: 992px) {
            .right-column {
                position: static;
            }
            
            .order-summary-wrapper {
                gap: 0.75rem;
            }
            
            #map {
                height: 250px;
            }
        }

        @media (max-width: 768px) {
            .container {
                padding: 1rem;
            }

            .page-header {
                margin-bottom: 1.5rem;
            }

            .page-header h2 {
                font-size: 1.5rem;
            }

            .card-body {
                padding: 1rem;
            }

            .card-header {
                padding: 1rem 1.25rem;
            }

            .payment-options {
                grid-template-columns: 1fr;
                gap: 0.25rem;
            }

            .product-image {
                width: 50px;
                height: 50px;
            }
            
            #map {
                height: 200px;
            }
            
            /* Responsive for one line user info */
            .user-info-one-line-content {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }
            
            .user-info-item {
                width: 100%;
            }
        }

        @media (max-width: 576px) {
            .container {
                padding: 0.75rem;
            }
            
            .empty-state {
                padding: 2rem 0.75rem;
            }
            
            .card-header {
                padding: 0.75rem 1rem;
            }
            
            .user-info-one-line-content {
                gap: 0.75rem;
            }
        }
    </style>
</head>
<body>

@include('components.login_modal')
@include('components.signup_modal')
@include('components.navbar')

<div class="container">
    <!-- Page Header -->
    <div class="page-header">
        <h2>Secure Checkout</h2>
        <p class="subtitle">Complete your purchase with confidence - Your handmade treasures await</p>
    </div>

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-exclamation-triangle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($cartItems->isEmpty())
        <div class="empty-state">
            <div class="empty-icon">
                <i class="bi bi-bag-x"></i>
            </div>
            <h3 class="mb-3">Your cart is empty</h3>
            <p class="text-muted mb-4">Discover our unique handmade collection and add items to your cart</p>
            <a href="{{ route('shop') }}" class="btn btn-primary">
                <i class="bi bi-arrow-left me-2"></i>Start Shopping
            </a>
        </div>
    @else
    <div class="row g-4">
        <!-- Left Column: Form -->
        <div class="col-lg-8">
            <form method="POST" action="{{ route('checkout.store') }}" id="checkoutForm" class="checkout-form">
                @csrf
                
                <!-- Customer Information (Read-only) -->
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-person-circle"></i>
                        Your Information
                    </div>
                    <div class="card-body">
                        <div class="user-info-display">
                            <div class="user-info-title">
                                <i class="bi bi-person-check"></i>
                                Account Details
                            </div>
                            
                            <!-- ONE LINE FOR NAME AND EMAIL WITH LABELS -->
                            <div class="user-info-one-line">
                                <div class="user-info-one-line-content">
                                    <div class="user-info-item">
                                        <span class="user-info-label">FULL NAME:</span>
                                        <span class="user-info-value">{{ Auth::user()->name }}</span>
                                    </div>
                                    
                                    <div class="user-info-item">
                                        <span class="user-info-label">EMAIL:</span>
                                        <span class="user-info-value">{{ Auth::user()->email }}</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Status badge -->
                            <div class="mt-2 d-flex align-items-center">
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1 rounded-pill">
                                    <i class="bi bi-shield-check me-1"></i>
                                    Account Verified
                                </span>
                            </div>
                        </div>
                        
                        <!-- Hidden inputs to pass user data -->
                        <input type="hidden" name="name" value="{{ Auth::user()->name }}">
                        <input type="hidden" name="email" value="{{ Auth::user()->email }}">
                        
                        <!-- Phone number input -->
                        <div class="form-group mt-2">
                            <label class="form-label">
                                <i class="bi bi-phone"></i>
                                Phone Number *
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-telephone text-primary-pink"></i>
                                </span>
                                <input type="tel" class="form-control border-start-0" name="phone" value="{{ old('phone', Auth::user()->phone ?? '') }}" placeholder="09123456789" pattern="[0-9]{11}" maxlength="11" required>
                            </div>
                            <small class="text-muted mt-1 d-block">
                                <i class="bi bi-info-circle me-1"></i>
                                Format: 09123456789 - We'll use this for delivery updates
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Delivery Address -->
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-geo-alt"></i>
                        Delivery Address
                    </div>
                    <div class="card-body">
                        <div class="location-info">
                            <i class="bi bi-info-circle"></i>
                            <strong>Service Area:</strong> We currently deliver within Lapu-Lapu City only
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label class="form-label">City *</label>
                                <select class="form-select" id="citySelect" name="city_id" required>
                                    <option value="">Select City</option>
                                </select>
                            </div>
                            <div class="col-md-6 form-group">
                                <label class="form-label">Barangay *</label>
                                <select class="form-select" id="barangaySelect" name="barangay_id" disabled required>
                                    <option value="">Select city first</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">
                                <i class="bi bi-signpost"></i>
                                Street Address / House No. *
                            </label>
                            <input type="text" class="form-control" name="street" id="street" placeholder="e.g., 123 Main Street, Building Name, Floor/Unit" required>
                        </div>
                        
                        <!-- Map -->
                        <div class="form-group">
                            <label class="form-label">
                                <i class="bi bi-map"></i>
                                Location Map
                            </label>
                            <div id="map"></div>
                            <small class="text-muted">Click on the map or select barangay to mark your location (Lapu-Lapu City only)</small>
                        </div>
                        
                        <!-- Location Display -->
                        <div class="address-display" id="addressDisplay">
                            <div class="mb-2">
                                <small class="text-muted d-block mb-1">Selected Address:</small>
                                <span class="location-badge" id="selectedRegion">Central Visayas</span>
                                <span class="location-badge" id="selectedProvince">Cebu</span>
                                <span class="location-badge" id="selectedCity">-</span>
                                <span class="location-badge" id="selectedBarangay">-</span>
                            </div>
                        </div>
                        
                        <!-- Hidden Fields -->
                        <input type="hidden" name="region_id" id="regionId" value="1">
                        <input type="hidden" name="province_id" id="provinceId" value="1">
                        <input type="hidden" id="barangayId">
                        <input type="hidden" name="latitude" id="latitude">
                        <input type="hidden" name="longitude" id="longitude">
                    </div>
                </div>
            </form>
        </div>

        <!-- Right Column: Order Summary & Payment - FIXED POSITION -->
        <div class="col-lg-4">
    
            <div class="right-column">
                <div class="order-summary-wrapper">
                    <!-- Order Summary Card -->
                    <div class="card">
                        <div class="card-header">
                            <i class="bi bi-receipt"></i>
                            Order Summary
                        </div>
                        <div class="card-body">
                            <!-- Product List -->
                            <div class="summary-section">
                                <h6 class="mb-2" style="font-size: 0.9rem;">Items ({{ $cartItems->count() }})</h6>
                                @foreach($cartItems as $item)
                                    <div class="product-item">
                                        <img src="{{ asset('asset/images/' . $item->product->image) }}" alt="{{ $item->product->name }}" class="product-image">
                                        <div class="product-details">
                                            <div class="product-name">{{ $item->product->name }}</div>
                                            <div class="product-meta">
                                                {{ $item->quantity }} × ₱{{ number_format($item->product->price, 2) }}
                                            </div>
                                            <div class="product-price">
                                                ₱{{ number_format($item->quantity * $item->product->price, 2) }}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Price Breakdown -->
                            <div class="summary-section">
                                <div class="summary-row">
                                    <span>Subtotal</span>
                                    <span>₱{{ number_format($total, 2) }}</span>
                                </div>
                                <div class="summary-row">
                                    <span>Shipping</span>
                                    <span>Calculated at checkout</span>
                                </div>
                                <div class="summary-row">
                                    <span>Tax</span>
                                    <span>Included</span>
                                </div>
                                <div class="summary-row total">
                                    <span>Total</span>
                                    <span>₱{{ number_format($total, 2) }}</span>
                                </div>
                            </div>

                            <!-- Security & Support -->
                            <div class="summary-section">
                                <div class="d-flex flex-wrap mb-2">
                                    <span class="security-badge">
                                        <i class="bi bi-shield-check"></i>
                                        SSL Secure
                                    </span>
                                    <span class="security-badge" style="background: #e3f2fd; color: #1565c0;">
                                        <i class="bi bi-truck"></i>
                                        Fast Delivery
                                    </span>
                                </div>
                                
                                <div class="alert alert-info small mb-0 p-2">
                                    <i class="bi bi-info-circle me-2"></i>
                                    Need help? <a href="#" class="fw-bold">Contact Support</a>
                                </div>
                            </div>
                            
                            <!-- Continue Shopping -->
                            <div class="text-center mt-2">
                                <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary btn-sm compact-btn">
                                    <i class="bi bi-arrow-left me-1"></i>Back to Cart
                                </a>
                                <a href="{{ route('shop') }}" class="btn btn-outline-primary btn-sm ms-1 compact-btn">
                                    <i class="bi bi-bag-plus me-1"></i>Shop More
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Method Card - NOW PROPERLY ATTACHED -->
                    <div class="card">
                        <div class="card-header">
                            <i class="bi bi-credit-card"></i>
                            Payment Method
                        </div>
                        <div class="card-body">
                            <div class="payment-options">
                                <div class="payment-option selected" onclick="selectPayment('GCash')">
                                    <div class="icon">
                                        <i class="bi bi-phone"></i>
                                    </div>
                                    <h6>GCash</h6>
                                    <p>Pay via GCash transfer</p>
                                    <input type="radio" name="payment_method" value="GCash" id="paymentGCash" class="d-none" checked form="checkoutForm">
                                </div>
                                <div class="payment-option" onclick="selectPayment('COD')">
                                    <div class="icon">
                                        <i class="bi bi-cash"></i>
                                    </div>
                                    <h6>Cash on Delivery</h6>
                                    <p>Pay when order arrives</p>
                                    <input type="radio" name="payment_method" value="COD" id="paymentCOD" class="d-none" form="checkoutForm">
                                </div>
                            </div>
                            
                            <!-- PLACE ORDER BUTTON -->
                            <div class="mt-3">
                                <button type="submit" class="btn btn-primary w-100 compact-btn" form="checkoutForm">
                                    <i class="bi bi-lock me-2"></i>Place Secure Order
                                </button>
                                <p class="text-center text-muted small mt-2 mb-0">
                                    By placing your order, you agree to our <a href="#">Terms</a>
                                </p>
                            </div>
                            
                            <div class="mt-3 pt-3 border-top">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bi bi-shield-check text-success fs-5"></i>
                                    <div>
                                        <h6 class="mb-0" style="font-size: 0.85rem;">Secure Payment</h6>
                                        <p class="text-muted small mb-0">Payment information is encrypted</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

@include('components.footer')

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    // Payment selection function
    function selectPayment(method) {
        document.querySelectorAll('.payment-option').forEach(option => {
            option.classList.remove('selected');
        });
        event.currentTarget.classList.add('selected');
        document.getElementById(`payment${method}`).checked = true;
    }

    // Rest of your JavaScript code remains the same...
    // ==========================================
    // CITY CONFIGURATIONS
    // ==========================================
    const CITY_CONFIGS = {
        1: { // Cebu City
            name: 'Cebu City',
            center: [10.3157, 123.8854],
            bounds: [[10.25, 123.80], [10.38, 123.97]],
            minZoom: 12,
            maxZoom: 18
        },
        2: { // Lapu-Lapu City
            name: 'Lapu-Lapu City',
            center: [10.3103, 123.9494],
            bounds: [[10.27, 123.90], [10.35, 124.00]],
            minZoom: 13,
            maxZoom: 18
        },
        3: { // Mandaue City
            name: 'Mandaue City',
            center: [10.3237, 123.9227],
            bounds: [[10.28, 123.88], [10.37, 123.97]],
            minZoom: 13,
            maxZoom: 18
        },
        4: { // Talisay City
            name: 'Talisy City',
            center: [10.2444, 123.8493],
            bounds: [[10.20, 123.81], [10.29, 123.89]],
            minZoom: 13,
            maxZoom: 18
        }
    };

    // ==========================================
    // DATA STRUCTURES
    // ==========================================
    let citiesData = [];
    let barangaysData = [];
    let currentCity = null;
    let selectedBarangay = null;
    let userLocation = null; // Store user's detected location
    
    // ==========================================
    // DOM ELEMENTS
    // ==========================================
    const citySelect = document.getElementById('citySelect');
    const barangaySelect = document.getElementById('barangaySelect');
    const locationInfo = document.querySelector('.location-info');
    
    // ==========================================
    // DETECT USER LOCATION & LOAD CITIES
    // ==========================================
    document.addEventListener('DOMContentLoaded', async function() {
        // Try to get user's location
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                async function(position) {
                    userLocation = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude
                    };
                    
                    // Detect which city the user is in
                    const detectedCity = detectUserCity(userLocation.lat, userLocation.lng);
                    
                    // Load only the detected city
                    await loadCities(detectedCity);
                    
                    // Auto-select the city
                    if (detectedCity && citiesData.length > 0) {
                        citySelect.value = detectedCity;
                        citySelect.dispatchEvent(new Event('change'));
                    }
                },
                function(error) {
                    console.log('Geolocation error:', error);
                    // Load all cities if location detection fails
                    loadAllCities();
                }
            );
        } else {
            // Load all cities if geolocation not supported
            loadAllCities();
        }
    });

    // ==========================================
    // DETECT USER'S CITY
    // ==========================================
    function detectUserCity(lat, lng) {
        for (const [cityId, config] of Object.entries(CITY_CONFIGS)) {
            const bounds = config.bounds;
            if (lat >= bounds[0][0] && lat <= bounds[1][0] && 
                lng >= bounds[0][1] && lng <= bounds[1][1]) {
                return parseInt(cityId);
            }
        }
        return null; // User not in any service area
    }

    // ==========================================
    // LOAD SPECIFIC CITY ONLY
    // ==========================================
    async function loadCities(cityId) {
        try {
            const response = await fetch('/api/locations/cities/1'); // Province ID = 1 (Cebu)
            const data = await response.json();
            
            // Filter to show only the detected city
            if (cityId && CITY_CONFIGS[cityId]) {
                citiesData = data.filter(city => city.id === cityId);
                locationInfo.innerHTML = `
                    <i class="bi bi-info-circle"></i>
                    <strong>Service Area:</strong> We currently deliver within ${CITY_CONFIGS[cityId].name} only
                `;
            } else {
                citiesData = data;
            }
            
            // Populate city dropdown
            citySelect.innerHTML = '<option value="">Select City</option>';
            citiesData.forEach(city => {
                const option = new Option(city.city_name, city.id);
                citySelect.add(option);
            });
            
            // If only one city available, show info message
            if (citiesData.length === 1) {
                locationInfo.classList.add('alert', 'alert-info');
                locationInfo.innerHTML = `
                    <i class="bi bi-check-circle me-2"></i>
                    <strong>Great!</strong> You're in our service area: ${citiesData[0].city_name}
                `;
            }
        } catch (error) {
            console.error('Error loading cities:', error);
        }
    }

    // ==========================================
    // LOAD ALL CITIES (FALLBACK)
    // ==========================================
    async function loadAllCities() {
        try {
            const response = await fetch('/api/locations/cities/1');
            const data = await response.json();
            
            // Show only configured cities
            citiesData = data.filter(city => CITY_CONFIGS[city.id]);
            
            citySelect.innerHTML = '<option value="">Select City</option>';
            citiesData.forEach(city => {
                const option = new Option(city.city_name, city.id);
                citySelect.add(option);
            });
            
            locationInfo.innerHTML = `
                <i class="bi bi-info-circle"></i>
                <strong>Service Areas:</strong> Cebu City, Lapu-Lapu City, Mandaue City, Talisay City
            `;
        } catch (error) {
            console.error('Error loading cities:', error);
        }
    }
    
    // ==========================================
    // CITY SELECTION
    // ==========================================
    citySelect.addEventListener('change', function() {
        const cityId = parseInt(this.value);
        if (!cityId) {
            resetCitySelection();
            return;
        }
        
        currentCity = citiesData.find(c => c.id === cityId);
        if (!currentCity || !CITY_CONFIGS[cityId]) return;
        
        const config = CITY_CONFIGS[cityId];
        
        // Update map bounds and center
        map.setView(config.center, config.minZoom);
        map.setMinZoom(config.minZoom);
        map.setMaxZoom(config.maxZoom);
        map.setMaxBounds(config.bounds);
        
        drawCityBoundary(config.bounds);
        updateCityInfo(currentCity);
        loadBarangays(cityId);
        resetBarangaySelection();
    });
    
    function resetCitySelection() {
        barangaySelect.disabled = true;
        barangaySelect.innerHTML = '<option value="">Select city first</option>';
        resetBarangaySelection();
        if (boundaryRect) map.removeLayer(boundaryRect);
        document.getElementById('addressDisplay').classList.remove('active');
    }
    
    // ==========================================
    // LOAD BARANGAYS
    // ==========================================
    function loadBarangays(cityId) {
        fetch(`/api/locations/barangays/${cityId}`)
            .then(res => res.json())
            .then(data => {
                barangaysData = data;
                barangaySelect.innerHTML = '<option value="">Choose Barangay</option>';
                barangaysData.forEach(barangay => {
                    const option = new Option(barangay.barangay_name, barangay.id);
                    barangaySelect.add(option);
                });
                barangaySelect.disabled = false;
            })
            .catch(err => console.error('Error loading barangays:', err));
    }
    
    // ==========================================
    // BARANGAY SELECTION
    // ==========================================
    barangaySelect.addEventListener('change', async function() {
        const barangayId = parseInt(this.value);
        if (!barangayId) {
            resetBarangaySelection();
            return;
        }
        
        selectedBarangay = barangaysData.find(b => b.id === barangayId);
        if (!selectedBarangay) return;
        
        updateBarangayInfo(selectedBarangay);
        await searchBarangayLocation(selectedBarangay);
    });
    
    function resetBarangaySelection() {
        if (marker) map.removeLayer(marker);
        selectedBarangay = null;
        document.getElementById('selectedBarangay').textContent = '-';
        document.getElementById('barangayId').value = '';
        document.getElementById('latitude').value = '';
        document.getElementById('longitude').value = '';
    }
    
    // ==========================================
    // UPDATE ADDRESS DISPLAY
    // ==========================================
    function updateCityInfo(city) {
        document.getElementById('selectedCity').textContent = city.city_name;
        document.getElementById('regionId').value = 1;
        document.getElementById('provinceId').value = 1;
        document.getElementById('addressDisplay').classList.add('active');
    }
    
    function updateBarangayInfo(barangay) {
        document.getElementById('selectedBarangay').textContent = barangay.barangay_name;
        document.getElementById('barangayId').value = barangay.id;
        document.getElementById('addressDisplay').classList.add('active');
    }
    
    // ==========================================
    // MAP SETUP
    // ==========================================
    const map = L.map('map', {
        center: [10.3157, 123.8854], // Default: Cebu City center
        zoom: 12,
        minZoom: 11,
        maxZoom: 18,
        maxBounds: [[10.15, 123.75], [10.45, 124.05]], // Greater Cebu area
        maxBoundsViscosity: 1.0
    });
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap',
        maxZoom: 18
    }).addTo(map);
    
    let marker = null;
    let boundaryRect = null;
    
    function drawCityBoundary(bounds) {
        if (boundaryRect) map.removeLayer(boundaryRect);
        boundaryRect = L.rectangle(bounds, {
            color: '#d63384',
            weight: 2,
            fillOpacity: 0.05,
            fillColor: '#d63384'
        }).addTo(map);
    }
    
    // ==========================================
    // SEARCH BARANGAY ON MAP
    // ==========================================
    async function searchBarangayLocation(barangay) {
        if (!currentCity) return;
        
        try {
            const searchQuery = `${barangay.barangay_name}, ${currentCity.city_name}, Cebu, Philippines`;
            const response = await fetch(
                `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(searchQuery)}&limit=1&countrycodes=ph`
            );
            const results = await response.json();
            
            if (results.length > 0) {
                const lat = parseFloat(results[0].lat);
                const lng = parseFloat(results[0].lon);
                
                // Verify location is within current city bounds
                if (isWithinCityBounds(lat, lng, currentCity.id)) {
                    placeMarker(lat, lng, `${barangay.barangay_name}, ${currentCity.city_name}`);
                } else {
                    alert(`Location outside ${currentCity.city_name}. Please select a valid location.`);
                }
            }
        } catch (error) {
            console.error('Search error:', error);
        }
    }
    
    // ==========================================
    // MAP CLICK HANDLER
    // ==========================================
    map.on('click', async function(e) {
        if (!currentCity) {
            alert('Please select your city first.');
            return;
        }
        
        const lat = e.latlng.lat;
        const lng = e.latlng.lng;
        
        // Check if click is within current city bounds
        if (!isWithinCityBounds(lat, lng, currentCity.id)) {
            alert(`Please select a location within ${currentCity.city_name} only.`);
            return;
        }
        
        placeMarker(lat, lng);
        
        const data = await reverseGeocode(lat, lng);
        if (data && data.address) {
            const barangayName = data.address.suburb || data.address.neighbourhood || 
                                data.address.quarter || data.address.city_district;
            const matchedBarangay = findMatchingBarangay(barangayName);
            
            if (matchedBarangay) {
                barangaySelect.value = matchedBarangay.id;
                selectedBarangay = matchedBarangay;
                updateBarangayInfo(matchedBarangay);
                marker.bindPopup(`<strong>${matchedBarangay.barangay_name}</strong><br>Auto-detected`).openPopup();
            }
        }
    });
    
    // ==========================================
    // HELPER FUNCTIONS
    // ==========================================
    function isWithinCityBounds(lat, lng, cityId) {
        if (!CITY_CONFIGS[cityId]) return false;
        const bounds = CITY_CONFIGS[cityId].bounds;
        return lat >= bounds[0][0] && lat <= bounds[1][0] && 
               lng >= bounds[0][1] && lng <= bounds[1][1];
    }
    
    function placeMarker(lat, lng, popupText = null) {
        document.getElementById('latitude').value = lat;
        document.getElementById('longitude').value = lng;
        
        if (marker) map.removeLayer(marker);
        marker = L.marker([lat, lng], {
            icon: L.divIcon({
                html: '<div style="background: #d63384; width: 20px; height: 20px; border-radius: 50%; border: 3px solid white; box-shadow: 0 2px 6px rgba(0,0,0,0.3);"></div>',
                iconSize: [20, 20],
                iconAnchor: [10, 10]
            })
        }).addTo(map);
        
        if (popupText) {
            marker.bindPopup(`<strong>${popupText}</strong>`).openPopup();
        }
        
        map.setView([lat, lng], 15);
    }
    
    async function reverseGeocode(lat, lng) {
        try {
            const response = await fetch(
                `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=18`
            );
            return await response.json();
        } catch (error) {
            return null;
        }
    }
    
    function findMatchingBarangay(locationName) {
        if (!locationName || barangaysData.length === 0) return null;
        const searchTerm = locationName.toLowerCase().trim();
        return barangaysData.find(b => 
            b.barangay_name.toLowerCase() === searchTerm ||
            b.barangay_name.toLowerCase().includes(searchTerm) ||
            searchTerm.includes(b.barangay_name.toLowerCase())
        );
    }
    
    // ==========================================
    // FORM VALIDATION
    // ==========================================
    document.getElementById('checkoutForm').addEventListener('submit', function(e) {
        if (!citySelect.value || !barangaySelect.value) {
            e.preventDefault();
            alert('Please select your city and barangay.');
            return false;
        }
        
        const lat = parseFloat(document.getElementById('latitude').value);
        const lng = parseFloat(document.getElementById('longitude').value);
        
        if (lat && lng && !isWithinCityBounds(lat, lng, parseInt(citySelect.value))) {
            e.preventDefault();
            alert(`Selected location must be within ${currentCity.city_name}.`);
            return false;
        }
    });
</script>
</body>
</html>