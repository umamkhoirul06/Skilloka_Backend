<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\BaseController;
use App\Http\Resources\LpkResource;
use App\Http\Resources\CourseResource;
use App\Models\Lpk;
use Illuminate\Http\Request;

class LpkController extends BaseController
{
    /**
     * GET /api/lpks
     * Menampilkan daftar LPK yang aktif dan terverifikasi
     */
    public function index(Request $request)
    {
        $query = Lpk::query()
            ->with(['location']) // Eager load lokasi agar tidak N+1 query
            ->withCount('courses')
            // Kita filter yang statusnya 'active'. 
            // NOTE: LPK kamu yang 'rejected' di database TIDAK AKAN muncul.
            ->where('status', 'active');

        // 1. Filter berdasarkan lokasi jika ada request location_id
        if ($request->filled('location_id')) {
            $query->where('location_id', $request->location_id);
        }

        // 2. Filter Terverifikasi (Default: true untuk user umum)
        // Jika di Flutter dikirim verified=false baru muncul semua
        if ($request->has('verified')) {
            $query->where('is_verified', $request->boolean('verified'));
        } else {
            // Standarnya kita tampilkan yang sudah terverifikasi saja
            $query->where('is_verified', true);
        }

        // 3. Pencarian Nama LPK atau Alamat
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%");
            });
        }

        // 4. Sorting yang lebih aman
        $sortBy = $request->get('sort_by', 'created_at');
        $sortDir = $request->get('sort_dir', 'desc');
        $allowedSorts = ['name', 'created_at', 'courses_count'];

        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortDir);
        }

        // 5. Eksekusi dengan Pagination
        $perPage = $request->get('per_page', 10);
        $lpks = $query->paginate($perPage);

        return $this->paginated($lpks, LpkResource::class);
    }

    /**
     * GET /api/lpks/{id}
     * Detail LPK beserta kursus yang tersedia
     */
    public function show(string $id)
    {
        // Cari LPK yang aktif, kalau tidak ada langsung 404
        $lpk = Lpk::with([
            'location',
            'courses' => function ($q) {
                $q->where('is_active', true); // Hanya ambil kursus yang aktif
            }
        ])
            ->withCount('courses')
            ->where('status', 'active')
            ->findOrFail($id);

        return $this->success([
            'lpk' => new LpkResource($lpk),
            'courses' => CourseResource::collection($lpk->courses),
        ]);
    }
}