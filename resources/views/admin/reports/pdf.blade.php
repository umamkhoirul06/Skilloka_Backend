<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; color: #1e293b; background: #fff; }

        .header {
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            color: white;
            padding: 24px 30px;
            margin-bottom: 24px;
        }
        .header h1 { font-size: 22px; font-weight: 700; margin-bottom: 4px; }
        .header p  { font-size: 12px; opacity: 0.85; }

        .meta {
            display: flex;
            justify-content: space-between;
            margin: 0 30px 20px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 14px 20px;
        }
        .meta-item label { font-size: 10px; color: #94a3b8; font-weight: 600; text-transform: uppercase; display: block; margin-bottom: 3px; }
        .meta-item span  { font-size: 13px; font-weight: 700; color: #1e293b; }

        .section { margin: 0 30px 24px; }
        .section-title {
            font-size: 13px; font-weight: 700; color: #4f46e5;
            border-left: 3px solid #4f46e5;
            padding-left: 10px; margin-bottom: 12px;
        }

        table { width: 100%; border-collapse: collapse; }
        thead tr { background: #4f46e5; color: white; }
        thead th { padding: 10px 12px; text-align: left; font-size: 10px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }
        tbody tr:nth-child(even) { background: #f8fafc; }
        tbody tr:hover { background: #f1f5f9; }
        tbody td { padding: 10px 12px; border-bottom: 1px solid #f1f5f9; font-size: 11px; color: #475569; }

        .badge {
            padding: 3px 10px; border-radius: 20px; font-size: 10px; font-weight: 700; display: inline-block;
        }
        .badge-approved  { background: #d1fae5; color: #059669; }
        .badge-pending   { background: #fef3c7; color: #d97706; }
        .badge-rejected  { background: #fee2e2; color: #dc2626; }
        .badge-paid      { background: #dbeafe; color: #2563eb; }
        .badge-completed { background: #d1fae5; color: #059669; }
        .badge-cancelled { background: #fee2e2; color: #dc2626; }

        .summary {
            display: flex;
            gap: 16px;
            margin: 0 30px 24px;
        }
        .summary-card {
            flex: 1;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 14px 18px;
            text-align: center;
        }
        .summary-card .num  { font-size: 24px; font-weight: 800; color: #4f46e5; }
        .summary-card .lbl  { font-size: 10px; color: #94a3b8; font-weight: 600; text-transform: uppercase; margin-top: 4px; }

        .footer {
            margin: 30px 30px 0;
            padding-top: 14px;
            border-top: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            font-size: 10px;
            color: #94a3b8;
        }
    </style>
</head>
<body>

{{-- HEADER --}}
<div class="header">
    <h1>{{ $lpk->name ?? 'Laporan LPK' }}</h1>
    <p>
        Laporan Periode:
        @if($period == 'month') Bulan Ini
        @elseif($period == '3months') 3 Bulan Terakhir
        @elseif($period == '6months') 6 Bulan Terakhir
        @else Tahun Ini
        @endif
        &nbsp;|&nbsp; {{ $startDate->format('d M Y') }} — {{ now()->format('d M Y') }}
    </p>
</div>

{{-- SUMMARY --}}
<div class="summary">
    <div class="summary-card">
        <div class="num">{{ $bookings->count() }}</div>
        <div class="lbl">Total Booking</div>
    </div>
    <div class="summary-card">
        <div class="num">{{ $bookings->where('status', 'approved')->count() }}</div>
        <div class="lbl">Disetujui</div>
    </div>
    <div class="summary-card">
        <div class="num">{{ $bookings->where('status', 'pending')->count() }}</div>
        <div class="lbl">Pending</div>
    </div>
    <div class="summary-card">
        <div class="num">Rp {{ number_format($bookings->sum('amount'), 0, ',', '.') }}</div>
        <div class="lbl">Total Pendapatan</div>
    </div>
</div>

{{-- TABLE --}}
<div class="section">
    <div class="section-title">Detail Booking</div>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Kode Booking</th>
                <th>Nama Student</th>
                <th>No. HP</th>
                <th>Course</th>
                <th>Jadwal</th>
                <th>Biaya</th>
                <th>Status</th>
                <th>Pembayaran</th>
            </tr>
        </thead>
        <tbody>
            @forelse($bookings as $i => $booking)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td><strong>{{ $booking->code }}</strong></td>
                <td>{{ $booking->user->name ?? '-' }}</td>
                <td>{{ $booking->user->phone ?? '-' }}</td>
                <td>{{ $booking->schedule->course->name ?? '-' }}</td>
                <td>
                    @if($booking->schedule)
                        {{ \Carbon\Carbon::parse($booking->schedule->start_date)->format('d M Y') }}
                        —
                        {{ \Carbon\Carbon::parse($booking->schedule->end_date)->format('d M Y') }}
                    @else
                        -
                    @endif
                </td>
                <td>Rp {{ number_format($booking->amount ?? 0, 0, ',', '.') }}</td>
                <td>
                    <span class="badge badge-{{ strtolower($booking->status) }}">
                        {{ ucfirst($booking->status) }}
                    </span>
                </td>
                <td>
                    <span class="badge badge-{{ strtolower($booking->payment_status) }}">
                        {{ ucfirst($booking->payment_status) }}
                    </span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" style="text-align:center; padding: 20px; color: #94a3b8;">
                    Tidak ada data booking pada periode ini.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- FOOTER --}}
<div class="footer">
    <span>Dicetak oleh: {{ auth()->user()->name }} — {{ now()->format('d M Y, H:i') }}</span>
    <span>Skilloka &copy; {{ now()->year }}</span>
</div>

</body>
</html>