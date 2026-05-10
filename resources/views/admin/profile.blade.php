@extends('layouts.admin')

@section('title', 'Profil Pengguna')

@section('content')

{{-- Tambahkan library Cropper.js dari CDN --}}
<link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

<div class="py-8">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Header Section --}}
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900">Pengaturan Profil</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola data pribadi dan foto profil Anda di sini.</p>
        </div>

        {{-- Success Notification --}}
        @if(session('success'))
            <div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-200 flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <span class="text-green-800 text-sm font-medium">{{ session('success') }}</span>
            </div>
        @endif

        {{-- Error Notification --}}
        @if($errors->any())
            <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 flex items-start gap-3">
                <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <ul class="text-red-800 text-sm font-medium mt-1.5 list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Main Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            
            <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                {{-- Dihapus: @method('PUT') karena route ternyata POST --}}

                <div class="flex flex-col md:flex-row">
                    
                    {{-- Left Panel: Avatar Section --}}
                    <div class="md:w-1/3 bg-gray-50 p-8 border-b md:border-b-0 md:border-r border-gray-200 flex flex-col items-center justify-start text-center">
                        
                        <h3 class="text-sm font-bold text-gray-700 mb-6 uppercase tracking-wider">Foto Profil</h3>

                        <div class="relative mb-6">
                            {{-- Avatar Container --}}
                            <div class="w-40 h-40 rounded-full shadow-md border-4 border-white overflow-hidden bg-white">
                                @php
                                    $user = auth()->user();
                                    $defaultAvatar = 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&color=1e3a8a&background=e0e7ff&size=200';
                                    $avatarUrl = $user->avatar ? asset('storage/'.$user->avatar) : $defaultAvatar;
                                @endphp
                                <img id="avatarPreview" src="{{ $avatarUrl }}" alt="Avatar" class="w-full h-full object-cover">
                            </div>
                        </div>

                        {{-- Upload Button --}}
                        <div class="w-full max-w-xs">
                            <label for="avatarInput" class="flex items-center justify-center w-full px-4 py-2.5 bg-white border border-gray-300 rounded-xl shadow-sm text-sm font-bold text-gray-700 hover:bg-gray-100 cursor-pointer transition-colors">
                                <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                </svg>
                                Ubah Foto
                            </label>
                            <input type="file" id="avatarInput" name="avatar" accept="image/*" class="hidden">
                            <p class="text-xs text-gray-400 mt-3">Pilih gambar lalu atur area (Crop). Maks 2MB.</p>
                        </div>

                    </div>

                    {{-- Right Panel: User Data Form --}}
                    <div class="md:w-2/3 p-8 lg:p-10">
                        <h3 class="text-lg font-bold text-gray-900 mb-6 border-b border-gray-100 pb-2">Informasi Dasar</h3>

                        <div class="space-y-6">
                            
                            {{-- Nama Lengkap --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap</label>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}" 
                                    class="w-full px-4 py-3 rounded-xl border border-gray-300 bg-white text-gray-900 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-shadow">
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                {{-- Email --}}
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Alamat Email</label>
                                    <input type="email" name="email" value="{{ old('email', $user->email) }}" 
                                        class="w-full px-4 py-3 rounded-xl border border-gray-300 bg-white text-gray-900 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-shadow">
                                </div>

                                {{-- Nomor Telepon --}}
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nomor Telepon / WA</label>
                                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" 
                                        class="w-full px-4 py-3 rounded-xl border border-gray-300 bg-white text-gray-900 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-shadow">
                                </div>
                            </div>

                            <h3 class="text-lg font-bold text-gray-900 mb-4 mt-8 border-b border-gray-100 pb-2">Informasi Lembaga</h3>

                            {{-- Nama LPK (Readonly) --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Lembaga Pelatihan (LPK)</label>
                                <div class="relative">
                                    <input type="text" readonly value="{{ $lpk->name ?? 'Belum terhubung ke LPK' }}" 
                                        class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-100 text-gray-500 cursor-not-allowed focus:outline-none select-none">
                                    
                                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                        <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                </div>
                                <p class="text-xs text-gray-500 mt-2">Nama lembaga terkunci. Silakan hubungi Super Admin untuk mengajukan perubahan data legalitas.</p>
                            </div>

                        </div>
                        
                    </div>
                </div>

                {{-- Footer Actions --}}
                <div class="bg-gray-50 px-8 py-5 border-t border-gray-200 flex justify-end gap-3">
                    <a href="{{ route('admin.dashboard') }}" class="px-6 py-2.5 rounded-xl border border-gray-300 text-sm font-bold text-gray-700 bg-white hover:bg-gray-100 transition-colors">
                        Batal
                    </a>
                    {{-- Menggunakan style inline untuk memastikan tombol Simpan 100% muncul jelas dengan warna gelap ala SaaS! --}}
                    <button type="submit" style="background-color: #0f172a; color: white;" class="px-8 py-2.5 rounded-xl text-sm font-bold shadow-md hover:opacity-90 transition-all">
                        Simpan Perubahan
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

{{-- Modal Cropper --}}
<div id="cropperModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-black bg-opacity-75 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full shadow-2xl overflow-hidden flex flex-col">
        <div class="p-4 border-b flex justify-between items-center">
            <h3 class="font-bold text-lg text-gray-800">Sesuaikan Foto</h3>
            <button type="button" id="closeCropper" class="text-gray-400 hover:text-red-500 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <div class="p-4 flex-1 bg-gray-100" style="max-height: 500px;">
            {{-- Tempat gambar akan di-load oleh cropper --}}
            <img id="imageToCrop" src="" alt="Picture" class="max-w-full block">
        </div>
        <div class="p-4 border-t bg-gray-50 flex justify-end gap-3">
            <button type="button" id="cancelCrop" class="px-5 py-2 rounded-xl text-sm font-bold text-gray-700 bg-white border border-gray-300 hover:bg-gray-100">Batal</button>
            <button type="button" id="applyCrop" style="background-color: #0f172a; color: white;" class="px-6 py-2 rounded-xl text-sm font-bold shadow-md hover:opacity-90">Terapkan (Crop)</button>
        </div>
    </div>
</div>

{{-- Script Integrasi Cropper.js --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const avatarInput = document.getElementById('avatarInput');
        const avatarPreview = document.getElementById('avatarPreview');
        const cropperModal = document.getElementById('cropperModal');
        const imageToCrop = document.getElementById('imageToCrop');
        const closeCropperBtn = document.getElementById('closeCropper');
        const cancelCropBtn = document.getElementById('cancelCrop');
        const applyCropBtn = document.getElementById('applyCrop');
        
        let cropper = null;

        // Fungsi membuka modal
        function openModal() {
            cropperModal.classList.remove('hidden');
        }

        // Fungsi tutup modal
        function closeModal() {
            cropperModal.classList.add('hidden');
            if (cropper) {
                cropper.destroy();
                cropper = null;
            }
            avatarInput.value = ''; // Reset input value
        }

        closeCropperBtn.addEventListener('click', closeModal);
        cancelCropBtn.addEventListener('click', closeModal);

        // Ketika user memilih file
        avatarInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                // Validasi gambar
                if(!file.type.match('image.*')) {
                    alert('Hanya file gambar yang diperbolehkan.');
                    this.value = '';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(event) {
                    imageToCrop.src = event.target.result;
                    openModal();
                    
                    // Inisialisasi cropper setelah modal terbuka
                    setTimeout(() => {
                        cropper = new Cropper(imageToCrop, {
                            aspectRatio: 1, // Memaksa rasio 1:1 (bulat/persegi)
                            viewMode: 1,
                            dragMode: 'move',
                            autoCropArea: 1,
                            restore: false,
                            guides: true,
                            center: true,
                            highlight: false,
                            cropBoxMovable: true,
                            cropBoxResizable: true,
                            toggleDragModeOnDblclick: false,
                        });
                    }, 100);
                }
                reader.readAsDataURL(file);
            }
        });

        // Ketika tombol "Terapkan (Crop)" diklik
        applyCropBtn.addEventListener('click', function() {
            if (!cropper) return;

            // Dapatkan hasil crop dalam format canvas
            const canvas = cropper.getCroppedCanvas({
                width: 500,
                height: 500,
            });

            // Ubah canvas menjadi Blob (File data)
            canvas.toBlob(function(blob) {
                // Tampilkan hasil crop ke lingkaran preview
                const url = URL.createObjectURL(blob);
                avatarPreview.src = url;

                // Buat objek File baru dari Blob
                const croppedFile = new File([blob], "avatar_cropped.jpg", { type: "image/jpeg", lastModified: new Date().getTime() });
                
                // Gunakan DataTransfer untuk memasukkan File baru ke dalam <input type="file">
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(croppedFile);
                avatarInput.files = dataTransfer.files;

                // Tutup modal
                cropperModal.classList.add('hidden');
                if (cropper) {
                    cropper.destroy();
                    cropper = null;
                }
            }, 'image/jpeg', 0.9);
        });
    });
</script>

@endsection