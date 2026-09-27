@extends('layouts.public')

@section('title', 'Daftar Lembaga Pelatihan Kerja (LPK) Resmi - Skilloka')
@section('meta_description', 'Direktori resmi Lembaga Pelatihan Kerja (LPK) terverifikasi di Kabupaten Indramayu. Temukan pusat pelatihan terdekat.')

@section('content')
<div class="bg-slate-100/70 border-b border-slate-200 py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-2xl">
            <h1 class="font-outfit text-3xl font-extrabold text-slate-900 tracking-tight">Lembaga Pelatihan Kerja (LPK) Terverifikasi</h1>
            <p class="text-sm text-slate-600 mt-2">Daftar lembaga mitra resmi di Kabupaten Indramayu dengan legalitas terpercaya.</p>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <!-- SEARCH FORM -->
    <div class="mb-8 max-w-xl">
        <form action="{{ route('public.lpks') }}" method="GET" class="flex gap-2">
            <div class="relative flex-1">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-3.5 text-slate-400 text-sm"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama LPK atau alamat..." class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-brand-500 bg-white">
            </div>
            <button type="submit" class="px-5 py-2.5 bg-brand-600 hover:bg-brand-700 text-white rounded-xl text-sm font-semibold shadow-sm transition">
                Cari LPK
            </button>
        </form>
    </div>

    <!-- GRID -->
    @if($lpks->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($lpks as $lpk)
                <div class="bg-white rounded-2xl border border-slate-200 p-6 hover:shadow-lg transition duration-300 flex flex-col justify-between">
                    <div>
                        <div class="flex items-start justify-between gap-3 mb-4">
                            <div class="w-12 h-12 rounded-xl bg-slate-900 text-white font-bold flex items-center justify-center text-lg shadow">
                                {{ strtoupper(substr($lpk->name, 0, 1)) }}
                            </div>
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold badge-verified">
                                <i class="fa-solid fa-circle-check text-[10px]"></i>
                                Terverifikasi
                            </span>
                        </div>

                        <h3 class="font-outfit text-lg font-bold text-slate-900 mb-1">{{ $lpk->name }}</h3>
                        <p class="text-xs text-slate-500 line-clamp-3 mb-4 leading-relaxed">
                            {{ $lpk->description ?? 'Lembaga Pelatihan Kerja terdaftar resmi yang menyediakan berbagai program pelatihan kejuruan vokasi berstandar kerja.' }}
                        </p>

                        <div class="space-y-1.5 text-xs text-slate-500 pt-2 border-t border-slate-100">
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-location-dot text-slate-400 w-4"></i>
                                <span class="truncate">{{ $lpk->address ?? 'Kabupaten Indramayu' }}</span>
                            </div>
                            @if($lpk->nib)
                                <div class="flex items-center gap-2">
                                    <i class="fa-solid fa-file-contract text-slate-400 w-4"></i>
                                    <span>NIB: {{ $lpk->nib }}</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between">
                        <span class="text-xs font-semibold text-brand-600 bg-brand-50 px-2.5 py-1 rounded-lg">
                            {{ $lpk->courses_count }} Kursus
                        </span>
                        <a href="{{ route('public.lpk.detail', $lpk->id) }}" class="text-xs font-bold text-slate-700 hover:text-brand-600 flex items-center gap-1">
                            <span>Lihat Profil & Kursus</span>
                            <i class="fa-solid fa-chevron-right text-[10px]"></i>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="pt-8">
            {{ $lpks->links() }}
        </div>
    @else
        <div class="p-16 text-center bg-white rounded-2xl border border-slate-200">
            <i class="fa-solid fa-building-columns text-4xl text-slate-300 mb-3"></i>
            <h3 class="font-outfit text-lg font-bold text-slate-800">Tidak ada LPK yang ditemukan</h3>
            <p class="text-xs text-slate-500 mt-1">Coba gunakan kata kunci pencarian yang berbeda.</p>
        </div>
    @endif
</div>
@endsection
