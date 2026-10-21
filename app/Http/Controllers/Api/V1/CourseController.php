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
            ->when($request->category, function ($q) use ($request) {
                $cat = trim($request->category);
                $isUuid = \Illuminate\Support\Str::isUuid($cat);
                if ($isUuid) {
                    $q->where('category_id', $cat);
                } else {
                    $q->whereHas('category', function ($sq) use ($cat) {
                        $sq->where('slug', $cat)
                           ->orWhere('name', 'like', "%{$cat}%");
                    });
                }
            })
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
