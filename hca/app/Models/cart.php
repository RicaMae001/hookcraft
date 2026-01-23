<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $table = 'cart';
    
    // ✅ FIXED: Add is_buy_now to fillable array
    protected $fillable = [
        'user_id',
        'is_buy_now'  // ← This was missing!
    ];
    
    public $timestamps = false; // ✅ No updated_at column

    // ✅ Cast is_buy_now to boolean/integer
    protected $casts = [
        'is_buy_now' => 'integer',
    ];

    // ✅ Set default attributes
    protected $attributes = [
        'is_buy_now' => 0,
    ];

    public function items()
    {
        return $this->hasMany(CartItem::class, 'cart_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
}