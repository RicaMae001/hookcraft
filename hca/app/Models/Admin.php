<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'admin';

    /**
     * Indicates if the model should be timestamped.
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
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'role' => 'string',
    ];

    /**
     * Check if the admin has Admin role
     *
     * @return bool
     */
    public function isAdmin()
    {
        return $this->role === 'Admin';
    }

    /**
     * Check if the admin has Staff role
     *
     * @return bool
     */
    public function isStaff()
    {
        return $this->role === 'Staff';
    }

    /**
     * Check if the admin has Admin or Staff role
     *
     * @return bool
     */
    public function isAdminOrStaff()
    {
        return in_array($this->role, ['Admin', 'Staff']);
    }

    /**
     * Gallery images created by this admin
     */
    public function galleryImages()
    {
        return $this->hasMany(GalleryImage::class, 'admin_id');
    }

    /**
     * Products created by this admin
     */
    public function products()
    {
        return $this->hasMany(Product::class, 'admin_id');
    }

    /**
     * Orders assigned to this admin
     */
    public function orders()
    {
        return $this->hasMany(Order::class, 'admin_id');
    }
}