<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Imports Namespace Controller
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CourseController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\LocationController;
use App\Http\Controllers\Api\V1\LpkController;
use App\Http\Controllers\Api\V1\BookingController;
use App\Http\Controllers\Api\V1\BannerController;
use App\Http\Controllers\Api\FavoriteController;
// Perbaikan: Impor PaymentController yang benar
use App\Http\Controllers\Api\PaymentController;

// ==========================================
// 1. PUBLIC ROUTES (Tanpa Token / Bebas Akses)
// ==========================================
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/request-otp', [AuthController::class, 'requestOtp']);
Route::post('/auth/verify-otp', [AuthController::class, 'verifyOtp']);

// Rute Kursus & LPK
Route::get('/courses', [CourseController::class, 'index']);
Route::get('/courses/{id}', [CourseController::class, 'show']);
Route::get('/lpks', [LpkController::class, 'index']);
Route::get('/lpks/{id}', [LpkController::class, 'show']);

// Rute Lokasi dan Kategori
Route::get('/locations', [LocationController::class, 'index']);
Route::get('/categories', [CategoryController::class, 'index']);

// 🔥 FIX: Midtrans Webhook (Sesuai dengan nama method di Controller)
Route::post('/payment/webhook', [PaymentController::class, 'webhook']);


// ==========================================
// 2. PROTECTED ROUTES (Wajib Punya Token)
// ==========================================
Route::middleware('auth:sanctum')->group(function () {

    // Rute Profil & Autentikasi
    Route::get('/auth/me', [AuthController::class, 'me']);
    Route::get('/user/profile', [AuthController::class, 'me']);
    Route::put('/user/profile', [AuthController::class, 'updateProfile']);
    Route::post('/user/profile/photo', [AuthController::class, 'updatePhoto']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);

    // Rute Banners
    Route::get('/banners', [BannerController::class, 'index']);

    // Rute Bookings
    Route::get('/bookings', [BookingController::class, 'index']);
    Route::post('/bookings', [BookingController::class, 'store']);
    Route::get('/bookings/{id}', [BookingController::class, 'show']);
    Route::patch('/bookings/{id}/cancel', [BookingController::class, 'cancel']);
    

    // 🔥 PAYMENT TRANSACTION
    Route::post('/payment/create-transaction', [PaymentController::class, 'createTransaction']);

    // Favorites
    Route::get('/favorites', [FavoriteController::class, 'index']);
    Route::post('/favorites/{course}', [FavoriteController::class, 'toggle']);
});