<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CourseController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\LocationController;
use App\Http\Controllers\Api\V1\LpkController;

Route::prefix('v1')->group(function () {
    // ─── Auth Public ─────────────────────────────────────────
    Route::post('/auth/register', [AuthController::class, 'register']);
    Route::post('/auth/login',    [AuthController::class, 'login']);
    Route::post('/auth/request-otp', [AuthController::class, 'requestOtp']);
    Route::post('/auth/verify-otp',  [AuthController::class, 'verifyOtp']);

    // ─── Public Discovery (tanpa login) ───────────────────────
    Route::get('/courses',         [CourseController::class,  'index']);
    Route::get('/courses/{id}',    [CourseController::class,  'show']);
    Route::get('/lpks',            [LpkController::class,     'index']);
    Route::get('/lpks/{id}',       [LpkController::class,     'show']);
    Route::get('/categories',      [CategoryController::class,'index']);
    Route::get('/locations',       [LocationController::class,'index']);

    // ─── Protected (perlu login) ───────────────────────────────
    Route::middleware('auth:sanctum')->group(function () {
        // Auth
        Route::get('/auth/me',       [AuthController::class, 'me']);
        Route::post('/auth/logout',  [AuthController::class, 'logout']);

        // Bookings
        Route::get('/bookings',              [\App\Http\Controllers\Api\V1\BookingController::class, 'index']);
        Route::post('/bookings',             [\App\Http\Controllers\Api\V1\BookingController::class, 'store']);
        Route::get('/bookings/{id}',         [\App\Http\Controllers\Api\V1\BookingController::class, 'show']);
        Route::patch('/bookings/{id}/cancel',[\App\Http\Controllers\Api\V1\BookingController::class, 'cancel']);
    });
});
