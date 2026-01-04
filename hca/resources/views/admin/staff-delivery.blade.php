@extends('admin.layouts.admin')

@section('title', 'Delivery Staff')

@section('content')
<div class="animate-fade-in">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 style="font-family: 'Playfair Display', serif; font-size: 2.25rem; font-weight: 700; margin-bottom: 0.5rem;">
                Delivery Staff
            </h1>
            <p style="color: var(--text-secondary); margin: 0;">Manage delivery coordinators and their assignments</p>
        </div>
        <button class="btn btn-modern-primary" data-bs-toggle="modal" data-bs-target="#addDeliveryModal">
            <i class="fas fa-plus-circle me-2"></i>Add Delivery Staff
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" style="border-radius: 12px; border-left: 4px solid var(--success);">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" style="border-radius: 12px; border-left: 4px solid var(--danger);">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-card-icon primary">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-card-label">Total Coordinators</div>
                <div class="stat-card-value">{{ $coordinators->count() }}</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-card-icon success">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-card-label">Active</div>
                <div class="stat-card-value">{{ $coordinators->where('status', 'Active')->count() }}</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-card-icon" style="background: linear-gradient(135deg, rgba(252, 129, 129, 0.15), rgba(252, 129, 129, 0.05)); color: var(--danger);">
                    <i class="fas fa-ban"></i>
                </div>
                <div class="stat-card-label">Inactive</div>
                <div class="stat-card-value">{{ $coordinators->where('status', 'Inactive')->count() }}</div>
            </div>
        </div>
    </div>

    <div class="content-card">
        <div class="content-card-header">
            <h3 class="content-card-title">Delivery Coordinators</h3>
        </div>
        <div class="table-responsive">
            <table class="modern-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Status</th>
                        <th>Joined</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($coordinators as $coordinator)
                        <tr>
                            <td><span style="font-weight: 700; color: var(--text-secondary);">#{{ $coordinator->coordinator_id }}</span></td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div style="width: 40px; height: 40px; border-radius: 10px; background: linear-gradient(135deg, var(--secondary), #7E8DEA); display: flex; align-items-center; justify-content: center; color: white; font-weight: 700; font-size: 14px;">
                                        {{ strtoupper(substr($coordinator->name, 0, 1)) }}
                                    </div>
                                    <div style="font-weight: 600;">{{ $coordinator->name }}</div>
                                </div>
                            </td>
                            <td><span style="color: var(--text-secondary);"><i class="fas fa-envelope me-1"></i>{{ $coordinator->email }}</span></td>
                            <td><span style="color: var(--text-secondary);"><i class="fas fa-phone me-1"></i>{{ $coordinator->phone ?: 'N/A' }}</span></td>
                            <td>
                                @if($coordinator->status == 'Active')
                                    <span class="badge-modern badge-success"><i class="fas fa-check me-1"></i>Active</span>
                                @else
                                    <span class="badge-modern badge-danger"><i class="fas fa-ban me-1"></i>Inactive</span>
                                @endif
                            </td>
                            <td><span style="color: var(--text-secondary); font-size: 0.875rem;">{{ \Carbon\Carbon::parse($coordinator->created_at)->format('M d, Y') }}</span></td>
                            <td>
                                <div class="d-flex gap-2 justify-content-center">
                                    <button class="btn btn-sm" style="background: var(--light-bg); border-radius: 8px; padding: 0.5rem 0.75rem;" 
                                            data-bs-toggle="modal" data-bs-target="#editModal{{ $coordinator->coordinator_id }}">
                                        <i class="fas fa-edit" style="color: var(--warning);"></i>
                                    </button>
                                    <button class="btn btn-sm" style="background: var(--light-bg); border-radius: 8px; padding: 0.5rem 0.75rem;"
                                            onclick="confirmDeleteDelivery({{ $coordinator->coordinator_id }}, '{{ $coordinator->name }}', '{{ route('admin.staff.delivery.delete', $coordinator->coordinator_id) }}')">
                                        <i class="fas fa-trash" style="color: var(--danger);"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <div class="modal fade" id="editModal{{ $coordinator->coordinator_id }}" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content" style="border-radius: 16px; border: none;">
                                    <form method="POST" action="{{ route('admin.staff.delivery.update', $coordinator->coordinator_id) }}">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-header" style="background: linear-gradient(135deg, var(--warning), #F6C176); color: white; border-radius: 16px 16px 0 0;">
                                            <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Edit Delivery Staff</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body p-4">
                                            <div class="mb-3">
                                                <label class="form-label" style="font-weight: 600;">Name *</label>
                                                <input type="text" name="name" class="form-control" value="{{ $coordinator->name }}" style="border-radius: 12px;" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label" style="font-weight: 600;">Email *</label>
                                                <input type="email" name="email" class="form-control" value="{{ $coordinator->email }}" style="border-radius: 12px;" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label" style="font-weight: 600;">Phone</label>
                                                <input type="text" name="phone" class="form-control" value="{{ $coordinator->phone }}" style="border-radius: 12px;">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label" style="font-weight: 600;">Status *</label>
                                                <select name="status" class="form-select" style="border-radius: 12px;" required>
                                                    <option value="Active" {{ $coordinator->status == 'Active' ? 'selected' : '' }}>Active</option>
                                                    <option value="Inactive" {{ $coordinator->status == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label" style="font-weight: 600;">New Password</label>
                                                <input type="password" name="password" class="form-control" minlength="6" placeholder="Leave blank to keep current" style="border-radius: 12px;">
                                            </div>
                                        </div>
                                        <div class="modal-footer" style="border: none;">
                                            <button type="button" class="btn btn-modern-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-modern-primary"><i class="fas fa-save me-2"></i>Update</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <i class="fas fa-users fa-3x mb-3" style="color: var(--border-color);"></i>
                                <p style="color: var(--text-secondary);">No delivery staff added yet</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="addDeliveryModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 16px; border: none;">
            <form method="POST" action="{{ route('admin.staff.delivery.store') }}">
                @csrf
                <div class="modal-header" style="background: linear-gradient(135deg, var(--primary-pink), #FF8AAE); color: white; border-radius: 16px 16px 0 0;">
                    <h5 class="modal-title"><i class="fas fa-plus-circle me-2"></i>Add Delivery Staff</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label" style="font-weight: 600;">Full Name *</label>
                        <input type="text" name="name" class="form-control" style="border-radius: 12px;" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" style="font-weight: 600;">Email Address *</label>
                        <input type="email" name="email" class="form-control" style="border-radius: 12px;" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" style="font-weight: 600;">Phone Number</label>
                        <input type="text" name="phone" class="form-control" placeholder="Optional" style="border-radius: 12px;">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" style="font-weight: 600;">Password *</label>
                        <input type="password" name="password" class="form-control" minlength="6" style="border-radius: 12px;" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" style="font-weight: 600;">Status *</label>
                        <select name="status" class="form-select" style="border-radius: 12px;" required>
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer" style="border: none;">
                    <button type="button" class="btn btn-modern-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-modern-primary"><i class="fas fa-plus me-2"></i>Create Staff</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteDeliveryModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 16px; border: none;">
            <div class="modal-header" style="background: var(--danger); color: white; border-radius: 16px 16px 0 0;">
                <h5 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i>Confirm Delete</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-5">
                <i class="fas fa-truck fa-4x mb-4" style="color: var(--danger);"></i>
                <h5>Delete delivery staff <strong id="deleteDeliveryName" style="color: var(--danger);"></strong>?</h5>
                <p style="color: var(--text-secondary);">This action cannot be undone!</p>
            </div>
            <div class="modal-footer justify-content-center" style="border: none;">
                <form id="deleteDeliveryForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-modern-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn" style="background: var(--danger); color: white; padding: 0.75rem 1.5rem; border-radius: 12px; font-weight: 600;">
                        <i class="fas fa-trash me-2"></i>Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function confirmDeleteDelivery(deliveryId, deliveryName, deleteUrl) {
        document.getElementById('deleteDeliveryName').textContent = deliveryName;
        document.getElementById('deleteDeliveryForm').action = deleteUrl;
        new bootstrap.Modal(document.getElementById('deleteDeliveryModal')).show();
    }
</script>
@endpush