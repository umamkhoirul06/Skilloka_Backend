@extends('layouts.public')

@section('title', 'Tentang Kami - Skilloka')
@section('meta_description', 'Mengenal Skilloka, platform ekosistem pelatihan vokasi dan lembaga pelatihan kerja (LPK) resmi di Kabupaten Indramayu.')

@section('content')
<div class="py-12 sm:py-16">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

        <!-- HEADER SINGKAT & LOGO WEB -->
        <div class="text-center space-y-3">
            <div class="inline-flex justify-center mb-1">
                <x-skilloka-logo class="h-11 sm:h-12" :withSubtitle="true" subtitle="Pusat Pelatihan Vokasi LPK" />
            </div>
            <h1 class="font-outfit text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">
                Tentang Skilloka
            </h1>
            <p class="text-sm sm:text-base text-slate-600 max-w-xl mx-auto leading-relaxed">
                Platform digital penghubung pencari kerja, pelajar, dan masyarakat umum dengan Lembaga Pelatihan Kerja (LPK) resmi di Kabupaten Indramayu.
            </p>
        </div>

        <!-- SEKILAS TENTANG KAMI -->
        <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200/90 shadow-xs space-y-4">
            <h2 class="font-outfit text-xl font-bold text-slate-900 flex items-center gap-2">
                <i class="fa-solid fa-circle-info text-brand-600 text-base"></i>
                Sekilas Tentang Kami
            </h2>
            <div class="text-sm text-slate-600 leading-relaxed space-y-3">
                <p>
                    Skilloka hadir untuk menjawab kebutuhan nyata di <strong>Kabupaten Indramayu dan sekitarnya</strong> dalam mempermudah akses informasi pelatihan kejuruan yang terpercaya. Banyak pemuda, lulusan SMA/SMK, dan angkatan kerja yang ingin meningkatkan keterampilan (upskilling), namun menghadapi kendala minimnya transparansi jadwal, rincian biaya, serta kejelasan bukti legalitas lembaga pelatihan.
                </p>
                <p>
                    Melalui integrasi platform web dan mobile, Skilloka menyatukan proses pencarian kursus, jadwal kelas yang fleksibel, proses pendaftaran yang aman, hingga kepastian sertifikat kompetensi resmi berstandar industri. Kami juga membantu lembaga LPK lokal bertransformasi digital dalam mengelola operasional pelatihannya secara modern.
                </p>
            </div>
        </div>

        <!-- VISI & MISI (2 CARD RAPI) -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Visi -->
            <div class="bg-white p-6 sm:p-7 rounded-2xl border border-slate-200/90 shadow-xs space-y-3">
                <div class="w-10 h-10 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-bullseye"></i>
                </div>
                <h3 class="font-outfit text-lg font-bold text-slate-900">Visi Kami</h3>
                <p class="text-sm text-slate-600 leading-relaxed">
                    Menjadi pusat ekosistem vokasi digital terpercaya yang mempermudah masyarakat mengakses keahlian nyata dan mencetak tenaga kerja lokal yang kompeten serta siap bersaing di dunia industri.
                </p>
            </div>

            <!-- Misi -->
            <div class="bg-white p-6 sm:p-7 rounded-2xl border border-slate-200/90 shadow-xs space-y-3">
                <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-compass"></i>
                </div>
                <h3 class="font-outfit text-lg font-bold text-slate-900">Misi Kami</h3>
                <ul class="text-sm text-slate-600 leading-relaxed space-y-2">
                    <li class="flex items-start gap-2">
                        <i class="fa-solid fa-check text-brand-600 text-xs mt-1"></i>
                        <span>Menyediakan informasi kursus dan jadwal LPK resmi secara transparan.</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <i class="fa-solid fa-check text-brand-600 text-xs mt-1"></i>
                        <span>Menjembatani peserta dengan pelatihan standar kompetensi kerja (SKKNI).</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <i class="fa-solid fa-check text-brand-600 text-xs mt-1"></i>
                        <span>Mendukung digitalisasi administrasi operasional lembaga pelatihan (LPK).</span>
                    </li>
                </ul>
            </div>
        </div>

        <!-- KOMITMEN & STANDAR LAYANAN (3 POIN PENTING) -->
        <div class="bg-white p-6 sm:p-7 rounded-2xl border border-slate-200/90 shadow-xs space-y-4">
            <h2 class="font-outfit text-xl font-bold text-slate-900 flex items-center gap-2">
                <i class="fa-solid fa-shield-check text-emerald-600 text-base"></i>
                Komitmen & Standar Layanan
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-100 space-y-1">
                    <div class="text-xs font-bold text-slate-900 flex items-center gap-1.5">
                        <i class="fa-solid fa-building-circle-check text-brand-600"></i>
                        LPK Berizin Resmi
                    </div>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Hanya bermitra dengan lembaga yang memiliki izin resmi (VIN Disnaker / Kemnaker).
                    </p>
                </div>
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-100 space-y-1">
                    <div class="text-xs font-bold text-slate-900 flex items-center gap-1.5">
                        <i class="fa-solid fa-receipt text-amber-600"></i>
                        Biaya & Kuota Transparan
                    </div>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Informasi biaya pelatihan, jadwal kelas, dan ketersediaan kuota ditampilkan terbuka.
                    </p>
                </div>
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-100 space-y-1">
                    <div class="text-xs font-bold text-slate-900 flex items-center gap-1.5">
                        <i class="fa-solid fa-award text-purple-600"></i>
                        Sertifikasi Kompetensi
                    </div>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Dukungan penerbitan sertifikat pelatihan resmi yang diakui dan dapat diverifikasi.
                    </p>
                </div>
            </div>
        </div>

        <!-- INFORMASI KONTAK & KANTOR -->
        <div class="bg-slate-900 text-white p-6 sm:p-7 rounded-2xl shadow-xs space-y-5">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-800 pb-4">
                <div>
                    <h3 class="font-outfit text-base font-bold text-white">Hubungi & Layanan Bantuan</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Informasi operasional dan layanan bantuan Skilloka</p>
                </div>
                <a href="{{ route('public.courses') }}" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-brand-600 hover:bg-brand-500 text-white text-xs font-semibold transition self-start sm:self-auto">
                    <span>Lihat Kursus</span>
                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 text-xs text-slate-300">
                <div class="space-y-1">
                    <span class="text-slate-400 font-semibold block text-[11px] uppercase tracking-wider">Lokasi</span>
                    <p class="text-slate-200">
                        Kabupaten Indramayu, Jawa Barat<br>
                        Indonesia
                    </p>
                </div>
                <div class="space-y-1">
                    <span class="text-slate-400 font-semibold block text-[11px] uppercase tracking-wider">Kontak & WhatsApp</span>
                    <p class="text-slate-200">
                        <a href="https://wa.me/62895405193300" target="_blank" class="hover:text-amber-400 transition underline">+62 895-4051-93300</a><br>
                        Senin - Sabtu (08.00 - 17.00 WIB)
                    </p>
                </div>
                <div class="space-y-1">
                    <span class="text-slate-400 font-semibold block text-[11px] uppercase tracking-wider">Email Resmi</span>
                    <p class="text-slate-200">
                        support@skilloka.id<br>
                        Kemitraan & Pertanyaan Umum
                    </p>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
