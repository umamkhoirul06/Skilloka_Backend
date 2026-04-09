<?php

namespace App\Http\Controllers\Admin\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Lpk;

class TenantController extends Controller
{

    public function index()
    {

        $lpks = Lpk::latest()->get();

        return view(
            'super_admin.tenants.index',
            compact('lpks')
        );

    }

}