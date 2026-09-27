@extends('layouts.public')

@section('title', 'Skilloka - Platform Pencarian, Booking & Manajemen Pelatihan LPK')

@section('content')
<!-- HERO SECTION -->
<section class="relative hero-pattern pt-12 pb-20 lg:pt-20 lg:pb-28 overflow-hidden border-b border-slate-200">
    <!-- Glow elements -->
    <div class="absolute top-1/4 left-1/2 -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-brand-400/15 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute top-1/2 right-10 w-80 h-80 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center max-w-3xl mx-auto space-y-6">
            <!-- Badge -->
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-semibold bg-brand-50 text-brand-700 border border-brand-200 shadow-sm">
                <i class="fa-solid fa-certificate text-brand-600"></i>
                Ekosistem Pelatihan Kerja Resmi & Terstandarisasi
            </div>

            <!-- Headline -->
            <h1 class="font-outfit text-4xl sm:text-5xl lg:text-6xl font-extrabold text-slate-900 tracking-tight leading-[1.15]">
                Temukan Pelatihan yang <br class="hidden sm:inline">
                <span class="bg-gradient-to-r from-brand-600 via-indigo-600 to-purple-600 bg-clip-text text-transparent">Sesuai dengan Tujuanmu</span>
            </h1>

            <!-- Subtitle -->
            <p class="text-base sm:text-lg text-slate-600 leading-relaxed max-w-2xl mx-auto">
                Pilih kursus keahlian dari Lembaga Pelatihan Kerja (LPK) terverifikasi di Kabupaten Indramayu. Dapatkan jadwal fleksibel, materi berstandar industri, dan sertifikat resmi.
            </p>

            <!-- Search Form Bar -->
            <div class="pt-4 max-w-2xl mx-auto">
                <form action="{{ route('public.courses') }}" method="GET" class="p-2 sm:p-2.5 rounded-2xl bg-white border border-slate-200 shadow-xl shadow-slate-200/60 flex flex-col sm:flex-row gap-2">
                    <div class="flex-1 flex items-center px-3 gap-3">
                        <i class="fa-solid fa-magnifying-glass text-slate-400"></i>
                        <input type="text" name="search" placeholder="Cari nama kursus, keahlian, atau LPK..." class="w-full text-sm text-slate-800 placeholder-slate-400 focus:outline-none bg-transparent py-2">
                    </div>
                    <div class="sm:w-48 border-t sm:border-t-0 sm:border-l border-slate-200 px-3 flex items-center">
                        <i class="fa-solid fa-shapes text-slate-400 mr-2 text-xs"></i>
                        <select name="category" class="w-full text-sm text-slate-700 focus:outline-none bg-transparent py-2 cursor-pointer">
                            <option value="">Semua Kategori</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->slug }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="shiny-btn shiny-btn-brand px-6 py-3 rounded-xl text-white font-bold text-sm shadow-md shadow-brand-500/25 flex items-center justify-center gap-2">
                        <span>Cari</span>
                        <i class="fa-solid fa-arrow-right text-xs"></i>
                    </button>
                </form>
            </div>

            <!-- Quick CTA & Trust Badges -->
            <div class="pt-3 flex flex-wrap items-center justify-center gap-4 text-sm">
                <a href="{{ route('public.courses') }}" class="font-semibold text-brand-600 hover:text-brand-700 flex items-center gap-1.5">
                    <span>Lihat Seluruh Katalog Kursus</span>
                    <i class="fa-solid fa-chevron-right text-xs"></i>
                </a>
                <span class="text-slate-300 hidden sm:inline">&bull;</span>
                <a href="{{ route('public.for_lpk') }}" class="font-semibold text-slate-600 hover:text-slate-900 flex items-center gap-1.5">
                    <i class="fa-solid fa-briefcase text-indigo-500 text-xs"></i>
                    <span>Saya Pengelola LPK</span>
                </a>
            </div>
        </div>

        <!-- Metric counter cards -->
        <div class="mt-14 max-w-4xl mx-auto grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-white/80 backdrop-blur-sm p-4 rounded-2xl border border-slate-200/80 text-center shadow-sm">
                <div class="font-outfit text-3xl font-extrabold text-brand-600">{{ $stats['total_lpks'] }}</div>
                <div class="text-xs font-medium text-slate-500 mt-1">LPK Terverifikasi</div>
            </div>
            <div class="bg-white/80 backdrop-blur-sm p-4 rounded-2xl border border-slate-200/80 text-center shadow-sm">
                <div class="font-outfit text-3xl font-extrabold text-indigo-600">{{ $stats['total_courses'] }}</div>
                <div class="text-xs font-medium text-slate-500 mt-1">Program Kursus Aktif</div>
            </div>
            <div class="bg-white/80 backdrop-blur-sm p-4 rounded-2xl border border-slate-200/80 text-center shadow-sm">
                <div class="font-outfit text-3xl font-extrabold text-purple-600">{{ $stats['total_categories'] }}</div>
                <div class="text-xs font-medium text-slate-500 mt-1">Bidang Kejuruan</div>
            </div>
            <div class="bg-white/80 backdrop-blur-sm p-4 rounded-2xl border border-slate-200/80 text-center shadow-sm">
                <div class="font-outfit text-xl font-extrabold text-emerald-600 pt-1">Indramayu</div>
                <div class="text-xs font-medium text-slate-500 mt-1">Fokus Wilayah Awal</div>
            </div>
        </div>
    </div>
