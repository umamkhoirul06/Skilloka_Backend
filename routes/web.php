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
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\CourseScheduleController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\BannerController; // 🔥 WAJIB INI
use App\Http\Controllers\Admin\ContentController;
use App\Http\Controllers\Admin\AttendanceController;
use App\Http\Controllers\Admin\CertificateController;
use App\Http\Controllers\Web\PublicController;
use App\Http\Controllers\Web\ChatbotController;

use App\Http\Controllers\Admin\SuperAdmin\DashboardController;
use App\Http\Controllers\Admin\SuperAdmin\TenantController;
use App\Http\Controllers\Admin\SuperAdmin\VerificationController;
use App\Http\Controllers\Admin\SuperAdmin\UserController;
use App\Http\Controllers\Admin\SuperAdmin\FinanceController;
use App\Http\Controllers\Admin\SuperAdmin\LogController;
use App\Http\Controllers\Admin\SuperAdmin\SettingsController;
use App\Http\Controllers\Admin\SuperAdmin\CategoryController as SuperCategoryController;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Password;
use Illuminate\Http\Request;

// Form lupa password
Route::get('/admin/forgot-password', function () {
    return view('admin.auth.forgot-password');
})->name('password.request');

// Kirim link reset
Route::post('/admin/forgot-password', function (Request $request) {
    $request->validate(['email' => 'required|email']);

    $status = Password::sendResetLink(
        $request->only('email')
    );

    return $status === Password::RESET_LINK_SENT
        ? back()->with('success', 'Link reset password telah dikirim ke email Anda!')
        : back()->withErrors(['email' => __($status)]);
})->name('password.email');

// Form reset password
Route::get('/admin/reset-password/{token}', function (string $token) {
    return view('admin.auth.reset-password', ['token' => $token]);
})->name('password.reset');

// Proses reset password
Route::post('/admin/reset-password', function (Request $request) {
    $request->validate([
        'token' => 'required',
        'email' => 'required|email',
        'password' => 'required|confirmed|min:8',
    ]);

    $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function ($user, $password) {
            $user->forceFill(['password' => bcrypt($password)])->save();
        }
    );

    return $status === Password::PASSWORD_RESET
        ? redirect()->route('admin.login')->with('success', 'Password berhasil direset!')
        : back()->withErrors(['email' => __($status)]);
})->name('password.update');

/*
|--------------------------------------------------------------------------
| PUBLIC WEB PAGES (PRD Section 6)
|--------------------------------------------------------------------------
*/

Route::get('/', [PublicController::class, 'index'])->name('home');
Route::get('/kursus', [PublicController::class, 'courses'])->name('public.courses');
Route::get('/kursus/{id}', [PublicController::class, 'courseDetail'])->name('public.course.detail');
Route::get('/lpk', [PublicController::class, 'lpks'])->name('public.lpks');
Route::get('/lpk/{id}', [PublicController::class, 'lpkDetail'])->name('public.lpk.detail');
Route::get('/untuk-lpk', [PublicController::class, 'forLpk'])->name('public.for_lpk');
Route::get('/tentang', [PublicController::class, 'about'])->name('public.about');
Route::get('/daftar-lpk', [AdminAuthController::class, 'showRegister'])->name('lpk.register');
Route::get('/daftar-lpk/sukses', [PublicController::class, 'registerSuccess'])->name('lpk.register.success');
Route::post('/chatbot/message', [ChatbotController::class, 'sendMessage'])->name('chatbot.message');

/*
|--------------------------------------------------------------------------
| LOGIN & REGISTER ADMIN
|--------------------------------------------------------------------------
*/

Route::get('/admin/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');

Route::post('/admin/login', [AdminAuthController::class, 'login'])
    ->middleware('throttle:5,1')
    ->name('admin.login.submit');

Route::get('/admin/register', [AdminAuthController::class, 'showRegister'])->name('admin.register');

Route::post('/admin/register', [AdminAuthController::class, 'register'])->name('admin.register.submit');

Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| ADMIN LPK AREA
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin_lpk'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [AdminDashboardController::class, 'index'])
            ->name('dashboard');

        Route::get('/dashboard/pdf', [AdminDashboardController::class, 'downloadPdf'])
            ->name('dashboard.pdf');

        Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
        Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

        Route::resource('courses', CourseController::class);
        Route::resource('students', StudentController::class)
            ->only([
                'index',
                'show',
                'edit',
                'update',
                'destroy'
            ]);
        Route::resource('banners', BannerController::class);
        Route::get('/dashboard/report/pdf', [AdminDashboardController::class, 'exportPdf'])
            ->name('admin.dashboard.pdf');
        Route::resource('bookings', BookingController::class)->except(['edit', 'update']);
        Route::patch('/bookings/{booking}/status', [BookingController::class, 'updateStatus'])->name('bookings.status');
        /*
|--------------------------------------------------------------------------
| PAYMENTS
|--------------------------------------------------------------------------
*/

        Route::resource('payments', PaymentController::class)
            ->only([
                'index',
                'show'
            ]);

        Route::patch(
            '/payments/{payment}/status',
            [PaymentController::class, 'updateStatus']
        )->name('payments.status');

        Route::resource('course-schedules', CourseScheduleController::class);

        // Content / Materi Kursus
        Route::resource('contents', ContentController::class);

        // Presensi / Absensi Siswa
        Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
        Route::get('/attendance/session', [AttendanceController::class, 'session'])->name('attendance.session');
        Route::post('/attendance', [AttendanceController::class, 'store'])->name('attendance.store');
        Route::get('/attendance/recap/{courseId}', [AttendanceController::class, 'recap'])->name('attendance.recap');

        // Sertifikat Siswa
        Route::resource('certificates', CertificateController::class);
    });



Route::patch('/students/booking/{bookingId}/status', [
    App\Http\Controllers\Admin\StudentController::class,
    'updateStatusBelajar'
])->name('admin.students.updateStatusBelajar');
/*
|--------------------------------------------------------------------------
| SUPER ADMIN AREA
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:super_admin'])
    ->prefix('super-admin')
    ->name('super.')
    ->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        Route::get('/tenants', [TenantController::class, 'index'])
            ->name('tenants');

        Route::get('/tenants/report/pdf', [TenantController::class, 'exportPdf'])
            ->name('super.tenants.pdf');

        Route::get('/tenants/{id}', [TenantController::class, 'show'])
            ->name('tenants.show');



        Route::delete('/tenants/{id}', [TenantController::class, 'destroy'])
            ->name('tenants.delete');
        Route::get(
            '/verifications',
            [VerificationController::class, 'index']
        )->name('verifications');

        Route::get(
            '/verifications/{id}',
            [VerificationController::class, 'show']
        )->name('verifications.show');

        Route::post(
            '/verifications/{id}/approve',
            [VerificationController::class, 'approve']
        )->name('verifications.approve');

        Route::post(
            '/verifications/{id}/reject',
            [VerificationController::class, 'reject']
        )->name('verifications.reject');

        Route::get('/users', [UserController::class, 'index'])->name('users');

        Route::get('/finance', [FinanceController::class, 'index'])->name('finance');

        Route::get('/logs', [LogController::class, 'index'])->name('logs');

        Route::get('/settings', [SettingsController::class, 'index'])
            ->name('settings');

        Route::post('/settings', [SettingsController::class, 'update'])
            ->name('settings.update');
        Route::get('/finance/report/pdf', [\App\Http\Controllers\Admin\SuperAdmin\FinanceController::class, 'exportPdf'])
            ->name('finance.pdf');

        // Kategori Kursus
        Route::resource('categories', SuperCategoryController::class);
    });