<div class="card order-card mb-3">
    <div class="card-body">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h5 class="card-title mb-2">
                    <span class="badge bg-secondary">#{{ $delivery->id }}</span>
                    <strong>{{ $delivery->customer_name }}</strong>
                </h5>
                <p class="mb-2">
                    <i class="fas fa-phone text-success me-2"></i>
                    <strong>Phone:</strong> {{ $delivery->phone }}
                </p>
                <p class="mb-2">
                    <i class="fas fa-map-marker-alt text-danger me-2"></i>
                    <strong>Address:</strong> {{ $delivery->address }}
                </p>
                <p class="mb-2">
                    <i class="fas fa-dollar-sign text-primary me-2"></i>
                    <strong>Total:</strong> ₱{{ number_format($delivery->total, 2) }}
                </p>
                <p class="mb-0">
                    <i class="fas fa-calendar text-info me-2"></i>
                    <strong>Order Date:</strong> {{ \Carbon\Carbon::parse($delivery->created_at)->format('M d, Y h:i A') }}
                </p>
            </div>
            <div class="col-md-4 text-end">
                <div class="mb-3">
                    @if($delivery->delivery_status == 'Delivered')
                        <span class="badge bg-success fs-6 px-3 py-2">
                            <i class="fas fa-check-circle me-1"></i>Delivered
                        </span>
                    @elseif($delivery->delivery_status == 'Out for Delivery')
                        <span class="badge bg-info fs-6 px-3 py-2">
                            <i class="fas fa-shipping-fast me-1"></i>Out for Delivery
                        </span>
                    @elseif($delivery->delivery_status == 'Cancelled')
                        <span class="badge bg-danger fs-6 px-3 py-2">
                            <i class="fas fa-times-circle me-1"></i>Cancelled
                        </span>
                    @else
                        <span class="badge bg-warning text-dark fs-6 px-3 py-2">
                            <i class="fas fa-clock me-1"></i>Pending
                        </span>
                    @endif
                </div>
                <div class="mb-3">
                    @if($delivery->payment_status == 'Paid')
                        <span class="badge bg-success">
                            <i class="fas fa-check me-1"></i>Payment: Paid
                        </span>
                    @else
                        <span class="badge bg-warning text-dark">
                            <i class="fas fa-clock me-1"></i>Payment: {{ $delivery->payment_status }}
                        </span>
                    @endif
                </div>
                <button type="button" class="btn btn-primary btn-sm" onclick="updateStatus({{ $delivery->id }}, '{{ addslashes($delivery->customer_name) }}')">
                    <i class="fas fa-edit me-1"></i>Update Status
                </button>
            </div>
        </div>
    </div>
</div>