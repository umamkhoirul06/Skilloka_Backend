@extends('layouts.public')

@section('title', $lpk->name . ' - Skilloka')
@section('meta_description', 'Profil resmi ' . $lpk->name . ' di Skilloka. Cek program kursus, alamat, legalitas, dan kontak resmi.')

@section('content')
<div class="bg-white border-b border-slate-200 py-3.5">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-xs text-slate-500 flex items-center gap-2">
        <a href="{{ route('home') }}" class="hover:text-brand-600">Beranda</a>
        <i class="fa-solid fa-chevron-right text-[10px] text-slate-300"></i>
        <a href="{{ route('public.lpks') }}" class="hover:text-brand-600">Lembaga LPK</a>
        <i class="fa-solid fa-chevron-right text-[10px] text-slate-300"></i>
        <span class="text-slate-800 font-medium truncate">{{ $lpk->name }}</span>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-10">
    <!-- LPK PROFILE BANNER -->
    <div class="bg-white p-6 sm:p-10 rounded-3xl border border-slate-200 shadow-sm flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
        <div class="flex items-center gap-5">
            <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl bg-gradient-to-tr from-slate-900 to-indigo-900 text-white font-bold flex items-center justify-center text-2xl shadow-md">
                {{ strtoupper(substr($lpk->name, 0, 1)) }}
            </div>
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold badge-verified">
                        <i class="fa-solid fa-circle-check text-[10px]"></i> Mitra Resmi Terverifikasi
                    </span>
                </div>
                <h1 class="font-outfit text-2xl sm:text-3xl font-extrabold text-slate-900">{{ $lpk->name }}</h1>
                <p class="text-xs text-slate-500 mt-1">
                    <i class="fa-solid fa-location-dot text-brand-600 mr-1"></i> {{ $lpk->address ?? 'Kabupaten Indramayu, Jawa Barat' }}
                </p>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            @if($lpk->phone || $lpk->wa_number)
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $lpk->wa_number ?? $lpk->phone) }}" target="_blank" class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow transition flex items-center gap-2">
                    <i class="fa-brands fa-whatsapp text-sm"></i>
                    <span>Hubungi LPK</span>
                </a>
            @endif
        </div>
    </div>

    <!-- DETAILS & COURSES -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- LEFT: COURSES LIST -->
        <div class="lg:col-span-2 space-y-6">
            <div class="flex items-center justify-between">
                <h2 class="font-outfit text-xl font-bold text-slate-900">Program Kursus yang Dibuka</h2>
                <span class="text-xs font-semibold text-slate-500">{{ $lpk->courses->count() }} Kursus</span>
            </div>

            @if($lpk->courses->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    @foreach($lpk->courses as $course)
                        <div class="bg-white rounded-2xl border border-slate-200 p-5 hover:shadow-md transition flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <span class="px-2.5 py-0.5 rounded-md text-[10px] font-semibold bg-brand-50 text-brand-700">
                                        {{ $course->category->name ?? 'Kejuruan' }}
                                    </span>
                                    <span class="text-[10px] text-slate-400">{{ $course->level }}</span>
                                </div>
                                <h3 class="font-outfit text-base font-bold text-slate-900 mb-2">{{ $course->title }}</h3>
                                <p class="text-xs text-slate-500 line-clamp-2 leading-relaxed">
                                    {{ $course->description ?? 'Pelatihan kejuruan intensif langsung siap kerja.' }}
                                </p>
                            </div>

                            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between">
                                <span class="font-outfit text-sm font-extrabold text-slate-900">
                                    Rp {{ number_format($course->price, 0, ',', '.') }}
                                </span>
                                <a href="{{ route('public.course.detail', $course->id) }}" class="text-xs font-bold text-brand-600 hover:text-brand-700">
                                    Lihat Detail &rarr;
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="p-12 text-center bg-white rounded-2xl border border-slate-200 text-slate-500 text-xs">
                    Lembaga ini sedang mempersiapkan gelombang kelas pelatihan baru.
                </div>
            @endif
        </div>

        <!-- RIGHT: LPK INFO SIDEBAR -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
                <h3 class="font-outfit text-base font-bold text-slate-900">Tentang Lembaga</h3>
                <p class="text-xs text-slate-600 leading-relaxed">
                    {{ $lpk->description ?? 'Lembaga Pelatihan Kerja resmi yang berdedikasi melatih tenaga kerja terampil dan kompeten di Kabupaten Indramayu.' }}
                </p>

                <div class="space-y-3 pt-3 border-t border-slate-100 text-xs text-slate-600">
                    @if($lpk->legal_name)
                        <div>
                            <span class="text-slate-400 block text-[10px] uppercase font-bold">Nama Legal / Badan Hukum</span>
                            <span class="font-medium text-slate-800">{{ $lpk->legal_name }}</span>
                        </div>
                    @endif
                    @if($lpk->nib)
                        <div>
                            <span class="text-slate-400 block text-[10px] uppercase font-bold">Nomor NIB</span>
                            <span class="font-medium text-slate-800">{{ $lpk->nib }}</span>
                        </div>
                    @endif
                    <div>
                        <span class="text-slate-400 block text-[10px] uppercase font-bold">Alamat Lengkap</span>
                        <span class="font-medium text-slate-800">{{ $lpk->address ?? 'Kabupaten Indramayu' }}</span>
                    </div>
                </div>
            </div>

            <!-- Facilities if any -->
            @if($lpk->facilities && count((array)$lpk->facilities) > 0)
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-3">
                    <h3 class="font-outfit text-base font-bold text-slate-900">Fasilitas Lembaga</h3>
                    <div class="space-y-2">
                        @foreach((array)$lpk->facilities as $fac)
                            <div class="flex items-center gap-2 text-xs text-slate-700">
                                <i class="fa-solid fa-check text-emerald-500"></i>
                                <span>{{ $fac }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
