<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Gallery - Admin</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: #f8f9fa;
            margin: 0;
            padding: 0;
        }
        
        /* Ensure navbar and sidebar includes are positioned correctly */
        /* The navbar should be fixed at top, sidebar fixed at left */
        
        /* Main content area with proper margins */
        .main-content {
            margin-left: 0;
            margin-top: 56px; /* Height of fixed navbar */
            padding: 20px;
            min-height: calc(100vh - 56px);
        }
        
        @media (min-width: 768px) {
            .main-content {
                margin-left: 250px; /* Width of sidebar */
                margin-top: 56px;
            }
        }
        
        /* Gallery cards */
        .gallery-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            height: 100%;
            border: none;
            border-radius: 12px;
            overflow: hidden;
        }
        
        .gallery-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }
        
        .gallery-img {
            height: 200px;
            object-fit: cover;
            width: 100%;
        }
        
        .badge-category {
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 1;
            font-size: 0.75rem;
            padding: 0.4em 0.8em;
        }
        
        .action-buttons .btn {
            padding: 5px 10px;
            font-size: 0.875rem;
        }
        
        /* Card styling */
        .card-title {
            font-weight: 600;
            margin-bottom: 0.5rem;
            font-size: 0.95rem;
        }
        
        .card-footer {
            background: white;
            border-top: 1px solid #f0f0f0;
            padding: 0.75rem;
        }
        
        /* Header styling */
        .page-header {
            margin-bottom: 2rem;
        }
        
        .page-header h2 {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 0.25rem;
        }
        
        /* Alert styling */
        .alert {
            border-radius: 10px;
            border: none;
        }
    </style>
</head>
<body>
    @include('admin.layouts.navbar')
    @include('admin.layouts.sidebar')

    <main class="main-content">
        <div class="container-fluid">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center page-header">
                <div>
                    <h2><i class="bi bi-images me-2"></i>Gallery Management</h2>
                    <p class="text-muted mb-0">Manage your gallery images</p>
                </div>
                <a href="{{ route('admin.gallery.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i>Add New Image
                </a>
            </div>

            <!-- Success Message -->
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            <!-- Gallery Grid -->
            <div class="row g-4">
                @forelse($galleryItems as $gallery)
                <div class="col-md-6 col-lg-4 col-xl-3">
                    <div class="card gallery-card">
                        <div class="position-relative">
                            <span class="badge bg-primary badge-category">{{ $gallery->category_name }}</span>
                            <img src="{{ asset('asset/images/' . $gallery->image_path) }}" 
                                 class="gallery-img" alt="{{ $gallery->title }}">
                        </div>
                        <div class="card-body">
                            <h6 class="card-title">{{ $gallery->title }}</h6>
                            <p class="card-text text-muted small">
                                {{ Str::limit($gallery->description, 60) }}
                            </p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge {{ $gallery->is_active ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $gallery->is_active ? 'Active' : 'Inactive' }}
                                </span>
                                <small class="text-muted">Order: {{ $gallery->display_order }}</small>
                            </div>
                        </div>
                        <div class="card-footer action-buttons">
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.gallery.edit', $gallery->id) }}" 
                                   class="btn btn-sm btn-outline-primary flex-fill">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                                <form action="{{ route('admin.gallery.toggle-status', $gallery->id) }}" 
                                      method="POST" class="flex-fill">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-outline-warning w-100">
                                        <i class="bi bi-toggle-{{ $gallery->is_active ? 'on' : 'off' }}"></i>
                                    </button>
                                </form>
                                <form action="{{ route('admin.gallery.destroy', $gallery->id) }}" 
                                      method="POST" 
                                      onsubmit="return confirm('Are you sure you want to delete this image?');"
                                      class="flex-fill">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger w-100">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12">
                    <div class="alert alert-info text-center">
                        <i class="bi bi-info-circle me-2"></i>No gallery images found. Add your first image!
                    </div>
                </div>
                @endforelse
            </div>

            <!-- Total Items Count -->
            @if($galleryItems->count() > 0)
            <div class="mt-4 text-center text-muted">
                <small>Showing {{ $galleryItems->count() }} gallery items</small>
            </div>
            @endif
        </div>
    </main>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>