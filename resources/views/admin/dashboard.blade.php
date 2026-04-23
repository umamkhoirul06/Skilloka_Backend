@extends('layouts.admin')

@section('title', 'Admin LPK Dashboard')

@section('content')

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
    /* Card design system */
    .stat-card {
        background: #ffffff;
        border-radius: 20px;
        padding: 24px;
        border: 1px solid rgba(226, 232, 240, 0.8);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.08);
    }

    .stat-title {
        font-size: 13px;
        color: #64748b;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
    }

    .stat-number {
        font-size: 28px;
        font-weight: 700;
        color: #0f172a;
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

    .i1 { background: linear-gradient(135deg, #e0e7ff 0%, #c7d2fe 100%); color: #4f46e5; }
    .i2 { background: linear-gradient(135deg, #cffafe 0%, #a5f3fc 100%); color: #0891b2; }
    .i3 { background: linear-gradient(135deg, #f3e8ff 0%, #e9d5ff 100%); color: #9333ea; }
    .i4 { background: linear-gradient(135deg, #ffedd5 0%, #fed7aa 100%); color: #ea580c; }

    .content-card {
        background: #ffffff;
        border-radius: 20px;
        border: 1px solid rgba(226, 232, 240, 0.8);
        padding: 24px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
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
        color: #64748b;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 12px 16px;
        border-bottom: 1px solid #e2e8f0;
        text-align: left;
    }

    .premium-table td {
        padding: 16px;
        font-size: 14px;
        color: #334155;
        border-bottom: 1px solid #f1f5f9;
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
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
    }
    
    .status-pending { background: #ffedd5; color: #ea580c; }
    .status-approved { background: #dcfce3; color: #16a34a; }
    .status-rejected { background: #fee2e2; color: #dc2626; }
</style>

<!-- ======================
     STATISTIK CARDS
     ====================== -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

    <div class="stat-card">
        <div>
            <p class="stat-title">Total Students</p>
            <p class="stat-number">{{ $totalStudents }}</p>
        </div>
        <div class="icon-box i1">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
        </div>
    </div>

    <div class="stat-card">
        <div>
            <p class="stat-title">Total Courses</p>
            <p class="stat-number">{{ $totalCourses }}</p>
        </div>
        <div class="icon-box i2">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
        </div>
    </div>

    <div class="stat-card">
        <div>
            <p class="stat-title">Upcoming Classes</p>
            <p class="stat-number">{{ $upcomingClasses }}</p>
        </div>
        <div class="icon-box i3">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
        </div>
    </div>

    <div class="stat-card">
        <div>
            <p class="stat-title">Pending Bookings</p>
            <p class="stat-number">{{ $pendingBookings }}</p>
        </div>
        <div class="icon-box i4">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
        </div>
    </div>

</div>

<!-- ======================
     CHARTS SECTION
     ====================== -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">

    <div class="content-card lg:col-span-2">
        <div class="card-header">
            Activity Overview
            <button class="text-sm font-medium text-indigo-600 bg-indigo-50 px-3 py-1.5 rounded-lg hover:bg-indigo-100 transition-colors">View Details</button>
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

<!-- ======================
     RECENT BOOKINGS
     ====================== -->
<div class="content-card">
    <div class="card-header">
        Recent Bookings
        <a href="{{ route('admin.bookings.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">View All →</a>
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
                @forelse($recentBookings as $booking)
                <tr>
                    <td class="font-medium text-gray-900">#BKG-{{ str_pad($booking->id, 5, '0', STR_PAD_LEFT) }}</td>
                    <td class="text-gray-500">{{ \Carbon\Carbon::parse($booking->created_at)->format('d M Y') ?? 'N/A' }}</td>
                    <td>
                        @php
                            $statusClass = 'status-pending';
                            if(strtolower($booking->status) == 'approved' || strtolower($booking->status) == 'completed') $statusClass = 'status-approved';
                            if(strtolower($booking->status) == 'rejected' || strtolower($booking->status) == 'cancelled') $statusClass = 'status-rejected';
                        @endphp
                        <span class="status-badge {{ $statusClass }}">
                            {{ ucfirst($booking->status) }}
                        </span>
                    </td>
                    <td class="text-right">
                        <button class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 p-2 rounded-lg hover:bg-indigo-100 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center py-8 text-gray-500">No recent bookings found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
    // Chart Configurations with modern styling
    Chart.defaults.font.family = "'Inter', sans-serif";
    Chart.defaults.color = '#64748b';
    Chart.defaults.scale.grid.color = '#f1f5f9';

    // Gradient generation for Line Chart
    const ctx = document.getElementById('chartLine').getContext('2d');
    const gradientBlue = ctx.createLinearGradient(0, 0, 0, 400);
    gradientBlue.addColorStop(0, 'rgba(79, 70, 229, 0.2)');
    gradientBlue.addColorStop(1, 'rgba(79, 70, 229, 0)');

    const gradientOrange = ctx.createLinearGradient(0, 0, 0, 400);
    gradientOrange.addColorStop(0, 'rgba(234, 88, 12, 0.2)');
    gradientOrange.addColorStop(1, 'rgba(234, 88, 12, 0)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [
                {
                    label: 'Students',
                    data: [10, 25, 15, 35, 22, 45],
                    borderColor: '#4f46e5',
                    backgroundColor: gradientBlue,
                    borderWidth: 3,
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#4f46e5',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6
                },
                {
                    label: 'Courses',
                    data: [5, 12, 18, 15, 28, 24],
                    borderColor: '#ea580c',
                    backgroundColor: gradientOrange,
                    borderWidth: 3,
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#ea580c',
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
                legend: {
                    position: 'top',
                    align: 'end',
                    labels: {
                        usePointStyle: true,
                        boxWidth: 8,
                        padding: 20
                    }
                },
                tooltip: {
                    backgroundColor: '#1e293b',
                    padding: 12,
                    titleFont: { size: 13 },
                    bodyFont: { size: 13 },
                    cornerRadius: 8,
                    displayColors: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    border: { display: false }
                },
                x: {
                    border: { display: false },
                    grid: { display: false }
                }
            },
            interaction: {
                intersect: false,
                mode: 'index',
            },
        }
    });

    // Donut Chart
    new Chart(document.getElementById('chartPie'), {
        type: 'doughnut',
        data: {
            labels: ['Students', 'Courses', 'Bookings'],
            datasets: [{
                data: [
                    {{ $totalStudents }},
                    {{ $totalCourses }},
                    {{ $pendingBookings }}
                ],
                backgroundColor: [
                    '#4f46e5', // Indigo
                    '#0891b2', // Cyan
                    '#ea580c'  // Orange
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
                legend: {
                    position: 'bottom',
                    labels: {
                        usePointStyle: true,
                        padding: 20
                    }
                }
            }
        }
    });
</script>

@endsection