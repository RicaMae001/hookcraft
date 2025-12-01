<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomizationOption extends Model
{
    protected $table = 'customization_options';

    public $timestamps = false;

    protected $fillable = [
        'customization_id',
        'option_type',
        'option_value',
        'additional_price',
    ];

    protected $casts = [
        'additional_price' => 'decimal:2',
    ];

    // Relationships
    public function customization()
    {
        return $this->belongsTo(ProductCustomization::class, 'customization_id');
    }
}