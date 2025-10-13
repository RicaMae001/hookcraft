<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delivery Staff - Staff Management</title>
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
            <h1 class="h2"><i class="fas fa-truck text-primary me-2"></i>Delivery Staff</h1>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addDeliveryModal">
                <i class="fas fa-plus-circle me-2"></i>Add Delivery Staff
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

        <div class="row mb-3">
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-users fa-2x text-primary mb-2"></i>
                        <h5 class="mb-0">{{ $coordinators->count() }}</h5>
                        <small class="text-muted">Total Coordinators</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                        <h5 class="mb-0">{{ $coordinators->where('status', 'Active')->count() }}</h5>
                        <small class="text-muted">Active</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-ban fa-2x text-danger mb-2"></i>
                        <h5 class="mb-0">{{ $coordinators->where('status', 'Inactive')->count() }}</h5>
                        <small class="text-muted">Inactive</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0"><i class="fas fa-list me-2"></i>Delivery Coordinators</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Status</th>
                                <th>Joined Date</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($coordinators as $coordinator)
                                <tr>
                                    <td><span class="badge bg-secondary">#{{ $coordinator->coordinator_id }}</span></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar bg-primary text-white rounded-circle me-2 d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                                                {{ strtoupper(substr($coordinator->name, 0, 1)) }}
                                            </div>
                                            <strong>{{ $coordinator->name }}</strong>
                                        </div>
                                    </td>
                                    <td><i class="fas fa-envelope text-muted me-2"></i>{{ $coordinator->email }}</td>
                                    <td><i class="fas fa-phone text-muted me-2"></i>{{ $coordinator->phone ?: 'N/A' }}</td>
                                    <td>
                                        @if($coordinator->status == 'Active')
                                            <span class="badge bg-success"><i class="fas fa-check me-1"></i>Active</span>
                                        @else
                                            <span class="badge bg-danger"><i class="fas fa-ban me-1"></i>Inactive</span>
                                        @endif
                                    </td>
                                    <td><small>{{ \Carbon\Carbon::parse($coordinator->created_at)->format('M d, Y') }}</small></td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal{{ $coordinator->coordinator_id }}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger" onclick="confirmDelete({{ $coordinator->coordinator_id }}, '{{ $coordinator->name }}')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>

                                <!-- Edit Modal -->
                                <div class="modal fade" id="editModal{{ $coordinator->coordinator_id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form method="POST" action="{{ route('admin.staff.delivery.update', $coordinator->coordinator_id) }}">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-header bg-primary text-white">
                                                    <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Edit Delivery Staff</h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label">Name *</label>
                                                        <input type="text" name="name" class="form-control" value="{{ $coordinator->name }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Email *</label>
                                                        <input type="email" name="email" class="form-control" value="{{ $coordinator->email }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Phone</label>
                                                        <input type="text" name="phone" class="form-control" value="{{ $coordinator->phone }}">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Status *</label>
                                                        <select name="status" class="form-select" required>
                                                            <option value="Active" {{ $coordinator->status == 'Active' ? 'selected' : '' }}>Active</option>
                                                            <option value="Inactive" {{ $coordinator->status == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">New Password (leave empty to keep current)</label>
                                                        <input type="password" name="password" class="form-control" minlength="6">
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-primary">Update</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">
                                        <i class="fas fa-users fa-3x mb-3"></i>
                                        <p>No delivery staff added yet</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <!-- Add Delivery Staff Modal -->
    <div class="modal fade" id="addDeliveryModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route('admin.staff.delivery.store') }}">
                    @csrf
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title"><i class="fas fa-plus-circle me-2"></i>Add Delivery Staff</h5>
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
                            <label class="form-label">Phone Number</label>
                            <input type="text" name="phone" class="form-control" placeholder="Optional">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password *</label>
                            <input type="password" name="password" class="form-control" minlength="6" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status *</label>
                            <select name="status" class="form-select" required>
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Create Staff</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i>Confirm Delete</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <i class="fas fa-user-times fa-4x text-danger mb-3"></i>
                    <h5>Delete delivery staff <strong id="deleteDeliveryName"></strong>?</h5>
                    <p class="text-muted">This action cannot be undone!</p>
                </div>
                <div class="modal-footer justify-content-center">
                    <form id="deleteForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger px-4">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function confirmDelete(coordinatorId, coordinatorName) {
            document.getElementById('deleteDeliveryName').textContent = coordinatorName;
            document.getElementById('deleteForm').action = '/admin/staff/delivery/' + coordinatorId;
            new bootstrap.Modal(document.getElementById('deleteModal')).show();
        }
    </script>
</body>
</html>