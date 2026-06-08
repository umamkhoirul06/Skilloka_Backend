<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\User;
use App\Models\CourseSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;

class BookingController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | LIST BOOKING
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $user = Auth::user();
        $bookings = Booking::with(['user', 'schedule.course', 'payment'])
            ->when(!$user->hasRole('super_admin'), function ($q) use ($user) {
                $q->where('tenant_id', $user->tenant_id);
            })
            ->latest()
            ->paginate(10);

        return view('admin.bookings.index', compact('bookings'));
    }

    /*
    |--------------------------------------------------------------------------
    | FORM CREATE
    |--------------------------------------------------------------------------
    */
    public function create()
    {
        $user = Auth::user();
        $students = User::where('tenant_id', $user->tenant_id)->whereIn('status', ['active', 'pending'])->get();
        $schedules = CourseSchedule::with('course')
            ->whereHas('course', function ($q) use ($user) {
                $q->where('tenant_id', $user->tenant_id);
            })->latest()->get();

        return view('admin.bookings.create', compact('students', 'schedules'));
    }

    /*
    |--------------------------------------------------------------------------
    | STORE BOOKING (ADMIN PANEL)
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'schedule_id' => 'required|exists:course_schedules,id',
        ]);

        DB::beginTransaction();
        try {
            $admin = Auth::user();
            $schedule = CourseSchedule::with('course')->findOrFail($request->schedule_id);

            // Proteksi Tenant Keamanan
            if (!$admin->hasRole('super_admin') && $schedule->course->tenant_id != $admin->tenant_id) {
                abort(403);
            }

            // Status awal wajib 'pending' menunggu konfirmasi pembayaran/berkas
            Booking::create([
                'user_id' => $request->user_id,
                'schedule_id' => $schedule->id,
                'tenant_id' => $schedule->course->tenant_id,
                'created_by' => $admin->id,
                'source' => 'admin_booking',
                'amount' => $schedule->course->price,
                'payment_status' => 'unpaid',
                'status' => 'pending',
                'notes' => $request->notes,
                'expires_at' => now()->addHours(24),
            ]);

            DB::commit();
            return redirect()->route('admin.bookings.index')->with('success', 'Booking berhasil dibuat, menunggu konfirmasi.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE STATUS (APPROVE & GENERATE QR)
    |--------------------------------------------------------------------------
    */
    public function updateStatus(Request $request, Booking $booking)
    {
        $request->validate(['status' => 'required|in:confirmed,cancelled']);

        if (Auth::user()->tenant_id != $booking->tenant_id && !Auth::user()->hasRole('super_admin')) {
            abort(403);
        }

        DB::beginTransaction();
        try {
            if ($request->status == 'confirmed') {
                // 1. Generate path unik untuk QR Code
                $qrPath = 'qrs/booking_' . $booking->id . '.png';

                // 2. Isi dari QR: ID Booking unik platform Skilloka
                $qrData = "SKILLOKA-PAYMENT-ID:" . $booking->id;

                // 3. Simpan gambar QR Code ke storage public
                Storage::disk('public')->put($qrPath, QrCode::format('png')->size(300)->generate($qrData));

                // 4. Sinkronisasi data kolom baru ke baris booking terkait
                $booking->update([
                    'status' => 'confirmed',
                    'qr_code_url' => $qrPath
                ]);

                // 5. Inisialisasi Invoice Pembayaran Record Baru
                Payment::updateOrCreate(
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
            return back()->withErrors(['error' => 'Gagal: ' . $e->getMessage()]);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | DETAIL BOOKING (PROTECTED)
    |--------------------------------------------------------------------------
    */
    public function show(Booking $booking)
    {
        $user = Auth::user();

        // 🔥 FIX: Validasi Keamanan Tenant agar LPK lain tidak bisa mengintip
        if (!$user->hasRole('super_admin') && $booking->tenant_id != $user->tenant_id) {
            abort(403);
        }

        $booking->load(['user', 'schedule.course', 'payment', 'creator']);
        return view('admin.bookings.show', compact('booking'));
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE BOOKING (PROTECTED)
    |--------------------------------------------------------------------------
    */
    public function destroy(Booking $booking)
    {
        $user = Auth::user();

        // 🔥 FIX: Validasi Keamanan Tenant agar LPK lain tidak bisa asal menghapus data
        if (!$user->hasRole('super_admin') && $booking->tenant_id != $user->tenant_id) {
            abort(403);
        }

        $booking->delete();
        return back()->with('success', 'Booking berhasil dihapus');
    }
}