<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Management - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .btn-pink {
            background-color: #FFB6C1;
            color: white;
            border: none;
        }
        .btn-pink:hover {
            background-color: #FF9EAD;
            color: white;
        }
        .bg-pink {
            background-color: #FFB6C1;
        }
        .text-pink {
            color: #FFB6C1;
        }
        .card {
            border: none;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            border-radius: 10px;
        }
        .table thead th {
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
        }
        .product-img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
            border: 2px solid #f0f0f0;
        }
        main {
            margin-left: 0;
        }
        @media (min-width: 768px) {
            main {
                margin-left: 250px;
            }
        }
    </style>
</head>
<body>
    @include('admin.layouts.navbar')

    <div class="container-fluid">
        <div class="row">
            @include('admin.layouts.sidebar')

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-4 border-bottom">
                    <h1 class="h2"><i class="fas fa-box text-pink me-2"></i>Product Management</h1>
                    <div>
                        <button class="btn btn-info me-2" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                            <i class="fas fa-folder-plus me-2"></i>Add Category
                        </button>
                        <button class="btn btn-secondary me-2" data-bs-toggle="modal" data-bs-target="#manageCategoriesModal">
                            <i class="fas fa-cog me-2"></i>Manage Categories
                        </button>
                        <button class="btn btn-pink px-4" data-bs-toggle="modal" data-bs-target="#addProductModal">
                            <i class="fas fa-plus-circle me-2"></i>Add New Product
                        </button>
                    </div>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="row mb-3">
                    <div class="col-md-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <i class="fas fa-boxes fa-2x text-pink mb-2"></i>
                                <h5 class="mb-0">{{ $products->count() }}</h5>
                                <small class="text-muted">Total Products</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <i class="fas fa-layer-group fa-2x text-info mb-2"></i>
                                <h5 class="mb-0">{{ $categories->count() }}</h5>
                                <small class="text-muted">Categories</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                                <h5 class="mb-0">{{ $products->where('stock', '>', 0)->count() }}</h5>
                                <small class="text-muted">In Stock</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <i class="fas fa-times-circle fa-2x text-danger mb-2"></i>
                                <h5 class="mb-0">{{ $products->where('stock', '<=', 0)->count() }}</h5>
                                <small class="text-muted">Out of Stock</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header bg-white py-3">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <h5 class="mb-0"><i class="fas fa-list me-2"></i>Product List</h5>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-group-text bg-white"><i class="fas fa-search"></i></span>
                                    <input type="text" id="searchProduct" class="form-control border-start-0" placeholder="Search products...">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0" id="productsTable">
                                <thead>
                                    <tr>
                                        <th style="width: 100px;">Image</th>
                                        <th style="width: 80px;">ID</th>
                                        <th>Product Name</th>
                                        <th>Category</th>
                                        <th>Price</th>
                                        <th>Stock</th>
                                        <th style="width: 150px;" class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($products as $product)
                                        <tr>
                                            <td>
                                                <img src="{{ asset('asset/images/' . $product->image) }}" alt="{{ $product->name }}" class="product-img">
                                            </td>
                                            <td><span class="badge bg-secondary">#{{ $product->id }}</span></td>
                                            <td><strong>{{ $product->name }}</strong></td>
                                            <td><span class="badge bg-info">{{ $product->category_name }}</span></td>
                                            <td><strong class="text-success">₱{{ number_format($product->price, 2) }}</strong></td>
                                            <td>
                                                @if($product->stock > 10)
                                                    <span class="badge bg-success">{{ $product->stock }}</span>
                                                @elseif($product->stock > 0)
                                                    <span class="badge bg-warning text-dark">{{ $product->stock }}</span>
                                                @else
                                                    <span class="badge bg-danger">Out of Stock</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group" role="group">
                                                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal{{ $product->id }}" title="Edit Product">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-danger"
                                                            onclick="confirmDelete({{ $product->id }}, '{{ $product->name }}', '{{ route('admin.products.destroy', $product->id) }}')"
                                                            title="Delete Product">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>

                                        <!-- Edit Product Modal -->
                                        <div class="modal fade" id="editModal{{ $product->id }}" tabindex="-1">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <form method="POST" action="{{ route('admin.products.update', $product->id) }}" enctype="multipart/form-data">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-header bg-pink text-white">
                                                            <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Edit Product</h5>
                                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="col-md-6 mb-3">
                                                                    <label class="form-label"><i class="fas fa-tag me-2"></i>Product Name *</label>
                                                                    <input type="text" name="name" class="form-control" value="{{ $product->name }}" required>
                                                                </div>
                                                                <div class="col-md-6 mb-3">
                                                                    <label class="form-label"><i class="fas fa-folder me-2"></i>Category *</label>
                                                                    <select name="category_id" class="form-select" required>
                                                                        @foreach($categories as $category)
                                                                            <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
                                                                                {{ $category->name }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-6 mb-3">
                                                                    <label class="form-label"><i class="fas fa-peso-sign me-2"></i>Price *</label>
                                                                    <input type="number" name="price" class="form-control" value="{{ $product->price }}" step="0.01" required>
                                                                </div>
                                                                <div class="col-md-6 mb-3">
                                                                    <label class="form-label"><i class="fas fa-warehouse me-2"></i>Stock *</label>
                                                                    <input type="number" name="stock" class="form-control" value="{{ $product->stock }}" required>
                                                                </div>
                                                                <div class="col-12 mb-3">
                                                                    <label class="form-label"><i class="fas fa-align-left me-2"></i>Description</label>
                                                                    <textarea name="description" class="form-control" rows="3">{{ $product->description }}</textarea>
                                                                </div>
                                                                <div class="col-12 mb-3">
                                                                    <label class="form-label"><i class="fas fa-image me-2"></i>Product Image</label>
                                                                    <input type="file" name="image" class="form-control" accept="image/*">
                                                                    <small class="text-muted">Leave empty to keep current image</small>
                                                                    <div class="mt-3 text-center">
                                                                        <img src="{{ asset('asset/images/' . $product->image) }}" alt="Current" class="rounded border" style="max-height: 150px;">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn btn-pink"><i class="fas fa-save me-2"></i>Update Product</button>
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
            </main>
        </div>
    </div>

    <!-- Manage Categories Modal -->
    <div class="modal fade" id="manageCategoriesModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-secondary text-white">
                    <h5 class="modal-title"><i class="fas fa-cog me-2"></i>Manage Categories</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
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
                                        <td><span class="badge bg-secondary">#{{ $category->id }}</span></td>
                                        <td><strong>{{ $category->name }}</strong></td>
                                        <td>{{ $category->description ?? 'No description' }}</td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                <button class="btn btn-sm btn-warning" onclick="openEditCategory({{ $category->id }})" title="Edit Category">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger"
                                                        onclick="confirmDeleteCategory({{ $category->id }}, '{{ $category->name }}', '{{ route('admin.categories.delete', $category->id) }}')"
                                                        title="Delete Category">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Category Modals (Outside Manage Modal) -->
    @foreach($categories as $category)
        <div class="modal fade" id="editCategoryModal{{ $category->id }}" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="POST" action="{{ route('admin.categories.update', $category->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="modal-header bg-warning text-white">
                            <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Edit Category</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label"><i class="fas fa-tag me-2"></i>Category Name *</label>
                                <input type="text" name="name" class="form-control" value="{{ $category->name }}" required>
                            </div>
                            <div class="mb-3">
                              
                                
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-warning"><i class="fas fa-save me-2"></i>Update Category</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

    <!-- Delete Category Confirmation Modal -->
    <div class="modal fade" id="deleteCategoryModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i>Confirm Delete</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <i class="fas fa-folder-open fa-4x text-danger mb-3"></i>
                    <h5>Delete <strong id="deleteCategoryName"></strong>?</h5>
                    <p class="text-muted">This will also affect products in this category!</p>
                </div>
                <div class="modal-footer justify-content-center">
                    <form id="deleteCategoryForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger px-4">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Category Modal -->
    <div class="modal fade" id="addCategoryModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route('admin.categories.store') }}">
                    @csrf
                    <div class="modal-header bg-info text-white">
                        <h5 class="modal-title"><i class="fas fa-folder-plus me-2"></i>Add New Category</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label"><i class="fas fa-tag me-2"></i>Category Name *</label>
                            <input type="text" name="name" class="form-control" placeholder="Enter category name" required>
                        </div>
                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-info"><i class="fas fa-plus me-2"></i>Add Category</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Product Modal -->
    <div class="modal fade" id="addProductModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header bg-pink text-white">
                        <h5 class="modal-title"><i class="fas fa-plus-circle me-2"></i>Add New Product</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><i class="fas fa-tag me-2"></i>Product Name *</label>
                                <input type="text" name="name" class="form-control" placeholder="Enter product name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><i class="fas fa-folder me-2"></i>Category *</label>
                                <select name="category_id" class="form-select" required>
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><i class="fas fa-peso-sign me-2"></i>Price *</label>
                                <input type="number" name="price" class="form-control" placeholder="0.00" step="0.01" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><i class="fas fa-warehouse me-2"></i>Stock Quantity *</label>
                                <input type="number" name="stock" class="form-control" placeholder="0" required>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label"><i class="fas fa-align-left me-2"></i>Description</label>
                                <textarea name="description" class="form-control" rows="3" placeholder="Enter product description"></textarea>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label"><i class="fas fa-image me-2"></i>Product Image *</label>
                                <input type="file" name="image" class="form-control" accept="image/*" required>
                                <small class="text-muted">Accepted formats: JPG, PNG, GIF (Max: 2MB)</small>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-pink"><i class="fas fa-plus me-2"></i>Add Product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i>Confirm Delete</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <i class="fas fa-box-open fa-4x text-danger mb-3"></i>
                    <h5>Delete <strong id="deleteProductName"></strong>?</h5>
                    <p class="text-muted">This action cannot be undone!</p>
                </div>
                <div class="modal-footer justify-content-center">
                    <form id="deleteForm" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger px-4">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function confirmDelete(productId, productName, deleteUrl) {
            document.getElementById('deleteProductName').textContent = productName;
            document.getElementById('deleteForm').action = deleteUrl;
            new bootstrap.Modal(document.getElementById('deleteModal')).show();
        }

        function confirmDeleteCategory(categoryId, categoryName, deleteUrl) {
            document.getElementById('deleteCategoryName').textContent = categoryName;
            document.getElementById('deleteCategoryForm').action = deleteUrl;
            new bootstrap.Modal(document.getElementById('deleteCategoryModal')).show();
        }

        function openEditCategory(categoryId) {
            // Close the manage categories modal first
            const manageCategoriesModal = bootstrap.Modal.getInstance(document.getElementById('manageCategoriesModal'));
            manageCategoriesModal.hide();
            
            // Wait for the modal to close, then open the edit modal
            setTimeout(function() {
                new bootstrap.Modal(document.getElementById('editCategoryModal' + categoryId)).show();
            }, 300);
        }

        document.getElementById('searchProduct').addEventListener('keyup', function() {
            const searchValue = this.value.toLowerCase();
            const tableRows = document.querySelectorAll('#productsTable tbody tr');
            
            tableRows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchValue) ? '' : 'none';
            });
        });
    </script>
</body>
</html>