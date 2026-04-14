<?php

namespace App\Http\Controllers\Admin\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Payment;

class FinanceController extends Controller
{

public function index()
{

$totalPayments = Payment::count();

$totalRevenue = Payment::sum('amount');

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