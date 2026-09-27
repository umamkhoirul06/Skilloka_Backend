<?php

namespace App\Http\Controllers\Admin\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('courses')
            ->orderBy('order', 'asc')
            ->get();

        return view('super_admin.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:categories,name',
            'icon' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:30',
            'order' => 'nullable|integer',
        ]);

        Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'icon' => $request->icon ?? 'fa-shapes',
            'color' => $request->color ?? '#3b82f6',
            'order' => $request->order ?? 0,
        ]);

        return redirect()->route('super.categories.index')->with('success', 'Kategori baru berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:100|unique:categories,name,' . $category->id,
            'icon' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:30',
            'order' => 'nullable|integer',
        ]);

        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'icon' => $request->icon ?? $category->icon,
            'color' => $request->color ?? $category->color,
            'order' => $request->order ?? $category->order,
        ]);

        return redirect()->route('super.categories.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $category = Category::withCount('courses')->findOrFail($id);

        if ($category->courses_count > 0) {
            return back()->withErrors(['error' => 'Kategori ini tidak dapat dihapus karena masih digunakan oleh ' . $category->courses_count . ' kursus aktif.']);
        }

        $category->delete();

        return redirect()->route('super.categories.index')->with('success', 'Kategori berhasil dihapus.');
    }
}
