<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hookcraft Avenue - Order Receipt</title>
    <link rel="icon" href="{{ asset('asset/images/logo.jpg') }}" type="image/png">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('asset/stylesnav.css') }}">
    <style>
        .receipt-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .receipt-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .receipt-logo {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin-bottom: 15px;
            border: 3px solid white;
            object-fit: cover;
        }
        
        .receipt-title {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .receipt-subtitle {
            opacity: 0.9;
            font-size: 0.9rem;
        }
        
        .receipt-content {
            padding: 30px;
        }
        
        .receipt-section {
            margin-bottom: 25px;
            padding-bottom: 25px;
            border-bottom: 1px dashed #ddd;
        }
        
        .receipt-section:last-child {
            border-bottom: none;
        }
        
        .section-title {
            color: #667eea;
            font-weight: bold;
            margin-bottom: 15px;
            font-size: 1.1rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
        }
        
        .info-item {
            margin-bottom: 10px;
        }
        
        .info-label {
            font-weight: bold;
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 3px;
        }
        
        .info-value {
            color: #333;
            font-size: 1rem;
        }
        
        .receipt-items table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .receipt-items th {
            background: #f8f9fa;
            padding: 12px;
            text-align: left;
            font-weight: bold;
            color: #667eea;
            border-bottom: 2px solid #dee2e6;
        }
        
        .receipt-items td {
            padding: 12px;
            border-bottom: 1px solid #dee2e6;
        }
        
        .receipt-items tr:last-child td {
            border-bottom: none;
        }
        
        /* Enhanced Product Image Styles */
        .item-image {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid #e0e0e0;
        }
        
        .customization-image {
            border-color: #667eea;
            border-style: dashed;
        }
        
        .item-image:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
            border-color: #667eea;
        }
        
        /* Category Badge */
        .category-badge {
            display: inline-block;
            background: linear-gradient(135deg, #e3f2fd, #bbdefb);
            color: #1565c0;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 0.7rem;
            font-weight: 500;
            margin-left: 5px;
            border: 1px solid #90caf9;
        }
        
        /* Customization Badge */
        .customization-badge {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            font-size: 0.75rem;
            padding: 2px 8px;
            border-radius: 4px;
            margin-left: 5px;
        }
        
        /* Customization Details */
        .customization-details {
            background: #f8f9ff;
            border-left: 3px solid #667eea;
            padding: 12px;
            margin-top: 8px;
            border-radius: 5px;
            font-size: 0.9rem;
        }
        
        .customization-details h6 {
            color: #667eea;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .customization-details p {
            margin-bottom: 5px;
            color: #555;
        }
        
        .customization-option {
            background: white;
            border: 1px solid #e0e0e0;
            border-radius: 4px;
            padding: 8px;
            margin-bottom: 5px;
        }
        
        /* Product Details */
        .product-details {
            margin-top: 5px;
        }
        
        .product-category {
            color: #666;
            font-size: 0.8rem;
            margin-bottom: 2px;
        }
        
        .product-category i {
            color: #667eea;
            margin-right: 3px;
        }
        
        /* Image Modal Styles */
        .image-modal {
            display: none;
            position: fixed;
            z-index: 99999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.9);
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .image-modal.active {
            display: flex;
            opacity: 1;
        }
        
        .modal-content-wrapper {
            margin: auto;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            max-width: 90%;
            max-height: 90%;
            animation: zoomIn 0.3s ease;
        }
        
        @keyframes zoomIn {
            from {
                transform: scale(0.7);
            }
            to {
                transform: scale(1);
            }
        }
        
        .modal-image {
            max-width: 100%;
            max-height: 80vh;
            object-fit: contain;
            border-radius: 10px;
            box-shadow: 0 0 30px rgba(255,255,255,0.3);
        }
        
        .modal-close {
            position: absolute;
            top: 20px;
            right: 35px;
            color: #f1f1f1;
            font-size: 40px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
            z-index: 100001;
        }
        
        .modal-close:hover,
        .modal-close:focus {
            color: #bbb;
        }
        
        .modal-caption {
            color: #f1f1f1;
            text-align: center;
            padding: 15px;
            margin-top: 15px;
            background: rgba(0,0,0,0.7);
            border-radius: 5px;
            max-width: 600px;
        }
        
        .modal-caption h4 {
            margin: 0 0 5px 0;
            font-size: 1.2rem;
        }
        
        .modal-caption p {
            margin: 0;
            font-size: 0.9rem;
            opacity: 0.8;
        }
        
        .total-row {
            background: #f8f9fa;
            font-weight: bold;
        }
        
        .receipt-footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            border-top: 1px dashed #ddd;
        }
        
        .receipt-footer p {
            margin: 5px 0;
            color: #666;
            font-size: 0.9rem;
        }
        
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .status-pending {
            background: #fff3cd;
            color: #856404;
        }
        
        .status-paid {
            background: #d4edda;
            color: #155724;
        }
        
        .status-delivered {
            background: #d1ecf1;
            color: #0c5460;
        }
        
        .status-cancelled {
            background: #f8d7da;
            color: #721c24;
        }
        
        .export-button {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1000;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        
        /* Loading overlay */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.7);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        .loading-overlay.active {
            display: flex;
        }

        .loading-content {
            background: white;
            padding: 30px;
            border-radius: 10px;
            text-align: center;
        }

        .spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #667eea;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 0 auto 15px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        @media (max-width: 768px) {
            .receipt-container {
                margin: 10px;
            }
            
            .receipt-content {
                padding: 20px;
            }
            
            .info-grid {
                grid-template-columns: 1fr;
            }
            
            .item-image {
                width: 60px;
                height: 60px;
            }
            
            .modal-close {
                top: 10px;
                right: 15px;
                font-size: 30px;
            }
            
            .export-button {
                position: static;
                margin: 20px auto;
                display: block;
                width: auto;
            }
        }
    </style>
