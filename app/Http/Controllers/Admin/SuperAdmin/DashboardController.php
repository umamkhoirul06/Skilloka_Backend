<?php

namespace App\Http\Controllers\Admin\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Lpk;
use App\Models\User;
use App\Models\Course;

class DashboardController extends Controller
{
    public function index()
    {
        // Menghitung total semua data
        $totalLpk = Lpk::count();
        $totalCourses = Course::count();
        $totalUsers = User::count();

        // 🔥 PASTIKAN INI: Menghitung yang benar-benar masih PENDING saja
        $pendingVerifications = Lpk::where('status_verifikasi', 'pending')->count();

        // Mengambil 5 LPK terbaru yang statusnya masih PENDING
        $pendingLpks = Lpk::where('status_verifikasi', 'pending')
            ->latest()
            ->take(5)
            ->get();

        // Logika Grafik (Tetap sama)
        $monthlyLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $monthlyLpk = [];
        $monthlyUsers = [];
        $monthlyCourses = [];

        foreach (range(1, 12) as $month) {
            $monthlyLpk[] = Lpk::whereMonth('created_at', $month)->count();
            $monthlyUsers[] = User::whereMonth('created_at', $month)->count();
            $monthlyCourses[] = Course::whereMonth('created_at', $month)->count();
        }

        return view('super_admin.dashboard', compact(
            'totalLpk',
            'totalCourses',
            'totalUsers',
            'pendingVerifications',
            'pendingLpks',
            'monthlyLabels',
            'monthlyLpk',
            'monthlyUsers',
            'monthlyCourses'
        ));
    }
}