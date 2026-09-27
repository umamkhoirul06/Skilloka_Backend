<?php

namespace App\Http\Controllers\Admin\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\Lpk;
use App\Models\LpkVerification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
// Tambah di bagian use
use App\Mail\LpkVerified;
use App\Mail\LpkRejected;
use Illuminate\Support\Facades\Mail;
class VerificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | LIST VERIFICATION
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $tenants = Tenant::with([

                'users',
                'lpk'

            ])

            ->whereHas('lpk', function ($q) {

                $q->where(
                    'status_verifikasi',
                    'pending'
                );

            })

            ->latest()

            ->get();



        return view(

            'super_admin.verifications.index',

            compact('tenants')

        );
    }



    /*
    |--------------------------------------------------------------------------
    | DETAIL VERIFICATION
    |--------------------------------------------------------------------------
    */

    public function show($id)
    {
        $tenant = Tenant::with([
                'users',
                'lpk.verifications'
            ])
            ->findOrFail($id);

        return view(
            'super_admin.verifications.show',
            compact('tenant')
        );
    }



    /*
    |--------------------------------------------------------------------------
    | APPROVE LPK
    |--------------------------------------------------------------------------
    */

    public function approve($id)
    {
        DB::beginTransaction();

        try {

            $tenant = Tenant::findOrFail($id);

            $lpk = Lpk::where(

                'tenant_id',
                $tenant->id

            )->first();



            if (!$lpk) {

                return back()->withErrors([

                    'error' => 'Data LPK tidak ditemukan.'

                ]);
            }



            /*
            |--------------------------------------------------------------------------
            | UPDATE LPK
            |--------------------------------------------------------------------------
            */

            $lpk->update([

                'is_verified' => true,

                'status' => 'active',

                'status_verifikasi' => 'approved',

            ]);



            /*
            |--------------------------------------------------------------------------
            | UPDATE VERIFICATION
            |--------------------------------------------------------------------------
            */

            LpkVerification::where(

                'lpk_id',
                $lpk->id

            )->update([

                'status' => 'approved'

            ]);



            /*
            |--------------------------------------------------------------------------
            | UPDATE TENANT
            |--------------------------------------------------------------------------
            */

            $tenant->update([

                'is_active' => true

            ]);



            /*
            |--------------------------------------------------------------------------
            | UPDATE USER
            |--------------------------------------------------------------------------
            */

            $users = User::where(

                'tenant_id',
                $tenant->id

            )->get();



            foreach ($users as $user) {

                $user->update([

                    'status' => 'active'

                ]);



                $user->syncRoles([

                    'admin_lpk'

                ]);
            }



            DB::commit();

            try {
                foreach ($users as $user) {
                    if ($user->email) {
                        Mail::to($user->email)->send(new LpkVerified(
                            $user->name,
                            $lpk->name
                        ));
                    }
                }
            } catch (\Throwable $mErr) {
                \Illuminate\Support\Facades\Log::warning('Verification approve email not sent: ' . $mErr->getMessage());
            }

            return redirect()
                ->route('super.tenants')
                ->with('success', 'LPK berhasil diverifikasi');



           
        } catch (\Exception $e) {

            DB::rollBack();



            return back()->withErrors([

                'error' => $e->getMessage()

            ]);
        }
    }



    /*
    |--------------------------------------------------------------------------
    | REJECT LPK
    |--------------------------------------------------------------------------
    */

    public function reject(Request $request, $id)
    {
        DB::beginTransaction();

        try {

            $tenant = Tenant::findOrFail($id);

            $lpk = Lpk::where(

                'tenant_id',
                $tenant->id

            )->first();



            if (!$lpk) {

                return back()->withErrors([

                    'error' => 'Data LPK tidak ditemukan.'

                ]);
            }



            /*
            |--------------------------------------------------------------------------
            | UPDATE LPK
            |--------------------------------------------------------------------------
            */

            $lpk->update([

                'is_verified' => false,

                'status' => 'rejected',

                'status_verifikasi' => 'rejected',

            ]);



            /*
            |--------------------------------------------------------------------------
            | UPDATE VERIFICATION
            |--------------------------------------------------------------------------
            */

            LpkVerification::where(

                'lpk_id',
                $lpk->id

            )->update([

                'status' => 'rejected',
                'notes' => $request->input('reason', 'Dokumen atau data legalitas LPK belum memenuhi ketentuan verifikasi.'),
                'verified_by' => auth()->id(),
            ]);

            /*
            |--------------------------------------------------------------------------
            | UPDATE TENANT
            |--------------------------------------------------------------------------
            */
            $tenant->update([
                'is_active' => false
            ]);

            /*
            |--------------------------------------------------------------------------
            | UPDATE USER
            |--------------------------------------------------------------------------
            */
            User::where('tenant_id', $tenant->id)->update([
                'status' => 'rejected'
            ]);

            DB::commit();

            // Kirim email ke semua user tenant jika ada konfigurasi mail
            try {
                $users = User::where('tenant_id', $tenant->id)->get();
                foreach ($users as $user) {
                    if ($user->email) {
                        Mail::to($user->email)->send(new LpkRejected($user->name, $lpk->name));
                    }
                }
            } catch (\Throwable $mailErr) {
                // Log and continue without failing the rejection
                \Illuminate\Support\Facades\Log::warning('Verification reject email not sent: ' . $mailErr->getMessage());
            }

            return redirect()
                ->route('super.verifications')
                ->with('success', 'Pengajuan LPK berhasil ditolak.');
            

        } catch (\Exception $e) {

            DB::rollBack();



            return back()->withErrors([

                'error' => $e->getMessage()

            ]);
        }
    }
}