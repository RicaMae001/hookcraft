@extends('admin.layouts.admin')

@section('title', 'User Management')

@section('content')
<div class="animate-fade-in">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 style="font-family: 'Playfair Display', serif; font-size: 2.25rem; font-weight: 700; margin-bottom: 0.5rem;">
                User Management
            </h1>
            <p style="color: var(--text-secondary); margin: 0;">Manage customer accounts and their information</p>
        </div>
        
        <div>
            <span class="badge-modern badge-info" style="font-size: 1rem; padding: 0.75rem 1.25rem;">
                <i class="fas fa-users me-2"></i>{{ $users->count() }} Total Users
            </span>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" style="border-radius: 12px; border-left: 4px solid var(--success);">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Users Table -->
    <div class="content-card">
        <div class="content-card-header">
            <h3 class="content-card-title">Customer List</h3>
            <div class="input-group" style="max-width: 300px;">
                <span class="input-group-text" style="background: var(--light-bg); border-right: none;">
                    <i class="fas fa-search"></i>
                </span>
                <input type="text" id="searchUser" class="form-control" placeholder="Search users..." style="border-left: none;">
            </div>
        </div>
        <div class="table-responsive">
            <table class="modern-table" id="usersTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Orders</th>
                        <th>Registered</th>
                        <th class="text-center" style="width: 150px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>
                                <span style="font-weight: 700; color: var(--text-secondary);">#{{ $user->id }}</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div style="width: 40px; height: 40px; border-radius: 10px; background: linear-gradient(135deg, var(--primary-pink), var(--secondary)); display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 14px;">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div style="font-weight: 600;">{{ $user->name }}</div>
                                </div>
                            </td>
                            <td>
                                <span style="color: var(--text-secondary); font-size: 0.875rem;">
                                    <i class="fas fa-envelope me-1"></i>{{ $user->email }}
                                </span>
                            </td>
                            <td>
                                @php
                                    $orderCount = DB::table('orders')->where('user_id', $user->id)->count();
                                @endphp
                                <span class="badge-modern badge-info">{{ $orderCount }} orders</span>
                            </td>
                            <td>
                                <span style="color: var(--text-secondary); font-size: 0.875rem;">
                                    <i class="fas fa-calendar me-1"></i>{{ \Carbon\Carbon::parse($user->created_at)->format('M d, Y') }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex gap-2 justify-content-center">
                                    <button class="btn btn-sm" style="background: var(--light-bg); border-radius: 8px; padding: 0.5rem 0.75rem;" 
                                            data-bs-toggle="modal" data-bs-target="#viewModal{{ $user->id }}" title="View Details">
                                        <i class="fas fa-eye" style="color: var(--secondary);"></i>
                                    </button>
                                    <button class="btn btn-sm" style="background: var(--light-bg); border-radius: 8px; padding: 0.5rem 0.75rem;" 
                                            data-bs-toggle="modal" data-bs-target="#editModal{{ $user->id }}" title="Edit User">
                                        <i class="fas fa-edit" style="color: var(--warning);"></i>
                                    </button>
                                    <button class="btn btn-sm" style="background: var(--light-bg); border-radius: 8px; padding: 0.5rem 0.75rem;"
                                            onclick="confirmDeleteUser({{ $user->id }}, '{{ $user->name }}', '{{ route('admin.users.delete', $user->id) }}')" title="Delete">
                                        <i class="fas fa-trash" style="color: var(--danger);"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- User Modals -->
@foreach($users as $user)
    <!-- View User Modal -->
    <div class="modal fade" id="viewModal{{ $user->id }}" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 16px; border: none;">
                <div class="modal-header" style="background: linear-gradient(135deg, var(--primary-pink), #FF8AAE); color: white; border-radius: 16px 16px 0 0;">
                    <h5 class="modal-title"><i class="fas fa-user-circle me-2"></i>User Details</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="text-center mb-4">
                        <div style="width: 80px; height: 80px; border-radius: 20px; background: linear-gradient(135deg, var(--primary-pink), var(--secondary)); display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 2rem; margin: 0 auto;">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <h4 style="margin-top: 1rem; font-weight: 700;">{{ $user->name }}</h4>
                    </div>
                    <table class="table table-borderless">
                        <tr>
                            <th width="150" style="color: var(--text-secondary);"><i class="fas fa-id-badge me-2"></i>User ID:</th>
                            <td><span style="font-weight: 700; color: var(--primary-pink);">#{{ $user->id }}</span></td>
                        </tr>
                        <tr>
                            <th style="color: var(--text-secondary);"><i class="fas fa-envelope me-2"></i>Email:</th>
                            <td>{{ $user->email }}</td>
                        </tr>
                        <tr>
                            <th style="color: var(--text-secondary);"><i class="fas fa-calendar-plus me-2"></i>Registered:</th>
                            <td>{{ \Carbon\Carbon::parse($user->created_at)->format('F d, Y h:i A') }}</td>
                        </tr>
                        <tr>
                            <th style="color: var(--text-secondary);"><i class="fas fa-shopping-bag me-2"></i>Total Orders:</th>
                            <td>
                                @php
                                    $orderCount = DB::table('orders')->where('user_id', $user->id)->count();
                                @endphp
                                <span class="badge-modern badge-success">{{ $orderCount }}</span>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="modal-footer" style="border: none;">
                    <button type="button" class="btn btn-modern-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div class="modal fade" id="editModal{{ $user->id }}" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 16px; border: none;">
                <form method="POST" action="{{ route('admin.users.update', $user->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="modal-header" style="background: linear-gradient(135deg, var(--warning), #F6C176); color: white; border-radius: 16px 16px 0 0;">
                        <h5 class="modal-title"><i class="fas fa-user-edit me-2"></i>Edit User</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label" style="font-weight: 600;">
                                <i class="fas fa-user me-2"></i>Name *
                            </label>
                            <input type="text" class="form-control" name="name" value="{{ $user->name }}" style="border-radius: 12px;" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" style="font-weight: 600;">
                                <i class="fas fa-envelope me-2"></i>Email *
                            </label>
                            <input type="email" class="form-control" name="email" value="{{ $user->email }}" style="border-radius: 12px;" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" style="font-weight: 600;">
                                <i class="fas fa-lock me-2"></i>New Password
                            </label>
                            <input type="password" class="form-control" name="password" placeholder="Leave blank to keep current" style="border-radius: 12px;">
                            <small style="color: var(--text-secondary);">Only fill this if you want to change the password</small>
                        </div>
                    </div>
                    <div class="modal-footer" style="border: none;">
                        <button type="button" class="btn btn-modern-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-modern-primary">
                            <i class="fas fa-save me-2"></i>Update User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach

<!-- Delete User Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 16px; border: none;">
            <div class="modal-header" style="background: var(--danger); color: white; border-radius: 16px 16px 0 0;">
                <h5 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i>Confirm Delete</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-5">
                <i class="fas fa-user-times fa-4x mb-4" style="color: var(--danger);"></i>
                <h5>Delete user <strong id="deleteUserName" style="color: var(--danger);"></strong>?</h5>
                <p style="color: var(--text-secondary);">This action cannot be undone and will delete all user data!</p>
            </div>
            <div class="modal-footer justify-content-center" style="border: none;">
                <form id="deleteUserForm" method="POST">
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
    function confirmDeleteUser(userId, userName, deleteUrl) {
        document.getElementById('deleteUserName').textContent = userName;
        document.getElementById('deleteUserForm').action = deleteUrl;
        new bootstrap.Modal(document.getElementById('deleteModal')).show();
    }

    document.getElementById('searchUser').addEventListener('keyup', function() {
        const searchValue = this.value.toLowerCase();
        const tableRows = document.querySelectorAll('#usersTable tbody tr');
        
        tableRows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchValue) ? '' : 'none';
        });
    });
</script>
@endpush