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
        $tenantId = $user->tenant_id;

        $myLpk = \App\Models\Lpk::where('tenant_id', $tenantId)->first();

        $totalStudents = User::where('tenant_id', $tenantId)->count();
        $totalCourses = Course::where('tenant_id', $tenantId)->count();
        $activeCourses = Course::where('tenant_id', $tenantId)->where('is_active', true)->count();

        $upcomingClasses = CourseSchedule::whereHas('course', fn($q) => $q->where('tenant_id', $tenantId))
            ->where('start_date', '>=', now())
            ->count();

        $pendingBookings = Booking::where('tenant_id', $tenantId)
            ->where('status', 'pending')
            ->count();

        $confirmedBookings = Booking::where('tenant_id', $tenantId)
            ->whereIn('status', ['paid', 'confirmed', 'ongoing', 'completed'])
            ->count();

        $totalRevenue = Booking::where('tenant_id', $tenantId)
            ->whereIn('status', ['paid', 'confirmed', 'ongoing', 'completed'])
            ->sum('total_price');

        $recentBookings = Booking::with(['user', 'course'])
            ->where('tenant_id', $tenantId)
            ->latest()
            ->take(6)
            ->get();

        $upcomingSchedules = CourseSchedule::with('course')
            ->whereHas('course', fn($q) => $q->where('tenant_id', $tenantId))
            ->where('start_date', '>=', now()->toDateString())
            ->orderBy('start_date', 'asc')
            ->take(4)
            ->get();

        $popularCourses = Course::where('tenant_id', $tenantId)
            ->withCount('bookings')
            ->orderBy('bookings_count', 'desc')
            ->take(4)
            ->get();

        // ── Dynamic Chart Data (Last 6 Months) ──
        $chartLabels = [];
        $chartBookings = [];
        $chartRevenue = [];
        for ($i = 5; $i >= 0; $i--) {
            $monthDate = Carbon::now()->subMonths($i);
            $chartLabels[] = $monthDate->translatedFormat('M Y');
            $chartBookings[] = Booking::where('tenant_id', $tenantId)
                ->whereYear('created_at', $monthDate->year)
                ->whereMonth('created_at', $monthDate->month)
                ->count();
            $chartRevenue[] = (int) Booking::where('tenant_id', $tenantId)
                ->whereIn('status', ['paid', 'confirmed', 'ongoing', 'completed', 'Selesai'])
                ->whereYear('created_at', $monthDate->year)
                ->whereMonth('created_at', $monthDate->month)
                ->sum('total_price');
        }

        $bookingDistribution = [
            'Pending' => Booking::where('tenant_id', $tenantId)->whereIn('status', ['pending', 'Menunggu'])->count(),
            'Lunas' => Booking::where('tenant_id', $tenantId)->whereIn('status', ['paid', 'confirmed', 'completed', 'Selesai'])->count(),
            'Batal' => Booking::where('tenant_id', $tenantId)->whereIn('status', ['cancelled', 'Dibatalkan'])->count(),
        ];

        return view('admin.dashboard', compact(
            'totalStudents',
            'totalCourses',
            'activeCourses',
            'upcomingClasses',
            'pendingBookings',
            'confirmedBookings',
            'totalRevenue',
            'recentBookings',
            'upcomingSchedules',
            'popularCourses',
            'myLpk',
            'chartLabels',
            'chartBookings',
            'chartRevenue',
            'bookingDistribution'
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