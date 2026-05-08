@extends('layouts.admin')

@section('title', 'LPK Profile')

@section('content')

{{-- NOTIFIKASI --}}
@if(session('success'))
    <div class="max-w-5xl mx-auto mb-4 px-4">
        <div class="flex items-center gap-3 p-4 bg-green-50 border border-green-200 text-green-800 rounded-xl">
            <svg class="w-4 h-4 flex-shrink-0 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <span class="text-sm font-semibold">{{ session('success') }}</span>
        </div>
    </div>
@endif

<div
    x-data="{
        activeTab: 'basic',
        logoPreview: null,
        handleLogoChange(event) {
            const file = event.target.files[0];
            if (file) { this.logoPreview = URL.createObjectURL(file); }
        }
    }"
    class="max-w-5xl mx-auto px-4 pb-10 space-y-4">

    {{-- ===================== HEADER CARD ===================== --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="h-20 bg-[#042C53]"></div>
        <div class="px-6 pb-5">
            <div class="-mt-8 flex items-end gap-4 flex-wrap">

                {{-- Logo --}}
                <div class="w-16 h-16 rounded-xl overflow-hidden border border-gray-200 bg-white flex-shrink-0 shadow-sm">
                    <template x-if="logoPreview">
                        <img :src="logoPreview" class="w-full h-full object-cover">
                    </template>
                    <template x-if="!logoPreview">
                        @if($lpk->logo)
                            <img src="{{ asset('storage/'.$lpk->logo) }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-blue-50 flex items-center justify-center text-2xl font-semibold text-blue-400">
                                {{ strtoupper(substr($lpk->name ?? 'L', 0, 1)) }}
                            </div>
                        @endif
                    </template>
                </div>

                {{-- Info --}}
                <div class="pb-1 flex-1 min-w-[200px]">
                    <div class="flex items-center gap-2 flex-wrap">
                        <h1 class="text-lg font-semibold text-gray-900">{{ $lpk->name ?? 'LPK Name' }}</h1>
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ $lpk->is_verified ? 'bg-blue-50 text-blue-700 border border-blue-200' : 'bg-amber-50 text-amber-700 border border-amber-200' }}">
                            @if($lpk->is_verified)
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                Verified
                            @else
                                Pending
                            @endif
                        </span>
                    </div>
                    <div class="flex items-center gap-2 mt-1.5 flex-wrap">
                        <span class="inline-flex items-center gap-1 text-xs text-gray-500 bg-gray-100 border border-gray-200 px-2 py-0.5 rounded-md font-medium">
                            NIB: {{ $lpk->nib ?? '-' }}
                        </span>
                        <span class="text-xs text-gray-400">
                            Update terakhir: {{ $lpk->updated_at->format('d M Y, H:i') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===================== MAIN FORM CARD ===================== --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">

        {{-- TAB NAVIGATION --}}
        <div class="flex gap-1.5 px-4 pt-3 pb-0 border-b border-gray-200 overflow-x-auto">
            <button type="button" @click="activeTab='basic'"
                :class="activeTab==='basic'
                    ? 'border-blue-600 text-blue-600 font-semibold'
                    : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                class="flex items-center gap-1.5 px-4 py-2.5 text-sm border-b-2 transition-colors duration-150 whitespace-nowrap -mb-px">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                Informasi dasar
            </button>
            <button type="button" @click="activeTab='location'"
                :class="activeTab==='location'
                    ? 'border-blue-600 text-blue-600 font-semibold'
                    : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                class="flex items-center gap-1.5 px-4 py-2.5 text-sm border-b-2 transition-colors duration-150 whitespace-nowrap -mb-px">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Lokasi
            </button>
            <button type="button" @click="activeTab='media'"
                :class="activeTab==='media'
                    ? 'border-blue-600 text-blue-600 font-semibold'
                    : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                class="flex items-center gap-1.5 px-4 py-2.5 text-sm border-b-2 transition-colors duration-150 whitespace-nowrap -mb-px">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                Media & fasilitas
            </button>
        </div>

        <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="p-6">

                {{-- ===== TAB 1: INFORMASI DASAR ===== --}}
                <div x-show="activeTab==='basic'" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-5">

                    <div>
                        <p class="text-xs font-medium text-gray-400 uppercase tracking-widest mb-4">Data umum</p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div class="space-y-1.5">
                                <label for="name" class="block text-xs font-medium text-gray-600 uppercase tracking-wide">Nama LPK</label>
                                <input type="text" name="name" id="name" value="{{ old('name', $lpk->name) }}"
                                    placeholder="Masukkan nama LPK"
                                    class="w-full rounded-lg border border-gray-300 bg-gray-50 px-3.5 py-2.5 text-sm text-gray-900 font-medium placeholder-gray-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all outline-none">
                                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div class="space-y-1.5">
                                <label for="legal_name" class="block text-xs font-medium text-gray-600 uppercase tracking-wide">Nama badan hukum (PT/CV)</label>
                                <input type="text" name="legal_name" id="legal_name" value="{{ old('legal_name', $lpk->legal_name) }}"
                                    placeholder="PT / CV ..."
                                    class="w-full rounded-lg border border-gray-300 bg-gray-50 px-3.5 py-2.5 text-sm text-gray-900 font-medium placeholder-gray-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all outline-none">
                            </div>

                            <div class="space-y-1.5">
                                <label for="nib" class="block text-xs font-medium text-gray-600 uppercase tracking-wide">Nomor NIB</label>
                                <input type="text" name="nib" id="nib" value="{{ old('nib', $lpk->nib) }}"
                                    placeholder="xxxx-xxxx-xxxx"
                                    class="w-full rounded-lg border border-gray-300 bg-gray-50 px-3.5 py-2.5 text-sm text-gray-900 font-medium placeholder-gray-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all outline-none">
                            </div>

                            <div class="space-y-1.5">
                                <label for="contact_info" class="block text-xs font-medium text-gray-600 uppercase tracking-wide">Kontak (WA / Email)</label>
                                <input type="text" name="contact_info" id="contact_info" value="{{ old('contact_info', $lpk->contact_info) }}"
                                    placeholder="+62 atau email@lpk.com"
                                    class="w-full rounded-lg border border-gray-300 bg-gray-50 px-3.5 py-2.5 text-sm text-gray-900 font-medium placeholder-gray-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all outline-none">
                            </div>
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label for="description" class="block text-xs font-medium text-gray-600 uppercase tracking-wide">Deskripsi singkat</label>
                        <textarea name="description" id="description" rows="4"
                            placeholder="Ceritakan tentang LPK ini..."
                            class="w-full rounded-lg border border-gray-300 bg-gray-50 px-3.5 py-2.5 text-sm text-gray-900 font-medium placeholder-gray-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all outline-none resize-none">{{ old('description', $lpk->description) }}</textarea>
                    </div>
                </div>

                {{-- ===== TAB 2: LOKASI ===== --}}
                <div x-show="activeTab==='location'" x-cloak x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-5">

                    <div class="space-y-1.5">
                        <label for="address" class="block text-xs font-medium text-gray-600 uppercase tracking-wide">Alamat lengkap kantor</label>
                        <textarea name="address" id="address" rows="3"
                            placeholder="Jl. Contoh No. 1, Kelurahan, Kecamatan, Kota, Provinsi..."
                            class="w-full rounded-lg border border-gray-300 bg-gray-50 px-3.5 py-2.5 text-sm text-gray-900 font-medium placeholder-gray-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all outline-none resize-none">{{ old('address', $lpk->address) }}</textarea>
                    </div>

                    {{-- Info koordinat --}}
                    <div class="flex items-start gap-3 p-3.5 bg-blue-50 border border-blue-200 rounded-lg">
                        <svg class="w-4 h-4 text-blue-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <p class="text-xs text-blue-700">
                            Salin koordinat dari <span class="font-semibold">Google Maps</span> — klik kanan pada lokasi lalu pilih
                            <span class="font-semibold">"Apa yang ada di sini?"</span> untuk mendapatkan latitude &amp; longitude.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="space-y-1.5">
                            <label for="lat" class="block text-xs font-medium text-gray-600 uppercase tracking-wide">Latitude</label>
                            <input type="text" name="lat" id="lat" value="{{ old('lat', $lpk->lat) }}"
                                placeholder="-6.xxxxxx"
                                class="w-full rounded-lg border border-gray-300 bg-gray-50 px-3.5 py-2.5 text-sm text-gray-900 font-medium placeholder-gray-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all outline-none">
                        </div>
                        <div class="space-y-1.5">
                            <label for="long" class="block text-xs font-medium text-gray-600 uppercase tracking-wide">Longitude</label>
                            <input type="text" name="long" id="long" value="{{ old('long', $lpk->long) }}"
                                placeholder="106.xxxxxx"
                                class="w-full rounded-lg border border-gray-300 bg-gray-50 px-3.5 py-2.5 text-sm text-gray-900 font-medium placeholder-gray-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all outline-none">
                        </div>
                    </div>
                </div>

                {{-- ===== TAB 3: MEDIA & FASILITAS ===== --}}
                <div x-show="activeTab==='media'" x-cloak x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-5">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                        {{-- Upload Logo --}}
                        <div class="border border-dashed border-blue-200 bg-blue-50/50 rounded-xl p-5 flex flex-col items-center gap-3 text-center">
                            <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-700">Logo LPK</p>
                                <p class="text-xs text-gray-400 mt-0.5">PNG, JPG — maks. 2MB</p>
                            </div>
                            <label class="cursor-pointer inline-flex items-center gap-1.5 px-4 py-1.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                Pilih file
                                <input type="file" name="logo" @change="handleLogoChange" accept="image/*" class="hidden">
                            </label>
                        </div>

                        {{-- Upload Galeri --}}
                        <div class="border border-dashed border-gray-300 bg-gray-50 rounded-xl p-5 flex flex-col items-center gap-3 text-center">
                            <div class="w-10 h-10 rounded-lg bg-gray-200 flex items-center justify-center">
                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-700">Galeri foto</p>
                                <p class="text-xs text-gray-400 mt-0.5">Bisa pilih beberapa foto sekaligus</p>
                            </div>
                            <label class="cursor-pointer inline-flex items-center gap-1.5 px-4 py-1.5 rounded-lg bg-gray-800 hover:bg-black text-white text-xs font-semibold transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                Pilih foto
                                <input type="file" name="images[]" multiple accept="image/*" class="hidden">
                            </label>
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label for="facilities" class="block text-xs font-medium text-gray-600 uppercase tracking-wide">Fasilitas LPK</label>
                        <textarea name="facilities" id="facilities" rows="4"
                            placeholder="Contoh: lab bahasa, asrama putra/putri, kantin, ruang komputer..."
                            class="w-full rounded-lg border border-gray-300 bg-gray-50 px-3.5 py-2.5 text-sm text-gray-900 font-medium placeholder-gray-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all outline-none resize-none">{{ old('facilities', $lpk->facilities) }}</textarea>
                    </div>
                </div>

            </div>

            {{-- FOOTER --}}
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50/50 flex justify-between items-center flex-wrap gap-3">
                <div class="flex items-center gap-1.5 text-gray-400">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span class="text-xs">Update terakhir: {{ $lpk->updated_at->format('d M Y, H:i') }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.dashboard') }}"
                        class="px-4 py-2 rounded-lg border border-gray-300 text-sm font-medium text-gray-600 hover:bg-gray-100 transition-colors">
                        Batal
                    </a>
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-5 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold shadow-sm transition-colors active:scale-95">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                        Simpan perubahan
                    </button>
                </div>
            </div>

        </form>
    </div>
</div>

<style>
    [x-cloak] { display: none !important; }
</style>

@endsection