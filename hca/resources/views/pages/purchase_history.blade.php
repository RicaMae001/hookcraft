<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase History - HookcraftAvenue</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="{{ asset('asset/stylesnav.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
    :root {
        --primary-pink: #FFB6C1;
        --primary-pink-dark: #FF9EAD;
        --gradient-start: #FFB6C1;
        --gradient-end: #FF9EAD;
        --success: #10b981;
        --info: #3b82f6;
        --warning: #f59e0b;
        --danger: #ef4444;
        --light-bg: #f8f9fa;
        --card-bg: #ffffff;
        --text-primary: #1A202C;
        --text-secondary: #718096;
        --border-color: #e5e7eb;
    }

    body {
        background: linear-gradient(135deg, #e7c9cf 50%, #beb2b2 100%);
        min-height: 100vh;
    }

    /* ── Profile Card ── */
    .profile-card { border-radius: 16px; overflow: hidden; transition: transform 0.3s ease; max-width: 280px; }
    .profile-card:hover { transform: translateY(-5px); }
    .profile-image-wrapper { position: relative; display: inline-block; }
    .profile-image { width: 100px; height: 100px; object-fit: cover; border: 4px solid var(--primary-pink); box-shadow: 0 4px 15px rgba(255,182,193,0.3); }
    .profile-badge { position: absolute; bottom: 5px; right: 5px; background: var(--success); color: white; width: 28px; height: 28px; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 3px solid white; font-size: 12px; }
    .list-group-item { transition: all 0.3s ease; font-weight: 500; }
    .list-group-item:hover:not(.active) { background-color: #fff5f7; color: var(--primary-pink); }
    .list-group-item.active { background: linear-gradient(135deg, var(--gradient-start), var(--gradient-end)); color: white; font-weight: 600; }

    /* ── Main Card ── */
    .main-card { border-radius: 20px; overflow: hidden; box-shadow: 0 4px 16px rgba(0,0,0,0.08); }
    .gradient-header { background: linear-gradient(135deg, var(--gradient-start), var(--gradient-end)); color: white; padding: 1.5rem; }

    /* ── Order Card ── */
    .order-card { background: white; border-radius: 16px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.08); transition: all 0.3s ease; margin-bottom: 1.5rem; }
    .order-card:hover { box-shadow: 0 8px 24px rgba(0,0,0,0.12); transform: translateY(-2px); }
    .order-header { padding: 1.5rem; background: linear-gradient(135deg, #fff5f7, #ffffff); border-bottom: 1px solid #f0f0f0; }
    .order-icon { width: 50px; height: 50px; background: linear-gradient(135deg, var(--gradient-start), var(--gradient-end)); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: white; font-size: 20px; margin-right: 1rem; flex-shrink: 0; }
    .order-body { padding: 1.5rem; }
    .order-footer { padding: 1rem 1.5rem; background: #f8f9fa; border-top: 1px solid #f0f0f0; }

    /* ── Info Section ── */
    .info-section { background: #f8f9fa; padding: 1.5rem; border-radius: 12px; }
    .section-title { color: #333; font-size: 0.95rem; font-weight: 600; }
    .info-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem; }
    .info-item { display: flex; gap: 0.75rem; align-items: start; }
    .info-item.full-width { grid-column: 1 / -1; }
    .info-item i { font-size: 18px; margin-top: 2px; }

    /* ── Status Badges ── */
    .status-badge { display: inline-flex; align-items: center; padding: 0.5rem 1rem; border-radius: 50px; font-size: 0.875rem; font-weight: 600; }
    .status-success  { background: #d1fae5; color: #065f46; }
    .status-warning  { background: #fef3c7; color: #92400e; }
    .status-danger   { background: #fee2e2; color: #991b1b; }
    .status-info     { background: #dbeafe; color: #1e40af; }
    .status-secondary{ background: #e5e7eb; color: #374151; }

    /* ── Buttons ── */
    .btn-gradient { background: linear-gradient(135deg, var(--gradient-start), var(--gradient-end)); color: white; border: none; font-weight: 600; transition: all 0.3s ease; }
    .btn-gradient:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(255,182,193,0.4); color: white; }
    .btn-action { padding: 0.5rem 1.25rem; border-radius: 8px; font-weight: 600; transition: all 0.3s ease; }

    /* ── Product Items Gallery ── */
    .products-section { padding: 0 1.5rem 1.25rem; }
    .products-section-title { font-size: 0.82rem; font-weight: 700; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.75rem; display: flex; align-items: center; gap: 6px; }
    .products-scroll { display: flex; gap: 0.75rem; overflow-x: auto; padding-bottom: 4px; scrollbar-width: none; }
    .products-scroll::-webkit-scrollbar { display: none; }

    .product-thumb {
        flex-shrink: 0; width: 88px;
        border-radius: 12px; overflow: hidden;
        border: 2px solid #f0f0f0; background: #f8f9fa;
        cursor: pointer; position: relative;
        transition: all 0.25s ease;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    }
    .product-thumb:hover { transform: translateY(-4px) scale(1.03); box-shadow: 0 8px 20px rgba(0,0,0,0.14); border-color: var(--primary-pink); }
    .product-thumb img { width: 88px; height: 88px; object-fit: cover; display: block; }
    .product-thumb-label { padding: 4px 6px; font-size: 0.6rem; font-weight: 500; color: #374151; line-height: 1.2; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; background: #fff; }

    .product-qty-badge { position: absolute; top: 4px; right: 4px; background: linear-gradient(135deg,var(--gradient-start),var(--gradient-end)); color: #fff; font-size: 0.55rem; font-weight: 700; padding: 2px 5px; border-radius: 8px; min-width: 16px; text-align: center; }

    .product-type-badge { position: absolute; top: 4px; left: 4px; font-size: 0.5rem; font-weight: 700; padding: 2px 5px; border-radius: 6px; text-transform: uppercase; letter-spacing: 0.04em; }
    .badge-custom  { background: rgba(245,158,11,0.9); color: #fff; }
    .badge-regular { background: rgba(107,114,128,0.75); color: #fff; }

    .products-more { flex-shrink: 0; width: 88px; height: 88px; border-radius: 12px; border: 2px dashed var(--primary-pink); background: #fff5f7; display: flex; align-items: center; justify-content: center; font-size: 0.78rem; font-weight: 700; color: var(--primary-pink-dark); cursor: pointer; transition: all 0.2s; text-align: center; line-height: 1.3; }
    .products-more:hover { background: #ffeef1; }

    /* ── Mini Map (matches reference) ── */
    .mini-map-wrapper { border-radius: 12px; overflow: hidden; border: 1px solid var(--border-color); margin: 0 0 0 0; }
    .mini-map-header { display: flex; align-items: center; justify-content: space-between; padding: 8px 14px; background: linear-gradient(135deg, var(--gradient-start), var(--gradient-end)); color: #fff; flex-wrap: wrap; gap: 6px; }
    .mini-map-title { display: flex; align-items: center; gap: 7px; font-weight: 700; font-size: 0.82rem; }
    .live-dot { width: 8px; height: 8px; background: #fff; border-radius: 50%; animation: pulse-live 1.4s ease-in-out infinite; flex-shrink: 0; }
    @keyframes pulse-live { 0%{box-shadow:0 0 0 0 rgba(255,255,255,0.7)} 70%{box-shadow:0 0 0 7px rgba(255,255,255,0)} 100%{box-shadow:0 0 0 0 rgba(255,255,255,0)} }
    .map-status-pill { font-size: 0.7rem; background: rgba(255,255,255,0.25); padding: 2px 9px; border-radius: 20px; font-weight: 600; white-space: nowrap; }
    .mini-map-canvas { height: 180px; width: 100%; }
    .mini-map-footer { display: flex; align-items: center; justify-content: space-between; padding: 7px 14px; background: #f8f9fa; border-top: 1px solid var(--border-color); font-size: 0.76rem; color: var(--text-secondary); font-weight: 500; flex-wrap: wrap; gap: 4px; }

    /* ── Modal Overlay (matches reference) ── */
    .delivery-modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.55); backdrop-filter: blur(4px); z-index: 9000; align-items: center; justify-content: center; padding: 16px; }
    .delivery-modal-overlay.active { display: flex; animation: fadeOverlay 0.2s ease; }
    @keyframes fadeOverlay { from{opacity:0} to{opacity:1} }
    .delivery-modal { background: #fff; border-radius: 20px; width: 100%; max-width: 880px; max-height: 92vh; overflow-y: auto; box-shadow: 0 24px 60px rgba(0,0,0,0.3); animation: slideModal 0.28s cubic-bezier(0.34,1.3,0.64,1); position: relative; }
    @keyframes slideModal { from{opacity:0;transform:translateY(40px) scale(0.97)} to{opacity:1;transform:translateY(0) scale(1)} }

    .dmodal-header { display: flex; align-items: center; justify-content: space-between; padding: 1.2rem 1.6rem; background: linear-gradient(135deg, var(--gradient-start), var(--gradient-end)); border-radius: 20px 20px 0 0; color: #fff; position: sticky; top: 0; z-index: 10; flex-wrap: wrap; gap: 8px; }
    .dmodal-header-left { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }
    .dmodal-order-id { background: rgba(255,255,255,0.3); padding: 4px 12px; border-radius: 8px; font-weight: 700; font-size: 0.9rem; }
    .dmodal-title { font-size: 1.1rem; font-weight: 700; }
    .dmodal-close { width: 34px; height: 34px; border-radius: 50%; background: rgba(255,255,255,0.25); border: none; color: #fff; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 1rem; transition: all 0.2s; flex-shrink: 0; }
    .dmodal-close:hover { background: rgba(255,255,255,0.4); transform: rotate(90deg); }
    .dmodal-body { padding: 1.5rem; }

    /* Badges in modal header */
    .badge-modern { padding: 0.32rem 0.8rem; border-radius: 8px; font-size: 0.78rem; font-weight: 600; display: inline-flex; align-items: center; gap: 0.3rem; }
    .badge-success { background: rgba(16,185,129,0.15); color: var(--success); }
    .badge-warning { background: rgba(245,158,11,0.15);  color: var(--warning); }
    .badge-danger  { background: rgba(239,68,68,0.15);   color: var(--danger); }
    .badge-info    { background: rgba(59,130,246,0.15);  color: var(--info); }

    /* Modal Map */
    .modal-map-wrapper { border-radius: 14px; overflow: hidden; border: 1px solid var(--border-color); margin-bottom: 1.3rem; }
    .modal-map-header { display: flex; align-items: center; justify-content: space-between; padding: 10px 15px; background: linear-gradient(135deg, var(--gradient-start), var(--gradient-end)); color: #fff; gap: 8px; flex-wrap: wrap; }
    .modal-map-title { display: flex; align-items: center; gap: 8px; font-weight: 700; font-size: 0.92rem; }
    .modal-map-status { font-weight: 400; font-size: 0.76rem; opacity: 0.85; }
    .modal-map-canvas { height: 360px; width: 100%; }
    .modal-map-footer { display: flex; align-items: center; justify-content: space-between; padding: 9px 15px; background: #f8f9fa; border-top: 1px solid var(--border-color); flex-wrap: wrap; gap: 8px; }
    .modal-map-stat { display: flex; align-items: center; gap: 6px; font-size: 0.83rem; font-weight: 600; color: var(--text-primary); }
    .modal-map-stat span { color: var(--text-secondary); font-weight: 400; }

    /* Route Steps */
    .route-steps { background: #f8f9fa; border: 1px solid var(--border-color); border-radius: 12px; padding: 13px; margin-bottom: 1.2rem; max-height: 200px; overflow-y: auto; }
    .route-steps-title { font-size: 0.78rem; font-weight: 700; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 9px; }
    .step-item { display: flex; align-items: flex-start; gap: 9px; padding: 6px 0; border-bottom: 1px solid var(--border-color); }
    .step-item:last-child { border-bottom: none; }
    .step-num { min-width: 22px; height: 22px; border-radius: 50%; background: linear-gradient(135deg, var(--gradient-start), var(--gradient-end)); color: #fff; font-size: 0.65rem; font-weight: 700; display: flex; align-items: center; justify-content: center; margin-top: 1px; flex-shrink: 0; }
    .step-text { font-size: 0.81rem; color: var(--text-primary); line-height: 1.4; }
    .step-dist { font-size: 0.72rem; color: var(--text-secondary); margin-top: 1px; }

    /* Delivery Info Card */
    .delivery-info-card { background: #f8f9fa; padding: 1.5rem; border-radius: 12px; border: 2px solid #f0f0f0; }
    .delivery-staff-info { border-left: 4px solid var(--primary-pink) !important; }
    .staff-icon { width: 32px; height: 32px; background: linear-gradient(135deg, var(--gradient-start), var(--gradient-end)); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; flex-shrink: 0; }

    /* Price Breakdown */
    .price-breakdown { background: white; border-radius: 10px; border: 1px solid #f0f0f0; padding: 1rem 1.25rem; }
    .price-row { display: flex; justify-content: space-between; align-items: center; padding: 0.35rem 0; border-bottom: 1px dashed #f0f0f0; font-size: 0.9rem; }
    .price-row:last-child { border-bottom: none; }
    .price-row-total { border-top: 2px solid #e5e7eb; border-bottom: none; margin-top: 0.35rem; padding-top: 0.65rem; }
    .price-label { color: #6b7280; }
    .price-value { color: #111827; }

    /* Modal Product Items */
    .modal-products-section { margin-bottom: 1.3rem; }
    .modal-products-title { font-size: 0.82rem; font-weight: 700; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.75rem; display: flex; align-items: center; gap: 6px; }

    .product-item-row { display: flex; align-items: center; gap: 1rem; padding: 0.85rem 1rem; background: #f8f9fa; border: 1px solid #f0f0f0; border-radius: 12px; margin-bottom: 0.6rem; transition: all 0.2s; }
    .product-item-row:hover { background: #fff5f7; transform: translateX(2px); }
    .product-item-img { width: 60px; height: 60px; border-radius: 10px; object-fit: cover; border: 2px solid #f0f0f0; flex-shrink: 0; cursor: pointer; transition: all 0.2s; }
    .product-item-img:hover { transform: scale(1.06); box-shadow: 0 4px 14px rgba(0,0,0,0.12); }
    .product-item-img-placeholder { width: 60px; height: 60px; border-radius: 10px; background: #fff5f7; border: 2px solid #f0f0f0; display: flex; align-items: center; justify-content: center; flex-shrink: 0; color: var(--primary-pink); font-size: 1.3rem; }
    .product-item-info { flex: 1; min-width: 0; }
    .product-item-name { font-weight: 600; font-size: 0.88rem; color: var(--text-primary); margin-bottom: 3px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .product-item-type { font-size: 0.65rem; font-weight: 700; padding: 2px 7px; border-radius: 6px; text-transform: uppercase; letter-spacing: 0.04em; display: inline-block; margin-bottom: 3px; }
    .type-custom  { background: rgba(245,158,11,0.12); color: #b45309; }
    .type-regular { background: rgba(107,114,128,0.1);  color: #4b5563; }
    .product-item-qty { font-size: 0.76rem; color: var(--text-secondary); }
    .product-item-price { text-align: right; flex-shrink: 0; }
    .product-item-unit  { font-size: 0.72rem; color: var(--text-secondary); }
    .product-item-total { font-size: 1rem; font-weight: 700; color: var(--text-primary); }

    /* Image lightbox */
    .img-lightbox { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.9); z-index: 99999; align-items: center; justify-content: center; padding: 20px; }
    .img-lightbox.show { display: flex; animation: fadeOverlay 0.2s ease; }
    .img-lightbox img { max-width: 90%; max-height: 85vh; border-radius: 12px; object-fit: contain; }
    .lightbox-close { position: absolute; top: 20px; right: 28px; color: rgba(255,255,255,0.7); font-size: 2.2rem; cursor: pointer; transition: 0.2s; line-height: 1; }
    .lightbox-close:hover { color: #fff; }

    /* Empty State */
    .empty-state { text-align: center; padding: 4rem 2rem; }
    .empty-icon { width: 120px; height: 120px; background: linear-gradient(135deg, #fef5f7, #fff5f7); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 2rem; }
    .empty-icon i { font-size: 60px; color: var(--primary-pink); opacity: 0.6; }

    /* Toast */
    .toast-notification { position: fixed; top: 20px; right: 20px; padding: 1rem 1.5rem; border-radius: 12px; color: white; font-weight: 500; box-shadow: 0 4px 12px rgba(0,0,0,0.15); z-index: 9999; animation: slideIn 0.3s ease; }
    .toast-notification.success { background: linear-gradient(135deg, #34d399, #10b981); }
    .toast-notification.error   { background: linear-gradient(135deg, #f87171, #ef4444); }
    @keyframes slideIn { from{transform:translateX(400px);opacity:0} to{transform:translateX(0);opacity:1} }

    ::-webkit-scrollbar { width: 6px; }
    ::-webkit-scrollbar-track { background: #f8f9fa; }
    ::-webkit-scrollbar-thumb { background: #e5e7eb; border-radius: 4px; }

    @media (max-width: 768px) {
        .info-grid { grid-template-columns: 1fr; }
        .profile-card { max-width: 100%; margin-bottom: 1.5rem; }
        .delivery-modal { max-height: 96vh; }
        .modal-map-canvas { height: 240px; }
        .order-header .row { flex-direction: column; }
        .order-footer .d-flex { flex-direction: column; }
        .btn-action { width: 100%; margin-bottom: 0.5rem; }
    }
    @media (max-width: 480px) {
        .toast-notification { left: 10px; right: 10px; text-align: center; }
    }
    </style>
</head>
<body>

@include('components.navbar')

<div class="container py-5">
    <div class="row g-4">

        <!-- ── Sidebar ── -->
        <div class="col-lg-3">
            <div class="card shadow-sm border-0 profile-card">
                <div class="card-body text-center p-4">
                    <div class="profile-image-wrapper mb-3">
                        <img src="{{ asset('asset/images/default-profile.png') }}" alt="User Image"
                             class="rounded-circle profile-image">
                        <div class="profile-badge">
                            <i class="fas fa-check"></i>
                        </div>
                    </div>
                    <h5 class="mb-1 fw-bold">{{ Auth::user()->name }}</h5>
                    <p class="text-muted small mb-0">{{ Auth::user()->email }}</p>
                </div>
                <div class="list-group list-group-flush">
                    <a href="{{ route('profile.index') }}" class="list-group-item list-group-item-action border-0 py-3">
                        <i class="fas fa-user me-3"></i> My Account
                    </a>
                    <a href="{{ route('profile.purchase-history') }}" class="list-group-item list-group-item-action active border-0 py-3">
                        <i class="fas fa-history me-3"></i> Purchase History
                    </a>
                    <a href="{{ route('profile.track-order') }}" class="list-group-item list-group-item-action border-0 py-3">
                        <i class="fas fa-truck me-3"></i> Track Order
                    </a>
                </div>
            </div>
        </div>

        <!-- ── Main Content ── -->
        <div class="col-lg-9">
            <div class="card shadow-sm border-0 main-card">
                <div class="card-header border-0 gradient-header">
                    <h4 class="mb-0 text-white fw-bold">
                        <i class="fas fa-history me-2"></i> Purchase History
                    </h4>
                    <p class="mb-0 text-white opacity-75 small mt-1">View all your past orders and receipts</p>
                </div>
                <div class="card-body p-4">

                    @if($orders->count() > 0)
                        @foreach($orders as $order)
                        @php
                            $orderItems = DB::table('order_item')
                                ->join('products','order_item.product_id','=','products.id')
                                ->leftJoin('product_customizations','order_item.customization_id','=','product_customizations.id')
                                ->where('order_item.order_id', $order->id)
                                ->select(
                                    'products.name',
                                    'products.image',
                                    'order_item.quantity',
                                    'order_item.price',
                                    'order_item.is_customization',
                                    'product_customizations.custom_image',
                                    'product_customizations.customization_name'
                                )
                                ->get();
                            $ds = $order->delivery_status;
                        @endphp

                        <div class="order-card">
                            <!-- Order Header -->
                            <div class="order-header">
                                <div class="row align-items-center">
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-center">
                                            <div class="order-icon">
                                                <i class="fas fa-receipt"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-1 fw-bold">Order #{{ $order->id }}</h6>
                                                <p class="text-muted small mb-0">
                                                    <i class="fas fa-calendar-alt me-1"></i>
                                                    {{ \Carbon\Carbon::parse($order->created_at)->format('M d, Y • h:i A') }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 text-md-end mt-3 mt-md-0">
                                        <h5 class="mb-0 text-success fw-bold">₱{{ number_format($order->grand_total ?? $order->total, 2) }}</h5>
                                        @if(!empty($order->delivery_fee) && $order->delivery_fee > 0)
                                            <small class="text-muted">
                                                Subtotal ₱{{ number_format($order->total, 2) }}
                                                + Delivery ₱{{ number_format($order->delivery_fee, 2) }}
                                            </small>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="order-body">
                                <div class="row g-3">
                                    <!-- Info Section -->
                                    <div class="col-md-8">
                                        <div class="info-section">
                                            <h6 class="section-title mb-3">
                                                <i class="fas fa-info-circle me-2"></i>Order Information
                                            </h6>
                                            <div class="info-grid">
                                                <div class="info-item">
                                                    <i class="fas fa-user text-muted"></i>
                                                    <div>
                                                        <small class="text-muted d-block">Customer</small>
                                                        <span class="fw-medium">{{ $order->customer_name }}</span>
                                                    </div>
                                                </div>
                                                <div class="info-item">
                                                    <i class="fas fa-phone text-muted"></i>
                                                    <div>
                                                        <small class="text-muted d-block">Phone</small>
                                                        <span class="fw-medium">{{ $order->phone }}</span>
                                                    </div>
                                                </div>
                                                <div class="info-item full-width">
                                                    <i class="fas fa-map-marker-alt text-muted"></i>
                                                    <div>
                                                        <small class="text-muted d-block">Address</small>
                                                        <span class="fw-medium">{{ $order->address }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Status Section -->
                                    <div class="col-md-4">
                                        <div class="status-section">
                                            <div class="status-item mb-3">
                                                <small class="text-muted d-block mb-2">Payment Status</small>
                                                @if($order->payment_status == 'Paid')
                                                    <span class="status-badge status-success"><i class="fas fa-check-circle me-1"></i> Paid</span>
                                                @elseif($order->payment_status == 'Pending')
                                                    <span class="status-badge status-warning"><i class="fas fa-clock me-1"></i> Pending</span>
                                                @elseif($order->payment_status == 'Unsuccessful')
                                                    <span class="status-badge status-danger"><i class="fas fa-times-circle me-1"></i> Unsuccessful</span>
                                                @else
                                                    <span class="status-badge status-secondary">{{ $order->payment_status }}</span>
                                                @endif
                                            </div>
                                            <div class="status-item">
                                                <small class="text-muted d-block mb-2">Delivery Status</small>
                                                @if($ds == 'Delivered')
                                                    <span class="status-badge status-success"><i class="fas fa-check-double me-1"></i> Delivered</span>
                                                @elseif($ds == 'Out for Delivery')
                                                    <span class="status-badge status-info"><i class="fas fa-shipping-fast me-1"></i> Out for Delivery</span>
                                                @elseif($ds == 'Cancelled')
                                                    <span class="status-badge status-danger"><i class="fas fa-ban me-1"></i> Cancelled</span>
                                                @else
                                                    <span class="status-badge status-warning"><i class="fas fa-hourglass-half me-1"></i> Pending</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- ── Product Items Gallery ── -->
                                <div class="products-section mt-4 px-0">
                                    <div class="products-section-title">
                                        <i class="fas fa-shopping-bag" style="color:var(--primary-pink-dark);"></i>
                                        Order Items ({{ $orderItems->count() }})
                                    </div>
                                    <div class="products-scroll">
                                        @foreach($orderItems->take(5) as $item)
                                        @php
                                            $isCustom = $item->is_customization == 1;
                                            $imgSrc = null;
                                            if ($isCustom && !empty($item->custom_image)) {
                                                $imgSrc = asset('uploads/custom_orders/' . $item->custom_image);
                                            } elseif (!empty($item->image)) {
                                                $imgSrc = asset('asset/images/' . $item->image);
                                            }
                                            $displayName = $isCustom && !empty($item->customization_name) ? $item->customization_name : $item->name;
                                        @endphp
                                        <div class="product-thumb"
                                             onclick="openLightbox('{{ $imgSrc ?? '' }}', '{{ addslashes($displayName) }}')">
                                            @if($imgSrc)
                                                <img src="{{ $imgSrc }}" alt="{{ $displayName }}"
                                                     onerror="this.parentElement.innerHTML='<div style=\'width:88px;height:88px;background:#fff5f7;display:flex;align-items:center;justify-content:center;color:var(--primary-pink);font-size:1.8rem;\'><i class=\'fas fa-image\'></i></div>'">
                                            @else
                                                <div style="width:88px;height:88px;background:#fff5f7;display:flex;align-items:center;justify-content:center;color:var(--primary-pink);font-size:1.8rem;">
                                                    <i class="fas fa-image"></i>
                                                </div>
                                            @endif
                                            <div class="product-thumb-label">{{ Str::limit($displayName, 13) }}</div>
                                            <div class="product-qty-badge">×{{ $item->quantity }}</div>
                                            <div class="product-type-badge {{ $isCustom ? 'badge-custom' : 'badge-regular' }}">
                                                {{ $isCustom ? '✦ Custom' : 'Regular' }}
                                            </div>
                                        </div>
                                        @endforeach

                                        @if($orderItems->count() > 5)
                                            <div class="products-more" onclick="openDetailModal({{ $order->id }})">
                                                +{{ $orderItems->count() - 5 }}<br>more
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- ── Mini Map ── -->
                                @if($ds !== 'Cancelled')
                                <div class="mini-map-wrapper mt-3" onclick="event.stopPropagation()">
                                    <div class="mini-map-header">
                                        <div class="mini-map-title">
                                            @if($ds == 'Out for Delivery')
                                                <span class="live-dot"></span> Live Delivery Tracking
                                            @elseif($ds == 'Delivered')
                                                <i class="fas fa-check-circle me-1" style="color:#fff;opacity:0.9;"></i> Delivered Location
                                            @else
                                                <i class="fas fa-map-marker-alt me-1"></i> Delivery Destination
                                            @endif
                                        </div>
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="map-status-pill" id="hist-mini-status-{{ $order->id }}">Loading…</span>
                                        </div>
                                    </div>
                                    <div id="hist-mini-map-{{ $order->id }}" class="mini-map-canvas"></div>
                                    <div class="mini-map-footer">
                                        <span>
                                            <i class="fas fa-road me-1" style="color:var(--primary-pink-dark);"></i>
                                            <span id="hist-mini-dist-{{ $order->id }}">—</span>
                                        </span>
                                        <span>
                                            <i class="fas fa-clock me-1" style="color:#f59e0b;"></i>
                                            <span id="hist-mini-eta-{{ $order->id }}">—</span>
                                        </span>
                                        <button class="btn btn-gradient btn-sm py-1 px-3"
                                                onclick="openDetailModal({{ $order->id }})"
                                                style="font-size:0.75rem;">
                                            <i class="fas fa-expand-alt me-1"></i>View Full Map
                                        </button>
                                    </div>
                                </div>
                                @endif

                                <!-- Delivery Staff Info (on card) -->
                                @if($ds == 'Out for Delivery' && $order->coordinator_id)
                                <div class="delivery-staff-info mt-3 p-3 bg-white rounded-3 border">
                                    <h6 class="fw-bold mb-2" style="color:var(--primary-pink-dark);">
                                        <i class="fas fa-user-tie me-2"></i>Your Delivery Staff
                                    </h6>
                                    <div class="row g-2">
                                        <div class="col-sm-6">
                                            <div class="d-flex align-items-center">
                                                <div class="staff-icon me-2"><i class="fas fa-user"></i></div>
                                                <div>
                                                    <small class="text-muted d-block">Name</small>
                                                    <span class="fw-bold">{{ $order->delivery_name ?? 'N/A' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="d-flex align-items-center">
                                                <div class="staff-icon me-2"><i class="fas fa-phone-alt"></i></div>
                                                <div>
                                                    <small class="text-muted d-block">Contact</small>
                                                    <span class="fw-bold">{{ $order->delivery_phone ?? 'N/A' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @elseif($ds == 'Delivered' && $order->coordinator_id)
                                <div class="delivery-staff-info mt-3 p-3 bg-white rounded-3 border">
                                    <h6 class="fw-bold mb-2" style="color:var(--success);">
                                        <i class="fas fa-check-circle me-2"></i>Delivered By
                                    </h6>
                                    <div class="d-flex align-items-center">
                                        <div class="staff-icon me-2" style="background:var(--success);">
                                            <i class="fas fa-user-check"></i>
                                        </div>
                                        <div>
                                            <span class="fw-bold">{{ $order->delivery_name ?? 'N/A' }}</span>
                                            <small class="text-muted d-block">Delivery Staff</small>
                                        </div>
                                    </div>
                                </div>
                                @elseif($ds == 'Pending' && !$order->coordinator_id)
                                <div class="delivery-staff-info mt-3 p-3 bg-light rounded-3 border">
                                    <div class="d-flex align-items-center">
                                        <div class="me-3" style="width:40px;height:40px;background:#e5e7eb;border-radius:50%;display:flex;align-items:center;justify-content:center;color:#9ca3af;">
                                            <i class="fas fa-user-clock"></i>
                                        </div>
                                        <div>
                                            <p class="fw-bold mb-0">Waiting for delivery assignment</p>
                                            <small class="text-muted">A delivery staff will be assigned soon.</small>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>

                            <div class="order-footer">
                                <div class="d-flex gap-2 justify-content-end">
                                    <button class="btn btn-gradient btn-action" onclick="openDetailModal({{ $order->id }})">
                                        <i class="fas fa-eye me-1"></i> View Details
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- ═══ DETAIL MODAL ═══ --}}
                        <div class="delivery-modal-overlay" id="hist-overlay-{{ $order->id }}"
                             onclick="closeDetailModal({{ $order->id }})">
                            <div class="delivery-modal" onclick="event.stopPropagation()">

                                <div class="dmodal-header">
                                    <div class="dmodal-header-left">
                                        <span class="dmodal-order-id">#{{ $order->id }}</span>
                                        <span class="dmodal-title">{{ $order->customer_name }}</span>
                                        @if($ds == 'Delivered')
                                            <span class="badge-modern badge-success"><i class="fas fa-check-circle"></i> Delivered</span>
                                        @elseif($ds == 'Out for Delivery')
                                            <span class="badge-modern badge-info"><i class="fas fa-shipping-fast"></i> Out for Delivery</span>
                                        @elseif($ds == 'Cancelled')
                                            <span class="badge-modern badge-danger"><i class="fas fa-times-circle"></i> Cancelled</span>
                                        @else
                                            <span class="badge-modern badge-warning"><i class="fas fa-clock"></i> Pending</span>
                                        @endif
                                    </div>
                                    <button class="dmodal-close" onclick="closeDetailModal({{ $order->id }})">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>

                                <div class="dmodal-body">

                                    {{-- Big Map --}}
                                    @if($ds !== 'Cancelled')
                                    <div class="modal-map-wrapper">
                                        <div class="modal-map-header">
                                            <div class="modal-map-title">
                                                @if($ds == 'Out for Delivery')
                                                    <span class="live-dot"></span> Live Route Tracking
                                                @elseif($ds == 'Delivered')
                                                    <i class="fas fa-check-circle me-1"></i> Delivered Location
                                                @else
                                                    <i class="fas fa-map-marker-alt me-1"></i> Delivery Location
                                                @endif
                                                <span class="modal-map-status" id="hist-modal-map-status-{{ $order->id }}">Loading map…</span>
                                            </div>
                                            <span id="hist-modal-maps-link-{{ $order->id }}"></span>
                                        </div>
                                        <div id="hist-modal-map-{{ $order->id }}" class="modal-map-canvas"></div>
                                        <div class="modal-map-footer">
                                            <div class="modal-map-stat">
                                                <i class="fas fa-road" style="color:var(--primary-pink-dark);"></i>
                                                Distance: <span id="hist-modal-dist-{{ $order->id }}">—</span>
                                            </div>
                                            @if($ds == 'Out for Delivery')
                                            <div class="modal-map-stat">
                                                <i class="fas fa-clock" style="color:#f59e0b;"></i>
                                                ETA: <span id="hist-modal-eta-{{ $order->id }}">—</span>
                                            </div>
                                            @endif
                                            <div class="modal-map-stat">
                                                <i class="fas fa-map-marker-alt" style="color:#ef4444;"></i>
                                                <span style="font-size:0.75rem;color:#6b7280;max-width:220px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $order->address }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    @if($ds == 'Out for Delivery')
                                    <div class="route-steps" id="hist-route-steps-{{ $order->id }}">
                                        <div class="route-steps-title"><i class="fas fa-route me-2"></i>Turn-by-Turn Directions</div>
                                        <div id="hist-steps-list-{{ $order->id }}">
                                            <div class="step-item"><span style="color:#9ca3af;font-size:0.82rem;">Getting directions…</span></div>
                                        </div>
                                    </div>
                                    @endif
                                    @endif

                                    {{-- Delivery Info --}}
                                    <div class="delivery-info-card mb-4">
                                        <h6 class="fw-bold mb-3">
                                            <i class="fas fa-info-circle me-2"></i>Order Information
                                        </h6>
                                        <div class="info-grid">
                                            <div class="info-item">
                                                <i class="fas fa-user text-muted"></i>
                                                <div>
                                                    <small class="text-muted d-block">Customer Name</small>
                                                    <span class="fw-medium">{{ $order->customer_name }}</span>
                                                </div>
                                            </div>
                                            <div class="info-item">
                                                <i class="fas fa-phone text-muted"></i>
                                                <div>
                                                    <small class="text-muted d-block">Contact Number</small>
                                                    <span class="fw-medium">{{ $order->phone }}</span>
                                                </div>
                                            </div>
                                            <div class="info-item">
                                                <i class="fas fa-calendar text-muted"></i>
                                                <div>
                                                    <small class="text-muted d-block">Order Date</small>
                                                    <span class="fw-medium">{{ \Carbon\Carbon::parse($order->created_at)->format('M d, Y • h:i A') }}</span>
                                                </div>
                                            </div>
                                            <div class="info-item">
                                                <i class="fas fa-credit-card text-muted"></i>
                                                <div>
                                                    <small class="text-muted d-block">Payment</small>
                                                    <span class="fw-medium">{{ $order->payment_method ?? 'COD' }}
                                                        @if($order->payment_status=='Paid') <span class="text-success small">· Paid</span>@endif
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="info-item full-width">
                                                <i class="fas fa-map-marker-alt text-muted"></i>
                                                <div>
                                                    <small class="text-muted d-block">Delivery Address</small>
                                                    <span class="fw-medium">{{ $order->address }}</span>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Delivery Staff Info --}}
                                        @if($ds == 'Out for Delivery' && $order->coordinator_id)
                                        <div class="delivery-staff-info mt-3 p-3 bg-white rounded-3 border">
                                            <h6 class="fw-bold mb-2" style="color:var(--primary-pink-dark);">
                                                <i class="fas fa-motorcycle me-2"></i>Your Rider
                                            </h6>
                                            <div class="row g-2">
                                                <div class="col-sm-4">
                                                    <small class="text-muted d-block">Name</small>
                                                    <span class="fw-bold">{{ $order->delivery_name ?? 'N/A' }}</span>
                                                </div>
                                                <div class="col-sm-4">
                                                    <small class="text-muted d-block">Phone</small>
                                                    <span class="fw-bold">{{ $order->delivery_phone ?? 'N/A' }}</span>
                                                </div>
                                                <div class="col-sm-4">
                                                    <small class="text-muted d-block">Email</small>
                                                    <span class="fw-bold">{{ $order->delivery_email ?? 'N/A' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        @elseif($ds == 'Delivered' && $order->coordinator_id)
                                        <div class="delivery-staff-info mt-3 p-3 bg-white rounded-3 border">
                                            <h6 class="fw-bold mb-2" style="color:var(--success);">
                                                <i class="fas fa-check-circle me-2"></i>Delivered By
                                            </h6>
                                            <div class="d-flex align-items-center">
                                                <div class="staff-icon me-2" style="background:var(--success);">
                                                    <i class="fas fa-user-check"></i>
                                                </div>
                                                <div>
                                                    <span class="fw-bold">{{ $order->delivery_name ?? 'N/A' }}</span>
                                                    <small class="text-muted d-block">Delivery Staff</small>
                                                </div>
                                            </div>
                                        </div>
                                        @elseif($ds == 'Pending' && !$order->coordinator_id)
                                        <div class="delivery-staff-info mt-3 p-3 bg-light rounded-3 border">
                                            <div class="d-flex align-items-center">
                                                <div class="me-3" style="width:40px;height:40px;background:#e5e7eb;border-radius:50%;display:flex;align-items:center;justify-content:center;color:#9ca3af;">
                                                    <i class="fas fa-user-clock"></i>
                                                </div>
                                                <div>
                                                    <p class="fw-bold mb-0">Waiting for delivery assignment</p>
                                                    <small class="text-muted">A delivery staff will be assigned soon.</small>
                                                </div>
                                            </div>
                                        </div>
                                        @endif

                                        {{-- Price Breakdown --}}
                                        <div class="price-breakdown mt-3">
                                            <h6 class="fw-bold mb-2"><i class="fas fa-receipt me-2"></i>Order Summary</h6>
                                            <div class="price-row">
                                                <span class="price-label">Subtotal</span>
                                                <span class="price-value">₱{{ number_format($order->total, 2) }}</span>
                                            </div>
                                            <div class="price-row">
                                                <span class="price-label">
                                                    <i class="fas fa-truck me-1 text-muted" style="font-size:0.75rem;"></i>Delivery Fee
                                                    @if(!empty($order->delivery_distance_km) && $order->delivery_distance_km > 0)
                                                        <small class="text-muted">(~{{ number_format($order->delivery_distance_km, 1) }} km)</small>
                                                    @endif
                                                </span>
                                                <span class="price-value">
                                                    @if(!empty($order->delivery_fee) && $order->delivery_fee > 0)
                                                        ₱{{ number_format($order->delivery_fee, 2) }}
                                                    @else <span class="text-muted">—</span>
                                                    @endif
                                                </span>
                                            </div>
                                            <div class="price-row price-row-total">
                                                <span class="price-label fw-bold">Grand Total</span>
                                                <span class="price-value fw-bold text-success" style="font-size:1.05rem;">
                                                    ₱{{ number_format($order->grand_total ?? $order->total, 2) }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Product Items in Modal --}}
                                    <div class="modal-products-section">
                                        <div class="modal-products-title">
                                            <i class="fas fa-shopping-bag" style="color:var(--primary-pink-dark);"></i>
                                            Order Items ({{ $orderItems->count() }})
                                        </div>

                                        @foreach($orderItems as $item)
                                        @php
                                            $isCustom = $item->is_customization == 1;
                                            $imgSrc = null;
                                            if ($isCustom && !empty($item->custom_image)) {
                                                $imgSrc = asset('uploads/custom_orders/' . $item->custom_image);
                                            } elseif (!empty($item->image)) {
                                                $imgSrc = asset('asset/images/' . $item->image);
                                            }
                                            $displayName = $isCustom && !empty($item->customization_name) ? $item->customization_name : $item->name;
                                        @endphp
                                        <div class="product-item-row">
                                            @if($imgSrc)
                                                <img src="{{ $imgSrc }}" alt="{{ $displayName }}"
                                                     class="product-item-img"
                                                     onclick="openLightbox('{{ $imgSrc }}','{{ addslashes($displayName) }}')"
                                                     onerror="this.outerHTML='<div class=\'product-item-img-placeholder\'><i class=\'fas fa-image\'></i></div>'">
                                            @else
                                                <div class="product-item-img-placeholder"><i class="fas fa-image"></i></div>
                                            @endif

                                            <div class="product-item-info">
                                                <div class="product-item-name">{{ $displayName }}</div>
                                                <div class="product-item-type {{ $isCustom ? 'type-custom' : 'type-regular' }}">
                                                    {{ $isCustom ? '✦ Custom Made' : 'Regular' }}
                                                </div>
                                                <div class="product-item-qty">Qty: {{ $item->quantity }}</div>
                                            </div>

                                            <div class="product-item-price">
                                                <div class="product-item-unit">₱{{ number_format($item->price, 2) }} each</div>
                                                <div class="product-item-total">₱{{ number_format($item->price * $item->quantity, 2) }}</div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>

                                </div>{{-- /dmodal-body --}}
                            </div>{{-- /delivery-modal --}}
                        </div>{{-- /overlay --}}

                        @endforeach
                    @else
                        <div class="empty-state">
                            <div class="empty-icon">
                                <i class="fas fa-receipt"></i>
                            </div>
                            <h5 class="fw-bold mb-2">No Purchase History</h5>
                            <p class="text-muted mb-4">You haven't placed any orders yet. Start shopping to see your history here!</p>
                            <a href="{{ route('shop') }}" class="btn btn-gradient btn-lg">
                                <i class="fas fa-shopping-bag me-2"></i> Start Shopping
                            </a>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>

{{-- Image Lightbox --}}
<div class="img-lightbox" id="imgLightbox" onclick="closeLightbox()">
    <span class="lightbox-close" onclick="closeLightbox()">&times;</span>
    <img id="lightboxImg" src="" alt="">
</div>

@if(session('success'))
    <div class="toast-notification success">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div class="toast-notification error">
        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
    </div>
@endif

@include('components.footer')

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
// ─── Store coordinates ───────────────────────────
const STORE_LAT = 10.314152;
const STORE_LNG = 123.906935;

// ─── Order map data ──────────────────────────────
@php
    $histMapOrders = collect($orders)
        ->whereNotIn('delivery_status', ['Cancelled'])
        ->map(fn($o) => [
            'id'     => $o->id,
            'lat'    => isset($o->latitude)  && $o->latitude  ? (float)$o->latitude  : null,
            'lng'    => isset($o->longitude) && $o->longitude ? (float)$o->longitude : null,
            'status' => $o->delivery_status,
        ])->values();
@endphp
const histMapData = @json($histMapOrders);
const histCoords  = {};
histMapData.forEach(o => { histCoords[o.id] = o; });

const mapInst    = {};
const modalInit  = {};
const liveTrk    = {};
const riderMkr   = {};

// ─── Helpers ─────────────────────────────────────
function hvs(a,b,c,d){const R=6371,r=x=>x*Math.PI/180,q=Math.sin(r(c-a)/2)**2+Math.cos(r(a))*Math.cos(r(c))*Math.sin(r(d-b)/2)**2;return R*2*Math.atan2(Math.sqrt(q),Math.sqrt(1-q));}
function fmtD(km){return km<1?(km*1000).toFixed(0)+' m':km.toFixed(1)+' km';}
function fmtT(s){const m=Math.round(s/60);return m<1?'Arriving!':m<60?m+' min':Math.floor(m/60)+'h '+(m%60)+'m';}
function sel(id,v,html=false){const e=document.getElementById(id);if(e)html?e.innerHTML=v:e.textContent=v;}
function cap(s){return s?s[0].toUpperCase()+s.slice(1):s;}

// ─── Lightbox ────────────────────────────────────
function openLightbox(src,name){
    if(!src)return;
    document.getElementById('lightboxImg').src=src;
    document.getElementById('lightboxImg').alt=name;
    document.getElementById('imgLightbox').classList.add('show');
    document.body.style.overflow='hidden';
}
function closeLightbox(){
    document.getElementById('imgLightbox').classList.remove('show');
    document.body.style.overflow='';
}
document.getElementById('lightboxImg')?.addEventListener('click',e=>e.stopPropagation());

// ─── Modal open/close ────────────────────────────
function openDetailModal(id){
    const o=document.getElementById('hist-overlay-'+id);
    if(!o)return;
    o.classList.add('active');
    document.body.style.overflow='hidden';
    if(!modalInit[id]){modalInit[id]=true;if(histCoords[id])setTimeout(()=>initHistMap(id,'modal'),80);}
}
function closeDetailModal(id){
    const o=document.getElementById('hist-overlay-'+id);
    if(o)o.classList.remove('active');
    document.body.style.overflow='';
    const k='modal'+id;
    if(liveTrk[k]){clearInterval(liveTrk[k]);delete liveTrk[k];}
}
document.addEventListener('keydown',e=>{
    if(e.key!=='Escape')return;
    document.querySelectorAll('.delivery-modal-overlay.active').forEach(el=>closeDetailModal(el.id.replace('hist-overlay-','')));
    closeLightbox();
});

// ─── Map init ────────────────────────────────────
function initHistMap(id,type){
    const d=histCoords[id];if(!d)return;
    const sk=(type==='mini'?'hist-mini-status-':'hist-modal-map-status-')+id;
    sel(sk,d.lat&&d.lng?'Location found':'⚠ No coords saved');
    buildHistMap(id,d.lat||10.3157,d.lng||123.8854,type,d.status);
}

function buildHistMap(id,dLat,dLng,type,status){
    const elId=(type==='mini'?'hist-mini-map-':'hist-modal-map-')+id;
    const el=document.getElementById(elId);if(!el)return;
    const key=type+id;if(mapInst[key])return;
    const isModal=type==='modal', isLive=status==='Out for Delivery', isDone=status==='Delivered';

    const map=L.map(el,{zoomControl:isModal,scrollWheelZoom:isModal});
    mapInst[key]=map;
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',{attribution:'© OpenStreetMap',maxZoom:19}).addTo(map);

    // Destination marker
    const destHtml=isDone
        ?'<div style="width:34px;height:34px;border-radius:50% 50% 50% 0;background:linear-gradient(135deg,#10b981,#34d399);display:flex;align-items:center;justify-content:center;color:#fff;transform:rotate(-45deg);box-shadow:0 3px 10px rgba(0,0,0,0.25);"><i class="fas fa-check" style="transform:rotate(45deg);font-size:0.8rem;"></i></div>'
        :'<div style="width:34px;height:34px;border-radius:50% 50% 50% 0;background:linear-gradient(135deg,#f093fb,#f5576c);display:flex;align-items:center;justify-content:center;color:#fff;transform:rotate(-45deg);box-shadow:0 3px 10px rgba(0,0,0,0.25);"><i class="fas fa-home" style="transform:rotate(45deg);font-size:0.8rem;"></i></div>';

    L.marker([dLat,dLng],{icon:L.divIcon({className:'',html:destHtml,iconSize:[34,34],iconAnchor:[17,34]})})
        .addTo(map).bindPopup(isDone?'<b>✅ Delivered here</b>':'<b>📦 Your delivery address</b>');

    if(!isLive){
        fetch(`https://router.project-osrm.org/route/v1/driving/${STORE_LNG},${STORE_LAT};${dLng},${dLat}?overview=full&geometries=geojson`)
            .then(r=>r.json()).then(data=>{
                if(data.code==='Ok'&&data.routes?.length){
                    const c=data.routes[0].geometry.coordinates.map(x=>[x[1],x[0]]);
                    const d=data.routes[0].distance/1000;
                    addStoreMarker(map);
                    L.polyline(c,{color:isDone?'#10b981':'#FFB6C1',weight:isModal?5:3,opacity:0.8,dashArray:isDone?null:'8,8',lineJoin:'round',lineCap:'round'}).addTo(map);
                    map.fitBounds(L.polyline(c).getBounds(),{padding:isModal?[50,50]:[25,25]});
                    if(isModal){
                        sel('hist-modal-dist-'+id,fmtD(d)+' from store');
                        sel('hist-modal-maps-link-'+id,`<a href="https://www.google.com/maps/dir/${STORE_LAT},${STORE_LNG}/${dLat},${dLng}" target="_blank" style="color:#fff;font-size:0.75rem;background:rgba(255,255,255,0.25);padding:3px 10px;border-radius:20px;text-decoration:none;"><i class="fas fa-map-marked-alt me-1"></i>Open Maps</a>`,true);
                    }else sel('hist-mini-dist-'+id,fmtD(d));
                }else map.setView([dLat,dLng],15);
            }).catch(()=>map.setView([dLat,dLng],15));
        sel((type==='mini'?'hist-mini-status-':'hist-modal-map-status-')+id,isDone?'✓ Delivered':'Pending');
        return;
    }
    drawHistRoute(map,dLat,dLng,id,type);
    requestHistGPS(map,id,dLat,dLng,type);
}

function addStoreMarker(map){
    L.marker([STORE_LAT,STORE_LNG],{icon:L.divIcon({className:'',
        html:'<div style="background:linear-gradient(135deg,#FFB6C1,#FF9EAD);width:30px;height:30px;border-radius:50%;border:3px solid #fff;display:flex;align-items:center;justify-content:center;box-shadow:0 2px 8px rgba(255,182,193,0.6);"><i class="fas fa-store" style="color:#fff;font-size:0.65rem;"></i></div>',
        iconSize:[30,30],iconAnchor:[15,15]})
    }).addTo(map).bindPopup('<b>🏪 HookcraftAvenue Store</b>');
}

async function drawHistRoute(map,dLat,dLng,id,type){
    const isModal=type==='modal',sk=(isModal?'hist-modal-map-status-':'hist-mini-status-')+id;
    try{
        const res=await fetch(`https://router.project-osrm.org/route/v1/driving/${STORE_LNG},${STORE_LAT};${dLng},${dLat}?overview=full&geometries=geojson&steps=true`);
        const data=await res.json();
        if(data.code!=='Ok'||!data.routes?.length)throw new Error();
        const route=data.routes[0],c=route.geometry.coordinates.map(x=>[x[1],x[0]]);
        const dist=route.distance/1000,dur=route.duration;
        addStoreMarker(map);
        L.polyline(c,{color:'#FFB6C1',weight:isModal?14:10,opacity:0.2,lineJoin:'round',lineCap:'round'}).addTo(map);
        L.polyline(c,{color:'#FF9EAD',weight:isModal?6:4,opacity:0.95,lineJoin:'round',lineCap:'round'}).addTo(map);
        L.polyline(c,{color:'#fff',weight:isModal?2.5:1.5,opacity:0.7,dashArray:'8 18',lineJoin:'round',lineCap:'round'}).addTo(map);
        L.circle([dLat,dLng],{radius:35,color:'#f5576c',fillColor:'#f5576c',fillOpacity:0.15,weight:2}).addTo(map);
        map.fitBounds(L.polyline(c).getBounds(),{padding:isModal?[55,55]:[28,28]});
        if(isModal){
            sel('hist-modal-dist-'+id,fmtD(dist));
            sel('hist-modal-eta-'+id,fmtT(dur));
            buildTBT(route,id);
            buildProg(id,0);
            sel('hist-modal-maps-link-'+id,`<a href="https://www.google.com/maps/dir/${STORE_LAT},${STORE_LNG}/${dLat},${dLng}" target="_blank" style="color:#fff;font-size:0.75rem;background:rgba(255,255,255,0.25);padding:3px 10px;border-radius:20px;text-decoration:none;"><i class="fas fa-map-marked-alt me-1"></i>Open Maps</a>`,true);
        }else{sel('hist-mini-dist-'+id,fmtD(dist));sel('hist-mini-eta-'+id,fmtT(dur));}
        sel(sk,'Route loaded');
    }catch{
        const l=L.polyline([[STORE_LAT,STORE_LNG],[dLat,dLng]],{color:'#FFB6C1',weight:4,opacity:0.75,dashArray:'9,9'}).addTo(map);
        map.fitBounds(l.getBounds(),{padding:[40,40]});
        sel(sk,'Approx. route');
    }
}

function requestHistGPS(map,id,dLat,dLng,type){
    if(!navigator.geolocation)return;
    const sk=(type==='mini'?'hist-mini-status-':'hist-modal-map-status-')+id;
    navigator.geolocation.getCurrentPosition(
        pos=>{sel(sk,'On the way');placeRider(map,id,pos.coords.latitude,pos.coords.longitude,dLat,dLng,type);
            const k=type+id;if(liveTrk[k])clearInterval(liveTrk[k]);
            liveTrk[k]=setInterval(()=>navigator.geolocation.getCurrentPosition(p=>placeRider(map,id,p.coords.latitude,p.coords.longitude,dLat,dLng,type),()=>{},{enableHighAccuracy:true,timeout:10000,maximumAge:15000}),15000);},
        err=>sel(sk,err.code===1?'⚠ Enable location':'⚠ GPS error'),
        {enableHighAccuracy:true,timeout:15000,maximumAge:0}
    );
}

function placeRider(map,id,rLat,rLng,dLat,dLng,type){
    if(riderMkr[id])riderMkr[id].setLatLng([rLat,rLng]);
    else riderMkr[id]=L.marker([rLat,rLng],{icon:L.divIcon({className:'',
        html:'<div style="position:relative;display:inline-block;"><div style="width:46px;height:46px;border-radius:50%;background:linear-gradient(135deg,#43e97b,#38f9d7);border:3px solid #fff;box-shadow:0 0 0 4px rgba(67,233,123,0.35),0 4px 14px rgba(67,233,123,0.5);display:flex;align-items:center;justify-content:center;"><i class="fas fa-motorcycle" style="color:#fff;font-size:1.1rem;"></i></div><div style="position:absolute;top:-26px;left:50%;transform:translateX(-50%);background:linear-gradient(135deg,#43e97b,#38f9d7);color:#fff;font-size:9px;padding:2px 8px;border-radius:12px;white-space:nowrap;font-weight:800;">🛵 RIDER</div></div>',
        iconSize:[46,46],iconAnchor:[23,23]}),zIndexOffset:1000}).addTo(map).bindPopup('<b>🛵 Your Rider</b>');
    const rem=hvs(rLat,rLng,dLat,dLng);
    if(type==='modal'){
        sel('hist-modal-eta-'+id,fmtT(rem/30*3600));
        const pct=Math.max(0,Math.min(100,Math.round((1-rem/hvs(STORE_LAT,STORE_LNG,dLat,dLng))*100)));
        buildProg(id,pct);
        sel('hist-modal-map-status-'+id,pct>=95?'Arriving!':'On the way '+pct+'%');
    }else sel('hist-mini-eta-'+id,fmtT(rem/30*3600));
}

function buildProg(id,pct){
    const c=document.getElementById('hist-steps-list-'+id);if(!c)return;
    const ex=document.getElementById('hist-pbf-'+id);
    if(ex){ex.style.width=pct+'%';const l=document.getElementById('hist-pbl-'+id);if(l)l.textContent=pct+'% complete';return;}
    const b=document.createElement('div');b.style.marginBottom='12px';
    b.innerHTML='<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:5px;">'
        +'<span style="font-size:0.75rem;font-weight:700;color:#718096;text-transform:uppercase;letter-spacing:0.05em;"><i class="fas fa-route me-1"></i>Delivery Progress</span>'
        +'<span id="hist-pbl-'+id+'" style="font-size:0.75rem;font-weight:700;color:#FF9EAD;">'+pct+'% complete</span>'
        +'</div>'
        +'<div style="background:#e5e7eb;border-radius:99px;height:8px;overflow:hidden;">'
        +'<div id="hist-pbf-'+id+'" style="height:100%;width:'+pct+'%;background:linear-gradient(90deg,#FFB6C1,#FF9EAD);border-radius:99px;transition:width 0.6s ease;box-shadow:0 0 8px rgba(255,182,193,0.5);"></div>'
        +'</div>'
        +'<div style="display:flex;justify-content:space-between;margin-top:3px;font-size:0.68rem;color:#718096;"><span>🏪 Store</span><span>🏠 Your Door</span></div>';
    c.prepend(b);
}

function buildTBT(route,id){
    const c=document.getElementById('hist-steps-list-'+id);if(!c)return;
    const steps=route.legs?.[0]?.steps??[];
    if(!steps.length){c.innerHTML='<div class="step-item"><span style="color:#9ca3af;font-size:0.82rem;">No steps available.</span></div>';return;}
    c.innerHTML=steps.map((s,i)=>{
        const type=s.maneuver?.type??'',mod=s.maneuver?.modifier??'',name=s.name||'road';
        const dist=s.distance<1000?s.distance.toFixed(0)+' m':(s.distance/1000).toFixed(1)+' km';
        const icons={'arrive':'🏁','depart':'🚀','roundabout':'⭕'};
        let icon=icons[type]||'⬆';
        if(type==='turn'){if(mod.includes('left'))icon='←';else if(mod.includes('right'))icon='→';}
        const desc=type==='arrive'?'🏁 Arrive at your address':`${cap(type.replace('-',' '))}${mod?' '+mod.replace('-',' '):''}${name?' on <b>'+name+'</b>':''}`;
        return `<div class="step-item"><div class="step-num">${i+1}</div><div><div class="step-text">${icon} ${desc}</div><div class="step-dist">${dist}</div></div></div>`;
    }).join('');
}

document.addEventListener('DOMContentLoaded',()=>{
    histMapData.forEach((o,i)=>setTimeout(()=>initHistMap(o.id,'mini'),i*180));
    document.querySelectorAll('.toast-notification').forEach(t=>{
        setTimeout(()=>{t.style.opacity='0';t.style.transform='translateX(400px)';setTimeout(()=>t.remove(),300);},5000);
        t.addEventListener('click',()=>{t.style.opacity='0';t.style.transform='translateX(400px)';setTimeout(()=>t.remove(),300);});
    });
});
window.addEventListener('beforeunload',()=>Object.values(liveTrk).forEach(clearInterval));
</script>
</body>
</html>