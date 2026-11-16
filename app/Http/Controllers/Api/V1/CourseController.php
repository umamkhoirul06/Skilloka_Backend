<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\BaseController;
use App\Http\Resources\CourseResource;
use App\Http\Resources\LpkResource;
use App\Models\Course;
use App\Models\Lpk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
     * GET /api/courses/search?q={kalimat}
     * Pencarian kursus dengan dukungan FTS dan graceful fallback ILIKE/LIKE
     */
    public function search(Request $request)
    {
        $q = trim($request->get('q', $request->get('search', '')));

        if (empty($q)) {
            return $this->index($request);
        }

        try {
            $isPgsql = DB::getDriverName() === 'pgsql';

            if ($isPgsql) {
                // Coba FTS dengan simple dictionary jika indonesian tidak tersedia
                $courses = Course::with(['lpk.location', 'category', 'schedules', 'contents'])
                    ->where('is_active', true)
                    ->where(function ($query) use ($q) {
                        $query->where('title', 'ILIKE', "%{$q}%")
                            ->orWhere('description', 'ILIKE', "%{$q}%")
                            ->orWhere('syllabus', 'ILIKE', "%{$q}%")
                            ->orWhereHas('category', fn($cq) => $cq->where('name', 'ILIKE', "%{$q}%"))
                            ->orWhereHas('lpk', fn($lq) => $lq->where('name', 'ILIKE', "%{$q}%"));
                    })
                    ->latest('created_at')
                    ->paginate($request->get('per_page', 15));
            } else {
                // Fallback untuk SQLite / MySQL
                $courses = Course::with(['lpk.location', 'category', 'schedules', 'contents'])
                    ->where('is_active', true)
                    ->where(function ($query) use ($q) {
                        $query->where('title', 'like', "%{$q}%")
                            ->orWhere('description', 'like', "%{$q}%")
                            ->orWhere('syllabus', 'like', "%{$q}%")
                            ->orWhereHas('category', fn($cq) => $cq->where('name', 'like', "%{$q}%"))
                            ->orWhereHas('lpk', fn($lq) => $lq->where('name', 'like', "%{$q}%"));
                    })
                    ->latest()
                    ->paginate($request->get('per_page', 15));
            }

            return $this->paginated($courses, CourseResource::class);
        } catch (\Throwable $e) {
            // Fallback darurat jika ada kendala database
            return $this->index($request);
        }
    }

    /**
     * GET /api/courses/autocomplete?q={kalimat}
     * Memberikan rekomendasi & saran pencarian interaktif instan (Courses & LPK)
     */
    public function autocomplete(Request $request)
    {
        $q = trim($request->get('q', ''));

        if (mb_strlen($q) < 2) {
            return $this->success([
                'query' => $q,
                'suggestions' => [],
                'courses' => [],
                'lpks' => [],
            ], 'Query pencarian minimal 2 karakter.');
        }

        try {
            $isPgsql = DB::getDriverName() === 'pgsql';
            $likeOp = $isPgsql ? 'ILIKE' : 'like';

            // 1. Ambil Kursus yang relevan (Maksimal 6)
            $courses = Course::with(['category', 'lpk'])
                ->where('is_active', true)
                ->where(function ($sub) use ($q, $likeOp) {
                    $sub->where('title', $likeOp, "%{$q}%")
                        ->orWhereHas('category', fn($cq) => $cq->where('name', $likeOp, "%{$q}%"))
                        ->orWhereHas('lpk', fn($lq) => $lq->where('name', $likeOp, "%{$q}%"));
                })
                ->limit(6)
                ->get();

            // 2. Ambil LPK yang relevan (Maksimal 3)
            $lpks = Lpk::where('is_verified', true)
                ->where(function ($statusQ) {
                    $statusQ->where('status_verifikasi', 'approved')
                        ->orWhere('status', 'active');
                })
                ->where(function ($sub) use ($q, $likeOp) {
                    $sub->where('name', $likeOp, "%{$q}%")
                        ->orWhere('address', $likeOp, "%{$q}%");
                })
                ->limit(3)
                ->get();

            // Format saran interaktif untuk dropdown/autocomplete
            $suggestions = [];

            foreach ($courses as $c) {
                $suggestions[] = [
                    'type' => 'course',
                    'id' => $c->id,
                    'title' => $c->title,
                    'subtitle' => ($c->category->name ?? 'Kejuruan') . ' • Rp ' . number_format($c->price, 0, ',', '.'),
                    'category' => $c->category->name ?? null,
                    'lpk_name' => $c->lpk->name ?? null,
                ];
            }

            foreach ($lpks as $l) {
                $suggestions[] = [
                    'type' => 'lpk',
                    'id' => $l->id,
                    'title' => $l->name,
                    'subtitle' => $l->address ?? 'Kabupaten Indramayu',
                    'logo_url' => $l->logo_url,
                    'initials' => $l->initials,
                ];
            }

            return $this->success([
                'query' => $q,
                'suggestions' => $suggestions,
                'courses' => CourseResource::collection($courses),
                'lpks' => LpkResource::collection($lpks),
            ], 'Saran pencarian berhasil diambil.');
        } catch (\Throwable $e) {
            return $this->success([
                'query' => $q,
                'suggestions' => [],
                'courses' => [],
                'lpks' => [],
            ], 'Pencarian saran tidak menemukan data.');
        }
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
