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

    public function requestOtp(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'channel' => 'nullable|string|in:whatsapp,telegram',
        ]);

        $channel = $request->channel ?? 'whatsapp';

        $user = User::where('phone', $request->phone)->first();

        // 🌟 LOGIKA BARU: AUTO-REGISTER JIKA NOMOR BELUM ADA
        if (!$user) {
            $user = User::create([
                'name' => 'Pengguna Baru', // Nama default sementara
                'phone' => $request->phone,
            ]);

            // Berikan role student secara otomatis
            $user->assignRole('student');
        }

        // Generate OTP 4 digit
        $otpCode = rand(1000, 9999);

        // Simpan ke cache 5 menit
        Cache::put('otp_' . $request->phone, $otpCode, now()->addMinutes(5));

        $phone = $request->phone;
        $message = "🔔 *Skilloka OTP Login*\n\nKode OTP Anda: *{$otpCode}*\n\n_Berlaku 5 menit. Jangan bagikan ke siapapun._";

        if ($channel === 'telegram') {
            $telegramToken = env('TELEGRAM_BOT_TOKEN', '');
            $telegramChatId = env('TELEGRAM_CHAT_ID', $phone); // Anda mungkin perlu logika mapping no hp ke chat_id

            if (empty($telegramToken)) {
                return $this->success(['dev_otp' => $otpCode], 'Telegram token belum diset, OTP via Telegram dilewati (OTP Dev: '.$otpCode.').');
            }

            try {
                $response = Http::post("https://api.telegram.org/bot{$telegramToken}/sendMessage", [
                    'chat_id' => $telegramChatId,
                    'text' => $message,
                    'parse_mode' => 'Markdown'
                ]);

                if ($response->successful()) {
                    return $this->success(['dev_otp' => $otpCode], 'OTP berhasil dikirim via Telegram.');
                } else {
                    return $this->error('Gagal mengirim Telegram.', 500);
                }
            } catch (\Exception $e) {
                return $this->error('Terjadi kesalahan koneksi ke server Telegram.', 500);
            }
        }

        // Mengambil token dari .env, dengan fallback
        $fonnteToken = env('FONNTE_TOKEN', 'i3wzy35ABcN9t7kLvp39');

        try {
            // Gunakan asForm() karena Fonnte seringkali tidak menerima application/json
            $response = Http::asForm()->withHeaders([
                'Authorization' => $fonnteToken,
            ])->post('https://api.fonnte.com/send', [
                'target' => $phone,
                'message' => $message,
                'countryCode' => '62',
            ]);

            $responseData = $response->json();

            if ($response->successful() && isset($responseData['status']) && ($responseData['status'] === true || $responseData['status'] === 'true')) {
                // Untuk tahap development, sertakan otpCode di response agar mudah login
                return $this->success(['dev_otp' => $otpCode], 'OTP berhasil dikirim via WhatsApp.');
            } else {
                $reason = $responseData['reason'] ?? $responseData['detail'] ?? 'Unknown error';
                // Meskipun gagal dikirim, demi proses development kita sertakan OTP-nya
                return $this->success(['dev_otp' => $otpCode], 'Gagal WA ('.$reason.'). Namun Anda tetap bisa login dengan OTP Dev ini.');
            }

        } catch (\Exception $e) {
            // Return OTP for development fallback
            return $this->success(['dev_otp' => $otpCode], 'Kesalahan server WA. OTP Dev: ' . $otpCode);
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