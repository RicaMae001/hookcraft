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
           --pastel-pink: #ffc0d3;
            --pastel-rose: #ffb3c6;
            --pastel-light: #ffe4ec;
            --pastel-bg: #fff5f8;
            --pastel-white: #ffffff;
            --text-primary: #4a4a4a;
            --text-secondary: #7a7a7a;
            --shadow-soft: 0 10px 40px rgba(255, 192, 211, 0.15);
            --shadow-medium: 0 15px 50px rgba(255, 192, 211, 0.2);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--pastel-bg);
            color: var(--text-primary);
            line-height: 1.6;
        }

        /* Hero Section - Minimal Design */
        .gallery-hero {
            background: var(--pastel-white);
            padding: 80px 0 40px;
            margin-top: 10px;
            border-bottom: 1px solid var(--pastel-light);
        }

        .hero-content {
            max-width: 600px;
            margin: 0 auto;
            text-align: center;
        }

        .gallery-hero h1 {
            font-family: 'Cormorant Garamond', serif;
            color: var(--text-primary);
            font-size: 3.5rem;
            font-weight: 600;
            margin-bottom: 1rem;
            letter-spacing: -0.5px;
        }

        .gallery-hero p {
            color: var(--text-secondary);
            font-size: 1.1rem;
            font-weight: 300;
        }

        /* Tab Navigation */
        .tab-navigation {
            background: var(--pastel-white);
            padding: 30px 0;
            position: sticky;
            top: 76px;
            z-index: 100;
            border-bottom: 2px solid var(--pastel-light);
        }

        .tab-container {
            display: flex;
            justify-content: center;
            gap: 0;
            flex-wrap: wrap;
        }

        .tab-btn {
            padding: 0.9rem 2.5rem;
            background: transparent;
            color: var(--text-secondary);
            border: none;
            font-weight: 500;
            font-size: 1rem;
            cursor: pointer;
            position: relative;
            transition: all 0.3s ease;
            border-bottom: 3px solid transparent;
        }

        .tab-btn::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--pastel-rose), var(--pastel-pink));
            transition: width 0.3s ease;
        }

        .tab-btn:hover {
            color: var(--text-primary);
        }

        .tab-btn.active {
            color: var(--pastel-rose);
            font-weight: 600;
        }

        .tab-btn.active::after {
            width: 100%;
        }

        /* Masonry Gallery Layout */
        .gallery-section {
            padding: 60px 0 40px;
        }

        .masonry-grid {
            column-count: 3;
            column-gap: 1rem;
        }

        .masonry-item {
            break-inside: avoid;
            margin-bottom: 1rem;
            display: inline-block;
            width: 100%;
            opacity: 0;
            animation: fadeIn 0.5s ease forwards;
        }

        @keyframes fadeIn {
            to {
                opacity: 1;
            }
        }

        .gallery-item {
            position: relative;
            overflow: hidden;
            border-radius: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
            background: var(--pastel-white);
            box-shadow: var(--shadow-soft);
        }

        .gallery-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 40px rgba(232, 185, 203, 0.18);
        }

        .gallery-item img {
            width: 100%;
            height: auto;
            display: block;
            border-radius: 20px;
        }

        .gallery-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(to top, rgba(0,0,0,0.75), transparent);
            padding: 2rem 1rem 1rem;
            transform: translateY(100%);
            transition: transform 0.3s ease;
        }

        .gallery-item:hover .gallery-overlay {
            transform: translateY(0);
        }

        .gallery-overlay h5 {
            color: white;
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 0.3rem;
        }

        .gallery-overlay p {
            color: rgba(255,255,255,0.9);
            font-size: 0.9rem;
            margin: 0;
        }

        .category-tag {
            position: absolute;
            top: 15px;
            left: 15px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            color: var(--pastel-rose);
            padding: 0.4rem 1rem;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 600;
            z-index: 2;
        }

        /* Lightbox - Improved Design */
        .lightbox-modal {
            background: rgba(0, 0, 0, 0.95);
        }

        .lightbox-modal .modal-dialog {
            max-width: 1200px;
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
            grid-template-columns: 1fr 400px;
            gap: 2rem;
            background: var(--pastel-white);
            border-radius: 20px;
            overflow: hidden;
        }

        .lightbox-image-side {
            padding: 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--pastel-light);
        }

        .lightbox-image {
            max-width: 100%;
            max-height: 80vh;
            object-fit: contain;
            border-radius: 15px;
        }

        .lightbox-details {
            padding: 3rem;
            background: var(--pastel-white);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .lightbox-details h3 {
            font-family: 'Cormorant Garamond', serif;
            font-size: 2.2rem;
            color: var(--text-primary);
            margin-bottom: 1rem;
            font-weight: 600;
        }

        .lightbox-details .category-badge {
            display: inline-block;
            background: var(--pastel-light);
            color: var(--pastel-rose);
            padding: 0.5rem 1.2rem;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
        }

        .lightbox-details p {
            color: var(--text-secondary);
            line-height: 1.8;
            font-size: 1.05rem;
        }

        .lightbox-actions {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }

        .lightbox-btn {
            flex: 1;
            padding: 0.9rem;
            border: 2px solid var(--pastel-rose);
            background: transparent;
            color: var(--text-primary);
            border-radius: 50px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .lightbox-btn:hover {
            background: var(--pastel-rose);
            color: white;
            border-color: var(--pastel-rose);
        }

        .lightbox-nav-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border: none;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10;
        }

        .lightbox-nav-btn:hover {
            background: rgba(232, 185, 203, 0.5);
        }

        .lightbox-nav-btn.prev {
            left: -25px;
        }

        .lightbox-nav-btn.next {
            right: -25px;
        }

        .lightbox-close-btn {
            position: absolute;
            top: -15px;
            right: -15px;
            background: var(--pastel-white);
            color: var(--text-primary);
            border: none;
            width: 45px;
            height: 45px;
            border-radius: 50%;
            font-size: 1.2rem;
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
            transform: rotate(90deg);
        }

        /* Empty State */
        .empty-gallery {
            text-align: center;
            padding: 100px 40px;
            max-width: 500px;
            margin: 0 auto;
        }

        .empty-gallery i {
            font-size: 5rem;
            color: var(--pastel-rose);
            margin-bottom: 2rem;
            opacity: 0.5;
        }

        .empty-gallery h3 {
            font-family: 'Cormorant Garamond', serif;
            font-size: 2rem;
            color: var(--text-primary);
            margin-bottom: 1rem;
        }

        .empty-gallery p {
            color: var(--text-secondary);
            font-size: 1.05rem;
        }

        /* Chatbot */
        .chatbot-float {
            position: fixed;
            bottom: 40px;
            right: 40px;
            z-index: 999;
        }

        .chatbot-btn {
            width: 65px;
            height: 65px;
            background: linear-gradient(135deg, var(--pastel-pink), var(--pastel-rose));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.8rem;
            text-decoration: none;
            box-shadow: 0 6px 25px rgba(232, 185, 203, 0.3);
            transition: all 0.3s ease;
        }

        .chatbot-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 8px 35px rgba(232, 185, 203, 0.4);
            color: white;
        }

        /* Responsive */
        @media (max-width: 1200px) {
            .lightbox-wrapper {
                grid-template-columns: 1fr;
            }

            .lightbox-details {
                padding: 2rem;
            }

            .lightbox-image-side {
                padding: 1.5rem;
            }
        }

        @media (max-width: 992px) {
            .masonry-grid {
                column-count: 2;
            }

            .gallery-hero h1 {
                font-size: 3rem;
            }
        }

        @media (max-width: 768px) {
            .gallery-hero {
                padding: 100px 0 30px;
            }

            .gallery-hero h1 {
                font-size: 2.5rem;
            }

            .tab-navigation {
                padding: 20px 0;
            }

            .tab-btn {
                padding: 0.7rem 1.5rem;
                font-size: 0.9rem;
            }

            .masonry-grid {
                column-count: 1;
                column-gap: 1rem;
            }

            .masonry-item {
                margin-bottom: 1rem;
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

            .chatbot-float {
                bottom: 25px;
                right: 25px;
            }

            .chatbot-btn {
                width: 60px;
                height: 60px;
                font-size: 1.6rem;
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
        <div class="masonry-grid" id="galleryGrid">
            @foreach($galleryItems as $index => $item)
            <div class="masonry-item" data-category="{{ $item->category_name }}" style="animation-delay: {{ ($index * 0.05) }}s">
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
            const items = document.querySelectorAll('.masonry-item');

            items.forEach(item => {
                const category = item.getAttribute('data-category');
                if (filter === 'all' || category === filter) {
                    item.style.display = 'inline-block';
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