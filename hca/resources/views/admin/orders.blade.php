@extends('admin.layouts.admin')

@section('title', 'Orders Management')

@section('content')
<div class="animate-fade-in">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 style="font-family: 'Playfair Display', serif; font-size: 2.25rem; font-weight: 700; margin-bottom: 0.5rem;">
                Order Management
            </h1>
            <p style="color: var(--text-secondary); margin: 0;">Manage and track all customer orders</p>
        </div>
        
        <div class="d-flex gap-2">
            <span class="badge-modern badge-info" style="font-size: 1rem; padding: 0.75rem 1.25rem;">
                <i class="fas fa-shopping-bag me-2"></i>{{ $orders->count() }} Total Orders
            </span>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" style="border-radius: 12px; border-left: 4px solid var(--success);">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-card-icon warning">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-card-label">Pending</div>
                <div class="stat-card-value">{{ $orders->where('delivery_status', 'Pending')->count() }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-card-icon info">
                    <i class="fas fa-truck"></i>
                </div>
                <div class="stat-card-label">Out for Delivery</div>
                <div class="stat-card-value">{{ $orders->where('delivery_status', 'Out for Delivery')->count() }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-card-icon success">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-card-label">Delivered</div>
                <div class="stat-card-value">{{ $orders->where('delivery_status', 'Delivered')->count() }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-card-icon" style="background: linear-gradient(135deg, rgba(252, 129, 129, 0.15), rgba(252, 129, 129, 0.05)); color: var(--danger);">
                    <i class="fas fa-times-circle"></i>
                </div>
                <div class="stat-card-label">Cancelled</div>
                <div class="stat-card-value">{{ $orders->where('delivery_status', 'Cancelled')->count() }}</div>
            </div>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="content-card mb-4">
        <div class="d-flex gap-2 p-3" style="border-bottom: 1px solid var(--border-color); overflow-x: auto;">
            <button class="btn btn-modern-secondary filter-tab active" data-filter="all" onclick="filterOrders('all')">
                <i class="fas fa-list me-2"></i>All Orders
            </button>
            <button class="btn btn-modern-secondary filter-tab" data-filter="Pending" onclick="filterOrders('Pending')">
                <i class="fas fa-clock me-2"></i>Pending
            </button>
            <button class="btn btn-modern-secondary filter-tab" data-filter="Out for Delivery" onclick="filterOrders('Out for Delivery')">
                <i class="fas fa-truck me-2"></i>Shipping
            </button>
            <button class="btn btn-modern-secondary filter-tab" data-filter="Delivered" onclick="filterOrders('Delivered')">
                <i class="fas fa-check me-2"></i>Delivered
            </button>
            <button class="btn btn-modern-secondary filter-tab" data-filter="Cancelled" onclick="filterOrders('Cancelled')">
                <i class="fas fa-ban me-2"></i>Cancelled
            </button>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="content-card">
        <div class="content-card-header">
            <h3 class="content-card-title">Order List</h3>
            <div class="d-flex gap-2">
                <div class="input-group" style="max-width: 300px;">
                    <span class="input-group-text" style="background: var(--light-bg); border-right: none;">
                        <i class="fas fa-search"></i>
                    </span>
                    <input type="text" id="searchOrder" class="form-control" placeholder="Search orders..." style="border-left: none;">
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="modern-table" id="ordersTable">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Phone</th>
                        <th>Total</th>
                        <th>Payment</th>
                        <th>Method</th>
                        <th>Proof</th>
                        <th>Status</th>
                        <th>Coordinator</th>
                        <th>Date</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr data-status="{{ $order->delivery_status }}">
                            <td>
                                <span style="font-weight: 700; color: var(--primary-pink);">#{{ $order->id }}</span>
                            </td>
                            <td>
                                <div style="font-weight: 600;">{{ $order->customer_name }}</div>
                            </td>
                            <td>
                                <span style="color: var(--text-secondary); font-size: 0.875rem;">
                                    <i class="fas fa-phone me-1"></i>{{ $order->phone }}
                                </span>
                            </td>
                            <td>
                                <span style="font-weight: 700; color: var(--success);">₱{{ number_format($order->total, 2) }}</span>
                            </td>
                            <td>
                                @if($order->payment_status == 'Paid')
                                    <span class="badge-modern badge-success">
                                        <i class="fas fa-check-circle"></i>Paid
                                    </span>
                                @elseif($order->payment_status == 'Pending')
                                    <span class="badge-modern badge-warning">
                                        <i class="fas fa-clock"></i>Pending
                                    </span>
                                @else
                                    <span class="badge-modern badge-danger">
                                        <i class="fas fa-times"></i>{{ $order->payment_status }}
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if($order->payment_method === 'COD')
                                    <span class="badge-modern badge-warning">
                                        <i class="fas fa-money-bill-wave"></i>COD
                                    </span>
                                @elseif($order->payment_method === 'GCash')
                                    <span class="badge-modern badge-info">
                                        <i class="fas fa-mobile-alt"></i>GCash
                                    </span>
                                @else
                                    <span class="badge-modern" style="background: var(--light-bg);">{{ $order->payment_method ?? '-' }}</span>
                                @endif
                            </td>
                            <td>
                                @if($order->payment_proof)
                                    <img src="{{ asset('uploads/payments/' . $order->payment_proof) }}" 
                                         alt="Payment Proof" 
                                         style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px; cursor: pointer; border: 2px solid var(--primary-pink);"
                                         onclick="openImageModal(this, 'Order #{{ $order->id }} - Payment Proof')">
                                @else
                                    <span style="color: var(--text-secondary); font-size: 0.875rem;">
                                        @if($order->payment_method === 'GCash')
                                            <i class="fas fa-hourglass-half me-1"></i>Awaiting
                                        @else
                                            <i class="fas fa-minus me-1"></i>N/A
                                        @endif
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if($order->delivery_status == 'Delivered')
                                    <span class="badge-modern badge-success">
                                        <i class="fas fa-check-circle"></i>Delivered
                                    </span>
                                @elseif($order->delivery_status == 'Out for Delivery')
                                    <span class="badge-modern badge-info">
                                        <i class="fas fa-shipping-fast"></i>Shipping
                                    </span>
                                @elseif($order->delivery_status == 'Cancelled')
                                    <span class="badge-modern badge-danger">
                                        <i class="fas fa-ban"></i>Cancelled
                                    </span>
                                @else
                                    <span class="badge-modern badge-warning">
                                        <i class="fas fa-clock"></i>Pending
                                    </span>
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
                                        <span style="color: var(--text-secondary); font-size: 0.875rem;">
                                            <i class="fas fa-user-tie me-1"></i>{{ $coordinator->name }}
                                        </span>
                                    @else
                                        <span style="color: var(--text-secondary); font-size: 0.875rem;">-</span>
                                    @endif
                                @else
                                    <span class="badge-modern badge-danger">
                                        <i class="fas fa-exclamation-circle"></i>Not Assigned
                                    </span>
                                @endif
                            </td>
                            <td>
                                <span style="color: var(--text-secondary); font-size: 0.875rem;">
                                    <i class="fas fa-calendar me-1"></i>{{ \Carbon\Carbon::parse($order->created_at)->format('M d, Y') }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex gap-2 justify-content-center">
                                    <button class="btn btn-sm" style="background: var(--light-bg); border-radius: 8px; padding: 0.5rem 0.75rem;" 
                                            data-bs-toggle="modal" data-bs-target="#viewModal{{ $order->id }}" title="View Details">
                                        <i class="fas fa-eye" style="color: var(--secondary);"></i>
                                    </button>
                                    <button class="btn btn-sm" style="background: var(--light-bg); border-radius: 8px; padding: 0.5rem 0.75rem;" 
                                            data-bs-toggle="modal" data-bs-target="#assignModal{{ $order->id }}" title="Assign Coordinator">
                                        <i class="fas fa-user-tie" style="color: var(--primary-pink);"></i>
                                    </button>
                                    <button class="btn btn-sm" style="background: var(--light-bg); border-radius: 8px; padding: 0.5rem 0.75rem;" 
                                            data-bs-toggle="modal" data-bs-target="#editModal{{ $order->id }}" title="Update Status">
                                        <i class="fas fa-edit" style="color: var(--warning);"></i>
                                    </button>
                                    <button class="btn btn-sm" style="background: var(--light-bg); border-radius: 8px; padding: 0.5rem 0.75rem;"
                                            onclick="confirmDeleteOrder({{ $order->id }}, '{{ $order->id }}', '{{ route('admin.orders.delete', $order->id) }}')" title="Delete">
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

<!-- Image Zoom Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" style="display: none; position: fixed; z-index: 9999; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.9);" onclick="closeImageModal()">
    <span style="position: absolute; top: 20px; right: 35px; color: #fff; font-size: 40px; font-weight: bold; cursor: pointer;" onclick="closeImageModal()">&times;</span>
    <img style="max-width: 90%; max-height: 90%; object-fit: contain; border-radius: 8px; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);" id="modalImage" alt="Payment Proof">
    <div style="position: absolute; bottom: 30px; left: 50%; transform: translateX(-50%); color: #fff; text-align: center; padding: 10px 20px; background: rgba(0, 0, 0, 0.7); border-radius: 8px;" id="modalCaption"></div>
</div>

<!-- Modals for each order -->
@foreach($orders as $order)
    <!-- View Order Modal -->
    <div class="modal fade" id="viewModal{{ $order->id }}" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content" style="border-radius: 16px; border: none;">
                <div class="modal-header" style="background: linear-gradient(135deg, var(--primary-pink), #FF8AAE); color: white; border-radius: 16px 16px 0 0; border: none;">
                    <h5 class="modal-title"><i class="fas fa-receipt me-2"></i>Order #{{ $order->id }} Details</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 style="color: var(--primary-pink); font-weight: 700; margin-bottom: 1rem;">
                                <i class="fas fa-user me-2"></i>Customer Information
                            </h6>
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td width="100" style="color: var(--text-secondary);"><strong>Name:</strong></td>
                                    <td>{{ $order->customer_name }}</td>
                                </tr>
                                <tr>
                                    <td style="color: var(--text-secondary);"><strong>Phone:</strong></td>
                                    <td>{{ $order->phone }}</td>
                                </tr>
                                <tr>
                                    <td style="color: var(--text-secondary);"><strong>Address:</strong></td>
                                    <td>{{ $order->address }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6 style="color: var(--primary-pink); font-weight: 700; margin-bottom: 1rem;">
                                <i class="fas fa-info-circle me-2"></i>Order Information
                            </h6>
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td width="140" style="color: var(--text-secondary);"><strong>Order Date:</strong></td>
                                    <td>{{ \Carbon\Carbon::parse($order->created_at)->format('M d, Y h:i A') }}</td>
                                </tr>
                                <tr>
                                    <td style="color: var(--text-secondary);"><strong>Payment:</strong></td>
                                    <td>
                                        @if($order->payment_method === 'COD')
                                            <span class="badge-modern badge-warning"><i class="fas fa-money-bill-wave me-1"></i>Cash on Delivery</span>
                                        @elseif($order->payment_method === 'GCash')
                                            <span class="badge-modern badge-info"><i class="fas fa-mobile-alt me-1"></i>GCash</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td style="color: var(--text-secondary);"><strong>Status:</strong></td>
                                    <td>
                                        @if($order->payment_status == 'Paid')
                                            <span class="badge-modern badge-success">Paid</span>
                                        @else
                                            <span class="badge-modern badge-warning">{{ $order->payment_status }}</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td style="color: var(--text-secondary);"><strong>Delivery:</strong></td>
                                    <td><span class="badge-modern badge-info">{{ $order->delivery_status }}</span></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <h6 style="color: var(--primary-pink); font-weight: 700; margin-bottom: 1rem;">
                        <i class="fas fa-shopping-bag me-2"></i>Order Items
                    </h6>
                    @php
                        $orderItems = DB::table('order_item')
                            ->join('products', 'order_item.product_id', '=', 'products.id')
                            ->where('order_item.order_id', $order->id)
                            ->select('products.name', 'products.image', 'order_item.quantity', 'order_item.price')
                            ->get();
                    @endphp
                    
                    <div class="table-responsive">
                        <table class="modern-table">
                            <thead>
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
                                            <div class="d-flex align-items-center gap-2">
                                                <img src="{{ asset('asset/images/' . $item->image) }}" width="40" style="border-radius: 8px;">
                                                <span style="font-weight: 600;">{{ $item->name }}</span>
                                            </div>
                                        </td>
                                        <td class="text-center">{{ $item->quantity }}</td>
                                        <td class="text-end">₱{{ number_format($item->price, 2) }}</td>
                                        <td class="text-end" style="font-weight: 700;">₱{{ number_format($item->price * $item->quantity, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr style="background: var(--light-bg);">
                                    <th colspan="3" class="text-end" style="padding: 1rem;">Total:</th>
                                    <th class="text-end" style="color: var(--success); padding: 1rem;">₱{{ number_format($order->total, 2) }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    @if($order->payment_proof)
                        <h6 style="color: var(--primary-pink); font-weight: 700; margin: 2rem 0 1rem;">
                            <i class="fas fa-file-image me-2"></i>Payment Proof
                        </h6>
                        <div class="text-center">
                            <img src="{{ asset('uploads/payments/' . $order->payment_proof) }}" 
                                 class="img-fluid" 
                                 style="max-height: 400px; cursor: pointer; border-radius: 12px; border: 2px solid var(--border-color);"
                                 onclick="openImageModal(this, 'Order #{{ $order->id }} - Payment Proof')">
                            <p style="color: var(--text-secondary); margin-top: 0.5rem; font-size: 0.875rem;">
                                <i class="fas fa-info-circle me-1"></i>Click to view full size
                            </p>
                        </div>
                    @endif
                </div>
                <div class="modal-footer" style="border: none;">
                    <button type="button" class="btn btn-modern-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Assign Coordinator Modal -->
    <div class="modal fade" id="assignModal{{ $order->id }}" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 16px; border: none;">
                <form method="POST" action="{{ route('admin.orders.assign-coordinator', $order->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="modal-header" style="background: var(--secondary); color: white; border-radius: 16px 16px 0 0;">
                        <h5 class="modal-title"><i class="fas fa-user-tie me-2"></i>Assign Delivery Coordinator</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="alert" style="background: rgba(102, 126, 234, 0.1); border-left: 4px solid var(--secondary); border-radius: 8px;">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Order #{{ $order->id }}</strong> - {{ $order->customer_name }}
                        </div>

                        <div class="mb-3">
                            <label class="form-label" style="font-weight: 600;"><i class="fas fa-user-tie me-2"></i>Select Coordinator</label>
                            <select name="coordinator_id" class="form-select" style="border-radius: 12px; border: 1px solid var(--border-color);" required>
                                <option value="">-- Choose Coordinator --</option>
                                @foreach($coordinators as $coordinator)
                                    <option value="{{ $coordinator->coordinator_id }}" 
                                        {{ $order->coordinator_id == $coordinator->coordinator_id ? 'selected' : '' }}>
                                        {{ $coordinator->name }} ({{ $coordinator->email }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer" style="border: none;">
                        <button type="button" class="btn btn-modern-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-modern-primary">
                            <i class="fas fa-check me-2"></i>Assign
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Order Status Modal -->
    <div class="modal fade" id="editModal{{ $order->id }}" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 16px; border: none;">
                <form method="POST" action="{{ route('admin.orders.update', $order->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="modal-header" style="background: linear-gradient(135deg, var(--warning), #F6C176); color: white; border-radius: 16px 16px 0 0;">
                        <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Update Order Status</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="alert" style="background: rgba(246, 173, 85, 0.1); border-left: 4px solid var(--warning); border-radius: 8px;">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Order #{{ $order->id }}</strong> - {{ $order->customer_name }}
                        </div>

                        <div class="mb-3">
                            <label class="form-label" style="font-weight: 600;"><i class="fas fa-money-bill-wave me-2"></i>Payment Status</label>
                            <select name="payment_status" class="form-select" style="border-radius: 12px; border: 1px solid var(--border-color);" required>
                                <option value="Pending" {{ $order->payment_status == 'Pending' ? 'selected' : '' }}>Pending</option>
                                <option value="Paid" {{ $order->payment_status == 'Paid' ? 'selected' : '' }}>Paid</option>
                                <option value="Unsuccessful" {{ $order->payment_status == 'Unsuccessful' ? 'selected' : '' }}>Unsuccessful</option>
                                <option value="Refunded" {{ $order->payment_status == 'Refunded' ? 'selected' : '' }}>Refunded</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" style="font-weight: 600;"><i class="fas fa-truck me-2"></i>Delivery Status</label>
                            <select name="delivery_status" class="form-select" style="border-radius: 12px; border: 1px solid var(--border-color);" required>
                                <option value="Pending" {{ $order->delivery_status == 'Pending' ? 'selected' : '' }}>Pending</option>
                                <option value="Out for Delivery" {{ $order->delivery_status == 'Out for Delivery' ? 'selected' : '' }}>Out for Delivery</option>
                                <option value="Delivered" {{ $order->delivery_status == 'Delivered' ? 'selected' : '' }}>Delivered</option>
                                <option value="Cancelled" {{ $order->delivery_status == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer" style="border: none;">
                        <button type="button" class="btn btn-modern-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-modern-primary">
                            <i class="fas fa-save me-2"></i>Update Status
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteOrderModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 16px; border: none;">
            <div class="modal-header" style="background: var(--danger); color: white; border-radius: 16px 16px 0 0;">
                <h5 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i>Confirm Delete</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-5">
                <i class="fas fa-file-invoice fa-4x mb-4" style="color: var(--danger);"></i>
                <h5>Delete order <strong id="deleteOrderNumber" style="color: var(--danger);"></strong>?</h5>
                <p style="color: var(--text-secondary);">This action cannot be undone!</p>
            </div>
            <div class="modal-footer justify-content-center" style="border: none;">
                <form id="deleteOrderForm" method="POST">
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

@push('styles')
<style>
    .filter-tab {
        border-radius: 12px;
        padding: 0.625rem 1.25rem;
        font-weight: 600;
        transition: all 0.2s ease;
        border: none;
    }
    
    .filter-tab.active {
        background: linear-gradient(135deg, var(--primary-pink), #FF8AAE);
        color: white;
    }
    
    .filter-tab:hover:not(.active) {
        background: var(--border-color);
    }
</style>
@endpush

@push('scripts')
<script>
    // Image Modal Functions
    function openImageModal(imgElement, caption) {
        const modal = document.getElementById('imageModal');
        const modalImg = document.getElementById('modalImage');
        const captionText = document.getElementById('modalCaption');
        
        modal.style.display = 'flex';
        modal.style.alignItems = 'center';
        modal.style.justifyContent = 'center';
        modalImg.src = imgElement.src;
        captionText.textContent = caption || imgElement.alt;
        document.body.style.overflow = 'hidden';
    }
    
    function closeImageModal() {
        document.getElementById('imageModal').style.display = 'none';
        document.body.style.overflow = 'auto';
    }
    
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') closeImageModal();
    });

    // Delete Order
    function confirmDeleteOrder(orderId, orderNumber, deleteUrl) {
        document.getElementById('deleteOrderNumber').textContent = '#' + orderNumber;
        document.getElementById('deleteOrderForm').action = deleteUrl;
        new bootstrap.Modal(document.getElementById('deleteOrderModal')).show();
    }

    // Search Orders
    document.getElementById('searchOrder').addEventListener('keyup', function() {
        const searchValue = this.value.toLowerCase();
        const tableRows = document.querySelectorAll('#ordersTable tbody tr');
        
        tableRows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchValue) ? '' : 'none';
        });
    });

    // Filter Orders
    function filterOrders(status) {
        const tableRows = document.querySelectorAll('#ordersTable tbody tr');
        const filterTabs = document.querySelectorAll('.filter-tab');
        
        filterTabs.forEach(tab => {
            if (tab.getAttribute('data-filter') === status) {
                tab.classList.add('active');
            } else {
                tab.classList.remove('active');
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
@endpush