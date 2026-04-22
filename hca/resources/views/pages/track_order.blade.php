<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Order - HookcraftAvenue</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="{{ asset('asset/stylesnav.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
   <!-- Navbar -->
  @include('components.navbar')

    <div class="container py-5">
        <div class="row g-4">
            <!-- Sidebar -->
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
                        <a href="{{ route('profile.purchase-history') }}" class="list-group-item list-group-item-action border-0 py-3">
                            <i class="fas fa-history me-3"></i> Purchase History
                        </a>
                        <a href="{{ route('profile.track-order') }}" class="list-group-item list-group-item-action active border-0 py-3">
                            <i class="fas fa-truck me-3"></i> Track Order
                        </a>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-lg-9">
                <div class="card shadow-sm border-0 main-card">
                    <div class="card-header border-0 p-4 gradient-header">
                        <h4 class="mb-0 text-white fw-bold">
                            <i class="fas fa-map-marked-alt me-2"></i> Track Your Orders
                        </h4>
                        <p class="mb-0 text-white opacity-75 small mt-1">Monitor your order status and live delivery location</p>
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
                                @endphp

                                <div class="order-card mb-4">
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
                                                <h5 class="mb-0 text-success fw-bold">
                                                    ₱{{ number_format($order->grand_total ?? $order->total, 2) }}
                                                </h5>
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
                                            <div class="col-md-8">
                                                <div class="info-section">
                                                    <h6 class="section-title mb-3">
                                                        <i class="fas fa-info-circle me-2"></i>Delivery Information
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
                                            <div class="col-md-4">
                                                <div class="status-section">
                                                    <div class="status-item mb-3">
                                                        <small class="text-muted d-block mb-2">Payment Status</small>
                                                        @if($order->payment_status == 'Paid')
                                                            <span class="status-badge status-success">
                                                                <i class="fas fa-check-circle me-1"></i> Paid
                                                            </span>
                                                        @elseif($order->payment_status == 'Pending')
                                                            <span class="status-badge status-warning">
                                                                <i class="fas fa-clock me-1"></i> Pending
                                                            </span>
                                                        @elseif($order->payment_status == 'Unsuccessful')
                                                            <span class="status-badge status-danger">
                                                                <i class="fas fa-times-circle me-1"></i> Unsuccessful
                                                            </span>
                                                        @else
                                                            <span class="status-badge status-secondary">{{ $order->payment_status }}</span>
                                                        @endif
                                                    </div>
                                                    <div class="status-item">
                                                        <small class="text-muted d-block mb-2">Delivery Status</small>
                                                        @if($order->delivery_status == 'Delivered')
                                                            <span class="status-badge status-success">
                                                                <i class="fas fa-check-double me-1"></i> Delivered
                                                            </span>
                                                        @elseif($order->delivery_status == 'Out for Delivery')
                                                            <span class="status-badge status-info">
                                                                <i class="fas fa-shipping-fast me-1"></i> Out for Delivery
                                                            </span>
                                                        @elseif($order->delivery_status == 'Cancelled')
                                                            <span class="status-badge status-danger">
                                                                <i class="fas fa-ban me-1"></i> Cancelled
                                                            </span>
                                                        @else
                                                            <span class="status-badge status-warning">
                                                                <i class="fas fa-hourglass-half me-1"></i> Pending
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- ── PRODUCT ITEMS GALLERY ── --}}
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
                                                     onclick="openTrackLightbox('{{ $imgSrc ?? '' }}', '{{ addslashes($displayName) }}')">
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
                                                    <div class="products-more" onclick="openTrackModal({{ $order->id }})">
                                                        +{{ $orderItems->count() - 5 }}<br>more
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Progress Bar -->
                                        <div class="progress-section mt-4">
                                            @php
                                                $progress = 0;
                                                $progressClass = 'progress-warning';
                                                $statusText = 'Order Placed';
                                                if($order->delivery_status == 'Pending') {
                                                    $progress = 25; $progressClass = 'progress-warning'; $statusText = 'Processing';
                                                } elseif($order->delivery_status == 'Out for Delivery') {
                                                    $progress = 75; $progressClass = 'progress-info'; $statusText = 'Out for Delivery';
                                                } elseif($order->delivery_status == 'Delivered') {
                                                    $progress = 100; $progressClass = 'progress-success'; $statusText = 'Delivered';
                                                } elseif($order->delivery_status == 'Cancelled') {
                                                    $progress = 100; $progressClass = 'progress-danger'; $statusText = 'Cancelled';
                                                }
                                            @endphp
                                            <div class="custom-progress">
                                                <div class="progress-bar-custom {{ $progressClass }}" style="width: {{ $progress }}%">
                                                    <span class="progress-text">{{ $statusText }} • {{ $progress }}%</span>
                                                </div>
                                            </div>
                                            <div class="progress-milestones">
                                                <div class="milestone {{ $progress >= 25 ? 'active' : '' }}">
                                                    <i class="fas fa-box"></i><span>Placed</span>
                                                </div>
                                                <div class="milestone {{ $progress >= 50 ? 'active' : '' }}">
                                                    <i class="fas fa-cog"></i><span>Processing</span>
                                                </div>
                                                <div class="milestone {{ $progress >= 75 ? 'active' : '' }}">
                                                    <i class="fas fa-truck"></i><span>Shipping</span>
                                                </div>
                                                <div class="milestone {{ $progress >= 100 ? 'active' : '' }}">
                                                    <i class="fas {{ $order->delivery_status == 'Cancelled' ? 'fa-times' : 'fa-home' }}"></i>
                                                    <span>{{ $order->delivery_status == 'Cancelled' ? 'Cancelled' : 'Delivered' }}</span>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- ══════════════════════════════════════════
                                             ESTIMATED DELIVERY TIMELINE — ORDER CARD
                                             Shows only for Pending / Out for Delivery
                                             Collapsed by default, click to expand
                                        ═══════════════════════════════════════════ --}}
                                        @if($order->delivery_status !== 'Delivered' && $order->delivery_status !== 'Cancelled')
                                        <div class="track-eta-card mt-3" id="card-eta-{{ $order->id }}">
                                            {{-- Header (toggle) --}}
                                            <div class="track-eta-header"
                                                 onclick="toggleTrackETA({{ $order->id }})"
                                                 role="button" aria-expanded="false">
                                                <div class="track-eta-header-left">
                                                    <div class="track-eta-title">
                                                        <i class="fas fa-calendar-check"></i>
                                                        Estimated Delivery
                                                    </div>
                                                    <span class="track-eta-badge" id="card-eta-badge-{{ $order->id }}">
                                                        Calculating…
                                                    </span>
                                                </div>
                                                <i class="fas fa-chevron-down track-eta-chevron" id="card-eta-chevron-{{ $order->id }}"></i>
                                            </div>

                                            {{-- Collapsible body --}}
                                            <div class="track-eta-body" id="card-eta-body-{{ $order->id }}">

                                                {{-- Progress bar --}}
                                                <div class="track-eta-progress-wrap">
                                                    <div class="track-eta-progress-labels">
                                                        <span>Order Placed</span>
                                                        <span>Production</span>
                                                        <span>Delivered</span>
                                                    </div>
                                                    <div class="track-eta-progress-bg">
                                                        <div class="track-eta-progress-fill" id="card-eta-fill-{{ $order->id }}"></div>
                                                    </div>
                                                </div>

                                                {{-- Steps --}}
                                                <div class="track-eta-steps">

                                                    <div class="track-eta-step">
                                                        <div class="track-eta-dot dot-order">
                                                            <i class="fas fa-check"></i>
                                                        </div>
                                                        <div class="track-eta-info">
                                                            <div class="track-eta-label">Order Confirmed</div>
                                                            <div class="track-eta-sub">Received &amp; processing started</div>
                                                            <div class="track-eta-date date-blue" id="card-eta-order-{{ $order->id }}">
                                                                <i class="fas fa-calendar"></i> <span>—</span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="track-eta-step">
                                                        <div class="track-eta-dot dot-produce dot-active">
                                                            <i class="fas fa-cut"></i>
                                                        </div>
                                                        <div class="track-eta-info">
                                                            <div class="track-eta-label">Crafting / Production</div>
                                                            <div class="track-eta-sub">Handmade with love — 1 to 2 weeks</div>
                                                            <div class="track-eta-date" id="card-eta-prod-{{ $order->id }}">
                                                                <i class="fas fa-calendar-week"></i> <span>—</span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="track-eta-step">
                                                        <div class="track-eta-dot dot-deliver">
                                                            <i class="fas fa-truck"></i>
                                                        </div>
                                                        <div class="track-eta-info">
                                                            <div class="track-eta-label">Out for Delivery</div>
                                                            <div class="track-eta-sub" id="card-eta-del-sub-{{ $order->id }}">
                                                                Shipped right after production · 1–3 days
                                                            </div>
                                                            <div class="track-eta-date date-green" id="card-eta-del-{{ $order->id }}">
                                                                <i class="fas fa-home"></i> <span>—</span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>

                                                {{-- Summary bar --}}
                                                <div class="track-eta-summary">
                                                    <div class="track-eta-summary-label">
                                                        <i class="fas fa-calendar-heart" style="color:#f97316;"></i>
                                                        Expected Arrival Window
                                                    </div>
                                                    <div>
                                                        <div class="track-eta-summary-dates" id="card-eta-window-{{ $order->id }}">Calculating…</div>
                                                        <div class="track-eta-summary-range"  id="card-eta-range-{{ $order->id }}"></div>
                                                    </div>
                                                </div>

                                                <div class="track-eta-disclaimer">
                                                    <i class="fas fa-info-circle" style="color:#fb8c00;flex-shrink:0;margin-top:1px;"></i>
                                                    <span>Dates are estimates. You'll be notified via SMS/email once your order ships.</span>
                                                </div>

                                            </div>{{-- /track-eta-body --}}
                                        </div>{{-- /track-eta-card --}}
                                        @elseif($order->delivery_status === 'Delivered')
                                        {{-- Delivered: show compact delivered chip --}}
                                        <div class="track-eta-delivered-chip mt-3">
    <i class="fas fa-check-circle me-2"></i>
    Order delivered on
    {{-- FIX: null-safe updated_at fallback to created_at --}}
    <strong>&nbsp;{{ !empty($order->updated_at) ? \Carbon\Carbon::parse($order->updated_at)->format('M d, Y') : \Carbon\Carbon::parse($order->created_at)->format('M d, Y') }}</strong>
