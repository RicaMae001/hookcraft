{{-- resources/views/components/pmodal.blade.php --}}
<link rel="stylesheet" href="{{ asset('asset/stylesshop.css') }}">

<!-- Product Modal -->
<div class="modal fade product-modal" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="productModalLabel">Product Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <!-- Product Image -->
                    <div class="col-md-6">
                        <img id="modalProductImage" src="" alt="" class="img-fluid product-modal-image w-100">
                    </div>

                    <!-- Product Details -->
                    <div class="col-md-6">
                        <h4 id="modalProductName" class="mb-3"></h4>

                        <!-- Price -->
                        <div class="product-modal-price mb-3" id="modalProductPrice"></div>

                        <!-- Category -->
                        <div class="mb-3">
                            <span class="badge bg-secondary" id="modalProductCategory"></span>
                        </div>

                        <!-- Description -->
                        <div class="mb-3">
                            <h6><i class="fas fa-info-circle me-2"></i>Description</h6>
                            <p id="modalProductDescription" class="text-muted"></p>
                        </div>

                        <!-- Stock Info -->
                        <div class="stock-info mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-boxes me-2"></i>Stock Available:</span>
                                <span id="modalProductStock" class="fw-bold"></span>
                            </div>
                        </div>

                        <!-- Quantity Selector -->
                        <div class="mb-3">
                            <label class="form-label"><i class="fas fa-sort-numeric-up me-2"></i>Quantity</label>
                            <div class="quantity-input-group d-flex">
                                <button type="button" class="quantity-btn" id="modalDecreaseBtn">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <input type="number" class="form-control quantity-input" id="modalQuantity" value="1" min="1" readonly>
                                <button type="button" class="quantity-btn" id="modalIncreaseBtn">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Add-ons Section (Dynamic) -->
                        <div class="add-ons-section d-none" id="addOnsSection">
                            <h6><i class="fas fa-plus-circle me-2"></i>Add-ons (Optional)</h6>
                            <div id="addOnsList">
                                <!-- Add-ons will be populated dynamically from database -->
                            </div>
                        </div>

                        <!-- Total Price -->
                        <div class="d-flex justify-content-between align-items-center mb-3 p-3 bg-light rounded">
                            <strong>Total Price:</strong>
                            <strong class="text-primary fs-5" id="modalTotalPrice">₱0.00</strong>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0">
                <div class="d-flex gap-2 w-100">
                    <button type="button" class="btn btn-primary flex-fill" id="modalAddToCart">
                        <i class="fas fa-cart-plus me-2"></i>Add to Cart
                    </button>
                    <button type="button" class="btn btn-buy-now flex-fill" id="modalBuyNow">
                        <i class="fas fa-bolt me-2"></i>Buy Now
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.add-on-item {
    padding: 12px;
    margin-bottom: 10px;
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.add-on-item:hover {
    border-color: #007bff;
    background-color: #f8f9fa;
}

.add-on-item.selected {
    border-color: #28a745;
    background-color: #d4edda;
}

.add-on-item .fa-check-circle {
    color: #28a745;
    font-size: 1.2em;
}
</style>

<script>
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
                            <span class="text-primary fw-bold">+₱${parseFloat(addon.price).toFixed(2)}</span>
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