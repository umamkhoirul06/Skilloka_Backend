<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\BaseController;
use App\Models\Booking;
use App\Models\CourseSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingController extends BaseController
{
    /**
     * GET /api/v1/bookings
     * Daftar booking milik user yang login
     */
    public function index(Request $request)
    {
        $bookings = Booking::query()
            ->where('user_id', $request->user()->id)
            ->with(['schedule.course.lpk', 'schedule.course.category'])
            ->latest()
            ->paginate(10);

        return $this->paginated($bookings);
    }

    /**
     * POST /api/v1/bookings
     * Buat booking baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'schedule_id' => 'required|uuid|exists:course_schedules,id',
        ]);

        $schedule = CourseSchedule::with('course')->findOrFail($request->schedule_id);

        // Cek apakah sudah pernah booking schedule ini
        $existing = Booking::where('user_id', $request->user()->id)
            ->where('schedule_id', $request->schedule_id)
            ->whereNotIn('status', ['cancelled'])
            ->first();

        if ($existing) {
            return $this->error('Anda sudah mendaftar pada jadwal ini.', 409);
        }

        $booking = DB::transaction(function () use ($request, $schedule) {
            return Booking::create([
                'user_id'     => $request->user()->id,
                'schedule_id' => $schedule->id,
                'tenant_id'   => $schedule->course->tenant_id,
                'amount'      => $schedule->course->price,
                'status'      => 'pending',
                'expires_at'  => now()->addHours(24),
            ]);
        });

        $booking->load(['schedule.course.lpk', 'schedule.course.category']);

        return $this->success($booking, 'Booking berhasil dibuat', 201);
    }

    /**
     * GET /api/v1/bookings/{id}
     * Detail satu booking
     */
    public function show(Request $request, string $id)
    {
        $booking = Booking::where('user_id', $request->user()->id)
            ->with(['schedule.course.lpk', 'schedule.course.category'])
            ->findOrFail($id);

        return $this->success($booking);
    }

    /**
     * PATCH /api/v1/bookings/{id}/cancel
     * Batalkan booking
     */
    public function cancel(Request $request, string $id)
    {
        $booking = Booking::where('user_id', $request->user()->id)
            ->findOrFail($id);

        if (!in_array($booking->status, ['pending', 'confirmed'])) {
            return $this->error('Booking ini tidak dapat dibatalkan.', 422);
        }

        $booking->update(['status' => 'cancelled']);

        return $this->success($booking, 'Booking berhasil dibatalkan');
    }
}
