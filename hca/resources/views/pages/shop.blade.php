{{-- resources/views/pages/shop.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop - Hookcraft Avenue</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('asset/images/logo.jpg') }}" type="image/png">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('asset/stylesnav.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/stylesshop.css') }}">
<style>
    .chatbot-float {
        position: fixed;
        bottom: 30px;
        right: 30px;
        z-index: 1000;
    }
    
    .chatbot-float a {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #FF69B4 0%, #FF1493 100%);
        border-radius: 50%;
        box-shadow: 0 4px 20px rgba(255, 105, 180, 0.4);
        color: white;
        text-decoration: none;
        transition: all 0.3s ease;
    }
    
    .chatbot-float a:hover {
        transform: scale(1.1);
        box-shadow: 0 6px 25px rgba(255, 105, 180, 0.6);
    }
    
    .chatbot-float i {
        font-size: 28px;
    }

    .chatbot-pulse {
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% {
            box-shadow: 0 4px 20px rgba(255, 105, 180, 0.4);
        }
        50% {
            box-shadow: 0 4px 30px rgba(255, 105, 180, 0.7);
        }
        100% {
            box-shadow: 0 4px 20px rgba(255, 105, 180, 0.4);
        }
    }

    /* Sidebar Layout Styles */
    .shop-layout {
        display: flex;
        gap: 25px;
        margin-top: 20px;
    }

    .sidebar-filters {
        width: 280px;
        flex-shrink: 0;
        position: sticky;
        top: 20px;
        height: fit-content;
        background: white;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.08);
    }

    .sidebar-section {
        margin-bottom: 20px;
    }

    .sidebar-section:last-child {
        margin-bottom: 0;
    }

    .sidebar-title {
        font-size: 14px;
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .sidebar-title i {
        color: #FF69B4;
        font-size: 16px;
    }

    .sidebar-search {
        position: relative;
    }

    .sidebar-search input {
        width: 100%;
        padding: 8px 35px 8px 12px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 13px;
        transition: all 0.3s ease;
    }

    .sidebar-search input:focus {
        outline: none;
        border-color: #FF69B4;
        box-shadow: 0 0 0 3px rgba(255, 105, 180, 0.1);
    }

    .sidebar-search i {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #a0aec0;
        font-size: 13px;
    }

    .price-range-container {
        padding: 0;
    }

    .price-values {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
        font-size: 12px;
        color: #718096;
    }

    .price-slider {
        width: 100%;
        height: 6px;
        border-radius: 3px;
        background: linear-gradient(to right, #FF69B4 0%, #FF69B4 100%);
        outline: none;
        -webkit-appearance: none;
    }

    .price-slider::-webkit-slider-thumb {
        -webkit-appearance: none;
        appearance: none;
        width: 18px;
        height: 18px;
        border-radius: 50%;
        background: #FF69B4;
        cursor: pointer;
        box-shadow: 0 2px 6px rgba(255, 105, 180, 0.4);
        transition: all 0.3s ease;
    }

    .price-slider::-webkit-slider-thumb:hover {
        transform: scale(1.2);
        box-shadow: 0 3px 10px rgba(255, 105, 180, 0.6);
    }

    .price-slider::-moz-range-thumb {
        width: 18px;
        height: 18px;
        border-radius: 50%;
        background: #FF69B4;
        cursor: pointer;
        border: none;
        box-shadow: 0 2px 6px rgba(255, 105, 180, 0.4);
    }

    .max-price-input {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-top: 10px;
    }

    .max-price-input span {
        font-size: 13px;
        color: #4a5568;
        font-weight: 500;
    }

    .max-price-input input {
        flex: 1;
        padding: 6px 10px;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 500;
        color: #2d3748;
    }

    .category-list {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .category-item {
        padding: 8px 12px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        color: #4a5568;
        background: white;
    }

    .category-item:hover {
        background: #f7fafc;
        border-color: #FF69B4;
        color: #FF69B4;
    }

    .category-item.active {
        background: linear-gradient(135deg, #FF69B4 0%, #FF1493 100%);
        border-color: #FF1493;
        color: white;
        font-weight: 500;
    }

    .category-item i {
        font-size: 14px;
    }

    .reset-filters-btn {
        width: 100%;
        padding: 8px;
        background: #f7fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        color: #4a5568;
        font-size: 13px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .reset-filters-btn:hover {
        background: #edf2f7;
        border-color: #cbd5e0;
    }

    .main-content {
        flex: 1;
        min-width: 0;
    }

    .results-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        padding: 15px 20px;
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }

    .results-count {
        font-size: 15px;
        color: #4a5568;
    }

    .results-count span {
        font-weight: 600;
        color: #2d3748;
    }

    .sort-dropdown {
        padding: 8px 15px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 14px;
        color: #4a5568;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .sort-dropdown:focus {
        outline: none;
        border-color: #FF69B4;
    }

    /* Category Section Styles */
    .category-section {
        margin-bottom: 40px;
        animation: fadeIn 0.5s ease-out;
    }

    .category-section.hidden {
        display: none;
    }

    .category-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 2px solid #FF69B4;
    }

    .category-title {
        font-size: 24px;
        font-weight: 600;
        color: #2d3748;
        margin: 0;
        display: flex;
        align-items: center;
    }

    .category-count {
        font-size: 14px;
        color: #718096;
        background: #f7fafc;
        padding: 4px 12px;
        border-radius: 20px;
        font-weight: 500;
    }

    .products-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 25px;
        margin-top: 20px;
    }

    .product-item {
        animation: fadeInUp 0.5s ease-out;
        opacity: 1;
        transition: opacity 0.3s ease, transform 0.3s ease;
    }

    .product-item.hidden {
        display: none;
    }

    .product-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        cursor: pointer;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.12);
    }

    .category-badge-overlay {
        position: absolute;
        top: 15px;
        left: 15px;
        background: linear-gradient(135deg, #FF69B4 0%, #FF1493 100%);
        color: white;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
        z-index: 2;
    }

    .stock-badge {
        position: absolute;
        top: 15px;
        right: 15px;
        z-index: 2;
    }

    .product-image-wrapper {
        position: relative;
        padding-top: 75%;
        overflow: hidden;
        background: #f8f9fa;
    }

    .product-image {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .product-card:hover .product-image {
        transform: scale(1.05);
    }

    .product-info {
        padding: 20px;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }

    .product-title {
        font-size: 16px;
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 10px;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .product-price {
        font-size: 20px;
        font-weight: 700;
        color: #FF1493;
        margin-bottom: 15px;
    }

    .cart-section {
        margin-top: auto;
    }

    .quantity-selector {
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 15px;
        gap: 10px;
    }

    .quantity-btn {
        width: 36px;
        height: 36px;
        border: 1px solid #e2e8f0;
        background: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        color: #4a5568;
        font-size: 14px;
    }

    .quantity-btn:hover {
        background: #f7fafc;
        border-color: #FF69B4;
        color: #FF69B4;
    }

    .quantity-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .quantity-input {
        width: 60px;
        text-align: center;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 8px;
        font-size: 14px;
        font-weight: 600;
        color: #2d3748;
        background: white;
    }

    .quantity-input:focus {
        outline: none;
        border-color: #FF69B4;
        box-shadow: 0 0 0 3px rgba(255, 105, 180, 0.1);
    }

    .add-to-cart-btn {
        width: 100%;
        padding: 12px;
        background: linear-gradient(135deg, #FF69B4 0%, #FF1493 100%);
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .add-to-cart-btn:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(255, 105, 180, 0.4);
    }

    .add-to-cart-btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        background: #6c757d;
    }

    .add-to-cart-btn.loading {
        background: #6c757d;
    }

    .add-to-cart-btn.success {
        background: #28a745;
    }

    .btn-text, .btn-loading, .btn-success {
        display: flex;
        align-items: center;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }

    .empty-state i {
        color: #e2e8f0;
        margin-bottom: 20px;
    }

    .empty-state h4 {
        color: #2d3748;
        margin-bottom: 10px;
    }

    .empty-state p {
        color: #718096;
        margin-bottom: 25px;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Mobile Responsive */
    @media (max-width: 992px) {
        .shop-layout {
            flex-direction: column;
        }

        .sidebar-filters {
            width: 100%;
            position: relative;
            top: 0;
        }

        .sidebar-section {
            margin-bottom: 20px;
        }

        .products-grid {
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        }
    }

    @media (max-width: 768px) {
        .sidebar-filters {
            padding: 20px;
        }

        .results-header {
            flex-direction: column;
            gap: 15px;
            align-items: stretch;
        }

        .sort-dropdown {
            width: 100%;
        }

        .products-grid {
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 15px;
        }

        .category-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }

        .category-count {
            align-self: flex-start;
        }
    }

    @media (max-width: 480px) {
        .products-grid {
            grid-template-columns: 1fr;
        }
    }

    /* Toast Styles */
    .toast-container {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
    }

    .toast {
        min-width: 300px;
        border: none;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        overflow: hidden;
        background: white;
    }

    .cart-toast {
        border-left: 4px solid #28a745;
    }

    .error-toast {
        border-left: 4px solid #dc3545;
    }

    .toast-body {
        padding: 15px;
        font-size: 14px;
        color: #2d3748;
    }

    .toast-body i {
        font-size: 18px;
    }

    .cart-toast .toast-body i {
        color: #28a745;
    }

    .error-toast .toast-body i {
        color: #dc3545;
    }
</style>  

</head>
<body>

@include('components.login_modal')
@include('components.signup_modal')
@include('components.pmodal')

<!-- Toast Container -->
<div class="toast-container">
    <div id="cartToast" class="toast cart-toast" role="alert">
        <div class="toast-body d-flex align-items-center">
            <i class="fas fa-check-circle me-2"></i>
            <span id="toastMessage">Product added to cart!</span>
        </div>
    </div>
    <div id="errorToast" class="toast error-toast" role="alert">
        <div class="toast-body d-flex align-items-center">
            <i class="fas fa-exclamation-circle me-2"></i>
            <span id="errorMessage">Something went wrong!</span>
        </div>
    </div>
</div>

<!-- Navbar -->
@include('components.navbar')
 
<div class="chatbot-float">
    <a href="{{ route('chatbot') }}" class="chatbot-pulse" title="Chat with AI Assistant">
        <i class="bi bi-robot"></i>
    </a>
</div>

<!-- Main Content with Sidebar -->
<div class="container">
    <div class="shop-layout">
        <!-- Sidebar Filters -->
        <aside class="sidebar-filters">
            <!-- Search Section -->
            <div class="sidebar-section">
                <h3 class="sidebar-title">
                    <i class="fas fa-search"></i>
                    Search Products
                </h3>
                <div class="sidebar-search">
                    <input type="text" 
                           id="productSearch" 
                           placeholder="Search handmade items..."
                           autocomplete="off">
                    <i class="fas fa-search"></i>
                </div>
            </div>

            <!-- Price Range Section -->
            <div class="sidebar-section">
                <h3 class="sidebar-title">
                    <i class="fas fa-peso-sign"></i>
                    Price Range
                </h3>
                <div class="price-range-container">
                    <div class="price-values">
                        <span>₱20</span>
                        <span>₱2,000</span>
                    </div>
                    <input type="range" 
                           class="price-slider" 
                           id="priceRange" 
                           min="20" 
                           max="2000" 
                           value="2000">
                    <div class="max-price-input">
                        <span>Max:</span>
                        <input type="number" 
                               id="maxPrice" 
                               min="20" 
                               max="2000" 
                               value="2000">
                    </div>
                </div>
            </div>

            <!-- Categories Section -->
            <div class="sidebar-section">
                <h3 class="sidebar-title">
                    <i class="fas fa-tags"></i>
                    Categories
                </h3>
                <div class="category-list">
                    <button class="category-item active" onclick="filterProducts('all')" data-category="all">
                        <i class="fas fa-th-large"></i>
                        <span>All Products</span>
                    </button>
                    @foreach($categories as $cat)
                    <button class="category-item" onclick="filterProducts('{{ $cat->id }}')" data-category="{{ $cat->id }}">
                        <i class="fas fa-tag"></i>
                        <span>{{ $cat->name }}</span>
                    </button>
                    @endforeach
                </div>
            </div>

            <!-- Reset Filters -->
            <div class="sidebar-section">
                <button class="reset-filters-btn" onclick="resetFilters()">
                    <i class="fas fa-redo"></i>
                    Reset Filters
                </button>
            </div>
        </aside>

        <!-- Main Content Area -->
        <main class="main-content">
            <!-- Results Header -->
            <div class="results-header">
                <div class="results-count">
                    Showing <span id="productCount">{{ $products->count() }}</span> products
                </div>
                <select class="sort-dropdown" id="sortSelect">
                    <option value="default">Sort by: Featured</option>
                    <option value="price-low">Price: Low to High</option>
                    <option value="price-high">Price: High to Low</option>
                    <option value="name">Name: A to Z</option>
                </select>
            </div>

            <!-- Products by Category -->
            <div id="products-container">
                @php
                    $groupedProducts = $products->groupBy('category_id');
                @endphp

                @foreach($categories as $category)
                    @php
                        $categoryProducts = isset($groupedProducts[$category->id]) ? $groupedProducts[$category->id] : collect([]);
                    @endphp
                    
                    @if($categoryProducts->count() > 0)
                        <div class="category-section" data-category-id="{{ $category->id }}">
                            <div class="category-header">
                                <h3 class="category-title">
                                    <i class="fas fa-tag me-2"></i>{{ $category->name }}
                                </h3>
                                <span class="category-count">{{ $categoryProducts->count() }} items</span>
                            </div>
                            
                            <div class="products-grid">
                                @foreach($categoryProducts as $product)
                                <div class="product-item"
                                     data-category="{{ $product->category_id }}"
                                     data-name="{{ strtolower($product->name) }}"
                                     data-price="{{ $product->price }}"
                                     data-product-id="{{ $product->id }}"
                                     data-product-name="{{ $product->name }}"
                                     data-product-image="{{ asset('asset/images/' . $product->image) }}"
                                     data-product-description="{{ $product->description ?? 'Beautiful handcrafted crochet item.' }}"
                                     data-product-stock="{{ $product->stock }}"
                                     data-product-category-name="{{ $product->category->name ?? 'Uncategorized' }}">
                                    <div class="product-card" onclick="openProductModal(this.parentElement)">
                                        <!-- Category Badge Overlay -->
                                        <div class="category-badge-overlay">
                                            {{ $product->category->name ?? 'Uncategorized' }}
                                        </div>

                                        <!-- Stock Badge -->
                                        <div class="stock-badge">
                                            @if($product->stock > 0)
                                                @if($product->stock <= 5)
                                                    <span class="badge bg-warning text-dark">
                                                        <i class="fas fa-exclamation-triangle me-1"></i>Only {{ $product->stock }} left
                                                    </span>
                                                @else
                                                    <span class="badge bg-success">
                                                        <i class="fas fa-check-circle me-1"></i>In Stock
                                                    </span>
                                                @endif
                                            @else
                                                <span class="badge bg-danger">
                                                    <i class="fas fa-times-circle me-1"></i>Out of Stock
                                                </span>
                                            @endif
                                        </div>

                                        <!-- Product Image -->
                                        <div class="product-image-wrapper">
                                            @if($product->image)
                                                <img src="{{ asset('asset/images/' . $product->image) }}" 
                                                     alt="{{ $product->name }}" 
                                                     class="product-image"
                                                     onerror="this.onerror=null; this.src='{{ asset('asset/images/placeholder.jpg') }}';">
                                            @else
                                                <img src="{{ asset('asset/images/placeholder.jpg') }}" 
                                                     alt="{{ $product->name }}" 
                                                     class="product-image">
                                            @endif
                                        </div>

                                        <!-- Product Info -->
                                        <div class="product-info">
                                            <h6 class="product-title">{{ $product->name }}</h6>
                                            <span class="product-price">₱{{ number_format($product->price, 2) }}</span>
                                            
                                            @if($product->stock > 0)
                                                <div class="cart-section" onclick="event.stopPropagation();">
                                                    <div class="quantity-selector">
                                                        <button type="button" class="quantity-btn" onclick="decreaseQuantity({{ $product->id }})">
                                                            <i class="fas fa-minus"></i>
                                                        </button>
                                                        <input type="number" 
                                                               id="quantity-{{ $product->id }}" 
                                                               class="quantity-input" 
                                                               value="1" 
                                                               min="1" 
                                                               max="{{ $product->stock }}"
                                                               readonly>
                                                        <button type="button" class="quantity-btn" onclick="increaseQuantity({{ $product->id }}, {{ $product->stock }})">
                                                            <i class="fas fa-plus"></i>
                                                        </button>
                                                    </div>
                                                    
                                                    @auth
                                                        <button type="button" 
                                                                class="add-to-cart-btn" 
                                                                id="add-btn-{{ $product->id }}"
                                                                onclick="addToCart({{ $product->id }})">
                                                            <span class="btn-text">
                                                                <i class="fas fa-cart-plus me-2"></i>Add to Cart
                                                            </span>
                                                            <span class="btn-loading d-none">
                                                                <i class="fas fa-spinner fa-spin me-2"></i>Adding...
                                                            </span>
                                                            <span class="btn-success d-none">
                                                                <i class="fas fa-check me-2"></i>Added!
                                                            </span>
                                                        </button>
                                                    @else
                                                        <button type="button" class="add-to-cart-btn" 
                                                                data-bs-toggle="modal" data-bs-target="#loginModal">
                                                            <i class="fas fa-sign-in-alt me-2"></i>Login to Purchase
                                                        </button>
                                                    @endauth
                                                </div>
                                            @else
                                                <button class="add-to-cart-btn" disabled style="background: #6c757d;">
                                                    <i class="fas fa-ban me-2"></i>Out of Stock
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endforeach

                <!-- Show Uncategorized Products -->
                @php
                    $uncategorizedProducts = $products->whereNull('category_id')->where('category_id', '!=', '');
                @endphp
                
                @if($uncategorizedProducts->count() > 0)
                    <div class="category-section" data-category-id="uncategorized">
                        <div class="category-header">
                            <h3 class="category-title">
                                <i class="fas fa-question-circle me-2"></i>Uncategorized
                            </h3>
                            <span class="category-count">{{ $uncategorizedProducts->count() }} items</span>
                        </div>
                        
                        <div class="products-grid">
                            @foreach($uncategorizedProducts as $product)
                            <div class="product-item"
                                 data-category="uncategorized"
                                 data-name="{{ strtolower($product->name) }}"
                                 data-price="{{ $product->price }}"
                                 data-product-id="{{ $product->id }}"
                                 data-product-name="{{ $product->name }}"
                                 data-product-image="{{ asset('asset/images/' . $product->image) }}"
                                 data-product-description="{{ $product->description ?? 'Beautiful handcrafted crochet item.' }}"
                                 data-product-stock="{{ $product->stock }}">
                                <div class="product-card" onclick="openProductModal(this.parentElement)">
                                    <div class="product-image-wrapper">
                                        @if($product->image)
                                            <img src="{{ asset('asset/images/' . $product->image) }}" 
                                                 alt="{{ $product->name }}" 
                                                 class="product-image"
                                                 onerror="this.onerror=null; this.src='{{ asset('asset/images/placeholder.jpg') }}';">
                                        @else
                                            <img src="{{ asset('asset/images/placeholder.jpg') }}" 
                                                 alt="{{ $product->name }}" 
                                                 class="product-image">
                                        @endif
                                    </div>

                                    <div class="product-info">
                                        <h6 class="product-title">{{ $product->name }}</h6>
                                        <span class="product-price">₱{{ number_format($product->price, 2) }}</span>
                                        
                                        @if($product->stock > 0)
                                            <div class="cart-section" onclick="event.stopPropagation();">
                                                <div class="quantity-selector">
                                                    <button type="button" class="quantity-btn" onclick="decreaseQuantity({{ $product->id }})">
                                                        <i class="fas fa-minus"></i>
                                                    </button>
                                                    <input type="number" 
                                                           id="quantity-{{ $product->id }}" 
                                                           class="quantity-input" 
                                                           value="1" 
                                                           min="1" 
                                                           max="{{ $product->stock }}"
                                                           readonly>
                                                    <button type="button" class="quantity-btn" onclick="increaseQuantity({{ $product->id }}, {{ $product->stock }})">
                                                        <i class="fas fa-plus"></i>
                                                    </button>
                                                </div>
                                                
                                                @auth
                                                    <button type="button" 
                                                            class="add-to-cart-btn" 
                                                            id="add-btn-{{ $product->id }}"
                                                            onclick="addToCart({{ $product->id }})">
                                                        <span class="btn-text">
                                                            <i class="fas fa-cart-plus me-2"></i>Add to Cart
                                                        </span>
                                                        <span class="btn-loading d-none">
                                                            <i class="fas fa-spinner fa-spin me-2"></i>Adding...
                                                        </span>
                                                        <span class="btn-success d-none">
                                                            <i class="fas fa-check me-2"></i>Added!
                                                        </span>
                                                    </button>
                                                @else
                                                    <button type="button" class="add-to-cart-btn" 
                                                            data-bs-toggle="modal" data-bs-target="#loginModal">
                                                        <i class="fas fa-sign-in-alt me-2"></i>Login to Purchase
                                                    </button>
                                                @endauth
                                            </div>
                                        @else
                                            <button class="add-to-cart-btn" disabled style="background: #6c757d;">
                                                <i class="fas fa-ban me-2"></i>Out of Stock
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Empty State -->
                <div id="emptyState" class="empty-state" style="display: none;">
                    <i class="fas fa-box-open fa-4x"></i>
                    <h4>No products found</h4>
                    <p class="text-muted">Try adjusting your filters or search terms</p>
                    <button class="btn btn-primary" onclick="resetFilters()">
                        <i class="fas fa-redo me-2"></i>Reset Filters
                    </button>
                </div>
            </div>
        </main>
    </div>
</div>

<!-- Footer -->
@include('components.footer')

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script>
    const ShopState = {
        filters: {
            category: 'all',
            price: 2000,
            search: '',
            sort: 'default'
        },
        elements: {
            productSearch: null,
            priceRange: null,
            maxPrice: null,
            sortSelect: null,
            categoryItems: null,
            productCount: null,
            emptyState: null,
            categorySections: null
        },
        
        init() {
            this.elements.productSearch = document.getElementById('productSearch');
            this.elements.priceRange = document.getElementById('priceRange');
            this.elements.maxPrice = document.getElementById('maxPrice');
            this.elements.sortSelect = document.getElementById('sortSelect');
            this.elements.categoryItems = document.querySelectorAll('.category-item');
            this.elements.productCount = document.getElementById('productCount');
            this.elements.emptyState = document.getElementById('emptyState');
            this.elements.categorySections = document.querySelectorAll('.category-section');
        },
        
        updateFilter(key, value) {
            this.filters[key] = value;
            FilterManager.apply();
        },
        
        resetFilters() {
            this.filters = {
                category: 'all',
                price: 2000,
                search: '',
                sort: 'default'
            };
        }
    };

    const FilterManager = {
        apply() {
            let totalVisibleCount = 0;
            let hasVisibleSection = false;
            
            ShopState.elements.categorySections.forEach(section => {
                const sectionCategoryId = section.dataset.categoryId;
                const items = section.querySelectorAll('.product-item');
                let visibleInSection = 0;
                
                // Check if this section should be visible based on category filter
                const categoryMatch = (
                    ShopState.filters.category === 'all' || 
                    ShopState.filters.category === sectionCategoryId
                );
                
                if (!categoryMatch) {
                    section.style.display = 'none';
                    return;
                }
                
                section.style.display = 'block';
                
                items.forEach((item) => {
                    const itemPrice = parseFloat(item.dataset.price) || 0;
                    const itemName = item.dataset.name.toLowerCase();
                    
                    const priceMatch = itemPrice <= ShopState.filters.price;
                    const searchMatch = (
                        ShopState.filters.search === '' || 
                        itemName.includes(ShopState.filters.search.toLowerCase())
                    );
                    
                    if (priceMatch && searchMatch) {
                        item.style.display = 'block';
                        visibleInSection++;
                        totalVisibleCount++;
                    } else {
                        item.style.display = 'none';
                    }
                });
                
                // Show/hide entire category section based on visible items
                if (visibleInSection > 0) {
                    section.style.display = 'block';
                    hasVisibleSection = true;
                } else {
                    section.style.display = 'none';
                }
            });
            
            this.updateUI(totalVisibleCount, hasVisibleSection);
            SortManager.apply();
        },
        
        updateUI(totalCount, hasVisibleSection) {
            if (ShopState.elements.productCount) {
                ShopState.elements.productCount.textContent = totalCount;
            }
            
            if (ShopState.elements.emptyState) {
                ShopState.elements.emptyState.style.display = (totalCount === 0 || !hasVisibleSection) ? 'block' : 'none';
            }
        },
        
        byCategory(category) {
            ShopState.updateFilter('category', category);
            
            ShopState.elements.categoryItems.forEach(item => {
                item.classList.toggle('active', item.dataset.category === category);
            });
        },
        
        byPrice(price) {
            ShopState.updateFilter('price', price);
        },
        
        bySearch(searchTerm) {
            ShopState.updateFilter('search', searchTerm);
        },
        
        reset() {
            ShopState.resetFilters();
            
            if (ShopState.elements.productSearch) {
                ShopState.elements.productSearch.value = '';
            }
            if (ShopState.elements.priceRange) {
                ShopState.elements.priceRange.value = 2000;
            }
            if (ShopState.elements.maxPrice) {
                ShopState.elements.maxPrice.value = 2000;
            }
            if (ShopState.elements.sortSelect) {
                ShopState.elements.sortSelect.value = 'default';
            }
            
            ShopState.elements.categoryItems.forEach(item => {
                item.classList.toggle('active', item.dataset.category === 'all');
            });
            
            this.apply();
        }
    };

    const SortManager = {
        apply() {
            const visibleSections = document.querySelectorAll('.category-section');
            
            visibleSections.forEach(section => {
                if (section.style.display === 'none') return;
                
                const container = section.querySelector('.products-grid');
                const items = Array.from(container.querySelectorAll('.product-item'));
                const visibleItems = items.filter(item => item.style.display !== 'none');
                
                visibleItems.sort((a, b) => this.compareItems(a, b));
                visibleItems.forEach(item => container.appendChild(item));
            });
        },
        
        compareItems(a, b) {
            switch(ShopState.filters.sort) {
                case 'price-low':
                    return (parseFloat(a.dataset.price) || 0) - (parseFloat(b.dataset.price) || 0);
                case 'price-high':
                    return (parseFloat(b.dataset.price) || 0) - (parseFloat(a.dataset.price) || 0);
                case 'name':
                    return (a.dataset.name || '').localeCompare(b.dataset.name || '');
                default:
                    return 0;
            }
        },
        
        change(sortType) {
            ShopState.updateFilter('sort', sortType);
        }
    };

    const PriceManager = {
        init() {
            if (!ShopState.elements.priceRange || !ShopState.elements.maxPrice) return;
            
            ShopState.elements.priceRange.addEventListener('input', (e) => {
                const value = parseInt(e.target.value);
                ShopState.elements.maxPrice.value = value;
                FilterManager.byPrice(value);
            });
            
            ShopState.elements.maxPrice.addEventListener('input', (e) => {
                let value = parseInt(e.target.value);
                value = this.constrainValue(value);
                
                e.target.value = value;
                ShopState.elements.priceRange.value = value;
                FilterManager.byPrice(value);
            });
            
            ShopState.elements.maxPrice.addEventListener('blur', (e) => {
                if (e.target.value === '' || parseInt(e.target.value) < 20) {
                    e.target.value = 20;
                    ShopState.elements.priceRange.value = 20;
                    FilterManager.byPrice(20);
                }
            });
        },
        
        constrainValue(value) {
            if (value < 20) return 20;
            if (value > 2000) return 2000;
            return value;
        }
    };

    const SearchManager = {
        searchTimeout: null,
        
        init() {
            if (!ShopState.elements.productSearch) return;
            
            ShopState.elements.productSearch.addEventListener('input', (e) => {
                clearTimeout(this.searchTimeout);
                this.searchTimeout = setTimeout(() => {
                    FilterManager.bySearch(e.target.value.trim());
                }, 300);
            });
            
            ShopState.elements.productSearch.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    clearTimeout(this.searchTimeout);
                    FilterManager.bySearch(e.target.value.trim());
                }
            });
        }
    };

    const CartManager = {
        add(productId) {
            const button = document.getElementById(`add-btn-${productId}`);
            const quantityInput = document.getElementById(`quantity-${productId}`);
            const quantity = parseInt(quantityInput.value);
            
            this.setButtonState(button, 'loading');
            
            const formData = new FormData();
            formData.append('product_id', productId);
            formData.append('quantity', quantity);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
            
            fetch('{{ route("cart.add") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                }
            })
            .then(async response => {
                const contentType = response.headers.get('content-type');
                
                if (contentType && contentType.includes('application/json')) {
                    return response.json();
                }
                throw new Error('Server returned non-JSON response');
            })
            .then(data => {
                if (data.success) {
                    this.handleSuccess(button, productId, quantity, data.cart_count);
                } else {
                    throw new Error(data.message || 'Failed to add to cart');
                }
            })
            .catch(error => {
                this.handleError(button, error);
            });
        },
        
        setButtonState(button, state) {
            button.classList.remove('loading', 'success');
            button.querySelector('.btn-text').classList.add('d-none');
            button.querySelector('.btn-loading').classList.add('d-none');
            button.querySelector('.btn-success').classList.add('d-none');
            
            switch(state) {
                case 'loading':
                    button.classList.add('loading');
                    button.querySelector('.btn-loading').classList.remove('d-none');
                    button.disabled = true;
                    break;
                case 'success':
                    button.classList.add('success');
                    button.querySelector('.btn-success').classList.remove('d-none');
                    break;
                case 'default':
                    button.querySelector('.btn-text').classList.remove('d-none');
                    button.disabled = false;
                    break;
            }
        },
        
        handleSuccess(button, productId, quantity, cartCount) {
            this.setButtonState(button, 'success');
            
            this.updateCartBadge(cartCount);
            
            const productName = button.closest('.product-item').dataset.productName;
            ToastManager.success(`${productName} (${quantity}) added to cart!`);
            
            setTimeout(() => {
                this.setButtonState(button, 'default');
                document.getElementById(`quantity-${productId}`).value = 1;
            }, 2000);
        },
        
        handleError(button, error) {
            this.setButtonState(button, 'default');
            ToastManager.error(error.message || 'Failed to add product to cart. Please try again.');
        },
        
        updateCartBadge(newCount) {
            const cartBadge = document.querySelector('.cart-badge');
            if (cartBadge) {
                cartBadge.textContent = newCount;
                cartBadge.style.animation = 'none';
                setTimeout(() => {
                    cartBadge.style.animation = 'pulse 0.6s ease-in-out';
                }, 10);
            }
        }
    };

    const QuantityManager = {
        decrease(productId) {
            const input = document.getElementById(`quantity-${productId}`);
            let currentValue = parseInt(input.value);
            
            if (currentValue > 1) {
                input.value = currentValue - 1;
            }
        },
        
        increase(productId, maxStock) {
            const input = document.getElementById(`quantity-${productId}`);
            let currentValue = parseInt(input.value);
            
            if (currentValue < maxStock) {
                input.value = currentValue + 1;
            }
        }
    };

    const ToastManager = {
        success(message) {
            this.show('cartToast', 'toastMessage', message, 3000);
        },
        
        error(message) {
            this.show('errorToast', 'errorMessage', message, 5000);
        },
        
        show(toastId, messageId, message, delay) {
            const toastElement = document.getElementById(toastId);
            const messageElement = document.getElementById(messageId);
            
            if (!toastElement || !messageElement) return;
            
            messageElement.textContent = message;
            
            const toast = new bootstrap.Toast(toastElement, {
                autohide: true,
                delay: delay
            });
            
            toast.show();
        }
    };

    // Global functions
    function filterProducts(category) {
        FilterManager.byCategory(category);
    }

    function resetFilters() {
        FilterManager.reset();
    }

    function decreaseQuantity(productId) {
        QuantityManager.decrease(productId);
    }

    function increaseQuantity(productId, maxStock) {
        QuantityManager.increase(productId, maxStock);
    }

    function addToCart(productId) {
        CartManager.add(productId);
    }

    function openProductModal(element) {
        // Add your product modal logic here
        console.log('Opening product modal for:', element.dataset.productName);
    }

    // Initialize everything when DOM is loaded
    document.addEventListener('DOMContentLoaded', function() {
        ShopState.init();
        
        PriceManager.init();
        SearchManager.init();
        
        if (ShopState.elements.sortSelect) {
            ShopState.elements.sortSelect.addEventListener('change', (e) => {
                SortManager.change(e.target.value);
            });
        }
        
        // Apply initial filters
        FilterManager.apply();
        
        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    });
</script>

</body>
</html>