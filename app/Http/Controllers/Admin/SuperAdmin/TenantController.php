<?php

namespace App\Http\Controllers\Admin\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
 use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;


class TenantController extends Controller
{
    /**
     * LIST LPK (hanya yang sudah active)
     */
    public function index(Request $request)
    {
        $tenants = Tenant::with(['users', 'lpk'])

            ->whereHas('lpk', function ($q) {

                $q->where('status', 'active');

            })

            ->when($request->search, function ($q) use ($request) {

                $q->where(
                    'lpk_name',
                    'ilike',
                    '%' . $request->search . '%'
                );

            })

            ->latest()

            ->get();



        return view(

            'super_admin.tenants.index',

            compact('tenants')

        );
    }



    /**
     * DETAIL LPK
     */
    public function show($id)
    {
        $tenant = Tenant::with([

                'users',
                'lpk'

            ])

            ->findOrFail($id);



        return view(

            'super_admin.tenants.show',

            compact('tenant')

        );
    }



    /**
     * DELETE LPK (tenant + relasinya)
     */
    public function destroy($id)
    {
        DB::beginTransaction();

        try {

            $tenant = Tenant::findOrFail($id);



            /*
            |--------------------------------------------------------------------------
            | HAPUS LPK
            |--------------------------------------------------------------------------
            */

            if ($tenant->lpk) {

                $tenant->lpk->delete();

            }



            /*
            |--------------------------------------------------------------------------
            | HAPUS USER
            |--------------------------------------------------------------------------
            */

            $tenant->users()->delete();



            /*
            |--------------------------------------------------------------------------
            | HAPUS TENANT
            |--------------------------------------------------------------------------
            */

            $tenant->delete();



            DB::commit();



            return back()->with(

                'success',

                'LPK berhasil dihapus'

            );

        } catch (\Exception $e) {

            DB::rollBack();



            return back()->withErrors([

                'error' => 'Gagal menghapus LPK'

            ]);
        }
    }
   
public function exportPdf(Request $request)
{
    $period = $request->get('period', 'month');

    $startDate = match($period) {
        'month'   => Carbon::now()->startOfMonth(),
        '3months' => Carbon::now()->subMonths(3),
        '6months' => Carbon::now()->subMonths(6),
        default   => Carbon::now()->startOfYear(),
    };

    $tenants = Tenant::with(['users', 'lpk'])
        ->whereHas('lpk', function ($q) {
            $q->where('status', 'active');
        })
        ->where('created_at', '>=', $startDate)
        ->latest()
        ->get();

    $pdf = Pdf::loadView('super_admin.tenants.pdf', compact(
        'tenants', 'period', 'startDate'
    ))->setPaper('a4', 'landscape');

    return $pdf->download('daftar-lpk-' . $period . '-' . now()->format('Ymd') . '.pdf');
}
}