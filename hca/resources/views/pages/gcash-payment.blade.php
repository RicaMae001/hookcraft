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
        :root {
            --primary-pink: #d63384;
            --accent-rose: #e91e63;
            --success-green: #198754;
        }

        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            min-height: 100vh;
            padding: 2rem 0;
        }

        .payment-container {
            max-width: 600px;
            margin: 0 auto;
        }

        .payment-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            overflow: hidden;
            margin-bottom: 2rem;
        }

        .payment-header {
            background: linear-gradient(135deg, var(--primary-pink), var(--accent-rose));
            color: white;
            padding: 2rem;
            text-align: center;
        }

        .payment-header h2 {
            margin: 0 0 0.5rem 0;
            font-weight: 700;
        }

        .payment-header p {
            margin: 0;
            opacity: 0.9;
        }

        .payment-body {
            padding: 2rem;
        }

        .qr-section {
            text-align: center;
            margin-bottom: 2rem;
            padding: 2rem;
            background: #f8f9fa;
            border-radius: 12px;
        }

        .qr-code {
            max-width: 300px;
            width: 100%;
            height: auto;
            border: 4px solid white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
            margin-bottom: 1rem;
        }

        .gcash-number {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-pink);
            margin: 1rem 0;
        }

        .amount-display {
            background: linear-gradient(135deg, #e8f5e9, #c8e6c9);
            padding: 1.5rem;
            border-radius: 12px;
            text-align: center;
            margin-bottom: 2rem;
        }

        .amount-label {
            font-size: 0.875rem;
            color: #2e7d32;
            margin-bottom: 0.5rem;
        }

        .amount-value {
            font-size: 2.5rem;
            font-weight: 700;
            color: #1b5e20;
        }

        .instructions {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 1.5rem;
            border-radius: 8px;
            margin-bottom: 2rem;
        }

        .instructions h5 {
            color: #856404;
            margin-bottom: 1rem;
            font-weight: 600;
        }

        .instructions ol {
            margin: 0;
            padding-left: 1.25rem;
        }

        .instructions li {
            margin-bottom: 0.5rem;
            color: #856404;
        }

        .upload-section {
            border: 2px dashed var(--primary-pink);
            border-radius: 12px;
            padding: 2rem;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .upload-section:hover {
            background: #fce7f3;
            border-color: var(--accent-rose);
        }

        .upload-icon {
            font-size: 3rem;
            color: var(--primary-pink);
            margin-bottom: 1rem;
        }

        #imagePreview {
            max-width: 100%;
            max-height: 300px;
            border-radius: 8px;
            margin-top: 1rem;
            display: none;
        }

        .btn-submit {
            background: linear-gradient(135deg, var(--primary-pink), var(--accent-rose));
            color: white;
            border: none;
            padding: 1rem 3rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1.1rem;
            width: 100%;
            transition: all 0.3s ease;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(214, 51, 132, 0.3);
        }

        .btn-submit:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .order-info {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 12px;
            margin-bottom: 2rem;
        }

        .order-info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.75rem;
        }

        .order-info-row:last-child {
            margin-bottom: 0;
            padding-top: 0.75rem;
            border-top: 2px solid #dee2e6;
            font-weight: 700;
        }

        @media (max-width: 768px) {
            .payment-container {
                padding: 0 1rem;
            }

            .payment-body {
                padding: 1.5rem;
            }

            .amount-value {
                font-size: 2rem;
            }

            .gcash-number {
                font-size: 1.25rem;
            }
        }
    </style>
</head>
<body>

@include('components.navbar')

