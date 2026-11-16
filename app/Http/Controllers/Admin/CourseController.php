<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Course;
use App\Models\Category;
use App\Models\Lpk;

class CourseController extends Controller
{
    /**
     * Helper to get categories or auto-seed standard default vocational categories if empty
     */
    private function getCategories()
    {
        $categories = Category::orderBy('order', 'asc')->orderBy('name', 'asc')->get();

        if ($categories->isEmpty()) {
            $defaultCategories = [
                ['name' => 'Teknologi & Digital', 'icon' => 'fa-laptop-code', 'color' => '#2563eb', 'order' => 1],
                ['name' => 'Bahasa Asing', 'icon' => 'fa-language', 'color' => '#10b981', 'order' => 2],
                ['name' => 'Teknik Otomotif & Manufaktur', 'icon' => 'fa-car', 'color' => '#f59e0b', 'order' => 3],
                ['name' => 'Teknik Pengelasan (Welding)', 'icon' => 'fa-fire-burner', 'color' => '#ea580c', 'order' => 4],
                ['name' => 'Bisnis & Manajemen', 'icon' => 'fa-briefcase', 'color' => '#0d9488', 'order' => 5],
                ['name' => 'Perhotelan & Pariwisata', 'icon' => 'fa-hotel', 'color' => '#e11d48', 'order' => 6],
                ['name' => 'Tata Busana & Menjahit', 'icon' => 'fa-scissors', 'color' => '#8b5cf6', 'order' => 7],
                ['name' => 'Tata Boga & Kuliner', 'icon' => 'fa-utensils', 'color' => '#ec4899', 'order' => 8],
                ['name' => 'Kecantikan & Tata Rias', 'icon' => 'fa-wand-magic-sparkles', 'color' => '#d946ef', 'order' => 9],
                ['name' => 'Kesehatan & Caregiver', 'icon' => 'fa-heart-pulse', 'color' => '#06b6d4', 'order' => 10],
                ['name' => 'Konstruksi & Bangunan', 'icon' => 'fa-trowel-bricks', 'color' => '#64748b', 'order' => 11],
                ['name' => 'Elektronika & Listrik', 'icon' => 'fa-bolt', 'color' => '#eab308', 'order' => 12],
            ];

            foreach ($defaultCategories as $item) {
                Category::firstOrCreate(
                    ['slug' => Str::slug($item['name'])],
                    $item
                );
            }

            $categories = Category::orderBy('order', 'asc')->orderBy('name', 'asc')->get();
        }

        return $categories;
    }

