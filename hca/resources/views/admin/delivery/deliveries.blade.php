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
            box-shadow: inset -1px 0 0 rgba(0,0,0,.1);
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
        .product-image {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
            border: 2px solid #e9ecef;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .product-image:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        .info-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 5px;
            font-size: 0.875rem;
        }
        .info-value {
            color: #212529;
            margin-bottom: 15px;
        }
        .product-item {
            background: #f8f9fa;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 10px;
            border: 1px solid #e9ecef;
        }
        .product-item:last-child {
            margin-bottom: 0;
        }
        .section-divider {
            border-top: 2px solid #e9ecef;
            margin: 20px 0;
        }
        .total-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px;
            border-radius: 8px;
            margin-top: 15px;
        }
        
        /* Image Modal Styles */
        .image-modal {
            display: none;
            position: fixed;
            z-index: 9999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.9);
            animation: fadeIn 0.3s ease;
        }
        
        .image-modal.show {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .image-modal-content {
            max-width: 90%;
            max-height: 90%;
            object-fit: contain;
            border-radius: 8px;
            animation: zoomIn 0.3s ease;
        }
        
        .image-modal-close {
            position: absolute;
            top: 20px;
            right: 35px;
            color: #fff;
            font-size: 40px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
            z-index: 10000;
        }
        
        .image-modal-close:hover,
        .image-modal-close:focus {
            color: #bbb;
        }
        
        .image-modal-caption {
            position: absolute;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
            color: #fff;
            text-align: center;
            padding: 10px 20px;
            background: rgba(0, 0, 0, 0.7);
            border-radius: 8px;
            font-size: 16px;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes zoomIn {
            from { transform: scale(0.5); }
            to { transform: scale(1); }
        }
        
        @media (max-width: 768px) {
            main {
                margin-left: 0;
            }
            .image-modal-content {
                max-width: 95%;
                max-height: 80%;
            }
            .image-modal-close {
                top: 10px;
                right: 15px;
                font-size: 30px;
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

                <div class="d-flex justify-content-between align-items-center pb-2 mb-4 border-bottom">
                    <h1 class="h2"><i class="fas fa-box me-2 text-primary"></i>My Deliveries</h1>
                    <span class="badge bg-primary fs-6 px-3 py-2">
                        <i class="fas fa-calendar me-2"></i>{{ date('F d, Y') }}
                    </span>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Deliveries List -->
                <div class="row">
                    @forelse ($deliveries as $delivery)
                        <div class="col-lg-6 mb-4">
                            <div class="card order-card">
                                <div class="card-body">
                                    <!-- Header with Order ID and Status -->
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h5 class="mb-0">
                                            <span class="badge bg-secondary">#{{ $delivery->id }}</span>
                                        </h5>
                                        @php
                                            $status = $delivery->delivery_status;
                                        @endphp
                                        @if($status === 'Delivered')
                                            <span class="badge bg-success">Delivered</span>
                                        @elseif($status === 'Out for Delivery')
                                            <span class="badge bg-info">Out for Delivery</span>
                                        @elseif($status === 'Cancelled')
                                            <span class="badge bg-danger">Cancelled</span>
                                        @else
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        @endif
                                    </div>

                                    <!-- Customer Information -->
                                    <div class="mb-3">
                                        <div class="info-label">
                                            <i class="fas fa-user text-primary me-2"></i>Customer Name
                                        </div>
                                        <div class="info-value">{{ $delivery->customer_name }}</div>

                                        <div class="info-label">
                                            <i class="fas fa-phone text-success me-2"></i>Phone Number
                                        </div>
                                        <div class="info-value">
                                            <a href="tel:{{ $delivery->phone }}" class="text-decoration-none">
                                                {{ $delivery->phone }}
                                            </a>
                                        </div>

                                        <div class="info-label">
                                            <i class="fas fa-map-marker-alt text-danger me-2"></i>Delivery Address
                                        </div>
                                        <div class="info-value">{{ $delivery->address }}</div>
                                    </div>

                                    <div class="section-divider"></div>

                                    <!-- Products Section -->
                                    <div class="mb-3">
                                        <div class="info-label mb-3">
                                            <i class="fas fa-shopping-bag text-info me-2"></i>Order Items
                                        </div>

                                        @php
                                            // Get order items with product details
                                            $orderItems = DB::table('order_item')
                                                ->join('products', 'order_item.product_id', '=', 'products.id')
                                                ->where('order_item.order_id', $delivery->id)
                                                ->select(
                                                    'products.name as product_name',
                                                    'products.image as product_image',
                                                    'order_item.quantity',
                                                    'order_item.price'
                                                )
                                                ->get();
                                        @endphp

                                        @forelse($orderItems as $item)
                                            <div class="product-item">
                                                <div class="row align-items-center">
                                                    <div class="col-auto">
                                                        @if($item->product_image)
                                                            <img src="{{ asset('asset/images/' . $item->product_image) }}" 
                                                                 alt="{{ $item->product_name }}" 
                                                                 class="product-image"
                                                                 onclick="openImageModal(this)"
                                                                 title="Click to zoom"
                                                                 onerror="this.onerror=null; this.src='{{ asset('asset/images/default-product.png') }}';">
                                                        @else
                                                            <div class="product-image bg-secondary d-flex align-items-center justify-content-center">
                                                                <i class="fas fa-image text-white"></i>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="col">
                                                        <h6 class="mb-1">{{ $item->product_name }}</h6>
                                                        <small class="text-muted">
                                                            Qty: {{ $item->quantity }} × ₱{{ number_format($item->price, 2) }}
                                                        </small>
                                                        <div class="fw-bold text-primary">
                                                            ₱{{ number_format($item->quantity * $item->price, 2) }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="product-item text-center text-muted">
                                                <i class="fas fa-box-open me-2"></i>No items found
                                            </div>
                                        @endforelse
                                    </div>

                                    <!-- Total Price -->
                                    <div class="total-section">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h5 class="mb-0">
                                                <i class="fas fa-receipt me-2"></i>Total Amount
                                            </h5>
                                            <h4 class="mb-0">₱{{ number_format($delivery->total, 2) }}</h4>
                                        </div>
                                    </div>

                                    <div class="section-divider"></div>

                                    <!-- Action Buttons -->
                                    <div class="d-grid gap-2">
                                        @if($status === 'Pending')
                                            <button class="btn btn-warning btn-sm"
                                                onclick="confirmStatus('{{ $delivery->id }}', '{{ $delivery->customer_name }}', 'Out for Delivery')">
                                                <i class="fas fa-shipping-fast me-2"></i>Out for Delivery
                                            </button>

                                            <button class="btn btn-danger btn-sm"
                                                onclick="confirmStatus('{{ $delivery->id }}', '{{ $delivery->customer_name }}', 'Cancelled')">
                                                <i class="fas fa-times-circle me-2"></i>Cancel Order
                                            </button>
                                        @endif

                                        @if($status === 'Out for Delivery')
                                            <button class="btn btn-success btn-sm"
                                                onclick="confirmStatus('{{ $delivery->id }}', '{{ $delivery->customer_name }}', 'Delivered')">
                                                <i class="fas fa-check-circle me-2"></i>Mark as Delivered
                                            </button>
                                        @endif

                                        @if($status === 'Delivered')
                                            <button class="btn btn-success btn-sm" disabled>
                                                <i class="fas fa-check-circle me-2"></i>Delivered
                                            </button>
                                        @endif

                                        @if($status === 'Cancelled')
                                            <button class="btn btn-secondary btn-sm" disabled>
                                                <i class="fas fa-ban me-2"></i>Cancelled
                                            </button>
                                        @endif
                                    </div>

                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="text-center py-5">
                                <i class="fas fa-box-open fa-4x text-muted mb-3"></i>
                                <p class="text-muted">No deliveries assigned yet</p>
                            </div>
                        </div>
                    @endforelse
                </div>

            </main>
        </div>
    </div>

    <!-- Image Zoom Modal -->
    <div class="image-modal" id="imageModal" onclick="closeImageModal()">
        <span class="image-modal-close" onclick="closeImageModal()">&times;</span>
        <img class="image-modal-content" id="modalImage" alt="Product Image">
        <div class="image-modal-caption" id="modalCaption"></div>
    </div>

    <!-- Confirmation Modal -->
    <div class="modal fade" id="confirmModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-exclamation-circle me-2"></i>Confirm Status Update</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <form id="confirmStatusForm" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="modal-body">
                        <p class="fw-bold mb-1">Order ID:</p>
                        <p id="confirmOrderId"></p>

                        <p class="fw-bold mb-1">Customer:</p>
                        <p id="confirmCustomerName"></p>

                        <p class="fw-bold mb-1">Change status to:</p>
                        <p class="text-primary fw-bold" id="confirmNewStatus"></p>

                        <input type="hidden" name="delivery_status" id="hiddenStatusValue">
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                        <button type="submit" class="btn btn-primary">Yes, Update</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Image Modal Functions
        function openImageModal(imgElement) {
            const modal = document.getElementById('imageModal');
            const modalImg = document.getElementById('modalImage');
            const caption = document.getElementById('modalCaption');
            
            modal.classList.add('show');
            modalImg.src = imgElement.src;
            caption.textContent = imgElement.alt;
            
            // Prevent body scroll when modal is open
            document.body.style.overflow = 'hidden';
        }
        
        function closeImageModal() {
            const modal = document.getElementById('imageModal');
            modal.classList.remove('show');
            
            // Restore body scroll
            document.body.style.overflow = 'auto';
        }
        
        // Close modal with Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeImageModal();
            }
        });
        
        // Prevent modal close when clicking on image
        document.getElementById('modalImage').addEventListener('click', function(event) {
            event.stopPropagation();
        });
        
        // Status Confirmation Modal
        function confirmStatus(orderId, customerName, newStatus) {
            document.getElementById('confirmOrderId').innerText = '#' + orderId;
            document.getElementById('confirmCustomerName').innerText = customerName;
            document.getElementById('confirmNewStatus').innerText = newStatus;
            document.getElementById('hiddenStatusValue').value = newStatus;

            document.getElementById('confirmStatusForm').action =
                '/delivery/deliveries/' + orderId + '/status';

            new bootstrap.Modal(document.getElementById('confirmModal')).show();
        }
    </script>

</body>
</html>