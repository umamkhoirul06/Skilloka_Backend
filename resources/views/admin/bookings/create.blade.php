@extends('layouts.admin')

@section('title', 'Tambah Booking Peserta')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.bookings.index') }}" class="w-9 h-9 rounded-xl bg-white border border-gray-200 flex items-center justify-center text-gray-600 hover:bg-gray-50 transition shadow-sm">
            <i class="fa-solid fa-arrow-left text-xs"></i>
        </a>
        <div>
            <h1 class="text-xl font-bold font-outfit text-gray-900">Registrasi Booking Manual</h1>
            <p class="text-xs text-gray-500">Pilih siswa dan jadwal kelas untuk mendaftarkan peserta secara langsung ke program pelatihan.</p>
        </div>
    </div>

    <!-- Error Alert -->
    @if ($errors->any())
        <div class="p-4 rounded-xl bg-red-50 border border-red-200 text-red-800 text-xs">
            <div class="font-bold mb-1">Periksa kembali isian formulir:</div>
            <ul class="list-disc pl-4 space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if($students->isEmpty())
        <div class="p-4 rounded-xl bg-amber-50 border border-amber-200 text-amber-900 text-xs flex items-center gap-3">
            <i class="fa-solid fa-circle-exclamation text-amber-600 text-base"></i>
            <div>
                <p class="font-bold">Belum ada siswa aktif terdaftar di LPK Anda.</p>
                <p class="text-[11px] text-amber-700">Silakan tambahkan data siswa terlebih dahulu melalui menu <strong>Data Siswa</strong>.</p>
            </div>
        </div>
    @endif

    <!-- Main Card -->
    <div class="bg-white p-6 sm:p-8 rounded-2xl border border-gray-200 shadow-sm">
        <form action="{{ route('admin.bookings.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Form Section: Data Siswa & Jadwal -->
            <div class="space-y-4">
                <div class="border-b border-gray-100 pb-2">
                    <h2 class="text-xs font-bold uppercase tracking-wider text-indigo-600">Pilihan Peserta & Program Kelas</h2>
                </div>

                <!-- Select Student -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                        Pilih Siswa / Peserta <span class="text-red-500">*</span>
                    </label>
                    <select name="user_id" required class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-xs text-gray-800 focus:outline-none focus:border-indigo-500 bg-white">
                        <option value="">-- Pilih Siswa --</option>
                        @foreach($students as $student)
                            <option value="{{ $student->id }}" {{ old('user_id') == $student->id ? 'selected' : '' }}>
                                {{ $student->name }} {{ $student->email ? '('.$student->email.')' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Select Schedule -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                        Pilih Jadwal Kelas Pelatihan <span class="text-red-500">*</span>
                    </label>
                    <select name="schedule_id" required class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-xs text-gray-800 focus:outline-none focus:border-indigo-500 bg-white">
                        <option value="">-- Pilih Jadwal Kelas Aktif --</option>
                        @foreach($schedules as $schedule)
                            <option value="{{ $schedule->id }}" {{ old('schedule_id') == $schedule->id ? 'selected' : '' }}>
                                {{ $schedule->course->title }} | {{ \Carbon\Carbon::parse($schedule->start_date)->format('d M Y') }} s/d {{ \Carbon\Carbon::parse($schedule->end_date)->format('d M Y') }} ({{ substr($schedule->daily_start,0,5) }} - {{ substr($schedule->daily_end,0,5) }}) • Rp {{ number_format($schedule->course->price,0,',','.') }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Form Section: Status & Catatan -->
            <div class="space-y-4 pt-2">
                <div class="border-b border-gray-100 pb-2">
                    <h2 class="text-xs font-bold uppercase tracking-wider text-indigo-600">Status Pembayaran & Catatan</h2>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">Status Booking Awal <span class="text-red-500">*</span></label>
                        <select name="status" class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-xs text-gray-800 focus:outline-none focus:border-indigo-500 bg-white">
                            <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending (Menunggu Pembayaran)</option>
                            <option value="paid" {{ old('status') == 'paid' ? 'selected' : '' }}>Paid (Lunas Terbayar)</option>
                            <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed (Selesai Pelatihan)</option>
                            <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Cancelled (Dibatalkan)</option>
                        </select>
                    </div>

                    <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100 flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-receipt text-xs"></i>
                        </div>
                        <div class="text-[11px] text-gray-500 leading-tight">
                            Invoice pembayaran otomatis dibuatkan untuk tagihan kursus ini.
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Catatan Tambahan (Opsional)</label>
                    <textarea name="notes" rows="3" placeholder="Tuliskan catatan khusus atau referensi pembayaran jika ada..." class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-xs text-gray-800 focus:outline-none focus:border-indigo-500 resize-none">{{ old('notes') }}</textarea>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                <a href="{{ route('admin.bookings.index') }}" class="px-5 py-2.5 rounded-xl border border-gray-200 text-xs font-medium text-gray-600 hover:bg-gray-50 transition">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold shadow-sm transition">
                    Simpan Booking Peserta
                </button>
            </div>
        </form>
    </div>
</div>
@endsection