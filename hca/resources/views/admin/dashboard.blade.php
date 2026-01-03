@extends('admin.layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Dashboard</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <button type="button" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-calendar"></i> Today
            </button>
        </div>
        
        <!-- Export Dropdown -->
        <div class="btn-group me-2">
            <button type="button" class="btn btn-sm btn-success dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-file-csv"></i> Export Reports
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#" onclick="event.preventDefault(); exportOrdersCSV();">
                    <i class="fas fa-shopping-cart me-2"></i>Orders Report
                </a></li>
                <li><a class="dropdown-item" href="#" onclick="event.preventDefault(); exportSalesCSV();">
                    <i class="fas fa-chart-line me-2"></i>Sales Report
                </a></li>
            </ul>
        </div>
        
        <!-- Notification Bell -->
        <div class="position-relative">
            <div class="notification-bell" onclick="toggleNotifications()">
                <i class="fas fa-bell fa-2x text-primary"></i>
                @if($unreadCount > 0)
                <span class="notification-badge">{{ $unreadCount }}</span>
                @endif
            </div>
            
            <!-- Notification Dropdown -->
            <div class="notification-dropdown" id="notificationDropdown">
                <div class="notification-header">
                    <h6 class="mb-0"><strong>Notifications</strong></h6>
                    @if($unreadCount > 0)
                    <a href="{{ route('admin.notifications.markAllRead') }}" class="btn btn-sm btn-link text-decoration-none">
                        Mark all read
                    </a>
                    @endif
                </div>
                
                <div class="notification-list">
                    @forelse($notifications as $notification)
                    <div class="notification-item {{ $notification['is_read'] ? '' : 'unread' }}" onclick="markAsRead('{{ $notification['id'] }}', '{{ $notification['order_id'] }}')">
                        <div class="d-flex">
                            <div class="notification-icon delivery">
                                <i class="fas fa-truck"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1"><strong>{{ $notification['title'] }}</strong></h6>
                                <p class="mb-1 small">{{ $notification['message'] }}</p>
                                <span class="notification-time">
                                    <i class="far fa-clock"></i> 
                                    {{ \Carbon\Carbon::parse($notification['timestamp'])->diffForHumans() }}
                                </span>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-5 text-muted">
                        <i class="far fa-bell-slash fa-3x mb-3"></i>
                        <p>No notifications yet</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card text-white bg-primary mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Total Sales</h6>
                        <h3>₱{{ number_format($totalSales, 2) }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-peso-sign fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card text-white bg-success mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Total Orders</h6>
                        <h3>{{ $totalOrders }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-shopping-cart fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card text-white bg-warning mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Pending Orders</h6>
                        <h3>{{ $pendingOrders }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-clock fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card text-white bg-info mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Total Products</h6>
                        <h3>{{ $totalProducts }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-box fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row mb-4">
    <!-- Sales Chart -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">Sales Analytics (Last 6 Months)</h5>
            </div>
            <div class="card-body">
                <canvas id="salesChart" height="80"></canvas>
            </div>
        </div>
    </div>

    <!-- Top Products -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">Top Selling Products</h5>
            </div>
            <div class="card-body">
                <canvas id="topProductsChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Recent Orders -->
<div class="card mb-4">
    <div class="card-header bg-white">
        <h5 class="mb-0">Recent Orders</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Total</th>
                        <th>Payment Status</th>
                        <th>Delivery Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentOrders as $order)
                        <tr>
                            <td>#{{ $order->id }}</td>
                            <td>{{ $order->customer_name }}</td>
                            <td>₱{{ number_format($order->total, 2) }}</td>
                            <td>
                                <span class="badge bg-{{ $order->payment_status == 'Paid' ? 'success' : 'warning' }}">
                                    {{ $order->payment_status }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $order->delivery_status == 'Delivered' ? 'success' : 'info' }}">
                                    {{ $order->delivery_status }}
                                </span>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($order->created_at)->format('M d, Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@if(isset($lowStockProducts) && count($lowStockProducts) > 0)
<!-- Low Stock Alert -->
<div class="card mb-4 border-warning">
    <div class="card-header bg-warning text-white">
        <h5 class="mb-0"><i class="fas fa-exclamation-triangle"></i> Low Stock Alert</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Category</th>
                        <th>Stock</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($lowStockProducts as $product)
                        <tr>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->category->name ?? 'N/A' }}</td>
                            <td><strong>{{ $product->stock }}</strong></td>
                            <td>
                                <span class="badge bg-warning">Low Stock</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

@if(isset($outOfStockProducts) && count($outOfStockProducts) > 0)
<!-- Out of Stock Alert -->
<div class="card mb-4 border-danger">
    <div class="card-header bg-danger text-white">
        <h5 class="mb-0"><i class="fas fa-times-circle"></i> Out of Stock</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Category</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($outOfStockProducts as $product)
                        <tr>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->category->name ?? 'N/A' }}</td>
                            <td>
                                <span class="badge bg-danger">Out of Stock</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif
@endsection

@push('scripts')
<script>
    // Export Orders to CSV
    function exportOrdersCSV() {
        const orders = {!! json_encode($recentOrders) !!};
        
        let csv = 'Order ID,Customer Name,Total,Payment Status,Delivery Status,Date\n';
        
        orders.forEach(order => {
            const total = parseFloat(order.total).toFixed(2);
            const date = new Date(order.created_at).toLocaleDateString();
            csv += `#${order.id},"${order.customer_name}",${total},${order.payment_status},${order.delivery_status},"${date}"\n`;
        });
        
        downloadCSV(csv, `orders_report_${new Date().toISOString().split('T')[0]}.csv`);
    }

    // Export Sales to CSV
    function exportSalesCSV() {
        const salesData = {!! json_encode($monthlySales) !!};
        
        let csv = 'Month,Total Sales\n';
        
        salesData.forEach(item => {
            const total = parseFloat(item.total).toFixed(2);
            csv += `"${item.month}",${total}\n`;
        });
        
        downloadCSV(csv, `sales_report_${new Date().toISOString().split('T')[0]}.csv`);
    }

    // Download CSV Helper Function
    function downloadCSV(csvContent, filename) {
        const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
        const link = document.createElement('a');
        
        if (link.download !== undefined) {
            const url = URL.createObjectURL(blob);
            link.setAttribute('href', url);
            link.setAttribute('download', filename);
            link.style.visibility = 'hidden';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    }

    // Sales Chart
    const salesCtx = document.getElementById('salesChart').getContext('2d');
    const salesChart = new Chart(salesCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($monthlySales->pluck('month')) !!},
            datasets: [{
                label: 'Sales (₱)',
                data: {!! json_encode($monthlySales->pluck('total')) !!},
                borderColor: '#FFB6C1',
                backgroundColor: 'rgba(255, 182, 193, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '₱' + value.toLocaleString();
                        }
                    }
                }
            }
        }
    });

    // Top Products Chart
    const topProductsCtx = document.getElementById('topProductsChart').getContext('2d');
    const topProductsChart = new Chart(topProductsCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($topProducts->pluck('name')) !!},
            datasets: [{
                data: {!! json_encode($topProducts->pluck('total_sold')) !!},
                backgroundColor: [
                    '#FFB6C1',
                    '#87CEEB',
                    '#98FB98',
                    '#DDA0DD',
                    '#F0E68C'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
</script>
@endpush