<?php

namespace App\Http\Controllers\Admin\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    // halaman settings
    public function index()
    {
        $setting = Setting::first();

        // kalau belum ada data settings
        if (!$setting) {

            $setting = Setting::create([
                'platform_name' => 'Skilloka',
                'support_email' => 'support@skilloka.com',
                'timezone' => 'Asia/Jakarta',
                'language' => 'Indonesia',
                'platform_fee' => 10,
                'payment_method' => 'Transfer Bank',
            ]);
        }

        return view(
            'super_admin.settings.index',
            compact('setting')
        );
    }

    // simpan settings
    public function update(Request $request)
    {
        $request->validate([
            'platform_name' => 'required',
            'support_email' => 'required|email',
            'timezone' => 'required',
            'language' => 'required',
            'platform_fee' => 'required|numeric',
            'payment_method' => 'required',
        ]);

        $setting = Setting::first();

        // kalau setting belum ada
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
            'Pengaturan berhasil disimpan'
        );
    }
}