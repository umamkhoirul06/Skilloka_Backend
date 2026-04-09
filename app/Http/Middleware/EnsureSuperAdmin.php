<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureSuperAdmin
{

    public function handle(Request $request, Closure $next)
    {

        if (!Auth::check()) {

            return redirect()->route('admin.login');

        }


        if (Auth::user()->role !== 'super_admin') {

            abort(403,'Akses khusus Super Admin');

        }


        return $next($request);

    }

}