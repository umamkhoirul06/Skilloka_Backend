@extends('layouts.admin')

@section('title', 'Terbitkan Sertifikat Siswa')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.certificates.index') }}" class="w-9 h-9 rounded-xl bg-white border border-gray-200 flex items-center justify-center text-gray-600 hover:bg-gray-50 transition">
            <i class="fa-solid fa-arrow-left text-xs"></i>
        </a>
        <div>
            <h1 class="text-xl font-bold font-outfit text-gray-900">Penerbitan Sertifikat Resmi</h1>
            <p class="text-xs text-gray-500">Buat dan berikan nomor sertifikat kompetensi resmi untuk siswa lulusan pelatihan.</p>
        </div>
    </div>

    @if($errors->any())
        <div class="p-4 rounded-xl bg-red-50 border border-red-200 text-red-800 text-xs">
            <ul class="list-disc pl-4 space-y-1">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white p-6 sm:p-8 rounded-2xl border border-gray-200 shadow-sm">
        <form action="{{ route('admin.certificates.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <!-- Student / Booking Selection -->
            @if($selectedBooking)
                <input type="hidden" name="booking_id" value="{{ $selectedBooking->id }}">
                <div class="p-4 rounded-xl bg-indigo-50/60 border border-indigo-100 flex items-center justify-between">
                    <div>
                        <span class="text-[10px] uppercase font-bold text-indigo-500 tracking-wider">Penerima Sertifikat</span>
                        <div class="font-bold text-sm text-gray-900 mt-0.5">{{ $selectedBooking->user->name ?? 'Siswa' }}</div>
                        <div class="text-xs text-gray-500">Kursus: <strong>{{ $selectedBooking->course->title ?? '-' }}</strong></div>
                    </div>
                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">
                        Siap Terbit
                    </span>
                </div>
            @else
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Pilih Peserta & Kursus <span class="text-red-500">*</span></label>
                    <select name="booking_id" required class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-xs text-gray-800 focus:outline-none focus:border-indigo-500 bg-white">
                        <option value="">-- Pilih Peserta --</option>
                        @foreach($courses as $c)
                            <optgroup label="{{ $c->title }}">
                                @foreach($c->bookings->where('certificate', null) as $b)
                                    <option value="{{ $b->id }}" {{ old('booking_id') == $b->id ? 'selected' : '' }}>
                                        {{ $b->user->name ?? 'Siswa' }} ({{ $b->user->email ?? '-' }})
                                    </option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                </div>
            @endif

            <!-- Certificate Number -->
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1.5">Nomor Registrasi Sertifikat <span class="text-red-500">*</span></label>
                <div class="relative">
                    <input type="text" name="number" value="{{ old('number', $suggestedNumber) }}" required class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-xs text-gray-800 font-mono font-bold focus:outline-none focus:border-indigo-500 bg-gray-50/50">
                </div>
                <p class="text-[11px] text-gray-400 mt-1">Nomor standar otomatis di-generate berdasarkan kode LPK dan tahun berjalan.</p>
            </div>

            <!-- Dates -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Tanggal Terbit <span class="text-red-500">*</span></label>
                    <input type="date" name="issue_date" value="{{ old('issue_date', date('Y-m-d')) }}" required class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-xs text-gray-800 focus:outline-none focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Masa Berlaku (Opsional)</label>
                    <input type="date" name="expiry_date" value="{{ old('expiry_date') }}" class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-xs text-gray-800 focus:outline-none focus:border-indigo-500">
                    <p class="text-[10px] text-gray-400 mt-0.5">Kosongkan jika berlaku seumur hidup.</p>
                </div>
            </div>

            <!-- File Upload -->
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1.5">Lampiran Dokumen Sertifikat Digital (Opsional PDF/JPG maks. 10MB)</label>
                <input type="file" name="file" class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                <p class="text-[11px] text-gray-400 mt-1">Jika tidak mengunggah file khusus, sistem akan membuatkan lembar sertifikat resmi berstandar Skilloka secara otomatis.</p>
            </div>

            <div class="pt-5 border-t border-gray-100 flex items-center justify-end gap-3">
                <a href="{{ route('admin.certificates.index') }}" class="px-5 py-2.5 rounded-xl text-xs font-semibold text-gray-600 hover:bg-gray-100 transition">Batal</a>
                <button type="submit" class="px-6 py-2.5 rounded-xl text-xs font-semibold text-white bg-emerald-600 hover:bg-emerald-700 shadow-md transition flex items-center gap-2">
                    <i class="fa-solid fa-stamp text-xs"></i>
                    <span>Terbitkan Sertifikat Resmi</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
