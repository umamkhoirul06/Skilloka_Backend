<?php

namespace App\Http\Controllers\Admin\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\Lpk;
use Illuminate\Support\Facades\DB;

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
        try {
            DB::beginTransaction();

            // 1. Ambil data Tenant
            $tenant = Tenant::findOrFail($id);

            // 2. KUNCI UTAMA: UPDATE STATUS TENANT AGAR BLADE BERUBAH HIJAU!
            $tenant->status_verification = 'approved';
            $tenant->is_active = true; // Mengaktifkan akses login Admin LPK
            $tenant->save();

            // 3. Update juga status di tabel LPK
            $lpk = Lpk::where('tenant_id', $tenant->id)->first();
            if ($lpk) {
                $lpk->is_verified = true;
                $lpk->status = 'approved';
                $lpk->save();
            }

            DB::commit();
            return back()->with('success', 'MANTAP! LPK berhasil di Approve dan status telah aktif!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memverifikasi LPK: ' . $e->getMessage());
        }
    }

    public function reject($id)
    {
        try {
            DB::beginTransaction();

            // 1. Ambil data Tenant
            $tenant = Tenant::findOrFail($id);

            // 2. KUNCI UTAMA: UPDATE STATUS TENANT JADI REJECTED
            $tenant->status_verification = 'rejected';
            $tenant->is_active = false;
            $tenant->save();

            // 3. Update juga status di tabel LPK
            $lpk = Lpk::where('tenant_id', $tenant->id)->first();
            if ($lpk) {
                $lpk->is_verified = false;
                $lpk->status = 'rejected';
                $lpk->save();
            }

            DB::commit();
            return back()->with('success', 'SIPP! Pendaftaran LPK berhasil ditolak.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menolak LPK: ' . $e->getMessage());
        }
    }
}