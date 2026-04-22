<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';

    // Your table has no updated_at column
    const CREATED_AT = 'created_at';
    const UPDATED_AT = null;

    protected $fillable = [
        'name',
        'email',
        'password',
        'otp',
        'otp_expires_at',
        'otp_verified',
        'role',
    ];

    protected $hidden = [
        'password',
        'otp',        // never expose OTP in JSON responses
    ];

    protected $casts = [
        'otp_expires_at' => 'datetime',
        'otp_verified'   => 'boolean',
        'created_at'     => 'datetime',
    ];

    protected $attributes = [
        'role'         => 'User',
        'otp_verified' => false,
    ];
}