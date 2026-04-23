<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Skilloka - Register LPK</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css','resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f4f7fb; }
        .font-outfit { font-family: 'Outfit', sans-serif; }
        .glass-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.05);
            border-radius: 24px;
        }
        .premium-input {
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 14px 16px;
            width: 100%;
            transition: all 0.3s;
            background: #f8fafc;
        }
        .premium-input:focus {
            background: #ffffff;
            border-color: #f97316;
            box-shadow: 0 0 0 4px rgba(249, 115, 22, 0.1);
            outline: none;
        }
        .btn-primary {
            background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
            color: white;
            font-weight: 600;
            border-radius: 12px;
            padding: 14px;
            width: 100%;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(234, 88, 12, 0.3);
        }
        .bg-pattern {
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background-image: radial-gradient(#e2e8f0 1px, transparent 1px);
            background-size: 32px 32px;
            opacity: 0.5;
            z-index: -1;
        }
        .gradient-text {
            background: linear-gradient(to right, #ea580c, #4f46e5);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center relative p-6">
    <div class="bg-pattern"></div>
    
    <div class="glass-card w-full max-w-4xl flex overflow-hidden">
        
        <!-- Left Side: Branding -->
        <div class="w-2/5 bg-gradient-to-br from-[#1e1b4b] to-[#312e81] p-10 flex flex-col justify-between text-white hidden md:flex relative overflow-hidden">
            <div class="absolute inset-0 opacity-10" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cpath d=\'M54.627 0l.83.83-54.628 54.628-.83-.83L54.627 0zM29.627 0l.83.83-29.628 29.628-.83-.83L29.627 0zM59.197 29.57l.83.83-29.628 29.628-.83-.83L59.197 29.57z\' fill=\'%23ffffff\' fill-opacity=\'1\' fill-rule=\'evenodd\'/%3E%3C/svg%3E');"></div>
            
            <div class="relative z-10">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-orange-400 to-orange-600 flex items-center justify-center mb-6 shadow-lg">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                </div>
                <h1 class="font-outfit text-4xl font-bold mb-4">Bergabunglah Bersama Skilloka</h1>
                <p class="text-indigo-200 text-lg leading-relaxed">Kelola lembaga pelatihan kerja Anda dengan mudah, jangkau lebih banyak siswa, dan tingkatkan kredibilitas melalui sistem terintegrasi kami.</p>
            </div>
            
            <div class="relative z-10">
                <div class="flex -space-x-3 mb-3">
                    <div class="w-10 h-10 rounded-full border-2 border-indigo-900 bg-gray-300"></div>
                    <div class="w-10 h-10 rounded-full border-2 border-indigo-900 bg-gray-400"></div>
                    <div class="w-10 h-10 rounded-full border-2 border-indigo-900 bg-gray-500"></div>
                    <div class="w-10 h-10 rounded-full border-2 border-indigo-900 bg-orange-500 flex items-center justify-center text-xs font-bold">+50</div>
                </div>
                <p class="text-sm text-indigo-300">Lebih dari 50+ LPK telah bergabung.</p>
            </div>
        </div>

        <!-- Right Side: Form -->
        <div class="w-full md:w-3/5 p-10 lg:p-14">
            <h2 class="font-outfit text-3xl font-bold text-gray-800 mb-2">Pendaftaran LPK</h2>
            <p class="text-gray-500 mb-8">Daftarkan lembaga pelatihan kerja Anda sekarang.</p>

            @if($errors->any())
                <div class="bg-red-50 text-red-500 p-4 rounded-xl mb-6 border border-red-100 text-sm">
                    <ul class="list-disc pl-5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.register.submit') }}" class="space-y-6">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Informasi Akun -->
                    <div class="space-y-5">
                        <h3 class="font-semibold text-gray-700 text-sm uppercase tracking-wider mb-2 border-b pb-2">Informasi Akun</h3>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap Admin</label>
                            <input type="text" name="name" class="premium-input" placeholder="Cth: Budi Santoso" value="{{ old('name') }}" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" name="email" class="premium-input" placeholder="admin@lpkanda.com" value="{{ old('email') }}" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                            <input type="password" name="password" class="premium-input" placeholder="Minimal 8 karakter" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" class="premium-input" placeholder="Ketik ulang password" required>
                        </div>
                    </div>

                    <!-- Informasi LPK -->
                    <div class="space-y-5">
                        <h3 class="font-semibold text-gray-700 text-sm uppercase tracking-wider mb-2 border-b pb-2">Informasi Lembaga</h3>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama LPK</label>
                            <input type="text" name="lpk_name" class="premium-input" placeholder="Cth: LPK Maju Jaya" value="{{ old('lpk_name') }}" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon LPK</label>
                            <input type="text" name="phone" class="premium-input" placeholder="08123456789" value="{{ old('phone') }}" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap</label>
                            <textarea name="address" class="premium-input h-24 resize-none" placeholder="Masukkan alamat lengkap lembaga" required>{{ old('address') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit" class="btn-primary font-outfit text-lg">Daftar Sekarang</button>
                </div>
            </form>

            <div class="mt-8 text-center text-sm text-gray-500">
                Sudah memiliki akun? <a href="{{ route('admin.login') }}" class="text-orange-600 font-semibold hover:underline">Masuk di sini</a>
            </div>
        </div>
    </div>
</body>
</html>
