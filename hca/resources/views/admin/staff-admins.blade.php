<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Accounts - Staff Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .btn-pink {
            background-color: #FFB6C1;
            color: white;
            border: none;
        }
        .btn-pink:hover {
            background-color: #FF9EAD;
            color: white;
        }
        .bg-pink {
            background-color: #FFB6C1;
        }
        .card {
            border: none;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            border-radius: 10px;
        }
        main {
            margin-left: 0;
        }
        @media (min-width: 768px) {
            main {
                margin-left: 250px;
            }
        }
    </style>
</head>
<body>
    @include('admin.layouts.navbar')
    @include('admin.layouts.sidebar')

    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-4 border-bottom">
            <h1 class="h2"><i class="fas fa-user-shield text-pink me-2"></i>Admin Accounts</h1>
            <button class="btn btn-pink" data-bs-toggle="modal" data-bs-target="#addAdminModal">
                <i class="fas fa-plus-circle me-2"></i>Add New Admin
            </button>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0"><i class="fas fa-list me-2"></i>Admin Staff ({{ $admins->count() }})</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
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
                                    <td><span class="badge bg-secondary">#{{ $admin->id }}</span></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar bg-pink text-white rounded-circle me-2 d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                                                {{ strtoupper(substr($admin->name, 0, 1)) }}
                                            </div>
                                            <strong>{{ $admin->name }}</strong>
                                            @if($admin->id == session('admin_id'))
                                                <span class="badge bg-success ms-2">You</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td><i class="fas fa-envelope text-muted me-2"></i>{{ $admin->email }}</td>
                                    <td>
                                        @if($admin->role == 'SuperAdmin')
                                            <span class="badge bg-danger"><i class="fas fa-crown me-1"></i>Super Admin</span>
                                        @else
                                            <span class="badge bg-info">Staff</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal{{ $admin->id }}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        @if($admin->id != session('admin_id'))
                                            <button class="btn btn-sm btn-danger"
                                                    onclick="confirmDeleteAdmin({{ $admin->id }}, '{{ $admin->name }}', '{{ route('admin.staff.admins.delete', $admin->id) }}')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        @endif
                                    </td>
                                </tr>

                                <!-- Edit Modal -->
                                <div class="modal fade" id="editModal{{ $admin->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form method="POST" action="{{ route('admin.staff.admins.update', $admin->id) }}">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-header bg-pink text-white">
                                                    <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Edit Admin</h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label">Name *</label>
                                                        <input type="text" name="name" class="form-control" value="{{ $admin->name }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Email *</label>
                                                        <input type="email" name="email" class="form-control" value="{{ $admin->email }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Role *</label>
                                                        <select name="role" class="form-select" required>
                                                            <option value="Staff" {{ $admin->role == 'Staff' ? 'selected' : '' }}>Staff</option>
                                                            <option value="SuperAdmin" {{ $admin->role == 'SuperAdmin' ? 'selected' : '' }}>Super Admin</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">New Password (leave empty to keep current)</label>
                                                        <input type="password" name="password" class="form-control" minlength="6">
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-pink">Update</button>
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
    </main>

    <!-- Add Admin Modal -->
    <div class="modal fade" id="addAdminModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route('admin.staff.admins.store') }}">
                    @csrf
                    <div class="modal-header bg-pink text-white">
                        <h5 class="modal-title"><i class="fas fa-plus-circle me-2"></i>Add New Admin</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Full Name *</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email Address *</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password *</label>
                            <input type="password" name="password" class="form-control" minlength="6" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Role *</label>
                            <select name="role" class="form-select" required>
                                <option value="Staff">Staff</option>
                                <option value="SuperAdmin">Super Admin</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-pink">Create Admin</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Admin Confirmation Modal -->
    <div class="modal fade" id="deleteAdminModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i>Confirm Delete</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <i class="fas fa-user-shield fa-4x text-danger mb-3"></i>
                    <h5>Are you sure you want to delete admin <strong id="deleteAdminName"></strong>?</h5>
                    <p class="text-muted">This action cannot be undone!</p>
                </div>
                <div class="modal-footer justify-content-center">
                    <form id="deleteAdminForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function confirmDeleteAdmin(adminId, adminName, deleteUrl) {
            document.getElementById('deleteAdminName').textContent = adminName;
            document.getElementById('deleteAdminForm').action = deleteUrl;
            new bootstrap.Modal(document.getElementById('deleteAdminModal')).show();
        }
    </script>
</body>
</html>