@extends('layouts.admin')

@section('title', 'Materi & Silabus Kursus')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold font-outfit text-gray-900">Manajemen Materi Pembelajaran</h1>
            <p class="text-xs text-gray-500 mt-1">Unggah dan kelola modul, video panduan, serta dokumen silabus untuk peserta kursus Anda.</p>
        </div>
        <a href="{{ route('admin.contents.create', ['course_id' => request('course_id')]) }}" class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-semibold shadow-md transition flex items-center gap-2">
            <i class="fa-solid fa-plus text-xs"></i>
            <span>Tambah Materi Baru</span>
        </a>
    </div>

    @if(session('success'))
        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-medium">
            {{ session('success') }}
        </div>
    @endif

    <!-- Filter by Course Card -->
    <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-3">
        <div class="text-xs font-bold text-gray-700">Filter Berdasarkan Kursus:</div>
        <form action="{{ route('admin.contents.index') }}" method="GET" class="flex items-center gap-2 w-full sm:w-auto">
            <select name="course_id" onchange="this.form.submit()" class="px-3.5 py-2 rounded-xl border border-gray-200 text-xs text-gray-700 focus:outline-none focus:border-indigo-500 bg-white">
                <option value="">Semua Kursus</option>
                @foreach($courses as $c)
                    <option value="{{ $c->id }}" {{ request('course_id') == $c->id ? 'selected' : '' }}>
                        {{ $c->title }}
                    </option>
                @endforeach
            </select>
            @if(request('course_id'))
                <a href="{{ route('admin.contents.index') }}" class="text-xs text-indigo-600 hover:underline">Reset</a>
            @endif
        </form>
    </div>

    <!-- Contents Table Card -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-gray-600">
                <thead class="bg-gray-50/80 text-gray-700 font-semibold border-b border-gray-200 uppercase tracking-wider text-[11px]">
                    <tr>
                        <th class="py-3.5 px-6">Urutan</th>
                        <th class="py-3.5 px-6">Judul Materi</th>
                        <th class="py-3.5 px-6">Kursus</th>
                        <th class="py-3.5 px-6">Tipe Konten</th>
                        <th class="py-3.5 px-6">Status Publikasi</th>
                        <th class="py-3.5 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($contents as $item)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="py-3.5 px-6 font-mono font-bold text-indigo-600">#{{ $item->order_index }}</td>
                            <td class="py-3.5 px-6">
                                <div class="font-bold text-gray-900 text-sm">{{ $item->title }}</div>
                                @if($item->body)
                                    <div class="text-[11px] text-gray-400 line-clamp-1 mt-0.5">{{ Str::limit(strip_tags($item->body), 50) }}</div>
                                @endif
                            </td>
                            <td class="py-3.5 px-6 font-medium text-gray-700">{{ $item->course->title ?? '-' }}</td>
                            <td class="py-3.5 px-6">
                                @if($item->type === 'video')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[10px] font-semibold bg-red-50 text-red-700">
                                        <i class="fa-solid fa-video"></i> Video
                                    </span>
                                @elseif($item->type === 'document')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[10px] font-semibold bg-blue-50 text-blue-700">
                                        <i class="fa-solid fa-file-pdf"></i> Dokumen PDF
                                    </span>
                                @elseif($item->type === 'article')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[10px] font-semibold bg-purple-50 text-purple-700">
                                        <i class="fa-solid fa-align-left"></i> Artikel Teks
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[10px] font-semibold bg-gray-100 text-gray-700">
                                        <i class="fa-solid fa-link"></i> Link Eksternal
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-6">
                                @if($item->is_published)
                                    <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-emerald-600">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Dipublikasikan
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-gray-400">
                                        <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span> Draft
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-6 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    @if($item->file_path)
                                        <a href="{{ asset('storage/' . $item->file_path) }}" target="_blank" class="px-2.5 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-xs font-medium" title="Buka Dokumen">
                                            <i class="fa-solid fa-download"></i>
                                        </a>
                                    @elseif($item->content_url)
                                        <a href="{{ $item->content_url }}" target="_blank" class="px-2.5 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-xs font-medium" title="Buka Link">
                                            <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                        </a>
                                    @endif
                                    <a href="{{ route('admin.contents.edit', $item->id) }}" class="px-3 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 rounded-lg text-xs font-medium transition">
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.contents.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus materi pembelajaran ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-1.5 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg text-xs font-medium transition">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-gray-400">
                                <div class="w-12 h-12 rounded-full bg-gray-100 text-gray-400 flex items-center justify-center mx-auto mb-3 text-lg">
                                    <i class="fa-solid fa-book-open"></i>
                                </div>
                                <div class="font-bold text-gray-700">Belum ada materi pembelajaran</div>
                                <p class="text-xs text-gray-400 mt-1">Klik tombol 'Tambah Materi Baru' untuk mulai mengunggah modul kursus.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-gray-100">
            {{ $contents->links() }}
        </div>
    </div>
</div>
@endsection
