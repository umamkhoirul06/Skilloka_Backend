@extends('layouts.admin')

@section('title', 'Ubah Materi Pembelajaran')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.contents.index', ['course_id' => $content->course_id]) }}" class="w-9 h-9 rounded-xl bg-white border border-gray-200 flex items-center justify-center text-gray-600 hover:bg-gray-50 transition">
            <i class="fa-solid fa-arrow-left text-xs"></i>
        </a>
        <div>
            <h1 class="text-xl font-bold font-outfit text-gray-900">Ubah Materi Pembelajaran</h1>
            <p class="text-xs text-gray-500">Perbarui rincian modul atau tautan file pembelajaran.</p>
        </div>
    </div>

    @if($errors->any())
        <div class="p-4 rounded-xl bg-red-50 border border-red-200 text-red-800 text-xs">
            <ul class="list-disc pl-4 space-y-1">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white p-6 sm:p-8 rounded-2xl border border-gray-200 shadow-sm">
        <form action="{{ route('admin.contents.update', $content->id) }}" method="POST" enctype="multipart/form-data" class="space-y-5" x-data="{ contentType: '{{ $content->type }}' }">
            @csrf
            @method('PUT')

            <!-- Course Selection -->
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1.5">Pilih Kursus Pelatihan <span class="text-red-500">*</span></label>
                <select name="course_id" required class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-xs text-gray-800 focus:outline-none focus:border-indigo-500 bg-white">
                    @foreach($courses as $c)
                        <option value="{{ $c->id }}" {{ (old('course_id', $content->course_id) == $c->id) ? 'selected' : '' }}>
                            {{ $c->title }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Title -->
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1.5">Judul Materi / Bab <span class="text-red-500">*</span></label>
                <input type="text" name="title" value="{{ old('title', $content->title) }}" required class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-xs text-gray-800 focus:outline-none focus:border-indigo-500">
            </div>

            <!-- Type -->
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1.5">Tipe Konten <span class="text-red-500">*</span></label>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5">
                    <label class="flex items-center gap-2 p-3 rounded-xl border border-gray-200 cursor-pointer hover:bg-gray-50 text-xs font-medium text-gray-700" :class="contentType === 'document' ? 'border-indigo-500 bg-indigo-50/50 text-indigo-700' : ''">
                        <input type="radio" name="type" value="document" x-model="contentType" class="text-indigo-600">
                        <i class="fa-solid fa-file-pdf"></i> Dokumen PDF
                    </label>
                    <label class="flex items-center gap-2 p-3 rounded-xl border border-gray-200 cursor-pointer hover:bg-gray-50 text-xs font-medium text-gray-700" :class="contentType === 'video' ? 'border-indigo-500 bg-indigo-50/50 text-indigo-700' : ''">
                        <input type="radio" name="type" value="video" x-model="contentType" class="text-indigo-600">
                        <i class="fa-solid fa-video"></i> Video
                    </label>
                    <label class="flex items-center gap-2 p-3 rounded-xl border border-gray-200 cursor-pointer hover:bg-gray-50 text-xs font-medium text-gray-700" :class="contentType === 'article' ? 'border-indigo-500 bg-indigo-50/50 text-indigo-700' : ''">
                        <input type="radio" name="type" value="article" x-model="contentType" class="text-indigo-600">
                        <i class="fa-solid fa-align-left"></i> Teks / Artikel
                    </label>
                    <label class="flex items-center gap-2 p-3 rounded-xl border border-gray-200 cursor-pointer hover:bg-gray-50 text-xs font-medium text-gray-700" :class="contentType === 'link' ? 'border-indigo-500 bg-indigo-50/50 text-indigo-700' : ''">
                        <input type="radio" name="type" value="link" x-model="contentType" class="text-indigo-600">
                        <i class="fa-solid fa-link"></i> Link Tautan
                    </label>
                </div>
            </div>

            <!-- Upload File (for document) -->
            <div x-show="contentType === 'document'" class="space-y-2">
                @if($content->file_path)
                    <div class="p-3 bg-gray-50 rounded-xl border border-gray-200 flex items-center justify-between text-xs text-gray-700">
                        <span class="flex items-center gap-2">
                            <i class="fa-solid fa-file text-indigo-600"></i> File tersimpan: {{ basename($content->file_path) }}
                        </span>
                        <a href="{{ asset('storage/' . $content->file_path) }}" target="_blank" class="text-indigo-600 hover:underline">Unduh File</a>
                    </div>
                @endif
                <label class="block text-xs font-semibold text-gray-700 mb-1.5">Ganti File Modul (Opsional, PDF/DOCX maks. 20MB)</label>
                <input type="file" name="file" class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
            </div>

            <!-- URL input (for video/link) -->
            <div x-show="contentType === 'video' || contentType === 'link'">
                <label class="block text-xs font-semibold text-gray-700 mb-1.5">URL Video / Tautan Eksternal</label>
                <input type="url" name="content_url" value="{{ old('content_url', $content->content_url) }}" placeholder="https://..." class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-xs text-gray-800 focus:outline-none focus:border-indigo-500">
            </div>

            <!-- Body Description -->
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1.5">Ringkasan / Catatan Penjelasan Materi</label>
                <textarea name="body" rows="4" class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-xs text-gray-800 focus:outline-none focus:border-indigo-500">{{ old('body', $content->body) }}</textarea>
            </div>

            <!-- Order & Publish status -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Urutan Bab / Pertemuan</label>
                    <input type="number" name="order_index" value="{{ old('order_index', $content->order_index) }}" class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-xs text-gray-800 focus:outline-none focus:border-indigo-500">
                </div>
                <div class="flex items-center pt-6">
                    <label class="flex items-center gap-2 cursor-pointer text-xs font-semibold text-gray-700">
                        <input type="checkbox" name="is_published" value="1" {{ $content->is_published ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 focus:ring-0">
                        <span>Publikasikan Materi</span>
                    </label>
                </div>
            </div>

            <div class="pt-5 border-t border-gray-100 flex items-center justify-end gap-3">
                <a href="{{ route('admin.contents.index', ['course_id' => $content->course_id]) }}" class="px-5 py-2.5 rounded-xl text-xs font-semibold text-gray-600 hover:bg-gray-100 transition">Batal</a>
                <button type="submit" class="px-6 py-2.5 rounded-xl text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 shadow-md transition">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
