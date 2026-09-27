<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Lpk;
use App\Models\Category;
use App\Models\Review;
use App\Models\CourseSchedule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PublicController extends Controller
{
    /**
     * Public Homepage / Landing Page
     */
    public function index()
    {
        $categories = Category::withCount(['courses' => function ($q) {
            $q->where('is_active', true);
        }])->orderBy('order', 'asc')->take(8)->get();

        $featuredCourses = Course::with(['lpk', 'category'])
            ->where('is_active', true)
            ->latest()
            ->take(6)
            ->get();

        $lpks = Lpk::where('status_verifikasi', 'approved')
            ->orWhere('is_verified', true)
            ->withCount('courses')
            ->latest()
            ->take(6)
            ->get();

        $stats = [
            'total_lpks' => Lpk::where('status_verifikasi', 'approved')->orWhere('is_verified', true)->count(),
            'total_courses' => Course::where('is_active', true)->count(),
            'total_categories' => Category::count(),
            'region' => 'Kabupaten Indramayu & Sekitarnya',
        ];

        $faqs = [
            [
                'q' => 'Apa itu Skilloka?',
                'a' => 'Skilloka adalah platform digital terintegrasi yang mempertemukan pencari kerja/pelajar dengan Lembaga Pelatihan Kerja (LPK) resmi, khususnya di wilayah Kabupaten Indramayu. Di Skilloka, Anda dapat mencari kursus terverifikasi, memilih jadwal fleksibel, melakukan booking, mengikuti absensi kelas, hingga memperoleh sertifikat resmi.'
            ],
            [
                'q' => 'Bagaimana cara mendaftar kursus di Skilloka?',
                'a' => 'Anda dapat menjelajahi katalog kursus di website ini tanpa login. Untuk melakukan booking jadwal dan pembayaran kelas, Anda dapat menggunakan aplikasi mobile Skilloka yang terhubung langsung dengan LPK penyelenggara.'
            ],
            [
                'q' => 'Apakah LPK yang terdaftar di Skilloka memiliki izin resmi?',
                'a' => 'Ya, setiap LPK yang bergabung diwajibkan menyertakan NIB, izin operasional, dan data legalitas yang diverifikasi secara ketat oleh tim Super Admin Skilloka sebelum dapat mempublikasikan kursus.'
            ],
            [
                'q' => 'Bagaimana cara LPK mendaftarkan lembaganya?',
                'a' => 'Klik tombol "Daftarkan LPK" di bagian navigasi atas, lengkapi formulir pendaftaran bertahap (Akun Admin, Profil LPK, Alamat, dan Legalitas). Pengajuan Anda akan masuk ke antrean verifikasi dan tim kami akan segera memprosesnya.'
            ],
            [
                'q' => 'Apakah siswa mendapatkan sertifikat setelah selesai pelatihan?',
                'a' => 'Tentu. Siswa yang memenuhi syarat kehadiran dan menyelesaikan program pelatihan akan diterbitkan sertifikat kompetensi resmi berstandar LPK dengan nomor registrasi unik yang dapat diverifikasi.'
            ],
        ];

        return view('public.index', compact('categories', 'featuredCourses', 'lpks', 'stats', 'faqs'));
    }

    /**
     * Public Course Catalog
     */
    public function courses(Request $request)
    {
        $query = Course::with(['lpk', 'category'])->where('is_active', true);

        if ($request->filled('search')) {
            $keyword = '%' . strtolower($request->search) . '%';
            $query->where(function ($q) use ($keyword) {
                $q->whereRaw('LOWER(title) LIKE ?', [$keyword])
                  ->orWhereRaw('LOWER(description) LIKE ?', [$keyword])
                  ->orWhereHas('lpk', function ($lq) use ($keyword) {
                      $lq->whereRaw('LOWER(name) LIKE ?', [$keyword]);
                  });
            });
        }

        if ($request->filled('category')) {
            $category = (string) $request->category;
            $isUuid = Str::isUuid($category);

            $query->whereHas('category', function ($q) use ($category, $isUuid) {
                $q->where('slug', $category);

                if ($isUuid) {
                    $q->orWhere('id', $category);
                }
            });
        }

        if ($request->filled('level')) {
            $query->where('level', $request->level);
        }

        if ($request->filled('sort')) {
            if ($request->sort === 'price_asc') {
                $query->orderBy('price', 'asc');
            } elseif ($request->sort === 'price_desc') {
                $query->orderBy('price', 'desc');
            } else {
                $query->latest();
            }
        } else {
            $query->latest();
        }

        $courses = $query->paginate(9)->withQueryString();
        $categories = Category::withCount(['courses' => function ($q) {
            $q->where('is_active', true);
        }])->orderBy('name')->get();

        return view('public.courses', compact('courses', 'categories'));
    }

    /**
     * Course Detail View
     */
    public function courseDetail($id)
    {
        $course = Course::with(['lpk', 'category', 'schedules' => function ($q) {
            $q->where('status', 'active')->orWhere('status', 'open');
        }, 'contents' => function ($q) {
            $q->where('is_published', true)->orderBy('order_index', 'asc');
        }])->findOrFail($id);

        $relatedCourses = Course::where('category_id', $course->category_id)
            ->where('id', '!=', $course->id)
            ->where('is_active', true)
            ->take(3)
            ->get();

        return view('public.course_detail', compact('course', 'relatedCourses'));
    }

    /**
     * Public LPK Directory
     */
    public function lpks(Request $request)
    {
        $query = Lpk::where('status_verifikasi', 'approved')
            ->orWhere('is_verified', true)
            ->withCount(['courses' => function ($q) {
                $q->where('is_active', true);
            }]);

        if ($request->filled('search')) {
            $keyword = '%' . strtolower($request->search) . '%';
            $query->where(function ($q) use ($keyword) {
                $q->whereRaw('LOWER(name) LIKE ?', [$keyword])
                  ->orWhereRaw('LOWER(city) LIKE ?', [$keyword])
                  ->orWhereRaw('LOWER(address) LIKE ?', [$keyword]);
            });
        }

        $lpks = $query->latest()->paginate(9)->withQueryString();

        return view('public.lpks', compact('lpks'));
    }

    /**
     * LPK Detail View
     */
    public function lpkDetail($id)
    {
        $lpk = Lpk::with(['courses' => function ($q) {
            $q->where('is_active', true)->with('category');
        }])->findOrFail($id);

        return view('public.lpk_detail', compact('lpk'));
    }

    /**
     * "Untuk LPK" / Partner Information
     */
    public function forLpk()
    {
        return view('public.for_lpk');
    }

    /**
     * "Tentang Kami" / About Page
     */
    public function about()
    {
        return view('public.about');
    }

    /**
     * Registration Success Confirmation
     */
    public function registerSuccess()
    {
        return view('public.register_success');
    }
}
