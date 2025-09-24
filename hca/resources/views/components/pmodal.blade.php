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
                        <div class="stock-info">
                            <div class="d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-boxes me-2"></i>Stock Available:</span>
                                <span id="modalProductStock" class="fw-bold"></span>
                            </div>
                        </div>

                        <!-- Quantity Selector -->
                        <div class="mb-3">
                            <label class="form-label"><i class="fas fa-sort-numeric-up me-2"></i>Quantity</label>
                            <div class="quantity-input-group d-flex">
                                <button type="button" class="quantity-btn" onclick="changeQuantity(-1)">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <input type="number" class="form-control quantity-input" id="modalQuantity" value="1" min="1" readonly>
                                <button type="button" class="quantity-btn" onclick="changeQuantity(1)">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Add-ons Section -->
                        <div class="add-ons-section" id="addOnsSection">
                            <h6><i class="fas fa-plus-circle me-2"></i>Add-ons (Optional)</h6>
                            <div id="addOnsList">
                                <!-- Example add-ons (make these dynamic later) -->
                                <div class="add-on-item" data-addon-id="1" data-addon-price="25" onclick="toggleAddOn(this)">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong>Gift Wrapping</strong>
                                            <small class="text-muted d-block">Beautiful gift wrap with ribbon</small>
                                        </div>
                                        <span class="text-primary fw-bold">+₱25</span>
                                    </div>
                                </div>
                                <div class="add-on-item" data-addon-id="2" data-addon-price="15" onclick="toggleAddOn(this)">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong>Express Shipping</strong>
                                            <small class="text-muted d-block">Delivered within 1-2 days</small>
                                        </div>
                                        <span class="text-primary fw-bold">+₱15</span>
                                    </div>
                                </div>
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

<script>
    let currentProduct = null;
    let selectedAddOns = [];
    let basePrice = 0;

    function openProductModal(productElement) {
        currentProduct = {
            id: productElement.dataset.productId,
            name: productElement.dataset.productName,
            image: productElement.dataset.productImage,
            description: productElement.dataset.productDescription,
            price: parseFloat(productElement.dataset.price),
            stock: parseInt(productElement.dataset.productStock),
            category: productElement.dataset.productCategoryName
        };

        selectedAddOns = [];
        basePrice = currentProduct.price;

        document.getElementById('modalProductName').textContent = currentProduct.name;
        document.getElementById('modalProductImage').src = currentProduct.image;
        document.getElementById('modalProductPrice').textContent = `₱${currentProduct.price.toFixed(2)}`;
        document.getElementById('modalProductCategory').textContent = currentProduct.category;
        document.getElementById('modalProductDescription').textContent = currentProduct.description;
        document.getElementById('modalProductStock').textContent = `${currentProduct.stock} pieces`;
        document.getElementById('modalQuantity').value = 1;

        updateTotalPrice();
        new bootstrap.Modal(document.getElementById('productModal')).show();
    }

    function changeQuantity(delta) {
        const input = document.getElementById('modalQuantity');
        let qty = parseInt(input.value);
        qty = Math.max(1, Math.min(currentProduct.stock, qty + delta));
        input.value = qty;
        updateTotalPrice();
    }

    function toggleAddOn(addOnElement) {
        const id = addOnElement.dataset.addonId;
        const price = parseFloat(addOnElement.dataset.addonPrice);

        if (addOnElement.classList.contains('selected')) {
            addOnElement.classList.remove('selected');
            selectedAddOns = selectedAddOns.filter(a => a.id !== id);
        } else {
            addOnElement.classList.add('selected');
            selectedAddOns.push({ id, price });
        }
        updateTotalPrice();
    }

    function updateTotalPrice() {
        const qty = parseInt(document.getElementById('modalQuantity').value);
        const addOnsTotal = selectedAddOns.reduce((sum, a) => sum + a.price, 0);
        const total = (basePrice * qty) + addOnsTotal;
        document.getElementById('modalTotalPrice').textContent = `₱${total.toFixed(2)}`;
    }

    // ✅ Real Add to Cart (AJAX)
    function modalAddToCart() {
        const qty = parseInt(document.getElementById('modalQuantity').value);

        fetch("{{ route('cart.add') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                product_id: currentProduct.id,
                quantity: qty
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                updateCartCount(data.cart_count);
                showToast("Added to cart: " + currentProduct.name);
            } else {
                showToast(data.message, true);
            }
        })
        .catch(() => showToast("Error adding to cart", true));
    }

    // ✅ Buy Now (add item then go to checkout)
    function modalBuyNow() {
        const qty = parseInt(document.getElementById('modalQuantity').value);

        fetch("{{ route('cart.add') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                product_id: currentProduct.id,
                quantity: qty
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                window.location.href = "{{ route('checkout.index') }}";
            } else {
                showToast(data.message, true);
            }
        })
        .catch(() => showToast("Error processing order", true));
    }

    function updateCartCount(newCount) {
        const badge = document.getElementById('cartCount');
        if (badge) {
            badge.textContent = newCount;
            badge.style.animation = "pulse 0.6s";
        }
    }

    function showToast(message, isError = false) {
        const toastEl = document.getElementById(isError ? "errorToast" : "cartToast");
        toastEl.querySelector("span").textContent = message;
        new bootstrap.Toast(toastEl).show();
    }

    document.addEventListener("DOMContentLoaded", () => {
        document.getElementById("modalAddToCart").addEventListener("click", modalAddToCart);
        document.getElementById("modalBuyNow").addEventListener("click", modalBuyNow);
    });
</script>
