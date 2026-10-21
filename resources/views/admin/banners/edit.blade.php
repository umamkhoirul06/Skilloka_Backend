@extends('layouts.admin')

@section('title', 'Ubah Banner Promosi')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    {{-- Header Navigation & Title --}}
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.banners.index') }}" 
           class="w-9 h-9 rounded-xl bg-white border border-gray-200 flex items-center justify-center text-gray-600 hover:bg-gray-50 transition shadow-sm">
            <i class="fa-solid fa-arrow-left text-xs"></i>
        </a>
        <div>
            <h1 class="text-xl font-bold font-outfit text-gray-900">Ubah Banner Promosi</h1>
            <p class="text-xs text-gray-500">Perbarui judul, gambar, atau status tampilan banner promosi.</p>
        </div>
    </div>

    {{-- Error Banner --}}
    @if($errors->any())
        <div class="p-4 rounded-xl bg-red-50 border border-red-200 text-red-800 text-xs">
            <div class="font-semibold mb-1 flex items-center gap-2">
                <i class="fa-solid fa-circle-exclamation text-red-600"></i>
                <span>Terdapat kesalahan pada input:</span>
            </div>
            <ul class="list-disc pl-5 space-y-1">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form Card --}}
    <div class="bg-white p-6 sm:p-8 rounded-2xl border border-gray-200 shadow-sm">
        <form action="{{ route('admin.banners.update', $banner->id) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('PUT')

            <!-- Title -->
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                    Judul Banner / Program <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       name="title" 
                       value="{{ old('title', $banner->title) }}" 
                       required
                       class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-xs text-gray-800 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 bg-white transition">
            </div>

            <!-- Existing Image -->
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                    Gambar Banner Saat Ini
                </label>
                <div class="rounded-xl overflow-hidden border border-gray-200 max-h-56 bg-slate-900">
                    <img src="{{ Storage::url($banner->image_path) }}" 
                         alt="{{ $banner->title }}" 
                         class="w-full h-48 object-cover">
                </div>
            </div>

            <!-- Replace Image -->
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                    Ganti File Gambar (Opsional)
                </label>
                <input type="file" 
                       name="image" 
                       accept="image/*"
                       class="w-full text-xs text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition border border-gray-200 rounded-xl bg-gray-50/50 p-1.5">
                <p class="text-[11px] text-gray-400 mt-1">Biarkan kosong jika tidak ingin mengubah gambar banner saat ini.</p>
            </div>

            <!-- Active Checkbox -->
            <div class="pt-2">
                <label class="flex items-center gap-3 p-3.5 rounded-xl border border-gray-200 bg-gray-50/60 cursor-pointer hover:bg-gray-100/60 transition">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" 
                           name="is_active" 
                           id="is_active" 
                           value="1" 
                           {{ $banner->is_active ? 'checked' : '' }}
                           class="rounded border-gray-300 text-indigo-600 focus:ring-0">
                    <div>
                        <span class="text-xs font-semibold text-gray-800 block">Tampilkan Banner (Status Aktif)</span>
                        <span class="text-[11px] text-gray-400">Centang agar banner muncul di aplikasi mobile dan katalog web.</span>
                    </div>
                </label>
            </div>

            <!-- Footer Buttons -->
            <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-100">
                <a href="{{ route('admin.banners.index') }}"
                   class="px-5 py-2.5 rounded-xl border border-gray-200 text-xs font-semibold text-gray-600 hover:bg-gray-50 transition">
                    Batal
                </a>
                <button type="submit"
                        class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold shadow-sm shadow-indigo-600/20 transition flex items-center gap-2">
                    <i class="fa-solid fa-floppy-disk text-xs"></i>
                    <span>Simpan Perubahan Banner</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection