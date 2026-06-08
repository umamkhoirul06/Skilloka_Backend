<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\BaseController;
use App\Http\Resources\BookingResource;
use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends BaseController
{
    public function index(Request $request)
    {
        // Pastikan relasi schedule.course terpasang di Model Booking
        $bookings = Booking::query()
            ->where('user_id', $request->user()->id)
            ->with(['schedule.course', 'schedule.course.lpk'])
            ->latest()
            ->get();

        return $this->success(BookingResource::collection($bookings), 'Riwayat pesanan berhasil diambil.');
    }

    public function show(string $id)
    {
        // 🔥 TAMBAHAN LOGIKA: Ambil QR Code jika status confirmed
        $booking = Booking::with(['schedule.course', 'schedule.course.lpk'])->findOrFail($id);

        if ($booking->user_id !== auth()->id()) {
            return $this->error('Unauthorized access.', 403);
        }

        // Kita return data booking termasuk QR Code
        return $this->success([
            'id' => $booking->id,
            'status' => $booking->status,
            'amount' => $booking->amount,
            'qr_code_url' => $booking->qr_code_url ? asset('storage/' . $booking->qr_code_url) : null,
            'course' => $booking->schedule->course,
            'schedule' => $booking->schedule,
        ]);
    }

    public function history(Request $request)
    {
        $user = $request->user();

        // Ambil semua booking user (termasuk yang statusnya 'pending' atau 'paid')
        $bookings = Booking::with([
            'schedule.course',
            'schedule.course.lpk',
        ])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Map data sesuai struktur yang dibutuhkan frontend
        $data = $bookings->map(function ($booking) {
            return [
                'id' => $booking->id,
                'status' => $booking->status, // pending / paid / cancelled
                'amount' => $booking->amount,
                'payment_method' => $booking->payment_method,
                'course' => [
                    'id' => $booking->schedule->course_id,
                    'title' => $booking->schedule->course->title,
                    'image' => $booking->schedule->course->thumbnail_url,
                ],
                'schedule' => [
                    'date' => $booking->schedule->date,
                    'start_time' => $booking->schedule->start_time,
                    'end_time' => $booking->schedule->end_time,
                    'location' => $booking->schedule->location,
                ],
                'lpk' => [
                    'id' => $booking->schedule->course->lpk_id,
                    'name' => $booking->schedule->course->lpk->name,
                    'address' => $booking->schedule->course->lpk->address,
                ],
                'qr_code' => $booking->qr_code_url ? asset('storage/' . $booking->qr_code_url) : null,
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'Riwayat pesanan berhasil diambil',
            'data' => $data,
        ]);
    }

}