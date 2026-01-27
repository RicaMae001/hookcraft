{{-- resources/views/components/pmodal.blade.php --}}
<link rel="stylesheet" href="{{ asset('asset/stylesshop.css') }}">

<!-- Product Modal -->
<div class="modal fade product-modal" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded-4 shadow-lg border-0">
            <!-- Modal Header -->
            <div class="modal-header bg-gradient-pink text-white rounded-top-4 border-0 py-4 position-relative">
                <h5 class="modal-title fw-bold" id="productModalLabel">
                    <i class="fas fa-gem me-2"></i>Product Details
                </h5>
                <!-- Visible X Button -->
                <button type="button" class="btn-close btn-close-pink bg-white shadow-sm" 
                        data-bs-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body p-0">
                <div class="row g-0">
                    <!-- Product Image Section -->
                    <div class="col-md-6 position-relative">
                        <div class="product-image-container p-4">
                            <img id="modalProductImage" src="" alt="" class="img-fluid rounded-3 shadow-sm product-modal-image">
                            <!-- Stock Badge Overlay -->
                            <div class="stock-badge position-absolute top-0 end-0 m-4">
                                <span id="modalProductStock" class="badge bg-pink-soft shadow-lg">
                                    <i class="fas fa-heart me-1"></i>
                                    <span id="stockCount">0</span> available
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Product Details Section -->
                    <div class="col-md-6 bg-pink-light rounded-end-4">
                        <div class="p-4 h-100 d-flex flex-column">
                            <!-- Product Title -->
                            <h3 id="modalProductName" class="fw-bold mb-2 text-pink-dark"></h3>
                            
                            <!-- Category Tag -->
                            <div class="mb-3">
                                <span class="badge bg-pink bg-opacity-20 text-white fw-medium px-3 py-2 rounded-pill" id="modalProductCategory"></span>
                            </div>

                            <!-- Price Display -->
                            <div class="product-price-display mb-4">
                                <div class="d-flex align-items-center">
                                    <span class="h2 fw-bold text-gradient-pink mb-0" id="modalProductPrice"></span>
                                    <small class="text-pink-muted ms-2">per item</small>
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="description-section mb-4">
                                <h6 class="fw-semibold mb-2 text-pink-dark">
                                    <i class="fas fa-align-left me-2 text-pink"></i>About This Item
                                </h6>
                                <p id="modalProductDescription" class="text-pink-muted mb-0 lh-base"></p>
                            </div>

                            <!-- Quantity Selector -->
                            <div class="quantity-section mb-4">
                                <label class="form-label fw-semibold text-pink-dark mb-2">
                                    <i class="fas fa-hashtag me-2 text-pink"></i>Select Quantity
                                </label>
                                <div class="d-flex align-items-center">
                                    <div class="quantity-controls d-flex align-items-center border rounded-3 bg-white shadow-sm border-pink-soft">
                                        <button type="button" class="btn quantity-btn px-3 py-2 border-0" id="modalDecreaseBtn">
                                            <i class="fas fa-minus text-pink"></i>
                                        </button>
                                        <input type="number" class="form-control quantity-input border-0 text-center fw-bold text-pink-dark" 
                                               id="modalQuantity" value="1" min="1" readonly>
                                        <button type="button" class="btn quantity-btn px-3 py-2 border-0" id="modalIncreaseBtn">
                                            <i class="fas fa-plus text-pink"></i>
                                        </button>
                                    </div>
                                   
                                </div>
                            </div>

                            <!-- Add-ons Section -->
                            <div class="add-ons-section d-none" id="addOnsSection">
                                <h6 class="fw-semibold mb-3 text-pink-dark">
                                    <i class="fas fa-sparkles me-2 text-pink"></i>Customize Your Order
                                </h6>
                                <div class="add-ons-list" id="addOnsList">
                                    <!-- Add-ons will be populated dynamically -->
                                </div>
                                <small class="text-pink-muted mt-2 d-block">
                                    <i class="fas fa-heart me-1"></i>Optional add-ons for personalization
                                </small>
                            </div>

                            <!-- Total Price -->
                            <div class="total-price-section mt-auto pt-4 border-top border-pink-soft">
                                <div class="d-flex justify-content-between align-items-center p-3 bg-white rounded-3 shadow-sm border border-pink-soft">
                                    <div>
                                        <strong class="text-pink-dark">Total Amount</strong>
                                        <small class="text-pink-muted d-block">Including all selections</small>
                                    </div>
                                    <strong class="text-pink fs-3 fw-bold" id="modalTotalPrice">₱0.00</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer border-0 bg-pink-light rounded-bottom-4 py-3">
                <div class="d-flex gap-3 w-100">
                    <button type="button" class="btn btn-lg btn-outline-pink flex-fill rounded-3 py-3" 
                            id="modalAddToCart">
                        <i class="fas fa-shopping-bag me-2"></i>Add to Bag
                    </button>
                    <button type="button" class="btn btn-lg btn-gradient-pink flex-fill rounded-3 py-3 shadow-sm" 
                            id="modalBuyNow">
                        <i class="fas fa-bolt me-2"></i>Buy Now
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Pink Color Palette */
    :root {
        --pink-primary: #ec4899;
        --pink-dark: #be185d;
        --pink-light: #fdf2f8;
        --pink-soft: #fce7f3;
        --pink-muted: #9d174d;
        --pink-gradient: linear-gradient(135deg, #f472b6 0%, #ec4899 100%);
        --pink-gradient-dark: linear-gradient(135deg, #db2777 0%, #be185d 100%);
    }

    /* Custom X Close Button */
    .btn-close-pink {
        width: 20px;
        height: 20px;
        border-radius: 50%;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 1;
        position: relative;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 2px solid white;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        background: white !important;
    }

    .btn-close-pink i {
        color: #ec4899;
        font-size: 1.2rem;
        font-weight: bold;
        transition: transform 0.3s ease;
    }

    .btn-close-pink:hover {
        background: linear-gradient(135deg, #f472b6 0%, #ec4899 100%) !important;
        transform: translateY(-2px) rotate(90deg);
        box-shadow: 0 6px 20px rgba(236, 72, 153, 0.4);
    }

    .btn-close-pink:hover i {
        color: white;
        transform: scale(1.1);
    }

    .btn-close-pink:active {
        transform: translateY(0) rotate(90deg);
        box-shadow: 0 3px 10px rgba(236, 72, 153, 0.3);
    }

    /* Alternative Close Button Style (Circle with X) */
    .close-button-wrapper {
        position: absolute;
        top: -15px;
        right: -15px;
        z-index: 1000;
    }

    .close-button-circle {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        background: white;
        border: 2px solid #ec4899;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #ec4899;
        font-size: 1.3rem;
        font-weight: bold;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(236, 72, 153, 0.2);
    }

    .close-button-circle:hover {
        background: #ec4899;
        color: white;
        transform: scale(1.1) rotate(90deg);
        box-shadow: 0 6px 25px rgba(236, 72, 153, 0.3);
    }

    /* Pink Backgrounds */
    .bg-gradient-pink {
        background: linear-gradient(135deg, #f472b6 0%, #ec4899 100%);
    }

    .bg-pink-light {
        background-color: #fdf2f8;
    }

    .bg-pink-soft {
        background-color: #fce7f3;
    }

    .bg-pink {
        background-color: #ec4899 !important;
    }

    /* Pink Text Colors */
    .text-pink {
        color: #ec4899 !important;
    }

    .text-pink-dark {
        color: #be185d !important;
    }

    .text-pink-muted {
        color: #9d174d !important;
    }

    .text-gradient-pink {
        background: linear-gradient(135deg, #f472b6 0%, #ec4899 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    /* Pink Buttons */
    .btn-gradient-pink {
        background: linear-gradient(135deg, #f472b6 0%, #ec4899 100%);
        border: none;
        color: white;
        transition: all 0.3s ease;
    }

    .btn-gradient-pink:hover {
        background: linear-gradient(135deg, #db2777 0%, #be185d 100%);
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(236, 72, 153, 0.3);
    }

    .btn-outline-pink {
        border: 2px solid #ec4899;
        color: #ec4899;
        background: white;
        transition: all 0.3s ease;
    }

    .btn-outline-pink:hover {
        background: linear-gradient(135deg, #f472b6 0%, #ec4899 100%);
        color: white;
        border-color: transparent;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(236, 72, 153, 0.2);
    }

    /* Pink Borders */
    .border-pink {
        border-color: #ec4899 !important;
    }

    .border-pink-soft {
        border-color: #fce7f3 !important;
    }

    /* Modal Content Styling */
    .modal-content {
        border: none;
        overflow: hidden;
        box-shadow: 0 20px 40px rgba(236, 72, 153, 0.15);
        position: relative;
    }

    /* Product Image Container */
    .product-image-container {
        background: linear-gradient(135deg, #fdf2f8 0%, #fce7f3 100%);
        min-height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }

    .product-modal-image {
        max-height: 400px;
        object-fit: contain;
        transition: transform 0.3s ease;
        border: 2px solid #fce7f3;
    }

    .product-modal-image:hover {
        transform: scale(1.05);
    }

    /* Stock Badge */
    .stock-badge .badge {
        font-size: 0.85rem;
        padding: 8px 16px;
        backdrop-filter: blur(10px);
        background: rgba(236, 72, 153, 0.9) !important;
        color: white;
    }

    /* Quantity Controls */
    .quantity-controls {
        width: 140px;
        border-color: #fce7f3 !important;
    }

    .quantity-btn {
        background: white;
        transition: all 0.2s ease;
    }

    .quantity-btn:hover {
        background: #fdf2f8;
    }

    .quantity-btn:active {
        transform: scale(0.95);
    }

    .quantity-input {
        font-size: 1.2rem;
        width: 60px;
        background: transparent;
    }

    .quantity-input:focus {
        box-shadow: none;
        border-color: #fce7f3;
    }

    /* Add-ons Styling */
    .add-ons-list {
        max-height: 200px;
        overflow-y: auto;
        padding-right: 5px;
    }

    .add-ons-list::-webkit-scrollbar {
        width: 6px;
    }

    .add-ons-list::-webkit-scrollbar-track {
        background: #fdf2f8;
        border-radius: 10px;
    }

    .add-ons-list::-webkit-scrollbar-thumb {
        background: #f472b6;
        border-radius: 10px;
    }

    .add-on-item {
        padding: 14px 18px;
        margin-bottom: 10px;
        border: 2px solid #fce7f3;
        border-radius: 14px;
        background: white;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }

    .add-on-item:hover {
        border-color: #f472b6;
        background: linear-gradient(135deg, rgba(244, 114, 182, 0.05) 0%, rgba(236, 72, 153, 0.05) 100%);
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(236, 72, 153, 0.1);
    }

    .add-on-item.selected {
        border-color: #ec4899;
        background: linear-gradient(135deg, rgba(244, 114, 182, 0.1) 0%, rgba(236, 72, 153, 0.1) 100%);
        animation: pinkSelectPulse 0.6s ease;
    }

    .add-on-item.selected::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, #f472b6 0%, #ec4899 100%);
    }

    .add-on-item .fa-check-circle {
        color: #ec4899;
        font-size: 1.3em;
        opacity: 0;
        transform: scale(0);
        transition: all 0.3s ease;
    }

    .add-on-item.selected .fa-check-circle {
        opacity: 1;
        transform: scale(1);
    }

    @keyframes pinkSelectPulse {
        0% { 
            transform: scale(1);
            box-shadow: 0 0 0 0 rgba(236, 72, 153, 0.7);
        }
        70% { 
            transform: scale(1.02);
            box-shadow: 0 0 0 10px rgba(236, 72, 153, 0);
        }
        100% { 
            transform: scale(1);
            box-shadow: 0 0 0 0 rgba(236, 72, 153, 0);
        }
    }

    /* Description Section */
    .description-section {
        background: linear-gradient(135deg, #fdf2f8 0%, #fce7f3 100%);
        padding: 18px;
        border-radius: 14px;
        border-left: 5px solid #ec4899;
    }

    /* Total Price Section */
    .total-price-section {
        animation: pinkSlideUp 0.5s ease;
    }

    @keyframes pinkSlideUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Modal Animations */
    .modal.fade .modal-content {
        transform: translateY(-50px);
        opacity: 0;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .modal.show .modal-content {
        transform: translateY(0);
        opacity: 1;
    }

    /* Floating Hearts Animation */
    .hearts-animation {
        position: absolute;
        width: 100%;
        height: 100%;
        pointer-events: none;
        z-index: 1;
    }

    .heart {
        position: absolute;
        color: rgba(236, 72, 153, 0.3);
        font-size: 1.5rem;
        animation: floatHeart 3s ease-in-out infinite;
    }

    @keyframes floatHeart {
        0%, 100% {
            transform: translateY(0) rotate(0deg);
            opacity: 0;
        }
        10%, 90% {
            opacity: 1;
        }
        50% {
            transform: translateY(-100px) rotate(180deg);
        }
    }

    /* Loading States */
    .btn.loading {
        position: relative;
        color: transparent !important;
    }

    .btn.loading::after {
        content: '';
        position: absolute;
        left: 50%;
        top: 50%;
        width: 20px;
        height: 20px;
        margin: -10px 0 0 -10px;
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-top-color: white;
        border-radius: 50%;
        animation: spin 0.6s linear infinite;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    /* Pink Badge Variants */
    .badge.bg-pink-soft {
        background-color: rgba(236, 72, 153, 0.1) !important;
        color: #be185d;
    }

    /* Pink Hover Effects */
    .text-pink-hover:hover {
        color: #ec4899 !important;
    }

    /* Pink Shadow Effects */
    .shadow-pink {
        box-shadow: 0 10px 25px rgba(236, 72, 153, 0.15);
    }

    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .modal-dialog {
            margin: 1rem;
        }
        
        .modal-body .row {
            flex-direction: column;
        }
        
        .product-image-container {
            min-height: 280px;
        }
        
        .product-modal-image {
            max-height: 220px;
        }
        
        .modal-footer .btn {
            padding: 14px !important;
            font-size: 1rem;
        }
        
        .modal-header, .modal-footer {
            padding: 1rem !important;
        }
        
        /* Smaller close button on mobile */
        .btn-close-pink {
            width: 35px;
            height: 35px;
        }
        
        .btn-close-pink i {
            font-size: 1rem;
        }
    }

    /* Pink Focus States */
    .btn-gradient-pink:focus,
    .btn-outline-pink:focus {
        box-shadow: 0 0 0 3px rgba(236, 72, 153, 0.25);
    }

    /* Pink Transitions */
    .pink-transition {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* Glow Effect for Special Elements */
    .pink-glow {
        box-shadow: 0 0 20px rgba(236, 72, 153, 0.3);
    }

    .pink-glow:hover {
        box-shadow: 0 0 30px rgba(236, 72, 153, 0.4);
    }
</style>

<script>
    // Keep all the original JavaScript code here - unchanged
    // (All your existing JavaScript code remains exactly the same)
    // Global variables for modal
    let currentProduct = null;
    let selectedAddOns = [];
    let basePrice = 0;
    let modalInstance = null;

    /**
     * Open product modal with product details
     */
    function openProductModal(productElement) {
        console.log('Opening modal for product:', productElement);
        
        // Extract product data from element
        currentProduct = {
            id: productElement.dataset.productId,
            name: productElement.dataset.productName,
            image: productElement.dataset.productImage,
            description: productElement.dataset.productDescription,
            price: parseFloat(productElement.dataset.price),
            stock: parseInt(productElement.dataset.productStock),
            category: productElement.dataset.productCategoryName,
            hasAddons: productElement.dataset.hasAddons === '1',
            addons: []
        };

        // Parse add-ons from JSON if available
        if (currentProduct.hasAddons && productElement.dataset.productAddons) {
            try {
                const addonsData = JSON.parse(productElement.dataset.productAddons);
                currentProduct.addons = addonsData.addons || [];
            } catch (e) {
                console.error('Error parsing add-ons:', e);
                currentProduct.addons = [];
            }
        }

        console.log('Current product data:', currentProduct);

        // Reset modal state
        selectedAddOns = [];
        basePrice = currentProduct.price;

        // Populate modal with product data
        document.getElementById('modalProductName').textContent = currentProduct.name;
        document.getElementById('modalProductImage').src = currentProduct.image;
        document.getElementById('modalProductImage').alt = currentProduct.name;
        document.getElementById('modalProductPrice').textContent = `₱${currentProduct.price.toFixed(2)}`;
        document.getElementById('modalProductCategory').textContent = currentProduct.category;
        document.getElementById('modalProductDescription').textContent = currentProduct.description;
        document.getElementById('modalProductStock').textContent = `${currentProduct.stock} pieces`;
        document.getElementById('modalQuantity').value = 1;
        document.getElementById('modalQuantity').max = currentProduct.stock;

        // Populate add-ons section
        populateAddOns();

        // Update total price
        updateModalTotalPrice();

        // Show modal
        const modalElement = document.getElementById('productModal');
        if (!modalInstance) {
            modalInstance = new bootstrap.Modal(modalElement);
        }
        modalInstance.show();
    }

    /**
     * Populate add-ons dynamically
     */
    function populateAddOns() {
        const addOnsSection = document.getElementById('addOnsSection');
        const addOnsList = document.getElementById('addOnsList');
        
        // Clear previous add-ons
        addOnsList.innerHTML = '';
        
        if (currentProduct.addons && currentProduct.addons.length > 0) {
            // Show add-ons section
            addOnsSection.classList.remove('d-none');
            
            // Create add-on elements
            currentProduct.addons.forEach(addon => {
                const addonElement = document.createElement('div');
                addonElement.className = 'add-on-item';
                addonElement.dataset.addonId = addon.id;
                addonElement.dataset.addonName = addon.name;
                addonElement.dataset.addonPrice = addon.price;
                
                addonElement.innerHTML = `
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong>${addon.name}</strong>
                            ${addon.description ? `<small class="text-muted d-block">${addon.description}</small>` : ''}
                        </div>
                        <div class="text-end">
                            <span class="text-pink fw-bold">+₱${parseFloat(addon.price).toFixed(2)}</span>
                            <i class="fas fa-check-circle ms-2 d-none"></i>
                        </div>
                    </div>
                `;
                
                addonElement.addEventListener('click', function() {
                    toggleAddOn(this);
                });
                
                addOnsList.appendChild(addonElement);
            });
        } else {
            // Hide add-ons section if no add-ons available
            addOnsSection.classList.add('d-none');
        }
    }

    /**
     * Change quantity in modal
     */
    function changeModalQuantity(delta) {
        if (!currentProduct) return;
        
        const input = document.getElementById('modalQuantity');
        let qty = parseInt(input.value) || 1;
        
        // Calculate new quantity
        qty = qty + delta;
        
        // Ensure quantity is within bounds
        qty = Math.max(1, Math.min(currentProduct.stock, qty));
        
        input.value = qty;
        updateModalTotalPrice();
    }

    /**
     * Toggle add-on selection
     */
    function toggleAddOn(addOnElement) {
        const id = addOnElement.dataset.addonId;
        const name = addOnElement.dataset.addonName;
        const price = parseFloat(addOnElement.dataset.addonPrice);
        const checkIcon = addOnElement.querySelector('.fa-check-circle');

        if (addOnElement.classList.contains('selected')) {
            // Deselect
            addOnElement.classList.remove('selected');
            checkIcon.classList.add('d-none');
            selectedAddOns = selectedAddOns.filter(a => a.id !== id);
        } else {
            // Select
            addOnElement.classList.add('selected');
            checkIcon.classList.remove('d-none');
            selectedAddOns.push({ id, name, price });
        }
        
        updateModalTotalPrice();
    }

    /**
     * Update total price display
     */
    function updateModalTotalPrice() {
        if (!currentProduct) return;
        
        const qty = parseInt(document.getElementById('modalQuantity').value) || 1;
        const addOnsTotal = selectedAddOns.reduce((sum, a) => sum + a.price, 0);
        const total = (basePrice * qty) + addOnsTotal;
        
        document.getElementById('modalTotalPrice').textContent = `₱${total.toFixed(2)}`;
    }

    /**
     * Add to cart from modal
     */
    function modalAddToCart() {
        if (!currentProduct) {
            showToast("No product selected", true);
            return;
        }

        const qty = parseInt(document.getElementById('modalQuantity').value) || 1;
        const button = document.getElementById('modalAddToCart');
        
        // Disable button and show loading
        button.disabled = true;
        const originalHTML = button.innerHTML;
        button.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Adding...';

        fetch("{{ route('cart.add') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                "X-Requested-With": "XMLHttpRequest"
            },
            body: JSON.stringify({
                product_id: currentProduct.id,
                quantity: qty,
                selected_addons: selectedAddOns
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                updateCartCount(data.cart_count);
                
                let message = `${currentProduct.name} (${qty}) added to cart!`;
                if (selectedAddOns.length > 0) {
                    message += ` with ${selectedAddOns.length} add-on(s)`;
                }
                showToast(message);
                
                setTimeout(() => {
                    if (modalInstance) {
                        modalInstance.hide();
                    }
                }, 1000);
            } else {
                showToast(data.message || "Failed to add to cart", true);
            }
        })
        .catch(error => {
            console.error('Error adding to cart:', error);
            showToast("Error adding to cart", true);
        })
        .finally(() => {
            button.disabled = false;
            button.innerHTML = originalHTML;
        });
    }

    /**
     * Buy now from modal - FIXED VERSION
     */
    function modalBuyNow() {
        if (!currentProduct) {
            showToast("No product selected", true);
            return;
        }

        const qty = parseInt(document.getElementById('modalQuantity').value) || 1;
        const button = document.getElementById('modalBuyNow');
        
        // Check if user is logged in
        const isLoggedIn = {{ Auth::check() ? 'true' : 'false' }};
        if (!isLoggedIn) {
            showToast("Please login to continue", true);
            setTimeout(() => {
                window.location.href = "{{ route('login') }}";
            }, 1500);
            return;
        }
        
        // Disable button and show loading
        button.disabled = true;
        const originalHTML = button.innerHTML;
        button.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Processing...';

        console.log('Buy Now - Starting request for product:', currentProduct.id);

        // Make AJAX request to buy-now endpoint
        fetch("{{ route('cart.buy-now') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                "Accept": "application/json",
                "X-Requested-With": "XMLHttpRequest"
            },
            body: JSON.stringify({
                product_id: currentProduct.id,
                quantity: qty,
                selected_addons: selectedAddOns || []
            })
        })
        .then(response => {
            console.log('Buy Now - Response received, status:', response.status);
            console.log('Buy Now - Response OK:', response.ok);
            
            // Check if response is OK (status 200-299)
            if (!response.ok) {
                // Try to parse error response
                return response.json().then(errorData => {
                    console.error('Buy Now - Server error:', errorData);
                    throw new Error(errorData.message || `Server error: ${response.status}`);
                }).catch(parseError => {
                    // If JSON parsing fails, throw generic error
                    console.error('Buy Now - Failed to parse error response:', parseError);
                    throw new Error(`Server error: ${response.status}`);
                });
            }
            
            // Parse successful response
            return response.json();
        })
        .then(data => {
            console.log('Buy Now - Success data:', data);
            
            if (data.success) {
                console.log('Buy Now - Success! Redirecting to checkout...');
                
                showToast("Redirecting to checkout...");
                
                // Close modal immediately
                if (modalInstance) {
                    modalInstance.hide();
                }
                
                // Get redirect URL from response or use default
                const checkoutUrl = data.redirect_url || "{{ route('checkout.index') }}";
                console.log('Buy Now - Final redirect URL:', checkoutUrl);
                
                // Force redirect after short delay
                setTimeout(() => {
                    console.log('Buy Now - Executing redirect NOW');
                    window.location.href = checkoutUrl;
                }, 300);
                
            } else {
                // Server returned success:false
                console.error('Buy Now - Server returned success:false', data);
                showToast(data.message || "Failed to process order", true);
                button.disabled = false;
                button.innerHTML = originalHTML;
            }
        })
        .catch(error => {
            console.error('Buy Now - Fetch error:', error);
            console.error('Buy Now - Error stack:', error.stack);
            
            showToast(error.message || "Error processing order. Please try again.", true);
            button.disabled = false;
            button.innerHTML = originalHTML;
        });
    }

    /**
     * Update cart count badge
     */
    function updateCartCount(newCount) {
        const badge = document.querySelector('.cart-badge');
        if (badge) {
            badge.textContent = newCount;
            badge.style.animation = 'none';
            setTimeout(() => {
                badge.style.animation = 'pulse 0.6s';
            }, 10);
        }
    }

    /**
     * Show toast notification
     */
    function showToast(message, isError = false) {
        const toastId = isError ? "errorToast" : "cartToast";
        const toastEl = document.getElementById(toastId);
        
        if (!toastEl) {
            console.error('Toast element not found:', toastId);
            return;
        }
        
        const messageSpan = toastEl.querySelector("span");
        if (messageSpan) {
            messageSpan.textContent = message;
        }
        
        const toast = new bootstrap.Toast(toastEl, {
            autohide: true,
            delay: 3000
        });
        toast.show();
    }

    /**
     * Initialize modal event listeners
     */
    document.addEventListener("DOMContentLoaded", () => {
        console.log('Initializing modal event listeners');
        console.log('Buy Now route:', "{{ route('cart.buy-now') }}");
        console.log('Checkout route:', "{{ route('checkout.index') }}");
        
        // Quantity buttons
        const decreaseBtn = document.getElementById("modalDecreaseBtn");
        const increaseBtn = document.getElementById("modalIncreaseBtn");
        
        if (decreaseBtn) {
            decreaseBtn.addEventListener("click", () => changeModalQuantity(-1));
        }
        
        if (increaseBtn) {
            increaseBtn.addEventListener("click", () => changeModalQuantity(1));
        }
        
        // Action buttons
        const addToCartBtn = document.getElementById("modalAddToCart");
        const buyNowBtn = document.getElementById("modalBuyNow");
        
        if (addToCartBtn) {
            console.log('Add to Cart button found, binding click event');
            addToCartBtn.addEventListener("click", modalAddToCart);
        } else {
            console.error('Add to Cart button NOT found!');
        }
        
        if (buyNowBtn) {
            console.log('Buy Now button found, binding click event');
            buyNowBtn.addEventListener("click", modalBuyNow);
        } else {
            console.error('Buy Now button NOT found!');
        }
        
        console.log('Modal event listeners initialized');
    });

    // Make function globally accessible
    window.openProductModal = openProductModal;
</script>