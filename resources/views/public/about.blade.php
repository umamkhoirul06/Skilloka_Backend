@extends('layouts.public')

@section('title', 'Tentang Kami - Skilloka Platform')
@section('meta_description', 'Mengenal visi, misi, dan komitmen Skilloka dalam memajukan kompetensi tenaga kerja di Kabupaten Indramayu.')

@section('content')
<div class="bg-white border-b border-slate-200 py-16">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-4">
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-brand-50 text-brand-700">
            Platform Vokasi Digital
        </span>
        <h1 class="font-outfit text-3xl sm:text-5xl font-extrabold text-slate-900 tracking-tight">
            Membangun Masa Depan Tenaga Kerja Kompeten
        </h1>
        <p class="text-sm sm:text-base text-slate-600 leading-relaxed max-w-2xl mx-auto">
            Skilloka menghubungkan pencari kerja, pelajar, dan masyarakat umum dengan Lembaga Pelatihan Kerja resmi untuk menciptakan SDM unggul dan berdaya saing tinggi.
        </p>
    </div>
</div>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16 space-y-12">
    <!-- STORY -->
    <div class="bg-white p-8 sm:p-10 rounded-3xl border border-slate-200 shadow-sm space-y-4">
        <h2 class="font-outfit text-2xl font-bold text-slate-900">Tentang Skilloka</h2>
        <p class="text-sm text-slate-600 leading-relaxed">
            Skilloka bermula dari kebutuhan nyata di <strong>Kabupaten Indramayu</strong>, di mana banyak pemuda, lulusan SMA/SMK, serta masyarakat yang ingin meningkatkan keterampilan kerja namun menghadapi kesulitan menemukan informasi LPK resmi yang terstandar, jadwal kelas yang transparan, dan bukti legalitas pelatihan.
        </p>
        <p class="text-sm text-slate-600 leading-relaxed">
            Melalui integrasi platform web dan mobile, Skilloka menyatukan seluruh proses: mulai dari pencarian kursus, jadwal fleksibel, booking aman, akses materi, absensi kelas, hingga penerbitan sertifikat kompetensi resmi berstandar industri.
        </p>
    </div>

    <!-- VISI & MISI -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white p-8 rounded-3xl border border-slate-200 shadow-sm space-y-3">
            <div class="w-10 h-10 rounded-xl bg-blue-100 text-brand-600 flex items-center justify-center text-lg mb-2">
                <i class="fa-solid fa-bullseye"></i>
            </div>
            <h3 class="font-outfit text-lg font-bold text-slate-900">Visi Kami</h3>
            <p class="text-xs text-slate-600 leading-relaxed">
                Menjadi platform ekosistem pelatihan kerja digital terdepan di Jawa Barat yang terpercaya, transparan, dan mampu menjembatani lulusan vokasi dengan kebutuhan dunia industri.
            </p>
        </div>

        <div class="bg-white p-8 rounded-3xl border border-slate-200 shadow-sm space-y-3">
            <div class="w-10 h-10 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center text-lg mb-2">
                <i class="fa-solid fa-compass"></i>
            </div>
            <h3 class="font-outfit text-lg font-bold text-slate-900">Misi Kami</h3>
            <p class="text-xs text-slate-600 leading-relaxed">
                1. Mempermudah akses pencarian kursus dan LPK resmi.<br>
                2. Menghadirkan transparansi jadwal, kuota, dan sertifikasi.<br>
                3. Mendukung digitalisasi tata kelola operasional lembaga pelatihan kerja.
            </p>
        </div>
    </div>
</div>
@endsection
