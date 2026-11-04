<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\BaseController;
use App\Http\Resources\BookingResource;
use App\Models\Booking;
use App\Models\CourseSchedule;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BookingController extends BaseController
{
    /**
     * GET /api/v1/user/bookings
     */
    public function index(Request $request)
    {
        $bookings = Booking::query()
            ->where('user_id', $request->user()->id)
            ->with(['schedule.course', 'schedule.course.lpk', 'course', 'payment'])
            ->latest()
            ->get();

        return $this->success(BookingResource::collection($bookings), 'Daftar riwayat pesanan berhasil diambil.');
    }

    /**
     * GET /api/v1/bookings/{id}
     */
    public function show(string $id)
    {
        $booking = Booking::with(['schedule.course', 'schedule.course.lpk', 'course', 'payment'])->findOrFail($id);

        if ($booking->user_id !== auth()->id()) {
            return $this->error('Unauthorized access to booking data.', 403);
        }

        $statusMap = [
            'Menunggu' => 'pending',
            'Menunggu Pembayaran' => 'pending',
            'Selesai' => 'completed',
            'confirmed' => 'completed',
            'paid' => 'completed',
            'Dibatalkan' => 'cancelled',
            'cancelled' => 'cancelled',
        ];

        return $this->success([
            'id' => $booking->id,
            'code' => $booking->code ?? $booking->id,
            'status' => $booking->status,
            'mapped_status' => $statusMap[$booking->status] ?? $booking->status,
            'amount' => (float) $booking->total_price,
            'total_price' => (float) $booking->total_price,
            'qr_code_url' => $booking->qr_code_url ? asset('storage/' . $booking->qr_code_url) : null,
            'course' => optional($booking->schedule)->course ?? $booking->course,
            'schedule' => $booking->schedule,
            'payment' => $booking->payment,
        ], 'Detail booking berhasil diambil.');
    }

    /**
     * POST /api/v1/bookings
     */
    public function store(Request $request)
    {
        $request->validate([
            'course_id' => 'required',
            'schedule_id' => 'nullable',
        ]);

        DB::beginTransaction();
        try {
            $user = $request->user();

            // Dapatkan schedule & course
            $schedule = null;
            $course = null;

            if ($request->filled('schedule_id')) {
                $schedule = CourseSchedule::with('course')->find($request->schedule_id);
            }

            if ($schedule && $schedule->course) {
                $course = $schedule->course;
            } else {
                $course = Course::findOrFail($request->course_id);
                // Jika tidak ada schedule yang dipilih, ambil schedule pertama yang tersedia
                $schedule = CourseSchedule::where('course_id', $course->id)->first();
            }

            // Hitung total harga resmi dari server (mencegah manipulasi harga dari client)
            $basePrice = (float) ($course->price ?? 0);
            $totalPrice = $basePrice;

            if ($request->boolean('include_subscription') || $request->input('service_type') === 'subscription') {
                $totalPrice += 50000; // Biaya langganan materi & absensi
            }
            if ($request->boolean('include_admin_fee') || $request->has('admin_fee')) {
                $totalPrice += 5000; // Biaya admin sistem
            }

            $bookingCode = 'BKG-' . strtoupper(Str::random(8));

            // Buat record Booking
            $booking = Booking::create([
                'user_id' => $user->id,
                'course_id' => $course->id,
                'tenant_id' => $course->tenant_id,
                'lpk_id' => $course->lpk_id ?? null,
                'total_price' => $totalPrice,
                'amount' => $totalPrice,
                'code' => $bookingCode,
                'schedule_id' => $schedule ? $schedule->id : null,
                'status' => 'Menunggu',
                'status_belajar' => 'sedang_belajar',
                'payment_method' => $request->input('payment_method', 'midtrans'),
                'booking_date' => now(),
            ]);

            // Sinkronisasi data user jika ada input nomor HP atau alamat baru
            $userUpdates = [];
            if ($request->filled('phone') && (empty($user->phone) || empty($user->phone_number))) {
                $userUpdates['phone'] = $request->phone;
                $userUpdates['phone_number'] = $request->phone;
            }
            if ($request->filled('address') && empty($user->address)) {
                $userUpdates['address'] = $request->address;
            }
            if (!empty($userUpdates)) {
                $user->update($userUpdates);
            }

            DB::commit();

            return $this->success([
                'booking_id' => $booking->id,
                'id' => $booking->id,
                'code' => $booking->code,
                'status' => 'pending',
                'db_status' => $booking->status,
                'amount' => (float) $booking->total_price,
                'total_price' => (float) $booking->total_price,
            ], 'Booking berhasil dibuat.');

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error('Gagal membuat booking: ' . $e->getMessage(), 500);
        }
    }

    /**
     * PATCH /api/v1/bookings/{id}/cancel
     */
    public function cancel(Request $request, string $id)
    {
        $booking = Booking::with('payment')
            ->where('id', $id)
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$booking) {
            return $this->error('Pesanan tidak ditemukan atau bukan milik akun Anda.', 404);
        }

        // Cek jika pesanan sudah dibayar
        $isPaid = in_array(strtolower($booking->status), ['selesai', 'confirmed', 'paid']) ||
            ($booking->payment && $booking->payment->status === 'success');

        if ($isPaid) {
            return $this->error('Pesanan yang sudah dibayar tidak dapat dibatalkan secara langsung. Silakan hubungi admin LPK terkait.', 422);
        }

        if (in_array(strtolower($booking->status), ['dibatalkan', 'cancelled'])) {
            return $this->error('Pesanan ini sudah dibatalkan sebelumnya.', 422);
        }

        $booking->update([
            'status' => 'Dibatalkan',
        ]);

        return $this->success([
            'booking_id' => $booking->id,
            'status' => 'cancelled',
            'db_status' => $booking->status,
        ], 'Pesanan berhasil dibatalkan.');
    }
}