</section>

<!-- SECTION: SKILLOKA UNTUK APA? (PRD SECTION 6.2) -->
<section class="py-20 bg-white border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-16">
            <h2 class="text-xs font-bold uppercase tracking-wider text-brand-600 mb-2">Nilai Inti Platform</h2>
            <p class="font-outfit text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">Skilloka Untuk Apa?</p>
            <p class="text-slate-500 text-sm mt-3">Skilloka mendampingi perjalanan pelatihan Anda dari awal penemuan hingga pengakuan keahlian secara transparan.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <!-- Pillar 1 -->
            <div class="p-6 rounded-2xl bg-slate-50 border border-slate-100 hover:border-brand-200 hover:shadow-lg transition duration-300">
                <div class="w-12 h-12 rounded-xl bg-blue-100 text-brand-600 flex items-center justify-center text-xl mb-5">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </div>
                <h3 class="font-outfit text-lg font-bold text-slate-900 mb-2">1. Pencarian Terarah</h3>
                <p class="text-slate-600 text-xs leading-relaxed">
                    Temukan LPK berizin resmi dan kursus sesuai jenjang pendidikanmu (SMA, SMK, Perguruan Tinggi, atau Umum) tanpa informasi yang berbelit.
                </p>
            </div>

            <!-- Pillar 2 -->
            <div class="p-6 rounded-2xl bg-slate-50 border border-slate-100 hover:border-brand-200 hover:shadow-lg transition duration-300">
                <div class="w-12 h-12 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center text-xl mb-5">
                    <i class="fa-solid fa-calendar-check"></i>
                </div>
                <h3 class="font-outfit text-lg font-bold text-slate-900 mb-2">2. Booking & Jadwal Pasti</h3>
                <p class="text-slate-600 text-xs leading-relaxed">
                    Pilih jadwal kelas dengan kuota yang diperbarui real-time. Konfirmasi pendaftaran secara instan dengan transaksi yang aman.
                </p>
            </div>

            <!-- Pillar 3 -->
            <div class="p-6 rounded-2xl bg-slate-50 border border-slate-100 hover:border-brand-200 hover:shadow-lg transition duration-300">
                <div class="w-12 h-12 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center text-xl mb-5">
                    <i class="fa-solid fa-book-open-reader"></i>
                </div>
                <h3 class="font-outfit text-lg font-bold text-slate-900 mb-2">3. Pembelajaran & Absensi</h3>
                <p class="text-slate-600 text-xs leading-relaxed">
                    Akses materi, silabus pembelajaran, dan pantau rekap presensi kehadiran per sesi kelas langsung dari sistem LPK.
                </p>
            </div>

            <!-- Pillar 4 -->
            <div class="p-6 rounded-2xl bg-slate-50 border border-slate-100 hover:border-brand-200 hover:shadow-lg transition duration-300">
                <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-xl mb-5">
                    <i class="fa-solid fa-award"></i>
                </div>
                <h3 class="font-outfit text-lg font-bold text-slate-900 mb-2">4. Sertifikat Resmi</h3>
                <p class="text-slate-600 text-xs leading-relaxed">
                    Setelah menyelesaikan pelatihan dan memenuhi kehadiran, terima sertifikat kompetensi resmi dengan nomor registrasi unik.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- SECTION: CARA KERJA (PRD SECTION 6.2) -->
