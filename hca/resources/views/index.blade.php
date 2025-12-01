<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hookcraft Avenue - HCA</title>
    <link rel="icon" href="{{ asset('asset/images/logo.jpg') }}" type="image/png">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('asset/styleshome.css') }}">
     <link rel="stylesheet" href="{{ asset('asset/stylesnav.css') }}">
      

</head>
<body>

@include('components.login_modal')
@include('components.signup_modal')

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
                <li class="nav-item"><a class="nav-link active" href="{{ route('home') }}">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('shop') }}">Shop</a></li>
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



<!-- Hero Section -->
<section class="hero">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="hero-content fade-in">
                    <h1>Elevate your moments with our handcrafted floral creations</h1>
                    <p>Celebrate beauty, one petal at a time.</p>
                    <a href="{{ route('shop') }}" class="btn btn-pink btn-lg">
                        <i class="bi bi-flower1 me-2"></i>Shop Now
                    </a>
                </div>
            </div>
            <div class="col-lg-6">
                <img src="{{ asset('asset/images/hero-image.png') }}" alt="Flower Hero" class="img-fluid">
            </div>
        </div>
    </div>
</section>

<!-- Categories Section -->
<section class="categories">
    <div class="container">
        <h2>Shop by Category</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="category-item">
                    <div class="position-relative overflow-hidden">
                        <img src="{{ asset('asset/images/birthday.jpg') }}" class="img-fluid" alt="Birthday Bouquets">
                        <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-end p-3" style="background: linear-gradient(transparent, rgba(0,0,0,0.3));">
                            <h3 class="text-white mb-0">Birthday Bouquets</h3>
                        </div>
                       
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="category-item">
                    <div class="position-relative overflow-hidden">
                        <img src="{{ asset('asset/images/casual.jpg') }}" class="img-fluid" alt="Casual Bouquets">
                        <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-end p-3" style="background: linear-gradient(transparent, rgba(0,0,0,0.3));">
                            <h3 class="text-white mb-0">Casual Bouquets</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="category-item">
                    <div class="position-relative overflow-hidden">
                        <img src="{{ asset('asset/images/tiny.jpg') }}" class="img-fluid" alt="Tiny Bouquets">
                        <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-end p-3" style="background: linear-gradient(transparent, rgba(0,0,0,0.3));">
                            <h3 class="text-white mb-0">Tiny Bouquets</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- About Section -->
<section class="about">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="about-text pe-lg-4">
                    <h2>About Us</h2>
                    <p >
                        At Hookcraft Avenue, we are passionate about delivering freshly picked flowers,
                        carefully handcrafted into beautiful arrangements that bring joy to every occasion.
                    </p>
                    <p>
                        Our skilled florists combine traditional techniques with modern creativity to create 
                        stunning bouquets that tell your unique story. Every flower is selected with care 
                        and arranged with love.
                    </p>
                    <div class="d-flex align-items-center mt-4">
                        <div class="me-4">
                            <i class="bi bi-flower1 fs-1 text-pink"></i>
                        </div>
                        <div>
                            <h5 class="mb-1">Fresh & Handcrafted</h5>
                            <p class="mb-0 text-muted">Every bouquet made with love and attention to detail</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="row g-3">
                    <div class="col-6">
                        <img src="{{ asset('asset/images/about-flower.jpg') }}" class="img-fluid w-100" style="height: 200px; object-fit: cover;">
                    </div>
                    <div class="col-6">
                        <img src="{{ asset('asset/images/about-flower1.jpg') }}" class="img-fluid w-100" style="height: 200px; object-fit: cover;">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Gallery Section -->
<section class="gallery">
    <div class="container">
        <h2>Our Beautiful Creations</h2>
        <div class="row g-3">
            @for($i=1;$i<=8;$i++)
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="gallery-item">
                    <img src="{{ asset("asset/images/gallery{$i}.jpg") }}" class="img-fluid" alt="Gallery Image">
                </div>
            </div>
            @endfor
        </div>
        <div class="text-center mt-4">
            <a href="{{ route('gallery') }}" class="btn btn-outline-secondary">
                <i class="bi bi-images me-2"></i>View Full Gallery
            </a>
        </div>
    </div>
</section>

