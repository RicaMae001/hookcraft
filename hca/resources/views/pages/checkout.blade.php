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
        .form-control { border-radius: 8px; border: 2px solid #e9ecef; }
        .form-control:focus { border-color: #007bff; box-shadow: 0 0 0 0.2rem rgba(0,123,255,0.25); }
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
        <!-- Customer Form (wraps only customer info and payment method) -->
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

                            <!-- Address Fields -->
                            <div class="row g-2">
                                <div class="col-md-4">
                                    <input type="text" class="form-control" name="region" id="region" placeholder="Region" required>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" name="province" id="province" placeholder="Province" required>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" name="city" id="city" placeholder="City" required>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="barangay" id="barangay" placeholder="Barangay" required>
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

        <!-- Order Summary (outside the checkout form) -->
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
                                <form method="POST" action="{{ route('cart.update', $item->id) }}" class="d-inline">
                                    @csrf
                                    <div class="input-group input-group-sm" style="max-width: 120px;">
                                        <input type="number" name="quantity" class="form-control cart-qty-input" min="1" max="{{ $item->product->stock }}" value="{{ $item->quantity }}" required data-id="{{ $item->id }}">
                                        <button class="btn btn-primary" type="submit"><i class="bi bi-arrow-repeat"></i></button>
                                    </div>
                                </form>
                                <form method="POST" action="{{ route('cart.delete', $item->id) }}" class="d-inline ms-2">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm" type="submit"><i class="bi bi-trash"></i></button>
                                </form>
                                <small class="text-muted">Stock: {{ $item->product->stock }}</small>
                            </div>
                            <div><strong class="text-success">₱{{ number_format($item->quantity * $item->product->price, 2) }}</strong></div>
                            <span class="product-price d-none">{{ $item->product->price }}</span>
                        </div>
                    @endforeach

                    <div class="total-highlight text-center mt-3">
                        <h5 class="mb-0">Total: ₱{{ number_format($total, 2) }}</h5>
                    </div>

                    <div class="d-grid gap-2 mt-4">
                        <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-2"></i>Back to Cart
                        </a>
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
    // Initialize map centered on Cebu City, Philippines
    const map = L.map('map').setView([10.3157, 123.8854], 13);
    
    // Add OpenStreetMap tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors',
        maxZoom: 19
    }).addTo(map);
    
    let marker = null;
    
    // Function to reverse geocode (get address from coordinates)
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
    
    // Function to fill address fields
    function fillAddressFields(address) {
        if (!address) return;
        
        const locationInfo = document.getElementById('locationInfo');
        const selectedLocation = document.getElementById('selectedLocation');
        
        // Extract address components
        const street = address.road || address.suburb || '';
        const barangay = address.suburb || address.village || address.neighbourhood || '';
        const city = address.city || address.town || address.municipality || '';
        const province = address.state || address.province || '';
        const region = address.region || 'Region VII';
        
        // Fill form fields
        document.getElementById('street').value = street;
        document.getElementById('barangay').value = barangay;
        document.getElementById('city').value = city;
        document.getElementById('province').value = province;
        document.getElementById('region').value = region;
        
        // Show location info
        selectedLocation.textContent = address.display_name || 'Location selected';
        locationInfo.classList.add('active');
    }
    
    // Handle map clicks
    map.on('click', async function(e) {
        const lat = e.latlng.lat;
        const lng = e.latlng.lng;
        
        // Store coordinates
        document.getElementById('latitude').value = lat;
        document.getElementById('longitude').value = lng;
        
        // Remove existing marker
        if (marker) {
            map.removeLayer(marker);
        }
        
        // Add new marker
        marker = L.marker([lat, lng]).addTo(map);
        
        // Get address and fill fields
        const data = await reverseGeocode(lat, lng);
        if (data && data.address) {
            fillAddressFields(data.address);
        }
    });
    
    // Search functionality
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
                    
                    // Remove existing marker
                    if (marker) {
                        map.removeLayer(marker);
                    }
                    
                    // Add marker and center map
                    marker = L.marker([lat, lng]).addTo(map);
                    map.setView([lat, lng], 15);
                    
                    // Store coordinates
                    document.getElementById('latitude').value = lat;
                    document.getElementById('longitude').value = lng;
                    
                    // Fill address fields
                    if (result.address) {
                        fillAddressFields(result.address);
                    }
                }
            } catch (error) {
                console.error('Search error:', error);
            }
        }, 500);
    });
    
    // Try to get user's current location
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            function(position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                map.setView([lat, lng], 15);
            },
            function(error) {
                console.log('Location access denied or unavailable');
            }
        );
    }

    // Auto-update total when quantity changes
document.querySelectorAll('input[name="quantity"]').forEach(function(input) {
    input.addEventListener('input', function() {
        let parent = input.closest('.product-item');
        let price = parseFloat(parent.querySelector('.text-success').textContent.replace(/[^\d.]/g, '')) / input.value;
        let newTotal = price * input.value;
        parent.querySelector('.text-success').textContent = '₱' + newTotal.toFixed(2);

        // Recalculate grand total
        let grandTotal = 0;
        document.querySelectorAll('.product-item').forEach(function(item) {
            let qty = parseInt(item.querySelector('input[name="quantity"]').value);
            let priceEach = parseFloat(item.querySelector('.text-success').textContent.replace(/[^\d.]/g, '')) / qty;
            grandTotal += priceEach * qty;
        });
        document.querySelector('.total-highlight h5').textContent = 'Total: ₱' + grandTotal.toFixed(2);
    });
});

document.querySelectorAll('.cart-qty-input').forEach(function(input) {
    input.addEventListener('change', function() {
        let cartItemId = input.getAttribute('data-id');
        let newQty = input.value;
        let maxStock = input.getAttribute('max');
        if (parseInt(newQty) < 1 || parseInt(newQty) > parseInt(maxStock)) {
            alert('Invalid quantity!');
            input.value = maxStock;
            newQty = maxStock;
        }

        fetch("{{ url('/cart/update') }}/" + cartItemId, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams({ quantity: newQty })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update item total
                let parent = input.closest('.product-item');
                let priceEach = parseFloat(parent.querySelector('.product-price').textContent.replace(/[^\d.]/g, ''));
                let newTotal = priceEach * newQty;
                parent.querySelector('.text-success').textContent = '₱' + newTotal.toFixed(2);

                // Recalculate grand total
                let grandTotal = 0;
                document.querySelectorAll('.product-item').forEach(function(item) {
                    let qty = parseInt(item.querySelector('.cart-qty-input').value);
                    let priceEach = parseFloat(item.querySelector('.product-price').textContent.replace(/[^\d.]/g, ''));
                    grandTotal += priceEach * qty;
                });
                document.querySelector('.total-highlight h5').textContent = 'Total: ₱' + grandTotal.toFixed(2);
            } else {
                alert(data.message || 'Error updating quantity');
            }
        })
        .catch(() => {
            alert('Error updating quantity');
        });
    });
});
</script>
</body>
</html>