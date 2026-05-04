<?php

namespace App\Http\Controllers\Admin\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\Lpk;
use App\Models\LpkVerification;
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

            $tenant = Tenant::findOrFail($id);

            // Cari data LPK yang terhubung dengan Tenant ini
            $lpk = Lpk::where('tenant_id', $tenant->id)->first();

            if ($lpk) {
                // Ubah status LPK menjadi terverifikasi
                $lpk->is_verified = true;

                // Dihapus karena tabel lpks tidak memiliki kolom status di database-mu
                // $lpk->status = 'approved'; 

                $lpk->save();

                // Ubah data di tabel riwayat verifikasi
                $verification = LpkVerification::where('lpk_id', $lpk->id)->first();
                if ($verification) {
                    $verification->status = 'approved';
                    $verification->save();
                }
            }

            DB::commit();
            return back()->with('success', 'LPK berhasil di approve dan diaktifkan!');

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

            // Cari data LPK yang terhubung dengan Tenant ini
            $lpk = Lpk::where('tenant_id', $tenant->id)->first();

            if ($lpk) {
                // Ubah status LPK menjadi tidak terverifikasi
                $lpk->is_verified = false;

                // Dihapus karena tabel lpks tidak memiliki kolom status di database-mu
                // $lpk->status = 'rejected'; 

                $lpk->save();

                // Ubah data di tabel riwayat verifikasi
                $verification = LpkVerification::where('lpk_id', $lpk->id)->first();
                if ($verification) {
                    $verification->status = 'rejected';
                    $verification->save();
                }
            }

            DB::commit();
            return back()->with('success', 'Pendaftaran LPK berhasil ditolak.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menolak LPK: ' . $e->getMessage());
        }
    }
}