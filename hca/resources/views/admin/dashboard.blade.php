<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - HookcraftAvenue</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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