@extends('layouts.super_admin')

@section('title', 'Super Admin Dashboard')

@section('content')

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body {
            background-color: #f9fafb;
        }

        .stat {
            background: white;
            border-radius: 16px;
            padding: 20px;
            border: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: transform 0.2s;
        }

        .stat:hover {
            transform: translateY(-3px);
        }

        .stat-title {
            font-size: 13px;
            color: #94a3b8;
            font-weight: 500;
        }

        .stat-number {
            font-size: 24px;
            font-weight: 700;
            color: #1e293b;
        }

        .icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
        }

        /* Palet Warna Soft Calming */
        .i1 {
            background: #e0e7ff;
            color: #6366f1;
        }

        /* Soft Indigo */
        .i2 {
            background: #ccfbf1;
            color: #14b8a6;
        }

        /* Soft Teal */
        .i3 {
            background: #fef3c7;
            color: #d97706;
        }

        /* Soft Amber */
        .i4 {
            background: #fae8ff;
            color: #d946ef;
        }

        /* Soft Pink */

        .card {
            background: white;
            border-radius: 16px;
            border: 1px solid #f1f5f9;
            padding: 24px;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.05);
        }

        .table th {
            font-size: 12px;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .table tr {
            border-bottom: 1px solid #f8fafc;
        }

        .status-badge {
            padding: 4px 12px;
            border-radius: 9999px;
            font-size: 11px;
            font-weight: 700;
        }

        .bg-pending {
            background: #fffbeb;
            color: #92400e;
        }
    </style>

    <div class="grid md:grid-cols-4 gap-6 mb-8">
        <div class="stat">
            <div>
                <p class="stat-title">Total LPK</p>
                <p class="stat-number">{{ $totalLpk }}</p>
            </div>
            <div class="icon i1">🏫</div>
        </div>

        <div class="stat">
            <div>
                <p class="stat-title">Total Courses</p>
                <p class="stat-number">{{ $totalCourses }}</p>
            </div>
            <div class="icon i2">📚</div>
        </div>

        <div class="stat">
            <div>
                <p class="stat-title">Total Users</p>
                <p class="stat-number">{{ $totalUsers }}</p>
            </div>
            <div class="icon i4">👨‍🎓</div>
        </div>

        <div class="stat">
            <div>
                <p class="stat-title">Pending</p>
                <p class="stat-number">{{ $pendingVerifications }}</p>
            </div>
            <div class="icon i3">⏳</div>
        </div>
    </div>

    <div class="grid lg:grid-cols-2 gap-8 mb-8">
        <div class="card">
            <h3 class="text-gray-700 font-bold mb-6">Pertumbuhan Platform</h3>
            <div style="height:320px">
                <canvas id="chartLine"></canvas>
            </div>
        </div>

        <div class="card flex flex-col items-center">
            <h3 class="text-gray-700 font-bold mb-6 w-full">Komposisi Data</h3>
            <div style="width:280px;height:280px">
                <canvas id="chartPie"></canvas>
            </div>
        </div>
    </div>

    <div class="card">
        <h3 class="text-gray-700 font-bold mb-4">Menunggu Verifikasi</h3>
        <table class="table w-full">
            <thead>
                <tr>
                    <th class="p-3 text-left">Nama LPK</th>
                    <th class="p-3 text-left">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pendingLpks as $lpk)
                    <tr>
                        <td class="p-3 font-medium text-gray-700">
                            {{ $lpk->lpk_name ?? $lpk->name ?? $lpk->nama ?? '-' }}
                        </td>
                        <td class="p-3">
                            <span class="status-badge bg-pending">
                                ● {{ strtoupper($lpk->status_verifikasi ?? 'PENDING') }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" class="p-8 text-center text-gray-400">
                            ✨ Luar biasa! Semua tugas verifikasi sudah selesai.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <script>
        // Line Chart - Warna Soft (Lavender, Mint, Sky Blue)
        new Chart(document.getElementById('chartLine'), {
            type: 'line',
            data: {
                labels: @json($monthlyLabels),
                datasets: [
                    {
                        label: 'LPK',
                        data: @json($monthlyLpk),
                        borderColor: '#818cf8', // Soft Indigo
                        backgroundColor: '#818cf820',
                        fill: true,
                        tension: 0.4
                    },
                    {
                        label: 'Users',
                        data: @json($monthlyUsers),
                        borderColor: '#2dd4bf', // Soft Teal
                        backgroundColor: 'transparent',
                        tension: 0.4
                    },
                    {
                        label: 'Courses',
                        data: @json($monthlyCourses),
                        borderColor: '#fbbf24', // Soft Amber
                        backgroundColor: 'transparent',
                        tension: 0.4
                    }
                ]
            },
            options: {
                maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom', labels: { usePointStyle: true, padding: 20 } } },
                scales: {
                    y: { grid: { display: false } },
                    x: { grid: { display: false } }
                }
            }
        });

        // Pie Chart - Warna Pastel Calming
        new Chart(document.getElementById('chartPie'), {
            type: 'doughnut',
            data: {
                labels: ['LPK', 'Courses', 'Users'],
                datasets: [{
                    data: [{{ $totalLpk }}, {{ $totalCourses }}, {{ $totalUsers }}],
                    backgroundColor: ['#818cf8', '#2dd4bf', '#f472b6'], // Indigo, Teal, Pink Soft
                    hoverOffset: 10,
                    borderWidth: 5,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                maintainAspectRatio: false,
                cutout: '75%',
                plugins: { legend: { position: 'bottom', labels: { padding: 20, usePointStyle: true } } }
            }
        });
    </script>

@endsection