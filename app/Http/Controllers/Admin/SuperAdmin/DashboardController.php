<?php

namespace App\Http\Controllers\Admin\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Lpk;
use App\Models\User;
use App\Models\Course;
use App\Models\Booking;
use App\Models\Category;

class DashboardController extends Controller
{
    public function index()
    {
        $totalLpk = Lpk::count();
        $verifiedLpk = Lpk::where('status_verifikasi', 'approved')->count();
        $totalCourses = Course::count();
        $totalUsers = User::count();
        $totalBookings = Booking::count();

        // Antrean verifikasi yang benar-benar masih PENDING
        $pendingVerifications = Lpk::where('status_verifikasi', 'pending')->count();

        // 5 LPK terbaru yang statusnya masih PENDING dengan relasi tenant
        $pendingLpks = Lpk::where('status_verifikasi', 'pending')
            ->with(['tenant.users'])
            ->latest()
            ->take(5)
            ->get();

        // Top Kategori Kursus
        $categoryDistribution = Category::withCount('courses')
            ->orderBy('courses_count', 'desc')
            ->take(5)
            ->get();

        // Tren Bulanan Platform
        $monthlyLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
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
            'verifiedLpk',
            'totalCourses',
            'totalUsers',
            'totalBookings',
            'pendingVerifications',
            'pendingLpks',
            'categoryDistribution',
            'monthlyLabels',
            'monthlyLpk',
            'monthlyUsers',
            'monthlyCourses'
        ));
    }
}