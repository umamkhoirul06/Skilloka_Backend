<?php

use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| CONTROLLERS
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\CourseScheduleController;

use App\Http\Controllers\Admin\SuperAdmin\DashboardController;
use App\Http\Controllers\Admin\SuperAdmin\TenantController;
use App\Http\Controllers\Admin\SuperAdmin\VerificationController;
use App\Http\Controllers\Admin\SuperAdmin\UserController;
use App\Http\Controllers\Admin\SuperAdmin\FinanceController;
use App\Http\Controllers\Admin\SuperAdmin\LogController;
use App\Http\Controllers\Admin\SuperAdmin\SettingsController;



/*
|--------------------------------------------------------------------------
| HOME
|--------------------------------------------------------------------------
*/

Route::get('/', function () {

    return redirect()->route('admin.login');

});



/*
|--------------------------------------------------------------------------
| LOGIN
|--------------------------------------------------------------------------
*/

Route::get(
'/admin/login',
[AdminAuthController::class,'showLogin']
)->name('admin.login');


Route::post(
'/admin/login',
[AdminAuthController::class,'login']
)->name('admin.login.submit');


Route::post(
'/logout',
[AdminAuthController::class,'logout']
)->name('logout');



/*
|--------------------------------------------------------------------------
| ADMIN LPK
|--------------------------------------------------------------------------
*/

Route::middleware(['auth','admin-lpk'])
->prefix('admin')
->name('admin.')
->group(function () {


Route::get(
'/dashboard',
[AdminAuthController::class,'dashboard']
)->name('dashboard');



Route::get(
'/profile',
function(){

return view('admin.profile');

}
)->name('profile');



Route::resource(
'courses',
CourseController::class
);



Route::resource(
'students',
StudentController::class
);



Route::resource(
'bookings',
BookingController::class
)->except(['edit','update']);



Route::patch(
'/bookings/{booking}/status',
[BookingController::class,'updateStatus']
)->name('bookings.status');



Route::resource(
'course-schedules',
CourseScheduleController::class
);


});



/*
|--------------------------------------------------------------------------
| SUPER ADMIN
|--------------------------------------------------------------------------
*/

Route::middleware(['auth','super-admin'])
->prefix('super-admin')
->name('super.')
->group(function () {



Route::get(
'/dashboard',
[DashboardController::class,'index']
)->name('dashboard');



Route::get(
'/tenants',
[TenantController::class,'index']
)->name('tenants');



Route::get(
'/verifications',
[VerificationController::class,'index']
)->name('verifications');



Route::post(
'/verifications/{id}/approve',
[VerificationController::class,'approve']
)->name('verifications.approve');



Route::post(
'/verifications/{id}/reject',
[VerificationController::class,'reject']
)->name('verifications.reject');



Route::get(
'/users',
[UserController::class,'index']
)->name('users');



Route::get(
'/finance',
[FinanceController::class,'index']
)->name('finance');



Route::get(
'/logs',
[LogController::class,'index']
)->name('logs');



Route::get(
'/settings',
[SettingsController::class,'index']
)->name('settings');


});