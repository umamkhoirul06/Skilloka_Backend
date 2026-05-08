<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

// Spatie Role
use Spatie\Permission\Traits\HasRoles;

// UUID
use Illuminate\Database\Eloquent\Concerns\HasUuids;

// Soft Delete
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, HasUuids, SoftDeletes;

    protected $guard_name = 'web';

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'tenant_id',
        'name',
        'email',
        'phone',
        'password',
        'avatar',
        'location_id',
        'fcm_token',
        'status', // pending / active / rejected
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /*
    =====================
    RELATIONSHIPS
    =====================
    */

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function lpk()
    {
        return $this->hasOne(Lpk::class);
    }

    /**
     * 🔥 PENTING: RELASI BOOKING (INI KUNCI STUDENT)
     */
    public function bookings()
    {
        return $this->hasMany(\App\Models\Booking::class);
    }

    /*
    =====================
    HELPER ROLE
    =====================
    */

    public function isSuperAdmin()
    {
        return $this->hasRole('super_admin');
    }

    public function isAdminLpk()
    {
        return $this->hasRole('admin_lpk');
    }

    public function isUser()
    {
        return $this->hasRole('user');
    }

    /*
    =====================
    HELPER STATUS (BONUS)
    =====================
    */

    public function isActive()
    {
        return $this->status === 'active';
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isRejected()
    {
        return $this->status === 'rejected';
    }
}