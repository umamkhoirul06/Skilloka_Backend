@extends('layouts.admin')

@section('title', 'Dashboard Operasional')

@section('content')

@php
    $status = $myLpk ? $myLpk->status_verifikasi : 'pending';
    $latestVerification = $myLpk ? $myLpk->verifications()->latest()->first() : null;
@endphp

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- ═══════════════════════════════════════
     STATUS VERIFIKASI BANNER
═══════════════════════════════════════ -->
@if($status === 'approved')
    <div class="mb-8 p-5 rounded-2xl bg-gradient-to-r from-emerald-50 to-teal-50 border border-emerald-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4 shadow-xs">
        <div class="flex items-start sm:items-center gap-3.5">
            <div class="w-10 h-10 rounded-xl bg-emerald-500 text-white flex items-center justify-center text-lg flex-shrink-0 shadow-sm shadow-emerald-500/20">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <div>
                <h3 class="font-outfit text-base font-bold text-emerald-950">LPK Terverifikasi & Aktif di Direktori Skilloka</h3>
                <p class="text-xs text-emerald-800 mt-0.5">Lembaga Anda telah berizin resmi (NIB: {{ $myLpk->nib ?? '-' }}) dan dapat menerima pemesanan kursus secara publik di Indramayu.</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('public.lpk.detail', $myLpk->id ?? '') }}" target="_blank"
               class="px-4 py-2 rounded-xl bg-white hover:bg-emerald-100/60 text-emerald-900 border border-emerald-300 font-bold text-xs transition-colors flex items-center gap-1.5 shadow-2xs">
                <span>Lihat Profil Publik</span>
                <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
            </a>
            <a href="{{ route('admin.dashboard.pdf') }}"
               class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs transition-colors flex items-center gap-1.5 shadow-sm shadow-emerald-600/20">
                <i class="fa-solid fa-file-pdf"></i>
                <span>Unduh Laporan</span>
            </a>
        </div>
    </div>
@elseif($status === 'rejected')
    <div class="mb-8 p-5 rounded-2xl bg-rose-50 border border-rose-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-start gap-3.5">
            <div class="w-10 h-10 rounded-xl bg-rose-500 text-white flex items-center justify-center text-lg flex-shrink-0 shadow-sm">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <div>
                <h3 class="font-outfit text-base font-bold text-rose-950">Pendaftaran LPK Memerlukan Perbaikan Dokumen</h3>
                <p class="text-xs text-rose-800 mt-0.5">
                    @if($latestVerification && $latestVerification->notes)
                        Catatan Verifikator: <strong>"{{ $latestVerification->notes }}"</strong>
                    @else
                        Dokumen legalitas atau izin operasional belum memenuhi persyaratan verifikasi resmi platform.
                    @endif
                </p>
            </div>
        </div>
        <a href="{{ route('admin.profile') }}" class="px-4 py-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs transition-colors whitespace-nowrap">
            Perbaiki Data LPK
        </a>
    </div>
@else
    <div class="mb-8 p-5 rounded-2xl bg-amber-50 border border-amber-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-start gap-3.5">
            <div class="w-10 h-10 rounded-xl bg-amber-500 text-white flex items-center justify-center text-lg flex-shrink-0 shadow-sm">
                <i class="fa-solid fa-hourglass-half"></i>
            </div>
            <div>
                <h3 class="font-outfit text-base font-bold text-amber-950">Pendaftaran LPK Sedang Ditinjau Super Admin</h3>
                <p class="text-xs text-amber-800 mt-0.5">Proses verifikasi dokumen legalitas membutuhkan 1x24 jam kerja. Anda tetap dapat menyiapkan kurikulum dan jadwal kelas.</p>
            </div>
        </div>
        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-amber-200 text-amber-900 self-start sm:self-auto">
            Status: PENDING REVIEW
        </span>
    </div>
@endif

<!-- ═══════════════════════════════════════
     ACTION-ORIENTED SUMMARY METRICS (4 CARDS)
