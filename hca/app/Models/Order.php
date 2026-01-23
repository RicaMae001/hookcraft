<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'customer_name',
        'address',
        'phone',
        'total',
        'payment_proof',
        'payment_method',
        'payment_status',
        'delivery_status',
        'coordinator_id',
        'admin_id',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'total' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->created_at)) {
                $model->created_at = now();
            }
        });
    }

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    public function coordinator()
    {
        return $this->belongsTo(DeliveryCoordinator::class, 'coordinator_id', 'coordinator_id');
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    public function deliveryLogs()
    {
        return $this->hasMany(DeliveryLog::class, 'order_id');
    }

    // Scopes
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopePending($query)
    {
        return $query->where('payment_status', 'Pending');
    }

    public function scopePaid($query)
    {
        return $query->where('payment_status', 'Paid');
    }

    public function scopeDeliveryStatus($query, $status)
    {
        return $query->where('delivery_status', $status);
    }

    // Accessors
    public function getFormattedTotalAttribute()
    {
        return '₱' . number_format($this->total, 2);
    }

    public function getOrderNumberAttribute()
    {
        return str_pad($this->id, 6, '0', STR_PAD_LEFT);
    }

    public function getItemsCountAttribute()
    {
        return $this->orderItems()->sum('quantity');
    }

    // Helper Methods
    public function canBeCancelled()
    {
        return in_array($this->delivery_status, ['Pending', 'Out for Delivery']) 
            && $this->payment_status !== 'Refunded';
    }

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