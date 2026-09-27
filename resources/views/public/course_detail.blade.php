@extends('layouts.public')

@section('title', $course->title . ' - Skilloka')
@section('meta_description', 'Detail pelatihan ' . $course->title . ' diselenggarakan oleh ' . ($course->lpk->name ?? 'LPK Mitra') . '. Cek kurikulum, jadwal, dan biaya.')

@section('content')
<!-- BREADCRUMB -->
<div class="bg-white border-b border-slate-200 py-3.5">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-xs text-slate-500 flex items-center gap-2">
        <a href="{{ route('home') }}" class="hover:text-brand-600">Beranda</a>
        <i class="fa-solid fa-chevron-right text-[10px] text-slate-300"></i>
        <a href="{{ route('public.courses') }}" class="hover:text-brand-600">Kursus</a>
        <i class="fa-solid fa-chevron-right text-[10px] text-slate-300"></i>
        <span class="text-slate-800 font-medium truncate">{{ $course->title }}</span>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
        <!-- LEFT: COURSE MAIN CONTENT -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Header Block -->
            <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-sm space-y-4">
                <div class="flex flex-wrap items-center gap-2.5">
                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-brand-50 text-brand-700 border border-brand-100">
                        {{ $course->category->name ?? 'Kejuruan' }}
                    </span>
                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-700">
                        Level: {{ $course->level }}
                    </span>
                    @if($course->cert_type)
                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100 flex items-center gap-1.5">
                            <i class="fa-solid fa-award"></i>
                            {{ $course->cert_type }}
                        </span>
                    @endif
                </div>

                <h1 class="font-outfit text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight leading-snug">
                    {{ $course->title }}
                </h1>

                <!-- Provider LPK -->
                <div class="flex items-center gap-3 pt-2">
                    <div class="w-10 h-10 rounded-xl bg-slate-800 text-white font-bold flex items-center justify-center text-sm shadow">
                        {{ strtoupper(substr($course->lpk->name ?? 'L', 0, 1)) }}
                    </div>
                    <div>
                        <div class="text-xs text-slate-400">Diselenggarakan oleh:</div>
                        <a href="{{ route('public.lpk.detail', $course->lpk_id) }}" class="text-sm font-bold text-slate-900 hover:text-brand-600 transition flex items-center gap-1.5">
                            <span>{{ $course->lpk->name ?? 'LPK Mitra' }}</span>
                            <i class="fa-solid fa-circle-check text-emerald-500 text-xs"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-sm space-y-4">
                <h2 class="font-outfit text-xl font-bold text-slate-900">Deskripsi Pelatihan</h2>
                <div class="text-sm text-slate-600 leading-relaxed space-y-3">
                    {!! nl2br(e($course->description ?? 'Program pelatihan intensif yang dirancang untuk membekali peserta dengan keterampilan praktis langsung pakai di dunia industri.')) !!}
                </div>
            </div>

            <!-- Syllabus / Materi -->
            <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-sm space-y-4">
                <h2 class="font-outfit text-xl font-bold text-slate-900">Silabus & Materi Pembelajaran</h2>
                
                @if($course->contents->count() > 0)
                    <div class="divide-y divide-slate-100 border border-slate-100 rounded-2xl overflow-hidden">
                        @foreach($course->contents as $index => $content)
                            <div class="p-4 flex items-center justify-between bg-slate-50/50 hover:bg-slate-50 transition">
                                <div class="flex items-center gap-3">
                                    <span class="w-7 h-7 rounded-lg bg-brand-100 text-brand-700 text-xs font-bold flex items-center justify-center">
                                        {{ $index + 1 }}
                                    </span>
                                    <div>
                                        <h4 class="text-sm font-bold text-slate-800">{{ $content->title }}</h4>
                                        <span class="text-[10px] uppercase font-semibold text-slate-400">{{ $content->type }}</span>
                                    </div>
                                </div>
                                <span class="text-xs text-slate-400"><i class="fa-solid fa-lock mr-1 text-[10px]"></i> Materi Kelas</span>
                            </div>
                        @endforeach
                    </div>
                @elseif($course->syllabus)
                    <div class="text-sm text-slate-600 leading-relaxed bg-slate-50 p-5 rounded-2xl border border-slate-100 whitespace-pre-line">
                        {{ $course->syllabus }}
                    </div>
                @else
                    <p class="text-xs text-slate-500 italic">Silabus terperinci akan dibagikan langsung saat kelas dimulai.</p>
                @endif
            </div>

            <!-- Facilities -->
            @if($course->facilities && count((array)$course->facilities) > 0)
                <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-sm space-y-4">
                    <h2 class="font-outfit text-xl font-bold text-slate-900">Fasilitas yang Disediakan</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @foreach((array)$course->facilities as $fac)
                            <div class="flex items-center gap-2.5 text-xs text-slate-700 bg-slate-50 p-3 rounded-xl border border-slate-100">
                                <i class="fa-solid fa-check text-emerald-500 text-sm"></i>
                                <span>{{ $fac }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Schedules -->
            <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-sm space-y-4">
                <h2 class="font-outfit text-xl font-bold text-slate-900">Jadwal Kelas Tersedia</h2>
                @if($course->schedules->count() > 0)
                    <div class="space-y-3">
                        @foreach($course->schedules as $sch)
                            <div class="p-4 rounded-2xl border border-slate-200 bg-slate-50 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                <div>
                                    <div class="text-xs font-semibold text-brand-600">Batch Kelas</div>
                                    <div class="text-sm font-bold text-slate-900 mt-0.5">
                                        {{ \Carbon\Carbon::parse($sch->start_date)->translatedFormat('d M Y') }} s/d {{ \Carbon\Carbon::parse($sch->end_date)->translatedFormat('d M Y') }}
                                    </div>
                                    <div class="text-xs text-slate-500 mt-1">
                                        <i class="fa-solid fa-clock text-slate-400 mr-1"></i> Jam: {{ substr($sch->daily_start, 0, 5) }} - {{ substr($sch->daily_end, 0, 5) }} WIB
                                        @if($sch->days_of_week)
                                            &bull; Hari: {{ $sch->days_of_week }}
                                        @endif
                                    </div>
                                </div>
                                <div class="text-right sm:text-right">
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700">
                                        Kuota: {{ $sch->max_capacity }} Peserta
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-6 text-center bg-slate-50 rounded-2xl border border-slate-100 text-slate-500 text-xs">
                        Jadwal gelombang berikutnya sedang disiapkan oleh LPK penyelenggara.
                    </div>
                @endif
            </div>
        </div>

        <!-- RIGHT: BOOKING & SUMMARY CARD (STICKY) -->
        <div class="lg:col-span-1">
            <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-lg sticky top-28 space-y-6">
                <div>
                    <span class="text-xs uppercase font-bold text-slate-400 tracking-wider block">Biaya Pelatihan</span>
                    <div class="font-outfit text-3xl font-extrabold text-slate-900 mt-1">
                        Rp {{ number_format($course->price, 0, ',', '.') }}
                    </div>
                    <p class="text-[11px] text-slate-400 mt-1">Biaya termasuk sertifikat kelulusan & bahan praktik.</p>
                </div>

                <div class="space-y-3 pt-3 border-t border-slate-100 text-xs text-slate-600">
                    <div class="flex items-center justify-between">
                        <span class="text-slate-400">Total Jam:</span>
                        <span class="font-bold text-slate-800">{{ $course->duration_hours }} Jam Belajar</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-400">Tingkat:</span>
                        <span class="font-bold text-slate-800">{{ $course->level }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-400">Maks. Peserta/Kelas:</span>
                        <span class="font-bold text-slate-800">{{ $course->max_participants ?? 20 }} Orang</span>
                    </div>
                </div>

                <!-- Booking CTA -->
                <div class="pt-4 border-t border-slate-100 space-y-3">
                    <div class="p-4 rounded-2xl bg-brand-50 border border-brand-100 text-brand-900 text-xs space-y-2">
                        <div class="font-bold flex items-center gap-1.5 text-brand-700">
                            <i class="fa-solid fa-mobile-screen"></i> Booking Melalui Aplikasi Mobile
                        </div>
                        <p class="text-slate-600 leading-relaxed text-[11px]">
                            Booking jadwal, pemilihan paket langganan, dan pembayaran terverifikasi dilakukan melalui aplikasi mobile Skilloka.
                        </p>
                    </div>

                    <a href="https://wa.me/62895405193300?text=Halo%20Admin%20Skilloka,%20saya%20tertarik%20dengan%20kursus%20{{ urlencode($course->title) }}" target="_blank" class="shiny-btn shiny-btn-emerald w-full py-3.5 rounded-xl text-white font-bold text-xs shadow-md flex items-center justify-center gap-2">
                        <i class="fa-brands fa-whatsapp text-sm"></i>
                        <span>Konsultasi / Daftar Kursus</span>
                    </a>
                </div>

                <!-- LPK Info Box -->
                <div class="pt-4 border-t border-slate-100">
                    <div class="text-[11px] uppercase font-bold text-slate-400 mb-2">Lokasi Pelaksanaan</div>
                    <p class="text-xs text-slate-700 leading-relaxed">
                        <i class="fa-solid fa-location-dot text-brand-600 mr-1.5"></i>
                        {{ $course->lpk->address ?? 'Kabupaten Indramayu, Jawa Barat' }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
