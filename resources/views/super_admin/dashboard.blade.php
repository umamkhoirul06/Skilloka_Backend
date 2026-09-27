@extends('layouts.super_admin')

@section('title', 'Platform Control & Monitoring')

@section('content')

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- ═══════════════════════════════════════
     HEADER GREETING & VERIFICATION ALERT
═══════════════════════════════════════ -->
<div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h2 class="font-outfit text-2xl font-black text-slate-900 tracking-tight">Ringkasan Platform Skilloka</h2>
        <p class="text-sm text-slate-500 mt-1">Pemantauan ekosistem pelatihan kerja LPK di wilayah Kabupaten Indramayu.</p>
    </div>

    @if(($pendingVerifications ?? 0) > 0)
        <a href="{{ route('super.verifications') }}"
           class="inline-flex items-center gap-2.5 px-4 py-2.5 rounded-2xl bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold text-xs shadow-md shadow-amber-500/20 transition-all">
            <span class="w-2 h-2 rounded-full bg-slate-950 animate-ping"></span>
            <span>Ada {{ $pendingVerifications }} LPK Menunggu Verifikasi Dokumen</span>
            <i class="fa-solid fa-arrow-right text-[11px]"></i>
        </a>
    @else
        <div class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold">
            <i class="fa-solid fa-circle-check text-emerald-600"></i>
            <span>Seluruh antrean verifikasi telah terselesaikan.</span>
        </div>
    @endif
</div>

<!-- ═══════════════════════════════════════
     SUMMARY METRICS (4 CARDS)
═══════════════════════════════════════ -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
    
    <!-- Total LPK -->
    <div class="p-6 rounded-3xl bg-white border border-slate-200/90 shadow-sm hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Lembaga</span>
            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-base">
                <i class="fa-solid fa-building-columns"></i>
            </div>
        </div>
        <div class="font-outfit text-3xl font-extrabold text-slate-900 mt-3">{{ number_format($totalLpk ?? 0) }}</div>
        <div class="text-xs font-semibold text-emerald-600 mt-2 flex items-center gap-1">
            <i class="fa-solid fa-circle-check"></i>
            <span>{{ $verifiedLpk ?? 0 }} terverifikasi aktif</span>
        </div>
    </div>

    <!-- Total Courses -->
    <div class="p-6 rounded-3xl bg-white border border-slate-200/90 shadow-sm hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Program</span>
            <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-base">
                <i class="fa-solid fa-shapes"></i>
            </div>
        </div>
        <div class="font-outfit text-3xl font-extrabold text-slate-900 mt-3">{{ number_format($totalCourses ?? 0) }}</div>
        <div class="text-xs font-semibold text-indigo-600 mt-2 flex items-center gap-1">
            <i class="fa-solid fa-layer-group"></i>
            <span>Kursus & pelatihan kerja</span>
        </div>
    </div>

    <!-- Total Users -->
    <div class="p-6 rounded-3xl bg-white border border-slate-200/90 shadow-sm hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Pengguna</span>
            <div class="w-10 h-10 rounded-xl bg-violet-50 text-violet-600 flex items-center justify-center text-base">
                <i class="fa-solid fa-users"></i>
            </div>
        </div>
        <div class="font-outfit text-3xl font-extrabold text-slate-900 mt-3">{{ number_format($totalUsers ?? 0) }}</div>
        <div class="text-xs font-semibold text-violet-600 mt-2 flex items-center gap-1">
            <i class="fa-solid fa-user-check"></i>
            <span>Akun pencari kerja & siswa</span>
        </div>
    </div>

    <!-- Pending Verification -->
    <div class="p-6 rounded-3xl {{ ($pendingVerifications ?? 0) > 0 ? 'bg-amber-50/70 border-amber-200' : 'bg-white border-slate-200/90' }} border shadow-sm hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between">
            <span class="text-xs font-bold uppercase tracking-wider {{ ($pendingVerifications ?? 0) > 0 ? 'text-amber-800' : 'text-slate-400' }}">Verifikasi LPK</span>
            <div class="w-10 h-10 rounded-xl {{ ($pendingVerifications ?? 0) > 0 ? 'bg-amber-100 text-amber-700' : 'bg-slate-100 text-slate-500' }} flex items-center justify-center text-base">
                <i class="fa-solid fa-shield-halved"></i>
            </div>
        </div>
        <div class="font-outfit text-3xl font-extrabold {{ ($pendingVerifications ?? 0) > 0 ? 'text-amber-900' : 'text-slate-900' }} mt-3">
            {{ number_format($pendingVerifications ?? 0) }}
        </div>
        <div class="text-xs font-semibold {{ ($pendingVerifications ?? 0) > 0 ? 'text-amber-800' : 'text-slate-500' }} mt-2 flex items-center gap-1">
            @if(($pendingVerifications ?? 0) > 0)
                <i class="fa-solid fa-clock text-amber-600"></i>
                <span>Menunggu persetujuan Anda</span>
            @else
                <i class="fa-solid fa-check text-emerald-500"></i>
                <span>Semua LPK tervalidasi</span>
            @endif
        </div>
    </div>

