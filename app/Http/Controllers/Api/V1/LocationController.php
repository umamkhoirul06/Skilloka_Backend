<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\BaseController;
use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends BaseController
{
    public function index(Request $request)
    {
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

        // If 'hierarchy' is requested, we can return a tree structure
        if ($request->boolean('hierarchy')) {
            $query->with('children');
            // If starting from root (no parent_id), we might get too much.
            // But for Indramayu app, maybe we just want the Indramayu regency and its children?
            // Let's assume we filter by code or name if needed.
        }

        $locations = $query->get();

        return $this->success($locations);
    }
}
