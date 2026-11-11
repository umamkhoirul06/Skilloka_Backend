<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\BaseController;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class LocationController extends BaseController
{
    /**
     * GET /api/locations
     * Mengambil daftar lokasi wilayah (Dioptimasi dengan cache untuk query standar).
     */
    public function index(Request $request)
    {
        $hasFilters = $request->has('parent_id') || $request->has('type') || $request->filled('search') || $request->boolean('hierarchy');

        if (!$hasFilters) {
            $locations = Cache::remember('api_default_locations', 600, function () {
                return Location::all();
            });
            return $this->success($locations);
        }

        $query = Location::query();

        if ($request->has('parent_id')) {
            $query->where('parent_id', $request->parent_id);
        }

        if ($request->has('type')) {
            $query->where('level', $request->type);
        }

        if ($request->search) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        if ($request->boolean('hierarchy')) {
            $query->with('children');
        }

        $locations = $query->get();

        return $this->success($locations);
    }
}
