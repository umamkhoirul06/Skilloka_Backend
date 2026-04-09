<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureAdminLpk
{

    public function handle(Request $request, Closure $next)
    {

        // jika belum login
        if (!Auth::check()) {

            return redirect()->route('admin.login');

        }


        // jika bukan admin lpk
        if (Auth::user()->role !== 'admin_lpk') {

            abort(403,'Akses khusus Admin LPK');

        }


        return $next($request);

    }

}