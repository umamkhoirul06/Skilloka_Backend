<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use App\Models\User;

class AuthController extends BaseController
{
    public function sendOtp(Request $request)
    {
        $request->validate([
            'phone' => 'required'
        ]);

        return $this->success(null, 'Kode OTP berhasil dikirim (Gunakan: 1234)');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'phone' => 'required',
            'otp' => 'required'
        ]);

        if ($request->otp === '1234') {
            $user = User::where('phone', $request->phone)->first();

            if (!$user) {
                return $this->error('Nomor HP tidak terdaftar!', 404);
            }

            $token = $user->createToken('MobileAppToken')->plainTextToken;

            $data = [
                'access_token' => $token,
                'refresh_token' => $token,
                'user' => $user
            ];

            return $this->success($data, 'Login berhasil!');
        }

        return $this->error('Kode OTP salah!', 401);
    }
}