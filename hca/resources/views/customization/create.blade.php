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
            --secondary: #667eea;
            --background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            --card-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
            --hover-shadow: 0 15px 50px rgba(0, 0, 0, 0.12);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: var(--background);
            min-height: 100vh;
            padding: 40px 20px;
        }

        .main-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Header Section */
        .page-header {
            text-align: center;
            margin-bottom: 40px;
            animation: fadeInDown 0.6s ease;
        }

        .page-header h1 {
            font-size: clamp(2rem, 5vw, 3rem);
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 10px;
            font-weight: 800;
        }

        .page-header p {
            color: #64748b;
            font-size: 1.1rem;
        }

        /* Progress Steps */
        .progress-steps {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-bottom: 40px;
            animation: fadeIn 0.8s ease;
        }

        .progress-step {
            display: flex;
            align-items: center;
            gap: 12px;
            background: white;
            padding: 15px 25px;
            border-radius: 50px;
            box-shadow: var(--card-shadow);
            transition: all 0.3s ease;
        }

        .progress-step.active {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            transform: scale(1.05);
        }

        .progress-step.active .step-icon {
            background: white;
            color: var(--primary);
        }

        .step-icon {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: var(--primary);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 14px;
        }

        .step-text {
            font-weight: 600;
            font-size: 14px;
        }

        /* Grid Layout */
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 400px;
            gap: 30px;
            animation: fadeInUp 0.8s ease;
        }

        /* Form Card */
        .form-card {
            background: white;
            border-radius: 24px;
            padding: 40px;
            box-shadow: var(--card-shadow);
        }

        .section-title {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 1.5rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 25px;
        }

        .section-title i {
            color: var(--primary);
            font-size: 1.3rem;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-label {
            display: block;
            font-weight: 600;
            color: #334155;
            margin-bottom: 10px;
            font-size: 14px;
        }

        .form-label.required::after {
            content: " *";
            color: var(--primary);
        }

        .form-input,
        .form-select,
        .form-textarea {
            width: 100%;
            padding: 14px 18px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 15px;
            font-family: inherit;
            transition: all 0.3s ease;
            background: #f8fafc;
        }

        .form-input:focus,
        .form-select:focus,
        .form-textarea:focus {
            outline: none;
            border-color: var(--primary);
            background: white;
            box-shadow: 0 0 0 4px rgba(255, 107, 157, 0.1);
        }

        .form-textarea {
            min-height: 150px;
            resize: vertical;
            line-height: 1.6;
        }

        /* Category Grid */
        .category-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }

        .category-card {
            background: white;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 25px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
            text-decoration: none;
            color: inherit;
            display: block;
        }

        .category-card:hover {
            border-color: var(--primary);
            transform: translateY(-2px);
            box-shadow: var(--hover-shadow);
        }

        .category-card.selected {
            border-color: var(--primary);
            background: linear-gradient(135deg, #fff5f8 0%, #ffe4ec 100%);
        }

        .category-icon {
            font-size: 2.5rem;
            color: var(--primary);
            margin-bottom: 10px;
        }

        .category-name {
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 5px;
            font-size: 1.1rem;
        }

        .category-count {
            font-size: 0.9rem;
            color: #64748b;
        }

        /* Product Preview */
        .product-preview {
            padding: 20px;
            background: #f8fafc;
            border-radius: 12px;
            margin-bottom: 25px;
        }

        .product-preview-flex {
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .product-preview img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 8px;
        }

        .product-info h4 {
            font-weight: 600;
            color: #1e293b;
            font-size: 1.1rem;
            margin-bottom: 5px;
        }

        .product-price {
            color: var(--primary);
            font-weight: 700;
            font-size: 1.3rem;
        }

        .product-description {
            color: #64748b;
            font-size: 0.9rem;
            margin-top: 5px;
        }

        .reference-note {
            background: #e7f3ff;
            border-left: 4px solid var(--primary);
            padding: 15px;
            border-radius: 8px;
            margin-top: 15px;
            color: #004085;
            font-size: 0.9rem;
        }

        /* Image Upload */
        .upload-area {
            border: 3px dashed var(--primary);
            border-radius: 20px;
            padding: 50px 30px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background: linear-gradient(135deg, #fff5f8 0%, #ffe4ec 100%);
            margin-bottom: 20px;
        }

        .upload-area:hover {
            background: linear-gradient(135deg, #ffe4ec 0%, #ffd1de 100%);
            transform: translateY(-2px);
        }

        .upload-area input {
            display: none;
        }

        .upload-icon {
            font-size: 3.5rem;
            color: var(--primary);
            margin-bottom: 15px;
        }

        .upload-text {
            font-size: 1.1rem;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 8px;
        }

        .upload-subtext {
            color: #64748b;
            font-size: 0.9rem;
        }

        .preview-box {
            display: none;
            position: relative;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: var(--card-shadow);
        }

        .preview-image {
            width: 100%;
            height: auto;
            display: block;
        }

        .remove-image-btn {
            position: absolute;
            top: 15px;
            right: 15px;
            background: rgba(255, 255, 255, 0.95);
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            color: #dc2626;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .remove-image-btn:hover {
            background: #dc2626;
            color: white;
            transform: scale(1.1);
        }

        /* Buttons */
        .btn {
            padding: 16px 32px;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            margin-right: 15px;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(255, 107, 157, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(255, 107, 157, 0.4);
        }

        .btn-primary:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .btn-secondary {
            background: white;
            color: #64748b;
            border: 2px solid #e2e8f0;
        }

        .btn-secondary:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
        }

        /* Sidebar */
        .sidebar {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .info-card {
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: var(--card-shadow);
        }

        .info-card.primary {
            background: linear-gradient(135deg, #fff5f8 0%, #ffe4ec 100%);
            border: 2px solid var(--primary);
        }

        .info-card h3 {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1.2rem;
            color: #1e293b;
            margin-bottom: 20px;
        }

        .info-card h3 i {
            color: var(--primary);
        }

        .info-list {
            list-style: none;
        }

        .info-list li {
            display: flex;
            gap: 12px;
            padding: 12px 0;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            color: #475569;
            line-height: 1.6;
        }

        .info-list li:last-child {
            border-bottom: none;
        }

        .info-list i {
            color: var(--primary);
            margin-top: 3px;
            flex-shrink: 0;
        }

        .alert {
            padding: 18px 20px;
            border-radius: 12px;
            display: flex;
            align-items: flex-start;
            gap: 12px;
            margin-bottom: 20px;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .alert-info {
            background: #e7f3ff;
            color: #004085;
            border: 1px solid #bee5eb;
        }

        .alert i {
            font-size: 1.3rem;
            margin-top: 2px;
        }

        /* Animations */
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
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

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        /* Responsive */
        @media (max-width: 968px) {
            .form-grid {
                grid-template-columns: 1fr;
            }

            .progress-steps {
                flex-direction: column;
                align-items: center;
            }

            .btn {
                width: 100%;
                justify-content: center;
                margin-right: 0;
                margin-bottom: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="main-container">
        <!-- Header -->
        <div class="page-header">
            <h1>🌸 Request Custom Bouquet</h1>
            <p>Create your perfect flower arrangement with our expert florists</p>
        </div>

        <!-- Progress Steps -->
        <div class="progress-steps">
            <div class="progress-step {{ !$category ? 'active' : '' }}">
                <div class="step-icon">1</div>
                <div class="step-text">Choose Category</div>
            </div>
            <div class="progress-step {{ $category ? 'active' : '' }}">
                <div class="step-icon">2</div>
                <div class="step-text">Customize Design</div>
            </div>
            <div class="progress-step">
                <div class="step-icon">3</div>
                <div class="step-text">Submit Request</div>
            </div>
        </div>

        <!-- Alerts -->
        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <div>{{ session('success') }}</div>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <div>{{ session('error') }}</div>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <div>
                    <strong>Please fix the following errors:</strong>
                    <ul style="margin-top: 10px; padding-left: 20px;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <!-- Main Content -->
        <div class="form-grid">
            <!-- Form -->
            <div class="form-card">
                @if(!$category)
                    <!-- Step 1: Category Selection -->
                    <div class="section-title">
                        <i class="fas fa-th-large"></i>
                        Choose a Category
                    </div>
                    <p style="color: #64748b; margin-bottom: 25px; font-size: 15px;">
                        Select a category to get started. We'll use the first available product as a reference for your custom design.
                    </p>

                    <div class="category-grid">
                        @foreach($categories as $cat)
                            <a href="{{ route('customization.create', ['category_id' => $cat->id]) }}" 
                               class="category-card">
                                <div class="category-icon">
                                    <i class="fas fa-box"></i>
                                </div>
                                <div class="category-name">{{ $cat->name }}</div>
                                <div class="category-count">
                                    {{ $cat->products->count() }} available products
                                </div>
                            </a>
                        @endforeach
                    </div>

                    <div style="text-align: center; margin-top: 30px;">
                        <a href="{{ route('customization.landing') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i>
                            Back to Home
                        </a>
                    </div>
                @else
                    <!-- Step 2: Customization Form -->
                    <form action="{{ route('customization.store') }}" method="POST" enctype="multipart/form-data" id="customizationForm">
                        @csrf

                        <!-- Hidden inputs -->
                        <input type="hidden" name="category_id" value="{{ $category->id }}">
                        <input type="hidden" name="product_id" value="{{ $referenceProduct->id }}">

                        <!-- Category Info -->
                        <div style="margin-bottom: 25px;">
                            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
                                <i class="fas fa-tag" style="color: var(--primary);"></i>
                                <span style="font-weight: 600; color: #1e293b;">Category: {{ $category->name }}</span>
                            </div>
                            <a href="{{ route('customization.create') }}" style="color: var(--primary); text-decoration: none; font-size: 0.9rem;">
                                <i class="fas fa-sync-alt"></i> Change Category
                            </a>
                        </div>

                        <!-- Reference Product -->
                        <div class="product-preview">
                            <div class="section-title" style="font-size: 1.2rem; margin-bottom: 15px;">
                                <i class="fas fa-star"></i>
                                Reference Product
                            </div>
                            <div class="product-preview-flex">
                                <img src="{{ asset('uploads/' . $referenceProduct->image) }}" 
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

                        <!-- Customization Details -->
                        <div style="margin-top: 35px;">
                            <div class="section-title">
                                <i class="fas fa-paint-brush"></i>
                                Customization Details
                            </div>

                            <div class="form-group">
                                <label class="form-label required">Customization Name</label>
                                <input type="text" 
                                       class="form-input"
                                       id="customization_name" 
                                       name="customization_name" 
                                       placeholder="e.g., Romantic Red Rose Bouquet"
                                       required
                                       value="{{ old('customization_name') }}">
                            </div>

                            <div class="form-group">
                                <label class="form-label required">Detailed Description</label>
                                <textarea class="form-textarea" 
                                          id="customization_details" 
                                          name="customization_details" 
                                          placeholder="Describe your vision in detail:&#10;• What flowers do you want?&#10;• Preferred colors and style?&#10;• Size and arrangement preferences?&#10;• Any specific flowers to include or avoid?&#10;• What's the occasion?"
                                          required>{{ old('customization_details') }}</textarea>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Special Instructions (Optional)</label>
                                <textarea class="form-textarea" 
                                          id="special_instructions" 
                                          name="special_instructions" 
                                          placeholder="Any additional details, delivery preferences, or special requests..."
                                          style="min-height: 100px;">{{ old('special_instructions') }}</textarea>
                            </div>
                        </div>

                        <!-- Image Upload -->
                        <div style="margin-top: 35px;">
                            <div class="section-title">
                                <i class="fas fa-images"></i>
                                Reference Images
                            </div>
                            <p style="color: #64748b; margin-bottom: 20px; font-size: 14px;">
                                Upload inspiration photos to help us understand your vision better (optional)
                            </p>

                            <div class="upload-area" onclick="document.getElementById('custom_image').click()">
                                <div class="upload-icon"><i class="fas fa-cloud-upload-alt"></i></div>
                                <div class="upload-text">Click to upload image</div>
                                <div class="upload-subtext">JPG, PNG, GIF up to 5MB</div>
                                <input type="file" 
                                       id="custom_image" 
                                       name="custom_image" 
                                       accept="image/*"
                                       onchange="previewImage(this)">
                            </div>

                            <div class="preview-box" id="previewBox">
                                <img id="imagePreview" class="preview-image" src="" alt="Preview">
                                <button type="button" class="remove-image-btn" onclick="removeImage()">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div style="margin-top: 40px; display: flex; gap: 15px;">
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <i class="fas fa-paper-plane"></i>
                                Submit Customization Request
                            </button>
                            <a href="{{ route('customization.create') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i>
                                Change Category
                            </a>
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
                            <li>
                                <i class="fas fa-check"></i>
                                <span>Choose a category to get started</span>
                            </li>
                            <li>
                                <i class="fas fa-check"></i>
                                <span>We'll show you a reference product from that category</span>
                            </li>
                            <li>
                                <i class="fas fa-check"></i>
                                <span>Describe your custom design requirements</span>
                            </li>
                            <li>
                                <i class="fas fa-check"></i>
                                <span>Submit your request for review</span>
                            </li>
                        </ul>
                    </div>
                @else
                    <div class="info-card primary">
                        <h3><i class="fas fa-info-circle"></i> About This Category</h3>
                        <ul class="info-list">
                            <li>
                                <i class="fas fa-star"></i>
                                <span>Category: <strong>{{ $category->name }}</strong></span>
                            </li>
                            <li>
                                <i class="fas fa-box"></i>
                                <span>Reference Product: <strong>{{ $referenceProduct->name }}</strong></span>
                            </li>
                            <li>
                                <i class="fas fa-money-bill-wave"></i>
                                <span>Base Price: <strong>₱{{ number_format($referenceProduct->price, 2) }}</strong></span>
                            </li>
                            <li>
                                <i class="fas fa-lightbulb"></i>
                                <span>Your custom design may vary in price based on requirements</span>
                            </li>
                        </ul>
                    </div>
                @endif

                <div class="info-card">
                    <h3><i class="fas fa-lightbulb"></i> Tips for Best Results</h3>
                    <ul class="info-list">
                        <li>
                            <i class="fas fa-star"></i>
                            <span>Be specific about flower types and colors</span>
                        </li>
                        <li>
                            <i class="fas fa-star"></i>
                            <span>Upload clear reference images if possible</span>
                        </li>
                        <li>
                            <i class="fas fa-star"></i>
                            <span>Mention the occasion for better recommendations</span>
                        </li>
                        <li>
                            <i class="fas fa-star"></i>
                            <span>Include budget preferences if any</span>
                        </li>
                    </ul>
                </div>

                <div class="alert alert-info" style="margin: 0;">
                    <i class="fas fa-shield-alt"></i>
                    <div>
                        <strong>No obligation!</strong> You'll receive a custom price quote for approval before any payment.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Image Preview
        function previewImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('imagePreview').src = e.target.result;
                    document.getElementById('previewBox').style.display = 'block';
                    document.querySelector('.upload-area').style.display = 'none';
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function removeImage() {
            document.getElementById('custom_image').value = '';
            document.getElementById('previewBox').style.display = 'none';
            document.querySelector('.upload-area').style.display = 'block';
        }

        // Form validation
        @if($category)
        document.getElementById('customizationForm').addEventListener('submit', function(e) {
            const customizationName = document.getElementById('customization_name').value;
            const customizationDetails = document.getElementById('customization_details').value;
            
            if (!customizationName.trim() || !customizationDetails.trim()) {
                e.preventDefault();
                alert('Please fill in all required fields.');
                return;
            }
            
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Submitting...';
        });
        @endif
    </script>
</body>
</html>