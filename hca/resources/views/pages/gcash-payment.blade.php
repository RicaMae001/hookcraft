{{-- Create file: resources/views/pages/gcash-payment.blade.php --}}

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GCash Payment - Hookcraft Avenue</title>
    <link rel="icon" href="{{ asset('asset/images/logo.jpg') }}" type="image/png">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('asset/stylesnav.css') }}">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: #f5f7fa;
            min-height: 100vh;
        }

        .payment-wrapper {
            max-width: 480px;
            margin: 0 auto;
            padding: 1rem;
            padding-top: 2rem;
        }

        /* Progress Steps */
        .progress-steps {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2rem;
            position: relative;
        }

        .progress-steps::before {
            content: '';
            position: absolute;
            top: 20px;
            left: 0;
            right: 0;
            height: 2px;
            background: #e0e6ed;
            z-index: 0;
        }

        .step {
            flex: 1;
            text-align: center;
            position: relative;
            z-index: 1;
        }

        .step-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: white;
            border: 2px solid #e0e6ed;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 0.5rem;
            font-weight: 600;
            color: #94a3b8;
            transition: all 0.3s ease;
        }

        .step.active .step-circle {
            background: #007bff;
            border-color: #007bff;
            color: white;
        }

        .step.completed .step-circle {
            background: #10b981;
            border-color: #10b981;
            color: white;
        }

        .step-label {
            font-size: 0.75rem;
            color: #64748b;
            font-weight: 500;
        }

        .step.active .step-label {
            color: #0f172a;
        }

        /* Main Card */
        .payment-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            margin-bottom: 1rem;
            overflow: hidden;
        }

        /* Order Summary */
        .order-summary {
            padding: 1.5rem;
            border-bottom: 1px solid #f1f5f9;
        }

        .order-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1rem;
        }

        .order-id {
            font-size: 0.875rem;
            color: #64748b;
            font-weight: 500;
        }

        .order-status {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.75rem;
            background: #fef3c7;
            color: #92400e;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .amount-box {
            background: linear-gradient(135deg, #0ea5e9 0%, #2563eb 100%);
            padding: 1.25rem;
            border-radius: 12px;
            text-align: center;
            color: white;
        }

        .amount-label {
            font-size: 0.875rem;
            opacity: 0.9;
            margin-bottom: 0.25rem;
        }

        .amount-value {
            font-size: 2rem;
            font-weight: 700;
            letter-spacing: -0.02em;
        }

        /* Payment Section */
        .payment-section {
            padding: 1.5rem;
        }

        .section-title {
            font-size: 0.875rem;
            font-weight: 600;
            color: #0f172a;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .gcash-info {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 1.25rem;
            margin-bottom: 1.5rem;
        }

        .qr-container {
            text-align: center;
            margin-bottom: 1rem;
        }

        .qr-image {
            width: 200px;
            height: 200px;
            border-radius: 8px;
            border: 3px solid white;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .gcash-details {
            text-align: center;
            padding-top: 1rem;
            border-top: 1px dashed #cbd5e1;
        }

        .gcash-number {
            font-size: 1.25rem;
            font-weight: 700;
            color: #0f172a;
            margin: 0.5rem 0 0.25rem;
            letter-spacing: 0.02em;
        }

        .gcash-name {
            font-size: 0.875rem;
            color: #64748b;
        }

        /* Steps List */
        .steps-list {
            background: #f1f5f9;
            border-radius: 12px;
            padding: 1.25rem;
            margin-bottom: 1.5rem;
        }

        .steps-list ol {
            margin: 0;
            padding-left: 1.25rem;
        }

        .steps-list li {
            color: #334155;
            font-size: 0.875rem;
            margin-bottom: 0.5rem;
            line-height: 1.6;
        }

        .steps-list li:last-child {
            margin-bottom: 0;
        }

        .steps-list strong {
            color: #0f172a;
        }

        /* Upload Area */
        .upload-area {
            position: relative;
            border: 2px dashed #cbd5e1;
            border-radius: 12px;
            padding: 2rem 1.5rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s ease;
            background: #fafbfc;
        }

        .upload-area:hover {
            border-color: #3b82f6;
            background: #eff6ff;
        }

        .upload-area.has-file {
            border-color: #10b981;
            background: #f0fdf4;
        }

        .upload-icon {
            font-size: 2.5rem;
            color: #94a3b8;
            margin-bottom: 0.75rem;
        }

        .upload-area.has-file .upload-icon {
            color: #10b981;
        }

        .upload-text h6 {
            font-size: 0.9375rem;
            font-weight: 600;
            color: #0f172a;
            margin-bottom: 0.25rem;
        }

        .upload-text p {
            font-size: 0.8125rem;
            color: #64748b;
            margin: 0;
        }

        .file-preview {
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid #e2e8f0;
        }

        .preview-image {
            max-width: 100%;
            max-height: 200px;
            border-radius: 8px;
            display: none;
        }

        .file-name {
            display: none;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
            color: #0f172a;
            margin-top: 0.75rem;
            font-weight: 500;
        }

        .file-name i {
            color: #10b981;
        }

        /* Submit Button */
        .btn-submit {
            width: 100%;
            background: #3b82f6;
            color: white;
            border: none;
            padding: 1rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            transition: all 0.2s ease;
            margin-top: 1.5rem;
        }

        .btn-submit:hover:not(:disabled) {
            background: #2563eb;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }

        .btn-submit:disabled {
            background: #cbd5e1;
            cursor: not-allowed;
            transform: none;
        }

        /* Footer */
        .payment-footer {
            text-align: center;
            padding: 1.5rem;
            font-size: 0.8125rem;
            color: #64748b;
        }

        .security-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            color: #10b981;
            font-weight: 500;
        }

        /* Alert */
        .alert-custom {
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 1rem;
            display: flex;
            gap: 0.75rem;
        }

        .alert-custom i {
            color: #dc2626;
            flex-shrink: 0;
        }

        .alert-custom-text {
            font-size: 0.875rem;
            color: #991b1b;
        }

        @media (max-width: 576px) {
            .payment-wrapper {
                padding: 0.75rem;
                padding-top: 1.5rem;
            }

            .amount-value {
                font-size: 1.75rem;
            }

            .qr-image {
                width: 180px;
                height: 180px;
            }

            .step-label {
                font-size: 0.7rem;
            }
        }
    </style>
</head>
<body>

@include('components.navbar')

<div class="payment-wrapper">
    <!-- Progress Steps -->
    <div class="progress-steps">
        <div class="step completed">
            <div class="step-circle"><i class="bi bi-check"></i></div>
            <div class="step-label">Order</div>
        </div>
        <div class="step active">
            <div class="step-circle">2</div>
            <div class="step-label">Payment</div>
        </div>
        <div class="step">
            <div class="step-circle">3</div>
            <div class="step-label">Complete</div>
        </div>
    </div>

    <!-- Main Payment Card -->
    <div class="payment-card">
        <!-- Order Summary -->
        <div class="order-summary">
            <div class="order-header">
                <div class="order-id">Order #{{ $order->id }}</div>
                <div class="order-status">
                    <i class="bi bi-clock me-1"></i> Pending Payment
                </div>
            </div>
            
            <div class="amount-box">
                <div class="amount-label">Total Amount</div>
                <div class="amount-value">₱{{ number_format($order->total, 2) }}</div>
            </div>
        </div>

        <!-- Payment Instructions -->
        <div class="payment-section">
            <div class="section-title">
                <i class="bi bi-1-circle"></i> Send payment via GCash
            </div>

            <div class="gcash-info">
                <div class="qr-container">
                    <img src="{{ asset('asset/images/qr.jpg') }}" alt="GCash QR Code" class="qr-image">
                </div>
                <div class="gcash-details">
                    <div style="font-size: 0.8125rem; color: #64748b;">Send to</div>
                    <div class="gcash-number">09567032464</div>
                    <div class="gcash-name">ST****N C</div>
                </div>
            </div>

            <div class="steps-list">
                <ol>
                    <li>Open your GCash app</li>
                    <li>Scan the QR code above or send to <strong>09123456789</strong></li>
                    <li>Enter the exact amount: <strong>₱{{ number_format($order->total, 2) }}</strong></li>
                    <li>Complete the payment</li>
                    <li>Take a screenshot of the payment confirmation</li>
                    <li>Upload the screenshot below</li>
                </ol>
            </div>

            <div class="section-title">
                <i class="bi bi-2-circle"></i> Upload payment proof
            </div>

            <form action="{{ route('checkout.gcash.submit', $order->id) }}" method="POST" enctype="multipart/form-data" id="paymentForm">
                @csrf
                
                <div class="upload-area" onclick="document.getElementById('paymentProof').click()" id="uploadArea">
                    <i class="bi bi-cloud-arrow-up upload-icon" id="uploadIcon"></i>
                    <div class="upload-text">
                        <h6>Tap to upload screenshot</h6>
                        <p>JPG or PNG, max 5MB</p>
                    </div>
                    <input type="file" 
                           id="paymentProof" 
                           name="payment_proof" 
                           accept="image/*" 
                           required 
                           style="display: none;"
                           onchange="previewImage(event)">
                    
                    <div class="file-preview">
                        <img id="imagePreview" class="preview-image" alt="Preview">
                        <div class="file-name" id="fileName">
                            <i class="bi bi-check-circle-fill"></i>
                            <span id="fileNameText"></span>
                        </div>
                    </div>
                </div>

                @error('payment_proof')
                    <div class="alert-custom">
                        <i class="bi bi-exclamation-circle"></i>
                        <div class="alert-custom-text">{{ $message }}</div>
                    </div>
                @enderror

                <button type="submit" class="btn-submit" id="submitBtn" disabled>
                    <i class="bi bi-check-circle"></i>
                    <span>Submit Payment</span>
                </button>
            </form>
        </div>

        <!-- Footer -->
        <div class="payment-footer">
            <div class="security-badge">
                <i class="bi bi-shield-check"></i>
                Secure payment processing
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script>
    function previewImage(event) {
        const file = event.target.files[0];
        const preview = document.getElementById('imagePreview');
        const submitBtn = document.getElementById('submitBtn');
        const uploadArea = document.getElementById('uploadArea');
        const uploadIcon = document.getElementById('uploadIcon');
        const fileName = document.getElementById('fileName');
        const fileNameText = document.getElementById('fileNameText');
        
        if (file) {
            // Validate file size (5MB max)
            if (file.size > 5 * 1024 * 1024) {
                alert('File size must be less than 5MB');
                event.target.value = '';
                return;
            }
            
            // Validate file type
            if (!file.type.match('image.*')) {
                alert('Please upload an image file');
                event.target.value = '';
                return;
            }
            
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
                uploadArea.classList.add('has-file');
                uploadIcon.className = 'bi bi-check-circle-fill upload-icon';
                fileName.style.display = 'flex';
                fileNameText.textContent = file.name;
                submitBtn.disabled = false;
            }
            reader.readAsDataURL(file);
        }
    }

    // Prevent form resubmission
    document.getElementById('paymentForm').addEventListener('submit', function(e) {
        const submitBtn = document.getElementById('submitBtn');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i><span>Processing...</span>';
    });
</script>

</body>
</html>