{{-- resources/views/components/pmodal.blade.php --}}
<link rel="stylesheet" href="{{ asset('asset/stylesshop.css') }}">

<!-- Product Modal - Landscape Cool Design -->
<div class="modal fade product-modal" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content landscape-modal">
            <!-- Close Button - Absolute Positioned -->
            <button type="button" class="btn-close-landscape" data-bs-dismiss="modal" aria-label="Close">
                <i class="fas fa-times"></i>
            </button>

            <div class="modal-inner">
                <!-- Left Side - Product Image -->
                <div class="image-side">
                    <div class="image-wrapper">
                        <div class="image-bg-pattern"></div>
                        <img id="modalProductImage" src="" alt="" class="product-img-landscape">
                        <div class="image-glow"></div>
                    </div>
                    <!-- Stock Badge -->
                    <div class="stock-badge-landscape">
                        <div class="stock-icon">
                            <i class="fas fa-box-open"></i>
                        </div>
                        <div class="stock-info">
                            <span class="stock-label">Available</span>
                            <span class="stock-number" id="stockCount">0</span>
                        </div>
                    </div>
                </div>

                <!-- Right Side - Product Details -->
                <div class="content-side">
                    <div class="content-scroll">
                        <!-- Header -->
                        <div class="product-header-landscape">
                            <span class="category-pill" id="modalProductCategory"></span>
                            <h3 id="modalProductName" class="product-name-landscape"></h3>
                        </div>

                        <!-- Price -->
                        <div class="price-box-landscape">
                            <div class="price-main">
                                <span class="price-value" id="modalProductPrice"></span>
                                <span class="price-per">/ item</span>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="description-landscape">
                            <div class="desc-header">
                                <i class="fas fa-info-circle"></i>
                                <span>About</span>
                            </div>
                            <p id="modalProductDescription" class="desc-text"></p>
                        </div>

                        <!-- Quantity & Add-ons Row -->
                        <div class="controls-row">
                            <!-- Quantity -->
                            <div class="quantity-box-landscape">
                                <label class="control-label">
                                    <i class="fas fa-cubes"></i> Quantity
                                </label>
                                <div class="qty-control-landscape">
                                    <button type="button" class="qty-btn-land" id="modalDecreaseBtn">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                    <input type="number" class="qty-display-land" id="modalQuantity" value="1" min="1" readonly>
                                    <button type="button" class="qty-btn-land" id="modalIncreaseBtn">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Add-ons (if available) -->
                            <div class="addons-box-landscape d-none" id="addOnsSection">
                                <label class="control-label">
                                    <i class="fas fa-star"></i> Extras
                                </label>
                                <div class="addons-list-landscape" id="addOnsList">
                                    <!-- Dynamic add-ons -->
                                </div>
                            </div>
                        </div>

                        <!-- Footer - Total & Actions -->
                        <div class="modal-footer-landscape">
                            <div class="total-box-landscape">
                                <div class="total-info">
                                    <span class="total-label-land">Total Price</span>
                                    <span class="total-value-land" id="modalTotalPrice">₱0.00</span>
                                </div>
                            </div>
                            
                            <div class="action-group-landscape">
                                <button type="button" class="btn-land btn-cart-land" id="modalAddToCart">
                                    <i class="fas fa-shopping-bag"></i>
                                    Add to Cart
                                </button>
                                <button type="button" class="btn-land btn-buy-land" id="modalBuyNow">
                                    <i class="fas fa-bolt"></i>
                                    Buy Now
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<style>
:root {
    --pink-primary: #ec4899;
    --pink-dark: #be185d;
    --pink-darker: #9f1239;
    --pink-light: #fce7f3;
    --pink-ultra-light: #fdf2f8;
    --text-dark: #1f2937;
    --text-muted: #6b7280;
    --white: #ffffff;
    --gray-bg: #f9fafb;
}

/* Modal Base - Landscape */
.product-modal .modal-dialog {
    max-width: 900px;
    margin: 1rem auto;
}

@media (min-width: 992px) {
    .product-modal .modal-dialog {
        max-width: 1000px;
    }
}

