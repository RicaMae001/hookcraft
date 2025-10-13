<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Deliveries</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .navbar-delivery {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .sidebar {
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 100;
            padding: 48px 0 0;
            box-shadow: inset -1px 0 0 rgba(0, 0, 0, .1);
            background: white;
        }
        .sidebar .nav-link {
            font-weight: 500;
            color: #333;
            padding: 0.75rem 1rem;
        }
        .sidebar .nav-link.active {
            color: #fff;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .sidebar .nav-link:hover {
            background-color: #f8f9fa;
        }
        .card {
            border: none;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            border-radius: 10px;
        }
        main {
            margin-left: 250px;
        }
        .order-card {
            transition: transform 0.2s;
            border-left: 4px solid #667eea;
        }
        .order-card:hover {
            transform: translateY(-5px);
        }
        @media (max-width: 768px) {
            main {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-dark sticky-top navbar-delivery flex-md-nowrap p-0 shadow">
        <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3" href="{{ route('delivery.dashboard') }}">
            <i class="fas fa-truck me-2"></i>Delivery Portal
        </a>
        <div class="navbar-nav">
            <div class="nav-item text-nowrap">
                <form action="{{ route('delivery.logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="nav-link px-3 btn btn-link text-white text-decoration-none">
                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-2 d-md-block sidebar collapse">
                <div class="position-sticky pt-3">
                    <div class="text-center mb-4">
                        <i class="fas fa-user-circle fa-3x text-primary"></i>
                        <p class="mt-2 mb-0"><strong>{{ session('coordinator_name') }}</strong></p>
                        <small class="text-muted">Delivery Coordinator</small>
                    </div>

                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('delivery.dashboard') }}">
                                <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="{{ route('delivery.deliveries') }}">
                                <i class="fas fa-box me-2"></i>My Deliveries
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('delivery.history') }}">
                                <i class="fas fa-history me-2"></i>Delivery History
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main Content -->
            <main class="col-md-10 ms-sm-auto px-md-4 py-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-4 border-bottom">
                    <h1 class="h2"><i class="fas fa-box me-2 text-primary"></i>My Deliveries</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <span class="badge bg-primary fs-6 px-3 py-2">
                            <i class="fas fa-calendar me-2"></i>{{ date('F d, Y') }}
                        </span>
                    </div>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Filter Tabs -->
                <ul class="nav nav-tabs mb-4" id="deliveryTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="all-tab" data-bs-toggle="tab" data-bs-target="#all" type="button">
                            <i class="fas fa-list me-2"></i>All Deliveries
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending" type="button">
                            <i class="fas fa-clock me-2"></i>Pending
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="delivery-tab" data-bs-toggle="tab" data-bs-target="#delivery" type="button">
                            <i class="fas fa-shipping-fast me-2"></i>Out for Delivery
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="completed-tab" data-bs-toggle="tab" data-bs-target="#completed" type="button">
                            <i class="fas fa-check-circle me-2"></i>Delivered
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="deliveryTabsContent">
                    <!-- All Deliveries -->
                    <div class="tab-pane fade show active" id="all" role="tabpanel">
                        @forelse($deliveries as $delivery)
                            @include('admin/delivery.delivery-card', ['delivery' => $delivery])
                        @empty
                            <div class="text-center py-5">
                                <i class="fas fa-box-open fa-4x text-muted mb-3"></i>
                                <p class="text-muted">No deliveries assigned yet</p>
                            </div>
                        @endforelse
                    </div>

                    <!-- Pending -->
                    <div class="tab-pane fade" id="pending" role="tabpanel">
                        @forelse($deliveries->where('delivery_status', 'Pending') as $delivery)
                            @include('admin/delivery.delivery-card', ['delivery' => $delivery])
                        @empty
                            <div class="text-center py-5">
                                <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
                                <p class="text-muted">No pending deliveries</p>
                            </div>
                        @endforelse
                    </div>

                    <!-- Out for Delivery -->
                    <div class="tab-pane fade" id="delivery" role="tabpanel">
                        @forelse($deliveries->where('delivery_status', 'Out for Delivery') as $delivery)
                            @include('admin/delivery.delivery-card', ['delivery' => $delivery])
                        @empty
                            <div class="text-center py-5">
                                <i class="fas fa-shipping-fast fa-4x text-info mb-3"></i>
                                <p class="text-muted">No deliveries in transit</p>
                            </div>
                        @endforelse
                    </div>

                    <!-- Delivered -->
                    <div class="tab-pane fade" id="completed" role="tabpanel">
                        @forelse($deliveries->where('delivery_status', 'Delivered') as $delivery)
                            @include('admin/delivery.delivery-card', ['delivery' => $delivery])
                        @empty
                            <div class="text-center py-5">
                                <i class="fas fa-truck fa-4x text-muted mb-3"></i>
                                <p class="text-muted">No completed deliveries yet</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Update Status Modal -->
    <div class="modal fade" id="updateStatusModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Update Delivery Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="updateStatusForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Order ID</label>
                            <input type="text" class="form-control" id="modalOrderId" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Customer Name</label>
                            <input type="text" class="form-control" id="modalCustomerName" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">New Status <span class="text-danger">*</span></label>
                            <select name="delivery_status" class="form-select" required>
                                <option value="">Select Status</option>
                                <option value="Pending">Pending</option>
                                <option value="Out for Delivery">Out for Delivery</option>
                                <option value="Delivered">Delivered</option>
                                <option value="Cancelled">Cancelled</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Update Status
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function updateStatus(orderId, customerName) {
            document.getElementById('modalOrderId').value = '#' + orderId;
            document.getElementById('modalCustomerName').value = customerName;
            // Use the correct route that matches web.php
            document.getElementById('updateStatusForm').action = '/delivery/deliveries/' + orderId + '/status';
            new bootstrap.Modal(document.getElementById('updateStatusModal')).show();
        }
    </script>
</body>
</html>