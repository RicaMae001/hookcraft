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
<nav class="navbar navbar-expand-lg navbar-light sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold" href="{{ route('home') }}">
            <img src="{{ asset('asset/images/logo.jpg') }}" alt="Logo" width="40" height="40" class="rounded-circle me-2">
            HookcraftAvenue
        </a>
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarContent">
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Home</a></li>
                <li class="nav-item"><a class="nav-link active" href="{{ route('shop') }}">Shop</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('about') }}">About</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('gallery') }}">Gallery</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('contact') }}">Contact</a></li>
            </ul>

            @php
                $isLoggedIn = Auth::check();
                $user = Auth::user();
            @endphp

            <ul class="navbar-nav flex-row align-items-center">
                <!-- Chatbot Icon -->
                <li class="nav-item me-3">
                    <a class="nav-link position-relative" href="{{ route('chatbot') }}" title="AI Assistant">
                        <i class="bi bi-robot fs-5" style="color: #FF69B4;"></i>
                        <span class="position-absolute top-0 start-100 translate-middle">
                            <span class="badge bg-success rounded-circle p-1" style="width: 8px; height: 8px;"></span>
                        </span>
                    </a>
                </li>

                <!-- Cart -->
                <li class="nav-item me-3">
                    <a class="nav-link position-relative" href="{{ $isLoggedIn ? route('cart.index') : '#' }}">
                        <i class="bi bi-cart fs-5"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger cart-badge">
                            {{ $cartCount }}
                        </span>
                    </a>
                </li>

                @if($isLoggedIn)
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle p-0 border-0 bg-transparent d-flex align-items-center" 
                           href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                            <img src="{{ asset('asset/images/default-profile.png') }}" alt="Profile" 
                                 width="40" height="40" class="rounded-circle" style="object-fit: cover; border: 2px solid #FFB6C1;">
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end mt-2 shadow" style="min-width: 250px;">
                            <li class="px-3 py-3 border-bottom">
                                <div class="d-flex align-items-center">
                                    <img src="{{ asset('asset/images/default-profile.png') }}" alt="Profile" 
                                         width="50" height="50" class="rounded-circle me-3" style="border: 2px solid #FFB6C1;">
                                    <div>
                                        <div class="fw-bold">{{ $user->name }}</div>
                                        <small class="text-muted">{{ $user->email }}</small>
                                    </div>
                                </div>
                            </li>
                            <li><a class="dropdown-item py-2" href="{{ route('profile.index') }}">
                                <i class="bi bi-person-circle me-2"></i>My Account</a></li>
                            <li><a class="dropdown-item py-2" href="{{ route('profile.purchase-history') }}">
                                <i class="bi bi-clock-history me-2"></i>Purchase History</a></li>
                            <li><a class="dropdown-item py-2" href="{{ route('profile.track-order') }}">
                                <i class="bi bi-truck me-2"></i>Track Order</a></li>
                            <li><hr class="dropdown-divider my-2"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button class="dropdown-item text-danger py-2" type="submit">
                                        <i class="bi bi-box-arrow-right me-2"></i>Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @else
                    <li class="nav-item me-2">
                        <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#loginModal">Login</a>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>
<div class="chatbot-float">
    <a href="{{ route('chatbot') }}" class="chatbot-pulse" title="Chat with AI Assistant">
        <i class="bi bi-robot"></i>
    </a>
</div>

