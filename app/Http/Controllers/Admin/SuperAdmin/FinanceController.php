<?php

namespace App\Http\Controllers\Admin\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Payment;

class FinanceController extends Controller
{
    public function index()
    {
        // 1. Ambil data total tanpa filter yang membatasi
        $totalPayments = Payment::count();
        $totalRevenue = Payment::where('status', 'success')->sum('amount');

        // 2. Ambil transaksi terbaru (arsip) dengan memuat relasi yang benar
        $recentPayments = Payment::with(['user', 'booking.schedule.course'])
            ->latest()
            ->get(); // Hapus ->limit() untuk memastikan semua data muncul

        // 3. Panggil view dengan path yang sudah dipastikan benar
        return view('super-admin.finance.index', compact('totalPayments', 'totalRevenue', 'recentPayments'));
    }
}