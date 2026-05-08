<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    use HasUuids;

    protected $fillable = [
        'name',
        'domain',
        'settings',
        'is_active',

        'lpk_name',
        'legal_name',
        'nib',
        'description',

        'phone',
        'email',
        'website',
        'instagram',
        'facebook',
        'tiktok',

        'province',
        'city',
        'district',
        'address',
        'latitude',
        'longitude',

        'logo',
        'banner',
        'facilities'
    ];

    protected $casts = [
        'settings' => 'array',
        'is_active' => 'boolean',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    // 🔥 TAMBAHAN WAJIB
    public function lpk()
    {
        return $this->hasOne(\App\Models\Lpk::class);
    }
}