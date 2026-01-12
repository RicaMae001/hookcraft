@extends('admin.layouts.admin')

@section('title', 'Products Management')

@section('content')
<div class="animate-fade-in">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 style="font-family: 'Playfair Display', serif; font-size: 2.25rem; font-weight: 700; margin-bottom: 0.5rem;">
                Product Management
            </h1>
            <p style="color: var(--text-secondary); margin: 0;">Manage your inventory and product catalog</p>
        </div>
        
        <div class="d-flex gap-2">
            <button class="btn btn-modern-secondary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                <i class="fas fa-folder-plus me-2"></i>Add Category
            </button>
            <button class="btn btn-modern-secondary" data-bs-toggle="modal" data-bs-target="#manageCategoriesModal">
                <i class="fas fa-cog me-2"></i>Manage Categories
            </button>
            <button class="btn btn-modern-primary" data-bs-toggle="modal" data-bs-target="#addProductModal">
                <i class="fas fa-plus-circle me-2"></i>Add Product
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" style="border-radius: 12px; border-left: 4px solid var(--success);">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" style="border-radius: 12px; border-left: 4px solid var(--danger);">
            <i class="fas fa-exclamation-circle me-2"></i>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-card-icon primary">
                    <i class="fas fa-boxes"></i>
                </div>
                <div class="stat-card-label">Total Products</div>
                <div class="stat-card-value">{{ $products->count() }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-card-icon info">
                    <i class="fas fa-layer-group"></i>
                </div>
                <div class="stat-card-label">Categories</div>
                <div class="stat-card-value">{{ $categories->count() }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-card-icon success">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-card-label">In Stock</div>
                <div class="stat-card-value">{{ $products->where('stock', '>', 0)->count() }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-card-icon" style="background: linear-gradient(135deg, rgba(252, 129, 129, 0.15), rgba(252, 129, 129, 0.05)); color: var(--danger);">
                    <i class="fas fa-times-circle"></i>
                </div>
                <div class="stat-card-label">Out of Stock</div>
                <div class="stat-card-value">{{ $products->where('stock', '<=', 0)->count() }}</div>
            </div>
        </div>
    </div>

    <!-- Products Table -->
    <div class="content-card">
        <div class="content-card-header">
            <h3 class="content-card-title">Product List</h3>
            <div class="input-group" style="max-width: 300px;">
                <span class="input-group-text" style="background: var(--light-bg); border-right: none;">
                    <i class="fas fa-search"></i>
                </span>
                <input type="text" id="searchProduct" class="form-control" placeholder="Search products..." style="border-left: none;">
            </div>
        </div>
        <div class="table-responsive">
            <table class="modern-table" id="productsTable">
                <thead>
                    <tr>
                        <th style="width: 80px;">Image</th>
                        <th>ID</th>
                        <th>Product Name</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th class="text-center" style="width: 150px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                        <tr>
                            <td>
                                <img src="{{ asset('asset/images/' . $product->image) }}" 
                                     alt="{{ $product->name }}" 
                                     style="width: 60px; height: 60px; object-fit: cover; border-radius: 12px; border: 2px solid var(--border-color);">
                            </td>
                            <td>
                                <span style="font-weight: 700; color: var(--text-secondary);">#{{ $product->id }}</span>
                            </td>
                            <td>
                                <div style="font-weight: 600;">{{ $product->name }}</div>
                            </td>
                            <td>
                                <span class="badge-modern badge-info">{{ $product->category_name }}</span>
                            </td>
                            <td>
                                <span style="font-weight: 700; color: var(--success);">₱{{ number_format($product->price, 2) }}</span>
                            </td>
                            <td>
                                @if($product->stock > 10)
                                    <span class="badge-modern badge-success">{{ $product->stock }} units</span>
                                @elseif($product->stock > 0)
                                    <span class="badge-modern badge-warning">{{ $product->stock }} units</span>
                                @else
                                    <span class="badge-modern badge-danger"><i class="fas fa-times-circle"></i>Out of Stock</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-2 justify-content-center">
                                    <button class="btn btn-sm" style="background: var(--light-bg); border-radius: 8px; padding: 0.5rem 0.75rem;" 
                                            data-bs-toggle="modal" data-bs-target="#editModal{{ $product->id }}" title="Edit Product">
                                        <i class="fas fa-edit" style="color: var(--warning);"></i>
                                    </button>
                                    <button class="btn btn-sm" style="background: var(--light-bg); border-radius: 8px; padding: 0.5rem 0.75rem;"
                                            onclick="confirmDelete({{ $product->id }}, '{{ $product->name }}', '{{ route('admin.products.destroy', $product->id) }}')" title="Delete">
                                        <i class="fas fa-trash" style="color: var(--danger);"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <!-- Edit Product Modal -->
                        <div class="modal fade" id="editModal{{ $product->id }}" tabindex="-1">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content" style="border-radius: 16px; border: none;">
                                    <form method="POST" action="{{ route('admin.products.update', $product->id) }}" enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-header" style="background: linear-gradient(135deg, var(--warning), #F6C176); color: white; border-radius: 16px 16px 0 0;">
                                            <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Edit Product</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body p-4">
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label class="form-label" style="font-weight: 600;"><i class="fas fa-tag me-2"></i>Product Name *</label>
                                                    <input type="text" name="name" class="form-control" value="{{ $product->name }}" style="border-radius: 12px;" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label" style="font-weight: 600;"><i class="fas fa-folder me-2"></i>Category *</label>
                                                    <select name="category_id" class="form-select" style="border-radius: 12px;" required>
                                                        @foreach($categories as $category)
                                                            <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
                                                                {{ $category->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label" style="font-weight: 600;"><i class="fas fa-peso-sign me-2"></i>Price *</label>
                                                    <input type="number" name="price" class="form-control" value="{{ $product->price }}" step="0.01" style="border-radius: 12px;" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label" style="font-weight: 600;"><i class="fas fa-warehouse me-2"></i>Stock *</label>
                                                    <input type="number" name="stock" class="form-control" value="{{ $product->stock }}" style="border-radius: 12px;" required>
                                                </div>
                                                <div class="col-12">
                                                    <label class="form-label" style="font-weight: 600;"><i class="fas fa-align-left me-2"></i>Description</label>
                                                    <textarea name="description" class="form-control" rows="3" style="border-radius: 12px;">{{ $product->description }}</textarea>
                                                </div>
                                                <div class="col-12">
                                                    <label class="form-label" style="font-weight: 600;"><i class="fas fa-image me-2"></i>Product Image</label>
                                                    <input type="file" name="image" class="form-control" accept="image/*" style="border-radius: 12px;">
                                                    <small style="color: var(--text-secondary);">Leave empty to keep current image</small>
                                                    <div class="mt-3 text-center">
                                                        <img src="{{ asset('asset/images/' . $product->image) }}" alt="Current" style="max-height: 150px; border-radius: 12px; border: 2px solid var(--border-color);">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer" style="border: none;">
                                            <button type="button" class="btn btn-modern-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-modern-primary">
                                                <i class="fas fa-save me-2"></i>Update Product
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Product Modal -->
<div class="modal fade" id="addProductModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="border-radius: 16px; border: none;">
            <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-header" style="background: linear-gradient(135deg, var(--primary-pink), #FF8AAE); color: white; border-radius: 16px 16px 0 0;">
                    <h5 class="modal-title"><i class="fas fa-plus-circle me-2"></i>Add New Product</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label" style="font-weight: 600;"><i class="fas fa-tag me-2"></i>Product Name *</label>
                            <input type="text" name="name" class="form-control" placeholder="Enter product name" style="border-radius: 12px;" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" style="font-weight: 600;"><i class="fas fa-folder me-2"></i>Category *</label>
                            <select name="category_id" class="form-select" style="border-radius: 12px;" required>
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" style="font-weight: 600;"><i class="fas fa-peso-sign me-2"></i>Price *</label>
                            <input type="number" name="price" class="form-control" placeholder="0.00" step="0.01" style="border-radius: 12px;" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" style="font-weight: 600;"><i class="fas fa-warehouse me-2"></i>Stock Quantity *</label>
                            <input type="number" name="stock" class="form-control" placeholder="0" style="border-radius: 12px;" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label" style="font-weight: 600;"><i class="fas fa-align-left me-2"></i>Description</label>
                            <textarea name="description" class="form-control" rows="3" placeholder="Enter product description" style="border-radius: 12px;"></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label" style="font-weight: 600;"><i class="fas fa-image me-2"></i>Product Image *</label>
                            <input type="file" name="image" class="form-control" accept="image/*" style="border-radius: 12px;" required>
                            <small style="color: var(--text-secondary);">Accepted formats: JPG, PNG, GIF (Max: 2MB)</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="border: none;">
                    <button type="button" class="btn btn-modern-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-modern-primary">
                        <i class="fas fa-plus me-2"></i>Add Product
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Category Modal -->
<div class="modal fade" id="addCategoryModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 16px; border: none;">
            <form method="POST" action="{{ route('admin.categories.store') }}">
                @csrf
                <div class="modal-header" style="background: var(--secondary); color: white; border-radius: 16px 16px 0 0;">
                    <h5 class="modal-title"><i class="fas fa-folder-plus me-2"></i>Add New Category</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label" style="font-weight: 600;"><i class="fas fa-tag me-2"></i>Category Name *</label>
                        <input type="text" name="name" class="form-control" placeholder="Enter category name" style="border-radius: 12px;" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" style="font-weight: 600;"><i class="fas fa-star me-2"></i>Limited Edition</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="limited_edition" value="1" id="limitedEditionAdd">
                            <label class="form-check-label" for="limitedEditionAdd">
                                Display as Limited Edition
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="border: none;">
                    <button type="button" class="btn btn-modern-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-modern-primary">
                        <i class="fas fa-plus me-2"></i>Add Category
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Manage Categories Modal -->
<div class="modal fade" id="manageCategoriesModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="border-radius: 16px; border: none;">
            <div class="modal-header" style="background: var(--primary-dark); color: white; border-radius: 16px 16px 0 0;">
                <h5 class="modal-title"><i class="fas fa-cog me-2"></i>Manage Categories</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <div class="table-responsive">
                    <table class="modern-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Category Name</th>
                                <th>Description</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($categories as $category)
                                <tr>
                                    <td><span style="font-weight: 700; color: var(--text-secondary);">#{{ $category->id }}</span></td>
                                    <td><strong>{{ $category->name }}</strong></td>
                                    <td style="color: var(--text-secondary);">{{ $category->description ?? 'No description' }}</td>
                                    <td>
                                        <div class="d-flex gap-2 justify-content-center">
                                            <button class="btn btn-sm" style="background: var(--light-bg); border-radius: 8px; padding: 0.5rem 0.75rem;" 
                                                    onclick="openEditCategory({{ $category->id }})">
                                                <i class="fas fa-edit" style="color: var(--warning);"></i>
                                            </button>
                                            <button class="btn btn-sm" style="background: var(--light-bg); border-radius: 8px; padding: 0.5rem 0.75rem;"
                                                    onclick="confirmDeleteCategory({{ $category->id }}, '{{ $category->name }}', '{{ route('admin.categories.delete', $category->id) }}')">
                                                <i class="fas fa-trash" style="color: var(--danger);"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer" style="border: none;">
                <button type="button" class="btn btn-modern-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Category Modals -->
@foreach($categories as $category)
    <div class="modal fade" id="editCategoryModal{{ $category->id }}" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 16px; border: none;">
                <form method="POST" action="{{ route('admin.categories.update', $category->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="modal-header" style="background: linear-gradient(135deg, var(--warning), #F6C176); color: white; border-radius: 16px 16px 0 0;">
                        <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Edit Category</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label" style="font-weight: 600;"><i class="fas fa-tag me-2"></i>Category Name *</label>
                            <input type="text" name="name" class="form-control" value="{{ $category->name }}" style="border-radius: 12px;" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" style="font-weight: 600;"><i class="fas fa-star me-2"></i>Limited Edition</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="limited_edition" value="1" id="limitedEdition{{ $category->id }}" {{ $category->limited_edition ? 'checked' : '' }}>
                                <label class="form-check-label" for="limitedEdition{{ $category->id }}">
                                    Display as Limited Edition
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer" style="border: none;">
                        <button type="button" class="btn btn-modern-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-modern-primary">
                            <i class="fas fa-save me-2"></i>Update Category
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach

<!-- Delete Product Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 16px; border: none;">
            <div class="modal-header" style="background: var(--danger); color: white; border-radius: 16px 16px 0 0;">
                <h5 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i>Confirm Delete</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-5">
                <i class="fas fa-box-open fa-4x mb-4" style="color: var(--danger);"></i>
                <h5>Delete <strong id="deleteProductName" style="color: var(--danger);"></strong>?</h5>
                <p style="color: var(--text-secondary);">This action cannot be undone!</p>
            </div>
            <div class="modal-footer justify-content-center" style="border: none;">
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-modern-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn" style="background: var(--danger); color: white; padding: 0.75rem 1.5rem; border-radius: 12px; font-weight: 600;">
                        <i class="fas fa-trash me-2"></i>Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Delete Category Modal -->
<div class="modal fade" id="deleteCategoryModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 16px; border: none;">
            <div class="modal-header" style="background: var(--danger); color: white; border-radius: 16px 16px 0 0;">
                <h5 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i>Confirm Delete</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-5">
                <i class="fas fa-folder-open fa-4x mb-4" style="color: var(--danger);"></i>
                <h5>Delete <strong id="deleteCategoryName" style="color: var(--danger);"></strong>?</h5>
                <p style="color: var(--text-secondary);">This will also affect products in this category!</p>
            </div>
            <div class="modal-footer justify-content-center" style="border: none;">
                <form id="deleteCategoryForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-modern-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn" style="background: var(--danger); color: white; padding: 0.75rem 1.5rem; border-radius: 12px; font-weight: 600;">
                        <i class="fas fa-trash me-2"></i>Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Delete Product
    function confirmDelete(productId, productName, deleteUrl) {
        document.getElementById('deleteProductName').textContent = productName;
        document.getElementById('deleteForm').action = deleteUrl;
        new bootstrap.Modal(document.getElementById('deleteModal')).show();
    }

    // Delete Category
    function confirmDeleteCategory(categoryId, categoryName, deleteUrl) {
        document.getElementById('deleteCategoryName').textContent = categoryName;
        document.getElementById('deleteCategoryForm').action = deleteUrl;
        new bootstrap.Modal(document.getElementById('deleteCategoryModal')).show();
    }

    // Open Edit Category
    function openEditCategory(categoryId) {
        const manageCategoriesModal = bootstrap.Modal.getInstance(document.getElementById('manageCategoriesModal'));
        manageCategoriesModal.hide();
        
        setTimeout(function() {
            new bootstrap.Modal(document.getElementById('editCategoryModal' + categoryId)).show();
        }, 300);
    }

    // Search Products
    document.getElementById('searchProduct').addEventListener('keyup', function() {
        const searchValue = this.value.toLowerCase();
        const tableRows = document.querySelectorAll('#productsTable tbody tr');
        
        tableRows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchValue) ? '' : 'none';
        });
    });
</script>
@endpush