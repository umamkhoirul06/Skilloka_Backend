<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Booking extends Model
{
    use HasFactory, HasUuids;

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

    /**
     * Relasi ke Jadwal Kursus
     */
    public function schedule()
    {
        return $this->belongsTo(CourseSchedule::class, 'schedule_id');
    }

    /**
     * Relasi ke Data Siswa/User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 🔥 FIX: Relasi ke tabel Payments agar Eager Loading di Admin Panel tidak crash
     */
    public function payment()
    {
        return $this->hasOne(Payment::class, 'booking_id');
    }

    public function getAmountAttribute()
    {
        return $this->total_price;
    }
}