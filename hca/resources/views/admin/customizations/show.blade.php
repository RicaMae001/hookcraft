@extends('admin.layouts.admin')

@section('title', 'Customization Details')

@push('styles')
<style>
.customization-detail-container {
    font-family: 'Inter', -apple-system;
}

.detail-card {
    background: var(--card-bg);
    border-radius: 16px;
    padding: 2rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    border: 1px solid var(--border-color);
}

.detail-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid var(--border-color);
}

.detail-title {
    font-size: 24px;
    font-weight: 700;
    color: var(--text-primary);
}

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 16px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
}

.status-badge.pending {
    background: #fef3c7;
    color: #d97706;
}

.status-badge.approved {
    background: #d1fae5;
    color: #059669;
}

.status-badge.rejected {
    background: #fee2e2;
    color: #dc2626;
}

.status-badge.completed {
    background: #cffafe;
    color: #0891b2;
}

.detail-row {
    margin-bottom: 1.5rem;
}

.detail-label {
    font-size: 13px;
    font-weight: 600;
    color: var(--text-secondary);
    text-transform: uppercase;
    margin-bottom: 0.5rem;
}

.detail-value {
    font-size: 15px;
    color: var(--text-primary);
    line-height: 1.6;
}

.customer-info {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.customer-avatar {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: linear-gradient(135deg, #ec4899, #be185d);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 18px;
}

.customer-name {
    font-weight: 600;
    font-size: 16px;
    color: var(--text-primary);
}

.customer-email {
    font-size: 14px;
    color: var(--text-secondary);
}

.product-display {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.product-thumbnail {
    width: 80px;
    height: 80px;
    border-radius: 12px;
    object-fit: cover;
    border: 2px solid var(--border-color);
}

.product-info {
    flex: 1;
}

.product-name {
    font-weight: 600;
    font-size: 16px;
    color: var(--text-primary);
}

.product-price {
    font-size: 18px;
    font-weight: 700;
    color: #10b981;
    margin-top: 4px;
}

.detail-text-box {
    background: var(--light-bg);
    padding: 1rem;
    border-radius: 12px;
    border: 1px solid var(--border-color);
    white-space: pre-wrap;
}

.image-preview {
    max-width: 100%;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.update-form {
    background: var(--card-bg);
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-label {
    display: block;
    font-size: 14px;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 0.5rem;
}

.form-label.required::after {
    content: ' *';
    color: #ef4444;
}

.form-control {
    width: 100%;
    padding: 12px 16px;
    border: 2px solid var(--border-color);
    border-radius: 12px;
    font-size: 14px;
    transition: all 0.2s;
    background: var(--card-bg);
    color: var(--text-primary);
}

.form-control:focus {
    outline: none;
    border-color: #ec4899;
    box-shadow: 0 0 0 4px rgba(236, 72, 153, 0.1);
}

.form-help {
    font-size: 12px;
    color: var(--text-secondary);
    margin-top: 0.25rem;
}

.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 12px 24px;
    border-radius: 12px;
    font-size: 14px;
    font-weight: 600;
    border: none;
    cursor: pointer;
    transition: all 0.2s;
    text-decoration: none;
    width: 100%;
}

.btn-primary {
    background: linear-gradient(135deg, #ec4899, #be185d);
    color: white;
    box-shadow: 0 4px 12px rgba(236, 72, 153, 0.3);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(236, 72, 153, 0.4);
}

.btn-danger {
    background: #ef4444;
    color: white;
}

.btn-danger:hover {
    background: #dc2626;
    transform: translateY(-1px);
}

.btn-secondary {
    background: var(--light-bg);
    color: var(--text-primary);
    border: 2px solid var(--border-color);
}

.btn-secondary:hover {
    background: var(--hover-bg);
}

.alert {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem 1.25rem;
    border-radius: 12px;
    margin-bottom: 1.5rem;
    border: none;
}

.alert-success {
    background: linear-gradient(135deg, #d1fae5, #a7f3d0);
    color: #065f46;
    border-left: 4px solid #10b981;
}

.alert-danger {
    background: linear-gradient(135deg, #fee2e2, #fecaca);
    color: #991b1b;
    border-left: 4px solid #ef4444;
}

.alert i {
    font-size: 20px;
}

.price-summary {
    background: linear-gradient(135deg, #fef3c7, #fde68a);
    border-radius: 12px;
    padding: 1.5rem;
    margin-top: 1.5rem;
}

.price-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.5rem;
}

.price-label {
    font-size: 14px;
    font-weight: 600;
    color: #92400e;
}

.price-value {
    font-size: 20px;
    font-weight: 700;
    color: #92400e;
}
</style>
@endpush

@section('content')
<div class="customization-detail-container">
    <!-- Alerts -->
    @if(session('success'))
    <div class="alert alert-success">
        <i class="fas fa-check-circle"></i>
        <div>
            <strong>Success!</strong> {{ session('success') }}
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-circle"></i>
        <div>
            <strong>Error!</strong> {{ session('error') }}
        </div>
    </div>
    @endif

    @if($errors->any())
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-circle"></i>
        <div>
            <strong>Please fix the following errors:</strong>
            <ul style="margin: 0.5rem 0 0 1.5rem; padding: 0;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    <div class="row">
        <!-- Left Column - Details -->
        <div class="col-lg-8">
            <div class="detail-card">
                <div class="detail-header">
                    <h2 class="detail-title">Customization Request #{{ $customization->id }}</h2>
                    <span class="status-badge {{ strtolower($customization->status) }}">
                        @if($customization->status === 'Pending')
                            <i class="fas fa-clock"></i>
                        @elseif($customization->status === 'Approved')
                            <i class="fas fa-check-circle"></i>
                        @elseif($customization->status === 'Rejected')
                            <i class="fas fa-times-circle"></i>
                        @else
                            <i class="fas fa-check-double"></i>
                        @endif
                        {{ $customization->status }}
                    </span>
                </div>

                <!-- Customer Info -->
                <div class="detail-row">
                    <div class="detail-label">Customer</div>
                    <div class="customer-info">
                        <div class="customer-avatar">
                            {{ substr($customization->user->name ?? 'N', 0, 1) }}
                        </div>
                        <div>
                            <div class="customer-name">{{ $customization->user->name ?? 'Unknown' }}</div>
                            <div class="customer-email">{{ $customization->user->email ?? 'N/A' }}</div>
                        </div>
                    </div>
                </div>

                <hr>

                <!-- Product -->
                @if($customization->product)
                <div class="detail-row">
                    <div class="detail-label">Base Product</div>
                    <div class="product-display">
                        <img src="{{ asset('asset/images/' . $customization->product->image) }}" 
                             alt="{{ $customization->product->name }}"
                             class="product-thumbnail">
                        <div class="product-info">
                            <div class="product-name">{{ $customization->product->name }}</div>
                            <div class="product-price">₱{{ number_format($customization->product->price, 2) }}</div>
                        </div>
                    </div>
                </div>
                <hr>
                @endif

                <!-- Customization Name -->
                <div class="detail-row">
                    <div class="detail-label">Customization Name</div>
                    <div class="detail-value">{{ $customization->customization_name }}</div>
                </div>

                <!-- Customization Details -->
                <div class="detail-row">
                    <div class="detail-label">Customization Details</div>
                    <div class="detail-text-box">{{ $customization->customization_details }}</div>
                </div>

                <!-- Special Instructions -->
                @if($customization->special_instructions)
                <div class="detail-row">
                    <div class="detail-label">Special Instructions</div>
                    <div class="detail-text-box">{{ $customization->special_instructions }}</div>
                </div>
                @endif

                <!-- Custom Image -->
                @if($customization->custom_image)
                <div class="detail-row">
                    <div class="detail-label">Reference Image</div>
                    <img src="{{ asset('uploads/customizations/' . $customization->custom_image) }}" 
                         alt="Custom Image"
                         class="image-preview">
                </div>
                @endif

                <!-- Admin Notes (if set) -->
                @if($customization->admin_notes)
                <div class="detail-row">
                    <div class="detail-label">Admin Notes</div>
                    <div class="alert alert-info" style="margin: 0;">
                        <i class="fas fa-info-circle"></i>
                        <div>{{ $customization->admin_notes }}</div>
                    </div>
                </div>
                @endif

                <!-- Timestamps -->
                <div class="detail-row">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="detail-label">Created</div>
                            <div class="detail-value">{{ $customization->created_at->format('M d, Y h:i A') }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="detail-label">Last Updated</div>
                            <div class="detail-value">{{ $customization->updated_at->format('M d, Y h:i A') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column - Update Form -->
        <div class="col-lg-4">
            <div class="detail-card update-form">
                <h5 class="detail-title" style="font-size: 18px; margin-bottom: 1.5rem;">
                    <i class="fas fa-edit"></i> Update Status & Pricing
                </h5>

                <form action="{{ route('admin.customizations.update', $customization->id) }}" method="POST" id="updateForm">
                    @csrf
                    @method('PUT')

                    <!-- Status -->
                    <div class="form-group">
                        <label class="form-label required">Status</label>
                        <select name="status" class="form-control" required id="statusSelect">
                            <option value="Pending" {{ $customization->status === 'Pending' ? 'selected' : '' }}>Pending Review</option>
                            <option value="Approved" {{ $customization->status === 'Approved' ? 'selected' : '' }}>Approved</option>
                            <option value="Rejected" {{ $customization->status === 'Rejected' ? 'selected' : '' }}>Rejected</option>
                            <option value="Completed" {{ $customization->status === 'Completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                    </div>

                    <!-- Admin Price -->
                    <div class="form-group" id="priceGroup">
                        <label class="form-label" id="priceLabel">Admin Price (₱)</label>
                        <input type="number" 
                               name="admin_price" 
                               class="form-control" 
                               step="0.01" 
                               min="0"
                               value="{{ old('admin_price', $customization->admin_price) }}"
                               placeholder="Enter final price"
                               id="adminPriceInput">
                        <small class="form-help">The total price customer will pay (including customization fees)</small>
                    </div>

                    <!-- Admin Notes -->
                    <div class="form-group">
                        <label class="form-label">Admin Notes</label>
                        <textarea name="admin_notes" 
                                  class="form-control" 
                                  rows="4"
                                  placeholder="Add notes for the customer...">{{ old('admin_notes', $customization->admin_notes) }}</textarea>
                        <small class="form-help">Customer can see these notes</small>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Customization
                    </button>
                </form>

                <hr style="margin: 1.5rem 0;">

                <!-- Delete Button -->
                <form action="{{ route('admin.customizations.destroy', $customization->id) }}" 
                      method="POST" 
                      id="deleteForm"
                      onsubmit="return confirm('Are you sure you want to delete this customization request? This action cannot be undone.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash"></i> Delete Request
                    </button>
                </form>

                <!-- Price Summary -->
                @if($customization->admin_price)
                <div class="price-summary">
                    <div class="price-row">
                        <span class="price-label"><i class="fas fa-star"></i> Admin Set Price</span>
                        <span class="price-value">₱{{ number_format($customization->admin_price, 2) }}</span>
                    </div>
                </div>
                @endif

                <!-- Back Button -->
                <a href="{{ route('admin.customizations.index') }}" class="btn btn-secondary" style="margin-top: 1rem;">
                    <i class="fas fa-arrow-left"></i> Back to List
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const statusSelect = document.getElementById('statusSelect');
    const priceGroup = document.getElementById('priceGroup');
    const priceLabel = document.getElementById('priceLabel');
    const priceInput = document.getElementById('adminPriceInput');

    function updatePriceRequirement() {
        const status = statusSelect.value;
        
        if (status === 'Approved') {
            priceLabel.classList.add('required');
            priceInput.setAttribute('required', 'required');
        } else {
            priceLabel.classList.remove('required');
            priceInput.removeAttribute('required');
        }
    }

    statusSelect.addEventListener('change', updatePriceRequirement);
    
    // Run on page load
    updatePriceRequirement();
});
</script>
@endpush