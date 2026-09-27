<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sertifikat Resmi - {{ $certificate->number }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@500;700;800&family=Inter:wght@400;500;600;700&family=Playfair+Display:ital,wght@0,600;0,700;1,400&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        @media print {
            body {
                background: white !important;
                padding: 0 !important;
                margin: 0 !important;
            }
            .no-print {
                display: none !important;
            }
            .certificate-container {
                border: 8px double #1e3a8a !important;
                box-shadow: none !important;
                width: 100% !important;
                height: 100vh !important;
                page-break-after: always;
            }
        }
        .font-cinzel { font-family: 'Cinzel', serif; }
        .font-playfair { font-family: 'Playfair Display', serif; }
        .font-inter { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-100 min-h-screen py-10 px-4 font-inter text-slate-800 flex flex-col items-center">

    <!-- Action Bar (no-print) -->
    <div class="max-w-4xl w-full flex items-center justify-between mb-6 no-print">
        <a href="{{ route('admin.certificates.index') }}" class="px-4 py-2 bg-white rounded-xl text-xs font-semibold text-slate-700 border border-slate-200 shadow-sm hover:bg-slate-50 transition">
            &larr; Kembali ke Daftar Sertifikat
        </a>
        <div class="flex items-center gap-3">
            <button onclick="window.print()" class="px-5 py-2.5 bg-brand-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold shadow-md transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                <span>Cetak / Unduh PDF</span>
            </button>
        </div>
    </div>

    <!-- CERTIFICATE CANVAS -->
    <div class="certificate-container max-w-4xl w-full bg-white p-12 sm:p-16 rounded-2xl border-[10px] border-double border-slate-900 shadow-2xl relative overflow-hidden text-center">
        <!-- Corner Ornaments -->
        <div class="absolute top-4 left-4 w-12 h-12 border-t-2 border-l-2 border-amber-600"></div>
        <div class="absolute top-4 right-4 w-12 h-12 border-t-2 border-r-2 border-amber-600"></div>
        <div class="absolute bottom-4 left-4 w-12 h-12 border-b-2 border-l-2 border-amber-600"></div>
        <div class="absolute bottom-4 right-4 w-12 h-12 border-b-2 border-r-2 border-amber-600"></div>

        <!-- Watermark / Background stamp -->
        <div class="absolute inset-0 flex items-center justify-center opacity-5 pointer-events-none">
            <span class="font-cinzel text-9xl font-bold tracking-widest text-slate-900">SKILLOKA</span>
        </div>

        <!-- Top Header -->
        <div class="relative z-10 space-y-2">
            <div class="flex items-center justify-center gap-3">
                <span class="text-xs tracking-[0.25em] font-bold text-amber-600 uppercase">Skilloka Competency Platform</span>
                <span>&bull;</span>
                <span class="text-xs font-semibold text-slate-500 uppercase">{{ $certificate->course->lpk->name ?? 'Lembaga Pelatihan Kerja' }}</span>
            </div>

            <h1 class="font-cinzel text-4xl sm:text-5xl font-bold text-slate-900 tracking-wider pt-2">
                SERTIFIKAT KELULUSAN
            </h1>
            <p class="font-inter text-xs text-slate-400 font-mono tracking-widest uppercase">
                Nomor Registrasi: {{ $certificate->number }}
            </p>
        </div>

        <!-- Body -->
        <div class="relative z-10 mt-10 space-y-4">
            <p class="font-playfair italic text-slate-600 text-base">Sertifikat ini dengan bangga diberikan kepada:</p>

            <h2 class="font-outfit text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight underline decoration-amber-500/50 decoration-2 underline-offset-8">
                {{ strtoupper($certificate->user->name ?? 'Peserta Pelatihan') }}
            </h2>

            <p class="text-xs sm:text-sm text-slate-600 max-w-xl mx-auto leading-relaxed pt-4">
                Telah berhasil menyelesaikan seluruh rangkaian materi, praktik kerja, dan evaluasi kompetensi pada program pelatihan kejuruan:
            </p>

            <div class="py-2">
                <span class="inline-block px-6 py-2 rounded-xl bg-slate-50 border border-slate-200 font-cinzel text-xl sm:text-2xl font-bold text-indigo-950">
                    {{ $certificate->course->title ?? 'Pelatihan Kejuruan' }}
                </span>
            </div>

            <p class="text-xs text-slate-500">
                Diselenggarakan di Kabupaten Indramayu dengan total beban pelatihan <strong>{{ $certificate->course->duration_hours ?? '-' }} Jam Pelajaran</strong>.
            </p>
        </div>

        <!-- Signatures & Verification Stamp -->
        <div class="relative z-10 mt-16 pt-8 border-t border-slate-200 grid grid-cols-3 gap-6 items-end">
            <!-- Left: Issue Date & Expiry -->
            <div class="text-left text-xs text-slate-500 space-y-1">
                <div>Tanggal Terbit: <strong>{{ \Carbon\Carbon::parse($certificate->issue_date)->translatedFormat('d F Y') }}</strong></div>
                @if($certificate->expiry_date)
                    <div>Berlaku Hingga: <strong>{{ \Carbon\Carbon::parse($certificate->expiry_date)->translatedFormat('d F Y') }}</strong></div>
                @else
                    <div>Masa Berlaku: <strong class="text-emerald-700">Seumur Hidup</strong></div>
                @endif
                <div class="text-[10px] text-slate-400 font-mono pt-1">UUID: {{ substr($certificate->id, 0, 18) }}...</div>
            </div>

            <!-- Center: Verification Stamp -->
            <div class="flex flex-col items-center">
                <div class="w-16 h-16 rounded-full border-2 border-emerald-600 flex items-center justify-center text-emerald-600 text-xs font-bold text-center leading-tight shadow-sm">
                    VERIFIED<br>SKILLOKA
                </div>
                <span class="text-[10px] text-slate-400 mt-1 uppercase font-semibold">Tervalidasi Digital</span>
            </div>

            <!-- Right: LPK Signature -->
            <div class="text-right text-xs space-y-1">
                <div class="text-slate-500">Direktur / Pimpinan LPK</div>
                <div class="h-12 flex items-center justify-end">
                    <span class="font-playfair italic font-bold text-slate-800 text-base">{{ $certificate->course->lpk->name ?? 'Mitra LPK' }}</span>
                </div>
                <div class="font-bold text-slate-900 border-t border-slate-300 pt-1 inline-block">Penanggung Jawab Lembaga</div>
            </div>
        </div>
    </div>

</body>
</html>
