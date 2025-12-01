<?php

// ============================================
// app/Models/ProductCustomization.php
// ============================================

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductCustomization extends Model
{
    protected $table = 'product_customizations';

    protected $fillable = [
        'user_id',
        'order_id',
        'product_id',
        'customization_name',
        'customization_details',
        'special_instructions',
        'custom_image',
        'total_price',
        'status',
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function options()
    {
        return $this->hasMany(CustomizationOption::class, 'customization_id');
    }

    // Accessor: Get total price including product base price
    public function getTotalWithProductAttribute()
    {
        return $this->product->price + $this->total_price;
    }
}