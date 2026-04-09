<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\BaseController;
use App\Http\Resources\CourseResource;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends BaseController
{
    public function index(Request $request)
    {
        // Public endpoint, so valid logic even without auth?
        // TenantScope applies only if tenant is set. 
        // For public discovery (all tenants), we might need to remove scope or handle differently.
        // If "Course Discovery" is global, we need withoutGlobalScope(TenantScope::class).

        $query = Course::query();

        // If discovery is global (cross-tenant):
        // $query->withoutGlobalScope(\App\Scopes\TenantScope::class);

        $courses = $query->with(['lpk', 'category'])
            ->when($request->category, fn($q) => $q->whereHas('category', fn($sq) => $sq->where('slug', $request->category)))
            ->when($request->search, fn($q) => $q->where('title', 'like', "%{$request->search}%"))
            ->latest()
            ->paginate(15);

        return $this->paginated($courses, CourseResource::class);
    }

    public function show(string $id)
    {
        $course = Course::with(['lpk.location', 'category', 'schedules'])
            ->findOrFail($id);

        return $this->success(new CourseResource($course));
    }

    // Helper for pagination in BaseController might need update to accept resource class
    // Or just return standard resource collection

    protected function paginated($paginator, $resourceClass = null): \Illuminate\Http\JsonResponse
    {
        return $this->success(
            $resourceClass ? $resourceClass::collection($paginator)->response()->getData(true) : $paginator
        );
    }
}
