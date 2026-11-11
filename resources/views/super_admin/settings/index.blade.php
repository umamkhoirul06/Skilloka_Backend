@extends('layouts.super_admin')

@section('title', 'Pengaturan Sistem')

@section('content')
<div class="space-y-6 max-w-5xl">

    <!-- ═══════════════════════════════════════
         HEADER SECTION
    ═══════════════════════════════════════ -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                <span>Keuangan & Sistem</span>
                <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
                <span class="text-slate-700 font-medium">Pengaturan</span>
            </div>
            <h1 class="font-outfit text-2xl font-bold text-slate-900 tracking-tight">Pengaturan Sistem Platform</h1>
            <p class="text-xs text-slate-500 mt-1">Konfigurasi parameter operasional, identitas aplikasi, dan skema monetisasi bagi hasil platform.</p>
        </div>

        <div class="flex items-center gap-2">
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                Sistem Siap Operasi
            </span>
        </div>
    </div>

    <!-- Alert Notifikasi -->
    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-medium flex items-center justify-between shadow-2xs">
            <div class="flex items-center gap-2.5">
                <div class="w-7 h-7 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-circle-check text-sm"></i>
                </div>
                <div>
                    <span class="font-bold block">Berhasil Disimpan!</span>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700 text-base px-1">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-medium shadow-2xs space-y-1">
            <div class="flex items-center gap-2 font-bold mb-1">
                <i class="fa-solid fa-triangle-exclamation"></i>
                <span>Terdapat kesalahan pengisian:</span>
            </div>
            <ul class="list-disc list-inside space-y-0.5 text-rose-700">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('super.settings.update') }}" class="space-y-6">
        @csrf

        <!-- ═══════════════════════════════════════
             1. IDENTITAS & KONTAK PLATFORM
        ═══════════════════════════════════════ -->
        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-2xs overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex items-center gap-3 bg-slate-50/50">
                <div class="w-9 h-9 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-sm shadow-2xs">
                    <i class="fa-solid fa-sliders"></i>
                </div>
                <div>
                    <h2 class="font-outfit font-bold text-sm sm:text-base text-slate-900">Identitas & Operasional Platform</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Pengaturan informasi umum yang digunakan pada nama aplikasi dan kanal komunikasi.</p>
                </div>
            </div>

            <div class="p-6 space-y-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- Nama Platform -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">
                            Nama Platform <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <i class="fa-solid fa-cube absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                            <input type="text" 
                                   name="platform_name" 
                                   value="{{ old('platform_name', $setting->platform_name) }}"
                                   required
                                   placeholder="Contoh: Skilloka"
                                   class="w-full pl-9 pr-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 placeholder-slate-400 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition shadow-2xs">
                        </div>
                        <p class="text-[11px] text-slate-400 mt-1">Nama resmi aplikasi yang tampil pada navbar, judul web, dan laporan.</p>
                    </div>

                    <!-- Email Support -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">
                            Email Dukungan / Support <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <i class="fa-regular fa-envelope absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                            <input type="email" 
                                   name="support_email" 
                                   value="{{ old('support_email', $setting->support_email) }}"
                                   required
                                   placeholder="support@skilloka.com"
                                   class="w-full pl-9 pr-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 placeholder-slate-400 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition shadow-2xs">
                        </div>
                        <p class="text-[11px] text-slate-400 mt-1">Alamat email pusat kendali bantuan bagi mitra LPK dan pengguna mobile.</p>
                    </div>

                    <!-- Timezone -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">
                            Zona Waktu Operasional (Timezone) <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <i class="fa-solid fa-clock absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                            <select name="timezone" 
                                    class="w-full pl-9 pr-8 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition shadow-2xs appearance-none">
                                <option value="Asia/Jakarta" {{ $setting->timezone == 'Asia/Jakarta' ? 'selected' : '' }}>
                                    Asia/Jakarta (WIB - UTC+7)
                                </option>
                                <option value="Asia/Makassar" {{ $setting->timezone == 'Asia/Makassar' ? 'selected' : '' }}>
                                    Asia/Makassar (WITA - UTC+8)
                                </option>
                                <option value="Asia/Jayapura" {{ $setting->timezone == 'Asia/Jayapura' ? 'selected' : '' }}>
                                    Asia/Jayapura (WIT - UTC+9)
                                </option>
                            </select>
                            <i class="fa-solid fa-chevron-down absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-[10px] pointer-events-none"></i>
                        </div>
                        <p class="text-[11px] text-slate-400 mt-1">Standar waktu pencatatan jadwal kelas, presensi, dan riwayat transaksi.</p>
                    </div>

                    <!-- Bahasa -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">
                            Bahasa Standar Antarmuka <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <i class="fa-solid fa-globe absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                            <select name="language" 
                                    class="w-full pl-9 pr-8 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition shadow-2xs appearance-none">
                                <option value="Indonesia" {{ $setting->language == 'Indonesia' ? 'selected' : '' }}>
                                    Bahasa Indonesia (Utama)
                                </option>
                                <option value="English" {{ $setting->language == 'English' ? 'selected' : '' }}>
                                    English (US)
                                </option>
                            </select>
                            <i class="fa-solid fa-chevron-down absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-[10px] pointer-events-none"></i>
                        </div>
                        <p class="text-[11px] text-slate-400 mt-1">Bahasa pengantar sistem dan format mata uang lokal.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- ═══════════════════════════════════════
             2. SKEMA MONETISASI & BAGI HASIL
        ═══════════════════════════════════════ -->
        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-2xs overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex items-center gap-3 bg-slate-50/50">
                <div class="w-9 h-9 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-sm shadow-2xs">
                    <i class="fa-solid fa-percent"></i>
                </div>
                <div>
                    <h2 class="font-outfit font-bold text-sm sm:text-base text-slate-900">Skema Bagi Hasil Platform (Platform Fee)</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Persentase potongan komisi yang diambil platform dari setiap transaksi pendaftaran kursus.</p>
                </div>
            </div>

            <div class="p-6 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">
                            Persentase Potongan Platform Fee (%) <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="number" 
                                   name="platform_fee" 
                                   id="inputPlatformFee"
                                   min="0" 
                                   max="100" 
                                   step="0.5"
                                   value="{{ old('platform_fee', $setting->platform_fee) }}"
                                   required
                                   class="w-full pl-3.5 pr-10 py-2.5 bg-white border border-slate-200 rounded-xl text-sm font-extrabold text-slate-900 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition shadow-2xs">
                            <span class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs font-bold">%</span>
                        </div>
                        <p class="text-[11px] text-slate-500 mt-1.5 leading-relaxed">
                            <i class="fa-solid fa-circle-info text-indigo-500 mr-0.5"></i>
                            Nilai ini otomatis dipakai pada menu <strong>Keuangan Platform</strong> untuk menghitung alokasi kas platform dari Gross Merchandise Value (GMV).
                        </p>
                    </div>

                    <!-- Card Simulasi Pembagian Hasil -->
                    <div class="p-4 rounded-xl border border-indigo-100 bg-indigo-50/40 space-y-3">
                        <div class="flex items-center justify-between text-xs font-bold text-indigo-900">
                            <span class="flex items-center gap-1.5">
                                <i class="fa-solid fa-calculator text-indigo-600"></i>
                                <span>Simulasi Pembagian Transaksi:</span>
                            </span>
                            <span class="text-slate-500 font-normal">Contoh Kursus Rp 1.000.000</span>
                        </div>

                        <div class="grid grid-cols-2 gap-3 pt-1">
                            <div class="p-3 rounded-lg bg-white border border-indigo-100/80 shadow-2xs">
                                <span class="text-[10px] uppercase font-bold text-slate-400 block">Bagian Platform (<span id="simFeePercent">{{ $setting->platform_fee }}</span>%)</span>
                                <div class="font-outfit font-extrabold text-indigo-600 text-sm mt-0.5" id="simPlatformFee">
                                    Rp {{ number_format(1000000 * ($setting->platform_fee / 100), 0, ',', '.') }}
                                </div>
                            </div>
                            <div class="p-3 rounded-lg bg-white border border-indigo-100/80 shadow-2xs">
                                <span class="text-[10px] uppercase font-bold text-slate-400 block">Hak Payout LPK (<span id="simLpkPercent">{{ 100 - $setting->platform_fee }}</span>%)</span>
                                <div class="font-outfit font-extrabold text-emerald-600 text-sm mt-0.5" id="simLpkPayout">
                                    Rp {{ number_format(1000000 * ((100 - $setting->platform_fee) / 100), 0, ',', '.') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ═══════════════════════════════════════
             3. JALUR & METODE PEMBAYARAN
        ═══════════════════════════════════════ -->
        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-2xs overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex items-center gap-3 bg-slate-50/50">
                <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-sm shadow-2xs">
                    <i class="fa-solid fa-credit-card"></i>
                </div>
                <div>
                    <h2 class="font-outfit font-bold text-sm sm:text-base text-slate-900">Saluran & Metode Pembayaran</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Konfigurasi saluran gateway yang dapat digunakan siswa saat checkout pendaftaran.</p>
                </div>
            </div>

            <div class="p-6 space-y-4">
                <div class="max-w-md">
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">
                        Saluran Pembayaran Aktif <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <i class="fa-solid fa-building-columns absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                        <select name="payment_method" 
                                class="w-full pl-9 pr-8 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition shadow-2xs appearance-none">
                            <option value="Transfer Bank" {{ $setting->payment_method == 'Transfer Bank' ? 'selected' : '' }}>
                                Transfer Bank Manual (Konfirmasi Pembayaran)
                            </option>
                            <option value="E-Wallet" {{ $setting->payment_method == 'E-Wallet' ? 'selected' : '' }}>
                                Dompet Digital (E-Wallet)
                            </option>
                            <option value="Midtrans Payment Gateway (QRIS, VA, E-Wallet)" {{ in_array($setting->payment_method, ['Midtrans Payment Gateway (QRIS, VA, E-Wallet)', 'Midtrans']) ? 'selected' : '' }}>
                                Midtrans Payment Gateway (Otomatis: QRIS, VA, GoPay, ShopeePay)
                            </option>
                        </select>
                        <i class="fa-solid fa-chevron-down absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-[10px] pointer-events-none"></i>
                    </div>
                    <p class="text-[11px] text-slate-400 mt-1">Metode checkout utama yang diprioritaskan untuk pemesanan kursus.</p>
                </div>
            </div>
        </div>

        <!-- ═══════════════════════════════════════
             SUBMIT BUTTON
        ═══════════════════════════════════════ -->
        <div class="flex items-center justify-end gap-3 pt-2">
            <button type="submit" 
                    class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white font-semibold rounded-xl text-xs shadow-md shadow-indigo-600/20 transition flex items-center gap-2">
                <i class="fa-solid fa-floppy-disk text-xs"></i>
                <span>Simpan Seluruh Pengaturan</span>
            </button>
        </div>

    </form>

</div>

<script>
    // Live update simulasi fee saat user mengetik angka fee
    const feeInput = document.getElementById('inputPlatformFee');
    const simFeePercent = document.getElementById('simFeePercent');
    const simLpkPercent = document.getElementById('simLpkPercent');
    const simPlatformFee = document.getElementById('simPlatformFee');
    const simLpkPayout = document.getElementById('simLpkPayout');

    if (feeInput) {
        feeInput.addEventListener('input', function() {
            let fee = parseFloat(this.value) || 0;
            if (fee < 0) fee = 0;
            if (fee > 100) fee = 100;

            const lpk = 100 - fee;
            simFeePercent.textContent = fee;
            simLpkPercent.textContent = lpk;

            const sample = 1000000;
            const feeVal = Math.round(sample * (fee / 100));
            const lpkVal = sample - feeVal;

            simPlatformFee.textContent = 'Rp ' + feeVal.toLocaleString('id-ID');
            simLpkPayout.textContent = 'Rp ' + lpkVal.toLocaleString('id-ID');
        });
    }
</script>
@endsection
