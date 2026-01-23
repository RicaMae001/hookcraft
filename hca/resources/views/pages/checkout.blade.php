<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hookcraft Avenue - Secure Checkout</title>
    <link rel="icon" href="{{ asset('asset/images/logo.jpg') }}" type="image/png">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('asset/stylescheckout.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/stylesnav.css') }}">
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
            --warning-yellow: #ffc107;
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
            padding: 2rem 1.5rem;
        }

        /* Page Header */
        .page-header {
            text-align: center;
            margin-bottom: 3rem;
            padding-top: 1rem;
        }

        .page-header h2 {
            font-size: 2.25rem;
            font-weight: 700;
            color: var(--dark-navy);
            margin-bottom: 0.75rem;
            letter-spacing: -0.025em;
        }

        .page-header .subtitle {
            color: var(--secondary-gray);
            font-size: 1.1rem;
            font-weight: 400;
            max-width: 600px;
            margin: 0 auto;
        }

        .checkout-progress {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 2rem;
            margin-top: 2rem;
            flex-wrap: wrap;
        }

        .progress-step {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: var(--secondary-gray);
            font-size: 0.9rem;
        }

        .progress-step.active {
            color: var(--primary-pink);
            font-weight: 600;
        }

        .progress-step .step-icon {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.875rem;
        }

        .progress-step.active .step-icon {
            background: linear-gradient(135deg, var(--primary-pink), var(--accent-rose));
            color: white;
        }

        /* Cards */
        .card {
            border: none;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-md);
            overflow: hidden;
            background: var(--white);
            transition: var(--transition);
            margin-bottom: 1.5rem;
        }

        .card:hover {
            box-shadow: var(--shadow-lg);
        }

        .card-header {
            background: linear-gradient(135deg, #f8f9fa, #ffffff);
            color: var(--dark-navy);
            padding: 1.5rem 1.75rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            border-bottom: 1px solid var(--border-color);
            font-size: 1.1rem;
        }

        .card-header i {
            color: var(--primary-pink);
        }

        .card-body {
            padding: 1.75rem;
        }

        /* Form Elements */
        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            font-weight: 600;
            color: var(--dark-navy);
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.95rem;
        }

        .form-label i {
            color: var(--primary-pink);
            width: 20px;
        }

        .form-control, .form-select {
            border-radius: 8px;
            border: 1px solid var(--border-color);
            padding: 0.875rem 1rem;
            font-size: 0.95rem;
            transition: var(--transition);
            background: var(--white);
            color: var(--dark-navy);
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-pink);
            box-shadow: 0 0 0 0.2rem rgba(214, 51, 132, 0.15);
            outline: none;
        }

        /* Product Items */
        .product-item {
            display: flex;
            align-items: center;
            padding: 1rem;
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
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
            margin-right: 1rem;
            border: 1px solid var(--border-color);
        }

        .product-details {
            flex: 1;
        }

        .product-name {
            font-weight: 600;
            color: var(--dark-navy);
            margin-bottom: 0.25rem;
        }

        .product-meta {
            color: var(--secondary-gray);
            font-size: 0.875rem;
            margin-bottom: 0.25rem;
        }

        .product-price {
            font-weight: 700;
            color: var(--primary-pink);
            font-size: 1.1rem;
        }

        /* Order Summary */
        .order-summary {
            position: sticky;
            top: 2rem;
        }

        .summary-section {
            padding: 1rem 0;
            border-bottom: 1px solid var(--border-color);
        }

        .summary-section:last-child {
            border-bottom: none;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.75rem;
            font-size: 0.95rem;
        }

        .summary-row.total {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--primary-pink);
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 2px solid var(--border-color);
        }

        .security-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: #e8f5e9;
            color: #2e7d32;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.875rem;
            margin-right: 0.5rem;
            margin-bottom: 0.5rem;
        }

        /* Payment Options */
        .payment-options {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }

        .payment-option {
            border: 2px solid var(--border-color);
            border-radius: 10px;
            padding: 1.25rem;
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
            width: 48px;
            height: 48px;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--primary-pink), var(--accent-rose));
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

        .payment-option h6 {
            font-weight: 600;
            margin-bottom: 0.25rem;
            color: var(--dark-navy);
        }

        .payment-option p {
            color: var(--secondary-gray);
            font-size: 0.875rem;
            margin: 0;
        }

        /* Map Container */
        .map-container {
            border-radius: 10px;
            overflow: hidden;
            border: 1px solid var(--border-color);
            margin: 1rem 0;
        }

        #map {
            height: 250px;
            width: 100%;
        }

        .location-display {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 1rem;
            margin-top: 1rem;
            border-left: 4px solid var(--primary-pink);
        }

        .location-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-top: 0.75rem;
        }

        .location-tag {
            background: linear-gradient(135deg, var(--primary-pink), var(--accent-rose));
            color: white;
            padding: 0.375rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        /* Buttons */
        .btn {
            border-radius: 8px;
            padding: 0.875rem 1.75rem;
            font-weight: 600;
            transition: var(--transition);
            border: none;
            font-size: 1rem;
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
            padding: 1rem 2rem;
            font-size: 1.1rem;
        }

        .btn-block {
            width: 100%;
            display: block;
        }

        /* Alerts */
        .alert {
            border-radius: 10px;
            border: none;
            padding: 1.25rem;
            margin-bottom: 2rem;
            box-shadow: var(--shadow-sm);
        }

        .alert-danger {
            background: linear-gradient(135deg, #fdf2f2, #fde8e8);
            color: #9b1c1c;
            border-left: 4px solid var(--danger-red);
        }

        .alert-success {
            background: linear-gradient(135deg, #f0fdf4, #dcfce7);
            color: #166534;
            border-left: 4px solid var(--success-green);
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            max-width: 500px;
            margin: 0 auto;
        }

        .empty-icon {
            width: 120px;
            height: 120px;
            margin: 0 auto 2rem;
            background: linear-gradient(135deg, #fce7f3, #f8bbd9);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .empty-icon i {
            font-size: 3.5rem;
            color: var(--primary-pink);
        }

        /* Service Notice */
        .service-notice {
            background: linear-gradient(135deg, #e3f2fd, #bbdefb);
            border-radius: 10px;
            padding: 1.25rem;
            margin-bottom: 1.5rem;
            border-left: 4px solid #2196f3;
        }

        .service-notice i {
            color: #2196f3;
            margin-right: 0.5rem;
        }

        /* Responsive Design */
        @media (max-width: 992px) {
            .order-summary {
                position: static;
                margin-top: 2rem;
            }
            
            .checkout-progress {
                gap: 1rem;
            }
            
            .progress-step {
                font-size: 0.8rem;
            }
        }

        @media (max-width: 768px) {
            .container {
                padding: 1.5rem 1rem;
            }
            
            .page-header h2 {
                font-size: 1.75rem;
            }
            
            .card-body {
                padding: 1.0rem;
            }
            
            .payment-options {
                grid-template-columns: 1fr;
            }
            
            .product-image {
                width: 70px;
                height: 70px;
            }
        }

        @media (max-width: 576px) {
            .checkout-progress {
                flex-direction: column;
                gap: 0.5rem;
            }
            
            .progress-step {
                width: 100%;
                justify-content: center;
            }
            
            .empty-state {
                padding: 3rem 1rem;
            }
        }
    </style>
</head>
<body>

@include('components.login_modal')
@include('components.signup_modal')
@include('components.navbar')

<div class="container">
    <!-- Page Header with Progress -->
    <div class="page-header">
        <h2>Secure Checkout</h2>
        <p class="subtitle">Complete your purchase with confidence - Your handmade treasures await</p>
        

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
    <div class="row">
        <!-- Left Column: Form -->
        <div class="col-lg-8">
            <form method="POST" action="{{ route('checkout.store') }}" id="checkoutForm" class="checkout-form">
                @csrf
                
                <!-- Customer Information -->
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-person-circle"></i>
                        Contact Information
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label class="form-label">
                                    <i class="bi bi-person"></i>
                                    Full Name *
                                </label>
                                <input type="text" class="form-control" name="name" value="{{ old('name') }}" placeholder="Enter your full name" required>
                            </div>
                            <div class="col-md-6 form-group">
                                <label class="form-label">
                                    <i class="bi bi-phone"></i>
                                    Phone Number *
                                </label>
                                <input type="tel" class="form-control" name="phone" value="{{ old('phone') }}" placeholder="09123456789" pattern="[0-9]{11}" maxlength="11" required>
                                <small class="text-muted">Format: 09123456789</small>
                            </div>
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
                        <div class="service-notice">
                            <i class="bi bi-info-circle"></i>
                            <strong>Service Area:</strong> We currently deliver within Central Visayas, Cebu Province
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
                            <div class="map-container">
                                <div id="map"></div>
                            </div>
                            <small class="text-muted">Click on the map or select barangay to mark your location</small>
                        </div>
                        
                        <!-- Location Display -->
                        <div class="location-display" id="addressDisplay" style="display: none;">
                            <h6 class="mb-2">Selected Location:</h6>
                            <div class="location-tags">
                                <span class="location-tag" id="selectedRegion">Region VII</span>
                                <span class="location-tag" id="selectedProvince">Cebu</span>
                                <span class="location-tag" id="selectedCity">-</span>
                                <span class="location-tag" id="selectedBarangay">-</span>
                            </div>
                        </div>
                        
                        <!-- Hidden Fields -->
                        <input type="hidden" name="region_id" id="regionId" value="7">
                        <input type="hidden" name="province_id" id="provinceId" value="1">
                        <input type="hidden" id="barangayId">
                        <input type="hidden" name="latitude" id="latitude">
                        <input type="hidden" name="longitude" id="longitude">
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary btn-lg btn-block">
                        <i class="bi bi-lock me-2"></i>Place Secure Order
                    </button>
                    <p class="text-center text-muted small mt-2">
                        By placing your order, you agree to our <a href="#">Terms & Conditions</a>
                    </p>
                </div>
            </form>
        </div>

        <!-- Right Column: Payment Method and Order Summary -->
        <div class="col-lg-4">
            <!-- Payment Method (Moved above Order Summary) -->
            <div class="card mb-3">
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
                            <input type="radio" name="payment_method" value="GCash" id="paymentGCash" class="d-none" checked>
                        </div>
                        <div class="payment-option" onclick="selectPayment('COD')">
                            <div class="icon">
                                <i class="bi bi-cash"></i>
                            </div>
                            <h6>Cash on Delivery</h6>
                            <p>Pay when order arrives</p>
                            <input type="radio" name="payment_method" value="COD" id="paymentCOD" class="d-none">
                        </div>
                    </div>
                    
                    <div class="mt-4 pt-3 border-top">
                        <div class="d-flex align-items-center gap-3">
                            <i class="bi bi-shield-check text-success fs-4"></i>
                            <div>
                                <h6 class="mb-1">Secure Payment</h6>
                                <p class="text-muted small mb-0">Your payment information is encrypted and secure</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="card order-summary">
                <div class="card-header">
                    <i class="bi bi-receipt"></i>
                    Order Summary
                </div>
                <div class="card-body">
                    <!-- Product List -->
                    <div class="summary-section">
                        <h6 class="mb-3">Items ({{ $cartItems->count() }})</h6>
                        @foreach($cartItems as $item)
                            <div class="product-item">
                                <img src="{{ asset('asset/images/' . $item->product->image) }}" alt="{{ $item->product->name }}" class="product-image">
                                <div class="product-details">
                                    <div class="product-name">{{ $item->product->name }}</div>
                                    <div class="product-meta">
                                        Quantity: {{ $item->quantity }} × ₱{{ number_format($item->product->price, 2) }}
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
                        <div class="d-flex flex-wrap mb-3">
                            <span class="security-badge">
                                <i class="bi bi-shield-check"></i>
                                SSL Secure
                            </span>
                            <span class="security-badge" style="background: #e3f2fd; color: #1565c0;">
                                <i class="bi bi-truck"></i>
                                Fast Delivery
                            </span>
                        </div>
                        
                        <div class="alert alert-info small mb-0">
                            <i class="bi bi-info-circle me-2"></i>
                            Need help? <a href="#" class="fw-bold">Contact Support</a>
                        </div>
                    </div>
                    
                    <!-- Continue Shopping -->
                    <div class="text-center mt-3">
                        <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-arrow-left me-1"></i>Back to Cart
                        </a>
                        <a href="{{ route('shop') }}" class="btn btn-outline-primary btn-sm ms-2">
                            <i class="bi bi-bag-plus me-1"></i>Continue Shopping
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

@include('components.footer')

<!-- Add this CSS to ensure footer is full width -->
<style>
    /* Footer Full Width Override */
    footer {
        width: 100vw;
        margin-left: calc(-50vw + 50%);
        margin-right: calc(-50vw + 50%);
        padding-left: 0;
        padding-right: 0;
    }
    
    /* Alternative simpler method if footer has specific class */
    .footer-container {
        max-width: 100%;
        width: 100%;
        padding: 0;
    }
</style>


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

    // Initialize with simplified location data structure
    const locationConfig = {
        cities: [],
        barangays: [],
        currentCity: null,
        selectedBarangay: null
    };

    // Initialize map
    const map = L.map('map', {
        center: [10.3157, 123.8854],
        zoom: 12,
        minZoom: 10
    });

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap',
        maxZoom: 18
    }).addTo(map);

    let marker = null;
    let cityBounds = null;

    // Load cities on page load
    document.addEventListener('DOMContentLoaded', function() {
        fetch('/api/locations/cities/1')
            .then(res => res.json())
            .then(data => {
                locationConfig.cities = data;
                const citySelect = document.getElementById('citySelect');
                
                // Add only Cebu metro cities
                const cebuCities = data.filter(city => [1, 2, 3, 4].includes(city.id));
                
                cebuCities.forEach(city => {
                    const option = document.createElement('option');
                    option.value = city.id;
                    option.textContent = city.city_name;
                    citySelect.appendChild(option);
                });
            })
            .catch(err => console.error('Error loading cities:', err));
    });

    // City selection handler
    document.getElementById('citySelect').addEventListener('change', function(e) {
        const cityId = parseInt(e.target.value);
        
        if (!cityId) {
            resetCitySelection();
            return;
        }

        locationConfig.currentCity = locationConfig.cities.find(c => c.id === cityId);
        if (!locationConfig.currentCity) return;

        // Update address display
        updateCityInfo(locationConfig.currentCity);
        document.getElementById('addressDisplay').style.display = 'block';

        // Update map view based on city
        updateMapForCity(cityId);

        // Load barangays
        loadBarangays(cityId);
    });

    // Barangay selection handler
    document.getElementById('barangaySelect').addEventListener('change', function(e) {
        const barangayId = parseInt(e.target.value);
        
        if (!barangayId) {
            resetBarangaySelection();
            return;
        }

        locationConfig.selectedBarangay = locationConfig.barangays.find(b => b.id === barangayId);
        if (!locationConfig.selectedBarangay) return;

        updateBarangayInfo(locationConfig.selectedBarangay);
        
        // Search for barangay on map
        searchBarangayOnMap(locationConfig.selectedBarangay);
    });

    // Map click handler
    map.on('click', async function(e) {
        const lat = e.latlng.lat;
        const lng = e.latlng.lng;
        
        document.getElementById('latitude').value = lat;
        document.getElementById('longitude').value = lng;
        
        updateMarker(lat, lng);
        
        // Try to get location name
        const locationData = await reverseGeocode(lat, lng);
        if (locationData && locationData.address) {
            displayLocationInfo(locationData);
        }
    });

    // Helper functions
    function resetCitySelection() {
        document.getElementById('barangaySelect').disabled = true;
        document.getElementById('barangaySelect').innerHTML = '<option value="">Select city first</option>';
        resetBarangaySelection();
        document.getElementById('addressDisplay').style.display = 'none';
    }

    function resetBarangaySelection() {
        if (marker) map.removeLayer(marker);
        locationConfig.selectedBarangay = null;
        document.getElementById('selectedBarangay').textContent = '-';
        document.getElementById('barangayId').value = '';
    }

    function updateCityInfo(city) {
        document.getElementById('selectedCity').textContent = city.city_name;
        document.getElementById('regionId').value = 7;
        document.getElementById('provinceId').value = 1;
    }

    function updateBarangayInfo(barangay) {
        document.getElementById('selectedBarangay').textContent = barangay.barangay_name;
        document.getElementById('barangayId').value = barangay.id;
    }

    function loadBarangays(cityId) {
        fetch(`/api/locations/barangays/${cityId}`)
            .then(res => res.json())
            .then(data => {
                locationConfig.barangays = data;
                const barangaySelect = document.getElementById('barangaySelect');
                
                barangaySelect.innerHTML = '<option value="">Select Barangay</option>';
                data.forEach(barangay => {
                    const option = document.createElement('option');
                    option.value = barangay.id;
                    option.textContent = barangay.barangay_name;
                    barangaySelect.appendChild(option);
                });
                
                barangaySelect.disabled = false;
            });
    }

    function updateMapForCity(cityId) {
        const cityBoundsConfig = {
            1: [[10.24, 123.78], [10.42, 123.98]], // Cebu City
            2: [[10.27, 123.90], [10.35, 124.00]], // Lapu-Lapu
            3: [[10.29, 123.90], [10.36, 123.95]], // Mandaue
            4: [[10.20, 123.82], [10.29, 123.88]]  // Talisay
        };

        if (cityBounds) map.removeLayer(cityBounds);
        
        if (cityBoundsConfig[cityId]) {
            cityBounds = L.rectangle(cityBoundsConfig[cityId], {
                color: '#d63384',
                weight: 2,
                fillOpacity: 0.05,
                fillColor: '#d63384'
            }).addTo(map);
            
            map.fitBounds(cityBoundsConfig[cityId]);
        }
    }

    function updateMarker(lat, lng) {
        if (marker) map.removeLayer(marker);
        
        marker = L.marker([lat, lng], {
            icon: L.divIcon({
                html: '<div style="background: #d63384; width: 20px; height: 20px; border-radius: 50%; border: 3px solid white; box-shadow: 0 2px 6px rgba(0,0,0,0.3);"></div>'
            })
        }).addTo(map);
        
        map.setView([lat, lng], 15);
    }

    async function searchBarangayOnMap(barangay) {
        if (!locationConfig.currentCity) return;
        
        const searchQuery = `${barangay.barangay_name}, ${locationConfig.currentCity.city_name}, Cebu`;
        
        try {
            const response = await fetch(
                `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(searchQuery)}&limit=1&countrycodes=ph`
            );
            const results = await response.json();
            
            if (results.length > 0) {
                const lat = parseFloat(results[0].lat);
                const lng = parseFloat(results[0].lon);
                
                document.getElementById('latitude').value = lat;
                document.getElementById('longitude').value = lng;
                updateMarker(lat, lng);
                
                marker.bindPopup(`
                    <div style="padding: 8px;">
                        <strong>${barangay.barangay_name}</strong><br>
                        ${locationConfig.currentCity.city_name}, Cebu
                    </div>
                `).openPopup();
            }
        } catch (error) {
            console.log('Could not locate barangay on map');
        }
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

    function displayLocationInfo(locationData) {
        // This function can be enhanced to update the UI with location details
        console.log('Location data:', locationData);
    }

    // Form validation
    document.getElementById('checkoutForm').addEventListener('submit', function(e) {
        const citySelect = document.getElementById('citySelect');
        const barangaySelect = document.getElementById('barangaySelect');
        
        if (!citySelect.value || !barangaySelect.value) {
            e.preventDefault();
            alert('Please select both city and barangay for delivery.');
            return false;
        }
        
        // Additional validation can be added here
        return true;
    });

    // Try to get user's location for better map initialization
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            function(position) {
                map.setView([position.coords.latitude, position.coords.longitude], 13);
            },
            function() {
                // Use default Cebu City center
                console.log('Using default location');
            }
        );
    }
</script>
</body>
</html>