<!-- Main Content -->
<div class="container" style="margin-top:20px">
    <!-- Filter Bar -->
    <div class="filter-bar">
        <div class="filter-section">
            <!-- Search -->
            <div class="search-wrapper">
                <div class="filter-label">
                    <i class="fas fa-search me-1"></i> Search Products
                </div>
                <div class="position-relative">
                    <input type="text" 
                           class="search-input" 
                           id="productSearch" 
                           placeholder="Search for handmade items..."
                           autocomplete="off">
                    <i class="fas fa-search search-icon"></i>
                </div>
            </div>

            <!-- Price Filter -->
            <div class="filter-group">
                <div class="filter-label">
                    <i class="fas fa-dollar-sign me-1"></i> Price Range
                </div>
                <div class="price-filter">
                    <span class="text-muted">₱20</span>
                    <input type="range" 
                           class="price-range-slider" 
                           id="priceRange" 
                           min="20" 
                           max="1000" 
                           value="1000">
                    <div class="price-display">
                        ₱<span id="maxPrice">1000</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Categories -->
        <div class="mt-4">
            <div class="filter-label mb-3">
                <i class="fas fa-tags me-1"></i> Categories
            </div>
            <div class="category-pills">
                <button class="category-pill active" onclick="filterProducts('all')" data-category="all">
                    <i class="fas fa-th-large me-1"></i> All Products
                </button>
                @foreach($categories as $cat)
                <button class="category-pill" onclick="filterProducts('{{ $cat->id }}')" data-category="{{ $cat->id }}">
                    <i class="fas fa-tag me-1"></i> {{ $cat->name }}
                </button>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Results Info -->
    <div class="results-info">
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

    <!-- Products Grid -->
    <div class="products-grid" id="product-list">
        @forelse($products as $product)
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
                    <img src="{{ asset('asset/images/' . $product->image) }}" 
                         alt="{{ $product->name }}" 
                         class="product-image">
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
                            
                            @if($isLoggedIn)
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
                            @endif
                        </div>
                    @else
                        <button class="add-to-cart-btn" disabled style="background: #6c757d;">
                            <i class="fas fa-ban me-2"></i>Out of Stock
                        </button>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="empty-state">
                <i class="fas fa-box-open fa-4x"></i>
                <h4>No products found</h4>
                <p class="text-muted">Try adjusting your filters or search terms</p>
                <button class="btn btn-primary" onclick="resetFilters()">
                    <i class="fas fa-redo me-2"></i>Reset Filters
                </button>
            </div>
        </div>
        @endforelse
    </div>
</div>

<!-- Footer -->
<footer class="footer text-white">
    <div class="container">
        <div class="row">
            <div class="col-md-6 text-md-start">
                <p class="mb-0">&copy; 2025 Hookcraft Avenue. All rights reserved.</p>
            </div>
            <div class="col-md-6 text-md-end">
                <div class="social-links">
                    <a href="#" class="text-white me-3"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="text-white me-3"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="text-white"><i class="bi bi-twitter"></i></a>
                </div>
            </div>
        </div>
    </div>
</footer>


