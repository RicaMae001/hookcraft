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

<!-- Toast Container for Messages -->
<div class="toast-container">
    <div id="cartToast" class="toast cart-toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-body d-flex align-items-center">
            <i class="fas fa-check-circle me-2"></i>
            <span id="toastMessage">Product added to cart!</span>
        </div>
    </div>
    <div id="errorToast" class="toast error-toast" role="alert" aria-live="assertive" aria-atomic="true">
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

                <!-- User -->
                @if($isLoggedIn)
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle p-0 border-0 bg-transparent d-flex align-items-center" 
                           href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="{{ asset('asset/images/default-profile.png') }}" alt="Profile" 
                                 width="40" height="40" class="rounded-circle" style="object-fit: cover; border: 2px solid #FFB6C1;">
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end mt-2 shadow" style="min-width: 250px;">
                            <!-- User Info Header -->
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

                            <!-- Menu Items -->
                            <li>
                                <a class="dropdown-item py-2" href="{{ route('profile.index') }}">
                                    <i class="bi bi-person-circle me-2"></i>My Account
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item py-2" href="{{ route('profile.purchase-history') }}">
                                    <i class="bi bi-clock-history me-2"></i>Purchase History
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item py-2" href="{{ route('profile.track-order') }}">
                                    <i class="bi bi-truck me-2"></i>Track Order
                                </a>
                            </li>
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

<!-- Shop Layout -->
<div class="container-fluid">
    <div class="row">
        <!-- Enhanced Sidebar Categories -->
        <aside class="col-lg-3 col-md-4">
            <div class="sidebar">
                <h4><i class="fas fa-list-alt me-2"></i>Categories</h4>
                <ul class="list-group category-list">
                    <li class="list-group-item category-item">
                        <a href="#" onclick="filterProducts('all')" class="category-link">
                            <i class="fas fa-th-large me-2"></i>All Products
                        </a>
                    </li>
                    @foreach($categories as $cat)
                    <li class="list-group-item category-item">
                        <a href="#" onclick="filterProducts('{{ $cat->id }}')" class="category-link">
                            <i class="fas fa-tag me-2"></i>{{ $cat->name }}
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>

            <!-- Enhanced Price Filter -->
            <div class="filter-price">
                <h5><i class="fas fa-filter me-2"></i>Filter by Price</h5>
                <div class="price-range-container">
                    <input type="range" class="form-range" min="20" max="1000" value="1000" id="priceRange">
                    <div class="d-flex justify-content-between mt-2">
                        <span class="badge bg-light text-dark">₱20</span>
                        <span class="badge bg-primary">₱<span id="maxPrice">1000</span></span>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Enhanced Product Grid -->
        <section class="col-lg-9 col-md-8">
            <!-- Search Bar -->
            <div class="search-container">
                <div class="search-input-group">
                    <input type="text" 
                           class="form-control search-input" 
                           id="productSearch" 
                           placeholder="Search for products..." 
                           autocomplete="off">
                    <button class="search-btn" onclick="searchProducts()">
                        <i class="fas fa-search"></i>
                    </button>
                    <button class="search-btn clear-search d-none" id="clearSearch" onclick="clearSearch()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>

            <!-- Search Results Info -->
            <div class="search-results-info d-none" id="searchResultsInfo">
                <i class="fas fa-info-circle me-2"></i>
                <span id="searchResultsText"></span>
            </div>

            <div class="row g-4" id="product-list">
                @forelse($products as $product)
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 product-item"
                     data-category="{{ $product->category_id }}"
                     data-name="{{ strtolower($product->name) }}"
                     data-price="{{ $product->price }}"
                     data-product-id="{{ $product->id }}"
                     data-product-name="{{ $product->name }}"
                     data-product-image="{{ asset('asset/images/' . $product->image) }}"
                     data-product-description="{{ $product->description ?? 'Beautiful handcrafted crochet item made with premium materials.' }}"
                     data-product-stock="{{ $product->stock }}"
                     data-product-category-name="{{ $product->category->name ?? 'Uncategorized' }}">
                    <div class="product-card {{ $product->stock <= 0 ? 'out-of-stock' : '' }}" onclick="openProductModal(this.parentElement)">
                        <!-- Stock Badge -->
                        <div class="stock-badge">
                            @if($product->stock > 0)
                                @if($product->stock <= 5)
                                    <span class="badge bg-warning text-dark">
                                        <i class="fas fa-exclamation-triangle me-1"></i>Low Stock: {{ $product->stock }}
                                    </span>
                                @else
                                    <span class="badge bg-success">
                                        <i class="fas fa-check-circle me-1"></i>{{ $product->stock }} Available
                                    </span>
                                @endif
                            @else
                                <span class="badge bg-danger">
                                    <i class="fas fa-times-circle me-1"></i>Out of Stock
                                </span>
                            @endif
                        </div>

                        <!-- Product Image -->
                        <div class="product-image-container">
                            <img src="{{ asset('asset/images/' . $product->image) }}" 
                                 alt="{{ $product->name }}" 
                                 class="img-fluid">
                        </div>

                        <!-- Product Info -->
                        <div class="product-info">
                            <div>
                                <h6 class="product-title">{{ $product->name }}</h6>
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="product-price">₱{{ number_format($product->price, 2) }}</span>
                                    <span class="badge category-badge">{{ $product->category->name ?? 'Uncategorized' }}</span>
                                </div>
                            </div>
                            
                            <!-- Enhanced Add to Cart Section -->
                            @if($product->stock > 0)
                                <div class="cart-section" onclick="event.stopPropagation();">
                                    <!-- Quantity Selector -->
                                    <div class="quantity-selector d-flex align-items-center justify-content-center mb-2">
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
                                    
                                    <!-- Add to Cart Form -->
                                    @if($isLoggedIn)
                                        <button type="button" 
                                                class="btn btn-primary btn-sm w-100 add-to-cart-btn" 
                                                id="add-btn-{{ $product->id }}"
                                                onclick="addToCart({{ $product->id }})"
                                                style="border-radius: 8px; font-weight: 500;">
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
                                        <button type="button" class="btn btn-outline-primary btn-sm w-100" 
                                                data-bs-toggle="modal" data-bs-target="#loginModal"
                                                style="border-radius: 8px; font-weight: 500;">
                                            <i class="fas fa-sign-in-alt me-2"></i>Login to Add
                                        </button>
                                    @endif
                                </div>
                            @else
                                <button class="btn btn-secondary btn-sm w-100" disabled
                                        style="border-radius: 8px; font-weight: 500;">
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
                        <h4>No products available</h4>
                        <p class="text-muted">Please check back later or contact us for more information.</p>
                        <a href="{{ route('contact') }}" class="btn btn-primary">
                            <i class="fas fa-envelope me-2"></i>Contact Us
                        </a>
                    </div>
                </div>
                @endforelse
            </div>

            <!-- Load More Button (if pagination needed) -->
            @if($products->count() >= 12)
            <div class="text-center mt-5">
                <button class="btn btn-outline-primary btn-lg" id="loadMoreBtn">
                    <i class="fas fa-plus me-2"></i>Load More Products
                </button>
            </div>
            @endif
        </section>
    </div>
