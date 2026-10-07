<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk Portal Manajemen - Skilloka</title>
    <meta name="description" content="Masuk ke portal manajemen resmi Mitra LPK dan Super Admin Skilloka.">

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

    <!-- Tailwind CSS CDN -->
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
                        }
                    }
                }
            }
        }
    </script>

    <!-- FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/shiny-button.css') }}">

    <style>
        * {
            font-family: 'Inter', sans-serif;
        }

        .font-outfit {
            font-family: 'Outfit', sans-serif;
        }

        /* Subtle Hero Grid Pattern */
        .hero-pattern {
            background-color: #f8fafc;
            background-image: radial-gradient(#cbd5e1 0.75px, transparent 0.75px);
            background-size: 24px 24px;
        }
    </style>

    <!-- Shiny Button CSS -->
    <link rel="stylesheet" href="{{ asset('css/shiny-button.css') }}">
</head>

<body class="hero-pattern text-slate-800 min-h-screen flex items-center justify-center p-4 sm:p-6 lg:p-8 antialiased relative overflow-x-hidden">

    <!-- Ambient Glowing Orbs -->
    <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-brand-400/15 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-indigo-500/15 rounded-full blur-3xl pointer-events-none"></div>

    <!-- Main Card Container -->
    <div class="w-full max-w-4xl bg-white rounded-3xl border border-slate-200/90 shadow-2xl shadow-slate-200/70 overflow-hidden relative z-10 grid grid-cols-1 lg:grid-cols-12">
        
        <!-- ═══════════════════════════════════════
             LEFT BRAND SHOWCASE (Minimalist & Sleek)
        ═══════════════════════════════════════ -->
        <div class="lg:col-span-5 bg-gradient-to-br from-brand-950 via-brand-900 to-indigo-950 text-white p-8 sm:p-10 flex flex-col justify-between relative overflow-hidden">
            <!-- Glow Accent -->
            <div class="absolute -top-20 -left-20 w-60 h-60 bg-brand-500/25 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-20 -right-20 w-60 h-60 bg-purple-500/20 rounded-full blur-3xl pointer-events-none"></div>

            <!-- Header / Brand Logo -->
            <div class="relative z-10">
                <a href="{{ route('home') }}" class="inline-flex items-center group transition-transform hover:scale-[1.02]">
                    <x-skilloka-logo class="h-10" :isDark="true" textColor="text-white" textSize="text-2xl" :withSubtitle="true" subtitle="LPK Center" subtitleColor="text-brand-200" />
                </a>
            </div>

            <!-- Compact, Clean Showcase (Tidak Rame) -->
            <div class="relative z-10 my-8 space-y-6">
                <!-- Tagline Ringkas -->
                <div>
                    <h1 class="font-outfit text-2xl sm:text-3xl font-extrabold text-white tracking-tight leading-snug">
                        Pusat Operasional Pelatihan Vokasi.
                    </h1>
                    <p class="text-xs text-brand-200/90 mt-2 leading-relaxed">
                        Kelola kurikulum, siswa, presensi, dan sertifikasi resmi LPK se-Kabupaten Indramayu.
                    </p>
                </div>

                <!-- Minimalist Glass Card -->
                <div class="p-4 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-xs space-y-3">
                    <div class="flex items-center gap-2.5 text-xs text-brand-100 font-medium">
                        <i class="fa-solid fa-shield-halved text-emerald-400"></i>
                        <span>Legalitas Izin Resmi NIB OSS</span>
                    </div>
                    <div class="flex items-center gap-2.5 text-xs text-brand-100 font-medium">
                        <i class="fa-solid fa-qrcode text-brand-400"></i>
                        <span>Sertifikat Digital Ber-QR Code</span>
                    </div>
                </div>
            </div>

            <!-- Bottom Footnote -->
            <div class="relative z-10 pt-4 border-t border-white/10 flex items-center justify-between text-xs text-brand-200/70">
                <span>&copy; {{ date('Y') }} Skilloka</span>
                <span class="flex items-center gap-1 text-emerald-300">
                    <i class="fa-solid fa-location-dot text-[10px]"></i> Indramayu
                </span>
            </div>
        </div>

        <!-- ═══════════════════════════════════════
             RIGHT LOGIN FORM (Clean, Modern SaaS)
        ═══════════════════════════════════════ -->
        <div class="lg:col-span-7 p-8 sm:p-10 lg:p-12 flex flex-col justify-center bg-white">
            <div class="max-w-sm w-full mx-auto">
                
                <!-- Title Header -->
                <div class="mb-7">
                    <h2 class="font-outfit text-2xl font-bold text-slate-900 tracking-tight">Selamat Datang</h2>
                    <p class="text-xs text-slate-500 mt-1">Silakan masukkan akun Anda untuk mengakses portal manajemen.</p>
                </div>

                <!-- Flash Alerts -->
                @if(session('success'))
                    <div class="mb-5 p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 flex items-start gap-2.5 text-emerald-900 text-xs">
                        <i class="fa-solid fa-circle-check text-emerald-600 text-sm mt-0.5"></i>
                        <span class="font-medium leading-relaxed">{{ session('success') }}</span>
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-5 p-3.5 rounded-xl bg-rose-50 border border-rose-200 flex items-start gap-2.5 text-rose-900 text-xs">
                        <i class="fa-solid fa-circle-exclamation text-rose-600 text-sm mt-0.5"></i>
                        <span class="font-medium leading-relaxed">{{ $errors->first() }}</span>
                    </div>
                @endif

                <!-- Login Form -->
                <form method="POST" action="{{ route('admin.login.submit') }}" class="space-y-4">
                    @csrf

                    <!-- Email Field -->
                    <div>
                        <label for="email" class="block text-xs font-bold text-slate-700 mb-1">
                            Alamat Email
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i class="fa-regular fa-envelope text-xs"></i>
                            </span>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                                class="w-full pl-9 pr-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs text-slate-900 placeholder-slate-400 focus:outline-none focus:bg-white focus:border-brand-600 focus:ring-3 focus:ring-brand-500/10 transition font-medium"
                                placeholder="nama@email.com">
                        </div>
                    </div>

                    <!-- Password Field -->
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <label for="password" class="block text-xs font-bold text-slate-700">
                                Kata Sandi
                            </label>
                            <a href="{{ route('password.request') }}" class="text-[11px] font-semibold text-brand-600 hover:text-brand-800 transition-colors">
                                Lupa sandi?
                            </a>
                        </div>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i class="fa-solid fa-lock text-xs"></i>
                            </span>
                            <input type="password" id="password" name="password" required
                                class="w-full pl-9 pr-10 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs text-slate-900 placeholder-slate-400 focus:outline-none focus:bg-white focus:border-brand-600 focus:ring-3 focus:ring-brand-500/10 transition font-medium"
                                placeholder="••••••••">
                            <button type="button" onclick="togglePasswordVisibility()"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 transition cursor-pointer">
                                <i id="eyeIcon" class="fa-regular fa-eye text-xs"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Remember Me Option -->
                    <div class="flex items-center justify-between pt-0.5">
                        <label class="flex items-center gap-2 cursor-pointer select-none">
                            <input type="checkbox" name="remember" class="w-3.5 h-3.5 rounded text-brand-600 border-slate-300 focus:ring-brand-500 cursor-pointer">
                            <span class="text-xs text-slate-600">Ingat saya</span>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-1.5">
                        <button type="submit"
                            class="shiny-btn shiny-btn-brand w-full py-3 px-5 rounded-xl text-white font-bold text-xs shadow-md shadow-brand-500/20 flex items-center justify-center gap-2 cursor-pointer">
                            <span>Masuk ke Dashboard</span>
                            <i class="fa-solid fa-arrow-right text-[10px]"></i>
                        </button>
                    </div>
                </form>

                <!-- Divider & Register Link -->
                <div class="mt-6 pt-5 border-t border-slate-100 text-center">
                    <p class="text-xs text-slate-500">
                        Belum mendaftarkan Lembaga Anda?
                    </p>
                    <a href="{{ route('lpk.register') }}"
                        class="inline-flex items-center gap-1.5 text-xs font-bold text-brand-600 hover:text-brand-800 mt-1 transition">
                        <span>Daftar Sebagai Mitra LPK Baru</span>
                        <i class="fa-solid fa-arrow-right-long text-[10px]"></i>
                    </a>
                </div>

                <div class="mt-4 text-center">
                    <a href="{{ route('home') }}" class="inline-flex items-center gap-1 text-[11px] font-medium text-slate-400 hover:text-slate-700 transition">
                        <i class="fa-solid fa-chevron-left text-[9px]"></i>
                        <span>Kembali ke Beranda Publik</span>
                    </a>
                </div>

            </div>
        </div>

    </div>

    <!-- Toggle Password Visibility JS -->
    <script>
        function togglePasswordVisibility() {
            const pwdInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            if (pwdInput.type === 'password') {
                pwdInput.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                pwdInput.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        }
    </script>
</body>

</html>