<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

use App\Models\Course;
use App\Models\Booking;
use App\Models\CourseSchedule;
use App\Models\User;
use App\Models\Tenant;
use App\Models\Lpk;
use App\Models\LpkVerification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AdminAuthController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | FORM REGISTER
    |--------------------------------------------------------------------------
    */

    public function showRegister()
    {
        return view('admin.auth.register');
    }

    /*
    |--------------------------------------------------------------------------
    | PROSES REGISTER
    |--------------------------------------------------------------------------
    */

    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'lpk_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string'],
        ]);

        try {
            DB::beginTransaction();

            // 1. Create Tenant
            $tenant = Tenant::create([
                'name' => $request->lpk_name,
                'domain' => Str::slug($request->lpk_name) . '-' . Str::random(5),
                'lpk_name' => $request->lpk_name,
                'phone' => $request->phone,
                'email' => $request->email,
                'address' => $request->address,
                'is_active' => true,
            ]);

            // 2. Create User
            $user = User::create([
                'tenant_id' => $tenant->id,
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
            ]);

            $user->assignRole('admin_lpk');

            // 3. Create LPK profile
            $lpk = Lpk::create([
                'tenant_id' => $tenant->id,
                'name' => $request->lpk_name,
                'address' => $request->address,
                'contact_info' => ['phone' => $request->phone, 'email' => $request->email],
                'is_verified' => false,
                'status' => 'pending',
            ]);

            // 4. Create Lpk Verification for Super Admin
            LpkVerification::create([
                'lpk_id' => $lpk->id,
                'status' => 'pending',
            ]);

            DB::commit();

            return redirect()->route('admin.login')->with('success', 'Registrasi berhasil! Akun Anda sedang direview oleh Super Admin. Silakan tunggu persetujuan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal mendaftar: ' . $e->getMessage()])->withInput();
        }
    }


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
    | PROSES LOGIN (SUDAH DI-BYPASS)
    |--------------------------------------------------------------------------
    */

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();

            if ($user->hasRole('super_admin')) {
                return redirect()->route('super.dashboard');
            }

            if ($user->hasRole('admin_lpk')) {
                return redirect()->route('admin.dashboard');
            }

            Auth::logout();
            throw ValidationException::withMessages([
                'email' => 'Anda tidak memiliki akses ke area ini.'
            ]);
        }

        throw ValidationException::withMessages([
            'email' => 'Email atau password salah.'
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

            'Jan',
            'Feb',
            'Mar',
            'Apr',
            'May',
            'Jun',
            'Jul',
            'Aug',
            'Sep',
            'Oct',
            'Nov',
            'Dec'

        ];


        $user = Auth::user();

        // OPTIMASI: 1 Query untuk seluruh Booking tahun ini
        $bookingsPerMonth = Booking::selectRaw('MONTH(created_at) as month, count(*) as total')
            ->where('tenant_id', $user->tenant_id)
            ->whereYear('created_at', now()->year)
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();

        // OPTIMASI: 1 Query untuk seluruh Course tahun ini
        $coursesPerMonth = Course::selectRaw('MONTH(created_at) as month, count(*) as total')
            ->where('tenant_id', $user->tenant_id)
            ->whereYear('created_at', now()->year)
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();

        foreach (range(1, 12) as $month) {
            $monthlyStudents[] = $bookingsPerMonth[$month] ?? 0;
            $monthlyCourses[] = $coursesPerMonth[$month] ?? 0;
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