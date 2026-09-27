<?php

namespace App\Http\Controllers\Admin\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        // Pastikan role utama terdaftar di database agar tidak terjadi RoleDoesNotExist exception
        Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'admin_lpk', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'user', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'student', 'guard_name' => 'web']);

        $roleFilter = $request->query('role');
        $search = $request->query('search');

        $query = User::with(['roles', 'lpk', 'tenant']);

        // Filter pencarian
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Filter role
        if ($roleFilter) {
            if ($roleFilter === 'super_admin') {
                $query->whereHas('roles', fn($r) => $r->whereIn('name', ['super_admin', 'super-admin']));
            } elseif ($roleFilter === 'admin_lpk') {
                $query->whereHas('roles', fn($r) => $r->whereIn('name', ['admin_lpk', 'admin-lpk', 'lpk-admin']));
            } elseif ($roleFilter === 'user') {
                $query->whereHas('roles', fn($r) => $r->whereIn('name', ['user', 'student']));
            }
        }

        $users = $query->latest()->paginate(15)->withQueryString();

        // Ringkasan jumlah user per role
        $counts = [
            'total' => User::count(),
            'super_admin' => User::whereHas('roles', fn($r) => $r->whereIn('name', ['super_admin', 'super-admin']))->count(),
            'admin_lpk' => User::whereHas('roles', fn($r) => $r->whereIn('name', ['admin_lpk', 'admin-lpk', 'lpk-admin']))->count(),
            'user' => User::whereHas('roles', fn($r) => $r->whereIn('name', ['user', 'student']))->count(),
        ];

        return view('super_admin.users.index', compact('users', 'counts', 'roleFilter', 'search'));
    }
}