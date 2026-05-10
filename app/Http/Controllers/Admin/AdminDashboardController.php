<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Course;
use App\Models\Booking;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use App\Models\CourseSchedule;

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

    public function exportPdf(Request $request)
    {
        $tenantId = auth()->user()->tenant_id;
        
        // Filter periode
        $period = $request->get('period', 'year');
        
        $startDate = match($period) {
            'month'    => Carbon::now()->startOfMonth(),
            '3months'  => Carbon::now()->subMonths(3),
            '6months'  => Carbon::now()->subMonths(6),
            default    => Carbon::now()->startOfYear(),
        };

        $bookings = \App\Models\Booking::with([
                'user',
                'schedule.course'
            ])
            ->where('tenant_id', $tenantId)
            ->where('created_at', '>=', $startDate)
            ->orderBy('created_at', 'desc')
            ->get();

        $lpk = \App\Models\Lpk::where('tenant_id', $tenantId)->first();

        $pdf = Pdf::loadView('admin.reports.pdf', compact('bookings', 'lpk', 'period', 'startDate'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('report-' . $period . '-' . now()->format('Ymd') . '.pdf');
    }

    /*
    |--------------------------------------------------------------------------
    | DOWNLOAD DASHBOARD PDF
    |--------------------------------------------------------------------------
    */

    public function downloadPdf()
    {
        $user = Auth::user();
        $tenantId = $user->tenant_id;

        $totalStudents = User::where('tenant_id', $tenantId)->count();
        $totalCourses = Course::where('tenant_id', $tenantId)->count();
        $upcomingClasses = CourseSchedule::whereHas('course', fn($q) => $q->where('tenant_id', $tenantId))
            ->where('start_date', '>=', now())
            ->count();
        $pendingBookings = Booking::where('tenant_id', $tenantId)->where('status', 'pending')->count();
        $recentBookings = Booking::where('tenant_id', $tenantId)->latest()->take(10)->get();

        $pdf = Pdf::loadView('admin.dashboard_pdf', compact(
            'totalStudents',
            'totalCourses',
            'upcomingClasses',
            'pendingBookings',
            'recentBookings'
        ))->setPaper('a4', 'portrait');

        return $pdf->download('laporan-dashboard-' . now()->format('Y-m-d') . '.pdf');
    }
}