<section class="py-20 bg-slate-50 border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-16">
            <h2 class="text-xs font-bold uppercase tracking-wider text-brand-600 mb-2">Alur Transaksi & Belajar</h2>
            <p class="font-outfit text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">5 Langkah Mudah Mengikuti Pelatihan</p>
            <p class="text-slate-500 text-sm mt-3">Proses terstruktur dan transparan dari awal hingga kelulusan.</p>
        </div>

        <div class="relative">
            <!-- Line connecting on desktop -->
            <div class="hidden lg:block absolute top-1/2 left-0 right-0 h-0.5 bg-slate-200 -translate-y-6 z-0"></div>

            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-6 relative z-10">
                <div class="bg-white p-6 rounded-2xl border border-slate-200 text-center shadow-sm">
                    <div class="w-10 h-10 rounded-full bg-brand-600 text-white font-bold text-sm flex items-center justify-center mx-auto mb-4 ring-4 ring-brand-50">1</div>
                    <h4 class="font-outfit font-bold text-slate-900 mb-1 text-sm">Cari Kursus</h4>
                    <p class="text-slate-500 text-xs leading-relaxed">Cari kursus dan LPK yang sesuai dengan minat dan tujuan karirmu.</p>
                </div>

                <div class="bg-white p-6 rounded-2xl border border-slate-200 text-center shadow-sm">
                    <div class="w-10 h-10 rounded-full bg-indigo-600 text-white font-bold text-sm flex items-center justify-center mx-auto mb-4 ring-4 ring-indigo-50">2</div>
                    <h4 class="font-outfit font-bold text-slate-900 mb-1 text-sm">Pilih Jadwal</h4>
                    <p class="text-slate-500 text-xs leading-relaxed">Tentukan tanggal mulai dan jam belajar kelas yang masih tersedia kuotanya.</p>
                </div>

                <div class="bg-white p-6 rounded-2xl border border-slate-200 text-center shadow-sm">
                    <div class="w-10 h-10 rounded-full bg-purple-600 text-white font-bold text-sm flex items-center justify-center mx-auto mb-4 ring-4 ring-purple-50">3</div>
                    <h4 class="font-outfit font-bold text-slate-900 mb-1 text-sm">Booking & Bayar</h4>
                    <p class="text-slate-500 text-xs leading-relaxed">Lakukan booking dan selesaikan pembayaran aman yang diverifikasi server.</p>
                </div>

                <div class="bg-white p-6 rounded-2xl border border-slate-200 text-center shadow-sm">
                    <div class="w-10 h-10 rounded-full bg-amber-500 text-white font-bold text-sm flex items-center justify-center mx-auto mb-4 ring-4 ring-amber-50">4</div>
                    <h4 class="font-outfit font-bold text-slate-900 mb-1 text-sm">Ikuti Pelatihan</h4>
                    <p class="text-slate-500 text-xs leading-relaxed">Hadiri kelas, pelajari materi dari instruktur, dan catat presensi kehadiran.</p>
                </div>

                <div class="bg-white p-6 rounded-2xl border border-slate-200 text-center shadow-sm">
                    <div class="w-10 h-10 rounded-full bg-emerald-600 text-white font-bold text-sm flex items-center justify-center mx-auto mb-4 ring-4 ring-emerald-50">5</div>
                    <h4 class="font-outfit font-bold text-slate-900 mb-1 text-sm">Raih Sertifikat</h4>
                    <p class="text-slate-500 text-xs leading-relaxed">Dapatkan sertifikat resmi LPK sebagai bukti kompetensi kerja yang teruji.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- SECTION: PILIH BERDASARKAN KEBUTUHAN / PENDIDIKAN (PRD SECTION 2.3 & 6.2) -->
