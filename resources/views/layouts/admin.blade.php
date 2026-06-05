<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Skilloka Dashboard</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
            margin: 0;
            overflow-x: hidden;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        .font-outfit {
            font-family: 'Outfit', sans-serif;
        }

        /* Sidebar Styling */
        .sidebar {
            background: linear-gradient(160deg, #1e1b4b 0%, #312e81 100%);
            color: white;
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            width: 18rem;
            /* w-72 */
            z-index: 50;
            overflow-y: auto;
            overflow-x: hidden;
        }

        .sidebar::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='%23ffffff' fill-opacity='0.03' fill-rule='evenodd'/%3E%3C/svg%3E");
            pointer-events: none;
        }

        .logo-box {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 24px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            position: relative;
            z-index: 10;
        }

        .logo-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
            box-shadow: 0 4px 12px rgba(249, 115, 22, 0.3);
            color: white;
        }

        .logo-text {
            font-size: 22px;
            font-weight: 700;
            background: linear-gradient(to right, #ffffff, #cbd5e1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .menu-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            margin: 4px 12px;
            border-radius: 10px;
            color: #9ca3af;
            font-weight: 500;
            transition: all 0.2s;
            text-decoration: none;
            position: relative;
            z-index: 10;
        }

        .menu-item:hover,
        .menu-item.active {
            background: rgba(255, 255, 255, 0.1);
            color: #ffffff;
        }

        .menu-item.active {
            background: rgba(16, 185, 129, 0.1);
            border-left: 3px solid #10b981;
        }

        /* Content Wrapper */
        .main-content {
            margin-left: 18rem;
            /* Samakan dengan lebar sidebar */
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .glass-header {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid #e2e8f0;
            position: sticky;
            top: 0;
            z-index: 40;
            padding: 1rem 2rem;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 5px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }
    </style>
</head>

<body class="antialiased text-gray-800">

    @php
        $user = auth()->user();
        $isSuperAdmin = false;
        $isAdminLpk = false;

        if ($user) {
            if ($user->email === 'admin@skilloka.com' || (method_exists($user, 'hasRole') && $user->hasRole('super_admin'))) {
                $isSuperAdmin = true;
            } elseif (method_exists($user, 'hasRole') && ($user->hasRole('admin_lpk') || $user->hasRole('admin'))) {
                $isAdminLpk = true;
            }
        }
        $currentRoute = request()->route() ? request()->route()->getName() : '';
    @endphp

    <aside class="sidebar">
        <div class="logo-box">
            <div class="logo-icon">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                    </path>
                </svg>
            </div>
            <div class="logo-text font-outfit">{{ $isSuperAdmin ? 'GOD VIEW' : 'Skilloka' }}</div>
        </div>

        <nav class="mt-6 px-2">
            @if($isAdminLpk)
                <div class="px-4 mb-2 text-xs font-semibold text-indigo-300 uppercase tracking-wider">Menu LPK</div>
                <a href="{{ route('admin.dashboard') }}"
                    class="menu-item {{ str_contains($currentRoute, 'dashboard') ? 'active' : '' }}">
                    Dashboard
                </a>
                <a href="{{ route('admin.profile') }}"
                    class="menu-item {{ str_contains($currentRoute, 'profile') ? 'active' : '' }}">
                    LPK Profile
                </a>

                <a href="{{ route('admin.banners.index') }}"
                    class="menu-item {{ str_contains($currentRoute, 'banners') ? 'active' : '' }}">
                    Banner Promosi
                </a>

                <a href="{{ route('admin.courses.index') }}"
                    class="menu-item {{ str_contains($currentRoute, 'courses') ? 'active' : '' }}">
                    Courses
                </a>
                <a href="{{ route('admin.students.index') }}"
                    class="menu-item {{ str_contains($currentRoute, 'students') ? 'active' : '' }}">
                    Students
                </a>
                <a href="{{ route('admin.bookings.index') }}"
                    class="menu-item {{ str_contains($currentRoute, 'bookings') ? 'active' : '' }}">
                    Bookings
                </a>
                <a href="{{ route('admin.course-schedules.index') }}"
                    class="menu-item {{ str_contains($currentRoute, 'schedule') ? 'active' : '' }}">
                    Schedule
                </a>
            @elseif($isSuperAdmin)
                <div class="px-4 mb-2 text-xs font-semibold text-emerald-400 uppercase tracking-wider">Menu Super Admin
                </div>
                <a href="{{ route('super.dashboard') }}"
                    class="menu-item {{ str_contains($currentRoute, 'dashboard') ? 'active' : '' }}">
                    Dashboard
                </a>
                <a href="{{ route('super.tenants') }}"
                    class="menu-item {{ str_contains($currentRoute, 'tenant') ? 'active' : '' }}">
                    LPK Centers
                </a>
                <a href="{{ route('super.verifications') }}"
                    class="menu-item {{ str_contains($currentRoute, 'verification') ? 'active' : '' }}">
                    Verifications
                </a>
            @endif

            <div class="mt-8 px-4 mb-2 text-xs font-semibold text-indigo-300 uppercase tracking-wider">Account</div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="menu-item w-full text-left hover:text-red-400">
                    Logout
                </button>
            </form>
        </nav>
    </aside>

    <div class="main-content">
        <header class="glass-header flex items-center justify-between">
            <h1 class="text-xl font-bold text-gray-800">@yield('title')</h1>

            <div class="flex items-center gap-4">
                <div class="flex items-center gap-3">
                    <div class="text-right hidden sm:block">
                        <div class="text-sm font-semibold text-gray-800">{{ auth()->user()->name ?? 'Guest' }}</div>
                        <div class="text-xs text-gray-500">{{ $isSuperAdmin ? 'Super Admin' : 'Admin LPK' }}</div>
                    </div>
                    <div
                        class="w-10 h-10 rounded-full bg-indigo-600 flex items-center justify-center text-white font-bold">
                        {{ substr(auth()->user()->name ?? 'A', 0, 1) }}
                    </div>
                </div>
            </div>
        </header>

        <main class="p-6">
            <div class="max-w-7xl mx-auto">
                @yield('content')
            </div>
        </main>
    </div>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

</body>

</html>