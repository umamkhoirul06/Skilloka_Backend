@extends('layouts.public')

@section('title', 'Kemitraan LPK - Skilloka Platform')
@section('meta_description', 'Bergabung menjadi mitra LPK resmi Skilloka di Kabupaten Indramayu. Dapatkan sistem manajemen kelas, pendaftaran siswa, presensi, dan sertifikasi terpadu.')

@section('content')
<!-- HERO FOR LPK -->
<div class="bg-gradient-to-br from-slate-900 via-indigo-950 to-slate-900 text-white py-16 lg:py-24 border-b border-slate-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center max-w-3xl">
        <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-indigo-500/20 text-indigo-300 border border-indigo-500/30 mb-4">
            <i class="fa-solid fa-handshake"></i> Kemitraan Strategis LPK
        </span>
        <h1 class="font-outfit text-3xl sm:text-5xl font-extrabold tracking-tight leading-tight">
            Digitalisasikan Operasional & Luaskan Jangkauan Lembaga Anda
        </h1>
        <p class="text-sm sm:text-base text-slate-300 mt-4 leading-relaxed">
            Skilloka hadir sebagai mitra teknologi bagi Lembaga Pelatihan Kerja (LPK) di Kabupaten Indramayu untuk mengelola operasional kelas secara modern, terstruktur, dan terpercaya.
        </p>
        <div class="mt-8 flex flex-wrap items-center justify-center gap-4">
            <a href="{{ route('lpk.register') }}" class="px-6 py-3.5 rounded-xl font-bold text-sm bg-gradient-to-r from-brand-500 to-indigo-500 hover:from-brand-600 hover:to-indigo-600 text-white shadow-lg shadow-brand-500/25 transition">
                Daftarkan LPK Anda
            </a>
            <a href="{{ route('admin.login') }}" class="px-6 py-3.5 rounded-xl font-bold text-sm bg-slate-800 hover:bg-slate-700 text-slate-300 border border-slate-700 transition">
                Sudah Menjadi Mitra? Masuk
            </a>
        </div>
    </div>
</div>

<!-- BENEFITS GRID -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
    <div class="text-center max-w-2xl mx-auto mb-16">
        <h2 class="text-xs font-bold uppercase tracking-wider text-brand-600 mb-2">Mengapa Bermitra?</h2>
        <p class="font-outfit text-3xl font-extrabold text-slate-900">Keuntungan Bergabung di Skilloka</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="bg-white p-8 rounded-3xl border border-slate-200 shadow-sm space-y-4">
            <div class="w-12 h-12 rounded-2xl bg-blue-100 text-brand-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-users"></i>
            </div>
            <h3 class="font-outfit text-lg font-bold text-slate-900">Jangkau Calon Siswa Lebih Luas</h3>
            <p class="text-xs text-slate-600 leading-relaxed">
                Program kursus Anda akan langsung tampil di aplikasi mobile ribuan calon peserta pelatihan di Indramayu dan sekitarnya.
            </p>
        </div>

        <div class="bg-white p-8 rounded-3xl border border-slate-200 shadow-sm space-y-4">
            <div class="w-12 h-12 rounded-2xl bg-indigo-100 text-indigo-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-laptop-code"></i>
            </div>
            <h3 class="font-outfit text-lg font-bold text-slate-900">Sistem Presensi & Materi Terpadu</h3>
            <p class="text-xs text-slate-600 leading-relaxed">
                Kelola silabus, unggah modul materi belajar, dan rekap absensi kehadiran siswa per sesi pertemuan tanpa kertas.
            </p>
        </div>

        <div class="bg-white p-8 rounded-3xl border border-slate-200 shadow-sm space-y-4">
            <div class="w-12 h-12 rounded-2xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-file-shield"></i>
            </div>
            <h3 class="font-outfit text-lg font-bold text-slate-900">Sertifikasi Resmi Terverifikasi</h3>
            <p class="text-xs text-slate-600 leading-relaxed">
                Terbitkan sertifikat kelulusan berstandar dengan nomor unik yang dapat divalidasi keabsahannya oleh pihak perusahaan/industri.
            </p>
        </div>
    </div>
</div>

<!-- HOW TO JOIN -->
<div class="bg-slate-50 border-t border-slate-200 py-20">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-12">
        <div>
            <h2 class="text-xs font-bold uppercase tracking-wider text-brand-600 mb-2">Proses Kemitraan</h2>
            <p class="font-outfit text-3xl font-extrabold text-slate-900">Alur Pendaftaran Mitra LPK</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-4 gap-6 text-left">
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                <div class="font-outfit font-extrabold text-2xl text-brand-600 mb-2">01</div>
                <h4 class="font-bold text-sm text-slate-900 mb-1">Isi Formulir</h4>
                <p class="text-xs text-slate-500">Lengkapi data akun admin dan profil legalitas lembaga Anda.</p>
            </div>
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                <div class="font-outfit font-extrabold text-2xl text-indigo-600 mb-2">02</div>
                <h4 class="font-bold text-sm text-slate-900 mb-1">Verifikasi</h4>
                <p class="text-xs text-slate-500">Super Admin memeriksa legalitas NIB dan izin operasional LPK.</p>
            </div>
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                <div class="font-outfit font-extrabold text-2xl text-purple-600 mb-2">03</div>
                <h4 class="font-bold text-sm text-slate-900 mb-1">Aktivasi</h4>
                <p class="text-xs text-slate-500">Akun disetujui dan dashboard operasional siap digunakan.</p>
            </div>
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                <div class="font-outfit font-extrabold text-2xl text-emerald-600 mb-2">04</div>
                <h4 class="font-bold text-sm text-slate-900 mb-1">Buka Kelas</h4>
                <p class="text-xs text-slate-500">Publikasikan program kursus dan terima pendaftaran siswa.</p>
            </div>
        </div>

        <div class="pt-4">
            <a href="{{ route('lpk.register') }}" class="inline-flex items-center gap-2 px-8 py-4 rounded-xl font-bold text-sm bg-brand-600 hover:bg-brand-700 text-white shadow-lg shadow-brand-500/25 transition">
                <span>Mulai Pendaftaran LPK</span>
                <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>
    </div>
</div>
@endsection
