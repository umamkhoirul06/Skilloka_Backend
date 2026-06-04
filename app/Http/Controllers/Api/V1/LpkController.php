<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\BaseController;
use App\Http\Resources\LpkResource;
use App\Http\Resources\CourseResource;
use App\Models\Lpk;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LpkController extends BaseController
{
    /**
     * GET /api/lpks
     */
    public function index(Request $request)
    {
        $query = Lpk::query()
            ->with(['location'])
            ->withCount('courses')
            ->where('status', 'active');

        if ($request->filled('location_id')) {
            $query->where('location_id', $request->location_id);
        }

        if ($request->has('verified')) {
            $query->where('is_verified', $request->boolean('verified'));
        } else {
            $query->where('is_verified', true);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%");
            });
        }

        $sortBy = $request->get('sort_by', 'created_at');
        $sortDir = $request->get('sort_dir', 'desc');
        $allowedSorts = ['name', 'created_at', 'courses_count'];

        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortDir);
        }

        return $this->paginated($query->paginate($request->get('per_page', 10)), LpkResource::class);
    }

    /**
     * GET /api/lpks/{id}
     */
    public function show(string $id)
    {
        // FIX: Membersihkan ID dari karakter ':' yang tidak sengaja terbawa dari route binding
        $cleanId = ltrim($id, ':');

        $lpk = Lpk::with([
            'location',
            'courses' => function ($q) {
                $q->where('is_active', true);
            }
        ])
            ->withCount('courses')
            ->where('status', 'active')
            ->where('id', $cleanId) // Menggunakan where ID agar lebih aman
            ->first();

        if (!$lpk) {
            return $this->error('LPK tidak ditemukan atau tidak aktif.', 404);
        }

        return $this->success([
            'lpk' => new LpkResource($lpk),
            'courses' => CourseResource::collection($lpk->courses),
        ]);
    }
}