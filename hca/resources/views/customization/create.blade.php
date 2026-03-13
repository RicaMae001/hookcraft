<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Custom Bouquet - Hookcraft Avenue</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>
        :root {
            --rose:       #E8637A;
            --rose-light: #F4A7B5;
            --rose-pale:  #FDF0F3;
            --rose-soft:  #FAD9E0;
            --petal:      #FFF5F7;
            --sage:       #8BAF8E;
            --cream:      #FDFAF7;
            --ink:        #2C2038;
            --muted:      #8C7A88;
            --border:     #EDD9E0;
            --shadow:     0 4px 24px rgba(44,32,56,0.07);
            --shadow-md:  0 8px 40px rgba(44,32,56,0.11);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        html, body { overflow-x: hidden; }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--cream);
            min-height: 100vh;
            color: var(--ink);
        }

        /* ── Decorative background blobs ── */
        body::before {
            content: '';
            position: fixed; inset: 0;
            background:
                radial-gradient(ellipse 600px 400px at 10% 20%, rgba(244,167,181,0.12) 0%, transparent 70%),
                radial-gradient(ellipse 500px 350px at 90% 80%, rgba(139,175,142,0.08) 0%, transparent 70%);
            pointer-events: none; z-index: 0;
        }

        nav { position: sticky; top: 0; width: 100%; z-index: 9999; }

        .main-container {
            max-width: 1160px;
            margin: 0 auto;
            padding: 40px 24px 80px;
            position: relative; z-index: 1;
        }

        /* ── Page Header ── */
        .page-header { text-align: center; margin-bottom: 48px; }
        .page-header .eyebrow {
            font-family: 'DM Sans', sans-serif;
            font-size: 11px; font-weight: 600; letter-spacing: 3px;
            text-transform: uppercase; color: var(--rose); margin-bottom: 12px;
        }
        .page-header h1 {
            font-family: 'Playfair Display', serif;
            font-size: clamp(2rem, 5vw, 3.2rem);
            font-weight: 700; color: var(--ink);
            line-height: 1.15; margin-bottom: 14px;
        }
        .page-header p { color: var(--muted); font-size: 1rem; font-weight: 400; }

        /* ── Progress Steps ── */
        .progress-steps {
            display: flex; align-items: center; justify-content: center;
            gap: 0; margin-bottom: 48px;
        }
        .progress-step {
            display: flex; align-items: center; gap: 10px;
            padding: 12px 24px; background: white;
            border: 1.5px solid var(--border);
            font-size: 13px; font-weight: 500; color: var(--muted);
            transition: all 0.3s;
        }
        .progress-step:first-child { border-radius: 50px 0 0 50px; }
        .progress-step:last-child  { border-radius: 0 50px 50px 0; }
        .progress-step.active {
            background: var(--rose); border-color: var(--rose);
            color: white; font-weight: 600;
        }
        .step-num {
            width: 22px; height: 22px; border-radius: 50%;
            background: rgba(255,255,255,0.25);
            display: flex; align-items: center; justify-content: center;
            font-size: 11px; font-weight: 700; flex-shrink: 0;
        }
        .progress-step:not(.active) .step-num {
            background: var(--rose-pale); color: var(--rose);
        }
        .step-divider { width: 1px; height: 40px; background: var(--border); }

        /* ── Layout Grid ── */
        .form-grid { display: grid; grid-template-columns: 1fr 360px; gap: 28px; }

        /* ── Card ── */
        .card {
            background: white; border-radius: 24px;
            border: 1.5px solid var(--border);
            box-shadow: var(--shadow);
            overflow: hidden;
        }
        .card-body { padding: 36px; }
        .card-header {
            padding: 20px 36px;
            border-bottom: 1.5px solid var(--rose-pale);
            display: flex; align-items: center; gap: 12px;
        }
        .card-header-icon {
            width: 36px; height: 36px; border-radius: 10px;
            background: var(--rose-pale);
            display: flex; align-items: center; justify-content: center;
            color: var(--rose); font-size: 15px;
        }
        .card-header h3 {
            font-family: 'Playfair Display', serif;
            font-size: 1.15rem; font-weight: 600; color: var(--ink);
        }

        /* ── Section label ── */
        .section-label {
            font-size: 10px; font-weight: 700; letter-spacing: 2.5px;
            text-transform: uppercase; color: var(--rose);
            margin-bottom: 20px; display: flex; align-items: center; gap: 8px;
        }
        .section-label::after {
            content: ''; flex: 1; height: 1px; background: var(--rose-soft);
        }

        /* ── Category Grid ── */
        .category-grid {
            display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 14px; margin-bottom: 20px;
        }
        .category-card {
            background: white; border: 1.5px solid var(--border); border-radius: 18px;
            overflow: hidden; cursor: pointer; transition: all 0.25s ease;
            text-decoration: none; color: inherit; display: block;
        }
        .category-card:hover {
            border-color: var(--rose-light); transform: translateY(-3px);
            box-shadow: var(--shadow-md);
        }
        .category-img-wrap { width: 100%; aspect-ratio: 4/3; overflow: hidden; position: relative; }
        .category-img-wrap img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.4s; }
        .category-card:hover .category-img-wrap img { transform: scale(1.05); }
        .category-img-fallback {
            width: 100%; height: 100%; display: flex; align-items: center;
            justify-content: center; font-size: 2.5rem; background: var(--rose-pale);
            position: absolute; inset: 0;
        }
        .category-body { padding: 14px 16px; }
        .category-name { font-weight: 600; font-size: 0.95rem; color: var(--ink); margin-bottom: 3px; }
        .category-count { font-size: 12px; color: var(--muted); }

        /* ── Product Preview ── */
        .product-preview {
            background: var(--rose-pale); border-radius: 16px; padding: 20px;
            margin-bottom: 28px; display: flex; gap: 16px; align-items: center;
        }
        .product-preview img { width: 80px; height: 80px; object-fit: cover; border-radius: 12px; }
        .product-preview-info h4 { font-weight: 600; font-size: 1rem; color: var(--ink); margin-bottom: 4px; }
        .product-preview-price { color: var(--rose); font-weight: 700; font-size: 1.1rem; margin-bottom: 4px; }
        .product-preview-desc { color: var(--muted); font-size: 12px; line-height: 1.5; }
        .reference-note {
            background: rgba(232,99,122,0.07); border-left: 3px solid var(--rose);
            padding: 12px 14px; border-radius: 8px; margin-top: 12px;
            color: #7a3245; font-size: 12px; line-height: 1.6;
        }

        /* ── Form Elements ── */
        .form-group { margin-bottom: 22px; }
        .form-label {
            display: block; font-size: 13px; font-weight: 600;
            color: var(--ink); margin-bottom: 8px;
        }
        .form-label.required::after { content: " *"; color: var(--rose); }
        .form-input, .form-select, .form-textarea {
            width: 100%; padding: 13px 16px;
            border: 1.5px solid var(--border); border-radius: 12px;
            font-size: 14px; font-family: 'DM Sans', sans-serif;
            transition: all 0.2s; background: white; color: var(--ink);
        }
        .form-input:focus, .form-select:focus, .form-textarea:focus {
            outline: none; border-color: var(--rose);
            box-shadow: 0 0 0 3px rgba(232,99,122,0.1);
        }
        .form-textarea { min-height: 130px; resize: vertical; line-height: 1.6; }

        /* ══════════════════════════════════════
           ADD-ONS — NEW DESIGN
        ══════════════════════════════════════ */
        .addons-section {
            margin-top: 36px;
            border: 1.5px solid var(--border);
            border-radius: 24px;
            overflow: hidden;
        }
        .addons-header {
            background: linear-gradient(135deg, var(--rose) 0%, #C94E65 100%);
            padding: 20px 28px;
            display: flex; align-items: center; justify-content: space-between;
        }
        .addons-header-left { display: flex; align-items: center; gap: 12px; }
        .addons-header-icon {
            width: 40px; height: 40px; border-radius: 12px;
            background: rgba(255,255,255,0.2);
            display: flex; align-items: center; justify-content: center;
            font-size: 18px; color: white;
        }
        .addons-header-text h4 {
            font-family: 'Playfair Display', serif;
            font-size: 1.1rem; color: white; font-weight: 700;
        }
        .addons-header-text p { font-size: 12px; color: rgba(255,255,255,0.75); margin-top: 2px; }
        .addons-badge {
            background: rgba(255,255,255,0.2); color: white;
            border-radius: 20px; padding: 4px 12px; font-size: 12px; font-weight: 600;
        }
        .addons-body { padding: 24px 28px; }

        /* Tab Pills */
        .addon-tabs {
            display: flex; gap: 6px; flex-wrap: wrap;
            margin-bottom: 22px; border-bottom: 1.5px solid var(--border);
            padding-bottom: 16px;
        }
        .addon-tab {
            padding: 8px 18px; border-radius: 50px;
            border: 1.5px solid var(--border); background: white;
            color: var(--muted); font-size: 13px; font-weight: 500;
            cursor: pointer; transition: all 0.2s; white-space: nowrap;
            display: flex; align-items: center; gap: 6px;
        }
        .addon-tab:hover { border-color: var(--rose-light); color: var(--rose); }
        .addon-tab.active {
            background: var(--rose); border-color: var(--rose);
            color: white; font-weight: 600;
        }
        .addon-tab-count {
            min-width: 18px; height: 18px; border-radius: 9px;
            background: rgba(255,255,255,0.3); font-size: 10px; font-weight: 700;
            display: inline-flex; align-items: center; justify-content: center;
            padding: 0 5px;
        }
        .addon-tab:not(.active) .addon-tab-count {
            background: var(--rose-pale); color: var(--rose);
        }

        /* Item Grid — new card style */
        .addon-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
            gap: 10px;
            margin-bottom: 20px;
        }
        .addon-item {
            position: relative; cursor: pointer;
            background: var(--cream); border: 1.5px solid var(--border);
            border-radius: 16px; padding: 16px 12px 14px;
            text-align: center; transition: all 0.2s; user-select: none;
            overflow: hidden;
        }
        .addon-item::before {
            content: '';
            position: absolute; inset: 0;
            background: linear-gradient(135deg, var(--rose-pale), var(--petal));
            opacity: 0; transition: opacity 0.2s;
        }
        .addon-item:hover::before { opacity: 1; }
        .addon-item:hover { border-color: var(--rose-light); transform: translateY(-2px); box-shadow: var(--shadow); }
        .addon-item.selected {
            border-color: var(--rose); background: white;
        }
        .addon-item.selected::before { opacity: 1; }

        .addon-check-ring {
            position: absolute; top: 9px; right: 9px;
            width: 20px; height: 20px; border-radius: 50%;
            border: 1.5px solid var(--border);
            background: white;
            display: flex; align-items: center; justify-content: center;
            font-size: 9px; color: transparent;
            transition: all 0.2s; z-index: 1;
        }
        .addon-item.selected .addon-check-ring {
            background: var(--rose); border-color: var(--rose);
            color: white;
        }

        .addon-emoji {
            font-size: 2rem; margin-bottom: 8px; display: block;
            position: relative; z-index: 1; line-height: 1;
        }
        .addon-name {
            font-size: 12px; font-weight: 700; color: var(--ink);
            margin-bottom: 3px; line-height: 1.3; position: relative; z-index: 1;
        }
        .addon-material {
            font-size: 9px; font-weight: 600; color: var(--rose);
            text-transform: uppercase; letter-spacing: 0.5px;
            margin-bottom: 2px; position: relative; z-index: 1;
        }
        .addon-note {
            font-size: 10px; color: var(--muted); margin-bottom: 6px;
            position: relative; z-index: 1;
        }
        .addon-price {
            font-size: 14px; font-weight: 700; color: var(--rose);
            position: relative; z-index: 1;
        }

        /* Material badge */
        .material-badge {
            display: inline-block;
            background: var(--rose-pale);
            color: var(--rose);
            font-size: 8px;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 12px;
            margin-bottom: 4px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            position: relative; z-index: 1;
        }

        /* Qty controls inside card */
        .addon-qty-wrap {
            display: none; align-items: center; justify-content: center;
            gap: 8px; margin-top: 10px; position: relative; z-index: 1;
        }
        .addon-item.selected .addon-qty-wrap { display: flex; }
        .qty-btn {
            width: 26px; height: 26px; border-radius: 8px;
            border: 1.5px solid var(--rose-light); background: white;
            color: var(--rose); font-size: 15px; font-weight: 700;
            cursor: pointer; display: flex; align-items: center; justify-content: center;
            transition: all 0.15s; line-height: 1; flex-shrink: 0;
        }
        .qty-btn:hover { background: var(--rose); color: white; border-color: var(--rose); }
        .qty-display {
            font-size: 14px; font-weight: 700; color: var(--ink);
            min-width: 22px; text-align: center;
        }

        #addonHiddenInputs { display: none; }

        /* Selected Summary Panel */
        .addon-summary-panel {
            background: var(--petal); border-radius: 16px;
            border: 1.5px dashed var(--rose-light);
            padding: 18px 20px;
        }
        .addon-summary-header {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 14px;
        }
        .addon-summary-title {
            font-size: 11px; font-weight: 700; color: var(--rose);
            letter-spacing: 1.5px; text-transform: uppercase;
        }
        .addon-summary-empty {
            color: var(--muted); font-size: 13px; text-align: center;
            padding: 12px 0; font-style: italic;
        }
        .addon-tags-wrap { display: flex; flex-wrap: wrap; gap: 7px; margin-bottom: 12px; }
        .addon-tag {
            display: inline-flex; align-items: center; gap: 6px;
            background: white; border: 1.5px solid var(--rose-light);
            border-radius: 30px; padding: 5px 10px 5px 12px;
            font-size: 12px; font-weight: 600; color: var(--rose);
        }
        .remove-tag {
            cursor: pointer; color: var(--muted); font-size: 10px;
            width: 16px; height: 16px; border-radius: 50%;
            background: var(--rose-pale);
            display: flex; align-items: center; justify-content: center;
            transition: all 0.15s; flex-shrink: 0;
        }
        .remove-tag:hover { background: var(--rose); color: white; }
        .addon-total-line {
            display: flex; justify-content: space-between; align-items: center;
            padding-top: 12px; border-top: 1.5px solid var(--rose-soft);
            margin-top: 4px;
        }
        .addon-total-label { font-size: 13px; font-weight: 600; color: var(--muted); }
        .addon-total-value { font-size: 1.15rem; font-weight: 800; color: var(--rose); }

        .addon-disclaimer {
            margin-top: 16px; padding: 12px 14px;
            background: rgba(232,99,122,0.06);
            border-radius: 10px; border-left: 3px solid var(--rose-light);
            font-size: 12px; color: var(--muted); line-height: 1.6;
        }

        /* ── Image Upload ── */
        .upload-area {
            border: 2px dashed var(--rose-light); border-radius: 20px;
            padding: 44px 24px; text-align: center; cursor: pointer;
            transition: all 0.25s; background: var(--rose-pale); margin-bottom: 16px;
        }
        .upload-area:hover { background: var(--rose-soft); border-color: var(--rose); }
        .upload-area input { display: none; }
        .upload-icon { font-size: 3rem; color: var(--rose-light); margin-bottom: 12px; }
        .upload-text { font-size: 1rem; font-weight: 600; color: var(--ink); margin-bottom: 6px; }
        .upload-subtext { color: var(--muted); font-size: 13px; }
        .preview-box { display: none; position: relative; border-radius: 18px; overflow: hidden; box-shadow: var(--shadow); }
        .preview-image { width: 100%; height: auto; display: block; }
        .remove-image-btn {
            position: absolute; top: 12px; right: 12px;
            background: rgba(255,255,255,0.95); border: none;
            width: 36px; height: 36px; border-radius: 50%; cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.1rem; color: #dc2626; transition: all 0.2s;
            box-shadow: 0 2px 8px rgba(0,0,0,0.12);
        }
        .remove-image-btn:hover { background: #dc2626; color: white; }

        /* ── Buttons ── */
        .btn {
            padding: 14px 28px; border: none; border-radius: 12px;
            font-size: 0.95rem; font-weight: 600; cursor: pointer;
            transition: all 0.25s; display: inline-flex; align-items: center;
            gap: 9px; text-decoration: none; font-family: 'DM Sans', sans-serif;
        }
        .btn-primary {
            background: linear-gradient(135deg, var(--rose) 0%, #C94E65 100%);
            color: white; box-shadow: 0 4px 16px rgba(232,99,122,0.35);
        }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(232,99,122,0.45); }
        .btn-primary:disabled { opacity: 0.6; cursor: not-allowed; transform: none; }

        /* ── Sidebar ── */
        .sidebar { display: flex; flex-direction: column; gap: 20px; }
        .sidebar .card-body { padding: 28px; }

        .info-list { list-style: none; }
        .info-list li {
            display: flex; gap: 10px; padding: 10px 0;
            border-bottom: 1px solid var(--rose-pale);
            color: var(--muted); font-size: 13px; line-height: 1.6;
        }
        .info-list li:last-child { border-bottom: none; }
        .info-list i { color: var(--rose); margin-top: 3px; flex-shrink: 0; font-size: 12px; }

        .estimate-card {
            background: linear-gradient(135deg, #FFF0F3, #FDE8ED);
            border: 1.5px solid var(--rose-light);
            border-radius: 20px; padding: 24px;
        }
        .estimate-card-header {
            display: flex; align-items: center; gap: 10px;
            margin-bottom: 16px;
        }
        .estimate-card-header i { color: var(--rose); }
        .estimate-card-header span {
            font-family: 'Playfair Display', serif;
            font-size: 1rem; font-weight: 600; color: var(--ink);
        }
        .estimate-total {
            font-family: 'Playfair Display', serif;
            font-size: 2.2rem; font-weight: 700;
            color: var(--rose); text-align: center; padding: 10px 0 6px;
        }
        .estimate-note { font-size: 11px; color: var(--muted); text-align: center; line-height: 1.6; margin-top: 4px; }
        .estimate-lines { margin-bottom: 6px; }
        .estimate-line {
            display: flex; justify-content: space-between;
            font-size: 12px; color: var(--muted);
            padding: 4px 0; border-bottom: 1px dashed var(--rose-soft);
        }

        .alert {
            padding: 14px 18px; border-radius: 12px;
            display: flex; align-items: flex-start; gap: 12px; margin-bottom: 16px;
            font-size: 13px;
        }
        .alert-error { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }
        .alert-success { background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }
        .alert i { margin-top: 1px; flex-shrink: 0; }

        @media (max-width: 968px) {
            .form-grid { grid-template-columns: 1fr; }
            .progress-steps { flex-wrap: wrap; }
            .btn { width: 100%; justify-content: center; }
        }
        @media (max-width: 600px) {
            .category-grid { grid-template-columns: repeat(2,1fr); }
            .addon-grid    { grid-template-columns: repeat(2,1fr); }
        }
    </style>
</head>
<body>
    @include('components.customization-navbar')

    <div class="main-container">

        <div class="page-header">
            <div class="eyebrow">Hookcraft Avenue</div>
            <h1>Request a Custom Bouquet</h1>
            <p>Design something beautiful — our florists will bring it to life</p>
        </div>

        <div class="progress-steps">
            <div class="progress-step {{ !$category ? 'active' : '' }}">
                <div class="step-num">1</div>
                <span>Category</span>
            </div>
            <div class="step-divider"></div>
            <div class="progress-step {{ $category ? 'active' : '' }}">
                <div class="step-num">2</div>
                <span>Design Details</span>
            </div>
            <div class="step-divider"></div>
            <div class="progress-step">
                <div class="step-num">3</div>
                <span>Submit</span>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success"><i class="fas fa-check-circle"></i><div>{{ session('success') }}</div></div>
        @endif
        @if(session('error'))
            <div class="alert alert-error"><i class="fas fa-exclamation-circle"></i><div>{{ session('error') }}</div></div>
        @endif
        @if($errors->any())
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <div><strong>Please fix the following errors:</strong>
                    <ul style="margin-top:8px;padding-left:18px;">
                        @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
            </div>
        @endif

        <div class="form-grid">
            <!-- MAIN COLUMN -->
            <div>
                @if(!$category)
                {{-- STEP 1: CATEGORY --}}
                <div class="card">
                    <div class="card-header">
                        <div class="card-header-icon"><i class="fas fa-th-large"></i></div>
                        <h3>Choose a Category</h3>
                    </div>
                    <div class="card-body">
                        <p style="color:var(--muted);font-size:14px;margin-bottom:24px;">
                            Pick a category to get started with your custom arrangement.
                        </p>
                        <div class="category-grid">
                            @foreach($categories as $cat)
                                @php $refProduct = $cat->products->first(); @endphp
                                <a href="{{ route('customization.create', ['category_id' => $cat->id]) }}" class="category-card">
                                    <div class="category-img-wrap">
                                        @if($refProduct && $refProduct->image)
                                            <img src="{{ asset('asset/images/' . $refProduct->image) }}" alt="{{ $cat->name }}"
                                                 onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
                                            <div class="category-img-fallback" style="display:none;">🌸</div>
                                        @else
                                            <div class="category-img-fallback">🌸</div>
                                        @endif
                                    </div>
                                    <div class="category-body">
                                        <div class="category-name">{{ $cat->name }}</div>
                                        <div class="category-count">{{ $cat->products->count() }} products</div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>

                @else
                {{-- STEP 2: CUSTOMIZATION FORM --}}
                <form action="{{ route('customization.store') }}" method="POST" enctype="multipart/form-data" id="customizationForm">
                    @csrf
                    <input type="hidden" name="category_id" value="{{ $category->id }}">
                    <input type="hidden" name="product_id"  value="{{ $referenceProduct->id }}">

                    <!-- Product Reference -->
                    <div class="card" style="margin-bottom:20px;">
                        <div class="card-body">
                            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
                                <div class="section-label">Reference</div>
                                <a href="{{ route('customization.create') }}" style="color:var(--rose);text-decoration:none;font-size:12px;font-weight:600;">
                                    <i class="fas fa-sync-alt"></i> Change Category
                                </a>
                            </div>
                            <div class="product-preview">
                                <img src="{{ asset('asset/images/' . $referenceProduct->image) }}"
                                     alt="{{ $referenceProduct->name }}"
                                     onerror="this.src='{{ asset('images/placeholder.jpg') }}'">
                                <div class="product-preview-info">
                                    <h4>{{ $referenceProduct->name }}</h4>
                                    <div class="product-preview-price">₱{{ number_format($referenceProduct->price, 2) }}</div>
                                    @if($referenceProduct->description)
                                        <div class="product-preview-desc">{{ Str::limit($referenceProduct->description, 90) }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="reference-note">
                                <i class="fas fa-info-circle"></i>
                                <strong> Note:</strong> This product is a visual reference. Your custom design will be created by our florists based on your description.
                            </div>
                        </div>
                    </div>

                    <!-- Design Details -->
                    <div class="card" style="margin-bottom:20px;">
                        <div class="card-header">
                            <div class="card-header-icon"><i class="fas fa-paint-brush"></i></div>
                            <h3>Describe Your Vision</h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label class="form-label required">Customization Name</label>
                                <input type="text" class="form-input" name="customization_name"
                                       placeholder="e.g., Romantic Red Rose Bouquet"
                                       required value="{{ old('customization_name') }}">
                            </div>
                            <div class="form-group">
                                <label class="form-label required">Detailed Description</label>
                                <textarea class="form-textarea" name="customization_details"
                                          placeholder="Tell us about your vision:&#10;• What flowers do you want?&#10;• Preferred colors and style?&#10;• Size and arrangement?&#10;• What's the occasion?"
                                          required>{{ old('customization_details') }}</textarea>
                            </div>
                            <div class="form-group" style="margin-bottom:0;">
                                <label class="form-label">Special Instructions <span style="font-weight:400;color:var(--muted);">(optional)</span></label>
                                <textarea class="form-textarea" name="special_instructions"
                                          placeholder="Delivery preferences, allergy notes, color restrictions..."
                                          style="min-height:90px;">{{ old('special_instructions') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- ════ ADD-ONS — WITH MATERIAL TYPES ════ -->
                    <div class="addons-section">
                        <div class="addons-header">
                            <div class="addons-header-left">
                                <div class="addons-header-icon">🌸</div>
                                <div class="addons-header-text">
                                    <h4>Add-Ons & Materials</h4>
                                    <p>Select items to help our florists understand your vision</p>
                                </div>
                            </div>
                            <span class="addons-badge" id="addonCountBadge">0 selected</span>
                        </div>

                        <div class="addons-body">
                            <!-- Tabs -->
                            <div class="addon-tabs">
                                <div class="addon-tab active" onclick="switchTab(this,'wrapper')">
                                    🎀 Wrappers
                                    <span class="addon-tab-count" data-tab="wrapper">0</span>
                                </div>
                                <div class="addon-tab" onclick="switchTab(this,'fuzzywire')">
                                    ✨ Fuzzy Wire Flowers
                                    <span class="addon-tab-count" data-tab="fuzzywire">0</span>
                                </div>
                                <div class="addon-tab" onclick="switchTab(this,'crochet')">
                                    🧶 Crochet Flowers
                                    <span class="addon-tab-count" data-tab="crochet">0</span>
                                </div>
                                <div class="addon-tab" onclick="switchTab(this,'satin')">
                                    🎀 Satin Flowers
                                    <span class="addon-tab-count" data-tab="satin">0</span>
                                </div>
                                <div class="addon-tab" onclick="switchTab(this,'extras')">
                                    ✨ Extras
                                    <span class="addon-tab-count" data-tab="extras">0</span>
                                </div>
                            </div>

                            <!-- ── WRAPPER TAB ── -->
                            <div class="addon-grid" id="tab-wrapper">
                                <div class="addon-item" onclick="toggleAddon(this)" data-name="Wrapper" data-price="50" data-key="w1" data-tab="wrapper" data-category="Wrappers" data-material="Wrapper">
                                    <div class="addon-check-ring"><i class="fas fa-check"></i></div>
                                    <span class="addon-emoji">🎀</span>
                                    <div class="addon-name">Wrapper</div>
                                    <div class="material-badge">WRAPPER</div>
                                    <div class="addon-note">Wrappers</div>
                                    <div class="addon-price">₱50</div>
                                    <div class="addon-qty-wrap">
                                        <button type="button" class="qty-btn" onclick="changeQty(event,this,-1)">−</button>
                                        <span class="qty-display">1</span>
                                        <button type="button" class="qty-btn" onclick="changeQty(event,this,1)">+</button>
                                    </div>
                                </div>
                                <div class="addon-item" onclick="toggleAddon(this)" data-name="Wrapper (2–4 stems)" data-price="100" data-key="w2" data-tab="wrapper" data-category="Wrappers" data-material="Wrapper">
                                    <div class="addon-check-ring"><i class="fas fa-check"></i></div>
                                    <span class="addon-emoji">🎀</span>
                                    <div class="addon-name">Wrapper (2–4 stems)</div>
                                    <div class="material-badge">WRAPPER</div>
                                    <div class="addon-note">Wrappers</div>
                                    <div class="addon-price">₱100</div>
                                    <div class="addon-qty-wrap">
                                        <button type="button" class="qty-btn" onclick="changeQty(event,this,-1)">−</button>
                                        <span class="qty-display">1</span>
                                        <button type="button" class="qty-btn" onclick="changeQty(event,this,1)">+</button>
                                    </div>
                                </div>
                                <div class="addon-item" onclick="toggleAddon(this)" data-name="Wrapper (5–7 stems)" data-price="150" data-key="w3" data-tab="wrapper" data-category="Wrappers" data-material="Wrapper">
                                    <div class="addon-check-ring"><i class="fas fa-check"></i></div>
                                    <span class="addon-emoji">🎀</span>
                                    <div class="addon-name">Wrapper (5–7 stems)</div>
                                    <div class="material-badge">WRAPPER</div>
                                    <div class="addon-note">Wrappers</div>
                                    <div class="addon-price">₱150</div>
                                    <div class="addon-qty-wrap">
                                        <button type="button" class="qty-btn" onclick="changeQty(event,this,-1)">−</button>
                                        <span class="qty-display">1</span>
                                        <button type="button" class="qty-btn" onclick="changeQty(event,this,1)">+</button>
                                    </div>
                                </div>
                                <div class="addon-item" onclick="toggleAddon(this)" data-name="Wrapper (8–12 stems)" data-price="200" data-key="w4" data-tab="wrapper" data-category="Wrappers" data-material="Wrapper">
                                    <div class="addon-check-ring"><i class="fas fa-check"></i></div>
                                    <span class="addon-emoji">🎀</span>
                                    <div class="addon-name">Wrapper (8–12 stems)</div>
                                    <div class="material-badge">WRAPPER</div>
                                    <div class="addon-note">Wrappers</div>
                                    <div class="addon-price">₱200</div>
                                    <div class="addon-qty-wrap">
                                        <button type="button" class="qty-btn" onclick="changeQty(event,this,-1)">−</button>
                                        <span class="qty-display">1</span>
                                        <button type="button" class="qty-btn" onclick="changeQty(event,this,1)">+</button>
                                    </div>
                                </div>
                                <div class="addon-item" onclick="toggleAddon(this)" data-name="Paper Bag" data-price="20" data-key="bag" data-tab="wrapper" data-category="Wrappers" data-material="Bag">
                                    <div class="addon-check-ring"><i class="fas fa-check"></i></div>
                                    <span class="addon-emoji">🛍️</span>
                                    <div class="addon-name">Paper Bag</div>
                                    <div class="material-badge">BAG</div>
                                    <div class="addon-note">Wrappers</div>
                                    <div class="addon-price">₱20</div>
                                    <div class="addon-qty-wrap">
                                        <button type="button" class="qty-btn" onclick="changeQty(event,this,-1)">−</button>
                                        <span class="qty-display">1</span>
                                        <button type="button" class="qty-btn" onclick="changeQty(event,this,1)">+</button>
                                    </div>
                                </div>
                            </div>

                            <!-- ── FUZZY WIRE TAB ── -->
                            <div class="addon-grid" id="tab-fuzzywire" style="display:none;">
                                @php
                                $fuzzyWireFlowers = [
                                    ['key'=>'fw_tulip',   'emoji'=>'🌷', 'name'=>'Tulip',     'price'=>80],
                                    ['key'=>'fw_lily',    'emoji'=>'🌺', 'name'=>'Lily',      'price'=>100],
                                    ['key'=>'fw_gerb',    'emoji'=>'🌼', 'name'=>'Gerbera',   'price'=>100],
                                    ['key'=>'fw_rose',    'emoji'=>'🌹', 'name'=>'Rose',      'price'=>100],
                                    ['key'=>'fw_bigrose', 'emoji'=>'🌹', 'name'=>'Big Rose',  'price'=>150],
                                    ['key'=>'fw_sun',     'emoji'=>'🌻', 'name'=>'Sunflower', 'price'=>100],
                                    ['key'=>'fw_carn',    'emoji'=>'🌸', 'name'=>'Carnation', 'price'=>150],
                                    ['key'=>'fw_peony',   'emoji'=>'🌸', 'name'=>'Peony',     'price'=>180],
                                    ['key'=>'fw_sundrop', 'emoji'=>'🌼', 'name'=>'Sundrop',   'price'=>150],
                                    ['key'=>'fw_leaves',  'emoji'=>'🌿', 'name'=>'Leaves',    'price'=>50],
                                ];
                                @endphp
                                @foreach($fuzzyWireFlowers as $f)
                                <div class="addon-item" onclick="toggleAddon(this)"
                                     data-name="{{ $f['name'] }}"
                                     data-price="{{ $f['price'] }}"
                                     data-key="{{ $f['key'] }}"
                                     data-tab="fuzzywire"
                                     data-category="Flowers"
                                     data-material="Fuzzy Wire">
                                    <div class="addon-check-ring"><i class="fas fa-check"></i></div>
                                    <span class="addon-emoji">{{ $f['emoji'] }}</span>
                                    <div class="addon-name">{{ $f['name'] }}</div>
                                    <div class="material-badge">FUZZY WIRE</div>
                                    <div class="addon-note">Flowers</div>
                                    <div class="addon-price">₱{{ $f['price'] }}</div>
                                    <div class="addon-qty-wrap">
                                        <button type="button" class="qty-btn" onclick="changeQty(event,this,-1)">−</button>
                                        <span class="qty-display">1</span>
                                        <button type="button" class="qty-btn" onclick="changeQty(event,this,1)">+</button>
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            <!-- ── CROCHET TAB ── -->
                            <div class="addon-grid" id="tab-crochet" style="display:none;">
                                @php
                                $crochetFlowers = [
                                    ['key'=>'cr_tulip',   'emoji'=>'🌷', 'name'=>'Tulip',     'price'=>120],
                                    ['key'=>'cr_lily',    'emoji'=>'🌺', 'name'=>'Lily',      'price'=>140],
                                    ['key'=>'cr_gerb',    'emoji'=>'🌼', 'name'=>'Gerbera',   'price'=>140],
                                    ['key'=>'cr_rose',    'emoji'=>'🌹', 'name'=>'Rose',      'price'=>140],
                                    ['key'=>'cr_bigrose', 'emoji'=>'🌹', 'name'=>'Big Rose',  'price'=>180],
                                    ['key'=>'cr_sun',     'emoji'=>'🌻', 'name'=>'Sunflower', 'price'=>140],
                                    ['key'=>'cr_carn',    'emoji'=>'🌸', 'name'=>'Carnation', 'price'=>180],
                                    ['key'=>'cr_peony',   'emoji'=>'🌸', 'name'=>'Peony',     'price'=>200],
                                ];
                                @endphp
                                @foreach($crochetFlowers as $f)
                                <div class="addon-item" onclick="toggleAddon(this)"
                                     data-name="{{ $f['name'] }}"
                                     data-price="{{ $f['price'] }}"
                                     data-key="{{ $f['key'] }}"
                                     data-tab="crochet"
                                     data-category="Flowers"
                                     data-material="Crochet">
                                    <div class="addon-check-ring"><i class="fas fa-check"></i></div>
                                    <span class="addon-emoji">{{ $f['emoji'] }}</span>
                                    <div class="addon-name">{{ $f['name'] }}</div>
                                    <div class="material-badge">CROCHET</div>
                                    <div class="addon-note">Flowers</div>
                                    <div class="addon-price">₱{{ $f['price'] }}</div>
                                    <div class="addon-qty-wrap">
                                        <button type="button" class="qty-btn" onclick="changeQty(event,this,-1)">−</button>
                                        <span class="qty-display">1</span>
                                        <button type="button" class="qty-btn" onclick="changeQty(event,this,1)">+</button>
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            <!-- ── SATIN TAB ── -->
                            <div class="addon-grid" id="tab-satin" style="display:none;">
                                @php
                                $satinFlowers = [
                                    ['key'=>'st_tulip',   'emoji'=>'🌷', 'name'=>'Tulip',     'price'=>150],
                                    ['key'=>'st_lily',    'emoji'=>'🌺', 'name'=>'Lily',      'price'=>170],
                                    ['key'=>'st_gerb',    'emoji'=>'🌼', 'name'=>'Gerbera',   'price'=>170],
                                    ['key'=>'st_rose',    'emoji'=>'🌹', 'name'=>'Rose',      'price'=>170],
                                    ['key'=>'st_bigrose', 'emoji'=>'🌹', 'name'=>'Big Rose',  'price'=>220],
                                    ['key'=>'st_sun',     'emoji'=>'🌻', 'name'=>'Sunflower', 'price'=>170],
                                    ['key'=>'st_carn',    'emoji'=>'🌸', 'name'=>'Carnation', 'price'=>220],
                                    ['key'=>'st_peony',   'emoji'=>'🌸', 'name'=>'Peony',     'price'=>250],
                                ];
                                @endphp
                                @foreach($satinFlowers as $f)
                                <div class="addon-item" onclick="toggleAddon(this)"
                                     data-name="{{ $f['name'] }}"
                                     data-price="{{ $f['price'] }}"
                                     data-key="{{ $f['key'] }}"
                                     data-tab="satin"
                                     data-category="Flowers"
                                     data-material="Satin">
                                    <div class="addon-check-ring"><i class="fas fa-check"></i></div>
                                    <span class="addon-emoji">{{ $f['emoji'] }}</span>
                                    <div class="addon-name">{{ $f['name'] }}</div>
                                    <div class="material-badge">SATIN</div>
                                    <div class="addon-note">Flowers</div>
                                    <div class="addon-price">₱{{ $f['price'] }}</div>
                                    <div class="addon-qty-wrap">
                                        <button type="button" class="qty-btn" onclick="changeQty(event,this,-1)">−</button>
                                        <span class="qty-display">1</span>
                                        <button type="button" class="qty-btn" onclick="changeQty(event,this,1)">+</button>
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            <!-- ── EXTRAS TAB ── -->
                            <div class="addon-grid" id="tab-extras" style="display:none;">
                                <div class="addon-item" onclick="toggleAddon(this)" data-name="Butterfly" data-price="10" data-key="e_butterfly" data-tab="extras" data-category="Extras" data-material="Decorative">
                                    <div class="addon-check-ring"><i class="fas fa-check"></i></div>
                                    <span class="addon-emoji">🦋</span>
                                    <div class="addon-name">Butterfly</div>
                                    <div class="material-badge">DECOR</div>
                                    <div class="addon-note">Extras</div>
                                    <div class="addon-price">₱10</div>
                                    <div class="addon-qty-wrap">
                                        <button type="button" class="qty-btn" onclick="changeQty(event,this,-1)">−</button>
                                        <span class="qty-display">1</span>
                                        <button type="button" class="qty-btn" onclick="changeQty(event,this,1)">+</button>
                                    </div>
                                </div>
                                <div class="addon-item" onclick="toggleAddon(this)" data-name="Lights" data-price="15" data-key="e_lights" data-tab="extras" data-category="Extras" data-material="Decorative">
                                    <div class="addon-check-ring"><i class="fas fa-check"></i></div>
                                    <span class="addon-emoji">💡</span>
                                    <div class="addon-name">Lights</div>
                                    <div class="material-badge">DECOR</div>
                                    <div class="addon-note">Extras</div>
                                    <div class="addon-price">₱15</div>
                                    <div class="addon-qty-wrap">
                                        <button type="button" class="qty-btn" onclick="changeQty(event,this,-1)">−</button>
                                        <span class="qty-display">1</span>
                                        <button type="button" class="qty-btn" onclick="changeQty(event,this,1)">+</button>
                                    </div>
                                </div>
                            </div>

                            <div id="addonHiddenInputs"></div>

                            <!-- Selected Summary -->
                            <div class="addon-summary-panel">
                                <div class="addon-summary-header">
                                    <div class="addon-summary-title"><i class="fas fa-shopping-basket"></i> &nbsp;Your Selection</div>
                                </div>
                                <div id="addonTagsContainer">
                                    <div class="addon-summary-empty">No add-ons selected yet — tap items above to add them</div>
                                </div>
                                <div class="addon-total-line" id="addonTotalLine" style="display:none;">
                                    <span class="addon-total-label">Estimated Add-Ons Total</span>
                                    <span class="addon-total-value" id="addonEstimateTotal">₱0</span>
                                </div>
                            </div>

                            <div class="addon-disclaimer">
                                <i class="fas fa-info-circle"></i>
                                <strong> Reference prices only.</strong> Our florists will review your selections and provide the official quote before any payment is made.
                            </div>
                        </div>
                    </div>

                    <!-- Image Upload -->
                    <div class="card" style="margin-top:20px;">
                        <div class="card-header">
                            <div class="card-header-icon"><i class="fas fa-images"></i></div>
                            <h3>Reference Images <span style="font-family:'DM Sans',sans-serif;font-weight:400;font-size:0.8rem;color:var(--muted);">(optional)</span></h3>
                        </div>
                        <div class="card-body">
                            <div class="upload-area" onclick="document.getElementById('custom_image').click()">
                                <div class="upload-icon"><i class="fas fa-cloud-upload-alt"></i></div>
                                <div class="upload-text">Click to upload inspiration photo</div>
                                <div class="upload-subtext">JPG, PNG, GIF up to 5MB</div>
                                <input type="file" id="custom_image" name="custom_image" accept="image/*" onchange="previewImage(this)">
                            </div>
                            <div class="preview-box" id="previewBox">
                                <img id="imagePreview" class="preview-image" src="" alt="Preview">
                                <button type="button" class="remove-image-btn" onclick="removeImage()"><i class="fas fa-times"></i></button>
                            </div>
                        </div>
                    </div>

                    <div style="margin-top:24px;">
                        <button type="submit" class="btn btn-primary" id="submitBtn" style="width:100%;justify-content:center;padding:16px;">
                            <i class="fas fa-paper-plane"></i> Submit Customization Request
                        </button>
                    </div>
                </form>
                @endif
            </div>

            <!-- SIDEBAR -->
            <div class="sidebar">
                @if(!$category)
                <div class="card">
                    <div class="card-body">
                        <div class="section-label">How It Works</div>
                        <ul class="info-list">
                            <li><i class="fas fa-circle" style="font-size:6px;margin-top:6px;"></i><span>Pick a category that fits your bouquet style</span></li>
                            <li><i class="fas fa-circle" style="font-size:6px;margin-top:6px;"></i><span>Describe your design and choose add-ons</span></li>
                            <li><i class="fas fa-circle" style="font-size:6px;margin-top:6px;"></i><span>Submit your request — no payment yet</span></li>
                            <li><i class="fas fa-circle" style="font-size:6px;margin-top:6px;"></i><span>Receive an official quote within 24 hours</span></li>
                        </ul>
                    </div>
                </div>
                @else
                <div class="estimate-card">
                    <div class="estimate-card-header">
                        <i class="fas fa-calculator"></i>
                        <span>Your Estimate</span>
                    </div>
                    <div class="estimate-total" id="sidebarTotal">₱0</div>
                    <div class="estimate-lines" id="sidebarBreakdown"></div>
                    <div class="estimate-note">Based on selected add-ons.<br>Final price confirmed by our florists.</div>
                </div>
                @endif

                <div class="card">
                    <div class="card-body">
                        <div class="section-label">Tips for Best Results</div>
                        <ul class="info-list">
                            <li><i class="fas fa-circle" style="font-size:6px;margin-top:6px;"></i><span>Be specific about flower types and colors</span></li>
                            <li><i class="fas fa-circle" style="font-size:6px;margin-top:6px;"></i><span>Upload a reference photo if possible</span></li>
                            <li><i class="fas fa-circle" style="font-size:6px;margin-top:6px;"></i><span>Mention the occasion in your description</span></li>
                            <li><i class="fas fa-circle" style="font-size:6px;margin-top:6px;"></i><span>Use add-ons as a starting point for florists</span></li>
                        </ul>
                    </div>
                </div>

                <div style="background:var(--rose-pale);border:1.5px solid var(--rose-light);border-radius:16px;padding:18px 20px;">
                    <div style="display:flex;align-items:flex-start;gap:10px;">
                        <i class="fas fa-shield-alt" style="color:var(--rose);margin-top:2px;flex-shrink:0;"></i>
                        <div>
                            <div style="font-weight:700;font-size:13px;color:var(--ink);margin-bottom:4px;">No obligation</div>
                            <div style="font-size:12px;color:var(--muted);line-height:1.6;">You'll receive a custom price quote for approval before any payment is made.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = e => {
                document.getElementById('imagePreview').src = e.target.result;
                document.getElementById('previewBox').style.display = 'block';
                document.querySelector('.upload-area').style.display = 'none';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
    function removeImage() {
        document.getElementById('custom_image').value = '';
        document.getElementById('previewBox').style.display = 'none';
        document.querySelector('.upload-area').style.display = 'block';
    }

    // ── Tabs ──────────────────────────────────────────────────────
    function switchTab(el, id) {
        document.querySelectorAll('.addon-tab').forEach(t => t.classList.remove('active'));
        el.classList.add('active');
        ['wrapper','fuzzywire','crochet','satin','extras'].forEach(tid => {
            const tabElement = document.getElementById('tab-' + tid);
            if (tabElement) {
                tabElement.style.display = (tid === id) ? 'grid' : 'none';
            }
        });
    }

    // ── Add-on state ──────────────────────────────────────────────
    const selectedAddons = {};

    function toggleAddon(el) {
        const key   = el.dataset.key;
        const name  = el.dataset.name;
        const price = parseFloat(el.dataset.price);
        const tab   = el.dataset.tab;
        const category = el.dataset.category;
        const material = el.dataset.material;

        if (el.classList.contains('selected')) {
            el.classList.remove('selected');
            delete selectedAddons[key];
        } else {
            el.classList.add('selected');
            selectedAddons[key] = { name, price, qty: 1, tab, category, material };
            el.querySelector('.qty-display').textContent = 1;
        }
        renderSummary();
    }

    function changeQty(e, btn, delta) {
        e.stopPropagation();
        const item = btn.closest('.addon-item');
        const key  = item.dataset.key;
        if (!selectedAddons[key]) return;
        let qty = selectedAddons[key].qty + delta;
        if (qty < 1) qty = 1;
        selectedAddons[key].qty = qty;
        item.querySelector('.qty-display').textContent = qty;
        renderSummary();
    }

    function removeAddon(key) {
        delete selectedAddons[key];
        document.querySelectorAll('.addon-item').forEach(el => {
            if (el.dataset.key === key) {
                el.classList.remove('selected');
                el.querySelector('.qty-display').textContent = 1;
            }
        });
        renderSummary();
    }

    function renderSummary() {
        const tagsContainer = document.getElementById('addonTagsContainer');
        const totalLine     = document.getElementById('addonTotalLine');
        const hiddenDiv     = document.getElementById('addonHiddenInputs');
        const sidebarTotal  = document.getElementById('sidebarTotal');
        const sidebarBd     = document.getElementById('sidebarBreakdown');
        const countBadge    = document.getElementById('addonCountBadge');

        tagsContainer.innerHTML = '';
        hiddenDiv.innerHTML = '';

        // Update tab counts
        const tabCounts = { wrapper: 0, fuzzywire: 0, crochet: 0, satin: 0, extras: 0 };
        Object.values(selectedAddons).forEach(a => { if (tabCounts[a.tab] !== undefined) tabCounts[a.tab]++; });
        document.querySelectorAll('.addon-tab-count').forEach(el => {
            const t = el.dataset.tab;
            el.textContent = tabCounts[t] || 0;
        });

        const keys = Object.keys(selectedAddons);
        const totalCount = keys.length;
        countBadge.textContent = totalCount + ' selected';

        if (keys.length === 0) {
            tagsContainer.innerHTML = '<div class="addon-summary-empty">No add-ons selected yet — tap items above to add them</div>';
            totalLine.style.display = 'none';
            if (sidebarTotal) sidebarTotal.textContent = '₱0';
            if (sidebarBd) sidebarBd.innerHTML = '';
            return;
        }

        let total = 0, idx = 0, bdHtml = '';
        const tagsWrap = document.createElement('div');
        tagsWrap.className = 'addon-tags-wrap';

        keys.forEach(key => {
            const { name, price, qty, category, material } = selectedAddons[key];
            const sub = price * qty;
            total += sub;

            const tag = document.createElement('span');
            tag.className = 'addon-tag';
            tag.innerHTML = `${name} (${material})&nbsp;×${qty} <span class="remove-tag" onclick="removeAddon('${key}')"><i class="fas fa-times"></i></span>`;
            tagsWrap.appendChild(tag);

            hiddenDiv.innerHTML += `
                <input type="hidden" name="materials[${idx}][label]"      value="${name} (${material} - ${category})">
                <input type="hidden" name="materials[${idx}][quantity]"   value="${qty}">
                <input type="hidden" name="materials[${idx}][unit_price]" value="${price}">
                <input type="hidden" name="materials[${idx}][product_id]" value="${key}">`;
            bdHtml += `<div class="estimate-line"><span>${name} (${material}) ×${qty}</span><span>₱${sub}</span></div>`;
            idx++;
        });

        tagsContainer.appendChild(tagsWrap);
        totalLine.style.display = 'flex';
        document.getElementById('addonEstimateTotal').textContent = '₱' + total.toLocaleString();
        if (sidebarTotal) sidebarTotal.textContent = '₱' + total.toLocaleString();
        if (sidebarBd) sidebarBd.innerHTML = bdHtml;
    }

    @if($category)
    document.getElementById('customizationForm').addEventListener('submit', function(e) {
        const nameVal = document.querySelector('[name="customization_name"]').value.trim();
        const descVal = document.querySelector('[name="customization_details"]').value.trim();
        if (!nameVal || !descVal) {
            e.preventDefault();
            alert('Please fill in all required fields.');
            return;
        }
        const btn = document.getElementById('submitBtn');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Submitting...';
    });
    @endif
    </script>
</body>
</html>