</div>

<!-- ═══════════════════════════════════════
     HIGH-UTILITY CHARTS (PRD: MAX 1-2 CHARTS)
═══════════════════════════════════════ -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    
    <!-- Chart 1: Pertumbuhan Platform (2 Cols) -->
    <div class="lg:col-span-2 p-6 sm:p-7 rounded-3xl bg-white border border-slate-200/90 shadow-sm">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
            <div>
                <h3 class="font-outfit text-base font-bold text-slate-900">Pertumbuhan Ekosistem Pelatihan</h3>
                <p class="text-xs text-slate-500 mt-0.5">Penambahan LPK, Pengguna, dan Kursus tahun {{ date('Y') }}</p>
            </div>
            <div class="flex items-center gap-3 text-xs font-semibold">
                <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span> LPK</span>
                <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-indigo-500"></span> Kursus</span>
                <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-violet-400"></span> Pengguna</span>
            </div>
        </div>
        <div class="h-72 w-full">
            <canvas id="growthChart"></canvas>
        </div>
    </div>

    <!-- Chart 2: Sebaran Kategori Kursus Terpopuler (1 Col) -->
    <div class="p-6 sm:p-7 rounded-3xl bg-white border border-slate-200/90 shadow-sm flex flex-col justify-between">
        <div class="mb-4">
            <h3 class="font-outfit text-base font-bold text-slate-900">Kategori Kejuruan Populer</h3>
            <p class="text-xs text-slate-500 mt-0.5">Distribusi program kursus per kategori</p>
        </div>
        <div class="h-56 w-full flex items-center justify-center my-auto">
            <canvas id="categoryDonutChart"></canvas>
        </div>
        <div class="pt-4 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
            <span>Total Kategori: {{ count($categoryDistribution ?? []) }}</span>
            <a href="{{ route('super.categories.index') }}" class="font-bold text-emerald-600 hover:text-emerald-800">
                Kelola Kategori &rarr;
            </a>
        </div>
    </div>

</div>

<!-- ═══════════════════════════════════════
     ACTIONABLE SECTION: ANTREAN VERIFIKASI PENDING
