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
        <a href="{{ route('admin.attendance.session') }}" class="shiny-btn shiny-btn-brand px-4 py-2.5 text-white rounded-xl text-xs font-semibold shadow-md shadow-brand-500/20 flex items-center gap-2">
            <i class="fa-solid fa-clipboard-check text-xs"></i>
            <span>Input Presensi Sesi Ini</span>
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
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex flex-col justify-between hover:shadow-md transition">
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-brand-50 text-brand-700 border border-brand-200">
                            {{ $c->level }}
                        </span>
                        <span class="text-xs text-slate-500 font-medium">
                            <i class="fa-solid fa-users text-slate-400 mr-1"></i> {{ $c->bookings_count }} Siswa Terdaftar
                        </span>
                    </div>
                    <h3 class="font-outfit font-bold text-base text-slate-900 line-clamp-1 mb-1">{{ $c->title }}</h3>
                    <p class="text-xs text-slate-400">Total durasi: {{ $c->duration_hours }} Jam</p>
                </div>

                <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between gap-2">
                    <a href="{{ route('admin.attendance.session', ['course_id' => $c->id]) }}" class="flex-1 text-center py-2 bg-brand-600 hover:bg-brand-700 text-white rounded-xl text-xs font-semibold shadow-sm shadow-brand-500/20 transition">
                        Isi Absensi
                    </a>
                    <a href="{{ route('admin.attendance.recap', $c->id) }}" class="flex-1 text-center py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-semibold transition">
                        Rekap Kehadiran
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-3 p-12 text-center bg-white rounded-2xl border border-gray-200 text-gray-400 text-xs">
                Belum ada kursus aktif yang memiliki siswa terdaftar.
            </div>
        @endforelse
    </div>

    <!-- Riwayat Catatan Kehadiran Terbaru -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-outfit font-bold text-sm text-gray-900">Catatan Kehadiran Terbaru</h3>
            <span class="text-xs text-gray-400">15 aktivitas terakhir</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-gray-600">
                <thead class="bg-gray-50/80 text-gray-700 font-semibold border-b border-gray-200 uppercase tracking-wider text-[11px]">
                    <tr>
                        <th class="py-3 px-6">Tanggal Sesi</th>
                        <th class="py-3 px-6">Sesi Pertemuan</th>
                        <th class="py-3 px-6">Nama Siswa</th>
                        <th class="py-3 px-6">Kursus</th>
                        <th class="py-3 px-6">Status Presensi</th>
                        <th class="py-3 px-6">Catatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($recentAttendances as $item)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="py-3 px-6 font-mono text-gray-500">{{ \Carbon\Carbon::parse($item->session_date)->translatedFormat('d M Y') }}</td>
                            <td class="py-3 px-6 font-bold text-gray-800">{{ $item->session_name }}</td>
                            <td class="py-3 px-6 font-medium text-gray-900">{{ $item->user->name ?? 'Siswa' }}</td>
                            <td class="py-3 px-6 text-gray-600">{{ $item->course->title ?? '-' }}</td>
                            <td class="py-3 px-6">
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
                            <td colspan="6" class="py-10 text-center text-gray-400">Belum ada riwayat absensi yang tercatat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
