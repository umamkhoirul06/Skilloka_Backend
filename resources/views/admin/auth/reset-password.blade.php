<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Buat Kata Sandi Baru - Skilloka</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Outfit:wght@500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN (Identik dengan Landing Page) -->
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * { font-family: 'Inter', sans-serif; }
        .font-outfit { font-family: 'Outfit', sans-serif; }
        .hero-pattern {
            background-color: #f8fafc;
            background-image: radial-gradient(#cbd5e1 0.75px, transparent 0.75px);
            background-size: 24px 24px;
        }
    </style>
</head>
<body class="hero-pattern min-h-screen flex items-center justify-center p-4 text-slate-800 relative overflow-hidden">

<!-- Glow Orbs -->
<div class="absolute top-10 left-10 w-96 h-96 bg-brand-400/15 rounded-full blur-3xl pointer-events-none"></div>
<div class="absolute bottom-10 right-10 w-96 h-96 bg-indigo-500/15 rounded-full blur-3xl pointer-events-none"></div>

<div class="bg-white rounded-3xl border border-slate-200/90 shadow-2xl shadow-slate-200/60 p-8 sm:p-10 w-full max-w-md relative z-10">
    <div class="w-14 h-14 rounded-2xl bg-brand-50 border border-brand-100 text-brand-600 flex items-center justify-center text-xl mx-auto mb-6 shadow-sm shadow-brand-500/20">
        <i class="fa-solid fa-lock-open"></i>
    </div>

    <h2 class="font-outfit text-2xl font-black text-slate-900 text-center tracking-tight">Kata Sandi Baru</h2>
    <p class="text-xs text-slate-500 text-center mt-2 mb-6">
        Silakan masukkan kata sandi baru untuk mengamankan kembali akun portal manajemen Anda.
    </p>

    @if($errors->any())
        <div class="mb-5 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold flex items-center gap-2">
            <i class="fa-solid fa-circle-exclamation text-rose-600"></i>
            <span>{{ $errors->first() }}</span>
        </div>
    @endif

    <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Email Akun</label>
            <input type="email" name="email" value="{{ old('email', request()->email) }}" required
                class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:bg-white focus:border-brand-600 focus:ring-4 focus:ring-brand-500/10 transition-all font-medium"
                placeholder="admin@lpk-anda.com">
        </div>

        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Kata Sandi Baru (Min. 8 Karakter)</label>
            <input type="password" name="password" required
                class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:bg-white focus:border-brand-600 focus:ring-4 focus:ring-brand-500/10 transition-all font-medium"
                placeholder="••••••••">
        </div>

        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Ulangi Kata Sandi Baru</label>
            <input type="password" name="password_confirmation" required
                class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:bg-white focus:border-brand-600 focus:ring-4 focus:ring-brand-500/10 transition-all font-medium"
                placeholder="••••••••">
        </div>

        <div class="pt-2">
            <button type="submit"
                class="w-full py-3.5 px-6 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs shadow-lg shadow-brand-500/25 transition-all flex items-center justify-center gap-2 cursor-pointer">
                <span>Simpan Kata Sandi Baru</span>
                <i class="fa-solid fa-check text-[10px]"></i>
            </button>
        </div>
    </form>

    <div class="mt-6 pt-5 border-t border-slate-100 text-center">
        <a href="{{ route('admin.login') }}" class="text-xs font-bold text-brand-600 hover:text-brand-800 inline-flex items-center gap-1.5 transition-colors">
            <i class="fa-solid fa-arrow-left text-[10px]"></i> Kembali ke Halaman Masuk
        </a>
    </div>
</div>

</body>
</html>