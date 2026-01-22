<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Customize Your Product - Flower Shop</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, #FF6B9D 0%, #C06C84 100%);
            color: white;
            padding: 30px 40px;
            text-align: center;
        }

        .header h1 {
            font-size: 2.5em;
            margin-bottom: 10px;
        }

        .content {
            padding: 40px;
        }

        .intro {
            text-align: center;
            margin-bottom: 40px;
        }

        .intro h2 {
            color: #FF6B9D;
            margin-bottom: 15px;
            font-size: 2em;
        }

        .intro p {
            color: #666;
            max-width: 800px;
            margin: 0 auto 20px;
            line-height: 1.6;
            font-size: 1.1em;
        }

        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 25px;
            margin-top: 30px;
        }

        .product-card {
            background: #f8f9fa;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .product-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
        }

        .product-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .product-info {
            padding: 20px;
        }

        .product-name {
            font-size: 1.2em;
            color: #333;
            margin-bottom: 10px;
            font-weight: 600;
        }

        .product-price {
            color: #FF6B9D;
            font-size: 1.3em;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .product-description {
            color: #666;
            font-size: 0.9em;
            margin-bottom: 20px;
            line-height: 1.5;
            min-height: 60px;
        }

        .btn {
            padding: 12px 25px;
            border: none;
            border-radius: 10px;
            font-size: 1em;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary {
            background: linear-gradient(135deg, #FF6B9D 0%, #C06C84 100%);
            color: white;
            width: 100%;
            text-align: center;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(255, 107, 157, 0.3);
        }

        .custom-design-section {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 40px;
            margin-top: 50px;
            text-align: center;
            border: 3px dashed #667eea;
        }

        .custom-design-section h3 {
            color: #667eea;
            margin-bottom: 20px;
            font-size: 1.8em;
        }

        .custom-design-section p {
            color: #666;
            margin-bottom: 25px;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
            font-size: 1.1em;
            line-height: 1.6;
        }

        .btn-secondary {
            background: #667eea;
            color: white;
            padding: 15px 40px;
            font-size: 1.1em;
        }

        .btn-secondary:hover {
            background: #5568d3;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }

        .alert {
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .alert-info {
            background: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
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

        .section-title {
            color: #FF6B9D;
            font-size: 1.8em;
            margin: 30px 0 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #f0f0f0;
        }

        @media (max-width: 768px) {
            .products-grid {
                grid-template-columns: 1fr;
            }
            
            .header h1 {
                font-size: 2em;
            }
            
            .intro h2 {
                font-size: 1.6em;
            }
            
            .content {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>✨ Design Your Custom Product ✨</h1>
            <p>Create something unique and personal</p>
        </div>

        <div class="content">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if(session('error'))
                <div class="alert alert-error">{{ session('error') }}</div>
            @endif

            <div class="intro">
                <h2>Start Your Custom Design Journey</h2>
                <p>Transform any product into a personalized masterpiece. Add text, stickers, images, and more to create something truly unique!</p>
                
                <div class="alert alert-info">
                    💡 <strong>Tip:</strong> All products can be customized! Base customization fee is ₱50.00 plus any additional options.
                </div>
            </div>

            <h2 class="section-title">🎁 Choose a Product to Customize</h2>
            
            @if($products->count() > 0)
                <div class="products-grid">
                    @foreach($products as $product)
                        <div class="product-card">
                            <img src="{{ asset('uploads/' . $product->image) }}" 
                                 alt="{{ $product->name }}"
                                 class="product-image"
                                 onerror="this.src='{{ asset('images/placeholder.jpg') }}'">
                            
                            <div class="product-info">
                                <div class="product-name">{{ $product->name }}</div>
                                <div class="product-price">₱{{ number_format($product->price, 2) }}</div>
                                <div class="product-description">
                                    @if($product->description)
                                        {{ \Illuminate\Support\Str::limit($product->description, 100) }}
                                    @else
                                        <span style="color: #999; font-style: italic;">No description available</span>
                                    @endif
                                </div>
                                
                                <a href="{{ route('customization.create', ['product_id' => $product->id]) }}" 
                                   class="btn btn-primary">
                                    🎨 Customize This
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div style="text-align: center; padding: 40px; color: #666;">
                    <h3>No products available for customization</h3>
                    <p>Please check back later!</p>
                </div>
            @endif

            <div class="custom-design-section">
                <h3>🎨 Create a Completely Custom Design</h3>
                <p>Don't see what you're looking for? Start from scratch and design something completely unique! Our design canvas lets you create exactly what you imagine.</p>
                <a href="{{ route('customization.create') }}" class="btn btn-secondary">
                    🚀 Start From Scratch
                </a>
            </div>

            <div style="margin-top: 40px; text-align: center; color: #666;">
                <p>💬 Need help? <a href="{{ route('livechat.request') }}" style="color: #667eea; text-decoration: none; font-weight: 600;">Chat with our design specialists</a></p>
            </div>
        </div>
    </div>

    <script>
        // Add any interactive features here
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Customization landing page loaded');
        });
    </script>
</body>
</html>