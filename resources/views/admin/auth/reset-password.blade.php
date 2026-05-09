<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reset Password - Skilloka</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { font-family: 'Inter', sans-serif; }
        body { background: linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%); min-height: 100vh; }
        .glass-card { background: rgba(255,255,255,0.08); backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.1); }
        .input-field { background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); transition: all 0.3s ease; }
        .input-field:focus { background: rgba(255,255,255,0.1); border-color: rgba(102,126,234,0.6); box-shadow: 0 0 0 4px rgba(102,126,234,0.15); outline: none; }
        .btn-gradient { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); transition: all 0.3s ease; }
        .btn-gradient:hover { transform: translateY(-2px); box-shadow: 0 10px 40px rgba(102,126,234,0.4); }
        .floating-shapes div { position: absolute; border-radius: 50%; filter: blur(60px); opacity: 0.4; animation: float 15s infinite ease-in-out; }
        .shape-1 { width:400px; height:400px; background: linear-gradient(135deg,#667eea,#764ba2); top:-100px; left:-100px; }
        .shape-2 { width:300px; height:300px; background: linear-gradient(135deg,#f093fb,#f5576c); bottom:-50px; right:-50px; animation-delay:-5s; }
        @keyframes float { 0%,100%{transform:translate(0,0)} 50%{transform:translate(-20px,20px)} }
    </style>
</head>
<body class="relative overflow-hidden">

<div class="floating-shapes">
    <div class="shape-1"></div>
    <div class="shape-2"></div>
</div>

<div class="min-h-screen flex items-center justify-center p-4 relative z-10">
    <div class="w-full max-w-md">
        <div class="glass-card rounded-2xl p-8 shadow-2xl">

            {{-- ICON --}}
            <div class="text-center mb-8">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-green-500 to-teal-600 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-white mb-2">Reset Password</h2>
                <p class="text-gray-400 text-sm">Buat password baru untuk akun kamu.</p>
            </div>

            {{-- ERROR --}}
            @if($errors->any())
                <div class="mb-6 p-4 rounded-lg bg-red-500/10 border border-red-500/20">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-red-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="text-red-400 text-sm">{{ $errors->first() }}</span>
                    </div>
                </div>
            @endif

            {{-- FORM --}}
            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="mb-5">
                    <label class="block text-gray-300 text-sm font-medium mb-2">Email</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                            </svg>
                        </span>
                        <input type="email" name="email" value="{{ old('email') }}"
                            class="input-field w-full pl-12 pr-4 py-3.5 rounded-xl text-white placeholder-gray-500"
                            placeholder="admin@lpk.com" required>
                    </div>
                </div>

                <div class="mb-5">
                    <label class="block text-gray-300 text-sm font-medium mb-2">Password Baru</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </span>
                        <input type="password" name="password"
                            class="input-field w-full pl-12 pr-4 py-3.5 rounded-xl text-white placeholder-gray-500"
                            placeholder="Minimal 8 karakter" required>
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-300 text-sm font-medium mb-2">Konfirmasi Password</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </span>
                        <input type="password" name="password_confirmation"
                            class="input-field w-full pl-12 pr-4 py-3.5 rounded-xl text-white placeholder-gray-500"
                            placeholder="Ulangi password baru" required>
                    </div>
                </div>

                <button type="submit" class="btn-gradient w-full py-3.5 rounded-xl text-white font-semibold shadow-lg">
                    Reset Password
                </button>
            </form>

            <div class="text-center mt-6">
                <a href="{{ route('admin.login') }}" class="text-purple-400 hover:text-purple-300 text-sm transition-colors">
                    ← Kembali ke Login
                </a>
            </div>

        </div>
    </div>
</div>

</body>
</html>