<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Hookcraft Avenue</title>
    <link rel="icon" href="{{ asset('asset/images/logo.jpg') }}" type="image/png">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('asset/styleshome.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/stylesnav.css') }}">
    <style>
        .contact-hero {
            background: linear-gradient(135deg, #a67c8a 0%, #8b6b7a 100%);
            padding: 80px 0 60px;
            margin-top: 76px;
        }
        .contact-hero h1 {
            color: #fff;
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 1rem;
            text-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }
        .contact-hero p {
            color: rgba(255,255,255,0.9);
            font-size: 1.2rem;
            text-shadow: 0 1px 2px rgba(0,0,0,0.2);
        }
        .contact-card {
            background: #fff;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            margin-bottom: 2rem;
            transition: transform 0.3s ease;
            border: 1px solid #f0f0f0;
        }
        .contact-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.12);
        }
        .contact-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #b8909f 0%, #a67c8a 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
        }
        .contact-icon i {
            font-size: 1.8rem;
            color: #fff;
        }
        .form-control:focus {
            border-color: #b8909f;
            box-shadow: 0 0 0 0.2rem rgba(184, 144, 159, 0.2);
        }
        .btn-submit {
            background: linear-gradient(135deg, #b8909f 0%, #a67c8a 100%);
            border: none;
            color: #fff;
            padding: 12px 40px;
            border-radius: 25px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(184, 144, 159, 0.3);
            color: #fff;
        }
        .map-container {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            margin-bottom: 2rem;
            border: 1px solid #f0f0f0;
        }
        .map-container iframe {
            width: 100%;
            height: 450px;
            border: 0;
        }
        .social-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, #b8909f 0%, #a67c8a 100%);
            border-radius: 50%;
            color: #fff;
            text-decoration: none;
            margin: 0 8px;
            transition: all 0.3s ease;
        }
        .social-link:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(184, 144, 159, 0.3);
            color: #fff;
        }
        .info-item {
            display: flex;
            align-items: start;
            margin-bottom: 1.5rem;
        }
        .info-item i {
            font-size: 1.5rem;
            color: #b8909f;
            margin-right: 1rem;
            margin-top: 0.2rem;
        }
        .section-title {
            font-size: 2rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 2rem;
            position: relative;
            padding-bottom: 1rem;
        }
        .section-title:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 60px;
            height: 3px;
            background: linear-gradient(135deg, #b8909f 0%, #a67c8a 100%);
        }
        .fb-page-container {
            padding: 1rem;
        }
        .fb-page-container iframe {
            border-radius: 10px;
        }
    </style>
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
                <li class="nav-item"><a class="nav-link" href="{{ route('about') }}">About</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('gallery') }}">Gallery</a></li>
                <li class="nav-item"><a class="nav-link active" href="{{ route('contact') }}">Contact</a></li>
            </ul>

            @php
                $isLoggedIn = Auth::check();
                $user = Auth::user();
            @endphp

            <ul class="navbar-nav flex-row align-items-center">
                <li class="nav-item me-3">
                    <a class="nav-link position-relative" href="{{ $isLoggedIn ? route('cart.index') : '#' }}">
                        <i class="bi bi-cart fs-5"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger cart-badge">
                            {{ $cartCount ?? 0 }}
                        </span>
                    </a>
                </li>

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

<!-- Hero Section -->
<section class="contact-hero text-center">
    <div class="container">
        <h1>Get In Touch</h1>
        <p>Elevate your moments with our handcrafted floral creations 🌸<br>
        Celebrate beauty, one petal at a time 🌺</p>
    </div>
</section>