<!-- Customize Section -->
<section class="customize text-center">
    <div class="container">
        <h2>Create Something Special</h2>
        <p class="lead text-white-50 mb-4">Design your perfect bouquet with our customization options</p>
        <div class="d-flex justify-content-center gap-4 my-4 flex-wrap">
            @for($i=1;$i<=3;$i++)
            <img src="{{ asset("asset/images/custom{$i}.png") }}" class="img-fluid" style="width:120px; height:120px; object-fit: cover;" alt="Custom Option">
            @endfor
        </div>
      <!-- Make sure this uses the correct product ID -->
<a href="{{ route('customization.create', ['id' => 1]) }}" class="btn btn-pink btn-lg">
    <i class="bi bi-palette me-2"></i>Customize Now
</a>
    </div>
</section>
<!-- Product Modal -->
<div class="modal fade product-modal" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="productModalLabel">Product Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <!-- Product Image -->
                    <div class="col-md-6">
                        <img id="modalProductImage" src="" alt="" class="img-fluid product-modal-image w-100">
                    </div>
                    
                    <!-- Product Details -->
                    <div class="col-md-6">
                        <h4 id="modalProductName" class="mb-3"></h4>
                        
                        <!-- Price -->
                        <div class="product-modal-price mb-3" id="modalProductPrice"></div>
                        
                        <!-- Category -->
                        <div class="mb-3">
                            <span class="badge bg-secondary" id="modalProductCategory"></span>
                        </div>
                        
                        <!-- Description -->
                        <div class="mb-3">
                            <h6><i class="fas fa-info-circle me-2"></i>Description</h6>
                            <p id="modalProductDescription" class="text-muted"></p>
                        </div>
                        
                        <!-- Stock Info -->
                        <div class="stock-info">
                            <div class="d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-boxes me-2"></i>Stock Available:</span>
                                <span id="modalProductStock" class="fw-bold"></span>
                            </div>
                        </div>
                        
                        <!-- Quantity Selector -->
                        <div class="mb-3">
                            <label class="form-label"><i class="fas fa-sort-numeric-up me-2"></i>Quantity</label>
                            <div class="quantity-input-group d-flex">
                                <button type="button" class="quantity-btn" onclick="changeQuantity(-1)">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <input type="number" class="form-control quantity-input" id="modalQuantity" value="1" min="1" readonly>
                                <button type="button" class="quantity-btn" onclick="changeQuantity(1)">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Add-ons Section -->
                        <div class="add-ons-section" id="addOnsSection">
                            <h6><i class="fas fa-plus-circle me-2"></i>Add-ons (Optional)</h6>
                            <div id="addOnsList">
                                <!-- Sample add-ons - replace with dynamic content -->
                                <div class="add-on-item" data-addon-id="1" data-addon-price="25" onclick="toggleAddOn(this)">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong>Gift Wrapping</strong>
                                            <small class="text-muted d-block">Beautiful gift wrap with ribbon</small>
                                        </div>
                                        <span class="text-primary fw-bold">+₱25</span>
                                    </div>
                                </div>
                                <div class="add-on-item" data-addon-id="2" data-addon-price="15" onclick="toggleAddOn(this)">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong>Express Shipping</strong>
                                            <small class="text-muted d-block">Delivered within 1-2 days</small>
                                        </div>
                                        <span class="text-primary fw-bold">+₱15</span>
                                    </div>
                                </div>
                                <div class="add-on-item" data-addon-id="3" data-addon-price="10" onclick="toggleAddOn(this)">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong>Care Instructions Card</strong>
                                            <small class="text-muted d-block">Detailed care guide</small>
                                        </div>
                                        <span class="text-primary fw-bold">+₱10</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Total Price -->
                        <div class="d-flex justify-content-between align-items-center mb-3 p-3 bg-light rounded">
                            <strong>Total Price:</strong>
                            <strong class="text-primary fs-5" id="modalTotalPrice">₱0.00</strong>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0">
                <div class="d-flex gap-2 w-100">
                    <button type="button" class="btn btn-primary flex-fill" id="modalAddToCart">
                        <i class="fas fa-cart-plus me-2"></i>Add to Cart
                    </button>
                    <button type="button" class="btn btn-buy-now flex-fill" id="modalBuyNow">
                        <i class="fas fa-bolt me-2"></i>Buy Now
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Footer -->
<footer class="footer py-4 text-center">
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
    // Add smooth scrolling for better UX
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            document.querySelector(this.getAttribute('href')).scrollIntoView({
                behavior: 'smooth'
            });
        });
    });

    // Add fade-in animation on scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('fade-in');
            }
        });
    }, observerOptions);

    document.querySelectorAll('section').forEach(section => {
        observer.observe(section);
    });
</script>
</body>
</html>