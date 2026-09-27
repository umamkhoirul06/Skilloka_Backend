<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Super Admin') - Skilloka Platform Control</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Outfit:wght@500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/shiny-button.css') }}">
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

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        * {
            font-family: 'Inter', sans-serif;
        }

        h1, h2, h3, h4, h5, h6, .font-outfit {
            font-family: 'Outfit', sans-serif;
        }

        .sidebar {
            background: #090d16;
            color: #e2e8f0;
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            width: 17.5rem;
            z-index: 50;
            overflow-y: auto;
            border-right: 1px solid #1e293b;
            transition: transform 0.3s ease;
        }

        .menu-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 16px;
            margin: 2px 12px;
            border-radius: 10px;
            color: #94a3b8;
            font-weight: 500;
            font-size: 13.5px;
            transition: all 0.15s ease;
            text-decoration: none;
        }

        .menu-item:hover {
            background: rgba(255, 255, 255, 0.06);
            color: #ffffff;
        }

        .menu-item.active {
            background: #10b981;
            color: #ffffff;
            font-weight: 600;
            box-shadow: 0 4px 14px rgba(16, 185, 129, 0.35);
        }

        .main-wrapper {
            margin-left: 17.5rem;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background-color: #f8fafc;
            transition: margin-left 0.3s ease;
        }

        @media (max-width: 1024px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.open {
                transform: translateX(0);
            }
            .main-wrapper {
                margin-left: 0;
            }
        }

        .top-navbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(8px);
            border-bottom: 1px solid #e2e8f0;
            position: sticky;
            top: 0;
            z-index: 40;
            padding: 0.85rem 1.75rem;
        }
    </style>
</head>

