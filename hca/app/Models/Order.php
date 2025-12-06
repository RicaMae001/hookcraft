<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';
    protected $fillable = [
        'user_id',
        'customer_name',
        'address',
        'phone',
        'total',
        'payment_status',
        'payment_method', // <-- This must be here!
    ];
    public $timestamps = false;   // <--- ADD THIS
    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function customizations()
{
    return $this->hasMany(ProductCustomization::class);
}
}
