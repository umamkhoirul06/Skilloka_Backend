<?php

namespace App\Http\Controllers\Admin\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Tenant;
use App\Models\Setting;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class FinanceController extends Controller
{
    public function index(Request $request)
    {
        $statusFilter = $request->query('status');
        $tenantFilter = $request->query('tenant_id');
        $search = $request->query('search');

        // Query utama pembayaran
        $query = Payment::with([
            'user',
            'tenant.lpk',
            'booking.course.category',
            'booking.schedule'
        ]);

        if ($statusFilter && $statusFilter !== 'all') {
            $query->where('status', $statusFilter);
        }

        if ($tenantFilter) {
            $query->where('tenant_id', $tenantFilter);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('external_id', 'like', "%{$search}%")
                  ->orWhere('provider', 'like', "%{$search}%")
                  ->orWhere('method', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  })
                  ->orWhereHas('booking.course', function ($cq) use ($search) {
                      $cq->where('title', 'like', "%{$search}%");
                  });
            });
        }

        $payments = $query->latest()->paginate(12)->withQueryString();

        // Ambil persentase fee dari pengaturan platform
        $setting = Setting::first();
        $platformFeeRate = (float) ($setting?->platform_fee ?? 10);
        $platformFeeMultiplier = $platformFeeRate / 100;

        // Metrik keuangan global
        $totalGrossVolume = Payment::where('status', 'success')
            ->sum('amount');

        $platformFeeTotal = round(
            $totalGrossVolume * $platformFeeMultiplier
        );

        $lpkPayoutTotal = $totalGrossVolume - $platformFeeTotal;

        $stats = [
            'gross_volume' => $totalGrossVolume,
            'platform_fee' => $platformFeeTotal,
            'lpk_payout' => $lpkPayoutTotal,
            'success_count' => Payment::where('status', 'success')->count(),
            'pending_count' => Payment::where('status', 'pending')->count(),
            'failed_count' => Payment::whereIn(
                'status',
                ['failed', 'expired', 'cancel']
            )->count(),
            'total_transactions' => Payment::count(),
        ];

        // Daftar tenant untuk dropdown filter
        $tenants = Tenant::with('lpk')
            ->orderBy('name')
            ->get();

        // Data grafik enam bulan terakhir
        $chartMonths = [];
        $chartRevenues = [];
        $chartFees = [];

        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthLabel = $month->translatedFormat('M Y');

            $monthlyGross = Payment::where('status', 'success')
                ->whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->sum('amount');

            $chartMonths[] = $monthLabel;
            $chartRevenues[] = (int) $monthlyGross;
            $chartFees[] = (int) round(
                $monthlyGross * $platformFeeMultiplier
            );
        }

        // Distribusi metode pembayaran
        $paymentMethodsCount = [
            'QRIS' => Payment::where('method', 'qris')->count(),
            'Virtual Account' => Payment::where(
                'method',
                'bank_transfer'
            )->count(),
            'E-Wallet' => Payment::where('method', 'gopay')
                ->orWhere('method', 'ewallet')
                ->count(),
        ];

        return view('super_admin.finance.index', compact(
            'payments',
            'stats',
            'tenants',
            'statusFilter',
            'tenantFilter',
            'search',
            'chartMonths',
            'chartRevenues',
            'chartFees',
            'paymentMethodsCount',
            'platformFeeRate'
        ));
    }

    /**
     * Ekspor laporan keuangan ke PDF
     */
    public function exportPdf(Request $request)
    {
        $payments = Payment::with([
            'user',
            'tenant.lpk',
            'booking.course'
        ])
            ->where('status', 'success')
            ->latest()
            ->get();

        // Gunakan persentase yang sama dengan pengaturan platform
        $setting = Setting::first();
        $platformFeeRate = (float) ($setting?->platform_fee ?? 10);
        $platformFeeMultiplier = $platformFeeRate / 100;

        $totalGrossVolume = $payments->sum('amount');

        $platformFeeTotal = round(
            $totalGrossVolume * $platformFeeMultiplier
        );

        $lpkPayoutTotal = $totalGrossVolume - $platformFeeTotal;

        $pdf = Pdf::loadView(
            'super_admin.finance.report_pdf',
            compact(
                'payments',
                'totalGrossVolume',
                'platformFeeTotal',
                'lpkPayoutTotal',
                'platformFeeRate'
            )
        )->setPaper('a4', 'portrait');

        return $pdf->download(
            'laporan-keuangan-skilloka-' .
            now()->format('Y-m-d') . '.pdf'
        );
    }
}