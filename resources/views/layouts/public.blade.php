<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Skilloka - Platform Pencarian, Booking & Manajemen Pelatihan LPK')</title>
    <meta name="description" content="@yield('meta_description', 'Skilloka adalah ekosistem digital pelatihan kerja resmi di Kabupaten Indramayu. Cari kursus bersertifikat, pilih jadwal fleksibel, dan kelola LPK secara terintegrasi.')">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN with Custom Configurations -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        outfit: ['Outfit', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
                            950: '#172554',
                        },
                        amberbrand: {
                            500: '#f59e0b',
                            600: '#d97706',
                        }
                    }
                }
            }
        }
    </script>

    <!-- FontAwesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/shiny-button.css') }}">

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
            color: #1e293b;
        }

        .font-outfit {
            font-family: 'Outfit', sans-serif;
        }

        .glass-nav {
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(226, 232, 240, 0.8);
        }

        .hero-pattern {
            background-color: #ffffff;
            background-image: radial-gradient(#e2e8f0 1.2px, transparent 1.2px), radial-gradient(#e2e8f0 1.2px, #ffffff 1.2px);
            background-size: 48px 48px;
            background-position: 0 0, 24px 24px;
        }

        .gradient-brand {
            background: linear-gradient(135deg, #2563eb 0%, #4f46e5 100%);
        }

        .gradient-brand-warm {
            background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
        }

        .badge-verified {
            background: #ecfdf5;
            color: #059669;
            border: 1px solid #a7f3d0;
        }
    </style>
    <!-- Shiny Button CSS -->
    <link rel="stylesheet" href="{{ asset('css/shiny-button.css') }}">
    @stack('styles')
</head>

<body class="flex flex-col min-h-screen antialiased selection:bg-brand-500 selection:text-white" x-data="{ mobileMenuOpen: false }">

    <!-- TOP ANNOUNCEMENT BANNER -->
    <div class="bg-slate-900 text-slate-300 text-xs py-2 px-4 border-b border-slate-800">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-500/20 text-emerald-400">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-ping"></span>
                    Aktif
                </span>
                <span>Wilayah Operasional Perdana: <strong>Kabupaten Indramayu & Sekitarnya</strong></span>
            </div>
            <div class="hidden md:flex items-center gap-4 text-slate-400">
                <a href="{{ route('public.for_lpk') }}" class="hover:text-white transition">Kemitraan LPK</a>
                <span>&bull;</span>
                <a href="{{ route('public.about') }}" class="hover:text-white transition">Pusat Bantuan</a>
                <span>&bull;</span>
                <span class="text-slate-300"><i class="fa-solid fa-phone mr-1 text-brand-400"></i> +62 895-4051-93300</span>
            </div>
        </div>
    </div>

    <!-- MAIN NAVBAR -->
    <header class="sticky top-0 z-50 glass-nav transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <!-- Brand Logo -->
                <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                    <div class="w-11 h-11 rounded-2xl bg-gradient-to-tr from-brand-600 via-indigo-600 to-purple-600 flex items-center justify-center text-white shadow-lg shadow-brand-500/25 group-hover:scale-105 transition-transform duration-300">
                        <i class="fa-solid fa-graduation-cap text-xl"></i>
                    </div>
                    <div>
                        <span class="font-outfit text-2xl font-extrabold tracking-tight text-slate-900">Skilloka<span class="text-brand-600">.</span></span>
                        <span class="block text-[10px] font-semibold uppercase tracking-wider text-slate-400 -mt-1">Pusat Pelatihan LPK</span>
                    </div>
                </a>

                <!-- Desktop Navigation Links -->
                <nav class="hidden lg:flex items-center gap-1">
                    <a href="{{ route('home') }}" class="px-4 py-2 rounded-xl text-sm font-semibold {{ request()->routeIs('home') ? 'text-brand-600 bg-brand-50' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/70' }} transition">
                        Beranda
                    </a>
                    <a href="{{ route('public.courses') }}" class="px-4 py-2 rounded-xl text-sm font-semibold {{ request()->routeIs('public.courses*') ? 'text-brand-600 bg-brand-50' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/70' }} transition">
                        Cari Kursus
                    </a>
                    <a href="{{ route('public.lpks') }}" class="px-4 py-2 rounded-xl text-sm font-semibold {{ request()->routeIs('public.lpks*') ? 'text-brand-600 bg-brand-50' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/70' }} transition">
                        Lembaga LPK
                    </a>
                    <a href="{{ route('public.for_lpk') }}" class="px-4 py-2 rounded-xl text-sm font-semibold {{ request()->routeIs('public.for_lpk') ? 'text-brand-600 bg-brand-50' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/70' }} transition">
                        Untuk Mitra LPK
                    </a>
                    <a href="{{ route('public.about') }}" class="px-4 py-2 rounded-xl text-sm font-semibold {{ request()->routeIs('public.about') ? 'text-brand-600 bg-brand-50' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/70' }} transition">
                        Tentang Kami
                    </a>
                </nav>

                <!-- Actions -->
                <div class="hidden sm:flex items-center gap-3">
                    @auth
                        @if(auth()->user()->hasRole('super_admin'))
                            <a href="{{ route('super.dashboard') }}" class="shiny-btn shiny-btn-dark px-4 py-2.5 rounded-xl text-sm font-semibold text-white shadow">
                                <i class="fa-solid fa-gauge-high mr-1.5"></i> Dashboard Super Admin
                            </a>
                        @elseif(auth()->user()->hasRole('admin_lpk') || auth()->user()->hasRole('admin'))
                            <a href="{{ route('admin.dashboard') }}" class="shiny-btn shiny-btn-brand px-4 py-2.5 rounded-xl text-sm font-semibold text-white shadow">
                                <i class="fa-solid fa-gauge mr-1.5"></i> Dashboard LPK
                            </a>
                        @endif
                    @else
                        <a href="{{ route('admin.login') }}" class="px-4 py-2.5 rounded-xl text-sm font-semibold text-slate-700 hover:text-brand-600 hover:bg-slate-100/80 transition">
                            <i class="fa-solid fa-arrow-right-to-bracket mr-1.5 text-slate-400"></i> Masuk
                        </a>
                        <a href="{{ route('lpk.register') }}" class="shiny-btn shiny-btn-brand px-5 py-2.5 rounded-full text-xs font-bold shadow-md shadow-brand-500/25">
                            <i class="fa-solid fa-building-columns mr-1.5"></i> Daftarkan LPK
                        </a>
                    @endauth
                </div>

                <!-- Mobile Menu Button -->
                <div class="flex items-center lg:hidden">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" type="button" class="p-2.5 rounded-xl text-slate-600 hover:text-slate-900 hover:bg-slate-100 focus:outline-none" aria-label="Toggle navigation">
                        <i :class="mobileMenuOpen ? 'fa-solid fa-xmark text-2xl' : 'fa-solid fa-bars text-xl'"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Drawer Menu -->
        <div x-show="mobileMenuOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-4" class="lg:hidden border-b border-slate-200 bg-white px-4 pt-2 pb-6 space-y-2 shadow-xl">
            <a href="{{ route('home') }}" class="block px-4 py-2.5 rounded-xl text-base font-semibold {{ request()->routeIs('home') ? 'text-brand-600 bg-brand-50' : 'text-slate-700 hover:bg-slate-50' }}">Beranda</a>
            <a href="{{ route('public.courses') }}" class="block px-4 py-2.5 rounded-xl text-base font-semibold {{ request()->routeIs('public.courses*') ? 'text-brand-600 bg-brand-50' : 'text-slate-700 hover:bg-slate-50' }}">Cari Kursus</a>
            <a href="{{ route('public.lpks') }}" class="block px-4 py-2.5 rounded-xl text-base font-semibold {{ request()->routeIs('public.lpks*') ? 'text-brand-600 bg-brand-50' : 'text-slate-700 hover:bg-slate-50' }}">Lembaga LPK</a>
            <a href="{{ route('public.for_lpk') }}" class="block px-4 py-2.5 rounded-xl text-base font-semibold {{ request()->routeIs('public.for_lpk') ? 'text-brand-600 bg-brand-50' : 'text-slate-700 hover:bg-slate-50' }}">Untuk Mitra LPK</a>
            <a href="{{ route('public.about') }}" class="block px-4 py-2.5 rounded-xl text-base font-semibold {{ request()->routeIs('public.about') ? 'text-brand-600 bg-brand-50' : 'text-slate-700 hover:bg-slate-50' }}">Tentang Kami</a>
            
            <div class="pt-4 border-t border-slate-100 flex flex-col gap-2">
                @auth
                    @if(auth()->user()->hasRole('super_admin'))
                        <a href="{{ route('super.dashboard') }}" class="w-full text-center px-4 py-3 rounded-xl text-sm font-semibold text-white bg-slate-900 shadow">Dashboard Super Admin</a>
                    @else
                        <a href="{{ route('admin.dashboard') }}" class="w-full text-center px-4 py-3 rounded-xl text-sm font-semibold text-white bg-slate-900 shadow">Dashboard Admin LPK</a>
                    @endif
                @else
                    <a href="{{ route('admin.login') }}" class="w-full text-center px-4 py-2.5 rounded-xl text-sm font-semibold text-slate-700 border border-slate-200 hover:bg-slate-50">Masuk ke Dashboard</a>
                    <a href="{{ route('lpk.register') }}" class="w-full text-center px-4 py-3 rounded-xl text-sm font-semibold text-white bg-gradient-to-r from-brand-600 to-indigo-600 shadow">Daftarkan LPK Sekarang</a>
                @endauth
            </div>
        </div>
    </header>

    <!-- CONTENT BODY -->
    <main class="flex-grow">
        @if(session('success'))
            <div class="max-w-7xl mx-auto px-4 mt-6">
                <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-circle-check text-emerald-500 text-xl"></i>
                        <p class="text-sm font-medium">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="max-w-7xl mx-auto px-4 mt-6">
                <div class="p-4 rounded-2xl bg-red-50 border border-red-200 text-red-800 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-circle-exclamation text-red-500 text-xl"></i>
                        <p class="text-sm font-medium">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- FOOTER -->
    <footer class="bg-slate-900 text-slate-300 border-t border-slate-800 pt-16 pb-12 mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-10 pb-12 border-b border-slate-800">
                <!-- Brand col -->
                <div class="lg:col-span-2 space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-brand-500 to-indigo-500 flex items-center justify-center text-white font-bold">
                            <i class="fa-solid fa-graduation-cap text-lg"></i>
                        </div>
                        <span class="font-outfit text-2xl font-bold text-white tracking-tight">Skilloka<span class="text-brand-400">.</span></span>
                    </div>
                    <p class="text-slate-400 text-sm leading-relaxed max-w-sm">
                        Platform digital pencarian, booking, pelatihan, dan manajemen Lembaga Pelatihan Kerja (LPK) modern. Menghubungkan potensi SDM Kabupaten Indramayu dengan kompetensi industri nyata.
                    </p>
                    <div class="flex items-center gap-3 pt-2">
                        <a href="#" class="w-9 h-9 rounded-lg bg-slate-800 hover:bg-brand-600 text-slate-300 hover:text-white flex items-center justify-center transition" aria-label="Instagram">
                            <i class="fa-brands fa-instagram"></i>
                        </a>
                        <a href="#" class="w-9 h-9 rounded-lg bg-slate-800 hover:bg-brand-600 text-slate-300 hover:text-white flex items-center justify-center transition" aria-label="Facebook">
                            <i class="fa-brands fa-facebook-f"></i>
                        </a>
                        <a href="https://wa.me/62895405193300" target="_blank" class="w-9 h-9 rounded-lg bg-slate-800 hover:bg-emerald-600 text-slate-300 hover:text-white flex items-center justify-center transition" aria-label="WhatsApp">
                            <i class="fa-brands fa-whatsapp"></i>
                        </a>
                    </div>
                </div>

                <!-- Col 2: Pelatihan -->
                <div>
                    <h3 class="font-outfit font-semibold text-white text-base mb-4">Jelajahi</h3>
                    <ul class="space-y-2.5 text-sm text-slate-400">
                        <li><a href="{{ route('public.courses') }}" class="hover:text-white transition">Katalog Semua Kursus</a></li>
                        <li><a href="{{ route('public.courses', ['level' => 'Pemula']) }}" class="hover:text-white transition">Kursus Tingkat Pemula</a></li>
                        <li><a href="{{ route('public.courses', ['level' => 'Menengah']) }}" class="hover:text-white transition">Kursus Tingkat Lanjut</a></li>
                        <li><a href="{{ route('public.lpks') }}" class="hover:text-white transition">Daftar LPK Resmi</a></li>
                    </ul>
                </div>

                <!-- Col 3: Untuk Lembaga -->
                <div>
                    <h3 class="font-outfit font-semibold text-white text-base mb-4">Mitra LPK</h3>
                    <ul class="space-y-2.5 text-sm text-slate-400">
                        <li><a href="{{ route('public.for_lpk') }}" class="hover:text-white transition">Manfaat Kemitraan</a></li>
                        <li><a href="{{ route('lpk.register') }}" class="hover:text-white transition">Daftarkan LPK Anda</a></li>
                        <li><a href="{{ route('admin.login') }}" class="hover:text-white transition">Portal Login Admin LPK</a></li>
                        <li><a href="{{ route('public.about') }}" class="hover:text-white transition">Panduan Verifikasi</a></li>
                    </ul>
                </div>

                <!-- Col 4: Kontak & Wilayah -->
                <div>
                    <h3 class="font-outfit font-semibold text-white text-base mb-4">Kantor & Layanan</h3>
                    <p class="text-sm text-slate-400 leading-relaxed mb-3">
                        <i class="fa-solid fa-location-dot text-brand-400 mr-2"></i> Kabupaten Indramayu, Jawa Barat, Indonesia
                    </p>
                    <p class="text-sm text-slate-400 mb-2">
                        <i class="fa-solid fa-envelope text-brand-400 mr-2"></i> info@skilloka.my.id
                    </p>
                    <p class="text-sm text-slate-400">
                        <i class="fa-solid fa-headset text-brand-400 mr-2"></i> 0895-4051-93300
                    </p>
                </div>
            </div>

            <div class="pt-8 flex flex-col sm:flex-row items-center justify-between text-xs text-slate-500 gap-4">
                <p>&copy; {{ date('Y') }} Skilloka Platform. Hak Cipta Dilindungi Undang-Undang.</p>
                <div class="flex items-center gap-6">
                    <a href="{{ route('public.about') }}" class="hover:text-slate-400 transition">Kebijakan Privasi</a>
                    <a href="{{ route('public.about') }}" class="hover:text-slate-400 transition">Syarat & Ketentuan</a>
                    <a href="{{ route('admin.login') }}" class="text-slate-400 hover:text-white transition">Portal Super Admin</a>
                </div>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