<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script>
    let currentFilters = {
        category: 'all',
        price: 1000,
        search: '',
        sort: 'default'
    };

    // Filter Products
    function filterProducts(category) {
        currentFilters.category = category;
        
        // Update active category pill
        document.querySelectorAll('.category-pill').forEach(pill => {
            pill.classList.remove('active');
            if (pill.dataset.category === category) {
                pill.classList.add('active');
            }
        });
        
        applyFilters();
    }

    function applyFilters() {
        const items = document.querySelectorAll('.product-item');
        let visibleCount = 0;
        
        items.forEach((item, index) => {
            const itemCategory = item.dataset.category;
            const itemPrice = parseFloat(item.dataset.price);
            const itemName = item.dataset.name;
            
            const categoryMatch = (currentFilters.category === 'all' || itemCategory === currentFilters.category);
            const priceMatch = (itemPrice <= currentFilters.price);
            const searchMatch = (currentFilters.search === '' || itemName.includes(currentFilters.search.toLowerCase()));
            
            const shouldShow = categoryMatch && priceMatch && searchMatch;
            
            if (shouldShow) {
                item.style.display = 'block';
                item.style.animationDelay = `${visibleCount * 0.05}s`;
                visibleCount++;
            } else {
                item.style.display = 'none';
            }
        });
        
        updateProductCount(visibleCount);
        applySorting();
    }

    function updateProductCount(count) {
        const countElement = document.getElementById('productCount');
        if (countElement) {
            countElement.textContent = count !== undefined ? count : document.querySelectorAll('.product-item:not([style*="display: none"])').length;
        }
    }

    // Price Range Filter
    const priceSlider = document.getElementById('priceRange');
    const maxPriceText = document.getElementById('maxPrice');

    if (priceSlider && maxPriceText) {
        priceSlider.addEventListener('input', function() {
            const maxPrice = parseInt(this.value);
            maxPriceText.textContent = maxPrice;
            currentFilters.price = maxPrice;
            applyFilters();
        });
    }

    // Search Functionality
    const searchInput = document.getElementById('productSearch');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(this.searchTimeout);
            this.searchTimeout = setTimeout(() => {
                currentFilters.search = this.value.trim();
                applyFilters();
            }, 300);
        });

        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                currentFilters.search = this.value.trim();
                applyFilters();
            }
        });
    }

    // Sorting
    const sortSelect = document.getElementById('sortSelect');
    if (sortSelect) {
        sortSelect.addEventListener('change', function() {
            currentFilters.sort = this.value;
            applySorting();
        });
    }

    function applySorting() {
        const container = document.getElementById('product-list');
        const items = Array.from(document.querySelectorAll('.product-item'));
        const visibleItems = items.filter(item => item.style.display !== 'none');
        
        visibleItems.sort((a, b) => {
            switch(currentFilters.sort) {
                case 'price-low':
                    return parseFloat(a.dataset.price) - parseFloat(b.dataset.price);
                case 'price-high':
                    return parseFloat(b.dataset.price) - parseFloat(a.dataset.price);
                case 'name':
                    return a.dataset.name.localeCompare(b.dataset.name);
                default:
                    return 0;
            }
        });
        
        visibleItems.forEach(item => container.appendChild(item));
    }

    // Reset Filters
    function resetFilters() {
        currentFilters = {
            category: 'all',
            price: 1000,
            search: '',
            sort: 'default'
        };
        
        document.getElementById('productSearch').value = '';
        document.getElementById('priceRange').value = 1000;
        document.getElementById('maxPrice').textContent = '1000';
        document.getElementById('sortSelect').value = 'default';
        
        document.querySelectorAll('.category-pill').forEach(pill => {
            pill.classList.remove('active');
            if (pill.dataset.category === 'all') {
                pill.classList.add('active');
            }
        });
        
        applyFilters();
    }

    // Quantity Controls
    function decreaseQuantity(productId) {
        const input = document.getElementById(`quantity-${productId}`);
        let currentValue = parseInt(input.value);
        
        if (currentValue > 1) {
            currentValue--;
            input.value = currentValue;
        }
    }

    function increaseQuantity(productId, maxStock) {
        const input = document.getElementById(`quantity-${productId}`);
        let currentValue = parseInt(input.value);
        
        if (currentValue < maxStock) {
            currentValue++;
            input.value = currentValue;
        }
    }

    // Toast Functions
    function showSuccessToast(message) {
        const toastElement = document.getElementById('cartToast');
        const toastMessage = document.getElementById('toastMessage');
        
        toastMessage.textContent = message;
        
        const toast = new bootstrap.Toast(toastElement, {
            autohide: true,
            delay: 3000
        });
        
        toast.show();
    }

    function showErrorToast(message) {
        const toastElement = document.getElementById('errorToast');
        const errorMessage = document.getElementById('errorMessage');
        
        errorMessage.textContent = message;
        
        const toast = new bootstrap.Toast(toastElement, {
            autohide: true,
            delay: 5000
        });
        
        toast.show();
    }

    // Update Cart Count
    function updateCartCount(newCount) {
        const cartBadge = document.querySelector('.cart-badge');
        if (cartBadge) {
            cartBadge.textContent = newCount;
            cartBadge.style.animation = 'pulse 0.6s ease-in-out';
        }
    }

    // Add to Cart
    function addToCart(productId) {
        const button = document.getElementById(`add-btn-${productId}`);
        const quantityInput = document.getElementById(`quantity-${productId}`);
        const quantity = parseInt(quantityInput.value);
        
        button.classList.add('loading');
        button.querySelector('.btn-text').classList.add('d-none');
        button.querySelector('.btn-loading').classList.remove('d-none');
        button.disabled = true;
        
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
            } else {
                const text = await response.text();
                throw new Error('Server returned non-JSON response');
            }
        })
        .then(data => {
            if (data.success) {
                button.classList.remove('loading');
                button.classList.add('success');
                button.querySelector('.btn-loading').classList.add('d-none');
                button.querySelector('.btn-success').classList.remove('d-none');
                
                updateCartCount(data.cart_count);
                
                const productName = button.closest('.product-item').dataset.productName;
                showSuccessToast(`${productName} (${quantity}) added to cart!`);
                
                setTimeout(() => {
                    button.classList.remove('success');
                    button.querySelector('.btn-success').classList.add('d-none');
                    button.querySelector('.btn-text').classList.remove('d-none');
                    button.disabled = false;
                    quantityInput.value = 1;
                }, 2000);
            } else {
                throw new Error(data.message || 'Failed to add to cart');
            }
        })
        .catch(error => {
            button.classList.remove('loading');
            button.querySelector('.btn-loading').classList.add('d-none');
            button.querySelector('.btn-text').classList.remove('d-none');
            button.disabled = false;
            
            showErrorToast(error.message || 'Failed to add product to cart. Please try again.');
        });
    }

    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        updateProductCount();
        
        // Smooth scrolling
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