<section class="py-20 bg-white border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-16">
            <h2 class="text-xs font-bold uppercase tracking-wider text-brand-600 mb-2">Personalization</h2>
            <p class="font-outfit text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">Pilih Pelatihan Sesuai Jenjang & Tujuan</p>
            <p class="text-slate-500 text-sm mt-3">Program kami didesain relevan dengan latar belakang pendidikan dan kebutuhan dunia kerja Anda.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <a href="{{ route('public.courses') }}?level=Pemula" class="group p-6 rounded-2xl border border-slate-200 bg-gradient-to-b from-white to-slate-50/50 hover:border-brand-300 hover:shadow-lg transition flex flex-col justify-between">
                <div>
                    <span class="inline-block px-2.5 py-1 rounded-lg text-xs font-bold bg-blue-50 text-brand-700 mb-4">Lulusan SMA</span>
                    <h4 class="font-outfit text-lg font-bold text-slate-900 group-hover:text-brand-600 transition mb-2">Persiapan Kerja & Bisnis</h4>
                    <p class="text-xs text-slate-500 leading-relaxed">Kuasai skill praktis seperti administrasi, bahasa asing, dan pemasaran digital untuk segera memasuki dunia kerja.</p>
                </div>
                <div class="mt-6 flex items-center text-xs font-bold text-brand-600 gap-1.5">
                    <span>Lihat Rekomendasi</span>
                    <i class="fa-solid fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                </div>
            </a>

            <a href="{{ route('public.courses') }}?level=Menengah" class="group p-6 rounded-2xl border border-slate-200 bg-gradient-to-b from-white to-slate-50/50 hover:border-indigo-300 hover:shadow-lg transition flex flex-col justify-between">
                <div>
                    <span class="inline-block px-2.5 py-1 rounded-lg text-xs font-bold bg-indigo-50 text-indigo-700 mb-4">Lulusan SMK</span>
                    <h4 class="font-outfit text-lg font-bold text-slate-900 group-hover:text-indigo-600 transition mb-2">Peningkatan Kejuruan Teknik</h4>
                    <p class="text-xs text-slate-500 leading-relaxed">Perdalam keahlian otomotif, pengelasan, kelistrikan, hingga teknik informatika berstandar sertifikasi industri.</p>
                </div>
                <div class="mt-6 flex items-center text-xs font-bold text-indigo-600 gap-1.5">
                    <span>Lihat Rekomendasi</span>
                    <i class="fa-solid fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                </div>
            </a>

            <a href="{{ route('public.courses') }}?level=Mahir" class="group p-6 rounded-2xl border border-slate-200 bg-gradient-to-b from-white to-slate-50/50 hover:border-purple-300 hover:shadow-lg transition flex flex-col justify-between">
                <div>
                    <span class="inline-block px-2.5 py-1 rounded-lg text-xs font-bold bg-purple-50 text-purple-700 mb-4">Mahasiswa / Sarjana</span>
                    <h4 class="font-outfit text-lg font-bold text-slate-900 group-hover:text-purple-600 transition mb-2">Kompetensi Digital & Manajerial</h4>
                    <p class="text-xs text-slate-500 leading-relaxed">Tingkatkan daya saing karier dengan coding, data analysis, kepemimpinan, dan manajemen operasional.</p>
                </div>
                <div class="mt-6 flex items-center text-xs font-bold text-purple-600 gap-1.5">
                    <span>Lihat Rekomendasi</span>
                    <i class="fa-solid fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                </div>
            </a>

            <a href="{{ route('public.courses') }}" class="group p-6 rounded-2xl border border-slate-200 bg-gradient-to-b from-white to-slate-50/50 hover:border-emerald-300 hover:shadow-lg transition flex flex-col justify-between">
                <div>
                    <span class="inline-block px-2.5 py-1 rounded-lg text-xs font-bold bg-emerald-50 text-emerald-700 mb-4">Masyarakat Umum</span>
                    <h4 class="font-outfit text-lg font-bold text-slate-900 group-hover:text-emerald-600 transition mb-2">Wirausaha & Keterampilan</h4>
                    <p class="text-xs text-slate-500 leading-relaxed">Belajar keterampilan mandiri seperti tata boga, menjahit, tata rias, dan kerajinan untuk membuka usaha mandiri.</p>
                </div>
                <div class="mt-6 flex items-center text-xs font-bold text-emerald-600 gap-1.5">
                    <span>Lihat Rekomendasi</span>
                    <i class="fa-solid fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                </div>
            </a>
        </div>
    </div>
