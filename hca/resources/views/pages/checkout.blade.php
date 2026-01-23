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
        }
        .address-display {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            border: 2px solid #dee2e6;
            margin-bottom: 15px;
            display: none;
        }
        .address-display.active {
            display: block;
        }
        .address-display .location-badge {
            display: inline-block;
            background: #007bff;
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            margin: 2px;
        }
    </style>
</head>
<body>

@include('components.login_modal')
@include('components.signup_modal')
@include('components.navbar')

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
                            
                            <div class="location-info">
                                <small><i class="bi bi-info-circle me-1"></i><strong>Service Area:</strong> Central Visayas, Cebu Province (Cebu City, Lapu-Lapu, Mandaue, Talisay)</small>
                            </div>

                            <!-- City Selection -->
                            <div class="mb-3">
                                <label class="form-label">Select City *</label>
                                <select class="form-select" id="citySelect" name="city_id" required>
                                    <option value="">Choose City</option>
                                </select>
                            </div>
                            
                            <!-- Barangay Selection -->
                            <div class="mb-3">
                                <label class="form-label">Select Barangay *</label>
                                <select class="form-select" id="barangaySelect" name="barangay_id" disabled required>
                                    <option value="">Select city first</option>
                                </select>
                            </div>

                            <!-- Map Container -->
                            <div id="map"></div>

                            <!-- Address Display -->
                            <div class="address-display" id="addressDisplay">
                                <div class="mb-2">
                                    <small class="text-muted d-block mb-1">Selected Address:</small>
                                    <span class="location-badge" id="selectedRegion">-</span>
                                    <span class="location-badge" id="selectedProvince">-</span>
                                    <span class="location-badge" id="selectedCity">-</span>
                                    <span class="location-badge" id="selectedBarangay">-</span>
                                </div>
                            </div>

                            <!-- Hidden Fields -->
                            <input type="hidden" name="region_id" id="regionId">
                            <input type="hidden" name="province_id" id="provinceId">
                            <input type="hidden" id="barangayId">
                            
                            <!-- Street Input -->
                            <div class="mt-3">
                                <label class="form-label fw-semibold">Street / House No. *</label>
                                <input type="text" class="form-control" name="street" id="street" placeholder="e.g., 123 Main Street, Bldg 5" required>
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
    // DATA STRUCTURES
    // ==========================================
    let citiesData = [];
    let barangaysData = [];
    let currentCity = null;
    let selectedBarangay = null;
    
    const METRO_CEBU_CITY_IDS = [1, 2, 3, 4]; // Cebu City, Lapu-Lapu, Mandaue, Talisay
    
    // Approximate bounds for each city
    const DEFAULT_CITY_BOUNDS = {
        1: { center: [10.3157, 123.8854], bounds: [[10.24, 123.78], [10.42, 123.98]] },
        2: { center: [10.3103, 123.9494], bounds: [[10.27, 123.90], [10.35, 124.00]] },
        3: { center: [10.3237, 123.9227], bounds: [[10.29, 123.90], [10.36, 123.95]] },
        4: { center: [10.2449, 123.8493], bounds: [[10.20, 123.82], [10.29, 123.88]] }
    };
    
    // ==========================================
    // DOM ELEMENTS
    // ==========================================
    const citySelect = document.getElementById('citySelect');
    const barangaySelect = document.getElementById('barangaySelect');
    
    // ==========================================
    // LOAD CITIES FROM DATABASE
    // ==========================================
    fetch('/api/locations/cities/1')
        .then(res => res.json())
        .then(data => {
            citiesData = data.filter(city => METRO_CEBU_CITY_IDS.includes(city.id));
            
            citiesData.forEach(city => {
                const option = new Option(city.city_name, city.id);
                citySelect.add(option);
            });
            
            console.log('Loaded cities:', citiesData.map(c => c.city_name));
        })
        .catch(err => console.error('Error loading cities:', err));
    
    // ==========================================
    // CITY SELECTION HANDLER
    // ==========================================
    citySelect.addEventListener('change', function() {
        const cityId = parseInt(this.value);
        
        if (!cityId) {
            resetCitySelection();
            return;
        }
        
        currentCity = citiesData.find(c => c.id === cityId);
        if (!currentCity) return;
        
        // Update map view
        const cityConfig = DEFAULT_CITY_BOUNDS[cityId];
        if (cityConfig) {
            map.setView(cityConfig.center, 13);
            drawCityBoundary(cityConfig.bounds);
        }
        
        // Update address display
        updateCityInfo(currentCity);
        
        // Load barangays for dropdown
        loadBarangays(cityId);
        
        // Clear previous selections
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
    // LOAD BARANGAYS FOR SELECTED CITY
    // ==========================================
    function loadBarangays(cityId) {
        fetch(`/api/locations/barangays/${cityId}`)
            .then(res => res.json())
            .then(data => {
                barangaysData = data;
                
                // Populate barangay dropdown
                barangaySelect.innerHTML = '<option value="">Choose Barangay</option>';
                barangaysData.forEach(barangay => {
                    const option = new Option(barangay.barangay_name, barangay.id);
                    barangaySelect.add(option);
                });
                
                barangaySelect.disabled = false;
                console.log(`Loaded ${barangaysData.length} barangays for ${currentCity.city_name}`);
            })
            .catch(err => console.error('Error loading barangays:', err));
    }
    
    // ==========================================
    // BARANGAY SELECTION HANDLER
    // ==========================================
    barangaySelect.addEventListener('change', async function() {
        const barangayId = parseInt(this.value);
        
        if (!barangayId) {
            resetBarangaySelection();
            return;
        }
        
        selectedBarangay = barangaysData.find(b => b.id === barangayId);
        if (!selectedBarangay) return;
        
        // Update address display
        updateBarangayInfo(selectedBarangay);
        
        // Try to find location on map
        await searchBarangayLocation(selectedBarangay);
    });
    
    function resetBarangaySelection() {
        if (marker) map.removeLayer(marker);
        selectedBarangay = null;
        document.getElementById('selectedBarangay').textContent = '-';
        document.getElementById('barangayId').value = '';
        document.getElementById('latitude').value = '';
        document.getElementById('longitude').value = '';
        document.getElementById('addressDisplay').classList.remove('active');
    }
    
    // ==========================================
    // UPDATE ADDRESS DISPLAY
    // ==========================================
    function updateCityInfo(city) {
        document.getElementById('selectedRegion').textContent = 'Central Visayas';
        document.getElementById('selectedProvince').textContent = 'Cebu';
        document.getElementById('selectedCity').textContent = city.city_name;
        
        document.getElementById('regionId').value = 1;
        document.getElementById('provinceId').value = 1;
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
        center: [10.3157, 123.8854],
        zoom: 12,
        minZoom: 11
    });
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors',
        maxZoom: 18
    }).addTo(map);
    
    let marker = null;
    let boundaryRect = null;
    
    function drawCityBoundary(bounds) {
        if (boundaryRect) map.removeLayer(boundaryRect);
        
        boundaryRect = L.rectangle(bounds, {
            color: '#007bff',
            weight: 2,
            fillOpacity: 0.05
        }).addTo(map);
    }
    
    // ==========================================
    // SEARCH BARANGAY LOCATION ON MAP
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
                const result = results[0];
                const lat = parseFloat(result.lat);
                const lng = parseFloat(result.lon);
                
                // Remove old marker
                if (marker) map.removeLayer(marker);
                
                // Add new marker
                marker = L.marker([lat, lng]).addTo(map)
                    .bindPopup(`
                        <strong>${barangay.barangay_name}</strong><br>
                        ${currentCity.city_name}, Cebu<br>
                        Central Visayas
                    `).openPopup();
                
                map.setView([lat, lng], 15);
                
                // Save coordinates
                document.getElementById('latitude').value = lat;
                document.getElementById('longitude').value = lng;
            } else {
                // Barangay location not found, but still valid selection
                console.log('Barangay location not found on map');
                alert('Barangay selected but location not found on map. You can still proceed with checkout.');
            }
        } catch (error) {
            console.error('Search error:', error);
        }
    }
    
    // ==========================================
    // MAP CLICK HANDLER - Auto-detect and change barangay
    // ==========================================
    map.on('click', async function(e) {
        if (!currentCity) {
            alert('Please select a city from the dropdown first.');
            return;
        }
        
        const lat = e.latlng.lat;
        const lng = e.latlng.lng;
        
        // Update coordinates
        document.getElementById('latitude').value = lat;
        document.getElementById('longitude').value = lng;
        
        // Update marker
        if (marker) map.removeLayer(marker);
        marker = L.marker([lat, lng]).addTo(map);
        
        // Try to detect barangay from clicked location
        const data = await reverseGeocode(lat, lng);
        
        if (data && data.address) {
            const barangayName = data.address.suburb || 
                                data.address.neighbourhood || 
                                data.address.quarter ||
                                data.address.city_district ||
                                data.address.village;
            
            // Find matching barangay in current city's data
            const matchedBarangay = findMatchingBarangay(barangayName);
            
            if (matchedBarangay) {
                // Update dropdown selection
                barangaySelect.value = matchedBarangay.id;
                selectedBarangay = matchedBarangay;
                
                // Update address display
                updateBarangayInfo(matchedBarangay);
                
                // Show popup
                marker.bindPopup(`
                    <strong>${matchedBarangay.barangay_name}</strong><br>
                    ${currentCity.city_name}, Cebu<br>
                    Central Visayas<br>
                    <small class="text-success">✓ Auto-detected from map</small>
                `).openPopup();
            } else {
                // Location clicked but barangay not found in database
                marker.bindPopup(`
                    <div class="text-center">
                        <i class="bi bi-exclamation-triangle text-warning"></i><br>
                        <strong>Location selected</strong><br>
                        <small>Could not auto-detect barangay.<br>Please select from dropdown.</small>
                    </div>
                `).openPopup();
                
                console.log('Detected location name:', barangayName);
                console.log('Available barangays:', barangaysData.map(b => b.barangay_name));
            }
        } else {
            // Geocoding failed
            marker.bindPopup(`
                <div class="text-center">
                    <i class="bi bi-geo-alt text-info"></i><br>
                    <strong>Location marked</strong><br>
                    <small>Please select barangay from dropdown</small>
                </div>
            `).openPopup();
        }
    });
    
    // ==========================================
    // REVERSE GEOCODING
    // ==========================================
    async function reverseGeocode(lat, lng) {
        try {
            const response = await fetch(
                `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&addressdetails=1&zoom=18`
            );
            return await response.json();
        } catch (error) {
            console.error('Geocoding error:', error);
            return null;
        }
    }
    
    // Find matching barangay from location name
    function findMatchingBarangay(locationName) {
        if (!locationName || barangaysData.length === 0) return null;
        
        const searchTerm = locationName.toLowerCase()
            .replace(/\s+/g, ' ')
            .trim();
        
        // Try exact match
        let match = barangaysData.find(b => 
            b.barangay_name.toLowerCase() === searchTerm
        );
        
        // Try partial match (contains)
        if (!match) {
            match = barangaysData.find(b => {
                const barangayLower = b.barangay_name.toLowerCase();
                return barangayLower.includes(searchTerm) || 
                       searchTerm.includes(barangayLower);
            });
        }
        
        // Try word-by-word match
        if (!match) {
            const searchWords = searchTerm.split(' ');
            match = barangaysData.find(b => {
                const barangayWords = b.barangay_name.toLowerCase().split(' ');
                return searchWords.some(sw => barangayWords.includes(sw));
            });
        }
        
        return match;
    }
    
    // ==========================================
    // FORM VALIDATION
    // ==========================================
    document.getElementById('checkoutForm').addEventListener('submit', function(e) {
        const cityId = citySelect.value;
        const barangayId = barangaySelect.value;
        
        if (!cityId) {
            e.preventDefault();
            alert('Please select a city.');
            citySelect.focus();
            return false;
        }
        
        if (!barangayId) {
            e.preventDefault();
            alert('Please select a barangay.');
            barangaySelect.focus();
            return false;
        }
        
        // Coordinates are optional since we're using dropdown selection
        console.log('Form validated successfully');
    });
    
    // ==========================================
    // GEOLOCATION (Try to detect user's location)
    // ==========================================
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            function(position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                
                // Check which city the user might be in
                for (const [cityId, config] of Object.entries(DEFAULT_CITY_BOUNDS)) {
                    const bounds = config.bounds;
                    if (lat >= bounds[0][0] && lat <= bounds[1][0] && 
                        lng >= bounds[0][1] && lng <= bounds[1][1]) {
                        map.setView([lat, lng], 15);
                        break;
                    }
                }
            },
            function(error) {
                console.log('Location access denied or unavailable');
            }
        );
    }
</script>
</body>
</html>