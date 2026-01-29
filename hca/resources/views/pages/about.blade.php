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

        /* Products Section */
        .products-section {
            padding: 50px 0;
            background: var(--pastel-white);
        }

        .products-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2rem;
            margin-top: 2rem;
        }

        .product-card {
            background: var(--pastel-light);
            padding: 2.25rem;
            border-radius: 20px;
            text-align: center;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .product-card:hover {
            transform: translateY(-8px);
            border-color: var(--pastel-rose);
            box-shadow: var(--shadow-medium);
        }

        .product-icon {
            width: 85px;
            height: 85px;
            background: linear-gradient(135deg, var(--pastel-pink), var(--pastel-rose));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
        }

        .product-icon i {
            font-size: 2.5rem;
            color: white;
        }

        .product-card h4 {
            font-size: 1.5rem;
            color: var(--text-primary);
            margin-bottom: 0.75rem;
            font-weight: 600;
        }

        .product-card p {
            color: var(--text-secondary);
            font-size: 1.1rem;
            margin: 0;
            line-height: 1.6;
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
        @media (max-width: 992px) {
            .hero-content-wrapper,
            .story-grid {
                grid-template-columns: 1fr;
                gap: 2rem;
            }

            .values-grid,
            .products-grid {
                grid-template-columns: 1fr;
            }

            .process-timeline {
                grid-template-columns: repeat(2, 1fr);
            }

            .process-timeline::before {
                display: none;
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
                <p>Where creativity meets craftsmanship. Founded by Alyssa Monterde, Hookcraft Avenue brings you beautiful handmade flowers crafted with love and attention to detail. Each piece is a unique work of art, perfect for gifts and special moments.</p>
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
            <h2 class="section-title">The Story Behind the Name</h2>
            <p class="section-subtitle">From crochet hooks to crafted beauty</p>
        </div>
        <div class="story-grid">
            <div class="story-text">
                <p>
                    Hookcraft Avenue was born from a passion for handmade crafts. The name itself tells our story - 
                    "Hook" represents our beginnings with crochet, where it all started with simple hooks creating 
                    beautiful patterns.
                </p>
                <p>
                    "Craft" symbolizes the artistry and skill that goes into every single piece we create. Each flower, 
                    keychain, and gift box is carefully handmade with precision and care. We don't just make products; 
                    we craft experiences and memories.
                </p>
                <p>
                    "Avenue" represents the path and space where all our crafts come together - a destination for those 
                    seeking unique, handmade gifts for their loved ones. We're here for all ages, for anyone who wants 
                    to give something truly special.
                </p>
            </div>
            <div class="story-images">
                <img src="{{ asset('asset/images/about-flower.jpg') }}" alt="Handcrafted Flowers">
                <img src="{{ asset('asset/images/about-flower1.jpg') }}" alt="Artisan Work">
            </div>
        </div>
    </div>
</section>

<!-- Products Section -->
<section class="products-section">
    <div class="container">
        <div class="section-header">
            <span class="section-label">What We Offer</span>
            <h2 class="section-title">Our Handmade Collections</h2>
            <p class="section-subtitle">Beautifully crafted with premium materials</p>
        </div>
        <div class="products-grid">
            <div class="product-card">
                <div class="product-icon">
                    <i class="bi bi-flower1"></i>
                </div>
                <h4>Fuzzy Wire Flowers</h4>
                <p>Delicate and colorful flowers crafted from soft fuzzy wire, perfect for bouquets and arrangements that last forever.</p>
            </div>
            <div class="product-card">
                <div class="product-icon">
                    <i class="bi bi-flower2"></i>
                </div>
                <h4>Satin Ribbon Flowers</h4>
                <p>Elegant flowers made from luxurious satin ribbons, bringing a touch of sophistication to any gift or decor.</p>
            </div>
            <div class="product-card">
                <div class="product-icon">
                    <i class="bi bi-flower3"></i>
                </div>
                <h4>Crochet Flowers</h4>
                <p>Our signature crochet flowers where it all began - handmade with care using traditional crochet techniques.</p>
            </div>
            <div class="product-card">
                <div class="product-icon">
                    <i class="bi bi-key"></i>
                </div>
                <h4>Crochet Keychains</h4>
                <p>Adorable handmade keychains perfect as small gifts or personal accessories that add a touch of handmade charm.</p>
            </div>
            <div class="product-card">
                <div class="product-icon">
                    <i class="bi bi-gift"></i>
                </div>
                <h4>Gift Boxes</h4>
                <p>Beautifully curated gift boxes combining our handmade items for the perfect present for any occasion.</p>
            </div>
            <div class="product-card">
                <div class="product-icon">
                    <i class="bi bi-box-seam"></i>
                </div>
                <h4>Gift Sets</h4>
                <p>Thoughtfully arranged gift sets that combine multiple handmade items for a complete gifting experience.</p>
            </div>
        </div>
    </div>
</section>

<!-- Values Section -->
<section class="values-section">
    <div class="container">
        <div class="section-header">
            <span class="section-label">Why Choose Us</span>
            <h2 class="section-title">What Makes Us Special</h2>
            <p class="section-subtitle">Quality, customization, and care in every piece</p>
        </div>
        <div class="values-grid">
            <div class="value-card">
                <div class="value-icon">
                    <i class="bi bi-hand-thumbs-up"></i>
                </div>
                <h4>100% Handmade</h4>
                <p>Every single item is carefully crafted by hand, ensuring unique quality and attention to detail in each piece.</p>
            </div>
            <div class="value-card">
                <div class="value-icon">
                    <i class="bi bi-palette"></i>
                </div>
                <h4>Custom Orders</h4>
                <p>We love bringing your vision to life! Customize colors, flowers, and designs to create something uniquely yours.</p>
            </div>
            <div class="value-card">
                <div class="value-icon">
                    <i class="bi bi-heart"></i>
                </div>
                <h4>Perfect for Gifting</h4>
                <p>Our handmade flowers and gifts are ideal for surprising loved ones on birthdays, anniversaries, and special occasions.</p>
            </div>
        </div>
    </div>
</section>

<!-- Process Section -->
<section class="process-section">
    <div class="container">
        <div class="section-header">
            <span class="section-label">How to Order</span>
            <h2 class="section-title">Easy Ordering Process</h2>
            <p class="section-subtitle">From browsing to delivery</p>
        </div>
        <div class="process-timeline">
            <div class="process-step">
                <div class="process-number">1</div>
                <h5>Browse & Choose</h5>
                <p>Explore our collections or request a custom design that matches your vision</p>
            </div>
            <div class="process-step">
                <div class="process-number">2</div>
                <h5>Place Order</h5>
                <p>Order through our website with secure payment via GCash, bank transfer, or cash on delivery</p>
            </div>
            <div class="process-step">
                <div class="process-number">3</div>
                <h5>Handcrafted</h5>
                <p>We carefully handcraft your order with love and attention to every detail</p>
            </div>
            <div class="process-step">
                <div class="process-number">4</div>
                <h5>Delivery/Pickup</h5>
                <p>Choose delivery via Maxim, Angkas, or Moveit, or pickup at your convenience</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="cta-section">
    <div class="container">
        <div class="cta-content">
            <h2>Ready to Create Something Special?</h2>
            <p>Browse our handmade collection or design your perfect custom gift today</p>
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