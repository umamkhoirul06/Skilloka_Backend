<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Traits\BelongsToTenant;

class Booking extends Model
{
    use HasFactory, HasUuids, BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'user_id',
        'schedule_id',
        'code',
        'status',
        'amount',
        'qr_code',
        'expires_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'expires_at' => 'datetime',
    ];

    /**
     * Boot function to generate booking code.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($booking) {
            if (empty($booking->code)) {
                $booking->code = 'BK' . date('Ymd') . strtoupper(substr(uniqid(), -6));
            }
        });
    }

    /**
     * Get the student who made the booking.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the schedule being booked.
     */
    public function schedule()
    {
        return $this->belongsTo(CourseSchedule::class, 'schedule_id');
    }

    /**
     * Get the course through schedule.
     */
    public function course()
    {
        return $this->hasOneThrough(
            Course::class,
            CourseSchedule::class,
            'id', // Foreign key on CourseSchedule
            'id', // Foreign key on Course
            'schedule_id', // Local key on Booking
            'course_id' // Local key on CourseSchedule
        );
    }
}
