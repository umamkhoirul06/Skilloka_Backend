<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids; // Pastikan pakai Trait UUID

class Booking extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'user_id',
        'schedule_id', // Tambahkan ini
        'tenant_id',
        'created_by',
        'source',
        'amount',
        'payment_status',
        'status',
        'notes',
        'expires_at',
        'qr_code_url', // Tambahkan ini
    ];

    // Tambahkan relasi ke schedule jika belum ada
    public function schedule()
    {
        return $this->belongsTo(CourseSchedule::class, 'schedule_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Tambahkan fungsi ini di BookingController.php
    public function updateStatus(Request $request, Booking $booking)
    {
        $request->validate(['status' => 'required|in:confirmed,cancelled']);

        if (Auth::user()->tenant_id != $booking->tenant_id && !Auth::user()->hasRole('super_admin')) {
            abort(403);
        }

        DB::beginTransaction();
        try {
            if ($request->status == 'confirmed') {
                // 1. Generate QR Code saat di-approve
                $qrPath = 'qrs/booking_' . $booking->id . '.png';
                $qrData = "PAYMENT-ID:" . $booking->id;

                // Generate dan simpan ke folder storage/app/public/qrs
                \Storage::disk('public')->put($qrPath, QrCode::format('png')->size(300)->generate($qrData));

                // 2. Update Booking
                $booking->update([
                    'status' => 'confirmed',
                    'qr_code_url' => $qrPath
                ]);

                // 3. Update atau buat Payment Record sebagai pending
                \App\Models\Payment::updateOrCreate(
                    ['booking_id' => $booking->id],
                    [
                        'user_id' => $booking->user_id,
                        'tenant_id' => $booking->tenant_id,
                        'amount' => $booking->amount,
                        'status' => 'pending',
                        'method' => 'manual',
                    ]
                );
            } else {
                $booking->update(['status' => 'cancelled']);
            }

            DB::commit();
            return back()->with('success', 'Status booking berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal memperbarui status: ' . $e->getMessage()]);
        }
    }
}