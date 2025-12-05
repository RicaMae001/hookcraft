<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GalleryImage extends Model
{
    protected $table = 'gallery_images';

    protected $fillable = [
        'title',
        'description',
        'image_path',
        'category_id', // ONLY category_id now
        'display_order',
        'is_active',
        'admin_id'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'display_order' => 'integer',
    ];

    // Relationship with admin
    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    // Relationship with category
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    // Scope for active images
    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    // Scope for ordering
    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order', 'asc');
    }

    // Get full image URL
    public function getImageUrlAttribute()
    {
        return asset('asset/images/' . $this->image_path);
    }

    // Get category name (convenience method)
    public function getCategoryNameAttribute()
    {
        return $this->category ? $this->category->name : null;
    }
}