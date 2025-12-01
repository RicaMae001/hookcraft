<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomizableProduct extends Model
{
    protected $table = 'customizable_products';

    public $timestamps = false;

    protected $fillable = [
        'product_id',
        'is_customizable',
        'customization_description',
        'min_customization_price',
        'max_customization_price',
    ];

    protected $casts = [
        'is_customizable' => 'boolean',
        'min_customization_price' => 'decimal:2',
        'max_customization_price' => 'decimal:2',
    ];

    // Relationships
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
