<?php

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
        'admin_price',
        'admin_notes',
        'admin_id',
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
        'admin_price' => 'decimal:2',
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

    public function admin()
    {
        return $this->belongsTo(\App\Models\Admin::class, 'admin_id');
    }
    
    // ✅ NEW: Relationship to cart items
    public function cartItems()
    {
        return $this->hasMany(CartItem::class, 'customization_id');
    }

    // Status methods
    public function isPending()
    {
        return $this->status === 'Pending';
    }

    public function isApproved()
    {
        return $this->status === 'Approved';
    }

    public function isRejected()
    {
        return $this->status === 'Rejected';
    }

    public function getFinalPriceAttribute()
    {
        return $this->admin_price ?? $this->total_price;
    }

    public function canCheckout()
    {
        return $this->isApproved() && !$this->order_id && $this->admin_price;
    }
    
    // ✅ NEW: Check if in cart
    public function isInCart()
    {
        return $this->cartItems()->exists();
    }
}