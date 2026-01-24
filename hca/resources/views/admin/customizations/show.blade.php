@extends('admin.layouts.admin')

@section('title', 'Customization Details')

@section('page-title', 'Customization Request #' . $customization->id)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.customizations.index') }}">Customizations</a></li>
    <li class="breadcrumb-item active">Request #{{ $customization->id }}</li>
@endsection

@section('content')
<div class="row">
    <!-- Customization Details -->
    <div class="col-md-8">
        <div class="admin-card">
            <h5 class="mb-3"><i class="fas fa-info-circle"></i> Customization Details</h5>
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <strong>Customer:</strong><br>
                    <i class="fas fa-user"></i> {{ $customization->user->name ?? 'Unknown' }}<br>
                    <small class="text-muted">{{ $customization->user->email ?? 'N/A' }}</small>
                </div>
                <div class="col-md-6">
                    <strong>Status:</strong><br>
                    @if($customization->status === 'Pending')
                        <span class="badge bg-warning">Pending Review</span>
                    @elseif($customization->status === 'Approved')
                        <span class="badge bg-success">Approved</span>
                    @elseif($customization->status === 'Rejected')
                        <span class="badge bg-danger">Rejected</span>
                    @elseif($customization->status === 'Completed')
                        <span class="badge bg-info">Completed</span>
                    @endif
                </div>
            </div>

            <hr>

            <div class="mb-3">
                <strong>Based on Product:</strong><br>
                @if($customization->product)
                    <span class="badge bg-secondary">{{ $customization->product->name }}</span>
                @else
                    <span class="text-muted">No base product</span>
                @endif
            </div>

            <div class="mb-3">
                <strong>Customization Name:</strong><br>
                {{ $customization->customization_name }}
            </div>

            <div class="mb-3">
                <strong>Customization Details:</strong><br>
                <div class="bg-light p-3 rounded">
                    {{ $customization->customization_details }}
                </div>
            </div>

            @if($customization->special_instructions)
            <div class="mb-3">
                <strong>Special Instructions:</strong><br>
                <div class="bg-light p-3 rounded">
                    {{ $customization->special_instructions }}
                </div>
            </div>
            @endif

            @if($customization->custom_image)
            <div class="mb-3">
                <strong>Custom Image:</strong><br>
                <img src="{{ asset('uploads/customizations/' . $customization->custom_image) }}" 
                     class="img-fluid rounded mt-2" 
                     style="max-width: 400px;"
                     alt="Custom Image">
            </div>
            @endif

            @if($customization->admin_notes)
            <div class="mb-3">
                <strong>Admin Notes:</strong><br>
                <div class="alert alert-info">
                    {{ $customization->admin_notes }}
                </div>
            </div>
            @endif

            <div class="row">
                <div class="col-md-6">
                    <small class="text-muted">
                        <strong>Submitted:</strong> {{ $customization->created_at->format('M d, Y h:i A') }}
                    </small>
                </div>
                <div class="col-md-6">
                    <small class="text-muted">
                        <strong>Last Updated:</strong> {{ $customization->updated_at->format('M d, Y h:i A') }}
                    </small>
                </div>
            </div>
        </div>
    </div>

    <!-- Update Form -->
    <div class="col-md-4">
        <div class="admin-card">
            <h5 class="mb-3"><i class="fas fa-edit"></i> Update Status</h5>
            
            <form action="{{ route('admin.customizations.update', $customization->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Status *</label>
                    <select name="status" class="form-select" required>
                        <option value="Pending" {{ $customization->status === 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Approved" {{ $customization->status === 'Approved' ? 'selected' : '' }}>Approved</option>
                        <option value="Rejected" {{ $customization->status === 'Rejected' ? 'selected' : '' }}>Rejected</option>
                        <option value="Completed" {{ $customization->status === 'Completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Admin Price (₱)</label>
                    <input type="number" 
                           name="admin_price" 
                           class="form-control" 
                           step="0.01" 
                           min="0"
                           value="{{ old('admin_price', $customization->admin_price) }}"
                           placeholder="Enter price if approved">
                    <small class="text-muted">Required when status is Approved</small>
                </div>

                <div class="mb-3">
                    <label class="form-label">Admin Notes</label>
                    <textarea name="admin_notes" 
                              class="form-control" 
                              rows="4"
                              placeholder="Add notes for customer...">{{ old('admin_notes', $customization->admin_notes) }}</textarea>
                </div>

                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-save"></i> Update Customization
                </button>
            </form>

            <hr>

            <button type="button" 
                    class="btn btn-danger w-100" 
                    onclick="deleteCustomization()">
                <i class="fas fa-trash"></i> Delete Request
            </button>
        </div>

        <!-- Price Summary -->
        @if($customization->admin_price)
        <div class="admin-card mt-3">
            <h6 class="mb-3">Price Summary</h6>
            <div class="d-flex justify-content-between">
                <strong>Admin Price:</strong>
                <strong class="text-success">₱{{ number_format($customization->admin_price, 2) }}</strong>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Delete Form -->
<form id="delete-form" 
      action="{{ route('admin.customizations.destroy', $customization->id) }}" 
      method="POST" 
      style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('scripts')
<script>
function deleteCustomization() {
    if (confirm('Are you sure you want to delete this customization request? This action cannot be undone.')) {
        document.getElementById('delete-form').submit();
    }
}
</script>
@endpush