<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';
    public $timestamps = false;

    protected $fillable = [
        'category_id',
        'name',
        'price',
        'image',
        'stock',
        'description',
        'admin_id',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'stock' => 'integer',
    ];

    // Relationships
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'product_id');
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class, 'product_id');
    }

    public function customizations()
    {
        return $this->hasMany(ProductCustomization::class, 'product_id');
    }

    // Scopes
    public function scopeInStock($query)
    {
        return $query->where('stock', '>', 0);
    }

    public function scopeOutOfStock($query)
    {
        return $query->where('stock', '<=', 0);
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    // Accessors
    public function getFormattedPriceAttribute()
    {
        return '₱' . number_format($this->price, 2);
    }

    // Updated to match your blade file paths
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            // Check if image exists in asset/images directory
            $path = public_path('asset/images/' . $this->image);
            if (file_exists($path)) {
                return asset('asset/images/' . $this->image);
            }
            
            // Fallback to storage if not in asset/images
            $storagePath = public_path('storage/products/' . $this->image);
            if (file_exists($storagePath)) {
                return asset('storage/products/' . $this->image);
            }
        }
        
        return asset('asset/images/no-image.png');
    }

    // Helper Methods
    public function isInStock()
    {
        return $this->stock > 0;
    }

    public function isOutOfStock()
    {
        return $this->stock <= 0;
    }

    public function reduceStock($quantity)
    {
        if ($this->stock >= $quantity) {
            $this->decrement('stock', $quantity);
            return true;
        }
        return false;
    }

    public function increaseStock($quantity)
    {
        $this->increment('stock', $quantity);
    }

    // Add category name accessor for convenience
    public function getCategoryNameAttribute()
    {
        return $this->category ? $this->category->name : 'Uncategorized';
    }
}