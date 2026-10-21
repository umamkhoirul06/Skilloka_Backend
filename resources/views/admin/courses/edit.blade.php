@extends('layouts.admin')

@section('title', 'Ubah Program Kursus')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    {{-- Header Navigation & Title --}}
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.courses.index') }}" 
           class="w-9 h-9 rounded-xl bg-white border border-gray-200 flex items-center justify-center text-gray-600 hover:bg-gray-50 transition shadow-sm">
            <i class="fa-solid fa-arrow-left text-xs"></i>
        </a>
        <div>
            <h1 class="text-xl font-bold font-outfit text-gray-900">Ubah Program Kursus</h1>
            <p class="text-xs text-gray-500">Perbarui rincian kurikulum, biaya, silabus, dan fasilitas pelatihan: <span class="font-semibold text-gray-800">{{ $course->title }}</span></p>
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

    {{-- Success Banner --}}
    @if(session('success'))
        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-circle-check text-emerald-600 text-sm"></i>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-800 font-bold text-sm">&times;</button>
        </div>
    @endif

    {{-- Form Card --}}
    <div class="bg-white p-6 sm:p-8 rounded-2xl border border-gray-200 shadow-sm">
        <form action="{{ route('admin.courses.update', $course->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- 1. INFORMASI DASAR --}}
            <div class="space-y-4">
                <div class="border-b border-gray-100 pb-2 flex items-center justify-between">
                    <h2 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                        <span class="w-6 h-6 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center text-xs font-bold">1</span>
                        Informasi Utama Pelatihan
                    </h2>
                    <label class="flex items-center gap-2 cursor-pointer text-xs font-semibold text-gray-700 bg-gray-50 px-3 py-1.5 rounded-lg border border-gray-200">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $course->is_active) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 focus:ring-0">
                        <span>Kursus Aktif (Tampil di Katalog)</span>
                    </label>
                </div>

                {{-- Title --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                        Judul Kursus / Program <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="title" 
                           value="{{ old('title', $course->title) }}" 
                           required 
                           placeholder="Contoh: Teknisi Otomotif & Servis Injeksi Motor Modern" 
                           class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-xs text-gray-800 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 bg-white transition">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    {{-- Category --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                            Kategori Kejuruan <span class="text-red-500">*</span>
                        </label>
                        <select name="category_id" 
                                required 
                                class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-xs text-gray-800 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 bg-white transition">
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id', $course->category_id) == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Level --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                            Tingkat Kesulitan / Level <span class="text-red-500">*</span>
                        </label>
                        <select name="level" 
                                required 
                                class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-xs text-gray-800 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 bg-white transition">
                            <option value="beginner" {{ old('level', $course->level) == 'beginner' ? 'selected' : '' }}>Pemula (Beginner)</option>
                            <option value="intermediate" {{ old('level', $course->level) == 'intermediate' ? 'selected' : '' }}>Menengah (Intermediate)</option>
                            <option value="advanced" {{ old('level', $course->level) == 'advanced' ? 'selected' : '' }}>Mahir (Advanced)</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    {{-- Certificate Type --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                            Jenis Sertifikat Kelulusan
                        </label>
                        <input type="text" 
                               name="cert_type" 
                               value="{{ old('cert_type', $course->cert_type) }}" 
                               placeholder="Contoh: BNSP / Disnaker / Sertifikat LPK Resmi" 
                               class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-xs text-gray-800 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 bg-white transition">
                    </div>

                    {{-- Max Participants --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                            Kapasitas Maksimal Siswa (Kuota)
                        </label>
                        <input type="number" 
                               name="max_participants" 
                               value="{{ old('max_participants', $course->max_participants) }}" 
                               min="1"
                               placeholder="Contoh: 20" 
                               class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-xs text-gray-800 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 bg-white transition">
                    </div>
                </div>
            </div>

            {{-- 2. BIAYA & DURASI --}}
            <div class="space-y-4 pt-4 border-t border-gray-100">
                <div class="border-b border-gray-100 pb-2">
                    <h2 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                        <span class="w-6 h-6 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center text-xs font-bold">2</span>
                        Biaya & Waktu Pelatihan
                    </h2>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    {{-- Price --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                            Biaya Pelatihan (Rp) <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-3.5 top-2.5 text-xs font-bold text-gray-400">Rp</span>
                            <input type="number" 
                                   name="price" 
                                   value="{{ old('price', (int)$course->price) }}" 
                                   required 
                                   min="0"
                                   placeholder="0 untuk gratis / beasiswa" 
                                   class="w-full pl-10 pr-3.5 py-2.5 rounded-xl border border-gray-200 text-xs font-semibold text-gray-800 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 bg-white transition">
                        </div>
                    </div>

                    {{-- Duration Hours --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                            Total Jam Pelatihan (Jam) <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="number" 
                                   name="duration_hours" 
                                   value="{{ old('duration_hours', $course->duration_hours) }}" 
                                   required 
                                   min="1"
                                   placeholder="Contoh: 80" 
                                   class="w-full pr-12 pl-3.5 py-2.5 rounded-xl border border-gray-200 text-xs font-semibold text-gray-800 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 bg-white transition">
                            <span class="absolute right-3.5 top-2.5 text-xs text-gray-400 font-medium">Jam</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 3. DESKRIPSI & SILABUS --}}
            <div class="space-y-4 pt-4 border-t border-gray-100">
                <div class="border-b border-gray-100 pb-2">
                    <h2 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                        <span class="w-6 h-6 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center text-xs font-bold">3</span>
                        Deskripsi & Silabus Kurikulum
                    </h2>
                </div>

                {{-- Description --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                        Ringkasan / Deskripsi Kursus
                    </label>
                    <textarea name="description" 
                              rows="3" 
                              placeholder="Jelaskan tujuan pelatihan, prospek kerja lulusan, dan gambaran umum program..." 
                              class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-xs text-gray-800 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 bg-white transition">{{ old('description', $course->description) }}</textarea>
                </div>

                {{-- Syllabus --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                        Silabus Materi / Pokok Pembahasan
                    </label>
                    <textarea name="syllabus" 
                              rows="3" 
                              placeholder="Tuliskan daftar bab, materi kejuruan, atau standar unit kompetensi SKKNI yang dipelajari..." 
                              class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-xs text-gray-800 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 bg-white transition">{{ old('syllabus', $course->syllabus) }}</textarea>
                </div>
            </div>

            {{-- 4. FASILITAS & FOTO --}}
            <div class="space-y-4 pt-4 border-t border-gray-100">
                <div class="border-b border-gray-100 pb-2">
                    <h2 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                        <span class="w-6 h-6 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center text-xs font-bold">4</span>
                        Fasilitas & Dokumentasi Foto
                    </h2>
                </div>

                {{-- Facilities --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-2">
                        Fasilitas Pelatihan Tersedia
                    </label>
                    @php
                        $availableFacilities = [
                            'Ruang Ber-AC', 
                            'Bengkel / Lab Praktek', 
                            'Modul Belajar Cetak & Digital', 
                            'Akses Wi-Fi Cepat', 
                            'Sertifikat Resmi Kelulusan', 
                            'Makan Siang & Snack',
                            'Penyaluran Kerja Magang',
                            'Perlengkapan APD & K3'
                        ];
                        $selectedFacilities = old('facilities', $course->facilities ?? []);
                    @endphp
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5">
                        @foreach($availableFacilities as $fac)
                            <label class="flex items-center gap-2 p-2.5 rounded-xl border border-gray-200 hover:bg-gray-50 cursor-pointer text-xs font-medium text-gray-700 select-none transition">
                                <input type="checkbox" 
                                       name="facilities[]" 
                                       value="{{ $fac }}" 
                                       {{ in_array($fac, (array)$selectedFacilities) ? 'checked' : '' }} 
                                       class="rounded border-gray-300 text-indigo-600 focus:ring-0">
                                <span>{{ $fac }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                {{-- Existing Images Preview --}}
                @if(!empty($course->images) && is_array($course->images) && count($course->images) > 0)
                    <div class="p-4 rounded-xl bg-gray-50 border border-gray-200">
                        <div class="text-xs font-semibold text-gray-700 mb-2.5 flex items-center justify-between">
                            <span>Foto Kursus Aktif Saat Ini:</span>
                            <span class="text-[11px] text-gray-400 font-normal">({{ count($course->images) }} gambar tersimpan)</span>
                        </div>
                        <div class="flex flex-wrap gap-3">
                            @foreach($course->images as $img)
                                <div class="relative w-20 h-20 rounded-xl overflow-hidden border border-gray-200 shadow-sm bg-white">
                                    <img src="{{ asset('storage/' . $img) }}" 
                                         alt="Foto Kursus" 
                                         class="w-full h-full object-cover">
                                </div>
                            @endforeach
                        </div>
                        <p class="text-[11px] text-amber-600 mt-2.5 flex items-center gap-1.5">
                            <i class="fa-solid fa-circle-info text-xs"></i>
                            <span>Mengunggah file foto baru di bawah ini akan memperbarui foto galeri yang sudah ada.</span>
                        </p>
                    </div>
                @endif

                {{-- New Images Upload --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                        Unggah Foto Pelatihan Baru (Opsional)
                    </label>
                    <input type="file" 
                           name="images[]" 
                           multiple 
                           accept="image/jpeg,image/png,image/jpg" 
                           class="w-full text-xs text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition border border-gray-200 rounded-xl bg-gray-50/50 p-1.5">
                    <p class="text-[11px] text-gray-400 mt-1">Biarkan kosong jika tidak ingin mengubah foto pelatihan saat ini.</p>
                </div>
            </div>

            {{-- FOOTER ACTION BUTTONS --}}
            <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-100">
                <a href="{{ route('admin.courses.index') }}" 
                   class="px-5 py-2.5 rounded-xl border border-gray-200 text-xs font-semibold text-gray-600 hover:bg-gray-50 transition">
                    Batal
                </a>
                <button type="submit" 
                        class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold shadow-sm shadow-indigo-600/20 transition flex items-center gap-2">
                    <i class="fa-solid fa-floppy-disk text-xs"></i>
                    <span>Simpan Perubahan Kursus</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection