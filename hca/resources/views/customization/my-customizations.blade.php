<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Customizations - Flower Shop</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Crimson+Pro:wght@600;700&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background: #FAFAFA;
            color: #1F1F1F;
            line-height: 1.6;
            padding: 0;
            overflow-x: hidden;
        }

        /* Navigation Bar */
        .nav-bar {
            background: white;
            border-bottom: 1px solid #E8E8E8;
            padding: 12px 0;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .nav-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 32px;
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .nav-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            background: #F5F5F5;
            color: #1F1F1F;
            border: none;
            border-radius: 6px;
            font-size: 0.875rem;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .nav-btn:hover {
            background: #FF1744;
            color: white;
        }

        .nav-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: #1F1F1F;
            margin-left: auto;
        }

        /* Top Bar */
        .top-bar {
            background: linear-gradient(90deg, #FF1744 0%, #F50057 50%, #FF4081 100%);
            height: 3px;
            width: 100%;
        }

        /* Main Container */
        .main-wrapper {
            max-width: 1400px;
            margin: 0 auto;
            padding: 28px 32px 40px;
        }

        /* Page Header */
        .page-header {
            margin-bottom: 28px;
            text-align: center;
        }

        .page-title {
            font-family: 'Crimson Pro', serif;
            font-size: 2.25rem;
            font-weight: 700;
            color: #1F1F1F;
            margin-bottom: 6px;
            letter-spacing: -1px;
        }

        .page-subtitle {
            font-size: 0.95rem;
            color: #6B6B6B;
            font-weight: 500;
        }

        /* Alerts */
        .alert {
            max-width: 800px;
            margin: 0 auto 24px;
            padding: 12px 20px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 600;
            font-size: 0.875rem;
            animation: slideDown 0.4s ease;
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .alert-success {
            background: #E8F5E9;
            color: #2E7D32;
            border-left: 3px solid #4CAF50;
        }

        .alert-info {
            background: #E3F2FD;
            color: #1565C0;
            border-left: 3px solid #2196F3;
        }

        .alert-icon {
            font-size: 1.2rem;
        }

        /* Grid */
        .cards-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
            gap: 24px;
            margin-bottom: 40px;
        }

        /* Card */
        .custom-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            transition: all 0.3s ease;
            border: 1px solid #E8E8E8;
            position: relative;
        }

        .custom-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.08);
            border-color: #FF1744;
        }

        /* Image Section */
        .card-img-section {
            position: relative;
            height: 220px;
            overflow: hidden;
            background: #F5F5F5;
        }

        .card-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .custom-card:hover .card-img {
            transform: scale(1.05);
        }

        .img-overlay {
            position: absolute;
            top: 12px;
            right: 12px;
        }

        /* Status Badge */
        .badge {
            padding: 6px 14px;
            border-radius: 50px;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: inline-block;
        }

        .badge-pending {
            background: #FFF3E0;
            color: #E65100;
        }

        .badge-approved {
            background: #E8F5E9;
            color: #2E7D32;
        }

        .badge-rejected {
            background: #FFEBEE;
            color: #C62828;
        }

        .badge-completed {
            background: #E8EAF6;
            color: #3F51B5;
        }

        /* Card Content */
        .card-content {
            padding: 20px;
        }

        .card-header {
            margin-bottom: 14px;
        }

        .card-name {
            font-family: 'Crimson Pro', serif;
            font-size: 1.35rem;
            font-weight: 700;
            color: #1F1F1F;
            margin-bottom: 6px;
            line-height: 1.2;
        }

        .card-badge-mobile {
            display: none;
        }

        .card-info {
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin-bottom: 16px;
            padding-bottom: 16px;
            border-bottom: 1px solid #F0F0F0;
        }

        .info-row {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.875rem;
            color: #6B6B6B;
        }

        .info-icon {
            width: 18px;
            text-align: center;
            font-size: 1rem;
        }

        .info-label {
            font-weight: 600;
            color: #1F1F1F;
            min-width: 70px;
        }

        /* Price Section */
        .price-section {
            margin-bottom: 16px;
        }

        .price-big {
            font-size: 1.875rem;
            font-weight: 800;
            color: #FF1744;
            line-height: 1;
            margin-bottom: 4px;
        }

        .price-label {
            font-size: 0.8rem;
            color: #6B6B6B;
            font-weight: 600;
        }

        /* Admin Price Box */
        .admin-box {
            background: linear-gradient(135deg, #FFF9C4 0%, #FFF59D 100%);
            border-radius: 8px;
            padding: 16px;
            margin-bottom: 14px;
            border: 2px solid #FFD54F;
        }

        .admin-box-title {
            font-size: 0.75rem;
            font-weight: 700;
            color: #F57F17;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }

        .admin-box-price {
            font-size: 1.75rem;
            font-weight: 800;
            color: #E65100;
            line-height: 1;
        }

        /* Status Box */
        .status-box {
            background: #F5F5F5;
            border-radius: 6px;
            padding: 14px;
            margin-bottom: 14px;
            border-left: 3px solid;
        }

        .status-box-pending {
            border-color: #FFA726;
            background: #FFF3E0;
        }

        .status-box-rejected {
            border-color: #EF5350;
            background: #FFEBEE;
        }

        .status-box-ordered {
            border-color: #42A5F5;
            background: #E3F2FD;
        }

        .status-box-content {
            display: flex;
            align-items: start;
            gap: 10px;
        }

        .status-icon {
            font-size: 1.2rem;
            flex-shrink: 0;
        }

        .status-text {
            font-size: 0.875rem;
            font-weight: 600;
            color: #424242;
        }

        /* Note Box */
        .note-box {
            background: #F5F5F5;
            border-radius: 6px;
            padding: 12px;
            margin-top: 12px;
        }

        .note-label {
            font-size: 0.75rem;
            font-weight: 700;
            color: #424242;
            margin-bottom: 4px;
        }

        .note-text {
            font-size: 0.85rem;
            color: #6B6B6B;
            line-height: 1.5;
        }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            font-family: 'Outfit', sans-serif;
            font-size: 0.875rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            white-space: nowrap;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.12);
        }

        .btn-pink {
            background: #FF1744;
            color: white;
        }

        .btn-pink:hover {
            background: #D50000;
        }

        .btn-green {
            background: #00C853;
            color: white;
        }

        .btn-green:hover {
            background: #00A344;
        }

        .btn-blue {
            background: #2979FF;
            color: white;
        }

        .btn-blue:hover {
            background: #2962FF;
        }

        .btn-outline {
            background: transparent;
            color: #FF1744;
            border: 2px solid #FF1744;
        }

        .btn-outline:hover {
            background: #FF1744;
            color: white;
        }

        .btn-full {
            width: 100%;
        }

        .btn-group {
            display: flex;
            gap: 10px;
            margin-top: 16px;
        }

        .btn-stack {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        /* Empty State */
        .empty-container {
            text-align: center;
            padding: 60px 32px;
            background: white;
            border-radius: 10px;
            border: 2px dashed #E0E0E0;
        }

        .empty-icon {
            font-size: 4rem;
            margin-bottom: 16px;
            opacity: 0.5;
        }

        .empty-title {
            font-family: 'Crimson Pro', serif;
            font-size: 2rem;
            font-weight: 700;
            color: #1F1F1F;
            margin-bottom: 10px;
        }

        .empty-text {
            font-size: 1rem;
            color: #6B6B6B;
            margin-bottom: 24px;
        }

        /* Responsive Design */

        /* Large Desktops */
        @media (min-width: 1600px) {
            .cards-container {
                grid-template-columns: repeat(auto-fill, minmax(360px, 1fr));
                gap: 28px;
            }
        }

        /* Standard Laptops */
        @media (max-width: 1400px) {
            .cards-container {
                grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
                gap: 20px;
            }
        }

        /* Small Laptops & Tablets */
        @media (max-width: 1024px) {
            .nav-container {
                padding: 0 24px;
            }

            .main-wrapper {
                padding: 24px;
            }

            .page-title {
                font-size: 2rem;
            }

            .cards-container {
                grid-template-columns: repeat(2, 1fr);
                gap: 20px;
            }

            .card-img-section {
                height: 200px;
            }
        }

        /* Tablets Portrait */
        @media (max-width: 768px) {
            .nav-container {
                padding: 0 20px;
            }

            .nav-title {
                display: none;
            }

            .main-wrapper {
                padding: 20px;
            }

            .page-header {
                margin-bottom: 20px;
            }

            .page-title {
                font-size: 1.875rem;
            }

            .page-subtitle {
                font-size: 0.9rem;
            }

            .cards-container {
                grid-template-columns: 1fr;
                gap: 16px;
            }

            .card-img-section {
                height: 220px;
            }

            .btn-group {
                flex-direction: column;
            }

            .btn-group .btn {
                width: 100%;
            }

            .empty-container {
                padding: 50px 24px;
            }
        }

        /* Mobile Phones */
        @media (max-width: 576px) {
            .nav-bar {
                padding: 10px 0;
            }

            .nav-container {
                padding: 0 16px;
            }

            .nav-btn {
                padding: 8px 14px;
                font-size: 0.8rem;
            }

            .main-wrapper {
                padding: 16px;
            }

            .page-title {
                font-size: 1.625rem;
            }

            .page-subtitle {
                font-size: 0.875rem;
            }

            .alert {
                padding: 10px 16px;
                font-size: 0.8rem;
            }

            .cards-container {
                gap: 14px;
            }

            .card-img-section {
                height: 200px;
            }

            .img-overlay {
                display: none;
            }

            .card-badge-mobile {
                display: block;
                margin-bottom: 12px;
            }

            .card-content {
                padding: 16px;
            }

            .card-name {
                font-size: 1.25rem;
            }

            .info-row {
                font-size: 0.8rem;
            }

            .info-label {
                min-width: 60px;
            }

            .price-big {
                font-size: 1.625rem;
            }

            .admin-box {
                padding: 14px;
            }

            .admin-box-price {
                font-size: 1.625rem;
            }

            .btn {
                padding: 10px 18px;
                font-size: 0.8rem;
            }

            .empty-container {
                padding: 40px 20px;
            }

            .empty-icon {
                font-size: 3rem;
            }

            .empty-title {
                font-size: 1.625rem;
            }

            .empty-text {
                font-size: 0.9rem;
            }
        }

        /* Small Mobile */
        @media (max-width: 375px) {
            .main-wrapper {
                padding: 14px;
            }

            .page-title {
                font-size: 1.5rem;
            }

            .card-img-section {
                height: 180px;
            }

            .card-content {
                padding: 14px;
            }

            .card-name {
                font-size: 1.125rem;
            }

            .price-big {
                font-size: 1.5rem;
            }

            .admin-box-price {
                font-size: 1.5rem;
            }

            .btn {
                padding: 9px 16px;
                font-size: 0.8rem;
            }
        }

        /* Animation */
        .custom-card {
            animation: fadeInUp 0.5s ease backwards;
        }

        .custom-card:nth-child(1) { animation-delay: 0.05s; }
        .custom-card:nth-child(2) { animation-delay: 0.1s; }
        .custom-card:nth-child(3) { animation-delay: 0.15s; }
        .custom-card:nth-child(4) { animation-delay: 0.2s; }
        .custom-card:nth-child(5) { animation-delay: 0.25s; }
        .custom-card:nth-child(6) { animation-delay: 0.3s; }

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
    </style>
</head>
<body>
{{-- Include Navigation Bar --}}
@include('components.customization-navbar')
<div class="top-bar"></div>

<div class="main-wrapper">
    
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">✨ My Custom Designs</h1>
        <p class="page-subtitle">View and manage all your personalized creations</p>
    </div>

    <!-- Alerts -->
    @if(session('success'))
        <div class="alert alert-success">
            <span class="alert-icon">✓</span>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('info'))
        <div class="alert alert-info">
            <span class="alert-icon">ℹ</span>
            <span>{{ session('info') }}</span>
        </div>
    @endif

    <!-- Cards Grid -->
    @if($customizations->count())
        <div class="cards-container">

            @foreach($customizations as $custom)
                <div class="custom-card">
                    
                    <!-- Image -->
                    <div class="card-img-section">
                        <img 
                            src="{{ $custom->custom_image ? asset('uploads/customizations/' . $custom->custom_image) : asset('asset/images/' . $custom->product->image) }}"
                            alt="{{ $custom->customization_name }}"
                            class="card-img"
                        >
                        <div class="img-overlay">
                            <span class="badge badge-{{ strtolower($custom->status) }}">
                                {{ $custom->status }}
                            </span>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="card-content">
                        
                        <div class="card-header">
                            <h3 class="card-name">{{ $custom->customization_name }}</h3>
                            <div class="card-badge-mobile">
                                <span class="badge badge-{{ strtolower($custom->status) }}">
                                    {{ $custom->status }}
                                </span>
                            </div>
                        </div>

                        <div class="card-info">
                            <div class="info-row">
                                <span class="info-icon">🌸</span>
                                <span class="info-label">Product</span>
                                <span>{{ $custom->product->name }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-icon">📅</span>
                                <span class="info-label">Created</span>
                                <span>{{ $custom->created_at->format('M d, Y') }}</span>
                            </div>
                        </div>

                        {{-- STATUS CONTENT --}}
                        @if($custom->isApproved() && !$custom->order_id)
                            {{-- APPROVED --}}
                            <div class="admin-box">
                                <div class="admin-box-title">✅ Final Price (Approved)</div>
                                <div class="admin-box-price">₱{{ number_format($custom->admin_price, 2) }}</div>
                                @if($custom->admin_notes)
                                    <div class="note-box">
                                        <div class="note-label">Note from Admin</div>
                                        <div class="note-text">{{ $custom->admin_notes }}</div>
                                    </div>
                                @endif
                            </div>

                            <div class="btn-stack">
                                <form action="{{ route('customization.proceed-checkout', $custom->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-green btn-full">
                                        🛒 Proceed to Checkout
                                    </button>
                                </form>

                                <form action="{{ route('customization.add-to-cart', $custom->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-blue btn-full">
                                        🛍️ Add to Cart
                                    </button>
                                </form>
                            </div>

                        @elseif($custom->isPending())
                            {{-- PENDING --}}
                            <div class="price-section">
                                <div class="price-big">₱{{ number_format($custom->product->price + $custom->total_price, 2) }}</div>
                                <div class="price-label">Estimated Price</div>
                            </div>

                            <div class="status-box status-box-pending">
                                <div class="status-box-content">
                                    <span class="status-icon">⏳</span>
                                    <span class="status-text">Waiting for admin review and final pricing</span>
                                </div>
                            </div>

                        @elseif($custom->isRejected())
                            {{-- REJECTED --}}
                            <div class="status-box status-box-rejected">
                                <div class="status-box-content">
                                    <span class="status-icon">❌</span>
                                    <div>
                                        <div class="status-text">Request Rejected</div>
                                        @if($custom->admin_notes)
                                            <div class="note-text" style="margin-top: 8px;">{{ $custom->admin_notes }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                        @elseif($custom->order_id)
                            {{-- ORDERED --}}
                            <div class="price-section">
                                <div class="price-big">₱{{ number_format($custom->admin_price, 2) }}</div>
                            </div>

                            <div class="status-box status-box-ordered">
                                <div class="status-box-content">
                                    <span class="status-icon">📦</span>
                                    <div class="status-text">Order Placed · Order #{{ $custom->order_id }}</div>
                                </div>
                            </div>
                        @endif

                        {{-- ACTION BUTTONS --}}
                        <div class="btn-group">
                            <a href="{{ route('customization.show', $custom->id) }}" class="btn btn-pink">
                                👁️ View
                            </a>

                            @if(!$custom->order_id && $custom->isPending())
                                <a href="{{ route('customization.edit', $custom->id) }}" class="btn btn-outline">
                                    ✏️ Edit
                                </a>
                            @endif
                        </div>

                    </div>
                </div>
            @endforeach

        </div>
    @else
        <!-- Empty State -->
        <div class="empty-container">
            <div class="empty-icon">🎨</div>
            <h2 class="empty-title">No Customizations Yet</h2>
            <p class="empty-text">Start creating your personalized flower arrangements!</p>
            <a href="{{ route('customization.create') }}" class="btn btn-pink">
                ✨ Start Customizing
            </a>
        </div>
    @endif

</div>

</body>
</html>