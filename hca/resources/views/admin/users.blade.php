<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }
        .content-wrapper {
            margin-left: 250px;
            padding: 20px;
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
        .text-pink {
            color: #FFB6C1;
        }
        .card {
            border: none;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            border-radius: 10px;
        }
        .table thead th {
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
        }
        @media (max-width: 768px) {
            .content-wrapper {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    @include('admin.layouts.navbar')
    @include('admin.layouts.sidebar')

    <div class="content-wrapper">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-4 border-bottom">
            <h1 class="h2"><i class="fas fa-users text-pink me-2"></i>User Management</h1>
            <div class="btn-toolbar mb-2 mb-md-0">
                <span class="badge bg-pink fs-6 px-3 py-2">
                    <i class="fas fa-user-friends me-2"></i>Total Users: {{ $users->count() }}
                </span>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card">
            <div class="card-header bg-white py-3">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h5 class="mb-0"><i class="fas fa-list me-2"></i>All Users</h5>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="fas fa-search"></i></span>
                            <input type="text" id="searchUser" class="form-control border-start-0" placeholder="Search by name or email...">
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="usersTable">
                        <thead>
                            <tr>
                                <th style="width: 80px;">ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Orders</th>
                                <th>Registered Date</th>
                                <th style="width: 150px;" class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td><span class="badge bg-secondary">#{{ $user->id }}</span></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar bg-pink text-white rounded-circle me-2 d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </div>
                                            <strong>{{ $user->name }}</strong>
                                        </div>
                                    </td>
                                    <td><i class="fas fa-envelope text-muted me-2"></i>{{ $user->email }}</td>
                                    <td>
                                        @php
                                            $orderCount = DB::table('orders')->where('user_id', $user->id)->count();
                                        @endphp
                                        <span class="badge bg-info">{{ $orderCount }} orders</span>
                                    </td>
                                    <td><i class="fas fa-calendar text-muted me-2"></i>{{ \Carbon\Carbon::parse($user->created_at)->format('M d, Y') }}</td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#viewModal{{ $user->id }}" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal{{ $user->id }}" title="Edit User">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger" onclick="confirmDelete({{ $user->id }}, '{{ $user->name }}')" title="Delete User">
                                                <i class="fas fa-trash"></i>
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
    </div>

    <!-- View User Modals -->
    @foreach($users as $user)
        <div class="modal fade" id="viewModal{{ $user->id }}" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-pink text-white">
                        <h5 class="modal-title"><i class="fas fa-user-circle me-2"></i>User Details</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="text-center mb-4">
                            <div class="avatar bg-pink text-white rounded-circle mx-auto d-flex align-items-center justify-content-center" style="width: 80px; height: 80px; font-size: 2rem;">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <h4 class="mt-3">{{ $user->name }}</h4>
                        </div>
                        <table class="table table-borderless">
                            <tr>
                                <th width="150"><i class="fas fa-id-badge text-muted me-2"></i>User ID:</th>
                                <td><span class="badge bg-secondary">#{{ $user->id }}</span></td>
                            </tr>
                            <tr>
                                <th><i class="fas fa-envelope text-muted me-2"></i>Email:</th>
                                <td>{{ $user->email }}</td>
                            </tr>
                            <tr>
                                <th><i class="fas fa-calendar-plus text-muted me-2"></i>Registered:</th>
                                <td>{{ \Carbon\Carbon::parse($user->created_at)->format('F d, Y h:i A') }}</td>
                            </tr>
                            <tr>
                                <th><i class="fas fa-shopping-bag text-muted me-2"></i>Total Orders:</th>
                                <td>
                                    @php
                                        $orderCount = DB::table('orders')->where('user_id', $user->id)->count();
                                    @endphp
                                    <span class="badge bg-success">{{ $orderCount }}</span>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit User Modal -->
        <div class="modal fade" id="editModal{{ $user->id }}" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-warning text-dark">
                        <h5 class="modal-title"><i class="fas fa-user-edit me-2"></i>Edit User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form method="POST" action="/admin/users/{{ $user->id }}">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="name{{ $user->id }}" class="form-label">
                                    <i class="fas fa-user me-2"></i>Name
                                </label>
                                <input type="text" class="form-control" id="name{{ $user->id }}" name="name" value="{{ $user->name }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="email{{ $user->id }}" class="form-label">
                                    <i class="fas fa-envelope me-2"></i>Email
                                </label>
                                <input type="email" class="form-control" id="email{{ $user->id }}" name="email" value="{{ $user->email }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="password{{ $user->id }}" class="form-label">
                                    <i class="fas fa-lock me-2"></i>New Password (leave blank to keep current)
                                </label>
                                <input type="password" class="form-control" id="password{{ $user->id }}" name="password" placeholder="Enter new password (optional)">
                                <small class="text-muted">Only fill this if you want to change the password</small>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-save me-2"></i>Update User
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i>Confirm Delete</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <i class="fas fa-user-times fa-4x text-danger mb-3"></i>
                    <h5>Are you sure you want to delete user <strong id="deleteUserName"></strong>?</h5>
                    <p class="text-muted">This action cannot be undone and will delete all user data!</p>
                </div>
                <div class="modal-footer justify-content-center">
                    <form id="deleteForm" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger px-4">Delete User</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function confirmDelete(userId, userName) {
            document.getElementById('deleteUserName').textContent = userName;
            document.getElementById('deleteForm').action = '/admin/users/' + userId;
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
</body>
</html>