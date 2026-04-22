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
        .page-header { text-align: center; margin-bottom: 2rem; padding-top: 1rem; }
        .page-header h2 { font-size: 2rem; font-weight: 700; color: var(--dark-navy); margin-bottom: 0.75rem; letter-spacing: -0.025em; }
        .page-header .subtitle { color: var(--secondary-gray); font-size: 1rem; max-width: 600px; margin: 0 auto; }
        .card { border: none; border-radius: var(--border-radius); box-shadow: var(--shadow-md); overflow: hidden; background: var(--white); transition: var(--transition); margin-bottom: 1rem; }
        .card:hover { box-shadow: var(--shadow-lg); }
        .card-header { background: linear-gradient(135deg, #f8f9fa, #ffffff); color: var(--dark-navy); padding: 1.25rem 1.5rem; font-weight: 600; display: flex; align-items: center; gap: 0.75rem; border-bottom: 1px solid var(--border-color); font-size: 1rem; }
        .card-header i { color: var(--primary-pink); }
        .card-body { padding: 1.5rem; }
        .user-info-display { background: linear-gradient(135deg, rgba(248,231,243,0.4), rgba(248,231,243,0.2)); border: 1px solid rgba(214,51,132,0.2); padding: 1rem; border-radius: 10px; margin-bottom: 1rem; }
        .user-info-title { font-size: 1rem; font-weight: 600; color: var(--dark-navy); margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.5rem; }
        .user-info-one-line { background: linear-gradient(135deg, rgba(248,249,250,0.8), rgba(255,255,255,0.9)); border: 1px solid rgba(0,0,0,0.08); padding: 1rem; border-radius: 10px; margin-bottom: 1rem; }
        .user-info-one-line-content { display: flex; align-items: center; gap: 2rem; flex-wrap: wrap; }
        .user-info-item { display: flex; align-items: baseline; gap: 0.5rem; }
        .user-info-label { font-size: 0.8rem; font-weight: 600; color: var(--secondary-gray); text-transform: uppercase; letter-spacing: 0.5px; min-width: 80px; }
        .user-info-value { font-size: 0.95rem; font-weight: 500; color: var(--dark-navy); }
        .location-info { background: linear-gradient(135deg, #e3f2fd, #f0f4ff); border: 1px solid #90caf9; border-radius: 8px; padding: 0.75rem 1rem; margin-bottom: 1rem; font-size: 0.85rem; color: #1565c0; display: flex; align-items: flex-start; gap: 0.5rem; }
        .address-display { background: linear-gradient(135deg, #f8f9fa, #fff); border: 1px solid var(--border-color); border-radius: 8px; padding: 0.75rem; margin-top: 0.75rem; display: none; }
        .address-display.active { display: block; }
        .location-badge { display: inline-block; background: linear-gradient(135deg, #f3e5f5, #e1bee7); color: #6a1b9a; padding: 3px 10px; border-radius: 12px; font-size: 0.75rem; font-weight: 500; margin: 2px; border: 1px solid #ce93d8; }
        .form-group { margin-bottom: 1rem; }
        .form-label { font-weight: 600; color: var(--dark-navy); margin-bottom: 0.25rem; display: flex; align-items: center; gap: 0.5rem; font-size: 0.9rem; }
        .form-control, .form-select { border-radius: 8px; border: 1px solid var(--border-color); padding: 0.625rem 0.875rem; font-size: 0.9rem; transition: var(--transition); background: var(--white); color: var(--dark-navy); width: 100%; }
        .form-control:focus, .form-select:focus { border-color: var(--primary-pink); box-shadow: 0 0 0 0.2rem rgba(214,51,132,0.15); outline: none; }
        .input-group { display: flex; }
        .input-group-text { display: flex; align-items: center; padding: 0.625rem 0.875rem; background: var(--light-gray); border: 1px solid var(--border-color); border-right: none; border-radius: 8px 0 0 8px; font-size: 0.9rem; }
        .input-group .form-control { border-left: none; border-radius: 0 8px 8px 0; }
        .map-wrapper { border-radius: 12px; overflow: hidden; border: 1px solid var(--border-color); margin-bottom: 0.5rem; box-shadow: var(--shadow-sm); }
        .map-header { display: flex; align-items: center; justify-content: space-between; padding: 8px 14px; background: linear-gradient(135deg, var(--primary-pink), var(--accent-rose)); color: #fff; flex-wrap: wrap; gap: 6px; }
        .map-header-title { display: flex; align-items: center; gap: 7px; font-weight: 700; font-size: 0.82rem; }
        .map-status-pill { font-size: 0.7rem; background: rgba(255,255,255,0.22); padding: 2px 9px; border-radius: 20px; font-weight: 500; white-space: nowrap; }
        #map { height: 300px; width: 100%; }
        .map-footer { display: flex; align-items: center; justify-content: space-between; padding: 7px 14px; background: var(--light-gray); border-top: 1px solid var(--border-color); font-size: 0.76rem; color: var(--secondary-gray); font-weight: 500; flex-wrap: wrap; gap: 4px; }
        .map-footer-stat { display: flex; align-items: center; gap: 5px; }
        .right-column { position: sticky; top: 2rem; }
        .order-summary-wrapper { display: flex; flex-direction: column; gap: 1rem; }
        .product-item { display: flex; align-items: center; padding: 0.5rem; border-bottom: 1px solid var(--border-color); transition: var(--transition); }
        .product-item:last-child { border-bottom: none; }
        .product-item:hover { background-color: #f8f9fa; }
        .product-image { width: 60px; height: 60px; object-fit: cover; border-radius: 6px; margin-right: 0.5rem; border: 1px solid var(--border-color); flex-shrink: 0; }
        .product-details { flex: 1; min-width: 0; }
        .product-name { font-weight: 600; color: var(--dark-navy); font-size: 0.85rem; margin-bottom: 0.125rem; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .product-meta { color: var(--secondary-gray); font-size: 0.75rem; margin-bottom: 0.125rem; }
        .product-price { font-weight: 700; color: var(--primary-pink); font-size: 0.9rem; }
        .summary-section { padding: 0.5rem 0; border-bottom: 1px solid var(--border-color); }
        .summary-section:last-child { border-bottom: none; }
        .summary-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.3rem; font-size: 0.85rem; }
        .summary-row:last-child { margin-bottom: 0; }
        .delivery-fee-box { background: linear-gradient(135deg, #fff8e1, #fef3c7); border: 1px solid #f59e0b; border-radius: 8px; padding: 0.6rem 0.75rem; margin: 0.35rem 0; }
        .delivery-fee-box .fee-row { display: flex; justify-content: space-between; align-items: center; font-size: 0.85rem; }
        .delivery-fee-box .fee-label { color: #92400e; font-weight: 600; }
        .delivery-fee-box .fee-value { color: #92400e; font-weight: 700; }
        .delivery-route-mini { font-size: 0.7rem; color: #b45309; margin-top: 0.25rem; display: flex; align-items: center; gap: 0.25rem; flex-wrap: wrap; }
        .fee-spinner { display: inline-block; width: 13px; height: 13px; border: 2px solid rgba(214,51,132,0.2); border-top-color: var(--primary-pink); border-radius: 50%; animation: spin 0.75s linear infinite; vertical-align: middle; margin-right: 4px; }
        @keyframes spin { to { transform: rotate(360deg); } }
        .grand-total-row { display: flex; justify-content: space-between; align-items: center; background: linear-gradient(135deg, rgba(214,51,132,0.07), rgba(233,30,99,0.04)); border: 1px solid rgba(214,51,132,0.2); border-radius: 10px; padding: 0.75rem 1rem; margin-top: 0.5rem; }
        .grand-total-row .gt-label { font-size: 0.95rem; font-weight: 700; color: var(--dark-navy); }
        .grand-total-row .gt-value { font-size: 1.15rem; font-weight: 800; color: var(--primary-pink); }
        .security-badge { display: inline-flex; align-items: center; gap: 0.25rem; background: #e8f5e9; color: #2e7d32; padding: 0.25rem 0.5rem; border-radius: 12px; font-size: 0.7rem; margin-right: 0.25rem; margin-bottom: 0.25rem; }
        .payment-options { display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap: 0.5rem; margin-top: 0.25rem; }
        .payment-option { border: 2px solid var(--border-color); border-radius: 8px; padding: 0.75rem; cursor: pointer; transition: var(--transition); background: var(--white); user-select: none; }
        .payment-option:hover { border-color: var(--primary-pink); transform: translateY(-2px); }
        .payment-option.selected { border-color: var(--primary-pink); background: linear-gradient(135deg, rgba(214,51,132,0.05), rgba(233,30,99,0.05)); box-shadow: 0 0 0 1px var(--primary-pink); }
        .payment-option .icon { width: 35px; height: 35px; border-radius: 8px; background: linear-gradient(135deg, var(--primary-pink), var(--accent-rose)); color: white; display: flex; align-items: center; justify-content: center; font-size: 1rem; margin-bottom: 0.5rem; }
        .payment-option h6 { font-weight: 600; margin-bottom: 0.125rem; color: var(--dark-navy); font-size: 0.9rem; }
        .payment-option p { color: var(--secondary-gray); font-size: 0.75rem; margin: 0; line-height: 1.2; }
        .btn { border-radius: 8px; padding: 0.75rem 1.5rem; font-weight: 600; transition: var(--transition); border: none; font-size: 0.95rem; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; gap: 0.4rem; text-decoration: none; }
        .btn-primary { background: linear-gradient(135deg, var(--primary-pink), var(--accent-rose)); color: white; }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 4px 20px rgba(214,51,132,0.3); color: white; }
        .btn-outline-secondary { background: transparent; color: var(--secondary-gray); border: 1px solid var(--border-color); }
        .btn-outline-secondary:hover { background: var(--light-gray); color: var(--dark-navy); }
        .btn-outline-primary { background: transparent; color: var(--primary-pink); border: 1px solid var(--primary-pink); }
        .btn-outline-primary:hover { background: var(--primary-pink); color: white; }
        .btn-sm { padding: 0.5rem 0.9rem; font-size: 0.85rem; }
        .w-100 { width: 100%; }
        .alert { padding: 0.75rem 1rem; border-radius: 8px; margin-bottom: 1rem; display: flex; align-items: flex-start; gap: 0.5rem; font-size: 0.875rem; }
        .alert-danger  { background: #fef2f2; border: 1px solid #fca5a5; color: #991b1b; }
        .alert-success { background: #f0fdf4; border: 1px solid #86efac; color: #166534; }
        .alert-info    { background: #eff6ff; border: 1px solid #93c5fd; color: #1e40af; }
        .alert-warning { background: #fef3c7; border: 1px solid #f59e0b; color: #92400e; }
        .btn-close { margin-left: auto; background: none; border: none; cursor: pointer; font-size: 1.1rem; opacity: 0.6; line-height: 1; }
        .btn-close:hover { opacity: 1; }
        .product-image-clickable { cursor: zoom-in; transition: transform 0.2s ease, box-shadow 0.2s ease; }
        .product-image-clickable:hover { transform: scale(1.08); box-shadow: 0 4px 12px rgba(0,0,0,0.18); }
        .img-modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.75); z-index: 9999; align-items: center; justify-content: center; padding: 1.5rem; backdrop-filter: blur(4px); }
        .img-modal-overlay.active { display: flex; }
        .img-modal-box { background: var(--white); border-radius: 16px; overflow: hidden; max-width: 520px; width: 100%; box-shadow: 0 25px 60px rgba(0,0,0,0.4); }
        .img-modal-header { display: flex; align-items: center; justify-content: space-between; padding: 0.85rem 1.1rem; border-bottom: 1px solid var(--border-color); }
        .img-modal-title { font-size: 0.9rem; font-weight: 600; color: var(--dark-navy); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 85%; }
        .img-modal-close { background: none; border: none; font-size: 1.3rem; cursor: pointer; color: var(--secondary-gray); line-height: 1; padding: 0 0.25rem; }
        .img-modal-close:hover { color: var(--danger-red); }
        .img-modal-body { padding: 1rem; text-align: center; background: #f9f9f9; }
        .img-modal-body img { max-width: 100%; max-height: 420px; object-fit: contain; border-radius: 8px; display: block; margin: 0 auto; }
        .empty-state { text-align: center; padding: 4rem 1rem; }
        .empty-icon { font-size: 4rem; color: var(--border-color); margin-bottom: 1rem; }
        .row { display: flex; flex-wrap: wrap; }
        .row.g-4 { gap: 1.5rem; flex-wrap: nowrap; }
        .col-lg-8 { width: 66.6667%; flex-shrink: 0; }
        .col-lg-4 { width: 33.3333%; flex-shrink: 0; }
        .col-md-6 { width: 50%; padding: 0 0.375rem; }
        .cols-row { display: flex; margin: 0 -0.375rem; flex-wrap: wrap; }

        /* Terms Modal */
        .modal-terms-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.7); z-index: 10000; align-items: center; justify-content: center; padding: 1.5rem; backdrop-filter: blur(6px); }
        .modal-terms-overlay.active { display: flex; }
        .modal-terms-box { background: var(--white); border-radius: 20px; max-width: 650px; width: 100%; max-height: 85vh; overflow: hidden; box-shadow: 0 30px 70px rgba(0,0,0,0.5); display: flex; flex-direction: column; }
        .modal-terms-header { padding: 1.25rem 1.75rem; background: linear-gradient(135deg, var(--primary-pink), var(--accent-rose)); color: white; display: flex; align-items: center; justify-content: space-between; }
        .modal-terms-header h3 { margin: 0; font-weight: 700; font-size: 1.25rem; display: flex; align-items: center; gap: 10px; }
        .modal-terms-close { background: rgba(255,255,255,0.2); border: none; color: white; width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; cursor: pointer; }
        .modal-terms-body { padding: 1.75rem; overflow-y: auto; max-height: 50vh; background: #fafafa; }
        .terms-section { margin-bottom: 1.5rem; background: white; border-radius: 12px; padding: 1.25rem; border: 1px solid #f0f0f0; }
        .terms-section h4 { color: var(--primary-pink); font-size: 1rem; font-weight: 700; margin-bottom: 0.75rem; display: flex; align-items: center; gap: 8px; }
        .terms-section p { color: #4a4a4a; font-size: 0.9rem; line-height: 1.6; margin-bottom: 0; }
        .terms-list { list-style: none; padding: 0; margin: 0.5rem 0 0 0; }
        .terms-list li { padding: 0.4rem 0 0.4rem 1.8rem; position: relative; font-size: 0.9rem; color: #555; }
        .terms-list li:before { content: "✓"; position: absolute; left: 0; color: var(--primary-pink); font-weight: 700; }
        .modal-terms-footer { padding: 1.25rem 1.75rem; background: white; border-top: 1px solid var(--border-color); display: flex; align-items: center; justify-content: space-between; gap: 1rem; flex-wrap: wrap; }
        .terms-agree-check { display: flex; align-items: center; gap: 10px; font-size: 0.95rem; font-weight: 500; }
        .terms-agree-check input[type="checkbox"] { width: 18px; height: 18px; accent-color: var(--primary-pink); cursor: pointer; }
        .modal-terms-actions { display: flex; gap: 12px; }
        .btn-terms-decline { background: #f1f3f4; color: #5f6368; border: 1px solid #dadce0; }
        .btn-terms-accept { background: linear-gradient(135deg, var(--primary-pink), var(--accent-rose)); color: white; min-width: 100px; }
        .btn-terms-accept:disabled { opacity: 0.5; cursor: not-allowed; }

        /* Voucher */
        .voucher-input {
            flex: 1;
            border-radius: 8px;
            border: 1.5px solid var(--border-color);
            padding: 0.55rem 0.8rem;
            font-size: 0.85rem;
            color: var(--dark-navy);
            background: #fff;
            outline: none;
            text-transform: uppercase;
            transition: border-color 0.2s;
        }
        .voucher-input:focus { border-color: var(--primary-pink); }
        .btn-apply-voucher {
            border-radius: 8px;
            padding: 0.55rem 1rem;
            font-size: 0.82rem;
            font-weight: 700;
            background: linear-gradient(135deg, var(--primary-pink), var(--accent-rose));
            color: #fff;
            border: none;
            cursor: pointer;
            white-space: nowrap;
        }
        .btn-apply-voucher:disabled { opacity: 0.6; cursor: not-allowed; }
        .voucher-applied-box {
            background: linear-gradient(135deg, #f0fdf4, #dcfce7);
            border: 1px solid #86efac;
            border-radius: 8px;
            padding: 0.55rem 0.75rem;
            margin-top: 0.45rem;
        }

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
            .modal-terms-footer { flex-direction: column; align-items: flex-start; }
            .modal-terms-actions { width: 100%; }
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
            <p style="color:var(--secondary-gray);margin-bottom:1.5rem;">Discover our unique handmade collection and add items to your cart</p>
            <a href="{{ route('shop') }}" class="btn btn-primary"><i class="bi bi-arrow-left"></i>Start Shopping</a>
        </div>

    @else

    <div class="row g-4">

        {{-- LEFT — Checkout Form --}}
        <div class="col-lg-8">
            <form method="POST" action="{{ route('checkout.store') }}" id="checkoutForm">
                @csrf

                {{-- Customer Information --}}
                <div class="card">
                    <div class="card-header"><i class="bi bi-person-circle"></i>Your Information</div>
                    <div class="card-body">

                        <div class="user-info-display">
                            <div class="user-info-title"><i class="bi bi-person-check"></i>Account Details</div>
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
                            <span style="display:inline-flex;align-items:center;gap:4px;background:rgba(25,135,84,0.1);color:#198754;border:1px solid rgba(25,135,84,0.25);padding:3px 10px;border-radius:50rem;font-size:0.75rem;font-weight:600;">
                                <i class="bi bi-shield-check"></i>Account Verified
                            </span>
                        </div>

                        <input type="hidden" name="name"  value="{{ Auth::user()->name }}">
                        <input type="hidden" name="email" value="{{ Auth::user()->email }}">

                        <div class="form-group" style="margin-top:0.75rem;">
                            <label class="form-label"><i class="bi bi-phone"></i>Phone Number *</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                                <input type="tel" class="form-control" name="phone"
                                       value="{{ old('phone', Auth::user()->phone ?? '') }}"
                                       placeholder="09123456789" pattern="[0-9]{11}" maxlength="11" required>
                            </div>
                            <small style="color:var(--secondary-gray);display:block;margin-top:0.35rem;font-size:0.78rem;">
                                <i class="bi bi-info-circle" style="margin-right:3px;"></i>Format: 09123456789 — We'll use this for delivery updates
                            </small>
                        </div>
                    </div>
                </div>

                {{-- Delivery Address --}}
                <div class="card">
                    <div class="card-header"><i class="bi bi-geo-alt"></i>Delivery Address</div>
                    <div class="card-body">

                        <div class="location-info" id="locationInfoBox">
                            <i class="bi bi-check-circle-fill" style="color:#15803d;flex-shrink:0;margin-top:2px;"></i>
                            <span><strong>Service Area:</strong> We currently deliver within <strong>Cebu City</strong></span>
                        </div>

                        <div class="cols-row">
                            <div class="col-md-6 form-group">
                                <label class="form-label">City *</label>
                                <select class="form-select" id="citySelect" name="city_id" required>
                                    <option value="">Loading...</option>
                                </select>
                            </div>
                            <div class="col-md-6 form-group">
                                <label class="form-label">Barangay *</label>
                                <select class="form-select" id="barangaySelect" name="barangay_id" disabled required>
                                    <option value="">Select barangay</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label"><i class="bi bi-signpost"></i>Street Address / House No. *</label>
                            <input type="text" class="form-control" name="street" id="street"
                                   placeholder="e.g., 123 Main Street, Building Name, Floor/Unit" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label"><i class="bi bi-map"></i>Location Map</label>
                            <div class="map-wrapper">
                                <div class="map-header">
                                    <div class="map-header-title"><i class="bi bi-truck"></i>Delivery Route</div>
                                    <span class="map-status-pill" id="mapStatusPill">Select barangay</span>
                                </div>
                                <div id="map"></div>
                                <div class="map-footer">
                                    <div class="map-footer-stat">
                                        <i class="bi bi-geo-alt-fill" style="color:var(--primary-pink);"></i>
                                        <span>Hipodromo, Cebu City</span>
                                        <i class="bi bi-arrow-right" style="opacity:0.5;font-size:0.7rem;"></i>
                                        <i class="bi bi-house-fill" style="color:#198754;"></i>
                                        <span id="mapFooterDest">—</span>
                                    </div>
                                    <div class="map-footer-stat">
                                        <i class="bi bi-signpost-split" style="color:var(--primary-pink);"></i>
                                        <span id="mapFooterDist">—</span>
                                        &nbsp;
                                        <i class="bi bi-clock" style="color:#f59e0b;"></i>
                                        <span id="mapFooterEta">—</span>
                                    </div>
                                </div>
                            </div>
                            <small style="color:var(--secondary-gray);font-size:0.78rem;">
                                Click on the map or select a barangay to pin your delivery location.
                            </small>
                        </div>

                        <div class="address-display" id="addressDisplay">
                            <small style="color:var(--secondary-gray);display:block;margin-bottom:4px;">Selected Address:</small>
                            <span class="location-badge">Central Visayas</span>
                            <span class="location-badge">Cebu</span>
                            <span class="location-badge" id="selectedCity">Cebu City</span>
                            <span class="location-badge" id="selectedBarangay">—</span>
                        </div>

                        <input type="hidden" name="region_id"   id="regionId"   value="1">
                        <input type="hidden" name="province_id" id="provinceId" value="1">
                        <input type="hidden" id="barangayId">
                        <input type="hidden" name="latitude"   id="latitude">
                        <input type="hidden" name="longitude"  id="longitude">
                        <input type="hidden" name="delivery_fee"         id="deliveryFeeInput"      value="0">
                        <input type="hidden" name="delivery_distance_km" id="deliveryDistanceInput" value="0">
                        <input type="hidden" name="grand_total"          id="grandTotalInput"       value="{{ $total }}">
                        <input type="hidden" name="voucher_code"         id="voucherCodeHidden"     value="">
                        <input type="hidden" name="voucher_id"           id="voucherIdHidden"       value="">
                        <input type="hidden" name="discount_amount"      id="discountAmountHidden"  value="0">

                    </div>
                </div>

            </form>
        </div>

        {{-- RIGHT — Order Summary + Payment --}}
        <div class="col-lg-4">
            <div class="right-column">
                <div class="order-summary-wrapper">

                    {{-- Order Summary --}}
                    <div class="card">
                        <div class="card-header"><i class="bi bi-receipt"></i>Order Summary</div>
                        <div class="card-body">

                            {{-- Items --}}
                            <div class="summary-section">
                                <h6 style="font-size:0.9rem;margin-bottom:0.5rem;">Items ({{ $cartItems->count() }})</h6>
                                @foreach($cartItems as $item)
                                    @php
                                        $isCustom  = $item->is_customization && $item->customization;
                                        $itemPrice = $isCustom ? $item->customization->admin_price : $item->product->price;
                                        $itemName  = $isCustom ? $item->product->name . ' (Custom)' : $item->product->name;
                                    @endphp
                                    <div class="product-item">
                                        @if($isCustom && $item->customization->custom_image)
                                            <img src="{{ asset('uploads/customizations/' . $item->customization->custom_image) }}"
                                                 alt="{{ $itemName }}" class="product-image product-image-clickable"
                                                 onclick="openImageModal(this.src, '{{ $itemName }}')" title="Click to enlarge">
                                        @else
                                            <img src="{{ asset('asset/images/' . $item->product->image) }}"
                                                 alt="{{ $itemName }}" class="product-image product-image-clickable"
                                                 onclick="openImageModal(this.src, '{{ $itemName }}')" title="Click to enlarge">
                                        @endif
                                        <div class="product-details">
                                            <div class="product-name">{{ $itemName }}</div>
                                            <div class="product-meta">{{ $item->quantity }} × ₱{{ number_format($itemPrice, 2) }}</div>
                                            <div class="product-price">₱{{ number_format($item->quantity * $itemPrice, 2) }}</div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            {{-- Totals --}}
                            <div class="summary-section">

                                <div class="summary-row">
                                    <span>Subtotal</span>
                                    <span>₱{{ number_format($total, 2) }}</span>
                                </div>

                                {{-- Voucher / Promo Code --}}
                                <div style="margin: 0.5rem 0;">
                                    <div style="display:flex;align-items:center;gap:6px;margin-bottom:0.4rem;">
                                        <i class="bi bi-ticket-perforated-fill" style="color:var(--primary-pink);font-size:0.9rem;"></i>
                                        <span style="font-weight:600;font-size:0.85rem;color:var(--dark-navy);">Promo / Voucher Code</span>
                                    </div>

                                    <div id="voucherInputRow" style="display:flex;gap:6px;">
                                        <input type="text" id="voucherCodeInput" placeholder="Enter code"
                                               maxlength="50" class="voucher-input"
                                               oninput="this.value=this.value.toUpperCase()"
                                               onkeydown="if(event.key==='Enter'){event.preventDefault();applyVoucher();}">
                                        <button type="button" id="applyVoucherBtn"
                                                class="btn-apply-voucher" onclick="applyVoucher()">
                                            Apply
                                        </button>
                                    </div>

                                    <div id="voucherFeedback" style="display:none;margin-top:0.4rem;font-size:0.8rem;padding:0.4rem 0.7rem;border-radius:7px;"></div>

                                    <div id="voucherApplied" class="voucher-applied-box" style="display:none;">
                                        <div style="display:flex;align-items:center;justify-content:space-between;">
                                            <div style="display:flex;align-items:center;gap:7px;">
                                                <i class="bi bi-check-circle-fill" style="color:#16a34a;font-size:0.95rem;"></i>
                                                <div>
                                                    <div id="appliedVoucherCode" style="font-weight:700;font-size:0.85rem;color:#15803d;"></div>
                                                    <div id="appliedVoucherDesc" style="font-size:0.75rem;color:#166534;"></div>
                                                </div>
                                            </div>
                                            <button type="button" onclick="removeVoucher()" title="Remove"
                                                    style="background:none;border:none;cursor:pointer;color:#dc2626;font-size:1.1rem;line-height:1;">
                                                <i class="bi bi-x-circle"></i>
                                            </button>
                                        </div>
                                        <div style="margin-top:0.3rem;font-size:0.8rem;color:#166534;font-weight:600;">
                                            You save: <span id="appliedVoucherSaving"></span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Delivery Fee --}}
                                <div class="delivery-fee-box">
                                    <div class="fee-row">
                                        <span class="fee-label"><i class="bi bi-truck" style="margin-right:3px;"></i>Delivery Fee</span>
                                        <span class="fee-value" id="deliveryFeeDisplay">
                                            <span style="font-size:0.78rem;font-weight:400;color:#b45309;">Select barangay</span>
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

                                {{-- Discount Row --}}
                                <div class="summary-row" id="discountRow" style="display:none;color:#16a34a;font-weight:600;">
                                    <span><i class="bi bi-tag-fill" style="margin-right:3px;"></i>Discount</span>
                                    <span id="discountDisplay">-₱0.00</span>
                                </div>

                                <div class="summary-row">
                                    <span>Tax</span>
                                    <span style="color:#198754;">Included</span>
                                </div>

                                <div class="grand-total-row">
                                    <span class="gt-label"><i class="bi bi-receipt" style="margin-right:4px;"></i>Grand Total</span>
                                    <span class="gt-value" id="grandTotalDisplay">₱{{ number_format($total, 2) }}</span>
                                </div>

                                <div id="codReminder" class="alert alert-warning" style="display:none;margin-top:0.5rem;padding:0.5rem 0.75rem;">
                                    <i class="bi bi-cash"></i>
                                    <span>Please prepare <strong id="codAmount">₱0.00</strong> in cash upon delivery.</span>
                                </div>

                            </div>

                            <div class="summary-section">
                                <div style="display:flex;flex-wrap:wrap;margin-bottom:0.5rem;">
                                    <span class="security-badge"><i class="bi bi-shield-check"></i>SSL Secure</span>
                                    <span class="security-badge" style="background:#e3f2fd;color:#1565c0;"><i class="bi bi-truck"></i>Fast Delivery</span>
                                </div>
                                <div class="alert alert-info" style="margin-bottom:0;padding:0.45rem 0.7rem;font-size:0.8rem;">
                                    <i class="bi bi-info-circle"></i>
                                    Need help? <a href="{{ route('chatbot') }}" style="font-weight:700;color:inherit;">Contact Support</a>
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
                        <div class="card-header"><i class="bi bi-credit-card"></i>Payment Method</div>
                        <div class="card-body">

                            <div class="payment-options">
                                <div class="payment-option selected" id="optCOD" onclick="selectPayment('COD', this)">
                                    <div class="icon"><i class="bi bi-cash"></i></div>
                                    <h6>Cash on Delivery</h6>
                                    <p>Pay when order arrives</p>
                                    <input type="radio" name="payment_method" value="COD" id="paymentCOD"
                                           style="display:none;" form="checkoutForm" checked>
                                </div>
                            </div>

                            <div style="margin-top:1rem;">
                                <button type="button" class="btn btn-primary w-100" id="placeOrderBtn" onclick="showTermsModal()">
                                    <i class="bi bi-lock"></i>Place Secure Order
                                </button>
                                <p style="text-align:center;color:var(--secondary-gray);font-size:0.78rem;margin-top:0.5rem;margin-bottom:0;">
                                    By placing your order you agree to our
                                    <a href="#" onclick="showTermsModal(); return false;" style="color:var(--primary-pink);">Terms & Conditions</a>
                                </p>
                            </div>

                            <div style="margin-top:1rem;padding-top:1rem;border-top:1px solid var(--border-color);">
                                <div style="display:flex;align-items:center;gap:0.5rem;">
                                    <i class="bi bi-shield-check" style="color:#198754;font-size:1.25rem;"></i>
                                    <div>
                                        <strong style="font-size:0.85rem;">Secure Payment</strong>
                                        <p style="color:var(--secondary-gray);font-size:0.75rem;margin:0;">Payment information is encrypted</p>
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

{{-- Terms Modal --}}
<div class="modal-terms-overlay" id="termsModalOverlay" onclick="closeTermsModal(event)">
    <div class="modal-terms-box" onclick="event.stopPropagation()">
        <div class="modal-terms-header">
            <h3><i class="bi bi-file-text"></i>Terms & Conditions</h3>
            <button class="modal-terms-close" onclick="closeTermsModal(null)">×</button>
        </div>
        <div class="modal-terms-body">
            <div class="terms-section">
                <h4><i class="bi bi-bag-check"></i> Order & Payment</h4>
                <ul class="terms-list">
                    <li>All orders are final once placed and confirmed</li>
                    <li>Payment is due upon delivery (Cash on Delivery only)</li>
                    <li>Prices are in Philippine Peso (₱) and inclusive of applicable taxes</li>
                    <li>We reserve the right to cancel any order due to stock unavailability</li>
                </ul>
            </div>
            <div class="terms-section">
                <h4><i class="bi bi-truck"></i> Delivery Terms</h4>
                <ul class="terms-list">
                    <li>Delivery is available only within Cebu City and selected areas</li>
                    <li>Delivery fees are calculated based on distance and displayed before order placement</li>
                    <li>Estimated delivery time is 1-3 business days within Cebu City</li>
                    <li>Please ensure someone is available to receive the order at the provided address</li>
                </ul>
            </div>
            <div class="terms-section">
                <h4><i class="bi bi-arrow-return-left"></i> Returns & Refunds</h4>
                <ul class="terms-list">
                    <li>Customized items are non-refundable and non-returnable</li>
                    <li>For defective items, please contact us within 24 hours of delivery</li>
                    <li>Returns are subject to inspection and approval by our team</li>
                    <li>Refunds will be processed within 5-7 business days if approved</li>
                </ul>
            </div>
            <div class="terms-section">
                <h4><i class="bi bi-shield-lock"></i> Privacy & Security</h4>
                <ul class="terms-list">
                    <li>Your personal information is secure and encrypted</li>
                    <li>We do not share your data with third parties without consent</li>
                    <li>By placing an order, you agree to receive order updates via SMS/email</li>
                </ul>
            </div>
            <div class="terms-section">
                <h4><i class="bi bi-exclamation-triangle"></i> Important Notes</h4>
                <ul class="terms-list">
                    <li>Please double-check your delivery address before confirming</li>
                    <li>Delivery personnel may contact you for location assistance</li>
                    <li>Hookcraft Avenue reserves the right to modify these terms</li>
                    <li>For questions, contact our support team</li>
                </ul>
            </div>
        </div>
        <div class="modal-terms-footer">
            <div class="terms-agree-check">
                <input type="checkbox" id="agreeTerms" onchange="toggleAcceptButton()">
                <label for="agreeTerms">I have read and agree to the Terms & Conditions</label>
            </div>
            <div class="modal-terms-actions">
                <button class="btn btn-terms-decline" onclick="closeTermsModal(null)">Decline</button>
                <button class="btn btn-terms-accept" id="acceptTermsBtn" disabled onclick="acceptTermsAndSubmit()">
                    Accept & Place Order
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Image Preview Modal --}}
<div class="img-modal-overlay" id="imgModalOverlay" onclick="closeImageModal(event)">
    <div class="img-modal-box">
        <div class="img-modal-header">
            <span class="img-modal-title" id="imgModalTitle"></span>
            <button class="img-modal-close" onclick="closeImageModal(null)">&#x2715;</button>
        </div>
        <div class="img-modal-body">
            <img id="imgModalImg" src="" alt="">
        </div>
    </div>
</div>

@include('components.footer')

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
const ORIGIN   = { lat: 10.314152, lng: 123.906935 };
const SUBTOTAL = {{ $total }};
const DELIVERY = { baseFee: 40, baseKm: 2, ratePerKm: 15, maxFee: 200, roadFactor: 1.3 };

// Only Cebu City config
const CEBU_CITY_ID = 1;
const CITY_CONFIGS = {
    1: { name: 'Cebu City', center: [10.3157, 123.8854], bounds: [[10.25, 123.80], [10.38, 123.97]], minZoom: 12, maxZoom: 18 }
};

let citiesData    = [];
let barangaysData = [];
let currentCity   = null;
let currentFee    = 0;
let currentDiscount = 0;

const citySelect     = document.getElementById('citySelect');
const barangaySelect = document.getElementById('barangaySelect');

// ── Voucher ───────────────────────────────────────────────────
async function applyVoucher() {
    const code = document.getElementById('voucherCodeInput').value.trim();
    if (!code) { showVoucherFeedback('Please enter a voucher code.', 'warn'); return; }

    const btn = document.getElementById('applyVoucherBtn');
    btn.disabled = true;
    btn.innerHTML = '<span class="fee-spinner" style="border-top-color:#fff;margin-right:0;"></span>';

    try {
        const res  = await fetch('/voucher/apply', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]')?.value || '',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ code, subtotal: SUBTOTAL })
        });

        const data = await res.json();

        if (data.ok) {
            currentDiscount = data.discount;

            document.getElementById('voucherInputRow').style.display  = 'none';
            document.getElementById('voucherFeedback').style.display  = 'none';
            document.getElementById('voucherApplied').style.display   = 'block';
            document.getElementById('appliedVoucherCode').textContent = '🎟 ' + data.code + ' — ' + data.discount_label;
            document.getElementById('appliedVoucherDesc').textContent = data.description || '';
            document.getElementById('appliedVoucherSaving').textContent = '₱' + data.discount.toLocaleString('en-PH', { minimumFractionDigits: 2 });

            document.getElementById('voucherCodeHidden').value    = data.code;
            document.getElementById('voucherIdHidden').value      = data.voucher_id;
            document.getElementById('discountAmountHidden').value = data.discount.toFixed(2);

            refreshTotals();
        } else {
            showVoucherFeedback(data.message || 'Invalid voucher.', 'error');
        }
    } catch (e) {
        showVoucherFeedback('Unable to apply voucher. Please try again.', 'error');
    } finally {
        btn.disabled = false;
        btn.innerHTML = 'Apply';
    }
}

function removeVoucher() {
    currentDiscount = 0;
    document.getElementById('voucherApplied').style.display  = 'none';
    document.getElementById('voucherInputRow').style.display = 'flex';
    document.getElementById('voucherCodeInput').value        = '';
    document.getElementById('voucherCodeHidden').value       = '';
    document.getElementById('voucherIdHidden').value         = '';
    document.getElementById('discountAmountHidden').value    = '0';
    refreshTotals();
}

function showVoucherFeedback(msg, type) {
    const el = document.getElementById('voucherFeedback');
    const styles = {
        error: 'background:#fef2f2;border:1px solid #fca5a5;color:#991b1b;',
        warn:  'background:#fef3c7;border:1px solid #f59e0b;color:#92400e;'
    };
    el.style.cssText = 'display:block;margin-top:0.4rem;font-size:0.8rem;padding:0.4rem 0.7rem;border-radius:7px;' + (styles[type] || styles.error);
    el.textContent = msg;
    setTimeout(() => { el.style.display = 'none'; }, 4000);
}

function refreshTotals() {
    const grand = Math.max(0, SUBTOTAL + currentFee - currentDiscount);

    if (currentDiscount > 0) {
        document.getElementById('discountRow').style.display = 'flex';
        document.getElementById('discountDisplay').textContent = '-₱' + currentDiscount.toLocaleString('en-PH', { minimumFractionDigits: 2 });
    } else {
        document.getElementById('discountRow').style.display = 'none';
    }

    document.getElementById('grandTotalDisplay').textContent = '₱' + grand.toLocaleString('en-PH', { minimumFractionDigits: 2 });
    document.getElementById('grandTotalInput').value         = grand.toFixed(2);
    document.getElementById('discountAmountHidden').value    = currentDiscount.toFixed(2);

    refreshCodReminder();
}

// ── Terms Modal ───────────────────────────────────────────────
function showTermsModal() {
    if (!validateCheckoutForm()) return false;
    document.getElementById('agreeTerms').checked      = false;
    document.getElementById('acceptTermsBtn').disabled = true;
    document.getElementById('termsModalOverlay').classList.add('active');
    document.body.style.overflow = 'hidden';
    return false;
}

function closeTermsModal(event) {
    if (event && event.target !== document.getElementById('termsModalOverlay')) return;
    document.getElementById('termsModalOverlay').classList.remove('active');
    document.body.style.overflow = '';
}

function toggleAcceptButton() {
    document.getElementById('acceptTermsBtn').disabled = !document.getElementById('agreeTerms').checked;
}

function acceptTermsAndSubmit() {
    ensureDeliveryFieldsSet();
    document.getElementById('checkoutForm').submit();
}

// ── Form Validation ───────────────────────────────────────────
function validateCheckoutForm() {
    if (!citySelect.value || !barangaySelect.value) {
        alert('Please select your barangay before placing your order.');
        return false;
    }
    return true;
}

// ── Payment ───────────────────────────────────────────────────
function selectPayment(method, el) {
    document.querySelectorAll('.payment-option').forEach(o => o.classList.remove('selected'));
    el.classList.add('selected');
    document.getElementById('payment' + method).checked = true;
    refreshCodReminder();
}

// ── Fee helpers ───────────────────────────────────────────────
function haversineKm(lat1, lng1, lat2, lng2) {
    const R  = 6371;
    const dL = (lat2 - lat1) * Math.PI / 180;
    const dG = (lng2 - lng1) * Math.PI / 180;
    const a  = Math.sin(dL / 2) ** 2 + Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) * Math.sin(dG / 2) ** 2;
    return R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
}

function computeFee(destLat, destLng) {
    const straight = haversineKm(ORIGIN.lat, ORIGIN.lng, destLat, destLng);
    const road     = Math.round(straight * DELIVERY.roadFactor * 10) / 10;
    let   fee      = road <= DELIVERY.baseKm ? DELIVERY.baseFee : DELIVERY.baseFee + (road - DELIVERY.baseKm) * DELIVERY.ratePerKm;
    fee = Math.min(Math.ceil(fee / 5) * 5, DELIVERY.maxFee);
    return { fee, road };
}

function applyDeliveryFee(destLat, destLng, labelName, roadKm) {
    const { fee, road } = computeFee(destLat, destLng);
    const displayRoad   = roadKm !== undefined ? roadKm : road;
    currentFee = fee;

    document.getElementById('deliveryFeeDisplay').innerHTML          = '<strong>₱' + fee.toFixed(2) + '</strong>';
    document.getElementById('routeDestLabel').textContent            = labelName;
    document.getElementById('routeDistLabel').textContent            = displayRoad.toFixed(1);
    document.getElementById('deliveryRouteInfo').style.display       = 'flex';
    document.getElementById('deliveryFeeInput').value                = fee.toFixed(2);
    document.getElementById('deliveryDistanceInput').value           = displayRoad.toFixed(1);

    refreshTotals();
}

function refreshCodReminder() {
    const isCOD = document.getElementById('paymentCOD').checked;
    const rem   = document.getElementById('codReminder');
    if (isCOD && currentFee > 0) {
        const grand = Math.max(0, SUBTOTAL + currentFee - currentDiscount);
        document.getElementById('codAmount').textContent = '₱' + grand.toLocaleString('en-PH', { minimumFractionDigits: 2 });
        rem.style.display = 'flex';
    } else {
        rem.style.display = 'none';
    }
}

function showCalculating() {
    document.getElementById('deliveryFeeDisplay').innerHTML     = '<span class="fee-spinner"></span><span style="font-size:0.78rem;color:#b45309;">Calculating…</span>';
    document.getElementById('deliveryRouteInfo').style.display  = 'none';
    document.getElementById('codReminder').style.display        = 'none';
    setMapStatus('Calculating route…');
}

function resetFee() {
    currentFee = 0;
    document.getElementById('deliveryFeeDisplay').innerHTML    = '<span style="font-size:0.78rem;font-weight:400;color:#b45309;">Select barangay</span>';
    document.getElementById('deliveryRouteInfo').style.display = 'none';
    document.getElementById('deliveryFeeInput').value          = '0';
    document.getElementById('deliveryDistanceInput').value     = '0';
    resetMapFooter();
    setMapStatus('Select barangay');
    refreshTotals();
}

function setMapStatus(text) { document.getElementById('mapStatusPill').textContent = text; }
function setMapFooter(dest, dist, eta) {
    document.getElementById('mapFooterDest').textContent = dest || '—';
    document.getElementById('mapFooterDist').textContent = dist || '—';
    document.getElementById('mapFooterEta').textContent  = eta  || '—';
}
function resetMapFooter() { setMapFooter('—', '—', '—'); }
function fmtDist(km) { return km < 1 ? (km * 1000).toFixed(0) + ' m' : km.toFixed(1) + ' km'; }
function fmtTime(s)  { const m = Math.round(s / 60); return m < 1 ? 'Arriving!' : m < 60 ? m + ' min' : Math.floor(m/60) + 'h ' + (m%60) + 'm'; }

function ensureDeliveryFieldsSet() {
    const lat = parseFloat(document.getElementById('latitude').value);
    const lng = parseFloat(document.getElementById('longitude').value);
    if (lat && lng && !isNaN(lat) && !isNaN(lng) && parseFloat(document.getElementById('deliveryFeeInput').value) === 0) {
        const { fee, road } = computeFee(lat, lng);
        document.getElementById('deliveryFeeInput').value      = fee.toFixed(2);
        document.getElementById('deliveryDistanceInput').value = road.toFixed(1);
    }
    if (!document.getElementById('deliveryFeeInput').value)      document.getElementById('deliveryFeeInput').value      = '0.00';
    if (!document.getElementById('deliveryDistanceInput').value) document.getElementById('deliveryDistanceInput').value = '0.00';
    refreshTotals();
}

// ── Map Setup ─────────────────────────────────────────────────
const map = L.map('map', {
    center: [10.3157, 123.8854], zoom: 13, minZoom: 12, maxZoom: 18,
    maxBounds: [[10.25, 123.80], [10.38, 123.97]], maxBoundsViscosity: 1.0
});
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '© OpenStreetMap', maxZoom: 18 }).addTo(map);

L.marker([ORIGIN.lat, ORIGIN.lng], {
    icon: L.divIcon({
        className: '',
        html: `<div style="position:relative;">
                 <div style="background:linear-gradient(135deg,#d63384,#e91e63);width:30px;height:30px;border-radius:50%;border:3px solid #fff;box-shadow:0 3px 10px rgba(214,51,132,0.45);display:flex;align-items:center;justify-content:center;">
                   <i class="bi bi-shop" style="color:#fff;font-size:0.75rem;"></i>
                 </div>
                 <div style="position:absolute;top:-26px;left:50%;transform:translateX(-50%);background:linear-gradient(135deg,#d63384,#e91e63);color:#fff;font-size:9px;padding:2px 7px;border-radius:10px;white-space:nowrap;font-weight:800;">🏪 STORE</div>
               </div>`,
        iconSize: [30, 30], iconAnchor: [15, 15]
    })
}).addTo(map).bindPopup('<b>🏪 Hookcraft Avenue</b><br>Hipodromo, Cebu City');

let destMarker   = null;
let boundaryRect = null;
let routeLayers  = [];

function clearRouteLayers() { routeLayers.forEach(l => map.removeLayer(l)); routeLayers = []; }

function drawBoundary(bounds) {
    if (boundaryRect) map.removeLayer(boundaryRect);
    boundaryRect = L.rectangle(bounds, { color: '#d63384', weight: 2, fillOpacity: 0.05, fillColor: '#d63384' }).addTo(map);
}

function placeDestMarker(lat, lng, popupText) {
    document.getElementById('latitude').value  = lat;
    document.getElementById('longitude').value = lng;
    if (destMarker) map.removeLayer(destMarker);
    destMarker = L.marker([lat, lng], {
        icon: L.divIcon({
            className: '',
            html: `<div style="width:28px;height:28px;background:linear-gradient(135deg,#f093fb,#f5576c);border-radius:50% 50% 50% 0;transform:rotate(-45deg);border:3px solid #fff;box-shadow:0 3px 10px rgba(0,0,0,0.25);display:flex;align-items:center;justify-content:center;">
                     <i class="bi bi-house-fill" style="transform:rotate(45deg);color:#fff;font-size:0.75rem;"></i>
                   </div>`,
            iconSize: [28, 28], iconAnchor: [14, 28]
        })
    }).addTo(map);
    if (popupText) destMarker.bindPopup('<b>📦 ' + popupText + '</b>').openPopup();
    map.setView([lat, lng], 15);
}

function inBounds(lat, lng) {
    const b = CITY_CONFIGS[CEBU_CITY_ID].bounds;
    return lat >= b[0][0] && lat <= b[1][0] && lng >= b[0][1] && lng <= b[1][1];
}

async function drawRoadRoute(destLat, destLng, labelName) {
    clearRouteLayers();
    const pulse = L.circle([destLat, destLng], { radius: 35, color: '#f5576c', fillColor: '#f5576c', fillOpacity: 0.2, weight: 2.5 }).addTo(map);
    routeLayers.push(pulse);

    try {
        const url  = 'https://router.project-osrm.org/route/v1/driving/' + ORIGIN.lng + ',' + ORIGIN.lat + ';' + destLng + ',' + destLat + '?overview=full&geometries=geojson&steps=false';
        const res  = await fetch(url);
        const data = await res.json();
        if (data.code !== 'Ok' || !data.routes?.length) throw new Error('no route');

        const route  = data.routes[0];
        const coords = route.geometry.coordinates.map(c => [c[1], c[0]]);
        const distKm = route.distance / 1000;
        const durS   = route.duration;

        const glow   = L.polyline(coords, { color: '#d63384', weight: 14, opacity: 0.18, lineJoin: 'round', lineCap: 'round' }).addTo(map);
        const main   = L.polyline(coords, { color: '#d63384', weight: 6,  opacity: 0.92, lineJoin: 'round', lineCap: 'round' }).addTo(map);
        const dashes = L.polyline(coords, { color: '#fff',    weight: 2.5, opacity: 0.75, dashArray: '8 18', lineJoin: 'round', lineCap: 'round' }).addTo(map);
        routeLayers.push(glow, main, dashes);
        map.fitBounds(main.getBounds(), { padding: [50, 50] });

        setMapStatus('Route loaded · ' + fmtDist(distKm));
        setMapFooter(labelName, fmtDist(distKm), fmtTime(durS));
        applyDeliveryFee(destLat, destLng, labelName, distKm);

    } catch (e) {
        const d    = haversineKm(ORIGIN.lat, ORIGIN.lng, destLat, destLng);
        const road = Math.round(d * DELIVERY.roadFactor * 10) / 10;
        const line = L.polyline([[ORIGIN.lat, ORIGIN.lng], [destLat, destLng]], { color: '#d63384', weight: 4, opacity: 0.7, dashArray: '9 9' }).addTo(map);
        routeLayers.push(line);
        map.fitBounds(line.getBounds(), { padding: [40, 40] });
        setMapStatus('Approx. route · ' + fmtDist(road));
        setMapFooter(labelName, fmtDist(road), '—');
        applyDeliveryFee(destLat, destLng, labelName, road);
    }
}

// ── Load only Cebu City on page load (no geolocation needed) ──
document.addEventListener('DOMContentLoaded', function () {
    loadCebuCity();
});

async function loadCebuCity() {
    try {
        const res  = await fetch('/api/locations/cities/1');
        const data = await res.json();

        // Filter to Cebu City only (id: 1)
        citiesData = data.filter(c => c.id === CEBU_CITY_ID);

        // Populate city dropdown
        citySelect.innerHTML = '<option value="">Select City</option>';
        citiesData.forEach(c => citySelect.add(new Option(c.city_name, c.id)));

        // Auto-select Cebu City
        citySelect.value = CEBU_CITY_ID;
        citySelect.dispatchEvent(new Event('change'));

    } catch (e) {
        console.error('Failed to load cities:', e);
        // Fallback: manually add Cebu City if API fails
        citySelect.innerHTML = '<option value="1">Cebu City</option>';
        citySelect.value = 1;
        citySelect.dispatchEvent(new Event('change'));
    }
}

// ── City change handler ───────────────────────────────────────
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
    barangaySelect.innerHTML = '<option value="">Select barangay</option>';
    if (boundaryRect) map.removeLayer(boundaryRect);
    clearRouteLayers();
    document.getElementById('addressDisplay').classList.remove('active');
    resetBarangay();
    resetFee();
}

function loadBarangays(cityId) {
    barangaySelect.disabled = true;
    barangaySelect.innerHTML = '<option value="">Loading barangays…</option>';

    fetch('/api/locations/barangays/' + cityId)
        .then(r => r.json())
        .then(data => {
            barangaysData = data;
            barangaySelect.innerHTML = '<option value="">Choose Barangay</option>';
            data.forEach(b => barangaySelect.add(new Option(b.barangay_name, b.id)));
            barangaySelect.disabled = false;
        })
        .catch(() => {
            barangaySelect.innerHTML = '<option value="">Failed to load — refresh page</option>';
            barangaySelect.disabled = false;
        });
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
    if (destMarker) map.removeLayer(destMarker);
    clearRouteLayers();
    document.getElementById('selectedBarangay').textContent = '—';
    document.getElementById('barangayId').value             = '';
    document.getElementById('latitude').value               = '';
    document.getElementById('longitude').value              = '';
}

async function geocodeBarangay(barangay) {
    if (!currentCity) return;
    const label = barangay.barangay_name + ', ' + currentCity.city_name;
    try {
        const q   = barangay.barangay_name + ', ' + currentCity.city_name + ', Cebu, Philippines';
        const res = await fetch('https://nominatim.openstreetmap.org/search?format=json&limit=1&countrycodes=ph&q=' + encodeURIComponent(q));
        const results = await res.json();

        let lat, lng;
        if (results.length > 0 && inBounds(parseFloat(results[0].lat), parseFloat(results[0].lon))) {
            lat = parseFloat(results[0].lat);
            lng = parseFloat(results[0].lon);
        } else {
            [lat, lng] = CITY_CONFIGS[CEBU_CITY_ID].center;
        }

        placeDestMarker(lat, lng, label);
        await drawRoadRoute(lat, lng, label);
    } catch {
        const [lat, lng] = CITY_CONFIGS[CEBU_CITY_ID].center;
        placeDestMarker(lat, lng, label);
        await drawRoadRoute(lat, lng, label);
    }
}

// ── Map click handler ─────────────────────────────────────────
map.on('click', async function (e) {
    if (!currentCity) { alert('Please wait, loading city data…'); return; }
    const { lat, lng } = e.latlng;
    if (!inBounds(lat, lng)) { alert('Please select a location within Cebu City only.'); return; }

    placeDestMarker(lat, lng, null);
    showCalculating();

    let label = currentCity.city_name;
    try {
        const res  = await fetch('https://nominatim.openstreetmap.org/reverse?format=json&zoom=18&lat=' + lat + '&lon=' + lng);
        const data = await res.json();
        if (data?.address) {
            const bn      = data.address.suburb || data.address.neighbourhood || data.address.quarter || data.address.city_district;
            const matched = bn ? barangaysData.find(b => { const n = b.barangay_name.toLowerCase(); const s = bn.toLowerCase().trim(); return n === s || n.includes(s) || s.includes(n); }) : null;
            if (matched) {
                barangaySelect.value = matched.id;
                document.getElementById('selectedBarangay').textContent = matched.barangay_name;
                document.getElementById('barangayId').value             = matched.id;
                document.getElementById('addressDisplay').classList.add('active');
                label = matched.barangay_name + ', ' + currentCity.city_name;
                destMarker.bindPopup('<b>📦 ' + matched.barangay_name + '</b><br>Auto-detected').openPopup();
            }
        }
    } catch { /* ignore */ }

    await drawRoadRoute(lat, lng, label);
});

// ── Image Modal ───────────────────────────────────────────────
function openImageModal(src, title) {
    document.getElementById('imgModalImg').src           = src;
    document.getElementById('imgModalTitle').textContent = title;
    document.getElementById('imgModalOverlay').classList.add('active');
    document.body.style.overflow = 'hidden';
}
function closeImageModal(event) {
    if (event && event.target !== document.getElementById('imgModalOverlay')) return;
    document.getElementById('imgModalOverlay').classList.remove('active');
    document.body.style.overflow = '';
}

document.addEventListener('keydown', e => {
    if (e.key === 'Escape') { closeImageModal(null); closeTermsModal(null); }
});
</script>
</body>
</html>