<?php

namespace App\Http\Controllers\Admin\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class FinanceController extends Controller
{
    /**
     * Tampilan Dashboard Finansial Super Admin
     */
    public function index()
    {
        // 1. Total Transaksi: Menghitung SEMUA data pendaftaran yang masuk (baik pending maupun lunas)
        $totalTransactions = Payment::count();

        // 2. Total Revenue: Hanya menjumlahkan dana dari transaksi yang SUDAH LUNAS (success)
        $totalRevenue = Payment::where('status', 'success')->sum('amount');

        // 3. Transaksi Terbaru: Menampilkan semua riwayat pembayaran sebagai bukti rekapan platform
        $transactions = Payment::with(['user', 'booking.schedule.course'])
            ->latest()
            ->paginate(10);

        return view('super-admin.finance.index', compact('totalTransactions', 'totalRevenue', 'transactions'));
    }

    /**
     * Export PDF Rekapan Finansial untuk Super Admin
     */
    public function exportPdf()
    {
        $transactions = Payment::with(['user', 'booking.schedule.course'])->latest()->get();
        $totalRevenue = Payment::where('status', 'success')->sum('amount');

        $pdf = \PDF::loadView('super-admin.finance.pdf', compact('transactions', 'totalRevenue'));
        return $pdf->download('rekapan-finansial-skilloka-' . now()->format('Y-m-d') . '.pdf');
    }
}