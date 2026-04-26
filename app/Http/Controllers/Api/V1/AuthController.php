<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\BaseController;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\ValidationException;

class AuthController extends BaseController
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|unique:users,phone',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
        ]);

        $user = User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole('student');

        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->success([
            'user' => new UserResource($user),
            'token' => $token,
        ], 'User registered successfully');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email_or_phone' => 'required',
            'password' => 'required',
        ]);

        $field = filter_var($request->email_or_phone, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        $user = User::where($field, $request->email_or_phone)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email_or_phone' => ['Invalid credentials provided.'],
            ]);
        }

        // Delete old tokens? Usually optional.
        // $user->tokens()->delete();

        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->success([
            'user' => new UserResource($user),
            'token' => $token,
        ], 'Login successful');
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return $this->success(null, 'Logged out successfully');
    }

    public function me(Request $request)
    {
        return $this->success(new UserResource($request->user()));
    }

    public function requestOtp(Request $request)
    {
        $request->validate([
            'phone_number' => 'required|string|min:9|max:15',
        ]);

        $phoneNumber = $request->phone_number;

        // Simpan OTP dummy statis '123456' ke Cache Laravel selama 5 menit
        Cache::put('otp_' . $phoneNumber, '123456', now()->addMinutes(5));

        return $this->success(null, 'OTP berhasil dikirim ke ' . $phoneNumber);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'phone_number' => 'required|string',
            'otp_code' => 'required|string',
        ]);

        $phoneNumber = $request->phone_number;
        $otpCode = $request->otp_code;

        // 1. Cek kecocokan OTP di Cache
        $cachedOtp = Cache::get('otp_' . $phoneNumber);

        if (!$cachedOtp || $cachedOtp !== $otpCode) {
            return $this->error('Kode OTP salah atau sudah kedaluwarsa.', 400);
        }

        // Hapus OTP dari cache agar tidak bisa dipakai 2 kali
        Cache::forget('otp_' . $phoneNumber);

        // 2. Cari user berdasarkan phone_number, jika tidak ada, buat baru otomatis
        $user = User::firstOrCreate(
            ['phone_number' => $phoneNumber],
            [
                'name' => 'User ' . substr($phoneNumber, -4), // Default nama dari 4 digit no HP
            ]
        );

        // Berikan role 'student' jika ini adalah user yang baru saja terdaftar/dibuat
        if ($user->wasRecentlyCreated) {
            $user->assignRole('student');
        }

        // 3. Rilis Token Sanctum
        $token = $user->createToken('auth_token')->plainTextToken;

        // 4. Kembalikan Respon JSON
        return $this->success([
            'user' => new UserResource($user),
            'token' => $token,
        ], 'Verifikasi OTP berhasil.');
    }
}
