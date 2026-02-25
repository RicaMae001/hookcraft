@extends('admin.layouts.admin')

@section('title', 'Orders Management')

@section('content')
<div class="animate-fade-in">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h1 style="font-family: 'Playfair Display', serif; font-size: clamp(1.5rem, 5vw, 2.25rem); font-weight: 700; margin-bottom: 0.5rem;">
                Order Management
            </h1>
            <p style="color: var(--text-secondary); margin: 0; font-size: clamp(0.875rem, 2vw, 1rem);">Manage and track all customer orders</p>
        </div>
        
        <div class="d-flex gap-2">
            <span class="badge-modern badge-info" style="font-size: clamp(0.875rem, 2vw, 1rem); padding: 0.625rem 1rem;">
                <i class="fas fa-shopping-bag me-2"></i>{{ $allOrders->count() }} Total
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
    <div class="row g-3 g-md-4 mb-4">
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-card-icon warning">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-card-label">Pending</div>
                <div class="stat-card-value">{{ $allOrders->where('delivery_status', 'Pending')->count() }}</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-card-icon info">
                    <i class="fas fa-truck"></i>
                </div>
                <div class="stat-card-label">Shipping</div>
                <div class="stat-card-value">{{ $allOrders->where('delivery_status', 'Out for Delivery')->count() }}</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-card-icon success">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-card-label">Delivered</div>
                <div class="stat-card-value">{{ $allOrders->where('delivery_status', 'Delivered')->count() }}</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-card-icon" style="background: linear-gradient(135deg, rgba(252, 129, 129, 0.15), rgba(252, 129, 129, 0.05)); color: var(--danger);">
                    <i class="fas fa-times-circle"></i>
                </div>
                <div class="stat-card-label">Cancelled</div>
                <div class="stat-card-value">{{ $allOrders->where('delivery_status', 'Cancelled')->count() }}</div>
            </div>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="content-card mb-4">
        <div class="d-flex gap-2 p-3 filter-tabs-container" style="border-bottom: 1px solid var(--border-color); overflow-x: auto;">
            <button class="btn btn-modern-secondary filter-tab active" data-filter="all" onclick="filterOrders('all')">
                <i class="fas fa-list me-1 me-md-2"></i><span class="d-none d-sm-inline">All </span>Orders
            </button>
            <button class="btn btn-modern-secondary filter-tab" data-filter="Pending" onclick="filterOrders('Pending')">
                <i class="fas fa-clock me-1 me-md-2"></i>Pending
            </button>
            <button class="btn btn-modern-secondary filter-tab" data-filter="Out for Delivery" onclick="filterOrders('Out for Delivery')">
                <i class="fas fa-truck me-1 me-md-2"></i><span class="d-none d-sm-inline">Shipping</span><span class="d-sm-none">Ship</span>
            </button>
            <button class="btn btn-modern-secondary filter-tab" data-filter="Delivered" onclick="filterOrders('Delivered')">
                <i class="fas fa-check me-1 me-md-2"></i><span class="d-none d-sm-inline">Delivered</span><span class="d-sm-none">Done</span>
            </button>
            <button class="btn btn-modern-secondary filter-tab" data-filter="Cancelled" onclick="filterOrders('Cancelled')">
                <i class="fas fa-ban me-1 me-md-2"></i><span class="d-none d-sm-inline">Cancelled</span><span class="d-sm-none">Cancel</span>
            </button>
        </div>
    </div>

    <!-- Orders Table/Cards -->
    <div class="content-card">
        <div class="content-card-header flex-wrap gap-3">
            <h3 class="content-card-title">Order List</h3>
            <div class="d-flex gap-2 flex-grow-1 flex-md-grow-0">
                <div class="input-group" style="max-width: 100%; min-width: 200px;">
                    <span class="input-group-text" style="background: var(--light-bg); border-right: none;">
                        <i class="fas fa-search"></i>
                    </span>
                    <input type="text" id="searchOrder" class="form-control" placeholder="Search..." style="border-left: none;">
                </div>
            </div>
        </div>

        <!-- Desktop Table View with Horizontal Scroll Controls -->
        <div class="d-none d-lg-block position-relative">
            <button class="scroll-btn scroll-left" id="scrollLeft" onclick="scrollTable('left')">
                <i class="fas fa-chevron-left"></i>
            </button>
            <button class="scroll-btn scroll-right" id="scrollRight" onclick="scrollTable('right')">
                <i class="fas fa-chevron-right"></i>
            </button>
            
            <div class="table-responsive" id="tableContainer">
                <table class="modern-table" id="ordersTable">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Phone</th>
                            <th>Subtotal</th>
                            <th>Delivery Fee</th>
                            <th>Grand Total</th>
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
                        @forelse($orders as $order)
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
                                    <span style="font-weight: 600; color: var(--text-secondary);">₱{{ number_format($order->total, 2) }}</span>
                                </td>
                                <td>
                                    <span style="font-weight: 600; color: var(--warning);">₱{{ number_format($order->delivery_fee, 2) }}</span>
                                </td>
                                <td>
                                    <span style="font-weight: 700; color: var(--success); font-size: 1.1rem;">₱{{ number_format($order->grand_total, 2) }}</span>
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
                                             style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px; cursor: pointer; border: 2px solid var(--primary-pink); transition: all 0.3s ease;"
                                             onclick="openImageModal(this, 'Order #{{ $order->id }} - Payment Proof')"
                                             onmouseover="this.style.transform='scale(1.1)'; this.style.boxShadow='0 4px 12px rgba(0,0,0,0.15)'"
                                             onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='none'">
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
                                        <button class="action-btn action-btn-view" 
                                                data-bs-toggle="modal" data-bs-target="#viewModal{{ $order->id }}" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="action-btn action-btn-assign" 
                                                data-bs-toggle="modal" data-bs-target="#assignModal{{ $order->id }}" title="Assign Coordinator">
                                            <i class="fas fa-user-tie"></i>
                                        </button>
                                        <button class="action-btn action-btn-edit" 
                                                data-bs-toggle="modal" data-bs-target="#editModal{{ $order->id }}" title="Update Status">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="action-btn action-btn-delete"
                                                onclick="confirmDeleteOrder({{ $order->id }}, '{{ $order->id }}', '{{ route('admin.orders.delete', $order->id) }}')" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="13" class="text-center py-5">
                                    <i class="fas fa-inbox fa-3x mb-3" style="color: var(--text-secondary); opacity: 0.5;"></i>
                                    <p style="color: var(--text-secondary);">No orders found</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Mobile Card View -->
        <div class="d-lg-none" id="ordersCards">
            @forelse($orders as $order)
                <div class="order-card" data-status="{{ $order->delivery_status }}" style="border-bottom: 1px solid var(--border-color); padding: 1rem;">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h6 style="font-weight: 700; color: var(--primary-pink); margin-bottom: 0.25rem;">
                                Order #{{ $order->id }}
                            </h6>
                            <p style="margin: 0; font-size: 0.875rem; color: var(--text-secondary);">
                                <i class="fas fa-calendar me-1"></i>{{ \Carbon\Carbon::parse($order->created_at)->format('M d, Y') }}
                            </p>
                        </div>
                        <div>
                            @if($order->delivery_status == 'Delivered')
                                <span class="badge-modern badge-success" style="font-size: 0.75rem;">
                                    <i class="fas fa-check-circle"></i> Delivered
                                </span>
                            @elseif($order->delivery_status == 'Out for Delivery')
                                <span class="badge-modern badge-info" style="font-size: 0.75rem;">
                                    <i class="fas fa-shipping-fast"></i> Shipping
                                </span>
                            @elseif($order->delivery_status == 'Cancelled')
                                <span class="badge-modern badge-danger" style="font-size: 0.75rem;">
                                    <i class="fas fa-ban"></i> Cancelled
                                </span>
                            @else
                                <span class="badge-modern badge-warning" style="font-size: 0.75rem;">
                                    <i class="fas fa-clock"></i> Pending
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-user me-2" style="color: var(--primary-pink); width: 20px;"></i>
                            <span style="font-weight: 600;">{{ $order->customer_name }}</span>
                        </div>
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-phone me-2" style="color: var(--primary-pink); width: 20px;"></i>
                            <span style="font-size: 0.875rem;">{{ $order->phone }}</span>
                        </div>
                        
                        <!-- Price Breakdown - Mobile -->
                        <div class="mb-2 p-2" style="background: var(--light-bg); border-radius: 8px;">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span style="font-size: 0.8rem; color: var(--text-secondary);">Subtotal:</span>
                                <span style="font-size: 0.8rem;">₱{{ number_format($order->total, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span style="font-size: 0.8rem; color: var(--text-secondary);">Delivery Fee:</span>
                                <span style="font-size: 0.8rem; color: var(--warning);">₱{{ number_format($order->delivery_fee, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span style="font-weight: 700; color: var(--success);">GRAND TOTAL:</span>
                                <span style="font-weight: 800; color: var(--success); font-size: 1rem;">₱{{ number_format($order->grand_total, 2) }}</span>
                            </div>
                        </div>
                        
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-credit-card me-2" style="color: var(--primary-pink); width: 20px;"></i>
                                @if($order->payment_status == 'Paid')
                                    <span class="badge-modern badge-success" style="font-size: 0.75rem;">
                                        <i class="fas fa-check-circle"></i> Paid
                                    </span>
                                @else
                                    <span class="badge-modern badge-warning" style="font-size: 0.75rem;">
                                        <i class="fas fa-clock"></i> {{ $order->payment_status }}
                                    </span>
                                @endif
                            </div>
                            <div>
                                @if($order->payment_method === 'COD')
                                    <span class="badge-modern badge-warning" style="font-size: 0.75rem;">
                                        <i class="fas fa-money-bill-wave"></i> COD
                                    </span>
                                @elseif($order->payment_method === 'GCash')
                                    <span class="badge-modern badge-info" style="font-size: 0.75rem;">
                                        <i class="fas fa-mobile-alt"></i> GCash
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        @if($order->delivery_distance_km > 0)
                        <div class="d-flex align-items-center">
                            <i class="fas fa-road me-2" style="color: var(--primary-pink); width: 20px;"></i>
                            <span style="font-size: 0.75rem; color: var(--text-secondary);">{{ number_format($order->delivery_distance_km, 1) }} km from store</span>
                        </div>
                        @endif
                    </div>

                    @if($order->payment_proof)
                        <div class="mb-3">
                            <img src="{{ asset('uploads/payments/' . $order->payment_proof) }}" 
                                 alt="Payment Proof" 
                                 style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px; cursor: pointer; border: 2px solid var(--primary-pink);"
                                 onclick="openImageModal(this, 'Order #{{ $order->id }} - Payment Proof')">
                        </div>
                    @endif

                    <div class="d-flex gap-2 flex-wrap">
                        <button class="btn btn-sm mobile-action-btn mobile-btn-view flex-fill" 
                                data-bs-toggle="modal" data-bs-target="#viewModal{{ $order->id }}">
                            <i class="fas fa-eye me-1"></i>View
                        </button>
                        <button class="btn btn-sm mobile-action-btn mobile-btn-assign flex-fill" 
                                data-bs-toggle="modal" data-bs-target="#assignModal{{ $order->id }}">
                            <i class="fas fa-user-tie me-1"></i>Assign
                        </button>
                        <button class="btn btn-sm mobile-action-btn mobile-btn-edit flex-fill" 
                                data-bs-toggle="modal" data-bs-target="#editModal{{ $order->id }}">
                            <i class="fas fa-edit me-1"></i>Update
                        </button>
                        <button class="btn btn-sm mobile-action-btn mobile-btn-delete"
                                onclick="confirmDeleteOrder({{ $order->id }}, '{{ $order->id }}', '{{ route('admin.orders.delete', $order->id) }}')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            @empty
                <div class="text-center py-5">
                    <i class="fas fa-inbox fa-3x mb-3" style="color: var(--text-secondary); opacity: 0.5;"></i>
                    <p style="color: var(--text-secondary);">No orders found</p>
                </div>
            @endforelse
        </div>
        
        {{-- Pagination --}}
        @if($orders->hasPages())
            <div class="d-flex justify-content-center mt-4 pb-3">
                <nav aria-label="Page navigation">
                    <ul class="pagination pagination-modern">
                        {{-- Previous Page Link --}}
                        @if ($orders->onFirstPage())
                            <li class="page-item disabled">
                                <span class="page-link">
                                    <i class="fas fa-chevron-left"></i>
                                </span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $orders->previousPageUrl() }}" rel="prev">
                                    <i class="fas fa-chevron-left"></i>
                                </a>
                            </li>
                        @endif

                        {{-- Pagination Elements --}}
                        @php
                            $currentPage = $orders->currentPage();
                            $lastPage = $orders->lastPage();
                            $start = max(1, $currentPage - 2);
                            $end = min($lastPage, $currentPage + 2);
                        @endphp

                        @if($start > 1)
                            <li class="page-item">
                                <a class="page-link" href="{{ $orders->url(1) }}">1</a>
                            </li>
                            @if($start > 2)
                                <li class="page-item disabled">
                                    <span class="page-link">...</span>
                                </li>
                            @endif
                        @endif

                        @for($i = $start; $i <= $end; $i++)
                            @if ($i == $currentPage)
                                <li class="page-item active">
                                    <span class="page-link">{{ $i }}</span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link" href="{{ $orders->url($i) }}">{{ $i }}</a>
                                </li>
                            @endif
                        @endfor

                        @if($end < $lastPage)
                            @if($end < $lastPage - 1)
                                <li class="page-item disabled">
                                    <span class="page-link">...</span>
                                </li>
                            @endif
                            <li class="page-item">
                                <a class="page-link" href="{{ $orders->url($lastPage) }}">{{ $lastPage }}</a>
                            </li>
                        @endif

                        {{-- Next Page Link --}}
                        @if ($orders->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $orders->nextPageUrl() }}" rel="next">
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            </li>
                        @else
                            <li class="page-item disabled">
                                <span class="page-link">
                                    <i class="fas fa-chevron-right"></i>
                                </span>
                            </li>
                        @endif
                    </ul>
                </nav>
            </div>
            
            {{-- Page Info --}}
            <div class="text-center pb-3">
                <small style="color: var(--text-secondary);">
                    Showing {{ $orders->firstItem() }} to {{ $orders->lastItem() }} of {{ $orders->total() }} orders
                </small>
            </div>
        @endif
    </div>
</div>

<!-- Image Zoom Overlay (NOT a Bootstrap modal — avoids conflicts) -->
<div id="imageModal"
     onclick="if(event.target===this) closeImageModal()"
     style="display:none; position:fixed; inset:0; z-index:99999;
            background:rgba(0,0,0,0.92); align-items:center;
            justify-content:center; flex-direction:column; gap:1rem;
            backdrop-filter:blur(4px);">
    <button onclick="closeImageModal()"
            style="position:absolute; top:18px; right:28px;
                   background:none; border:none; color:#fff;
                   font-size:2.5rem; line-height:1; cursor:pointer;
                   opacity:0.8; transition:opacity 0.15s;"
            onmouseover="this.style.opacity=1"
            onmouseout="this.style.opacity=0.8">&times;</button>
    <img id="modalImage" alt=""
         style="max-width:90vw; max-height:80vh; object-fit:contain;
                border-radius:10px; box-shadow:0 8px 40px rgba(0,0,0,0.5);">
    <div id="modalCaption"
         style="color:#fff; font-size:0.95rem; font-weight:600;
                background:rgba(0,0,0,0.6); padding:6px 18px;
                border-radius:20px; max-width:90vw; text-align:center;"></div>
</div>

<!-- Modals for each order -->
@foreach($orders as $order)
    <!-- View Order Modal -->
    <div class="modal fade" id="viewModal{{ $order->id }}" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content" style="border-radius: 16px; border: none;">
                <div class="modal-header" style="background: linear-gradient(135deg, var(--primary-pink), #FF8AAE); color: white; border-radius: 16px 16px 0 0; border: none;">
                    <h5 class="modal-title"><i class="fas fa-receipt me-2"></i>Order #{{ $order->id }} Details</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-3 p-md-4">
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3 mb-md-0">
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
                            ->leftJoin('product_customizations', 'order_item.customization_id', '=', 'product_customizations.id')
                            ->where('order_item.order_id', $order->id)
                            ->select(
                                'products.name',
                                'products.image',
                                'order_item.quantity',
                                'order_item.price',
                                'order_item.is_customization',
                                'product_customizations.custom_image',
                                'product_customizations.customization_name',
                                'product_customizations.customization_details',
                                'product_customizations.special_instructions',
                                'product_customizations.admin_notes'
                            )
                            ->get();
                    @endphp
                    
                    <div class="table-responsive">
                        <table class="modern-table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-end">Price</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orderItems as $item)
                                    @php
                                        $isCustom  = $item->is_customization;
                                        $imgSrc    = $isCustom && $item->custom_image
                                            ? asset('uploads/customizations/' . $item->custom_image)
                                            : asset('asset/images/' . $item->image);
                                        $itemLabel = $item->name . ($isCustom ? ' (Custom)' : '');
                                    @endphp
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <img src="{{ $imgSrc }}"
                                                     width="40"
                                                     alt="{{ $itemLabel }}"
                                                     title="Click to enlarge"
                                                     style="border-radius: 8px; cursor: zoom-in; border: 2px solid var(--border-color); transition: transform 0.2s ease, box-shadow 0.2s ease;"
                                                     onmouseover="this.style.transform='scale(1.12)'; this.style.boxShadow='0 4px 12px rgba(0,0,0,0.18)'; this.style.borderColor='var(--primary-pink)';"
                                                     onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='none'; this.style.borderColor='var(--border-color)';"
                                                     onclick="openImageModal(this, '{{ addslashes($itemLabel) }}')">
                                                <div>
                                                    <span style="font-weight: 600; font-size: 0.875rem;">{{ $item->name }}</span>
                                                    @if($isCustom)
                                                        <span class="badge-modern badge-info" style="font-size: 0.7rem; padding: 2px 7px; margin-left: 4px;">Custom</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">{{ $item->quantity }}</td>
                                        <td class="text-end">₱{{ number_format($item->price, 2) }}</td>
                                        <td class="text-end" style="font-weight: 700;">₱{{ number_format($item->price * $item->quantity, 2) }}</td>
                                    </tr>
                                    {{-- Customization Info Row --}}
                                    @if($isCustom)
                                        <tr>
                                            <td colspan="4" style="padding: 0 0.75rem 0.75rem;">
                                                <div style="background: linear-gradient(135deg, #f0f4ff, #faf5ff);
                                                            border: 1px solid #c7d7ff;
                                                            border-left: 4px solid var(--primary-pink);
                                                            border-radius: 10px;
                                                            padding: 0.85rem 1rem;
                                                            font-size: 0.82rem;">
                                                    <div style="font-weight: 700; color: var(--primary-pink); margin-bottom: 0.6rem; font-size: 0.85rem;">
                                                        <i class="fas fa-paint-brush me-1"></i>Customization Details
                                                        @if($item->customization_name)
                                                            — <span style="color: var(--text-primary);">{{ $item->customization_name }}</span>
                                                        @endif
                                                    </div>
                                                    <div style="display: flex; flex-direction: column; gap: 0.45rem;">
                                                        @if($item->customization_details)
                                                            <div style="display: flex; gap: 0.5rem;">
                                                                <span style="font-weight: 600; color: var(--text-secondary); min-width: 130px;">
                                                                    <i class="fas fa-align-left me-1"></i>Description:
                                                                </span>
                                                                <span style="color: var(--text-primary);">{{ $item->customization_details }}</span>
                                                            </div>
                                                        @endif
                                                        @if($item->special_instructions)
                                                            <div style="display: flex; gap: 0.5rem;">
                                                                <span style="font-weight: 600; color: var(--text-secondary); min-width: 130px;">
                                                                    <i class="fas fa-sticky-note me-1"></i>Special Notes:
                                                                </span>
                                                                <span style="color: var(--text-primary);">{{ $item->special_instructions }}</span>
                                                            </div>
                                                        @endif
                                                        @if($item->admin_notes)
                                                            <div style="display: flex; gap: 0.5rem; margin-top: 0.25rem; padding-top: 0.45rem; border-top: 1px dashed #c7d7ff;">
                                                                <span style="font-weight: 600; color: var(--text-secondary); min-width: 130px;">
                                                                    <i class="fas fa-user-shield me-1"></i>Admin Notes:
                                                                </span>
                                                                <span style="color: var(--text-primary); font-style: italic;">{{ $item->admin_notes }}</span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr style="background: var(--light-bg);">
                                    <th colspan="3" class="text-end" style="padding: 1rem;">Subtotal:</th>
                                    <th class="text-end" style="color: var(--text-secondary); padding: 1rem;">₱{{ number_format($order->total, 2) }}</th>
                                </tr>
                                <tr style="background: var(--light-bg);">
                                    <th colspan="3" class="text-end" style="padding: 1rem;">Delivery Fee:</th>
                                    <th class="text-end" style="color: var(--warning); padding: 1rem;">₱{{ number_format($order->delivery_fee, 2) }}</th>
                                </tr>
                                <tr style="background: var(--light-bg); border-top: 2px solid var(--success);">
                                    <th colspan="3" class="text-end" style="padding: 1rem; font-size: 1.1rem;">GRAND TOTAL:</th>
                                    <th class="text-end" style="color: var(--success); font-size: 1.2rem; padding: 1rem;">₱{{ number_format($order->grand_total, 2) }}</th>
                                </tr>
                                @if($order->delivery_distance_km > 0)
                                <tr>
                                    <td colspan="4" class="text-center text-muted">
                                        <small><i class="fas fa-road me-1"></i>Distance: {{ number_format($order->delivery_distance_km, 1) }} km from store</small>
                                    </td>
                                </tr>
                                @endif
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
                            <strong>Order #{{ $order->id }}</strong> - {{ $order->customer_name }} | Total: ₱{{ number_format($order->grand_total, 2) }}
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

    <!-- Edit Order Status Modal with Automatic Payment -->
    <div class="modal fade" id="editModal{{ $order->id }}" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 16px; border: none;">
                <form method="POST" action="{{ route('admin.orders.update', $order->id) }}" id="editForm{{ $order->id }}">
                    @csrf
                    @method('PUT')
                    <div class="modal-header" style="background: linear-gradient(135deg, var(--warning), #F6C176); color: white; border-radius: 16px 16px 0 0;">
                        <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Update Order Status</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="alert" style="background: rgba(246, 173, 85, 0.1); border-left: 4px solid var(--warning); border-radius: 8px;">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Order #{{ $order->id }}</strong> - {{ $order->customer_name }} | Total: ₱{{ number_format($order->grand_total, 2) }}
                        </div>

                        <div class="mb-3">
                            <label class="form-label" style="font-weight: 600;"><i class="fas fa-money-bill-wave me-2"></i>Payment Status</label>
                            <select name="payment_status" class="form-select" id="paymentStatus{{ $order->id }}" style="border-radius: 12px; border: 1px solid var(--border-color);" required>
                                <option value="Pending" {{ $order->payment_status == 'Pending' ? 'selected' : '' }}>Pending</option>
                                <option value="Paid" {{ $order->payment_status == 'Paid' ? 'selected' : '' }}>Paid</option>
                                <option value="Unsuccessful" {{ $order->payment_status == 'Unsuccessful' ? 'selected' : '' }}>Unsuccessful</option>
                                <option value="Refunded" {{ $order->payment_status == 'Refunded' ? 'selected' : '' }}>Refunded</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" style="font-weight: 600;"><i class="fas fa-truck me-2"></i>Delivery Status</label>
                            <select name="delivery_status" class="form-select" id="deliveryStatus{{ $order->id }}" style="border-radius: 12px; border: 1px solid var(--border-color);" required onchange="autoUpdatePayment({{ $order->id }})">
                                <option value="Pending" {{ $order->delivery_status == 'Pending' ? 'selected' : '' }}>Pending</option>
                                <option value="Out for Delivery" {{ $order->delivery_status == 'Out for Delivery' ? 'selected' : '' }}>Out for Delivery</option>
                                <option value="Delivered" {{ $order->delivery_status == 'Delivered' ? 'selected' : '' }}>Delivered</option>
                                <option value="Cancelled" {{ $order->delivery_status == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                        
                        <!-- Auto-payment notification -->
                        <div class="alert alert-info" id="autoPaymentInfo{{ $order->id }}" style="display: none; border-radius: 8px; margin-top: 10px;">
                            <i class="fas fa-info-circle me-2"></i>
                            <span id="autoPaymentMessage{{ $order->id }}"></span>
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
            <div class="modal-body text-center py-4 py-md-5">
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
    /* Filter Tabs */
    .filter-tab {
        border-radius: 12px;
        padding: 0.625rem 1.25rem;
        font-weight: 600;
        transition: all 0.2s ease;
        border: 2px solid transparent;
        white-space: nowrap;
    }
    
    .filter-tab.active {
        background: linear-gradient(135deg, var(--primary-pink), #FF8AAE);
        color: white;
        border-color: var(--primary-pink);
    }
    
    .filter-tab:hover:not(.active) {
        background: var(--border-color);
        border-color: var(--border-color);
    }

    .filter-tabs-container {
        -webkit-overflow-scrolling: touch;
        scrollbar-width: thin;
    }

    .filter-tabs-container::-webkit-scrollbar {
        height: 6px;
    }

    .filter-tabs-container::-webkit-scrollbar-thumb {
        background: var(--border-color);
        border-radius: 3px;
    }

    /* Horizontal Scroll Buttons */
    .scroll-btn {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background: linear-gradient(135deg, var(--primary-pink), #FF8AAE);
        color: white;
        border: 2px solid var(--primary-pink);
        border-radius: 50%;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 10;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    }

    .scroll-btn:hover {
        background: linear-gradient(135deg, #FF8AAE, var(--primary-pink));
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.25);
        transform: translateY(-50%) scale(1.1);
    }

    .scroll-btn:active {
        transform: translateY(-50%) scale(0.95);
    }

    .scroll-left {
        left: 10px;
    }

    .scroll-right {
        right: 10px;
    }

    .scroll-btn.hidden {
        opacity: 0;
        pointer-events: none;
    }

    /* Action Buttons - Desktop */
    .action-btn {
        background: var(--light-bg);
        border: 2px solid var(--border-color);
        border-radius: 8px;
        padding: 0.5rem 0.75rem;
        cursor: pointer;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .action-btn:active {
        transform: translateY(0);
    }

    .action-btn-view {
        border-color: var(--secondary);
    }

    .action-btn-view:hover {
        background: var(--secondary);
        border-color: var(--secondary);
    }

    .action-btn-view:hover i {
        color: white;
    }

    .action-btn-view i {
        color: var(--secondary);
        transition: color 0.2s ease;
    }

    .action-btn-assign {
        border-color: var(--primary-pink);
    }

    .action-btn-assign:hover {
        background: var(--primary-pink);
        border-color: var(--primary-pink);
    }

    .action-btn-assign:hover i {
        color: white;
    }

    .action-btn-assign i {
        color: var(--primary-pink);
        transition: color 0.2s ease;
    }

    .action-btn-edit {
        border-color: var(--warning);
    }

    .action-btn-edit:hover {
        background: var(--warning);
        border-color: var(--warning);
    }

    .action-btn-edit:hover i {
        color: white;
    }

    .action-btn-edit i {
        color: var(--warning);
        transition: color 0.2s ease;
    }

    .action-btn-delete {
        border-color: var(--danger);
    }

    .action-btn-delete:hover {
        background: var(--danger);
        border-color: var(--danger);
    }

    .action-btn-delete:hover i {
        color: white;
    }

    .action-btn-delete i {
        color: var(--danger);
        transition: color 0.2s ease;
    }

    /* Action Buttons - Mobile */
    .mobile-action-btn {
        border: 2px solid var(--border-color);
        border-radius: 8px;
        padding: 0.5rem 1rem;
        font-weight: 600;
        transition: all 0.2s ease;
        background: white;
    }

    .mobile-action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .mobile-btn-view {
        border-color: var(--secondary);
        color: var(--secondary);
    }

    .mobile-btn-view:hover {
        background: var(--secondary);
        color: white;
        border-color: var(--secondary);
    }

    .mobile-btn-assign {
        border-color: var(--primary-pink);
        color: var(--primary-pink);
    }

    .mobile-btn-assign:hover {
        background: var(--primary-pink);
        color: white;
        border-color: var(--primary-pink);
    }

    .mobile-btn-edit {
        border-color: var(--warning);
        color: var(--warning);
    }

    .mobile-btn-edit:hover {
        background: var(--warning);
        color: white;
        border-color: var(--warning);
    }

    .mobile-btn-delete {
        background: var(--danger);
        color: white;
        border: 2px solid var(--danger);
    }

    .mobile-btn-delete:hover {
        background: #dc2626;
        border-color: #dc2626;
    }

    /* Order Cards */
    .order-card {
        transition: background-color 0.2s ease;
    }

    .order-card:hover {
        background-color: var(--light-bg);
    }

    .order-card:last-child {
        border-bottom: none !important;
    }

    /* Table Container */
    #tableContainer {
        scroll-behavior: smooth;
    }

    #tableContainer::-webkit-scrollbar {
        height: 8px;
    }

    #tableContainer::-webkit-scrollbar-track {
        background: var(--light-bg);
        border-radius: 10px;
    }

    #tableContainer::-webkit-scrollbar-thumb {
        background: var(--border-color);
        border-radius: 10px;
    }

    #tableContainer::-webkit-scrollbar-thumb:hover {
        background: var(--primary-pink);
    }

    /* Responsive adjustments */
    @media (max-width: 575px) {
        .filter-tab {
            padding: 0.5rem 0.75rem;
            font-size: 0.875rem;
        }

        .stat-card-label {
            font-size: 0.75rem;
        }

        .stat-card-value {
            font-size: 1.5rem;
        }

        .badge-modern {
            font-size: 0.7rem;
            padding: 0.25rem 0.5rem;
        }

        .mobile-action-btn {
            font-size: 0.875rem;
            padding: 0.4rem 0.75rem;
        }
    }

    @media (max-width: 991px) {
        .content-card-header {
            flex-direction: column;
            align-items: flex-start !important;
        }

        .content-card-header .input-group {
            max-width: 100% !important;
        }
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

        modalImg.src = imgElement.src;
        captionText.textContent = caption || imgElement.alt;
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }
    
    function closeImageModal() {
        const modal = document.getElementById('imageModal');
        modal.style.display = 'none';
        document.getElementById('modalImage').src = '';
        document.body.style.overflow = '';
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

    // Horizontal Table Scroll
    function scrollTable(direction) {
        const container = document.getElementById('tableContainer');
        const scrollAmount = 300;
        
        if (direction === 'left') {
            container.scrollLeft -= scrollAmount;
        } else {
            container.scrollLeft += scrollAmount;
        }
        
        updateScrollButtons();
    }

    function updateScrollButtons() {
        const container = document.getElementById('tableContainer');
        const scrollLeft = document.getElementById('scrollLeft');
        const scrollRight = document.getElementById('scrollRight');
        
        if (!container || !scrollLeft || !scrollRight) return;
        
        // Hide left button if at start
        if (container.scrollLeft <= 0) {
            scrollLeft.classList.add('hidden');
        } else {
            scrollLeft.classList.remove('hidden');
        }
        
        // Hide right button if at end
        if (container.scrollLeft + container.clientWidth >= container.scrollWidth - 5) {
            scrollRight.classList.add('hidden');
        } else {
            scrollRight.classList.remove('hidden');
        }
    }

    // Initialize scroll buttons on page load
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('tableContainer');
        if (container) {
            updateScrollButtons();
            container.addEventListener('scroll', updateScrollButtons);
        }
    });

    // Search Orders (works for both table and cards)
    document.getElementById('searchOrder').addEventListener('keyup', function() {
        const searchValue = this.value.toLowerCase();
        
        // Search in desktop table
        const tableRows = document.querySelectorAll('#ordersTable tbody tr');
        tableRows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchValue) ? '' : 'none';
        });

        // Search in mobile cards
        const orderCards = document.querySelectorAll('.order-card');
        orderCards.forEach(card => {
            const text = card.textContent.toLowerCase();
            card.style.display = text.includes(searchValue) ? '' : 'none';
        });
    });

    // Filter Orders (works for both table and cards)
    function filterOrders(status) {
        const tableRows = document.querySelectorAll('#ordersTable tbody tr');
        const orderCards = document.querySelectorAll('.order-card');
        const filterTabs = document.querySelectorAll('.filter-tab');
        
        // Update active tab
        filterTabs.forEach(tab => {
            if (tab.getAttribute('data-filter') === status) {
                tab.classList.add('active');
            } else {
                tab.classList.remove('active');
            }
        });
        
        // Filter desktop table
        tableRows.forEach(row => {
            if (status === 'all') {
                row.style.display = '';
            } else {
                const rowStatus = row.getAttribute('data-status');
                row.style.display = rowStatus === status ? '' : 'none';
            }
        });

        // Filter mobile cards
        orderCards.forEach(card => {
            if (status === 'all') {
                card.style.display = '';
            } else {
                const cardStatus = card.getAttribute('data-status');
                card.style.display = cardStatus === status ? '' : 'none';
            }
        });
    }

    // Auto-update payment status when delivery is set to "Delivered"
    function autoUpdatePayment(orderId) {
        const deliverySelect = document.getElementById('deliveryStatus' + orderId);
        const paymentSelect = document.getElementById('paymentStatus' + orderId);
        const autoPaymentInfo = document.getElementById('autoPaymentInfo' + orderId);
        const autoPaymentMessage = document.getElementById('autoPaymentMessage' + orderId);
        
        if (deliverySelect.value === 'Delivered') {
            // Check if payment is not already paid
            if (paymentSelect.value !== 'Paid') {
                // Store the previous value
                const previousValue = paymentSelect.value;
                
                // Set payment to Paid
                paymentSelect.value = 'Paid';
                
                // Show notification
                autoPaymentInfo.style.display = 'block';
                autoPaymentMessage.innerHTML = 'Payment status automatically set to <strong>Paid</strong> because order is delivered.';
                
                // Highlight the payment select
                paymentSelect.style.borderColor = '#10b981';
                paymentSelect.style.boxShadow = '0 0 0 3px rgba(16, 185, 129, 0.25)';
            }
        } else {
            // Hide notification if delivery is not "Delivered"
            autoPaymentInfo.style.display = 'none';
            paymentSelect.style.borderColor = '';
            paymentSelect.style.boxShadow = '';
        }
    }
    
    // Initialize auto-payment check when modals are opened
    document.addEventListener('DOMContentLoaded', function() {
        // For each edit modal, check if delivery is already "Delivered" on open
        @foreach($orders as $order)
            const editModal{{ $order->id }} = document.getElementById('editModal{{ $order->id }}');
            if (editModal{{ $order->id }}) {
                editModal{{ $order->id }}.addEventListener('shown.bs.modal', function() {
                    // Check if delivery status is already "Delivered"
                    const deliverySelect = document.getElementById('deliveryStatus{{ $order->id }}');
                    const paymentSelect = document.getElementById('paymentStatus{{ $order->id }}');
                    
                    if (deliverySelect.value === 'Delivered' && paymentSelect.value !== 'Paid') {
                        const autoPaymentInfo = document.getElementById('autoPaymentInfo{{ $order->id }}');
                        const autoPaymentMessage = document.getElementById('autoPaymentMessage{{ $order->id }}');
                        
                        autoPaymentInfo.style.display = 'block';
                        autoPaymentMessage.innerHTML = 'This order is already delivered. Consider setting payment to <strong>Paid</strong>.';
                    }
                });
            }
        @endforeach
    });
</script>
@endpush