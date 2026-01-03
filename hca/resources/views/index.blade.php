<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hookcraft Avenue - HCA</title>
    <link rel="icon" href="{{ asset('asset/images/logo.jpg') }}" type="image/png">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('asset/stylesnav.css') }}">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Inter:wght@300;400;500;600&display=swap');

        :root {
            --pastel-pink: #ffb3d9;
            --pastel-rose: #c2185b;
            --pastel-light: #d4a5b8;
            --pastel-bg: #fce4ec;
            --pastel-white: #ffffff;
            --text-primary: #2d2d2d;
            --text-secondary: #4a4a4a;
            --shadow-soft: 0 10px 40px rgba(194, 24, 91, 0.15);
            --shadow-medium: 0 15px 50px rgba(194, 24, 91, 0.2);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--pastel-bg);
            color: var(--text-primary);
            line-height: 1.7;
            overflow-x: hidden;
            font-size: 18px;
        }

        /* Hero Section - New Split Design */
        .hero {
            min-height: 85vh;
            display: flex;
            align-items: center;
            /* padding: 80px 0 60px; */
            /* margin-top: px; */
            background: linear-gradient(135deg, var(--pastel-pink) 0%, var(--pastel-white) 100%);
            position: relative;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 50%;
            height: 100%;
            background: var(--pastel-pink);
            opacity: 0.2;
            clip-path: polygon(20% 0, 100% 0, 100% 100%, 0% 100%);
        }

        .hero-content {
            position: relative;
            z-index: 1.5;
        }

        .hero-badge {
            display: inline-block;
            background: rgba(42, 0, 54, 0.15);
            color: var(--text-primary);
            padding: 0.6rem 1.5rem;
            border-radius: 50px;
            font-size: 1rem;
            font-weight: 600;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            margin-bottom: 2rem;
        }

        .hero h1 {
            font-family: 'Playfair Display', serif;
            font-size: 4.5rem;
            font-weight: 700;
            line-height: 1.2;
            color: var(--text-primary);
            margin-bottom: 1.5rem;
            letter-spacing: -1px;
        }

        .hero p {
            font-size: 1.4rem;
            color: var(--text-secondary);
            margin-bottom: 2.5rem;
            max-width: 500px;
            font-weight: 400;
        }

        .btn-primary-custom {
            background: linear-gradient(135deg, var(--pastel-rose), var(--pastel-rose));
            color: white;
            padding: 1.2rem 3.5rem;
            border-radius: 50px;
            border: none;
            font-weight: 600;
            font-size: 1.15rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(233, 162, 190, 0.3);
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary-custom:hover {
            transform: translateY(-4px);
            box-shadow: 0 6px 25px rgba(75, 71, 73, 0.4);
            color: white;
        }

        .hero-image-wrapper {
            position: relative;
            z-index: 1.5;
        }

        .hero-image-wrapper img {
            width: 80%;
            height: auto;
            /* border-radius: 30px; */
            /* box-shadow: var(--shadow-medium); */
        }

        /* Categories Section - Card Grid */
        .categories {
            padding: 100px 0;
            background: var(--pastel-white);
        }

        .section-header {
            text-align: center;
            margin-bottom: 4rem;
        }

        .section-label {
            display: inline-block;
            color: var(--pastel-rose);
            font-size: 1.05rem;
            font-weight: 600;
            letter-spacing: 2.5px;
            text-transform: uppercase;
            margin-bottom: 1rem;
        }

        .section-title {
            font-family: 'Playfair Display', serif;
            font-size: 3.5rem;
            color: var(--text-primary);
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .section-subtitle {
            color: var(--text-secondary);
            font-size: 1.3rem;
            max-width: 600px;
            margin: 0 auto;
        }

        .category-card {
            position: relative;
            border-radius: 25px;
            overflow: hidden;
            height: 400px;
            cursor: pointer;
            box-shadow: var(--shadow-soft);
            transition: all 0.4s ease;
        }

        .category-card:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow-medium);
        }

        .category-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.4s ease;
        }

        .category-card:hover img {
            transform: scale(1.08);
        }

        .category-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);
            padding: 2.5rem 2rem;
            transition: all 0.3s ease;
        }

        .category-card:hover .category-overlay {
            padding-bottom: 3rem;
        }

        .category-overlay h3 {
            color: white;
            font-size: 2rem;
            font-weight: 600;
            margin: 0;
        }

        /* About Section - Two Column Layout */
        .about {
            padding: 100px 0;
            background: var(--pastel-bg);
        }

        .about-content {
            padding-right: 3rem;
        }

        .about-content h2 {
            font-family: 'Playfair Display', serif;
            font-size: 3.5rem;
            color: var(--text-primary);
            margin-bottom: 1.5rem;
            font-weight: 700;
        }

        .about-content p {
            color: var(--text-secondary);
            font-size: 1.25rem;
            line-height: 1.9;
            margin-bottom: 1.5rem;
        }

        .feature-box {
            background: var(--pastel-white);
            padding: 2rem;
            border-radius: 20px;
            border-left: 4px solid var(--pastel-rose);
            box-shadow: var(--shadow-soft);
            margin-top: 2rem;
            transition: all 0.3s ease;
        }

        .feature-box:hover {
            transform: translateX(10px);
        }

        .feature-box i {
            font-size: 2.5rem;
            color: var(--pastel-rose);
            margin-bottom: 1rem;
        }

        .feature-box h5 {
            color: var(--text-primary);
            font-weight: 600;
            margin-bottom: 0.5rem;
            font-size: 1.25rem;
        }

        .feature-box p {
            color: var(--text-secondary);
            margin: 0;
            font-size: 1.1rem;
        }

        .about-images {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
        }

        .about-images img {
            width: 100%;
            height: 280px;
            object-fit: cover;
            border-radius: 20px;
            box-shadow: var(--shadow-soft);
        }

        /* Gallery Section - Masonry Grid */
        .gallery {
            padding: 100px 0;
            background: var(--pastel-white);
        }

        .gallery-grid {
            column-count: 4;
            column-gap: 1rem;
            margin-top: 3rem;
        }

        .gallery-item {
            break-inside: avoid;
            margin-bottom: 1rem;
            position: relative;
            border-radius: 15px;
            overflow: hidden;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .gallery-item:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-medium);
        }

        .gallery-item img {
            width: 100%;
            height: auto;
            display: block;
            border-radius: 15px;
        }

        .btn-outline-custom {
            border: 2px solid var(--pastel-rose);
            color: var(--pastel-rose);
            padding: 1rem 3rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1.15rem;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn-outline-custom:hover {
            background: var(--pastel-rose);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(194, 24, 91, 0.3);
        }

        /* Customize Section */
        .customize {
            padding: 100px 0;
            background: linear-gradient(135deg, var(--pastel-rose), var(--pastel-pink));
            position: relative;
            overflow: hidden;
        }

        .customize::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(255,255,255,0.2) 0%, transparent 70%);
            border-radius: 50%;
        }

        .customize-content {
            position: relative;
            z-index: 2;
            text-align: center;
        }

        .customize h2 {
            font-family: 'Playfair Display', serif;
            font-size: 3.5rem;
            color: white;
            margin-bottom: 1rem;
            font-weight: 700;
        }

        .customize p {
            color: rgba(255,255,255,0.95);
            font-size: 1.35rem;
            margin-bottom: 3rem;
        }

        .custom-options {
            display: flex;
            justify-content: center;
            gap: 2rem;
            margin-bottom: 3rem;
            flex-wrap: wrap;
        }

        .custom-option {
            width: 140px;
            height: 140px;
            border-radius: 50%;
            overflow: hidden;
            border: 4px solid white;
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
            transition: all 0.3s ease;
        }

        .custom-option:hover {
            transform: scale(1.1) rotate(5deg);
        }

        .custom-option img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .btn-white-custom {
            background: white;
            color: var(--pastel-rose);
            padding: 1.2rem 3.5rem;
            border-radius: 50px;
            border: none;
            font-weight: 600;
            font-size: 1.15rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            text-decoration: none;
            display: inline-block;
        }

        .btn-white-custom:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 25px rgba(0,0,0,0.3);
            color: var(--pastel-rose);
        }

        /* Chatbot */
        .chatbot-float {
            position: fixed;
            bottom: 40px;
            right: 40px;
            z-index: 1000;
        }

        .chatbot-btn {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, var(--pastel-pink), var(--pastel-rose));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2rem;
            text-decoration: none;
            box-shadow: 0 8px 30px rgba(194, 24, 91, 0.3);
            transition: all 0.3s ease;
        }

        .chatbot-btn:hover {
            transform: scale(1.1) rotate(10deg);
            box-shadow: 0 10px 40px rgba(194, 24, 91, 0.4);
            color: white;
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in {
            animation: fadeInUp 0.8s ease forwards;
        }

        /* Responsive */
        @media (max-width: 1200px) {
            .gallery-grid {
                column-count: 3;
            }
        }

        @media (max-width: 992px) {
            .hero h1 {
                font-size: 3.5rem;
            }

            .section-title {
                font-size: 3rem;
            }

            .about-content h2 {
                font-size: 3rem;
            }

            .about-content {
                padding-right: 0;
                margin-bottom: 3rem;
            }

            .gallery-grid {
                column-count: 2;
            }
        }

        @media (max-width: 768px) {
            body {
                font-size: 16px;
            }

            .hero {
                padding: 100px 0 60px;
                min-height: auto;
            }

            .hero h1 {
                font-size: 2.8rem;
            }

            .hero p {
                font-size: 1.2rem;
            }

            .section-title {
                font-size: 2.5rem;
            }

            .about-content h2 {
                font-size: 2.5rem;
            }

            .customize h2 {
                font-size: 2.8rem;
            }

            .gallery-grid {
                column-count: 1;
            }

            .category-card {
                height: 300px;
            }

            .chatbot-float {
                bottom: 25px;
                right: 25px;
            }

            .chatbot-btn {
                width: 60px;
                height: 60px;
                font-size: 1.7rem;
            }
        }
    </style>
</head>
<body>

@include('components.login_modal')
@include('components.signup_modal')
@include('components.navbar')

<!-- Chatbot Button -->
<div class="chatbot-float">
    <a href="{{ route('chatbot') }}" class="chatbot-btn" title="Chat with AI Assistant">
        <i class="bi bi-robot"></i>
    </a>
</div>

<!-- Hero Section -->
<section class="hero">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="hero-content">
                    <span class="hero-badge">Handcrafted with Love</span>
                    <h1>Elevate Your Moments</h1>
                    <p>Celebrate beauty, one petal at a time with our handcrafted floral creations</p>
                    <a href="{{ route('shop') }}" class="btn-primary-custom">
                        <i class="bi bi-flower1 me-2"></i>Explore Collection
                    </a>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="hero-image-wrapper">
                    <img src="{{ asset('asset/images/hero-image.png') }}" alt="Flower Hero">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Categories Section -->
<section class="categories">
    <div class="container">
        <div class="section-header">
            <span class="section-label">Our Collections</span>
            <h2 class="section-title">Shop by Category</h2>
            <p class="section-subtitle">Discover our carefully curated collections for every occasion</p>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="category-card">
                    <img src="{{ asset('asset/images/birthday.jpg') }}" alt="Birthday Bouquets">
                    <div class="category-overlay">
                        <h3>Birthday Bouquets</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="category-card">
                    <img src="{{ asset('asset/images/casual.jpg') }}" alt="Casual Bouquets">
                    <div class="category-overlay">
                        <h3>Casual Bouquets</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="category-card">
                    <img src="{{ asset('asset/images/tiny.jpg') }}" alt="Tiny Bouquets">
                    <div class="category-overlay">
                        <h3>Tiny Bouquets</h3>
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
                <div class="about-content">
                    <span class="section-label">Our Story</span>
                    <h2>About Hookcraft Avenue</h2>
                    <p>
                        At Hookcraft Avenue, we are passionate about delivering freshly picked flowers,
                        carefully handcrafted into beautiful arrangements that bring joy to every occasion.
                    </p>
                    <p>
                        Our skilled florists combine traditional techniques with modern creativity to create 
                        stunning bouquets that tell your unique story. Every flower is selected with care 
                        and arranged with love.
                    </p>
                    <div class="feature-box">
                        <i class="bi bi-flower1"></i>
                        <h5>Fresh & Handcrafted</h5>
                        <p>Every bouquet made with love and attention to detail</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="about-images">
                    <img src="{{ asset('asset/images/about-flower.jpg') }}" alt="About Flower 1">
                    <img src="{{ asset('asset/images/about-flower1.jpg') }}" alt="About Flower 2">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Gallery Section -->
<section class="gallery">
    <div class="container">
        <div class="section-header">
            <span class="section-label">Inspiration</span>
            <h2 class="section-title">Our Beautiful Creations</h2>
            <p class="section-subtitle">A glimpse into our handcrafted masterpieces</p>
        </div>
        <div class="gallery-grid">
            @for($i=1;$i<=8;$i++)
            <div class="gallery-item">
                <img src="{{ asset("asset/images/gallery{$i}.jpg") }}" alt="Gallery Image">
            </div>
            @endfor
        </div>
        <div class="text-center mt-5">
            <a href="{{ route('gallery') }}" class="btn-outline-custom">
                <i class="bi bi-images me-2"></i>View Full Gallery
            </a>
        </div>
    </div>
</section>

<!-- Customize Section -->
<section class="customize">
    <div class="container">
        <div class="customize-content">
            <h2>Create Something Special</h2>
            <p>Design your perfect bouquet with our customization options</p>
            <div class="custom-options">
                @for($i=1;$i<=3;$i++)
                <div class="custom-option">
                    <img src="{{ asset("asset/images/custom{$i}.png") }}" alt="Custom Option">
                </div>
                @endfor
            </div>
            <a href="{{ route('customization.create', ['id' => 1]) }}" class="btn-white-custom">
                <i class="bi bi-palette me-2"></i>Start Customizing
            </a>
        </div>
    </div>
</section>

@include('components.footer')

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script>
    // Smooth scrolling
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

    // Fade-in animation on scroll
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