<!-- Contact Information Section -->
<section class="py-5">
    <div class="container">
        <div class="row g-4 mb-5">
            <!-- Phone Card -->
            <div class="col-md-4">
                <div class="contact-card text-center">
                    <div class="contact-icon mx-auto">
                        <i class="bi bi-telephone"></i>
                    </div>
                    <h5 class="mb-3">Call Us</h5>
                    <p class="text-muted mb-2">We're available to answer your questions</p>
                    <a href="tel:09055569763" class="text-decoration-none fw-bold" style="color: #a67c8a;">0905 556 9763</a>
                </div>
            </div>

            <!-- Location Card -->
            <div class="col-md-4">
                <div class="contact-card text-center">
                    <div class="contact-icon mx-auto">
                        <i class="bi bi-geo-alt"></i>
                    </div>
                    <h5 class="mb-3">Visit Us</h5>
                    <p class="text-muted mb-2">Come see our beautiful arrangements</p>
                    <p class="fw-bold mb-0" style="color: #a67c8a;">Cebu City, Philippines</p>
                    <small class="text-muted">Saint Jude Hipodromo</small>
                </div>
            </div>

            <!-- Hours Card -->
            <div class="col-md-4">
                <div class="contact-card text-center">
                    <div class="contact-icon mx-auto">
                        <i class="bi bi-clock"></i>
                    </div>
                    <h5 class="mb-3">Business Hours</h5>
                    <p class="text-muted mb-2">Always open to serve you</p>
                    <p class="fw-bold mb-0" style="color: #a67c8a;">24/7</p>
                    <small class="text-muted">Open All Days</small>
                </div>
            </div>
        </div>

        <!-- Map Section -->
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <h2 class="section-title text-center">Find Us Here</h2>
                <div class="map-container">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d505.04868866433014!2d123.90669385320574!3d10.312446158188536!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33a999828a212d3b%3A0x80ecadf9173d97d3!2sSaint%20jude%20Hipodromo!5e0!3m2!1sen!2sph!4v1761275217741!5m2!1sen!2sph" 
                            allowfullscreen="" 
                            loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>

                <!-- Social Media Links -->
                <div class="mt-4 text-center">
                    <h5 class="mb-3">Follow Us</h5>
                    <div class="d-flex align-items-center justify-content-center">
                        <a href="https://www.instagram.com/hookcraft_avenue?utm_medium=copy_link" target="_blank" class="social-link" title="Instagram">
                            <i class="bi bi-instagram"></i>
                        </a>
                        <a href="https://tiktok.com/@hookcraftavenue" target="_blank" class="social-link" title="TikTok">
                            <i class="bi bi-tiktok"></i>
                        </a>
                        <a href="https://www.facebook.com/hookcraft.avenue" target="_blank" class="social-link" title="Facebook">
                            <i class="bi bi-facebook"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Facebook Page Embed -->
        <div class="row justify-content-center mt-5">
            <div class="col-lg-8">
                <h2 class="section-title text-center">Connect With Us on Facebook</h2>
                <div class="contact-card">
                    <div class="fb-page-container text-center">
                        <iframe src="https://www.facebook.com/plugins/page.php?href=https%3A%2F%2Fwww.facebook.com%2FCrochetbyAlys&tabs=timeline&width=500&height=600&small_header=false&adapt_container_width=true&hide_cover=false&show_facepile=true&appId" 
                                width="500" 
                                height="600" 
                                style="border:none;overflow:hidden;max-width:100%;" 
                                scrolling="no" 
                                frameborder="0" 
                                allowfullscreen="true" 
                                allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share">
                        </iframe>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Info -->
        <div class="row mt-5">
            <div class="col-lg-6 mx-auto">
                <div class="contact-card">
                    <h5 class="mb-4 text-center">Quick Information</h5>
                    <div class="info-item">
                        <i class="bi bi-info-circle"></i>
                        <div>
                            <strong>Business Type</strong>
                            <p class="mb-0 text-muted">Arts & Crafts Store</p>
                        </div>
                    </div>
                    <div class="info-item">
                        <i class="bi bi-star"></i>
                        <div>
                            <strong>Rating</strong>
                            <p class="mb-0 text-muted">Not yet rated (3 Reviews)</p>
                        </div>
                    </div>
                    <div class="info-item mb-0">
                        <i class="bi bi-people"></i>
                        <div>
                            <strong>Instagram</strong>
                            <p class="mb-0 text-muted">1.6K followers - Confirmed link</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="footer py-4 text-center">
    <div class="container">
        <div class="row">
            <div class="col-md-6 text-md-start">
                <p class="mb-0">&copy; 2025 Hookcraft Avenue. All rights reserved.</p>
            </div>
            <div class="col-md-6 text-md-end">
                <div class="social-links">
                    <a href="https://www.facebook.com/hookcraft.avenue" target="_blank" class="text-white me-3"><i class="bi bi-facebook"></i></a>
                    <a href="https://www.instagram.com/hookcraft_avenue?utm_medium=copy_link" target="_blank" class="text-white me-3"><i class="bi bi-instagram"></i></a>
                    <a href="https://tiktok.com/@hookcraftavenue" target="_blank" class="text-white"><i class="bi bi-tiktok"></i></a>
                </div>
            </div>
        </div>
    </div>
</footer>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>