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
        'course_id',
        'lpk_id',
        'status',
        'total_price',
        'booking_date'
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
        'booking_date' => 'datetime',
    ];

    /**
     * Relasi ke User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke Course
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Relasi ke LPK
     */
    public function lpk()
    {
        return $this->belongsTo(Lpk::class);
    }

    /**
     * Relasi ke Tenant (Wajib untuk trait BelongsToTenant)
     */
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}