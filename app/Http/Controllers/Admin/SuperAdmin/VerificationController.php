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

            $tenant = Tenant::findOrFail($id);
            // 🔥 KUNCI LOGIN: Set is_active jadi true agar Admin LPK bisa masuk
            $tenant->is_active = true;
            $tenant->save();

            $lpk = Lpk::where('tenant_id', $tenant->id)->first();
            if ($lpk) {
                $lpk->is_verified = true;
                $lpk->status = 'active'; // Lolos check constraint Postgres
                $lpk->status_verifikasi = 'approved'; // Kolom yang dibaca di dashboard
                $lpk->save();
            }

            DB::commit();
            return back()->with('success', 'MANTAP! LPK berhasil di Approve. Sekarang Admin LPK tersebut sudah bisa LOGIN!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memverifikasi LPK: ' . $e->getMessage());
        }
    }

    public function reject($id)
    {
        try {
            DB::beginTransaction();

            $tenant = Tenant::findOrFail($id);
            $tenant->is_active = false; // Blokir akses login
            $tenant->save();

            $lpk = Lpk::where('tenant_id', $tenant->id)->first();
            if ($lpk) {
                $lpk->is_verified = false;
                $lpk->status = 'pending';
                $lpk->status_verifikasi = 'rejected';
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