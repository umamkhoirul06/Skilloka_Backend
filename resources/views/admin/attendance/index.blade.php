@extends('layouts.admin')

@section('title', 'Presensi & Absensi Siswa')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold font-outfit text-gray-900">Presensi & Kehadiran Siswa</h1>
            <p class="text-xs text-gray-500 mt-1">Catat kehadiran per sesi pertemuan dan pantau persentase kehadiran peserta untuk syarat penerbitan sertifikat.</p>
        </div>
        <a href="{{ route('admin.attendance.session', $selectedCourseId ? ['course_id' => $selectedCourseId] : []) }}" class="bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white px-4 py-2.5 rounded-xl text-xs font-semibold shadow-md shadow-blue-500/25 flex items-center gap-2 transition cursor-pointer">
            <i class="fa-solid fa-clipboard-check text-xs"></i>
            <span>{{ $activeCourse ? 'Input Presensi ' . Str::limit($activeCourse->title, 20) : 'Input Presensi Sesi Ini' }}</span>
        </a>
    </div>

    @if(session('success'))
        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-medium">
            {{ session('success') }}
        </div>
    @endif

    <!-- Kursus & Status Absensi Card Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        @forelse($courses as $c)
            @php $isSelected = ($selectedCourseId == $c->id); @endphp
            <div @class([
                'bg-white p-5 rounded-2xl border shadow-sm flex flex-col justify-between transition hover:shadow-md relative',
                'border-indigo-500 ring-2 ring-indigo-500/20 bg-indigo-50/20' => $isSelected,
                'border-slate-200' => !$isSelected,
            ])>
                @if($isSelected)
                    <div class="absolute -top-2.5 right-4 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-indigo-600 text-white shadow-sm flex items-center gap-1">
                        <i class="fa-solid fa-filter text-[9px]"></i> Filter Aktif
                    </div>
                @endif
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-brand-50 text-brand-700 border border-brand-200">
                            {{ $c->level }}
                        </span>
                        <span class="text-xs text-slate-500 font-medium">
                            <i class="fa-solid fa-users text-slate-400 mr-1"></i> {{ $c->bookings_count }} Siswa
                        </span>
                    </div>
                    <a href="{{ route('admin.attendance.index', ['course_id' => $c->id]) }}" class="block group">
                        <h3 class="font-outfit font-bold text-base text-slate-900 group-hover:text-indigo-600 transition line-clamp-1 mb-1">{{ $c->title }}</h3>
                    </a>
                    <div class="flex items-center gap-3 text-xs text-slate-400">
                        <span><i class="fa-regular fa-clock mr-1"></i>{{ $c->duration_hours }} Jam</span>
                        <span>•</span>
                        <span><i class="fa-solid fa-clipboard-user mr-1 text-slate-400"></i>{{ $c->attendances_count }} Presensi</span>
                    </div>
                </div>

                <div class="mt-5 pt-4 border-t border-slate-100 flex items-center justify-between gap-2">
                    <a href="{{ route('admin.attendance.index', ['course_id' => $c->id]) }}" 
                       class="flex-1 text-center py-2 {{ $isSelected ? 'bg-indigo-600 text-white' : 'bg-indigo-50 hover:bg-indigo-100 text-indigo-700' }} rounded-xl text-xs font-semibold transition">
                        {{ $isSelected ? '✓ Terpilih' : 'Filter Data' }}
                    </a>
                    <a href="{{ route('admin.attendance.session', ['course_id' => $c->id]) }}" class="flex-1 text-center py-2 bg-brand-600 hover:bg-brand-700 text-white rounded-xl text-xs font-semibold shadow-sm transition">
                        Isi Absensi
                    </a>
                    <a href="{{ route('admin.attendance.recap', $c->id) }}" class="p-2 text-slate-500 hover:text-slate-800 bg-slate-100 hover:bg-slate-200 rounded-xl text-xs transition" title="Rekap Kehadiran">
                        <i class="fa-solid fa-chart-pie"></i>
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-3 p-12 text-center bg-white rounded-2xl border border-gray-200 text-gray-400 text-xs">
                Belum ada kursus aktif yang memiliki siswa terdaftar.
            </div>
        @endforelse
    </div>

    <!-- ═════════════════════════════════════════════════════════
         BAGIAN DATA PRESENSI TERPISAH PER KURSUS
    ═════════════════════════════════════════════════════════ -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        
        <!-- Header & Tab Navigasi Pemisah Kursus -->
        <div class="p-5 border-b border-gray-100 space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    <div class="flex items-center gap-2">
                        <h3 class="font-outfit font-bold text-base text-gray-900">
                            {{ $activeCourse ? 'Presensi: ' . $activeCourse->title : 'Catatan Kehadiran Semua Kursus' }}
                        </h3>
                        @if($activeCourse)
                            <a href="{{ route('admin.attendance.index') }}" class="text-[11px] font-semibold text-indigo-600 hover:text-indigo-800 bg-indigo-50 hover:bg-indigo-100 px-2 py-0.5 rounded-full transition flex items-center gap-1">
                                <i class="fa-solid fa-xmark text-[10px]"></i> Reset Filter
                            </a>
                        @endif
                    </div>
                    <p class="text-xs text-gray-500 mt-0.5">
                        {{ $activeCourse ? 'Menampilkan riwayat presensi khusus untuk kursus ' . $activeCourse->title : 'Pilih tab kursus di bawah untuk memisahkan data presensi per program kursus.' }}
                    </p>
                </div>

                @if($activeCourse)
                    <div class="flex items-center gap-2 self-start sm:self-auto">
                        <a href="{{ route('admin.attendance.session', ['course_id' => $activeCourse->id]) }}" class="px-3 py-1.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold shadow-sm transition flex items-center gap-1.5">
                            <i class="fa-solid fa-plus text-[10px]"></i> Input Sesi Ini
                        </a>
                        <a href="{{ route('admin.attendance.recap', $activeCourse->id) }}" class="px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition flex items-center gap-1.5">
                            <i class="fa-solid fa-chart-simple text-[10px]"></i> Rekap
                        </a>
                    </div>
                @endif
            </div>

            <!-- Tab Pills Pemisah Kursus -->
            <div class="flex items-center gap-2 overflow-x-auto pb-1 pt-1 no-scrollbar">
                {{-- Tab: Semua Kursus --}}
                <a href="{{ route('admin.attendance.index') }}" 
                   @class([
                       'px-3.5 py-2 rounded-xl text-xs font-semibold whitespace-nowrap transition flex items-center gap-2 shrink-0',
                       'bg-gray-900 text-white shadow-sm' => is_null($selectedCourseId),
                       'bg-gray-100 text-gray-600 hover:bg-gray-200' => !is_null($selectedCourseId),
                   ])>
                    <i class="fa-solid fa-layer-group text-[11px]"></i>
                    <span>Semua Kursus</span>
                    <span @class([
                        'px-1.5 py-0.5 rounded-md text-[10px] font-bold',
                        'bg-gray-800 text-gray-200' => is_null($selectedCourseId),
                        'bg-gray-200 text-gray-700' => !is_null($selectedCourseId),
                    ])>
                        {{ $courses->sum('attendances_count') }}
                    </span>
                </a>

                {{-- Tab per Kursus --}}
                @foreach($courses as $c)
                    @php $isActive = ($selectedCourseId == $c->id); @endphp
                    <a href="{{ route('admin.attendance.index', ['course_id' => $c->id]) }}"
                       @class([
                           'px-3.5 py-2 rounded-xl text-xs font-semibold whitespace-nowrap transition flex items-center gap-2 shrink-0 border',
                           'bg-indigo-600 border-indigo-600 text-white shadow-sm shadow-indigo-500/20' => $isActive,
                           'bg-white border-gray-200 text-gray-700 hover:bg-gray-50 hover:border-gray-300' => !$isActive,
                       ])>
                        <i class="fa-solid fa-graduation-cap text-[11px] {{ $isActive ? 'text-indigo-200' : 'text-gray-400' }}"></i>
                        <span>{{ $c->title }}</span>
                        <span @class([
                            'px-1.5 py-0.5 rounded-md text-[10px] font-bold',
                            'bg-indigo-700 text-indigo-100' => $isActive,
                            'bg-gray-100 text-gray-600' => !$isActive,
                        ])>
                            {{ $c->attendances_count }}
                        </span>
                    </a>
                @endforeach
            </div>

            <!-- Mini Statistik Kehadiran untuk Monitoring Cepat -->
            <div class="grid grid-cols-2 sm:grid-cols-5 gap-3 pt-2">
                <div class="p-3 rounded-xl bg-gray-50 border border-gray-100 flex items-center justify-between">
                    <div>
                        <div class="text-[10px] font-bold uppercase tracking-wider text-gray-400">Total Absensi</div>
                        <div class="text-base font-extrabold text-gray-800 mt-0.5">{{ number_format($stats['total']) }}</div>
                    </div>
                    <div class="w-7 h-7 rounded-lg bg-gray-200/60 text-gray-600 flex items-center justify-center text-xs">
                        <i class="fa-solid fa-clipboard-list"></i>
                    </div>
                </div>
                <div class="p-3 rounded-xl bg-emerald-50/70 border border-emerald-100 flex items-center justify-between">
                    <div>
                        <div class="text-[10px] font-bold uppercase tracking-wider text-emerald-600">Hadir</div>
                        <div class="text-base font-extrabold text-emerald-700 mt-0.5">{{ number_format($stats['present']) }}</div>
                    </div>
                    <div class="w-7 h-7 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs">
                        <i class="fa-solid fa-check"></i>
                    </div>
                </div>
                <div class="p-3 rounded-xl bg-amber-50/70 border border-amber-100 flex items-center justify-between">
                    <div>
                        <div class="text-[10px] font-bold uppercase tracking-wider text-amber-600">Sakit</div>
                        <div class="text-base font-extrabold text-amber-700 mt-0.5">{{ number_format($stats['sick']) }}</div>
                    </div>
                    <div class="w-7 h-7 rounded-lg bg-amber-100 text-amber-700 flex items-center justify-center text-xs">
                        <i class="fa-solid fa-briefcase-medical"></i>
                    </div>
                </div>
                <div class="p-3 rounded-xl bg-blue-50/70 border border-blue-100 flex items-center justify-between">
                    <div>
                        <div class="text-[10px] font-bold uppercase tracking-wider text-blue-600">Izin</div>
                        <div class="text-base font-extrabold text-blue-700 mt-0.5">{{ number_format($stats['excused']) }}</div>
                    </div>
                    <div class="w-7 h-7 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center text-xs">
                        <i class="fa-solid fa-envelope-open-text"></i>
                    </div>
                </div>
                <div class="col-span-2 sm:col-span-1 p-3 rounded-xl bg-rose-50/70 border border-rose-100 flex items-center justify-between">
                    <div>
                        <div class="text-[10px] font-bold uppercase tracking-wider text-rose-600">Alpa</div>
                        <div class="text-base font-extrabold text-rose-700 mt-0.5">{{ number_format($stats['absent']) }}</div>
                    </div>
                    <div class="w-7 h-7 rounded-lg bg-rose-100 text-rose-700 flex items-center justify-center text-xs">
                        <i class="fa-solid fa-xmark"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel Catatan Presensi -->
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-gray-600">
                <thead class="bg-gray-50/80 text-gray-700 font-semibold border-b border-gray-200 uppercase tracking-wider text-[11px]">
                    <tr>
                        <th class="py-3 px-6">Tanggal Sesi</th>
                        <th class="py-3 px-6">Sesi Pertemuan</th>
                        <th class="py-3 px-6">Nama Siswa</th>
                        @if(is_null($selectedCourseId))
                            <th class="py-3 px-6">Program Kursus</th>
                        @endif
                        <th class="py-3 px-6">Status Presensi</th>
                        <th class="py-3 px-6">Catatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($recentAttendances as $item)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="py-3 px-6 font-mono text-gray-500 whitespace-nowrap">
                                <i class="fa-regular fa-calendar text-gray-400 mr-1.5"></i>
                                {{ \Carbon\Carbon::parse($item->session_date)->translatedFormat('d M Y') }}
                            </td>
                            <td class="py-3 px-6 font-bold text-gray-800 whitespace-nowrap">
                                <span class="px-2 py-0.5 rounded-lg bg-gray-100 text-gray-700 font-mono text-[11px]">
                                    {{ $item->session_name }}
                                </span>
                            </td>
                            <td class="py-3 px-6 font-medium text-gray-900 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center text-[10px] font-bold">
                                        {{ strtoupper(substr($item->user->name ?? 'S', 0, 1)) }}
                                    </div>
                                    <span>{{ $item->user->name ?? 'Siswa' }}</span>
                                </div>
                            </td>
                            @if(is_null($selectedCourseId))
                                <td class="py-3 px-6">
                                    <a href="{{ route('admin.attendance.index', ['course_id' => $item->course_id]) }}" class="inline-flex items-center gap-1 text-gray-700 hover:text-indigo-600 font-medium transition" title="Filter hanya kursus ini">
                                        <span>{{ $item->course->title ?? '-' }}</span>
                                        <i class="fa-solid fa-arrow-up-right-from-square text-[9px] text-gray-400"></i>
                                    </a>
                                </td>
                            @endif
                            <td class="py-3 px-6 whitespace-nowrap">
                                @if($item->status === 'present')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <i class="fa-solid fa-check text-[9px]"></i> Hadir
                                    </span>
                                @elseif($item->status === 'sick')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                        <i class="fa-solid fa-briefcase-medical text-[9px]"></i> Sakit
                                    </span>
                                @elseif($item->status === 'excused')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-blue-50 text-blue-700 border border-blue-200">
                                        <i class="fa-solid fa-envelope-open-text text-[9px]"></i> Izin
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-red-50 text-red-700 border border-red-200">
                                        <i class="fa-solid fa-xmark text-[9px]"></i> Alpa
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-6 text-gray-400">{{ $item->notes ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ is_null($selectedCourseId) ? 6 : 5 }}" class="py-12 text-center text-gray-400">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-gray-400 text-sm">
                                        <i class="fa-regular fa-folder-open"></i>
                                    </div>
                                    <p class="font-medium text-gray-600">
                                        {{ $activeCourse ? 'Belum ada catatan presensi untuk kursus ' . $activeCourse->title . '.' : 'Belum ada riwayat absensi yang tercatat.' }}
                                    </p>
                                    <p class="text-[11px] text-gray-400">Silakan input sesi presensi peserta untuk mulai mencatat kehadiran.</p>
                                    <a href="{{ route('admin.attendance.session', $selectedCourseId ? ['course_id' => $selectedCourseId] : []) }}" 
                                       class="mt-2 px-3 py-1.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold shadow-sm transition">
                                        <i class="fa-solid fa-plus mr-1"></i> Input Presensi Sesi
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($recentAttendances->hasPages())
            <div class="p-4 border-t border-gray-100">
                {{ $recentAttendances->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
