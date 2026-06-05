<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Banner;
use App\Models\Lpk;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    // Fungsi bantuan untuk mengambil data LPK milik Admin yang login
    private function getLpk()
    {
        $user = Auth::user();
        return Lpk::where('tenant_id', $user->tenant_id)->firstOrFail();
    }

    public function index()
    {
        $lpk = $this->getLpk();
        // Ambil semua banner milik LPK ini
        $banners = Banner::where('lpk_id', $lpk->id)->latest()->get();

        return view('admin.banners.index', compact('banners', 'lpk'));
    }

    public function create()
    {
        $lpk = $this->getLpk();
        return view('admin.banners.create', compact('lpk'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $lpk = $this->getLpk();
        $path = $request->file('image')->store('banners', 'public');

        Banner::create([
            'lpk_id' => $lpk->id,
            'title' => $request->title,
            'image_path' => $path,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.banners.index')->with('success', 'Banner berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $lpk = $this->getLpk();
        $banner = Banner::where('lpk_id', $lpk->id)->findOrFail($id);

        return view('admin.banners.edit', compact('banner', 'lpk'));
    }

    public function update(Request $request, $id)
    {
        $lpk = $this->getLpk();
        $banner = Banner::where('lpk_id', $lpk->id)->findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        if ($request->hasFile('image')) {
            // Hapus gambar lama
            if (Storage::disk('public')->exists($banner->image_path)) {
                Storage::disk('public')->delete($banner->image_path);
            }
            // Simpan gambar baru
            $banner->image_path = $request->file('image')->store('banners', 'public');
        }

        $banner->title = $request->title;
        $banner->is_active = $request->has('is_active');
        $banner->save();

        return redirect()->route('admin.banners.index')->with('success', 'Banner berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $lpk = $this->getLpk();
        $banner = Banner::where('lpk_id', $lpk->id)->findOrFail($id);

        // Hapus gambar fisik dari storage
        if (Storage::disk('public')->exists($banner->image_path)) {
            Storage::disk('public')->delete($banner->image_path);
        }

        $banner->delete();
        return redirect()->route('admin.banners.index')->with('success', 'Banner berhasil dihapus!');
    }
}