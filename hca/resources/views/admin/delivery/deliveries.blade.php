<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>My Deliveries</title>

    <script>
        (function() {
            const savedTheme = localStorage.getItem('delivery-theme') || 'light';
            document.documentElement.setAttribute('data-theme', savedTheme);
        })();
    </script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <style>
        :root {
            --primary-blue: #667eea;
            --primary-purple: #764ba2;
            --success: #48BB78;
            --warning: #F6AD55;
            --danger: #FC8181;
            --info: #63B3ED;
            --light-bg: #F7FAFC;
            --card-bg: #FFFFFF;
            --text-primary: #1A202C;
            --text-secondary: #718096;
            --border-color: #E2E8F0;
            --sidebar-width: 280px;
            --hover-bg: rgba(0,0,0,0.05);
        }
        [data-theme="dark"] {
            --light-bg: #1A202C;
            --card-bg: #2D3748;
            --text-primary: #F7FAFC;
            --text-secondary: #A0AEC0;
            --border-color: #4A5568;
            --hover-bg: rgba(255,255,255,0.05);
        }

        * { margin:0; padding:0; box-sizing:border-box; }
        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--light-bg);
            color: var(--text-primary);
            margin-top: 20px;
            line-height: 1.6;
            transition: background-color 0.3s, color 0.3s;
        }
        .main-content { margin-left: var(--sidebar-width); padding: 6rem 2rem 2rem; min-height:100vh; }
        .page-title { font-size:2rem; font-weight:700; color:var(--text-primary); display:flex; align-items:center; gap:1rem; margin-bottom:0.5rem; }
        .page-subtitle { color:var(--text-secondary); font-size:1rem; }
        .date-badge { background:linear-gradient(135deg,var(--primary-blue),var(--primary-purple)); color:#fff; padding:0.5rem 1rem; border-radius:12px; font-weight:600; display:inline-flex; align-items:center; gap:0.5rem; }

        /* Order Card */
        .order-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 14px;
            padding: 1.4rem;
            border-left: 4px solid var(--primary-blue);
            cursor: pointer;
            transition: all 0.22s ease;
            position: relative;
        }
        .order-card:hover { transform:translateY(-5px); box-shadow:0 12px 28px rgba(102,126,234,0.18); border-left-color:var(--primary-purple); }
        .order-card[data-status="Failed"] { border-left-color: var(--danger); background: rgba(252,129,129,0.03); }
        .order-card[data-status="Failed"]:hover { box-shadow:0 12px 28px rgba(252,129,129,0.18); border-left-color:#f56565; }
        .expand-hint { position:absolute; bottom:10px; right:14px; font-size:0.72rem; color:var(--text-secondary); display:flex; align-items:center; gap:4px; opacity:0.65; transition:all 0.2s; }
        .order-card:hover .expand-hint { opacity:1; color:var(--primary-blue); }

        .order-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem; }
        .order-id { background:var(--text-secondary); color:#fff; padding:0.25rem 0.75rem; border-radius:8px; font-weight:600; font-size:0.875rem; }
        .customer-name { font-size:1.1rem; font-weight:700; color:var(--text-primary); margin-bottom:0.3rem; }
        .order-address { display:flex; align-items:flex-start; gap:0.5rem; color:var(--text-secondary); font-size:0.84rem; margin-bottom:0.55rem; }

        /* Badges */
        .badge-modern { padding:0.32rem 0.8rem; border-radius:8px; font-size:0.78rem; font-weight:600; display:inline-flex; align-items:center; gap:0.3rem; }
        .badge-success  { background:rgba(72,187,120,0.15);  color:var(--success); }
        .badge-warning  { background:rgba(246,173,85,0.15);  color:var(--warning); }
        .badge-danger   { background:rgba(252,129,129,0.15); color:var(--danger); }
        .badge-info     { background:rgba(99,179,237,0.15);  color:var(--info); }
        .badge-failed   { background:rgba(252,129,129,0.18); color:#E53E3E; border:1px solid rgba(229,62,62,0.25); }

        /* Buttons */
        .btn-modern { padding:0.6rem 1.2rem; border-radius:10px; font-weight:600; border:none; cursor:pointer; transition:all 0.2s; display:inline-flex; align-items:center; gap:0.5rem; justify-content:center; }
        .btn-modern-primary   { background:linear-gradient(135deg,var(--primary-blue),var(--primary-purple)); color:#fff; }
        .btn-modern-primary:hover   { transform:translateY(-2px); box-shadow:0 8px 16px rgba(102,126,234,0.3); color:#fff; }
        .btn-modern-success   { background:var(--success); color:#fff; }
        .btn-modern-success:hover   { background:#38a169; transform:translateY(-2px); color:#fff; }
        .btn-modern-info      { background:var(--info); color:#fff; }
        .btn-modern-info:hover      { background:#4299e1; transform:translateY(-2px); color:#fff; }
        .btn-modern-danger    { background:var(--danger); color:#fff; }
        .btn-modern-danger:hover    { background:#f56565; transform:translateY(-2px); color:#fff; }
        .btn-modern-secondary { background:var(--light-bg); color:var(--text-primary); }
        .btn-modern-warning   { background:var(--warning); color:#fff; }
        .btn-modern-warning:hover   { background:#ed8936; transform:translateY(-2px); color:#fff; }

        /* Alerts */
        .alert-modern { padding:1rem 1.5rem; border-radius:12px; border:none; margin-bottom:1.5rem; display:flex; align-items:center; gap:1rem; }
        .alert-success { background:rgba(72,187,120,0.15);  color:var(--success); }
        .alert-danger  { background:rgba(252,129,129,0.15); color:var(--danger); }
        .alert-warning { background:rgba(246,173,85,0.15);  color:var(--warning); }

        /* Failed alert strip on card */
        .card-failed-strip {
            display: flex;
            align-items: center;
            gap: 8px;
            background: rgba(252,129,129,0.1);
            border: 1px solid rgba(229,62,62,0.3);
            border-radius: 10px;
            padding: 8px 12px;
            margin-top: 10px;
            font-size: 0.81rem;
            color: #E53E3E;
            font-weight: 600;
        }
        .card-failed-strip i { flex-shrink: 0; }

        /* Misc */
        .info-label { font-weight:600; color:var(--text-secondary); margin-bottom:5px; font-size:0.84rem; }
        .section-divider { border-top:1.5px solid var(--border-color); margin:14px 0; }
        .product-image { width:54px; height:54px; object-fit:cover; border-radius:8px; border:2px solid var(--border-color); cursor:pointer; transition:all 0.25s; }
        .product-image:hover { transform:scale(1.06); box-shadow:0 4px 12px rgba(0,0,0,0.15); }
        .product-item { background:var(--light-bg); padding:10px; border-radius:8px; margin-bottom:8px; border:1px solid var(--border-color); }
        .total-section { background:linear-gradient(135deg,var(--primary-blue),var(--primary-purple)); color:#fff; padding:13px; border-radius:8px; margin-top:12px; }
        .payment-method-badge { display:inline-block; padding:6px 12px; border-radius:6px; font-weight:600; margin-bottom:8px; }
        .payment-method-cod   { background:var(--warning); color:#000; }
        .payment-method-gcash { background:var(--info);    color:#fff; }

        /* Payment proof on card */
        .card-payment-proof-section { display:flex; align-items:center; gap:10px; background:var(--light-bg); border:1px solid var(--border-color); border-radius:10px; padding:9px 12px; margin-top:10px; }
        .card-proof-thumb { width:46px; height:46px; object-fit:cover; border-radius:7px; border:2px solid var(--info); cursor:pointer; flex-shrink:0; transition:transform 0.2s,box-shadow 0.2s; }
        .card-proof-thumb:hover { transform:scale(1.08); box-shadow:0 4px 14px rgba(99,179,237,0.4); }
        .card-proof-info { flex:1; min-width:0; }
        .card-proof-title { font-size:0.76rem; font-weight:700; color:var(--text-secondary); text-transform:uppercase; letter-spacing:0.04em; }
        .card-proof-status { font-size:0.83rem; font-weight:600; }
        .card-proof-status.verified { color:var(--success); }
        .card-proof-status.pending  { color:var(--warning); }
        .card-proof-no-proof { display:flex; align-items:center; gap:10px; background:rgba(252,129,129,0.08); border:1px dashed var(--danger); border-radius:10px; padding:9px 12px; margin-top:10px; }
        .card-proof-no-proof i { color:var(--danger); font-size:1.2rem; flex-shrink:0; }
        .card-proof-no-proof-text { font-size:0.82rem; }
        .card-proof-no-proof-text strong { display:block; color:var(--danger); font-size:0.8rem; }
        .card-proof-no-proof-text span   { color:var(--text-secondary); font-size:0.75rem; }

        /* Mini map */
        .mini-map-wrapper { border-radius:10px; overflow:hidden; border:1px solid var(--border-color); }
        .mini-map-header { display:flex; align-items:center; justify-content:space-between; padding:7px 12px; background:linear-gradient(135deg,var(--primary-blue),var(--primary-purple)); color:#fff; }
        .mini-map-title { display:flex; align-items:center; gap:7px; font-weight:700; font-size:0.82rem; }
        .live-dot { width:8px; height:8px; background:#fff; border-radius:50%; animation:pulse-live 1.4s ease-in-out infinite; flex-shrink:0; }
        @keyframes pulse-live { 0%{box-shadow:0 0 0 0 rgba(255,255,255,0.7)} 70%{box-shadow:0 0 0 7px rgba(255,255,255,0)} 100%{box-shadow:0 0 0 0 rgba(255,255,255,0)} }
        .map-status-pill { font-size:0.7rem; background:rgba(255,255,255,0.2); padding:2px 8px; border-radius:20px; font-weight:500; white-space:nowrap; }
        .mini-map-canvas { height:175px; width:100%; }
        .mini-map-footer { display:flex; align-items:center; justify-content:space-between; padding:6px 12px; background:var(--light-bg); border-top:1px solid var(--border-color); font-size:0.76rem; color:var(--text-secondary); font-weight:500; flex-wrap:wrap; gap:4px; }

        /* Delivery detail modal */
        .delivery-modal-overlay { display:none; position:fixed; inset:0; background:rgba(0,0,0,0.55); backdrop-filter:blur(4px); z-index:9000; align-items:center; justify-content:center; padding:16px; }
        .delivery-modal-overlay.active { display:flex; animation:fadeOverlay 0.2s ease; }
        @keyframes fadeOverlay { from{opacity:0} to{opacity:1} }
        .delivery-modal { background:var(--card-bg); border-radius:20px; width:100%; max-width:880px; max-height:92vh; overflow-y:auto; box-shadow:0 24px 60px rgba(0,0,0,0.3); animation:slideModal 0.28s cubic-bezier(0.34,1.3,0.64,1); position:relative; }
        @keyframes slideModal { from{opacity:0;transform:translateY(40px) scale(0.97)} to{opacity:1;transform:translateY(0) scale(1)} }
        .dmodal-header { display:flex; align-items:center; justify-content:space-between; padding:1.2rem 1.6rem; background:linear-gradient(135deg,var(--primary-blue),var(--primary-purple)); border-radius:20px 20px 0 0; color:#fff; position:sticky; top:0; z-index:10; flex-wrap:wrap; gap:8px; }
        .dmodal-header-left { display:flex; align-items:center; gap:10px; flex-wrap:wrap; }
        .dmodal-order-id { background:rgba(255,255,255,0.25); padding:4px 12px; border-radius:8px; font-weight:700; font-size:0.9rem; }
        .dmodal-title { font-size:1.1rem; font-weight:700; }

        /* Failed header variant */
        .dmodal-header.failed-header { background:linear-gradient(135deg,#FC8181,#F56565); }

        .dmodal-close { width:34px; height:34px; border-radius:50%; background:rgba(255,255,255,0.2); border:none; color:#fff; cursor:pointer; display:flex; align-items:center; justify-content:center; font-size:1rem; transition:all 0.2s; flex-shrink:0; }
        .dmodal-close:hover { background:rgba(255,255,255,0.35); transform:rotate(90deg); }
        .dmodal-body { padding:1.5rem; }

        /* Failed banner inside modal */
        .failed-delivery-banner {
            background: rgba(252,129,129,0.1);
            border: 1.5px solid rgba(229,62,62,0.4);
            border-left: 4px solid #E53E3E;
            border-radius: 12px;
            padding: 14px 16px;
            margin-bottom: 1.2rem;
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }
        .failed-delivery-banner i { color: #E53E3E; font-size: 1.4rem; flex-shrink: 0; margin-top: 1px; }
        .failed-delivery-banner-content .title { font-weight: 700; color: #E53E3E; font-size: 0.95rem; margin-bottom: 4px; }
        .failed-delivery-banner-content .desc  { font-size: 0.84rem; color: var(--text-secondary); line-height: 1.5; }

        /* Modal map */
        .modal-map-wrapper { border-radius:14px; overflow:hidden; border:1px solid var(--border-color); margin-bottom:1.3rem; }
        .modal-map-header { display:flex; align-items:center; justify-content:space-between; padding:10px 15px; background:linear-gradient(135deg,var(--primary-blue),var(--primary-purple)); color:#fff; gap:8px; flex-wrap:wrap; }
        .modal-map-title { display:flex; align-items:center; gap:8px; font-weight:700; font-size:0.92rem; }
        .modal-map-status { font-weight:400; font-size:0.76rem; opacity:0.85; }
        .modal-map-canvas { height:360px; width:100%; }
        .modal-map-footer { display:flex; align-items:center; justify-content:space-between; padding:9px 15px; background:var(--light-bg); border-top:1px solid var(--border-color); flex-wrap:wrap; gap:8px; }
        .modal-map-stat { display:flex; align-items:center; gap:6px; font-size:0.83rem; font-weight:600; color:var(--text-primary); }
        .modal-map-stat span { color:var(--text-secondary); font-weight:400; }

        /* Info grid */
        .dmodal-info-grid { display:grid; grid-template-columns:1fr 1fr; gap:10px; margin-bottom:1.1rem; }
        @media(max-width:580px){ .dmodal-info-grid{ grid-template-columns:1fr; } }
        .dmodal-info-box { background:var(--light-bg); border:1px solid var(--border-color); border-radius:11px; padding:11px 13px; }
        .dmodal-info-box .label { font-size:0.7rem; text-transform:uppercase; letter-spacing:0.05em; color:var(--text-secondary); font-weight:700; margin-bottom:3px; }
        .dmodal-info-box .value { font-size:0.92rem; font-weight:600; color:var(--text-primary); }

        /* Route steps */
        .route-steps { background:var(--light-bg); border:1px solid var(--border-color); border-radius:12px; padding:13px; margin-bottom:1.2rem; max-height:210px; overflow-y:auto; }
        .route-steps-title { font-size:0.78rem; font-weight:700; color:var(--text-secondary); text-transform:uppercase; letter-spacing:0.05em; margin-bottom:9px; }
        .step-item { display:flex; align-items:flex-start; gap:9px; padding:6px 0; border-bottom:1px solid var(--border-color); }
        .step-item:last-child { border-bottom:none; }
        .step-num { min-width:22px; height:22px; border-radius:50%; background:linear-gradient(135deg,var(--primary-blue),var(--primary-purple)); color:#fff; font-size:0.65rem; font-weight:700; display:flex; align-items:center; justify-content:center; margin-top:1px; flex-shrink:0; }
        .step-text { font-size:0.81rem; color:var(--text-primary); line-height:1.4; }
        .step-dist { font-size:0.72rem; color:var(--text-secondary); margin-top:1px; }

        /* Modal proof */
        .modal-proof-box { background:rgba(99,179,237,0.08); border:1.5px solid var(--info); border-radius:12px; padding:14px; margin-bottom:1.1rem; }
        .modal-proof-box-header { display:flex; align-items:center; gap:8px; margin-bottom:12px; }
        .modal-proof-box-header i { color:var(--info); font-size:1rem; }
        .modal-proof-box-title { font-weight:700; font-size:0.88rem; color:var(--text-primary); }
        .modal-proof-status-pill { margin-left:auto; padding:3px 10px; border-radius:20px; font-size:0.72rem; font-weight:700; }
        .pill-paid    { background:rgba(72,187,120,0.15);  color:var(--success); }
        .pill-pending { background:rgba(246,173,85,0.15);  color:var(--warning); }
        .modal-proof-img-wrap { text-align:center; }
        .modal-proof-img-wrap img { max-width:100%; max-height:260px; border-radius:10px; border:2px solid var(--info); cursor:pointer; transition:transform 0.2s,box-shadow 0.2s; }
        .modal-proof-img-wrap img:hover { transform:scale(1.02); box-shadow:0 8px 24px rgba(99,179,237,0.3); }

        /* Map pins */
        .map-pin { width:32px; height:32px; border-radius:50% 50% 50% 0; display:flex; align-items:center; justify-content:center; color:#fff; font-size:0.75rem; transform:rotate(-45deg); box-shadow:0 3px 10px rgba(0,0,0,0.25); }
        .map-pin i { transform:rotate(45deg); }
        .map-pin-dest  { background:linear-gradient(135deg,#f093fb,#f5576c); }
        .map-pin-rider { background:linear-gradient(135deg,#43e97b,#38f9d7); border-radius:50%; transform:none; width:36px; height:36px; border:3px solid #fff; animation:rider-pulse 2s ease-in-out infinite; }
        .map-pin-rider i { transform:none; font-size:0.95rem; }
        @keyframes rider-pulse { 0%,100%{box-shadow:0 0 0 0 rgba(67,233,123,0.5)} 50%{box-shadow:0 0 0 10px rgba(67,233,123,0)} }

        /* Image modal */
        .image-modal { display:none; position:fixed; z-index:99999; inset:0; background:rgba(0,0,0,0.92); }
        .image-modal.show { display:flex; align-items:center; justify-content:center; }
        .image-modal-content { max-width:90%; max-height:90%; object-fit:contain; border-radius:8px; animation:zoomIn 0.25s ease; }
        .image-modal-close { position:absolute; top:18px; right:30px; color:#fff; font-size:36px; font-weight:bold; cursor:pointer; transition:0.3s; z-index:100000; }
        .image-modal-close:hover { color:#bbb; }
        @keyframes zoomIn { from{transform:scale(0.5)} to{transform:scale(1)} }

        .modal-content { background:var(--card-bg); color:var(--text-primary); border:1px solid var(--border-color); border-radius:16px; }
        .modal-header { border-bottom:1px solid var(--border-color); }
        .modal-footer { border-top:1px solid var(--border-color); }

        ::-webkit-scrollbar { width:6px; height:6px; }
        ::-webkit-scrollbar-track { background:var(--light-bg); }
        ::-webkit-scrollbar-thumb { background:var(--border-color); border-radius:4px; }

        @keyframes fadeIn { from{opacity:0;transform:translateY(10px)} to{opacity:1;transform:translateY(0)} }
        .animate-fade-in { animation:fadeIn 0.5s ease; }

        /* GPS banner */
        .gps-permission-banner { background:rgba(246,173,85,0.15); border:1px solid var(--warning); border-radius:12px; padding:16px; margin-bottom:16px; display:flex; align-items:flex-start; gap:12px; }
        .gps-permission-banner i { color:var(--warning); font-size:1.5rem; }
        .gps-permission-banner-content { flex:1; }
        .gps-permission-banner-title { font-weight:700; color:var(--text-primary); margin-bottom:4px; }
        .gps-permission-banner-text { font-size:0.85rem; color:var(--text-secondary); margin-bottom:12px; }
        .gps-permission-banner-buttons { display:flex; gap:10px; flex-wrap:wrap; }
        .gps-retry-btn { background:linear-gradient(135deg,var(--primary-blue),var(--primary-purple)); color:white; border:none; padding:8px 16px; border-radius:8px; font-size:0.85rem; font-weight:600; cursor:pointer; display:inline-flex; align-items:center; gap:6px; }
        .gps-manual-btn { background:var(--light-bg); color:var(--text-primary); border:1px solid var(--border-color); padding:8px 16px; border-radius:8px; font-size:0.85rem; font-weight:600; cursor:pointer; display:inline-flex; align-items:center; gap:6px; text-decoration:none; }
        .gps-manual-btn:hover { background:var(--border-color); color:var(--text-primary); }

        @media(max-width:768px){
            .main-content { margin-left:0; }
            .page-title { font-size:1.5rem; }
            .modal-map-canvas { height:240px; }
            .delivery-modal { max-height:96vh; }
        }
    </style>
</head>
<body>

@include('admin.delivery.layouts.navbar')
@include('admin.delivery.layouts.sidebar')

<main class="main-content">

    <div class="page-header d-flex justify-content-between align-items-start mb-4">
        <div>
            <h1 class="page-title">
                <i class="fas fa-box me-2" style="color:var(--primary-blue);"></i>My Deliveries
            </h1>
            <p class="page-subtitle">Manage and update your delivery assignments</p>
        </div>
        <div class="date-badge">
            <i class="fas fa-calendar"></i>
            <span id="currentDate"></span>
        </div>
    </div>

    @if(session('success'))
        <div class="alert-modern alert-success animate-fade-in">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        @forelse ($deliveries as $delivery)
        @php
            $status     = $delivery->delivery_status;
            $orderItems = DB::table('order_item')
                ->join('products','order_item.product_id','=','products.id')
                ->where('order_item.order_id', $delivery->id)
                ->select('products.name as product_name','products.image as product_image','order_item.quantity','order_item.price')
                ->get();
        @endphp

        <div class="col-lg-6 mb-4">

            {{-- ── CLICKABLE CARD ── --}}
            <div class="order-card" data-status="{{ $status }}" onclick="openDeliveryModal({{ $delivery->id }})">

                <div class="order-header">
                    <span class="order-id">#{{ $delivery->id }}</span>
                    @if($status === 'Delivered')
                        <span class="badge-modern badge-success"><i class="fas fa-check-circle"></i> Delivered</span>
                    @elseif($status === 'Out for Delivery')
                        <span class="badge-modern badge-info"><i class="fas fa-shipping-fast"></i> Out for Delivery</span>
                    @elseif($status === 'Failed')
                        <span class="badge-modern badge-failed"><i class="fas fa-exclamation-triangle"></i> Failed</span>
                    @elseif($status === 'Cancelled')
                        <span class="badge-modern badge-danger"><i class="fas fa-times-circle"></i> Cancelled</span>
                    @else
                        <span class="badge-modern badge-warning"><i class="fas fa-clock"></i> Pending</span>
                    @endif
                </div>

                <h6 class="customer-name">{{ $delivery->customer_name }}</h6>
                <div class="order-address">
                    <i class="fas fa-phone text-success mt-1"></i>
                    <span>{{ $delivery->phone }}</span>
                </div>
                <div class="order-address">
                    <i class="fas fa-map-marker-alt text-danger mt-1"></i>
                    <span>{{ $delivery->address }}</span>
                </div>
                <div class="mb-2">
                    @if($delivery->payment_method === 'COD')
                        <span class="payment-method-badge payment-method-cod" style="font-size:0.76rem;padding:4px 10px;">
                            <i class="fas fa-money-bill-wave me-1"></i>COD
                        </span>
                    @elseif($delivery->payment_method === 'GCash')
                        <span class="payment-method-badge payment-method-gcash" style="font-size:0.76rem;padding:4px 10px;">
                            <i class="fas fa-mobile-alt me-1"></i>GCash
                        </span>
                    @endif
                    <span class="ms-2 fw-bold" style="color:var(--primary-blue);">
                        ₱{{ number_format($delivery->grand_total,2) }}
                    </span>
                </div>

                {{-- Payment proof on card --}}
                @if($delivery->payment_proof)
                    <div class="card-payment-proof-section" onclick="event.stopPropagation();">
                        <img src="{{ asset('uploads/payments/'.$delivery->payment_proof) }}"
                             alt="Payment Proof"
                             class="card-proof-thumb"
                             onclick="openImageModal(this)"
                             onerror="this.style.display='none'">
                        <div class="card-proof-info">
                            <div class="card-proof-title"><i class="fas fa-file-image me-1"></i>Payment Proof</div>
                            @if($delivery->payment_status === 'Paid')
                                <div class="card-proof-status verified"><i class="fas fa-check-circle me-1"></i>Verified — Paid</div>
                            @else
                                <div class="card-proof-status pending"><i class="fas fa-hourglass-half me-1"></i>Uploaded — Awaiting Verification</div>
                            @endif
                        </div>
                        <i class="fas fa-search-plus" style="color:var(--info);font-size:1rem;flex-shrink:0;"></i>
                    </div>
                @elseif($delivery->payment_status !== 'Paid')
                    <div class="card-proof-no-proof" onclick="event.stopPropagation();">
                        <i class="fas fa-exclamation-circle"></i>
                        <div class="card-proof-no-proof-text">
                            <strong><i class="fas fa-upload me-1"></i>Payment Proof Required</strong>
                            <span>No proof uploaded yet — tap card to upload</span>
                        </div>
                    </div>
                @endif

                {{-- Failed strip on card --}}
                @if($status === 'Failed')
                    <div class="card-failed-strip" onclick="event.stopPropagation();">
                        <i class="fas fa-exclamation-triangle"></i>
                        Delivery attempt failed — tap to retry or review
                    </div>
                @endif

                {{-- Mini map — hide for Cancelled and Failed --}}
                @if(!in_array($status, ['Cancelled', 'Failed']))
                <div class="section-divider"></div>
                <div class="mini-map-wrapper" onclick="event.stopPropagation()">
                    <div class="mini-map-header">
                        <div class="mini-map-title">
                            @if($status === 'Out for Delivery')
                                <span class="live-dot"></span> Live Navigation
                            @elseif($status === 'Delivered')
                                <i class="fas fa-check-circle me-1" style="color:#48BB78;"></i> Delivered Location
                            @else
                                <i class="fas fa-map-marker-alt me-1"></i> Delivery Location
                            @endif
                        </div>
                        <span class="map-status-pill" id="mini-status-{{ $delivery->id }}">Loading…</span>
                    </div>
                    <div id="mini-map-{{ $delivery->id }}" class="mini-map-canvas"></div>
                    <div class="mini-map-footer">
                        <span>
                            <i class="fas fa-road me-1" style="color:var(--primary-blue);"></i>
                            <span id="mini-dist-{{ $delivery->id }}">
                                @if($status === 'Out for Delivery')Calculating…@else—@endif
                            </span>
                        </span>
                        <span>
                            <i class="fas fa-clock me-1" style="color:var(--warning);"></i>
                            <span id="mini-eta-{{ $delivery->id }}">—</span>
                        </span>
                    </div>
                </div>
                @endif

                <div class="expand-hint">
                    <i class="fas fa-expand-alt"></i> Click to expand
                </div>
            </div>{{-- /order-card --}}
        </div>

        {{-- ═══════════════════════════════════════
             DELIVERY DETAIL MODAL
        ════════════════════════════════════════ --}}
        <div class="delivery-modal-overlay" id="modal-overlay-{{ $delivery->id }}"
             onclick="closeDeliveryModal({{ $delivery->id }})">
            <div class="delivery-modal" onclick="event.stopPropagation()">

                {{-- Modal header --}}
                <div class="dmodal-header {{ $status === 'Failed' ? 'failed-header' : '' }}">
                    <div class="dmodal-header-left">
                        <span class="dmodal-order-id">#{{ $delivery->id }}</span>
                        <span class="dmodal-title">{{ $delivery->customer_name }}</span>
                        @if($status === 'Delivered')
                            <span class="badge-modern badge-success"><i class="fas fa-check-circle"></i> Delivered</span>
                        @elseif($status === 'Out for Delivery')
                            <span class="badge-modern badge-info"><i class="fas fa-shipping-fast"></i> Out for Delivery</span>
                        @elseif($status === 'Failed')
                            <span class="badge-modern badge-failed"><i class="fas fa-exclamation-triangle"></i> Failed</span>
                        @elseif($status === 'Cancelled')
                            <span class="badge-modern badge-danger"><i class="fas fa-times-circle"></i> Cancelled</span>
                        @else
                            <span class="badge-modern badge-warning"><i class="fas fa-clock"></i> Pending</span>
                        @endif
                    </div>
                    <button class="dmodal-close" onclick="closeDeliveryModal({{ $delivery->id }})">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <div class="dmodal-body">

                    {{-- Failed delivery banner --}}
                    @if($status === 'Failed')
                    <div class="failed-delivery-banner">
                        <i class="fas fa-exclamation-triangle"></i>
                        <div class="failed-delivery-banner-content">
                            <div class="title">Delivery Attempt Failed</div>
                            <div class="desc">
                                This delivery could not be completed. The customer and admin have been notified.
                                You can retry the delivery to reset the order back to Pending and schedule a new attempt.
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- Map — hide for Cancelled and Failed --}}
                    @if(!in_array($status, ['Cancelled', 'Failed']))
                    <div class="modal-map-wrapper">
                        <div class="modal-map-header">
                            <div class="modal-map-title">
                                @if($status === 'Out for Delivery')
                                    <span class="live-dot"></span> Route to Customer
                                @elseif($status === 'Delivered')
                                    <i class="fas fa-check-circle me-1" style="color:#48BB78;"></i> Delivered Location
                                @else
                                    <i class="fas fa-map-marker-alt me-1"></i> Delivery Location
                                @endif
                                <span class="modal-map-status" id="modal-map-status-{{ $delivery->id }}">Loading map…</span>
                            </div>
                            <span id="modal-maps-link-{{ $delivery->id }}"></span>
                        </div>
                        <div id="modal-map-{{ $delivery->id }}" class="modal-map-canvas"></div>
                        <div class="modal-map-footer">
                            <div class="modal-map-stat">
                                <i class="fas fa-road" style="color:var(--primary-blue);"></i>
                                Distance: <span id="modal-dist-{{ $delivery->id }}">—</span>
                            </div>
                            @if($status === 'Out for Delivery')
                            <div class="modal-map-stat">
                                <i class="fas fa-clock" style="color:var(--warning);"></i>
                                ETA: <span id="modal-eta-{{ $delivery->id }}">—</span>
                            </div>
                            @else
                            <div class="modal-map-stat">
                                <i class="fas fa-check-circle" style="color:var(--success);"></i>
                                <span style="color:var(--success);font-weight:600;">
                                    @if($status === 'Delivered') Order Delivered @else Pending @endif
                                </span>
                            </div>
                            @endif
                            <div class="modal-map-stat">
                                <i class="fas fa-map-marker-alt" style="color:var(--danger);"></i>
                                <span style="font-size:0.75rem;color:var(--text-secondary);max-width:260px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $delivery->address }}</span>
                            </div>
                        </div>
                    </div>

                    @if($status === 'Out for Delivery')
                    <div class="route-steps" id="route-steps-{{ $delivery->id }}">
                        <div class="route-steps-title"><i class="fas fa-route me-2"></i>Turn-by-Turn Directions</div>
                        <div id="steps-list-{{ $delivery->id }}">
                            <div class="step-item">
                                <span style="color:var(--text-secondary);font-size:0.82rem;">Getting directions…</span>
                            </div>
                        </div>
                    </div>
                    @endif
                    @endif

                    {{-- Payment proof --}}
                    <div class="modal-proof-box">
                        <div class="modal-proof-box-header">
                            <i class="fas fa-receipt"></i>
                            <span class="modal-proof-box-title">Payment Proof</span>
                            @if($delivery->payment_status === 'Paid')
                                <span class="modal-proof-status-pill pill-paid">
                                    <i class="fas fa-check-circle me-1"></i>Paid & Verified
                                </span>
                            @elseif($delivery->payment_proof)
                                <span class="modal-proof-status-pill pill-pending">
                                    <i class="fas fa-hourglass-half me-1"></i>Awaiting Verification
                                </span>
                            @else
                                <span class="modal-proof-status-pill" style="background:rgba(252,129,129,0.15);color:var(--danger);">
                                    <i class="fas fa-times-circle me-1"></i>Not Uploaded
                                </span>
                            @endif
                        </div>

                        @if($delivery->payment_proof)
                            <div class="modal-proof-img-wrap">
                                <img src="{{ asset('uploads/payments/'.$delivery->payment_proof) }}"
                                     alt="Payment Proof"
                                     onclick="openImageModal(this); event.stopPropagation();"
                                     onerror="this.closest('.modal-proof-img-wrap').innerHTML='<p style=\'color:var(--danger);font-size:0.82rem;\'><i class=\'fas fa-exclamation-triangle me-1\'></i>Image could not be loaded.</p>'">
                                <div style="margin-top:8px;font-size:0.76rem;color:var(--text-secondary);">
                                    <i class="fas fa-search-plus me-1"></i>Tap image to view full size
                                </div>
                            </div>
                        @else
                            <div style="background:rgba(252,129,129,0.08);border:2px dashed var(--danger);border-radius:10px;padding:14px;text-align:center;color:var(--danger);font-size:0.85rem;font-weight:600;margin-bottom:12px;">
                                <i class="fas fa-image" style="display:block;font-size:1.6rem;margin-bottom:6px;opacity:0.7;"></i>
                                No payment proof uploaded yet
                            </div>
                            <div style="margin-bottom:12px;">
                                <label style="font-size:0.82rem;font-weight:600;color:var(--text-secondary);margin-bottom:6px;display:block;">
                                    <i class="fas fa-image me-1"></i>Select payment screenshot
                                </label>
                                <input type="file"
                                       id="proofFile{{ $delivery->id }}"
                                       class="form-control"
                                       accept="image/*"
                                       onclick="event.stopPropagation()"
                                       onchange="previewProofFile(this, {{ $delivery->id }})">
                            </div>
                            <div id="imagePreview{{ $delivery->id }}" style="display:none;margin-bottom:12px;text-align:center;">
                                <img id="preview{{ $delivery->id }}" style="max-height:160px;border-radius:8px;border:2px solid var(--info);">
                            </div>
                            <button type="button"
                                    id="proofSubmitBtn{{ $delivery->id }}"
                                    onclick="event.stopPropagation(); submitProof({{ $delivery->id }}, '{{ route('delivery.upload-payment-proof', $delivery->id) }}')"
                                    style="width:100%;padding:0.75rem 1.2rem;border-radius:10px;font-weight:700;border:none;cursor:pointer;font-size:0.95rem;background:linear-gradient(135deg,var(--primary-blue),var(--primary-purple));color:#fff;display:flex;align-items:center;justify-content:center;gap:0.5rem;">
                                <i class="fas fa-cloud-upload-alt"></i> Submit Payment Proof
                            </button>
                            <div id="proofMsg{{ $delivery->id }}" style="margin-top:8px;font-size:0.82rem;text-align:center;"></div>
                        @endif
                    </div>

                    {{-- Info grid --}}
                    <div class="dmodal-info-grid">
                        <div class="dmodal-info-box">
                            <div class="label"><i class="fas fa-phone me-1"></i> Phone</div>
                            <div class="value">
                                <a href="tel:{{ $delivery->phone }}" class="text-decoration-none" style="color:var(--text-primary);">{{ $delivery->phone }}</a>
                            </div>
                        </div>
                        <div class="dmodal-info-box">
                            <div class="label"><i class="fas fa-credit-card me-1"></i> Payment Method</div>
                            <div class="value">{{ $delivery->payment_method ?? 'N/A' }}</div>
                        </div>
                        <div class="dmodal-info-box">
                            <div class="label"><i class="fas fa-calendar me-1"></i> Order Date</div>
                            <div class="value">{{ \Carbon\Carbon::parse($delivery->created_at)->format('M d, Y h:i A') }}</div>
                        </div>
                        <div class="dmodal-info-box">
                            <div class="label"><i class="fas fa-tag me-1"></i> Payment Status</div>
                            <div class="value">{{ $delivery->payment_status ?? 'Pending' }}</div>
                        </div>
                        <div class="dmodal-info-box" style="grid-column:1/-1;">
                            <div class="label"><i class="fas fa-map-marker-alt me-1"></i> Delivery Address</div>
                            <div class="value">{{ $delivery->address }}</div>
                        </div>
                    </div>

                    {{-- Order items --}}
                    <div class="info-label mb-2">
                        <i class="fas fa-shopping-bag text-info me-2"></i>Order Items
                    </div>
                    @forelse($orderItems as $item)
                        <div class="product-item">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    @if($item->product_image)
                                        <img src="{{ asset('asset/images/'.$item->product_image) }}"
                                             alt="{{ $item->product_name }}"
                                             class="product-image"
                                             onclick="openImageModal(this); event.stopPropagation();"
                                             onerror="this.src='{{ asset('asset/images/default-product.png') }}';">
                                    @else
                                        <div class="product-image bg-secondary d-flex align-items-center justify-content:center">
                                            <i class="fas fa-image text-white"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="col">
                                    <h6 class="mb-1">{{ $item->product_name }}</h6>
                                    <small class="text-muted">Qty: {{ $item->quantity }} × ₱{{ number_format($item->price,2) }}</small>
                                    <div class="fw-bold" style="color:var(--primary-blue);">₱{{ number_format($item->quantity * $item->price,2) }}</div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="product-item text-center text-muted">
                            <i class="fas fa-box-open me-2"></i>No items found
                        </div>
                    @endforelse

                    <div class="total-section mt-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0"><i class="fas fa-receipt me-2"></i>Total Amount</h5>
                            <h4 class="mb-0">₱{{ number_format($delivery->grand_total,2) }}</h4>
                        </div>
                    </div>

                    {{-- Action buttons --}}
                    <div class="section-divider"></div>
                    <div class="d-grid gap-2">
                        @if($status === 'Pending')
                            <button class="btn-modern btn-modern-info"
                                onclick="confirmStatus('{{ $delivery->id }}','{{ addslashes($delivery->customer_name) }}','Out for Delivery'); closeDeliveryModal({{ $delivery->id }});">
                                <i class="fas fa-shipping-fast me-2"></i>Mark Out for Delivery
                            </button>
                            <button class="btn-modern btn-modern-danger"
                                onclick="confirmStatus('{{ $delivery->id }}','{{ addslashes($delivery->customer_name) }}','Cancelled'); closeDeliveryModal({{ $delivery->id }});">
                                <i class="fas fa-times-circle me-2"></i>Cancel Order
                            </button>

                        @elseif($status === 'Out for Delivery')
                            <button class="btn-modern btn-modern-success"
                                onclick="confirmStatus('{{ $delivery->id }}','{{ addslashes($delivery->customer_name) }}','Delivered'); closeDeliveryModal({{ $delivery->id }});">
                                <i class="fas fa-check-circle me-2"></i>Mark as Delivered
                            </button>
                            <button class="btn-modern btn-modern-danger"
                                onclick="confirmStatus('{{ $delivery->id }}','{{ addslashes($delivery->customer_name) }}','Failed'); closeDeliveryModal({{ $delivery->id }});">
                                <i class="fas fa-exclamation-triangle me-2"></i>Mark as Failed
                            </button>

                        @elseif($status === 'Failed')
                            <div class="alert-modern alert-danger" style="font-size:0.85rem;margin-bottom:0.5rem;">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                Delivery could not be completed. Retry to reschedule a new attempt.
                            </div>
                            <button class="btn-modern btn-modern-warning"
                                onclick="confirmStatus('{{ $delivery->id }}','{{ addslashes($delivery->customer_name) }}','Pending'); closeDeliveryModal({{ $delivery->id }});">
                                <i class="fas fa-redo me-2"></i>Retry Delivery (Reset to Pending)
                            </button>

                        @elseif($status === 'Delivered')
                            <button class="btn-modern btn-modern-success" disabled>
                                <i class="fas fa-check-circle me-2"></i>Delivered
                            </button>

                        @elseif($status === 'Cancelled')
                            <button class="btn-modern btn-modern-secondary" disabled>
                                <i class="fas fa-ban me-2"></i>Cancelled
                            </button>
                        @endif
                    </div>

                </div>{{-- /dmodal-body --}}
            </div>{{-- /delivery-modal --}}
        </div>{{-- /delivery-modal-overlay --}}

        @empty
        <div class="col-12 text-center py-5">
            <i class="fas fa-box-open fa-4x text-muted mb-3 d-block"></i>
            <p class="text-muted">No deliveries assigned yet</p>
        </div>
        @endforelse
    </div>
</main>

<!-- Image Zoom Modal -->
<div class="image-modal" id="imageModal" onclick="closeImageModal()">
    <span class="image-modal-close" onclick="closeImageModal()">&times;</span>
    <img class="image-modal-content" id="modalImage" alt="">
</div>

<!-- Status Confirm Modal -->
<div class="modal fade" id="confirmModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-exclamation-circle me-2"></i>Confirm Status Update</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="confirmStatusForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <p class="fw-bold mb-1">Order ID:</p><p id="confirmOrderId"></p>
                    <p class="fw-bold mb-1">Customer:</p><p id="confirmCustomerName"></p>
                    <p class="fw-bold mb-1">Change status to:</p>
                    <p class="fw-bold" id="confirmNewStatus" style="color:var(--primary-blue);"></p>
                    <input type="hidden" name="delivery_status" id="hiddenStatusValue">
                    <div id="failedStatusNote" style="display:none;" class="alert-modern alert-danger" style="font-size:0.84rem;margin-top:8px;">
                        <i class="fas fa-info-circle me-2"></i>
                        The customer and admin will be notified via in-app notification and email.
                    </div>
                    <div id="retryStatusNote" style="display:none;" class="alert-modern alert-warning" style="font-size:0.84rem;margin-top:8px;">
                        <i class="fas fa-info-circle me-2"></i>
                        The order will be reset to Pending. The customer will be notified that delivery has been rescheduled.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-modern btn-modern-secondary" data-bs-dismiss="modal">No</button>
                    <button type="submit" class="btn-modern btn-modern-primary">Yes, Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- GPS Permission Modal -->
<div class="modal fade" id="gpsPermissionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-map-marker-alt me-2" style="color:var(--warning);"></i>Location Access Required</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3"><i class="fas fa-map-marked-alt fa-3x" style="color:var(--warning);"></i></div>
                <p class="mb-3">To track your delivery route and provide turn-by-turn directions, we need access to your location.</p>
                <div class="alert alert-info mb-3"><strong><i class="fas fa-info-circle me-2"></i>How to enable:</strong></div>
                <div class="mb-3">
                    <div class="d-flex align-items-center mb-2"><span class="badge bg-primary rounded-circle me-2">1</span><span>Click the <strong>🔒 lock icon</strong> in your browser's address bar</span></div>
                    <div class="d-flex align-items-center mb-2"><span class="badge bg-primary rounded-circle me-2">2</span><span>Find <strong>Location</strong> permission and set it to <strong>Allow</strong></span></div>
                    <div class="d-flex align-items-center mb-2"><span class="badge bg-primary rounded-circle me-2">3</span><span>Refresh the page and click "Allow" when prompted</span></div>
                </div>
                <div class="text-center">
                    <button class="btn-modern btn-modern-primary" onclick="location.reload()">
                        <i class="fas fa-sync-alt me-2"></i>Refresh Page
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
const STORE_LAT = 10.314152;
const STORE_LNG = 123.906935;

// ── Theme ──────────────────────────────────────────────────────────
function toggleTheme() {
    const t = document.documentElement.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
    document.documentElement.setAttribute('data-theme', t);
    localStorage.setItem('delivery-theme', t);
}

document.addEventListener('DOMContentLoaded', function() {
    const sidebarToggle = document.getElementById('sidebarToggle');
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', () => {
            document.getElementById('sidebar')?.classList.toggle('show');
        });
    }
    const dateEl = document.getElementById('currentDate');
    if (dateEl) {
        dateEl.textContent = new Date().toLocaleDateString('en-US', { month:'short', day:'numeric', year:'numeric' });
    }
});

// ── Proof upload helpers ───────────────────────────────────────────
function previewProofFile(input, id) {
    if (!input.files?.[0]) return;
    const r = new FileReader();
    r.onload = e => {
        document.getElementById('imagePreview'+id).style.display = 'block';
        document.getElementById('preview'+id).src = e.target.result;
    };
    r.readAsDataURL(input.files[0]);
}

function submitProof(id, url) {
    const fileInput = document.getElementById('proofFile' + id);
    const btn       = document.getElementById('proofSubmitBtn' + id);
    const msg       = document.getElementById('proofMsg' + id);

    if (!fileInput || !fileInput.files[0]) {
        if (msg) { msg.style.color = 'var(--danger)'; msg.textContent = 'Please select an image first.'; }
        return;
    }

    const formData = new FormData();
    formData.append('payment_proof', fileInput.files[0]);
    formData.append('_token', document.querySelector('meta[name="csrf-token"]')?.content || '');

    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Uploading…';
    if (msg) msg.textContent = '';

    fetch(url, {
        method: 'POST',
        body: formData,
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
    })
    .then(async res => {
        let data = {};
        if ((res.headers.get('content-type') || '').includes('application/json')) data = await res.json();
        if (res.ok) {
            if (msg) { msg.style.color = 'var(--success)'; msg.textContent = data.success || 'Uploaded! Reloading…'; }
            setTimeout(() => window.location.reload(), 800);
        } else {
            throw new Error(data.message || data.error || 'Server error: ' + res.status);
        }
    })
    .catch(err => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-cloud-upload-alt"></i> Submit Payment Proof';
        if (msg) { msg.style.color = 'var(--danger)'; msg.textContent = err.message || 'Upload failed.'; }
    });
}

// ── Image modal ────────────────────────────────────────────────────
function openImageModal(img) {
    document.getElementById('modalImage').src = img.src;
    document.getElementById('imageModal').classList.add('show');
    document.body.style.overflow = 'hidden';
}
function closeImageModal() {
    document.getElementById('imageModal').classList.remove('show');
    document.body.style.overflow = '';
}
document.getElementById('modalImage')?.addEventListener('click', e => e.stopPropagation());

// ── Status confirm modal ───────────────────────────────────────────
function confirmStatus(id, name, status) {
    document.getElementById('confirmOrderId').innerText    = '#' + id;
    document.getElementById('confirmCustomerName').innerText = name;
    document.getElementById('confirmNewStatus').innerText  = status;
    document.getElementById('hiddenStatusValue').value     = status;
    document.getElementById('confirmStatusForm').action    = '/delivery/deliveries/' + id + '/status';

    // Show contextual notes
    document.getElementById('failedStatusNote').style.display = (status === 'Failed')  ? 'flex' : 'none';
    document.getElementById('retryStatusNote').style.display  = (status === 'Pending') ? 'flex' : 'none';

    new bootstrap.Modal(document.getElementById('confirmModal')).show();
}

// ── Delivery detail modal ──────────────────────────────────────────
const modalMapInited = {};

function openDeliveryModal(id) {
    const overlay = document.getElementById('modal-overlay-' + id);
    if (!overlay) return;
    overlay.classList.add('active');
    document.body.style.overflow = 'hidden';
    if (!modalMapInited[id]) {
        modalMapInited[id] = true;
        if (mapCoords[id]) setTimeout(() => initDeliveryMap(id, 'modal'), 80);
    }
}

function closeDeliveryModal(id) {
    const overlay = document.getElementById('modal-overlay-' + id);
    if (overlay) overlay.classList.remove('active');
    document.body.style.overflow = '';
    const trackerKey = 'modal' + id;
    if (liveTrackers[trackerKey]) {
        clearInterval(liveTrackers[trackerKey]);
        delete liveTrackers[trackerKey];
    }
}

document.addEventListener('keydown', e => {
    if (e.key !== 'Escape') return;
    closeImageModal();
    document.querySelectorAll('.delivery-modal-overlay.active').forEach(el => {
        closeDeliveryModal(el.id.replace('modal-overlay-', ''));
    });
    document.body.style.overflow = '';
});

function showGpsPermissionModal() {
    try { new bootstrap.Modal(document.getElementById('gpsPermissionModal')).show(); } catch(e) {}
}

// ═══════════════════════════════════════════════════════════════
//  MAP ENGINE
// ═══════════════════════════════════════════════════════════════
@php
    $mapDeliveries = collect($deliveries)
        ->whereNotIn('delivery_status', ['Cancelled', 'Failed'])
        ->map(function($d) {
            return [
                'id'      => $d->id,
                'address' => $d->address,
                'lat'     => $d->latitude  ? (float) $d->latitude  : null,
                'lng'     => $d->longitude ? (float) $d->longitude : null,
                'status'  => $d->delivery_status,
            ];
        })
        ->values();
@endphp
const deliveryMapData = @json($mapDeliveries);
const mapCoords = {};
deliveryMapData.forEach(d => { mapCoords[d.id] = d; });

const mapInstances  = {};
const liveTrackers  = {};
const riderMarkers  = {};

function haversine(a, b, c, d) {
    const R = 6371, r = x => x * Math.PI / 180;
    const dA = r(c-a), dB = r(d-b);
    const q = Math.sin(dA/2)**2 + Math.cos(r(a)) * Math.cos(r(c)) * Math.sin(dB/2)**2;
    return R * 2 * Math.atan2(Math.sqrt(q), Math.sqrt(1-q));
}
function fmtDist(km) { return km < 1 ? (km*1000).toFixed(0)+' m' : km.toFixed(1)+' km'; }
function fmtTime(s)  { const m=Math.round(s/60); return m<1?'Arriving!':m<60?m+' min':Math.floor(m/60)+'h '+(m%60)+'m'; }
function setEl(id, html, isHTML=false) { const el=document.getElementById(id); if(!el)return; if(isHTML)el.innerHTML=html; else el.textContent=html; }

function checkGpsPermission() {
    return new Promise(resolve => {
        if (!navigator.geolocation) { resolve('unsupported'); return; }
        if (navigator.permissions?.query) {
            navigator.permissions.query({ name:'geolocation' })
                .then(p => resolve(p.state))
                .catch(() => navigator.geolocation.getCurrentPosition(
                    () => resolve('granted'),
                    err => resolve(err.code===1?'denied':'prompt'),
                    { timeout:100 }
                ));
        } else {
            navigator.geolocation.getCurrentPosition(
                () => resolve('granted'),
                err => resolve(err.code===1?'denied':'prompt'),
                { timeout:100 }
            );
        }
    });
}

function initDeliveryMap(id, mapType) {
    const data = mapCoords[id];
    if (!data) return;
    const statusKey = (mapType==='mini' ? 'mini-status-' : 'modal-map-status-') + id;
    if (data.lat && data.lng) {
        setEl(statusKey, 'Exact location');
        buildMap(id, data.lat, data.lng, mapType, data.status);
    } else {
        setEl(statusKey, '⚠ No pin saved');
        buildMap(id, 10.3157, 123.8854, mapType, data.status);
    }
}

function buildMap(id, destLat, destLng, mapType, deliveryStatus) {
    const elId  = (mapType==='mini' ? 'mini-map-' : 'modal-map-') + id;
    const mapEl = document.getElementById(elId);
    if (!mapEl) return;

    const key = mapType + id;
    if (mapInstances[key]) return;

    const isModal     = mapType === 'modal';
    const isLive      = deliveryStatus === 'Out for Delivery';
    const isDelivered = deliveryStatus === 'Delivered';

    const map = L.map(mapEl, { zoomControl:isModal, scrollWheelZoom:isModal });
    mapInstances[key] = map;

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution:'© OpenStreetMap', maxZoom:19
    }).addTo(map);

    const pinHtml = isDelivered
        ? '<div class="map-pin map-pin-dest" style="background:linear-gradient(135deg,#48BB78,#38a169);"><i class="fas fa-check"></i></div>'
        : '<div class="map-pin map-pin-dest"><i class="fas fa-home"></i></div>';

    L.marker([destLat, destLng], {
        icon: L.divIcon({ className:'', html:pinHtml, iconSize:[32,32], iconAnchor:[16,32] })
    }).addTo(map).bindPopup(isDelivered ? '✅ Delivered here' : '📦 Deliver here');

    const statusKey = isModal ? 'modal-map-status-'+id : 'mini-status-'+id;

    if (!isLive) {
        const url = 'https://router.project-osrm.org/route/v1/driving/'
            + STORE_LNG+','+STORE_LAT+';'+destLng+','+destLat
            + '?overview=full&geometries=geojson';
        fetch(url).then(r=>r.json()).then(data => {
            if (data.code==='Ok' && data.routes?.length) {
                const coords = data.routes[0].geometry.coordinates.map(c=>[c[1],c[0]]);
                const distKm = data.routes[0].distance/1000;
                L.marker([STORE_LAT,STORE_LNG],{icon:L.divIcon({className:'',html:'<div style="background:linear-gradient(135deg,#667eea,#764ba2);width:28px;height:28px;border-radius:50%;border:3px solid #fff;display:flex;align-items:center;justify-content:center;box-shadow:0 2px 8px rgba(102,126,234,0.5);"><i class="fas fa-store" style="color:#fff;font-size:0.65rem;"></i></div>',iconSize:[28,28],iconAnchor:[14,14]})}).addTo(map).bindPopup('<b>🏪 Store (Hipodromo)</b>');
                L.polyline(coords,{color:isDelivered?'#48BB78':'#667eea',weight:isModal?5:3,opacity:0.7,dashArray:isDelivered?null:'8,8',lineJoin:'round',lineCap:'round'}).addTo(map);
                map.fitBounds(L.polyline(coords).getBounds(),{padding:isModal?[50,50]:[25,25]});
                if(isModal){
                    setEl('modal-dist-'+id, fmtDist(distKm)+' from store');
                    setEl('modal-maps-link-'+id,`<a href="https://www.google.com/maps/dir/${STORE_LAT},${STORE_LNG}/${destLat},${destLng}" target="_blank" style="color:#fff;font-size:0.75rem;background:rgba(255,255,255,0.2);padding:3px 10px;border-radius:20px;text-decoration:none;"><i class="fas fa-map-marked-alt me-1"></i>Open in Maps</a>`,true);
                } else {
                    setEl('mini-dist-'+id, fmtDist(distKm));
                }
            } else { map.setView([destLat,destLng],15); }
        }).catch(()=>map.setView([destLat,destLng],15));
        setEl(statusKey, isDelivered?'✓ Delivered':'Pending');
        return;
    }

    // Live tracking
    checkGpsPermission().then(perm => {
        if (perm==='denied') {
            map.setView([destLat,destLng],15);
            setEl(statusKey,'⚠ GPS blocked');
            showGpsBlockedBanner(id,mapType,destLat,destLng);
            drawRoute(map,destLat,destLng,id,mapType);
            return;
        }
        if (perm==='prompt'||perm==='unsupported') showGpsPromptBanner(id,mapType,destLat,destLng);
        drawRoute(map,destLat,destLng,id,mapType);
        requestGPS(map,id,destLat,destLng,mapType,statusKey,isModal);
    });
}

function showGpsPromptBanner(id, mapType, destLat, destLng) {
    if (mapType!=='modal') return;
    const el = document.getElementById('steps-list-'+id);
    if (!el) return;
    el.innerHTML=`<div class="gps-permission-banner"><i class="fas fa-map-marker-alt"></i><div class="gps-permission-banner-content"><div class="gps-permission-banner-title">Location access requested</div><div class="gps-permission-banner-text">Allow location access to see your live position.</div><div class="gps-permission-banner-buttons"><button class="gps-retry-btn" onclick="retryGpsPermission(${id},${destLat},${destLng})"><i class="fas fa-sync-alt"></i>Retry GPS</button><a href="https://www.google.com/maps/dir/${STORE_LAT},${STORE_LNG}/${destLat},${destLng}" target="_blank" class="gps-manual-btn"><i class="fas fa-map-marked-alt"></i>Google Maps</a></div></div></div>`;
}

function showGpsBlockedBanner(id, mapType, destLat, destLng) {
    if (mapType!=='modal') return;
    const el = document.getElementById('steps-list-'+id);
    if (!el) return;
    el.innerHTML=`<div class="gps-permission-banner" style="background:rgba(252,129,129,0.15);border-color:var(--danger);"><i class="fas fa-exclamation-triangle" style="color:var(--danger);"></i><div class="gps-permission-banner-content"><div class="gps-permission-banner-title">Location access is blocked</div><div class="gps-permission-banner-text">Enable location in your browser settings, then refresh.</div><div class="gps-permission-banner-buttons"><button class="gps-retry-btn" onclick="showGpsPermissionModal()"><i class="fas fa-question-circle"></i>How to enable</button><a href="https://www.google.com/maps/dir/${STORE_LAT},${STORE_LNG}/${destLat},${destLng}" target="_blank" class="gps-manual-btn"><i class="fas fa-map-marked-alt"></i>Google Maps</a></div></div></div>`;
}

function retryGpsPermission(id, destLat, destLng) {
    const map = mapInstances['modal'+id];
    if (!map) return;
    const statusKey = 'modal-map-status-'+id;
    const el = document.getElementById('steps-list-'+id);
    if (el) el.innerHTML='<div class="step-item"><span style="color:var(--text-secondary);">Requesting location…</span></div>';
    setEl(statusKey,'Requesting GPS…');
    requestGPS(map,id,destLat,destLng,'modal',statusKey,true);
}

function requestGPS(map, id, destLat, destLng, mapType, statusKey, isModal) {
    if (!navigator.geolocation) return;
    navigator.geolocation.getCurrentPosition(
        pos => {
            setEl(statusKey,'On the way');
            placeRiderMarker(map,id,pos.coords.latitude,pos.coords.longitude,destLat,destLng,mapType,statusKey,isModal);
            const tk='modal'+id;
            if(liveTrackers[tk]) clearInterval(liveTrackers[tk]);
            liveTrackers[tk]=setInterval(()=>{
                navigator.geolocation.getCurrentPosition(
                    p=>placeRiderMarker(map,id,p.coords.latitude,p.coords.longitude,destLat,destLng,mapType,statusKey,isModal),
                    ()=>{},{enableHighAccuracy:true,timeout:10000,maximumAge:15000}
                );
            },15000);
        },
        err=>{
            let msg='⚠ GPS error';
            if(err.code===1){msg='⚠ GPS blocked';showGpsBlockedBanner(id,mapType,destLat,destLng);}
            else if(err.code===2){msg='⚠ Position unavailable';}
            else if(err.code===3){msg='⚠ GPS timeout';}
            setEl(statusKey,msg);
        },
        {enableHighAccuracy:true,timeout:15000,maximumAge:0}
    );
}

function placeRiderMarker(map, id, rLat, rLng, destLat, destLng, mapType, statusKey, isModal) {
    if (riderMarkers[id]) {
        riderMarkers[id].setLatLng([rLat,rLng]);
    } else {
        riderMarkers[id]=L.marker([rLat,rLng],{
            icon:L.divIcon({className:'',html:'<div style="position:relative;display:inline-block;"><div style="width:46px;height:46px;border-radius:50%;background:linear-gradient(135deg,#43e97b,#38f9d7);border:3px solid #fff;box-shadow:0 0 0 4px rgba(67,233,123,0.35),0 4px 14px rgba(67,233,123,0.5);display:flex;align-items:center;justify-content:center;"><i class="fas fa-motorcycle" style="color:#fff;font-size:1.1rem;"></i></div><div style="position:absolute;top:-26px;left:50%;transform:translateX(-50%);background:linear-gradient(135deg,#43e97b,#38f9d7);color:#fff;font-size:9px;padding:2px 8px;border-radius:12px;white-space:nowrap;font-weight:800;">🛵 RIDER</div></div>',iconSize:[46,46],iconAnchor:[23,23]}),zIndexOffset:1000
        }).addTo(map).bindPopup('<b>🛵 Your Rider</b><br><small>Live Location</small>');
    }
    const remKm=haversine(rLat,rLng,destLat,destLng);
    if(isModal){
        setEl('modal-eta-'+id,fmtTime(remKm/30*3600));
        const pct=Math.max(0,Math.min(100,Math.round((1-remKm/haversine(STORE_LAT,STORE_LNG,destLat,destLng))*100)));
        buildProgressBar(id,pct);
        setEl(statusKey,pct>=95?'Arriving!':'On the way '+pct+'%');
    } else {
        setEl('mini-eta-'+id,fmtTime(remKm/30*3600));
    }
}

async function drawRoute(map, destLat, destLng, id, mapType) {
    const isModal=mapType==='modal';
    const statusKey=isModal?'modal-map-status-'+id:'mini-status-'+id;
    try {
        const url='https://router.project-osrm.org/route/v1/driving/'+STORE_LNG+','+STORE_LAT+';'+destLng+','+destLat+'?overview=full&geometries=geojson&steps=true';
        const res=await fetch(url);
        const data=await res.json();
        if(data.code!=='Ok'||!data.routes?.length) throw new Error('no route');
        const route=data.routes[0];
        const coords=route.geometry.coordinates.map(c=>[c[1],c[0]]);
        const distKm=route.distance/1000;
        const durS=route.duration;
        L.marker([STORE_LAT,STORE_LNG],{icon:L.divIcon({className:'',html:'<div style="background:linear-gradient(135deg,#667eea,#764ba2);width:28px;height:28px;border-radius:50%;border:3px solid #fff;display:flex;align-items:center;justify-content:center;box-shadow:0 2px 8px rgba(102,126,234,0.5);"><i class="fas fa-store" style="color:#fff;font-size:0.65rem;"></i></div>',iconSize:[28,28],iconAnchor:[14,14]})}).addTo(map).bindPopup('<b>🏪 Store (Hipodromo)</b>');
        L.polyline(coords,{color:'#667eea',weight:isModal?14:10,opacity:0.18,lineJoin:'round',lineCap:'round'}).addTo(map);
        const routeLine=L.polyline(coords,{color:'#667eea',weight:isModal?6:4,opacity:0.92,lineJoin:'round',lineCap:'round'}).addTo(map);
        L.polyline(coords,{color:'#fff',weight:isModal?2.5:1.5,opacity:0.7,dashArray:'8 18',lineJoin:'round',lineCap:'round'}).addTo(map);
        L.circle([destLat,destLng],{radius:35,color:'#f5576c',fillColor:'#f5576c',fillOpacity:0.2,weight:2.5}).addTo(map);
        map.fitBounds(routeLine.getBounds(),{padding:isModal?[55,55]:[28,28]});
        if(isModal){
            setEl('modal-dist-'+id,fmtDist(distKm));
            setEl('modal-eta-'+id,fmtTime(durS));
            buildTurnByTurn(route,id);
            buildProgressBar(id,0);
            setEl('modal-maps-link-'+id,`<a href="https://www.google.com/maps/dir/${STORE_LAT},${STORE_LNG}/${destLat},${destLng}" target="_blank" style="color:#fff;font-size:0.75rem;background:rgba(255,255,255,0.2);padding:3px 10px;border-radius:20px;text-decoration:none;"><i class="fas fa-map-marked-alt me-1"></i>Open in Maps</a>`,true);
        } else {
            setEl('mini-dist-'+id,fmtDist(distKm));
            setEl('mini-eta-'+id,fmtTime(durS));
        }
        setEl(statusKey,'Route loaded');
    } catch(e) {
        const line=L.polyline([[STORE_LAT,STORE_LNG],[destLat,destLng]],{color:'#667eea',weight:4,opacity:0.75,dashArray:'9,9'}).addTo(map);
        map.fitBounds(line.getBounds(),{padding:[40,40]});
        const d=haversine(STORE_LAT,STORE_LNG,destLat,destLng);
        if(isModal){
            setEl('modal-dist-'+id,fmtDist(d)+' (straight line)');
            setEl('steps-list-'+id,'<div class="step-item"><span style="color:var(--text-secondary);font-size:0.82rem;">Road directions unavailable.</span></div>',true);
        } else { setEl('mini-dist-'+id,fmtDist(d)); }
        setEl(statusKey,'Approx. route');
    }
}

function buildProgressBar(id, pct) {
    const container=document.getElementById('steps-list-'+id);
    if(!container) return;
    const existing=document.getElementById('progress-bar-fill-'+id);
    if(existing){existing.style.width=pct+'%';const l=document.getElementById('progress-bar-label-'+id);if(l)l.textContent=pct+'% complete';return;}
    const bar=document.createElement('div');
    bar.style.marginBottom='12px';
    bar.innerHTML='<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:5px;"><span style="font-size:0.75rem;font-weight:700;color:var(--text-secondary);text-transform:uppercase;letter-spacing:0.05em;"><i class="fas fa-route me-1"></i>Delivery Progress</span><span id="progress-bar-label-'+id+'" style="font-size:0.75rem;font-weight:700;color:var(--primary-blue);">'+pct+'% complete</span></div><div style="background:var(--border-color);border-radius:99px;height:8px;overflow:hidden;"><div id="progress-bar-fill-'+id+'" style="height:100%;width:'+pct+'%;background:linear-gradient(90deg,#667eea,#764ba2);border-radius:99px;transition:width 0.6s ease;box-shadow:0 0 8px rgba(102,126,234,0.5);"></div></div><div style="display:flex;justify-content:space-between;margin-top:3px;font-size:0.68rem;color:var(--text-secondary);"><span>🏪 Store</span><span>🏠 Customer</span></div>';
    container.prepend(bar);
}

function buildTurnByTurn(route, id) {
    const container=document.getElementById('steps-list-'+id);
    if(!container) return;
    const steps=route.legs?.[0]?.steps??[];
    if(!steps.length){container.innerHTML='<div class="step-item"><span style="color:var(--text-secondary);font-size:0.82rem;">No steps available.</span></div>';return;}
    container.innerHTML=steps.map((s,i)=>{
        const type=s.maneuver?.type??'', mod=s.maneuver?.modifier??'', name=s.name||'road';
        const dist=s.distance<1000?s.distance.toFixed(0)+' m':(s.distance/1000).toFixed(1)+' km';
        const icon=getManeuverIcon(type,mod);
        const desc=type==='arrive'?'🏁 Arrive at destination':`${cap(type.replace('-',' '))}${mod?' '+mod.replace('-',' '):''}${name?' on <b>'+name+'</b>':''}`;
        return `<div class="step-item"><div class="step-num">${i+1}</div><div><div class="step-text">${icon} ${desc}</div><div class="step-dist">${dist}</div></div></div>`;
    }).join('');
}

function getManeuverIcon(type,mod){
    if(type==='arrive') return '🏁';
    if(type==='depart') return '🚀';
    if(type==='roundabout') return '⭕';
    if(type==='turn'){
        if(mod.includes('sharp left')) return '↰';
        if(mod.includes('left')) return '←';
        if(mod.includes('sharp right')) return '↱';
        if(mod.includes('right')) return '→';
        if(mod.includes('uturn')) return '↩';
        return '⬆';
    }
    if(type==='merge') return '↖';
    if(type==='fork') return '⑂';
    return '⬆';
}

function cap(s){ return s?s[0].toUpperCase()+s.slice(1):s; }

document.addEventListener('DOMContentLoaded', () => {
    deliveryMapData.forEach((d, i) => {
        setTimeout(() => initDeliveryMap(d.id, 'mini'), i * 150);
    });
});

window.addEventListener('beforeunload', () => {
    Object.values(liveTrackers).forEach(clearInterval);
});
</script>

</body>
</html>