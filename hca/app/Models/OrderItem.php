<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $table = 'order_item';

    // Fillable fields match your database schema
    protected $fillable = [
        'order_id',
        'product_id',
        'category_id',
        'quantity',
        'price',
    ];

    // Disable timestamps since your table has no created_at / updated_at
    public $timestamps = false;

    /**
     * Relationships
     */

    // An order item belongs to an order
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    // An order item belongs to a product
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    // An order item belongs to a category (optional, but useful since you store category_id)
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
