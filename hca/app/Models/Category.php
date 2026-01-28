<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';
    
    protected $fillable = [
        'name',
        'limited_edition',
    ];

    protected $casts = [
        'limited_edition' => 'boolean',
    ];

    public $timestamps = false;

    /**
     * Get all products in this category
     */
    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }

    /**
     * Get only available products in this category
     */
    public function availableProducts()
    {
        return $this->hasMany(Product::class, 'category_id')
                    ->where('is_available', 1)
                    ->orderBy('created_at', 'asc');
    }

    /**
     * Get the first available product (for customization reference)
     */
    public function getFirstProductAttribute()
    {
        return $this->availableProducts()->first();
    }

    /**
     * Check if category has any available products
     */
    public function hasAvailableProducts()
    {
        return $this->availableProducts()->exists();
    }

    /**
     * Scope to get only categories with available products
     */
    public function scopeWithAvailableProducts($query)
    {
        return $query->whereHas('products', function($q) {
            $q->where('is_available', 1);
        });
    }
}