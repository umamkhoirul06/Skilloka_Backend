<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

use App\Models\Course;
use App\Models\Booking;
use App\Models\CourseSchedule;

class AdminAuthController extends Controller
{


    /*
    |--------------------------------------------------------------------------
    | FORM LOGIN
    |--------------------------------------------------------------------------
    */

    public function showLogin()
    {
        return view('admin.auth.login');
    }



    /*
    |--------------------------------------------------------------------------
    | PROSES LOGIN
    |--------------------------------------------------------------------------
    */

    public function login(Request $request)
    {

        $credentials = $request->validate([

            'email' => ['required','email'],

            'password' => ['required'],

        ]);


        if (Auth::attempt($credentials)) {

            $request->session()->regenerate();

            $user = Auth::user();


            /*
            |--------------------------------------------------------------------------
            | REDIRECT BERDASARKAN ROLE
            |--------------------------------------------------------------------------
            */


            // SUPER ADMIN
            if ($user->role === 'super_admin') {

                return redirect()->route('super.dashboard');

            }


            // ADMIN LPK
            if ($user->role === 'admin_lpk') {

                return redirect()->route('admin.dashboard');

            }


            // USER BIASA
            return redirect('/');

        }


        throw ValidationException::withMessages([

            'email' => 'Email atau password salah'

        ]);

    }



    /*
    |--------------------------------------------------------------------------
    | LOGOUT
    |--------------------------------------------------------------------------
    */

    public function logout(Request $request)
    {

        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('admin.login');

    }



    /*
    |--------------------------------------------------------------------------
    | DASHBOARD ADMIN LPK
    |--------------------------------------------------------------------------
    */

    public function dashboard()
    {

        // =====================
        // STATISTIK
        // =====================

        $totalCourses = Course::count();

        // student dihitung dari booking
        $totalStudents = Booking::count();

        $upcomingClasses = CourseSchedule::count();

        $pendingBookings = Booking::where(
            'status',
            'pending'
        )->count();



        // =====================
        // BOOKING TERBARU
        // =====================

        $recentBookings = Booking::latest()
            ->take(5)
            ->get();



        // =====================
        // DATA CHART BULANAN
        // =====================

        $monthlyLabels = [

            'Jan','Feb','Mar','Apr','May','Jun',
            'Jul','Aug','Sep','Oct','Nov','Dec'

        ];


        $monthlyStudents = [];

        $monthlyCourses = [];


        foreach(range(1,12) as $month){

            $monthlyStudents[] = Booking::whereYear(
                'created_at',
                now()->year
            )
            ->whereMonth(
                'created_at',
                $month
            )
            ->count();



            $monthlyCourses[] = Course::whereYear(
                'created_at',
                now()->year
            )
            ->whereMonth(
                'created_at',
                $month
            )
            ->count();

        }



        // =====================
        // RETURN VIEW
        // =====================

        return view(

            'admin.dashboard',

            compact(

                'totalCourses',

                'totalStudents',

                'upcomingClasses',

                'pendingBookings',

                'recentBookings',

                'monthlyLabels',

                'monthlyStudents',

                'monthlyCourses'

            )

        );

    }

}