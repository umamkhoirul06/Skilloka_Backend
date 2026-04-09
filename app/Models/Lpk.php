<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Lpk extends Model
{
    use HasUuids, BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'name',
        'legal_name',
        'nib',
        'location_id',
        'address',
        'lat',
        'long',
        'description',
        'facilities',
        'is_verified',
        'status',
        'contact_info',
        'logo',
        'images',
    ];

    protected $casts = [
        'facilities' => 'array',
        'contact_info' => 'array',
        'images' => 'array',
        'is_verified' => 'boolean',
        'lat' => 'decimal:8',
        'long' => 'decimal:8',
    ];

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    public function verifications()
    {
        return $this->hasMany(LpkVerification::class);
    }
}