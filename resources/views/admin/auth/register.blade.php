<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Register Mitra LPK - Skilloka</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
          rel="stylesheet">

    @vite(['resources/css/app.css','resources/js/app.js'])

    <style>
        body{
            font-family:'Inter',sans-serif;
            background:
                radial-gradient(circle at top left,#e0e7ff,#f8fafc 45%);
        }

        .container{
            max-width:1200px;
            margin:auto;
        }

        .card{
            background:rgba(255,255,255,.95);
            backdrop-filter:blur(14px);
            border:1px solid #e5e7eb;
            border-radius:24px;
            box-shadow:0 20px 40px rgba(15,23,42,.08);
            overflow:hidden;
        }

        .left-side{
            background:
                linear-gradient(135deg,#4f46e5,#7c3aed);
            color:white;
            padding:50px 40px;
            position:relative;
        }

        .left-side::before{
            content:'';
            position:absolute;
            width:300px;
            height:300px;
            background:rgba(255,255,255,.08);
            border-radius:999px;
            top:-80px;
            right:-80px;
        }

        .input{
            width:100%;
            border:1px solid #e5e7eb;
            background:#f9fafb;
            border-radius:14px;
            padding:13px 15px;
            font-size:14px;
            transition:.2s;
        }

        .input:focus{
            outline:none;
            background:white;
            border-color:#6366f1;
            box-shadow:0 0 0 4px rgba(99,102,241,.08);
        }

        .label{
            font-size:13px;
            font-weight:500;
            color:#374151;
            margin-bottom:6px;
            display:block;
        }

        .section-title{
            font-size:15px;
            font-weight:700;
            color:#111827;
            margin-bottom:18px;
        }

        .btn{
            width:100%;
            padding:15px;
            border-radius:14px;
            background:linear-gradient(135deg,#6366f1,#4f46e5);
            color:white;
            font-weight:600;
            font-size:15px;
            transition:.2s;
        }

        .btn:hover{
            transform:translateY(-1px);
            box-shadow:0 10px 25px rgba(79,70,229,.2);
        }

        .badge{
            display:inline-flex;
            align-items:center;
            gap:8px;
            background:rgba(255,255,255,.12);
            border:1px solid rgba(255,255,255,.15);
            padding:10px 14px;
            border-radius:999px;
            font-size:13px;
            margin-bottom:24px;
        }

        .feature{
            display:flex;
            gap:12px;
            margin-bottom:18px;
        }

        .feature-icon{
            width:38px;
            height:38px;
            border-radius:12px;
            background:rgba(255,255,255,.12);
            display:flex;
            align-items:center;
            justify-content:center;
            flex-shrink:0;
        }

        .feature-title{
            font-size:14px;
            font-weight:600;
        }

        .feature-desc{
            font-size:13px;
            opacity:.8;
            margin-top:2px;
        }

        .info-box{
            background:#f8fafc;
            border:1px solid #e2e8f0;
            border-radius:16px;
            padding:18px;
        }

        input[type=file]{
            background:white;
            border:1px dashed #c7d2fe;
        }

    </style>
</head>

<body>

<div class="container min-h-screen flex items-center py-10 px-4">

    <div class="card w-full grid lg:grid-cols-2">

        <!-- LEFT -->
        <div class="left-side hidden lg:block">

            <div class="badge">
                ✅ Verifikasi oleh Skilloka
            </div>

            <h1 class="text-4xl font-extrabold leading-tight mb-5">
                Gabung Menjadi
                Mitra LPK Skilloka
            </h1>

            <p class="text-indigo-100 leading-relaxed mb-10">
                Bangun pelatihan terpercaya bersama platform
                Skilloka untuk wilayah Indramayu dan sekitarnya.
            </p>

            <div class="space-y-5">

                <div class="feature">

                    <div class="feature-icon">
                        🛡️
                    </div>

                    <div>
                        <div class="feature-title">
                            LPK Terverifikasi
                        </div>

                        <div class="feature-desc">
                            Seluruh mitra diverifikasi untuk menjaga
                            keamanan dan kepercayaan siswa.
                        </div>
                    </div>

                </div>



                <div class="feature">

                    <div class="feature-icon">
                        📚
                    </div>

                    <div>
                        <div class="feature-title">
                            Kelola Kursus Profesional
                        </div>

                        <div class="feature-desc">
                            Buat jadwal, kelola siswa,
                            dan monitoring booking dalam satu dashboard.
                        </div>
                    </div>

                </div>



                <div class="feature">

                    <div class="feature-icon">
                        💬
                    </div>

                    <div>
                        <div class="feature-title">
                            Terhubung Langsung dengan Siswa
                        </div>

                        <div class="feature-desc">
                            Siswa dapat menghubungi admin LPK melalui WhatsApp
                            untuk meningkatkan kepercayaan.
                        </div>
                    </div>

                </div>

            </div>

        </div>



        <!-- RIGHT -->
        <div class="p-8 lg:p-10">

            <div class="mb-8">

                <h2 class="text-2xl font-bold text-gray-900">
                    Pendaftaran Mitra LPK
                </h2>

                <p class="text-gray-500 mt-2 text-sm">
                    Lengkapi data berikut untuk mengajukan verifikasi LPK di Skilloka.
                </p>

            </div>



            @if($errors->any())

                <div class="mb-6 bg-red-50 border border-red-200 text-red-600 rounded-xl p-4 text-sm">

                    <ul class="space-y-1">

                        @foreach($errors->all() as $error)

                            <li>
                                • {{ $error }}
                            </li>

                        @endforeach

                    </ul>

                </div>

            @endif



            <form method="POST"
      action="{{ route('admin.register.submit') }}">

                @csrf



                <!-- ADMIN -->
                <div class="mb-8">

                    <div class="section-title">
                        Informasi Admin
                    </div>

                    <div class="space-y-5">

                        <div>

                            <label class="label">
                                Nama Admin
                            </label>

                            <input
                                type="text"
                                name="name"
                                value="{{ old('name') }}"
                                class="input"
                                placeholder="Nama lengkap admin"
                                required>

                        </div>



                        <div>

                            <label class="label">
                                Email Admin
                            </label>

                            <input
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                class="input"
                                placeholder="admin@lpk.com"
                                required>

                        </div>



                        <div>

                            <label class="label">
                                Nomor WhatsApp Admin
                            </label>

                            <input
                                type="text"
                                name="phone"
                                value="{{ old('phone') }}"
                                class="input"
                                placeholder="08xxxxxxxxxx"
                                required>

                        </div>



                        <div class="grid md:grid-cols-2 gap-5">

                            <div>

                                <label class="label">
                                    Password
                                </label>

                                <input
                                    type="password"
                                    name="password"
                                    class="input"
                                    placeholder="Minimal 8 karakter"
                                    required>

                            </div>



                            <div>

                                <label class="label">
                                    Konfirmasi Password
                                </label>

                                <input
                                    type="password"
                                    name="password_confirmation"
                                    class="input"
                                    placeholder="Ulangi password"
                                    required>

                            </div>

                        </div>

                    </div>

                </div>



                <!-- LPK -->
                <div class="mb-8">

                    <div class="section-title">
                        Informasi LPK
                    </div>

                    <div class="space-y-5">

                        <div>

                            <label class="label">
                                Nama LPK
                            </label>

                            <input
                                type="text"
                                name="lpk_name"
                                value="{{ old('lpk_name') }}"
                                class="input"
                                placeholder="Contoh: LPK Sakura Indonesia"
                                required>

                        </div>



                        <div>

                            <label class="label">
                                Nama Legal / Yayasan
                            </label>

                            <input
                                type="text"
                                name="legal_name"
                                value="{{ old('legal_name') }}"
                                class="input"
                                placeholder="Nama legal resmi"
                                required>

                        </div>



                        <div class="grid md:grid-cols-2 gap-5">

                            <div>

                                <label class="label">
                                    NIB
                                </label>

                                <input
                                    type="text"
                                    name="nib"
                                    value="{{ old('nib') }}"
                                    class="input"
                                    placeholder="Nomor Induk Berusaha"
                                    required>

                            </div>



                            <div>

                                <label class="label">
                                    Email LPK
                                </label>

                                <input
                                    type="email"
                                    name="lpk_email"
                                    value="{{ old('lpk_email') }}"
                                    class="input"
                                    placeholder="info@lpk.com">

                            </div>

                        </div>



                        <div>

                            <label class="label">
                                Alamat Lengkap
                            </label>

                            <textarea
                                name="address"
                                class="input h-28 resize-none"
                                placeholder="Alamat lengkap LPK"
                                required>{{ old('address') }}</textarea>

                        </div>



                        <div class="grid md:grid-cols-2 gap-5">

                            <div>

                                <label class="label">
                                    Kota / Kabupaten
                                </label>

                                <input
                                    type="text"
                                    name="city"
                                    value="{{ old('city') }}"
                                    class="input"
                                    placeholder="Indramayu"
                                    required>

                            </div>



                            <div>

                                <label class="label">
                                    Instagram
                                </label>

                                <input
                                    type="text"
                                    name="instagram"
                                    value="{{ old('instagram') }}"
                                    class="input"
                                    placeholder="@lpkanda">

                            </div>

                        </div>



                        <div>

                            <label class="label">
                                Deskripsi LPK
                            </label>

                            <textarea
                                name="description"
                                class="input h-32 resize-none"
                                placeholder="Ceritakan singkat tentang LPK Anda"
                                required>{{ old('description') }}</textarea>

                        </div>



                        <div>

                            <label class="label">
                                Fasilitas LPK
                            </label>

                            <textarea
                                name="facilities"
                                class="input h-28 resize-none"
                                placeholder="AC, Wifi, Sertifikat, Asrama, dll">{{ old('facilities') }}</textarea>

                        </div>

                    </div>

                </div>



                <!-- INFO -->
                <div class="info-box mb-8">

                    <div class="flex items-start gap-3">

                        <div class="text-lg">
                            ⏳
                        </div>

                        <div>

                            <h4 class="font-semibold text-gray-800 mb-1">
                                Menunggu Verifikasi Skilloka
                            </h4>

                            <p class="text-sm text-gray-600 leading-relaxed">

                                Seluruh data LPK akan diperiksa terlebih dahulu
                                oleh tim Skilloka sebelum akun dapat digunakan.

                            </p>

                        </div>

                    </div>

                </div>



                {{-- AGREEMENT --}}
<div class="mb-8 flex items-start gap-3">
    <input
        type="checkbox"
        id="persetujuan"
        required
        class="mt-1 rounded border-gray-300"
        onchange="toggleBtn(this)">

    <p class="text-sm text-gray-600 leading-relaxed">
        Saya menyatakan bahwa seluruh data yang diberikan benar
        dan bersedia diverifikasi oleh tim Skilloka.
    </p>
</div>

{{-- BUTTON --}}
<button id="btnKirim" class="btn opacity-50 cursor-not-allowed" disabled>
    Kirim Pengajuan Verifikasi
</button>

            </form>



            <!-- LOGIN -->
            <div class="text-center mt-8 text-sm text-gray-500">

                Sudah terdaftar sebagai mitra LPK?

                <a href="{{ route('admin.login') }}"
                   class="text-indigo-600 font-semibold hover:underline">

                    Login di sini

                </a>

            </div>

        </div>

    </div>

</div>
<script>
function toggleBtn(checkbox) {
    const btn = document.getElementById('btnKirim');
    if (checkbox.checked) {
        btn.disabled = false;
        btn.classList.remove('opacity-50', 'cursor-not-allowed');
    } else {
        btn.disabled = true;
        btn.classList.add('opacity-50', 'cursor-not-allowed');
    }
}
</script>

</body>
</html>