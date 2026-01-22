<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Purchase History - HookcraftAvenue</title>

<!-- Bootstrap & Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('asset/stylesnav.css') }}">

<style>
    :root {
        --pink: #ffb6c1;
        --pink-dark: #ff9ead;
        --pink-light: #fff5f7;
        --success: #10b981;
        --info: #3b82f6;
        --warning: #f59e0b;
        --danger: #ef4444;
    }

    body { background: #f8f9fa; }

    /* ===== SIDEBAR ===== */
    .sidebar-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 14px rgba(0,0,0,0.08);
        overflow: hidden;
    }

    .sidebar-header {
        text-align: center;
        padding: 2rem 1.5rem 1.5rem;
    }
    .sidebar-header h6 {
        font-weight: 700; /* Make name bold */
        margin-bottom: 0.25rem;
        color: #111827;
    }

    .sidebar-avatar {
        width: 110px;
        height: 110px;
        border-radius: 50%;
        padding: 5px;
        border: 4px solid var(--pink);
        margin-bottom: 1rem;
    }

    .sidebar-menu a {
        display: flex;
        align-items: center;
        gap: .75rem;
        padding: .9rem 1.25rem;
        font-weight: 600;
        text-decoration: none;
        color: #111827;
    }

    .sidebar-menu a.active {
        background: var(--pink);
        color: white;
    }

    .sidebar-menu a:hover:not(.active) {
        background: var(--pink-light);
        color: var(--pink-dark);
    }

    /* ===== MAIN CARD ===== */
    .main-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 4px 16px rgba(0,0,0,0.08);
        overflow: hidden;
    }

    .gradient-header {
        background: linear-gradient(135deg, var(--pink), var(--pink-dark));
        color: white;
        padding: 1.5rem;
    }

    /* ===== TABLE ===== */
    .table thead th {
        background: var(--pink-light);
        font-weight: 700;
        font-size: .8rem;
        text-transform: uppercase;
    }

    .table tbody tr:hover {
        background: var(--pink-light);
    }

    /* ===== STATUS BADGES ===== */
    .status-badge {
        padding: .4rem .85rem;
        border-radius: 50px;
        font-size: .75rem;
        font-weight: 700;
        display: inline-block;
        text-transform: capitalize;
        color: white;
    }

    /* Payment Status */
    .payment-pending { background:#f59e0b; }  /* yellow/orange */
    .payment-paid    { background:#10b981; }  /* green */
    .payment-failed  { background:#ef4444; }  /* red */

    /* Delivery Status */
    .delivery-pending         { background:#fbbf24; }  /* amber */
    .delivery-out-for-delivery{ background:#3b82f6; }  /* blue */
    .delivery-delivered       { background:#10b981; }  /* green */
    .delivery-cancelled       { background:#ef4444; }  /* red */

    /* ===== BUTTON ===== */
    .btn-gradient {
        background: linear-gradient(135deg,var(--pink),var(--pink-dark));
        color:white;
        border:none;
        font-weight:600;
    }

    /* ===== RESPONSIVE ENHANCEMENTS ===== */
    
    /* Mobile Responsive - Tablet & Below */
    @media (max-width: 992px) {
        .container {
            padding-left: 15px;
            padding-right: 15px;
        }
        
        .sidebar-card {
            margin-bottom: 1.5rem;
        }
        
        .sidebar-header {
            padding: 1.5rem 1rem;
        }
        
        .sidebar-avatar {
            width: 90px;
            height: 90px;
        }
        
        .main-card {
            border-radius: 16px;
        }
    }
    
    /* Mobile Responsive - Small Tablets & Phones */
    @media (max-width: 768px) {
        body {
            font-size: 14px;
            line-height: 1.5;
        }
        
        .container.py-5 {
            padding-top: 1.5rem !important;
            padding-bottom: 1.5rem !important;
        }
        
        /* Sidebar mobile optimization */
        .sidebar-card {
            max-width: 100%;
        }
        
        .sidebar-menu a {
            padding: 0.75rem 1rem;
            font-size: 0.9rem;
        }
        
        .sidebar-menu a i {
            min-width: 24px;
        }
        
        /* Main content optimization */
        .gradient-header {
            padding: 1rem;
        }
        
        .gradient-header h5 {
            font-size: 1.1rem;
        }
        
        /* Table mobile optimization */
        .table-responsive {
            border-radius: 12px;
            border: 1px solid #f0f0f0;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        
        .table {
            min-width: 600px; /* Ensure table scrolls on mobile */
            margin-bottom: 0;
        }
        
        .table thead th {
            font-size: 0.75rem;
            padding: 0.75rem 0.5rem;
            white-space: nowrap;
        }
        
        .table tbody td {
            padding: 0.75rem 0.5rem;
            vertical-align: middle;
        }
        
        /* Status badges mobile */
        .status-badge {
            padding: 0.35rem 0.5rem;
            font-size: 0.7rem;
            display: block;
            text-align: center;
            min-width: 80px;
            white-space: nowrap;
        }
        
        /* Button mobile optimization */
        .btn-gradient {
            padding: 0.4rem 0.75rem;
            font-size: 0.8rem;
        }
        
        .btn-gradient i {
            margin: 0 !important;
        }
        
        /* Modal mobile optimization */
        .modal-dialog {
            margin: 10px;
        }
        
        .modal-content {
            border-radius: 12px;
        }
        
        .modal-header {
            padding: 1rem;
        }
        
        .modal-body {
            padding: 1rem;
        }
        
        .modal-footer {
            padding: 1rem;
        }
        
        .btn-close {
            width: 40px;
            height: 40px;
            background-size: 1rem;
        }
    }
    
    /* Extra Small Devices (Phones < 576px) */
    @media (max-width: 575.98px) {
        .container {
            padding-left: 12px;
            padding-right: 12px;
        }
        
        /* Sidebar avatar adjustment */
        .sidebar-avatar {
            width: 70px;
            height: 70px;
        }
        
        .sidebar-header h6 {
            font-size: 0.95rem;
        }
        
        .sidebar-header p {
            font-size: 0.8rem;
        }
        
        /* Table header adjustments */
        .table thead th:nth-child(2), /* Date */
        .table thead th:nth-child(3) { /* Total */
            display: none;
        }
        
        .table tbody td:nth-child(2), /* Date */
        .table tbody td:nth-child(3) { /* Total */
            display: none;
        }
        
        /* Status badges stacking */
        .status-badge {
            min-width: 70px;
            padding: 0.3rem 0.4rem;
            font-size: 0.65rem;
        }
        
        /* Button text hide on very small screens */
        .btn-gradient span {
            display: none;
        }
        
        .btn-gradient i {
            font-size: 0.9rem;
        }
        
        /* Modal table adjustments */
        .modal-body .table {
            min-width: 100%;
        }
        
        .modal-body .table th,
        .modal-body .table td {
            padding: 0.5rem 0.25rem;
            font-size: 0.85rem;
        }
        
        /* Modal content spacing */
        .modal-body p {
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }
    }
    
    /* Fix for iPhone Safari */
    @supports (-webkit-touch-callout: none) {
        .sidebar-menu a,
        .btn-gradient {
            min-height: 44px; /* Apple's minimum tap target */
        }
        
        .status-badge {
            min-height: 30px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
    }
    
    /* Horizontal scroll prevention */
    @media (max-width: 768px) {
        body {
            overflow-x: hidden;
        }
        
        .row {
            margin-left: 0;
            margin-right: 0;
        }
        
        .col-md-3,
        .col-md-9 {
            padding-left: 0;
            padding-right: 0;
        }
    }
    
    /* Better touch targets for mobile */
    @media (max-width: 768px) {
        .sidebar-menu a,
        .btn,
        .table tbody tr {
            min-height: 44px;
        }
        
        .table tbody tr {
            cursor: pointer;
        }
    }
    
    /* Mobile hover removal */
    @media (hover: none) and (pointer: coarse) {
        .table tbody tr:hover {
            background: transparent;
        }
        
        .sidebar-menu a:hover:not(.active) {
            background: transparent;
        }
    }
    
    /* Ensure images don't overflow */
    @media (max-width: 768px) {
        img {
            max-width: 100%;
            height: auto;
        }
        
        .sidebar-avatar {
            max-width: 110px;
            margin: 0 auto 1rem;
        }
    }
    
    /* Better text readability on mobile */
    @media (max-width: 768px) {
        h5, h6 {
            line-height: 1.3;
        }
        
        .text-muted {
            opacity: 0.9;
        }
        
        .gradient-header h5 {
            font-size: 1rem;
        }
    }
    
    /* Modal touch improvements */
    @media (max-width: 768px) {
        .modal-content {
            max-height: 90vh;
            overflow-y: auto;
        }
        
        .modal-body {
            max-height: calc(90vh - 120px);
            overflow-y: auto;
        }
    }
    
    /* Empty state mobile */
    @media (max-width: 768px) {
        .text-center.text-muted {
            padding: 3rem 1rem;
            font-size: 1rem;
        }
    }
    
    /* Toast notification mobile positioning */
    @media (max-width: 768px) {
        .toast-notification {
            left: 10px;
            right: 10px;
            top: 10px;
            width: calc(100% - 20px);
            text-align: center;
            animation: slideDown 0.3s ease;
        }
        
        @keyframes slideDown {
            from {
                transform: translateY(-100px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
    }
    
    /* Prevent zoom on input focus */
    @media (max-width: 768px) {
        input,
        select,
        textarea {
            font-size: 16px !important;
        }
    }
</style>
</head>

<body>

@include('components.navbar')

<div class="container py-5">
<div class="row">

<!-- SIDEBAR -->
<div class="col-md-3 mb-4">
    <div class="sidebar-card">
        <div class="sidebar-header">
            <img src="{{ asset('asset/images/default-profile.png') }}" class="sidebar-avatar">
            <h6>{{ Auth::user()->name }}</h6>
            <p class="text-muted small">{{ Auth::user()->email }}</p>
        </div>

        <div class="sidebar-menu">
            <a href="{{ route('profile.index') }}">
                <i class="fas fa-user"></i> My Account
            </a>
            <a href="{{ route('profile.purchase-history') }}" class="active">
                <i class="fas fa-history"></i> Purchase History
            </a>
            <a href="{{ route('profile.track-order') }}">
                <i class="fas fa-truck"></i> Track Order
            </a>
        </div>
    </div>
</div>

<!-- MAIN CONTENT -->
<div class="col-md-9">
<div class="main-card">
<div class="gradient-header">
    <h5 class="mb-0"><i class="fas fa-history me-2"></i> Purchase History</h5>
</div>

<div class="p-4">
@if($orders->count())

<div class="table-responsive">
<table class="table align-middle">
<thead>
<tr>
    <th>Order</th>
    <th>Date</th>
    <th>Total</th>
    <th>Payment</th>
    <th>Delivery</th>
    <th>Action</th>
</tr>
</thead>
<tbody>

@foreach($orders as $order)
<tr>
<td><strong>#{{ $order->id }}</strong></td>
<td>{{ \Carbon\Carbon::parse($order->created_at)->format('M d, Y') }}</td>
<td>₱{{ number_format($order->total,2) }}</td>

<td>
<span class="status-badge 
    {{ strtolower($order->payment_status) == 'pending' ? 'payment-pending' : '' }}
    {{ strtolower($order->payment_status) == 'paid' ? 'payment-paid' : '' }}
    {{ strtolower($order->payment_status) == 'failed' ? 'payment-failed' : '' }}">
    {{ $order->payment_status }}
</span>
</td>

<td>
<span class="status-badge 
    {{ strtolower($order->delivery_status) == 'pending' ? 'delivery-pending' : '' }}
    {{ strtolower($order->delivery_status) == 'out for delivery' ? 'delivery-out-for-delivery' : '' }}
    {{ strtolower($order->delivery_status) == 'delivered' ? 'delivery-delivered' : '' }}
    {{ strtolower($order->delivery_status) == 'cancelled' ? 'delivery-cancelled' : '' }}">
    {{ $order->delivery_status }}
</span>
</td>

<td>
<button class="btn btn-gradient btn-sm"
        data-bs-toggle="modal"
        data-bs-target="#orderModal{{ $order->id }}">
<i class="fas fa-eye"></i> <span class="d-none d-sm-inline">View</span>
</button>
</td>
</tr>
@endforeach

</tbody>
</table>
</div>

@else
<div class="text-center text-muted py-5">
    <i class="fas fa-receipt fa-3x mb-3" style="color: var(--pink); opacity: 0.6;"></i>
    <p class="mb-0">No purchase history found.</p>
</div>
@endif
</div>
</div>
</div>

</div>
</div>

<!-- ================= VIEW MODALS ================= -->
@foreach($orders as $order)
@php
    $orderItems = DB::table('order_item')
        ->join('products','order_item.product_id','=','products.id')
        ->where('order_item.order_id',$order->id)
        ->select('products.name','order_item.quantity','order_item.price')
        ->get();
@endphp

<div class="modal fade" id="orderModal{{ $order->id }}" tabindex="-1">
<div class="modal-dialog modal-lg modal-dialog-centered">
<div class="modal-content">

<div class="modal-header" style="background:var(--pink);">
<h5 class="modal-title text-white">Order #{{ $order->id }}</h5>
<button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">

<p><strong>Customer:</strong> {{ $order->customer_name }}</p>
<p><strong>Address:</strong> {{ $order->address }}</p>
<p><strong>Date:</strong> {{ \Carbon\Carbon::parse($order->created_at)->format('M d, Y • h:i A') }}</p>

<hr>

<div class="table-responsive">
<table class="table table-sm">
<thead>
<tr>
<th>Product</th>
<th>Qty</th>
<th>Price</th>
<th>Subtotal</th>
</tr>
</thead>
<tbody>
@foreach($orderItems as $item)
<tr>
<td>{{ $item->name }}</td>
<td>{{ $item->quantity }}</td>
<td>₱{{ number_format($item->price,2) }}</td>
<td>₱{{ number_format($item->price * $item->quantity,2) }}</td>
</tr>
@endforeach
</tbody>
<tfoot>
<tr>
<th colspan="3" class="text-end">Total</th>
<th>₱{{ number_format($order->total,2) }}</th>
</tr>
</tfoot>
</table>
</div>

</div>

<div class="modal-footer">
<button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
</div>

</div>
</div>
</div>
@endforeach

@if(session('success'))
    <div class="toast-notification success" style="position: fixed; top: 20px; right: 20px; padding: 1rem 1.5rem; border-radius: 12px; color: white; font-weight: 500; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15); z-index: 9999; animation: slideIn 0.3s ease; background: linear-gradient(135deg, #34d399, #10b981);">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div class="toast-notification error" style="position: fixed; top: 20px; right: 20px; padding: 1rem 1.5rem; border-radius: 12px; color: white; font-weight: 500; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15); z-index: 9999; animation: slideIn 0.3s ease; background: linear-gradient(135deg, #f87171, #ef4444);">
        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
    </div>
@endif

@include('components.footer')

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
// Auto-hide toast notifications
document.addEventListener('DOMContentLoaded', function() {
    const toasts = document.querySelectorAll('.toast-notification');
    toasts.forEach(toast => {
        setTimeout(() => {
            toast.style.transition = 'opacity 0.5s';
            toast.style.opacity = '0';
            setTimeout(() => toast.remove(), 500);
        }, 3000);
    });
    
    // Better table row interaction on mobile
    const tableRows = document.querySelectorAll('.table tbody tr');
    tableRows.forEach(row => {
        row.addEventListener('click', function(e) {
            if (window.innerWidth <= 768 && !e.target.closest('button')) {
                const button = this.querySelector('button[data-bs-toggle="modal"]');
                if (button) button.click();
            }
        });
    });
});
</script>

</body>
</html>