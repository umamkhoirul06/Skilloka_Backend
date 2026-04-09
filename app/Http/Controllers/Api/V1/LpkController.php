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
     * GET /api/v1/lpks
     * Public endpoint - daftar semua LPK (bisa difilter)
     */
    public function index(Request $request)
    {
        $query = Lpk::query()
            ->with(['location'])
            ->withCount('courses')
            ->where('status', 'active');

        // Filter by kecamatan/location
        if ($request->location_id) {
            $query->where('location_id', $request->location_id);
        }

        // Filter hanya yang terverifikasi
        if ($request->boolean('verified')) {
            $query->where('is_verified', true);
        }

        // Pencarian nama LPK
        if ($request->search) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortDir = $request->get('sort_dir', 'desc');
        $allowedSorts = ['name', 'rating', 'created_at'];
        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortDir);
        }

        $lpks = $query->paginate($request->get('per_page', 15));

        return $this->paginated($lpks, LpkResource::class);
    }

    /**
     * GET /api/v1/lpks/{id}
     * Public endpoint - detail satu LPK beserta kursusnya
     */
    public function show(string $id)
    {
        $lpk = Lpk::with(['location', 'courses.category'])
            ->withCount('courses')
            ->where('status', 'active')
            ->findOrFail($id);

        return $this->success([
            'lpk'     => new LpkResource($lpk),
            'courses' => CourseResource::collection($lpk->courses->where('is_active', true)),
        ]);
    }
}
