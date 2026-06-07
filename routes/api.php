<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Imports Namespace Controller dengan benar
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CourseController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\LocationController;
use App\Http\Controllers\Api\V1\LpkController;
use App\Http\Controllers\Api\V1\BookingController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\V1\BannerController;
use App\Http\Controllers\Api\FavoriteController;

// ==========================================
// 1. PUBLIC ROUTES (Tanpa Token / Bebas Akses)
// ==========================================
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/request-otp', [AuthController::class, 'requestOtp']);
Route::post('/auth/verify-otp', [AuthController::class, 'verifyOtp']);

// Rute Kursus
Route::get('/courses', [CourseController::class, 'index']);
Route::get('/courses/{id}', [CourseController::class, 'show']);

// Rute LPK
Route::get('/lpks', [LpkController::class, 'index']);
Route::get('/lpks/{id}', [LpkController::class, 'show']);

// Rute Lokasi dan Kategori
Route::get('/locations', [LocationController::class, 'index']);
Route::get('/categories', [CategoryController::class, 'index']);

// Midtrans Payment Webhook (Public)
Route::post('/payment/callback', [PaymentController::class, 'callback']);


// ==========================================
// 2. PROTECTED ROUTES (Wajib Punya Token)
// ==========================================
Route::middleware('auth:sanctum')->group(function () {

    // Rute Profil & Autentikasi
    Route::get('/auth/me', [AuthController::class, 'me']);
    Route::get('/user/profile', [AuthController::class, 'me']); // Alias untuk mobile
    Route::put('/user/profile', [AuthController::class, 'updateProfile']);
    Route::post('/user/profile/photo', [AuthController::class, 'updatePhoto']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/banners', [BannerController::class, 'index']); // Rute untuk menampilkan Banner

    // Rute Bookings
    Route::get('/bookings', [BookingController::class, 'index']);
    Route::get('/user/bookings', [BookingController::class, 'index']); // Alias untuk mobile
    Route::post('/bookings', [BookingController::class, 'store']);
    Route::get('/bookings/{id}', [BookingController::class, 'show']);
    Route::patch('/bookings/{id}/cancel', [BookingController::class, 'cancel']);

    // Midtrans Payment Transactions
    Route::post('/payment/create-transaction', [PaymentController::class, 'createTransaction']);

    //favorite

    Route::get('/favorites', [
        FavoriteController::class,
        'index'
    ]);

    Route::post('/favorites/{course}', [
        FavoriteController::class,
        'toggle'
    ]);

});
    