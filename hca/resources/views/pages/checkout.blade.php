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
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        .product-image { width: 60px; height: 60px; object-fit: cover; border-radius: 8px; }
        body { background-color: #f8f9fa; }
        .card { border: none; box-shadow: 0 4px 6px rgba(0,0,0,0.1); border-radius: 12px; }
        .card-header { background: linear-gradient(135deg, #007bff, #0056b3); color: white; border-radius: 12px 12px 0 0 !important; }
        .form-control, .form-select { border-radius: 8px; border: 2px solid #e9ecef; }
        .form-control:focus, .form-select:focus { border-color: #007bff; box-shadow: 0 0 0 0.2rem rgba(0,123,255,0.25); }
        .btn { border-radius: 8px; }
        .product-item { background: #f8f9fa; padding: 15px; border-radius: 10px; margin-bottom: 10px; }
        .total-highlight { background: linear-gradient(135deg, #28a745, #20c997); color: white; padding: 20px; border-radius: 10px; }
        
        /* Map Styles */
        #map { 
            height: 400px; 
            width: 100%; 
            border-radius: 8px; 
            margin-bottom: 15px;
            border: 2px solid #e9ecef;
        }
        .map-search-box {
            position: relative;
            margin-bottom: 15px;
        }
        .map-search-box input {
            padding-right: 40px;
        }
        .map-search-box .search-icon {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
        }
        .location-info {
            background: #e3f2fd;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 15px;
            display: none;
        }
        .location-info.active {
            display: block;
        }
        .form-select:disabled {
            background-color: #e9ecef;
            cursor: not-allowed;
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

<!-- Checkout Section -->
<div class="container my-5">
    <div class="d-flex align-items-center mb-4">
        <i class="bi bi-credit-card text-primary fs-3 me-3"></i>
        <h2 class="mb-0">Checkout</h2>
    </div>

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
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
            <form method="POST" action="{{ route('checkout.store') }}" id="checkoutForm">
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
                            
                            <!-- Map Search -->
                            <div class="map-search-box">
                                <input type="text" id="mapSearch" class="form-control" placeholder="Search for a location...">
                                <i class="bi bi-search search-icon"></i>
                            </div>

                            <!-- Map Container -->
                            <div id="map"></div>

                            <!-- Location Info Display -->
                            <div class="location-info" id="locationInfo">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-geo-alt-fill text-primary me-2"></i>
                                    <small><strong>Selected Location:</strong> <span id="selectedLocation">Click on the map to select</span></small>
                                </div>
                            </div>

                            <!-- Address Dropdowns -->
                            <div class="row g-2">
                                <div class="col-md-4">
                                    <select class="form-select" name="region_id" id="regionSelect" required>
                                        <option value="">Select Region</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <select class="form-select" name="province_id" id="provinceSelect" disabled required>
                                        <option value="">Select Province</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <select class="form-select" name="city_id" id="citySelect" disabled required>
                                        <option value="">Select City</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <select class="form-select" name="barangay_id" id="barangaySelect" disabled required>
                                        <option value="">Select Barangay</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="street" id="street" placeholder="Street / House No." required>
                                </div>
                            </div>
                            <input type="hidden" name="latitude" id="latitude">
                            <input type="hidden" name="longitude" id="longitude">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold"><i class="bi bi-phone me-1"></i>Phone Number *</label>
                            <input type="tel" class="form-control" name="phone" value="{{ old('phone') }}" placeholder="09123456789" pattern="[0-9]{11}" maxlength="11" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold"><i class="bi bi-wallet2 me-1"></i>Payment Method</label>
                            <div class="card p-3" style="background: #e3f2fd;">
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="payment_method" value="GCash" id="paymentGCash" checked>
                                    <label class="form-check-label" for="paymentGCash">
                                        <strong>GCash (Manual Payment)</strong>
                                        <small class="d-block text-muted">Pay via GCash transfer</small>
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="payment_method" value="COD" id="paymentCOD">
                                    <label class="form-check-label" for="paymentCOD">
                                        <strong>Cash On Delivery (COD)</strong>
                                        <small class="d-block text-muted">Pay when your order arrives</small>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-success btn-lg fw-semibold">
                        <i class="bi bi-shield-check me-2"></i>Place Order
                    </button>
                </div>
            </form>
        </div>

        <!-- Order Summary (Read-only, no edit/remove buttons) -->
        <div class="col-lg-5">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-receipt me-2"></i>Order Summary</h5>
                </div>
                <div class="card-body">
                    @foreach($cartItems as $item)
                        <div class="product-item d-flex align-items-center">
                            <img src="{{ asset('asset/images/' . $item->product->image) }}" alt="{{ $item->product->name }}" class="product-image me-3">
                            <div class="flex-grow-1">
                                <h6 class="mb-1">{{ $item->product->name }}</h6>
                                <small class="text-muted">Quantity: {{ $item->quantity }}</small>
                            </div>
                            <div>
                                <strong class="text-success">₱{{ number_format($item->quantity * $item->product->price, 2) }}</strong>
                            </div>
                        </div>
                    @endforeach

                    <div class="total-highlight text-center mt-3">
                        <h5 class="mb-0">Total: ₱{{ number_format($total, 2) }}</h5>
                    </div>

                    <div class="alert alert-info mt-3 text-center">
                        <i class="bi bi-info-circle me-2"></i>
                        <small>Need to edit items? <a href="{{ route('cart.index') }}" class="fw-bold">Go back to cart</a></small>
                    </div>
                </div>
            </div>
        </div>
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
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    // ==========================================
    // LOCATION DROPDOWN CASCADE
    // ==========================================
    const regionSelect = document.getElementById('regionSelect');
    const provinceSelect = document.getElementById('provinceSelect');
    const citySelect = document.getElementById('citySelect');
    const barangaySelect = document.getElementById('barangaySelect');

    // Load regions on page load
    fetch('/api/locations/regions')
        .then(res => res.json())
        .then(data => {
            data.forEach(region => {
                const option = new Option(region.region_name, region.id);
                regionSelect.add(option);
            });
        })
        .catch(err => console.error('Error loading regions:', err));

    // Region change - load provinces
    regionSelect.addEventListener('change', function() {
        const regionId = this.value;
        
        provinceSelect.innerHTML = '<option value="">Select Province</option>';
        citySelect.innerHTML = '<option value="">Select City</option>';
        barangaySelect.innerHTML = '<option value="">Select Barangay</option>';
        provinceSelect.disabled = !regionId;
        citySelect.disabled = true;
        barangaySelect.disabled = true;

        if (regionId) {
            fetch(`/api/locations/provinces/${regionId}`)
                .then(res => res.json())
                .then(data => {
                    data.forEach(province => {
                        const option = new Option(province.province_name, province.id);
                        provinceSelect.add(option);
                    });
                })
                .catch(err => console.error('Error loading provinces:', err));
        }
    });

    // Province change - load cities
    provinceSelect.addEventListener('change', function() {
        const provinceId = this.value;
        
        citySelect.innerHTML = '<option value="">Select City</option>';
        barangaySelect.innerHTML = '<option value="">Select Barangay</option>';
        citySelect.disabled = !provinceId;
        barangaySelect.disabled = true;

        if (provinceId) {
            fetch(`/api/locations/cities/${provinceId}`)
                .then(res => res.json())
                .then(data => {
                    data.forEach(city => {
                        const option = new Option(city.city_name, city.id);
                        citySelect.add(option);
                    });
                })
                .catch(err => console.error('Error loading cities:', err));
        }
    });

    // City change - load barangays
    citySelect.addEventListener('change', function() {
        const cityId = this.value;
        
        barangaySelect.innerHTML = '<option value="">Select Barangay</option>';
        barangaySelect.disabled = !cityId;

        if (cityId) {
            fetch(`/api/locations/barangays/${cityId}`)
                .then(res => res.json())
                .then(data => {
                    data.forEach(barangay => {
                        const option = new Option(barangay.barangay_name, barangay.id);
                        barangaySelect.add(option);
                    });
                })
                .catch(err => console.error('Error loading barangays:', err));
        }
    });

    // ==========================================
    // MAP FUNCTIONALITY
    // ==========================================
    const map = L.map('map').setView([10.3157, 123.8854], 13);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors',
        maxZoom: 19
    }).addTo(map);
    
    let marker = null;
    
    async function reverseGeocode(lat, lng) {
        try {
            const response = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&addressdetails=1`);
            const data = await response.json();
            return data;
        } catch (error) {
            console.error('Geocoding error:', error);
            return null;
        }
    }
    
    function fillAddressFields(address) {
        if (!address) return;
        
        const locationInfo = document.getElementById('locationInfo');
        const selectedLocation = document.getElementById('selectedLocation');
        
        const street = address.road || address.suburb || '';
        document.getElementById('street').value = street;
        
        selectedLocation.textContent = address.display_name || 'Location selected';
        locationInfo.classList.add('active');
    }
    
    map.on('click', async function(e) {
        const lat = e.latlng.lat;
        const lng = e.latlng.lng;
        
        document.getElementById('latitude').value = lat;
        document.getElementById('longitude').value = lng;
        
        if (marker) {
            map.removeLayer(marker);
        }
        
        marker = L.marker([lat, lng]).addTo(map);
        
        const data = await reverseGeocode(lat, lng);
        if (data && data.address) {
            fillAddressFields(data.address);
        }
    });
    
    // Map Search
    const searchInput = document.getElementById('mapSearch');
    let searchTimeout;
    
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        const query = this.value.trim();
        
        if (query.length < 3) return;
        
        searchTimeout = setTimeout(async () => {
            try {
                const response = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&countrycodes=ph&addressdetails=1&limit=1`);
                const results = await response.json();
                
                if (results.length > 0) {
                    const result = results[0];
                    const lat = parseFloat(result.lat);
                    const lng = parseFloat(result.lon);
                    
                    if (marker) {
                        map.removeLayer(marker);
                    }
                    
                    marker = L.marker([lat, lng]).addTo(map);
                    map.setView([lat, lng], 15);
                    
                    document.getElementById('latitude').value = lat;
                    document.getElementById('longitude').value = lng;
                    
                    if (result.address) {
                        fillAddressFields(result.address);
                    }
                }
            } catch (error) {
                console.error('Search error:', error);
            }
        }, 500);
    });
    
    // Try to get user's location
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            function(position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                map.setView([lat, lng], 15);
            },
            function(error) {
                console.log('Location access denied');
            }
        );
    }
</script>
</body>
</html>