<body class="antialiased bg-slate-50 text-slate-800">

    @php
        $user = auth()->user();
        $currentRoute = request()->route() ? request()->route()->getName() : '';
        $pendingVerifCount = \App\Models\Lpk::where('status_verifikasi', 'pending')->count();
    @endphp

    <!-- MOBILE OVERLAY BACKDROP -->
    <div id="sidebarBackdrop" onclick="toggleSidebar()" class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs z-40 hidden lg:hidden"></div>

    <!-- SIDEBAR -->
    <aside id="mainSidebar" class="sidebar">
        <!-- Logo Header -->
        <div class="p-5 border-b border-slate-800 flex items-center justify-between">
            <a href="{{ route('super.dashboard') }}" class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-emerald-500 to-teal-600 flex items-center justify-center text-white shadow-md shadow-emerald-500/30">
                    <i class="fa-solid fa-crown text-base"></i>
                </div>
                <div>
                    <span class="font-outfit text-lg font-bold text-white tracking-tight">SKILLOKA</span>
                    <span class="block text-[10px] font-semibold text-emerald-400 uppercase tracking-wider">Super Administrator</span>
                </div>
            </a>
            <button onclick="toggleSidebar()" class="lg:hidden text-slate-400 hover:text-white p-1">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <!-- Super Admin Status Badge -->
        <div class="p-3 mx-3 my-3 rounded-xl bg-emerald-950/40 border border-emerald-800/40">
            <div class="flex items-center gap-2.5">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span class="text-xs font-bold text-emerald-300">God Mode Active</span>
                <span class="ml-auto text-[10px] font-semibold px-2 py-0.5 rounded-md bg-emerald-500/20 text-emerald-200">
                    v3.0
                </span>
            </div>
        </div>

        <!-- Navigation Menu -->
        <nav class="py-2">
            <div class="px-5 mb-1.5 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Platform Monitoring</div>

            <a href="{{ route('super.dashboard') }}"
                class="menu-item {{ $currentRoute == 'super.dashboard' ? 'active' : '' }}">
                <i class="fa-solid fa-chart-line w-4 text-center"></i>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('super.verifications') }}"
                class="menu-item {{ str_contains($currentRoute, 'verification') ? 'active' : '' }}">
                <i class="fa-solid fa-shield-halved w-4 text-center"></i>
                <span class="flex-1">Verifikasi LPK</span>
                @if($pendingVerifCount > 0)
                    <span class="text-[10px] font-black px-2 py-0.5 rounded-full bg-amber-500 text-slate-900 shadow-sm">
                        {{ $pendingVerifCount }}
                    </span>
                @endif
            </a>

            <a href="{{ route('super.tenants') }}"
                class="menu-item {{ str_contains($currentRoute, 'tenant') ? 'active' : '' }}">
                <i class="fa-solid fa-building-columns w-4 text-center"></i>
                <span>Mitra LPK</span>
            </a>

            <div class="px-5 mt-4 mb-1.5 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Master Data & User</div>

            <a href="{{ route('super.categories.index') }}"
                class="menu-item {{ str_contains($currentRoute, 'categories') ? 'active' : '' }}">
                <i class="fa-solid fa-shapes w-4 text-center"></i>
                <span>Kategori Kursus</span>
            </a>

            <a href="{{ route('super.users') }}"
                class="menu-item {{ str_contains($currentRoute, 'users') ? 'active' : '' }}">
                <i class="fa-solid fa-users w-4 text-center"></i>
                <span>Manajemen User</span>
            </a>

            <div class="px-5 mt-4 mb-1.5 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Keuangan & Sistem</div>

            <a href="{{ route('super.finance') }}"
                class="menu-item {{ str_contains($currentRoute, 'finance') ? 'active' : '' }}">
                <i class="fa-solid fa-sack-dollar w-4 text-center"></i>
                <span>Keuangan Platform</span>
            </a>

            <a href="{{ route('super.logs') }}"
                class="menu-item {{ str_contains($currentRoute, 'logs') ? 'active' : '' }}">
                <i class="fa-solid fa-clock-rotate-left w-4 text-center"></i>
                <span>Audit & Log Sistem</span>
            </a>

            <a href="{{ route('super.settings') }}"
                class="menu-item {{ str_contains($currentRoute, 'settings') ? 'active' : '' }}">
                <i class="fa-solid fa-sliders w-4 text-center"></i>
                <span>Pengaturan Sistem</span>
            </a>

            <!-- Quick Public Link -->
            <div class="px-4 mt-6">
                <a href="{{ route('home') }}" target="_blank"
                   class="flex items-center justify-center gap-2 w-full py-2.5 px-3 rounded-xl bg-slate-800 hover:bg-slate-700 text-xs font-bold text-emerald-300 transition-colors">
                    <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                    <span>Kunjungi Web Publik</span>
                </a>
            </div>

            <!-- Logout Section -->
            <div class="p-4 mt-4 border-t border-slate-800">
                <form method="POST" action="{{ route('logout') }}" onsubmit="return confirm('Apakah Anda yakin ingin keluar dari portal Super Admin?')">
                    @csrf
                    <button type="submit" class="flex items-center gap-3 w-full px-3 py-2 text-xs font-semibold text-rose-400 hover:bg-rose-500/10 rounded-lg transition-colors">
                        <i class="fa-solid fa-right-from-bracket"></i>
                        <span>Keluar (Logout)</span>
                    </button>
                </form>
            </div>
        </nav>
    </aside>

    <!-- MAIN CONTENT AREA -->
    <div class="main-wrapper">
        <!-- TOPBAR -->
        <header class="top-navbar flex items-center justify-between">
            <div class="flex items-center gap-3">
                <button onclick="toggleSidebar()" class="lg:hidden w-9 h-9 rounded-lg border border-slate-200 flex items-center justify-center text-slate-600 hover:bg-slate-100">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <div>
                    <h1 class="font-outfit text-lg sm:text-xl font-bold text-slate-900 tracking-tight">@yield('title', 'Super Admin')</h1>
                    <div class="text-[11px] text-slate-400 font-medium">Kabupaten Indramayu &bull; Panel Pengawasan Platform Terpusat</div>
                </div>
            </div>

            <div class="flex items-center gap-3 sm:gap-4">
                <a href="{{ route('home') }}" target="_blank" class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 text-xs font-semibold text-slate-600 transition-colors">
                    <i class="fa-solid fa-globe text-slate-400"></i>
                    <span>Lihat Web</span>
                </a>

                <div class="h-6 w-px bg-slate-200 hidden sm:block"></div>

                <!-- User Dropdown Profile Pill -->
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-xl bg-emerald-600 text-white font-bold flex items-center justify-center text-xs shadow-sm">
                        {{ strtoupper(substr($user->name ?? 'S', 0, 1)) }}
                    </div>
                    <div class="hidden md:block text-left">
                        <div class="text-xs font-bold text-slate-800 leading-tight">{{ $user->name ?? 'Super Admin' }}</div>
                        <div class="text-[11px] text-emerald-600 font-semibold">Master Platform</div>
                    </div>
                </div>
            </div>
        </header>

        <!-- MAIN VIEW CONTAINER -->
        <main class="p-4 sm:p-6 lg:p-8 flex-1">
            <div class="max-w-7xl mx-auto">
                <!-- Session Alerts -->
                @if(session('success'))
                    <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 flex items-center justify-between gap-3 text-emerald-900 text-sm">
                        <div class="flex items-center gap-2.5">
                            <i class="fa-solid fa-circle-check text-emerald-600 text-base"></i>
                            <span class="font-medium">{{ session('success') }}</span>
                        </div>
                        <button onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-800 text-xs font-bold">&times;</button>
                    </div>
                @endif

                @if(session('error') || $errors->any())
                    <div class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-200 flex items-center justify-between gap-3 text-rose-900 text-sm">
                        <div class="flex items-center gap-2.5">
                            <i class="fa-solid fa-circle-exclamation text-rose-600 text-base"></i>
                            <span class="font-medium">{{ session('error') ?? $errors->first() }}</span>
                        </div>
                        <button onclick="this.parentElement.remove()" class="text-rose-600 hover:text-rose-800 text-xs font-bold">&times;</button>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>

        <!-- FOOTER -->
        <footer class="py-4 px-6 border-t border-slate-200 bg-white text-xs text-slate-500 flex flex-col sm:flex-row items-center justify-between gap-2">
            <div>&copy; {{ date('Y') }} Skilloka Platform &bull; Super Admin Master Control.</div>
            <div class="text-slate-400">Pilot Wilayah: Kabupaten Indramayu</div>
        </footer>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('mainSidebar');
            const backdrop = document.getElementById('sidebarBackdrop');
            sidebar.classList.toggle('open');
            backdrop.classList.toggle('hidden');
        }
    </script>
</body>

</html>