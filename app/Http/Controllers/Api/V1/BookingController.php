<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\BaseController;
use App\Http\Resources\BookingResource;
use App\Models\Booking;
use App\Models\CourseSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingController extends BaseController
{
    /**
     * GET /api/v1/user/bookings
     * Ambil riwayat pesanan milik user yang sedang login
     */
    public function index(Request $request)
    {
        $bookings = Booking::query()
            ->where('user_id', $request->user()->id)
            ->with(['schedule.course', 'schedule.course.lpk'])
            ->latest()
            ->get();

        return $this->success(BookingResource::collection($bookings), 'Daftar riwayat pesanan berhasil diambil.');
    }

    /**
     * GET /api/v1/bookings/{id}
     * Ambil detail booking berdasarkan ID
     */
    public function show(string $id)
    {
        $booking = Booking::with(['schedule.course', 'schedule.course.lpk'])->findOrFail($id);

        if ($booking->user_id !== auth()->id()) {
            return $this->error('Unauthorized access to booking data.', 403);
        }

        return $this->success([
            'id' => $booking->id,
            'status' => $booking->status,
            'amount' => $booking->amount,
            'qr_code_url' => $booking->qr_code_url ? asset('storage/' . $booking->qr_code_url) : null,
            'course' => $booking->schedule->course,
            'schedule' => $booking->schedule,
        ], 'Detail booking berhasil diambil.');
    }

    /**
     * POST /api/v1/bookings
     * 🔥 METHOD STORE: Menangani pendaftaran/booking baru dari aplikasi Mobile
     */
    public function store(Request $request)
    {
        // Validasi input dari aplikasi mobile
        $request->validate([
            'course_id' => 'required',
            'schedule_id' => 'required|exists:course_schedules,id',
        ]);

        DB::beginTransaction();
        try {
            $user = $request->user();
            
            // Ambil data jadwal beserta kursusnya untuk mendapatkan tenant_id & harga
            $schedule = CourseSchedule::with('course')->findOrFail($request->schedule_id);

            // Buat record booking baru dengan status awal 'pending'
            $booking = Booking::create([
                'user_id' => $user->id,
                'schedule_id' => $schedule->id,
                'tenant_id' => $schedule->course->tenant_id,
                'created_by' => $user->id,
                'source' => 'mobile_booking',
                'amount' => $schedule->course->price,
                'payment_status' => 'unpaid',
                'status' => 'pending', // Menunggu persetujuan admin di Web
                'notes' => $request->notes ?? 'Pendaftaran via Aplikasi Mobile',
                'expires_at' => now()->addHours(24),
            ]);

            DB::commit();

            // Return response sukses dengan membawa data id booking untuk dibaca Flutter
            return $this->success([
                'booking_id' => $booking->id,
                'id' => $booking->id,
                'status' => $booking->status,
                'amount' => $booking->amount,
            ], 'Booking berhasil dibuat, menunggu persetujuan admin LPK.');

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error('Gagal membuat booking: ' . $e->getMessage(), 500);
        }
    }
}