═══════════════════════════════════════ -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
    
    <!-- Metric 1: Siswa Aktif -->
    <div class="p-6 rounded-3xl bg-white border border-slate-200/90 shadow-sm hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Siswa</span>
            <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-base">
                <i class="fa-solid fa-user-graduate"></i>
            </div>
        </div>
        <div class="font-outfit text-3xl font-extrabold text-slate-900 mt-3">{{ number_format($totalStudents ?? 0) }}</div>
        <div class="text-xs font-semibold text-emerald-600 mt-2 flex items-center gap-1">
            <i class="fa-solid fa-users"></i>
            <span>Peserta terdaftar</span>
        </div>
    </div>

    <!-- Metric 2: Kursus Aktif -->
    <div class="p-6 rounded-3xl bg-white border border-slate-200/90 shadow-sm hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Program Kursus</span>
            <div class="w-10 h-10 rounded-xl bg-violet-50 text-violet-600 flex items-center justify-center text-base">
                <i class="fa-solid fa-layer-group"></i>
            </div>
        </div>
        <div class="font-outfit text-3xl font-extrabold text-slate-900 mt-3">{{ number_format($totalCourses ?? 0) }}</div>
        <div class="text-xs font-semibold text-indigo-600 mt-2 flex items-center gap-1">
            <i class="fa-solid fa-circle-check"></i>
            <span>{{ $activeCourses ?? 0 }} kursus aktif dibuka</span>
        </div>
    </div>

    <!-- Metric 3: Jadwal Mendatang -->
    <div class="p-6 rounded-3xl bg-white border border-slate-200/90 shadow-sm hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Jadwal Kelas</span>
            <div class="w-10 h-10 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center text-base">
                <i class="fa-regular fa-calendar-check"></i>
            </div>
        </div>
        <div class="font-outfit text-3xl font-extrabold text-slate-900 mt-3">{{ number_format($upcomingClasses ?? 0) }}</div>
        <div class="text-xs font-semibold text-teal-600 mt-2 flex items-center gap-1">
            <i class="fa-solid fa-clock"></i>
            <span>Sesi pertemuan mendatang</span>
        </div>
    </div>

    <!-- Metric 4: Booking Butuh Tindakan (Action Card) -->
    <div class="p-6 rounded-3xl {{ ($pendingBookings ?? 0) > 0 ? 'bg-amber-50/60 border-amber-200' : 'bg-white border-slate-200/90' }} border shadow-sm hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between">
            <span class="text-xs font-bold uppercase tracking-wider {{ ($pendingBookings ?? 0) > 0 ? 'text-amber-800' : 'text-slate-400' }}">Antrean Booking</span>
            <div class="w-10 h-10 rounded-xl {{ ($pendingBookings ?? 0) > 0 ? 'bg-amber-100 text-amber-700' : 'bg-slate-100 text-slate-500' }} flex items-center justify-center text-base">
                <i class="fa-solid fa-bell"></i>
            </div>
        </div>
        <div class="font-outfit text-3xl font-extrabold {{ ($pendingBookings ?? 0) > 0 ? 'text-amber-900' : 'text-slate-900' }} mt-3">
            {{ number_format($pendingBookings ?? 0) }}
        </div>
        <div class="text-xs font-semibold {{ ($pendingBookings ?? 0) > 0 ? 'text-amber-800' : 'text-slate-500' }} mt-2 flex items-center gap-1">
            @if(($pendingBookings ?? 0) > 0)
                <i class="fa-solid fa-circle-exclamation text-amber-600"></i>
                <span>Perlu ditinjau segera</span>
            @else
                <i class="fa-solid fa-check text-emerald-500"></i>
                <span>Semua booking tertangani</span>
            @endif
        </div>
    </div>

</div>

<!-- ═══════════════════════════════════════
     HIGH-UTILITY CHARTS (PRD: MAX 1-2 CHARTS)
═══════════════════════════════════════ -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    
    <!-- Chart 1: Tren Pendaftaran Bulanan (2 Cols) -->
    <div class="lg:col-span-2 p-6 sm:p-7 rounded-3xl bg-white border border-slate-200/90 shadow-sm">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
            <div>
                <h3 class="font-outfit text-base font-bold text-slate-900">Tren Pemesanan Pelatihan</h3>
                <p class="text-xs text-slate-500 mt-0.5">Jumlah siswa yang memesan kursus dalam 6 bulan terakhir</p>
            </div>
            <span class="text-xs font-semibold px-3 py-1.5 rounded-xl bg-slate-100 text-slate-600">
                Kabupaten Indramayu
            </span>
        </div>
        <div class="h-72 w-full">
            <canvas id="lineBookingsChart"></canvas>
        </div>
    </div>

    <!-- Chart 2: Distribusi Status Transaksi (1 Col) -->
    <div class="p-6 sm:p-7 rounded-3xl bg-white border border-slate-200/90 shadow-sm flex flex-col justify-between">
        <div class="mb-4">
            <h3 class="font-outfit text-base font-bold text-slate-900">Distribusi Status Peserta</h3>
            <p class="text-xs text-slate-500 mt-0.5">Proporsi pemesanan lunas, menunggu, dan batal</p>
        </div>
        <div class="h-56 w-full flex items-center justify-center my-auto">
            <canvas id="doughnutStatusChart"></canvas>
        </div>
        <div class="grid grid-cols-3 gap-2 pt-4 border-t border-slate-100 text-center text-xs">
            <div>
                <div class="font-bold text-emerald-600">{{ $bookingDistribution['Lunas'] ?? 0 }}</div>
                <div class="text-[11px] text-slate-400 mt-0.5">Lunas</div>
            </div>
            <div>
                <div class="font-bold text-amber-600">{{ $bookingDistribution['Pending'] ?? 0 }}</div>
                <div class="text-[11px] text-slate-400 mt-0.5">Menunggu</div>
            </div>
            <div>
                <div class="font-bold text-rose-600">{{ $bookingDistribution['Batal'] ?? 0 }}</div>
                <div class="text-[11px] text-slate-400 mt-0.5">Batal</div>
            </div>
        </div>
    </div>