</section>

<!-- SECTION: KURSUS TERSEDIA (DARI DATABASE) -->
<section class="py-20 bg-slate-50 border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-12">
            <div>
                <h2 class="text-xs font-bold uppercase tracking-wider text-brand-600 mb-2">Program Pelatihan Pilihan</h2>
                <p class="font-outfit text-3xl font-extrabold text-slate-900 tracking-tight">Kursus Populer Tersedia</p>
                <p class="text-slate-500 text-sm mt-1">Daftar kelas pelatihan aktif yang siap diikuti di Kabupaten Indramayu.</p>
            </div>
            <a href="{{ route('public.courses') }}" class="mt-4 md:mt-0 inline-flex items-center gap-2 text-sm font-semibold text-brand-600 hover:text-brand-700">
                <span>Lihat Semua Kursus</span>
                <i class="fa-solid fa-arrow-right text-xs"></i>
            </a>
        </div>

        @if($featuredCourses->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($featuredCourses as $course)
                    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden hover:shadow-xl transition-all duration-300 flex flex-col justify-between group">
                        <div>
                            <!-- Header / Category & Level -->
                            <div class="p-5 pb-3">
                                <div class="flex items-center justify-between mb-3">
                                    <span class="px-2.5 py-1 rounded-lg text-xs font-semibold bg-brand-50 text-brand-700">
                                        {{ $course->category->name ?? 'Kejuruan' }}
                                    </span>
                                    <span class="text-xs font-medium text-slate-500 bg-slate-100 px-2 py-0.5 rounded">
                                        {{ $course->level }}
                                    </span>
                                </div>
                                <h3 class="font-outfit text-lg font-bold text-slate-900 group-hover:text-brand-600 transition line-clamp-2">
                                    {{ $course->title }}
                                </h3>
                                <p class="text-xs text-slate-500 mt-2 line-clamp-2">
                                    {{ $course->description ?? 'Pelatihan intensif dengan kurikulum industri dan praktik komprehensif.' }}
                                </p>
                            </div>

                            <!-- Meta details -->
                            <div class="px-5 py-3 border-t border-slate-100 text-xs text-slate-600 space-y-1.5 bg-slate-50/50">
                                <div class="flex items-center gap-2">
                                    <i class="fa-solid fa-building text-slate-400 w-4"></i>
                                    <span class="font-medium text-slate-800">{{ $course->lpk->name ?? 'Mitra LPK' }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <i class="fa-solid fa-clock text-slate-400 w-4"></i>
                                    <span>Durasi: {{ $course->duration_hours }} Jam Pelatihan</span>
                                </div>
                                @if($course->cert_type)
                                    <div class="flex items-center gap-2">
                                        <i class="fa-solid fa-certificate text-emerald-500 w-4"></i>
                                        <span>Sertifikat: {{ $course->cert_type }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Price & CTA -->
                        <div class="p-5 border-t border-slate-100 flex items-center justify-between bg-white">
                            <div>
                                <span class="text-[10px] uppercase font-semibold text-slate-400 block">Biaya Kursus</span>
                                <span class="font-outfit text-lg font-extrabold text-slate-900">
                                    Rp {{ number_format($course->price, 0, ',', '.') }}
                                </span>
                            </div>
                            <a href="{{ route('public.course.detail', $course->id) }}" class="px-4 py-2 rounded-xl text-xs font-bold text-white bg-slate-900 hover:bg-brand-600 transition shadow">
                                Detail Kursus
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="p-12 text-center bg-white rounded-2xl border border-slate-200">
                <i class="fa-solid fa-book-open text-4xl text-slate-300 mb-3"></i>
                <h4 class="text-base font-bold text-slate-700">Belum ada kursus aktif</h4>
                <p class="text-xs text-slate-500 mt-1">Kursus dari mitra LPK akan segera ditampilkan di sini.</p>
            </div>
        @endif
    </div>
</section>

<!-- SECTION: LPK TERSEDIA (PRD SECTION 6.2) -->
<section class="py-20 bg-white border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-12">
            <div>
                <h2 class="text-xs font-bold uppercase tracking-wider text-brand-600 mb-2">Mitra Terpercaya</h2>
                <p class="font-outfit text-3xl font-extrabold text-slate-900 tracking-tight">Lembaga Pelatihan Kerja (LPK) Resmi</p>
                <p class="text-slate-500 text-sm mt-1">LPK dengan status verifikasi legal dan kredibel di Kabupaten Indramayu.</p>
            </div>
            <a href="{{ route('public.lpks') }}" class="mt-4 md:mt-0 inline-flex items-center gap-2 text-sm font-semibold text-brand-600 hover:text-brand-700">
                <span>Lihat Semua LPK</span>
                <i class="fa-solid fa-arrow-right text-xs"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($lpks as $lpk)
                <div class="bg-white rounded-2xl border border-slate-200 p-6 hover:shadow-lg hover:border-slate-300 transition duration-300 flex flex-col justify-between">
                    <div>
                        <div class="flex items-start justify-between gap-3 mb-4">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-tr from-slate-800 to-indigo-900 text-white font-bold flex items-center justify-center text-lg shadow">
                                {{ strtoupper(substr($lpk->name, 0, 1)) }}
                            </div>
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold badge-verified">
                                <i class="fa-solid fa-circle-check text-[10px]"></i>
                                Terverifikasi
                            </span>
                        </div>

                        <h3 class="font-outfit text-lg font-bold text-slate-900 mb-1">{{ $lpk->name }}</h3>
                        <p class="text-xs text-slate-500 line-clamp-2 mb-4 leading-relaxed">
                            {{ $lpk->description ?? 'Lembaga Pelatihan Kerja terdaftar resmi yang menyediakan berbagai program pelatihan vokasi dan kompetensi kerja.' }}
                        </p>

                        <div class="space-y-1.5 text-xs text-slate-500 pt-2 border-t border-slate-100">
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-location-dot text-slate-400 w-4"></i>
                                <span class="truncate">{{ $lpk->address ?? 'Kabupaten Indramayu' }}</span>
                            </div>
                            @if($lpk->nib)
                                <div class="flex items-center gap-2">
                                    <i class="fa-solid fa-file-contract text-slate-400 w-4"></i>
                                    <span>NIB: {{ $lpk->nib }}</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between">
                        <span class="text-xs font-semibold text-brand-600 bg-brand-50 px-2.5 py-1 rounded-lg">
                            {{ $lpk->courses_count }} Kursus Tersedia
                        </span>
                        <a href="{{ route('public.lpk.detail', $lpk->id) }}" class="text-xs font-bold text-slate-700 hover:text-brand-600 flex items-center gap-1">
                            <span>Profil LPK</span>
                            <i class="fa-solid fa-chevron-right text-[10px]"></i>
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-3 p-10 text-center bg-slate-50 rounded-2xl">
                    <p class="text-slate-500 text-sm">Belum ada LPK yang terverifikasi.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

<!-- SECTION: UNTUK LPK (PRD SECTION 6.2) -->
<section class="py-20 bg-gradient-to-br from-slate-900 via-indigo-950 to-slate-900 text-white relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div class="space-y-6">
                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-indigo-500/20 text-indigo-300 border border-indigo-500/30">
                    <i class="fa-solid fa-building-columns"></i>
                    Solusi Terpadu Pengelola LPK
                </span>
                <h2 class="font-outfit text-3xl sm:text-4xl font-extrabold tracking-tight leading-tight">
                    Tingkatkan Jangkauan Siswa & Digitalisasi Operasional LPK Anda
                </h2>
                <p class="text-slate-300 text-sm sm:text-base leading-relaxed">
                    Tinggalkan pembukuan manual dan kesulitan menjaring siswa baru. Skilloka menyediakan dashboard terpadu bagi pengelola LPK di Kabupaten Indramayu untuk mengatur seluruh alur pelatihan dalam satu sistem.
                </p>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg bg-indigo-500/20 text-indigo-400 flex items-center justify-center flex-shrink-0 text-sm mt-0.5">
                            <i class="fa-solid fa-calendar-days"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-sm text-white">Kelola Jadwal & Kuota</h4>
                            <p class="text-xs text-slate-400">Atur batch kelas, hari, jam, dan batasan peserta.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg bg-indigo-500/20 text-indigo-400 flex items-center justify-center flex-shrink-0 text-sm mt-0.5">
                            <i class="fa-solid fa-user-check"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-sm text-white">Presensi / Absensi Sesi</h4>
                            <p class="text-xs text-slate-400">Rekap kehadiran siswa per pertemuan kelas.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg bg-indigo-500/20 text-indigo-400 flex items-center justify-center flex-shrink-0 text-sm mt-0.5">
                            <i class="fa-solid fa-certificate"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-sm text-white">Penerbitan Sertifikat</h4>
                            <p class="text-xs text-slate-400">Terbitkan sertifikat kelulusan terverifikasi otomatis.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg bg-indigo-500/20 text-indigo-400 flex items-center justify-center flex-shrink-0 text-sm mt-0.5">
                            <i class="fa-solid fa-credit-card"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-sm text-white">Monitoring Pembayaran</h4>
                            <p class="text-xs text-slate-400">Pantau transaksi pendaftaran secara transparan.</p>
                        </div>
                    </div>
                </div>

                <div class="pt-4 flex flex-wrap items-center gap-4">
                    <a href="{{ route('lpk.register') }}" class="px-6 py-3.5 rounded-xl font-bold text-sm bg-gradient-to-r from-brand-500 to-indigo-500 hover:from-brand-600 hover:to-indigo-600 text-white shadow-lg shadow-brand-500/25 transition">
                        Daftarkan LPK Anda Sekarang
                    </a>
                    <a href="{{ route('public.for_lpk') }}" class="px-6 py-3.5 rounded-xl font-bold text-sm text-slate-300 hover:text-white border border-slate-700 hover:bg-slate-800 transition">
                        Pelajari Selengkapnya
                    </a>
                </div>
            </div>

            <!-- Visual Card mockup -->
            <div class="p-8 rounded-3xl bg-slate-800/60 border border-slate-700/80 backdrop-blur-xl shadow-2xl space-y-5">
                <div class="flex items-center justify-between border-b border-slate-700 pb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-3 h-3 rounded-full bg-red-500"></div>
                        <div class="w-3 h-3 rounded-full bg-amber-500"></div>
                        <div class="w-3 h-3 rounded-full bg-emerald-500"></div>
                        <span class="text-xs text-slate-400 font-mono ml-2">Dashboard Operasional LPK</span>
                    </div>
                    <span class="text-xs font-semibold px-2 py-0.5 rounded bg-emerald-500/20 text-emerald-400">Online</span>
                </div>
                
                <div class="space-y-3">
                    <div class="p-4 rounded-xl bg-slate-900/80 border border-slate-700/50 flex items-center justify-between">
                        <div>
                            <div class="text-xs text-slate-400">Pendaftaran Terbaru</div>
                            <div class="text-sm font-bold text-white mt-0.5">Kelas Teknisi AC & Pendingin</div>
                        </div>
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-500/20 text-amber-300">Perlu Dicek</span>
                    </div>
                    <div class="p-4 rounded-xl bg-slate-900/80 border border-slate-700/50 flex items-center justify-between">
                        <div>
                            <div class="text-xs text-slate-400">Agenda Hari Ini</div>
                            <div class="text-sm font-bold text-white mt-0.5">Bahasa Jepang N4 - Sesi 12 (09:00 WIB)</div>
                        </div>
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-blue-500/20 text-blue-300">Siap Absen</span>
                    </div>
                    <div class="p-4 rounded-xl bg-slate-900/80 border border-slate-700/50 flex items-center justify-between">
                        <div>
                            <div class="text-xs text-slate-400">Sertifikat Siap Terbit</div>
                            <div class="text-sm font-bold text-white mt-0.5">8 Peserta Lulus Pelatihan Komputer</div>
                        </div>
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-500/20 text-emerald-300">Cetak Sertifikat</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- SECTION: FAQ (PRD SECTION 6.2) -->
<section class="py-20 bg-slate-50 border-b border-slate-200">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-xs font-bold uppercase tracking-wider text-brand-600 mb-2">Tanya Jawab</h2>
            <p class="font-outfit text-3xl font-extrabold text-slate-900 tracking-tight">Pertanyaan yang Sering Diajukan (FAQ)</p>
            <p class="text-slate-500 text-sm mt-2">Ketahui lebih banyak mengenai cara kerja dan layanan Skilloka.</p>
        </div>

        <div class="space-y-4" x-data="{ activeFaq: null }">
            @foreach($faqs as $index => $faq)
                <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm transition">
                    <button @click="activeFaq = (activeFaq === {{ $index }} ? null : {{ $index }})" class="w-full px-6 py-4 text-left font-outfit font-bold text-slate-900 flex items-center justify-between gap-4 hover:text-brand-600 transition">
                        <span class="text-base">{{ $faq['q'] }}</span>
                        <i class="fa-solid fa-chevron-down text-xs transition-transform duration-200" :class="activeFaq === {{ $index }} ? 'rotate-180 text-brand-600' : 'text-slate-400'"></i>
                    </button>
                    <div x-show="activeFaq === {{ $index }}" x-collapse class="px-6 pb-5 text-sm text-slate-600 leading-relaxed border-t border-slate-100 pt-3">
                        {{ $faq['a'] }}
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- CTA FINAL BANNER -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="p-8 sm:p-12 rounded-3xl bg-gradient-to-r from-brand-600 via-indigo-600 to-purple-600 text-white text-center shadow-xl">
            <h2 class="font-outfit text-2xl sm:text-4xl font-extrabold mb-4">Mulai Perjalanan Keahlian Anda Hari Ini</h2>
            <p class="text-brand-100 text-sm sm:text-base max-w-xl mx-auto mb-8 leading-relaxed">
                Jelajahi kursus terbaik di Kabupaten Indramayu atau daftarkan lembaga pelatihan Anda untuk menjangkau generasi siap kerja.
            </p>
            <div class="flex flex-wrap items-center justify-center gap-4">
                <a href="{{ route('public.courses') }}" class="shiny-btn shiny-btn-dark px-7 py-3.5 rounded-xl font-bold text-sm text-white shadow-lg">
                    <span>Cari Program Kursus</span>
                    <i class="fa-solid fa-arrow-right text-xs ml-2"></i>
                </a>
                <a href="{{ route('lpk.register') }}" class="shiny-btn shiny-btn-brand px-7 py-3.5 rounded-xl font-bold text-sm text-white shadow-lg">
                    <span>Daftar Sebagai Mitra LPK</span>
                    <i class="fa-solid fa-building-columns text-xs ml-2"></i>
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
