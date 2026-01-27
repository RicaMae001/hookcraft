<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Notification extends Model
{
    protected $fillable = [
        'recipient_type',
        'recipient_id',
        'sender_type',
        'sender_id',
        'type',
        'title',
        'message',
        'entity_type',
        'entity_id',
        'action_url',
        'is_read',
        'read_at',
        'priority',
        'metadata'
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
        'metadata' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Scopes
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeForRecipient($query, $recipientType, $recipientId = null)
    {
        return $query->where('recipient_type', $recipientType)
            ->where(function($q) use ($recipientId) {
                $q->where('recipient_id', $recipientId)
                  ->orWhereNull('recipient_id'); // Broadcast to all
            });
    }

    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeHighPriority($query)
    {
        return $query->whereIn('priority', ['high', 'urgent']);
    }

    public function scopeRecent($query, $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    // Methods
    public function markAsRead()
    {
        $this->update([
            'is_read' => true,
            'read_at' => now()
        ]);
    }

    public function markAsUnread()
    {
        $this->update([
            'is_read' => false,
            'read_at' => null
        ]);
    }

    // Static helper methods
    public static function markAllAsRead($recipientType, $recipientId)
    {
        return static::forRecipient($recipientType, $recipientId)
            ->unread()
            ->update([
                'is_read' => true,
                'read_at' => now()
            ]);
    }

    public static function getUnreadCount($recipientType, $recipientId)
    {
        return static::forRecipient($recipientType, $recipientId)
            ->unread()
            ->count();
    }

    public static function deleteOldNotifications($days = 30)
    {
        return static::where('created_at', '<', now()->subDays($days))
            ->where('is_read', true)
            ->delete();
    }

    // Relationships
    public function sender()
    {
        switch ($this->sender_type) {
            case 'admin':
            case 'staff':
                return DB::table('admin')->where('id', $this->sender_id)->first();
            case 'delivery':
                return DB::table('delivery_coordinator')->where('coordinator_id', $this->sender_id)->first();
            case 'user':
                return DB::table('users')->where('id', $this->sender_id)->first();
            default:
                return null;
        }
    }

    public function getSenderNameAttribute()
    {
        $sender = $this->sender();
        return $sender ? $sender->name : 'System';
    }

    // Accessors
    public function getTimeAgoAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    public function getIconAttribute()
    {
        $icons = [
            'order_created' => 'fa-shopping-cart',
            'order_updated' => 'fa-edit',
            'order_cancelled' => 'fa-times-circle',
            'payment_received' => 'fa-money-bill-wave',
            'payment_proof_uploaded' => 'fa-file-upload',
            'delivery_assigned' => 'fa-truck',
            'delivery_status_changed' => 'fa-shipping-fast',
            'product_low_stock' => 'fa-exclamation-triangle',
            'product_out_of_stock' => 'fa-box-open',
            'product_created' => 'fa-plus-circle',
            'product_updated' => 'fa-pen',
            'chat_message' => 'fa-comment',
            'system_alert' => 'fa-bell',
        ];

        return $icons[$this->type] ?? 'fa-bell';
    }

    public function getColorClassAttribute()
    {
        $colors = [
            'order_created' => 'success',
            'order_updated' => 'info',
            'order_cancelled' => 'danger',
            'payment_received' => 'success',
            'payment_proof_uploaded' => 'info',
            'delivery_assigned' => 'primary',
            'delivery_status_changed' => 'info',
            'product_low_stock' => 'warning',
            'product_out_of_stock' => 'danger',
            'product_created' => 'success',
            'product_updated' => 'info',
            'chat_message' => 'primary',
            'system_alert' => 'warning',
        ];

        return $colors[$this->type] ?? 'secondary';
    }
}