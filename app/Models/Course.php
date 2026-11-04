<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasUuids, BelongsToTenant;

    protected $guarded = ['id'];

    protected $appends = ['level_label'];

    protected $casts = [
        'is_active' => 'boolean',
        'price' => 'decimal:2',
        'images' => 'array',
        'facilities' => 'array',
    ];

    public function getLevelLabelAttribute(): string
    {
        return match (strtolower($this->level ?? '')) {
            'beginner' => 'Pemula',
            'intermediate' => 'Menengah',
            'advanced' => 'Mahir',
            default => ucfirst($this->level ?? 'Pemula'),
        };
    }

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

    public function contents()
    {
        return $this->hasMany(CourseContent::class)->orderBy('order_index', 'asc');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }
}
