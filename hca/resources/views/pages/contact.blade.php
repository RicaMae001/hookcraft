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
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Inter:wght@300;400;500;600&display=swap');

        :root {
            --pastel-pink: #ff7aa8;
            --pastel-rose: #ff6b9d;
            --pastel-light: #ffd9e4;
            --pastel-bg: #fff0f5;
            --pastel-white: #ffffff;
            --text-primary: #3a3a3a;
            --text-secondary: #666666;
            --text-light: #888888;
            --shadow-soft: 0 10px 40px rgba(255, 122, 168, 0.15);
            --shadow-medium: 0 15px 50px rgba(255, 107, 157, 0.2);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            color: var(--text-primary);
            background-color: var(--pastel-bg);
            line-height: 1.7;
        }

        /* Hero Section */
        .contact-hero {
            background: linear-gradient(170deg, var(--pastel-light) 0%, var(--pastel-pink) 100%);
            padding: 180px 0 80px;
            margin-top: 10px;
            position: relative;
            clip-path: ellipse(100% 100% at 50% 0%);
        }

        .hero-content {
            max-width: 700px;
            margin: 0 auto;
            text-align: center;
        }

        .hero-badge {
            display: inline-block;
            background: rgba(255, 255, 255, 0.95);
            color: var(--pastel-rose);
            padding: 0.6rem 1.5rem;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 2rem;
            box-shadow: 0 4px 15px rgba(255, 107, 157, 0.2);
            border: 1px solid var(--pastel-light);
        }

        .contact-hero h1 {
            font-family: 'Playfair Display', serif;
            color: var(--text-primary);
            font-size: 4rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            line-height: 1.2;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .contact-hero p {
            color: var(--text-secondary);
            font-size: 1.2rem;
            line-height: 1.9;
            font-weight: 400;
        }

        /* Contact Grid */
        .contact-section {
            padding: 80px 0;
            background: var(--pastel-white);
        }

        .contact-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2.5rem;
            margin-bottom: 4rem;
        }

        .contact-item {
            background: var(--pastel-white);
            padding: 2.5rem;
            border-radius: 30px;
            border: 2px solid var(--pastel-light);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            text-align: center;
            position: relative;
            box-shadow: var(--shadow-soft);
        }

        .contact-item:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow-medium);
            border-color: var(--pastel-rose);
        }

        .contact-item-icon {
            width: 90px;
            height: 90px;
            background: linear-gradient(135deg, var(--pastel-pink), var(--pastel-rose));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 2.5rem;
            color: white;
            box-shadow: 0 8px 25px rgba(255, 122, 168, 0.3);
        }

        .contact-item h3 {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            color: var(--text-primary);
            margin-bottom: 1rem;
            font-weight: 600;
        }

        .contact-item p {
            color: var(--text-secondary);
            margin-bottom: 1.5rem;
            font-size: 1rem;
        }

        .contact-item a,
        .contact-item .info-text {
            color: var(--pastel-rose);
            font-weight: 600;
            font-size: 1.1rem;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .contact-item a:hover {
            color: var(--pastel-pink);
            text-decoration: underline;
        }

        /* Split Layout */
        .split-section {
            padding: 80px 0;
            background: var(--pastel-bg);
        }

        .content-box {
            background: var(--pastel-white);
            border-radius: 40px;
            padding: 3.5rem;
            box-shadow: var(--shadow-soft);
            border: 2px solid var(--pastel-light);
            height: 100%;
        }

        .box-title {
            font-family: 'Playfair Display', serif;
            font-size: 2.2rem;
            color: var(--text-primary);
            margin-bottom: 2rem;
            font-weight: 600;
        }

        .map-frame {
            width: 100%;
            height: 500px;
            border-radius: 25px;
            border: none;
            box-shadow: 0 5px 20px rgba(255, 122, 168, 0.15);
            border: 2px solid var(--pastel-light);
        }

        /* Social Media */
        .social-container {
            display: flex;
            gap: 1.5rem;
            margin-top: 2.5rem;
            flex-wrap: wrap;
        }

        .social-item {
            flex: 1;
            min-width: 150px;
            background: linear-gradient(135deg, var(--pastel-light), var(--pastel-pink));
            border-radius: 20px;
            padding: 2rem 1.5rem;
            text-align: center;
            text-decoration: none;
            transition: all 0.3s ease;
            border: 2px solid transparent;
            color: var(--text-primary);
        }

        .social-item:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-medium);
            border-color: var(--pastel-rose);
            color: var(--text-primary);
        }

        .social-item i {
            font-size: 2.5rem;
            color: white;
            display: block;
            margin-bottom: 1rem;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .social-item span {
            color: var(--text-primary);
            font-weight: 600;
            font-size: 0.95rem;
        }

        /* Info Cards */
        .info-section {
            padding: 80px 0;
            background: var(--pastel-white);
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }

        .info-box {
            background: var(--pastel-bg);
            padding: 2.5rem;
            border-radius: 30px;
            border-left: 5px solid var(--pastel-rose);
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(255, 122, 168, 0.1);
        }

        .info-box:hover {
            transform: translateX(10px);
            box-shadow: var(--shadow-soft);
        }

        .info-box-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--pastel-pink), var(--pastel-rose));
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            color: white;
            font-size: 1.8rem;
            box-shadow: 0 4px 10px rgba(255, 122, 168, 0.2);
        }

        .info-box h4 {
            font-family: 'Playfair Display', serif;
            color: var(--text-primary);
            font-size: 1.3rem;
            margin-bottom: 0.75rem;
            font-weight: 600;
        }

        .info-box p {
            color: var(--text-secondary);
            margin: 0;
            line-height: 1.7;
        }

        /* Facebook Embed */
        .facebook-wrapper {
            background: var(--pastel-white);
            border-radius: 40px;
            padding: 4rem;
            box-shadow: var(--shadow-soft);
            text-align: center;
            border: 2px solid var(--pastel-light);
        }

        .facebook-wrapper h2 {
            font-family: 'Playfair Display', serif;
            font-size: 2.5rem;
            color: var(--text-primary);
            margin-bottom: 1rem;
            font-weight: 600;
        }

        .facebook-wrapper .subtitle {
            color: var(--text-secondary);
            font-size: 1.1rem;
            margin-bottom: 3rem;
            font-weight: 400;
        }

        .fb-frame {
            display: inline-block;
            border-radius: 25px;
            overflow: hidden;
            box-shadow: var(--shadow-soft);
            border: 2px solid var(--pastel-light);
        }

        /* Chatbot Button */
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
            box-shadow: 0 8px 30px rgba(255, 122, 168, 0.4);
            transition: all 0.3s ease;
            border: 2px solid white;
        }

        .chatbot-btn:hover {
            transform: scale(1.1) rotate(10deg);
            box-shadow: 0 10px 40px rgba(255, 122, 168, 0.5);
            color: white;
        }

        /* Section Headers */
        .section-header {
            text-align: center;
            margin-bottom: 4rem;
        }

        .section-label {
            display: inline-block;
            color: var(--pastel-rose);
            font-size: 0.9rem;
            font-weight: 600;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-bottom: 1rem;
            background: rgba(255, 122, 168, 0.1);
            padding: 0.5rem 1.5rem;
            border-radius: 50px;
        }

        .section-title {
            font-family: 'Playfair Display', serif;
            font-size: 3rem;
            color: var(--text-primary);
            margin-bottom: 1rem;
            font-weight: 600;
            line-height: 1.2;
        }

        .section-subtitle {
            color: var(--text-secondary);
            font-size: 1.15rem;
            max-width: 700px;
            margin: 0 auto;
            font-weight: 400;
        }

        /* Footer Contact Info */
        .footer-contact {
            background: var(--pastel-white);
            border-top: 2px solid var(--pastel-light);
            padding: 3rem 0;
            text-align: center;
        }

        .footer-contact p {
            color: var(--text-secondary);
            margin-bottom: 0.5rem;
            font-size: 1rem;
        }

        .footer-contact a {
            color: var(--pastel-rose);
            text-decoration: none;
            font-weight: 600;
        }

        .footer-contact a:hover {
            color: var(--pastel-pink);
            text-decoration: underline;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .contact-hero h1 {
                font-size: 3rem;
            }

            .section-title {
                font-size: 2.5rem;
            }

            .content-box {
                margin-bottom: 2rem;
            }

            .map-frame {
                height: 400px;
            }
        }

        @media (max-width: 768px) {
            .contact-hero {
                padding: 140px 0 60px;
            }

            .contact-hero h1 {
                font-size: 2.5rem;
            }

            .contact-grid {
                grid-template-columns: 1fr;
            }

            .info-grid {
                grid-template-columns: 1fr;
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

            .content-box {
                padding: 2rem;
            }

            .facebook-wrapper {
                padding: 2rem;
            }

            .section-title {
                font-size: 2rem;
            }
        }

        @media (max-width: 576px) {
            .contact-hero h1 {
                font-size: 2rem;
            }

            .section-title {
                font-size: 1.75rem;
            }

            .social-container {
                flex-direction: column;
            }

            .social-item {
                min-width: 100%;
            }

            .contact-item {
                padding: 2rem;
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
        <i class="bi bi-chat"></i>
    </a>
</div>

<!-- Hero Section -->
<section class="contact-hero">
    <div class="container">
        <div class="hero-content">
            <span class="hero-badge">Let's Connect</span>
            <h1>We'd Love to Hear From You</h1>
            <p>Whether you have a question about our handcrafted creations, need a custom order, or just want to say hello, we're here and ready to help 🌸</p>
        </div>
    </div>
</section>

<!-- Contact Information -->
<section class="contact-section">
    <div class="container">
        <div class="section-header">
            <span class="section-label">Get in Touch</span>
            <h2 class="section-title">How to Reach Us</h2>
            <p class="section-subtitle">Choose your preferred way to connect with us</p>
        </div>

        <div class="contact-grid">
            <div class="contact-item">
                <div class="contact-item-icon">
                    <i class="bi bi-telephone-fill"></i>
                </div>
                <h3>Call Us</h3>
                <p>Speak directly with our team</p>
                <a href="tel:09055569763">0905 556 9763</a>
            </div>

            <div class="contact-item">
                <div class="contact-item-icon">
                    <i class="bi bi-geo-alt-fill"></i>
                </div>
                <h3>Visit Our Store</h3>
                <p>Saint Jude Hipodromo<br>Cebu City, Philippines</p>
                <span class="info-text">Drop by anytime!</span>
            </div>

            <div class="contact-item">
                <div class="contact-item-icon">
                    <i class="bi bi-clock-fill"></i>
                </div>
                <h3>Open 24/7</h3>
                <p>We're always available for you</p>
                <span class="info-text">All Days of the Week</span>
            </div>
        </div>
    </div>
</section>

<!-- Map and Location Details -->
<section class="split-section">
    <div class="container">
        <div class="section-header">
            <span class="section-label">Location</span>
            <h2 class="section-title">Find Us on the Map</h2>
        </div>

        <div class="row">
            <div class="col-lg-8 mb-4 mb-lg-0">
                <div class="content-box">
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d505.04868866433014!2d123.90669385320574!3d10.312446158188536!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33a999828a212d3b%3A0x80ecadf9173d97d3!2sSaint%20jude%20Hipodromo!5e0!3m2!1sen!2sph!4v1761275217741!5m2!1sen!2sph" 
                        class="map-frame"
                        allowfullscreen="" 
                        loading="lazy" 
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="content-box">
                    <h3 class="box-title">Connect With Us</h3>
                    <p style="color: var(--text-secondary); margin-bottom: 2rem; font-weight: 400;">Follow us on social media for updates, inspiration, and special offers</p>
                    
                    <div class="social-container" style="flex-direction: column;">
                        <a href="https://www.instagram.com/hookcraft_avenue" target="_blank" class="social-item">
                            <i class="bi bi-instagram"></i>
                            <span>@hookcraft_avenue</span>
                        </a>
                        <a href="https://www.tiktok.com/@hookcraftavenue" target="_blank" class="social-item">
                            <i class="bi bi-tiktok"></i>
                            <span>@hookcraftavenue</span>
                        </a>
                        <a href="https://www.facebook.com/CrochetbyAlys" target="_blank" class="social-item">
                            <i class="bi bi-facebook"></i>
                            <span>Hookcraft Avenue</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Business Information -->
<section class="info-section">
    <div class="container">
        <div class="section-header">
            <span class="section-label">About Us</span>
            <h2 class="section-title">Why Choose Hookcraft Avenue</h2>
        </div>

        <div class="info-grid">
            <div class="info-box">
                <div class="info-box-icon">
                    <i class="bi bi-shop"></i>
                </div>
                <h4>Arts & Crafts Store</h4>
                <p>Specializing in beautiful handcrafted floral arrangements and crochet creations made with love and attention to detail</p>
            </div>

            <div class="info-box">
                <div class="info-box-icon">
                    <i class="bi bi-heart-fill"></i>
                </div>
                <h4>Handmade Quality</h4>
                <p>Every piece is carefully crafted by hand, ensuring unique and personalized creations for your special moments</p>
            </div>

            <div class="info-box">
                <div class="info-box-icon">
                    <i class="bi bi-people-fill"></i>
                </div>
                <h4>Growing Community</h4>
                <p>Join our 1.6K+ Instagram followers who love and appreciate handcrafted artistry</p>
            </div>
        </div>
    </div>
</section>

<!-- Facebook Section -->
<section class="split-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="facebook-wrapper">
                    <h2>Stay Connected</h2>
                    <p class="subtitle">Follow our Facebook page for daily inspiration and behind-the-scenes content</p>
                    
                    <div class="fb-frame">
                        <iframe 
                            src="https://www.facebook.com/plugins/page.php?href=https%3A%2F%2Fwww.facebook.com%2FCrochetbyAlys&tabs=timeline&width=500&height=600&small_header=false&adapt_container_width=true&hide_cover=false&show_facepile=true&appId" 
                            width="500" 
                            height="600" 
                            style="border:none;overflow:hidden;" 
                            scrolling="no" 
                            frameborder="0" 
                            allowfullscreen="true" 
                            allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share">
                        </iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Footer Contact -->
<div class="footer-contact">
    <div class="container">
        <p>📞 <a href="tel:09055569763">0905 556 9763</a> | 📍 Saint Jude Hipodromo, Cebu City, Philippines</p>
        <p>📧 <a href="mailto:hookcraftavenue@gmail.com">hookcraftavenue@gmail.com</a> | ⏰ Open 24/7</p>
        <p style="color: var(--text-light); font-size: 0.9rem; margin-top: 1rem;">© 2024 Hookcraft Avenue. All rights reserved.</p>
    </div>
</div>

@include('components.footer')

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>