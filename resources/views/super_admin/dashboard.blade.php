@extends('layouts.admin')

@section('title', 'Super Admin Dashboard')

@section('content')

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        .stat {
            background: white;
            border-radius: 12px;
            padding: 18px;
            border: 1px solid #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .stat-title {
            font-size: 13px;
            color: #6b7280;
        }

        .stat-number {
            font-size: 22px;
            font-weight: 600;
        }

        .icon {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }

        .i1 {
            background: #eef2ff;
            color: #4f46e5;
        }

        .i2 {
            background: #ecfeff;
            color: #0891b2;
        }

        .i3 {
            background: #f5f3ff;
            color: #7c3aed;
        }

        .i4 {
            background: #fff7ed;
            color: #ea580c;
        }

        .card {
            background: white;
            border-radius: 12px;
            border: 1px solid #e5e7eb;
            padding: 20px;
        }

        .table th {
            font-size: 13px;
            color: #6b7280;
            font-weight: 500;
        }

        .table tr {
            border-bottom: 1px solid #f1f5f9;
        }
    </style>

    <div class="grid md:grid-cols-4 gap-5 mb-6">
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
            <div class="icon i3">👨‍🎓</div>
        </div>

        <div class="stat">
            <div>
                <p class="stat-title">Pending Verification</p>
                <p class="stat-number">{{ $pendingVerifications }}</p>
            </div>
            <div class="icon i4">⏳</div>
        </div>
    </div>

    <div class="grid lg:grid-cols-2 gap-6 mb-6">
        <div class="card">
            <h3 class="text-sm font-semibold mb-4">Pertumbuhan Platform</h3>
            <div style="height:300px">
                <canvas id="chartLine"></canvas>
            </div>
        </div>

        <div class="card flex flex-col items-center justify-center">
            <h3 class="text-sm font-semibold mb-4 w-full text-left">Ringkasan Data</h3>
            <div style="width:260px;height:260px">
                <canvas id="chartPie"></canvas>
            </div>
        </div>
    </div>

    <div class="card">
        <h3 class="text-sm font-semibold mb-3">LPK Pending Verification</h3>
        <table class="table w-full">
            <thead>
                <tr>
                    <th class="p-2 text-left">Nama LPK</th>
                    <th class="p-2 text-left">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pendingLpks as $lpk)
                    <tr>
                        <td class="p-2">
                            {{ $lpk->lpk_name ?? $lpk->name ?? $lpk->nama ?? '-' }}
                        </td>
                        <td class="p-2">
                            <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-md font-semibold text-xs">
                                {{ strtoupper($lpk->status_verifikasi ?? 'PENDING') }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" class="p-6 text-center text-gray-400 font-medium">
                            🎉 Yeay! Semua LPK sudah diverifikasi.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <script>
        // Data Line Chart
        new Chart(document.getElementById('chartLine'), {
            type: 'line',
            data: {
                labels: @json($monthlyLabels),
                datasets: [
                    {
                        label: 'LPK',
                        data: @json($monthlyLpk),
                        borderColor: '#4f46e5',
                        backgroundColor: '#4f46e5',
                        tension: 0.4
                    },
                    {
                        label: 'Users',
                        data: @json($monthlyUsers),
                        borderColor: '#7c3aed',
                        backgroundColor: '#7c3aed',
                        tension: 0.4
                    },
                    {
                        label: 'Courses',
                        data: @json($monthlyCourses),
                        borderColor: '#0891b2',
                        backgroundColor: '#0891b2',
                        tension: 0.4
                    }
                ]
            },
            options: {
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' }
                },
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });

        // Data Pie Chart
        new Chart(document.getElementById('chartPie'), {
            type: 'doughnut',
            data: {
                labels: ['LPK', 'Courses', 'Users'],
                datasets: [{
                    data: [
                            {{ $totalLpk }},
                            {{ $totalCourses }},
                        {{ $totalUsers }}
                    ],
                    backgroundColor: [
                        '#4f46e5', // Indigo untuk LPK
                        '#0891b2', // Cyan untuk Courses
                        '#7c3aed'  // Purple untuk Users
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' }
                },
                cutout: '70%' // Membuat efek cincin doughnut lebih elegan
            }
        });
    </script>

@endsection