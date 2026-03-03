<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Voucher extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'description',
        'discount_type',
        'discount_value',
        'min_order_amount',
        'max_discount_cap',
        'max_uses',
        'max_uses_per_user',
        'used_count',
        'is_active',
        'starts_at',
        'expires_at',
        'created_by',
    ];

    protected $casts = [
        'is_active'        => 'boolean',
        'starts_at'        => 'datetime',
        'expires_at'       => 'datetime',
        'discount_value'   => 'float',
        'min_order_amount' => 'float',
        'max_discount_cap' => 'float',
    ];

    // ── Relationships ────────────────────────────────────────────────────────

    public function usages()
    {
        return $this->hasMany(VoucherUsage::class);
    }

    // ── Scopes ───────────────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // ── Accessors ────────────────────────────────────────────────────────────

    public function getDiscountLabelAttribute(): string
    {
        if ($this->discount_type === 'percent') {
            $label = "{$this->discount_value}% off";
            if ($this->max_discount_cap) {
                $label .= " (up to ₱" . number_format($this->max_discount_cap, 2) . ")";
            }
            return $label;
        }

        return "₱" . number_format($this->discount_value, 2) . " off";
    }

    // ── Core methods ─────────────────────────────────────────────────────────

    /**
     * Validate whether a voucher can be used by a user for a given subtotal.
     * Always returns ['valid' => bool, 'message' => string].
     */
    public function validate(int $userId, float $subtotal): array
    {
        if (!$this->is_active) {
            return ['valid' => false, 'message' => 'This voucher is inactive.'];
        }

        if ($this->starts_at && Carbon::now()->lt($this->starts_at)) {
            return ['valid' => false, 'message' => 'This voucher is not yet active.'];
        }

        if ($this->expires_at && Carbon::now()->gt($this->expires_at)) {
            return ['valid' => false, 'message' => 'This voucher has expired.'];
        }

        if ($this->max_uses !== null && $this->used_count >= $this->max_uses) {
            return ['valid' => false, 'message' => 'This voucher has reached its usage limit.'];
        }

        $userUsageCount = VoucherUsage::where('voucher_id', $this->id)
            ->where('user_id', $userId)
            ->count();

        if ($userUsageCount >= $this->max_uses_per_user) {
            return ['valid' => false, 'message' => "You have already used this voucher {$this->max_uses_per_user} time(s)."];
        }

        if ($subtotal < $this->min_order_amount) {
            return [
                'valid'   => false,
                'message' => 'Minimum order of ₱' . number_format($this->min_order_amount, 2) . ' required.',
            ];
        }

        return ['valid' => true, 'message' => 'Voucher is valid.'];
    }

    /**
     * Compute the discount amount for a given subtotal.
     */
    public function computeDiscount(float $subtotal): float
    {
        if ($this->discount_type === 'percent') {
            $discount = $subtotal * ($this->discount_value / 100);

            if ($this->max_discount_cap && $discount > $this->max_discount_cap) {
                $discount = $this->max_discount_cap;
            }

            return round($discount, 2);
        }

        // Fixed discount — never exceed the subtotal
        return round(min($this->discount_value, $subtotal), 2);
    }
}