.landscape-modal {
    border: none;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    background: var(--white);
    position: relative;
}

/* Close Button - Landscape */
.btn-close-landscape {
    position: absolute;
    top: 20px;
    right: 20px;
    width: 44px;
    height: 44px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 100;
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
}

.btn-close-landscape i {
    color: var(--text-dark);
    font-size: 20px;
    transition: all 0.3s ease;
}

.btn-close-landscape:hover {
    background: var(--pink-primary);
    transform: rotate(90deg) scale(1.15);
    box-shadow: 0 8px 30px rgba(236, 72, 153, 0.5);
}

.btn-close-landscape:hover i {
    color: var(--white);
    transform: rotate(-90deg);
}

/* Modal Inner Layout */
.modal-inner {
    display: flex;
    flex-direction: row;
    min-height: 600px;
    max-height: 85vh;
}

/* Left Side - Image */
.image-side {
    flex: 0 0 45%;
    background: linear-gradient(135deg, #fdf2f8 0%, #fce7f3 50%, #fbcfe8 100%);
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}

.image-bg-pattern {
    position: absolute;
    inset: 0;
    background-image: 
        radial-gradient(circle at 20% 50%, rgba(236, 72, 153, 0.1) 0%, transparent 50%),
        radial-gradient(circle at 80% 80%, rgba(190, 24, 93, 0.1) 0%, transparent 50%);
    pointer-events: none;
}

.image-wrapper {
    position: relative;
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 40px;
}

.product-img-landscape {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
    border-radius: 16px;
    transition: transform 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
    filter: drop-shadow(0 20px 40px rgba(0, 0, 0, 0.2));
    position: relative;
    z-index: 2;
}

.product-img-landscape:hover {
    transform: scale(1.08) rotate(2deg);
}

.image-glow {
    position: absolute;
    inset: 10%;
    background: radial-gradient(circle, rgba(236, 72, 153, 0.3), transparent 70%);
    filter: blur(40px);
    opacity: 0.6;
    pointer-events: none;
    animation: pulse-glow 3s ease-in-out infinite;
}

@keyframes pulse-glow {
    0%, 100% { opacity: 0.4; transform: scale(1); }
    50% { opacity: 0.7; transform: scale(1.1); }
}

/* Stock Badge - Landscape */
.stock-badge-landscape {
    position: absolute;
    top: 24px;
    left: 24px;
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    padding: 12px 20px;
    border-radius: 50px;
    display: flex;
    align-items: center;
    gap: 12px;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
    z-index: 10;
}

.stock-icon {
    width: 36px;
    height: 36px;
    background: linear-gradient(135deg, var(--pink-primary), var(--pink-dark));
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--white);
    font-size: 16px;
}

