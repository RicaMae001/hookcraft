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
        /* ALL YOUR EXISTING CSS STYLES REMAIN EXACTLY THE SAME */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg,rgb(223, 206, 216) 0%, #fff5f9 100%);
            min-height: 100vh;
        }

        .page-header {
            background: linear-gradient(135deg,rgb(255, 191, 223) 0%,rgb(247, 113, 184) 100%);
            padding: 60px 0 ;
            margin-bottom: 50px;
            position: relative;
            overflow: hidden;
        }

        .page-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.1'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            opacity: 0.5;
        }

        .page-header h1 {
            color: white;
            font-weight: 700;
            font-size: 3rem;
            margin-bottom: 15px;
            position: relative;
            z-index: 1;
        }

        .page-header p {
            color: rgba(255, 255, 255, 0.9);
            font-size: 1.2rem;
            position: relative;
            z-index: 1;
        }

        .filter-section {
            margin-bottom: 40px;
        }

        .filter-btn {
            padding: 10px 25px;
            margin: 5px;
            border: 2px solid #ff69b4;
            background: white;
            color: #ff69b4;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .filter-btn:hover,
        .filter-btn.active {
            background: linear-gradient(135deg, #ff69b4 0%, #ff1493 100%);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 105, 180, 0.3);
        }

        .gallery-container {
            padding: 0 0 60px;
        }

        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 25px;
            margin-top: 30px;
        }

        .gallery-item {
            position: relative;
            overflow: hidden;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            transition: all 0.4s ease;
            background: white;
            cursor: pointer;
        }

        .gallery-item:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 35px rgba(255, 105, 180, 0.3);
        }

        .gallery-item img {
            width: 100%;
            height: 300px;
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
            background: linear-gradient(transparent, rgba(0, 0, 0, 0.8));
            padding: 20px;
            transform: translateY(100%);
            transition: transform 0.4s ease;
        }

        .gallery-item:hover .gallery-overlay {
            transform: translateY(0);
        }

        .gallery-overlay h5 {
            color: white;
            margin: 0 0 5px 0;
            font-size: 1.1rem;
        }

        .gallery-overlay p {
            color: rgba(255, 255, 255, 0.8);
            margin: 0;
            font-size: 0.9rem;
        }

        .gallery-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: linear-gradient(135deg, #ff69b4 0%, #ff1493 100%);
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            z-index: 2;
        }

        .lightbox-modal .modal-dialog {
            max-width: 90%;
            margin: 2rem auto;
        }

        .lightbox-modal .modal-content {
            background: rgba(0, 0, 0, 0.95);
            border: none;
            border-radius: 0;
        }

        .lightbox-modal .modal-body {
            padding: 0;
            position: relative;
        }

        .lightbox-image {
            width: 100%;
            height: auto;
            max-height: 80vh;
            object-fit: contain;
        }

        .lightbox-info {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(transparent, rgba(0, 0, 0, 0.9));
            padding: 30px;
            color: white;
        }

        .lightbox-nav {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: none;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            font-size: 1.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .lightbox-nav:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .lightbox-nav.prev {
            left: 20px;
        }

        .lightbox-nav.next {
            right: 20px;
        }

        .lightbox-close {
            position: absolute;
            top: 20px;
            right: 20px;
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            font-size: 1.2rem;
            cursor: pointer;
            z-index: 10;
        }

        .fade-in-up {
            animation: fadeInUp 0.6s ease forwards;
            opacity: 0;
        }

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

        .no-gallery {
            text-align: center;
            padding: 60px 20px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }

        .no-gallery i {
            font-size: 4rem;
            color: #ff69b4;
            margin-bottom: 20px;
        }
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
        margin-bottom: 100px;
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
    </style>
</head>
<body>

@include('components.login_modal')
@include('components.signup_modal')

<!-- Navbar -->
@include('components.navbar')


<div class="chatbot-float">
    <a href="{{ route('chatbot') }}" class="chatbot-pulse" title="Chat with AI Assistant">
        <i class="bi bi-robot"></i>
    </a>
</div>

<!-- Page Header -->
<div class="page-header text-center">
    <div class="container">
        <h1><i class="bi bi-images me-3"></i>Our Gallery</h1>
        <p>Explore our beautiful collection of handcrafted Products</p>
    </div>
</div>

<!-- Gallery Section -->
<div class="gallery-container">
    <div class="container">
        <!-- Filter Buttons -->
        <div class="filter-section text-center">
            <button class="filter-btn active" data-filter="all">All Collections</button>
            <button class="filter-btn" data-filter="birthday">Birthday Bouquets</button>
            <button class="filter-btn" data-filter="casual">Casual Bouquets</button>
            <button class="filter-btn" data-filter="tiny">Tiny Bouquets</button>
            <button class="filter-btn" data-filter="wedding">Wedding Arrangements</button>
            <button class="filter-btn" data-filter="custom">Custom Creations</button>
        </div>

        <!-- Gallery Grid -->
        @if($galleryItems->count() > 0)
        <div class="gallery-grid" id="galleryGrid">
            @foreach($galleryItems as $index => $item)
            <div class="gallery-item fade-in-up" data-category="{{ $item->category_name }}" style="animation-delay: {{ ($index * 0.1) }}s">
                <span class="gallery-badge">{{ $item->category_name }}</span>
                <!-- UPDATED: Use same logic as product page -->
                <img src="{{ asset('asset/images/' . $item->image_path) }}" alt="{{ $item->title }}" data-index="{{ $index }}">
                <div class="gallery-overlay">
                    <h5>{{ $item->title }}</h5>
                    <p>{{ $item->description }}</p>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="no-gallery">
            <i class="bi bi-images"></i>
            <h3>No Gallery Images Yet</h3>
            <p class="text-muted">Our beautiful collection is coming soon!</p>
        </div>
        @endif
    </div>
</div>

<!-- Lightbox Modal -->
<div class="modal fade lightbox-modal" id="lightboxModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <button class="lightbox-close" data-bs-dismiss="modal">&times;</button>
                <button class="lightbox-nav prev" onclick="navigateLightbox(-1)">
                    <i class="bi bi-chevron-left"></i>
                </button>
                <button class="lightbox-nav next" onclick="navigateLightbox(1)">
                    <i class="bi bi-chevron-right"></i>
                </button>
                <!-- UPDATED: Use same logic as product page -->
                <img src="" alt="" class="lightbox-image" id="lightboxImage">
                <div class="lightbox-info">
                    <h4 id="lightboxTitle"></h4>
                    <p id="lightboxDescription"></p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Footer -->
@include('components.footer')

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script>
    const galleryItems = @json($galleryItems);
    let currentLightboxIndex = 0;
    const lightboxModal = new bootstrap.Modal(document.getElementById('lightboxModal'));

    // Filter functionality
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            const filter = this.getAttribute('data-filter');
            const items = document.querySelectorAll('.gallery-item');

            items.forEach((item, index) => {
                const category = item.getAttribute('data-category');
                if (filter === 'all' || category === filter) {
                    item.style.display = 'block';
                    item.style.animation = `fadeInUp 0.6s ease ${index * 0.05}s forwards`;
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
            // UPDATED: Use same logic as product page
            document.getElementById('lightboxImage').src = "{{ asset('asset/images') }}/" + item.image_path;
            document.getElementById('lightboxTitle').textContent = item.title;
            document.getElementById('lightboxDescription').textContent = item.description;
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