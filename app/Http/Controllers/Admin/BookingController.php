<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\User;
use App\Models\CourseSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{

    public function index()
    {

        $user = Auth::user();

        $bookings = Booking::with([
                'user',
                'schedule.course'
            ])
            ->when($user->role != 'super_admin', function ($q) use ($user) {

                $q->where('tenant_id', $user->tenant_id);

            })
            ->latest()
            ->paginate(10);


        return view(
            'admin.bookings.index',
            compact('bookings')
        );

    }



    public function create()
    {

        $user = Auth::user();


        /*
        ambil student milik tenant
        TIDAK pakai filter role dulu
        supaya pasti muncul
        */
        $students = User::where('tenant_id', $user->tenant_id)
            ->latest()
            ->get();



        /*
        ambil schedule berdasarkan course tenant
        */
        $schedules = CourseSchedule::with('course')
            ->whereHas('course', function ($q) use ($user) {

                $q->where('tenant_id', $user->tenant_id);

            })
            ->latest()
            ->get();


        return view(
            'admin.bookings.create',
            compact(
                'students',
                'schedules'
            )
        );

    }



    public function store(Request $request)
    {

        $request->validate([

            'user_id' => 'required|exists:users,id',

            'schedule_id' => 'required|exists:course_schedules,id',

            'status' => 'required'

        ]);


        $schedule = CourseSchedule::with('course')
            ->findOrFail($request->schedule_id);



        Booking::create([

            'user_id' => $request->user_id,

            'schedule_id' => $schedule->id,

            'tenant_id' => $schedule->course->tenant_id,

            'amount' => $schedule->course->price,

            'status' => $request->status,

            'expires_at' => now()->addHours(24)

        ]);


        return redirect()
            ->route('admin.bookings.index')
            ->with(
                'success',
                'Booking berhasil dibuat'
            );

    }



    public function destroy(Booking $booking)
    {

        $booking->delete();

        return back()->with(
            'success',
            'Booking berhasil dihapus'
        );

    }



    public function updateStatus(Request $request, Booking $booking)
    {

        $booking->update([

            'status' => $request->status

        ]);

        return back();

    }

}