<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Booking extends Model
{
    use HasFactory, HasUuids;

    // 🔥 Kolom disesuaikan dengan struktur asli PostgreSQL kamu
    protected $fillable = [
        'user_id',
        'course_id',
        'lpk_id',
        'status',
        'total_price',
        'booking_date',
        'tenant_id',
        'status_belajar',
        'schedule_id',
        'qr_code_url',
    ];

    public function schedule()
    {
        return $this->belongsTo(CourseSchedule::class, 'schedule_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}