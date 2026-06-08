<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids; // Menggunakan Trait UUID untuk PostgreSQL

class Booking extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'user_id',
        'schedule_id',
        'tenant_id',
        'created_by',
        'source',
        'amount',
        'payment_status',
        'status',
        'notes',
        'expires_at',
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