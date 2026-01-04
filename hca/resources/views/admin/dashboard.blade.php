@extends('admin.layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="animate-fade-in">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 style="font-family: 'Playfair Display', serif; font-size: 2.25rem; font-weight: 700; margin-bottom: 0.5rem;">
                Dashboard Overview
            </h1>
            <p style="color: var(--text-secondary); margin: 0;">Welcome back, {{ session('admin_name', 'Admin') }}! Here's what's happening today.</p>
        </div>
        
        <div class="d-flex gap-2">
            <div class="dropdown">
                <button class="btn btn-modern-secondary dropdown-toggle" data-bs-toggle="dropdown">
                    <i class="fas fa-download me-2"></i>Export Reports
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
        <div class="col-md-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-card-icon primary">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <div class="stat-card-label">Total Revenue</div>
                <div class="stat-card-value">₱{{ number_format($totalSales, 2) }}</div>
                <div style="color: var(--success); font-size: 0.875rem; font-weight: 600;">
                    <i class="fas fa-arrow-up me-1"></i>+12.5% from last month
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-card-icon success">
                    <i class="fas fa-shopping-bag"></i>
                </div>
                <div class="stat-card-label">Total Orders</div>
                <div class="stat-card-value">{{ $totalOrders }}</div>
                <div style="color: var(--success); font-size: 0.875rem; font-weight: 600;">
                    <i class="fas fa-arrow-up me-1"></i>+8.2% from last month
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-card-icon warning">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-card-label">Pending Orders</div>
                <div class="stat-card-value">{{ $pendingOrders }}</div>
                <div style="color: var(--text-secondary); font-size: 0.875rem; font-weight: 600;">
                    Needs attention
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-card-icon info">
                    <i class="fas fa-box"></i>
                </div>
                <div class="stat-card-label">Products</div>
                <div class="stat-card-value">{{ $totalProducts }}</div>
                <div style="color: var(--text-secondary); font-size: 0.875rem; font-weight: 600;">
                    In inventory
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row g-4 mb-4">
        <div class="col-lg-8">
            <div class="content-card">
                <div class="content-card-header">
                    <h3 class="content-card-title">Sales Analytics</h3>
                    <span style="color: var(--text-secondary); font-size: 0.875rem;">Last 6 months</span>
                </div>
                <div class="p-4">
                    <canvas id="salesChart" height="80"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="content-card">
                <div class="content-card-header">
                    <h3 class="content-card-title">Top Products</h3>
                </div>
                <div class="p-4">
                    <canvas id="topProductsChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="content-card mb-4">
        <div class="content-card-header">
            <h3 class="content-card-title">Recent Orders</h3>
            <a href="{{ route('admin.orders') }}" class="btn btn-modern-secondary btn-sm">
                View All <i class="fas fa-arrow-right ms-2"></i>
            </a>
        </div>
        <div class="table-responsive">
            <table class="modern-table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Amount</th>
                        <th>Payment</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentOrders as $order)
                        <tr>
                            <td>
                                <span style="font-weight: 600; color: var(--primary-pink);">#{{ $order->id }}</span>
                            </td>
                            <td>
                                <div style="font-weight: 600;">{{ $order->customer_name }}</div>
                            </td>
                            <td>
                                <span style="font-weight: 700; color: var(--success);">₱{{ number_format($order->total, 2) }}</span>
                            </td>
                            <td>
                                @if($order->payment_status == 'Paid')
                                    <span class="badge-modern badge-success">
                                        <i class="fas fa-check-circle"></i>Paid
                                    </span>
                                @else
                                    <span class="badge-modern badge-warning">
                                        <i class="fas fa-clock"></i>Pending
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if($order->delivery_status == 'Delivered')
                                    <span class="badge-modern badge-success">
                                        <i class="fas fa-check"></i>Delivered
                                    </span>
                                @elseif($order->delivery_status == 'Out for Delivery')
                                    <span class="badge-modern badge-info">
                                        <i class="fas fa-truck"></i>Shipping
                                    </span>
                                @else
                                    <span class="badge-modern badge-warning">
                                        <i class="fas fa-clock"></i>{{ $order->delivery_status }}
                                    </span>
                                @endif
                            </td>
                            <td>
                                <span style="color: var(--text-secondary); font-size: 0.875rem;">
                                    {{ \Carbon\Carbon::parse($order->created_at)->format('M d, Y') }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <i class="fas fa-inbox fa-3x mb-3" style="color: var(--border-color);"></i>
                                <p style="color: var(--text-secondary);">No recent orders</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Inventory Alerts -->
    <div class="row g-4">
        @if(isset($lowStockProducts) && count($lowStockProducts) > 0)
        <div class="col-lg-6">
            <div class="content-card" style="border-left: 4px solid var(--warning);">
                <div class="content-card-header">
                    <div>
                        <h3 class="content-card-title">
                            <i class="fas fa-exclamation-triangle" style="color: var(--warning);"></i>
                            Low Stock Alert
                        </h3>
                        <p style="margin: 0.25rem 0 0 0; font-size: 0.875rem; color: var(--text-secondary);">
                            {{ count($lowStockProducts) }} products running low
                        </p>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="modern-table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Category</th>
                                <th>Stock</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($lowStockProducts as $product)
                                <tr>
                                    <td><strong>{{ $product->name }}</strong></td>
                                    <td><span class="badge-modern badge-info">{{ $product->category->name ?? 'N/A' }}</span></td>
                                    <td><span class="badge-modern badge-warning"><strong>{{ $product->stock }}</strong> left</span></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif

        @if(isset($outOfStockProducts) && count($outOfStockProducts) > 0)
        <div class="col-lg-6">
            <div class="content-card" style="border-left: 4px solid var(--danger);">
                <div class="content-card-header">
                    <div>
                        <h3 class="content-card-title">
                            <i class="fas fa-times-circle" style="color: var(--danger);"></i>
                            Out of Stock
                        </h3>
                        <p style="margin: 0.25rem 0 0 0; font-size: 0.875rem; color: var(--text-secondary);">
                            {{ count($outOfStockProducts) }} products need restocking
                        </p>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="modern-table">
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
                                    <td><strong>{{ $product->name }}</strong></td>
                                    <td><span class="badge-modern badge-info">{{ $product->category->name ?? 'N/A' }}</span></td>
                                    <td><span class="badge-modern badge-danger"><i class="fas fa-times"></i>Out of Stock</span></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Export Functions
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

    function exportSalesCSV() {
        const salesData = {!! json_encode($monthlySales) !!};
        let csv = 'Month,Total Sales\n';
        salesData.forEach(item => {
            const total = parseFloat(item.total).toFixed(2);
            csv += `"${item.month}",${total}\n`;
        });
        downloadCSV(csv, `sales_report_${new Date().toISOString().split('T')[0]}.csv`);
    }

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
    const gradient = salesCtx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(255, 107, 157, 0.2)');
    gradient.addColorStop(1, 'rgba(255, 107, 157, 0.0)');

    new Chart(salesCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($monthlySales->pluck('month')) !!},
            datasets: [{
                label: 'Sales',
                data: {!! json_encode($monthlySales->pluck('total')) !!},
                borderColor: '#FF6B9D',
                backgroundColor: gradient,
                borderWidth: 3,
                tension: 0.4,
                fill: true,
                pointBackgroundColor: '#FF6B9D',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 6,
                pointHoverRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#fff',
                    titleColor: '#1A202C',
                    bodyColor: '#718096',
                    borderColor: '#E2E8F0',
                    borderWidth: 1,
                    padding: 12,
                    boxPadding: 6,
                    usePointStyle: true
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: '#F7FAFC', drawBorder: false },
                    ticks: {
                        callback: value => '₱' + value.toLocaleString(),
                        color: '#718096'
                    }
                },
                x: {
                    grid: { display: false, drawBorder: false },
                    ticks: { color: '#718096' }
                }
            }
        }
    });

    // Top Products Chart
    new Chart(document.getElementById('topProductsChart'), {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($topProducts->pluck('name')) !!},
            datasets: [{
                data: {!! json_encode($topProducts->pluck('total_sold')) !!},
                backgroundColor: [
                    '#FF6B9D',
                    '#667EEA',
                    '#48BB78',
                    '#F6AD55',
                    '#FC8181'
                ],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 15,
                        usePointStyle: true,
                        font: { size: 12, family: 'DM Sans' }
                    }
                }
            }
        }
    });
</script>
@endpush