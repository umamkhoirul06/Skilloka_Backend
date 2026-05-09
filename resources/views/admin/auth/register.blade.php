<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Register Mitra LPK - Skilloka">
    <title>Register Mitra LPK - Skilloka</title>
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
            overflow: hidden; /* No scroll on body */
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.06);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }

        .gradient-text {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Modern Input Fields */
        .input-field {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: white;
            transition: all 0.3s ease;
        }

        .input-field:focus {
            background: rgba(255, 255, 255, 0.12);
            border-color: rgba(102, 126, 234, 0.6);
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.15);
            outline: none;
        }

        .input-field::placeholder {
            color: rgba(255, 255, 255, 0.3);
        }
        
        /* Disable resize for textareas to prevent layout breaking */
        textarea.input-field {
            resize: none;
        }

        /* Floating Animation */
        .floating-shapes div {
            position: absolute; border-radius: 50%; filter: blur(70px); opacity: 0.5;
            animation: float 15s infinite ease-in-out; z-index: -1;
        }
        .shape-1 { width: 400px; height: 400px; background: linear-gradient(135deg, #667eea, #764ba2); top: -100px; left: -100px; }
        .shape-2 { width: 300px; height: 300px; background: linear-gradient(135deg, #f093fb, #f5576c); bottom: -50px; right: -50px; animation-delay: -5s; }
        @keyframes float {
            0%, 100% { transform: translate(0, 0) rotate(0deg); }
            50% { transform: translate(20px, 30px) rotate(5deg); }
        }

        /* Buttons */
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            transition: all 0.3s ease; position: relative; overflow: hidden;
            border: none;
        }
        .btn-primary:hover {
            transform: translateY(-2px); box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
        }
        .btn-secondary {
            background: rgba(255, 255, 255, 0.1); border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease; color: white;
        }
        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.15);
        }

        /* Step Indicators */
        .step-indicator {
            width: 32px; height: 32px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 14px; font-weight: 600; transition: 0.4s ease;
            background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2);
            color: rgba(255,255,255,0.5);
        }
        .step-indicator.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-color: transparent; color: white;
            box-shadow: 0 0 15px rgba(102, 126, 234, 0.5);
        }
        .step-indicator.completed {
            background: rgba(16, 185, 129, 0.2); border-color: rgba(16, 185, 129, 0.5); color: #34d399;
        }
        .step-line {
            flex: 1; height: 2px; background: rgba(255,255,255,0.1); margin: 0 10px; transition: 0.4s ease;
        }
        .step-line.active { background: #667eea; }

        /* Form Steps */
        .form-step { display: none; animation: fadeIn 0.4s ease forwards; }
        .form-step.active { display: block; }
        @keyframes fadeIn { from { opacity: 0; transform: translateX(10px); } to { opacity: 1; transform: translateX(0); } }

        .feature-icon {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.2), rgba(118, 75, 162, 0.2));
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        /* Smaller text for dense form */
        .label-sm { font-size: 12px; font-weight: 500; color: rgba(255,255,255,0.8); margin-bottom: 6px; display: block; }
    </style>
</head>

