<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Skilloka - Platform Pencarian, Booking & Manajemen Pelatihan LPK')</title>
    <meta name="description" content="@yield('meta_description', 'Skilloka adalah ekosistem digital pelatihan kerja resmi di Kabupaten Indramayu. Cari kursus bersertifikat, pilih jadwal fleksibel, dan kelola LPK secara terintegrasi.')">

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="alternate icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

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
            background: rgba(255, 255, 255, 0.88);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(226, 232, 240, 0.8);
        }

        .hero-aura-canvas {
            background-color: #fbfcfe;
            background-image: radial-gradient(rgba(148, 163, 184, 0.18) 1.2px, transparent 1.2px);
            background-size: 36px 36px;
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

        /* ===== HERO ENTRANCE ANIMATIONS ===== */
        @keyframes fadeSlideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeSlideDown {
            from { opacity: 0; transform: translateY(-12px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes shimmerGradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .hero-entrance { opacity: 0; animation: fadeSlideUp 0.7s ease-out forwards; }
        .hero-entrance-d1 { animation-delay: 0.08s; }
        .hero-entrance-d2 { animation-delay: 0.18s; }
        .hero-entrance-d3 { animation-delay: 0.3s; }
        .hero-entrance-d4 { animation-delay: 0.42s; }
        .hero-entrance-d5 { animation-delay: 0.55s; }


        .gradient-shimmer {
            background-size: 250% 250%;
            animation: shimmerGradient 7s ease-in-out infinite;
        }

        /* ===== FLUID ROTATING HEADLINE ===== */
        .rotating-word-container {
            display: inline-flex;
            align-items: baseline;
            position: relative;
            overflow: hidden;
            vertical-align: bottom;
            min-height: 1.25em;
        }

        .rotating-word {
            display: inline-block;
            transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1), opacity 0.6s ease, filter 0.6s ease;
            will-change: transform, opacity, filter;
        }

        .rotating-word.is-exiting {
            transform: translateY(-110%);
            opacity: 0;
            filter: blur(4px);
        }

        .rotating-word.is-entering {
            transform: translateY(110%);
            opacity: 0;
            filter: blur(4px);
        }

        .rotating-word.is-active {
            transform: translateY(0);
            opacity: 1;
            filter: blur(0px);
        }

        /* ===== SKILLOKA AMBIENT AURA GRADING (BESPOKE & FLUID) ===== */
        @keyframes skillokaAuraBreath {
            0%, 100% {
                transform: translate(-50%, -50%) scale(1);
                opacity: 0.85;
            }
            50% {
                transform: translate(-50%, -50%) scale(1.12);
                opacity: 1;
            }
        }

        @keyframes skillokaAuraDrift {
            0%, 100% {
                transform: translate(-50%, -50%) rotate(0deg) scale(1);
            }
            50% {
                transform: translate(-48%, -52%) rotate(4deg) scale(1.06);
            }
        }

        .skilloka-aura-core {
            position: absolute;
            top: 48%;
            left: 50%;
            width: 820px;
            max-width: 95vw;
            height: 480px;
            transform: translate(-50%, -50%);
            background: radial-gradient(ellipse 65% 55% at 50% 50%, rgba(37, 99, 235, 0.18) 0%, rgba(99, 102, 241, 0.13) 38%, rgba(139, 92, 246, 0.08) 65%, transparent 85%);
            filter: blur(60px);
            pointer-events-none;
            z-index: 1;
            animation: skillokaAuraBreath 9s ease-in-out infinite;
        }

        .skilloka-aura-ambient {
            position: absolute;
            top: 52%;
            left: 50%;
            width: 1200px;
            max-width: 98vw;
            height: 600px;
            transform: translate(-50%, -50%);
            background: radial-gradient(ellipse 70% 55% at 50% 50%, rgba(56, 189, 248, 0.14) 0%, rgba(99, 102, 241, 0.09) 45%, rgba(244, 247, 254, 0) 80%);
            filter: blur(90px);
            pointer-events-none;
            z-index: 0;
            animation: skillokaAuraDrift 16s ease-in-out infinite alternate;
        }

        .skilloka-aura-warm {
            position: absolute;
            top: 25%;
            right: 15%;
            width: 380px;
            height: 380px;
            background: radial-gradient(circle, rgba(249, 115, 22, 0.08) 0%, rgba(251, 146, 60, 0.03) 50%, transparent 75%);
            filter: blur(50px);
            pointer-events-none;
            z-index: 0;
        }

        /* ===== LPK CARD ENHANCEMENTS ===== */
        .lpk-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease, border-color 0.3s ease;
        }
        .lpk-card:hover {
            transform: translateY(-4px);
        }
        .lpk-card .lpk-accent {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            border-radius: 16px 16px 0 0;
        }

        /* ===== METRIC CARD 3D TILT ===== */
        .metric-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .metric-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 24px -4px rgba(0,0,0,0.08);
        }

        /* ===== HARDWARE-ACCELERATED CARD INTERACTIONS ===== */
        .card-interactive {
            transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.25s cubic-bezier(0.16, 1, 0.3, 1), border-color 0.25s ease;
            will-change: transform;
            backface-visibility: hidden;
            transform: translateZ(0);
        }
        .card-interactive:hover {
            transform: translateY(-4px);
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
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-md text-[10px] font-bold bg-emerald-500/15 text-emerald-300 border border-emerald-500/25">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
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
                <a href="{{ route('home') }}" class="group transition-transform duration-300 hover:scale-[1.02]">
                    <x-skilloka-logo class="h-10" :withSubtitle="true" />
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
                    <button @click="mobileMenuOpen = !mobileMenuOpen" type="button" class="p-2.5 rounded-xl text-slate-700 hover:text-slate-900 hover:bg-slate-100 focus:outline-none" aria-label="Toggle navigation">
                        <i :class="mobileMenuOpen ? 'fa-solid fa-xmark text-2xl' : 'fa-solid fa-bars text-xl'"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Drawer Menu -->
        <div x-show="mobileMenuOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-4" class="lg:hidden border-b border-slate-200 bg-white/95 backdrop-blur-xl px-4 pt-2 pb-6 space-y-2 shadow-xl">
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
    <footer class="bg-slate-900 text-slate-300 border-t border-slate-800 pt-16 pb-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-10 pb-12 border-b border-slate-800">
                <!-- Brand col -->
                <div class="lg:col-span-2 space-y-4">
                    <x-skilloka-logo class="h-9" :isDark="true" textColor="text-white" :withSubtitle="true" />
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

    <!-- SKILLOKA 3D CHATBOT ASSISTANT WIDGET -->
    <x-chatbot-widget />

    @stack('scripts')
</body>
</html>
