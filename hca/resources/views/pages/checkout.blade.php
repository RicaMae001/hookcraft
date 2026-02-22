<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hookcraft Avenue - Secure Checkout</title>
    <link rel="icon" href="{{ asset('asset/images/logo.jpg') }}" type="image/png">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
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
            --shadow-sm: 0 1px 3px rgba(0,0,0,0.05);
            --shadow-md: 0 4px 6px rgba(0,0,0,0.07);
            --shadow-lg: 0 10px 25px rgba(0,0,0,0.1);
            --transition: all 0.25s cubic-bezier(0.4,0,0.2,1);
            --border-radius: 12px;
        }

        *, *::before, *::after { box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            background-color: #f9f9f9;
            color: var(--dark-navy);
            line-height: 1.6;
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
        }

        .container { max-width: 1200px; margin: 0 auto; padding: 0 1rem; }

        /* ── Page Header ── */
        .page-header { text-align: center; margin-bottom: 2rem; padding-top: 1rem; }
        .page-header h2 {
            font-size: 2rem; font-weight: 700; color: var(--dark-navy);
            margin-bottom: 0.75rem; letter-spacing: -0.025em;
        }
        .page-header .subtitle { color: var(--secondary-gray); font-size: 1rem; max-width: 600px; margin: 0 auto; }

        /* ── Cards ── */
        .card {
            border: none; border-radius: var(--border-radius);
            box-shadow: var(--shadow-md); overflow: hidden;
            background: var(--white); transition: var(--transition); margin-bottom: 1rem;
        }
        .card:hover { box-shadow: var(--shadow-lg); }
        .card-header {
            background: linear-gradient(135deg, #f8f9fa, #ffffff);
            color: var(--dark-navy); padding: 1.25rem 1.5rem;
            font-weight: 600; display: flex; align-items: center;
            gap: 0.75rem; border-bottom: 1px solid var(--border-color); font-size: 1rem;
        }
        .card-header i { color: var(--primary-pink); }
        .card-body { padding: 1.5rem; }

        /* ── User Info ── */
        .user-info-display {
            background: linear-gradient(135deg, rgba(248,231,243,0.4), rgba(248,231,243,0.2));
            border: 1px solid rgba(214,51,132,0.2);
            padding: 1rem; border-radius: 10px; margin-bottom: 1rem;
        }
        .user-info-title {
            font-size: 1rem; font-weight: 600; color: var(--dark-navy);
            margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.5rem;
        }
        .user-info-one-line {
            background: linear-gradient(135deg, rgba(248,249,250,0.8), rgba(255,255,255,0.9));
            border: 1px solid rgba(0,0,0,0.08);
            padding: 1rem; border-radius: 10px; margin-bottom: 1rem;
        }
        .user-info-one-line-content { display: flex; align-items: center; gap: 2rem; flex-wrap: wrap; }
        .user-info-item { display: flex; align-items: baseline; gap: 0.5rem; }
        .user-info-label {
            font-size: 0.8rem; font-weight: 600; color: var(--secondary-gray);
            text-transform: uppercase; letter-spacing: 0.5px; min-width: 80px;
        }
        .user-info-value { font-size: 0.95rem; font-weight: 500; color: var(--dark-navy); }

        /* ── Location Info box ── */
        .location-info {
            background: linear-gradient(135deg, #e3f2fd, #f0f4ff);
            border: 1px solid #90caf9; border-radius: 8px;
            padding: 0.75rem 1rem; margin-bottom: 1rem;
            font-size: 0.85rem; color: #1565c0;
            display: flex; align-items: flex-start; gap: 0.5rem;
        }

        /* ── Address display ── */
        .address-display {
            background: linear-gradient(135deg, #f8f9fa, #fff);
            border: 1px solid var(--border-color);
            border-radius: 8px; padding: 0.75rem; margin-top: 0.75rem; display: none;
        }
        .address-display.active { display: block; }
        .location-badge {
            display: inline-block;
            background: linear-gradient(135deg, #f3e5f5, #e1bee7);
            color: #6a1b9a; padding: 3px 10px; border-radius: 12px;
            font-size: 0.75rem; font-weight: 500; margin: 2px; border: 1px solid #ce93d8;
        }

        /* ── Form elements ── */
        .form-group { margin-bottom: 1rem; }
        .form-label {
            font-weight: 600; color: var(--dark-navy);
            margin-bottom: 0.25rem; display: flex; align-items: center;
            gap: 0.5rem; font-size: 0.9rem;
        }
        .form-control, .form-select {
            border-radius: 8px; border: 1px solid var(--border-color);
            padding: 0.625rem 0.875rem; font-size: 0.9rem;
            transition: var(--transition); background: var(--white);
            color: var(--dark-navy); width: 100%;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-pink);
            box-shadow: 0 0 0 0.2rem rgba(214,51,132,0.15); outline: none;
        }
        .input-group { display: flex; }
        .input-group-text {
            display: flex; align-items: center; padding: 0.625rem 0.875rem;
            background: var(--light-gray); border: 1px solid var(--border-color);
            border-right: none; border-radius: 8px 0 0 8px; font-size: 0.9rem;
        }
        .input-group .form-control { border-left: none; border-radius: 0 8px 8px 0; }

        /* ── Map ── */
        #map {
            height: 300px; width: 100%;
            border-radius: 8px; margin-bottom: 10px; border: 2px solid var(--border-color);
        }

        /* ── Right column ── */
        .right-column { position: sticky; top: 2rem; }
        .order-summary-wrapper { display: flex; flex-direction: column; gap: 1rem; }

        /* ── Product items ── */
        .product-item {
            display: flex; align-items: center; padding: 0.5rem;
            border-bottom: 1px solid var(--border-color); transition: var(--transition);
        }
        .product-item:last-child { border-bottom: none; }
        .product-item:hover { background-color: #f8f9fa; }
        .product-image {
            width: 60px; height: 60px; object-fit: cover; border-radius: 6px;
            margin-right: 0.5rem; border: 1px solid var(--border-color); flex-shrink: 0;
        }
        .product-details { flex: 1; min-width: 0; }
        .product-name {
            font-weight: 600; color: var(--dark-navy); font-size: 0.85rem;
            margin-bottom: 0.125rem; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
        }
        .product-meta { color: var(--secondary-gray); font-size: 0.75rem; margin-bottom: 0.125rem; }
        .product-price { font-weight: 700; color: var(--primary-pink); font-size: 0.9rem; }

        /* ── Summary sections ── */
        .summary-section { padding: 0.5rem 0; border-bottom: 1px solid var(--border-color); }
        .summary-section:last-child { border-bottom: none; }
        .summary-row {
            display: flex; justify-content: space-between;
            align-items: center; margin-bottom: 0.3rem; font-size: 0.85rem;
        }
        .summary-row:last-child { margin-bottom: 0; }

        /* ── Delivery fee box ── */
        .delivery-fee-box {
            background: linear-gradient(135deg, #fff8e1, #fef3c7);
            border: 1px solid #f59e0b; border-radius: 8px;
            padding: 0.6rem 0.75rem; margin: 0.35rem 0;
        }
        .delivery-fee-box .fee-row {
            display: flex; justify-content: space-between;
            align-items: center; font-size: 0.85rem;
        }
        .delivery-fee-box .fee-label { color: #92400e; font-weight: 600; }
        .delivery-fee-box .fee-value { color: #92400e; font-weight: 700; }
        .delivery-route-mini {
            font-size: 0.7rem; color: #b45309; margin-top: 0.25rem;
            display: flex; align-items: center; gap: 0.25rem; flex-wrap: wrap;
        }

        /* Spinner */
        .fee-spinner {
            display: inline-block; width: 13px; height: 13px;
            border: 2px solid rgba(214,51,132,0.2); border-top-color: var(--primary-pink);
            border-radius: 50%; animation: spin 0.75s linear infinite;
            vertical-align: middle; margin-right: 4px;
        }
        @keyframes spin { to { transform: rotate(360deg); } }

        /* ── Grand total row ── */
        .grand-total-row {
            display: flex; justify-content: space-between; align-items: center;
            background: linear-gradient(135deg, rgba(214,51,132,0.07), rgba(233,30,99,0.04));
            border: 1px solid rgba(214,51,132,0.2); border-radius: 10px;
            padding: 0.75rem 1rem; margin-top: 0.5rem;
        }
        .grand-total-row .gt-label { font-size: 0.95rem; font-weight: 700; color: var(--dark-navy); }
        .grand-total-row .gt-value { font-size: 1.15rem; font-weight: 800; color: var(--primary-pink); }

        /* ── Security badges ── */
        .security-badge {
            display: inline-flex; align-items: center; gap: 0.25rem;
            background: #e8f5e9; color: #2e7d32; padding: 0.25rem 0.5rem;
            border-radius: 12px; font-size: 0.7rem;
            margin-right: 0.25rem; margin-bottom: 0.25rem;
        }

        /* ── Payment options ── */
        .payment-options {
            display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            gap: 0.5rem; margin-top: 0.25rem;
        }
        .payment-option {
            border: 2px solid var(--border-color); border-radius: 8px;
            padding: 0.75rem; cursor: pointer; transition: var(--transition);
            background: var(--white); user-select: none;
        }
        .payment-option:hover { border-color: var(--primary-pink); transform: translateY(-2px); }
        .payment-option.selected {
            border-color: var(--primary-pink);
            background: linear-gradient(135deg, rgba(214,51,132,0.05), rgba(233,30,99,0.05));
            box-shadow: 0 0 0 1px var(--primary-pink);
        }
        .payment-option .icon {
            width: 35px; height: 35px; border-radius: 8px;
            background: linear-gradient(135deg, var(--primary-pink), var(--accent-rose));
            color: white; display: flex; align-items: center;
            justify-content: center; font-size: 1rem; margin-bottom: 0.5rem;
        }
        .payment-option h6 {
            font-weight: 600; margin-bottom: 0.125rem;
            color: var(--dark-navy); font-size: 0.9rem;
        }
        .payment-option p { color: var(--secondary-gray); font-size: 0.75rem; margin: 0; line-height: 1.2; }

        /* ── Buttons ── */
        .btn {
            border-radius: 8px; padding: 0.75rem 1.5rem; font-weight: 600;
            transition: var(--transition); border: none; font-size: 0.95rem;
            cursor: pointer; display: inline-flex; align-items: center;
            justify-content: center; gap: 0.4rem; text-decoration: none;
        }
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-pink), var(--accent-rose));
            color: white;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 20px rgba(214,51,132,0.3); color: white;
        }
        .btn-outline-secondary {
            background: transparent; color: var(--secondary-gray);
            border: 1px solid var(--border-color);
        }
        .btn-outline-secondary:hover { background: var(--light-gray); color: var(--dark-navy); }
        .btn-outline-primary {
            background: transparent; color: var(--primary-pink);
            border: 1px solid var(--primary-pink);
        }
        .btn-outline-primary:hover { background: var(--primary-pink); color: white; }
        .btn-sm { padding: 0.5rem 0.9rem; font-size: 0.85rem; }
        .w-100 { width: 100%; }

        /* ── Alerts ── */
        .alert {
            padding: 0.75rem 1rem; border-radius: 8px; margin-bottom: 1rem;
            display: flex; align-items: flex-start; gap: 0.5rem; font-size: 0.875rem;
        }
        .alert-danger  { background: #fef2f2; border: 1px solid #fca5a5; color: #991b1b; }
        .alert-success { background: #f0fdf4; border: 1px solid #86efac; color: #166534; }
        .alert-info    { background: #eff6ff; border: 1px solid #93c5fd; color: #1e40af; }
        .alert-warning { background: #fef3c7; border: 1px solid #f59e0b; color: #92400e; }
        .btn-close {
            margin-left: auto; background: none; border: none;
            cursor: pointer; font-size: 1.1rem; opacity: 0.6; line-height: 1;
        }
        .btn-close:hover { opacity: 1; }

        /* ── Empty state ── */
        .empty-state { text-align: center; padding: 4rem 1rem; }
        .empty-icon { font-size: 4rem; color: var(--border-color); margin-bottom: 1rem; }

        /* ── Layout ── */
        .row { display: flex; flex-wrap: wrap; }
        .row.g-4 { gap: 1.5rem; flex-wrap: nowrap; }
        .col-lg-8 { width: 66.6667%; flex-shrink: 0; }
        .col-lg-4 { width: 33.3333%; flex-shrink: 0; }
        .col-md-6 { width: 50%; padding: 0 0.375rem; }
        .cols-row { display: flex; margin: 0 -0.375rem; flex-wrap: wrap; }

        /* ── Responsive ── */
        @media (max-width: 991px) {
            .row.g-4 { flex-wrap: wrap; gap: 1rem; }
            .col-lg-8, .col-lg-4 { width: 100%; }
            .right-column { position: static; }
            #map { height: 250px; }
        }
        @media (max-width: 768px) {
            .page-header h2 { font-size: 1.5rem; }
            .card-body { padding: 1rem; }
            .card-header { padding: 1rem 1.25rem; }
            .payment-options { grid-template-columns: 1fr; }
            .product-image { width: 50px; height: 50px; }
            #map { height: 200px; }
            .user-info-one-line-content { flex-direction: column; gap: 0.75rem; }
            .col-md-6 { width: 100%; }
        }
        @media (max-width: 576px) {
            .container { padding: 0 0.75rem; }
            .card-header { padding: 0.75rem 1rem; }
        }
    </style>
</head>
<body>

@include('components.login_modal')
@include('components.signup_modal')
@include('components.navbar')

<div class="container">

    <div class="page-header">
        <h2>Secure Checkout</h2>
        <p class="subtitle">Complete your purchase with confidence — Your handmade treasures await</p>
    </div>

    @if(session('error'))
        <div class="alert alert-danger">
            <i class="bi bi-exclamation-triangle"></i>
            <span>{{ session('error') }}</span>
            <button class="btn-close" onclick="this.parentElement.remove()">×</button>
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success">
            <i class="bi bi-check-circle"></i>
            <span>{{ session('success') }}</span>
            <button class="btn-close" onclick="this.parentElement.remove()">×</button>
        </div>
    @endif

    @if($cartItems->isEmpty())
        <div class="empty-state">
            <div class="empty-icon"><i class="bi bi-bag-x"></i></div>
            <h3 style="margin-bottom:0.5rem;">Your cart is empty</h3>
            <p style="color:var(--secondary-gray);margin-bottom:1.5rem;">
                Discover our unique handmade collection and add items to your cart
            </p>
            <a href="{{ route('shop') }}" class="btn btn-primary">
                <i class="bi bi-arrow-left"></i>Start Shopping
            </a>
        </div>

    @else

    <div class="row g-4">

        {{-- ══ LEFT — Checkout Form ══ --}}
        <div class="col-lg-8">
            <form method="POST" action="{{ route('checkout.store') }}" id="checkoutForm">
                @csrf

                {{-- Customer Information --}}
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-person-circle"></i>Your Information
                    </div>
                    <div class="card-body">

                        <div class="user-info-display">
                            <div class="user-info-title">
                                <i class="bi bi-person-check"></i>Account Details
                            </div>
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
                            <span style="display:inline-flex;align-items:center;gap:4px;
                                         background:rgba(25,135,84,0.1);color:#198754;
                                         border:1px solid rgba(25,135,84,0.25);
                                         padding:3px 10px;border-radius:50rem;font-size:0.75rem;font-weight:600;">
                                <i class="bi bi-shield-check"></i>Account Verified
                            </span>
                        </div>

                        <input type="hidden" name="name"  value="{{ Auth::user()->name }}">
                        <input type="hidden" name="email" value="{{ Auth::user()->email }}">

                        <div class="form-group" style="margin-top:0.75rem;">
                            <label class="form-label">
                                <i class="bi bi-phone"></i>Phone Number *
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-telephone"></i>
                                </span>
                                <input type="tel" class="form-control"
                                       name="phone"
                                       value="{{ old('phone', Auth::user()->phone ?? '') }}"
                                       placeholder="09123456789"
                                       pattern="[0-9]{11}" maxlength="11" required>
                            </div>
                            <small style="color:var(--secondary-gray);display:block;margin-top:0.35rem;font-size:0.78rem;">
                                <i class="bi bi-info-circle" style="margin-right:3px;"></i>
                                Format: 09123456789 — We'll use this for delivery updates
                            </small>
                        </div>
                    </div>
                </div>

                {{-- Delivery Address --}}
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-geo-alt"></i>Delivery Address
                    </div>
                    <div class="card-body">

                        <div class="location-info" id="locationInfoBox">
                            <i class="bi bi-info-circle" style="flex-shrink:0;margin-top:2px;"></i>
                            <span>
                                <strong>Service Area:</strong>
                                We currently deliver within Cebu City
                            </span>
                        </div>

                        <div class="cols-row">
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
                                <i class="bi bi-signpost"></i>Street Address / House No. *
                            </label>
                            <input type="text" class="form-control" name="street" id="street"
                                   placeholder="e.g., 123 Main Street, Building Name, Floor/Unit" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <i class="bi bi-map"></i>Location Map
                            </label>
                            <div id="map"></div>
                            <small style="color:var(--secondary-gray);font-size:0.78rem;">
                                Click on the map or select a barangay to pin your delivery location.
                                The dashed line shows the route from our store.
                            </small>
                        </div>

                        <div class="address-display" id="addressDisplay">
                            <small style="color:var(--secondary-gray);display:block;margin-bottom:4px;">
                                Selected Address:
                            </small>
                            <span class="location-badge">Central Visayas</span>
                            <span class="location-badge">Cebu</span>
                            <span class="location-badge" id="selectedCity">—</span>
                            <span class="location-badge" id="selectedBarangay">—</span>
                        </div>

                        {{-- Hidden location fields --}}
                        <input type="hidden" name="region_id"   id="regionId"   value="1">
                        <input type="hidden" name="province_id" id="provinceId" value="1">
                        <input type="hidden" id="barangayId">
                        <input type="hidden" name="latitude"   id="latitude">
                        <input type="hidden" name="longitude"  id="longitude">

                        {{-- Delivery fee hidden fields --}}
                        <input type="hidden" name="delivery_fee"         id="deliveryFeeInput"      value="0">
                        <input type="hidden" name="delivery_distance_km" id="deliveryDistanceInput" value="0">
                        <input type="hidden" name="grand_total"          id="grandTotalInput"       value="{{ $total }}">

                    </div>
                </div>

            </form>
        </div>

        {{-- ══ RIGHT — Order Summary + Payment ══ --}}
        <div class="col-lg-4">
            <div class="right-column">
                <div class="order-summary-wrapper">

                    {{-- Order Summary --}}
                    <div class="card">
                        <div class="card-header">
                            <i class="bi bi-receipt"></i>Order Summary
                        </div>
                        <div class="card-body">

                            {{-- Items --}}
                            <div class="summary-section">
                                <h6 style="font-size:0.9rem;margin-bottom:0.5rem;">
                                    Items ({{ $cartItems->count() }})
                                </h6>

                                @foreach($cartItems as $item)
                                    @php
                                        $isCustom  = $item->is_customization && $item->customization;
                                        $itemPrice = $isCustom ? $item->customization->admin_price : $item->product->price;
                                        $itemName  = $isCustom ? $item->product->name . ' (Custom)' : $item->product->name;
                                        $itemImg   = $isCustom && $item->customization->image
                                                        ? $item->customization->image
                                                        : $item->product->image;
                                    @endphp
                                    <div class="product-item">
                                        <img src="{{ asset('asset/images/' . $itemImg) }}"
                                             alt="{{ $itemName }}"
                                             class="product-image">
                                        <div class="product-details">
                                            <div class="product-name">{{ $itemName }}</div>
                                            <div class="product-meta">
                                                {{ $item->quantity }} × ₱{{ number_format($itemPrice, 2) }}
                                            </div>
                                            <div class="product-price">
                                                ₱{{ number_format($item->quantity * $itemPrice, 2) }}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            {{-- Price Breakdown --}}
                            <div class="summary-section">

                                <div class="summary-row">
                                    <span>Subtotal</span>
                                    <span>₱{{ number_format($total, 2) }}</span>
                                </div>

                                {{-- Delivery Fee — updated live by JS --}}
                                <div class="delivery-fee-box">
                                    <div class="fee-row">
                                        <span class="fee-label">
                                            <i class="bi bi-truck" style="margin-right:3px;"></i>Delivery Fee
                                        </span>
                                        <span class="fee-value" id="deliveryFeeDisplay">
                                            <span style="font-size:0.78rem;font-weight:400;color:#b45309;">
                                                Select address
                                            </span>
                                        </span>
                                    </div>
                                    <div class="delivery-route-mini" id="deliveryRouteInfo" style="display:none;">
                                        <i class="bi bi-geo-alt-fill" style="color:#dc3545;font-size:0.68rem;"></i>
                                        Hipodromo, Cebu City
                                        <i class="bi bi-arrow-right" style="font-size:0.65rem;opacity:0.6;"></i>
                                        <i class="bi bi-house-fill" style="color:#15803d;font-size:0.68rem;"></i>
                                        <strong id="routeDestLabel">—</strong>
                                        &nbsp;·&nbsp;~<strong id="routeDistLabel">0</strong> km
                                    </div>
                                </div>

                                <div class="summary-row">
                                    <span>Tax</span>
                                    <span style="color:#198754;">Included</span>
                                </div>

                                {{-- Grand Total --}}
                                <div class="grand-total-row">
                                    <span class="gt-label">
                                        <i class="bi bi-receipt" style="margin-right:4px;"></i>Grand Total
                                    </span>
                                    <span class="gt-value" id="grandTotalDisplay">
                                        ₱{{ number_format($total, 2) }}
                                    </span>
                                </div>

                                {{-- COD cash reminder --}}
                                <div id="codReminder"
                                     class="alert alert-warning"
                                     style="display:none;margin-top:0.5rem;padding:0.5rem 0.75rem;">
                                    <i class="bi bi-cash"></i>
                                    <span>
                                        Please prepare
                                        <strong id="codAmount">₱0.00</strong>
                                        in cash upon delivery.
                                    </span>
                                </div>

                            </div>

                            {{-- Security + support --}}
                            <div class="summary-section">
                                <div style="display:flex;flex-wrap:wrap;margin-bottom:0.5rem;">
                                    <span class="security-badge">
                                        <i class="bi bi-shield-check"></i>SSL Secure
                                    </span>
                                    <span class="security-badge" style="background:#e3f2fd;color:#1565c0;">
                                        <i class="bi bi-truck"></i>Fast Delivery
                                    </span>
                                </div>
                                <div class="alert alert-info"
                                     style="margin-bottom:0;padding:0.45rem 0.7rem;font-size:0.8rem;">
                                    <i class="bi bi-info-circle"></i>
                                    Need help?
                                    <a href="{{ route('chatbot') }}" style="font-weight:700;color:inherit;">Contact Support</a>
                                </div>
                            </div>

                            <div style="text-align:center;margin-top:0.75rem;">
                                <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary btn-sm">
                                    <i class="bi bi-arrow-left"></i>Back to Cart
                                </a>
                                <a href="{{ route('shop') }}" class="btn btn-outline-primary btn-sm" style="margin-left:0.35rem;">
                                    <i class="bi bi-bag-plus"></i>Shop More
                                </a>
                            </div>

                        </div>
                    </div>

                    {{-- Payment Method --}}
                    <div class="card">
                        <div class="card-header">
                            <i class="bi bi-credit-card"></i>Payment Method
                        </div>
                        <div class="card-body">

                            <div class="payment-options">
                                <div class="payment-option selected"
                                     id="optCOD"
                                     onclick="selectPayment('COD', this)">
                                    <div class="icon"><i class="bi bi-cash"></i></div>
                                    <h6>Cash on Delivery</h6>
                                    <p>Pay when order arrives</p>
                                    <input type="radio" name="payment_method" value="COD"
                                           id="paymentCOD" style="display:none;"
                                           form="checkoutForm" checked>
                                </div>

                                {{-- Uncomment to enable GCash:
                                <div class="payment-option"
                                     id="optGCash"
                                     onclick="selectPayment('GCash', this)">
                                    <div class="icon"><i class="bi bi-phone"></i></div>
                                    <h6>GCash</h6>
                                    <p>Pay via GCash transfer</p>
                                    <input type="radio" name="payment_method" value="GCash"
                                           id="paymentGCash" style="display:none;"
                                           form="checkoutForm">
                                </div>
                                --}}
                            </div>

                            <div style="margin-top:1rem;">
                                <button type="submit"
                                        form="checkoutForm"
                                        class="btn btn-primary w-100"
                                        id="placeOrderBtn">
                                    <i class="bi bi-lock"></i>Place Secure Order
                                </button>
                                <p style="text-align:center;color:var(--secondary-gray);
                                           font-size:0.78rem;margin-top:0.5rem;margin-bottom:0;">
                                    By placing your order you agree to our
                                    <a href="#" style="color:var(--primary-pink);">Terms</a>
                                </p>
                            </div>

                            <div style="margin-top:1rem;padding-top:1rem;border-top:1px solid var(--border-color);">
                                <div style="display:flex;align-items:center;gap:0.5rem;">
                                    <i class="bi bi-shield-check" style="color:#198754;font-size:1.25rem;"></i>
                                    <div>
                                        <strong style="font-size:0.85rem;">Secure Payment</strong>
                                        <p style="color:var(--secondary-gray);font-size:0.75rem;margin:0;">
                                            Payment information is encrypted
                                        </p>
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
// ════════════════════════════════════════════════════════════════
//  DELIVERY FEE
//  Origin : Hipodromo, Cebu City
//  Formula: haversine × 1.3 road-factor
//           ₱40 base (first 2 km) + ₱15/km after → round up ₱5 → cap ₱200
// ════════════════════════════════════════════════════════════════

// const ORIGIN = { lat: 10.3219, lng: 123.9019 };
const ORIGIN = { lat: 10.3141, lng: 123.9070 };
const DELIVERY = {
    baseFee:    40,
    baseKm:      2,
    ratePerKm:  15,
    maxFee:    200,
    roadFactor: 1.3
};

const SUBTOTAL = {{ $total }};

// ── City configs ──────────────────────────────────────────────
const CITY_CONFIGS = {
    1: { name: 'Cebu City',      center: [10.3157, 123.8854], bounds: [[10.25, 123.80], [10.38, 123.97]], minZoom: 12, maxZoom: 18 },
    2: { name: 'Lapu-Lapu City', center: [10.3103, 123.9494], bounds: [[10.27, 123.90], [10.35, 124.00]], minZoom: 13, maxZoom: 18 },
    3: { name: 'Mandaue City',   center: [10.3237, 123.9227], bounds: [[10.28, 123.88], [10.37, 123.97]], minZoom: 13, maxZoom: 18 },
    4: { name: 'Talisay City',   center: [10.2444, 123.8493], bounds: [[10.20, 123.81], [10.29, 123.89]], minZoom: 13, maxZoom: 18 }
};

// ── State ─────────────────────────────────────────────────────
let citiesData    = [];
let barangaysData = [];
let currentCity   = null;
let currentFee    = 0;

// ── DOM refs ──────────────────────────────────────────────────
const citySelect      = document.getElementById('citySelect');
const barangaySelect  = document.getElementById('barangaySelect');
const locationInfoBox = document.getElementById('locationInfoBox');

// ════════════════════════════════════════════════════════════════
//  PAYMENT SELECTION
// ════════════════════════════════════════════════════════════════
function selectPayment(method, el) {
    document.querySelectorAll('.payment-option').forEach(o => o.classList.remove('selected'));
    el.classList.add('selected');
    document.getElementById('payment' + method).checked = true;
    refreshCodReminder();
}

// ════════════════════════════════════════════════════════════════
//  DELIVERY FEE HELPERS
// ════════════════════════════════════════════════════════════════
function haversineKm(lat1, lng1, lat2, lng2) {
    const R  = 6371;
    const dL = (lat2 - lat1) * Math.PI / 180;
    const dG = (lng2 - lng1) * Math.PI / 180;
    const a  = Math.sin(dL / 2) ** 2
             + Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180)
             * Math.sin(dG / 2) ** 2;
    return R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
}

function computeFee(destLat, destLng) {
    const straight = haversineKm(ORIGIN.lat, ORIGIN.lng, destLat, destLng);
    const road     = Math.round(straight * DELIVERY.roadFactor * 10) / 10;
    let   fee      = road <= DELIVERY.baseKm
        ? DELIVERY.baseFee
        : DELIVERY.baseFee + (road - DELIVERY.baseKm) * DELIVERY.ratePerKm;
    fee = Math.min(Math.ceil(fee / 5) * 5, DELIVERY.maxFee);
    return { fee, road };
}

function applyDeliveryFee(destLat, destLng, labelName) {
    const { fee, road } = computeFee(destLat, destLng);
    currentFee = fee;
    const grand = SUBTOTAL + fee;

    document.getElementById('deliveryFeeDisplay').innerHTML = '<strong>₱' + fee.toFixed(2) + '</strong>';
    document.getElementById('grandTotalDisplay').textContent =
        '₱' + grand.toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

    document.getElementById('routeDestLabel').textContent      = labelName;
    document.getElementById('routeDistLabel').textContent      = road.toFixed(1);
    document.getElementById('deliveryRouteInfo').style.display = 'flex';

    document.getElementById('deliveryFeeInput').value      = fee.toFixed(2);
    document.getElementById('deliveryDistanceInput').value = road.toFixed(1);
    document.getElementById('grandTotalInput').value       = grand.toFixed(2);

    refreshCodReminder();
}

function refreshCodReminder() {
    const isCOD = document.getElementById('paymentCOD').checked;
    const rem   = document.getElementById('codReminder');
    if (isCOD && currentFee > 0) {
        const grand = SUBTOTAL + currentFee;
        document.getElementById('codAmount').textContent =
            '₱' + grand.toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        rem.style.display = 'flex';
    } else {
        rem.style.display = 'none';
    }
}

function showCalculating() {
    document.getElementById('deliveryFeeDisplay').innerHTML =
        '<span class="fee-spinner"></span><span style="font-size:0.78rem;color:#b45309;">Calculating…</span>';
    document.getElementById('deliveryRouteInfo').style.display = 'none';
    document.getElementById('codReminder').style.display       = 'none';
}

function resetFee() {
    currentFee = 0;
    document.getElementById('deliveryFeeDisplay').innerHTML =
        '<span style="font-size:0.78rem;font-weight:400;color:#b45309;">Select address</span>';
    document.getElementById('grandTotalDisplay').textContent =
        '₱' + SUBTOTAL.toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    document.getElementById('deliveryRouteInfo').style.display = 'none';
    document.getElementById('deliveryFeeInput').value          = '0';
    document.getElementById('deliveryDistanceInput').value     = '0';
    document.getElementById('grandTotalInput').value           = SUBTOTAL.toFixed(2);
    document.getElementById('codReminder').style.display       = 'none';
}

// ════════════════════════════════════════════════════════════════
//  MAP SETUP
// ════════════════════════════════════════════════════════════════
const map = L.map('map', {
    center: [10.3219, 123.9019], zoom: 13, minZoom: 11, maxZoom: 18,
    maxBounds: [[10.15, 123.75], [10.45, 124.05]], maxBoundsViscosity: 1.0
});

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap', maxZoom: 18
}).addTo(map);

// Store pin
L.marker([ORIGIN.lat, ORIGIN.lng], {
    icon: L.divIcon({
        html: `<div style="position:relative;">
                 <div style="background:#dc3545;width:14px;height:14px;border-radius:50%;
                      border:3px solid #fff;box-shadow:0 2px 6px rgba(0,0,0,0.4);"></div>
                 <div style="position:absolute;top:-24px;left:50%;transform:translateX(-50%);
                      background:#dc3545;color:#fff;font-size:9px;padding:2px 6px;
                      border-radius:4px;white-space:nowrap;font-weight:700;">📦 Store</div>
               </div>`,
        iconSize: [14, 14], iconAnchor: [7, 7]
    })
}).addTo(map).bindPopup('<strong>📦 Hookcraft Avenue</strong><br>Hipodromo, Cebu City');

let marker       = null;
let boundaryRect = null;
let routeLine    = null;

function drawBoundary(bounds) {
    if (boundaryRect) map.removeLayer(boundaryRect);
    boundaryRect = L.rectangle(bounds, {
        color: '#d63384', weight: 2, fillOpacity: 0.05, fillColor: '#d63384'
    }).addTo(map);
}

function drawRoute(destLat, destLng) {
    if (routeLine) map.removeLayer(routeLine);
    routeLine = L.polyline(
        [[ORIGIN.lat, ORIGIN.lng], [destLat, destLng]],
        { color: '#d63384', weight: 2, opacity: 0.6, dashArray: '8 5' }
    ).addTo(map);
}

function placeMarker(lat, lng, popupText) {
    document.getElementById('latitude').value  = lat;
    document.getElementById('longitude').value = lng;
    if (marker) map.removeLayer(marker);
    marker = L.marker([lat, lng], {
        icon: L.divIcon({
            html: '<div style="background:#d63384;width:20px;height:20px;border-radius:50%;border:3px solid #fff;box-shadow:0 2px 6px rgba(0,0,0,0.3);"></div>',
            iconSize: [20, 20], iconAnchor: [10, 10]
        })
    }).addTo(map);
    if (popupText) marker.bindPopup('<strong>' + popupText + '</strong>').openPopup();
    map.setView([lat, lng], 15);
}

function inBounds(lat, lng, cityId) {
    if (!CITY_CONFIGS[cityId]) return false;
    const b = CITY_CONFIGS[cityId].bounds;
    return lat >= b[0][0] && lat <= b[1][0] && lng >= b[0][1] && lng <= b[1][1];
}

// ════════════════════════════════════════════════════════════════
//  GEOLOCATION on page load
// ════════════════════════════════════════════════════════════════
document.addEventListener('DOMContentLoaded', function () {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            async pos => {
                const { latitude: lat, longitude: lng } = pos.coords;
                const id = detectCityId(lat, lng);
                await loadCities(id);
                if (id && citiesData.length) {
                    citySelect.value = id;
                    citySelect.dispatchEvent(new Event('change'));
                }
            },
            () => loadAllCities()
        );
    } else {
        loadAllCities();
    }
});

function detectCityId(lat, lng) {
    for (const [id, cfg] of Object.entries(CITY_CONFIGS)) {
        const b = cfg.bounds;
        if (lat >= b[0][0] && lat <= b[1][0] && lng >= b[0][1] && lng <= b[1][1])
            return parseInt(id);
    }
    return null;
}

// ════════════════════════════════════════════════════════════════
//  LOAD CITIES
// ════════════════════════════════════════════════════════════════
async function loadCities(detectedId) {
    try {
        const res  = await fetch('/api/locations/cities/1');
        const data = await res.json();
        citiesData = (detectedId && CITY_CONFIGS[detectedId])
            ? data.filter(c => c.id === detectedId)
            : data.filter(c => CITY_CONFIGS[c.id]);
        fillCityDropdown();
        if (detectedId && CITY_CONFIGS[detectedId]) {
            locationInfoBox.innerHTML =
                '<i class="bi bi-check-circle-fill" style="color:#15803d;flex-shrink:0;"></i>'
                + '<span><strong>Great!</strong> You\'re in our delivery area: <strong>'
                + CITY_CONFIGS[detectedId].name + '</strong></span>';
        }
    } catch { loadAllCities(); }
}

async function loadAllCities() {
    try {
        const res  = await fetch('/api/locations/cities/1');
        const data = await res.json();
        citiesData = data.filter(c => CITY_CONFIGS[c.id]);
        fillCityDropdown();
    } catch (e) { console.error('City load error:', e); }
}

function fillCityDropdown() {
    citySelect.innerHTML = '<option value="">Select City</option>';
    citiesData.forEach(c => citySelect.add(new Option(c.city_name, c.id)));
}

// ════════════════════════════════════════════════════════════════
//  CITY SELECTION
// ════════════════════════════════════════════════════════════════
citySelect.addEventListener('change', function () {
    const id = parseInt(this.value);
    if (!id) { resetCity(); return; }

    currentCity = citiesData.find(c => c.id === id);
    if (!currentCity || !CITY_CONFIGS[id]) return;

    const cfg = CITY_CONFIGS[id];
    map.setView(cfg.center, cfg.minZoom);
    map.setMinZoom(cfg.minZoom);
    map.setMaxZoom(cfg.maxZoom);
    map.setMaxBounds(cfg.bounds);
    drawBoundary(cfg.bounds);

    document.getElementById('selectedCity').textContent = currentCity.city_name;
    document.getElementById('addressDisplay').classList.add('active');

    loadBarangays(id);
    resetBarangay();
    resetFee();
});

function resetCity() {
    barangaySelect.disabled = true;
    barangaySelect.innerHTML = '<option value="">Select city first</option>';
    if (boundaryRect) map.removeLayer(boundaryRect);
    if (routeLine)    map.removeLayer(routeLine);
    document.getElementById('addressDisplay').classList.remove('active');
    resetBarangay();
    resetFee();
}

// ════════════════════════════════════════════════════════════════
//  BARANGAY LOADING + SELECTION
// ════════════════════════════════════════════════════════════════
function loadBarangays(cityId) {
    fetch('/api/locations/barangays/' + cityId)
        .then(r => r.json())
        .then(data => {
            barangaysData = data;
            barangaySelect.innerHTML = '<option value="">Choose Barangay</option>';
            data.forEach(b => barangaySelect.add(new Option(b.barangay_name, b.id)));
            barangaySelect.disabled = false;
        })
        .catch(e => console.error('Barangay load error:', e));
}

barangaySelect.addEventListener('change', async function () {
    const id = parseInt(this.value);
    if (!id) { resetBarangay(); resetFee(); return; }

    const selected = barangaysData.find(b => b.id === id);
    if (!selected) return;

    document.getElementById('selectedBarangay').textContent = selected.barangay_name;
    document.getElementById('barangayId').value             = selected.id;
    document.getElementById('addressDisplay').classList.add('active');

    showCalculating();
    await geocodeBarangay(selected);
});

function resetBarangay() {
    if (marker)    map.removeLayer(marker);
    if (routeLine) map.removeLayer(routeLine);
    document.getElementById('selectedBarangay').textContent = '—';
    document.getElementById('barangayId').value             = '';
    document.getElementById('latitude').value               = '';
    document.getElementById('longitude').value              = '';
}

// ════════════════════════════════════════════════════════════════
//  GEOCODING — barangay → lat/lng via Nominatim
// ════════════════════════════════════════════════════════════════
async function geocodeBarangay(barangay) {
    if (!currentCity) return;
    const label = barangay.barangay_name + ', ' + currentCity.city_name;

    try {
        const q   = barangay.barangay_name + ', ' + currentCity.city_name + ', Cebu, Philippines';
        const res = await fetch(
            'https://nominatim.openstreetmap.org/search?format=json&limit=1&countrycodes=ph&q='
            + encodeURIComponent(q)
        );
        const results = await res.json();

        let lat, lng;
        if (results.length > 0 && inBounds(parseFloat(results[0].lat), parseFloat(results[0].lon), currentCity.id)) {
            lat = parseFloat(results[0].lat);
            lng = parseFloat(results[0].lon);
        } else {
            [lat, lng] = CITY_CONFIGS[currentCity.id].center;
        }

        placeMarker(lat, lng, label);
        drawRoute(lat, lng);
        applyDeliveryFee(lat, lng, label);

    } catch {
        const [lat, lng] = CITY_CONFIGS[currentCity.id].center;
        placeMarker(lat, lng, label);
        drawRoute(lat, lng);
        applyDeliveryFee(lat, lng, label);
    }
}

// ── Map click — manual pin ────────────────────────────────────
map.on('click', async function (e) {
    if (!currentCity) { alert('Please select your city first.'); return; }

    const { lat, lng } = e.latlng;
    if (!inBounds(lat, lng, currentCity.id)) {
        alert('Please select a location within ' + currentCity.city_name + ' only.');
        return;
    }

    placeMarker(lat, lng, null);
    drawRoute(lat, lng);
    showCalculating();

    let label = currentCity.city_name;

    try {
        const res  = await fetch(
            'https://nominatim.openstreetmap.org/reverse?format=json&zoom=18&lat=' + lat + '&lon=' + lng
        );
        const data = await res.json();
        if (data && data.address) {
            const bn = data.address.suburb || data.address.neighbourhood
                    || data.address.quarter || data.address.city_district;
            if (bn) {
                const s       = bn.toLowerCase().trim();
                const matched = barangaysData.find(b => {
                    const n = b.barangay_name.toLowerCase();
                    return n === s || n.includes(s) || s.includes(n);
                });
                if (matched) {
                    barangaySelect.value = matched.id;
                    document.getElementById('selectedBarangay').textContent = matched.barangay_name;
                    document.getElementById('barangayId').value             = matched.id;
                    document.getElementById('addressDisplay').classList.add('active');
                    label = matched.barangay_name + ', ' + currentCity.city_name;
                    marker.bindPopup('<strong>' + matched.barangay_name + '</strong><br>Auto-detected').openPopup();
                }
            }
        }
    } catch { /* ignore */ }

    applyDeliveryFee(lat, lng, label);
});

// ════════════════════════════════════════════════════════════════
//  ENSURE DELIVERY FIELDS ARE SET (NEW FUNCTION)
// ════════════════════════════════════════════════════════════════
function ensureDeliveryFieldsSet() {
    const lat = parseFloat(document.getElementById('latitude').value);
    const lng = parseFloat(document.getElementById('longitude').value);
    const cityId = parseInt(document.getElementById('citySelect').value);
    
    // If we have coordinates but fee is still 0, calculate it
    if (lat && lng && !isNaN(lat) && !isNaN(lng) && parseFloat(document.getElementById('deliveryFeeInput').value) === 0) {
        const { fee, road } = computeFee(lat, lng);
        document.getElementById('deliveryFeeInput').value = fee.toFixed(2);
        document.getElementById('deliveryDistanceInput').value = road.toFixed(1);
        document.getElementById('grandTotalInput').value = (SUBTOTAL + fee).toFixed(2);
    }
    
    // Final safety check - ensure all numeric fields have values (never empty)
    if (!document.getElementById('deliveryFeeInput').value || document.getElementById('deliveryFeeInput').value === '') {
        document.getElementById('deliveryFeeInput').value = '0.00';
    }
    if (!document.getElementById('deliveryDistanceInput').value || document.getElementById('deliveryDistanceInput').value === '') {
        document.getElementById('deliveryDistanceInput').value = '0.00';
    }
    if (!document.getElementById('grandTotalInput').value || document.getElementById('grandTotalInput').value === '') {
        document.getElementById('grandTotalInput').value = SUBTOTAL.toFixed(2);
    }
}

// ════════════════════════════════════════════════════════════════
//  FORM VALIDATION (UPDATED)
// ════════════════════════════════════════════════════════════════
document.getElementById('checkoutForm').addEventListener('submit', function (e) {

    if (!citySelect.value || !barangaySelect.value) {
        e.preventDefault();
        alert('Please select your city and barangay before placing your order.');
        return false;
    }

    const lat = parseFloat(document.getElementById('latitude').value);
    const lng = parseFloat(document.getElementById('longitude').value);

    if (lat && lng && !isNaN(lat) && !isNaN(lng) && !inBounds(lat, lng, parseInt(citySelect.value))) {
        e.preventDefault();
        alert('Selected location must be within ' + (currentCity ? currentCity.city_name : 'the service area') + '.');
        return false;
    }

    // Ensure all delivery fields are set before submission
    ensureDeliveryFieldsSet();
    
    // One final check - if delivery fee is still 0 but we have coordinates, block submission
    if (parseFloat(document.getElementById('deliveryFeeInput').value) === 0 && lat && lng && !isNaN(lat) && !isNaN(lng)) {
        e.preventDefault();
        alert('Please wait for delivery fee calculation to complete. If this persists, try selecting your barangay again.');
        return false;
    }
    
    // Log the values being submitted (for debugging)
    console.log('Submitting with values:', {
        delivery_fee: document.getElementById('deliveryFeeInput').value,
        delivery_distance_km: document.getElementById('deliveryDistanceInput').value,
        grand_total: document.getElementById('grandTotalInput').value
    });
});
</script>