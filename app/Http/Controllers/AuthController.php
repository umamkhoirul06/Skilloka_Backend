<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController; // Memanggil BaseController milikmu
use Illuminate\Http\Request;
use App\Models\User;

class AuthController extends BaseController // Ubah extends Controller menjadi BaseController
{
    // 1. Fungsi untuk menerima No HP dan pura-pura kirim OTP
    public function sendOtp(Request $request)
    {
        $request->validate([
            'phone' => 'required'
        ]);

        // Menggunakan fungsi success() dari BaseController kamu
        return $this->success(null, 'Kode OTP berhasil dikirim (Gunakan: 1234)');
    }

    // 2. Fungsi untuk memverifikasi OTP dan membuat Token Login
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'phone' => 'required',
            'otp' => 'required'
        ]);

        // CEK OTP DUMMY
        if ($request->otp === '1234') {

            // Cari user berdasarkan no HP
            $user = User::where('phone', $request->phone)->first();

            // Jika user belum ada
            if (!$user) {
                return $this->error('Nomor HP tidak terdaftar!', 404);
            }

            // Buat Token menggunakan Laravel Sanctum
            $token = $user->createToken('MobileAppToken')->plainTextToken;

            // Bungkus data yang mau dikirim
            $data = [
                'access_token' => $token,
                'refresh_token' => $token, // Dummy refresh token
                'user' => $user
            ];

            // Menggunakan fungsi success() dari BaseController kamu
            return $this->success($data, 'Login berhasil!');
        }

        // Jika OTP bukan 1234, gunakan fungsi error() dari BaseController
        return $this->error('Kode OTP salah!', 401);
    }
}