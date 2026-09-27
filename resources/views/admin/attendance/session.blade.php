@extends('layouts.admin')

@section('title', 'Input Presensi Pertemuan')

@section('content')
<div class="space-y-6" x-data="{
    setAll(status) {
        document.querySelectorAll('input[type=radio]').forEach(el => {
            if (el.value === status) el.checked = true;
        });
    }
}">
    <!-- Header -->
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.attendance.index') }}" class="w-9 h-9 rounded-xl bg-white border border-gray-200 flex items-center justify-center text-gray-600 hover:bg-gray-50 transition">
            <i class="fa-solid fa-arrow-left text-xs"></i>
        </a>
        <div>
            <h1 class="text-xl font-bold font-outfit text-gray-900">Form Presensi Kelas</h1>
            <p class="text-xs text-gray-500">Pilih sesi pertemuan, catat kehadiran peserta, dan simpan data presensi.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-medium">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 rounded-xl bg-red-50 border border-red-200 text-red-800 text-xs">
            {{ $errors->first() }}
        </div>
    @endif

    <!-- Selector Bar -->
    <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm">
        <form action="{{ route('admin.attendance.session') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-4 gap-4 items-end">
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1.5">Pilih Kursus Pelatihan</label>
                <select name="course_id" class="w-full px-3 py-2 rounded-xl border border-gray-200 text-xs text-gray-800 focus:outline-none focus:border-indigo-500 bg-white">
                    @foreach($courses as $c)
                        <option value="{{ $c->id }}" {{ ($course && $course->id == $c->id) ? 'selected' : '' }}>
                            {{ $c->title }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1.5">Tanggal Sesi</label>
                <input type="date" name="session_date" value="{{ $sessionDate }}" class="w-full px-3 py-2 rounded-xl border border-gray-200 text-xs text-gray-800 focus:outline-none focus:border-indigo-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1.5">Nama Sesi / Pertemuan</label>
                <input type="text" name="session_name" value="{{ $sessionName }}" placeholder="Contoh: Pertemuan 1" class="w-full px-3 py-2 rounded-xl border border-gray-200 text-xs text-gray-800 focus:outline-none focus:border-indigo-500">
            </div>

            <div>
                <button type="submit" class="w-full py-2 bg-gray-900 hover:bg-gray-800 text-white rounded-xl text-xs font-semibold shadow transition">
                    Muat Daftar Siswa
                </button>
            </div>
        </form>
    </div>

    <!-- Attendance Form Table -->
    @if($course && count($students) > 0)
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-gray-50/50">
                <div>
                    <h3 class="font-outfit font-bold text-sm text-gray-900">{{ $course->title }}</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Sesi: <strong>{{ $sessionName }}</strong> &bull; Tanggal: <strong>{{ \Carbon\Carbon::parse($sessionDate)->translatedFormat('d F Y') }}</strong> ({{ count($students) }} Siswa Terdaftar)</p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-xs text-gray-500 mr-1">Tandai Cepat:</span>
                    <button type="button" @click="setAll('present')" class="px-2.5 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-lg text-xs font-medium hover:bg-emerald-100">Semua Hadir</button>
                    <button type="button" @click="setAll('sick')" class="px-2.5 py-1 bg-amber-50 text-amber-700 border border-amber-200 rounded-lg text-xs font-medium hover:bg-amber-100">Semua Sakit</button>
                </div>
            </div>

            <form action="{{ route('admin.attendance.store') }}" method="POST">
                @csrf
                <input type="hidden" name="course_id" value="{{ $course->id }}">
                <input type="hidden" name="session_date" value="{{ $sessionDate }}">
                <input type="hidden" name="session_name" value="{{ $sessionName }}">
                <input type="hidden" name="schedule_id" value="{{ $scheduleId }}">

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-gray-600">
                        <thead class="bg-gray-50 text-gray-700 font-semibold border-b border-gray-200 uppercase tracking-wider text-[11px]">
                            <tr>
                                <th class="py-3 px-6">No</th>
                                <th class="py-3 px-6">Nama Siswa</th>
                                <th class="py-3 px-6">Kontak / Email</th>
                                <th class="py-3 px-6 text-center">Status Presensi Kehadiran</th>
                                <th class="py-3 px-6">Catatan Sesi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($students as $idx => $st)
                                @php $bId = $st['booking']->id; @endphp
                                <tr class="hover:bg-gray-50/50 transition">
                                    <td class="py-3.5 px-6 font-mono text-gray-400">{{ $idx + 1 }}</td>
                                    <td class="py-3.5 px-6">
                                        <div class="font-bold text-gray-900 text-sm">{{ $st['user']->name ?? 'Siswa' }}</div>
                                        <div class="text-[10px] text-gray-400 font-mono">Booking #{{ substr($bId, 0, 8) }}</div>
                                    </td>
                                    <td class="py-3.5 px-6 text-gray-600">
                                        <div>{{ $st['user']->email ?? '-' }}</div>
                                        <div class="text-gray-400 text-[11px]">{{ $st['user']->phone ?? '' }}</div>
                                    </td>
                                    <td class="py-3.5 px-6">
                                        <div class="flex items-center justify-center gap-3">
                                            <label class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl border border-gray-200 cursor-pointer hover:bg-emerald-50 text-xs">
                                                <input type="radio" name="attendance[{{ $bId }}][status]" value="present" {{ $st['status'] == 'present' ? 'checked' : '' }} class="text-emerald-600">
                                                <span class="font-semibold text-emerald-700">Hadir</span>
                                            </label>
                                            <label class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl border border-gray-200 cursor-pointer hover:bg-amber-50 text-xs">
                                                <input type="radio" name="attendance[{{ $bId }}][status]" value="sick" {{ $st['status'] == 'sick' ? 'checked' : '' }} class="text-amber-600">
                                                <span class="font-semibold text-amber-700">Sakit</span>
                                            </label>
                                            <label class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl border border-gray-200 cursor-pointer hover:bg-blue-50 text-xs">
                                                <input type="radio" name="attendance[{{ $bId }}][status]" value="excused" {{ $st['status'] == 'excused' ? 'checked' : '' }} class="text-blue-600">
                                                <span class="font-semibold text-blue-700">Izin</span>
                                            </label>
                                            <label class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl border border-gray-200 cursor-pointer hover:bg-red-50 text-xs">
                                                <input type="radio" name="attendance[{{ $bId }}][status]" value="absent" {{ $st['status'] == 'absent' ? 'checked' : '' }} class="text-red-600">
                                                <span class="font-semibold text-red-700">Alpa</span>
                                            </label>
                                        </div>
                                    </td>
                                    <td class="py-3.5 px-6">
                                        <input type="text" name="attendance[{{ $bId }}][notes]" value="{{ $st['notes'] }}" placeholder="Catatan opsional..." class="w-full px-3 py-1.5 rounded-lg border border-gray-200 text-xs focus:outline-none focus:border-indigo-500">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="p-5 border-t border-gray-100 flex items-center justify-between bg-gray-50/50">
                    <span class="text-xs text-gray-500">Pastikan status kehadiran telah diverifikasi sebelum disimpan.</span>
                    <button type="submit" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-semibold shadow-md transition flex items-center gap-2">
                        <i class="fa-solid fa-floppy-disk text-xs"></i>
                        <span>Simpan Presensi Sesi</span>
                    </button>
                </div>
            </form>
        </div>
    @elseif($course)
        <div class="p-12 text-center bg-white rounded-2xl border border-gray-200 text-gray-400 text-xs">
            <i class="fa-solid fa-user-xmark text-3xl mb-2 text-gray-300"></i>
            <p>Belum ada siswa dengan status pendaftaran aktif/lunas pada kursus ini.</p>
        </div>
    @endif
</div>
@endsection
