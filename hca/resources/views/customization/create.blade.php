<!-- resources/views/customization/create.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $product ? "Customize {$product->name}" : 'Create Custom Design' }} - Flower Shop</title>
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
            max-width: 1400px;
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
            display: grid;
            grid-template-columns: 1.2fr 0.8fr;
            gap: 30px;
            padding: 40px;
        }

        .canvas-section {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 30px;
            position: relative;
        }

        .canvas-container {
            position: relative;
            width: 100%;
            aspect-ratio: 1;
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        #bouquetCanvas {
            width: 100%;
            height: 100%;
            background: white;
            position: relative;
        }

        .bouquet-background {
            position: absolute;
            width: 100%;
            height: 100%;
            background-image: url('{{ asset('asset/images/bouquet-silhouette.png') }}');
            background-size: 60% 90%;
            background-position: center;
            background-repeat: no-repeat;
            opacity: 0.15;
            pointer-events: none;
            z-index: 0;
        }

        .items-layer {
            position: absolute;
            width: 100%;
            height: 100%;
            z-index: 2;
        }

        .draggable-item {
            position: absolute;
            cursor: move;
            border: 2px solid transparent;
            border-radius: 8px;
            transition: border-color 0.2s;
            z-index: 3;
        }

        .draggable-item:hover {
            border-color: #FF6B9D;
        }

        .draggable-item.selected {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.2);
        }

        .delete-btn {
            position: absolute;
            top: -10px;
            right: -10px;
            background: #ff4757;
            color: white;
            border: none;
            border-radius: 50%;
            width: 25px;
            height: 25px;
            cursor: pointer;
            font-size: 14px;
            display: none;
            z-index: 10;
        }

        .draggable-item.selected .delete-btn {
            display: block;
        }

        .fill-indicator {
            position: absolute;
            width: 70px;
            height: 70px;
            border: 2px dashed rgba(255, 107, 157, 0.4);
            border-radius: 50%;
            background: rgba(255, 107, 157, 0.08);
            pointer-events: none;
            z-index: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            color: rgba(255, 107, 157, 0.5);
            font-size: 24px;
            transform: translate(-50%, -50%);
        }

        .fill-indicator.filled {
            display: none;
        }

        .controls-section {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .control-panel {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 25px;
        }

        .control-panel h3 {
            color: #FF6B9D;
            margin-bottom: 15px;
            font-size: 1.3em;
        }

        .color-picker-group {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            margin-bottom: 15px;
        }

        .color-option {
            width: 100%;
            aspect-ratio: 1;
            border-radius: 10px;
            cursor: pointer;
            border: 3px solid transparent;
            transition: all 0.3s ease;
        }

        .color-option:hover {
            transform: scale(1.1);
        }

        .color-option.selected {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.2);
        }

        .upload-section {
            border: 3px dashed #FF6B9D;
            border-radius: 15px;
            padding: 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .upload-section:hover {
            background: #fff5f8;
            border-color: #ff4757;
        }

        .upload-section input {
            display: none;
        }

        .sticker-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
        }

        .sticker-btn {
            aspect-ratio: 1;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            background: white;
            cursor: pointer;
            font-size: 2em;
            transition: all 0.3s ease;
        }

        .sticker-btn:hover {
            transform: scale(1.1);
            border-color: #FF6B9D;
        }

        .text-controls {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .text-controls input,
        .text-controls select {
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 1em;
        }

        .btn {
            padding: 12px 25px;
            border: none;
            border-radius: 10px;
            font-size: 1em;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: linear-gradient(135deg, #FF6B9D 0%, #C06C84 100%);
            color: white;
            width: 100%;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(255, 107, 157, 0.3);
        }

        .btn-secondary {
            background: #667eea;
            color: white;
            width: 100%;
        }

        .btn-secondary:hover {
            background: #5568d3;
        }

        .price-summary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 25px;
            border-radius: 15px;
        }

        .price-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }

        .price-row:last-child {
            border-bottom: none;
            font-size: 1.3em;
            font-weight: bold;
            margin-top: 10px;
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

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .mode-toggle {
            background: white;
            padding: 15px;
            border-radius: 10px;
            border: 2px solid #667eea;
            text-align: center;
        }

        .mode-toggle label {
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            font-weight: 600;
            color: #667eea;
        }

        .mode-toggle input[type="checkbox"] {
            width: 20px;
            height: 20px;
            cursor: pointer;
        }

        #detectionCanvas {
            display: none;
        }

        .product-selection {
            margin: 20px 0;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 10px;
            border-left: 5px solid #667eea;
        }

        .product-selection h3 {
            color: #667eea;
            margin-bottom: 15px;
            font-size: 1.2em;
        }

        .product-select {
            width: 100%;
            padding: 12px;
            border: 2px solid #667eea;
            border-radius: 8px;
            font-size: 1em;
            background: white;
        }

        .product-info-section {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 20px;
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
    <div class="container">
        <div class="header">
            <h1>✨ {{ $product ? "Design Your Custom {$product->name}" : 'Create Your Custom Design' }} ✨</h1>
            <p>Drag, drop, and customize to create your perfect arrangement</p>
        </div>

        <div class="content">
            <!-- Canvas Section -->
            <div class="canvas-section">
                <h3 style="margin-bottom: 15px; color: #667eea;">🎨 Your Design Canvas</h3>
                
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if($errors->any())
                    <div class="alert alert-error">
                        <ul style="margin-left: 20px;">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Product Selection (if no product was preselected) -->
                @if(!$product)
                <div class="product-selection">
                    <h3>📦 Select Product to Customize</h3>
                    <select id="productSelect" class="product-select">
                        <option value="">-- Choose a Product --</option>
                        @foreach(\App\Models\Product::all() as $prod)
                            <option value="{{ $prod->id }}" data-price="{{ $prod->price }}" data-name="{{ $prod->name }}">
                                {{ $prod->name }} - ₱{{ number_format($prod->price, 2) }}
                            </option>
                        @endforeach
                    </select>
                    <small style="color: #666; display: block; margin-top: 10px;">Select a product to see pricing and start customizing</small>
                </div>
                @endif

                <!-- Product Info Section -->
                <div class="product-info-section" id="productInfoSection" style="{{ !$product ? 'display: none;' : '' }}">
                    <h3 id="productName">{{ $product ? $product->name : '' }}</h3>
                    @if($product && $product->description)
                        <p style="color: #666; margin-bottom: 15px;" id="productDescription">{{ $product->description }}</p>
                    @endif
                </div>

                <div class="canvas-container">
                    <div id="bouquetCanvas">
                        <div class="bouquet-background"></div>
                        <div class="items-layer" id="itemsLayer"></div>
                    </div>
                </div>
                
                <div class="mode-toggle" style="margin-top: 15px;">
                    <label>
                        <input type="checkbox" id="autoFillMode" onchange="toggleAutoFill()"> 
                        <span id="modeLabel">🎯 Enable Auto-Fill Mode</span>
                    </label>
                </div>
                
                <div style="margin-top: 10px; text-align: center; color: #666;">
                    <small id="fillStatus">💡 Manual mode: Drag items anywhere | Auto-fill: Items fill bouquet organically</small>
                </div>
            </div>

            <!-- Controls Section -->
            <div class="controls-section">
                <!-- Background Color -->
                <div class="control-panel">
                    <h3>🎨 Background Color</h3>
                    <div class="color-picker-group">
                        <div class="color-option" style="background: linear-gradient(135deg, #ffd3e3, #ffacc7);" onclick="changeBackground('#ffd3e3')"></div>
                        <div class="color-option" style="background: linear-gradient(135deg, #d4f1f4, #b3e5fc);" onclick="changeBackground('#d4f1f4')"></div>
                        <div class="color-option" style="background: linear-gradient(135deg, #fff9c4, #fff59d);" onclick="changeBackground('#fff9c4')"></div>
                        <div class="color-option" style="background: linear-gradient(135deg, #e1bee7, #ce93d8);" onclick="changeBackground('#e1bee7')"></div>
                        <div class="color-option" style="background: linear-gradient(135deg, #c8e6c9, #a5d6a7);" onclick="changeBackground('#c8e6c9')"></div>
                        <div class="color-option" style="background: linear-gradient(135deg, #ffccbc, #ffab91);" onclick="changeBackground('#ffccbc')"></div>
                        <div class="color-option selected" style="background: linear-gradient(135deg, #f0f4f8, #e8eef4);" onclick="changeBackground('#f0f4f8')"></div>
                        <div class="color-option" style="background: linear-gradient(135deg, #ffe0b2, #ffcc80);" onclick="changeBackground('#ffe0b2')"></div>
                    </div>
                </div>

                <!-- Upload Image -->
                <div class="control-panel">
                    <h3>📷 Add Custom Image</h3>
                    <div class="upload-section" onclick="document.getElementById('imageUpload').click()">
                        <p style="font-size: 2.5em; margin-bottom: 5px;">🖼️</p>
                        <p>Upload Image</p>
                        <input type="file" id="imageUpload" accept="image/*" onchange="addCustomImage(this)">
                    </div>
                </div>

                <!-- Stickers -->
                <div class="control-panel">
                    <h3>✨ Add Stickers</h3>
                    <div class="sticker-grid">
                        <button class="sticker-btn" onclick="addSticker('🌸')">🌸</button>
                        <button class="sticker-btn" onclick="addSticker('🌹')">🌹</button>
                        <button class="sticker-btn" onclick="addSticker('🌺')">🌺</button>
                        <button class="sticker-btn" onclick="addSticker('🌻')">🌻</button>
                        <button class="sticker-btn" onclick="addSticker('🦋')">🦋</button>
                        <button class="sticker-btn" onclick="addSticker('⭐')">⭐</button>
                        <button class="sticker-btn" onclick="addSticker('💝')">💝</button>
                        <button class="sticker-btn" onclick="addSticker('🎀')">🎀</button>
                    </div>
                </div>

                <!-- Add Text -->
                <div class="control-panel">
                    <h3>📝 Add Text</h3>
                    <div class="text-controls">
                        <input type="text" id="textInput" placeholder="Enter your message...">
                        <select id="textColor">
                            <option value="#FF6B9D">Pink</option>
                            <option value="#667eea">Purple</option>
                            <option value="#ff4757">Red</option>
                            <option value="#2ed573">Green</option>
                            <option value="#1e90ff">Blue</option>
                            <option value="#ffa502">Orange</option>
                            <option value="#000000">Black</option>
                        </select>
                        <button class="btn btn-secondary" onclick="addText()">➕ Add Text</button>
                    </div>
                </div>

                <!-- Price Summary -->
                <div class="price-summary">
                    <h3 style="margin-bottom: 15px;">💰 Price Summary</h3>
                    <div class="price-row">
                        <span>Base Price:</span>
                        <span id="basePrice">₱{{ $product ? number_format($product->price, 2) : '0.00' }}</span>
                    </div>
                    <div class="price-row">
                        <span>Customization:</span>
                        <span id="customPrice">₱50.00</span>
                    </div>
                    <div class="price-row">
                        <span>Total:</span>
                        <span id="totalPrice">₱{{ $product ? number_format($product->price + 50, 2) : '50.00' }}</span>
                    </div>
                </div>

                <!-- Action Buttons -->
                <button class="btn btn-secondary" onclick="saveDraft()">💾 Save as Draft</button>
                <button class="btn btn-primary" onclick="saveDesign()">🛒 Add to Cart</button>
                <a href="{{ route('customization.landing') }}" class="btn" style="background: #f8f9fa; color: #333; text-align: center; margin-top: 10px;">
                    ← Back to Products
                </a>
            </div>
        </div>
    </div>

    <!-- Hidden canvas for detection -->
    <canvas id="detectionCanvas"></canvas>

    <!-- Hidden Form -->
    <form id="customizationForm" action="{{ route('customization.store') }}" method="POST" enctype="multipart/form-data" style="display: none;">
        @csrf
        <input type="hidden" name="product_id" id="product_id" value="{{ $product ? $product->id : '' }}">
        <input type="hidden" name="customization_name" id="customization_name">
        <input type="hidden" name="customization_details" id="customization_details">
        <input type="hidden" name="canvas_data" id="canvas_data">
        <input type="hidden" name="status" id="status" value="Pending">
    </form>

    <script>
        let selectedItem = null;
        let dragOffset = { x: 0, y: 0 };
        let isDragging = false;
        let itemCounter = 0;
        let basePrice = {{ $product ? $product->price : 0 }};
        const customizationPrice = 50;
        let autoFillMode = false;
        let fillPositions = [];
        let currentFillIndex = 0;
        let fillIndicators = [];
        let selectedProductId = {{ $product ? $product->id : 'null' }};
        let selectedProductName = '{{ $product ? $product->name : "" }}';
        let selectedProductDescription = '{{ $product ? addslashes($product->description) : "" }}';

        // Initialize product selection if no product was preselected
        document.addEventListener('DOMContentLoaded', function() {
            if (!selectedProductId) {
                // Show product selection
                document.getElementById('productInfoSection').style.display = 'none';
                
                // Add event listener for product selection
                const productSelect = document.getElementById('productSelect');
                if (productSelect) {
                    productSelect.addEventListener('change', function(e) {
                        const selectedOption = this.options[this.selectedIndex];
                        if (selectedOption.value) {
                            selectedProductId = this.value;
                            basePrice = parseFloat(selectedOption.dataset.price);
                            selectedProductName = selectedOption.dataset.name;
                            
                            // Update the hidden field
                            document.getElementById('product_id').value = selectedProductId;
                            
                            // Update the product info section
                            const productInfoSection = document.getElementById('productInfoSection');
                            productInfoSection.style.display = 'block';
                            document.getElementById('productName').textContent = selectedProductName;
                            
                            // Update prices
                            document.getElementById('basePrice').textContent = '₱' + basePrice.toFixed(2);
                            updateTotalPrice();
                        } else {
                            // No product selected
                            document.getElementById('productInfoSection').style.display = 'none';
                            selectedProductId = null;
                            basePrice = 0;
                            document.getElementById('product_id').value = '';
                            document.getElementById('basePrice').textContent = '₱0.00';
                            updateTotalPrice();
                        }
                    });
                }
            } else {
                // Product was preselected, update total price
                updateTotalPrice();
            }
            
            detectBouquetShape();
        });

        function updateTotalPrice() {
            const totalPriceElement = document.getElementById('totalPrice');
            const total = basePrice + customizationPrice;
            totalPriceElement.textContent = '₱' + total.toFixed(2);
        }

        function detectBouquetShape() {
            const img = new Image();
            img.crossOrigin = "anonymous";
            img.src = '{{ asset('images/bouquet-silhouette.png') }}';
            
            img.onload = function() {
                const canvas = document.getElementById('detectionCanvas');
                const ctx = canvas.getContext('2d');
                
                canvas.width = 200;
                canvas.height = 300;
                
                ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
                
                const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
                const data = imageData.data;
                
                // Scan for dark pixels (silhouette)
                const positions = [];
                const step = 25; // Sample every 25 pixels
                
                for (let y = canvas.height - step; y >= step; y -= step) {
                    for (let x = step; x < canvas.width - step; x += step) {
                        const index = (y * canvas.width + x) * 4;
                        const r = data[index];
                        const g = data[index + 1];
                        const b = data[index + 2];
                        const a = data[index + 3];
                        
                        // Check if pixel is dark (part of silhouette)
                        const brightness = (r + g + b) / 3;
                        if (brightness < 200 && a > 100) {
                            positions.push({
                                x: (x / canvas.width) * 100,
                                y: (y / canvas.height) * 100
                            });
                        }
                    }
                }
                
                fillPositions = positions;
                console.log(`Detected ${fillPositions.length} fill positions`);
                
                if (autoFillMode) {
                    showFillIndicators();
                }
            };
            
            img.onerror = function() {
                console.warn('Could not load silhouette image, using default positions');
                generateDefaultPositions();
            };
        }

        function generateDefaultPositions() {
            // Fallback positions if image can't be loaded
            fillPositions = [];
            for (let y = 85; y >= 10; y -= 8) {
                const width = Math.sin((y / 100) * Math.PI) * 40 + 10;
                for (let x = 50 - width; x <= 50 + width; x += 12) {
                    fillPositions.push({ x, y });
                }
            }
            console.log(`Generated ${fillPositions.length} default positions`);
        }

        function showFillIndicators() {
            clearFillIndicators();
            const container = document.getElementById('itemsLayer');
            const containerRect = container.getBoundingClientRect();
            
            fillPositions.forEach((pos, index) => {
                if (index >= currentFillIndex) {
                    const indicator = document.createElement('div');
                    indicator.className = 'fill-indicator';
                    indicator.style.left = `${pos.x}%`;
                    indicator.style.top = `${pos.y}%`;
                    indicator.innerHTML = '◯';
                    indicator.dataset.index = index;
                    container.appendChild(indicator);
                    fillIndicators.push(indicator);
                }
            });
        }

        function clearFillIndicators() {
            fillIndicators.forEach(ind => ind.remove());
            fillIndicators = [];
        }

        function toggleAutoFill() {
            autoFillMode = document.getElementById('autoFillMode').checked;
            const status = document.getElementById('fillStatus');
            const label = document.getElementById('modeLabel');
            
            if (autoFillMode) {
                status.innerHTML = '✨ <strong>Auto-Fill Active:</strong> Items will fill the bouquet from bottom to top organically';
                status.style.color = '#667eea';
                status.style.fontWeight = '600';
                label.textContent = '✅ Auto-Fill Mode Active';
                showFillIndicators();
            } else {
                status.innerHTML = '💡 Manual mode: Drag items anywhere | Auto-fill: Items fill bouquet organically';
                status.style.color = '#666';
                status.style.fontWeight = 'normal';
                label.textContent = '🎯 Enable Auto-Fill Mode';
                clearFillIndicators();
            }
        }

        function getNextFillPosition() {
            if (currentFillIndex < fillPositions.length) {
                return fillPositions[currentFillIndex];
            }
            return null;
        }

        function positionItemAtNextSpot(item) {
            const pos = getNextFillPosition();
            if (!pos) {
                alert('Bouquet is full! Try removing some items or switch to manual mode.');
                return false;
            }
            
            const container = document.getElementById('itemsLayer');
            const containerRect = container.getBoundingClientRect();
            
            const x = (pos.x / 100) * containerRect.width - item.offsetWidth / 2;
            const y = (pos.y / 100) * containerRect.height - item.offsetHeight / 2;
            
            item.style.left = x + 'px';
            item.style.top = y + 'px';
            item.style.transform = 'none';
            item.dataset.fillIndex = currentFillIndex;
            
            // Hide the indicator
            const indicator = fillIndicators.find(ind => parseInt(ind.dataset.index) === currentFillIndex);
            if (indicator) {
                indicator.classList.add('filled');
            }
            
            currentFillIndex++;
            return true;
        }

        function changeBackground(color) {
            const canvas = document.getElementById('bouquetCanvas');
            canvas.style.background = `linear-gradient(135deg, ${color}, ${adjustColor(color, -20)})`;
            
            document.querySelectorAll('.color-option').forEach(opt => opt.classList.remove('selected'));
            event.target.classList.add('selected');
        }

        function adjustColor(color, amount) {
            const num = parseInt(color.replace("#", ""), 16);
            const r = Math.max(0, Math.min(255, (num >> 16) + amount));
            const g = Math.max(0, Math.min(255, ((num >> 8) & 0x00FF) + amount));
            const b = Math.max(0, Math.min(255, (num & 0x0000FF) + amount));
            return "#" + (r << 16 | g << 8 | b).toString(16).padStart(6, '0');
        }

        function addSticker(emoji) {
            const container = document.getElementById('itemsLayer');
            const item = document.createElement('div');
            item.className = 'draggable-item';
            item.innerHTML = `
                <div style="font-size: 50px; user-select: none;">${emoji}</div>
                <button class="delete-btn" onclick="deleteItem(this)">×</button>
            `;
            item.dataset.id = itemCounter++;
            
            container.appendChild(item);
            
            if (autoFillMode) {
                if (!positionItemAtNextSpot(item)) {
                    item.remove();
                    return;
                }
            } else {
                item.style.left = '50%';
                item.style.top = '50%';
                item.style.transform = 'translate(-50%, -50%)';
            }
            
            makeItemDraggable(item);
        }

        function addText() {
            const textInput = document.getElementById('textInput');
            const textColor = document.getElementById('textColor');
            const text = textInput.value.trim();
            
            if (!text) {
                alert('Please enter some text!');
                return;
            }

            const container = document.getElementById('itemsLayer');
            const item = document.createElement('div');
            item.className = 'draggable-item';
            item.innerHTML = `
                <div style="font-size: 20px; font-weight: bold; color: ${textColor.value}; 
                            padding: 8px; background: rgba(255,255,255,0.9); 
                            border-radius: 8px; user-select: none; white-space: nowrap;">${text}</div>
                <button class="delete-btn" onclick="deleteItem(this)">×</button>
            `;
            item.dataset.id = itemCounter++;
            
            container.appendChild(item);
            
            if (autoFillMode) {
                if (!positionItemAtNextSpot(item)) {
                    item.remove();
                    return;
                }
            } else {
                item.style.left = '50%';
                item.style.top = '50%';
                item.style.transform = 'translate(-50%, -50%)';
            }
            
            makeItemDraggable(item);
            textInput.value = '';
        }

        function addCustomImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const container = document.getElementById('itemsLayer');
                    const item = document.createElement('div');
                    item.className = 'draggable-item';
                    item.innerHTML = `
                        <img src="${e.target.result}" style="width: 100px; height: 100px; 
                                object-fit: cover; border-radius: 10px; user-select: none;">
                        <button class="delete-btn" onclick="deleteItem(this)">×</button>
                    `;
                    item.dataset.id = itemCounter++;
                    
                    container.appendChild(item);
                    
                    if (autoFillMode) {
                        if (!positionItemAtNextSpot(item)) {
                            item.remove();
                            return;
                        }
                    } else {
                        item.style.left = '50%';
                        item.style.top = '50%';
                        item.style.transform = 'translate(-50%, -50%)';
                    }
                    
                    makeItemDraggable(item);
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        function makeItemDraggable(item) {
            item.addEventListener('mousedown', startDrag);
            item.addEventListener('touchstart', startDrag);
        }

        function startDrag(e) {
            e.preventDefault();
            selectedItem = this;
            
            document.querySelectorAll('.draggable-item').forEach(i => i.classList.remove('selected'));
            selectedItem.classList.add('selected');
            
            isDragging = true;
            
            const rect = selectedItem.getBoundingClientRect();
            const clientX = e.type === 'touchstart' ? e.touches[0].clientX : e.clientX;
            const clientY = e.type === 'touchstart' ? e.touches[0].clientY : e.clientY;
            
            dragOffset.x = clientX - rect.left;
            dragOffset.y = clientY - rect.top;
            
            document.addEventListener('mousemove', drag);
            document.addEventListener('mouseup', stopDrag);
            document.addEventListener('touchmove', drag);
            document.addEventListener('touchend', stopDrag);
        }

        function drag(e) {
            if (!isDragging || !selectedItem) return;
            e.preventDefault();
            
            const container = document.getElementById('itemsLayer').getBoundingClientRect();
            const clientX = e.type === 'touchmove' ? e.touches[0].clientX : e.clientX;
            const clientY = e.type === 'touchmove' ? e.touches[0].clientY : e.clientY;
            
            let x = clientX - container.left - dragOffset.x;
            let y = clientY - container.top - dragOffset.y;
            
            x = Math.max(0, Math.min(x, container.width - selectedItem.offsetWidth));
            y = Math.max(0, Math.min(y, container.height - selectedItem.offsetHeight));
            
            selectedItem.style.left = x + 'px';
            selectedItem.style.top = y + 'px';
            selectedItem.style.transform = 'none';
        }

        function stopDrag() {
            isDragging = false;
            document.removeEventListener('mousemove', drag);
            document.removeEventListener('mouseup', stopDrag);
            document.removeEventListener('touchmove', drag);
            document.removeEventListener('touchend', stopDrag);
        }

        function deleteItem(btn) {
            const item = btn.closest('.draggable-item');
            
            // If item was auto-filled, adjust the counter
            if (item.dataset.fillIndex !== undefined) {
                const fillIndex = parseInt(item.dataset.fillIndex);
                // Reset counter if this was the last item
                if (fillIndex === currentFillIndex - 1) {
                    currentFillIndex--;
                    if (autoFillMode) {
                        showFillIndicators();
                    }
                }
            }
            
            item.remove();
        }

        function collectCanvasData() {
            const items = document.querySelectorAll('.draggable-item');
            const canvas = document.getElementById('bouquetCanvas');
            const container = document.getElementById('itemsLayer');
            const containerRect = container.getBoundingClientRect();
            
            const elements = [];
            items.forEach((item, index) => {
                const rect = item.getBoundingClientRect();
                const img = item.querySelector('img');
                const text = item.querySelector('div[style*="font-size"]');
                
                elements.push({
                    type: img ? 'image' : (text ? 'text' : 'sticker'),
                    content: img ? img.src : (text ? text.textContent : item.querySelector('div').textContent),
                    x: rect.left - containerRect.left,
                    y: rect.top - containerRect.top,
                    width: rect.width,
                    height: rect.height
                });
            });
            
            return {
                background: canvas.style.background,
                elements: elements,
                timestamp: new Date().toISOString()
            };
        }

        function saveDesign() {
            // Check if product is selected
            if (!selectedProductId) {
                alert('Please select a product first!');
                return;
            }
            
            const items = document.querySelectorAll('.draggable-item');
            if (items.length === 0) {
                alert('Please add some customizations to your design first!');
                return;
            }
            
            const canvasData = collectCanvasData();
            
            document.getElementById('customization_name').value = selectedProductName + ' Custom Design';
            document.getElementById('customization_details').value = `Custom design with ${items.length} elements`;
            document.getElementById('canvas_data').value = JSON.stringify(canvasData);
            document.getElementById('status').value = 'Pending';
            
            document.getElementById('customizationForm').submit();
        }

        function saveDraft() {
            // Check if product is selected
            if (!selectedProductId) {
                alert('Please select a product first!');
                return;
            }
            
            const items = document.querySelectorAll('.draggable-item');
            if (items.length === 0) {
                alert('Please add some customizations first!');
                return;
            }
            
            const canvasData = collectCanvasData();
            
            document.getElementById('customization_name').value = 'Draft - ' + selectedProductName;
            document.getElementById('customization_details').value = `Draft design with ${items.length} elements`;
            document.getElementById('canvas_data').value = JSON.stringify(canvasData);
            document.getElementById('status').value = 'Draft';
            
            document.getElementById('customizationForm').submit();
        }

        document.getElementById('bouquetCanvas').addEventListener('click', function(e) {
            if (e.target === this || e.target.classList.contains('bouquet-background') || e.target.classList.contains('items-layer')) {
                document.querySelectorAll('.draggable-item').forEach(i => i.classList.remove('selected'));
            }
        });
    </script>
</body>
</html>