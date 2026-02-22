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

<style>
    /* Modern Info Item */
    .info-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px;
        background: var(--light-bg);
        border-radius: 10px;
        border: 1px solid var(--border-color);
        transition: all 0.2s ease;
    }

    .info-item:hover {
        background: var(--hover-bg);
        transform: translateY(-2px);
    }

    .info-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(102, 126, 234, 0.1);
        color: var(--primary-blue);
        font-size: 1.1rem;
    }

    .info-icon.text-success {
        background: rgba(72, 187, 120, 0.1);
        color: var(--success);
    }

    .info-icon.text-info {
        background: rgba(99, 179, 237, 0.1);
        color: var(--info);
    }

    .info-label {
        font-size: 0.75rem;
        color: var(--text-secondary);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 2px;
    }

    .info-value {
        font-size: 0.95rem;
        font-weight: 600;
        color: var(--text-primary);
    }

    /* Total Amount Display */
    .total-amount {
        text-align: right;
    }

    .total-label {
        display: block;
        font-size: 0.8rem;
        color: var(--text-secondary);
        font-weight: 500;
        margin-bottom: 2px;
    }

    .total-value {
        display: block;
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--primary-blue);
        background: linear-gradient(135deg, var(--primary-blue), var(--primary-purple));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    /* Enhanced Address */
    .order-address {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        padding: 12px;
        background: rgba(252, 129, 129, 0.05);
        border-radius: 10px;
        border-left: 4px solid var(--danger);
    }

    .order-address i {
        font-size: 1.1rem;
        margin-top: 2px;
    }

    .order-address span {
        color: var(--text-primary);
        font-size: 0.95rem;
        line-height: 1.5;
    }

    /* Animation Delays */
    .animate-fade-in {
        animation: fadeIn 0.5s ease forwards;
        opacity: 0;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .order-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }
        
        .info-item {
            padding: 10px;
        }
        
        .total-value {
            font-size: 1.25rem;
        }
    }
</style>