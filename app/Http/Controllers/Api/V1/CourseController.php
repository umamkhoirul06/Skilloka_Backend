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

        // ⚡ Performa: Eager load lpk, category, schedules, & contents untuk mencegah N+1 Query
        $courses = $query->with(['lpk.location', 'category', 'schedules', 'contents'])
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
            ->when($request->jenjang, function ($q) use ($request) {
                $jenjang = strtolower(trim($request->jenjang));
                $q->where(function ($jq) use ($jenjang) {
                    if ($jenjang === 'sma') {
                        $jq->whereHas('category', function ($cq) {
                            $cq->whereIn('slug', ['bahasa-asing', 'bisnis-manajemen', 'teknologi-digital'])
                               ->orWhereRaw('LOWER(name) LIKE ?', ['%bahasa%'])
                               ->orWhereRaw('LOWER(name) LIKE ?', ['%bisnis%'])
                               ->orWhereRaw('LOWER(name) LIKE ?', ['%administrasi%']);
                        });
                    } elseif ($jenjang === 'smk') {
                        $jq->whereHas('category', function ($cq) {
                            $cq->whereIn('slug', ['teknik-otomotif-manufaktur', 'teknologi-digital'])
                               ->orWhereRaw('LOWER(name) LIKE ?', ['%teknik%'])
                               ->orWhereRaw('LOWER(name) LIKE ?', ['%otomotif%'])
                               ->orWhereRaw('LOWER(name) LIKE ?', ['%manufaktur%'])
                               ->orWhereRaw('LOWER(name) LIKE ?', ['%las%']);
                        });
                    } elseif ($jenjang === 'sarjana') {
                        $jq->whereHas('category', function ($cq) {
                            $cq->whereIn('slug', ['teknologi-digital', 'bisnis-manajemen'])
                               ->orWhereRaw('LOWER(name) LIKE ?', ['%digital%'])
                               ->orWhereRaw('LOWER(name) LIKE ?', ['%teknologi%'])
                               ->orWhereRaw('LOWER(name) LIKE ?', ['%data%'])
                               ->orWhereRaw('LOWER(name) LIKE ?', ['%manajemen%']);
                        });
                    } elseif ($jenjang === 'umum') {
                        $jq->whereHas('category', function ($cq) {
                            $cq->whereIn('slug', ['perhotelan-pariwisata', 'bisnis-manajemen'])
                               ->orWhereRaw('LOWER(name) LIKE ?', ['%perhotelan%'])
                               ->orWhereRaw('LOWER(name) LIKE ?', ['%pariwisata%'])
                               ->orWhereRaw('LOWER(name) LIKE ?', ['%boga%'])
                               ->orWhereRaw('LOWER(name) LIKE ?', ['%wirausaha%']);
                        });
                    }
                });
            })
            ->when($request->level, function ($q) use ($request) {
                $levelMap = [
                    'pemula'       => 'beginner',
                    'menengah'     => 'intermediate',
                    'mahir'        => 'advanced',
                    'beginner'     => 'beginner',
                    'intermediate' => 'intermediate',
                    'advanced'     => 'advanced',
                ];
                $normalizedLevel = $levelMap[strtolower(trim($request->level))] ?? $request->level;
                $q->where('level', $normalizedLevel);
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
        $course = Course::with(['lpk.location', 'category', 'schedules', 'contents'])
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
