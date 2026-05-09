@extends('layouts.admin')

@section('title', 'Admin LPK Dashboard')

@section('content')

    @php
        // 🔥 Pengecekan Status LPK otomatis
        $myLpk = \App\Models\Lpk::where('tenant_id', auth()->user()->tenant_id)->first();
        $status = $myLpk ? $myLpk->status_verifikasi : 'pending';
    @endphp

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body { background-color: #f1f5f9; }

        /* ── Status Banner ── */
        .status-banner {
            border-radius: 20px;
            padding: 18px 28px;
            margin-bottom: 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border: 1px solid transparent;
            backdrop-filter: blur(8px);
        }
        .banner-approved { background: linear-gradient(135deg,#d1fae5,#a7f3d0); border-color:#6ee7b7; color:#065f46; }
        .banner-pending  { background: linear-gradient(135deg,#fef9c3,#fde68a); border-color:#fcd34d; color:#92400e; }
        .banner-rejected { background: linear-gradient(135deg,#fee2e2,#fecaca); border-color:#fca5a5; color:#991b1b; }

        /* ── Stat Cards (Glassmorphism) ── */
        .stat-card {
            background: rgba(255,255,255,0.72);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-radius: 22px;
            padding: 26px 24px;
            border: 1px solid rgba(255,255,255,0.85);
            box-shadow: 0 4px 24px rgba(99,102,241,0.06);
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: transform 0.3s ease, box-shadow 0.3s ease, border-color 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        .stat-card::before {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: 22px;
            background: linear-gradient(135deg, rgba(255,255,255,0.4) 0%, rgba(255,255,255,0) 100%);
            pointer-events: none;
        }
        .stat-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 40px rgba(99,102,241,0.13);
            border-color: rgba(165,180,252,0.5);
        }
        .stat-title {
            font-size: 12px;
            color: #94a3b8;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.7px;
            margin-bottom: 8px;
        }
        .stat-number {
            font-size: 32px;
            font-weight: 800;
            color: #1e293b;
            font-family: 'Outfit', sans-serif;
            line-height: 1;
        }
        .stat-trend {
            font-size: 11px;
            font-weight: 600;
            margin-top: 6px;
            display: inline-flex;
            align-items: center;
            gap: 3px;
        }
        .trend-up   { color: #10b981; }
        .trend-down { color: #f43f5e; }

        .icon-box {
            width: 60px; height: 60px;
            border-radius: 18px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .i1 { background: linear-gradient(135deg,#e0e7ff,#c7d2fe); color: #6366f1; }
        .i2 { background: linear-gradient(135deg,#ccfbf1,#99f6e4); color: #0d9488; }
        .i3 { background: linear-gradient(135deg,#fce7f3,#fbcfe8); color: #ec4899; }
        .i4 { background: linear-gradient(135deg,#fef3c7,#fde68a); color: #d97706; }

        /* ── Content Cards ── */
        .content-card {
            background: rgba(255,255,255,0.78);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-radius: 22px;
            border: 1px solid rgba(255,255,255,0.9);
            padding: 28px;
            box-shadow: 0 4px 24px rgba(99,102,241,0.06);
            transition: box-shadow 0.3s ease;
        }
        .content-card:hover { box-shadow: 0 12px 40px rgba(99,102,241,0.1); }

        .card-header {
            font-family: 'Outfit', sans-serif;
            font-size: 17px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
        }

        /* ── Filter Dropdown ── */
        .filter-select {
            appearance: none;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 6px 32px 6px 12px;
            font-size: 12px;
            font-weight: 600;
            color: #475569;
            cursor: pointer;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%2394a3b8' stroke-width='2.5'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 10px center;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .filter-select:focus { outline: none; border-color: #818cf8; box-shadow: 0 0 0 3px rgba(129,140,248,0.15); }

        /* ── PDF Button ── */
        .btn-pdf {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            color: #fff;
            font-size: 13px;
            font-weight: 600;
            padding: 10px 20px;
            border-radius: 12px;
            text-decoration: none;
            border: none;
            cursor: pointer;
            box-shadow: 0 4px 14px rgba(99,102,241,0.35);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .btn-pdf:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(99,102,241,0.45); color: #fff; }

        /* ── Table ── */
        .premium-table { width: 100%; border-collapse: separate; border-spacing: 0; }
        .premium-table th {
            font-size: 11px; color: #94a3b8; font-weight: 700;
            text-transform: uppercase; letter-spacing: 0.6px;
            padding: 12px 16px; border-bottom: 1px solid #f1f5f9; text-align: left;
        }
        .premium-table td {
            padding: 14px 16px; font-size: 14px; color: #475569;
            border-bottom: 1px solid #f8fafc; vertical-align: middle;
        }
        .premium-table tr:last-child td { border-bottom: none; }
        .premium-table tbody tr { transition: background-color 0.2s; }
        .premium-table tbody tr:hover { background-color: rgba(241,245,249,0.7); }

        .status-badge {
            padding: 5px 12px; border-radius: 20px; font-size: 11px;
            font-weight: 700; display: inline-block; letter-spacing: 0.5px;
        }
        .status-pending  { background:#fef3c7; color:#d97706; }
        .status-approved { background:#d1fae5; color:#059669; }
        .status-rejected { background:#fee2e2; color:#dc2626; }
        .status-paid     { background:#dbeafe; color:#2563eb; }
        .status-completed{ background:#d1fae5; color:#059669; }
        .status-cancelled{ background:#fee2e2; color:#dc2626; }

        /* ── Section Header with PDF btn ── */
        .page-topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 28px;
            flex-wrap: wrap;
            gap: 12px;
        }
        .page-topbar-title { font-size: 13px; color: #94a3b8; font-weight: 500; }
    </style>

    {{-- ═══════════════════════════════════════
         Status Banner (JANGAN DIUBAH LOGIKANYA)
    ═══════════════════════════════════════ --}}
    <div class="page-topbar">
        <div>
            <p class="page-topbar-title">Ringkasan Operasional LPK Anda</p>
        </div>
        @if($status == 'approved')
        <a href="{{ route('admin.dashboard.pdf') }}" class="btn-pdf" target="_blank" id="btn-export-pdf">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
            </svg>
            Download Report (PDF)
        </a>
        @endif
    </div>

    <div class="status-banner banner-{{ $status }}" style="margin-bottom:28px;">
        <div>
            <h3 class="font-bold text-lg mb-1">Selamat Datang di LPK Center</h3>
            @if($status == 'approved')
                <p class="text-sm opacity-90">✅ Pendaftaran LPK Anda telah <strong>Diverifikasi</strong>. Anda memiliki akses penuh ke sistem.</p>
            @elseif($status == 'pending')
                <p class="text-sm opacity-90">⏳ Pendaftaran LPK Anda sedang <strong>Menunggu Verifikasi</strong> oleh Super Admin.</p>
            @else
                <p class="text-sm opacity-90">❌ Pendaftaran LPK Anda <strong>Ditolak</strong>. Silakan hubungi Super Admin untuk info lebih lanjut.</p>
            @endif
        </div>
        <div class="hidden md:block">
            <span class="status-badge status-{{ $status }} px-4 py-2 text-sm shadow-sm">
                STATUS: {{ strtoupper($status) }}
            </span>
        </div>
    </div>

    {{-- ═══════════════════════════════════════
         Stat Cards
    ═══════════════════════════════════════ --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

        <div class="stat-card">
            <div>
                <p class="stat-title">Total Students</p>
                <p class="stat-number">{{ $totalStudents ?? 0 }}</p>
                <span class="stat-trend trend-up">↑ Aktif terdaftar</span>
            </div>
            <div class="icon-box i1">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
            </div>
        </div>

        <div class="stat-card">
            <div>
                <p class="stat-title">Total Courses</p>
                <p class="stat-number">{{ $totalCourses ?? 0 }}</p>
                <span class="stat-trend trend-up">↑ Program tersedia</span>
            </div>
            <div class="icon-box i2">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
            </div>
        </div>

        <div class="stat-card">
            <div>
                <p class="stat-title">Upcoming Classes</p>
                <p class="stat-number">{{ $upcomingClasses ?? 0 }}</p>
                <span class="stat-trend trend-up">↑ Jadwal mendatang</span>
            </div>
            <div class="icon-box i3">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
        </div>

        <div class="stat-card">
            <div>
                <p class="stat-title">Pending Bookings</p>
                <p class="stat-number">{{ $pendingBookings ?? 0 }}</p>
                <span class="stat-trend trend-down">● Perlu ditinjau</span>
            </div>
            <div class="icon-box i4">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                </svg>
            </div>
        </div>

    </div>

    {{-- ═══════════════════════════════════════
         Charts Section
    ═══════════════════════════════════════ --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">

        {{-- Line Chart --}}
        <div class="content-card lg:col-span-2">
            <div class="card-header">
                <span>Activity Overview</span>
                <div class="flex items-center gap-2">
                    @if($status == 'approved')
                    <select class="filter-select" id="filterLine" onchange="updateLineChart(this.value)">
                        <option value="year">Tahun Ini</option>
                        <option value="month">Bulan Ini</option>
                        <option value="week">7 Hari Terakhir</option>
                    </select>
                    @endif
                </div>
            </div>
            <div style="height: 300px; width: 100%;">
                <canvas id="chartLine"></canvas>
            </div>
        </div>

        {{-- Donut Chart --}}
        <div class="content-card">
            <div class="card-header">
                <span>Distribution</span>
                @if($status == 'approved')
                <select class="filter-select" id="filterPie" onchange="updatePieChart(this.value)">
                    <option value="year">Tahun Ini</option>
                    <option value="month">Bulan Ini</option>
                </select>
                @endif
            </div>
            <div style="height: 300px; width: 100%; display: flex; align-items: center; justify-content: center;">
                <canvas id="chartPie"></canvas>
            </div>
        </div>

    </div>

    {{-- ═══════════════════════════════════════
         Recent Bookings Table (JANGAN DIHAPUS)
    ═══════════════════════════════════════ --}}
    <div class="content-card">
        <div class="card-header">
            <span>Log Pemesanan Terbaru</span>
            <a href="{{ route('admin.bookings.index') ?? '#' }}"
                class="text-sm font-semibold text-indigo-500 hover:text-indigo-700 transition-colors">
                Lihat Semua →
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="premium-table">
                <thead>
                    <tr>
                        <th>Booking ID</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentBookings ?? [] as $booking)
                        <tr>
                            <td class="font-semibold text-gray-800">
                                #BKG-{{ str_pad($booking->id, 5, '0', STR_PAD_LEFT) }}
                            </td>
                            <td class="text-gray-500">
                                {{ \Carbon\Carbon::parse($booking->created_at)->format('d M Y') ?? 'N/A' }}
                            </td>
                            <td>
                                @php
                                    $s = strtolower($booking->status);
                                    $sc = match(true) {
                                        in_array($s, ['approved','completed']) => 'status-approved',
                                        in_array($s, ['rejected','cancelled']) => 'status-rejected',
                                        $s === 'paid'                          => 'status-paid',
                                        default                                => 'status-pending',
                                    };
                                @endphp
                                <span class="status-badge {{ $sc }}">{{ ucfirst($booking->status) }}</span>
                            </td>
                            <td class="text-right">
                                <button class="text-indigo-500 hover:text-indigo-800 bg-indigo-50 p-2 rounded-lg hover:bg-indigo-100 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-10 text-gray-400 font-medium">
                                Belum ada data pemesanan terbaru.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ═══════════════════════════════════════
         Chart.js Scripts
    ═══════════════════════════════════════ --}}
    <script>
        Chart.defaults.font.family = "'Inter', 'Outfit', sans-serif";
        Chart.defaults.color = '#94a3b8';

        // ── Data Sets per Filter ──────────────────────────────────────────────
        const lineDataSets = {
            year: {
                labels: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
                students: @json($monthlyStudents ?? [10,25,15,35,22,45,30,40,28,50,35,60]),
                courses:  @json($monthlyCourses  ?? [5,12,18,15,28,24,20,18,22,30,25,35]),
            },
            month: {
                labels: ['Minggu 1','Minggu 2','Minggu 3','Minggu 4'],
                students: [8, 14, 11, 19],
                courses:  [3, 7, 5, 9],
            },
            week: {
                labels: ['Sen','Sel','Rab','Kam','Jum','Sab','Min'],
                students: [3, 6, 4, 8, 5, 2, 1],
                courses:  [1, 3, 2, 4, 3, 1, 0],
            },
        };

        const pieDataSets = {
            year: [@json($totalStudents ?? 0), @json($totalCourses ?? 0), @json($pendingBookings ?? 0)],
            month: [
                Math.floor(@json($totalStudents ?? 0) * 0.3),
                Math.floor(@json($totalCourses  ?? 0) * 0.4),
                Math.floor(@json($pendingBookings ?? 0) * 0.5),
            ],
        };

        // ── Gradients ────────────────────────────────────────────────────────
        const ctxLine = document.getElementById('chartLine').getContext('2d');
        const gradIndigo = ctxLine.createLinearGradient(0, 0, 0, 400);
        gradIndigo.addColorStop(0, 'rgba(99,102,241,0.18)');
        gradIndigo.addColorStop(1, 'rgba(99,102,241,0)');

        const gradTeal = ctxLine.createLinearGradient(0, 0, 0, 400);
        gradTeal.addColorStop(0, 'rgba(13,148,136,0.18)');
        gradTeal.addColorStop(1, 'rgba(13,148,136,0)');

        // ── Line Chart ───────────────────────────────────────────────────────
        const lineChart = new Chart(ctxLine, {
            type: 'line',
            data: {
                labels: lineDataSets.year.labels,
                datasets: [
                    {
                        label: 'Students',
                        data: lineDataSets.year.students,
                        borderColor: '#6366f1',
                        backgroundColor: gradIndigo,
                        borderWidth: 3,
                        tension: 0.45,
                        fill: true,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: '#6366f1',
                        pointBorderWidth: 2.5,
                        pointRadius: 5,
                        pointHoverRadius: 7,
                    },
                    {
                        label: 'Courses',
                        data: lineDataSets.year.courses,
                        borderColor: '#0d9488',
                        backgroundColor: gradTeal,
                        borderWidth: 3,
                        tension: 0.45,
                        fill: true,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: '#0d9488',
                        pointBorderWidth: 2.5,
                        pointRadius: 5,
                        pointHoverRadius: 7,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'top', align: 'end', labels: { usePointStyle: true, boxWidth: 8, padding: 20 } },
                    tooltip: { backgroundColor: '#1e293b', padding: 14, cornerRadius: 10, displayColors: true }
                },
                scales: {
                    y: { beginAtZero: true, border: { display: false }, grid: { color: '#f1f5f9' } },
                    x: { border: { display: false }, grid: { display: false } }
                },
                interaction: { intersect: false, mode: 'index' },
                animation: { duration: 600, easing: 'easeInOutQuart' }
            }
        });

        // ── Donut Chart ──────────────────────────────────────────────────────
        const pieChart = new Chart(document.getElementById('chartPie'), {
            type: 'doughnut',
            data: {
                labels: ['Students', 'Courses', 'Bookings'],
                datasets: [{
                    data: pieDataSets.year,
                    backgroundColor: ['#6366f1', '#0d9488', '#f59e0b'],
                    borderWidth: 3,
                    borderColor: '#fff',
                    hoverOffset: 8,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '74%',
                plugins: {
                    legend: { position: 'bottom', labels: { usePointStyle: true, padding: 18 } },
                    tooltip: { backgroundColor: '#1e293b', padding: 12, cornerRadius: 10 }
                },
                animation: { duration: 600, easing: 'easeInOutQuart' }
            }
        });

        // ── Filter Functions ─────────────────────────────────────────────────
        function updateLineChart(period) {
            const d = lineDataSets[period];
            lineChart.data.labels = d.labels;
            lineChart.data.datasets[0].data = d.students;
            lineChart.data.datasets[1].data = d.courses;
            lineChart.update();
        }

        function updatePieChart(period) {
            pieChart.data.datasets[0].data = pieDataSets[period];
            pieChart.update();
        }
    </script>

@endsection