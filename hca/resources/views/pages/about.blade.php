<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Hookcraft Avenue</title>
    <link rel="icon" href="{{ asset('asset/images/logo.jpg') }}" type="image/png">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
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
                <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('shop') }}">Shop</a></li>
                <li class="nav-item"><a class="nav-link active" href="{{ route('about') }}">About</a></li>
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
                            {{ $cartCount ?? 0 }}
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
<!-- About Hero Section -->
<section class="hero" style="padding: 80px 0;">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="hero-content fade-in">
                    <h1>Welcome to Hookcraft Avenue</h1>
                    <p class="lead">Where artistry meets craftsmanship, creating timeless handcrafted artificial flower arrangements that last forever.</p>
                </div>
            </div>
            <div class="col-lg-6" style="padding: 20px">
                <img src="{{ asset('asset/images/about1.jpg') }}" alt="About Us" style="width:350px"class="img-fluid rounded shadow">
            </div>
        </div>
    </div>
</section>

<!-- Our Story Section -->
<section class="about py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="mb-3">Our Story</h2>
            <p class="text-muted lead">A journey of passion, creativity, and timeless beauty</p>
        </div>
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <div class="about-text pe-lg-4">
                    <p class="fs-5">
                        At Hookcraft Avenue, we specialize in creating beautifully handcrafted artificial flower arrangements 
                        that capture the elegance of nature while lasting forever.
                    </p>
                    <p>
                        Our skilled artisans meticulously craft each petal, stem, and leaf by hand, combining traditional 
                        techniques with modern creativity. Every arrangement is a unique work of art, designed to bring 
                        lasting joy and beauty to any space or occasion.
                    </p>
                    <p>
                        We believe that handcrafted artificial flowers offer the perfect blend of beauty and practicality. 
                        They never wilt, require no maintenance, and maintain their vibrant colors indefinitely – making 
                        them ideal for those who want everlasting beauty in their homes or as meaningful gifts.
                    </p>
                    <p>
                        Each piece is thoughtfully created with attention to detail, ensuring that every arrangement 
                        reflects our commitment to quality craftsmanship and artistic excellence.
                    </p>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="row g-3">
                    <div class="col-6">
                        <img src="{{ asset('asset/images/about-flower.jpg') }}" class="img-fluid w-100 rounded shadow" style="height: 250px; object-fit: cover;">
                    </div>
                    <div class="col-6">
                        <img src="{{ asset('asset/images/about-flower1.jpg') }}" class="img-fluid w-100 rounded shadow" style="height: 250px; object-fit: cover;">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Our Values Section -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="mb-3">Why Choose Handcrafted Artificial Flowers</h2>
            <p class="text-muted">The perfect blend of art and longevity</p>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="text-center p-4 h-100 bg-light rounded shadow-sm">
                    <div class="mb-3">
                        <i class="bi bi-infinity fs-1" style="color: #FFB6C1;"></i>
                    </div>
                    <h4 class="mb-3">Everlasting Beauty</h4>
                    <p class="text-muted">Our handcrafted arrangements maintain their vibrant colors and perfect shape forever, never wilting or fading.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="text-center p-4 h-100 bg-light rounded shadow-sm">
                    <div class="mb-3">
                        <i class="bi bi-hand-thumbs-up fs-1" style="color: #FFB6C1;"></i>
                    </div>
                    <h4 class="mb-3">Handcrafted Quality</h4>
                    <p class="text-muted">Each piece is meticulously crafted by hand, ensuring unique artistry and attention to every detail.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="text-center p-4 h-100 bg-light rounded shadow-sm">
                    <div class="mb-3">
                        <i class="bi bi-droplet-half fs-1" style="color: #FFB6C1;"></i>
                    </div>
                    <h4 class="mb-3">Zero Maintenance</h4>
                    <p class="text-muted">No watering, no sunlight needed – just lasting beauty that fits perfectly into your busy lifestyle.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Our Process Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="mb-3">Our Crafting Process</h2>
            <p class="text-muted">From concept to creation</p>
        </div>
        <div class="row g-4">
            <div class="col-md-3">
                <div class="text-center">
                    <div class="mb-3 d-flex justify-content-center align-items-center" style="width: 80px; height: 80px; background: #FFB6C1; border-radius: 50%; margin: 0 auto;">
                        <span class="fs-2 fw-bold text-white">1</span>
                    </div>
                    <h5 class="mb-2">Design</h5>
                    <p class="text-muted small">We conceptualize unique arrangements inspired by nature's beauty</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="text-center">
                    <div class="mb-3 d-flex justify-content-center align-items-center" style="width: 80px; height: 80px; background: #FFB6C1; border-radius: 50%; margin: 0 auto;">
                        <span class="fs-2 fw-bold text-white">2</span>
                    </div>
                    <h5 class="mb-2">Handcraft</h5>
                    <p class="text-muted small">Our artisans carefully shape and assemble each petal and stem by hand</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="text-center">
                    <div class="mb-3 d-flex justify-content-center align-items-center" style="width: 80px; height: 80px; background: #FFB6C1; border-radius: 50%; margin: 0 auto;">
                        <span class="fs-2 fw-bold text-white">3</span>
                    </div>
                    <h5 class="mb-2">Arrange</h5>
                    <p class="text-muted small">Each piece is artfully arranged to create a stunning, balanced composition</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="text-center">
                    <div class="mb-3 d-flex justify-content-center align-items-center" style="width: 80px; height: 80px; background: #FFB6C1; border-radius: 50%; margin: 0 auto;">
                        <span class="fs-2 fw-bold text-white">4</span>
                    </div>
                    <h5 class="mb-2">Deliver</h5>
                    <p class="text-muted small">Carefully packaged and delivered to bring lasting joy to your space</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Benefits Section -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="mb-3">Perfect For Every Occasion</h2>
            <p class="text-muted">Timeless gifts and decorations</p>
        </div>
        <div class="row g-4">
            <div class="col-md-6">
                <div class="d-flex align-items-start p-3 bg-light rounded">
                    <i class="bi bi-gift fs-3 me-3" style="color: #FFB6C1;"></i>
                    <div>
                        <h5>Thoughtful Gifts</h5>
                        <p class="text-muted mb-0">Give a gift that lasts forever – perfect for birthdays, anniversaries, and special occasions.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="d-flex align-items-start p-3 bg-light rounded">
                    <i class="bi bi-house-heart fs-3 me-3" style="color: #FFB6C1;"></i>
                    <div>
                        <h5>Home Decor</h5>
                        <p class="text-muted mb-0">Add permanent beauty to any room without the worry of wilting or maintenance.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="d-flex align-items-start p-3 bg-light rounded">
                    <i class="bi bi-building fs-3 me-3" style="color: #FFB6C1;"></i>
                    <div>
                        <h5>Office & Business</h5>
                        <p class="text-muted mb-0">Enhance professional spaces with elegant, low-maintenance floral displays.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="d-flex align-items-start p-3 bg-light rounded">
                    <i class="bi bi-camera fs-3 me-3" style="color: #FFB6C1;"></i>
                    <div>
                        <h5>Photo Props</h5>
                        <p class="text-muted mb-0">Perfect for photography, weddings, and events – always camera-ready.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Gallery Preview Section -->
<section class="gallery py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-5">Our Handcrafted Creations</h2>
        <div class="row g-3">
            @for($i=1;$i<=8;$i++)
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="gallery-item">
                    <img src="{{ asset("asset/images/gallery{$i}.jpg") }}" class="img-fluid rounded shadow" alt="Gallery Image">
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

<!-- Call to Action Section -->
<section class="customize text-center py-5">
    <div class="container">
        <h2 class="text-white mb-3">Ready to Create Something Timeless?</h2>
        <p class="lead text-white-50 mb-4">Explore our handcrafted collection or design your perfect custom arrangement</p>
        <div class="d-flex justify-content-center gap-3">
            <a href="{{ route('shop') }}" class="btn btn-pink btn-lg">
                <i class="bi bi-flower1 me-2"></i>Browse Shop
            </a>
            <a href="{{ route('customization.create', ['id' => 1]) }}" class="btn btn-outline-light btn-lg">
                <i class="bi bi-palette me-2"></i>Customize Now
            </a>
        </div>
    </div>
</section>

<!-- Footer -->
@include('components.footer')

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script>
    // Add smooth scrolling
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth'
                });
            }
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