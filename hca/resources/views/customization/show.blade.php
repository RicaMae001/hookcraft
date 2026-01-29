<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customization Details - {{ $customization->customization_name }}</title>
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
            font-size: 2em;
            margin-bottom: 10px;
        }

        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.9em;
            font-weight: 600;
            margin-top: 10px;
        }

        .status-draft { background: #ffd93d; color: #333; }
        .status-pending { background: #6bcf7f; color: white; }
        .status-in-progress { background: #4834df; color: white; }
        .status-completed { background: #26de81; color: white; }
        .status-cancelled { background: #fc5c65; color: white; }

        .content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            padding: 40px;
        }

        .section {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 15px;
        }

        .section h3 {
            color: #FF6B9D;
            margin-bottom: 15px;
            font-size: 1.3em;
        }

        .canvas-preview {
            width: 100%;
            aspect-ratio: 1;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            position: relative;
        }

        .canvas-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #e0e0e0;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            font-weight: 600;
            color: #555;
        }

        .info-value {
            color: #333;
        }

        .options-list {
            list-style: none;
        }

        .options-list li {
            padding: 10px;
            background: white;
            margin-bottom: 8px;
            border-radius: 8px;
            display: flex;
            justify-content: space-between;
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
            margin-right: 10px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #FF6B9D 0%, #C06C84 100%);
            color: white;
        }

        .btn-secondary {
            background: #667eea;
            color: white;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }

        .action-buttons {
            margin-top: 20px;
        }

        @media (max-width: 968px) {
            .content {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    {{-- Include Navigation Bar --}}
@include('components.customization-navbar')
    <div class="container">
        <div class="header">
            <h1>{{ $customization->customization_name }}</h1>
            <span class="status-badge status-{{ strtolower($customization->status) }}">
                {{ $customization->status }}
            </span>
        </div>

        <div class="content">
            <!-- Canvas Preview -->
            <div>
                <div class="section">
                    <h3>🎨 Design Preview</h3>
                    <div class="canvas-preview">
                        @if($customization->custom_image)
                            <img src="{{ asset('uploads/customizations/' . $customization->custom_image) }}" 
                                 alt="Customization Preview">
                        @else
                            <img src="{{ asset('uploads/' . $customization->product->image) }}" 
                                 alt="{{ $customization->product->name }}">
                        @endif
                    </div>
                    
                    <div class="info-row">
                        <span class="info-label">Product:</span>
                        <span class="info-value">{{ $customization->product->name }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Base Price:</span>
                        <span class="info-value">₱{{ number_format($customization->product->price, 2) }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Customization Fee:</span>
                        <span class="info-value">₱{{ number_format($customization->total_price, 2) }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Total Price:</span>
                        <span class="info-value" style="font-size: 1.2em; color: #FF6B9D; font-weight: bold;">
                            ₱{{ number_format($customization->product->price + $customization->total_price, 2) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Details -->
            <div>
                <div class="section">
                    <h3>📝 Customization Details</h3>
                    <div class="info-row">
                        <span class="info-label">Created:</span>
                        <span class="info-value">{{ $customization->created_at->format('M d, Y h:i A') }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Last Updated:</span>
                        <span class="info-value">{{ $customization->updated_at->format('M d, Y h:i A') }}</span>
                    </div>
                    
                    @if($customization->order_id)
                    <div class="info-row">
                        <span class="info-label">Order ID:</span>
                        <span class="info-value">#{{ $customization->order_id }}</span>
                    </div>
                    @endif

                    <div style="margin-top: 20px;">
                        <p style="font-weight: 600; color: #555; margin-bottom: 10px;">Description:</p>
                        <p style="color: #333; line-height: 1.6;">{{ $customization->customization_details }}</p>
                    </div>

                    @if($customization->special_instructions)
                    <div style="margin-top: 20px;">
                        <p style="font-weight: 600; color: #555; margin-bottom: 10px;">Special Instructions:</p>
                        <p style="color: #333; line-height: 1.6;">{{ $customization->special_instructions }}</p>
                    </div>
                    @endif
                </div>

                @if($customization->options->count() > 0)
                <div class="section" style="margin-top: 20px;">
                    <h3>✨ Customization Options</h3>
                    <ul class="options-list">
                        @foreach($customization->options as $option)
                            @if($option->option_type !== 'canvas_design')
                            <li>
                                <span>
                                    <strong>{{ ucfirst(str_replace('_', ' ', $option->option_type)) }}:</strong>
                                    {{ $option->option_value }}
                                </span>
                                <span style="color: #667eea; font-weight: 600;">
                                    +₱{{ number_format($option->additional_price, 2) }}
                                </span>
                            </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
                @endif

                <div class="action-buttons">
                    @if(!$customization->order_id)
                        <a href="{{ route('customization.edit', $customization->id) }}" class="btn btn-primary" >
                            ✏️ Edit Design
                        </a>
                        <form action="{{ route('customization.destroy', $customization->id) }}" 
                              method="POST" style="display: inline;"
                              onsubmit="return confirm('Are you sure you want to delete this customization?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-secondary" 
                                    style="background: #fc5c65;">
                                🗑️ Delete
                            </button>
                        </form>
                    @endif
                    
                    <a href="{{ route('customization.my-customizations') }}" class="btn btn-secondary" >
                        ← Back to My Customizations
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>