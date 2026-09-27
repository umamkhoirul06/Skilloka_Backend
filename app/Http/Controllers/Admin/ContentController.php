<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Course;
use App\Models\CourseContent;
use Illuminate\Support\Facades\Storage;

class ContentController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $courses = Course::where('tenant_id', $user->tenant_id)->latest()->get();

        $query = CourseContent::whereIn('course_id', $courses->pluck('id'))
            ->with('course');

        if ($request->filled('course_id')) {
            $query->where('course_id', $request->course_id);
        }

        $contents = $query->orderBy('order_index', 'asc')
            ->latest('updated_at')
            ->paginate(15)
            ->withQueryString();

        return view('admin.contents.index', compact('contents', 'courses'));
    }

    public function create(Request $request)
    {
        $user = Auth::user();
        $courses = Course::where('tenant_id', $user->tenant_id)->latest()->get();
        $selectedCourseId = $request->query('course_id');

        return view('admin.contents.create', compact('courses', 'selectedCourseId'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'course_id'    => 'required|uuid|exists:courses,id',
            'title'        => 'required|string|max:255',
            'type'         => 'required|in:document,video,article,link',
            'content_url'  => 'nullable|string|max:500',
            'file'         => 'nullable|file|max:20480', // Max 20MB
            'body'         => 'nullable|string',
            'order_index'  => 'nullable|integer',
            'is_published' => 'nullable',
        ]);

        // Security: verify course ownership
        Course::where('id', $request->course_id)
            ->where('tenant_id', $user->tenant_id)
            ->firstOrFail();

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('course_contents', 'public');
        }

        CourseContent::create([
            'course_id'    => $request->course_id,
            'title'        => $request->title,
            'type'         => $request->type,
            'content_url'  => $request->content_url,
            'file_path'    => $filePath,
            'body'         => $request->body,
            'order_index'  => $request->order_index ?? 0,
            'is_published' => $request->has('is_published') ? true : false,
        ]);

        return redirect()
            ->route('admin.contents.index', ['course_id' => $request->course_id])
            ->with('success', 'Materi pembelajaran berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $user = Auth::user();
        $courses = Course::where('tenant_id', $user->tenant_id)->get();

        $content = CourseContent::whereIn('course_id', $courses->pluck('id'))
            ->findOrFail($id);

        return view('admin.contents.edit', compact('content', 'courses'));
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $courses = Course::where('tenant_id', $user->tenant_id)->get();

        $content = CourseContent::whereIn('course_id', $courses->pluck('id'))
            ->findOrFail($id);

        $request->validate([
            'course_id'    => 'required|uuid|exists:courses,id',
            'title'        => 'required|string|max:255',
            'type'         => 'required|in:document,video,article,link',
            'content_url'  => 'nullable|string|max:500',
            'file'         => 'nullable|file|max:20480',
            'body'         => 'nullable|string',
            'order_index'  => 'nullable|integer',
            'is_published' => 'nullable',
        ]);

        Course::where('id', $request->course_id)
            ->where('tenant_id', $user->tenant_id)
            ->firstOrFail();

        $filePath = $content->file_path;
        if ($request->hasFile('file')) {
            if ($filePath && Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }
            $filePath = $request->file('file')->store('course_contents', 'public');
        }

        $content->update([
            'course_id'    => $request->course_id,
            'title'        => $request->title,
            'type'         => $request->type,
            'content_url'  => $request->content_url,
            'file_path'    => $filePath,
            'body'         => $request->body,
            'order_index'  => $request->order_index ?? 0,
            'is_published' => $request->has('is_published') ? true : false,
        ]);

        return redirect()
            ->route('admin.contents.index', ['course_id' => $content->course_id])
            ->with('success', 'Materi berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $user = Auth::user();
        $courses = Course::where('tenant_id', $user->tenant_id)->get();

        $content = CourseContent::whereIn('course_id', $courses->pluck('id'))
            ->findOrFail($id);

        if ($content->file_path && Storage::disk('public')->exists($content->file_path)) {
            Storage::disk('public')->delete($content->file_path);
        }

        $courseId = $content->course_id;
        $content->delete();

        return redirect()
            ->route('admin.contents.index', ['course_id' => $courseId])
            ->with('success', 'Materi berhasil dihapus.');
    }
}
