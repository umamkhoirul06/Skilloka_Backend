<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\BaseController;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

class AuthController extends BaseController
{
    // ─── Register ─────────────────────────────────────────────────────────────
    public function register(Request $request)
    {
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

    // ─── Login biasa (email/password) ─────────────────────────────────────────
    public function login(Request $request)
    {
        $request->validate([
            'email_or_phone' => 'required',
            'password' => 'required',
        ]);

        $field = filter_var($request->email_or_phone, FILTER_VALIDATE_EMAIL)
            ? 'email'
            : 'phone';

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

    // ─── Logout ───────────────────────────────────────────────────────────────
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return $this->success(null, 'Logged out successfully');
    }

    // ─── Me (profil sendiri) ──────────────────────────────────────────────────
    public function me(Request $request)
    {
        return $this->success(new UserResource($request->user()));
    }

    // ─── Request OTP (HANYA VIA WHATSAPP FONNTE) ──────────────────────────────
    public function requestOtp(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
        ]);

        $user = User::where('phone', $request->phone)->first();

        if (!$user) {
            return $this->error('Nomor belum terdaftar.', 404);
        }

        // Generate OTP 4 digit
        $otpCode = rand(1000, 9999);

        // Simpan ke cache 5 menit
        Cache::put('otp_' . $request->phone, $otpCode, now()->addMinutes(5));

        $phone = $request->phone;
        $message = "🔔 *Skilloka OTP Login*\n\nKode OTP Anda: *{$otpCode}*\n\n_Berlaku 5 menit. Jangan bagikan ke siapapun._";

        // Mengambil token dari .env, dengan fallback ke token yang kamu berikan
        $fonnteToken = env('FONNTE_TOKEN', 'i3wzy35ABcN9t7kLvp39');

        try {
            $response = Http::withHeaders([
                'Authorization' => $fonnteToken,
            ])->post('https://api.fonnte.com/send', [
                        'target' => $phone,
                        'message' => $message,
                        'countryCode' => '62', // Format otomatis ke 628...
                    ]);

            $responseData = $response->json();

            // Cek apakah pesan WA benar-benar terkirim menurut server Fonnte
            if ($response->successful() && isset($responseData['status']) && ($responseData['status'] === true || $responseData['status'] === 'true')) {
                return $this->success(null, 'OTP berhasil dikirim via WhatsApp.');
            } else {
                // Jika Fonnte menolak (misal kuota habis/nomor tidak valid)
                $reason = $responseData['reason'] ?? $responseData['detail'] ?? 'Unknown error';
                return $this->error('Gagal mengirim WhatsApp: ' . $reason, 500);
            }

        } catch (\Exception $e) {
            return $this->error('Terjadi kesalahan koneksi ke server Fonnte.', 500);
        }
    }

    // ─── Verify OTP ───────────────────────────────────────────────────────────
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'otp_code' => 'required|string',
        ]);

        $cachedOtp = Cache::get('otp_' . $request->phone);

        if (!$cachedOtp || $cachedOtp != $request->otp_code) {
            return $this->error('Kode OTP salah atau sudah kedaluwarsa.', 400);
        }

        $user = User::where('phone', $request->phone)->first();

        if (!$user) {
            return $this->error('Nomor HP tidak ditemukan.', 404);
        }

        Cache::forget('otp_' . $request->phone);

        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->success([
            'user' => new UserResource($user),
            'token' => $token,
        ], 'Verifikasi OTP berhasil, login sukses.');
    }

    // ─── Update Profil ────────────────────────────────────────────────────────
    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:500',
            'gender' => 'nullable|in:male,female',
            'birth_date' => 'nullable|date',
        ]);

        $user = $request->user();

        $user->update(array_filter([
            'name' => $request->name,
            'email' => $request->email,
            'address' => $request->address,
            'gender' => $request->gender,
            'birth_date' => $request->birth_date,
            'phone' => $request->phone ?: $user->phone,
        ], fn($v) => $v !== null));

        return $this->success(
            new UserResource($user->fresh()),
            'Profil berhasil diperbarui.'
        );
    }

    // ─── Upload Foto Profil ───────────────────────────────────────────────────
    public function updatePhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $user = $request->user();

        if ($user->photo && Storage::disk('public')->exists($user->photo)) {
            Storage::disk('public')->delete($user->photo);
        }

        $path = $request->file('photo')->store('users/photos', 'public');
        $user->update(['photo' => $path]);

        return $this->success([
            'photo_url' => Storage::url($path),
            'photo' => $path,
        ], 'Foto profil berhasil diperbarui.');
    }
}