.stock-info {
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.stock-label {
    font-size: 11px;
    color: var(--text-muted);
    text-transform: uppercase;
    font-weight: 600;
    letter-spacing: 0.5px;
}

.stock-number {
    font-size: 18px;
    font-weight: 800;
    background: linear-gradient(135deg, var(--pink-primary), var(--pink-dark));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

/* Right Side - Content */
.content-side {
    flex: 1;
    display: flex;
    flex-direction: column;
    background: var(--white);
}

.content-scroll {
    flex: 1;
    overflow-y: auto;
    padding: 20px;
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.content-scroll::-webkit-scrollbar {
    width: 8px;
}

.content-scroll::-webkit-scrollbar-track {
    background: var(--gray-bg);
}

.content-scroll::-webkit-scrollbar-thumb {
    background: linear-gradient(180deg, var(--pink-primary), var(--pink-dark));
    border-radius: 4px;
}

/* Product Header - Landscape */
.product-header-landscape {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.category-pill {
    display: inline-block;
    background: linear-gradient(135deg, var(--pink-primary), var(--pink-dark));
    color: var(--white);
    padding: 8px 20px;
    border-radius: 50px;
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
    align-self: flex-start;
    box-shadow: 0 4px 12px rgba(236, 72, 153, 0.3);
}

.product-name-landscape {
    font-size: 28px;
    font-weight: 800;
    color: var(--text-dark);
    line-height: 1.2;
    margin: 0;
}

/* Price Box - Landscape */
.price-box-landscape {
    background: linear-gradient(135deg, var(--pink-ultra-light), var(--pink-light));
    border-radius: 16px;
   width:150px;
   padding: 6px;
        border: 2px solid var(--pink-light);
}

.price-main {
    display: flex;
    align-items: baseline;
   
}

.price-value {
    font-size: 25px;
    font-weight: 900;
    background: linear-gradient(135deg, var(--pink-primary), var(--pink-dark));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    letter-spacing: -1px;
}

.price-per {
    font-size: 14px;
    color: var(--text-muted);
    font-weight: 600;
}

/* Description - Landscape */
.description-landscape {
    background: var(--gray-bg);
    border-radius: 12px;
    padding: 16px 20px;
    width:500px;
    height:700px;
    border-left: 4px solid var(--pink-primary);
}

.desc-header {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 8px;
    color: var(--pink-dark);
    font-weight: 500;
    font-size: 14px;
}

.desc-header i {
    font-size: 16px;
}

.desc-text {
   
    line-height: 1.6;
    color: var(--text-dark);
    margin: 0;
    
    max-height: 90px;
    font-size: 12px;
}


.desc-text::-webkit-scrollbar {
    width: 4px;
}

.desc-text::-webkit-scrollbar-thumb {
    background: var(--pink-primary);
    border-radius: 2px;
}

/* Controls Row */
.controls-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
}

.control-label {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    font-weight: 500;
    color: var(--text-dark);
    margin-bottom: 10px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.control-label i {
    color: var(--pink-primary);
    font-size: 14px;
}

/* Quantity Box - Landscape */
.quantity-box-landscape {
    background: var(--white);
    border: 2px solid var(--pink-light);
    border-radius: 14px;
    padding: 12px;
    width: 150px;
}

.qty-control-landscape {
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: linear-gradient(135deg, var(--pink-ultra-light), var(--pink-light));
    border-radius: 12px;
    padding: 2px;
    gap: 2px;
}

.qty-btn-land {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: var(--white);
    border: 2px solid var(--pink-primary);
    color: var(--pink-primary);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    font-size: 14px;
    font-weight: bold;
}

.qty-btn-land:hover {
    background: var(--pink-primary);
    color: var(--white);
    transform: scale(1.1);
    box-shadow: 0 4px 12px rgba(236, 72, 153, 0.3);
}

.qty-btn-land:active {
    transform: scale(0.95);
}

.qty-display-land {
    flex: 1;
    text-align: center;
    font-size: 20px;
    font-weight: 800;
    color: var(--pink-dark);
    background: transparent;
    border: none;
    outline: none;
}

/* Add-ons Box - Landscape */
.addons-box-landscape {
    background: var(--white);
    border: 2px solid var(--pink-light);
    border-radius: 14px;
    padding: 16px;
}

.addons-list-landscape {
    display: flex;
    flex-direction: column;
    gap: 8px;
    max-height: 120px;
    overflow-y: auto;
}

.addons-list-landscape::-webkit-scrollbar {
    width: 4px;
}

.addons-list-landscape::-webkit-scrollbar-thumb {
    background: var(--pink-primary);
    border-radius: 2px;
}

.add-on-item {
    background: var(--gray-bg);
    border: 2px solid transparent;
    border-radius: 10px;
    padding: 10px 14px;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.add-on-item:hover {
    border-color: var(--pink-primary);
    background: var(--pink-ultra-light);
    transform: translateX(4px);
}

.add-on-item.selected {
    border-color: var(--pink-primary);
    background: linear-gradient(135deg, rgba(236, 72, 153, 0.15), rgba(190, 24, 93, 0.15));
    box-shadow: 0 4px 12px rgba(236, 72, 153, 0.2);
}

.add-on-info {
    flex: 1;
}

.add-on-name {
    display: block;
    font-weight: 700;
    font-size: 13px;
    color: var(--text-dark);
    margin-bottom: 2px;
}

.add-on-desc {
    display: block;
    font-size: 11px;
    color: var(--text-muted);
}

.add-on-price {
    font-weight: 800;
    font-size: 13px;
    color: var(--pink-primary);
    margin-right: 8px;
}

.add-on-item .fa-check-circle {
    color: var(--pink-primary);
    font-size: 18px;
    opacity: 0;
    transform: scale(0);
    transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
}

.add-on-item.selected .fa-check-circle {
    opacity: 1;
    transform: scale(1) rotate(360deg);
}

/* Footer - Landscape */
.modal-footer-landscape {
    background: var(--white);
    border-top: 2px solid var(--pink-light);
    padding: 20px 32px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
}

.total-box-landscape {
    flex: 1;
}

.total-info {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.total-label-land {
    font-size: 12px;
    color: var(--text-muted);
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.total-value-land {
    font-size: 32px;
    font-weight: 900;
    background: linear-gradient(135deg, var(--pink-primary), var(--pink-dark));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

/* Action Buttons - Landscape */
.action-group-landscape {
    display: flex;
    gap: 12px;
}

.btn-land {
    padding: 14px 28px;
    border-radius: 12px;
    border: none;
    font-size: 15px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    display: flex;
    align-items: center;
    gap: 8px;
    position: relative;
    overflow: hidden;
    white-space: nowrap;
}

.btn-land::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.3);
    transform: translate(-50%, -50%);
    transition: width 0.6s, height 0.6s;
}

.btn-land:hover::before {
    width: 400px;
    height: 400px;
}

.btn-cart-land {
    background: var(--white);
    color: var(--pink-primary);
    border: 2px solid var(--pink-primary);
}

.btn-cart-land:hover {
    background: var(--pink-primary);
    color: var(--white);
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(236, 72, 153, 0.4);
}

.btn-buy-land {
    background: linear-gradient(135deg, var(--pink-primary), var(--pink-dark));
    color: var(--white);
    box-shadow: 0 8px 24px rgba(236, 72, 153, 0.3);
}

.btn-buy-land:hover {
    background: linear-gradient(135deg, var(--pink-dark), var(--pink-darker));
    transform: translateY(-2px);
    box-shadow: 0 12px 32px rgba(236, 72, 153, 0.5);
}

.btn-land:active {
    transform: translateY(0);
}

/* Loading State */
.btn-land.loading {
    pointer-events: none;
    opacity: 0.7;
}

.btn-land.loading i,
.btn-land.loading span {
    opacity: 0;
}

.btn-land.loading::after {
    content: '';
    position: absolute;
    width: 20px;
    height: 20px;
    border: 3px solid rgba(255, 255, 255, 0.3);
    border-top-color: var(--white);
    border-radius: 50%;
    animation: spin 0.6s linear infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

/* Modal Animation */
.product-modal.fade .modal-dialog {
    transform: scale(0.8);
    opacity: 0;
    transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
}

.product-modal.show .modal-dialog {
    transform: scale(1);
    opacity: 1;
}

/* Responsive - Tablet */
@media (max-width: 991px) {
    .product-modal .modal-dialog {
        max-width: 90%;
    }

    .modal-inner {
        min-height: 450px;
    }

    .image-side {
        flex: 0 0 40%;
    }

    .content-scroll {
        padding: 24px;
        gap: 20px;
    }

    .product-name-landscape {
        font-size: 24px;
    }

    .price-value {
        font-size: 32px;
    }
}

/* Responsive - Mobile */
@media (max-width: 767px) {
    .product-modal .modal-dialog {
        margin: 0;
        max-width: 100%;
        height: 100vh;
    }

    .landscape-modal {
        border-radius: 0;
        height: 100vh;
    }

    .modal-inner {
        flex-direction: column;
        min-height: 100vh;
        max-height: 100vh;
    }

    .image-side {
        flex: 0 0 280px;
    }

    .content-side {
        flex: 1;
        overflow: hidden;
    }

    .content-scroll {
        padding: 20px;
        gap: 16px;
    }

    .product-name-landscape {
        font-size: 22px;
    }

    .price-value {
        font-size: 28px;
    }

    .controls-row {
        grid-template-columns: 1fr;
    }

    .modal-footer-landscape {
        flex-direction: column;
        align-items: stretch;
        padding: 16px 20px;
    }

    .total-box-landscape {
        text-align: center;
    }

    .action-group-landscape {
        flex-direction: column;
        width: 100%;
    }

    .btn-land {
        width: 100%;
        justify-content: center;
        padding: 16px;
    }

    .btn-close-landscape {
        top: 16px;
        right: 16px;
        width: 40px;
        height: 40px;
    }

    .stock-badge-landscape {
        top: 16px;
        left: 16px;
        padding: 10px 16px;
    }
}

/* Small Mobile */
@media (max-width: 575px) {
    .image-side {
        flex: 0 0 240px;
    }

    .product-name-landscape {
        font-size: 20px;
    }

    .price-value {
        font-size: 24px;
    }

    .total-value-land {
        font-size: 28px;
    }
}
</style>

<script>
    let currentProduct = null;
    let selectedAddOns = [];
    let basePrice = 0;
    let modalInstance = null;

    function openProductModal(productElement) {
        // console.log('Opening modal for product:', productElement); // Removed
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

        if (currentProduct.hasAddons && productElement.dataset.productAddons) {
            try {
                const addonsData = JSON.parse(productElement.dataset.productAddons);
                currentProduct.addons = addonsData.addons || [];
            } catch (e) {
                // console.error('Error parsing add-ons:', e); // Removed
                currentProduct.addons = [];
            }
        }

        selectedAddOns = [];
        basePrice = currentProduct.price;

        document.getElementById('modalProductName').textContent = currentProduct.name;
        document.getElementById('modalProductImage').src = currentProduct.image;
        document.getElementById('modalProductImage').alt = currentProduct.name;
        document.getElementById('modalProductPrice').textContent = `₱${currentProduct.price.toFixed(2)}`;
        document.getElementById('modalProductCategory').textContent = currentProduct.category;
        document.getElementById('modalProductDescription').textContent = currentProduct.description;
        document.getElementById('stockCount').textContent = currentProduct.stock;
        document.getElementById('modalQuantity').value = 1;
        document.getElementById('modalQuantity').max = currentProduct.stock;

        populateAddOns();
        updateModalTotalPrice();

        const modalElement = document.getElementById('productModal');
        if (!modalInstance) {
            modalInstance = new bootstrap.Modal(modalElement);
        }
        modalInstance.show();
    }

    function populateAddOns() {
        const addOnsSection = document.getElementById('addOnsSection');
        const addOnsList = document.getElementById('addOnsList');
        
        addOnsList.innerHTML = '';
        
        if (currentProduct.addons && currentProduct.addons.length > 0) {
            addOnsSection.classList.remove('d-none');
            
            currentProduct.addons.forEach(addon => {
                const addonElement = document.createElement('div');
                addonElement.className = 'add-on-item';
                addonElement.dataset.addonId = addon.id;
                addonElement.dataset.addonName = addon.name;
                addonElement.dataset.addonPrice = addon.price;
                
                addonElement.innerHTML = `
                    <div class="add-on-info">
                        <span class="add-on-name">${addon.name}</span>
                        ${addon.description ? `<span class="add-on-desc">${addon.description}</span>` : ''}
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="add-on-price">+₱${parseFloat(addon.price).toFixed(2)}</span>
                        <i class="fas fa-check-circle"></i>
                    </div>
                `;
                
                addonElement.addEventListener('click', function() {
                    toggleAddOn(this);
                });
                
                addOnsList.appendChild(addonElement);
            });
        } else {
            addOnsSection.classList.add('d-none');
        }
    }

    function changeModalQuantity(delta) {
        if (!currentProduct) return;
        
        const input = document.getElementById('modalQuantity');
        let qty = parseInt(input.value) || 1;
        qty = qty + delta;
        qty = Math.max(1, Math.min(currentProduct.stock, qty));
        input.value = qty;
        updateModalTotalPrice();
    }

    function toggleAddOn(addOnElement) {
        const id = addOnElement.dataset.addonId;
        const name = addOnElement.dataset.addonName;
        const price = parseFloat(addOnElement.dataset.addonPrice);

        if (addOnElement.classList.contains('selected')) {
            addOnElement.classList.remove('selected');
            selectedAddOns = selectedAddOns.filter(a => a.id !== id);
        } else {
            addOnElement.classList.add('selected');
            selectedAddOns.push({ id, name, price });
        }
        
        updateModalTotalPrice();
    }

    function updateModalTotalPrice() {
        if (!currentProduct) return;
        
        const qty = parseInt(document.getElementById('modalQuantity').value) || 1;
        const addOnsTotal = selectedAddOns.reduce((sum, a) => sum + a.price, 0);
        const total = (basePrice * qty) + addOnsTotal;
        
        document.getElementById('modalTotalPrice').textContent = `₱${total.toFixed(2)}`;
    }

    function showLoginModal() {
        // Close product modal first
        if (modalInstance) {
            modalInstance.hide();
        }
        
        // Show the existing HookCraft login modal
        const loginModalElement = document.getElementById('loginModal');
        if (loginModalElement) {
            const loginModal = new bootstrap.Modal(loginModalElement);
            loginModal.show();
        }
    }

    function modalAddToCart() {
        if (!currentProduct) {
            showToast("No product selected", true);
            return;
        }

        const isLoggedIn = {{ Auth::check() ? 'true' : 'false' }};
        if (!isLoggedIn) {
            showLoginModal();
            return;
        }

        const qty = parseInt(document.getElementById('modalQuantity').value) || 1;
        const button = document.getElementById('modalAddToCart');
        
        button.disabled = true;
        button.classList.add('loading');

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
                showToast(`${currentProduct.name} added!`);
                setTimeout(() => modalInstance?.hide(), 1000);
            } else {
                showToast(data.message || "Failed to add to cart", true);
            }
        })
        .catch(error => {
            // console.error('Error:', error); // Removed
            showToast("Error adding to cart", true);
        })
        .finally(() => {
            button.disabled = false;
            button.classList.remove('loading');
        });
    }

    function modalBuyNow() {
        if (!currentProduct) {
            showToast("No product selected", true);
            return;
        }

        const isLoggedIn = {{ Auth::check() ? 'true' : 'false' }};
        if (!isLoggedIn) {
            showLoginModal();
            return;
        }

        const qty = parseInt(document.getElementById('modalQuantity').value) || 1;
        const button = document.getElementById('modalBuyNow');
        
        button.disabled = true;
        button.classList.add('loading');

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
            if (!response.ok) {
                return response.json().then(errorData => {
                    throw new Error(errorData.message || `Server error: ${response.status}`);
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                showToast("Redirecting to checkout...");
                modalInstance?.hide();
                const checkoutUrl = data.redirect_url || "{{ route('checkout.index') }}";
                setTimeout(() => window.location.href = checkoutUrl, 300);
            } else {
                showToast(data.message || "Failed to process order", true);
                button.disabled = false;
                button.classList.remove('loading');
            }
        })
        .catch(error => {
            // console.error('Error:', error); // Removed
            showToast(error.message || "Error processing order", true);
            button.disabled = false;
            button.classList.remove('loading');
        });
    }

    function updateCartCount(newCount) {
        const badge = document.querySelector('.cart-badge');
        if (badge) badge.textContent = newCount;
    }

    function showToast(message, isError = false) {
        const toastId = isError ? "errorToast" : "cartToast";
        const toastEl = document.getElementById(toastId);
        if (!toastEl) return;
        const messageSpan = toastEl.querySelector("span");
        if (messageSpan) messageSpan.textContent = message;
        const toast = new bootstrap.Toast(toastEl, { autohide: true, delay: 3000 });
        toast.show();
    }

    document.addEventListener("DOMContentLoaded", () => {
        document.getElementById("modalDecreaseBtn")?.addEventListener("click", () => changeModalQuantity(-1));
        document.getElementById("modalIncreaseBtn")?.addEventListener("click", () => changeModalQuantity(1));
        document.getElementById("modalAddToCart")?.addEventListener("click", modalAddToCart);
        document.getElementById("modalBuyNow")?.addEventListener("click", modalBuyNow);
    });

    window.openProductModal = openProductModal;
</script>