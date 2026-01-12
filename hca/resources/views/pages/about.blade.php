<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Hookcraft Avenue</title>
    <link rel="icon" href="{{ asset('asset/images/logo.jpg') }}" type="image/png">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('asset/stylesnav.css') }}">
   <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Inter:wght@300;400;500;600&display=swap');

        :root {
            --pastel-pink: #ffb3d9;
            --pastel-rose: #c2185b;
            --pastel-light: #fce4ec;
            --pastel-bg: #fff0f5;
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

        /* Hero Section */
        .about-hero {
            padding: 80px 0 50px;
            margin-top: 5px;
            background: var(--pastel-white);
            position: relative;
        }

        .hero-content-wrapper {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 3rem;
            align-items: center;
        }

        .hero-text h1 {
            font-family: 'Playfair Display', serif;
            font-size: 3.5rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 1rem;
            line-height: 1.2;
        }

        .hero-text p {
            font-size: 1.3rem;
            color: var(--text-secondary);
            line-height: 1.8;
        }

        .hero-image {
            position: relative;
        }

        .hero-image img {
            width: 100%;
            height: 450px;
            object-fit: cover;
            border-radius: 25px;
            box-shadow: var(--shadow-medium);
        }

        /* Story Section */
        .story-section {
            padding: 50px 0;
            background: var(--pastel-bg);
        }

        .section-header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .section-label {
            display: inline-block;
            color: var(--pastel-rose);
            font-size: 1rem;
            font-weight: 600;
            letter-spacing: 2.5px;
            text-transform: uppercase;
            margin-bottom: 0.5rem;
        }

        .section-title {
            font-family: 'Playfair Display', serif;
            font-size: 3rem;
            color: var(--text-primary);
            font-weight: 700;
            margin-bottom: 0.75rem;
        }

        .section-subtitle {
            color: var(--text-secondary);
            font-size: 1.25rem;
        }

        .story-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 3rem;
            align-items: start;
        }

        .story-text p {
            color: var(--text-secondary);
            font-size: 1.2rem;
            line-height: 1.8;
            margin-bottom: 1.25rem;
        }

        .story-images {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        .story-images img {
            width: 100%;
            height: 240px;
            object-fit: cover;
            border-radius: 18px;
            box-shadow: var(--shadow-soft);
        }

        /* Values Cards */
        .values-section {
            padding: 50px 0;
            background: var(--pastel-white);
        }

        .values-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2rem;
            margin-top: 2rem;
        }

        .value-card {
            background: var(--pastel-light);
            padding: 2.25rem;
            border-radius: 20px;
            text-align: center;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .value-card:hover {
            transform: translateY(-8px);
            border-color: var(--pastel-rose);
            box-shadow: var(--shadow-medium);
        }

        .value-icon {
            width: 85px;
            height: 85px;
            background: linear-gradient(135deg, var(--pastel-pink), var(--pastel-rose));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
        }

        .value-icon i {
            font-size: 2.5rem;
            color: white;
        }

        .value-card h4 {
            font-size: 1.5rem;
            color: var(--text-primary);
            margin-bottom: 0.75rem;
            font-weight: 600;
        }

        .value-card p {
            color: var(--text-secondary);
            font-size: 1.1rem;
            margin: 0;
            line-height: 1.6;
        }

        /* Process Timeline */
        .process-section {
            padding: 50px 0;
            background: var(--pastel-bg);
        }

        .process-timeline {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 2rem;
            margin-top: 2rem;
            position: relative;
        }

        .process-timeline::before {
            content: '';
            position: absolute;
            top: 40px;
            left: 12.5%;
            right: 12.5%;
            height: 3px;
            background: linear-gradient(90deg, var(--pastel-rose), var(--pastel-pink));
            z-index: 0;
        }

        .process-step {
            text-align: center;
            position: relative;
            z-index: 1;
        }

        .process-number {
            width: 85px;
            height: 85px;
            background: linear-gradient(135deg, var(--pastel-pink), var(--pastel-rose));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.25rem;
            font-size: 2.25rem;
            font-weight: 700;
            color: white;
            box-shadow: 0 6px 20px rgba(194, 24, 91, 0.3);
            border: 4px solid var(--pastel-white);
        }

        .process-step h5 {
            font-size: 1.3rem;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
            font-weight: 600;
        }

        .process-step p {
            color: var(--text-secondary);
            font-size: 1.05rem;
            margin: 0;
        }

        /* Benefits Grid */
        .benefits-section {
            padding: 50px 0;
            background: var(--pastel-white);
        }

        .benefits-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
            margin-top: 2rem;
        }

        .benefit-card {
            display: flex;
            gap: 1.25rem;
            padding: 2rem;
            background: var(--pastel-light);
            border-radius: 18px;
            border-left: 4px solid var(--pastel-rose);
            transition: all 0.3s ease;
        }

        .benefit-card:hover {
            transform: translateX(8px);
            box-shadow: var(--shadow-soft);
        }

        .benefit-icon {
            flex-shrink: 0;
            width: 65px;
            height: 65px;
            background: linear-gradient(135deg, var(--pastel-pink), var(--pastel-rose));
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .benefit-icon i {
            font-size: 2rem;
            color: white;
        }

        .benefit-content h5 {
            font-size: 1.3rem;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
            font-weight: 600;
        }

        .benefit-content p {
            color: var(--text-secondary);
            font-size: 1.1rem;
            margin: 0;
            line-height: 1.6;
        }

        /* Gallery Preview */
        .gallery-preview {
            padding: 50px 0;
            background: var(--pastel-bg);
        }

        .gallery-masonry {
            column-count: 4;
            column-gap: 1rem;
            margin-top: 2rem;
        }

        .gallery-item {
            break-inside: avoid;
            margin-bottom: 1rem;
        }

        .gallery-item img {
            width: 100%;
            height: auto;
            border-radius: 15px;
            box-shadow: var(--shadow-soft);
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .gallery-item img:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-medium);
        }

        /* CTA Section */
        .cta-section {
            padding: 50px 0;
            background: linear-gradient(135deg, var(--pastel-rose), var(--pastel-pink));
            position: relative;
            overflow: hidden;
        }

        .cta-section::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(255,255,255,0.2) 0%, transparent 70%);
            border-radius: 50%;
        }

        .cta-content {
            text-align: center;
            position: relative;
            z-index: 2;
        }

        .cta-content h2 {
            font-family: 'Playfair Display', serif;
            font-size: 3rem;
            color: white;
            margin-bottom: 0.75rem;
            font-weight: 700;
        }

        .cta-content p {
            color: rgba(255,255,255,0.95);
            font-size: 1.3rem;
            margin-bottom: 2rem;
        }

        .cta-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn-white {
            background: white;
            color: var(--pastel-rose);
            padding: 1rem 3rem;
            border-radius: 50px;
            border: none;
            font-weight: 600;
            font-size: 1.15rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
            text-decoration: none;
            display: inline-block;
        }

        .btn-white:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 25px rgba(0,0,0,0.25);
            color: var(--pastel-rose);
        }

        .btn-outline-white {
            background: transparent;
            color: white;
            padding: 1rem 3rem;
            border-radius: 50px;
            border: 2px solid white;
            font-weight: 600;
            font-size: 1.15rem;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn-outline-white:hover {
            background: white;
            color: var(--pastel-rose);
            transform: translateY(-3px);
        }

        .btn-view-gallery {
            border: 2px solid var(--pastel-rose);
            color: var(--pastel-rose);
            padding: 0.875rem 2.75rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn-view-gallery:hover {
            background: var(--pastel-rose);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(194, 24, 91, 0.3);
        }

        /* Chatbot */
        .chatbot-float {
            position: fixed;
            bottom: 30px;
            right: 30px;
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
            box-shadow: 0 6px 25px rgba(194, 24, 91, 0.3);
            transition: all 0.3s ease;
        }

        .chatbot-btn:hover {
            transform: scale(1.1) rotate(10deg);
            box-shadow: 0 8px 35px rgba(194, 24, 91, 0.4);
            color: white;
        }

        /* Responsive */
        @media (max-width: 1200px) {
            .gallery-masonry {
                column-count: 3;
            }
        }

        @media (max-width: 992px) {
            .hero-content-wrapper,
            .story-grid {
                grid-template-columns: 1fr;
                gap: 2rem;
            }

            .values-grid {
                grid-template-columns: 1fr;
            }

            .process-timeline {
                grid-template-columns: repeat(2, 1fr);
            }

            .process-timeline::before {
                display: none;
            }

            .gallery-masonry {
                column-count: 2;
            }

            .hero-text h1 {
                font-size: 3rem;
            }

            .section-title {
                font-size: 2.5rem;
            }
        }

        @media (max-width: 768px) {
            body {
                font-size: 16px;
            }

            .about-hero {
                padding: 60px 0 40px;
            }

            .hero-text h1 {
                font-size: 2.5rem;
            }

            .hero-text p {
                font-size: 1.15rem;
            }

            .section-title {
                font-size: 2.25rem;
            }

            .section-subtitle {
                font-size: 1.1rem;
            }

            .hero-image img {
                height: 350px;
            }

            .process-timeline {
                grid-template-columns: 1fr;
            }

            .benefits-grid {
                grid-template-columns: 1fr;
            }

            .gallery-masonry {
                column-count: 1;
            }

            .cta-content h2 {
                font-size: 2.5rem;
            }

            .cta-content p {
                font-size: 1.15rem;
            }

            .cta-buttons {
                flex-direction: column;
                align-items: stretch;
            }

            .chatbot-float {
                bottom: 20px;
                right: 20px;
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
<section class="about-hero">
    <div class="container">
        <div class="hero-content-wrapper">
            <div class="hero-text">
                <h1>Welcome to Hookcraft Avenue</h1>
                <p>Where artistry meets craftsmanship, creating timeless handcrafted artificial flower arrangements that last forever. Each piece is meticulously crafted by hand, bringing everlasting beauty to your space.</p>
            </div>
            <div class="hero-image">
                <img src="{{ asset('asset/images/about1.jpg') }}" alt="About Hookcraft Avenue">
            </div>
        </div>
    </div>
</section>

<!-- Story Section -->
<section class="story-section">
    <div class="container">
        <div class="section-header">
            <span class="section-label">Our Journey</span>
            <h2 class="section-title">Our Story</h2>
            <p class="section-subtitle">A journey of passion, creativity, and timeless beauty</p>
        </div>
        <div class="story-grid">
            <div class="story-text">
                <p>
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
                    They never wilt, require no maintenance, and maintain their vibrant colors indefinitely.
                </p>
            </div>
            <div class="story-images">
                <img src="{{ asset('asset/images/about-flower.jpg') }}" alt="Handcrafted Flowers">
                <img src="{{ asset('asset/images/about-flower1.jpg') }}" alt="Artisan Work">
            </div>
        </div>
    </div>
</section>

<!-- Values Section -->
<section class="values-section">
    <div class="container">
        <div class="section-header">
            <span class="section-label">Our Values</span>
            <h2 class="section-title">Why Choose Handcrafted Artificial Flowers</h2>
            <p class="section-subtitle">The perfect blend of art and longevity</p>
        </div>
        <div class="values-grid">
            <div class="value-card">
                <div class="value-icon">
                    <i class="bi bi-infinity"></i>
                </div>
                <h4>Everlasting Beauty</h4>
                <p>Our handcrafted arrangements maintain their vibrant colors and perfect shape forever, never wilting or fading.</p>
            </div>
            <div class="value-card">
                <div class="value-icon">
                    <i class="bi bi-hand-thumbs-up"></i>
                </div>
                <h4>Handcrafted Quality</h4>
                <p>Each piece is meticulously crafted by hand, ensuring unique artistry and attention to every detail.</p>
            </div>
            <div class="value-card">
                <div class="value-icon">
                    <i class="bi bi-droplet-half"></i>
                </div>
                <h4>Zero Maintenance</h4>
                <p>No watering, no sunlight needed – just lasting beauty that fits perfectly into your busy lifestyle.</p>
            </div>
        </div>
    </div>
</section>

<!-- Process Section -->
<section class="process-section">
    <div class="container">
        <div class="section-header">
            <span class="section-label">How We Work</span>
            <h2 class="section-title">Our Crafting Process</h2>
            <p class="section-subtitle">From concept to creation</p>
        </div>
        <div class="process-timeline">
            <div class="process-step">
                <div class="process-number">1</div>
                <h5>Design</h5>
                <p>We conceptualize unique arrangements inspired by nature's beauty</p>
            </div>
            <div class="process-step">
                <div class="process-number">2</div>
                <h5>Handcraft</h5>
                <p>Our artisans carefully shape and assemble each petal by hand</p>
            </div>
            <div class="process-step">
                <div class="process-number">3</div>
                <h5>Arrange</h5>
                <p>Each piece is artfully arranged to create stunning compositions</p>
            </div>
            <div class="process-step">
                <div class="process-number">4</div>
                <h5>Deliver</h5>
                <p>Carefully packaged and delivered to bring lasting joy</p>
            </div>
        </div>
    </div>
</section>

<!-- Benefits Section -->
<section class="benefits-section">
    <div class="container">
        <div class="section-header">
            <span class="section-label">Perfect For You</span>
            <h2 class="section-title">Perfect For Every Occasion</h2>
            <p class="section-subtitle">Timeless gifts and decorations</p>
        </div>
        <div class="benefits-grid">
            <div class="benefit-card">
                <div class="benefit-icon">
                    <i class="bi bi-gift"></i>
                </div>
                <div class="benefit-content">
                    <h5>Thoughtful Gifts</h5>
                    <p>Give a gift that lasts forever – perfect for birthdays, anniversaries, and special occasions.</p>
                </div>
            </div>
            <div class="benefit-card">
                <div class="benefit-icon">
                    <i class="bi bi-house-heart"></i>
                </div>
                <div class="benefit-content">
                    <h5>Home Decor</h5>
                    <p>Add permanent beauty to any room without the worry of wilting or maintenance.</p>
                </div>
            </div>
            <div class="benefit-card">
                <div class="benefit-icon">
                    <i class="bi bi-building"></i>
                </div>
                <div class="benefit-content">
                    <h5>Office & Business</h5>
                    <p>Enhance professional spaces with elegant, low-maintenance floral displays.</p>
                </div>
            </div>
            <div class="benefit-card">
                <div class="benefit-icon">
                    <i class="bi bi-camera"></i>
                </div>
                <div class="benefit-content">
                    <h5>Photo Props</h5>
                    <p>Perfect for photography, weddings, and events – always camera-ready.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Gallery Preview -->
<section class="gallery-preview">
    <div class="container">
        <div class="section-header">
            <span class="section-label">Our Work</span>
            <h2 class="section-title">Our Handcrafted Creations</h2>
        </div>
        <div class="gallery-masonry">
            @for($i=1;$i<=8;$i++)
            <div class="gallery-item">
                <img src="{{ asset("asset/images/gallery{$i}.jpg") }}" alt="Gallery Image">
            </div>
            @endfor
        </div>
        <div class="text-center mt-4">
            <a href="{{ route('gallery') }}" class="btn-view-gallery">
                <i class="bi bi-images me-2"></i>View Full Gallery
            </a>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="cta-section">
    <div class="container">
        <div class="cta-content">
            <h2>Ready to Create Something Timeless?</h2>
            <p>Explore our handcrafted collection or design your perfect custom arrangement</p>
            <div class="cta-buttons">
                <a href="{{ route('shop') }}" class="btn-white">
                    <i class="bi bi-flower1 me-2"></i>Browse Shop
                </a>
                <a href="{{ route('customization.create', ['id' => 1]) }}" class="btn-outline-white">
                    <i class="bi bi-palette me-2"></i>Customize Now
                </a>
            </div>
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
</script>
</body>
</html>