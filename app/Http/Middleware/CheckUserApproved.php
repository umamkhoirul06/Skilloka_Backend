<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserApproved
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if ($user && $user->hasRole('admin_lpk') && $user->status !== 'approved') {
            Auth::logout();

            return redirect()->route('admin.login')
                ->withErrors([
                    'email' => 'Akun Anda masih menunggu persetujuan Super Admin'
                ]);
        }

        return $next($request);
    }
}