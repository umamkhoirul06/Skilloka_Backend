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
        $tenants = Tenant::with('users')->latest()->get();
        return view('super_admin.verifications.index', compact('tenants'));
    }

    public function approve($id)
    {
        try {
            DB::beginTransaction();

            // Aktifkan akun Tenant agar bisa login
            $tenant = Tenant::findOrFail($id);
            $tenant->is_active = true;
            $tenant->save();

            // Update tabel Lpk dengan kolom yang BENAR
            $lpk = Lpk::where('tenant_id', $tenant->id)->first();
            if ($lpk) {
                $lpk->is_verified = true;
                $lpk->status = 'active'; // Lolos satpam database
                $lpk->status_verifikasi = 'approved'; // INI KOLOM ASLINYA!
                $lpk->save();
            }

            DB::commit();
            return back()->with('success', 'MANTAP! LPK berhasil di Approve dan aktif!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memverifikasi LPK: ' . $e->getMessage());
        }
    }

    public function reject($id)
    {
        try {
            DB::beginTransaction();

            // Matikan akun Tenant
            $tenant = Tenant::findOrFail($id);
            $tenant->is_active = false;
            $tenant->save();

            // Update tabel Lpk dengan kolom yang BENAR
            $lpk = Lpk::where('tenant_id', $tenant->id)->first();
            if ($lpk) {
                $lpk->is_verified = false;
                $lpk->status = 'pending'; // Lolos satpam database
                $lpk->status_verifikasi = 'rejected'; // INI KOLOM ASLINYA!
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