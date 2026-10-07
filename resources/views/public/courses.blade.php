@extends('layouts.public')

@section('title', 'Katalog Kursus & Pelatihan - Skilloka')
@section('meta_description', 'Jelajahi berbagai program pelatihan kerja resmi di Kabupaten Indramayu. Filter berdasarkan kategori, jenjang, dan biaya.')

@section('content')
<div class="bg-slate-100/70 border-b border-slate-200 py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-2xl">
            <h1 class="font-outfit text-3xl font-extrabold text-slate-900 tracking-tight">Katalog Kursus & Pelatihan</h1>
            <p class="text-sm text-slate-600 mt-2">Pilih keahlian terbaik dari Lembaga Pelatihan Kerja (LPK) terverifikasi resmi.</p>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- FILTER SIDEBAR -->
        <div class="lg:col-span-1">
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm sticky top-28 space-y-6">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                    <h3 class="font-outfit font-bold text-slate-900 text-base">Filter Kursus</h3>
                    <a href="{{ route('public.courses') }}" class="text-xs text-brand-600 hover:underline">Reset Filter</a>
                </div>

                <form action="{{ route('public.courses') }}" method="GET" class="space-y-5">
                    <!-- Search input -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Kata Kunci</label>
                        <div class="relative">
                            <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-3 text-slate-400 text-xs"></i>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Judul / nama LPK..." class="w-full pl-9 pr-3 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:border-brand-500">
                        </div>
                    </div>

                    <!-- Category filter -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Kategori Kejuruan</label>
                        <select name="category" class="w-full p-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:border-brand-500 bg-white">
                            <option value="">Semua Kategori</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->slug }}" {{ request('category') == $cat->slug ? 'selected' : '' }}>
                                    {{ $cat->name }} ({{ $cat->courses_count }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Level filter -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Tingkat Kesulitan</label>
                        <select name="level" class="w-full p-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:border-brand-500 bg-white">
                            <option value="">Semua Tingkat</option>
                            <option value="Pemula" {{ request('level') == 'Pemula' ? 'selected' : '' }}>Pemula</option>
                            <option value="Menengah" {{ request('level') == 'Menengah' ? 'selected' : '' }}>Menengah</option>
                            <option value="Mahir" {{ request('level') == 'Mahir' ? 'selected' : '' }}>Mahir</option>
                        </select>
                    </div>

                    <!-- Sort -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Urutkan Biaya</label>
                        <select name="sort" class="w-full p-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:border-brand-500 bg-white">
                            <option value="">Terbaru</option>
                            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Biaya Terendah</option>
                            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Biaya Tertinggi</option>
                        </select>
                    </div>

                    <button type="submit" class="w-full py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-semibold text-xs shadow-sm transition">
                        Terapkan Filter
                    </button>
                </form>
            </div>
        </div>

        <!-- COURSE LISTING GRID -->
        <div class="lg:col-span-3 space-y-6">
            <div class="flex items-center justify-between text-xs text-slate-500 pb-2">
                <span>Menampilkan <strong>{{ $courses->total() }}</strong> program pelatihan ditemukan</span>
            </div>

            @if($courses->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($courses as $course)
                        <div class="card-interactive bg-white rounded-2xl border border-slate-200 overflow-hidden hover:border-brand-300 hover:shadow-xl transition-all duration-300 flex flex-col justify-between group relative">
                            <!-- Top Accent Line -->
                            <div class="h-1 w-full bg-gradient-to-r from-brand-500 to-indigo-600 opacity-70 group-hover:opacity-100 transition-opacity"></div>
                            
                            <div>
                                <div class="p-5 pb-3">
                                    <div class="flex items-center justify-between mb-3">
                                        <span class="px-2.5 py-1 rounded-lg text-xs font-semibold bg-brand-50 text-brand-700 border border-brand-100/60">
                                            {{ $course->category->name ?? 'Kejuruan' }}
                                        </span>
                                        <span class="text-[11px] font-semibold text-slate-600 bg-slate-100 px-2.5 py-0.5 rounded-full border border-slate-200/50">
                                            {{ $course->level }}
                                        </span>
                                    </div>
                                    <h3 class="font-outfit text-base font-bold text-slate-900 group-hover:text-brand-600 transition-colors line-clamp-2">
                                        {{ $course->title }}
                                    </h3>
                                    <p class="text-xs text-slate-500 mt-2 line-clamp-2 leading-relaxed">
                                        {{ $course->description ?? 'Kurikulum pelatihan praktis berstandar kerja.' }}
                                    </p>
                                </div>

                                <div class="px-5 py-3 border-t border-slate-100 text-xs text-slate-600 space-y-2 bg-slate-50/50">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-6 h-6 rounded-md bg-white border border-slate-200/80 text-slate-400 flex items-center justify-center flex-shrink-0 text-[10px]">
                                            <i class="fa-solid fa-building"></i>
                                        </div>
                                        <span class="font-medium text-slate-800 truncate">{{ $course->lpk->name ?? 'Mitra LPK' }}</span>
                                    </div>
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-6 h-6 rounded-md bg-white border border-slate-200/80 text-slate-400 flex items-center justify-center flex-shrink-0 text-[10px]">
                                            <i class="fa-solid fa-clock"></i>
                                        </div>
                                        <span>Durasi: {{ $course->duration_hours }} Jam Pelatihan</span>
                                    </div>
                                    @if($course->cert_type)
                                        <div class="flex items-center gap-2.5">
                                            <div class="w-6 h-6 rounded-md bg-emerald-50 border border-emerald-200/70 text-emerald-600 flex items-center justify-center flex-shrink-0 text-[10px]">
                                                <i class="fa-solid fa-certificate"></i>
                                            </div>
                                            <span class="font-medium text-emerald-700">{{ $course->cert_type }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="p-4 sm:p-5 border-t border-slate-100 flex items-center justify-between bg-gradient-to-b from-white to-slate-50/80">
                                <div>
                                    <span class="text-[10px] uppercase font-bold tracking-wider text-slate-400 block mb-0.5">Biaya Pelatihan</span>
                                    <div class="flex items-baseline gap-1">
                                        <span class="font-outfit text-xs font-extrabold text-brand-600">Rp</span>
                                        <span class="font-outfit text-lg sm:text-xl font-black text-slate-900 tracking-tight group-hover:text-brand-600 transition-colors">
                                            {{ number_format($course->price, 0, ',', '.') }}
                                        </span>
                                    </div>
                                </div>
                                <a href="{{ route('public.course.detail', $course->id) }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold text-white bg-slate-900 group-hover:bg-brand-600 group-hover:shadow-md group-hover:shadow-brand-500/25 transition-all duration-200">
                                    <span>Lihat Detail</span>
                                    <i class="fa-solid fa-arrow-right text-[10px] transition-transform duration-200 group-hover:translate-x-0.5"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="pt-6">
                    {{ $courses->links() }}
                </div>
            @else
                <div class="p-16 text-center bg-white rounded-2xl border border-slate-200">
                    <i class="fa-solid fa-magnifying-glass text-4xl text-slate-300 mb-3"></i>
                    <h3 class="font-outfit text-lg font-bold text-slate-800">Tidak ada kursus yang sesuai</h3>
                    <p class="text-xs text-slate-500 mt-1 max-w-md mx-auto">Coba ubah kata kunci pencarian atau reset filter kategori untuk melihat program pelatihan lainnya.</p>
                    <a href="{{ route('public.courses') }}" class="inline-block mt-4 px-4 py-2 rounded-xl text-xs font-semibold bg-brand-600 text-white">
                        Reset Semua Filter
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
