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


        // BASIC INFO
        'lpk_name',
        'legal_name',
        'nib',
        'description',


        // CONTACT
        'phone',
        'email',
        'website',

        'instagram',
        'facebook',
        'tiktok',


        // LOCATION
        'province',
        'city',
        'district',
        'address',

        'latitude',
        'longitude',


        // MEDIA
        'logo',
        'banner',


        // FACILITIES
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

}