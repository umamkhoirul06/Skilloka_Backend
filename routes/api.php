<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

// Imports Namespace Controller
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CourseController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\LocationController;
use App\Http\Controllers\Api\V1\LpkController;
use App\Http\Controllers\Api\V1\BookingController;
use App\Http\Controllers\Api\V1\MyCourseController;
use App\Http\Controllers\Api\V1\BannerController;
use App\Http\Controllers\Api\FavoriteController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\V1\ChatbotController;
use App\Http\Controllers\Admin\ProfileController;

// ==========================================
// 1. PUBLIC ROUTES (Tanpa Token / Bebas Akses)
// ==========================================
Route::post('/auth/register', [AuthController::class, 'register'])->middleware('throttle:10,1');
Route::post('/auth/login', [AuthController::class, 'login'])->middleware('throttle:10,1');
Route::post('/auth/request-otp', [AuthController::class, 'requestOtp'])->middleware('throttle:5,1');
Route::post('/auth/verify-otp', [AuthController::class, 'verifyOtp'])->middleware('throttle:10,1');

// Rute Kursus & LPK
Route::get('/courses', [CourseController::class, 'index']);
Route::get('/courses/{id}', [CourseController::class, 'show']);
Route::get('/lpks', [LpkController::class, 'index']);
Route::get('/lpks/{id}', [LpkController::class, 'show']);

// Rute Lokasi dan Kategori
Route::get('/locations', [LocationController::class, 'index']);
Route::get('/categories', [CategoryController::class, 'index']);

// Rute Banners (Public)
Route::get('/banners', [BannerController::class, 'index']);

// Midtrans Webhook
Route::post('/payment/webhook', [PaymentController::class, 'webhook']);

// Chatbot AI Endpoint (Dilindungi Throttle agar kuota API aman)
Route::post('/chatbot/messages', [ChatbotController::class, 'sendMessage'])->middleware('throttle:20,1');
Route::post('/chatbot/message', [ChatbotController::class, 'sendMessage'])->middleware('throttle:20,1');

// ==========================================
// 1.5. MEDIA / STORAGE
// ==========================================
Route::get('/media/{path}', function ($path) {
    if (!Storage::disk('public')->exists($path)) {
        return response()->json([
            'success' => false,
            'message' => 'File tidak ditemukan.',
        ], 404);
    }

    $mimeType = Storage::disk('public')->mimeType($path);
    $file = Storage::disk('public')->get($path);

    return response($file, 200)
        ->header('Content-Type', $mimeType)
        ->header('Access-Control-Allow-Origin', '*')
        ->header('Access-Control-Allow-Methods', 'GET, OPTIONS')
        ->header(
            'Access-Control-Allow-Headers',
            'Origin, Content-Type, Accept, Authorization'
        );
})->where('path', '.*');

// ==========================================
// 2. PROTECTED ROUTES (Wajib Punya Token)
// ==========================================
Route::middleware('auth:sanctum')->group(function () {

    // Rute Profil & Autentikasi
    Route::get('/profile', [ProfileController::class, 'apiShow']);
    Route::get('/auth/me', [AuthController::class, 'me']);
    Route::get('/user/profile', [AuthController::class, 'me']);
    Route::put('/user/profile', [AuthController::class, 'updateProfile']);
    Route::post('/user/profile/photo', [AuthController::class, 'updatePhoto']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);


    // Rute Bookings
    Route::get('/user/bookings', [BookingController::class, 'index']);
    // Rute My Courses / Pembelajaran (PRD 8.8 & SPEC Section E)
    Route::get('/my-courses', [MyCourseController::class, 'index']);
    Route::get('/my-courses/{courseId}/content', [MyCourseController::class, 'content']);
    Route::get('/my-courses/{courseId}/attendance', [MyCourseController::class, 'attendance']);
    Route::get('/my-courses/{courseId}/certificate', [MyCourseController::class, 'certificate']);

    Route::get('/bookings', [BookingController::class, 'index']);
    Route::post('/bookings', [BookingController::class, 'store']);
    Route::get('/bookings/{id}', [BookingController::class, 'show']);
    Route::patch('/bookings/{id}/cancel', [BookingController::class, 'cancel']);

    // Payment Transaction
    Route::post('/bookings/{id}/payment', [PaymentController::class, 'createTransaction']);
    Route::post('/payment/create-transaction', [PaymentController::class, 'createTransaction']);
    Route::post('/payment/confirm-dummy', [PaymentController::class, 'confirmDummy']);

    // Favorites
    Route::get('/favorites', [FavoriteController::class, 'index']);
    Route::post('/favorites/{course}', [FavoriteController::class, 'toggle']);
});