<?php

namespace App\Http\Controllers\Admin\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login()
    {
        // ✅ Jika sudah login
        if (Auth::check()) {

            $user = Auth::user();

            // 🔥 SUPER ADMIN → langsung dashboard
            if ($user->hasRole('super_admin')) {
                return redirect()->route('super.dashboard');
            }

            // 🔥 ADMIN LPK
            if ($user->hasRole('admin_lpk')) {

                // ❌ CEK STATUS APPROVAL
                if ($user->status !== 'approved') {

                    Auth::logout();

                    return redirect()->route('admin.login')
                        ->withErrors([
                            'email' => 'Akun Anda masih menunggu persetujuan Super Admin'
                        ]);
                }

                // ✅ kalau sudah approved
                return redirect()->route('admin.dashboard');
            }

            // 🔥 USER (mobile / fallback)
            if ($user->hasRole('user')) {
                return redirect('/'); // atau home
            }

            // ❌ fallback jika role tidak dikenali
            Auth::logout();

            return redirect()->route('admin.login')
                ->withErrors([
                    'email' => 'Role tidak dikenali.'
                ]);
        }

        // ❌ belum login
        return redirect()->route('admin.login');
    }
}