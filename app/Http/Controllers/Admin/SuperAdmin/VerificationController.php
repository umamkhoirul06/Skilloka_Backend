<?php

namespace App\Http\Controllers\Admin\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;

class VerificationController extends Controller
{

    public function index()
    {

        $tenants = Tenant::with('users')
            ->latest()
            ->get();

        return view(
            'super_admin.verifications.index',
            compact('tenants')
        );

    }



    public function approve($id)
    {

        $tenant = Tenant::findOrFail($id);

        $tenant->status_verification = 'approved';

        $tenant->save();

        return back()->with(
            'success',
            'LPK berhasil di approve'
        );

    }



    public function reject($id)
    {

        $tenant = Tenant::findOrFail($id);

        $tenant->status_verification = 'rejected';

        $tenant->save();

        return back()->with(
            'success',
            'LPK berhasil di reject'
        );

    }

}