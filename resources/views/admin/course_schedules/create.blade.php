@extends('layouts.admin')

@section('title', 'Tambah Jadwal Kelas')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    {{-- Header Navigation & Title --}}
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.course-schedules.index') }}" 
           class="w-9 h-9 rounded-xl bg-white border border-gray-200 flex items-center justify-center text-gray-600 hover:bg-gray-50 transition shadow-sm">
            <i class="fa-solid fa-arrow-left text-xs"></i>
        </a>
        <div>
            <h1 class="text-xl font-bold font-outfit text-gray-900">Tambah Jadwal Kelas Pelatihan</h1>
            <p class="text-xs text-gray-500">Atur periode kelas, hari belajar, jam pertemuan, dan kapasitas kuota peserta.</p>
        </div>
    </div>

    {{-- Error Banner --}}
    @if($errors->any())
        <div class="p-4 rounded-xl bg-red-50 border border-red-200 text-red-800 text-xs">
            <div class="font-semibold mb-1 flex items-center gap-2">
                <i class="fa-solid fa-circle-exclamation text-red-600"></i>
                <span>Terdapat kesalahan pada input:</span>
            </div>
            <ul class="list-disc pl-5 space-y-1">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form Card --}}
    <div class="bg-white p-6 sm:p-8 rounded-2xl border border-gray-200 shadow-sm">
        <form action="{{ route('admin.course-schedules.store') }}" method="POST" class="space-y-6">
            @csrf

            {{-- 1. PILIH KURSUS --}}
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                    Pilih Program Kursus <span class="text-red-500">*</span>
                </label>
                <select name="course_id" 
                        required 
                        class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-xs text-gray-800 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 bg-white transition">
                    <option value="">-- Pilih Program Kursus --</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
                            {{ $course->title }} ({{ $course->category->name ?? 'Umum' }})
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- 2. PERIODE TANGGAL --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                        Tanggal Mulai Pelatihan <span class="text-red-500">*</span>
                    </label>
                    <input type="date" 
                           name="start_date" 
                           value="{{ old('start_date') }}" 
                           required 
                           class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-xs text-gray-800 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 bg-white transition">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                        Tanggal Selesai Pelatihan <span class="text-red-500">*</span>
                    </label>
                    <input type="date" 
                           name="end_date" 
                           value="{{ old('end_date') }}" 
                           required 
                           class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-xs text-gray-800 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 bg-white transition">
                </div>
            </div>

            {{-- 3. HARI BELAJAR --}}
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                    Hari Pertemuan Belajar <span class="text-red-500">*</span>
                </label>
                <select name="days_of_week" 
                        required 
                        class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-xs text-gray-800 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 bg-white transition">
                    <option value="">-- Pilih Pola Hari Belajar --</option>
                    <option value="Senin - Jumat" {{ old('days_of_week') == 'Senin - Jumat' ? 'selected' : '' }}>Senin - Jumat (Reguler Penuh)</option>
                    <option value="Senin, Rabu, Jumat" {{ old('days_of_week') == 'Senin, Rabu, Jumat' ? 'selected' : '' }}>Senin, Rabu, Jumat (Hari Ganjil)</option>
                    <option value="Selasa, Kamis, Sabtu" {{ old('days_of_week') == 'Selasa, Kamis, Sabtu' ? 'selected' : '' }}>Selasa, Kamis, Sabtu (Hari Genap)</option>
                    <option value="Sabtu - Minggu" {{ old('days_of_week') == 'Sabtu - Minggu' ? 'selected' : '' }}>Sabtu & Minggu (Kelas Weekend / Karyawan)</option>
                    <option value="Setiap Hari" {{ old('days_of_week') == 'Setiap Hari' ? 'selected' : '' }}>Setiap Hari (Program Intensif Camp)</option>
                    <option value="Sesuai Kesepakatan" {{ old('days_of_week') == 'Sesuai Kesepakatan' ? 'selected' : '' }}>Sesuai Kesepakatan (Fleksibel)</option>
                </select>
            </div>

            {{-- 4. JAM PERTEMUAN --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                        Jam Mulai Pertemuan <span class="text-red-500">*</span>
                    </label>
                    <input type="time" 
                           name="daily_start" 
                           value="{{ old('daily_start', '08:00') }}" 
                           required 
                           class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-xs text-gray-800 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 bg-white transition">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                        Jam Selesai Pertemuan <span class="text-red-500">*</span>
                    </label>
                    <input type="time" 
                           name="daily_end" 
                           value="{{ old('daily_end', '15:00') }}" 
                           required 
                           class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-xs text-gray-800 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 bg-white transition">
                </div>
            </div>

            {{-- 5. KUOTA PESERTA --}}
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                    Kapasitas Maksimal Siswa (Kuota Batch) <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <input type="number" 
                           name="max_capacity" 
                           value="{{ old('max_capacity', 20) }}" 
                           required 
                           min="1"
                           placeholder="Contoh: 20" 
                           class="w-full pr-16 pl-3.5 py-2.5 rounded-xl border border-gray-200 text-xs text-gray-800 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 bg-white transition">
                    <span class="absolute right-3.5 top-2.5 text-xs text-gray-400 font-medium">Peserta</span>
                </div>
                <p class="text-[11px] text-gray-400 mt-1">Sistem akan otomatis menutup pendaftaran jika kuota batch telah penuh.</p>
            </div>

            {{-- FOOTER ACTION BUTTONS --}}
            <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-100">
                <a href="{{ route('admin.course-schedules.index') }}" 
                   class="px-5 py-2.5 rounded-xl border border-gray-200 text-xs font-semibold text-gray-600 hover:bg-gray-50 transition">
                    Batal
                </a>
                <button type="submit" 
                        class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold shadow-sm shadow-indigo-600/20 transition flex items-center gap-2">
                    <i class="fa-solid fa-calendar-check text-xs"></i>
                    <span>Simpan Jadwal Kelas</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection