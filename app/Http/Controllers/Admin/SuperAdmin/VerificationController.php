<?php

namespace App\Http\Controllers\Admin\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\Lpk;
use App\Models\LpkVerification;

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

        // Cari data LPK yang terhubung dengan Tenant ini
        $lpk = Lpk::where('tenant_id', $tenant->id)->first();

        if ($lpk) {
            // Ubah status LPK menjadi terverifikasi
            $lpk->is_verified = true;
            $lpk->status = 'approved';
            $lpk->save();

            // Ubah juga data di tabel riwayat verifikasi
            $verification = LpkVerification::where('lpk_id', $lpk->id)->first();
            if ($verification) {
                $verification->status = 'approved';
                $verification->save();
            }
        }

        return back()->with(
            'success',
            'LPK berhasil di approve dan diaktifkan!'
        );
    }


    public function reject($id)
    {
        $tenant = Tenant::findOrFail($id);

        // Cari data LPK yang terhubung dengan Tenant ini
        $lpk = Lpk::where('tenant_id', $tenant->id)->first();

        if ($lpk) {
            // Ubah status LPK menjadi tidak terverifikasi
            $lpk->is_verified = false;
            $lpk->status = 'rejected';
            $lpk->save();

            // Ubah juga data di tabel riwayat verifikasi
            $verification = LpkVerification::where('lpk_id', $lpk->id)->first();
            if ($verification) {
                $verification->status = 'rejected';
                $verification->save();
            }
        }

        return back()->with(
            'success',
            'Pendaftaran LPK berhasil ditolak.'
        );
    }

}