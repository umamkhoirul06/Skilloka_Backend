<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{

    protected $fillable = [

        'platform_name',
        'support_email',
        'timezone',
        'language',
        'platform_fee',
        'payment_method'

    ];

}