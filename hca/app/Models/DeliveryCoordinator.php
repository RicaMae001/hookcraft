<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryCoordinator extends Model
{
    use HasFactory;

    protected $table = 'delivery_coordinator';

    protected $primaryKey = 'coordinator_id';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'status',
        'created_at',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    /**
     * Get the orders assigned to this coordinator.
     */
    public function orders()
    {
        return $this->hasMany(Order::class, 'coordinator_id', 'coordinator_id');
    }
}