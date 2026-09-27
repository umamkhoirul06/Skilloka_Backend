<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class EducationProfile extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'user_id',
        'education_level', // SMA, SMK, PERGURUAN_TINGGI, LAINNYA
        'major',
        'institution_name',
        'graduation_year',
        'career_goal',
        'preferred_location',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
