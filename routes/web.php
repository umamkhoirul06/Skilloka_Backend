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
use App\Http\Controllers\Admin\ProfileController;

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
| LOGIN ADMIN
|--------------------------------------------------------------------------
*/

Route::get('/admin/login',

[AdminAuthController::class,'showLogin']

)->name('admin.login');



Route::post('/admin/login',

[AdminAuthController::class,'login']

)->name('admin.login.submit');



Route::post('/logout',

[AdminAuthController::class,'logout']

)->name('logout');



/*
|--------------------------------------------------------------------------
| ADMIN LPK AREA
|--------------------------------------------------------------------------
*/

Route::middleware(['auth','admin-lpk'])

->prefix('admin')

->name('admin.')

->group(function () {



/*
|--------------------------------------------------------------------------
| DASHBOARD
|--------------------------------------------------------------------------
*/

Route::get('/dashboard',

[AdminAuthController::class,'dashboard']

)->name('dashboard');



/*
|--------------------------------------------------------------------------
| PROFILE LPK
|--------------------------------------------------------------------------
*/

Route::get('/profile',

[ProfileController::class,'index']

)->name('profile');



Route::post('/profile/update',

[ProfileController::class,'update']

)->name('profile.update');



/*
|--------------------------------------------------------------------------
| COURSES
|--------------------------------------------------------------------------
*/

Route::resource('courses',

CourseController::class

);



/*
|--------------------------------------------------------------------------
| STUDENTS
|--------------------------------------------------------------------------
*/

Route::resource('students',

StudentController::class

);



/*
|--------------------------------------------------------------------------
| BOOKINGS
|--------------------------------------------------------------------------
*/

Route::resource('bookings',

BookingController::class

)->except(['edit','update']);



Route::patch('/bookings/{booking}/status',

[BookingController::class,'updateStatus']

)->name('bookings.status');



/*
|--------------------------------------------------------------------------
| COURSE SCHEDULE
|--------------------------------------------------------------------------
*/

Route::resource('course-schedules',

CourseScheduleController::class

);



});



/*
|--------------------------------------------------------------------------
| SUPER ADMIN AREA
|--------------------------------------------------------------------------
*/

Route::middleware(['auth','super-admin'])

->prefix('super-admin')

->name('super.')

->group(function () {



/*
|--------------------------------------------------------------------------
| DASHBOARD
|--------------------------------------------------------------------------
*/

Route::get('/dashboard',

[DashboardController::class,'index']

)->name('dashboard');



/*
|--------------------------------------------------------------------------
| TENANTS (LPK)
|--------------------------------------------------------------------------
*/

Route::get('/tenants',

[TenantController::class,'index']

)->name('tenants');



/*
|--------------------------------------------------------------------------
| VERIFICATION LPK
|--------------------------------------------------------------------------
*/

Route::get('/verifications',

[VerificationController::class,'index']

)->name('verifications');



Route::post('/verifications/{id}/approve',

[VerificationController::class,'approve']

)->name('verifications.approve');



Route::post('/verifications/{id}/reject',

[VerificationController::class,'reject']

)->name('verifications.reject');



/*
|--------------------------------------------------------------------------
| USERS
|--------------------------------------------------------------------------
*/

Route::get('/users',

[UserController::class,'index']

)->name('users');



/*
|--------------------------------------------------------------------------
| FINANCE
|--------------------------------------------------------------------------
*/

Route::get('/finance',

[FinanceController::class,'index']

)->name('finance');



/*
|--------------------------------------------------------------------------
| LOGS
|--------------------------------------------------------------------------
*/

Route::get('/logs',

[LogController::class,'index']

)->name('logs');



/*
|--------------------------------------------------------------------------
| SETTINGS
|--------------------------------------------------------------------------
*/

Route::get('/settings',

[SettingsController::class,'index']

)->name('settings');



});