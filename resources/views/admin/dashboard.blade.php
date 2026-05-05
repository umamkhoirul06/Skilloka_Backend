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
        body {
            background-color: #f8fafc;
        }

        /* Banner Status */
        .status-banner {
            border-radius: 16px;
            padding: 16px 24px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border: 1px solid transparent;
        }

        .banner-approved {
            background: #ecfdf5;
            border-color: #d1fae5;
            color: #065f46;
        }

        .banner-pending {
            background: #fffbeb;
            border-color: #fef08a;
            color: #92400e;
        }

        .banner-rejected {
            background: #fef2f2;
            border-color: #fecaca;
            color: #991b1b;
        }

        /* Card design system */
        .stat-card {
            background: #ffffff;
            border-radius: 20px;
            padding: 24px;
            border: 1px solid rgba(226, 232, 240, 0.8);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.02);
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.05);
        }

        .stat-title {
            font-size: 13px;
            color: #94a3b8;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }

        .stat-number {
            font-size: 28px;
            font-weight: 700;
            color: #1e293b;
            font-family: 'Outfit', sans-serif;
        }

        .icon-box {
            width: 56px;
            height: 56px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Warna Soft Calming Pastel */
        .i1 {
            background: #e0e7ff;
            color: #818cf8;
        }

        /* Soft Indigo */
        .i2 {
            background: #ccfbf1;
            color: #2dd4bf;
        }

        /* Soft Teal */
        .i3 {
            background: #fce7f3;
            color: #f472b6;
        }

        /* Soft Pink */
        .i4 {
            background: #fef3c7;
            color: #fbbf24;
        }

        /* Soft Amber */

        .content-card {
            background: #ffffff;
            border-radius: 20px;
            border: 1px solid rgba(226, 232, 240, 0.8);
            padding: 24px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.02);
        }

        .card-header {
            font-family: 'Outfit', sans-serif;
            font-size: 18px;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        /* Table styles */
        .premium-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .premium-table th {
            font-size: 12px;
            color: #94a3b8;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 12px 16px;
            border-bottom: 1px solid #f1f5f9;
            text-align: left;
        }

        .premium-table td {
            padding: 16px;
            font-size: 14px;
            color: #475569;
            border-bottom: 1px solid #f8fafc;
            vertical-align: middle;
        }

        .premium-table tr:last-child td {
            border-bottom: none;
        }

        .premium-table tbody tr {
            transition: background-color 0.2s;
        }

        .premium-table tbody tr:hover {
            background-color: #f8fafc;
        }

        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            display: inline-block;
            letter-spacing: 0.5px;
        }

        .status-pending {
            background: #fef3c7;
            color: #d97706;
        }

        .status-approved {
            background: #d1fae5;
            color: #059669;
        }

        .status-rejected {
            background: #fee2e2;
            color: #dc2626;
        }
    </style>

    <div class="status-banner banner-{{ $status }}">
        <div>
            <h3 class="font-bold text-lg mb-1">Selamat Datang di LPK Center</h3>
            @if($status == 'approved')
                <p class="text-sm opacity-90">✅ Pendaftaran LPK Anda telah <strong>Diverifikasi</strong>. Anda memiliki akses
                    penuh ke sistem.</p>
            @elseif($status == 'pending')
                <p class="text-sm opacity-90">⏳ Pendaftaran LPK Anda sedang <strong>Menunggu Verifikasi</strong> oleh Super
                    Admin.</p>
            @else
                <p class="text-sm opacity-90">❌ Pendaftaran LPK Anda <strong>Ditolak</strong>. Silakan hubungi Super Admin untuk
                    info lebih lanjut.</p>
            @endif
        </div>
        <div class="hidden md:block">
            <span class="status-badge status-{{ $status }} px-4 py-2 text-sm shadow-sm">
                STATUS: {{ strtoupper($status) }}
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="stat-card">
            <div>
                <p class="stat-title">Total Students</p>
                <p class="stat-number">{{ $totalStudents ?? 0 }}</p>
            </div>
            <div class="icon-box i1">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                    </path>
                </svg>
            </div>
        </div>

        <div class="stat-card">
            <div>
                <p class="stat-title">Total Courses</p>
                <p class="stat-number">{{ $totalCourses ?? 0 }}</p>
            </div>
            <div class="icon-box i2">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                    </path>
                </svg>
            </div>
        </div>

        <div class="stat-card">
            <div>
                <p class="stat-title">Upcoming Classes</p>
                <p class="stat-number">{{ $upcomingClasses ?? 0 }}</p>
            </div>
            <div class="icon-box i3">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
        </div>

        <div class="stat-card">
            <div>
                <p class="stat-title">Pending Bookings</p>
                <p class="stat-number">{{ $pendingBookings ?? 0 }}</p>
            </div>
            <div class="icon-box i4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                    </path>
                </svg>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        <div class="content-card lg:col-span-2">
            <div class="card-header">
                Activity Overview
                <button
                    class="text-sm font-medium text-indigo-500 bg-indigo-50 px-3 py-1.5 rounded-lg hover:bg-indigo-100 transition-colors">View
                    Details</button>
            </div>
            <div style="height: 320px; width: 100%;">
                <canvas id="chartLine"></canvas>
            </div>
        </div>

        <div class="content-card">
            <div class="card-header">
                Distribution Summary
            </div>
            <div style="height: 320px; width: 100%; display: flex; align-items: center; justify-content: center;">
                <canvas id="chartPie"></canvas>
            </div>
        </div>
    </div>

    <div class="content-card">
        <div class="card-header">
            Recent Bookings
            <a href="{{ route('admin.bookings.index') ?? '#' }}"
                class="text-sm font-medium text-indigo-500 hover:text-indigo-700">View All →</a>
        </div>

        <div class="overflow-x-auto">
            <table class="premium-table">
                <thead>
                    <tr>
                        <th>Booking ID</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th class="text-right">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentBookings ?? [] as $booking)
                        <tr>
                            <td class="font-medium text-gray-800">#BKG-{{ str_pad($booking->id, 5, '0', STR_PAD_LEFT) }}</td>
                            <td class="text-gray-500">
                                {{ \Carbon\Carbon::parse($booking->created_at)->format('d M Y') ?? 'N/A' }}</td>
                            <td>
                                @php
                                    $statusClass = 'status-pending';
                                    if (strtolower($booking->status) == 'approved' || strtolower($booking->status) == 'completed')
                                        $statusClass = 'status-approved';
                                    if (strtolower($booking->status) == 'rejected' || strtolower($booking->status) == 'cancelled')
                                        $statusClass = 'status-rejected';
                                @endphp
                                <span class="status-badge {{ $statusClass }}">
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </td>
                            <td class="text-right">
                                <button
                                    class="text-indigo-500 hover:text-indigo-800 bg-indigo-50 p-2 rounded-lg hover:bg-indigo-100 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                        </path>
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-8 text-gray-400 font-medium">No recent bookings found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <script>
        Chart.defaults.font.family = "'Inter', 'Outfit', sans-serif";
        Chart.defaults.color = '#94a3b8';
        Chart.defaults.scale.grid.color = '#f8fafc';

        const ctx = document.getElementById('chartLine').getContext('2d');

        // Gradient Soft Indigo
        const gradientIndigo = ctx.createLinearGradient(0, 0, 0, 400);
        gradientIndigo.addColorStop(0, 'rgba(129, 140, 248, 0.2)');
        gradientIndigo.addColorStop(1, 'rgba(129, 140, 248, 0)');

        // Gradient Soft Teal
        const gradientTeal = ctx.createLinearGradient(0, 0, 0, 400);
        gradientTeal.addColorStop(0, 'rgba(45, 212, 191, 0.2)');
        gradientTeal.addColorStop(1, 'rgba(45, 212, 191, 0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [
                    {
                        label: 'Students',
                        data: [10, 25, 15, 35, 22, 45],
                        borderColor: '#818cf8', // Soft Indigo
                        backgroundColor: gradientIndigo,
                        borderWidth: 3,
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: '#818cf8',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    },
                    {
                        label: 'Courses',
                        data: [5, 12, 18, 15, 28, 24],
                        borderColor: '#2dd4bf', // Soft Teal
                        backgroundColor: gradientTeal,
                        borderWidth: 3,
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: '#2dd4bf',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'top', align: 'end', labels: { usePointStyle: true, boxWidth: 8, padding: 20 } },
                    tooltip: { backgroundColor: '#1e293b', padding: 12, cornerRadius: 8, displayColors: false }
                },
                scales: {
                    y: { beginAtZero: true, border: { display: false } },
                    x: { border: { display: false }, grid: { display: false } }
                },
                interaction: { intersect: false, mode: 'index' },
            }
        });

        // Donut Chart
        new Chart(document.getElementById('chartPie'), {
            type: 'doughnut',
            data: {
                labels: ['Students', 'Courses', 'Bookings'],
                datasets: [{
                    data: [
                        {{ $totalStudents ?? 0 }},
                        {{ $totalCourses ?? 0 }},
                        {{ $pendingBookings ?? 0 }}
                    ],
                    backgroundColor: [
                        '#818cf8', // Soft Indigo
                        '#2dd4bf', // Soft Teal
                        '#fbbf24'  // Soft Amber
                    ],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '75%',
                plugins: {
                    legend: { position: 'bottom', labels: { usePointStyle: true, padding: 20 } }
                }
            }
        });
    </script>

@endsection