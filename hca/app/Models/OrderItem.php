<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'order_item';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'order_id',
        'category_id',
        'product_id',
        'quantity',
        'price',
        'is_customization',    // NEW: Flag for customized products
        'customization_id',    // NEW: Reference to product_customizations
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'price' => 'decimal:2',
        'quantity' => 'integer',
        'is_customization' => 'boolean', // Add casting for the new field
    ];

    /**
     * Get the order that owns the order item.
     */
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    /**
     * Get the product associated with this order item.
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    /**
     * Get the category associated with this order item.
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * NEW: Get the customization associated with this order item.
     */
    public function customization()
    {
        return $this->belongsTo(ProductCustomization::class, 'customization_id');
    }

    /**
     * Get the subtotal for this order item.
     */
    public function getSubtotalAttribute()
    {
        return $this->price * $this->quantity;
    }

    /**
     * Get the formatted price.
     */
    public function getFormattedPriceAttribute()
    {
        return 'â‚±' . number_format($this->price, 2);
    }

    /**
     * Get the formatted subtotal.
     */
    public function getFormattedSubtotalAttribute()
    {
        return 'â‚±' . number_format($this->subtotal, 2);
    }

    /**
     * Helper to check if item is a customization
     */
    public function isCustomized()
    {
        return $this->is_customization && $this->customization_id;
    }
}