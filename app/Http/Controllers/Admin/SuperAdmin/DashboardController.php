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
)
->latest()
->take(5)
->get();



$monthlyLabels = [
'Jan','Feb','Mar','Apr','May','Jun',
'Jul','Aug','Sep','Oct','Nov','Dec'
];


$monthlyLpk = [];
$monthlyUsers = [];
$monthlyCourses = [];


foreach(range(1,12) as $month){

$monthlyLpk[] = Lpk::whereMonth(
'created_at',
$month
)->count();


$monthlyUsers[] = User::whereMonth(
'created_at',
$month
)->count();


$monthlyCourses[] = Course::whereMonth(
'created_at',
$month
)->count();

}



return view(

'super_admin.dashboard',

compact(

'totalLpk',
'totalCourses',
'totalUsers',
'pendingVerifications',
'pendingLpks',

'monthlyLabels',
'monthlyLpk',
'monthlyUsers',
'monthlyCourses'

)

);


}

}