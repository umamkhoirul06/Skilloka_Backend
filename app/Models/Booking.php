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
        'amount',
        'code',
        'snap_token',
        'payment_method',
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
     * Relasi ke tabel Payments
     */
    public function payment()
    {
        return $this->hasOne(Payment::class, 'booking_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function lpk()
    {
        return $this->belongsTo(Lpk::class, 'lpk_id');
    }

    public function certificate()
    {
        return $this->hasOne(Certificate::class, 'booking_id');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'booking_id');
    }

    public function getAmountAttribute()
    {
        return $this->total_price;
    }

    /**
     * Accessor untuk status pembayaran yang akurat dan konsisten
     */
    public function getPaymentStatusAttribute($value = null)
    {
        // 1. Jika ada nilai eksplisit pada field payment_status
        if (!empty($value)) {
            $val = strtolower($value);
            if (in_array($val, ['paid', 'lunas', 'success', 'settlement', 'capture'])) {
                return 'paid';
            }
        }

        // 2. Cek dari relasi tabel payment
        $payment = $this->relationLoaded('payment') ? $this->payment : $this->payment()->first();
        if ($payment) {
            $paymentStatus = strtolower($payment->status ?? '');
            if (in_array($paymentStatus, ['paid', 'lunas', 'success', 'settlement', 'capture', 'verified'])) {
                return 'paid';
            }
            if ($paymentStatus === 'pending') {
                return 'pending';
            }
            if (in_array($paymentStatus, ['failed', 'deny', 'expire', 'cancel', 'batal'])) {
                return 'failed';
            }
        }

        // 3. Cek dari status booking
        $bookingStatus = strtolower($this->status ?? '');
        if (in_array($bookingStatus, ['selesai', 'paid', 'confirmed', 'completed', 'approved'])) {
            return 'paid';
        }

        if (in_array($bookingStatus, ['dibatalkan', 'cancelled'])) {
            return 'cancelled';
        }

        return 'unpaid';
    }

    /**
     * Accessor untuk tanggal kedaluwarsa booking (default 24 jam dari booking dibuat)
     */
    public function getExpiresAtAttribute($value = null)
    {
        $raw = $value ?? ($this->attributes['expires_at'] ?? null);
        if ($raw) {
            return \Carbon\Carbon::parse($raw);
        }

        $baseDate = $this->booking_date ?? $this->created_at;
        return $baseDate ? \Carbon\Carbon::parse($baseDate)->addHours(24) : null;
    }
}