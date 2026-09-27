<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pendaftaran Mitra Lembaga Pelatihan Kerja (LPK) - Skilloka</title>
    <meta name="description" content="Formulir resmi pendaftaran kemitraan Lembaga Pelatihan Kerja (LPK) di Kabupaten Indramayu.">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Outfit:wght@500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN with Custom Configurations (Identik dengan Landing Page) -->
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

    <style>
        * {
            font-family: 'Inter', sans-serif;
        }

        .font-outfit {
            font-family: 'Outfit', sans-serif;
        }

        .hero-pattern {
            background-color: #f8fafc;
            background-image: radial-gradient(#cbd5e1 0.75px, transparent 0.75px);
            background-size: 24px 24px;
        }

        .step-circle {
            width: 40px;
            height: 40px;
            border-radius: 9999px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .step-circle.active {
            background-color: #2563eb;
            color: white;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.2);
        }

        .step-circle.completed {
            background-color: #10b981;
            color: white;
        }

        .step-circle.inactive {
            background-color: #e2e8f0;
            color: #64748b;
        }
    </style>
</head>

<body class="hero-pattern text-slate-800 min-h-screen py-10 px-4 sm:px-6 lg:px-8 antialiased relative overflow-x-hidden">

    <!-- Glowing Background Orbs -->
    <div class="absolute top-10 left-10 w-96 h-96 bg-brand-400/15 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-10 right-10 w-96 h-96 bg-indigo-500/15 rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-3xl mx-auto relative z-10">
        
        <!-- Header & Branding Matching Landing Page -->
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2.5 group">
                <div class="w-11 h-11 rounded-2xl bg-gradient-to-tr from-brand-600 to-indigo-600 flex items-center justify-center text-white shadow-lg shadow-brand-500/25 group-hover:scale-105 transition-transform">
                    <i class="fa-solid fa-graduation-cap text-lg"></i>
                </div>
                <div>
                    <span class="font-outfit text-2xl font-black text-slate-900 tracking-tight">SKILLOKA</span>
                    <span class="block text-[10px] font-bold text-brand-600 tracking-wider uppercase">Portal Kemitraan LPK</span>
                </div>
            </a>
            
            <div class="mt-4 inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-brand-50 text-brand-700 border border-brand-200">
                <i class="fa-solid fa-briefcase text-brand-600"></i>
                Wilayah Khusus: Kabupaten Indramayu
            </div>

            <h1 class="font-outfit text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight mt-3">
                Formulir Pendaftaran Mitra LPK
            </h1>
            <p class="text-sm text-slate-500 mt-2 max-w-lg mx-auto">
                Bermitra dengan Skilloka untuk mendigitalkan pendaftaran kursus, presensi, sertifikat, dan menjangkau ribuan calon peserta pelatihan di Indramayu.
            </p>
        </div>

        <!-- Wizard Progress Bar Card -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200/90 shadow-sm mb-8">
            <div class="flex items-center justify-between relative">
                <!-- Connector Track -->
                <div class="absolute top-1/2 left-0 right-0 -translate-y-1/2 h-1 bg-slate-100 -z-0 mx-10"></div>
                <div id="progressBar" class="absolute top-1/2 left-0 -translate-y-1/2 h-1 bg-brand-600 -z-0 mx-10 transition-all duration-300" style="width: 0%;"></div>

                <!-- Step 1 Indicator -->
                <div class="relative z-10 flex flex-col items-center bg-white px-2">
                    <div id="stepCircle1" class="step-circle active">1</div>
                    <span id="stepLabel1" class="text-xs font-bold text-brand-600 mt-2">Akun Pengelola</span>
                </div>

                <!-- Step 2 Indicator -->
                <div class="relative z-10 flex flex-col items-center bg-white px-2">
                    <div id="stepCircle2" class="step-circle inactive">2</div>
                    <span id="stepLabel2" class="text-xs font-semibold text-slate-400 mt-2">Legalitas LPK</span>
                </div>

                <!-- Step 3 Indicator -->
                <div class="relative z-10 flex flex-col items-center bg-white px-2">
                    <div id="stepCircle3" class="step-circle inactive">3</div>
                    <span id="stepLabel3" class="text-xs font-semibold text-slate-400 mt-2">Profil & Fasilitas</span>
                </div>
            </div>
        </div>

        <!-- Main Form Card -->
        <div class="bg-white rounded-3xl p-6 sm:p-10 border border-slate-200/90 shadow-xl shadow-slate-200/50">
            
            <!-- Global Error Alert -->
            @if($errors->any())
                <div class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-200 flex items-start gap-3">
                    <i class="fa-solid fa-circle-exclamation text-rose-600 mt-0.5 text-base"></i>
                    <div>
                        <div class="text-sm font-bold text-rose-900">Periksa kembali data formulir:</div>
                        <ul class="list-disc list-inside text-xs text-rose-700 mt-1 space-y-1">
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <form id="registerForm" method="POST" action="{{ route('admin.register.submit') }}" class="space-y-6">
                @csrf

                <!-- ═══════════════════════════════════════
                     STEP 1: AKUN PENANGGUNG JAWAB
                ═══════════════════════════════════════ -->
                <div id="step1Section" class="space-y-5">
                    <div>
                        <h3 class="font-outfit text-xl font-bold text-slate-900">Langkah 1: Akun Penanggung Jawab LPK</h3>
                        <p class="text-xs text-slate-500 mt-1">Akun ini digunakan untuk login ke portal Admin LPK Anda.</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                Nama Lengkap Penanggung Jawab <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" required
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:bg-white focus:border-brand-600 focus:ring-4 focus:ring-brand-500/10 transition-all font-medium"
                                placeholder="Contoh: Budi Santoso, S.Kom">
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                Email Login <span class="text-rose-500">*</span>
                            </label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" required
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:bg-white focus:border-brand-600 focus:ring-4 focus:ring-brand-500/10 transition-all font-medium"
                                placeholder="admin@lpk-anda.com">
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                Nomor WhatsApp Aktif <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" id="phone" name="phone" value="{{ old('phone') }}" required
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:bg-white focus:border-brand-600 focus:ring-4 focus:ring-brand-500/10 transition-all font-medium"
                                placeholder="081234567890">
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                Kata Sandi (Min. 8 Karakter) <span class="text-rose-500">*</span>
                            </label>
                            <input type="password" id="password" name="password" required
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:bg-white focus:border-brand-600 focus:ring-4 focus:ring-brand-500/10 transition-all font-medium"
                                placeholder="••••••••">
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                Konfirmasi Kata Sandi <span class="text-rose-500">*</span>
                            </label>
                            <input type="password" id="password_confirmation" name="password_confirmation" required
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:bg-white focus:border-brand-600 focus:ring-4 focus:ring-brand-500/10 transition-all font-medium"
                                placeholder="••••••••">
                        </div>
                    </div>

                    <div class="pt-4 flex justify-between items-center border-t border-slate-100">
                        <a href="{{ route('admin.login') }}" class="text-xs font-bold text-slate-500 hover:text-brand-600 transition-colors">
                            <i class="fa-solid fa-arrow-left text-[10px] mr-1"></i> Sudah punya akun? Masuk
                        </a>
                        <button type="button" onclick="nextStep(2)"
                            class="px-6 py-3 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs shadow-md shadow-brand-600/25 transition-all flex items-center gap-2 cursor-pointer">
                            <span>Lanjut ke Legalitas LPK</span>
                            <i class="fa-solid fa-arrow-right text-[10px]"></i>
                        </button>
                    </div>
                </div>

                <!-- ═══════════════════════════════════════
                     STEP 2: IDENTITAS & LEGALITAS LPK
                ═══════════════════════════════════════ -->
                <div id="step2Section" class="space-y-5 hidden">
                    <div>
                        <h3 class="font-outfit text-xl font-bold text-slate-900">Langkah 2: Legalitas & Profil Usaha LPK</h3>
                        <p class="text-xs text-slate-500 mt-1">Data resmi ini digunakan oleh Super Admin untuk verifikasi keabsahan izin lembaga.</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                Nama Lembaga (Brand / Populer) <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" id="lpk_name" name="lpk_name" value="{{ old('lpk_name') }}" required
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:bg-white focus:border-brand-600 focus:ring-4 focus:ring-brand-500/10 transition-all font-medium"
                                placeholder="Contoh: LPK Karya Mandiri Indramayu">
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                Nama Badan Hukum Resmi <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" id="legal_name" name="legal_name" value="{{ old('legal_name') }}" required
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:bg-white focus:border-brand-600 focus:ring-4 focus:ring-brand-500/10 transition-all font-medium"
                                placeholder="Contoh: PT Karya Mandiri / Yayasan Mandiri">
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                Nomor Induk Berusaha (NIB OSS) <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" id="nib" name="nib" value="{{ old('nib') }}" required
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:bg-white focus:border-brand-600 focus:ring-4 focus:ring-brand-500/10 transition-all font-medium font-mono"
                                placeholder="Nomor 13 digit dari sistem OSS">
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                Kota / Kabupaten Domisili <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" id="city" name="city" value="{{ old('city', 'Indramayu') }}" required
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:bg-white focus:border-brand-600 focus:ring-4 focus:ring-brand-500/10 transition-all font-medium"
                                placeholder="Kabupaten Indramayu">
                        </div>
                    </div>

                    <div class="p-4 rounded-2xl bg-brand-50 border border-brand-200 text-xs text-brand-900 flex items-start gap-2.5">
                        <i class="fa-solid fa-circle-info text-brand-600 mt-0.5"></i>
                        <span>Pastikan NIB yang dimasukkan terdaftar di OSS untuk izin pelatihan kerja. Verifikator platform Skilloka akan mencocokkan nomor ini secara resmi.</span>
                    </div>

                    <div class="pt-4 flex justify-between items-center border-t border-slate-100">
                        <button type="button" onclick="prevStep(1)"
                            class="px-5 py-2.5 rounded-xl border border-slate-300 hover:bg-slate-50 text-slate-700 font-bold text-xs transition-all flex items-center gap-2 cursor-pointer">
                            <i class="fa-solid fa-arrow-left text-[10px]"></i>
                            <span>Kembali</span>
                        </button>
                        <button type="button" onclick="nextStep(3)"
                            class="px-6 py-3 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs shadow-md shadow-brand-600/25 transition-all flex items-center gap-2 cursor-pointer">
                            <span>Lanjut ke Detail & Fasilitas</span>
                            <i class="fa-solid fa-arrow-right text-[10px]"></i>
                        </button>
                    </div>
                </div>

                <!-- ═══════════════════════════════════════
                     STEP 3: OPERASIONAL & FASILITAS LPK
                ═══════════════════════════════════════ -->
                <div id="step3Section" class="space-y-5 hidden">
                    <div>
                        <h3 class="font-outfit text-xl font-bold text-slate-900">Langkah 3: Fasilitas & Operasional LPK</h3>
                        <p class="text-xs text-slate-500 mt-1">Lengkapi informasi publik yang akan ditampilkan kepada calon peserta kursus.</p>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                Alamat Lengkap Operasional (Indramayu) <span class="text-rose-500">*</span>
                            </label>
                            <textarea id="address" name="address" rows="2" required
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:bg-white focus:border-brand-600 focus:ring-4 focus:ring-brand-500/10 transition-all font-medium"
                                placeholder="Jl. Raya Indramayu - Cirebon No. 123, Kec. Sindang, Kab. Indramayu">{{ old('address') }}</textarea>
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                Deskripsi Profil & Keunggulan Lembaga <span class="text-rose-500">*</span>
                            </label>
                            <textarea id="description" name="description" rows="3" required
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:bg-white focus:border-brand-600 focus:ring-4 focus:ring-brand-500/10 transition-all font-medium"
                                placeholder="Jelaskan bidang kejuruan unggulan, sertifikasi industri, instruktur berpengalaman, dan akreditasi lembaga Anda...">{{ old('description') }}</textarea>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                    Fasilitas Pelatihan
                                </label>
                                <input type="text" id="facilities" name="facilities" value="{{ old('facilities') }}"
                                    class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:bg-white focus:border-brand-600 focus:ring-4 focus:ring-brand-500/10 transition-all font-medium"
                                    placeholder="Bengkel AC, Lab Komputer, Wi-Fi, Sertifikat">
                                <span class="text-[11px] text-slate-400 mt-1 block">Pisahkan setiap fasilitas dengan tanda koma</span>
                            </div>

                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                    Username Instagram Resmi
                                </label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 font-semibold text-sm">@</span>
                                    <input type="text" id="instagram" name="instagram" value="{{ old('instagram') }}"
                                        class="w-full pl-8 pr-4 py-3 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:bg-white focus:border-brand-600 focus:ring-4 focus:ring-brand-500/10 transition-all font-medium"
                                        placeholder="lpkindramayu">
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                Email Resmi Lembaga (Opsional)
                            </label>
                            <input type="email" id="lpk_email" name="lpk_email" value="{{ old('lpk_email') }}"
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:bg-white focus:border-brand-600 focus:ring-4 focus:ring-brand-500/10 transition-all font-medium"
                                placeholder="info@lpk-anda.com">
                        </div>
                    </div>

                    <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-xs text-emerald-900 flex items-start gap-2.5">
                        <i class="fa-solid fa-circle-check text-emerald-600 mt-0.5"></i>
                        <span>Setelah pendaftaran dikirimkan, akun akan masuk ke antrean verifikasi Super Admin. Tim kami akan meninjau dokumen dalam 1x24 jam kerja.</span>
                    </div>

                    <div class="pt-4 flex justify-between items-center border-t border-slate-100">
                        <button type="button" onclick="prevStep(2)"
                            class="px-5 py-2.5 rounded-xl border border-slate-300 hover:bg-slate-50 text-slate-700 font-bold text-xs transition-all flex items-center gap-2 cursor-pointer">
                            <i class="fa-solid fa-arrow-left text-[10px]"></i>
                            <span>Kembali</span>
                        </button>
                        <button type="submit"
                            class="px-8 py-3.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs shadow-lg shadow-brand-600/30 transition-all flex items-center gap-2 cursor-pointer">
                            <i class="fa-solid fa-paper-plane text-[10px]"></i>
                            <span>Kirim Pendaftaran Kemitraan</span>
                        </button>
                    </div>
                </div>

            </form>
        </div>

        <!-- Footer -->
        <div class="mt-8 text-center text-xs text-slate-400">
            &copy; {{ date('Y') }} Skilloka Platform &bull; Ekosistem Lembaga Pelatihan Kerja Resmi Kabupaten Indramayu.
        </div>
    </div>

    <!-- Wizard Step Logic -->
    <script>
        let currentStep = 1;

        function updateStepUI(step) {
            document.getElementById('step1Section').classList.add('hidden');
            document.getElementById('step2Section').classList.add('hidden');
            document.getElementById('step3Section').classList.add('hidden');

            document.getElementById(`step${step}Section`).classList.remove('hidden');

            for (let i = 1; i <= 3; i++) {
                const circle = document.getElementById(`stepCircle${i}`);
                const label = document.getElementById(`stepLabel${i}`);

                if (i < step) {
                    circle.className = 'step-circle completed';
                    circle.innerHTML = '<i class="fa-solid fa-check text-xs"></i>';
                    label.className = 'text-xs font-semibold text-emerald-600 mt-2';
                } else if (i === step) {
                    circle.className = 'step-circle active';
                    circle.innerHTML = i;
                    label.className = 'text-xs font-bold text-brand-600 mt-2';
                } else {
                    circle.className = 'step-circle inactive';
                    circle.innerHTML = i;
                    label.className = 'text-xs font-semibold text-slate-400 mt-2';
                }
            }

            const progress = document.getElementById('progressBar');
            if (step === 1) progress.style.width = '0%';
            if (step === 2) progress.style.width = '50%';
            if (step === 3) progress.style.width = '100%';
        }

        function nextStep(step) {
            if (step === 2) {
                const name = document.getElementById('name').value.trim();
                const email = document.getElementById('email').value.trim();
                const phone = document.getElementById('phone').value.trim();
                const pwd = document.getElementById('password').value;
                const pwdConf = document.getElementById('password_confirmation').value;

                if (!name || !email || !phone || !pwd) {
                    alert('Mohon lengkapi seluruh field akun yang berbintang merah.');
                    return;
                }
                if (pwd.length < 8) {
                    alert('Kata sandi minimal harus 8 karakter.');
                    return;
                }
                if (pwd !== pwdConf) {
                    alert('Konfirmasi kata sandi tidak cocok.');
                    return;
                }
            }

            if (step === 3) {
                const lpkName = document.getElementById('lpk_name').value.trim();
                const legalName = document.getElementById('legal_name').value.trim();
                const nib = document.getElementById('nib').value.trim();

                if (!lpkName || !legalName || !nib) {
                    alert('Mohon isi nama LPK, nama badan hukum resmi, dan nomor NIB.');
                    return;
                }
            }

            currentStep = step;
            updateStepUI(currentStep);
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function prevStep(step) {
            currentStep = step;
            updateStepUI(currentStep);
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    </script>
</body>

</html>