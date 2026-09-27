{{-- resources/views/admin/courses/index.blade.php --}}

@extends('layouts.admin')

@section('title', 'Daftar Course')

@section('content')

    <div class="w-full px-4 sm:px-6 lg:px-8 py-8">

        {{-- HEADER --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
            <div>
                <h2 class="font-outfit text-2xl font-black text-slate-900 tracking-tight">Katalog Program Pelatihan</h2>
                <p class="text-xs text-slate-500 mt-1">Kelola seluruh kurikulum, jadwal, dan penetapan harga pelatihan kejuruan LPK Anda.</p>
            </div>
            <a href="{{ route('admin.courses.create') }}"
                class="shiny-btn shiny-btn-brand inline-flex items-center gap-2 px-4 py-2.5 text-white text-xs font-bold rounded-xl shadow-md shadow-brand-500/20">
                <i class="fa-solid fa-plus text-xs"></i>
                <span>Tambah Program Baru</span>
            </a>
        </div>

        {{-- SUCCESS MESSAGE --}}
        @if(session('success'))
            <div class="mb-6 rounded-2xl bg-emerald-50 p-4 border border-emerald-200 flex items-center justify-between gap-3 text-emerald-900 text-xs">
                <div class="flex items-center gap-2.5">
                    <i class="fa-solid fa-circle-check text-emerald-600 text-base"></i>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-800 text-xs font-bold">&times;</button>
            </div>
        @endif

        {{-- TABLE CARD --}}
        <div class="w-full bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="w-full overflow-x-auto">
                <table class="w-full min-w-full table-auto divide-y divide-slate-100">
                    <thead class="bg-slate-50/80">
                        <tr>
                            <th scope="col"
                                class="px-6 py-4 text-left text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                                Program Pelatihan
                            </th>
                            <th scope="col"
                                class="px-6 py-4 text-left text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                                Kategori & Level
                            </th>
                            <th scope="col"
                                class="px-6 py-4 text-left text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                                Biaya Kursus
                            </th>
                            <th scope="col"
                                class="px-6 py-4 text-left text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                                Status Publikasi
                            </th>
                            <th scope="col"
                                class="px-6 py-4 text-right text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-100">
                        @forelse($courses as $course)
                            <tr class="hover:bg-slate-50/60 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div
                                            class="flex-shrink-0 h-12 w-12 rounded-xl bg-slate-100 overflow-hidden border border-slate-200">
                                            @if(!empty($course->images) && count($course->images) > 0)
                                                <img class="h-12 w-12 object-cover"
                                                    src="{{ asset('storage/' . $course->images[0]) }}" alt="{{ $course->title }}">
                                            @else
                                                <div
                                                    class="h-12 w-12 flex items-center justify-center bg-brand-50 text-brand-400">
                                                    <i class="fa-solid fa-graduation-cap text-base"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-xs font-bold text-slate-900">{{ $course->title }}</div>
                                            <div class="text-[11px] text-slate-400 mt-0.5">{{ $course->duration_hours }} Jam • Kuota
                                                {{ $course->max_participants ?? '-' }} Peserta</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-xs text-slate-800 font-semibold">{{ $course->category->name ?? '-' }}</div>
                                    <div class="text-[11px] text-slate-400 capitalize mt-0.5">{{ $course->level }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-xs font-bold text-slate-900">
                                        Rp {{ number_format($course->price, 0, ',', '.') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($course->is_active)
                                        <span
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>
                                            Aktif Publik
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-slate-100 text-slate-600 border border-slate-200">
                                            <span class="w-1.5 h-1.5 bg-slate-400 rounded-full"></span>
                                            Draft / Nonaktif
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-xs font-medium">
                                    <div class="flex justify-end gap-1.5">
                                        <a href="{{ route('admin.courses.edit', $course->id) }}"
                                            class="w-8 h-8 rounded-lg flex items-center justify-center text-amber-600 hover:bg-amber-50 border border-transparent hover:border-amber-200 transition-colors"
                                            title="Edit Program">
                                            <i class="fa-solid fa-pen text-xs"></i>
                                        </a>
                                        <form action="{{ route('admin.courses.destroy', $course->id) }}" method="POST"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus course ini?');"
                                            class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="w-8 h-8 rounded-lg flex items-center justify-center text-rose-600 hover:bg-rose-50 border border-transparent hover:border-rose-200 transition-colors cursor-pointer"
                                                title="Hapus Program">
                                                <i class="fa-regular fa-trash-can text-xs"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center text-slate-400">
                                        <div class="w-12 h-12 rounded-2xl bg-slate-100 flex items-center justify-center text-xl text-slate-400 mb-3">
                                            <i class="fa-solid fa-inbox"></i>
                                        </div>
                                        <p class="text-sm font-bold text-slate-800">Belum Ada Program Pelatihan</p>
                                        <p class="text-xs text-slate-400 mt-1">Mulai buat kurikulum kursus pertama untuk ditampilkan pada direktori pencarian siswa.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($courses->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                    {{ $courses->links() }}
                </div>
            @endif
        </div>
    </div>

@endsection