═══════════════════════════════════════ -->
<div class="p-6 sm:p-7 rounded-3xl bg-white border border-slate-200/90 shadow-sm">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
        <div>
            <h3 class="font-outfit text-base font-bold text-slate-900">Antrean Verifikasi Legalitas LPK</h3>
            <p class="text-xs text-slate-500 mt-0.5">Lembaga yang baru mendaftar dan menunggu pemeriksaan NIB & izin operasional</p>
        </div>
        <a href="{{ route('super.verifications') }}" class="text-xs font-bold text-emerald-600 hover:text-emerald-800 transition-colors">
            Buka Semua Antrean &rarr;
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs">
            <thead>
                <tr class="border-b border-slate-100 text-[11px] uppercase tracking-wider text-slate-400 font-bold">
                    <th class="py-3 px-3">Nama Lembaga (LPK)</th>
                    <th class="py-3 px-3">Badan Hukum & NIB</th>
                    <th class="py-3 px-3">Kontak Admin</th>
                    <th class="py-3 px-3">Wilayah</th>
                    <th class="py-3 px-3 text-right">Tindakan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($pendingLpks as $lpk)
                    @php
                        $adminUser = $lpk->tenant ? $lpk->tenant->users->first() : null;
                    @endphp
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="py-3.5 px-3">
                            <div class="font-bold text-slate-900 text-sm">{{ $lpk->name ?? '-' }}</div>
                            <div class="text-[11px] text-slate-400 mt-0.5">Diajukan: {{ $lpk->created_at->format('d M Y H:i') }}</div>
                        </td>
                        <td class="py-3.5 px-3">
                            <div class="font-semibold text-slate-800">{{ $lpk->legal_name ?? '-' }}</div>
                            <div class="text-[11px] text-indigo-600 font-mono mt-0.5">NIB: {{ $lpk->nib ?? '-' }}</div>
                        </td>
                        <td class="py-3.5 px-3">
                            <div class="font-semibold text-slate-800">{{ $adminUser->name ?? '-' }}</div>
                            <div class="text-[11px] text-slate-500">{{ $adminUser->phone ?? '-' }}</div>
                        </td>
                        <td class="py-3.5 px-3">
                            <span class="inline-flex items-center gap-1 text-slate-600 font-medium">
                                <i class="fa-solid fa-location-dot text-slate-400 text-[10px]"></i>
                                {{ $lpk->tenant->city ?? 'Indramayu' }}
                            </span>
                        </td>
                        <td class="py-3.5 px-3 text-right">
                            <a href="{{ route('super.verifications.show', $lpk->tenant_id ?? '') }}"
                               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-sm transition-all">
                                <span>Tinjau & Verifikasi</span>
                                <i class="fa-solid fa-arrow-right text-[10px]"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-10 text-center text-slate-400">
                            <i class="fa-solid fa-circle-check text-3xl mb-2 text-emerald-400 block"></i>
                            <span class="font-semibold text-slate-600 block">Semua Permohonan Terverifikasi!</span>
                            <span class="text-xs text-slate-400">Tidak ada pengajuan LPK yang tertahan di antrean saat ini.</span>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- ═══════════════════════════════════════
     CHART.JS SCRIPTS
═══════════════════════════════════════ -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        Chart.defaults.font.family = "'Inter', sans-serif";
        Chart.defaults.color = '#64748b';

        // 1. Line Growth Chart
        const ctxGrowth = document.getElementById('growthChart').getContext('2d');
        new Chart(ctxGrowth, {
            type: 'line',
            data: {
                labels: @json($monthlyLabels),
                datasets: [
                    {
                        label: 'LPK Baru',
                        data: @json($monthlyLpk),
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        fill: true,
                        tension: 0.35,
                        borderWidth: 2
                    },
                    {
                        label: 'Kursus Baru',
                        data: @json($monthlyCourses),
                        borderColor: '#6366f1',
                        backgroundColor: 'transparent',
                        tension: 0.35,
                        borderWidth: 2
                    },
                    {
                        label: 'Pengguna',
                        data: @json($monthlyUsers),
                        borderColor: '#8b5cf6',
                        backgroundColor: 'transparent',
                        tension: 0.35,
                        borderWidth: 2
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#090d16',
                        titleFont: { size: 12, weight: 'bold' },
                        bodyFont: { size: 12 },
                        padding: 10,
                        cornerRadius: 8
                    }
                },
                scales: {
                    x: { grid: { display: false }, ticks: { font: { size: 11 } } },
                    y: { beginAtZero: true, grid: { color: '#f1f5f9' }, ticks: { precision: 0, font: { size: 11 } } }
                }
            }
        });

        // 2. Category Doughnut Chart
        @php
            $catNames = [];
            $catCounts = [];
            foreach(($categoryDistribution ?? []) as $cat) {
                $catNames[] = $cat->name;
                $catCounts[] = $cat->courses_count;
            }
            if (empty($catNames)) {
                $catNames = ['Teknologi', 'Otomotif', 'Bahasa', 'Manajemen', 'Lainnya'];
                $catCounts = [4, 3, 2, 2, 1];
            }
        @endphp

        const ctxCat = document.getElementById('categoryDonutChart').getContext('2d');
        new Chart(ctxCat, {
            type: 'doughnut',
            data: {
                labels: @json($catNames),
                datasets: [{
                    data: @json($catCounts),
                    backgroundColor: ['#10b981', '#6366f1', '#f59e0b', '#ec4899', '#06b6d4'],
                    borderWidth: 0,
                    hoverOffset: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 8,
                            boxHeight: 8,
                            usePointStyle: true,
                            font: { size: 10, weight: '600' },
                            padding: 10
                        }
                    }
                }
            }
        });
    });
</script>

@endsection