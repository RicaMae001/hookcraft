@extends('admin.layouts.admin')

@section('title', 'Admin Accounts')

@section('content')
<div class="animate-fade-in">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 style="font-family: 'Playfair Display', serif; font-size: 2.25rem; font-weight: 700; margin-bottom: 0.5rem;">
                Admin Accounts
            </h1>
            <p style="color: var(--text-secondary); margin: 0;">Manage administrative staff accounts and permissions</p>
        </div>
        <button class="btn btn-modern-primary" data-bs-toggle="modal" data-bs-target="#addAdminModal">
            <i class="fas fa-plus-circle me-2"></i>Add New Admin
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

    <div class="content-card">
        <div class="content-card-header">
            <h3 class="content-card-title">Admin Staff ({{ $admins->count() }})</h3>
        </div>
        <div class="table-responsive">
            <table class="modern-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($admins as $admin)
                        <tr>
                            <td><span style="font-weight: 700; color: var(--text-secondary);">#{{ $admin->id }}</span></td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div style="width: 40px; height: 40px; border-radius: 10px; background: linear-gradient(135deg, var(--primary-pink), var(--secondary)); display: flex; align-items-center; justify-content: center; color: white; font-weight: 700; font-size: 14px;">
                                        {{ strtoupper(substr($admin->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div style="font-weight: 600;">{{ $admin->name }}</div>
                                        @if($admin->id == session('admin_id'))
                                            <span class="badge-modern badge-success" style="font-size: 0.7rem;">You</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td><span style="color: var(--text-secondary);"><i class="fas fa-envelope me-1"></i>{{ $admin->email }}</span></td>
                            <td>
                                @if($admin->role == 'SuperAdmin')
                                    <span class="badge-modern badge-danger"><i class="fas fa-crown me-1"></i>Super Admin</span>
                                @else
                                    <span class="badge-modern badge-info">Staff</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-2 justify-content-center">
                                    <button class="btn btn-sm" style="background: var(--light-bg); border-radius: 8px; padding: 0.5rem 0.75rem;" 
                                            data-bs-toggle="modal" data-bs-target="#editModal{{ $admin->id }}">
                                        <i class="fas fa-edit" style="color: var(--warning);"></i>
                                    </button>
                                    @if($admin->id != session('admin_id'))
                                        <button class="btn btn-sm" style="background: var(--light-bg); border-radius: 8px; padding: 0.5rem 0.75rem;"
                                                onclick="confirmDeleteAdmin({{ $admin->id }}, '{{ $admin->name }}', '{{ route('admin.staff.admins.delete', $admin->id) }}')">
                                            <i class="fas fa-trash" style="color: var(--danger);"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>

                        <div class="modal fade" id="editModal{{ $admin->id }}" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content" style="border-radius: 16px; border: none;">
                                    <form method="POST" action="{{ route('admin.staff.admins.update', $admin->id) }}">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-header" style="background: linear-gradient(135deg, var(--warning), #F6C176); color: white; border-radius: 16px 16px 0 0;">
                                            <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Edit Admin</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body p-4">
                                            <div class="mb-3">
                                                <label class="form-label" style="font-weight: 600;">Name *</label>
                                                <input type="text" name="name" class="form-control" value="{{ $admin->name }}" style="border-radius: 12px;" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label" style="font-weight: 600;">Email *</label>
                                                <input type="email" name="email" class="form-control" value="{{ $admin->email }}" style="border-radius: 12px;" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label" style="font-weight: 600;">Role *</label>
                                                <select name="role" class="form-select" style="border-radius: 12px;" required>
                                                    <option value="Staff" {{ $admin->role == 'Staff' ? 'selected' : '' }}>Staff</option>
                                                    <option value="SuperAdmin" {{ $admin->role == 'SuperAdmin' ? 'selected' : '' }}>Super Admin</option>
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
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="addAdminModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 16px; border: none;">
            <form method="POST" action="{{ route('admin.staff.admins.store') }}">
                @csrf
                <div class="modal-header" style="background: linear-gradient(135deg, var(--primary-pink), #FF8AAE); color: white; border-radius: 16px 16px 0 0;">
                    <h5 class="modal-title"><i class="fas fa-plus-circle me-2"></i>Add New Admin</h5>
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
                        <label class="form-label" style="font-weight: 600;">Password *</label>
                        <input type="password" name="password" class="form-control" minlength="6" style="border-radius: 12px;" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" style="font-weight: 600;">Role *</label>
                        <select name="role" class="form-select" style="border-radius: 12px;" required>
                            <option value="Staff">Staff</option>
                            <option value="SuperAdmin">Super Admin</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer" style="border: none;">
                    <button type="button" class="btn btn-modern-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-modern-primary"><i class="fas fa-plus me-2"></i>Create Admin</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteAdminModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 16px; border: none;">
            <div class="modal-header" style="background: var(--danger); color: white; border-radius: 16px 16px 0 0;">
                <h5 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i>Confirm Delete</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-5">
                <i class="fas fa-user-shield fa-4x mb-4" style="color: var(--danger);"></i>
                <h5>Delete admin <strong id="deleteAdminName" style="color: var(--danger);"></strong>?</h5>
                <p style="color: var(--text-secondary);">This action cannot be undone!</p>
            </div>
            <div class="modal-footer justify-content-center" style="border: none;">
                <form id="deleteAdminForm" method="POST">
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
    function confirmDeleteAdmin(adminId, adminName, deleteUrl) {
        document.getElementById('deleteAdminName').textContent = adminName;
        document.getElementById('deleteAdminForm').action = deleteUrl;
        new bootstrap.Modal(document.getElementById('deleteAdminModal')).show();
    }
</script>
@endpush