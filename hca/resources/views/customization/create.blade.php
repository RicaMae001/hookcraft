<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Custom Bouquet - Hookcraft Avenue</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary: #FF6B9D;
            --primary-dark: #C06C84;
            --background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            --card-shadow: 0 10px 40px rgba(0,0,0,0.08);
            --hover-shadow: 0 15px 50px rgba(0,0,0,0.12);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        html, body { overflow-x: hidden; }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: var(--background);
            min-height: 100vh;
        }

        nav { position: sticky; top: 0; width: 100%; z-index: 9999; }
        .main-container { max-width: 1200px; margin: 40px auto; padding: 0 20px; }

        .page-header { text-align: center; margin-bottom: 40px; animation: fadeInDown 0.6s ease; }
        .page-header h1 {
            font-size: clamp(2rem, 5vw, 3rem);
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
            margin-bottom: 10px; font-weight: 800;
        }
        .page-header p { color: #64748b; font-size: 1.1rem; }

        .progress-steps { display: flex; justify-content: center; gap: 20px; margin-bottom: 40px; animation: fadeIn 0.8s ease; }
        .progress-step {
            display: flex; align-items: center; gap: 12px; background: white;
            padding: 15px 25px; border-radius: 50px; box-shadow: var(--card-shadow); transition: all 0.3s ease;
        }
        .progress-step.active {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white; transform: scale(1.05);
        }
        .progress-step.active .step-icon { background: white; color: var(--primary); }
        .step-icon {
            width: 36px; height: 36px; border-radius: 50%; background: var(--primary); color: white;
            display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 14px;
        }
        .step-text { font-weight: 600; font-size: 14px; }

        .form-grid { display: grid; grid-template-columns: 1fr 400px; gap: 30px; animation: fadeInUp 0.8s ease; }
        .form-card { background: white; border-radius: 24px; padding: 40px; box-shadow: var(--card-shadow); }

        .section-title {
            display: flex; align-items: center; gap: 12px; font-size: 1.5rem;
            font-weight: 700; color: #1e293b; margin-bottom: 25px;
        }
        .section-title i { color: var(--primary); font-size: 1.3rem; }

        .form-group { margin-bottom: 25px; }
        .form-label { display: block; font-weight: 600; color: #334155; margin-bottom: 10px; font-size: 14px; }
        .form-label.required::after { content: " *"; color: var(--primary); }

        .form-input, .form-select, .form-textarea {
            width: 100%; padding: 14px 18px; border: 2px solid #e2e8f0; border-radius: 12px;
            font-size: 15px; font-family: inherit; transition: all 0.3s ease; background: #f8fafc;
        }
        .form-input:focus, .form-select:focus, .form-textarea:focus {
            outline: none; border-color: var(--primary); background: white;
            box-shadow: 0 0 0 4px rgba(255,107,157,0.1);
        }
        .form-textarea { min-height: 150px; resize: vertical; line-height: 1.6; }

        /* Category Grid */
        .category-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 15px; margin-bottom: 20px; }
        .category-card {
            background: white; border: 2px solid #e2e8f0; border-radius: 16px; overflow: hidden;
            cursor: pointer; transition: all 0.3s ease; text-decoration: none; color: inherit; display: block;
        }
        .category-card:hover { border-color: var(--primary); transform: translateY(-4px); box-shadow: var(--hover-shadow); }
        .category-img-wrap { width: 100%; aspect-ratio: 4/3; overflow: hidden; background: #f1f5f9; position: relative; }
        .category-img-wrap img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.4s ease; }
        .category-card:hover .category-img-wrap img { transform: scale(1.07); }
        .category-img-fallback {
            width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;
            font-size: 3rem; color: var(--primary); background: linear-gradient(135deg, #fff5f8, #ffe4ec);
        }
        .category-body { padding: 16px 18px; text-align: center; }
        .category-name { font-weight: 700; color: #1e293b; margin-bottom: 4px; font-size: 1rem; }
        .category-count { font-size: 0.85rem; color: #64748b; }

        /* Product Preview */
        .product-preview { padding: 20px; background: #f8fafc; border-radius: 12px; margin-bottom: 25px; }
        .product-preview-flex { display: flex; gap: 15px; align-items: center; }
        .product-preview img { width: 100px; height: 100px; object-fit: cover; border-radius: 8px; }
        .product-info h4 { font-weight: 600; color: #1e293b; font-size: 1.1rem; margin-bottom: 5px; }
        .product-price { color: var(--primary); font-weight: 700; font-size: 1.3rem; }
        .product-description { color: #64748b; font-size: 0.9rem; margin-top: 5px; }
        .reference-note {
            background: #e7f3ff; border-left: 4px solid var(--primary);
            padding: 15px; border-radius: 8px; margin-top: 15px; color: #004085; font-size: 0.9rem;
        }

        /* ══ ADD-ONS SECTION ══ */
        .addons-section {
            margin-top: 35px;
            background: linear-gradient(135deg, #fff8fb, #fff0f5);
            border: 2px solid #ffd6e7;
            border-radius: 20px;
            padding: 28px;
        }
        .addons-section-title {
            display: flex; align-items: center; gap: 10px;
            font-size: 1.2rem; font-weight: 700; color: #1e293b; margin-bottom: 6px;
        }
        .addons-section-title i { color: var(--primary); }
        .addons-subtitle { color: #64748b; font-size: 13px; margin-bottom: 20px; }

        .addon-tabs { display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 20px; }
        .addon-tab {
            padding: 7px 16px; border-radius: 20px; border: 2px solid #e2e8f0;
            background: white; color: #64748b; font-size: 13px; font-weight: 600;
            cursor: pointer; transition: all 0.2s; white-space: nowrap; user-select: none;
        }
        .addon-tab:hover { border-color: var(--primary); color: var(--primary); }
        .addon-tab.active {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border-color: var(--primary); color: white;
        }

        .addon-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(155px, 1fr));
            gap: 10px;
            margin-bottom: 16px;
        }
        .addon-item {
            background: white; border: 2px solid #e2e8f0; border-radius: 14px;
            padding: 14px 10px; cursor: pointer; transition: all 0.2s;
            position: relative; text-align: center; user-select: none;
        }
        .addon-item:hover {
            border-color: var(--primary); transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255,107,157,0.15);
        }
        .addon-item.selected {
            border-color: var(--primary);
            background: linear-gradient(135deg, #fff5f8, #ffe4ec);
        }
        .addon-check {
            position: absolute; top: 8px; right: 8px;
            width: 20px; height: 20px; border-radius: 50%;
            background: var(--primary); color: white;
            display: flex; align-items: center; justify-content: center;
            font-size: 10px; opacity: 0; transform: scale(0.5);
            transition: all 0.2s;
        }
        .addon-item.selected .addon-check { opacity: 1; transform: scale(1); }
        .addon-emoji { font-size: 1.5rem; margin-bottom: 5px; }
        .addon-name  { font-size: 12px; font-weight: 700; color: #1e293b; margin-bottom: 3px; line-height: 1.3; }
        .addon-price { font-size: 13px; font-weight: 800; color: var(--primary); }
        .addon-note  { font-size: 10px; color: #94a3b8; margin-top: 2px; }

        .addon-qty-wrap {
            display: none; align-items: center; justify-content: center;
            gap: 8px; margin-top: 8px;
        }
        .addon-item.selected .addon-qty-wrap { display: flex; }
        .qty-btn {
            width: 24px; height: 24px; border-radius: 6px; border: 2px solid var(--primary);
            background: white; color: var(--primary); font-size: 15px; font-weight: 700;
            cursor: pointer; display: flex; align-items: center; justify-content: center; line-height: 1;
            transition: all 0.2s;
        }
        .qty-btn:hover { background: var(--primary); color: white; }
        .qty-display { font-size: 13px; font-weight: 700; color: #1e293b; min-width: 20px; text-align: center; }

        #addonHiddenInputs { display: none; }

        .addon-summary {
            background: white; border: 2px solid #e2e8f0; border-radius: 14px;
            padding: 16px; margin-top: 16px;
        }
        .addon-summary-title {
            font-size: 12px; font-weight: 700; color: #94a3b8;
            text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 10px;
        }
        .addon-summary-empty { color: #cbd5e1; font-size: 13px; text-align: center; padding: 8px 0; }
        .addon-tag {
            display: inline-flex; align-items: center; gap: 6px;
            background: linear-gradient(135deg, #fff5f8, #ffe4ec);
            border: 1px solid var(--primary); border-radius: 20px;
            padding: 4px 10px; font-size: 12px; font-weight: 600; color: #be185d; margin: 3px;
        }
        .remove-tag {
            cursor: pointer; color: #94a3b8; font-size: 10px;
            width: 16px; height: 16px; border-radius: 50%; background: #f1f5f9;
            display: flex; align-items: center; justify-content: center; transition: all 0.2s;
        }
        .remove-tag:hover { background: var(--primary); color: white; }
        .addon-estimate {
            display: flex; justify-content: space-between; align-items: center;
            padding-top: 12px; margin-top: 10px; border-top: 2px dashed #ffd6e7;
        }
        .addon-estimate-label { font-size: 13px; font-weight: 600; color: #64748b; }
        .addon-estimate-total { font-size: 1.2rem; font-weight: 800; color: var(--primary); }

        .addon-disclaimer {
            background: #fffbeb; border-left: 3px solid #f59e0b;
            padding: 10px 14px; border-radius: 8px; margin-top: 14px;
            color: #92400e; font-size: 12px; line-height: 1.6;
        }

        /* Image Upload */
        .upload-area {
            border: 3px dashed var(--primary); border-radius: 20px; padding: 50px 30px;
            text-align: center; cursor: pointer; transition: all 0.3s ease;
            background: linear-gradient(135deg, #fff5f8 0%, #ffe4ec 100%); margin-bottom: 20px;
        }
        .upload-area:hover { background: linear-gradient(135deg, #ffe4ec 0%, #ffd1de 100%); transform: translateY(-2px); }
        .upload-area input { display: none; }
        .upload-icon { font-size: 3.5rem; color: var(--primary); margin-bottom: 15px; }
        .upload-text { font-size: 1.1rem; font-weight: 600; color: #1e293b; margin-bottom: 8px; }
        .upload-subtext { color: #64748b; font-size: 0.9rem; }
        .preview-box { display: none; position: relative; border-radius: 20px; overflow: hidden; box-shadow: var(--card-shadow); }
        .preview-image { width: 100%; height: auto; display: block; }
        .remove-image-btn {
            position: absolute; top: 15px; right: 15px; background: rgba(255,255,255,0.95);
            border: none; width: 40px; height: 40px; border-radius: 50%; cursor: pointer;
            display: flex; align-items: center; justify-content: center; font-size: 1.2rem;
            color: #dc2626; transition: all 0.3s ease; box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        .remove-image-btn:hover { background: #dc2626; color: white; transform: scale(1.1); }

        .btn {
            padding: 16px 32px; border: none; border-radius: 12px; font-size: 1rem; font-weight: 600;
            cursor: pointer; transition: all 0.3s ease; display: inline-flex; align-items: center;
            gap: 10px; text-decoration: none; margin-right: 15px;
        }
        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white; box-shadow: 0 4px 15px rgba(255,107,157,0.3);
        }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(255,107,157,0.4); }
        .btn-primary:disabled { opacity: 0.6; cursor: not-allowed; transform: none; }

        .sidebar { display: flex; flex-direction: column; gap: 20px; }
        .info-card { background: white; border-radius: 20px; padding: 30px; box-shadow: var(--card-shadow); }
        .info-card.primary { background: linear-gradient(135deg, #fff5f8 0%, #ffe4ec 100%); border: 2px solid var(--primary); }
        .info-card h3 { display: flex; align-items: center; gap: 10px; font-size: 1.2rem; color: #1e293b; margin-bottom: 20px; }
        .info-card h3 i { color: var(--primary); }
        .info-list { list-style: none; }
        .info-list li { display: flex; gap: 12px; padding: 12px 0; border-bottom: 1px solid rgba(0,0,0,0.05); color: #475569; line-height: 1.6; }
        .info-list li:last-child { border-bottom: none; }
        .info-list i { color: var(--primary); margin-top: 3px; flex-shrink: 0; }

        .alert { padding: 18px 20px; border-radius: 12px; display: flex; align-items: flex-start; gap: 12px; margin-bottom: 20px; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-error   { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .alert-info    { background: #e7f3ff; color: #004085; border: 1px solid #bee5eb; }
        .alert i { font-size: 1.3rem; margin-top: 2px; }

        .estimate-card {
            background: linear-gradient(135deg, #f0fdf4, #dcfce7);
            border: 2px solid #22c55e; border-radius: 20px; padding: 25px;
        }
        .estimate-card h3 { color: #15803d; }
        .estimate-card h3 i { color: #22c55e; }
        .estimate-total { font-size: 2rem; font-weight: 800; color: #15803d; text-align: center; padding: 12px 0; }
        .estimate-note  { font-size: 0.8rem; color: #166534; text-align: center; }
        .estimate-line  { display: flex; justify-content: space-between; font-size: 12px; color: #166534; padding: 3px 0; border-bottom: 1px dashed #bbf7d0; }
        .estimate-line:last-child { border-bottom: none; }

        @keyframes fadeInDown { from { opacity:0; transform:translateY(-30px); } to { opacity:1; transform:translateY(0); } }
        @keyframes fadeInUp   { from { opacity:0; transform:translateY(30px);  } to { opacity:1; transform:translateY(0); } }
        @keyframes fadeIn     { from { opacity:0; } to { opacity:1; } }

        @media (max-width: 968px) {
            .form-grid { grid-template-columns: 1fr; }
            .progress-steps { flex-direction: column; align-items: center; }
            .btn { width: 100%; justify-content: center; margin-right: 0; margin-bottom: 10px; }
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
            <h1>🌸 Request Custom Bouquet</h1>
            <p>Create your perfect flower arrangement with our expert florists</p>
        </div>

        <div class="progress-steps">
            <div class="progress-step {{ !$category ? 'active' : '' }}">
                <div class="step-icon">1</div><div class="step-text">Choose Category</div>
            </div>
            <div class="progress-step {{ $category ? 'active' : '' }}">
                <div class="step-icon">2</div><div class="step-text">Customize Design</div>
            </div>
            <div class="progress-step">
                <div class="step-icon">3</div><div class="step-text">Submit Request</div>
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
                    <ul style="margin-top:10px;padding-left:20px;">
                        @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
            </div>
        @endif

        <div class="form-grid">
            <div class="form-card">

                @if(!$category)
                {{-- STEP 1 --}}
                <div class="section-title"><i class="fas fa-th-large"></i> Choose a Category</div>
                <p style="color:#64748b;margin-bottom:25px;font-size:15px;">
                    Select a category to get started.
                </p>
                <div class="category-grid">
                    @foreach($categories as $cat)
                        @php $refProduct = $cat->products->first(); @endphp
                        <a href="{{ route('customization.create', ['category_id' => $cat->id]) }}" class="category-card">
                            <div class="category-img-wrap">
                                @if($refProduct && $refProduct->image)
                                    <img src="{{ asset('asset/images/' . $refProduct->image) }}" alt="{{ $cat->name }}"
                                         onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
                                    <div class="category-img-fallback" style="display:none;position:absolute;inset:0;"><i class="fas fa-box"></i></div>
                                @else
                                    <div class="category-img-fallback"><i class="fas fa-box"></i></div>
                                @endif
                            </div>
                            <div class="category-body">
                                <div class="category-name">{{ $cat->name }}</div>
                                <div class="category-count">{{ $cat->products->count() }} available products</div>
                            </div>
                        </a>
                    @endforeach
                </div>

                @else
                {{-- STEP 2 --}}
                <form action="{{ route('customization.store') }}" method="POST" enctype="multipart/form-data" id="customizationForm">
                    @csrf
                    <input type="hidden" name="category_id" value="{{ $category->id }}">
                    <input type="hidden" name="product_id"  value="{{ $referenceProduct->id }}">

                    <div style="margin-bottom:25px;">
                        <div style="display:flex;align-items:center;gap:10px;margin-bottom:10px;">
                            <i class="fas fa-tag" style="color:var(--primary);"></i>
                            <span style="font-weight:600;color:#1e293b;">Category: {{ $category->name }}</span>
                        </div>
                        <a href="{{ route('customization.create') }}" style="color:var(--primary);text-decoration:none;font-size:0.9rem;">
                            <i class="fas fa-sync-alt"></i> Change Category
                        </a>
                    </div>

                    <div class="product-preview">
                        <div class="section-title" style="font-size:1.2rem;margin-bottom:15px;">
                            <i class="fas fa-star"></i> Reference Product
                        </div>
                        <div class="product-preview-flex">
                            <img src="{{ asset('asset/images/' . $referenceProduct->image) }}"
                                 alt="{{ $referenceProduct->name }}"
                                 onerror="this.src='{{ asset('images/placeholder.jpg') }}'">
                            <div class="product-info">
                                <h4>{{ $referenceProduct->name }}</h4>
                                <div class="product-price">₱{{ number_format($referenceProduct->price, 2) }}</div>
                                @if($referenceProduct->description)
                                    <div class="product-description">{{ Str::limit($referenceProduct->description, 100) }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="reference-note">
                            <i class="fas fa-info-circle"></i>
                            <strong>Note:</strong> This product is shown as a reference. Your custom design will be based on this style and category.
                        </div>
                    </div>

                    <div style="margin-top:35px;">
                        <div class="section-title"><i class="fas fa-paint-brush"></i> Customization Details</div>

                        <div class="form-group">
                            <label class="form-label required">Customization Name</label>
                            <input type="text" class="form-input" id="customization_name" name="customization_name"
                                   placeholder="e.g., Romantic Red Rose Bouquet" required value="{{ old('customization_name') }}">
                        </div>

                        <div class="form-group">
                            <label class="form-label required">Detailed Description</label>
                            <textarea class="form-textarea" id="customization_details" name="customization_details"
                                      placeholder="Describe your vision:&#10;• What flowers do you want?&#10;• Preferred colors and style?&#10;• Size and arrangement?&#10;• What's the occasion?"
                                      required>{{ old('customization_details') }}</textarea>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Special Instructions (Optional)</label>
                            <textarea class="form-textarea" id="special_instructions" name="special_instructions"
                                      placeholder="Delivery preferences, color preferences, etc."
                                      style="min-height:100px;">{{ old('special_instructions') }}</textarea>
                        </div>
                    </div>

                    {{-- ══ ADD-ONS ══ --}}
                    <div class="addons-section">
                        <div class="addons-section-title">
                            <i class="fas fa-plus-circle"></i> Add-Ons & Materials
                        </div>
                        <p class="addons-subtitle">
                            Select any add-ons you'd like. Tap an item to add it, then adjust the quantity. These are reference prices — our florists confirm the final amount.
                        </p>

                        <div class="addon-tabs">
                            <div class="addon-tab active" onclick="switchTab(this,'wrapper')">🎀 Wrapper</div>
                            <div class="addon-tab" onclick="switchTab(this,'flowers')">🌸 Flowers</div>
                            <div class="addon-tab" onclick="switchTab(this,'extras')">✨ Extras</div>
                        </div>

                        {{-- WRAPPER TAB --}}
                        <div class="addon-grid" id="tab-wrapper">
                            <div class="addon-item" onclick="toggleAddon(this)" data-name="Wrapper (1 stem)"    data-price="50"  data-key="w1">
                                <div class="addon-check"><i class="fas fa-check"></i></div>
                                <div class="addon-emoji">🎀</div>
                                <div class="addon-name">Wrapper</div>
                                <div class="addon-price">₱50</div>
                                <div class="addon-note">1 stem</div>
                                <div class="addon-qty-wrap">
                                    <button type="button" class="qty-btn" onclick="changeQty(event,this,-1)">−</button>
                                    <span class="qty-display">1</span>
                                    <button type="button" class="qty-btn" onclick="changeQty(event,this,1)">+</button>
                                </div>
                            </div>
                            <div class="addon-item" onclick="toggleAddon(this)" data-name="Wrapper (2–4 stems)" data-price="100" data-key="w2">
                                <div class="addon-check"><i class="fas fa-check"></i></div>
                                <div class="addon-emoji">🎀</div>
                                <div class="addon-name">Wrapper</div>
                                <div class="addon-price">₱100</div>
                                <div class="addon-note">2–4 stems</div>
                                <div class="addon-qty-wrap">
                                    <button type="button" class="qty-btn" onclick="changeQty(event,this,-1)">−</button>
                                    <span class="qty-display">1</span>
                                    <button type="button" class="qty-btn" onclick="changeQty(event,this,1)">+</button>
                                </div>
                            </div>
                            <div class="addon-item" onclick="toggleAddon(this)" data-name="Wrapper (5–7 stems)" data-price="150" data-key="w3">
                                <div class="addon-check"><i class="fas fa-check"></i></div>
                                <div class="addon-emoji">🎀</div>
                                <div class="addon-name">Wrapper</div>
                                <div class="addon-price">₱150</div>
                                <div class="addon-note">5–7 stems</div>
                                <div class="addon-qty-wrap">
                                    <button type="button" class="qty-btn" onclick="changeQty(event,this,-1)">−</button>
                                    <span class="qty-display">1</span>
                                    <button type="button" class="qty-btn" onclick="changeQty(event,this,1)">+</button>
                                </div>
                            </div>
                            <div class="addon-item" onclick="toggleAddon(this)" data-name="Wrapper (8–12 stems)" data-price="200" data-key="w4">
                                <div class="addon-check"><i class="fas fa-check"></i></div>
                                <div class="addon-emoji">🎀</div>
                                <div class="addon-name">Wrapper</div>
                                <div class="addon-price">₱200</div>
                                <div class="addon-note">8–12 stems</div>
                                <div class="addon-qty-wrap">
                                    <button type="button" class="qty-btn" onclick="changeQty(event,this,-1)">−</button>
                                    <span class="qty-display">1</span>
                                    <button type="button" class="qty-btn" onclick="changeQty(event,this,1)">+</button>
                                </div>
                            </div>
                            <div class="addon-item" onclick="toggleAddon(this)" data-name="Paper Bag" data-price="20" data-key="bag">
                                <div class="addon-check"><i class="fas fa-check"></i></div>
                                <div class="addon-emoji">🛍️</div>
                                <div class="addon-name">Paper Bag</div>
                                <div class="addon-price">₱20</div>
                                <div class="addon-note">per piece</div>
                                <div class="addon-qty-wrap">
                                    <button type="button" class="qty-btn" onclick="changeQty(event,this,-1)">−</button>
                                    <span class="qty-display">1</span>
                                    <button type="button" class="qty-btn" onclick="changeQty(event,this,1)">+</button>
                                </div>
                            </div>
                        </div>

                        {{-- FLOWERS TAB --}}
                        <div class="addon-grid" id="tab-flowers" style="display:none;">
                            <div class="addon-item" onclick="toggleAddon(this)" data-name="Tulip (Fuzzy Wire)"     data-price="80"  data-key="f_tulip">
                                <div class="addon-check"><i class="fas fa-check"></i></div>
                                <div class="addon-emoji">🌷</div><div class="addon-name">Tulip</div>
                                <div class="addon-price">₱80</div><div class="addon-note">Fuzzy Wire</div>
                                <div class="addon-qty-wrap">
                                    <button type="button" class="qty-btn" onclick="changeQty(event,this,-1)">−</button>
                                    <span class="qty-display">1</span>
                                    <button type="button" class="qty-btn" onclick="changeQty(event,this,1)">+</button>
                                </div>
                            </div>
                            <div class="addon-item" onclick="toggleAddon(this)" data-name="Lily (Fuzzy Wire)"      data-price="100" data-key="f_lily">
                                <div class="addon-check"><i class="fas fa-check"></i></div>
                                <div class="addon-emoji">🌺</div><div class="addon-name">Lily</div>
                                <div class="addon-price">₱100</div><div class="addon-note">Fuzzy Wire</div>
                                <div class="addon-qty-wrap">
                                    <button type="button" class="qty-btn" onclick="changeQty(event,this,-1)">−</button>
                                    <span class="qty-display">1</span>
                                    <button type="button" class="qty-btn" onclick="changeQty(event,this,1)">+</button>
                                </div>
                            </div>
                            <div class="addon-item" onclick="toggleAddon(this)" data-name="Gerbera (Fuzzy Wire)"   data-price="100" data-key="f_gerb">
                                <div class="addon-check"><i class="fas fa-check"></i></div>
                                <div class="addon-emoji">🌼</div><div class="addon-name">Gerbera</div>
                                <div class="addon-price">₱100</div><div class="addon-note">Fuzzy Wire</div>
                                <div class="addon-qty-wrap">
                                    <button type="button" class="qty-btn" onclick="changeQty(event,this,-1)">−</button>
                                    <span class="qty-display">1</span>
                                    <button type="button" class="qty-btn" onclick="changeQty(event,this,1)">+</button>
                                </div>
                            </div>
                            <div class="addon-item" onclick="toggleAddon(this)" data-name="Rose (Fuzzy Wire)"      data-price="100" data-key="f_rose">
                                <div class="addon-check"><i class="fas fa-check"></i></div>
                                <div class="addon-emoji">🌹</div><div class="addon-name">Rose</div>
                                <div class="addon-price">₱100</div><div class="addon-note">Fuzzy Wire</div>
                                <div class="addon-qty-wrap">
                                    <button type="button" class="qty-btn" onclick="changeQty(event,this,-1)">−</button>
                                    <span class="qty-display">1</span>
                                    <button type="button" class="qty-btn" onclick="changeQty(event,this,1)">+</button>
                                </div>
                            </div>
                            <div class="addon-item" onclick="toggleAddon(this)" data-name="Big Rose (Fuzzy Wire)"  data-price="150" data-key="f_bigrose">
                                <div class="addon-check"><i class="fas fa-check"></i></div>
                                <div class="addon-emoji">🌹</div><div class="addon-name">Big Rose</div>
                                <div class="addon-price">₱150</div><div class="addon-note">Fuzzy Wire</div>
                                <div class="addon-qty-wrap">
                                    <button type="button" class="qty-btn" onclick="changeQty(event,this,-1)">−</button>
                                    <span class="qty-display">1</span>
                                    <button type="button" class="qty-btn" onclick="changeQty(event,this,1)">+</button>
                                </div>
                            </div>
                            <div class="addon-item" onclick="toggleAddon(this)" data-name="Sunflower (Fuzzy Wire)" data-price="100" data-key="f_sun">
                                <div class="addon-check"><i class="fas fa-check"></i></div>
                                <div class="addon-emoji">🌻</div><div class="addon-name">Sunflower</div>
                                <div class="addon-price">₱100</div><div class="addon-note">Fuzzy Wire</div>
                                <div class="addon-qty-wrap">
                                    <button type="button" class="qty-btn" onclick="changeQty(event,this,-1)">−</button>
                                    <span class="qty-display">1</span>
                                    <button type="button" class="qty-btn" onclick="changeQty(event,this,1)">+</button>
                                </div>
                            </div>
                            <div class="addon-item" onclick="toggleAddon(this)" data-name="Carnation (Fuzzy Wire)" data-price="150" data-key="f_carn">
                                <div class="addon-check"><i class="fas fa-check"></i></div>
                                <div class="addon-emoji">💮</div><div class="addon-name">Carnation</div>
                                <div class="addon-price">₱150</div><div class="addon-note">Fuzzy Wire</div>
                                <div class="addon-qty-wrap">
                                    <button type="button" class="qty-btn" onclick="changeQty(event,this,-1)">−</button>
                                    <span class="qty-display">1</span>
                                    <button type="button" class="qty-btn" onclick="changeQty(event,this,1)">+</button>
                                </div>
                            </div>
                            <div class="addon-item" onclick="toggleAddon(this)" data-name="Peony (Fuzzy Wire)"     data-price="180" data-key="f_peony">
                                <div class="addon-check"><i class="fas fa-check"></i></div>
                                <div class="addon-emoji">🌸</div><div class="addon-name">Peony</div>
                                <div class="addon-price">₱180</div><div class="addon-note">Fuzzy Wire</div>
                                <div class="addon-qty-wrap">
                                    <button type="button" class="qty-btn" onclick="changeQty(event,this,-1)">−</button>
                                    <span class="qty-display">1</span>
                                    <button type="button" class="qty-btn" onclick="changeQty(event,this,1)">+</button>
                                </div>
                            </div>
                            <div class="addon-item" onclick="toggleAddon(this)" data-name="Sundrop (Fuzzy Wire)"   data-price="150" data-key="f_sundrop">
                                <div class="addon-check"><i class="fas fa-check"></i></div>
                                <div class="addon-emoji">🌼</div><div class="addon-name">Sundrop</div>
                                <div class="addon-price">₱150</div><div class="addon-note">Fuzzy Wire</div>
                                <div class="addon-qty-wrap">
                                    <button type="button" class="qty-btn" onclick="changeQty(event,this,-1)">−</button>
                                    <span class="qty-display">1</span>
                                    <button type="button" class="qty-btn" onclick="changeQty(event,this,1)">+</button>
                                </div>
                            </div>
                            <div class="addon-item" onclick="toggleAddon(this)" data-name="Leaves (Fuzzy Wire)"    data-price="50"  data-key="f_leaves">
                                <div class="addon-check"><i class="fas fa-check"></i></div>
                                <div class="addon-emoji">🌿</div><div class="addon-name">Leaves</div>
                                <div class="addon-price">₱50</div><div class="addon-note">Fuzzy Wire</div>
                                <div class="addon-qty-wrap">
                                    <button type="button" class="qty-btn" onclick="changeQty(event,this,-1)">−</button>
                                    <span class="qty-display">1</span>
                                    <button type="button" class="qty-btn" onclick="changeQty(event,this,1)">+</button>
                                </div>
                            </div>
                        </div>

                        {{-- EXTRAS TAB --}}
                        <div class="addon-grid" id="tab-extras" style="display:none;">
                            <div class="addon-item" onclick="toggleAddon(this)" data-name="Butterfly" data-price="10" data-key="e_butterfly">
                                <div class="addon-check"><i class="fas fa-check"></i></div>
                                <div class="addon-emoji">🦋</div><div class="addon-name">Butterfly</div>
                                <div class="addon-price">₱10</div><div class="addon-note">per piece</div>
                                <div class="addon-qty-wrap">
                                    <button type="button" class="qty-btn" onclick="changeQty(event,this,-1)">−</button>
                                    <span class="qty-display">1</span>
                                    <button type="button" class="qty-btn" onclick="changeQty(event,this,1)">+</button>
                                </div>
                            </div>
                            <div class="addon-item" onclick="toggleAddon(this)" data-name="Lights" data-price="15" data-key="e_lights">
                                <div class="addon-check"><i class="fas fa-check"></i></div>
                                <div class="addon-emoji">💡</div><div class="addon-name">Lights</div>
                                <div class="addon-price">₱15</div><div class="addon-note">per set</div>
                                <div class="addon-qty-wrap">
                                    <button type="button" class="qty-btn" onclick="changeQty(event,this,-1)">−</button>
                                    <span class="qty-display">1</span>
                                    <button type="button" class="qty-btn" onclick="changeQty(event,this,1)">+</button>
                                </div>
                            </div>
                        </div>

                        <div id="addonHiddenInputs"></div>

                        <div class="addon-summary">
                            <div class="addon-summary-title"><i class="fas fa-shopping-basket"></i> Selected Add-Ons</div>
                            <div id="addonTagsContainer">
                                <div class="addon-summary-empty">No add-ons selected yet</div>
                            </div>
                            <div class="addon-estimate" id="addonEstimateRow" style="display:none;">
                                <span class="addon-estimate-label">Add-ons Estimate</span>
                                <span class="addon-estimate-total" id="addonEstimateTotal">₱0</span>
                            </div>
                        </div>

                        <div class="addon-disclaimer">
                            <i class="fas fa-info-circle"></i>
                            <strong>These are reference prices only.</strong> Our florists will review your selections and confirm the official price before you pay. Nothing is charged yet.
                        </div>
                    </div>

                    <!-- Image Upload -->
                    <div style="margin-top:35px;">
                        <div class="section-title"><i class="fas fa-images"></i> Reference Images</div>
                        <p style="color:#64748b;margin-bottom:20px;font-size:14px;">Upload inspiration photos (optional)</p>
                        <div class="upload-area" onclick="document.getElementById('custom_image').click()">
                            <div class="upload-icon"><i class="fas fa-cloud-upload-alt"></i></div>
                            <div class="upload-text">Click to upload image</div>
                            <div class="upload-subtext">JPG, PNG, GIF up to 5MB</div>
                            <input type="file" id="custom_image" name="custom_image" accept="image/*" onchange="previewImage(this)">
                        </div>
                        <div class="preview-box" id="previewBox">
                            <img id="imagePreview" class="preview-image" src="" alt="Preview">
                            <button type="button" class="remove-image-btn" onclick="removeImage()"><i class="fas fa-times"></i></button>
                        </div>
                    </div>

                    <div style="margin-top:40px;display:flex;gap:15px;">
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <i class="fas fa-paper-plane"></i> Submit Customization Request
                        </button>
                    </div>
                </form>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="sidebar">
                @if(!$category)
                    <div class="info-card primary">
                        <h3><i class="fas fa-info-circle"></i> How It Works</h3>
                        <ul class="info-list">
                            <li><i class="fas fa-check"></i><span>Choose a category</span></li>
                            <li><i class="fas fa-check"></i><span>Describe your design & pick add-ons</span></li>
                            <li><i class="fas fa-check"></i><span>Submit your request</span></li>
                            <li><i class="fas fa-check"></i><span>Get an official quote within 24 hours</span></li>
                        </ul>
                    </div>
                @else
                    <div class="info-card primary">
                        <h3><i class="fas fa-info-circle"></i> About This Category</h3>
                        <ul class="info-list">
                            <li><i class="fas fa-star"></i><span>Category: <strong>{{ $category->name }}</strong></span></li>
                            <li><i class="fas fa-box"></i><span>Reference: <strong>{{ $referenceProduct->name }}</strong></span></li>
                            <li><i class="fas fa-lightbulb"></i><span>Final price set by our florists after review</span></li>
                        </ul>
                    </div>

                    <div class="estimate-card">
                        <h3><i class="fas fa-calculator"></i> Your Estimate</h3>
                        <div class="estimate-total" id="sidebarTotal">₱0</div>
                        <div id="sidebarBreakdown" style="margin-bottom:8px;"></div>
                        <div class="estimate-note">Based on selected add-ons.<br>Final price confirmed by florists.</div>
                    </div>
                @endif

                <div class="info-card">
                    <h3><i class="fas fa-lightbulb"></i> Tips for Best Results</h3>
                    <ul class="info-list">
                        <li><i class="fas fa-star"></i><span>Be specific about flower types and colors</span></li>
                        <li><i class="fas fa-star"></i><span>Upload a reference photo if possible</span></li>
                        <li><i class="fas fa-star"></i><span>Mention the occasion in your description</span></li>
                        <li><i class="fas fa-star"></i><span>Pick add-ons to give florists a starting point</span></li>
                    </ul>
                </div>

                <div class="alert alert-info" style="margin:0;">
                    <i class="fas fa-shield-alt"></i>
                    <div><strong>No obligation!</strong> You'll receive a custom price quote for approval before any payment.</div>
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

    // ── Tabs ───────────────────────────────────────
    function switchTab(el, id) {
        document.querySelectorAll('.addon-tab').forEach(t => t.classList.remove('active'));
        el.classList.add('active');
        ['wrapper','flowers','extras'].forEach(tid => {
            document.getElementById('tab-' + tid).style.display = (tid === id) ? 'grid' : 'none';
        });
    }

    // ── Add-on state ───────────────────────────────
    const selectedAddons = {};

    function toggleAddon(el) {
        const key   = el.dataset.key;
        const name  = el.dataset.name;
        const price = parseFloat(el.dataset.price);
        if (el.classList.contains('selected')) {
            el.classList.remove('selected');
            delete selectedAddons[key];
        } else {
            el.classList.add('selected');
            selectedAddons[key] = { name, price, qty: 1 };
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
        const container    = document.getElementById('addonTagsContainer');
        const estimateRow  = document.getElementById('addonEstimateRow');
        const hiddenDiv    = document.getElementById('addonHiddenInputs');
        const sidebarTotal = document.getElementById('sidebarTotal');
        const sidebarBd    = document.getElementById('sidebarBreakdown');

        container.innerHTML = '';
        hiddenDiv.innerHTML = '';

        const keys = Object.keys(selectedAddons);
        if (keys.length === 0) {
            container.innerHTML = '<div class="addon-summary-empty">No add-ons selected yet</div>';
            estimateRow.style.display = 'none';
            if (sidebarTotal) sidebarTotal.textContent = '₱0';
            if (sidebarBd) sidebarBd.innerHTML = '';
            return;
        }

        let total = 0, idx = 0, bdHtml = '';
        keys.forEach(key => {
            const { name, price, qty } = selectedAddons[key];
            const sub = price * qty;
            total += sub;

            const tag = document.createElement('span');
            tag.className = 'addon-tag';
            tag.innerHTML = `${name} ×${qty} &nbsp;<span class="remove-tag" onclick="removeAddon('${key}')"><i class="fas fa-times"></i></span>`;
            container.appendChild(tag);

            hiddenDiv.innerHTML += `
                <input type="hidden" name="materials[${idx}][label]"       value="${name}">
                <input type="hidden" name="materials[${idx}][quantity]"    value="${qty}">
                <input type="hidden" name="materials[${idx}][unit_price]"  value="${price}">`;
            bdHtml += `<div class="estimate-line"><span>${name} ×${qty}</span><span>₱${sub}</span></div>`;
            idx++;
        });

        estimateRow.style.display = 'flex';
        document.getElementById('addonEstimateTotal').textContent = '₱' + total;
        if (sidebarTotal) sidebarTotal.textContent = '₱' + total;
        if (sidebarBd) sidebarBd.innerHTML = bdHtml;
    }

    @if($category)
    document.getElementById('customizationForm').addEventListener('submit', function(e) {
        if (!document.getElementById('customization_name').value.trim() ||
            !document.getElementById('customization_details').value.trim()) {
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