    /**
     * Display list course
     */
    public function index()
    {
        $user = Auth::user();

        $courses = Course::with('category')
            ->where('tenant_id', $user->tenant_id)
            ->latest()
            ->paginate(10);

        return view('admin.courses.index', compact('courses'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        $user = Auth::user();

        // category global (auto-seed if empty on server)
        $categories = $this->getCategories();

        $courses = Course::with('category')
            ->where('tenant_id', $user->tenant_id)
            ->latest()
            ->get();

        return view('admin.courses.create', compact(
            'categories',
            'courses'
        ));
    }

    /**
     * Store course
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'            => 'required|string|max:255',
            'description'      => 'nullable|string',
            'syllabus'         => 'nullable|string',
            'price'            => 'required|numeric|min:0',
            'duration_hours'   => 'required|integer|min:1',
            'category_id'      => [
                'required',
                function ($attribute, $value, $fail) use ($request) {
                    if ($value === '__new__') {
                        if (!$request->filled('new_category_name')) {
                            $fail('Nama kategori kejuruan baru wajib diisi jika memilih tambah baru.');
                        }
                    } elseif (!Category::where('id', $value)->exists()) {
                        $fail('Kategori kejuruan yang dipilih tidak valid.');
                    }
                },
            ],
            'new_category_name' => 'nullable|string|max:100',
            'level'            => 'required|in:beginner,intermediate,advanced',
            'cert_type'        => 'nullable|string|max:255',
            'max_participants' => 'nullable|integer|min:1',
            'images'           => 'nullable|array',
            'images.*'         => 'image|mimes:jpeg,png,jpg|max:5120',
            'facilities'       => 'nullable|array',
        ]);

        // Tangani jika LPK memilih Tambah Kategori Baru on-the-fly
        if ($request->category_id === '__new__' && $request->filled('new_category_name')) {
            $catName = trim($request->new_category_name);
            $newCat = Category::firstOrCreate(
                ['slug' => Str::slug($catName)],
                [
                    'name' => $catName,
                    'icon' => 'fa-shapes',
                    'color' => '#2563eb',
                    'order' => (Category::max('order') ?? 0) + 1,
                ]
            );
            $validated['category_id'] = $newCat->id;
        }
        unset($validated['new_category_name']);

        $user = Auth::user();

        // cari LPK berdasarkan tenant
        $lpk = Lpk::where(
            'tenant_id',
            $user->tenant_id
        )->first();

        if (!$lpk) {
            return back()->withErrors([
                'error' => 'LPK tidak ditemukan'
            ]);
        }

        $validated['tenant_id'] = $user->tenant_id;
        $validated['lpk_id'] = $lpk->id;

        // slug unik
        $validated['slug'] =
            Str::slug($validated['title']) . '-' . uniqid();

        $validated['is_active'] = true;

        if ($request->hasFile('images')) {
            $paths = [];
            foreach ($request->file('images') as $file) {
                $paths[] = $file->store('courses', 'public');
            }
            $validated['images'] = $paths;
        }

        if ($request->has('facilities')) {
            $validated['facilities'] = $request->facilities;
        }

        Course::create($validated);

        return redirect()
            ->route('admin.courses.index')
            ->with('success', 'Program pelatihan baru berhasil ditambahkan.');
    }

    /**
     * Show detail course
     */
    public function show(Course $course)
    {
        if ($course->tenant_id !== Auth::user()->tenant_id) {
            abort(403);
        }

        $course->load('category', 'schedules');

        return view('admin.courses.show', compact('course'));
    }

    /**
     * Show edit form
     */
    public function edit(Course $course)
    {
        if ($course->tenant_id !== Auth::user()->tenant_id) {
            abort(403);
        }

        // category global (auto-seed if empty on server)
        $categories = $this->getCategories();

        return view('admin.courses.edit', compact(
            'course',
            'categories'
        ));
    }

    /**
     * Update course
     */
    public function update(Request $request, Course $course)
    {
        if ($course->tenant_id !== Auth::user()->tenant_id) {
            abort(403);
        }

        $validated = $request->validate([
            'title'            => 'required|string|max:255',
            'description'      => 'nullable|string',
            'syllabus'         => 'nullable|string',
            'price'            => 'required|numeric|min:0',
            'duration_hours'   => 'required|integer|min:1',
            'category_id'      => [
                'required',
                function ($attribute, $value, $fail) use ($request) {
                    if ($value === '__new__') {
                        if (!$request->filled('new_category_name')) {
                            $fail('Nama kategori kejuruan baru wajib diisi jika memilih tambah baru.');
                        }
                    } elseif (!Category::where('id', $value)->exists()) {
                        $fail('Kategori kejuruan yang dipilih tidak valid.');
                    }
                },
            ],
            'new_category_name' => 'nullable|string|max:100',
            'level'            => 'required|in:beginner,intermediate,advanced',
            'cert_type'        => 'nullable|string|max:255',
            'max_participants' => 'nullable|integer|min:1',
            'is_active'        => 'nullable|boolean',
            'images'           => 'nullable|array',
            'images.*'         => 'image|mimes:jpeg,png,jpg|max:5120',
            'facilities'       => 'nullable|array',
        ]);

        // Tangani jika LPK memilih Tambah Kategori Baru on-the-fly
        if ($request->category_id === '__new__' && $request->filled('new_category_name')) {
            $catName = trim($request->new_category_name);
            $newCat = Category::firstOrCreate(
                ['slug' => Str::slug($catName)],
                [
                    'name' => $catName,
                    'icon' => 'fa-shapes',
                    'color' => '#2563eb',
                    'order' => (Category::max('order') ?? 0) + 1,
                ]
            );
            $validated['category_id'] = $newCat->id;
        }
        unset($validated['new_category_name']);

        $validated['slug'] =
            Str::slug($validated['title']) . '-' . uniqid();

        if ($request->hasFile('images')) {
            $paths = [];
            foreach ($request->file('images') as $file) {
                $paths[] = $file->store('courses', 'public');
            }
            $validated['images'] = $paths;
        }

        if ($request->has('facilities')) {
            $validated['facilities'] = $request->facilities;
        }

        $course->update($validated);

        return redirect()
            ->route('admin.courses.index')
            ->with('success', 'Program pelatihan berhasil diperbarui.');
    }

    /**
     * Delete course
     */
    public function destroy(Course $course)
    {
        if ($course->tenant_id !== Auth::user()->tenant_id) {
            abort(403);
        }

        $course->delete();

        return redirect()
            ->route('admin.courses.index')
            ->with('success', 'Program pelatihan berhasil dihapus.');
    }
}
