<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Management - Admin</title>
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
        .nav-tabs .nav-link {
            color: #6c757d;
            border: none;
            border-bottom: 3px solid transparent;
        }
        .nav-tabs .nav-link.active {
            background-color: transparent;
            border-bottom: 3px solid #FFB6C1;
            color: #FFB6C1;
            font-weight: bold;
        }
        .nav-tabs .nav-link:hover {
            border-bottom: 3px solid #FFB6C1;
            color: #FFB6C1;
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
            <h1 class="h2"><i class="fas fa-shopping-cart text-pink me-2"></i>Order Management</h1>
            <div class="btn-toolbar mb-2 mb-md-0">
                <span class="badge bg-pink fs-6 px-3 py-2">
                    <i class="fas fa-box me-2"></i>Total Orders: {{ $orders->count() }}
                </span>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-clock fa-2x text-warning mb-2"></i>
                        <h5 class="mb-0">{{ $orders->where('delivery_status', 'Pending')->count() }}</h5>
                        <small class="text-muted">Pending</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-shipping-fast fa-2x text-info mb-2"></i>
                        <h5 class="mb-0">{{ $orders->where('delivery_status', 'Out for Delivery')->count() }}</h5>
                        <small class="text-muted">Out for Delivery</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                        <h5 class="mb-0">{{ $orders->where('delivery_status', 'Delivered')->count() }}</h5>
                        <small class="text-muted">Delivered</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-times-circle fa-2x text-danger mb-2"></i>
                        <h5 class="mb-0">{{ $orders->where('delivery_status', 'Cancelled')->count() }}</h5>
                        <small class="text-muted">Cancelled</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Tabs -->
        <ul class="nav nav-tabs mb-3">
            <li class="nav-item">
                <a class="nav-link active" data-filter="all" href="javascript:void(0)" onclick="filterOrders('all')">
                    <i class="fas fa-list me-2"></i>All Orders
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-filter="Pending" href="javascript:void(0)" onclick="filterOrders('Pending')">
                    <i class="fas fa-clock me-2"></i>Pending
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-filter="Out for Delivery" href="javascript:void(0)" onclick="filterOrders('Out for Delivery')">
                    <i class="fas fa-truck me-2"></i>Out for Delivery
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-filter="Delivered" href="javascript:void(0)" onclick="filterOrders('Delivered')">
                    <i class="fas fa-check me-2"></i>Delivered
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-filter="Cancelled" href="javascript:void(0)" onclick="filterOrders('Cancelled')">
                    <i class="fas fa-ban me-2"></i>Cancelled
                </a>
            </li>
        </ul>

        <div class="card">
            <div class="card-header bg-white py-3">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h5 class="mb-0"><i class="fas fa-list-alt me-2"></i>Order List</h5>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="fas fa-search"></i></span>
                            <input type="text" id="searchOrder" class="form-control border-start-0" placeholder="Search by order ID or customer name...">
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="ordersTable">
                        <thead>
                            <tr>
                                <th style="width: 100px;">Order ID</th>
                                <th>Customer</th>
                                <th>Phone</th>
                                <th>Total</th>
                                <th>Payment</th>
                                <th>Delivery</th>
                                <th>Coordinator</th>
                                <th>Date</th>
                                <th style="width: 220px;" class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                                <tr data-status="{{ $order->delivery_status }}">
                                    <td><span class="badge bg-secondary fs-6">#{{ $order->id }}</span></td>
                                    <td>
                                        <div>
                                            <strong>{{ $order->customer_name }}</strong>
                                        </div>
                                    </td>
                                    <td><i class="fas fa-phone text-muted me-2"></i>{{ $order->phone }}</td>
                                    <td><strong class="text-success">₱{{ number_format($order->total, 2) }}</strong></td>
                                    <td>
                                        @if($order->payment_status == 'Paid')
                                            <span class="badge bg-success"><i class="fas fa-check me-1"></i>Paid</span>
                                        @elseif($order->payment_status == 'Pending')
                                            <span class="badge bg-warning text-dark"><i class="fas fa-clock me-1"></i>Pending</span>
                                        @elseif($order->payment_status == 'Unsuccessful')
                                            <span class="badge bg-danger"><i class="fas fa-times me-1"></i>Failed</span>
                                        @else
                                            <span class="badge bg-secondary">{{ $order->payment_status }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($order->delivery_status == 'Delivered')
                                            <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>Delivered</span>
                                        @elseif($order->delivery_status == 'Out for Delivery')
                                            <span class="badge bg-info text-dark"><i class="fas fa-shipping-fast me-1"></i>Shipping</span>
                                        @elseif($order->delivery_status == 'Cancelled')
                                            <span class="badge bg-danger"><i class="fas fa-ban me-1"></i>Cancelled</span>
                                        @else
                                            <span class="badge bg-warning text-dark"><i class="fas fa-clock me-1"></i>Pending</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($order->coordinator_id)
                                            @php
                                                $coordinator = DB::table('delivery_coordinator')
                                                    ->where('coordinator_id', $order->coordinator_id)
                                                    ->first();
                                            @endphp
                                            @if($coordinator)
                                                <small class="text-muted">
                                                    <i class="fas fa-user-tie me-1"></i>{{ $coordinator->name }}
                                                </small>
                                            @else
                                                <small class="text-muted">-</small>
                                            @endif
                                        @else
                                            <small class="text-danger"><i class="fas fa-exclamation-circle me-1"></i>Not Assigned</small>
                                        @endif
                                    </td>
                                    <td><small><i class="fas fa-calendar text-muted me-1"></i>{{ \Carbon\Carbon::parse($order->created_at)->format('M d, Y') }}</small></td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#viewModal{{ $order->id }}" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#assignModal{{ $order->id }}" title="Assign Coordinator">
                                                <i class="fas fa-user-tie"></i>
                                            </button>
                                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal{{ $order->id }}" title="Update Status">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger"
                                                    onclick="confirmDeleteOrder({{ $order->id }}, '{{ $order->id }}', '{{ route('admin.orders.delete', $order->id) }}')">
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

    <!-- Modals -->
    @foreach($orders as $order)
        <!-- Assign Coordinator Modal -->
        <div class="modal fade" id="assignModal{{ $order->id }}" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form method="POST" action="{{ route('admin.orders.assign-coordinator', $order->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title"><i class="fas fa-user-tie me-2"></i>Assign Delivery Coordinator</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Order #{{ $order->id }}</strong> - {{ $order->customer_name }}
                            </div>

                            <div class="mb-3">
                                <label class="form-label"><i class="fas fa-user-tie me-2"></i>Select Delivery Coordinator</label>
                                <select name="coordinator_id" class="form-select" required>
                                    <option value="">-- Select Coordinator --</option>
                                    @foreach($coordinators as $coordinator)
                                        <option value="{{ $coordinator->coordinator_id }}" 
                                            {{ $order->coordinator_id == $coordinator->coordinator_id ? 'selected' : '' }}>
                                            {{ $coordinator->name }} ({{ $coordinator->email }})
                                        </option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    The selected coordinator will be able to see and manage this order
                                </small>
                            </div>

                            @if($order->coordinator_id)
                                <div class="alert alert-warning mb-0">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    This order is currently assigned. Changing the coordinator will transfer the order.
                                </div>
                            @endif
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-check me-2"></i>Assign Coordinator
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- View Order Modal -->
        <div class="modal fade" id="viewModal{{ $order->id }}" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-pink text-white">
                        <h5 class="modal-title"><i class="fas fa-receipt me-2"></i>Order #{{ $order->id }} Details</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h6 class="text-pink"><i class="fas fa-user me-2"></i>Customer Information</h6>
                                <table class="table table-sm table-borderless">
                                    <tr>
                                        <td width="100"><strong>Name:</strong></td>
                                        <td>{{ $order->customer_name }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Phone:</strong></td>
                                        <td>{{ $order->phone }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Address:</strong></td>
                                        <td>{{ $order->address }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-pink"><i class="fas fa-info-circle me-2"></i>Order Information</h6>
                                <table class="table table-sm table-borderless">
                                    <tr>
                                        <td width="120"><strong>Order Date:</strong></td>
                                        <td>{{ \Carbon\Carbon::parse($order->created_at)->format('M d, Y h:i A') }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Payment:</strong></td>
                                        <td>
                                            <span class="badge bg-{{ $order->payment_status == 'Paid' ? 'success' : 'warning' }}">
                                                {{ $order->payment_status }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Delivery:</strong></td>
                                        <td>
                                            <span class="badge bg-info">{{ $order->delivery_status }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Coordinator:</strong></td>
                                        <td>
                                            @if($order->coordinator_id)
                                                @php
                                                    $coordinator = DB::table('delivery_coordinator')
                                                        ->where('coordinator_id', $order->coordinator_id)
                                                        ->first();
                                                @endphp
                                                {{ $coordinator ? $coordinator->name : 'N/A' }}
                                            @else
                                                <span class="text-muted">Not Assigned</span>
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <h6 class="text-pink"><i class="fas fa-shopping-bag me-2"></i>Order Items</h6>
                        @php
                            $orderItems = DB::table('order_item')
                                ->join('products', 'order_item.product_id', '=', 'products.id')
                                ->where('order_item.order_id', $order->id)
                                ->select('products.name', 'products.image', 'order_item.quantity', 'order_item.price')
                                ->get();
                        @endphp
                        
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Product</th>
                                        <th class="text-center">Quantity</th>
                                        <th class="text-end">Price</th>
                                        <th class="text-end">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($orderItems as $item)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ asset('asset/images/' . $item->image) }}" width="40" class="rounded me-2">
                                                    {{ $item->name }}
                                                </div>
                                            </td>
                                            <td class="text-center">{{ $item->quantity }}</td>
                                            <td class="text-end">₱{{ number_format($item->price, 2) }}</td>
                                            <td class="text-end">₱{{ number_format($item->price * $item->quantity, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-light">
                                    <tr>
                                        <th colspan="3" class="text-end">Total:</th>
                                        <th class="text-end text-success">₱{{ number_format($order->total, 2) }}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        @if($order->payment_proof)
                            <h6 class="text-pink mt-3"><i class="fas fa-file-image me-2"></i>Payment Proof</h6>
                            <div class="text-center">
                                <img src="{{ asset('uploads/payments/' . $order->payment_proof) }}" class="img-fluid rounded border" style="max-height: 300px;">
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Order Status Modal -->
        <div class="modal fade" id="editModal{{ $order->id }}" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form method="POST" action="{{ route('admin.orders.update', $order->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="modal-header bg-pink text-white">
                            <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Update Order Status</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Order #{{ $order->id }}</strong> - {{ $order->customer_name }}
                            </div>

                            <div class="mb-3">
                                <label class="form-label"><i class="fas fa-money-bill-wave me-2"></i>Payment Status</label>
                                <select name="payment_status" class="form-select" required>
                                    <option value="Pending" {{ $order->payment_status == 'Pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="Paid" {{ $order->payment_status == 'Paid' ? 'selected' : '' }}>Paid</option>
                                    <option value="Unsuccessful" {{ $order->payment_status == 'Unsuccessful' ? 'selected' : '' }}>Unsuccessful</option>
                                    <option value="Refunded" {{ $order->payment_status == 'Refunded' ? 'selected' : '' }}>Refunded</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label"><i class="fas fa-truck me-2"></i>Delivery Status</label>
                                <select name="delivery_status" class="form-select" required>
                                    <option value="Pending" {{ $order->delivery_status == 'Pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="Out for Delivery" {{ $order->delivery_status == 'Out for Delivery' ? 'selected' : '' }}>Out for Delivery</option>
                                    <option value="Delivered" {{ $order->delivery_status == 'Delivered' ? 'selected' : '' }}>Delivered</option>
                                    <option value="Cancelled" {{ $order->delivery_status == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-pink"><i class="fas fa-save me-2"></i>Update Status</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteOrderModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i>Confirm Delete</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <i class="fas fa-file-invoice fa-4x text-danger mb-3"></i>
                    <h5>Are you sure you want to delete order <strong id="deleteOrderNumber"></strong>?</h5>
                    <p class="text-muted">This action cannot be undone!</p>
                </div>
                <div class="modal-footer justify-content-center">
                    <form id="deleteOrderForm" method="POST">
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
        function confirmDeleteOrder(orderId, orderNumber, deleteUrl) {
            document.getElementById('deleteOrderNumber').textContent = orderNumber;
            document.getElementById('deleteOrderForm').action = deleteUrl;
            new bootstrap.Modal(document.getElementById('deleteOrderModal')).show();
        }

        document.getElementById('searchOrder').addEventListener('keyup', function() {
            const searchValue = this.value.toLowerCase();
            const tableRows = document.querySelectorAll('#ordersTable tbody tr');
            
            tableRows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchValue) ? '' : 'none';
            });
        });

        function filterOrders(status) {
            const tableRows = document.querySelectorAll('#ordersTable tbody tr');
            const navLinks = document.querySelectorAll('.nav-tabs .nav-link');
            
            navLinks.forEach(link => {
                if (link.getAttribute('data-filter') === status) {
                    link.classList.add('active');
                } else {
                    link.classList.remove('active');
                }
            });
            
            tableRows.forEach(row => {
                if (status === 'all') {
                    row.style.display = '';
                } else {
                    const rowStatus = row.getAttribute('data-status');
                    row.style.display = rowStatus === status ? '' : 'none';
                }
            });
        }
    </script>
</body>
</html>