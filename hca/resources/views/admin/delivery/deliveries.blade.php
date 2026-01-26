<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Deliveries</title>
    
    <!-- CRITICAL: Load theme BEFORE any styles to prevent flicker -->
    <script>
        (function() {
            const savedTheme = localStorage.getItem('delivery-theme') || 'light';
            document.documentElement.setAttribute('data-theme', savedTheme);
        })();
    </script>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    
    <style>
        /* EXACT DESIGN FROM YOUR DASHBOARD - NO CHANGES TO YOUR HTML */
        /* Light Mode Colors */
        :root {
            --primary-blue: #667eea;
            --primary-purple: #764ba2;
            --primary-dark: #2D3748;
            --success: #48BB78;
            --warning: #F6AD55;
            --danger: #FC8181;
            --info: #63B3ED;
            --light-bg: #F7FAFC;
            --card-bg: #FFFFFF;
            --text-primary: #1A202C;
            --text-secondary: #718096;
            --border-color: #E2E8F0;
            --sidebar-width: 280px;
            --hover-bg: rgba(0, 0, 0, 0.05);
        }

        /* Dark Mode Colors */
        [data-theme="dark"] {
            --primary-blue: #667eea;
            --primary-purple: #764ba2;
            --primary-dark: #1A202C;
            --success: #48BB78;
            --warning: #F6AD55;
            --danger: #FC8181;
            --info: #63B3ED;
            --light-bg: #1A202C;
            --card-bg: #2D3748;
            --text-primary: #F7FAFC;
            --text-secondary: #A0AEC0;
            --border-color: #4A5568;
            --hover-bg: rgba(255, 255, 255, 0.05);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DM Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--light-bg);
            color: var(--text-primary);
            margin-top: 20px;
            line-height: 1.6;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        /* Modern Navbar - EXACT from your dashboard */
     

        /* Main Content - EXACT from your dashboard */
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 6rem 2rem 2rem;
            min-height: 100vh;
            transition: all 0.3s ease;
        }

        /* Page Header - EXACT from your dashboard */
        .page-header {
            margin-bottom: 2rem;
        }

        .page-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .page-subtitle {
            color: var(--text-secondary);
            font-size: 1rem;
        }

        .date-badge {
            background: linear-gradient(135deg, var(--primary-blue), var(--primary-purple));
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 12px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* Stats Cards - EXACT from your dashboard */
        .stat-card {
            background: var(--card-bg);
            border-radius: 16px;
            padding: 1.75rem;
            border: 1px solid var(--border-color);
            transition: all 0.2s ease;
            height: 100%;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.08);
        }

        .stat-card-icon {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

        .stat-card-icon.primary {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.15), rgba(102, 126, 234, 0.05));
            color: var(--primary-blue);
        }

        .stat-card-icon.success {
            background: linear-gradient(135deg, rgba(72, 187, 120, 0.15), rgba(72, 187, 120, 0.05));
            color: var(--success);
        }

        .stat-card-icon.warning {
            background: linear-gradient(135deg, rgba(246, 173, 85, 0.15), rgba(246, 173, 85, 0.05));
            color: var(--warning);
        }

        .stat-card-icon.info {
            background: linear-gradient(135deg, rgba(99, 179, 237, 0.15), rgba(99, 179, 237, 0.05));
            color: var(--info);
        }

        .stat-card-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0.5rem 0;
        }

        .stat-card-label {
            color: var(--text-secondary);
            font-size: 0.875rem;
            font-weight: 500;
        }

        /* Content Cards - EXACT from your dashboard */
        .content-card {
            background: var(--card-bg);
            border-radius: 16px;
            border: 1px solid var(--border-color);
            overflow: hidden;
            margin-bottom: 2rem;
        }

        .content-card-header {
            padding: 1.5rem 2rem;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .content-card-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .content-card-body {
            padding: 2rem;
        }

        /* Order Cards - EXACT from your dashboard */
        .order-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 1.5rem;
            transition: all 0.2s ease;
            border-left: 4px solid var(--primary-blue);
        }

        .order-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        }

        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .order-id {
            background: var(--text-secondary);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.875rem;
        }

        .customer-name {
            font-size: 1.125rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
        }

        .order-address {
            display: flex;
            align-items: start;
            gap: 0.5rem;
            color: var(--text-secondary);
            font-size: 0.875rem;
            margin-bottom: 1rem;
        }

        /* Badges - EXACT from your dashboard */
        .badge-modern {
            padding: 0.375rem 0.875rem;
            border-radius: 8px;
            font-size: 0.8125rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
        }

        .badge-success {
            background: rgba(72, 187, 120, 0.15);
            color: var(--success);
        }

        .badge-warning {
            background: rgba(246, 173, 85, 0.15);
            color: var(--warning);
        }

        .badge-danger {
            background: rgba(252, 129, 129, 0.15);
            color: var(--danger);
        }

        .badge-info {
            background: rgba(99, 179, 237, 0.15);
            color: var(--info);
        }

        /* Buttons - EXACT from your dashboard */
        .btn-modern {
            padding: 0.625rem 1.25rem;
            border-radius: 10px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            justify-content: center;
        }

        .btn-modern-primary {
            background: linear-gradient(135deg, var(--primary-blue), var(--primary-purple));
            color: white;
        }

        .btn-modern-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(102, 126, 234, 0.3);
            color: white;
        }

        .btn-modern-success {
            background: var(--success);
            color: white;
        }

        .btn-modern-success:hover {
            background: #38a169;
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(72, 187, 120, 0.3);
            color: white;
        }

        .btn-modern-info {
            background: var(--info);
            color: white;
        }

        .btn-modern-info:hover {
            background: #4299e1;
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(99, 179, 237, 0.3);
            color: white;
        }

        .btn-modern-danger {
            background: var(--danger);
            color: white;
        }

        .btn-modern-danger:hover {
            background: #f56565;
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(252, 129, 129, 0.3);
            color: white;
        }

        .btn-modern-secondary {
            background: var(--light-bg);
            color: var(--text-primary);
        }

        .btn-modern-secondary:hover {
            background: var(--hover-bg);
        }

        /* Alerts - EXACT from your dashboard */
        .alert-modern {
            padding: 1rem 1.5rem;
            border-radius: 12px;
            border: none;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .alert-success {
            background: rgba(72, 187, 120, 0.15);
            color: var(--success);
        }

        .alert-danger {
            background: rgba(252, 129, 129, 0.15);
            color: var(--danger);
        }

        /* Modal Dark Mode - EXACT from your dashboard */
        .modal-content {
            background: var(--card-bg);
            color: var(--text-primary);
            border: 1px solid var(--border-color);
            border-radius: 16px;
        }

        .modal-header {
            border-bottom: 1px solid var(--border-color);
        }

        .modal-footer {
            border-top: 1px solid var(--border-color);
        }

        /* Scrollbar - EXACT from your dashboard */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: var(--light-bg);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--border-color);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--text-secondary);
        }

        /* Animations - EXACT from your dashboard */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fadeIn 0.5s ease;
        }

        /* Responsive - EXACT from your dashboard */
        @media (max-width: 768px) {
            .modern-sidebar {
                transform: translateX(-100%);
            }

            .modern-sidebar.show {
                transform: translateX(0);
            }

            .modern-navbar,
            .main-content {
                margin-left: 0;
                left: 0;
            }

            .page-title {
                font-size: 1.5rem;
            }

            .stat-card-value {
                font-size: 1.5rem;
            }
        }

        /* YOUR ORIGINAL DELIVERY PAGE STYLES - KEPT INTACT */
        .info-label {
            font-weight: 600;
            color: var(--text-secondary);
            margin-bottom: 5px;
            font-size: 0.875rem;
        }
        
        .info-value {
            color: var(--text-primary);
            margin-bottom: 15px;
        }
        
        .product-image {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
            border: 2px solid var(--border-color);
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .product-image:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        
        .product-item {
            background: var(--light-bg);
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 10px;
            border: 1px solid var(--border-color);
        }
        
        .product-item:last-child {
            margin-bottom: 0;
        }
        
        .section-divider {
            border-top: 2px solid var(--border-color);
            margin: 20px 0;
        }
        
        .total-section {
            background: linear-gradient(135deg, var(--primary-blue), var(--primary-purple));
            color: white;
            padding: 15px;
            border-radius: 8px;
            margin-top: 15px;
        }
        
        .payment-method-badge {
            display: inline-block;
            padding: 8px 15px;
            border-radius: 6px;
            font-weight: 600;
            margin-bottom: 10px;
        }
        
        .payment-method-cod {
            background: var(--warning);
            color: #000;
        }
        
        .payment-method-gcash {
            background: var(--info);
            color: #fff;
        }
        
        .gcash-upload-section {
            background: rgba(99, 179, 237, 0.1);
            border: 2px dashed var(--info);
            padding: 20px;
            border-radius: 8px;
            margin-top: 15px;
        }
        
        .payment-proof-preview {
            max-width: 100%;
            max-height: 300px;
            border-radius: 8px;
            border: 2px solid var(--info);
            cursor: pointer;
        }
        
        /* Image Modal Styles - YOUR ORIGINAL */
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
        
        @keyframes zoomIn {
            from { transform: scale(0.5); }
            to { transform: scale(1); }
        }
        
        @media (max-width: 768px) {
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

    <!-- YOUR EXACT HTML STRUCTURE - NO CHANGES -->
     <!-- Modern Navbar -->
   @include('admin.delivery.layouts.navbar')

    <!-- Modern Sidebar -->
  

   @include('admin.delivery.layouts.sidebar')

    <!-- Main Content -->
    <main class="main-content">
        <!-- Page Header -->
        <div class="page-header d-flex justify-content-between align-items-start mb-4">
            <div>
                <h1 class="page-title">
                    <i class="fas fa-box me-2" style="color: var(--primary-blue);"></i>
                    My Deliveries
                </h1>
                <p class="page-subtitle">Manage and update your delivery assignments</p>
            </div>
            <div class="date-badge">
                <i class="fas fa-calendar"></i>
                <span id="currentDate"></span>
            </div>
        </div>

        @if(session('success'))
            <div class="alert-modern alert-success animate-fade-in">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- YOUR ORIGINAL DELIVERIES LIST - NO CHANGES -->
        <div class="row">
            @forelse ($deliveries as $delivery)
                <div class="col-lg-6 mb-4">
                    <div class="order-card">
                        <!-- Header with Order ID and Status -->
                        <div class="order-header">
                            <span class="order-id">#{{ $delivery->id }}</span>
                            @php
                                $status = $delivery->delivery_status;
                            @endphp
                            @if($status === 'Delivered')
                                <span class="badge-modern badge-success">Delivered</span>
                            @elseif($status === 'Out for Delivery')
                                <span class="badge-modern badge-info">Out for Delivery</span>
                            @elseif($status === 'Cancelled')
                                <span class="badge-modern badge-danger">Cancelled</span>
                            @else
                                <span class="badge-modern badge-warning">Pending</span>
                            @endif
                        </div>

                        <!-- Payment Method -->
                        <div class="mb-3">
                            <div class="info-label">
                                <i class="fas fa-credit-card text-info me-2"></i>Payment Method
                            </div>
                            @if($delivery->payment_method === 'COD')
                                <span class="payment-method-badge payment-method-cod">
                                    <i class="fas fa-money-bill-wave me-2"></i>Cash on Delivery (COD)
                                </span>
                            @elseif($delivery->payment_method === 'GCash')
                                <span class="payment-method-badge payment-method-gcash">
                                    <i class="fas fa-mobile-alt me-2"></i>GCash
                                </span>
                            @else
                                <span class="badge bg-secondary">{{ $delivery->payment_method ?? 'Not Specified' }}</span>
                            @endif
                        </div>

                        <!-- Customer Information -->
                        <div class="mb-3">
                            <h6 class="customer-name">{{ $delivery->customer_name }}</h6>

                            <div class="order-address">
                                <i class="fas fa-phone text-success me-2"></i>
                                <span>
                                    <a href="tel:{{ $delivery->phone }}" class="text-decoration-none">
                                        {{ $delivery->phone }}
                                    </a>
                                </span>
                            </div>

                            <div class="order-address">
                                <i class="fas fa-map-marker-alt text-danger me-2"></i>
                                <span>{{ $delivery->address }}</span>
                            </div>
                        </div>

                        <div class="section-divider"></div>

                        <!-- Products Section -->
                        <div class="mb-3">
                            <div class="info-label mb-3">
                                <i class="fas fa-shopping-bag text-info me-2"></i>Order Items
                            </div>

                            @php
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

                        <!-- GCash Payment Proof Section -->
                        @if($delivery->payment_method === 'GCash')
                            <div class="section-divider"></div>
                            
                            @if($delivery->payment_proof)
                                <!-- Show existing proof -->
                                <div class="mb-3">
                                    <div class="info-label">
                                        <i class="fas fa-check-circle text-success me-2"></i>GCash Payment Proof Uploaded
                                    </div>
                                    <div class="text-center mt-2">
                                        <img src="{{ asset('uploads/payments/' . $delivery->payment_proof) }}" 
                                             alt="Payment Proof" 
                                             class="payment-proof-preview"
                                             onclick="openImageModal(this)"
                                             title="Click to view full size">
                                    </div>
                                    <div class="alert-modern alert-success mt-3 mb-0">
                                        <i class="fas fa-info-circle me-2"></i>
                                        Payment proof has been submitted and verified.
                                    </div>
                                </div>
                            @elseif($delivery->payment_status !== 'Paid')
                                <!-- Upload form for GCash proof -->
                                <div class="gcash-upload-section">
                                    <h6 class="text-primary mb-3">
                                        <i class="fas fa-upload me-2"></i>Upload GCash Payment Proof
                                    </h6>
                                    <form action="{{ route('delivery.upload-payment-proof', $delivery->id) }}" 
                                          method="POST" 
                                          enctype="multipart/form-data"
                                          id="uploadForm{{ $delivery->id }}">
                                        @csrf
                                        <div class="mb-3">
                                            <input type="file" 
                                                   class="form-control" 
                                                   name="payment_proof" 
                                                   accept="image/*" 
                                                   required
                                                   onchange="previewImage(this, {{ $delivery->id }})">
                                            <small class="form-text text-light opacity-25"></small>

                                                <i class="fas fa-info-circle me-1"></i>
                                                Please upload a clear screenshot of your GCash payment receipt
                                            </small>
                                        </div>
                                        <div id="imagePreview{{ $delivery->id }}" class="mb-3 text-center" style="display: none;">
                                            <img id="preview{{ $delivery->id }}" class="img-fluid" style="max-height: 200px; border-radius: 8px;">
                                        </div>
                                        <button type="submit" class="btn-modern btn-modern-primary w-100">
                                            <i class="fas fa-cloud-upload-alt me-2"></i>Submit Payment Proof
                                        </button>
                                    </form>
                                </div>
                            @endif
                        @endif

                        <div class="section-divider"></div>

                        <!-- Action Buttons -->
                        <div class="d-grid gap-2">
                            @if($status === 'Pending')
                                <button class="btn-modern btn-modern-info"
                                    onclick="confirmStatus('{{ $delivery->id }}', '{{ $delivery->customer_name }}', 'Out for Delivery')">
                                    <i class="fas fa-shipping-fast me-2"></i>Out for Delivery
                                </button>

                                <button class="btn-modern btn-modern-danger"
                                    onclick="confirmStatus('{{ $delivery->id }}', '{{ $delivery->customer_name }}', 'Cancelled')">
                                    <i class="fas fa-times-circle me-2"></i>Cancel Order
                                </button>
                            @endif

                            @if($status === 'Out for Delivery')
                                <button class="btn-modern btn-modern-success"
                                    onclick="confirmStatus('{{ $delivery->id }}', '{{ $delivery->customer_name }}', 'Delivered')">
                                    <i class="fas fa-check-circle me-2"></i>Mark as Delivered
                                </button>
                            @endif

                            @if($status === 'Delivered')
                                <button class="btn-modern btn-modern-success" disabled>
                                    <i class="fas fa-check-circle me-2"></i>Delivered
                                </button>
                            @endif

                            @if($status === 'Cancelled')
                                <button class="btn-modern btn-modern-secondary" disabled>
                                    <i class="fas fa-ban me-2"></i>Cancelled
                                </button>
                            @endif
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

    <!-- Image Zoom Modal - YOUR ORIGINAL -->
    <div class="image-modal" id="imageModal" onclick="closeImageModal()">
        <span class="image-modal-close" onclick="closeImageModal()">&times;</span>
        <img class="image-modal-content" id="modalImage" alt="Product Image">
        <div class="image-modal-caption" id="modalCaption"></div>
    </div>

    <!-- Confirmation Modal - YOUR ORIGINAL -->
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
                        <button type="button" class="btn-modern btn-modern-secondary" data-bs-dismiss="modal">No</button>
                        <button type="submit" class="btn-modern btn-modern-primary">Yes, Update</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <!-- Logout Modal - FROM YOUR DASHBOARD -->
    <div class="modal fade" id="logoutModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-sign-out-alt me-2 text-danger"></i>
                        Confirm Logout
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <i class="fas fa-question-circle fa-4x mb-4" style="color: var(--warning);"></i>
                    <h5 class="mb-3">Are you sure you want to logout?</h5>
                    <p class="text-muted">You will be redirected to the login page.</p>
                </div>
                <div class="modal-footer justify-content-center border-0">
                    <button type="button" class="btn-modern btn-modern-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Cancel
                    </button>
                    <button type="button" class="btn-modern btn-modern-danger">
                        <i class="fas fa-sign-out-alt me-1"></i> Yes, Logout
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Theme Toggle Function - FROM YOUR DASHBOARD
        function toggleTheme() {
            const html = document.documentElement;
            const currentTheme = html.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            
            html.setAttribute('data-theme', newTheme);
            localStorage.setItem('delivery-theme', newTheme);
        }

        // Sidebar Toggle for Mobile - FROM YOUR DASHBOARD
        document.getElementById('sidebarToggle')?.addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('show');
        });

        // Update Current Date - FROM YOUR DASHBOARD
        function updateCurrentDate() {
            const options = { month: 'short', day: 'numeric', year: 'numeric' };
            const dateString = new Date().toLocaleDateString('en-US', options);
            document.getElementById('currentDate').textContent = dateString;
        }
        updateCurrentDate();

        // YOUR ORIGINAL FUNCTIONS - NO CHANGES
        // Image preview function
        function previewImage(input, orderId) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('imagePreview' + orderId).style.display = 'block';
                    document.getElementById('preview' + orderId).src = e.target.result;
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Image Modal Functions
        function openImageModal(imgElement) {
            const modal = document.getElementById('imageModal');
            const modalImg = document.getElementById('modalImage');
            const caption = document.getElementById('modalCaption');
            
            modal.classList.add('show');
            modalImg.src = imgElement.src;
            caption.textContent = imgElement.alt;
            
            document.body.style.overflow = 'hidden';
        }
        
        function closeImageModal() {
            const modal = document.getElementById('imageModal');
            modal.classList.remove('show');
            document.body.style.overflow = 'auto';
        }
        
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeImageModal();
            }
        });
        
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