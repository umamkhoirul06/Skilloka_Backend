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



        $bookings = Booking::with([

                'user',
                'schedule.course',
                'payment'

            ])

            ->when(

                !$user->hasRole('super_admin'),

                function ($q) use ($user) {

                    $q->where(
                        'tenant_id',
                        $user->tenant_id
                    );

                }

            )

            ->latest()

            ->paginate(10);



        return view(
            'admin.bookings.index',
            compact('bookings')
        );

    }



    /*
    |--------------------------------------------------------------------------
    | FORM CREATE
    |--------------------------------------------------------------------------
    */

    public function create()
    {

        $user = Auth::user();



        /*
        |--------------------------------------------------------------------------
        | STUDENTS
        |--------------------------------------------------------------------------
        |
        | sementara TANPA ROLE FILTER
        | supaya booking pasti jalan
        |
        */

        $students = User::where(

                'tenant_id',
                $user->tenant_id

            )

            ->whereIn('status', [

                'active',
                'pending'

            ])

            ->latest()

            ->get();



        /*
        |--------------------------------------------------------------------------
        | SCHEDULES
        |--------------------------------------------------------------------------
        */

        $schedules = CourseSchedule::with('course')

            ->whereHas('course', function ($q) use ($user) {

                $q->where(
                    'tenant_id',
                    $user->tenant_id
                );

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



    /*
    |--------------------------------------------------------------------------
    | STORE BOOKING
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {

        $request->validate([

            'user_id' => [

                'required',
                'exists:users,id'

            ],

            'schedule_id' => [

                'required',
                'exists:course_schedules,id'

            ],

            'status' => [

                'required',

                'in:pending,paid,cancelled,completed'

            ],

        ]);



        DB::beginTransaction();



        try {

            $admin = Auth::user();



            /*
            |--------------------------------------------------------------------------
            | SCHEDULE
            |--------------------------------------------------------------------------
            */

            $schedule = CourseSchedule::with('course')

                ->findOrFail(
                    $request->schedule_id
                );



            /*
            |--------------------------------------------------------------------------
            | SECURITY TENANT
            |--------------------------------------------------------------------------
            */

            if (

                !$admin->hasRole('super_admin')

                &&

                $schedule->course->tenant_id
                    !=
                $admin->tenant_id

            ) {

                abort(403);

            }



            /*
            |--------------------------------------------------------------------------
            | PAYMENT STATUS
            |--------------------------------------------------------------------------
            */

            $paymentStatus = 'unpaid';

            if ($request->status == 'paid') {

                $paymentStatus = 'paid';

            }



            /*
            |--------------------------------------------------------------------------
            | CREATE BOOKING
            |--------------------------------------------------------------------------
            */

            $booking = Booking::create([

                'user_id' => $request->user_id,

                'schedule_id' => $schedule->id,

                'tenant_id' => $schedule->course->tenant_id,

                'created_by' => $admin->id,

                'source' => 'admin_booking',

                'amount' => $schedule->course->price,

                'payment_status' => $paymentStatus,

                'status' => $request->status,

                'notes' => $request->notes,

                'expires_at' => now()->addHours(24),

            ]);



            /*
            |--------------------------------------------------------------------------
            | PAYMENT
            |--------------------------------------------------------------------------
            */

            $paymentDbStatus = 'pending';

            $paidAt = null;



            if ($request->status == 'paid') {

                $paymentDbStatus = 'success';

                $paidAt = now();

            }



            /*
            |--------------------------------------------------------------------------
            | CREATE PAYMENT
            |--------------------------------------------------------------------------
            */

            Payment::create([

                'booking_id' => $booking->id,

                'user_id' => $booking->user_id,

                'tenant_id' => $booking->tenant_id,

                'amount' => $booking->amount,

                'method' => 'manual',

                'provider' => 'manual',

                'status' => $paymentDbStatus,

                'paid_at' => $paidAt,

            ]);



            DB::commit();



            return redirect()

                ->route('admin.bookings.index')

                ->with(

                    'success',

                    'Booking berhasil dibuat'

                );

        } catch (\Exception $e) {

            DB::rollBack();



            /*
            |--------------------------------------------------------------------------
            | DEBUG ERROR
            |--------------------------------------------------------------------------
            */

            dd($e->getMessage());

        }

    }



    /*
    |--------------------------------------------------------------------------
    | DETAIL BOOKING
    |--------------------------------------------------------------------------
    */

    public function show(Booking $booking)
    {

        $user = Auth::user();



        if (

            !$user->hasRole('super_admin')

            &&

            $booking->tenant_id != $user->tenant_id

        ) {

            abort(403);

        }



        $booking->load([

            'user',
            'schedule.course',
            'payment',
            'creator',

        ]);



        return view(

            'admin.bookings.show',

            compact('booking')

        );

    }



    /*
    |--------------------------------------------------------------------------
    | DELETE BOOKING
    |--------------------------------------------------------------------------
    */

    public function destroy(Booking $booking)
    {

        $user = Auth::user();



        if (

            !$user->hasRole('super_admin')

            &&

            $booking->tenant_id != $user->tenant_id

        ) {

            abort(403);

        }



        $booking->delete();



        return back()->with(

            'success',

            'Booking berhasil dihapus'

        );

    }



    /*
    |--------------------------------------------------------------------------
    | UPDATE STATUS
    |--------------------------------------------------------------------------
    */

    public function updateStatus(
        Request $request,
        Booking $booking
    )
    {

        $request->validate([

            'status' => [

                'required',

                'in:pending,paid,cancelled,completed'

            ]

        ]);



        $user = Auth::user();



        if (

            !$user->hasRole('super_admin')

            &&

            $booking->tenant_id != $user->tenant_id

        ) {

            abort(403);

        }



        $booking->update([

            'status' => $request->status

        ]);



        return back()->with(

            'success',

            'Status booking berhasil diperbarui'

        );

    }

}