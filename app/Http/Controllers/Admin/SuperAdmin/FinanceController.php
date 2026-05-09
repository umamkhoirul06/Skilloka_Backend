<?php

namespace App\Http\Controllers\Admin\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Booking;
use App\Models\Lpk;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class FinanceController extends Controller
{
    public function index()
    {
        $totalPayments = Payment::count();

        $totalRevenue = Payment::where('status', 'success')
            ->sum('amount');

        $recentPayments = Payment::with(['user', 'tenant'])
            ->latest()
            ->take(10)
            ->get();

        return view(
            'super_admin.finance.index',
            compact('totalPayments', 'totalRevenue', 'recentPayments')
        );
    }

    public function exportPdf(Request $request)
    {
        $period = $request->get('period', 'month');

        $startDate = match($period) {
            'month'   => Carbon::now()->startOfMonth(),
            '3months' => Carbon::now()->subMonths(3),
            '6months' => Carbon::now()->subMonths(6),
            default   => Carbon::now()->startOfYear(),
        };

        $payments = Payment::with(['user', 'tenant', 'booking.schedule.course'])
            ->where('created_at', '>=', $startDate)
            ->orderBy('created_at', 'desc')
            ->get();

        $lpkStats = Lpk::withCount(['courses'])
            ->where('status', 'active')
            ->get();

        $totalRevenue    = $payments->where('status', 'success')->sum('amount');
        $totalPending    = $payments->where('status', 'pending')->sum('amount');
        $totalTransaksi  = $payments->count();

        $pdf = Pdf::loadView('super_admin.finance.pdf', compact(
            'payments', 'lpkStats', 'period', 'startDate',
            'totalRevenue', 'totalPending', 'totalTransaksi'
        ))->setPaper('a4', 'landscape');

        return $pdf->download('finance-report-' . $period . '-' . now()->format('Ymd') . '.pdf');
    }
}