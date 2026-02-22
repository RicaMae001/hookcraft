<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCustomization extends Model
{
    use HasFactory;

    protected $table = 'product_customizations'; // Explicitly define table name
    
    protected $fillable = [
        'user_id',
        'product_id',
        'customization_name',
        'customization_details',
        'special_instructions',
        'custom_image',
        'total_price',
        'admin_price',
        'status',
        'admin_notes',
        'admin_id',
        'order_id',
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
        'admin_price' => 'decimal:2',
    ];

    // ==================== RELATIONSHIPS ====================

    /**
     * Get the user who created this customization
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the base product for this customization
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the admin who reviewed this customization
     */
    public function admin()
    {
        return $this->belongsTo(\App\Models\Admin::class, 'admin_id');
    }

    /**
     * Get the order associated with this customization
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get customization options (if you have a separate options table)
     */
    public function options()
    {
        // Specify the foreign key as 'customization_id' (not 'product_customization_id')
        return $this->hasMany(CustomizationOption::class, 'customization_id');
    }

    // ==================== STATUS HELPER METHODS ====================

    /**
     * Check if customization is pending
     */
    public function isPending()
    {
        return $this->status === 'Pending';
    }

    /**
     * Check if customization is approved
     */
    public function isApproved()
    {
        return $this->status === 'Approved';
    }

    /**
     * Check if customization is rejected
     */
    public function isRejected()
    {
        return $this->status === 'Rejected';
    }

    /**
     * Check if customization is completed
     */
    public function isCompleted()
    {
        return $this->status === 'Completed';
    }

    /**
     * Check if customization can be checked out
     */
    public function canCheckout()
    {
        return $this->isApproved() 
            && $this->admin_price > 0 
            && !$this->order_id;
    }

    /**
     * Check if customization can be edited
     */
    public function canEdit()
    {
        return $this->isPending() && !$this->order_id;
    }

    // ==================== SCOPE METHODS ====================

    /**
     * Scope to get only pending customizations
     */
    public function scopePending($query)
    {
        return $query->where('status', 'Pending');
    }

    /**
     * Scope to get only approved customizations
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'Approved');
    }

    /**
     * Scope to get only rejected customizations
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'Rejected');
    }

    /**
     * Scope to get only completed customizations
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'Completed');
    }

    /**
     * Scope to get customizations for a specific user
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // ==================== ACCESSOR METHODS ====================

    /**
     * Get the full image URL
     */
    public function getImageUrlAttribute()
    {
        if ($this->custom_image) {
            return asset('uploads/customizations/' . $this->custom_image);
        }
        
        // Fallback to product image if no custom image
        if ($this->product && $this->product->image) {
            return asset('uploads/products/' . $this->product->image);
        }
        
        return asset('images/placeholder.jpg');
    }

    /**
     * Get status badge color
     */
    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'Pending' => 'warning',
            'Approved' => 'success',
            'Rejected' => 'danger',
            'Completed' => 'info',
            default => 'secondary'
        };
    }

    /**
     * Get formatted price
     */
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