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
            width: 80px;
            height: 80px;
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
            <div class="progress-step active">
                <div class="step-icon">1</div>
                <div class="step-text">Design</div>
            </div>
            <div class="progress-step">
                <div class="step-icon">2</div>
                <div class="step-text">Review</div>
            </div>
            <div class="progress-step">
                <div class="step-icon">3</div>
                <div class="step-text">Approve</div>
            </div>
            <div class="progress-step">
                <div class="step-icon">4</div>
                <div class="step-text">Checkout</div>
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
                <form action="{{ route('customization.store') }}" method="POST" enctype="multipart/form-data" id="customizationForm">
                    @csrf

                    <!-- Product Selection -->
                    <div class="section-title">
                        <i class="fas fa-box-open"></i>
                        Select Base Product
                    </div>

                    @if(!$product)
                        <!-- Dropdown for product selection -->
                        <div class="form-group">
                            <label class="form-label required">Choose Product to Customize</label>
                            <select class="form-select" id="productSelect" name="product_id" required>
                                <option value="">-- Select a Product --</option>
                                @foreach($products as $p)
                                    <option value="{{ $p->id }}" 
                                            data-name="{{ $p->name }}"
                                            data-price="{{ $p->price }}"
                                            data-image="{{ $p->image }}"
                                            data-description="{{ $p->description ?? '' }}"
                                            {{ old('product_id') == $p->id ? 'selected' : '' }}>
                                        {{ $p->name }} - ₱{{ number_format($p->price, 2) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Selected Product Preview -->
                        <div id="selectedProductInfo" class="product-preview" style="display: none;">
                            <div class="product-preview-flex">
                                <img id="productImage" src="" alt="">
                                <div class="product-info">
                                    <h4 id="productName"></h4>
                                    <div class="product-price" id="productPrice"></div>
                                    <div class="product-description" id="productDescription"></div>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- Pre-selected product -->
                        <input type="hidden" name="product_id" id="product_id" value="{{ $product->id }}">
                        
                        <div class="product-preview">
                            <div class="product-preview-flex">
                                <img src="{{ asset('uploads/' . $product->image) }}" 
                                     alt="{{ $product->name }}"
                                     onerror="this.src='{{ asset('images/placeholder.jpg') }}'">
                                <div class="product-info">
                                    <h4>{{ $product->name }}</h4>
                                    <div class="product-price">₱{{ number_format($product->price, 2) }}</div>
                                    @if($product->description)
                                        <div class="product-description">{{ Str::limit($product->description, 100) }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif

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
                                      placeholder="Describe your vision:&#10;• What flowers do you want?&#10;• Preferred colors?&#10;• Size and arrangement style?&#10;• Any specific flowers to include or avoid?&#10;• What's the occasion?"
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
                    <div style="margin-top: 40px;">
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <i class="fas fa-paper-plane"></i>
                            Submit Customization Request
                        </button>
                        <a href="{{ route('customization.landing') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i>
                            Back
                        </a>
                    </div>
                </form>
            </div>

            <!-- Sidebar -->
            <div class="sidebar">
                <div class="info-card primary">
                    <h3><i class="fas fa-info-circle"></i> How It Works</h3>
                    <ul class="info-list">
                        <li>
                            <i class="fas fa-check"></i>
                            <span>Submit your custom design request with details and images</span>
                        </li>
                        <li>
                            <i class="fas fa-check"></i>
                            <span>Our florists review your request within 24 hours</span>
                        </li>
                        <li>
                            <i class="fas fa-check"></i>
                            <span>Receive a custom price quote for your design</span>
                        </li>
                        <li>
                            <i class="fas fa-check"></i>
                            <span>Approve and proceed to checkout</span>
                        </li>
                    </ul>
                </div>

                <div class="info-card">
                    <h3><i class="fas fa-lightbulb"></i> Tips for Best Results</h3>
                    <ul class="info-list">
                        <li>
                            <i class="fas fa-star"></i>
                            <span>Be specific about flower types and colors you want</span>
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
                            <span>Include any allergies or flower preferences</span>
                        </li>
                    </ul>
                </div>

                <div class="alert alert-info" style="margin: 0;">
                    <i class="fas fa-shield-alt"></i>
                    <div>
                        <strong>No obligation!</strong> You can review and approve the price before making any payment.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Product Selection
        @if(!$product)
        document.getElementById('productSelect').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const productInfo = document.getElementById('selectedProductInfo');
            
            if (selectedOption.value) {
                productInfo.style.display = 'block';
                document.getElementById('productName').textContent = selectedOption.dataset.name;
                document.getElementById('productPrice').textContent = '₱' + parseFloat(selectedOption.dataset.price).toLocaleString('en-US', {minimumFractionDigits: 2});
                document.getElementById('productDescription').textContent = selectedOption.dataset.description || 'No description available';
                
                const imgElement = document.getElementById('productImage');
                if (selectedOption.dataset.image) {
                    imgElement.src = '{{ asset("uploads/") }}/' + selectedOption.dataset.image;
                    imgElement.onerror = function() {
                        this.src = '{{ asset("images/placeholder.jpg") }}';
                    };
                }
            } else {
                productInfo.style.display = 'none';
            }
        });
        @endif

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
        document.getElementById('customizationForm').addEventListener('submit', function(e) {
            const productId = document.querySelector('input[name="product_id"], select[name="product_id"]').value;
            const customizationName = document.getElementById('customization_name').value;
            const customizationDetails = document.getElementById('customization_details').value;
            
            if (!productId) {
                e.preventDefault();
                alert('Please select a product to customize.');
                return;
            }
            
            if (!customizationName.trim() || !customizationDetails.trim()) {
                e.preventDefault();
                alert('Please fill in all required fields.');
                return;
            }
            
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Submitting...';
        });
    </script>
</body>
</html>