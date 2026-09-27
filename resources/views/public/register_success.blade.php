@extends('layouts.public')

@section('title', 'Pendaftaran LPK Berhasil - Skilloka')

@section('content')
<div class="py-20 max-w-xl mx-auto px-4 sm:px-6 text-center">
    <div class="bg-white p-8 sm:p-12 rounded-3xl border border-slate-200 shadow-xl space-y-6">
        <div class="w-20 h-20 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-3xl mx-auto shadow-inner">
            <i class="fa-solid fa-circle-check"></i>
        </div>

        <div>
            <span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200 mb-3">
                Status: Menunggu Verifikasi
            </span>
            <h1 class="font-outfit text-2xl sm:text-3xl font-extrabold text-slate-900">Pendaftaran Berhasil Dikirim!</h1>
            <p class="text-xs sm:text-sm text-slate-600 mt-3 leading-relaxed">
                Terima kasih telah mendaftarkan Lembaga Pelatihan Kerja Anda di Skilloka. Pengajuan Anda telah masuk ke <strong>Antrean Verifikasi Super Admin</strong>.
            </p>
        </div>

        <div class="p-5 rounded-2xl bg-slate-50 border border-slate-100 text-left text-xs text-slate-600 space-y-2">
            <div class="font-bold text-slate-800 text-xs mb-1">Tahap Selanjutnya:</div>
            <div class="flex items-start gap-2">
                <i class="fa-solid fa-check text-brand-600 mt-0.5"></i>
                <span>Tim verifikasi akan meninjau data legalitas NIB dan perizinan lembaga Anda (1x24 jam).</span>
            </div>
            <div class="flex items-start gap-2">
                <i class="fa-solid fa-check text-brand-600 mt-0.5"></i>
                <span>Setelah disetujui, akun Admin LPK Anda akan aktif dan dapat langsung mempublikasikan kelas pelatihan.</span>
            </div>
        </div>

        <div class="pt-4 flex flex-col sm:flex-row gap-3">
            <a href="{{ route('admin.login') }}" class="flex-1 py-3 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs shadow transition">
                Masuk ke Portal Login
            </a>
            <a href="{{ route('home') }}" class="flex-1 py-3 rounded-xl border border-slate-200 hover:bg-slate-50 text-slate-700 font-bold text-xs transition">
                Kembali ke Beranda
            </a>
        </div>
    </div>
</div>
@endsection
