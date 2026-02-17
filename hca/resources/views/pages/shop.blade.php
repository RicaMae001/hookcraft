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
        <i class="bi bi-chat"></i>
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
                    @if($groupedProducts->has($category->id))
                        <div class="category-section" data-category-id="{{ $category->id }}">
                            <div class="category-header">
                                <h3 class="category-title">
                                    <i class="fas fa-tag me-2"></i>{{ $category->name }}
                                </h3>
                                <span class="category-count">{{ $groupedProducts[$category->id]->count() }} items</span>
                            </div>
                            
                            <div class="products-grid">
                                @foreach($groupedProducts[$category->id] as $product)
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
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endforeach

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
            
            ShopState.elements.categorySections.forEach(section => {
                const sectionCategoryId = section.dataset.categoryId;
                const items = section.querySelectorAll('.product-item');
                let visibleInSection = 0;
                
                const categoryMatch = (
                    ShopState.filters.category === 'all' || 
                    sectionCategoryId === ShopState.filters.category
                );
                
                if (!categoryMatch) {
                    section.classList.add('hidden');
                    return;
                }
                
                items.forEach((item, index) => {
                    const itemPrice = parseFloat(item.dataset.price);
                    const itemName = item.dataset.name;
                    
                    const priceMatch = itemPrice <= ShopState.filters.price;
                    const searchMatch = (
                        ShopState.filters.search === '' || 
                        itemName.includes(ShopState.filters.search.toLowerCase())
                    );
                    
                    const shouldShow = priceMatch && searchMatch;
                    
                    if (shouldShow) {
                        item.style.display = 'block';
                        item.style.animationDelay = `${visibleInSection * 0.05}s`;
                        visibleInSection++;
                        totalVisibleCount++;
                    } else {
                        item.style.display = 'none';
                    }
                });
                
                section.classList.toggle('hidden', visibleInSection === 0);
            });
            
            this.updateUI(totalVisibleCount);
            SortManager.apply();
        },
        
        updateUI(totalCount) {
            if (ShopState.elements.productCount) {
                ShopState.elements.productCount.textContent = totalCount;
            }
            
            if (ShopState.elements.emptyState) {
                ShopState.elements.emptyState.style.display = totalCount === 0 ? 'block' : 'none';
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
            const visibleSections = document.querySelectorAll('.category-section:not(.hidden)');
            
            visibleSections.forEach(section => {
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
                    return parseFloat(a.dataset.price) - parseFloat(b.dataset.price);
                case 'price-high':
                    return parseFloat(b.dataset.price) - parseFloat(a.dataset.price);
                case 'name':
                    return a.dataset.name.localeCompare(b.dataset.name);
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

    document.addEventListener('DOMContentLoaded', function() {
        ShopState.init();
        
        PriceManager.init();
        SearchManager.init();
        
        if (ShopState.elements.sortSelect) {
            ShopState.elements.sortSelect.addEventListener('change', (e) => {
                SortManager.change(e.target.value);
            });
        }
        
        FilterManager.apply();
        
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