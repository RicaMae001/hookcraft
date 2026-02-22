<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Customization - {{ $customization->customization_name }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #FF6B9D;
            --primary-dark: #C06C84;
            --secondary: #667eea;
            --background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            --card-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
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
            max-width: 1000px;
            margin: 0 auto;
        }

        .page-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .page-header h1 {
            font-size: clamp(2rem, 5vw, 2.5rem);
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 10px;
            font-weight: 800;
        }

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

        .current-image {
            margin-bottom: 20px;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: var(--card-shadow);
        }

        .current-image img {
            width: 100%;
            max-width: 400px;
            display: block;
        }

        .current-image-label {
            font-size: 0.9rem;
            color: #64748b;
            margin-bottom: 10px;
            font-weight: 600;
        }

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

        .btn-secondary {
            background: white;
            color: #64748b;
            border: 2px solid #e2e8f0;
        }

        .btn-secondary:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
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
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
        }

        .alert i {
            font-size: 1.3rem;
            margin-top: 2px;
        }

        .product-info-box {
            background: #f8fafc;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 30px;
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .product-info-box img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
        }

        .product-info-box h3 {
            font-size: 1.1rem;
            color: #1e293b;
            margin-bottom: 5px;
        }

        .product-info-box .price {
            color: var(--primary);
            font-weight: 700;
            font-size: 1.2rem;
        }

        @media (max-width: 768px) {
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
        <div class="page-header">
            <h1>✏️ Edit Customization</h1>
            <p style="color: #64748b;">Update your custom bouquet request</p>
        </div>

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

        <div class="form-card">
            <!-- Product Info -->
            <div class="product-info-box">
                <img src="{{ asset('uploads/' . $customization->product->image) }}" 
                     alt="{{ $customization->product->name }}"
                     onerror="this.src='{{ asset('images/placeholder.jpg') }}'">
                <div>
                    <h3>{{ $customization->product->name }}</h3>
                    <div class="price">₱{{ number_format($customization->product->price, 2) }}</div>
                </div>
            </div>

            <form action="{{ route('customization.update', $customization->id) }}" 
                  method="POST" 
                  enctype="multipart/form-data" 
                  id="editForm">
                @csrf
                @method('PUT')

                <!-- Customization Details -->
                <div class="section-title">
                    <i class="fas fa-paint-brush"></i>
                    Customization Details
                </div>

                <div class="form-group">
                    <label class="form-label required">Customization Name</label>
                    <input type="text" 
                           class="form-input"
                           name="customization_name" 
                           placeholder="e.g., Romantic Red Rose Bouquet"
                           required
                           value="{{ old('customization_name', $customization->customization_name) }}">
                </div>

                <div class="form-group">
                    <label class="form-label required">Detailed Description</label>
                    <textarea class="form-textarea" 
                              name="customization_details" 
                              placeholder="Describe your vision..."
                              required>{{ old('customization_details', $customization->customization_details) }}</textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Special Instructions (Optional)</label>
                    <textarea class="form-textarea" 
                              name="special_instructions" 
                              placeholder="Any additional details..."
                              style="min-height: 100px;">{{ old('special_instructions', $customization->special_instructions) }}</textarea>
                </div>

                <!-- Image Upload -->
                <div style="margin-top: 35px;">
                    <div class="section-title">
                        <i class="fas fa-images"></i>
                        Reference Image
                    </div>

                    @if($customization->custom_image)
                        <div class="current-image-label">Current Image:</div>
                        <div class="current-image">
                            <img src="{{ asset('uploads/customizations/' . $customization->custom_image) }}" 
                                 alt="Current customization image">
                        </div>
                        <p style="color: #64748b; margin-bottom: 20px; font-size: 14px;">
                            Upload a new image to replace the current one (optional)
                        </p>
                    @else
                        <p style="color: #64748b; margin-bottom: 20px; font-size: 14px;">
                            Upload an inspiration photo (optional)
                        </p>
                    @endif

                    <div class="upload-area" onclick="document.getElementById('custom_image').click()">
                        <div class="upload-icon"><i class="fas fa-cloud-upload-alt"></i></div>
                        <div class="upload-text">Click to upload new image</div>
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
                        <i class="fas fa-save"></i>
                        Update Customization
                    </button>
                    <a href="{{ route('customization.show', $customization->id) }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i>
                        Cancel
                    </a>
                </div>
            </form>
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

        // Form submission
        document.getElementById('editForm').addEventListener('submit', function(e) {
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Updating...';
        });
    </script>
</body>
</html>