</head>
<body>

@include('components.login_modal')
@include('components.signup_modal')
@include('components.navbar')

<!-- Image Modal -->
<div id="imageModal" class="image-modal">
    <span class="modal-close">&times;</span>
    <div class="modal-content-wrapper">
        <img class="modal-image" id="modalImage" src="" alt="">
        <div class="modal-caption">
            <h4 id="modalTitle"></h4>
            <p id="modalDescription"></p>
        </div>
    </div>
</div>

<!-- Loading Overlay -->
<div class="loading-overlay" id="loadingOverlay">
    <div class="loading-content">
        <div class="spinner"></div>
        <h5>Generating Receipt Image...</h5>
        <p class="text-muted mb-0">Please wait a moment</p>
    </div>
</div>

<section class="thankyou py-5">
    <div class="container">
        <!-- Success Message -->
        <div class="row mb-4">
            <div class="col-12 text-center">
                <div class="alert alert-success d-flex align-items-center justify-content-center" role="alert">
                    <i class="bi bi-check-circle-fill me-2" style="font-size: 1.5rem;"></i>
                    <div>
                        <h4 class="alert-heading mb-0">Order Placed Successfully!</h4>
                        <p class="mb-0">Thank you for your purchase. Your order has been confirmed.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Receipt Container -->
        <div class="receipt-container" id="receiptContainer">
            <div class="receipt-header">
                <img src="{{ asset('asset/images/logo.jpg') }}" alt="Hookcraft Avenue Logo" class="receipt-logo">
                <h1 class="receipt-title">HOOKCRAFT AVENUE</h1>
                <p class="receipt-subtitle">Order Receipt</p>
                <div class="mt-3">
                    <span class="badge bg-light text-dark">Order #{{ $order_id }}</span>
                    <span class="badge bg-light text-dark">{{ $order_date }}</span>
                </div>
            </div>

            <div class="receipt-content">
                <!-- Order Information -->
                <div class="receipt-section">
                    <h3 class="section-title">
                        <i class="bi bi-receipt"></i> Order Information
                    </h3>
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label">Order Number</div>
                            <div class="info-value">#{{ $order_id }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Order Date</div>
                            <div class="info-value">{{ $order_date }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Payment Status</div>
                            <div class="info-value">
                                <span class="status-badge status-{{ strtolower($order->payment_status) }}">
                                    {{ $order->payment_status }}
                                </span>
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Delivery Status</div>
                            <div class="info-value">
                                <span class="status-badge status-{{ strtolower(str_replace(' ', '-', $order->delivery_status)) }}">
                                    {{ $order->delivery_status }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Personal Information -->
                <div class="receipt-section">
                    <h3 class="section-title">
                        <i class="bi bi-person-circle"></i> Personal Information
                    </h3>
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label">Customer Name</div>
                            <div class="info-value">{{ $order->customer_name }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Contact Number</div>
                            <div class="info-value">{{ $order->phone }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Email Address</div>
                            <div class="info-value">{{ Auth::user()->email }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Payment Method</div>
                            <div class="info-value">{{ $order->payment_method }}</div>
                        </div>
                    </div>
                </div>

                <!-- Delivery Address -->
                <div class="receipt-section">
                    <h3 class="section-title">
                        <i class="bi bi-geo-alt"></i> Delivery Address
                    </h3>
                    <div class="info-item">
                        <div class="info-label">Full Address</div>
                        <div class="info-value">{{ $order->address }}</div>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="receipt-section receipt-items">
                    <h3 class="section-title">
                        <i class="bi bi-cart-check"></i> Order Items
                    </h3>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Qty</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order_items as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($item->is_customization && $item->customization && $item->customization->custom_image)
                                                <!-- Customized product image -->
                                                <img src="{{ asset('uploads/customizations/' . $item->customization->custom_image) }}" 
                                                     alt="{{ $item->customization->customization_name }}" 
                                                     class="item-image customization-image me-3"
                                                     data-title="{{ $item->customization->customization_name }}"
                                                     data-description="{{ $item->customization->customization_details }}"
                                                     title="Click to view customization"
                                                     crossorigin="anonymous">
                                            @elseif($item->product && $item->product->image)
                                                <!-- Regular product image -->
                                                <img src="{{ asset('asset/images/' . $item->product->image) }}" 
                                                     alt="{{ $item->product->name }}" 
                                                     class="item-image me-3"
                                                     data-title="{{ $item->product->name }}"
                                                     data-description="{{ $item->product->description ? Str::limit($item->product->description, 100) : 'No description available' }}"
                                                     title="Click to view"
                                                     crossorigin="anonymous">
                                            @else
                                            <div class="item-image me-3 d-flex align-items-center justify-content-center bg-light" 
                                                 style="width: 80px; height: 80px; border-radius: 8px; cursor: not-allowed;"
                                                 title="No image available">
                                                <i class="bi bi-image text-muted" style="font-size: 24px;"></i>
                                            </div>
                                            @endif
                                            <div>
                                                <div class="d-flex align-items-center">
                                                    @if($item->is_customization)
                                                        <strong>{{ $item->customization->customization_name ?? 'Customized Product' }}</strong>
                                                        <span class="customization-badge">CUSTOMIZED</span>
                                                        @if($item->product->category)
                                                            <span class="category-badge">
                                                                <i class="bi bi-tag-fill"></i> {{ $item->product->category->name }}
                                                            </span>
                                                        @endif
                                                    @else
                                                        <strong>{{ $item->product->name ?? 'Product' }}</strong>
                                                        @if($item->product->category)
                                                            <span class="category-badge">
                                                                <i class="bi bi-tag-fill"></i> {{ $item->product->category->name }}
                                                            </span>
                                                        @endif
                                                    @endif
                                                </div>
                                                
                                                <div class="product-details">
                                                    @if($item->product->category)
                                                        <div class="product-category">
                                                            <i class="bi bi-tags"></i> {{ $item->product->category->name }}
                                                        </div>
                                                    @endif
                                                    
                                                    @if($item->is_customization && $item->customization)
                                                        <small class="text-muted">Based on: {{ $item->product->name ?? 'Product' }}</small>
                                                    @elseif($item->product && $item->product->description)
                                                        <small class="text-muted">{{ Str::limit($item->product->description, 50) }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Customization Details -->
                                        @if($item->is_customization && $item->customization)
                                        <div class="customization-details mt-2">
                                            <h6>Customization Details:</h6>
                                            <p><strong>Name:</strong> {{ $item->customization->customization_name }}</p>
                                            <p><strong>Details:</strong> {{ $item->customization->customization_details }}</p>
                                            
                                            @if($item->customization->special_instructions)
                                            <p><strong>Special Instructions:</strong> {{ $item->customization->special_instructions }}</p>
                                            @endif
                                            
                                            @if($item->customization->options && $item->customization->options->count() > 0)
                                            <div class="mt-2">
                                                <strong>Options:</strong>
                                                @foreach($item->customization->options as $option)
                                                <div class="customization-option">
                                                    <small>
                                                        <strong>{{ ucfirst($option->option_type) }}:</strong> 
                                                        {{ $option->option_value }}
                                                        @if($option->additional_price > 0)
                                                        <span class="text-success">(+₱{{ number_format($option->additional_price, 2) }})</span>
                                                        @endif
                                                    </small>
                                                </div>
                                                @endforeach
                                            </div>
                                            @endif
                                            
                                            @if($item->customization->admin_price)
                                            <p class="text-success mt-2 mb-0">
                                                <strong>Customization Price:</strong> ₱{{ number_format($item->customization->admin_price, 2) }}
                                            </p>
                                            @endif
                                        </div>
                                        @endif
                                    </td>
                                    <td>
                                        @if($item->is_customization && $item->customization && $item->customization->admin_price)
                                            ₱{{ number_format($item->customization->admin_price, 2) }}
                                        @else
                                            ₱{{ number_format($item->price, 2) }}
                                        @endif
                                    </td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>
                                        @if($item->is_customization && $item->customization && $item->customization->admin_price)
                                            ₱{{ number_format($item->customization->admin_price * $item->quantity, 2) }}
                                        @else
                                            ₱{{ number_format($item->price * $item->quantity, 2) }}
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="receipt-section">
                    <h3 class="section-title">
                        <i class="bi bi-calculator"></i> Order Summary
                    </h3>
                    <div class="row">
                        <div class="col-md-6 offset-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="text-end"><strong>Subtotal:</strong></td>
                                    <td class="text-end">₱{{ number_format($subtotal, 2) }}</td>
                                </tr>
                                <tr>
                                    <td class="text-end"><strong>Shipping Fee:</strong></td>
                                    <td class="text-end">₱{{ number_format($shipping_fee, 2) }}</td>
                                </tr>
                                <tr class="total-row">
                                    <td class="text-end"><strong>Total Amount:</strong></td>
                                    <td class="text-end"><strong>₱{{ number_format($order->total, 2) }}</strong></td>
                                </tr>
                                @if($order->payment_method === 'COD')
                                <tr>
                                    <td colspan="2" class="text-center text-muted">
                                        <small><i class="bi bi-info-circle me-1"></i>Cash on Delivery</small>
                                    </td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="receipt-footer">
                <p><strong>Thank you for shopping with Hookcraft Avenue!</strong></p>
                <p>For any questions about your order, please contact our customer support.</p>
                <p class="mb-0">
                    <i class="bi bi-telephone me-1"></i> (032) 123-4567 | 
                    <i class="bi bi-envelope ms-3 me-1"></i> support@hookcraftavenue.com
                </p>
                <p class="text-muted mt-2">
                    <small>This is an official receipt. Please keep it for your records.</small>
                </p>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="row mt-4">
            <div class="col-12 text-center">
                <div class="d-flex flex-column flex-md-row justify-content-center gap-3">
                    <button onclick="exportReceipt()" class="btn btn-primary btn-lg">
                        <i class="bi bi-download me-2"></i> Export Receipt
                    </button>
                    <a href="{{ route('profile.purchase-history') }}" class="btn btn-outline-primary btn-lg">
                        <i class="bi bi-clock-history me-2"></i> View Order History
                    </a>
                    <a href="{{ route('shop') }}" class="btn btn-success btn-lg">
                        <i class="bi bi-shop me-2"></i> Continue Shopping
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Floating Export Button -->
<button onclick="exportReceipt()" class="btn btn-primary export-button rounded-circle" 
        style="width: 60px; height: 60px;" title="Export Receipt">
    <i class="bi bi-download" style="font-size: 1.2rem;"></i>
</button>

@include('components.footer')

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script>
    // Image Modal Functions
    function openModal(imgSrc, title, description) {
        console.log('Opening modal with:', { imgSrc, title, description });
        
        const modal = document.getElementById('imageModal');
        const modalImg = document.getElementById('modalImage');
        const modalTitle = document.getElementById('modalTitle');
        const modalDescription = document.getElementById('modalDescription');
        
        // Set the image source and details
        modalImg.src = imgSrc;
        modalTitle.textContent = title;
        modalDescription.textContent = description;
        
        // Show the modal
        modal.classList.add('active');
        
        // Prevent body scroll when modal is open
        document.body.style.overflow = 'hidden';
    }
    
    function closeModal() {
        console.log('Closing modal');
        const modal = document.getElementById('imageModal');
        modal.classList.remove('active');
        document.body.style.overflow = 'auto';
    }
    
    // Initialize modal events when DOM is loaded
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Setting up modal events');
        
        const modal = document.getElementById('imageModal');
        const closeBtn = document.querySelector('.modal-close');
        
        // Event delegation for all product images
        document.addEventListener('click', function(event) {
            // Check if clicked element is an item-image
            if (event.target.classList.contains('item-image')) {
                const title = event.target.dataset.title || event.target.alt || 'Product Image';
                const description = event.target.dataset.description || 'No description available';
                const imgSrc = event.target.src;
                
                openModal(imgSrc, title, description);
            }
        });
        
        // Close modal when clicking the close button
        if (closeBtn) {
            closeBtn.addEventListener('click', closeModal);
        }
        
        // Close modal when clicking outside the image
        modal.addEventListener('click', function(event) {
            if (event.target === modal) {
                closeModal();
            }
        });
        
        // Close modal with Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape' && modal.classList.contains('active')) {
                closeModal();
            }
        });
        
        console.log('Modal events setup complete');
    });
    
    async function exportReceipt() {
        const overlay = document.getElementById('loadingOverlay');
        const receiptContainer = document.getElementById('receiptContainer');
        
        try {
            overlay.classList.add('active');
            
            // Wait a bit for any animations to complete
            await new Promise(resolve => setTimeout(resolve, 300));
            
            const canvas = await html2canvas(receiptContainer, {
                scale: 2,
                useCORS: true,
                allowTaint: true,
                backgroundColor: '#ffffff',
                logging: false,
                width: receiptContainer.scrollWidth,
                height: receiptContainer.scrollHeight,
                onclone: (clonedDoc) => {
                    const clonedContainer = clonedDoc.getElementById('receiptContainer');
                    if (clonedContainer) {
                        clonedContainer.style.boxShadow = 'none';
                        clonedContainer.style.margin = '0';
                    }
                }
            });
            
            canvas.toBlob((blob) => {
                const url = URL.createObjectURL(blob);
                const link = document.createElement('a');
                link.href = url;
                link.download = `Hookcraft_Receipt_Order_{{ $order_id }}_${Date.now()}.png`;
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                URL.revokeObjectURL(url);
                
                overlay.classList.remove('active');
                showToast('Receipt exported successfully!', 'success');
            }, 'image/png', 1.0);
            
        } catch (error) {
            console.error('Export error:', error);
            overlay.classList.remove('active');
            showToast('Failed to export receipt. Please try again.', 'error');
        }
    }
    
    function showToast(message, type = 'info') {
        const toastContainer = document.createElement('div');
        toastContainer.style.position = 'fixed';
        toastContainer.style.top = '20px';
        toastContainer.style.right = '20px';
        toastContainer.style.zIndex = '10000';
        
        const toast = document.createElement('div');
        toast.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show`;
        toast.setAttribute('role', 'alert');
        toast.innerHTML = `
            <i class="bi ${type === 'success' ? 'bi-check-circle' : 'bi-exclamation-circle'} me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        toastContainer.appendChild(toast);
        document.body.appendChild(toastContainer);
        
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toastContainer.remove(), 300);
        }, 3000);
    }
</script>
</body>
</html>