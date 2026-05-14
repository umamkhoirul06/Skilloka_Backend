<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Dashboard Report - {{ date('d M Y') }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            color: #1e293b;
            font-size: 13px;
            margin: 0;
            padding: 24px;
        }

        .header {
            border-bottom: 3px solid #6366f1;
            padding-bottom: 14px;
            margin-bottom: 24px;
        }

        .header h1 {
            font-size: 22px;
            font-weight: 800;
            color: #6366f1;
            margin: 0 0 4px 0;
        }

        .header p {
            color: #64748b;
            margin: 0;
            font-size: 12px;
        }

        .stat-grid {
            display: table;
            width: 100%;
            border-spacing: 10px;
            margin-bottom: 24px;
        }

        .stat-item {
            display: table-cell;
            width: 25%;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 14px;
            text-align: center;
        }

        .stat-item .num {
            font-size: 28px;
            font-weight: 800;
            color: #6366f1;
        }

        .stat-item .lbl {
            font-size: 11px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 4px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }

        th {
            background: #6366f1;
            color: #fff;
            font-size: 11px;
            padding: 10px 12px;
            text-align: left;
            letter-spacing: 0.5px;
        }

        td {
            padding: 10px 12px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 12px;
        }

        tr:nth-child(even) td {
            background: #f8fafc;
        }

        .badge {
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: 700;
        }

        .badge-pending {
            background: #fef3c7;
            color: #d97706;
        }

        .badge-approved {
            background: #d1fae5;
            color: #059669;
        }

        .badge-rejected {
            background: #fee2e2;
            color: #dc2626;
        }

        .footer {
            border-top: 1px solid #e2e8f0;
            margin-top: 24px;
            padding-top: 10px;
            font-size: 11px;
            color: #94a3b8;
            text-align: center;
        }

        h2 {
            font-size: 15px;
            font-weight: 700;
            color: #1e293b;
            margin: 20px 0 10px 0;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>📊 Dashboard Report — Skilloka LPK</h1>
        <p>Digenerate pada: {{ date('d F Y, H:i') }} WIB | Tenant ID: {{ auth()->user()->tenant_id ?? '-' }}</p>
    </div>

    <div class="stat-grid">
        <div class="stat-item">
            <div class="num">{{ $totalStudents ?? 0 }}</div>
            <div class="lbl">Total Students</div>
        </div>
        <div class="stat-item">
            <div class="num">{{ $totalCourses ?? 0 }}</div>
            <div class="lbl">Total Courses</div>
        </div>
        <div class="stat-item">
            <div class="num">{{ $upcomingClasses ?? 0 }}</div>
            <div class="lbl">Upcoming Classes</div>
        </div>
        <div class="stat-item">
            <div class="num">{{ $pendingBookings ?? 0 }}</div>
            <div class="lbl">Pending Bookings</div>
        </div>
    </div>

    <h2>Log Pemesanan Terbaru</h2>
    <table>
        <thead>
            <tr>
                <th>Booking ID</th>
                <th>Tanggal</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($recentBookings ?? [] as $booking)
                <tr>
                    <td>#BKG-{{ str_pad($booking->id, 5, '0', STR_PAD_LEFT) }}</td>
                    <td>{{ \Carbon\Carbon::parse($booking->created_at)->format('d M Y') }}</td>
                    <td>
                        @php
                            $s = strtolower($booking->status);
                            $bc = in_array($s, ['approved', 'completed']) ? 'badge-approved' : (in_array($s, ['rejected', 'cancelled']) ? 'badge-rejected' : 'badge-pending');
                        @endphp
                        <span class="badge {{ $bc }}">{{ ucfirst($booking->status) }}</span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" style="text-align:center;color:#94a3b8;">Tidak ada data.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">Skilloka LPK Management System — Dokumen ini digenerate otomatis oleh sistem.</div>
</body>

</html>