</div>

<!-- Enhanced Footer -->
<footer class="footer py-4 text-center bg-dark text-white">
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
    // Enhanced product filtering and animations
    let currentFilters = {
        category: 'all',
        price: 1000,
        search: ''
    };

    function updateProductCount() {
        const visibleProducts = document.querySelectorAll('.product-item:not([style*="display: none"])').length;
        document.getElementById('product-count').textContent = visibleProducts;
        
        // Update the header badge
        const countBadge = document.querySelector('.count-badge');
        if (countBadge) {
            countBadge.innerHTML = `<i class="fas fa-box me-2"></i>${visibleProducts} Products Available`;
        }
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
                // Stagger animation for filtered results
                setTimeout(() => {
                    item.style.animation = 'fadeInUp 0.4s ease forwards';
                }, visibleCount * 50);
                visibleCount++;
            } else {
                item.style.display = 'none';
            }
        });
        
        setTimeout(updateProductCount, 100);
        updateSearchResultsInfo();
    }

    function filterProducts(category) {
        currentFilters.category = category;
        applyFilters();
    }

    function searchProducts() {
        const searchInput = document.getElementById('productSearch');
        const searchTerm = searchInput.value.trim();
        
        currentFilters.search = searchTerm;
        applyFilters();
        
        // Show/hide clear button
        const clearBtn = document.getElementById('clearSearch');
        if (searchTerm) {
            clearBtn.classList.remove('d-none');
        } else {
            clearBtn.classList.add('d-none');
        }
    }

    function clearSearch() {
        const searchInput = document.getElementById('productSearch');
        searchInput.value = '';
        currentFilters.search = '';
        
        document.getElementById('clearSearch').classList.add('d-none');
        document.getElementById('searchResultsInfo').classList.add('d-none');
        
        applyFilters();
    }

    function updateSearchResultsInfo() {
        const searchResultsInfo = document.getElementById('searchResultsInfo');
        const searchResultsText = document.getElementById('searchResultsText');
        
        if (currentFilters.search) {
            const visibleProducts = document.querySelectorAll('.product-item:not([style*="display: none"])').length;
            searchResultsText.textContent = `Found ${visibleProducts} product(s) matching "${currentFilters.search}"`;
            searchResultsInfo.classList.remove('d-none');
        } else {
            searchResultsInfo.classList.add('d-none');
        }
    }

    // Enhanced price filter
    const priceSlider = document.getElementById('priceRange');
    const maxPriceText = document.getElementById('maxPrice');

    if (priceSlider && maxPriceText) {
        priceSlider.addEventListener('input', function () {
            const maxPrice = parseInt(this.value);
            maxPriceText.textContent = maxPrice;
            currentFilters.price = maxPrice;
            applyFilters();
        });
    }

    // Real-time search as user types
    const searchInput = document.getElementById('productSearch');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(this.searchTimeout);
            this.searchTimeout = setTimeout(() => {
                searchProducts();
            }, 300); // Debounce search for better performance
        });

        // Search on Enter key
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                searchProducts();
            }
        });
    }

    // Quantity control functions
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

    // Function to show success toast
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

    // Function to show error toast
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

    // FIXED: Function to update cart count - now targets the correct element
    function updateCartCount(newCount) {
        const cartBadge = document.querySelector('.cart-badge');
        if (cartBadge) {
            cartBadge.textContent = newCount;
            
            // Add animation to cart badge
            cartBadge.style.animation = 'none';
            setTimeout(() => {
                cartBadge.style.animation = 'pulse 0.6s ease-in-out';
            }, 10);
        }
    }

    // Main add to cart function
    function addToCart(productId) {
        const button = document.getElementById(`add-btn-${productId}`);
        const quantityInput = document.getElementById(`quantity-${productId}`);
        const quantity = parseInt(quantityInput.value);
        
        // Show loading state
        button.classList.add('loading');
        button.querySelector('.btn-text').classList.add('d-none');
        button.querySelector('.btn-loading').classList.remove('d-none');
        button.disabled = true;
        
        // Prepare form data
        const formData = new FormData();
        formData.append('product_id', productId);
        formData.append('quantity', quantity);
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
        
        console.log('Adding to cart:', { productId, quantity }); // Debug log
        
        // Submit via fetch
        fetch('{{ route("cart.add") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            }
        })
        .then(async response => {
            const contentType = response.headers.get('content-type');
            console.log('Response status:', response.status); // Debug log
            console.log('Response content-type:', contentType); // Debug log
            
            if (contentType && contentType.includes('application/json')) {
                return response.json();
            } else {
                // If response is not JSON, get text for debugging
                const text = await response.text();
                console.log('Response text:', text); // Debug log
                throw new Error('Server returned non-JSON response: ' + text.substring(0, 200));
            }
        })
        .then(data => {
            console.log('Success response:', data); // Debug log
            
            if (data.success) {
                // Show success state
                button.classList.remove('loading');
                button.classList.add('success');
                button.querySelector('.btn-loading').classList.add('d-none');
                button.querySelector('.btn-success').classList.remove('d-none');
                
                // Update cart count - THIS IS THE FIX
                updateCartCount(data.cart_count);
                
                // Show success toast
                const productName = button.closest('.product-item').dataset.productName;
                showSuccessToast(`${productName} (${quantity}) added to cart!`);
                
                // Reset button after 2 seconds
                setTimeout(() => {
                    button.classList.remove('success');
                    button.querySelector('.btn-success').classList.add('d-none');
                    button.querySelector('.btn-text').classList.remove('d-none');
                    button.disabled = false;
                    
                    // Reset quantity to 1
                    quantityInput.value = 1;
                }, 2000);
            } else {
                throw new Error(data.message || 'Failed to add to cart');
            }
        })
        .catch(error => {
            console.error('Error adding to cart:', error); // Debug log
            
            // Reset button state
            button.classList.remove('loading');
            button.querySelector('.btn-loading').classList.add('d-none');
            button.querySelector('.btn-text').classList.remove('d-none');
            button.disabled = false;
            
            // Show error message
            showErrorToast(error.message || 'Failed to add product to cart. Please try again.');
        });
    }

    // Initialize page
    document.addEventListener('DOMContentLoaded', function() {
        updateProductCount();
        
        // Add smooth scrolling for better UX
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
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
        
        console.log('Page loaded, CSRF token:', document.querySelector('meta[name="csrf-token"]').getAttribute('content')); // Debug log
    });
</script>

</body>
</html>