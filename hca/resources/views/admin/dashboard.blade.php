<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - HookcraftAvenue</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .notification-bell {
            position: relative;
            cursor: pointer;
        }
        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #dc3545;
            color: white;
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 10px;
            font-weight: bold;
        }
        .notification-dropdown {
            position: absolute;
            right: 0;
            top: 100%;
            width: 380px;
            max-height: 500px;
            overflow-y: auto;
            background: white;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
            border-radius: 8px;
            z-index: 1000;
            display: none;
            margin-top: 10px;
        }
        .notification-dropdown.show {
            display: block;
        }
        .notification-item {
            padding: 15px;
            border-bottom: 1px solid #f0f0f0;
            transition: background 0.2s;
        }
        .notification-item:hover {
            background: #f8f9fa;
        }
        .notification-item.unread {
            background: #e3f2fd;
        }
        .notification-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            border-bottom: 2px solid #f0f0f0;
        }
        .notification-time {
            font-size: 11px;
            color: #999;
        }
        .notification-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
        }
        .notification-icon.delivery {
            background: #e3f2fd;
            color: #2196f3;
        }

    </style>
</head>
<body>
    @include('admin.layouts.navbar')

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            @include('admin.layouts.sidebar')

            <!-- Main Content -->
            <main class="col-md-10 ms-sm-auto px-md-4">
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
            </main>
        </div>
    </div>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Notification Toggle
        function toggleNotifications() {
            const dropdown = document.getElementById('notificationDropdown');
            dropdown.classList.toggle('show');
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const bell = document.querySelector('.notification-bell');
            const dropdown = document.getElementById('notificationDropdown');
            
            if (!bell.contains(event.target) && !dropdown.contains(event.target)) {
                dropdown.classList.remove('show');
            }
        });

        // Mark notification as read
        function markAsRead(notificationId, orderId) {
            fetch('/admin/notifications/' + notificationId + '/read', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            }).then(response => {
                if (response.ok) {
                    window.location.href = '/admin/orders/' + orderId;
                }
            });
        }

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
</body>
</html>