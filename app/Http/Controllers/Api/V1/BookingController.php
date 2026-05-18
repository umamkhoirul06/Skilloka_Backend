<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\BaseController;
use App\Http\Resources\BookingResource;
use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends BaseController
{
    /**
     * GET /api/user/bookings
     * Ambil riwayat pesanan milik user yang sedang login
     */
    public function index(Request $request)
    {
        $bookings = Booking::query()
            ->where('user_id', $request->user()->id)
            ->with(['course.category', 'lpk'])
            ->latest('booking_date')
            ->get();

        return $this->success(BookingResource::collection($bookings), 'Daftar riwayat pesanan berhasil diambil.');
    }

    /**
     * GET /api/v1/bookings/{id}
     */
    public function show(string $id)
    {
        $booking = Booking::with(['course.category', 'lpk'])->findOrFail($id);

        if ($booking->user_id !== auth()->id()) {
            return $this->error('Unauthorized access to booking data.', 403);
        }

        return $this->success(new BookingResource($booking));
    }
}
