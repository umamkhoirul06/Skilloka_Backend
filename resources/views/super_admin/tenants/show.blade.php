@extends('layouts.super_admin')

@section('title', 'Detail Mitra LPK - ' . ($tenant->lpk->name ?? $tenant->name ?? 'LPK'))

@section('content')
@php
    $lpk = $tenant->lpk;
    $user = $tenant->users->first();
    $facilities = [];
    if ($lpk && !empty($lpk->facilities)) {
        $facilities = is_array($lpk->facilities) ? $lpk->facilities : (json_decode($lpk->facilities, true) ?: [$lpk->facilities]);
    }
    $totalCourses = $lpk?->courses?->count() ?? 0;
    $totalBookings = $lpk?->courses?->sum('bookings_count') ?? 0;
@endphp

<div class="space-y-6">

    <!-- ═══════════════════════════════════════
         BREADCRUMB & TOP ACTIONS
    ═══════════════════════════════════════ -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('super.tenants') }}" 
               class="w-10 h-10 rounded-xl bg-white border border-slate-200 text-slate-600 hover:text-indigo-600 hover:border-indigo-200 hover:bg-indigo-50/50 flex items-center justify-center transition shadow-2xs">
                <i class="fa-solid fa-arrow-left text-sm"></i>
            </a>
            <div>
                <div class="flex items-center gap-2 text-xs text-slate-500">
                    <a href="{{ route('super.tenants') }}" class="hover:text-indigo-600 transition">Mitra LPK</a>
                    <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
                    <span class="text-slate-700 font-medium">Detail Lembaga</span>
                </div>
                <h1 class="font-outfit text-2xl font-bold text-slate-900 tracking-tight mt-0.5">
                    {{ $lpk->name ?? $tenant->name ?? 'Detail Mitra LPK' }}
                </h1>
            </div>
        </div>

        <div class="flex items-center gap-2.5">
            @if($lpk)
                <a href="{{ route('public.lpk.detail', $lpk->id) }}" target="_blank"
                   class="px-4 py-2.5 bg-white border border-slate-200 hover:border-slate-300 text-slate-700 rounded-xl text-xs font-semibold shadow-2xs transition flex items-center gap-2">
                    <i class="fa-solid fa-arrow-up-right-from-square text-xs text-slate-400"></i>
                    <span>Kunjungi Web Publik</span>
                </a>
            @endif

            <form action="{{ route('super.tenants.delete', $tenant->id) }}" method="POST" 
                  onsubmit="return confirm('PERINGATAN: Menghapus mitra LPK ini akan menghapus akun admin, data lembaga, dan kursus terkait. Yakin ingin melanjutkan?');">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="px-4 py-2.5 bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200/80 rounded-xl text-xs font-semibold transition flex items-center gap-2">
                    <i class="fa-solid fa-trash-can text-xs"></i>
                    <span>Hapus Kemitraan</span>
                </button>
            </form>
        </div>
    </div>

    <!-- ═══════════════════════════════════════
         PROFILE BANNER CARD
    ═══════════════════════════════════════ -->
    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-2xs overflow-hidden">
        <!-- Banner Top Cover with Gradient -->
        <div class="h-32 sm:h-36 bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 relative">
            <div class="absolute inset-0 bg-[radial-gradient(#3b82f6_1px,transparent_1px)] [background-size:16px_16px] opacity-20"></div>
            <div class="absolute right-6 top-4 hidden sm:flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/10 backdrop-blur-md border border-white/15 text-white/90 text-xs">
                <i class="fa-solid fa-calendar-days text-[11px] text-indigo-300"></i>
                <span>Bergabung sejak {{ $tenant->created_at ? $tenant->created_at->translatedFormat('d F Y') : '-' }}</span>
            </div>
        </div>

        <!-- Banner Info Row -->
        <div class="px-6 pb-6 pt-3 relative">
            <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-5">
                <div class="flex flex-col sm:flex-row sm:items-end gap-5">
                    <!-- Logo Avatar (Hanya avatar yang overlap ke banner) -->
                    <div class="relative shrink-0 -mt-14 sm:-mt-16">
                        @if($lpk && !empty($lpk->logo))
                            <img src="{{ asset('storage/' . $lpk->logo) }}" 
                                 alt="{{ $lpk->name }}" 
                                 class="w-24 h-24 sm:w-28 sm:h-28 rounded-2xl object-cover ring-4 ring-white shadow-md bg-white border border-slate-100">
                        @else
                            <div class="w-24 h-24 sm:w-28 sm:h-28 rounded-2xl bg-gradient-to-tr from-indigo-600 via-indigo-500 to-blue-500 text-white font-outfit font-extrabold text-3xl sm:text-4xl flex items-center justify-center ring-4 ring-white shadow-md">
                                {{ strtoupper(substr($lpk->name ?? $tenant->name ?? 'L', 0, 1)) }}
                            </div>
                        @endif
                        <span class="absolute -bottom-1 -right-1 w-6 h-6 rounded-full bg-emerald-500 ring-2 ring-white flex items-center justify-center text-white text-[10px] shadow-xs" title="Aktif">
                            <i class="fa-solid fa-check"></i>
                        </span>
                    </div>

                    <!-- Title & Badges (Berada sepenuhnya di area putih dengan kontras jelas) -->
                    <div class="pb-1">
                        <div class="flex flex-wrap items-center gap-2.5">
                            <h2 class="font-outfit text-xl sm:text-2xl font-bold text-slate-900 tracking-tight leading-tight">
                                {{ $lpk->name ?? $tenant->name ?? '-' }}
                            </h2>
                            <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                Mitra Aktif
                            </span>
                            @if(($lpk->status_verifikasi ?? '') === 'approved' || ($lpk->is_verified ?? false))
                                <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-200 flex items-center gap-1">
                                    <i class="fa-solid fa-certificate text-[10px] text-indigo-500"></i>
                                    Terverifikasi NIB
                                </span>
                            @endif
                        </div>
                        <p class="text-xs text-slate-500 mt-1.5 flex flex-wrap items-center gap-2">
                            <span><i class="fa-solid fa-location-dot text-slate-400 mr-1"></i>{{ $tenant->city ?? 'Kabupaten Indramayu' }}</span>
                            <span class="text-slate-300">•</span>
                            <span><i class="fa-solid fa-id-card text-slate-400 mr-1"></i>NIB: <strong class="text-slate-700 font-semibold">{{ $lpk->nib ?? '-' }}</strong></span>
                        </p>
                    </div>
                </div>

                <!-- Quick Stats Badges -->
                <div class="flex items-center gap-3 shrink-0 pb-1">
                    <div class="px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200/80 text-center min-w-[95px] shadow-2xs">
                        <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Total Kursus</span>
                        <div class="font-outfit font-extrabold text-xl text-slate-900 leading-tight mt-0.5">
                            {{ $totalCourses }}
                        </div>
                    </div>
                    <div class="px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200/80 text-center min-w-[95px] shadow-2xs">
                        <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Pendaftar</span>
                        <div class="font-outfit font-extrabold text-xl text-indigo-600 leading-tight mt-0.5">
                            {{ $totalBookings }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ═══════════════════════════════════════
         MAIN 2-COLUMN GRID
    ═══════════════════════════════════════ -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- ──────────────────────────────────────────
             LEFT COLUMN (1 Col): ADMIN & LEGALITAS
        ────────────────────────────────────────── -->
        <div class="space-y-6">

            <!-- Card: Penanggung Jawab Akun -->
            <div class="bg-white rounded-2xl border border-slate-200/90 shadow-2xs p-5">
                <div class="flex items-center justify-between pb-3 mb-4 border-b border-slate-100">
                    <h3 class="font-outfit font-bold text-sm text-slate-900 flex items-center gap-2">
                        <i class="fa-solid fa-user-tie text-indigo-600 text-xs"></i>
                        <span>Penanggung Jawab LPK</span>
                    </h3>
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-md bg-indigo-50 text-indigo-700">
                        Admin Utama
                    </span>
                </div>

                <div class="flex items-center gap-3 mb-4">
                    <div class="w-12 h-12 rounded-xl bg-slate-100 text-slate-700 font-outfit font-bold text-lg flex items-center justify-center border border-slate-200">
                        {{ strtoupper(substr($user->name ?? 'A', 0, 1)) }}
                    </div>
                    <div>
                        <div class="font-semibold text-sm text-slate-900">{{ $user->name ?? '-' }}</div>
                        <div class="text-xs text-slate-500">Administrator Mitra</div>
                    </div>
                </div>

                <div class="space-y-3 pt-2 border-t border-slate-100 text-xs">
                    <div>
                        <span class="text-slate-400 font-medium block mb-0.5">Email Akun:</span>
                        <a href="mailto:{{ $user->email ?? '' }}" class="text-slate-800 font-semibold hover:text-indigo-600 transition flex items-center gap-1.5 break-all">
                            <i class="fa-regular fa-envelope text-slate-400 text-xs"></i>
                            <span>{{ $user->email ?? '-' }}</span>
                        </a>
                    </div>
                    <div>
                        <span class="text-slate-400 font-medium block mb-0.5">WhatsApp / No. Telepon:</span>
                        @if($user && !empty($user->phone))
                            @php
                                $waClean = preg_replace('/[^0-9]/', '', $user->phone);
                                if (str_starts_with($waClean, '0')) {
                                    $waClean = '62' . substr($waClean, 1);
                                }
                            @endphp
                            <a href="https://wa.me/{{ $waClean }}" target="_blank" 
                               class="text-emerald-700 font-semibold hover:underline flex items-center gap-1.5">
                                <i class="fa-brands fa-whatsapp text-emerald-500 text-sm"></i>
                                <span>{{ $user->phone }}</span>
                            </a>
                        @else
                            <span class="text-slate-400">-</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Card: Legalitas & Kontak Lembaga -->
            <div class="bg-white rounded-2xl border border-slate-200/90 shadow-2xs p-5">
                <div class="pb-3 mb-4 border-b border-slate-100">
                    <h3 class="font-outfit font-bold text-sm text-slate-900 flex items-center gap-2">
                        <i class="fa-solid fa-building text-indigo-600 text-xs"></i>
                        <span>Legalitas & Kontak Lembaga</span>
                    </h3>
                </div>

                <div class="space-y-3.5 text-xs">
                    <div>
                        <span class="text-slate-400 font-medium block mb-0.5">Nomor Induk Berusaha (NIB):</span>
                        <span class="font-mono font-semibold text-slate-800 bg-slate-50 px-2 py-1 rounded border border-slate-200/70 inline-block">
                            {{ $lpk->nib ?? '-' }}
                        </span>
                    </div>

                    @if(!empty($lpk->legal_name))
                        <div>
                            <span class="text-slate-400 font-medium block mb-0.5">Nama Legalitas Hukum:</span>
                            <span class="font-semibold text-slate-800">{{ $lpk->legal_name }}</span>
                        </div>
                    @endif

                    <div>
                        <span class="text-slate-400 font-medium block mb-0.5">Wilayah Operasional:</span>
                        <span class="font-semibold text-slate-800">{{ $tenant->city ?? 'Kabupaten Indramayu' }}</span>
                    </div>

                    <div>
                        <span class="text-slate-400 font-medium block mb-0.5">Telepon Lembaga:</span>
                        <span class="font-semibold text-slate-800">{{ $tenant->phone ?? $lpk->phone ?? '-' }}</span>
                    </div>

                    <div>
                        <span class="text-slate-400 font-medium block mb-0.5">Email Resmi Lembaga:</span>
                        <span class="font-semibold text-slate-800 break-all">{{ $tenant->email ?? '-' }}</span>
                    </div>

                    <div>
                        <span class="text-slate-400 font-medium block mb-0.5">Alamat Lengkap:</span>
                        <p class="text-slate-600 leading-relaxed bg-slate-50 p-2.5 rounded-xl border border-slate-200/60 mt-1">
                            {{ $lpk->address ?? $tenant->address ?? 'Alamat belum diisi lengkap.' }}
                        </p>
                    </div>
                </div>
            </div>

        </div>

        <!-- ──────────────────────────────────────────
             RIGHT COLUMN (2 Cols): DETAIL PROFIL & KURSUSE
        ────────────────────────────────────────── -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Card: Profil & Deskripsi Lembaga -->
            <div class="bg-white rounded-2xl border border-slate-200/90 shadow-2xs p-6">
                <div class="pb-3 mb-4 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="font-outfit font-bold text-base text-slate-900 flex items-center gap-2">
                        <i class="fa-solid fa-circle-info text-indigo-600 text-sm"></i>
                        <span>Profil & Deskripsi Lembaga</span>
                    </h3>
                </div>

                <div class="prose prose-sm max-w-none text-slate-600 leading-relaxed text-sm">
                    @if(!empty($lpk->description))
                        <p class="whitespace-pre-line">{{ $lpk->description }}</p>
                    @else
                        <p class="text-slate-400 italic">Belum ada deskripsi profil lembaga yang dimasukkan.</p>
                    @endif
                </div>

                <!-- Fasilitas jika ada -->
                @if(count($facilities) > 0)
                    <div class="mt-5 pt-4 border-t border-slate-100">
                        <span class="text-xs font-bold text-slate-700 block mb-2.5">Fasilitas Lembaga:</span>
                        <div class="flex flex-wrap gap-2">
                            @foreach($facilities as $item)
                                <span class="px-2.5 py-1 rounded-lg bg-slate-100 text-slate-700 text-xs font-medium flex items-center gap-1.5 border border-slate-200/60">
                                    <i class="fa-solid fa-check text-indigo-500 text-[10px]"></i>
                                    {{ trim($item) }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Card: Statistik Sesi Kursus & Kapasitas Room -->
            <div class="bg-white rounded-2xl border border-slate-200/90 shadow-2xs p-6">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-4 mb-5 border-b border-slate-100">
                    <div>
                        <h3 class="font-outfit font-bold text-base text-slate-900 flex items-center gap-2">
                            <i class="fa-solid fa-graduation-cap text-indigo-600 text-sm"></i>
                            <span>Statistik Program Kursus & Kapasitas Sesi</span>
                        </h3>
                        <p class="text-xs text-slate-500 mt-0.5">Monitoring kuota peserta dan okupansi pendaftar per room pelatihan.</p>
                    </div>
                    <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-slate-100 text-slate-700 w-fit">
                        {{ $totalCourses }} Program Terdaftar
                    </span>
                </div>

                @if($lpk && $lpk->courses && count($lpk->courses) > 0)
                    <div class="space-y-4">
                        @foreach($lpk->courses as $course)
                            @php
                                $max = $course->max_participants > 0 ? $course->max_participants : 1;
                                $count = $course->bookings_count ?? 0;
                                $percentage = min(100, round(($count / $max) * 100));

                                $statusColor = 'emerald';
                                $statusText = 'Tersedia';
                                $statusBg = 'bg-emerald-50 text-emerald-700 border-emerald-200';
                                $barBg = 'bg-emerald-500';

                                if($percentage >= 100) {
                                    $statusColor = 'rose';
                                    $statusText = 'Penuh';
                                    $statusBg = 'bg-rose-50 text-rose-700 border-rose-200';
                                    $barBg = 'bg-rose-500';
                                } elseif($percentage >= 80) {
                                    $statusColor = 'amber';
                                    $statusText = 'Hampir Penuh';
                                    $statusBg = 'bg-amber-50 text-amber-700 border-amber-200';
                                    $barBg = 'bg-amber-500';
                                }
                            @endphp

                            <div class="p-4 rounded-xl border border-slate-200/80 bg-slate-50/60 hover:bg-slate-50 transition">
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-3">
                                    <div>
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <h4 class="font-outfit font-bold text-sm text-slate-900">
                                                {{ $course->title }}
                                            </h4>
                                            @if($course->category)
                                                <span class="text-[10px] font-semibold px-2 py-0.5 rounded-md bg-white border border-slate-200 text-slate-600">
                                                    {{ $course->category->name }}
                                                </span>
                                            @endif
                                        </div>
                                        <div class="flex items-center gap-3 text-xs text-slate-500 mt-1">
                                            <span>
                                                <i class="fa-solid fa-users text-slate-400 mr-1 text-[11px]"></i>
                                                Pendaftar: <strong class="text-slate-800">{{ $count }}</strong> / {{ $course->max_participants ?? '∞' }} Peserta
                                            </span>
                                            <span class="text-slate-300">•</span>
                                            <span>
                                                <i class="fa-solid fa-tag text-slate-400 mr-1 text-[11px]"></i>
                                                Rp {{ number_format($course->price, 0, ',', '.') }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-2 self-start sm:self-auto">
                                        <span class="px-2.5 py-1 rounded-full text-[11px] font-bold border {{ $statusBg }}">
                                            {{ $statusText }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Progress Bar -->
                                <div class="space-y-1">
                                    <div class="w-full bg-slate-200/80 rounded-full h-2 overflow-hidden">
                                        <div class="{{ $barBg }} h-full rounded-full transition-all duration-500" 
                                             style="width: {{ $percentage }}%"></div>
                                    </div>
                                    <div class="flex justify-between items-center text-[10px] text-slate-400 font-medium">
                                        <span>Okupansi Kelas</span>
                                        <span>{{ $percentage }}%</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-8 rounded-xl border border-dashed border-slate-200 text-center bg-slate-50/50">
                        <div class="w-12 h-12 rounded-xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-2 text-base">
                            <i class="fa-solid fa-graduation-cap"></i>
                        </div>
                        <h4 class="font-outfit font-semibold text-sm text-slate-700">Belum Ada Program Kursus</h4>
                        <p class="text-xs text-slate-500 mt-0.5 max-w-sm mx-auto">
                            Lembaga Pelatihan Kerja ini belum menerbitkan program pelatihan atau kelas di platform.
                        </p>
                    </div>
                @endif
            </div>

        </div>

    </div>

</div>
@endsection
