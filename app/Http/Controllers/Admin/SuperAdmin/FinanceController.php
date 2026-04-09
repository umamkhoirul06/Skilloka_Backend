<?php

namespace App\Http\Controllers\Admin\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Payment;

class FinanceController extends Controller
{

    public function index()
    {

        // total jumlah transaksi
        $totalPayments = Payment::count();

        // total revenue
        $totalRevenue = Payment::sum('amount');

        // list transaksi terbaru
        $recentPayments = Payment::latest()
            ->take(10)
            ->get();

        return view(
            'super_admin.finance.index',
            compact(
                'totalPayments',
                'totalRevenue',
                'recentPayments'
            )
        );

    }

}