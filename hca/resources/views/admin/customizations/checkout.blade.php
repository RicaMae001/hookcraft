<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout Customization - Flower Shop</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px 40px;
            text-align: center;
        }

        .header h1 {
            font-size: 2.2em;
            margin-bottom: 10px;
        }

        .content {
            padding: 40px;
        }

        .customization-summary {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 30px;
            border-left: 5px solid #FF6B9D;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #e0e0e0;
        }

        .summary-row:last-child {
            border-bottom: none;
        }

        .total-row {
            font-size: 1.3em;
            font-weight: bold;
            color: #FF6B9D;
        }

        .btn {
            padding: 15px 30px;
            border: none;
            border-radius: 10px;
            font-size: 1.1em;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-block;
            text-decoration: none;
            text-align: center;
            width: 100%;
            margin-top: 10px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #FF6B9D 0%, #C06C84 100%);
            color: white;
        }

        .btn-secondary {
            background: #667eea;
            color: white;
        }

        .btn-success {
            background: #26de81;
            color: white;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }

        .image-preview {
            width: 200px;
            height: 200px;
            object-fit: cover;
            border-radius: 10px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🛒 Checkout Customization</h1>
            <p>Review your customized item before checkout</p>
        </div>

        <div class="content">
            @if(session('error'))
                <div style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 10px; margin-bottom: 20px;">
                    {{ session('error') }}
                </div>
            @endif

            @if(session('success'))
                <div style="background: #d4edda; color: #155724; padding: 15px; border-radius: 10px; margin-bottom: 20px;">
                    {{ session('success') }}
                </div>
            @endif

            <div class="customization-summary">
                <h3>{{ $customization->customization_name }}</h3>
                
                @if($customization->custom_image)
                    <img src="{{ asset('uploads/customizations/' . $customization->custom_image) }}" 
                         alt="{{ $customization->customization_name }}"
                         class="image-preview">
                @endif
                
                <p style="color: #666; margin: 10px 0 20px;">Based on: <strong>{{ $customization->product->name }}</strong></p>
                
                <div class="summary-row">
                    <span>Base Product Price:</span>
                    <span>₱{{ number_format($customization->product->price, 2) }}</span>
                </div>
                
                <div class="summary-row">
                    <span>Customization Fee:</span>
                    <span>₱{{ number_format($customization->admin_price, 2) }}</span>
                </div>
                
                <div class="summary-row total-row">
                    <span>Total Price:</span>
                    <span>₱{{ number_format($customization->product->price + $customization->admin_price, 2) }}</span>
                </div>

                @if($customization->admin_notes)
                    <div style="margin-top: 20px; padding: 15px; background: #e3f2fd; border-radius: 8px;">
                        <strong>Admin Notes:</strong>
                        <p style="margin-top: 5px;">{{ $customization->admin_notes }}</p>
                    </div>
                @endif
            </div>

            <form action="{{ route('customization.add-to-cart', $customization->id) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-success">
                    🛒 Add to Cart & Proceed to Checkout
                </button>
            </form>

            <a href="{{ route('cart.index') }}" class="btn btn-secondary">
                View Cart First
            </a>

            <a href="{{ route('customization.my-customizations') }}" class="btn" style="background: #ccc; color: #333;">
                ← Back to My Customizations
            </a>
        </div>
    </div>
</body>
</html>