</div>

<!-- ═══════════════════════════════════════
     ACTIONABLE SECTION: BOOKING TERBARU & JADWAL TERDEKAT
═══════════════════════════════════════ -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    <!-- Table: Booking Terbaru (2 Cols) -->
    <div class="lg:col-span-2 p-6 rounded-3xl bg-white border border-slate-200/90 shadow-sm">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h3 class="font-outfit text-base font-bold text-slate-900">Pemesanan Kursus Terbaru</h3>
                <p class="text-xs text-slate-500 mt-0.5">Daftar pendaftar kursus yang baru masuk</p>
            </div>
            <a href="{{ route('admin.bookings.index') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 transition-colors">
                Lihat Semua &rarr;
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-slate-100 text-[11px] uppercase tracking-wider text-slate-400 font-bold">
                        <th class="py-3 px-3">Kode & Peserta</th>
                        <th class="py-3 px-3">Kursus</th>
                        <th class="py-3 px-3">Biaya</th>
                        <th class="py-3 px-3">Status</th>
                        <th class="py-3 px-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($recentBookings as $booking)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="py-3 px-3">
                                <div class="font-bold text-slate-900">{{ $booking->code ?? ('#BKG-' . substr($booking->id, 0, 8)) }}</div>
                                <div class="text-slate-500 text-[11px] mt-0.5">{{ $booking->user->name ?? 'Peserta' }}</div>
                            </td>
                            <td class="py-3 px-3">
                                <span class="font-semibold text-slate-800 truncate max-w-xs block">
                                    {{ $booking->course->name ?? 'Kursus LPK' }}
                                </span>
                            </td>
                            <td class="py-3 px-3 font-semibold text-slate-900 whitespace-nowrap">
                                Rp {{ number_format($booking->total_price ?? $booking->amount ?? 0, 0, ',', '.') }}
                            </td>
                            <td class="py-3 px-3">
                                @php
                                    $bStatus = strtolower($booking->status);
                                @endphp
                                @if(in_array($bStatus, ['paid', 'confirmed', 'completed', 'selesai']))
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">Lunas</span>
                                @elseif(in_array($bStatus, ['cancelled', 'dibatalkan']))
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-rose-100 text-rose-800">Batal</span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800">Menunggu</span>
                                @endif
                            </td>
                            <td class="py-3 px-3 text-right">
                                <a href="{{ route('admin.bookings.index') }}" class="p-1.5 rounded-lg bg-indigo-50 hover:bg-indigo-100 text-indigo-600 transition-colors inline-block">
                                    <i class="fa-solid fa-eye text-xs"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-slate-400">
                                <i class="fa-solid fa-inbox text-2xl mb-2 text-slate-300 block"></i>
                                Belum ada pemesanan kursus yang tercatat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Card: Jadwal Terdekat & Quick Actions (1 Col) -->
    <div class="space-y-6">
        
        <!-- Jadwal Kelas Terdekat -->
        <div class="p-6 rounded-3xl bg-white border border-slate-200/90 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-outfit text-base font-bold text-slate-900">Jadwal Kelas Terdekat</h3>
                <a href="{{ route('admin.course-schedules.index') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800">
                    Semua &rarr;
                </a>
            </div>

            <div class="space-y-3">
                @forelse($upcomingSchedules as $sched)
                    <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100">
                        <div class="font-bold text-xs text-slate-900 truncate">{{ $sched->course->name ?? 'Kursus' }}</div>
                        <div class="flex items-center justify-between mt-2 text-[11px] text-slate-500">
                            <span class="flex items-center gap-1.5">
                                <i class="fa-regular fa-calendar text-indigo-500"></i>
                                {{ \Carbon\Carbon::parse($sched->start_date)->translatedFormat('d M Y') }}
                            </span>
                            <span class="font-semibold text-slate-700">
                                {{ $sched->quota ?? $sched->max_participants ?? 20 }} Kuota
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="py-6 text-center text-xs text-slate-400">
                        Tidak ada agenda kelas terdekat.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Shortcut Tindakan Cepat -->
        <div class="p-6 rounded-3xl bg-gradient-to-br from-indigo-900 to-slate-900 text-white shadow-sm">
            <h4 class="font-outfit text-base font-bold text-white mb-2">Tindakan Cepat</h4>
            <p class="text-xs text-indigo-200 mb-4">Akses langsung fungsi operasional harian LPK Anda.</p>

            <div class="grid grid-cols-2 gap-2 text-xs">
                <a href="{{ route('admin.courses.create') }}" class="p-2.5 rounded-xl bg-white/10 hover:bg-white/20 transition-colors text-center font-semibold text-white">
                    <i class="fa-solid fa-plus block text-base mb-1 text-indigo-300"></i>
                    Buka Kursus
                </a>
                <a href="{{ route('admin.course-schedules.create') }}" class="p-2.5 rounded-xl bg-white/10 hover:bg-white/20 transition-colors text-center font-semibold text-white">
                    <i class="fa-regular fa-calendar-plus block text-base mb-1 text-teal-300"></i>
                    Tambah Jadwal
                </a>
                <a href="{{ route('admin.attendance.index') }}" class="p-2.5 rounded-xl bg-white/10 hover:bg-white/20 transition-colors text-center font-semibold text-white">
                    <i class="fa-solid fa-clipboard-check block text-base mb-1 text-emerald-300"></i>
                    Input Presensi
                </a>
                <a href="{{ route('admin.certificates.create') }}" class="p-2.5 rounded-xl bg-white/10 hover:bg-white/20 transition-colors text-center font-semibold text-white">
                    <i class="fa-solid fa-certificate block text-base mb-1 text-amber-300"></i>
                    Terbit Sertifikat
                </a>
            </div>
        </div>

    </div>

