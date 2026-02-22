<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'orders';

    /**
     * Indicates if the model should be timestamped.
     * Set to false because orders table only has created_at, not updated_at
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
        'user_id',
        'customer_name',
        'email',
        'address',
        'phone',
        'city_id',
        'barangay_id',
        'latitude',
        'longitude',
        'total',
        'delivery_fee',          
        'delivery_distance_km',   
        'grand_total',            
        'payment_proof',
        'payment_method',
        'payment_status',
        'delivery_status',
        'coordinator_id',
        'admin_id',
        'created_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
        'total' => 'decimal:2',
        'delivery_fee'         => 'decimal:2',   
        'delivery_distance_km' => 'decimal:2',  
        'grand_total'          => 'decimal:2',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    /**
     * The "booting" method of the model.
     * Automatically set created_at when creating
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->created_at)) {
                $model->created_at = now();
            }
        });
    }

    /**
     * Get the user that owns the order.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the order items for the order.
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    /**
     * Get the delivery coordinator assigned to the order.
     */
    public function coordinator()
    {
        return $this->belongsTo(DeliveryCoordinator::class, 'coordinator_id', 'coordinator_id');
    }

    /**
     * Get the admin who processed the order.
     */
    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    /**
     * Get the delivery logs for this order.
     */
    public function deliveryLogs()
    {
        return $this->hasMany(DeliveryLog::class, 'order_id');
    }

    /**
     * Scope a query to only include orders for a specific user.
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope a query to only include pending orders.
     */
    public function scopePending($query)
    {
        return $query->where('payment_status', 'Pending');
    }

    /**
     * Scope a query to only include paid orders.
     */
    public function scopePaid($query)
    {
        return $query->where('payment_status', 'Paid');
    }

    /**
     * Scope a query to filter by delivery status.
     */
    public function scopeDeliveryStatus($query, $status)
    {
        return $query->where('delivery_status', $status);
    }

    /**
     * Get the order total with currency formatting.
     */
    public function getFormattedTotalAttribute()
    {
        return '₱' . number_format($this->total, 2);
    }

    /**
     * Get the formatted grand total.
     */
    public function getFormattedGrandTotalAttribute()
    {
        return '₱' . number_format($this->grand_total, 2);
    }

    /**
     * Get the formatted delivery fee.
     */
    public function getFormattedDeliveryFeeAttribute()
    {
        return '₱' . number_format($this->delivery_fee, 2);
    }

    /**
     * Check if the order can be cancelled.
     */
    public function canBeCancelled()
    {
        return in_array($this->delivery_status, ['Pending', 'Out for Delivery']) 
            && $this->payment_status !== 'Refunded';
    }

    /**
     * Get the status badge color class.
     */
    public function getPaymentStatusColorAttribute()
    {
        return match($this->payment_status) {
            'Pending' => 'warning',
            'Paid' => 'success',
            'Unsuccessful' => 'danger',
            'Refunded' => 'info',
            default => 'secondary',
        };
    }

    /**
     * Get the delivery status badge color class.
     */
    public function getDeliveryStatusColorAttribute()
    {
        return match($this->delivery_status) {
            'Pending' => 'warning',
            'Out for Delivery' => 'info',
            'Delivered' => 'success',
            'Cancelled' => 'danger',
            default => 'secondary',
        };
    }
}