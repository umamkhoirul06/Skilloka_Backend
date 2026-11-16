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

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
    @php
        $rawImages = $lpk->gallery_images ?? [];
        $coverPhoto = count($rawImages) > 0 ? $rawImages[0] : null;
        $logoPhoto = $lpk->logo_url;
        $initials = $lpk->initials;
    @endphp

    <!-- LPK PROFILE BANNER -->
    <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm overflow-hidden">
        {{-- Cover Banner --}}
        <div class="relative h-44 sm:h-56 w-full bg-gradient-to-r from-slate-900 via-indigo-950 to-blue-900 overflow-hidden">
            @if($coverPhoto)
                <img src="{{ $coverPhoto }}" alt="Cover {{ $lpk->name }}" class="w-full h-full object-cover opacity-40 blur-[1px]">
                <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-slate-900/40 to-transparent"></div>
            @else
                <div class="absolute inset-0 opacity-20 bg-[radial-gradient(#38bdf8_1px,transparent_1px)] [background-size:16px_16px]"></div>
                <div class="absolute inset-0 bg-gradient-to-t from-slate-950/70 to-transparent"></div>
            @endif

            <div class="absolute top-4 right-4 sm:top-6 sm:right-6 flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-bold bg-white/95 text-slate-800 shadow-md backdrop-blur-md">
                    <i class="fa-solid fa-circle-check text-emerald-500"></i>
                    <span>Mitra Resmi Terverifikasi</span>
                </span>
            </div>
        </div>

        {{-- Profile Header Content --}}
        <div class="px-6 sm:px-10 pb-7 pt-0 relative">
            <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-5">
                    {{-- Logo / Avatar: Hanya avatar yang naik ke atas banner --}}
                    <div class="relative w-24 h-24 sm:w-28 sm:h-28 -mt-12 sm:-mt-14 rounded-2xl overflow-hidden bg-white border-4 border-white shadow-xl flex-shrink-0 flex items-center justify-center z-10">
                        @if($logoPhoto)
                            <img src="{{ $logoPhoto }}" 
                                 alt="{{ $lpk->name }}" 
                                 class="w-full h-full object-cover"
                                 onerror="this.style.display='none'; this.nextElementSibling.classList.remove('hidden'); this.nextElementSibling.classList.add('flex');">
                            <div class="w-full h-full bg-gradient-to-tr from-indigo-600 to-blue-600 text-white font-black hidden items-center justify-center text-3xl tracking-wider">
                                {{ $initials }}
                            </div>
                        @else
                            <div class="w-full h-full bg-gradient-to-tr from-indigo-600 to-blue-600 text-white font-black flex items-center justify-center text-3xl tracking-wider">
                                {{ $initials }}
                            </div>
                        @endif
                    </div>

                    {{-- Title & Meta: Berada di area putih dengan kontras jelas --}}
                    <div class="space-y-1 pt-3 sm:pt-4">
                        @if($lpk->legal_name)
                            <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-md text-[11px] font-bold uppercase tracking-wider bg-indigo-50 text-indigo-700 border border-indigo-100/80">
                                <i class="fa-solid fa-landmark text-[10px]"></i>
                                <span>{{ $lpk->legal_name }}</span>
                            </div>
                        @endif
                        <h1 class="font-outfit text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight leading-tight">
                            {{ $lpk->name }}
                        </h1>
                        <p class="text-xs sm:text-sm text-slate-500 flex items-center gap-1.5">
                            <i class="fa-solid fa-location-dot text-brand-600 text-xs"></i>
                            <span>{{ $lpk->address ?? 'Kabupaten Indramayu, Jawa Barat' }}</span>
                        </p>
                    </div>
                </div>

                {{-- Action Buttons: Ukuran proporsional, icon WA pas dan rapi --}}
                <div class="flex flex-wrap items-center gap-2.5 w-full md:w-auto pt-2 sm:pt-4">
                    @if($lpk->wa_number || $lpk->phone)
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $lpk->wa_number ?? $lpk->phone) }}" 
                           target="_blank" 
                           class="flex-1 md:flex-none px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold shadow-sm hover:shadow transition flex items-center justify-center gap-2">
                            <i class="fa-brands fa-whatsapp text-sm"></i>
                            <span>Chat WhatsApp LPK</span>
                        </a>
                    @endif
                    @if($lpk->phone)
                        <a href="tel:{{ preg_replace('/[^0-9]/', '', $lpk->phone) }}" 
                           class="px-4 py-2.5 rounded-xl border border-slate-200 hover:bg-slate-50 text-slate-700 text-xs font-semibold transition flex items-center justify-center gap-1.5">
                            <i class="fa-solid fa-phone text-xs text-slate-500"></i>
                            <span>Telepon</span>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- DETAILS & COURSES -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- LEFT: PROFIL, GALERI & COURSES -->
        <div class="lg:col-span-2 space-y-8">
            
            {{-- 1. TENTANG & DESKRIPSI LPK --}}
            <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/90 shadow-sm space-y-4">
                <div class="flex items-center gap-3 pb-3 border-b border-slate-100">
                    <div class="w-10 h-10 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center text-lg">
                        <i class="fa-solid fa-building-circle-check"></i>
                    </div>
                    <div>
                        <h2 class="font-outfit text-lg sm:text-xl font-bold text-slate-900">Tentang & Profil Lembaga</h2>
                        <p class="text-xs text-slate-500">Mengenal lebih dekat visi pelatihan dan legalitas resmi lembaga</p>
                    </div>
                </div>

                <div class="text-xs sm:text-sm text-slate-700 leading-relaxed space-y-3 pt-2">
                    @if(!empty($lpk->description))
                        <div class="prose prose-sm max-w-none text-slate-700 whitespace-pre-line leading-relaxed">
                            {{ $lpk->description }}
                        </div>
                    @else
                        <p class="italic text-slate-400">
                            {{ $lpk->name }} adalah Lembaga Pelatihan Kerja (LPK) resmi mitra Skilloka di Kabupaten Indramayu yang berkomitmen mencetak sumber daya manusia kompeten dan tersertifikasi siap kerja di industri lokal maupun internasional.
                        </p>
                    @endif
                </div>
            </div>

            {{-- 2. GALERI FOTO LPK --}}
            @if(count($rawImages) > 0)
                <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/90 shadow-sm space-y-5">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-lg">
                                <i class="fa-solid fa-images"></i>
                            </div>
                            <div>
                                <h2 class="font-outfit text-lg sm:text-xl font-bold text-slate-900">Galeri Fasilitas & Sarana Pelatihan</h2>
                                <p class="text-xs text-slate-500">Dokumentasi ruang teori, bengkel praktek, dan kegiatan belajar mengajar</p>
                            </div>
                        </div>
                        <span class="text-xs font-bold text-indigo-600 bg-indigo-50 px-3 py-1 rounded-full">
                            {{ count($rawImages) }} Foto
                        </span>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3.5">
                        @foreach($rawImages as $imgUrl)
                            <div class="relative group rounded-2xl overflow-hidden border border-slate-200 aspect-video bg-slate-100 shadow-sm hover:shadow-md transition">
                                <img src="{{ $imgUrl }}" 
                                     alt="Galeri {{ $lpk->name }}" 
                                     class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- 3. DAFTAR KURSUS --}}
            <div class="space-y-5">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="font-outfit text-xl font-bold text-slate-900">Program Kursus yang Dibuka</h2>
                        <p class="text-xs text-slate-500 mt-0.5">Pilihan program kejuruan bersertifikasi dari lembaga ini</p>
                    </div>
                    <span class="text-xs font-semibold text-slate-600 bg-slate-100 px-3 py-1.5 rounded-full">
                        {{ $lpk->courses->count() }} Program Pelatihan
                    </span>
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
                                        <span class="text-[10px] text-slate-400 capitalize">{{ $course->level }}</span>
                                    </div>
                                    <h3 class="font-outfit text-base font-bold text-slate-900 mb-2 leading-snug">{{ $course->title }}</h3>
                                    <p class="text-xs text-slate-500 line-clamp-2 leading-relaxed">
                                        {{ $course->description ?? 'Pelatihan kejuruan intensif langsung siap kerja.' }}
                                    </p>
                                </div>

                                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between">
                                    <span class="font-outfit text-sm font-extrabold text-slate-900">
                                        Rp {{ number_format($course->price, 0, ',', '.') }}
                                    </span>
                                    <a href="{{ route('public.course.detail', $course->id) }}" class="text-xs font-bold text-brand-600 hover:text-brand-700 flex items-center gap-1">
                                        <span>Lihat Detail</span>
                                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-12 text-center bg-white rounded-3xl border border-slate-200 text-slate-500 text-xs space-y-2">
                        <i class="fa-solid fa-graduation-cap text-3xl text-slate-300"></i>
                        <p class="font-medium text-slate-700">Lembaga ini sedang mempersiapkan gelombang kelas pelatihan baru.</p>
                        <p class="text-slate-400">Silakan hubungi kontak LPK via WhatsApp untuk info pendaftaran terdekat.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- RIGHT: LPK INFO SIDEBAR -->
        <div class="lg:col-span-1 space-y-6">
            {{-- INFORMASI LEGALITAS & KONTAK --}}
            <div class="bg-white p-6 rounded-3xl border border-slate-200/90 shadow-sm space-y-5">
                <h3 class="font-outfit text-base font-bold text-slate-900 flex items-center gap-2">
                    <i class="fa-solid fa-shield-halved text-emerald-600"></i>
                    <span>Informasi Legalitas Resmi</span>
                </h3>

                <div class="space-y-3.5 text-xs text-slate-600">
                    @if($lpk->legal_name)
                        <div class="p-3 rounded-xl bg-slate-50 border border-slate-100">
                            <span class="text-slate-400 block text-[10px] uppercase font-bold tracking-wider mb-0.5">Nama Legalitas / Badan Hukum</span>
                            <span class="font-semibold text-slate-800">{{ $lpk->legal_name }}</span>
                        </div>
                    @endif
                    @if($lpk->nib)
                        <div class="p-3 rounded-xl bg-slate-50 border border-slate-100">
                            <span class="text-slate-400 block text-[10px] uppercase font-bold tracking-wider mb-0.5">Nomor Induk Berusaha (NIB)</span>
                            <span class="font-semibold text-slate-800 font-mono">{{ $lpk->nib }}</span>
                        </div>
                    @endif
                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-100">
                        <span class="text-slate-400 block text-[10px] uppercase font-bold tracking-wider mb-0.5">Alamat Lengkap Operasional</span>
                        <span class="font-semibold text-slate-800 leading-relaxed block">{{ $lpk->address ?? 'Kabupaten Indramayu, Jawa Barat' }}</span>
                        @if($lpk->address)
                            <a href="https://maps.google.com/?q={{ urlencode($lpk->address) }}" 
                               target="_blank" 
                               class="inline-flex items-center gap-1.5 text-brand-600 font-bold hover:underline mt-2 text-[11px]">
                                <i class="fa-solid fa-map-location-dot"></i>
                                <span>Buka di Google Maps</span>
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Facilities if any -->
            @php
                $rawFac = $lpk->facilities ?? [];
                $facList = is_string($rawFac) ? (json_decode($rawFac, true) ?: []) : (is_array($rawFac) ? $rawFac : []);
            @endphp
            @if(count($facList) > 0)
                <div class="bg-white p-6 rounded-3xl border border-slate-200/90 shadow-sm space-y-4">
                    <h3 class="font-outfit text-base font-bold text-slate-900 flex items-center gap-2">
                        <i class="fa-solid fa-toolbox text-brand-600"></i>
                        <span>Fasilitas Pelatihan</span>
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-1 gap-2.5">
                        @foreach($facList as $fac)
                            <div class="flex items-center gap-2.5 p-2 rounded-xl bg-slate-50 text-xs font-medium text-slate-700">
                                <i class="fa-solid fa-circle-check text-emerald-500 text-sm"></i>
                                <span>{{ $fac }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Konsultasi AI Assistant Card --}}
            <div class="p-6 rounded-3xl bg-gradient-to-br from-brand-600 to-indigo-700 text-white space-y-3 shadow-md">
                <div class="flex items-center gap-2.5">
                    <i class="fa-solid fa-robot text-xl text-yellow-300"></i>
                    <h4 class="font-outfit font-bold text-sm">Bingung Pilih Kursus?</h4>
                </div>
                <p class="text-xs text-white/90 leading-relaxed">
                    Tanyakan prospek kerja, rincian materi, atau syarat pendaftaran langsung ke asisten AI Loka.
                </p>
                <a href="{{ route('public.about') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white text-brand-700 text-xs font-bold shadow-sm hover:bg-slate-100 transition">
                    <span>Mulai Konsultasi</span>
                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
