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

        // category global
        $categories = Category::latest()->get();

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
            'category_id'      => 'required|exists:categories,id',
            'level'            => 'required|in:beginner,intermediate,advanced',
            'cert_type'        => 'nullable|string|max:255',
            'max_participants' => 'nullable|integer|min:1',
            'image'            => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'facilities'       => 'nullable|array',
        ]);

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

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('courses', 'public');
            $validated['images'] = [$path];
        }

        if ($request->has('facilities')) {
            $validated['facilities'] = $request->facilities;
        }

        Course::create($validated);

        return redirect()
            ->route('admin.courses.create')
            ->with('success', 'Course berhasil dibuat');
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

        // category global
        $categories = Category::latest()->get();

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
            'category_id'      => 'required|exists:categories,id',
            'level'            => 'required|in:beginner,intermediate,advanced',
            'cert_type'        => 'nullable|string|max:255',
            'max_participants' => 'nullable|integer|min:1',
            'is_active'        => 'nullable|boolean',
            'image'            => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'facilities'       => 'nullable|array',
        ]);

        $validated['slug'] =
            Str::slug($validated['title']) . '-' . uniqid();

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('courses', 'public');
            $validated['images'] = [$path];
        }

        if ($request->has('facilities')) {
            $validated['facilities'] = $request->facilities;
        }

        $course->update($validated);

        return redirect()
            ->route('admin.courses.create')
            ->with('success', 'Course berhasil diupdate');
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
            ->route('admin.courses.create')
            ->with('success', 'Course berhasil dihapus');
    }
}