<div class="payment-container">
    <!-- Payment Card -->
    <div class="payment-card">
        <div class="payment-header">
            <i class="bi bi-phone fs-1 mb-3"></i>
            <h2>GCash Payment</h2>
            <p>Order #{{ $order->id }}</p>
        </div>

        <div class="payment-body">
            <!-- Order Information -->
            <div class="order-info">
                <h5 class="mb-3"><i class="bi bi-receipt me-2"></i>Order Details</h5>
                <div class="order-info-row">
                    <span>Customer:</span>
                    <strong>{{ $order->customer_name }}</strong>
                </div>
                <div class="order-info-row">
                    <span>Phone:</span>
                    <strong>{{ $order->phone }}</strong>
                </div>
                <div class="order-info-row">
                    <span>Order Date:</span>
                    <strong>{{ $order->created_at->format('M d, Y h:i A') }}</strong>
                </div>
                <div class="order-info-row">
                    <span>Total Amount:</span>
                    <strong class="text-success">₱{{ number_format($order->total, 2) }}</strong>
                </div>
            </div>

            <!-- Amount Display -->
            <div class="amount-display">
                <div class="amount-label">Amount to Pay</div>
                <div class="amount-value">₱{{ number_format($order->total, 2) }}</div>
            </div>

            <!-- QR Code Section -->
            <div class="qr-section">
                <h5 class="mb-3">Scan QR Code</h5>
                <img src="{{ asset('asset/images/gcash-qr.png') }}" alt="GCash QR Code" class="qr-code">
                <p class="text-muted mb-2">or send to</p>
                <div class="gcash-number">09123456789</div>
                <small class="text-muted">Jane Doe</small>
            </div>

            <!-- Instructions -->
            <div class="instructions">
                <h5><i class="bi bi-info-circle me-2"></i>Payment Instructions</h5>
                <ol>
                    <li>Open your GCash app</li>
                    <li>Scan the QR code above or send to <strong>09123456789</strong></li>
                    <li>Enter the exact amount: <strong>₱{{ number_format($order->total, 2) }}</strong></li>
                    <li>Complete the payment</li>
                    <li>Take a screenshot of the payment confirmation</li>
                    <li>Upload the screenshot below</li>
                </ol>
            </div>

            <!-- Upload Section -->
            <form action="{{ route('checkout.gcash.submit', $order->id) }}" method="POST" enctype="multipart/form-data" id="paymentForm">
                @csrf
                
                <div class="upload-section" onclick="document.getElementById('paymentProof').click()">
                    <i class="bi bi-cloud-upload upload-icon"></i>
                    <h5>Upload Payment Proof</h5>
                    <p class="text-muted mb-0">Click to select screenshot (JPG, PNG, max 5MB)</p>
                    <input type="file" 
                           id="paymentProof" 
                           name="payment_proof" 
                           accept="image/*" 
                           required 
                           style="display: none;"
                           onchange="previewImage(event)">
                    <img id="imagePreview" alt="Preview">
                </div>

                @error('payment_proof')
                    <div class="alert alert-danger mt-3">{{ $message }}</div>
                @enderror

                <button type="submit" class="btn btn-submit mt-4" id="submitBtn" disabled>
                    <i class="bi bi-check-circle me-2"></i>Submit Payment Proof
                </button>
            </form>

            <div class="text-center mt-3">
                <small class="text-muted">
                    <i class="bi bi-shield-check me-1"></i>
                    Your payment information is secure
                </small>
            </div>
        </div>
    </div>

    <!-- Help Section -->
    <div class="text-center">
        <p class="text-muted">
            Need help? <a href="#" class="fw-bold" style="color: var(--primary-pink);">Contact Support</a>
        </p>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script>
    function previewImage(event) {
        const file = event.target.files[0];
        const preview = document.getElementById('imagePreview');
        const submitBtn = document.getElementById('submitBtn');
        
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
                submitBtn.disabled = false;
            }
            reader.readAsDataURL(file);
        }
    }

    // Prevent form resubmission
    document.getElementById('paymentForm').addEventListener('submit', function() {
        document.getElementById('submitBtn').disabled = true;
        document.getElementById('submitBtn').innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Submitting...';
    });
</script>

</body>
</html>