<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gallery - Hookcraft Avenue</title>
    <link rel="icon" href="{{ asset('asset/images/logo.jpg') }}" type="image/png">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('asset/stylesnav.css') }}">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;600;700&family=Outfit:wght@300;400;500;600&display=swap');

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
        
        
        .gallery-hero {
            background: linear-gradient(135deg, var(--pastel-white) 0%, var(--pastel-light) 100%);
            padding: 100px 0 50px;
            margin-top: 10px;
            border-bottom: 1px solid var(--pastel-light);
        }

        .hero-content {
            max-width: 700px;
            margin: 0 auto;
            text-align: center;
        }

        .gallery-hero h1 {
            font-family: 'Cormorant Garamond', serif;
            color: var(--text-primary);
            font-size: 4rem;
            font-weight: 600;
            margin-bottom: 1.25rem;
            letter-spacing: -0.5px;
        }

        .gallery-hero p {
            color: var(--text-secondary);
            font-size: 1.35rem;
            font-weight: 300;
        }

        /* Tab Navigation */
        .tab-navigation {
            background: var(--pastel-white);
            padding: 35px 0;
            position: sticky;
            top: 76px;
            z-index: 100;
            border-bottom: 2px solid var(--pastel-light);
            box-shadow: 0 2px 10px rgba(194, 24, 91, 0.05);
        }

        .tab-container {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .tab-btn {
            padding: 1rem 2.75rem;
            background: transparent;
            color: var(--text-secondary);
            border: none;
            font-weight: 500;
            font-size: 1.1rem;
            cursor: pointer;
            position: relative;
            transition: all 0.3s ease;
            border-radius: 50px;
        }

        .tab-btn::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--pastel-rose), var(--pastel-pink));
            transition: width 0.3s ease;
            border-radius: 3px;
        }

        .tab-btn:hover {
            color: var(--text-primary);
            background: var(--pastel-light);
        }

        .tab-btn.active {
            color: white;
            background: linear-gradient(135deg, var(--pastel-pink), var(--pastel-rose));
            font-weight: 600;
        }

        .tab-btn.active::after {
            width: 0;
        }

        /* Modern Grid Gallery Layout */
        .gallery-section {
            padding: 70px 0 50px;
        }

        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 2rem;
        }

        .gallery-item-wrapper {
            opacity: 0;
            animation: fadeInScale 0.6s ease forwards;
        }

        @keyframes fadeInScale {
            to {
                opacity: 1;
                transform: scale(1);
            }
            from {
                opacity: 0;
                transform: scale(0.9);
            }
        }

        .gallery-item {
            position: relative;
            overflow: hidden;
            border-radius: 25px;
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            background: var(--pastel-white);
            box-shadow: var(--shadow-soft);
            height: 400px;
        }

        .gallery-item:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: var(--shadow-medium);
        }

        .gallery-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.4s ease;
        }

        .gallery-item:hover img {
            transform: scale(1.1);
        }

        .gallery-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(to top, rgba(0,0,0,0.85), rgba(0,0,0,0.3) 50%, transparent);
            padding: 2.5rem 1.75rem 1.75rem;
            transform: translateY(0);
            transition: all 0.3s ease;
        }

        .gallery-item:hover .gallery-overlay {
            background: linear-gradient(to top, rgba(194, 24, 91, 0.95), rgba(194, 24, 91, 0.7) 50%, transparent);
        }

        .gallery-overlay h5 {
            color: white;
            font-size: 1.35rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .gallery-overlay p {
            color: rgba(255,255,255,0.95);
            font-size: 1.05rem;
            margin: 0;
            line-height: 1.5;
        }

        .category-tag {
            position: absolute;
            top: 20px;
            left: 20px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            color: var(--pastel-rose);
            padding: 0.6rem 1.35rem;
            border-radius: 50px;
            font-size: 0.95rem;
            font-weight: 600;
            z-index: 2;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        /* Lightbox - Improved Design */
        .lightbox-modal {
            background: rgba(0, 0, 0, 0.95);
        }

        .lightbox-modal .modal-dialog {
            max-width: 1300px;
            margin: 3rem auto;
        }

        .lightbox-modal .modal-content {
            background: transparent;
            border: none;
        }

        .lightbox-modal .modal-body {
            padding: 0;
            position: relative;
        }

        .lightbox-wrapper {
            display: grid;
            grid-template-columns: 1.5fr 1fr;
            gap: 0;
            background: var(--pastel-white);
            border-radius: 25px;
            overflow: hidden;
            box-shadow: 0 25px 100px rgba(0, 0, 0, 0.3);
        }

        .lightbox-image-side {
            padding: 3rem;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--pastel-light);
        }

        .lightbox-image {
            max-width: 100%;
            max-height: 75vh;
            object-fit: contain;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
        }

        .lightbox-details {
            padding: 3.5rem;
            background: var(--pastel-white);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .lightbox-details h3 {
            font-family: 'Cormorant Garamond', serif;
            font-size: 2.5rem;
            color: var(--text-primary);
            margin-bottom: 1.25rem;
            font-weight: 600;
            line-height: 1.2;
        }

        .lightbox-details .category-badge {
            display: inline-block;
            background: linear-gradient(135deg, var(--pastel-pink), var(--pastel-rose));
            color: white;
            padding: 0.6rem 1.5rem;
            border-radius: 50px;
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 1.75rem;
        }

        .lightbox-details p {
            color: var(--text-secondary);
            line-height: 1.8;
            font-size: 1.2rem;
        }

        .lightbox-actions {
            display: flex;
            gap: 1rem;
            margin-top: 2.5rem;
        }

        .lightbox-btn {
            flex: 1;
            padding: 1.1rem;
            border: 2px solid var(--pastel-rose);
            background: transparent;
            color: var(--text-primary);
            border-radius: 50px;
            font-weight: 600;
            font-size: 1.05rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .lightbox-btn:hover {
            background: var(--pastel-rose);
            color: white;
            border-color: var(--pastel-rose);
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(194, 24, 91, 0.3);
        }

        .lightbox-nav-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(10px);
            border: none;
            width: 55px;
            height: 55px;
            border-radius: 50%;
            color: white;
            font-size: 1.75rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10;
        }

        .lightbox-nav-btn:hover {
            background: var(--pastel-rose);
            transform: translateY(-50%) scale(1.1);
        }

        .lightbox-nav-btn.prev {
            left: -27px;
        }

        .lightbox-nav-btn.next {
            right: -27px;
        }

        .lightbox-close-btn {
            position: absolute;
            top: -20px;
            right: -20px;
            background: var(--pastel-white);
            color: var(--text-primary);
            border: none;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            font-size: 1.4rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: var(--shadow-soft);
            z-index: 10;
        }

        .lightbox-close-btn:hover {
            background: var(--pastel-rose);
            color: white;
            transform: rotate(90deg) scale(1.1);
        }

        /* Empty State */
        .empty-gallery {
            text-align: center;
            padding: 120px 40px;
            max-width: 550px;
            margin: 0 auto;
        }

        .empty-gallery i {
            font-size: 6rem;
            color: var(--pastel-rose);
            margin-bottom: 2.5rem;
            opacity: 0.6;
        }

        .empty-gallery h3 {
            font-family: 'Cormorant Garamond', serif;
            font-size: 2.5rem;
            color: var(--text-primary);
            margin-bottom: 1.25rem;
        }

        .empty-gallery p {
            color: var(--text-secondary);
            font-size: 1.25rem;
        }

        /* Chatbot */
        .chatbot-float {
            position: fixed;
            bottom: 40px;
            right: 40px;
            z-index: 999;
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
            transform: scale(1.15) rotate(10deg);
            box-shadow: 0 8px 35px rgba(194, 24, 91, 0.4);
            color: white;
        }

        /* Responsive */
        @media (max-width: 1200px) {
            .lightbox-wrapper {
                grid-template-columns: 1fr;
            }

            .lightbox-details {
                padding: 2.5rem;
            }

            .lightbox-image-side {
                padding: 2rem;
            }

            .gallery-grid {
                grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
                gap: 1.5rem;
            }
        }

        @media (max-width: 992px) {
            .gallery-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .gallery-hero h1 {
                font-size: 3.5rem;
            }

            .gallery-item {
                height: 350px;
            }
        }

        @media (max-width: 768px) {
            body {
                font-size: 16px;
            }

            .gallery-hero {
                padding: 80px 0 35px;
            }

            .gallery-hero h1 {
                font-size: 2.75rem;
            }

            .gallery-hero p {
                font-size: 1.15rem;
            }

            .tab-navigation {
                padding: 25px 0;
            }

            .tab-btn {
                padding: 0.8rem 1.75rem;
                font-size: 1rem;
            }

            .gallery-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .gallery-item {
                height: 400px;
            }

            .lightbox-nav-btn.prev {
                left: 10px;
            }

            .lightbox-nav-btn.next {
                right: 10px;
            }

            .lightbox-close-btn {
                top: 10px;
                right: 10px;
            }

            .lightbox-details h3 {
                font-size: 2rem;
            }

            .lightbox-details p {
                font-size: 1.05rem;
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
        <i class="bi bi-chat"></i>
    </a>
</div>

<!-- Hero Section -->
<section class="gallery-hero">
    <div class="container">
        <div class="hero-content">
            <h1>Our Gallery</h1>
            <p>A curated collection of handcrafted beauty</p>
        </div>
    </div>
</section>

<!-- Tab Navigation -->
<section class="tab-navigation">
    <div class="container">
        <div class="tab-container">
            <button class="tab-btn active" data-filter="all">All</button>
            <button class="tab-btn" data-filter="birthday">Birthday</button>
            <button class="tab-btn" data-filter="casual">Casual</button>
            <button class="tab-btn" data-filter="tiny">Tiny</button>
            <button class="tab-btn" data-filter="wedding">Wedding</button>
            <button class="tab-btn" data-filter="custom">Custom</button>
        </div>
    </div>
</section>

<!-- Gallery Section -->
<section class="gallery-section">
    <div class="container">
        @if($galleryItems->count() > 0)
        <div class="gallery-grid" id="galleryGrid">
            @foreach($galleryItems as $index => $item)
            <div class="gallery-item-wrapper" data-category="{{ $item->category_name }}" style="animation-delay: {{ ($index * 0.05) }}s">
                <div class="gallery-item">
                    <span class="category-tag">{{ $item->category_name }}</span>
                    <img src="{{ asset('asset/images/' . $item->image_path) }}" alt="{{ $item->title }}" data-index="{{ $index }}">
                    <div class="gallery-overlay">
                        <h5>{{ $item->title }}</h5>
                        <p>{{ $item->description }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="empty-gallery">
            <i class="bi bi-camera"></i>
            <h3>Gallery Coming Soon</h3>
            <p>We're preparing something beautiful for you. Stay tuned!</p>
        </div>
        @endif
    </div>
</section>

<!-- Lightbox Modal -->
<div class="modal fade lightbox-modal" id="lightboxModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <button class="lightbox-close-btn" data-bs-dismiss="modal">
                    <i class="bi bi-x"></i>
                </button>
                <!-- <button class="lightbox-nav-btn prev" onclick="navigateLightbox(-1)">
                    <i class="bi bi-chevron-left"></i>
                </button>
                <button class="lightbox-nav-btn next" onclick="navigateLightbox(1)">
                    <i class="bi bi-chevron-right"></i>
                </button>
                 -->
                <div class="lightbox-wrapper">
                    <div class="lightbox-image-side">
                        <img src="" alt="" class="lightbox-image" id="lightboxImage">
                    </div>
                    <div class="lightbox-details">
                        <div>
                            <span class="category-badge" id="lightboxCategory"></span>
                            <h3 id="lightboxTitle"></h3>
                            <p id="lightboxDescription"></p>
                        </div>
                        <div class="lightbox-actions">
                            <button class="lightbox-btn" onclick="navigateLightbox(-1)">
                                <i class="bi bi-chevron-left me-2"></i>Previous
                            </button>
                            <button class="lightbox-btn" onclick="navigateLightbox(1)">
                                Next<i class="bi bi-chevron-right ms-2"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('components.footer')

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script>
    const galleryItems = @json($galleryItems);
    let currentLightboxIndex = 0;
    const lightboxModal = new bootstrap.Modal(document.getElementById('lightboxModal'));

    // Tab filter functionality
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            const filter = this.getAttribute('data-filter');
            const items = document.querySelectorAll('.gallery-item-wrapper');

            items.forEach(item => {
                const category = item.getAttribute('data-category');
                if (filter === 'all' || category === filter) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });

    // Lightbox functionality
    document.querySelectorAll('.gallery-item img').forEach(img => {
        img.addEventListener('click', function() {
            currentLightboxIndex = parseInt(this.getAttribute('data-index'));
            openLightbox(currentLightboxIndex);
        });
    });

    function openLightbox(index) {
        if (galleryItems.length > 0) {
            const item = galleryItems[index];
            document.getElementById('lightboxImage').src = "{{ asset('asset/images') }}/" + item.image_path;
            document.getElementById('lightboxTitle').textContent = item.title;
            document.getElementById('lightboxDescription').textContent = item.description;
            document.getElementById('lightboxCategory').textContent = item.category_name;
            lightboxModal.show();
        }
    }

    function navigateLightbox(direction) {
        currentLightboxIndex += direction;
        if (currentLightboxIndex < 0) currentLightboxIndex = galleryItems.length - 1;
        if (currentLightboxIndex >= galleryItems.length) currentLightboxIndex = 0;
        openLightbox(currentLightboxIndex);
    }

    // Keyboard navigation
    document.addEventListener('keydown', function(e) {
        if (document.querySelector('.lightbox-modal.show')) {
            if (e.key === 'ArrowLeft') navigateLightbox(-1);
            if (e.key === 'ArrowRight') navigateLightbox(1);
            if (e.key === 'Escape') lightboxModal.hide();
        }
    });
</script>
</body>
</html>