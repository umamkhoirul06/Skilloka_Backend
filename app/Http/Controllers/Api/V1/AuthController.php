<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\BaseController;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

class AuthController extends BaseController
{
    public function register(Request $request)
    {
        // Hanya validasi name dan phone
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|unique:users,phone',
        ]);

        $user = User::create([
            'name' => $request->name,
            'phone' => $request->phone,
        ]);

        $user->assignRole('student');

        return $this->success([
            'user' => new UserResource($user)
        ], 'Pendaftaran berhasil. Silakan request OTP untuk login.');
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
            'phone' => 'required|string',
        ]);

        $user = User::where('phone', $request->phone)->first();

        // Jika nomor TIDAK ada di database, return 404
        if (!$user) {
            return $this->error('Nomor belum terdaftar.', 404);
        }

        // Generate 4 digit angka random
        $otpCode = rand(1000, 9999);

        // Simpan OTP ke Cache Laravel (TTL 5 menit)
        Cache::put('otp_' . $request->phone, $otpCode, now()->addMinutes(5));

        // Tembak API Telegram
        $telegramToken = env('TELEGRAM_BOT_TOKEN');
        $chatId = env('TELEGRAM_CHAT_ID');

        if ($telegramToken && $chatId) {
            Http::post("https://api.telegram.org/bot{$telegramToken}/sendMessage", [
                'chat_id' => $chatId,
                'text' => "🔔 *Skilloka OTP Login*\n\nNomor HP: {$request->phone}\nKode OTP Anda adalah: *{$otpCode}*\n\n_Kode ini berlaku selama 5 menit._",
                'parse_mode' => 'Markdown'
            ]);
        }

        return $this->success(null, 'OTP berhasil dikirim.');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'otp_code' => 'required|string',
        ]);

        // Ambil OTP dari Cache
        $cachedOtp = Cache::get('otp_' . $request->phone);

        // Cocokkan dengan input user
        if (!$cachedOtp || $cachedOtp != $request->otp_code) {
            return $this->error('Kode OTP salah atau sudah kedaluwarsa.', 400);
        }

        $user = User::where('phone', $request->phone)->first();

        if (!$user) {
            return $this->error('Nomor HP tidak ditemukan.', 404);
        }

        // Hapus OTP dari cache setelah berhasil diverifikasi
        Cache::forget('otp_' . $request->phone);

        // Generate Token Sanctum
        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->success([
            'user' => new UserResource($user),
            'token' => $token,
        ], 'Verifikasi OTP berhasil, login sukses.');
    }
}