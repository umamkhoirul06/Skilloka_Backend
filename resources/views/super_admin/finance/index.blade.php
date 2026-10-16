@extends('layouts.super_admin')

@section('title', 'Manajemen Keuangan Platform')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="space-y-6">

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="font-outfit text-2xl font-bold text-slate-900 tracking-tight">Manajemen Finansial & Transaksi</h2>
            <p class="text-xs text-slate-500 mt-1">Pemantauan arus kas bruto (GMV), pendapatan komisi platform (5%), serta riwayat pembayaran Midtrans seluruh LPK.</p>
        </div>
        <div class="flex items-center gap-2.5">
            <a href="{{ route('super.finance.pdf') }}" 
               class="shiny-btn shiny-btn-dark px-4 py-2.5 rounded-xl text-white font-bold text-xs flex items-center gap-2">
                <i class="fa-solid fa-file-pdf text-rose-400"></i>
                <span>Unduh Rekap PDF</span>
            </a>
        </div>
    </div>

    <!-- ═══════════════════════════════════════
         FINANCIAL METRICS (4 CARDS)
    ═══════════════════════════════════════ -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- GMV Gross Volume -->
        <div class="p-5 rounded-2xl bg-white border border-slate-200/90 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Transaksi Bruto (GMV)</span>
                <div class="w-8 h-8 rounded-lg bg-brand-50 text-brand-600 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-wallet"></i>
                </div>
            </div>
            <div class="font-outfit text-2xl font-black text-slate-900 mt-2">
                Rp {{ number_format($stats['gross_volume'], 0, ',', '.') }}
            </div>
            <div class="text-[11px] font-semibold text-emerald-600 mt-1 flex items-center gap-1">
                <i class="fa-solid fa-circle-check"></i>
                <span>{{ $stats['success_count'] }} transaksi telah lunas</span>
            </div>
        </div>

        <!-- Platform Revenue (5% Fee) -->
        <div class="p-5 rounded-2xl bg-white border border-slate-200/90 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-brand-600">Pendapatan Platform ({{ $platformFeeRate }}%)</span>
                <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-sack-dollar"></i>
                </div>
            </div>
            <div class="font-outfit text-2xl font-black text-emerald-600 mt-2">
                Rp {{ number_format($stats['platform_fee'], 0, ',', '.') }}
            </div>
            <div class="text-[11px] text-slate-400 mt-1">Komisi otomatis per booking</div>
        </div>

        <!-- LPK Payout Pool (95%) -->
        <div class="p-5 rounded-2xl bg-white border border-slate-200/90 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Hak Pembayaran LPK ({{ 100 - $platformFeeRate }}%)</span>
                <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-building-columns"></i>
                </div>
            </div>
            <div class="font-outfit text-2xl font-black text-indigo-950 mt-2">
                Rp {{ number_format($stats['lpk_payout'], 0, ',', '.') }}
            </div>
            <div class="text-[11px] text-slate-400 mt-1">Disalurkan ke mitra lembaga</div>
        </div>

        <!-- Transaction Ratio -->
        <div class="p-5 rounded-2xl bg-white border border-slate-200/90 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Antrean & Status</span>
                <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-hourglass-half"></i>
                </div>
            </div>
            <div class="font-outfit text-2xl font-black text-slate-900 mt-2">
                {{ $stats['pending_count'] }} <span class="text-xs font-normal text-slate-400">Pending</span>
            </div>
            <div class="text-[11px] text-slate-500 mt-1 flex items-center justify-between">
                <span>Total log: {{ $stats['total_transactions'] }}</span>
                @if($stats['failed_count'] > 0)
                    <span class="text-rose-500 font-bold">{{ $stats['failed_count'] }} gagal/kadaluarsa</span>
                @endif
            </div>
        </div>
    </div>

    <!-- ═══════════════════════════════════════
         CHARTS ROW
    ═══════════════════════════════════════ -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">
        <!-- Monthly Trend Line Chart (8 Cols) -->
        <div class="lg:col-span-8 p-6 rounded-2xl bg-white border border-slate-200/90 shadow-xs">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="font-outfit text-sm font-bold text-slate-900">Tren Transaksi & Komisi Platform</h3>
                    <p class="text-xs text-slate-400">Historis akumulasi pembayaran 6 bulan terakhir.</p>
                </div>
                <div class="flex items-center gap-3 text-xs">
                    <span class="flex items-center gap-1.5 text-brand-600 font-medium">
                        <span class="w-2.5 h-2.5 rounded-full bg-brand-500"></span> Transaksi Bruto
                    </span>
                    <span class="flex items-center gap-1.5 text-emerald-600 font-medium">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span> Fee Platform ({{ $platformFeeRate }}%)
                    </span>
                </div>
            </div>
            <div class="h-64">
                <canvas id="financeTrendChart"></canvas>
            </div>
        </div>

        <!-- Payment Method Breakdown (4 Cols) -->
        <div class="lg:col-span-4 p-6 rounded-2xl bg-white border border-slate-200/90 shadow-xs flex flex-col justify-between">
            <div>
                <h3 class="font-outfit text-sm font-bold text-slate-900">Saluran Pembayaran</h3>
                <p class="text-xs text-slate-400 mb-4">Preferensi metode bayar siswa via Midtrans.</p>
                <div class="h-44 relative flex items-center justify-center">
                    <canvas id="paymentMethodChart"></canvas>
                </div>
            </div>
            <div class="grid grid-cols-3 gap-2 text-center pt-4 border-t border-slate-100 mt-2">
                <div class="p-2 rounded-xl bg-slate-50">
                    <div class="text-[10px] text-slate-400 font-medium">QRIS</div>
                    <div class="text-xs font-bold text-slate-800">{{ $paymentMethodsCount['QRIS'] }}</div>
                </div>
                <div class="p-2 rounded-xl bg-slate-50">
                    <div class="text-[10px] text-slate-400 font-medium">Virtual Acc.</div>
                    <div class="text-xs font-bold text-slate-800">{{ $paymentMethodsCount['Virtual Account'] }}</div>
                </div>
                <div class="p-2 rounded-xl bg-slate-50">
                    <div class="text-[10px] text-slate-400 font-medium">E-Wallet</div>
                    <div class="text-xs font-bold text-slate-800">{{ $paymentMethodsCount['E-Wallet'] }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- ═══════════════════════════════════════
         FILTER TOOLBAR
    ═══════════════════════════════════════ -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs flex flex-col lg:flex-row items-center justify-between gap-3">
        <!-- Search Form -->
        <form action="{{ route('super.finance') }}" method="GET" class="flex flex-col sm:flex-row items-center gap-2.5 w-full lg:w-auto flex-1">
            @if($statusFilter)
                <input type="hidden" name="status" value="{{ $statusFilter }}">
            @endif

            <div class="relative w-full sm:w-64">
                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <i class="fa-solid fa-magnifying-glass text-xs"></i>
                </span>
                <input type="text" name="search" value="{{ $search ?? '' }}"
                       placeholder="Cari ID, siswa, kursus..." 
                       class="w-full pl-9 pr-3 py-2 rounded-xl border border-slate-200 bg-slate-50 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:bg-white focus:border-brand-500 transition">
            </div>

            <!-- Tenant Dropdown Filter -->
            <select name="tenant_id" onchange="this.form.submit()"
                    class="w-full sm:w-56 px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 text-xs text-slate-700 focus:outline-none focus:bg-white focus:border-brand-500 transition">
                <option value="">Semua Mitra LPK</option>
                @foreach($tenants as $t)
                    <option value="{{ $t->id }}" {{ $tenantFilter == $t->id ? 'selected' : '' }}>
                        {{ $t->name }}
                    </option>
                @endforeach
            </select>
        </form>

        <!-- Status Pills -->
        <div class="flex items-center gap-1.5 w-full lg:w-auto overflow-x-auto pb-1 lg:pb-0">
            <a href="{{ route('super.finance', array_merge(request()->except('status'), ['status' => 'all'])) }}"
               class="px-3 py-1.5 rounded-lg text-xs font-semibold whitespace-nowrap transition {{ empty($statusFilter) || $statusFilter === 'all' ? 'bg-slate-900 text-white' : 'bg-slate-100 hover:bg-slate-200 text-slate-600' }}">
                Semua Status
            </a>
            <a href="{{ route('super.finance', array_merge(request()->except('status'), ['status' => 'success'])) }}"
               class="px-3 py-1.5 rounded-lg text-xs font-semibold whitespace-nowrap transition {{ $statusFilter === 'success' ? 'bg-emerald-600 text-white' : 'bg-slate-100 hover:bg-slate-200 text-slate-600' }}">
                Lunas ({{ $stats['success_count'] }})
            </a>
            <a href="{{ route('super.finance', array_merge(request()->except('status'), ['status' => 'pending'])) }}"
               class="px-3 py-1.5 rounded-lg text-xs font-semibold whitespace-nowrap transition {{ $statusFilter === 'pending' ? 'bg-amber-500 text-slate-950 font-bold' : 'bg-slate-100 hover:bg-slate-200 text-slate-600' }}">
                Pending ({{ $stats['pending_count'] }})
            </a>
            <a href="{{ route('super.finance', array_merge(request()->except('status'), ['status' => 'failed'])) }}"
               class="px-3 py-1.5 rounded-lg text-xs font-semibold whitespace-nowrap transition {{ $statusFilter === 'failed' ? 'bg-rose-600 text-white' : 'bg-slate-100 hover:bg-slate-200 text-slate-600' }}">
                Gagal
            </a>
            @if($search || $tenantFilter || $statusFilter)
                <a href="{{ route('super.finance') }}" class="px-2.5 py-1.5 text-xs text-rose-600 hover:underline font-semibold ml-1">
                    Reset
                </a>
            @endif
        </div>
    </div>

    <!-- ═══════════════════════════════════════
         TRANSACTIONS DATA TABLE
    ═══════════════════════════════════════ -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600 divide-y divide-slate-100">
                <thead class="bg-slate-50/80 text-slate-500 uppercase tracking-wider text-[11px] font-bold">
                    <tr>
                        <th class="py-3.5 px-6">ID & Referensi</th>
                        <th class="py-3.5 px-6">Siswa & Peserta</th>
                        <th class="py-3.5 px-6">Mitra LPK & Kursus</th>
                        <th class="py-3.5 px-6">Metode Midtrans</th>
                        <th class="py-3.5 px-6">Nominal & Fee Platform</th>
                        <th class="py-3.5 px-6">Status</th>
                        <th class="py-3.5 px-6 text-right">Waktu</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($payments as $payment)
                        @php
                            $fee = round($payment->amount * ($platformFeeRate / 100));
                        @endphp
                        <tr class="hover:bg-slate-50/60 transition">
                            <!-- Ref ID -->
                            <td class="py-3.5 px-6 whitespace-nowrap">
                                <div class="font-mono font-bold text-slate-900 text-xs">
                                    {{ $payment->external_id ?? ('#TRX-' . strtoupper(substr($payment->id, 0, 8))) }}
                                </div>
                                <div class="text-[10px] text-slate-400 font-mono mt-0.5">
                                    ID: {{ substr($payment->id, 0, 8) }}
                                </div>
                            </td>

                            <!-- Student -->
                            <td class="py-3.5 px-6 whitespace-nowrap">
                                <div class="font-bold text-slate-900">{{ $payment->user->name ?? 'Siswa Tamu' }}</div>
                                <div class="text-[11px] text-slate-400 font-mono">{{ $payment->user->email ?? '-' }}</div>
                            </td>

                            <!-- LPK & Course -->
                            <td class="py-3.5 px-6 whitespace-nowrap">
                                <div class="font-bold text-slate-800">
                                    {{ $payment->tenant->name ?? ($payment->tenant->lpk->name ?? 'Mitra LPK') }}
                                </div>
                                <div class="text-[11px] text-slate-500 truncate max-w-xs mt-0.5">
                                    {{ $payment->booking->course->title ?? '-' }}
                                </div>
                            </td>

                            <!-- Method -->
                            <td class="py-3.5 px-6 whitespace-nowrap">
                                <div class="flex items-center gap-1.5 font-semibold text-slate-700">
                                    @if($payment->method === 'qris')
                                        <i class="fa-solid fa-qrcode text-brand-600"></i>
                                    @elseif($payment->method === 'bank_transfer')
                                        <i class="fa-solid fa-building-columns text-emerald-600"></i>
                                    @else
                                        <i class="fa-solid fa-wallet text-purple-600"></i>
                                    @endif
                                    <span>{{ $payment->provider ?: ucfirst($payment->method) }}</span>
                                </div>
                            </td>

                            <!-- Amount & Fee -->
                            <td class="py-3.5 px-6 whitespace-nowrap">
                                <div class="font-bold text-slate-900">
                                    Rp {{ number_format($payment->amount, 0, ',', '.') }}
                                </div>
                                <div class="text-[10px] text-emerald-600 font-medium mt-0.5">
                                    Fee ({{ $platformFeeRate }}%): Rp {{ number_format($fee, 0, ',', '.') }}
                                </div>
                            </td>

                            <!-- Status -->
                            <td class="py-3.5 px-6 whitespace-nowrap">
                                @if($payment->status === 'success')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>
                                        Lunas
                                    </span>
                                @elseif($payment->status === 'pending')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                        <span class="w-1.5 h-1.5 bg-amber-500 rounded-full animate-ping"></span>
                                        Menunggu Bayar
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                        <span class="w-1.5 h-1.5 bg-rose-500 rounded-full"></span>
                                        Gagal / Batal
                                    </span>
                                @endif
                            </td>

                            <!-- Time -->
                            <td class="py-3.5 px-6 whitespace-nowrap text-right font-mono text-slate-500">
                                <div>{{ $payment->created_at->format('d M Y') }}</div>
                                <div class="text-[10px] text-slate-400">{{ $payment->created_at->format('H:i') }} WIB</div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-400">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-12 h-12 rounded-2xl bg-slate-100 flex items-center justify-center text-xl text-slate-400 mb-3">
                                        <i class="fa-solid fa-receipt"></i>
                                    </div>
                                    <p class="text-sm font-bold text-slate-700">Belum Ada Riwayat Transaksi</p>
                                    <p class="text-xs text-slate-400 mt-1">Transaksi pembayaran peserta pelatihan melalui Midtrans akan dicatat otomatis di sini.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($payments->hasPages())
            <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                {{ $payments->links() }}
            </div>
        @endif
    </div>

</div>

<!-- ═══════════════════════════════════════
     CHART.JS SCRIPTS
═══════════════════════════════════════ -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // 1. Finance Trend Chart
        const trendCtx = document.getElementById('financeTrendChart').getContext('2d');
        new Chart(trendCtx, {
            type: 'line',
            data: {
                labels: @json($chartMonths),
                datasets: [
                    {
                        label: 'Gross Volume (GMV)',
                        data: @json($chartRevenues),
                        borderColor: '#2563eb',
                        backgroundColor: 'rgba(37, 99, 235, 0.08)',
                        fill: true,
                        tension: 0.35,
                        borderWidth: 2.5,
                        pointBackgroundColor: '#2563eb',
                        pointRadius: 4,
                    },
                    {
                        label: 'Fee Platform ({{ $platformFeeRate }}%)',
                        data: @json($chartFees),
                        borderColor: '#059669',
                        backgroundColor: 'rgba(5, 150, 105, 0.08)',
                        fill: true,
                        tension: 0.35,
                        borderWidth: 2,
                        pointBackgroundColor: '#059669',
                        pointRadius: 3,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(ctx) {
                                return ctx.dataset.label + ': Rp ' + new Intl.NumberFormat('id-ID').format(ctx.raw);
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(val) {
                                if (val >= 1000000) return 'Rp ' + (val / 1000000).toFixed(1) + 'M';
                                if (val >= 1000) return 'Rp ' + (val / 1000).toFixed(0) + 'K';
                                return val;
                            },
                            font: { size: 10 }
                        },
                        grid: { color: '#f1f5f9' }
                    },
                    x: {
                        ticks: { font: { size: 11 } },
                        grid: { display: false }
                    }
                }
            }
        });

        // 2. Payment Method Doughnut Chart
        const methodCtx = document.getElementById('paymentMethodChart').getContext('2d');
        new Chart(methodCtx, {
            type: 'doughnut',
            data: {
                labels: ['QRIS', 'Virtual Account', 'E-Wallet'],
                datasets: [{
                    data: [
                        {{ $paymentMethodsCount['QRIS'] }},
                        {{ $paymentMethodsCount['Virtual Account'] }},
                        {{ $paymentMethodsCount['E-Wallet'] }}
                    ],
                    backgroundColor: ['#2563eb', '#10b981', '#8b5cf6'],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                cutout: '70%'
            }
        });
    });
</script>
@endsection