</div>
                                        @endif

                                        {{-- ── MINI MAP ON CARD ── --}}
                                        @if($order->delivery_status !== 'Cancelled')
                                        <div class="mini-map-wrapper mt-3" onclick="event.stopPropagation()">
                                            <div class="mini-map-header">
                                                <div class="mini-map-title">
                                                    @if($order->delivery_status == 'Out for Delivery')
                                                        <span class="live-dot"></span> Live Delivery Tracking
                                                    @elseif($order->delivery_status == 'Delivered')
                                                        <i class="fas fa-check-circle me-1" style="color:#48BB78;"></i> Delivered Location
                                                    @else
                                                        <i class="fas fa-map-marker-alt me-1"></i> Delivery Destination
                                                    @endif
                                                </div>
                                                <div class="d-flex align-items-center gap-2">
                                                    <span class="map-status-pill" id="cust-mini-status-{{ $order->id }}">Loading…</span>
                                                </div>
                                            </div>
                                            <div id="cust-mini-map-{{ $order->id }}" class="mini-map-canvas"></div>
                                            <div class="mini-map-footer">
                                                <span>
                                                    <i class="fas fa-road me-1" style="color:var(--primary-pink-dark);"></i>
                                                    <span id="cust-mini-dist-{{ $order->id }}">—</span>
                                                </span>
                                                <span>
                                                    <i class="fas fa-clock me-1" style="color:#f59e0b;"></i>
                                                    <span id="cust-mini-eta-{{ $order->id }}">—</span>
                                                </span>
                                               
                                            </div>
                                        </div>
                                        @endif

                                    </div>

                                    <div class="order-footer">
                                        <div class="d-flex gap-2 justify-content-end">
                                            @if($order->delivery_status === 'Pending')
                                                <form method="POST" action="{{ route('orders.cancel', ['order' => $order->id]) }}" 
                                                      id="cancelForm{{ $order->id }}" class="d-inline"
                                                      onsubmit="return confirmCancel('{{ $order->id }}')">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-outline-danger btn-action" 
                                                            id="cancelBtn{{ $order->id }}">
                                                        <i class="fas fa-times me-1"></i> Cancel Order
                                                    </button>
                                                </form>
                                            @elseif(!in_array($order->delivery_status, ['Delivered', 'Cancelled']))
                                                <button class="btn btn-outline-danger btn-action" disabled>
                                                    <i class="fas fa-times me-1"></i> Cancel Order
                                                </button>
                                            @endif
                                            <button class="btn btn-gradient btn-action" onclick="openTrackModal({{ $order->id }})">
                                                <i class="fas fa-route me-1"></i> View Details & Map
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                {{-- ═══════════════════════════════════
                                     TRACK ORDER DETAIL MODAL
                                ════════════════════════════════════ --}}
                                <div class="delivery-modal-overlay" id="track-overlay-{{ $order->id }}"
                                     onclick="closeTrackModal({{ $order->id }})">
                                    <div class="delivery-modal" onclick="event.stopPropagation()">

                                        <div class="dmodal-header">
                                            <div class="dmodal-header-left">
                                                <span class="dmodal-order-id">#{{ $order->id }}</span>
                                                <span class="dmodal-title">{{ $order->customer_name }}</span>
                                                @if($order->delivery_status == 'Delivered')
                                                    <span class="badge-modern badge-success"><i class="fas fa-check-circle"></i> Delivered</span>
                                                @elseif($order->delivery_status == 'Out for Delivery')
                                                    <span class="badge-modern badge-info"><i class="fas fa-shipping-fast"></i> Out for Delivery</span>
                                                @elseif($order->delivery_status == 'Cancelled')
                                                    <span class="badge-modern badge-danger"><i class="fas fa-times-circle"></i> Cancelled</span>
                                                @else
                                                    <span class="badge-modern badge-warning"><i class="fas fa-clock"></i> Pending</span>
                                                @endif
                                            </div>
                                            <button class="dmodal-close" onclick="closeTrackModal({{ $order->id }})">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>

                                        <div class="dmodal-body">

                                            {{-- Big Map --}}
                                            @if($order->delivery_status !== 'Cancelled')
                                            <div class="modal-map-wrapper">
                                                <div class="modal-map-header">
                                                    <div class="modal-map-title">
                                                        @if($order->delivery_status == 'Out for Delivery')
                                                            <span class="live-dot"></span> Live Route Tracking
                                                        @elseif($order->delivery_status == 'Delivered')
                                                            <i class="fas fa-check-circle me-1" style="color:#48BB78;"></i> Delivered Location
                                                        @else
                                                            <i class="fas fa-map-marker-alt me-1"></i> Delivery Location
                                                        @endif
                                                        <span class="modal-map-status" id="cust-modal-map-status-{{ $order->id }}">Loading map…</span>
                                                    </div>
                                                    <span id="cust-modal-maps-link-{{ $order->id }}"></span>
                                                </div>
                                                <div id="cust-modal-map-{{ $order->id }}" class="modal-map-canvas"></div>
                                                <div class="modal-map-footer">
                                                    <div class="modal-map-stat">
                                                        <i class="fas fa-road" style="color:var(--primary-pink-dark);"></i>
                                                        Distance: <span id="cust-modal-dist-{{ $order->id }}">—</span>
                                                    </div>
                                                    @if($order->delivery_status == 'Out for Delivery')
                                                    <div class="modal-map-stat">
                                                        <i class="fas fa-clock" style="color:#f59e0b;"></i>
                                                        ETA: <span id="cust-modal-eta-{{ $order->id }}">—</span>
                                                    </div>
                                                    @endif
                                                    <div class="modal-map-stat">
                                                        <i class="fas fa-map-marker-alt" style="color:#ef4444;"></i>
                                                        <span style="font-size:0.75rem;color:#6b7280;max-width:220px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                                                            {{ $order->address }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>

                                            @if($order->delivery_status == 'Out for Delivery')
                                            <div class="route-steps" id="cust-route-steps-{{ $order->id }}">
                                                <div class="route-steps-title"><i class="fas fa-route me-2"></i>Turn-by-Turn Directions</div>
                                                <div id="cust-steps-list-{{ $order->id }}">
                                                    <div class="step-item">
                                                        <span style="color:#9ca3af;font-size:0.82rem;">Getting directions…</span>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                            @endif

                                            {{-- ══════════════════════════════════════════
                                                 ESTIMATED DELIVERY TIMELINE — MODAL
                                                 Only for Pending / Out for Delivery
                                            ═══════════════════════════════════════════ --}}
                                            @if($order->delivery_status !== 'Delivered' && $order->delivery_status !== 'Cancelled')
                                            <div class="modal-eta-card mb-4" id="modal-eta-{{ $order->id }}">
                                                {{-- Header (toggle) --}}
                                                <div class="modal-eta-header"
                                                     onclick="toggleModalETA({{ $order->id }})"
                                                     role="button" aria-expanded="false">
                                                    <div class="modal-eta-header-left">
                                                        <div class="modal-eta-title">
                                                            <i class="fas fa-calendar-check"></i>
                                                            Estimated Delivery Schedule
                                                        </div>
                                                        <span class="modal-eta-badge" id="modal-eta-badge-{{ $order->id }}">
                                                            Calculating…
                                                        </span>
                                                    </div>
                                                    <i class="fas fa-chevron-down modal-eta-chevron" id="modal-eta-chevron-{{ $order->id }}"></i>
                                                </div>

                                                {{-- Collapsible body --}}
                                                <div class="modal-eta-body" id="modal-eta-body-{{ $order->id }}">

                                                    <div class="modal-eta-progress-wrap">
                                                        <div class="modal-eta-progress-labels">
                                                            <span>Order Placed</span>
                                                            <span>Production</span>
                                                            <span>Delivered</span>
                                                        </div>
                                                        <div class="modal-eta-progress-bg">
                                                            <div class="modal-eta-progress-fill" id="modal-eta-fill-{{ $order->id }}"></div>
                                                        </div>
                                                    </div>

                                                    <div class="modal-eta-steps">

                                                        <div class="modal-eta-step">
                                                            <div class="meta-dot dot-order">
                                                                <i class="fas fa-check"></i>
                                                            </div>
                                                            <div class="meta-info">
                                                                <div class="meta-label">Order Confirmed</div>
                                                                <div class="meta-sub">Received &amp; processing started</div>
                                                                <div class="meta-date date-blue" id="modal-eta-order-{{ $order->id }}">
                                                                    <i class="fas fa-calendar"></i> <span>—</span>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="modal-eta-step">
                                                            <div class="meta-dot dot-produce dot-active">
                                                                <i class="fas fa-cut"></i>
                                                            </div>
                                                            <div class="meta-info">
                                                                <div class="meta-label">Crafting / Production</div>
                                                                <div class="meta-sub">Handmade with love — 1 to 2 weeks</div>
                                                                <div class="meta-date" id="modal-eta-prod-{{ $order->id }}">
                                                                    <i class="fas fa-calendar-week"></i> <span>—</span>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="modal-eta-step">
                                                            <div class="meta-dot dot-deliver">
                                                                <i class="fas fa-truck"></i>
                                                            </div>
                                                            <div class="meta-info">
                                                                <div class="meta-label">Out for Delivery</div>
                                                                <div class="meta-sub" id="modal-eta-del-sub-{{ $order->id }}">
                                                                    Shipped right after production · 1–3 days
                                                                </div>
                                                                <div class="meta-date date-green" id="modal-eta-del-{{ $order->id }}">
                                                                    <i class="fas fa-home"></i> <span>—</span>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>

                                                    <div class="modal-eta-summary">
                                                        <div class="modal-eta-summary-label">
                                                            <i class="fas fa-calendar-heart" style="color:#f97316;"></i>
                                                            Expected Arrival Window
                                                        </div>
                                                        <div>
                                                            <div class="modal-eta-summary-dates" id="modal-eta-window-{{ $order->id }}">Calculating…</div>
                                                            <div class="modal-eta-summary-range"  id="modal-eta-range-{{ $order->id }}"></div>
                                                        </div>
                                                    </div>

                                                    <div class="modal-eta-disclaimer">
                                                        <i class="fas fa-info-circle" style="color:#fb8c00;flex-shrink:0;margin-top:1px;"></i>
                                                        <span>Dates are estimates. Production may vary by order volume. You'll be notified via SMS/email once your order ships.</span>
                                                    </div>

                                                </div>
                                            </div>
                                            @elseif($order->delivery_status === 'Delivered')
                                            <div class="track-eta-delivered-chip mb-4">
                                                <i class="fas fa-check-circle me-2"></i>
                                                Order successfully delivered on
                                                {{-- FIX: null-safe updated_at fallback to created_at --}}
                                                <strong>{{ !empty($order->updated_at) ? \Carbon\Carbon::parse($order->updated_at)->format('M d, Y') : \Carbon\Carbon::parse($order->created_at)->format('M d, Y') }}</strong>
                                            </div>
                                            @endif

                                            {{-- Timeline --}}
                                            <div class="timeline-modern mb-4">
                                                <div class="timeline-item-modern {{ $order->delivery_status != 'Cancelled' ? 'completed' : 'cancelled' }}">
                                                    <div class="timeline-marker-modern"><i class="fas fa-check"></i></div>
                                                    <div class="timeline-content-modern">
                                                        <div class="timeline-badge"><i class="fas fa-shopping-cart"></i></div>
                                                        <h6 class="fw-bold mb-1">Order Placed</h6>
                                                        <small class="text-muted">{{ \Carbon\Carbon::parse($order->created_at)->format('M d, Y • h:i A') }}</small>
                                                        <p class="small text-muted mb-0 mt-1">Your order has been received and confirmed</p>
                                                    </div>
                                                </div>
                                                <div class="timeline-item-modern {{ in_array($order->delivery_status, ['Out for Delivery', 'Delivered']) ? 'completed' : ($order->delivery_status == 'Cancelled' ? 'cancelled' : '') }}">
                                                    <div class="timeline-marker-modern"><i class="fas fa-check"></i></div>
                                                    <div class="timeline-content-modern">
                                                        <div class="timeline-badge"><i class="fas fa-cogs"></i></div>
                                                        <h6 class="fw-bold mb-1">Processing</h6>
                                                        <small class="text-muted">Order preparation in progress</small>
                                                        <p class="small text-muted mb-0 mt-1">Your items are being carefully prepared</p>
                                                    </div>
                                                </div>
                                                <div class="timeline-item-modern {{ in_array($order->delivery_status, ['Out for Delivery', 'Delivered']) ? 'completed' : ($order->delivery_status == 'Cancelled' ? 'cancelled' : '') }}">
                                                    <div class="timeline-marker-modern"><i class="fas fa-check"></i></div>
                                                    <div class="timeline-content-modern">
                                                        <div class="timeline-badge"><i class="fas fa-shipping-fast"></i></div>
                                                        <h6 class="fw-bold mb-1">Out for Delivery</h6>
                                                        <small class="text-muted">Package is on the way</small>
                                                        <p class="small text-muted mb-0 mt-1">Your order is being delivered to your address</p>
                                                    </div>
                                                </div>
                                                <div class="timeline-item-modern {{ $order->delivery_status == 'Delivered' ? 'completed' : ($order->delivery_status == 'Cancelled' ? 'cancelled' : '') }}">
                                                    <div class="timeline-marker-modern">
                                                        <i class="fas {{ $order->delivery_status == 'Delivered' ? 'fa-check' : ($order->delivery_status == 'Cancelled' ? 'fa-times' : 'fa-clock') }}"></i>
                                                    </div>
                                                    <div class="timeline-content-modern">
                                                        <div class="timeline-badge"><i class="fas {{ $order->delivery_status == 'Cancelled' ? 'fa-ban' : 'fa-home' }}"></i></div>
                                                        <h6 class="fw-bold mb-1">{{ $order->delivery_status == 'Cancelled' ? 'Order Cancelled' : 'Delivered' }}</h6>
                                                        <small class="text-muted">
                                                            {{ $order->delivery_status == 'Delivered' ? 'Order successfully delivered' : ($order->delivery_status == 'Cancelled' ? 'Order has been cancelled' : 'Awaiting delivery') }}
                                                        </small>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Delivery Info --}}
                                            <div class="delivery-info-card">
                                                <h6 class="fw-bold mb-3">
                                                    <i class="fas fa-map-marker-alt me-2"></i>Delivery Information
                                                </h6>
                                                <div class="info-grid">
                                                    <div class="info-item">
                                                        <i class="fas fa-user text-muted"></i>
                                                        <div>
                                                            <small class="text-muted d-block">Full Name</small>
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
                                                    <div class="info-item full-width">
                                                        <i class="fas fa-map-marker-alt text-muted"></i>
                                                        <div>
                                                            <small class="text-muted d-block">Delivery Address</small>
                                                            <span class="fw-medium">{{ $order->address }}</span>
                                                        </div>
                                                    </div>
                                                </div>

                                                @if($order->delivery_status == 'Out for Delivery' && $order->coordinator_id)
                                                <div class="delivery-staff-info mt-3 p-3 bg-white rounded-3 border">
                                                    <h6 class="fw-bold mb-2" style="color:var(--primary-pink-dark);">
                                                        <i class="fas fa-motorcycle me-2"></i>Your Rider
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
                                                @elseif($order->delivery_status == 'Delivered' && $order->coordinator_id)
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
                                                @elseif($order->delivery_status == 'Pending' && !$order->coordinator_id)
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
                                                            @else
                                                                <span class="text-muted">—</span>
                                                            @endif
                                                        </span>
                                                    </div>
                                                    <div class="price-row price-row-total">
                                                        <span class="price-label fw-bold">Grand Total</span>
                                                        <span class="price-value fw-bold text-success" style="font-size:1.05rem;">
                                                            ₱{{ number_format($order->grand_total ?? $order->total, 2) }}
                                                        </span>
                                                    </div>
                                                    <div class="price-row" style="margin-top:0.4rem;">
                                                        <span class="price-label text-muted" style="font-size:0.8rem;">
                                                            <i class="fas fa-credit-card me-1"></i>Payment Method
                                                        </span>
                                                        <span class="price-value text-muted" style="font-size:0.8rem;">
                                                            {{ $order->payment_method ?? 'COD' }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Product Items in Modal --}}
                                            <div class="modal-products-section mt-4">
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
                                                             onclick="openTrackLightbox('{{ $imgSrc }}','{{ addslashes($displayName) }}')"
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
                                </div>{{-- /delivery-modal-overlay --}}

                            @endforeach
                        @else
                            <div class="empty-state">
                                <div class="empty-icon">
                                    <i class="fas fa-box-open"></i>
                                </div>
                                <h5 class="fw-bold mb-2">No Orders to Track</h5>
                                <p class="text-muted mb-4">You haven't placed any orders yet. Start shopping to see your orders here!</p>
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
    <div class="img-lightbox" id="trackImgLightbox" onclick="closeTrackLightbox()">
        <span class="lightbox-close" onclick="closeTrackLightbox()">&times;</span>
        <img id="trackLightboxImg" src="" alt="">
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

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

    body { background: linear-gradient(135deg, #e7c9cf 50%, #beb2b2 100%); min-height: 100vh; }

    /* Profile */
    .profile-card { border-radius: 16px; overflow: hidden; transition: transform 0.3s ease; max-width: 280px; }
    .profile-card:hover { transform: translateY(-5px); }
    .profile-image-wrapper { position: relative; display: inline-block; }
    .profile-image { width: 100px; height: 100px; object-fit: cover; border: 4px solid var(--primary-pink); box-shadow: 0 4px 15px rgba(255,182,193,0.3); }
    .profile-badge { position: absolute; bottom: 5px; right: 5px; background: var(--success); color: white; width: 28px; height: 28px; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 3px solid white; font-size: 12px; }
    .list-group-item { transition: all 0.3s ease; font-weight: 500; }
    .list-group-item:hover:not(.active) { background-color: #fff5f7; color: var(--primary-pink); }
    .list-group-item.active { background: linear-gradient(135deg, var(--gradient-start), var(--gradient-end)); color: white; font-weight: 600; }

    /* Cards */
    .main-card { border-radius: 20px; overflow: hidden; }
    .gradient-header { background: linear-gradient(135deg, var(--gradient-start), var(--gradient-end)); }
    .order-card { background: white; border-radius: 16px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.08); transition: all 0.3s ease; }
    .order-card:hover { box-shadow: 0 8px 24px rgba(0,0,0,0.12); transform: translateY(-2px); }
    .order-header { padding: 1.5rem; background: linear-gradient(135deg, #fff5f7, #ffffff); border-bottom: 1px solid #f0f0f0; }
    .order-icon { width: 50px; height: 50px; background: linear-gradient(135deg, var(--gradient-start), var(--gradient-end)); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: white; font-size: 20px; margin-right: 1rem; }
    .order-body { padding: 1.5rem; }
    .order-footer { padding: 1rem 1.5rem; background: #f8f9fa; border-top: 1px solid #f0f0f0; }

    /* Info */
    .info-section { background: #f8f9fa; padding: 1.5rem; border-radius: 12px; }
    .section-title { color: #333; font-size: 0.95rem; font-weight: 600; }
    .info-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem; }
    .info-item { display: flex; gap: 0.75rem; align-items: start; }
    .info-item.full-width { grid-column: 1 / -1; }
    .info-item i { font-size: 18px; margin-top: 2px; }

    /* Badges */
    .status-badge { display: inline-flex; align-items: center; padding: 0.5rem 1rem; border-radius: 50px; font-size: 0.875rem; font-weight: 600; }
    .status-success { background: #d1fae5; color: #065f46; }
    .status-warning { background: #fef3c7; color: #92400e; }
    .status-danger  { background: #fee2e2; color: #991b1b; }
    .status-info    { background: #dbeafe; color: #1e40af; }
    .status-secondary { background: #e5e7eb; color: #374151; }

    /* Product gallery */
    .products-section { padding: 0; }
    .products-section-title { font-size: 0.82rem; font-weight: 700; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.75rem; display: flex; align-items: center; gap: 6px; }
    .products-scroll { display: flex; gap: 0.75rem; overflow-x: auto; padding-bottom: 4px; scrollbar-width: none; }
    .products-scroll::-webkit-scrollbar { display: none; }
    .product-thumb { flex-shrink: 0; width: 88px; border-radius: 12px; overflow: hidden; border: 2px solid #f0f0f0; background: #f8f9fa; cursor: pointer; position: relative; transition: all 0.25s ease; box-shadow: 0 2px 8px rgba(0,0,0,0.06); }
    .product-thumb:hover { transform: translateY(-4px) scale(1.03); box-shadow: 0 8px 20px rgba(0,0,0,0.14); border-color: var(--primary-pink); }
    .product-thumb img { width: 88px; height: 88px; object-fit: cover; display: block; }
    .product-thumb-label { padding: 4px 6px; font-size: 0.6rem; font-weight: 500; color: #374151; line-height: 1.2; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; background: #fff; }
    .product-qty-badge { position: absolute; top: 4px; right: 4px; background: linear-gradient(135deg,var(--gradient-start),var(--gradient-end)); color: #fff; font-size: 0.55rem; font-weight: 700; padding: 2px 5px; border-radius: 8px; }
    .product-type-badge { position: absolute; top: 4px; left: 4px; font-size: 0.5rem; font-weight: 700; padding: 2px 5px; border-radius: 6px; text-transform: uppercase; letter-spacing: 0.04em; }
    .badge-custom  { background: rgba(245,158,11,0.9); color: #fff; }
    .badge-regular { background: rgba(107,114,128,0.75); color: #fff; }
    .products-more { flex-shrink: 0; width: 88px; height: 88px; border-radius: 12px; border: 2px dashed var(--primary-pink); background: #fff5f7; display: flex; align-items: center; justify-content: center; font-size: 0.78rem; font-weight: 700; color: var(--primary-pink-dark); cursor: pointer; transition: all 0.2s; text-align: center; line-height: 1.3; }
    .products-more:hover { background: #ffeef1; }

    /* Modal products */
    .modal-products-section { margin-bottom: 1.3rem; }
    .modal-products-title { font-size: 0.82rem; font-weight: 700; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.75rem; display: flex; align-items: center; gap: 6px; }
    .product-item-row { display: flex; align-items: center; gap: 1rem; padding: 0.85rem 1rem; background: #f8f9fa; border: 1px solid #f0f0f0; border-radius: 12px; margin-bottom: 0.6rem; transition: all 0.2s; }
    .product-item-row:hover { background: #fff5f7; transform: translateX(2px); }
    .product-item-img { width: 60px; height: 60px; border-radius: 10px; object-fit: cover; border: 2px solid #f0f0f0; flex-shrink: 0; cursor: pointer; transition: all 0.2s; }
    .product-item-img:hover { transform: scale(1.06); box-shadow: 0 4px 14px rgba(0,0,0,0.12); }
    .product-item-img-placeholder { width: 60px; height: 60px; border-radius: 10px; background: #fff5f7; border: 2px solid #f0f0f0; display: flex; align-items: center; justify-content: center; flex-shrink: 0; color: var(--primary-pink); font-size: 1.3rem; }
    .product-item-info { flex: 1; min-width: 0; }
    .product-item-name  { font-weight: 600; font-size: 0.88rem; color: var(--text-primary); margin-bottom: 3px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .product-item-type  { font-size: 0.65rem; font-weight: 700; padding: 2px 7px; border-radius: 6px; text-transform: uppercase; letter-spacing: 0.04em; display: inline-block; margin-bottom: 3px; }
    .type-custom  { background: rgba(245,158,11,0.12); color: #b45309; }
    .type-regular { background: rgba(107,114,128,0.1);  color: #4b5563; }
    .product-item-qty   { font-size: 0.76rem; color: var(--text-secondary); }
    .product-item-price { text-align: right; flex-shrink: 0; }
    .product-item-unit  { font-size: 0.72rem; color: var(--text-secondary); }
    .product-item-total { font-size: 1rem; font-weight: 700; color: var(--text-primary); }

    /* Lightbox */
    .img-lightbox { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.9); z-index: 99999; align-items: center; justify-content: center; padding: 20px; }
    .img-lightbox.show { display: flex; animation: fadeOverlay 0.2s ease; }
    .img-lightbox img { max-width: 90%; max-height: 85vh; border-radius: 12px; object-fit: contain; }
    .lightbox-close { position: absolute; top: 20px; right: 28px; color: rgba(255,255,255,0.7); font-size: 2.2rem; cursor: pointer; transition: 0.2s; line-height: 1; }
    .lightbox-close:hover { color: #fff; }

    /* Progress */
    .progress-section { background: #f8f9fa; padding: 1.5rem; border-radius: 12px; }
    .custom-progress { height: 40px; background: #e5e7eb; border-radius: 50px; overflow: hidden; box-shadow: inset 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 1.5rem; }
    .progress-bar-custom { height: 100%; display: flex; align-items: center; justify-content: center; transition: width 0.6s ease; position: relative; overflow: hidden; }
    .progress-bar-custom::before { content: ''; position: absolute; top: 0; left: -100%; width: 100%; height: 100%; background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent); animation: shimmer 2s infinite; }
    @keyframes shimmer { 0%{left:-100%} 100%{left:100%} }
    .progress-warning { background: linear-gradient(135deg, #fbbf24, #f59e0b); }
    .progress-info    { background: linear-gradient(135deg, #60a5fa, #3b82f6); }
    .progress-success { background: linear-gradient(135deg, #34d399, #10b981); }
    .progress-danger  { background: linear-gradient(135deg, #f87171, #ef4444); }
    .progress-text { color: white; font-weight: 600; font-size: 0.875rem; z-index: 1; }
    .progress-milestones { display: flex; justify-content: space-between; }
    .milestone { display: flex; flex-direction: column; align-items: center; gap: 0.5rem; opacity: 0.4; transition: all 0.3s ease; }
    .milestone.active { opacity: 1; }
    .milestone i { width: 40px; height: 40px; background: #e5e7eb; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 18px; transition: all 0.3s ease; }
    .milestone.active i { background: linear-gradient(135deg, var(--gradient-start), var(--gradient-end)); color: white; box-shadow: 0 4px 12px rgba(255,182,193,0.4); }
    .milestone span { font-size: 0.75rem; font-weight: 500; color: #6b7280; }
    .milestone.active span { color: #111827; font-weight: 600; }

    /* ════════════════════════════════════════════
       ESTIMATED DELIVERY TIMELINE — ORDER CARD
    ═══════════════════════════════════════════ */
    .track-eta-card {
        background: linear-gradient(135deg, #fffbf0, #fff8e8);
        border: 1.5px solid #f6c35a;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(246,195,90,0.15);
    }
    .track-eta-header {
        background: linear-gradient(135deg, #f97316, #ea580c);
        padding: 0.55rem 0.9rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 6px;
        cursor: pointer;
        user-select: none;
        transition: filter 0.18s;
    }
    .track-eta-header:hover { filter: brightness(1.07); }
    .track-eta-header-left { display: flex; align-items: center; gap: 7px; flex: 1; min-width: 0; flex-wrap: wrap; gap: 5px; }
    .track-eta-title { display: flex; align-items: center; gap: 7px; font-size: 0.8rem; font-weight: 800; color: #fff; white-space: nowrap; }
    .track-eta-badge { background: rgba(255,255,255,0.22); color: #fff; font-size: 0.66rem; font-weight: 700; padding: 2px 8px; border-radius: 20px; white-space: nowrap; }
    .track-eta-chevron { color: rgba(255,255,255,0.85); font-size: 0.75rem; flex-shrink: 0; transition: transform 0.28s cubic-bezier(0.4,0,0.2,1); }
    .track-eta-card.open .track-eta-chevron { transform: rotate(180deg); }
    .track-eta-body { padding: 0; max-height: 0; overflow: hidden; transition: max-height 0.38s cubic-bezier(0.4,0,0.2,1), padding 0.28s ease; }
    .track-eta-card.open .track-eta-body { max-height: 580px; padding: 0.75rem 0.9rem; }

    /* Shared ETA step styles (card + modal) */
    .track-eta-steps, .modal-eta-steps { display: flex; flex-direction: column; gap: 0; margin-bottom: 0.6rem; }
    .track-eta-step, .modal-eta-step { display: flex; align-items: flex-start; gap: 10px; position: relative; padding-bottom: 0.5rem; }
    .track-eta-step:last-child, .modal-eta-step:last-child { padding-bottom: 0; }
    .track-eta-step:not(:last-child)::before, .modal-eta-step:not(:last-child)::before {
        content: ''; position: absolute; left: 13px; top: 26px; bottom: 0; width: 2px;
        background: linear-gradient(to bottom, #fdba74, #fcd34d); border-radius: 2px;
    }
    .track-eta-dot, .meta-dot {
        width: 27px; height: 27px; border-radius: 50%; display: flex; align-items: center; justify-content: center;
        font-size: 0.68rem; flex-shrink: 0; border: 2.5px solid #fff;
        box-shadow: 0 2px 6px rgba(0,0,0,0.13); color: #fff; position: relative; z-index: 1;
    }
    .dot-order   { background: linear-gradient(135deg, #3b82f6, #1d4ed8); }
    .dot-produce { background: linear-gradient(135deg, #f97316, #c2410c); }
    .dot-deliver { background: linear-gradient(135deg, #22c55e, #15803d); }
    .dot-active  { animation: pulse-dot 1.5s ease-in-out infinite; }
    @keyframes pulse-dot {
        0%,100% { box-shadow: 0 2px 6px rgba(0,0,0,0.13), 0 0 0 0 rgba(249,115,22,0.4); }
        50%      { box-shadow: 0 2px 6px rgba(0,0,0,0.13), 0 0 0 6px rgba(249,115,22,0); }
    }
    .track-eta-info, .meta-info { flex: 1; min-width: 0; }
    .track-eta-label, .meta-label { font-size: 0.8rem; font-weight: 700; color: #7c2d12; line-height: 1.2; }
    .track-eta-sub,   .meta-sub   { font-size: 0.7rem; color: #9a3412; line-height: 1.3; margin-top: 1px; }
    .track-eta-date, .meta-date {
        font-size: 0.68rem; font-weight: 800; margin-top: 3px;
        display: inline-flex; align-items: center; gap: 4px;
        background: rgba(249,115,22,0.12); color: #c2410c;
        padding: 2px 8px; border-radius: 8px; border: 1px solid rgba(249,115,22,0.2);
    }
    .track-eta-date.date-green, .meta-date.date-green { background: rgba(34,197,94,0.1); color: #15803d; border-color: rgba(34,197,94,0.2); }
    .track-eta-date.date-blue,  .meta-date.date-blue  { background: rgba(59,130,246,0.1); color: #1d4ed8; border-color: rgba(59,130,246,0.2); }

    /* Progress bar (card) */
    .track-eta-progress-wrap { margin-bottom: 0.6rem; }
    .track-eta-progress-labels { display: flex; justify-content: space-between; font-size: 0.62rem; color: #b45309; font-weight: 600; margin-bottom: 3px; }
    .track-eta-progress-bg   { height: 5px; background: #fed7aa; border-radius: 10px; overflow: hidden; }
    .track-eta-progress-fill { height: 100%; border-radius: 10px; background: linear-gradient(90deg,#3b82f6 0%,#f97316 50%,#22c55e 100%); width: 0%; transition: width 1s ease; }

    /* Summary bar (card) */
    .track-eta-summary { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 6px; background: linear-gradient(135deg,#fff7ed,#fef3c7); border: 1px solid #fed7aa; border-radius: 9px; padding: 0.5rem 0.7rem; }
    .track-eta-summary-label  { font-size: 0.72rem; font-weight: 600; color: #92400e; display: flex; align-items: center; gap: 5px; }
    .track-eta-summary-dates  { font-size: 0.76rem; font-weight: 800; color: #c2410c; }
    .track-eta-summary-range  { font-size: 0.65rem; color: #b45309; font-weight: 500; }
    .track-eta-disclaimer { font-size: 0.68rem; color: #b45309; margin-top: 0.45rem; display: flex; align-items: flex-start; gap: 5px; line-height: 1.4; padding-top: 0.4rem; border-top: 1px dashed #fcd34d; }

    /* Delivered chip */
    .track-eta-delivered-chip { display: inline-flex; align-items: center; background: linear-gradient(135deg,#d1fae5,#a7f3d0); border: 1px solid #6ee7b7; border-radius: 10px; padding: 0.45rem 0.9rem; font-size: 0.8rem; color: #065f46; font-weight: 600; }

    /* ════════════════════════════════════════════
       ESTIMATED DELIVERY TIMELINE — MODAL
    ═══════════════════════════════════════════ */
    .modal-eta-card {
        background: linear-gradient(135deg, #fffbf0, #fff8e8);
        border: 1.5px solid #f6c35a;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(246,195,90,0.15);
    }
    .modal-eta-header {
        background: linear-gradient(135deg, #f97316, #ea580c);
        padding: 0.6rem 1rem;
        display: flex; align-items: center; justify-content: space-between; gap: 6px;
        cursor: pointer; user-select: none; transition: filter 0.18s;
    }
    .modal-eta-header:hover { filter: brightness(1.07); }
    .modal-eta-header-left { display: flex; align-items: center; gap: 7px; flex: 1; flex-wrap: wrap; gap: 5px; }
    .modal-eta-title { display: flex; align-items: center; gap: 7px; font-size: 0.85rem; font-weight: 800; color: #fff; white-space: nowrap; }
    .modal-eta-badge { background: rgba(255,255,255,0.22); color: #fff; font-size: 0.7rem; font-weight: 700; padding: 2px 9px; border-radius: 20px; white-space: nowrap; }
    .modal-eta-chevron { color: rgba(255,255,255,0.85); font-size: 0.8rem; flex-shrink: 0; transition: transform 0.28s cubic-bezier(0.4,0,0.2,1); }
    .modal-eta-card.open .modal-eta-chevron { transform: rotate(180deg); }
    .modal-eta-body { padding: 0; max-height: 0; overflow: hidden; transition: max-height 0.38s cubic-bezier(0.4,0,0.2,1), padding 0.28s ease; }
    .modal-eta-card.open .modal-eta-body { max-height: 580px; padding: 0.9rem 1rem; }

    /* Progress bar (modal) */
    .modal-eta-progress-wrap { margin-bottom: 0.6rem; }
    .modal-eta-progress-labels { display: flex; justify-content: space-between; font-size: 0.65rem; color: #b45309; font-weight: 600; margin-bottom: 3px; }
    .modal-eta-progress-bg   { height: 6px; background: #fed7aa; border-radius: 10px; overflow: hidden; }
    .modal-eta-progress-fill { height: 100%; border-radius: 10px; background: linear-gradient(90deg,#3b82f6 0%,#f97316 50%,#22c55e 100%); width: 0%; transition: width 1s ease; }

    /* Summary bar (modal) */
    .modal-eta-summary { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 6px; background: linear-gradient(135deg,#fff7ed,#fef3c7); border: 1px solid #fed7aa; border-radius: 9px; padding: 0.55rem 0.75rem; }
    .modal-eta-summary-label  { font-size: 0.75rem; font-weight: 600; color: #92400e; display: flex; align-items: center; gap: 5px; }
    .modal-eta-summary-dates  { font-size: 0.78rem; font-weight: 800; color: #c2410c; }
    .modal-eta-summary-range  { font-size: 0.68rem; color: #b45309; font-weight: 500; }
    .modal-eta-disclaimer { font-size: 0.7rem; color: #b45309; margin-top: 0.5rem; display: flex; align-items: flex-start; gap: 5px; line-height: 1.4; padding-top: 0.45rem; border-top: 1px dashed #fcd34d; }

    /* Maps */
    .mini-map-wrapper { border-radius: 12px; overflow: hidden; border: 1px solid var(--border-color); }
    .mini-map-header { display: flex; align-items: center; justify-content: space-between; padding: 8px 14px; background: linear-gradient(135deg, var(--gradient-start), var(--gradient-end)); color: #fff; flex-wrap: wrap; gap: 6px; }
    .mini-map-title { display: flex; align-items: center; gap: 7px; font-weight: 700; font-size: 0.82rem; }
    .live-dot { width: 8px; height: 8px; background: #fff; border-radius: 50%; animation: pulse-live 1.4s ease-in-out infinite; flex-shrink: 0; }
    @keyframes pulse-live { 0%{box-shadow:0 0 0 0 rgba(255,255,255,0.7)} 70%{box-shadow:0 0 0 7px rgba(255,255,255,0)} 100%{box-shadow:0 0 0 0 rgba(255,255,255,0)} }
    .map-status-pill { font-size: 0.7rem; background: rgba(255,255,255,0.25); padding: 2px 9px; border-radius: 20px; font-weight: 600; white-space: nowrap; }
    .mini-map-canvas { height: 180px; width: 100%; }
    .mini-map-footer { display: flex; align-items: center; justify-content: space-between; padding: 7px 14px; background: #f8f9fa; border-top: 1px solid var(--border-color); font-size: 0.76rem; color: var(--text-secondary); font-weight: 500; flex-wrap: wrap; gap: 4px; }

    /* Buttons */
    .btn-gradient { background: linear-gradient(135deg, var(--gradient-start), var(--gradient-end)); color: white; border: none; font-weight: 600; transition: all 0.3s ease; }
    .btn-gradient:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(255,182,193,0.4); color: white; }
    .btn-action { padding: 0.5rem 1.25rem; border-radius: 8px; font-weight: 600; transition: all 0.3s ease; }

    /* Modal */
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

    /* Modal map */
    .modal-map-wrapper { border-radius: 14px; overflow: hidden; border: 1px solid var(--border-color); margin-bottom: 1.3rem; }
    .modal-map-header { display: flex; align-items: center; justify-content: space-between; padding: 10px 15px; background: linear-gradient(135deg, var(--gradient-start), var(--gradient-end)); color: #fff; gap: 8px; flex-wrap: wrap; }
    .modal-map-title { display: flex; align-items: center; gap: 8px; font-weight: 700; font-size: 0.92rem; }
    .modal-map-status { font-weight: 400; font-size: 0.76rem; opacity: 0.85; }
    .modal-map-canvas { height: 360px; width: 100%; }
    .modal-map-footer { display: flex; align-items: center; justify-content: space-between; padding: 9px 15px; background: #f8f9fa; border-top: 1px solid var(--border-color); flex-wrap: wrap; gap: 8px; }
    .modal-map-stat { display: flex; align-items: center; gap: 6px; font-size: 0.83rem; font-weight: 600; color: var(--text-primary); }
    .modal-map-stat span { color: var(--text-secondary); font-weight: 400; }

    /* Badges in modal */
    .badge-modern { padding: 0.32rem 0.8rem; border-radius: 8px; font-size: 0.78rem; font-weight: 600; display: inline-flex; align-items: center; gap: 0.3rem; }
    .badge-success { background: rgba(16,185,129,0.15); color: var(--success); }
    .badge-warning { background: rgba(245,158,11,0.15); color: var(--warning); }
    .badge-danger  { background: rgba(239,68,68,0.15);  color: var(--danger);  }
    .badge-info    { background: rgba(59,130,246,0.15);  color: var(--info);    }

    /* Route steps */
    .route-steps { background: #f8f9fa; border: 1px solid var(--border-color); border-radius: 12px; padding: 13px; margin-bottom: 1.2rem; max-height: 200px; overflow-y: auto; }
    .route-steps-title { font-size: 0.78rem; font-weight: 700; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 9px; }
    .step-item { display: flex; align-items: flex-start; gap: 9px; padding: 6px 0; border-bottom: 1px solid var(--border-color); }
    .step-item:last-child { border-bottom: none; }
    .step-num { min-width: 22px; height: 22px; border-radius: 50%; background: linear-gradient(135deg, var(--gradient-start), var(--gradient-end)); color: #fff; font-size: 0.65rem; font-weight: 700; display: flex; align-items: center; justify-content: center; margin-top: 1px; flex-shrink: 0; }
    .step-text { font-size: 0.81rem; color: var(--text-primary); line-height: 1.4; }
    .step-dist { font-size: 0.72rem; color: var(--text-secondary); margin-top: 1px; }

    /* Timeline */
    .timeline-modern { position: relative; padding: 1rem 0; }
    .timeline-item-modern { position: relative; padding-left: 80px; padding-bottom: 2rem; }
    .timeline-item-modern::before { content: ''; position: absolute; left: 30px; top: 50px; width: 3px; height: calc(100% - 30px); background: linear-gradient(to bottom, #e5e7eb, #f3f4f6); }
    .timeline-item-modern:last-child::before { display: none; }
    .timeline-marker-modern { position: absolute; left: 18px; top: 15px; width: 28px; height: 28px; border-radius: 50%; background: white; border: 3px solid #e5e7eb; display: flex; align-items: center; justify-content: center; font-size: 12px; color: #9ca3af; z-index: 1; transition: all 0.3s ease; }
    .timeline-item-modern.completed .timeline-marker-modern { background: var(--success); border-color: var(--success); color: white; box-shadow: 0 0 0 4px rgba(16,185,129,0.2); }
    .timeline-item-modern.cancelled .timeline-marker-modern { background: var(--danger);  border-color: var(--danger);  color: white; box-shadow: 0 0 0 4px rgba(239,68,68,0.2); }
    .timeline-item-modern.completed::before { background: linear-gradient(to bottom, var(--success), #86efac); }
    .timeline-item-modern.cancelled::before { background: linear-gradient(to bottom, var(--danger), #fca5a5); }
    .timeline-content-modern { background: white; padding: 1.25rem; border-radius: 12px; border: 2px solid #f3f4f6; transition: all 0.3s ease; }
    .timeline-item-modern.completed .timeline-content-modern { border-color: #d1fae5; background: #f0fdf4; }
    .timeline-item-modern.cancelled .timeline-content-modern { border-color: #fee2e2; background: #fef2f2; }
    .timeline-badge { display: inline-flex; align-items: center; justify-content: center; width: 36px; height: 36px; background: linear-gradient(135deg, var(--gradient-start), var(--gradient-end)); border-radius: 10px; color: white; margin-bottom: 0.75rem; }

    /* Delivery info card */
    .delivery-info-card { background: #f8f9fa; padding: 1.5rem; border-radius: 12px; border: 2px solid #f0f0f0; }
    .delivery-staff-info { border-left: 4px solid var(--primary-pink) !important; }
    .staff-icon { width: 32px; height: 32px; background: linear-gradient(135deg, var(--gradient-start), var(--gradient-end)); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; flex-shrink: 0; }

    /* Price */
    .price-breakdown { background: white; border-radius: 10px; border: 1px solid #f0f0f0; padding: 1rem 1.25rem; }
    .price-row { display: flex; justify-content: space-between; align-items: center; padding: 0.35rem 0; border-bottom: 1px dashed #f0f0f0; font-size: 0.9rem; }
    .price-row:last-child { border-bottom: none; }
    .price-row-total { border-top: 2px solid #e5e7eb; border-bottom: none; margin-top: 0.35rem; padding-top: 0.65rem; }
    .price-label { color: #6b7280; }
    .price-value { color: #111827; }

    /* Empty state */
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
        .progress-milestones { flex-wrap: wrap; gap: 1rem; }
        .profile-card { max-width: 100%; margin-bottom: 1.5rem; }
        .delivery-modal { max-height: 96vh; }
        .modal-map-canvas { height: 240px; }
        .track-eta-summary, .modal-eta-summary { flex-direction: column; align-items: flex-start; }
    }
    </style>

    <script>
    // ─── STORE COORDINATES — Hipodromo, Cebu City ───
    const STORE_LAT = 10.314152;
    const STORE_LNG = 123.906935;

    // ─── ORDER MAP DATA ──────────────────────────────
    @php
        $mapOrders = collect($orders)
            ->whereNotIn('delivery_status', ['Cancelled'])
            ->map(function($o) {
                return [
                    'id'         => $o->id,
                    'address'    => $o->address,
                    'lat'        => isset($o->latitude)  && $o->latitude  ? (float)$o->latitude  : null,
                    'lng'        => isset($o->longitude) && $o->longitude ? (float)$o->longitude : null,
                    'status'     => $o->delivery_status,
                    'created_at' => $o->created_at,
                    'distance_km'=> isset($o->delivery_distance_km) ? (float)$o->delivery_distance_km : 0,
                ];
            })
            ->values();
    @endphp
    const orderMapData = @json($mapOrders);
    const orderCoords  = {};
    orderMapData.forEach(o => { orderCoords[o.id] = o; });

    const mapInstances   = {};
    const modalMapInited = {};
    const liveTrackers   = {};
    const riderMarkers   = {};

    /* ════════════════════════════════════════════════════════════
       PH HOLIDAYS 2026 — for business day calculation
    ═══════════════════════════════════════════════════════════ */
    const PH_HOLIDAYS = new Set([
        '2026-01-01','2026-02-25','2026-04-02','2026-04-03','2026-04-04',
        '2026-04-09','2026-05-01','2026-06-12','2026-08-21','2026-08-31',
        '2026-11-01','2026-11-02','2026-11-30','2026-12-08',
        '2026-12-24','2026-12-25','2026-12-30','2026-12-31',
    ]);

    function toYMD(d) {
        return d.getFullYear()+'-'+String(d.getMonth()+1).padStart(2,'0')+'-'+String(d.getDate()).padStart(2,'0');
    }
    function isBusinessDay(d) {
        const dow = d.getDay();
        return dow !== 0 && dow !== 6 && !PH_HOLIDAYS.has(toYMD(d));
    }
    function addBusinessDays(start, days) {
        const r = new Date(start); let added = 0;
        while (added < days) { r.setDate(r.getDate()+1); if (isBusinessDay(r)) added++; }
        return r;
    }
    function fmtDate(d) {
        return d.toLocaleDateString('en-PH',{weekday:'short',month:'short',day:'numeric',year:'numeric'});
    }
    function fmtShort(d) {
        return d.toLocaleDateString('en-PH',{month:'short',day:'numeric'});
    }

    function computeOrderETA(orderPlacedAt, distanceKm) {
        const placed = new Date(orderPlacedAt);
        placed.setHours(0, 0, 0, 0);
        let orderDate = new Date(placed);
        if (!isBusinessDay(orderDate)) orderDate = addBusinessDays(placed, 1);
        const prodEnd_early = addBusinessDays(orderDate, 7);
        const prodEnd_late  = addBusinessDays(orderDate, 14);
        const delMaxDays = distanceKm > 10 ? 3 : (distanceKm > 0 ? 2 : 3);
        const del_early  = addBusinessDays(prodEnd_early, 1);
        const del_late   = addBusinessDays(prodEnd_late,  delMaxDays);
        return { orderDate, prodEnd_early, prodEnd_late, del_early, del_late, distanceKm };
    }

    function renderETA(orderId, prefix, fillId, orderPlacedAt, distanceKm) {
        const eta = computeOrderETA(orderPlacedAt, distanceKm || 0);
        const setDateChip = (id, text) => {
            const el = document.getElementById(id);
            if (!el) return;
            const sp = el.querySelector('span');
            if (sp) sp.textContent = text;
        };
        setDateChip(`${prefix}-order-${orderId}`, fmtDate(eta.orderDate));
        const prodSame = eta.prodEnd_early.getTime() === eta.prodEnd_late.getTime();
        setDateChip(`${prefix}-prod-${orderId}`,
            prodSame ? fmtDate(eta.prodEnd_early)
                     : fmtShort(eta.prodEnd_early) + ' – ' + fmtDate(eta.prodEnd_late));
        const delSame = eta.del_early.getTime() === eta.del_late.getTime();
        setDateChip(`${prefix}-del-${orderId}`,
            delSame ? fmtDate(eta.del_early)
                    : fmtShort(eta.del_early) + ' – ' + fmtDate(eta.del_late));
        const sub = document.getElementById(`${prefix}-del-sub-${orderId}`);
        if (sub) {
            sub.textContent = distanceKm > 0
                ? `Shipped after production · ~${distanceKm.toFixed(1)} km · 1–${distanceKm > 10 ? 3 : 2} business days`
                : 'Shipped right after production · 1–3 business days';
        }
        const window_ = document.getElementById(`${prefix}-window-${orderId}`);
        if (window_) window_.textContent = fmtShort(eta.del_early) + ' – ' + fmtDate(eta.del_late);
        const range_ = document.getElementById(`${prefix}-range-${orderId}`);
        if (range_) {
            const minD = Math.round((eta.del_early - eta.orderDate) / 86400000);
            const maxD = Math.round((eta.del_late  - eta.orderDate) / 86400000);
            range_.textContent = `Approx. ${minD}–${maxD} calendar days from order`;
        }
        const badge = document.getElementById(`${prefix}-badge-${orderId}`);
        if (badge) badge.textContent = fmtShort(eta.del_early) + ' – ' + fmtShort(eta.del_late);
    }

    function toggleTrackETA(id) {
        const card = document.getElementById('card-eta-'+id);
        if (!card) return;
        const isOpen = card.classList.toggle('open');
        card.querySelector('.track-eta-header')?.setAttribute('aria-expanded', String(isOpen));
        if (isOpen) {
            setTimeout(() => {
                const fill = document.getElementById('card-eta-fill-'+id);
                if (fill) fill.style.width = '16%';
            }, 220);
        } else {
            const fill = document.getElementById('card-eta-fill-'+id);
            if (fill) fill.style.width = '0%';
        }
    }

    function toggleModalETA(id) {
        const card = document.getElementById('modal-eta-'+id);
        if (!card) return;
        const isOpen = card.classList.toggle('open');
        card.querySelector('.modal-eta-header')?.setAttribute('aria-expanded', String(isOpen));
        if (isOpen) {
            setTimeout(() => {
                const fill = document.getElementById('modal-eta-fill-'+id);
                if (fill) fill.style.width = '16%';
            }, 220);
        } else {
            const fill = document.getElementById('modal-eta-fill-'+id);
            if (fill) fill.style.width = '0%';
        }
    }

    // ─── Helpers ─────────────────────────────────────
    function haversine(a,b,c,d) {
        const R=6371,r=x=>x*Math.PI/180;
        const q=Math.sin(r(c-a)/2)**2+Math.cos(r(a))*Math.cos(r(c))*Math.sin(r(d-b)/2)**2;
        return R*2*Math.atan2(Math.sqrt(q),Math.sqrt(1-q));
    }
    function fmtDist(km){ return km<1?(km*1000).toFixed(0)+' m':km.toFixed(1)+' km'; }
    function fmtTime(s){ const m=Math.round(s/60); return m<1?'Arriving!':m<60?m+' min':Math.floor(m/60)+'h '+(m%60)+'m'; }
    function setEl(id,val,isHTML=false){ const el=document.getElementById(id); if(!el)return; isHTML?el.innerHTML=val:el.textContent=val; }
    function cap(s){ return s?s[0].toUpperCase()+s.slice(1):s; }

    // ─── Lightbox ────────────────────────────────────
    function openTrackLightbox(src, name) {
        if (!src) return;
        document.getElementById('trackLightboxImg').src = src;
        document.getElementById('trackLightboxImg').alt = name;
        document.getElementById('trackImgLightbox').classList.add('show');
        document.body.style.overflow = 'hidden';
    }
    function closeTrackLightbox() {
        document.getElementById('trackImgLightbox').classList.remove('show');
        document.body.style.overflow = '';
    }
    document.getElementById('trackLightboxImg')?.addEventListener('click', e => e.stopPropagation());

    // ─── Modal open/close ─────────────────────────────
    function openTrackModal(id) {
        const overlay = document.getElementById('track-overlay-'+id);
        if (!overlay) return;
        overlay.classList.add('active');
        document.body.style.overflow = 'hidden';
        if (!modalMapInited[id]) {
            modalMapInited[id] = true;
            if (orderCoords[id]) setTimeout(()=>initOrderMap(id,'modal'),80);
        }
        const o = orderCoords[id];
        if (o) renderETA(id, 'modal-eta', 'modal-eta-fill-'+id, o.created_at, o.distance_km || 0);
    }
    function closeTrackModal(id) {
        const overlay = document.getElementById('track-overlay-'+id);
        if (overlay) overlay.classList.remove('active');
        document.body.style.overflow = '';
        const key = 'modal'+id;
        if (liveTrackers[key]) { clearInterval(liveTrackers[key]); delete liveTrackers[key]; }
    }
    document.addEventListener('keydown', e=>{
        if(e.key!=='Escape') return;
        document.querySelectorAll('.delivery-modal-overlay.active').forEach(el=>{
            closeTrackModal(el.id.replace('track-overlay-',''));
        });
        closeTrackLightbox();
    });

    // ─── Map init ─────────────────────────────────────
    function initOrderMap(id, mapType) {
        const data = orderCoords[id];
        if (!data) return;
        const statusKey = (mapType==='mini'?'cust-mini-status-':'cust-modal-map-status-')+id;
        if (data.lat && data.lng) {
            setEl(statusKey, 'Location found');
            buildOrderMap(id, data.lat, data.lng, mapType, data.status);
        } else {
            setEl(statusKey, '⚠ No coords saved');
            buildOrderMap(id, 10.3157, 123.8854, mapType, data.status);
        }
    }

    function buildOrderMap(id, destLat, destLng, mapType, deliveryStatus) {
        const elId = (mapType==='mini'?'cust-mini-map-':'cust-modal-map-')+id;
        const mapEl = document.getElementById(elId);
        if (!mapEl) return;
        const key = mapType+id;
        if (mapInstances[key]) return;
        const isModal     = mapType==='modal';
        const isLive      = deliveryStatus==='Out for Delivery';
        const isDelivered = deliveryStatus==='Delivered';

        const map = L.map(mapEl, { zoomControl:isModal, scrollWheelZoom:isModal });
        mapInstances[key] = map;
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',{attribution:'© OpenStreetMap',maxZoom:19}).addTo(map);

        const destHtml = isDelivered
            ? '<div style="width:34px;height:34px;border-radius:50% 50% 50% 0;background:linear-gradient(135deg,#10b981,#34d399);display:flex;align-items:center;justify-content:center;color:#fff;transform:rotate(-45deg);box-shadow:0 3px 10px rgba(0,0,0,0.25);"><i class="fas fa-check" style="transform:rotate(45deg);font-size:0.8rem;"></i></div>'
            : '<div style="width:34px;height:34px;border-radius:50% 50% 50% 0;background:linear-gradient(135deg,#f093fb,#f5576c);display:flex;align-items:center;justify-content:center;color:#fff;transform:rotate(-45deg);box-shadow:0 3px 10px rgba(0,0,0,0.25);"><i class="fas fa-home" style="transform:rotate(45deg);font-size:0.8rem;"></i></div>';

        L.marker([destLat,destLng],{
            icon:L.divIcon({className:'',html:destHtml,iconSize:[34,34],iconAnchor:[17,34]})
        }).addTo(map).bindPopup(isDelivered?'<b>✅ Delivered here</b>':'<b>📦 Your delivery address</b>');

        if (!isLive) {
            fetch(`https://router.project-osrm.org/route/v1/driving/${STORE_LNG},${STORE_LAT};${destLng},${destLat}?overview=full&geometries=geojson`)
                .then(r=>r.json()).then(data=>{
                    if(data.code==='Ok'&&data.routes?.length){
                        const coords=data.routes[0].geometry.coordinates.map(c=>[c[1],c[0]]);
                        const distKm=data.routes[0].distance/1000;
                        addStoreMarker(map);
                        L.polyline(coords,{color:isDelivered?'#10b981':'#FFB6C1',weight:isModal?5:3,opacity:0.8,dashArray:isDelivered?null:'8,8',lineJoin:'round',lineCap:'round'}).addTo(map);
                        map.fitBounds(L.polyline(coords).getBounds(),{padding:isModal?[50,50]:[25,25]});
                        if(isModal){
                            setEl('cust-modal-dist-'+id, fmtDist(distKm)+' from store');
                            setEl('cust-modal-maps-link-'+id,
                                `<a href="https://www.google.com/maps/dir/${STORE_LAT},${STORE_LNG}/${destLat},${destLng}" target="_blank"
                                    style="color:#fff;font-size:0.75rem;background:rgba(255,255,255,0.25);padding:3px 10px;border-radius:20px;text-decoration:none;">
                                    <i class="fas fa-map-marked-alt me-1"></i>Open Maps</a>`, true);
                        } else {
                            setEl('cust-mini-dist-'+id, fmtDist(distKm));
                            if (orderCoords[id]) {
                                orderCoords[id].distance_km = distKm;
                                renderETA(id, 'card-eta', 'card-eta-fill-'+id, orderCoords[id].created_at, distKm);
                            }
                        }
                    } else { map.setView([destLat,destLng],15); }
                }).catch(()=>map.setView([destLat,destLng],15));
            const statusKey=(mapType==='mini'?'cust-mini-status-':'cust-modal-map-status-')+id;
            setEl(statusKey, isDelivered?'✓ Delivered':'Pending');
            return;
        }

        drawOrderRoute(map, destLat, destLng, id, mapType);
        requestOrderGPS(map, id, destLat, destLng, mapType);
    }

    function addStoreMarker(map) {
        L.marker([STORE_LAT,STORE_LNG],{
            icon:L.divIcon({
                className:'',
                html:'<div style="background:linear-gradient(135deg,#FFB6C1,#FF9EAD);width:30px;height:30px;border-radius:50%;border:3px solid #fff;display:flex;align-items:center;justify-content:center;box-shadow:0 2px 8px rgba(255,182,193,0.6);"><i class="fas fa-store" style="color:#fff;font-size:0.65rem;"></i></div>',
                iconSize:[30,30],iconAnchor:[15,15]
            })
        }).addTo(map).bindPopup('<b>🏪 HookcraftAvenue Store</b>');
    }

    async function drawOrderRoute(map, destLat, destLng, id, mapType) {
        const isModal   = mapType==='modal';
        const statusKey = (isModal?'cust-modal-map-status-':'cust-mini-status-')+id;
        try {
            const res  = await fetch(`https://router.project-osrm.org/route/v1/driving/${STORE_LNG},${STORE_LAT};${destLng},${destLat}?overview=full&geometries=geojson&steps=true`);
            const data = await res.json();
            if(data.code!=='Ok'||!data.routes?.length) throw new Error('no route');

            const route  = data.routes[0];
            const coords = route.geometry.coordinates.map(c=>[c[1],c[0]]);
            const distKm = route.distance/1000;
            const durS   = route.duration;

            addStoreMarker(map);
            L.polyline(coords,{color:'#FFB6C1',weight:isModal?14:10,opacity:0.2,lineJoin:'round',lineCap:'round'}).addTo(map);
            L.polyline(coords,{color:'#FF9EAD',weight:isModal?6:4,opacity:0.95,lineJoin:'round',lineCap:'round'}).addTo(map);
            L.polyline(coords,{color:'#fff',weight:isModal?2.5:1.5,opacity:0.7,dashArray:'8 18',lineJoin:'round',lineCap:'round'}).addTo(map);
            L.circle([destLat,destLng],{radius:35,color:'#f5576c',fillColor:'#f5576c',fillOpacity:0.15,weight:2}).addTo(map);
            map.fitBounds(L.polyline(coords).getBounds(),{padding:isModal?[55,55]:[28,28]});

            if(isModal){
                setEl('cust-modal-dist-'+id, fmtDist(distKm));
                setEl('cust-modal-eta-'+id,  fmtTime(durS));
                buildOrderTurnByTurn(route, id);
                buildOrderProgressBar(id, 0);
                setEl('cust-modal-maps-link-'+id,
                    `<a href="https://www.google.com/maps/dir/${STORE_LAT},${STORE_LNG}/${destLat},${destLng}" target="_blank"
                        style="color:#fff;font-size:0.75rem;background:rgba(255,255,255,0.25);padding:3px 10px;border-radius:20px;text-decoration:none;">
                        <i class="fas fa-map-marked-alt me-1"></i>Open Maps</a>`, true);
            } else {
                setEl('cust-mini-dist-'+id, fmtDist(distKm));
                setEl('cust-mini-eta-'+id,  fmtTime(durS));
                if (orderCoords[id]) {
                    orderCoords[id].distance_km = distKm;
                    renderETA(id, 'card-eta', 'card-eta-fill-'+id, orderCoords[id].created_at, distKm);
                }
            }
            setEl(statusKey, 'Route loaded');
        } catch(e) {
            const line=L.polyline([[STORE_LAT,STORE_LNG],[destLat,destLng]],{color:'#FFB6C1',weight:4,opacity:0.75,dashArray:'9,9'}).addTo(map);
            map.fitBounds(line.getBounds(),{padding:[40,40]});
            setEl(statusKey,'Approx. route');
        }
    }

    function requestOrderGPS(map, id, destLat, destLng, mapType) {
        if(!navigator.geolocation) return;
        const statusKey=(mapType==='mini'?'cust-mini-status-':'cust-modal-map-status-')+id;
        navigator.geolocation.getCurrentPosition(
            pos=>{
                setEl(statusKey,'On the way');
                placeOrderRiderMarker(map,id,pos.coords.latitude,pos.coords.longitude,destLat,destLng,mapType);
                const key=mapType+id;
                if(liveTrackers[key]) clearInterval(liveTrackers[key]);
                liveTrackers[key]=setInterval(()=>{
                    navigator.geolocation.getCurrentPosition(
                        p=>placeOrderRiderMarker(map,id,p.coords.latitude,p.coords.longitude,destLat,destLng,mapType),
                        ()=>{},{enableHighAccuracy:true,timeout:10000,maximumAge:15000}
                    );
                },15000);
            },
            err=>{ setEl(statusKey, err.code===1?'⚠ Enable location':'⚠ GPS error'); },
            {enableHighAccuracy:true,timeout:15000,maximumAge:0}
        );
    }

    function placeOrderRiderMarker(map, id, rLat, rLng, destLat, destLng, mapType) {
        const isModal = mapType==='modal';
        if(riderMarkers[id]) {
            riderMarkers[id].setLatLng([rLat,rLng]);
        } else {
            riderMarkers[id]=L.marker([rLat,rLng],{
                icon:L.divIcon({
                    className:'',
                    html:'<div style="position:relative;display:inline-block;">'
                        +'<div style="width:46px;height:46px;border-radius:50%;background:linear-gradient(135deg,#43e97b,#38f9d7);border:3px solid #fff;box-shadow:0 0 0 4px rgba(67,233,123,0.35),0 4px 14px rgba(67,233,123,0.5);display:flex;align-items:center;justify-content:center;">'
                        +'<i class="fas fa-motorcycle" style="color:#fff;font-size:1.1rem;"></i></div>'
                        +'<div style="position:absolute;top:-26px;left:50%;transform:translateX(-50%);background:linear-gradient(135deg,#43e97b,#38f9d7);color:#fff;font-size:9px;padding:2px 8px;border-radius:12px;white-space:nowrap;font-weight:800;">🛵 RIDER</div></div>',
                    iconSize:[46,46],iconAnchor:[23,23]
                }),
                zIndexOffset:1000
            }).addTo(map).bindPopup('<b>🛵 Your Rider</b><br><small>Live Location</small>');
        }
        const remKm=haversine(rLat,rLng,destLat,destLng);
        if(isModal){
            setEl('cust-modal-eta-'+id, fmtTime(remKm/30*3600));
            const pct=Math.max(0,Math.min(100,Math.round((1-remKm/haversine(STORE_LAT,STORE_LNG,destLat,destLng))*100)));
            buildOrderProgressBar(id,pct);
            setEl('cust-modal-map-status-'+id, pct>=95?'Arriving!':'On the way '+pct+'%');
        } else {
            setEl('cust-mini-eta-'+id, fmtTime(remKm/30*3600));
        }
    }

    function buildOrderProgressBar(id, pct) {
        const container=document.getElementById('cust-steps-list-'+id);
        if(!container) return;
        const existing=document.getElementById('cust-progress-bar-fill-'+id);
        if(existing){ existing.style.width=pct+'%'; const lbl=document.getElementById('cust-progress-bar-label-'+id); if(lbl)lbl.textContent=pct+'% complete'; return; }
        const bar=document.createElement('div');
        bar.style.marginBottom='12px';
        bar.innerHTML='<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:5px;">'
            +'<span style="font-size:0.75rem;font-weight:700;color:#718096;text-transform:uppercase;letter-spacing:0.05em;"><i class="fas fa-route me-1"></i>Delivery Progress</span>'
            +'<span id="cust-progress-bar-label-'+id+'" style="font-size:0.75rem;font-weight:700;color:#FF9EAD;">'+pct+'% complete</span>'
            +'</div>'
            +'<div style="background:#e5e7eb;border-radius:99px;height:8px;overflow:hidden;">'
            +'<div id="cust-progress-bar-fill-'+id+'" style="height:100%;width:'+pct+'%;background:linear-gradient(90deg,#FFB6C1,#FF9EAD);border-radius:99px;transition:width 0.6s ease;box-shadow:0 0 8px rgba(255,182,193,0.5);"></div>'
            +'</div>'
            +'<div style="display:flex;justify-content:space-between;margin-top:3px;font-size:0.68rem;color:#718096;"><span>🏪 Store</span><span>🏠 Your Door</span></div>';
        container.prepend(bar);
    }

    function buildOrderTurnByTurn(route, id) {
        const container=document.getElementById('cust-steps-list-'+id);
        if(!container) return;
        const steps=route.legs?.[0]?.steps??[];
        if(!steps.length){ container.innerHTML='<div class="step-item"><span style="color:#9ca3af;font-size:0.82rem;">No steps available.</span></div>'; return; }
        container.innerHTML=steps.map((s,i)=>{
            const type=s.maneuver?.type??'',mod=s.maneuver?.modifier??'',name=s.name||'road';
            const dist=s.distance<1000?s.distance.toFixed(0)+' m':(s.distance/1000).toFixed(1)+' km';
            const icons={'arrive':'🏁','depart':'🚀','roundabout':'⭕'};
            let icon=icons[type]||'⬆';
            if(type==='turn'){ if(mod.includes('left'))icon='←'; else if(mod.includes('right'))icon='→'; }
            const desc=type==='arrive'?'🏁 Arrive at your address':`${cap(type.replace('-',' '))}${mod?' '+mod.replace('-',' '):''}${name?' on <b>'+name+'</b>':''}`;
            return `<div class="step-item"><div class="step-num">${i+1}</div><div><div class="step-text">${icon} ${desc}</div><div class="step-dist">${dist}</div></div></div>`;
        }).join('');
    }

    // ─── Init on page load ────────────────────────────
    document.addEventListener('DOMContentLoaded', ()=>{
        orderMapData.forEach((o, i) => {
            renderETA(o.id, 'card-eta', 'card-eta-fill-'+o.id, o.created_at, o.distance_km || 0);
            setTimeout(() => initOrderMap(o.id, 'mini'), i * 180);
        });

        document.querySelectorAll('.toast-notification').forEach(t=>{
            setTimeout(()=>{ t.style.opacity='0'; t.style.transform='translateX(400px)'; setTimeout(()=>t.remove(),300); },5000);
            t.addEventListener('click',()=>{ t.style.opacity='0'; t.style.transform='translateX(400px)'; setTimeout(()=>t.remove(),300); });
        });
    });

    window.addEventListener('beforeunload',()=>{ Object.values(liveTrackers).forEach(clearInterval); });

    function confirmCancel(orderId) {
        if(!confirm('Are you sure you want to cancel this order?\n\nThis action cannot be undone.')) return false;
        const button=document.getElementById('cancelBtn'+orderId);
        button.innerHTML='<i class="fas fa-spinner fa-spin me-1"></i> Cancelling...';
        button.disabled=true;
        setTimeout(()=>{ button.innerHTML='<i class="fas fa-times me-1"></i> Cancel Order'; button.disabled=false; },10000);
        return true;
    }
    </script>
</body>
</html>