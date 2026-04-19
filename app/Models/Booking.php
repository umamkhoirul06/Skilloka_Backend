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

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($booking) {

            if(empty($booking->code)){

                $booking->code =
                    'BK'
                    .date('Ymd')
                    .strtoupper(substr(uniqid(), -6));

            }

        });
    }

    /*
    |--------------------------------------------------------------------------
    | RELATION
    |--------------------------------------------------------------------------
    */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function schedule()
    {
        return $this->belongsTo(
            CourseSchedule::class,
            'schedule_id'
        );
    }

    public function course()
    {
        return $this->hasOneThrough(
            Course::class,
            CourseSchedule::class,
            'id',
            'id',
            'schedule_id',
            'course_id'
        );
    }
}