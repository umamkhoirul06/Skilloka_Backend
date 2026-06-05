<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasUuids, BelongsToTenant;

    protected $guarded = ['id'];

    protected $casts = [
        'is_active' => 'boolean',
        'price' => 'decimal:2',
        'images' => 'array',
        'facilities' => 'array',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function lpk()
    {
        return $this->belongsTo(Lpk::class);
    }

    public function schedules()
    {
        return $this->hasMany(CourseSchedule::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
    public function favorites()
    {
    return $this->hasMany(Favorite::class);
    }
}
