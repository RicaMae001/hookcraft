<div class="order-card animate-fade-in" style="animation-delay: {{ $loop->index * 0.1 }}s;">
    <div class="order-header">
        <div class="d-flex align-items-center gap-3">
            <span class="order-id">#{{ $delivery->id }}</span>
            <h6 class="customer-name mb-0">{{ $delivery->customer_name }}</h6>
        </div>
        
        <!-- Status Badge -->
        @if($delivery->delivery_status == 'Delivered')
            <span class="badge-modern badge-success">
                <i class="fas fa-check-circle me-1"></i>Delivered
            </span>
        @elseif($delivery->delivery_status == 'Out for Delivery')
            <span class="badge-modern badge-info">
                <i class="fas fa-shipping-fast me-1"></i>Out for Delivery
            </span>
        @elseif($delivery->delivery_status == 'Cancelled')
            <span class="badge-modern badge-danger">
                <i class="fas fa-times-circle me-1"></i>Cancelled
            </span>
        @else
            <span class="badge-modern badge-warning">
                <i class="fas fa-clock me-1"></i>Pending
            </span>
        @endif
    </div>

    <!-- Customer Info Grid -->
    <div class="row g-3 mb-3">
        <div class="col-md-6">
            <div class="info-item">
                <div class="info-icon">
                    <i class="fas fa-phone text-success"></i>
                </div>
                <div>
                    <div class="info-label">Phone Number</div>
                    <div class="info-value">
                        <a href="tel:{{ $delivery->phone }}" class="text-decoration-none">
                            {{ $delivery->phone }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="info-item">
                <div class="info-icon">
                    <i class="fas fa-calendar text-info"></i>
                </div>
                <div>
                    <div class="info-label">Order Date</div>
                    <div class="info-value">{{ \Carbon\Carbon::parse($delivery->created_at)->format('M d, Y h:i A') }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Address Section -->
    <div class="order-address mb-3">
        <i class="fas fa-map-marker-alt text-danger me-2"></i>
        <span>{{ $delivery->address }}</span>
    </div>

    <!-- ===================== DELIVERY MAP TRACKER ===================== -->
    @if($delivery->delivery_status == 'Out for Delivery')
    <div class="delivery-map-wrapper mb-3">
        <div class="map-header">
            <div class="map-title">
                <span class="live-dot"></span>
                <span>Live Tracking</span>
            </div>
            <span class="map-eta" id="eta-{{ $delivery->id }}">Calculating ETA...</span>
        </div>
        <div id="delivery-map-{{ $delivery->id }}" class="delivery-map"></div>
        <div class="rider-info-bar">
            <div class="rider-detail">
                <i class="fas fa-motorcycle text-info me-2"></i>
                <span class="rider-name">Rider: {{ $delivery->rider_name ?? 'Assigned Rider' }}</span>
            </div>
            <div class="rider-detail">
                <i class="fas fa-map-marker-alt text-danger me-2"></i>
                <span id="rider-distance-{{ $delivery->id }}">Fetching location...</span>
            </div>
        </div>
    </div>
    @endif
    @if($delivery->delivery_status == 'Delivered')
    <div class="delivered-map-notice mb-3">
        <i class="fas fa-check-circle text-success me-2"></i>
        <span>Order was successfully delivered.</span>
    </div>
    @endif
    <!-- ===================== END MAP TRACKER ===================== -->

    <!-- Payment & Total Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            @if($delivery->payment_status == 'Paid')
                <span class="badge-modern badge-success">
                    <i class="fas fa-check me-1"></i>Payment: Paid
                </span>
            @else
                <span class="badge-modern badge-warning">
                    <i class="fas fa-clock me-1"></i>Payment: {{ $delivery->payment_status }}
                </span>
            @endif
        </div>
        
        <div class="total-amount">
            <span class="total-label">Total Amount</span>
            <span class="total-value">₱{{ number_format($delivery->total, 2) }}</span>
        </div>
    </div>

    <!-- Action Button -->
    <div class="d-grid">
        <button type="button" class="btn-modern btn-modern-primary" 
                onclick="updateStatus({{ $delivery->id }}, '{{ addslashes($delivery->customer_name) }}')">
            <i class="fas fa-edit me-2"></i>Update Delivery Status
        </button>
    </div>
</div>

{{-- ===================== STATUS UPDATE MODAL ===================== --}}
<div class="modal fade" id="statusModal-{{ $delivery->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="border-radius:20px; border:none; overflow:hidden;">

            {{-- Header --}}
            <div class="modal-header" style="background:linear-gradient(135deg,#667eea,#764ba2); border:none; padding:1.2rem 1.6rem;">
                <div class="d-flex align-items-center gap-3 flex-wrap">
                    <span style="background:rgba(255,255,255,0.25);padding:4px 12px;border-radius:8px;font-weight:700;font-size:0.9rem;color:#fff;">
                        #{{ $delivery->id }}
                    </span>
                    <span style="font-size:1.1rem;font-weight:700;color:#fff;">{{ $delivery->customer_name }}</span>
                    @if($delivery->delivery_status == 'Delivered')
                        <span class="badge-modern badge-success"><i class="fas fa-check-circle me-1"></i>Delivered</span>
                    @elseif($delivery->delivery_status == 'Out for Delivery')
                        <span class="badge-modern badge-info"><i class="fas fa-shipping-fast me-1"></i>Out for Delivery</span>
                    @elseif($delivery->delivery_status == 'Cancelled')
                        <span class="badge-modern badge-danger"><i class="fas fa-times-circle me-1"></i>Cancelled</span>
                    @else
                        <span class="badge-modern badge-warning"><i class="fas fa-clock me-1"></i>Pending</span>
                    @endif
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body" style="padding:1.5rem;">

                {{-- ══ PAYMENT PROOF SECTION ══ --}}
                @if($delivery->payment_method === 'GCash')
                <div class="modal-proof-box mb-3">
                    <div class="modal-proof-box-header">
                        <i class="fas fa-mobile-alt" style="color:#63B3ED;"></i>
                        <span style="font-weight:700;font-size:0.88rem;">GCash Payment Proof</span>
                        @if($delivery->payment_status === 'Paid')
                            <span class="modal-proof-status-pill pill-paid ms-auto">
                                <i class="fas fa-check-circle me-1"></i>Paid & Verified
                            </span>
                        @elseif($delivery->payment_proof)
                            <span class="modal-proof-status-pill pill-pending ms-auto">
                                <i class="fas fa-hourglass-half me-1"></i>Awaiting Verification
                            </span>
                        @else
                            <span class="modal-proof-status-pill ms-auto" style="background:rgba(252,129,129,0.15);color:#FC8181;">
                                <i class="fas fa-times-circle me-1"></i>Not Uploaded
                            </span>
                        @endif
                    </div>

                    @if($delivery->payment_proof)
                        {{-- Show existing proof image --}}
                        <div class="text-center">
                            <img src="{{ asset('uploads/payments/'.$delivery->payment_proof) }}"
                                 alt="GCash Payment Proof"
                                 class="proof-img-preview"
                                 onclick="openImageModal(this)"
                                 onerror="this.style.display='none'">
                            <div style="margin-top:8px;font-size:0.76rem;color:#718096;">
                                <i class="fas fa-search-plus me-1"></i>Tap to view full size
                            </div>
                        </div>
                    @else
                        {{-- Upload form --}}
                        <div class="proof-no-upload-notice">
                            <i class="fas fa-image"></i>
                            No payment proof uploaded yet
                        </div>
                        <form action="{{ route('delivery.upload-payment-proof', $delivery->id) }}"
                              method="POST"
                              enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label style="font-size:0.82rem;font-weight:600;color:#718096;margin-bottom:6px;display:block;">
                                    <i class="fas fa-image me-1"></i>Select GCash screenshot
                                </label>
                                <input type="file"
                                       class="form-control"
                                       name="payment_proof"
                                       accept="image/*"
                                       required
                                       onchange="previewProof(this, {{ $delivery->id }})">
                            </div>
                            <div id="proofPreviewWrap{{ $delivery->id }}" class="mb-3 text-center" style="display:none;">
                                <img id="proofPreview{{ $delivery->id }}"
                                     style="max-height:160px;border-radius:8px;border:2px solid #63B3ED;">
                            </div>
                            <button type="submit"
                                    style="width:100%;padding:0.75rem 1.2rem;border-radius:10px;font-weight:700;
                                           border:none;cursor:pointer;font-size:0.95rem;
                                           background:linear-gradient(135deg,#667eea,#764ba2);color:#fff;
                                           display:flex;align-items:center;justify-content:center;gap:0.5rem;">
                                <i class="fas fa-cloud-upload-alt"></i> Submit Payment Proof
                            </button>
                        </form>
                    @endif
                </div>

                @elseif($delivery->payment_method === 'COD')
                {{-- COD notice --}}
                <div style="background:rgba(246,173,85,0.1);border:1px solid rgba(246,173,85,0.5);
                            border-radius:12px;padding:13px 16px;margin-bottom:1rem;
                            display:flex;align-items:center;gap:12px;">
                    <i class="fas fa-money-bill-wave" style="color:#F6AD55;font-size:1.4rem;flex-shrink:0;"></i>
                    <div>
                        <div style="font-weight:700;font-size:0.88rem;">Cash on Delivery</div>
                        <div style="font-size:0.82rem;color:#718096;">
                            Collect <strong>₱{{ number_format($delivery->grand_total ?? $delivery->total, 2) }}</strong> in cash from the customer upon delivery.
                        </div>
                    </div>
                </div>
                @endif
                {{-- ══ END PAYMENT PROOF SECTION ══ --}}

                {{-- Info grid --}}
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:1rem;">
                    <div class="dmodal-info-box">
                        <div class="label"><i class="fas fa-phone me-1"></i>Phone</div>
                        <div class="value">
                            <a href="tel:{{ $delivery->phone }}" class="text-decoration-none" style="color:inherit;">
                                {{ $delivery->phone }}
                            </a>
                        </div>
                    </div>
                    <div class="dmodal-info-box">
                        <div class="label"><i class="fas fa-credit-card me-1"></i>Payment Method</div>
                        <div class="value">{{ $delivery->payment_method ?? 'N/A' }}</div>
                    </div>
                    <div class="dmodal-info-box">
                        <div class="label"><i class="fas fa-calendar me-1"></i>Order Date</div>
                        <div class="value">{{ \Carbon\Carbon::parse($delivery->created_at)->format('M d, Y h:i A') }}</div>
                    </div>
                    <div class="dmodal-info-box">
                        <div class="label"><i class="fas fa-tag me-1"></i>Payment Status</div>
                        <div class="value">{{ $delivery->payment_status ?? 'Pending' }}</div>
                    </div>
                    <div class="dmodal-info-box" style="grid-column:1/-1;">
                        <div class="label"><i class="fas fa-map-marker-alt me-1"></i>Delivery Address</div>
                        <div class="value">{{ $delivery->address }}</div>
                    </div>
                </div>

                {{-- Order Items --}}
                <div style="font-weight:600;color:#718096;margin-bottom:8px;font-size:0.84rem;">
                    <i class="fas fa-shopping-bag text-info me-2"></i>Order Items
                </div>
                @php
                    $orderItems = DB::table('order_item')
                        ->join('products','order_item.product_id','=','products.id')
                        ->where('order_item.order_id', $delivery->id)
                        ->select('products.name as product_name','products.image as product_image','order_item.quantity','order_item.price')
                        ->get();
                @endphp
                @forelse($orderItems as $item)
                    <div class="product-item mb-2">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                @if($item->product_image)
                                    <img src="{{ asset('asset/images/'.$item->product_image) }}"
                                         alt="{{ $item->product_name }}"
                                         style="width:54px;height:54px;object-fit:cover;border-radius:8px;border:2px solid #E2E8F0;cursor:pointer;"
                                         onclick="openImageModal(this)"
                                         onerror="this.src='{{ asset('asset/images/default-product.png') }}';">
                                @else
                                    <div style="width:54px;height:54px;border-radius:8px;background:#718096;display:flex;align-items:center;justify-content:center;">
                                        <i class="fas fa-image text-white"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="col">
                                <h6 class="mb-1">{{ $item->product_name }}</h6>
                                <small class="text-muted">Qty: {{ $item->quantity }} × ₱{{ number_format($item->price,2) }}</small>
                                <div style="font-weight:700;color:#667eea;">₱{{ number_format($item->quantity * $item->price,2) }}</div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-muted py-2">
                        <i class="fas fa-box-open me-2"></i>No items found
                    </div>
                @endforelse

                {{-- Total --}}
                <div style="background:linear-gradient(135deg,#667eea,#764ba2);color:#fff;padding:13px;border-radius:8px;margin-top:12px;margin-bottom:16px;">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-receipt me-2"></i>Total Amount</h5>
                        <h4 class="mb-0">₱{{ number_format($delivery->grand_total ?? $delivery->total, 2) }}</h4>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="d-grid gap-2">
                    @if($delivery->delivery_status === 'Pending')
                        <button class="btn-modern btn-modern-info"
                            onclick="confirmStatusChange({{ $delivery->id }}, 'Out for Delivery')">
                            <i class="fas fa-shipping-fast me-2"></i>Mark Out for Delivery
                        </button>
                        <button class="btn-modern btn-modern-danger"
                            onclick="confirmStatusChange({{ $delivery->id }}, 'Cancelled')">
                            <i class="fas fa-times-circle me-2"></i>Cancel Order
                        </button>
                    @elseif($delivery->delivery_status === 'Out for Delivery')
                        <button class="btn-modern btn-modern-success"
                            onclick="confirmStatusChange({{ $delivery->id }}, 'Delivered')">
                            <i class="fas fa-check-circle me-2"></i>Mark as Delivered
                        </button>
                    @elseif($delivery->delivery_status === 'Delivered')
                        <button class="btn-modern btn-modern-success" disabled>
                            <i class="fas fa-check-circle me-2"></i>Delivered
                        </button>
                    @elseif($delivery->delivery_status === 'Cancelled')
                        <button class="btn-modern" style="background:#E2E8F0;color:#718096;" disabled>
                            <i class="fas fa-ban me-2"></i>Cancelled
                        </button>
                    @endif
                </div>

            </div>{{-- /modal-body --}}
        </div>
    </div>
</div>
{{-- ===================== END STATUS UPDATE MODAL ===================== --}}

{{-- ===================== LEAFLET MAP SCRIPTS ===================== --}}
@once
    @push('styles')
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    @endpush
    @push('scripts')
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    @endpush
@endonce

@if($delivery->delivery_status == 'Out for Delivery')
<script>
(function() {
    const deliveryId = {{ $delivery->id }};
    const storeLat  = {{ $delivery->store_lat  ?? 10.3157 }};
    const storeLng  = {{ $delivery->store_lng  ?? 123.8854 }};
    const destLat   = {{ $delivery->dest_lat   ?? 10.3200 }};
    const destLng   = {{ $delivery->dest_lng   ?? 123.8900 }};
    const riderLat  = {{ $delivery->rider_lat  ?? 10.3175 }};
    const riderLng  = {{ $delivery->rider_lng  ?? 123.8875 }};

    document.addEventListener('DOMContentLoaded', function () {
        initDeliveryMap(deliveryId, storeLat, storeLng, destLat, destLng, riderLat, riderLng);
    });

    function initDeliveryMap(id, sLat, sLng, dLat, dLng, rLat, rLng) {
        const mapEl = document.getElementById('delivery-map-' + id);
        if (!mapEl || typeof L === 'undefined') return;

        const map = L.map(mapEl, { zoomControl: true, scrollWheelZoom: false })
                     .setView([rLat, rLng], 14);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors', maxZoom: 19
        }).addTo(map);

        const storeIcon = L.divIcon({ className: '', html: `<div class="map-pin map-pin-store"><i class="fas fa-store"></i></div>`, iconSize: [36,36], iconAnchor: [18,36] });
        const destIcon  = L.divIcon({ className: '', html: `<div class="map-pin map-pin-dest"><i class="fas fa-home"></i></div>`,  iconSize: [36,36], iconAnchor: [18,36] });
        const riderIcon = L.divIcon({ className: '', html: `<div class="map-pin map-pin-rider"><i class="fas fa-motorcycle"></i></div>`, iconSize: [40,40], iconAnchor: [20,20] });

        L.marker([sLat, sLng], { icon: storeIcon }).addTo(map).bindPopup('<b>📦 Store / Pick-up Point</b>');
        L.marker([dLat, dLng], { icon: destIcon  }).addTo(map).bindPopup('<b>🏠 Delivery Address</b>');

        const riderMarker = L.marker([rLat, rLng], { icon: riderIcon }).addTo(map)
            .bindPopup('<b>🛵 Rider is on the way!</b>').openPopup();

        const routeLine = L.polyline([[sLat,sLng],[rLat,rLng],[dLat,dLng]], {
            color:'#667eea', weight:4, opacity:0.75, dashArray:'8, 8'
        }).addTo(map);

        map.fitBounds(routeLine.getBounds(), { padding:[40,40] });

        function haversine(lat1,lon1,lat2,lon2) {
            const R=6371, r=x=>x*Math.PI/180;
            const a=Math.sin(r(lat2-lat1)/2)**2+Math.cos(r(lat1))*Math.cos(r(lat2))*Math.sin(r(lon2-lon1)/2)**2;
            return R*2*Math.atan2(Math.sqrt(a),Math.sqrt(1-a));
        }

        function updateETA(rLat,rLng,dLat,dLng) {
            const dist=haversine(rLat,rLng,dLat,dLng);
            const mins=Math.round((dist/30)*60);
            const etaEl=document.getElementById('eta-'+id);
            const distEl=document.getElementById('rider-distance-'+id);
            if(etaEl) etaEl.textContent = mins<=1?'Arriving soon!':'ETA: ~'+mins+' min';
            if(distEl) distEl.textContent = dist<1?(dist*1000).toFixed(0)+'m away':dist.toFixed(1)+'km away';
        }

        updateETA(rLat,rLng,dLat,dLng);

        setInterval(function() {
            fetch(`/deliveries/${id}/location`)
                .then(r=>r.json())
                .then(data=>{
                    if(data&&data.lat&&data.lng){
                        riderMarker.setLatLng([data.lat,data.lng]);
                        routeLine.setLatLngs([[sLat,sLng],[data.lat,data.lng],[dLat,dLng]]);
                        updateETA(data.lat,data.lng,dLat,dLng);
                    }
                }).catch(()=>{});
        }, 15000);
    }
})();
</script>
@endif
{{-- ===================== END MAP SCRIPTS ===================== --}}

<style>
    /* ── Payment proof in modal ── */
    .modal-proof-box {
        background: rgba(99,179,237,0.08);
        border: 1.5px solid #63B3ED;
        border-radius: 12px;
        padding: 14px;
    }
    .modal-proof-box-header {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 12px;
    }
    .modal-proof-status-pill {
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 0.72rem;
        font-weight: 700;
    }
    .pill-paid    { background:rgba(72,187,120,0.15); color:#48BB78; }
    .pill-pending { background:rgba(246,173,85,0.15); color:#F6AD55; }

    .proof-img-preview {
        max-width: 100%;
        max-height: 240px;
        border-radius: 10px;
        border: 2px solid #63B3ED;
        cursor: pointer;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .proof-img-preview:hover {
        transform: scale(1.02);
        box-shadow: 0 8px 24px rgba(99,179,237,0.3);
    }
    .proof-no-upload-notice {
        background: rgba(252,129,129,0.08);
        border: 2px dashed #FC8181;
        border-radius: 10px;
        padding: 14px;
        text-align: center;
        color: #FC8181;
        font-size: 0.85rem;
        font-weight: 600;
        margin-bottom: 12px;
    }
    .proof-no-upload-notice i {
        display: block;
        font-size: 1.6rem;
        margin-bottom: 6px;
        opacity: 0.7;
    }

    /* ── Info boxes ── */
    .dmodal-info-box {
        background: var(--light-bg, #F7FAFC);
        border: 1px solid var(--border-color, #E2E8F0);
        border-radius: 11px;
        padding: 11px 13px;
    }
    .dmodal-info-box .label {
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--text-secondary, #718096);
        font-weight: 700;
        margin-bottom: 3px;
    }
    .dmodal-info-box .value {
        font-size: 0.92rem;
        font-weight: 600;
        color: var(--text-primary, #1A202C);
    }

    /* ── Product item ── */
    .product-item {
        background: var(--light-bg, #F7FAFC);
        padding: 10px;
        border-radius: 8px;
        border: 1px solid var(--border-color, #E2E8F0);
    }

    /* ── Map tracker styles ── */
    .delivery-map-wrapper {
        border-radius: 14px;
        overflow: hidden;
        border: 1px solid var(--border-color, #E2E8F0);
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    }
    .map-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 14px;
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: #fff;
    }
    .map-title { display:flex; align-items:center; gap:8px; font-weight:700; font-size:0.9rem; }
    .live-dot {
        display: inline-block; width:10px; height:10px;
        background:#fff; border-radius:50%;
        animation: pulse-dot 1.4s ease-in-out infinite;
    }
    @keyframes pulse-dot {
        0%   { box-shadow:0 0 0 0 rgba(255,255,255,0.7); }
        70%  { box-shadow:0 0 0 8px rgba(255,255,255,0); }
        100% { box-shadow:0 0 0 0 rgba(255,255,255,0); }
    }
    .map-eta { font-size:0.8rem; font-weight:600; background:rgba(255,255,255,0.2); padding:3px 10px; border-radius:20px; }
    .delivery-map { height:220px; width:100%; z-index:1; }
    .rider-info-bar {
        display:flex; justify-content:space-between; align-items:center;
        padding:8px 14px; background:var(--light-bg,#f8f9fa);
        border-top:1px solid var(--border-color,#e2e8f0);
        font-size:0.85rem; color:var(--text-primary,#2d3748); font-weight:500;
    }
    .rider-detail { display:flex; align-items:center; }
    .map-pin {
        width:36px; height:36px; border-radius:50% 50% 50% 0;
        display:flex; align-items:center; justify-content:center;
        color:#fff; font-size:0.85rem; transform:rotate(-45deg);
        box-shadow:0 3px 10px rgba(0,0,0,0.25);
    }
    .map-pin i { transform:rotate(45deg); }
    .map-pin-store { background:linear-gradient(135deg,#667eea,#764ba2); }
    .map-pin-dest  { background:linear-gradient(135deg,#f093fb,#f5576c); }
    .map-pin-rider {
        background:linear-gradient(135deg,#43e97b,#38f9d7);
        border-radius:50%; transform:none; width:40px; height:40px;
        border:3px solid #fff; animation:rider-pulse 2s ease-in-out infinite;
    }
    .map-pin-rider i { transform:none; font-size:1rem; }
    @keyframes rider-pulse {
        0%,100% { box-shadow:0 0 0 0 rgba(67,233,123,0.5); }
        50%     { box-shadow:0 0 0 10px rgba(67,233,123,0); }
    }
    .delivered-map-notice {
        display:flex; align-items:center; padding:10px 14px;
        background:rgba(72,187,120,0.08); border:1px solid rgba(72,187,120,0.3);
        border-radius:10px; font-size:0.9rem; font-weight:500;
    }

    /* ── Card styles ── */
    .info-item {
        display:flex; align-items:center; gap:12px; padding:12px;
        background:var(--light-bg,#f8f9fa); border-radius:10px;
        border:1px solid var(--border-color,#e2e8f0); transition:all 0.2s ease;
    }
    .info-item:hover { transform:translateY(-2px); }
    .info-icon {
        width:40px; height:40px; border-radius:10px;
        display:flex; align-items:center; justify-content:center;
        background:rgba(102,126,234,0.1); font-size:1.1rem;
    }
    .info-label { font-size:0.75rem; color:var(--text-secondary,#718096); font-weight:600; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:2px; }
    .info-value  { font-size:0.95rem; font-weight:600; color:var(--text-primary,#2d3748); }
    .total-amount { text-align:right; }
    .total-label  { display:block; font-size:0.8rem; color:var(--text-secondary,#718096); font-weight:500; margin-bottom:2px; }
    .total-value  {
        display:block; font-size:1.5rem; font-weight:700;
        background:linear-gradient(135deg,#667eea,#764ba2);
        -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text;
    }
    .order-address {
        display:flex; align-items:flex-start; gap:10px; padding:12px;
        background:rgba(252,129,129,0.05); border-radius:10px;
        border-left:4px solid var(--danger,#FC8181);
    }
    .order-address i    { font-size:1.1rem; margin-top:2px; }
    .order-address span { font-size:0.95rem; line-height:1.5; }
    .animate-fade-in { animation:fadeIn 0.5s ease forwards; opacity:0; }
    @keyframes fadeIn { from{opacity:0;transform:translateY(20px)} to{opacity:1;transform:translateY(0)} }

    @media(max-width:768px){
        .order-header  { flex-direction:column; align-items:flex-start; gap:10px; }
        .rider-info-bar{ flex-direction:column; align-items:flex-start; gap:4px; }
        .total-value   { font-size:1.25rem; }
        [style*="grid-template-columns:1fr 1fr"] { grid-template-columns:1fr !important; }
    }
</style>

<script>
function previewProof(input, id) {
    if (!input.files?.[0]) return;
    const r = new FileReader();
    r.onload = e => {
        document.getElementById('proofPreviewWrap'+id).style.display = 'block';
        document.getElementById('proofPreview'+id).src = e.target.result;
    };
    r.readAsDataURL(input.files[0]);
}

function updateStatus(id, name) {
    const modal = document.getElementById('statusModal-' + id);
    if (modal) {
        new bootstrap.Modal(modal).show();
    }
}

function confirmStatusChange(id, newStatus) {
    if (!confirm('Change status to "' + newStatus + '"?')) return;
    fetch('/delivery/deliveries/' + id + '/status', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content || ''
        },
        body: JSON.stringify({ _method: 'PUT', delivery_status: newStatus })
    }).then(r => {
        if (r.ok || r.redirected) window.location.reload();
    }).catch(() => window.location.reload());
}
</script>