</div>

<!-- ═══════════════════════════════════════
     CHART.JS SCRIPTS
═══════════════════════════════════════ -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        Chart.defaults.font.family = "'Inter', sans-serif";
        Chart.defaults.color = '#64748b';

        // 1. Line Chart Pemesanan
        const ctxLine = document.getElementById('lineBookingsChart').getContext('2d');
        const gradientLine = ctxLine.createLinearGradient(0, 0, 0, 300);
        gradientLine.addColorStop(0, 'rgba(79, 70, 229, 0.25)');
        gradientLine.addColorStop(1, 'rgba(79, 70, 229, 0.00)');

        new Chart(ctxLine, {
            type: 'line',
            data: {
                labels: @json($chartLabels),
                datasets: [{
                    label: 'Pemesanan Siswa',
                    data: @json($chartBookings),
                    borderColor: '#4f46e5',
                    backgroundColor: gradientLine,
                    fill: true,
                    tension: 0.35,
                    borderWidth: 2.5,
                    pointBackgroundColor: '#4f46e5',
                    pointHoverRadius: 6,
                    pointRadius: 4,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#0f172a',
                        titleFont: { size: 12, weight: 'bold' },
                        bodyFont: { size: 12 },
                        padding: 10,
                        cornerRadius: 8,
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 11 } }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: '#f1f5f9' },
                        ticks: { precision: 0, font: { size: 11 } }
                    }
                }
            }
        });

        // 2. Doughnut Chart Distribusi Status
        const ctxPie = document.getElementById('doughnutStatusChart').getContext('2d');
        new Chart(ctxPie, {
            type: 'doughnut',
            data: {
                labels: ['Lunas', 'Menunggu', 'Batal'],
                datasets: [{
                    data: [
                        {{ $bookingDistribution['Lunas'] ?? 0 }},
                        {{ $bookingDistribution['Pending'] ?? 0 }},
                        {{ $bookingDistribution['Batal'] ?? 0 }}
                    ],
                    backgroundColor: ['#10b981', '#f59e0b', '#f43f5e'],
                    borderWidth: 0,
                    hoverOffset: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '72%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 10,
                            boxHeight: 10,
                            usePointStyle: true,
                            font: { size: 11, weight: '600' },
                            padding: 14
                        }
                    }
                }
            }
        });
    });
</script>

@endsection