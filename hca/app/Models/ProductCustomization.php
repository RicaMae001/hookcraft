<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCustomization extends Model
{
    use HasFactory;

    protected $table = 'product_customizations';

    protected $fillable = [
        'user_id',
        'product_id',
        'customization_name',
        'customization_details',
        'special_instructions',
        'custom_image',
        'total_price',
        'admin_price',
        'price_breakdown',   // ← NEW: JSON array of line items
        'status',
        'admin_notes',
        'admin_id',
        'order_id',
    ];

    protected $casts = [
        'total_price'      => 'decimal:2',
        'admin_price'      => 'decimal:2',
        'price_breakdown'  => 'array',   // ← auto encode/decode JSON
    ];

    // ==================== RELATIONSHIPS ====================

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function admin()
    {
        return $this->belongsTo(\App\Models\Admin::class, 'admin_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function options()
    {
        return $this->hasMany(CustomizationOption::class, 'customization_id');
    }

    // ==================== PRICE BREAKDOWN HELPERS ====================

    /**
     * Calculate total from breakdown rows.
     * Each row: ['label' => string, 'quantity' => float, 'unit_price' => float, 'subtotal' => float]
     */
    public function calculateBreakdownTotal(): float
    {
        if (empty($this->price_breakdown)) {
            return 0;
        }

        return collect($this->price_breakdown)->sum(function ($row) {
            return (float) ($row['subtotal'] ?? ($row['quantity'] * $row['unit_price']));
        });
    }

    // ==================== STATUS HELPERS ====================

    public function isPending()   { return $this->status === 'Pending'; }
    public function isApproved()  { return $this->status === 'Approved'; }
    public function isRejected()  { return $this->status === 'Rejected'; }
    public function isCompleted() { return $this->status === 'Completed'; }

    public function canCheckout()
    {
        return $this->isApproved() && $this->admin_price > 0 && !$this->order_id;
    }

    public function canEdit()
    {
        return $this->isPending() && !$this->order_id;
    }

    // ==================== SCOPES ====================

    public function scopePending($query)   { return $query->where('status', 'Pending'); }
    public function scopeApproved($query)  { return $query->where('status', 'Approved'); }
    public function scopeRejected($query)  { return $query->where('status', 'Rejected'); }
    public function scopeCompleted($query) { return $query->where('status', 'Completed'); }
    public function scopeForUser($query, $userId) { return $query->where('user_id', $userId); }

    // ==================== ACCESSORS ====================

    public function getImageUrlAttribute()
    {
        if ($this->custom_image) {
            return asset('uploads/customizations/' . $this->custom_image);
        }
        if ($this->product && $this->product->image) {
            return asset('uploads/products/' . $this->product->image);
        }
        return asset('images/placeholder.jpg');
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'Pending'   => 'warning',
            'Approved'  => 'success',
            'Rejected'  => 'danger',
            'Completed' => 'info',
            default     => 'secondary'
        };
    }

    public function getFormattedPriceAttribute()
    {
        if ($this->admin_price) {
            return '₱' . number_format($this->admin_price, 2);
        }
        if ($this->total_price) {
            return '₱' . number_format($this->total_price, 2);
        }
        return 'Price not set';
    }
}