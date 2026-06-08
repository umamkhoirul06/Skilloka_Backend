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
            'amount' => (float) $booking->total_price, // 🔥 Menggunakan total_price
            'qr_code_url' => $booking->qr_code_url ? asset('storage/' . $booking->qr_code_url) : null,
            'course' => optional($booking->schedule)->course,
            'schedule' => $booking->schedule,
        ], 'Detail booking berhasil diambil.');
    }

    /**
     * POST /api/v1/bookings
     */
    public function store(Request $request)
    {
        $request->validate([
            'course_id' => 'required',
            'schedule_id' => 'required|exists:course_schedules,id',
        ]);

        DB::beginTransaction();
        try {
            $user = $request->user();
            $schedule = CourseSchedule::with('course')->findOrFail($request->schedule_id);

            // 🔥 FIX: Hanya memasukkan kolom yang benar-benar ada di database kamu
            $booking = Booking::create([
                'user_id' => $user->id,
                'course_id' => $schedule->course->id,
                'tenant_id' => $schedule->course->tenant_id,
                'total_price' => $schedule->course->price, // Kolom pengganti amount
                'schedule_id' => $schedule->id,
                'status' => 'Menunggu', // Sesuai default value di database kamu
            ]);

            DB::commit();

            return $this->success([
                'booking_id' => $booking->id,
                'id' => $booking->id,
                'status' => $booking->status,
                'amount' => (float) $booking->total_price,
            ], 'Booking berhasil dibuat, menunggu persetujuan admin LPK.');

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error('Gagal membuat booking: ' . $e->getMessage(), 500);
        }
    }
}