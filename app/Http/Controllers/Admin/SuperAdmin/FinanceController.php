<?php

namespace App\Http\Controllers\Admin\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class FinanceController extends Controller
{
    /**
     * Tampilan Dashboard Finansial Super Admin
     */
    public function index(Request $request)
    {
        $period = $request->query('period', 'month');
        $query = Payment::query();

        // Logika Filter Periode
        if ($period == '3months')
            $query->where('created_at', '>=', now()->subMonths(3));
        elseif ($period == '6months')
            $query->where('created_at', '>=', now()->subMonths(6));
        elseif ($period == 'year')
            $query->where('created_at', '>=', now()->startOfYear());
        else
            $query->where('created_at', '>=', now()->startOfMonth());

        $totalPayments = $query->count();
        $totalRevenue = $query->where('status', 'success')->sum('amount');
        $recentPayments = $query->with('user')->latest()->limit(10)->get();

        return view('super-admin.finance.index', compact('totalPayments', 'totalRevenue', 'recentPayments', 'period'));
    }
    /**
     * Export PDF Rekapan Finansial
     */
    public function exportPdf()
    {
        try {
            $transactions = Payment::with(['user', 'booking.schedule.course'])->latest()->get();
            $totalRevenue = Payment::where('status', 'success')->sum('amount');

            // 🔥 Pastikan file PDF view sudah ada
            if (!View::exists('super-admin.finance.pdf')) {
                return back()->with('error', 'Template PDF belum tersedia.');
            }

            $pdf = \PDF::loadView('super-admin.finance.pdf', compact('transactions', 'totalRevenue'));
            return $pdf->download('rekapan-finansial-skilloka-' . now()->format('Y-m-d') . '.pdf');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal export PDF: ' . $e->getMessage());
        }
    }
}