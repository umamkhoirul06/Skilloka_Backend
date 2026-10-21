@extends('layouts.admin')

@section('title', 'Jadwal Kelas')

@section('content')
<div class="w-full px-4 sm:px-6 lg:px-8 py-8 space-y-6">

    {{-- HEADER --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="font-outfit text-2xl font-black text-slate-900 tracking-tight">Jadwal Kelas & Sesi Belajar</h1>
            <p class="text-xs text-slate-500 mt-1">Atur periode tanggal, jadwal pertemuan harian, dan kuota peserta kursus LPK Anda.</p>
        </div>
        <a href="{{ route('admin.course-schedules.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow-sm shadow-indigo-500/20 transition">
            <i class="fa-solid fa-plus text-xs"></i>
            <span>Tambah Jadwal Kelas</span>
        </a>
    </div>

    {{-- SUCCESS MESSAGE --}}
    @if(session('success'))
        <div class="rounded-2xl bg-emerald-50 p-4 border border-emerald-200 flex items-center justify-between gap-3 text-emerald-900 text-xs">
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
                        <th scope="col" class="px-6 py-4 text-left text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                            Program Kursus
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                            Periode Tanggal
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                            Hari Pertemuan
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                            Jam Belajar
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                            Kapasitas Kuota
                        </th>
                        <th scope="col" class="px-6 py-4 text-right text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-100">
                    @forelse($schedules as $schedule)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-xs font-bold text-slate-900">
                                    {{ $schedule->course->title ?? '-' }}
                                </div>
                                <div class="text-[11px] text-slate-400 mt-0.5">
                                    {{ $schedule->course->category->name ?? 'Kategori Umum' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-xs font-semibold text-slate-800">
                                    {{ \Carbon\Carbon::parse($schedule->start_date)->format('d M Y') }}
                                </div>
                                <div class="text-[11px] text-slate-400 mt-0.5">
                                    s.d {{ \Carbon\Carbon::parse($schedule->end_date)->format('d M Y') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-indigo-50 text-indigo-700 border border-indigo-100">
                                    <i class="fa-regular fa-calendar text-[10px]"></i>
                                    {{ $schedule->days_of_week }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-medium bg-slate-100 text-slate-800">
                                    <i class="fa-regular fa-clock text-[10px] text-slate-500"></i>
                                    {{ substr($schedule->daily_start, 0, 5) }} - {{ substr($schedule->daily_end, 0, 5) }} WIB
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-xs font-bold text-slate-900">
                                    {{ $schedule->max_capacity }}
                                    <span class="text-slate-400 text-[11px] font-normal">Siswa</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.course-schedules.edit', $schedule->id) }}"
                                       class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 hover:bg-amber-100 border border-amber-200 flex items-center justify-center transition"
                                       title="Ubah Jadwal">
                                        <i class="fa-solid fa-pen-to-square text-xs"></i>
                                    </a>
                                    <form action="{{ route('admin.course-schedules.destroy', $schedule->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus jadwal ini?');"
                                          class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 border border-red-200 flex items-center justify-center transition"
                                                title="Hapus Jadwal">
                                            <i class="fa-solid fa-trash-can text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-slate-400">
                                    <div class="w-12 h-12 rounded-2xl bg-slate-50 flex items-center justify-center text-slate-300 mb-3 border border-slate-100">
                                        <i class="fa-regular fa-calendar-xmark text-xl"></i>
                                    </div>
                                    <p class="text-xs font-bold text-slate-700">Belum Ada Jadwal Kelas</p>
                                    <p class="text-[11px] text-slate-400 mt-1 max-w-sm">Jadwal pertemuan kelas pelatihan Anda akan muncul di sini.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection