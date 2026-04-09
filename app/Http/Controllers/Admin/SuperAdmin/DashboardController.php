<?php

namespace App\Http\Controllers\Admin\SuperAdmin;

use App\Http\Controllers\Controller;

use App\Models\Lpk;
use App\Models\User;
use App\Models\Course;

class DashboardController extends Controller
{

public function index()
{

$totalLpk = Lpk::count();

$totalCourses = Course::count();

$totalUsers = User::count();

$pendingVerifications = Lpk::where(
'status_verifikasi',
'pending'
)->count();



$pendingLpks = Lpk::where(
'status_verifikasi',
'pending'
)->latest()
->take(5)
->get();



return view(

'super_admin.dashboard',

compact(

'totalLpk',
'totalCourses',
'totalUsers',
'pendingVerifications',
'pendingLpks'

)

);

}

}