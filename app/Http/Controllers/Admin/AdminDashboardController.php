<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Course;
use App\Models\Booking;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $totalStudents = User::where('tenant_id', $user->tenant_id)->count();

        $totalCourses = Course::where('tenant_id', $user->tenant_id)->count();

        $upcomingClasses = Course::where('tenant_id', $user->tenant_id)->count();

        $pendingBookings = Booking::where('tenant_id', $user->tenant_id)
            ->where('status', 'pending')
            ->count();

        $recentBookings = Booking::where('tenant_id', $user->tenant_id)
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalStudents',
            'totalCourses',
            'upcomingClasses',
            'pendingBookings',
            'recentBookings'
        ));
    }
}