<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\BaseController;
use App\Http\Resources\CourseResource;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends BaseController
{
    /**
     * Mengambil daftar Courses/Pelatihan yang berstatus aktif
     */
    public function index(Request $request)
    {
        $query = Course::query()->where('is_active', true);

        // Filter & Search Opsional
        $courses = $query->with(['lpk', 'category'])
            ->when($request->category, fn($q) => $q->whereHas('category', fn($sq) => $sq->where('slug', $request->category)))
            ->when($request->search, fn($q) => $q->where('title', 'like', "%{$request->search}%"))
            ->latest()
            ->paginate(15);

        // Menggunakan helper paginated dari BaseController
        return $this->paginated($courses, CourseResource::class);
    }

    /**
     * Mengambil detail satu kursus
     */
    public function show(string $id)
    {
        $course = Course::with(['lpk.location', 'category', 'schedules'])
            ->where('is_active', true)
            ->findOrFail($id);

        // Helper success akan mengembalikan format: { status, message, data }
        return $this->success(new CourseResource($course), 'Detail kursus berhasil diambil.');
    }

    // Helper Pagination (Tetap dipertahankan agar kompatibel dengan BaseController)
    protected function paginated($paginator, $resourceClass = null): \Illuminate\Http\JsonResponse
    {
        return $this->success(
            $resourceClass ? $resourceClass::collection($paginator)->response()->getData(true) : $paginator,
            'Data berhasil diambil.'
        );
    }
}
