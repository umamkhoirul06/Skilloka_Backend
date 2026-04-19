<?php

namespace App\Http\Controllers\Admin\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;

class TenantController extends Controller
{

    public function index()
    {

        $tenants = Tenant::with('users')
            ->latest()
            ->get();

        return view(
            'super_admin.tenants.index',
            compact('tenants')
        );

    }

}