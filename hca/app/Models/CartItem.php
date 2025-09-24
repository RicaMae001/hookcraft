<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $table = 'cart_item';
    
    // Include category_id since it exists in your database
    protected $fillable = [
        'cart_id', 
        'product_id', 
        'category_id',  // ✅ Added back since it exists in DB
        'quantity', 
        'price', 
        'subtotal'
    ];
    
    protected $casts = [
        'quantity' => 'integer',
        'price' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];
    
    public $timestamps = false; // No created_at / updated_at in cart_item

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Direct relationship since category_id exists in cart_item table
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    
    // You can still access category through product if needed
    public function productCategory()
    {
        return $this->hasOneThrough(Category::class, Product::class, 'id', 'id', 'product_id', 'category_id');
    }

    // Auto-calculate subtotal when saving
    protected static function boot()
    {
        parent::boot();
        
        static::saving(function ($cartItem) {
            $cartItem->subtotal = $cartItem->quantity * $cartItem->price;
        });
    }

    // Helper method to update quantity
    public function updateQuantity($newQuantity)
    {
        $this->quantity = max(1, (int) $newQuantity);
        $this->subtotal = $this->quantity * $this->price;
        return $this->save();
    }
}