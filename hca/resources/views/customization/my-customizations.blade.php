<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Customizations - Flower Shop</title>
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
        }

        .header h1 {
            font-size: 2.5em;
            margin-bottom: 10px;
        }

        .content {
            padding: 40px;
        }

        .alert {
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .customization-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }

        .customization-card {
            background: #f8f9fa;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .customization-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        .card-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .card-body {
            padding: 20px;
        }

        .card-title {
            font-size: 1.2em;
            color: #333;
            margin-bottom: 10px;
            font-weight: 600;
        }

        .card-details {
            color: #666;
            font-size: 0.9em;
            margin-bottom: 10px;
        }

        .status-badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.85em;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .status-draft { background: #ffd93d; color: #333; }
        .status-pending { background: #6bcf7f; color: white; }
        .status-in-progress { background: #4834df; color: white; }
        .status-completed { background: #26de81; color: white; }
        .status-cancelled { background: #fc5c65; color: white; }

        .price {
            font-size: 1.3em;
            color: #FF6B9D;
            font-weight: bold;
            margin: 10px 0;
        }

        .card-actions {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            font-size: 0.9em;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }

        .btn-primary {
            background: linear-gradient(135deg, #FF6B9D 0%, #C06C84 100%);
            color: white;
            flex: 1;
        }

        .btn-secondary {
            background: #667eea;
            color: white;
            flex: 1;
        }

        .btn:hover {
            transform: translateY(-2px);
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
        }

        .empty-state h3 {
            color: #555;
            margin-bottom: 20px;
        }

        .empty-state p {
            color: #777;
            margin-bottom: 30px;
        }

        .btn-large {
            padding: 15px 40px;
            font-size: 1.1em;
        }

        @media (max-width: 768px) {
            .customization-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>✨ My Custom Designs</h1>
            <p>View and manage all your personalized creations</p>
        </div>

        <div class="content">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if($customizations->count() > 0)
                <div class="customization-grid">
                    @foreach($customizations as $custom)
                        <div class="customization-card">
                            <img src="{{ $custom->custom_image 
                                ? asset('uploads/customizations/' . $custom->custom_image) 
                                : asset('uploads/' . $custom->product->image) }}" 
                                 alt="{{ $custom->customization_name }}"
                                 class="card-image">
                            
                            <div class="card-body">
                                <div class="card-title">{{ $custom->customization_name }}</div>
                                
                                <span class="status-badge status-{{ strtolower($custom->status) }}">
                                    {{ $custom->status }}
                                </span>

                                <div class="card-details">
                                    <strong>Product:</strong> {{ $custom->product->name }}
                                </div>

                                <div class="card-details">
                                    <strong>Created:</strong> {{ $custom->created_at->format('M d, Y') }}
                                </div>

                                @if($custom->order_id)
                                    <div class="card-details">
                                        <strong>Order:</strong> #{{ $custom->order_id }}
                                    </div>
                                @endif

                                <div class="price">
                                    ₱{{ number_format($custom->product->price + $custom->total_price, 2) }}
                                </div>

                                <div class="card-actions">
                                    <a href="{{ route('customization.show', $custom->id) }}" 
                                       class="btn btn-primary">
                                        👁️ View
                                    </a>
                                    
                                    @if(!$custom->order_id)
                                        <a href="{{ route('customization.edit', $custom->id) }}" 
                                           class="btn btn-secondary">
                                            ✏️ Edit
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <h3>🎨 No Customizations Yet</h3>
                    <p>Start creating your personalized flower arrangements!</p>
                    <!-- CHANGED: From Browse Products to Start Customizing -->
                    <a href="{{ route('customization.landing') }}" class="btn btn-primary btn-large">
                        Start Customizing
                    </a>
                </div>
            @endif
        </div>
    </div>
</body>
</html>