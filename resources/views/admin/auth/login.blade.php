<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Skilloka Admin - Platform manajemen LPK dan pelatihan terbaik di Indonesia">
    <title>Login - Skilloka Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%);
            min-height: 100vh;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .gradient-text {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .input-field {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }

        .input-field:focus {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(102, 126, 234, 0.6);
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.15);
        }

        .btn-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 40px rgba(102, 126, 234, 0.4);
        }

        .btn-gradient::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }

        .btn-gradient:hover::before {
            left: 100%;
        }

        .floating-shapes div {
            position: absolute;
            border-radius: 50%;
            filter: blur(60px);
            opacity: 0.4;
            animation: float 15s infinite ease-in-out;
        }

        .floating-shapes .shape-1 {
            width: 400px;
            height: 400px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            top: -100px;
            left: -100px;
            animation-delay: 0s;
        }

        .floating-shapes .shape-2 {
            width: 300px;
            height: 300px;
            background: linear-gradient(135deg, #f093fb, #f5576c);
            bottom: -50px;
            right: -50px;
            animation-delay: -5s;
        }

        .floating-shapes .shape-3 {
            width: 200px;
            height: 200px;
            background: linear-gradient(135deg, #4facfe, #00f2fe);
            top: 50%;
            right: 20%;
            animation-delay: -10s;
        }

        @keyframes float {

            0%,
            100% {
                transform: translate(0, 0) rotate(0deg);
            }

            25% {
                transform: translate(30px, -30px) rotate(5deg);
            }

            50% {
                transform: translate(-20px, 20px) rotate(-5deg);
            }

            75% {
                transform: translate(20px, 30px) rotate(3deg);
            }
        }

        .feature-icon {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.2), rgba(118, 75, 162, 0.2));
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .slide-up {
            animation: slideUp 0.6s ease-out forwards;
            opacity: 0;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .delay-100 {
            animation-delay: 0.1s;
        }

        .delay-200 {
            animation-delay: 0.2s;
        }

        .delay-300 {
            animation-delay: 0.3s;
        }

        .delay-400 {
            animation-delay: 0.4s;
        }
    </style>
</head>

<body class="relative overflow-hidden">
    <!-- Floating Background Shapes -->
    <div class="floating-shapes">
        <div class="shape-1"></div>
        <div class="shape-2"></div>
        <div class="shape-3"></div>
    </div>

    <div class="min-h-screen flex items-center justify-center p-4 relative z-10">
        <div class="w-full max-w-6xl grid lg:grid-cols-2 gap-8 items-center">

            <!-- Left Side - Branding -->
            <div class="hidden lg:block text-white p-8 slide-up">
                <div class="mb-8">
                    <div class="flex items-center mb-6">
                        <div
                            class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center mr-4">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                </path>
                            </svg>
                        </div>
                        <span class="text-3xl font-bold gradient-text">Skilloka</span>
                    </div>
                    <h1 class="text-4xl font-bold mb-4 leading-tight">
                        Platform Manajemen <br>
                        <span class="gradient-text">LPK Terbaik</span> di Indonesia
                    </h1>
                    <p class="text-gray-400 text-lg leading-relaxed">
                        Kelola pelatihan, siswa, dan sertifikasi dengan mudah dalam satu dashboard yang powerful.
                    </p>
                </div>

                <!-- Features -->
                <div class="space-y-4">
                    <div class="flex items-center slide-up delay-100">
                        <div class="feature-icon w-12 h-12 rounded-lg flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-white">Keamanan Terjamin</h3>
                            <p class="text-gray-400 text-sm">Data terenkripsi end-to-end</p>
                        </div>
                    </div>

                    <div class="flex items-center slide-up delay-200">
                        <div class="feature-icon w-12 h-12 rounded-lg flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-white">Super Cepat</h3>
                            <p class="text-gray-400 text-sm">Performa tinggi & responsif</p>
                        </div>
                    </div>

                    <div class="flex items-center slide-up delay-300">
                        <div class="feature-icon w-12 h-12 rounded-lg flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-white">Multi-Tenant</h3>
                            <p class="text-gray-400 text-sm">Kelola banyak LPK sekaligus</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side - Login Form -->
            <div class="w-full max-w-md mx-auto slide-up delay-100">
                <div class="glass-card rounded-2xl p-8 shadow-2xl">
                    <!-- Mobile Logo -->
                    <div class="lg:hidden flex items-center justify-center mb-8">
                        <div
                            class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center mr-3">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                </path>
                            </svg>
                        </div>
                        <span class="text-2xl font-bold gradient-text">Skilloka</span>
                    </div>

                    <div class="text-center mb-8">
                        <h2 class="text-2xl font-bold text-white mb-2">Selamat Datang! 👋</h2>
                        <p class="text-gray-400">Masuk ke dashboard admin Anda</p>
                    </div>

                    @if($errors->any())
                        <div class="mb-6 p-4 rounded-lg bg-red-500/10 border border-red-500/20">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-red-400 mr-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="text-red-400 text-sm">{{ $errors->first() }}</span>
                            </div>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.login.submit') }}">
                        @csrf

                        <div class="mb-5">
                            <label class="block text-gray-300 text-sm font-medium mb-2" for="email">
                                Email Address
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207">
                                        </path>
                                    </svg>
                                </span>
                                <input
                                    class="input-field w-full pl-12 pr-4 py-3.5 rounded-xl text-white placeholder-gray-500 focus:outline-none"
                                    id="email" type="email" name="email" value="{{ old('email') }}"
                                    placeholder="admin@example.com" required autofocus>
                            </div>
                        </div>

                        <div class="mb-6">
                            <label class="block text-gray-300 text-sm font-medium mb-2" for="password">
                                Password
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                        </path>
                                    </svg>
                                </span>
                                <input
                                    class="input-field w-full pl-12 pr-4 py-3.5 rounded-xl text-white placeholder-gray-500 focus:outline-none"
                                    id="password" type="password" name="password" placeholder="••••••••" required>
                            </div>
                        </div>

                        <div class="flex items-center justify-between mb-6">
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" name="remember"
                                    class="w-4 h-4 rounded border-gray-600 bg-gray-700 text-purple-600 focus:ring-purple-500 focus:ring-offset-0">
                                <span class="ml-2 text-sm text-gray-400">Ingat saya</span>
                            </label>
                            <a href="#" class="text-sm text-purple-400 hover:text-purple-300 transition-colors">
                                Lupa password?
                            </a>
                        </div>

                        <button type="submit"
                            class="btn-gradient w-full py-3.5 rounded-xl text-white font-semibold shadow-lg">
                            Masuk ke Dashboard
                        </button>
                    </form>

                    <div class="mt-8 pt-6 border-t border-gray-700/50">
                        <p class="text-center text-gray-500 text-sm">
                            Belum punya akun LPK?
                            <a href="#" class="text-purple-400 hover:text-purple-300 font-medium">Daftar Sekarang</a>
                        </p>
                    </div>
                </div>

                <!-- Demo Credentials -->
                <div class="mt-6 glass-card rounded-xl p-4 slide-up delay-400">
                    <div class="flex items-center mb-3">
                        <svg class="w-4 h-4 text-yellow-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="text-yellow-400 text-sm font-medium">Demo Credentials</span>
                    </div>
                    <div class="grid grid-cols-2 gap-3 text-xs">
                        <div class="bg-white/5 rounded-lg p-2">
                            <p class="text-gray-400 mb-1">Super Admin</p>
                            <p class="text-white font-mono">god@skilloka.com</p>
                        </div>
                        <div class="bg-white/5 rounded-lg p-2">
                            <p class="text-gray-400 mb-1">LPK Admin</p>
                            <p class="text-white font-mono">admin@merahputih.com</p>
                        </div>
                    </div>
                    <p class="text-gray-500 text-xs mt-2 text-center">Password: <span
                            class="text-white font-mono">password</span></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="absolute bottom-4 left-0 right-0 text-center">
        <p class="text-gray-600 text-sm">
            © 2026 Skilloka. All rights reserved.
        </p>
    </div>
</body>

</html>