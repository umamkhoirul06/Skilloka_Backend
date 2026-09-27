@extends('layouts.admin')

@section('title', 'Rekap Kehadiran - ' . $course->title)

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.attendance.index') }}" class="w-9 h-9 rounded-xl bg-white border border-gray-200 flex items-center justify-center text-gray-600 hover:bg-gray-50 transition">
                <i class="fa-solid fa-arrow-left text-xs"></i>
            </a>
            <div>
                <h1 class="text-xl font-bold font-outfit text-gray-900">Rekap Persentase Kehadiran</h1>
                <p class="text-xs text-gray-500">Kursus: <strong>{{ $course->title }}</strong></p>
            </div>
        </div>
        <a href="{{ route('admin.attendance.session', ['course_id' => $course->id]) }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-semibold shadow transition">
            + Input Sesi Baru
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-gray-600">
                <thead class="bg-gray-50 text-gray-700 font-semibold border-b border-gray-200 uppercase tracking-wider text-[11px]">
                    <tr>
                        <th class="py-3.5 px-6">Nama Siswa</th>
                        <th class="py-3.5 px-6 text-center">Total Sesi Diikuti</th>
                        <th class="py-3.5 px-6 text-center">Hadir</th>
                        <th class="py-3.5 px-6 text-center">Sakit</th>
                        <th class="py-3.5 px-6 text-center">Izin</th>
                        <th class="py-3.5 px-6 text-center">Alpa</th>
                        <th class="py-3.5 px-6 text-center">Persentase Kehadiran</th>
                        <th class="py-3.5 px-6 text-right">Kelayakan Sertifikat</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($recap as $item)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="py-3.5 px-6">
                                <div class="font-bold text-gray-900 text-sm">{{ $item['user']->name ?? 'Siswa' }}</div>
                                <div class="text-[10px] text-gray-400 font-mono">{{ $item['user']->email ?? '-' }}</div>
                            </td>
                            <td class="py-3.5 px-6 text-center font-bold text-gray-800">{{ $item['total'] }}</td>
                            <td class="py-3.5 px-6 text-center font-semibold text-emerald-600">{{ $item['present'] }}</td>
                            <td class="py-3.5 px-6 text-center font-semibold text-amber-600">{{ $item['sick'] }}</td>
                            <td class="py-3.5 px-6 text-center font-semibold text-blue-600">{{ $item['excused'] }}</td>
                            <td class="py-3.5 px-6 text-center font-semibold text-red-600">{{ $item['absent'] }}</td>
                            <td class="py-3.5 px-6 text-center">
                                <div class="inline-flex items-center gap-2">
                                    <div class="w-16 bg-gray-200 rounded-full h-2 overflow-hidden">
                                        <div class="bg-emerald-500 h-2 rounded-full" style="width: {{ $item['percentage'] }}%"></div>
                                    </div>
                                    <span class="font-bold text-gray-800 text-xs">{{ $item['percentage'] }}%</span>
                                </div>
                            </td>
                            <td class="py-3.5 px-6 text-right">
                                @if($item['percentage'] >= 75)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <i class="fa-solid fa-check"></i> Memenuhi Syarat
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                        <i class="fa-solid fa-triangle-exclamation"></i> Belum Cukup
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-12 text-center text-gray-400">Belum ada data siswa pada kursus ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
