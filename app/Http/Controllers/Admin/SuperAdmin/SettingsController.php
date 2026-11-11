<?php

namespace App\Http\Controllers\Admin\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    /**
     * Halaman Pengaturan Sistem
     */
    public function index()
    {
        $setting = Setting::first();

        if (!$setting) {
            $setting = Setting::create([
                'platform_name' => 'Skilloka',
                'support_email' => 'support@skilloka.com',
                'timezone' => 'Asia/Jakarta',
                'language' => 'Indonesia',
                'platform_fee' => 10,
                'payment_method' => 'Midtrans Payment Gateway (QRIS, VA, E-Wallet)',
            ]);
        }

        return view(
            'super_admin.settings.index',
            compact('setting')
        );
    }

    /**
     * Simpan Pembaruan Pengaturan
     */
    public function update(Request $request)
    {
        $request->validate([
            'platform_name' => 'required|string|max:100',
            'support_email' => 'required|email|max:150',
            'timezone' => 'required|string|in:Asia/Jakarta,Asia/Makassar,Asia/Jayapura',
            'language' => 'required|string|in:Indonesia,English',
            'platform_fee' => 'required|numeric|min:0|max:100',
            'payment_method' => 'required|string|max:150',
        ], [
            'platform_name.required' => 'Nama platform wajib diisi.',
            'support_email.required' => 'Email support wajib diisi.',
            'support_email.email' => 'Format email support tidak valid.',
            'platform_fee.required' => 'Persentase platform fee wajib diisi.',
            'platform_fee.numeric' => 'Persentase platform fee harus berupa angka.',
            'platform_fee.min' => 'Platform fee minimal 0%.',
            'platform_fee.max' => 'Platform fee maksimal 100%.',
        ]);

        $setting = Setting::first();

        if (!$setting) {
            $setting = new Setting();
        }

        $setting->platform_name = $request->platform_name;
        $setting->support_email = $request->support_email;
        $setting->timezone = $request->timezone;
        $setting->language = $request->language;
        $setting->platform_fee = $request->platform_fee;
        $setting->payment_method = $request->payment_method;
        $setting->save();

        return back()->with(
            'success',
            'Pengaturan sistem berhasil diperbarui dan diterapkan ke seluruh platform.'
        );
    }
}