<body>
    <div class="floating-shapes">
        <div class="shape-1"></div>
        <div class="shape-2"></div>
    </div>

    <!-- Main Container -->
    <div class="w-full max-w-6xl px-4 z-10">
        <div class="glass-card rounded-3xl overflow-hidden grid md:grid-cols-2">

            <!-- LEFT SIDE: Branding (Hidden on very small screens) -->
            <div class="hidden md:flex flex-col justify-between p-10 lg:p-12 border-r border-white/10 relative">
                <div>
                    <div class="flex items-center mb-8">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center mr-3">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                        <span class="text-2xl font-bold text-white">Skilloka</span>
                    </div>

                    <h1 class="text-3xl lg:text-4xl font-bold text-white mb-4 leading-tight">
                        Platform Manajemen<br>
                        <span class="gradient-text">LPK Terbaik</span>
                    </h1>
                    
                    <p class="text-gray-400 text-sm leading-relaxed mb-8">
                        Lengkapi profil lembaga Anda untuk bergabung dengan ekosistem Skilloka. Kelola kelas, siswa, dan sertifikasi dalam satu sentuhan.
                    </p>

                    <div class="space-y-5">
                        <div class="flex items-center">
                            <div class="feature-icon w-10 h-10 rounded-lg flex items-center justify-center mr-4 flex-shrink-0">
                                <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-white text-sm">Aman & Terverifikasi</h3>
                                <p class="text-gray-400 text-xs">Data terlindungi enkripsi</p>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <div class="feature-icon w-10 h-10 rounded-lg flex items-center justify-center mr-4 flex-shrink-0">
                                <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-white text-sm">Super Responsif</h3>
                                <p class="text-gray-400 text-xs">Akses super cepat tanpa lag</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-8 text-xs text-gray-500">
                    © 2026 Skilloka. All rights reserved.
                </div>
            </div>

            <!-- RIGHT SIDE: Wizard Form -->
            <div class="p-8 lg:p-12 relative flex flex-col justify-center min-h-[500px]">
                
                <div class="mb-6">
                    <h2 class="text-xl font-bold text-white mb-1">Registrasi LPK</h2>
                    <p class="text-xs text-gray-400">Lengkapi data secara bertahap.</p>
                </div>

                <!-- Progress Tracker -->
                <div class="flex items-center mb-8 px-2">
                    <div class="step-indicator active" id="indicator-1">1</div>
                    <div class="step-line" id="line-1"></div>
                    <div class="step-indicator" id="indicator-2">2</div>
                    <div class="step-line" id="line-2"></div>
                    <div class="step-indicator" id="indicator-3">3</div>
                </div>

                @if($errors->any())
                    <div class="mb-5 p-3 rounded-xl bg-red-500/10 border border-red-500/20">
                        <div class="flex items-start">
                            <svg class="w-4 h-4 text-red-400 mr-2 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <ul class="text-red-400 text-xs space-y-1">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.register.submit') }}" id="registerForm">
                    @csrf

                    <!-- STEP 1: Akun Admin -->
                    <div class="form-step active" id="step-1">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="col-span-2">
                                <label class="label-sm">Nama Lengkap Admin</label>
                                <input class="input-field w-full px-4 py-2.5 rounded-xl text-sm" type="text" name="name" value="{{ old('name') }}" placeholder="John Doe" required>
                            </div>
                            <div class="col-span-2 sm:col-span-1">
                                <label class="label-sm">Email Admin</label>
                                <input class="input-field w-full px-4 py-2.5 rounded-xl text-sm" type="email" name="email" value="{{ old('email') }}" placeholder="admin@lpk.com" required>
                            </div>
                            <div class="col-span-2 sm:col-span-1">
                                <label class="label-sm">No WhatsApp</label>
                                <input class="input-field w-full px-4 py-2.5 rounded-xl text-sm" type="text" name="phone" value="{{ old('phone') }}" placeholder="08xxxxxxxxxx" required>
                            </div>
                            <div class="col-span-2 sm:col-span-1">
                                <label class="label-sm">Password</label>
                                <input class="input-field w-full px-4 py-2.5 rounded-xl text-sm" type="password" name="password" placeholder="Minimal 8 karakter" required>
                            </div>
                            <div class="col-span-2 sm:col-span-1">
                                <label class="label-sm">Konfirmasi Password</label>
                                <input class="input-field w-full px-4 py-2.5 rounded-xl text-sm" type="password" name="password_confirmation" placeholder="Ulangi password" required>
                            </div>
                        </div>
                        
                        <div class="mt-8">
                            <button type="button" onclick="nextStep(2)" class="btn-primary w-full py-3 rounded-xl text-white text-sm font-semibold shadow-lg">Selanjutnya <i class="fas fa-arrow-right ml-1"></i></button>
                        </div>
                    </div>

                    <!-- STEP 2: Detail LPK -->
                    <div class="form-step" id="step-2">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="col-span-2">
                                <label class="label-sm">Nama LPK (Publik)</label>
                                <input class="input-field w-full px-4 py-2.5 rounded-xl text-sm" type="text" name="lpk_name" value="{{ old('lpk_name') }}" placeholder="LPK Skilloka" required>
                            </div>
                            <div class="col-span-2">
                                <label class="label-sm">Nama Legal / Yayasan</label>
                                <input class="input-field w-full px-4 py-2.5 rounded-xl text-sm" type="text" name="legal_name" value="{{ old('legal_name') }}" placeholder="Yayasan Edukasi..." required>
                            </div>
                            <div class="col-span-2 sm:col-span-1">
                                <label class="label-sm">Nomor NIB</label>
                                <input class="input-field w-full px-4 py-2.5 rounded-xl text-sm" type="text" name="nib" value="{{ old('nib') }}" placeholder="NIB LPK" required>
                            </div>
                            <div class="col-span-2 sm:col-span-1">
                                <label class="label-sm">Email Resmi LPK</label>
                                <input class="input-field w-full px-4 py-2.5 rounded-xl text-sm" type="email" name="lpk_email" value="{{ old('lpk_email') }}" placeholder="info@lpk.com">
                            </div>
                        </div>

                        <div class="flex gap-3 mt-8">
                            <button type="button" onclick="prevStep(1)" class="btn-secondary px-5 py-3 rounded-xl text-sm font-semibold">Kembali</button>
                            <button type="button" onclick="nextStep(3)" class="btn-primary flex-1 py-3 rounded-xl text-white text-sm font-semibold shadow-lg">Selanjutnya <i class="fas fa-arrow-right ml-1"></i></button>
                        </div>
                    </div>

                    <!-- STEP 3: Lokasi & Fasilitas -->
                    <div class="form-step" id="step-3">
                        <div class="grid gap-3">
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="label-sm">Kota / Kabupaten</label>
                                    <input class="input-field w-full px-4 py-2 rounded-xl text-sm" type="text" name="city" value="{{ old('city') }}" placeholder="Indramayu" required>
                                </div>
                                <div>
                                    <label class="label-sm">Instagram</label>
                                    <input class="input-field w-full px-4 py-2 rounded-xl text-sm" type="text" name="instagram" value="{{ old('instagram') }}" placeholder="@skilloka">
                                </div>
                            </div>
                            
                            <div>
                                <label class="label-sm">Alamat Lengkap</label>
                                <textarea class="input-field w-full px-4 py-2 rounded-xl text-sm h-16" name="address" placeholder="Alamat lengkap..." required>{{ old('address') }}</textarea>
                            </div>

                            <div>
                                <label class="label-sm">Deskripsi & Fasilitas</label>
                                <textarea class="input-field w-full px-4 py-2 rounded-xl text-sm h-16" name="description" placeholder="Ceritakan program dan fasilitas Anda..." required>{{ old('description') }}</textarea>
                            </div>
                            
                            <!-- Hidden facility input mapping since we combine them visually to save space -->
                            <input type="hidden" name="facilities" value="{{ old('facilities', 'Lihat di deskripsi') }}">

                            <div class="flex items-start mt-2">
                                <input type="checkbox" required class="mt-0.5 rounded border-gray-600 bg-gray-700 text-purple-600 cursor-pointer">
                                <label class="text-[11px] text-gray-400 ml-2 cursor-pointer">
                                    Saya menjamin data ini valid dan menyetujui persyaratan Skilloka.
                                </label>
                            </div>
                        </div>

                        <div class="flex gap-3 mt-6">
                            <button type="button" onclick="prevStep(2)" class="btn-secondary px-5 py-3 rounded-xl text-sm font-semibold">Kembali</button>
                            <button type="submit" class="btn-primary flex-1 py-3 rounded-xl text-white text-sm font-semibold shadow-lg">Selesaikan Pendaftaran</button>
                        </div>
                    </div>

                </form>

                <div class="mt-6 pt-5 border-t border-white/10 text-center">
                    <p class="text-gray-400 text-xs">
                        Sudah menjadi mitra resmi? 
                        <a href="{{ route('admin.login') }}" class="text-purple-400 hover:text-purple-300 font-medium ml-1">
                            Masuk ke Dashboard
                        </a>
                    </p>
                </div>

            </div>

        </div>
    </div>

    <!-- JS for Wizard Steps -->
    <script>
        let currentStep = 1;

        function updateUI() {
            // Hide all steps
            document.querySelectorAll('.form-step').forEach(el => el.classList.remove('active'));
            // Show current step
            document.getElementById('step-' + currentStep).classList.add('active');

            // Update Indicators
            document.querySelectorAll('.step-indicator').forEach((el, index) => {
                let stepNum = index + 1;
                el.classList.remove('active', 'completed');
                if (stepNum === currentStep) {
                    el.classList.add('active');
                } else if (stepNum < currentStep) {
                    el.classList.add('completed');
                    el.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>';
                } else {
                    el.innerHTML = stepNum;
                }
            });

            // Update Lines
            document.querySelectorAll('.step-line').forEach((el, index) => {
                let lineNum = index + 1;
                if (lineNum < currentStep) {
                    el.classList.add('active');
                } else {
                    el.classList.remove('active');
                }
            });
        }

        function nextStep(step) {
            // Basic validation for the current step before moving
            const currentStepEl = document.getElementById('step-' + currentStep);
            const inputs = currentStepEl.querySelectorAll('input[required], textarea[required]');
            let allValid = true;
            
            inputs.forEach(input => {
                if (!input.value.trim()) {
                    input.style.borderColor = '#ef4444'; // Red border
                    allValid = false;
                } else {
                    input.style.borderColor = ''; // Reset
                }
            });

            if(allValid) {
                currentStep = step;
                updateUI();
            } else {
                alert('Mohon lengkapi semua field yang wajib diisi pada langkah ini.');
            }
        }

        function prevStep(step) {
            currentStep = step;
            updateUI();
        }
    </script>
</body>
</html>