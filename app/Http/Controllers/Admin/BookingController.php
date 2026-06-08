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
    public function index()
    {
        $user = Auth::user();

        $query = Booking::with(['user', 'schedule.course', 'payment'])
            ->when(!$user->hasRole('super_admin'), function ($q) use ($user) {
                $q->where('tenant_id', $user->tenant_id);
            });

        // ✅ FIX: Hitung summary SEBELUM paginate, dari relasi payment yang benar
        $allBookings = (clone $query)->get();

        $totalBookings   = $allBookings->count();

        // Paid: payment ada dan statusnya paid/verified/success
        $paidBookings    = $allBookings->filter(fn($b) =>
            $b->payment && in_array(strtolower($b->payment->status), ['paid', 'verified', 'success'])
        );

        // Pending: belum ada payment ATAU payment masih pending
        $pendingBookings = $allBookings->filter(fn($b) =>
            !$b->payment || strtolower($b->payment->status ?? '') === 'pending'
        );

        $totalRevenue    = $paidBookings->sum('total_price');
        $paidCount       = $paidBookings->count();
        $pendingCount    = $pendingBookings->count();

        // Paginate untuk tabel
        $bookings = $query->latest()->paginate(10);

        return view('admin.bookings.index', compact(
            'bookings',
            'totalBookings',
            'paidCount',
            'pendingCount',
            'totalRevenue'
        ));
    }

    public function create()
    {
        $user = Auth::user();
        $students = User::where('tenant_id', $user->tenant_id)
            ->whereIn('status', ['active', 'pending'])
            ->get();
        $schedules = CourseSchedule::with('course')
            ->whereHas('course', function ($q) use ($user) {
                $q->where('tenant_id', $user->tenant_id);
            })->latest()->get();

        return view('admin.bookings.create', compact('students', 'schedules'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id'     => 'required|exists:users,id',
            'schedule_id' => 'required|exists:course_schedules,id',
        ]);

        DB::beginTransaction();
        try {
            $admin    = Auth::user();
            $schedule = CourseSchedule::with('course')->findOrFail($request->schedule_id);

            if (!$admin->hasRole('super_admin') && $schedule->course->tenant_id != $admin->tenant_id) {
                abort(403);
            }

            Booking::create([
                'user_id'     => $request->user_id,
                'course_id'   => $schedule->course->id,
                'tenant_id'   => $schedule->course->tenant_id,
                'total_price' => $schedule->course->price,
                'schedule_id' => $schedule->id,
                'status'      => 'Menunggu',
            ]);

            DB::commit();
            return redirect()->route('admin.bookings.index')
                ->with('success', 'Booking berhasil dibuat, menunggu konfirmasi.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        $request->validate(['status' => 'required|in:confirmed,cancelled']);

        if (Auth::user()->tenant_id != $booking->tenant_id && !Auth::user()->hasRole('super_admin')) {
            abort(403);
        }

        DB::beginTransaction();
        try {
            if ($request->status == 'confirmed') {
                $qrPath = 'qrs/booking_' . $booking->id . '.png';
                $qrData = "SKILLOKA-PAYMENT-ID:" . $booking->id;

                Storage::disk('public')->put(
                    $qrPath,
                    QrCode::format('png')->size(300)->generate($qrData)
                );

                $booking->update([
                    'status'      => 'Selesai',
                    'qr_code_url' => $qrPath,
                ]);

                $coursePrice = optional(optional($booking->schedule)->course)->price ?? 0;

                Payment::updateOrCreate(
                    ['booking_id' => $booking->id],
                    [
                        'user_id'   => $booking->user_id,
                        'tenant_id' => $booking->tenant_id,
                        'amount'    => $coursePrice,
                        'status'    => 'pending',
                        'method'    => 'manual',
                        'provider'  => 'manual',
                    ]
                );
            } else {
                $booking->update(['status' => 'Dibatalkan']);
            }

            DB::commit();
            return back()->with('success', 'Status booking berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal: ' . $e->getMessage()]);
        }
    }

    public function show(Booking $booking)
    {
        $user = Auth::user();

        if (!$user->hasRole('super_admin') && $booking->tenant_id != $user->tenant_id) {
            abort(403);
        }

        $booking->load(['user', 'schedule.course', 'payment']);
        return view('admin.bookings.show', compact('booking'));
    }

    public function destroy(Booking $booking)
    {
        $user = Auth::user();

        if (!$user->hasRole('super_admin') && $booking->tenant_id != $user->tenant_id) {
            abort(403);
        }

        $booking->delete();
        return back()->with('success', 'Booking berhasil dihapus');
    }
}