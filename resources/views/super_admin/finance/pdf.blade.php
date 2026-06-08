<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10px;
            color: #1e293b;
            background: #fff;
        }

        /* HEADER */
        .header {
            background: #1e1b4b;
            color: white;
            padding: 20px 28px;
            margin-bottom: 20px;
        }

        .header-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header h1 {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .header p {
            font-size: 11px;
            opacity: .8;
        }

        .header-badge {
            background: rgba(255, 255, 255, .12);
            padding: 6px 16px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 600;
        }

        /* SUMMARY */
        .summary {
            display: flex;
            gap: 14px;
            margin: 0 28px 20px;
        }

        .summary-card {
            flex: 1;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 14px 16px;
            text-align: center;
        }

        .summary-card .num {
            font-size: 20px;
            font-weight: 800;
            color: #4f46e5;
        }

        .summary-card .lbl {
            font-size: 9px;
            color: #94a3b8;
            font-weight: 600;
            text-transform: uppercase;
            margin-top: 3px;
        }

        .summary-card.green .num {
            color: #059669;
        }

        .summary-card.yellow .num {
            color: #d97706;
        }

        .summary-card.blue .num {
            color: #2563eb;
        }

        /* TABLE */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 0 28px;
        }

        thead tr {
            background: #1e1b4b;
            color: white;
        }

        thead th {
            padding: 9px 11px;
            text-align: left;
            font-size: 9px;
            font-weight: 600;
            text-transform: uppercase;
        }

        tbody tr:nth-child(even) {
            background: #f8fafc;
        }

        tbody td {
            padding: 9px 11px;
            border: 1px solid #e2e8f0;
            font-size: 10px;
            color: #475569;
        }

        /* BADGE - FIXED SINKRONISASI DENGAN DB */
        .badge {
            padding: 3px 9px;
            border-radius: 20px;
            font-size: 9px;
            font-weight: 700;
            display: inline-block;
        }

        .bg-success {
            background: #d1fae5;
            color: #059669;
        }

        .bg-pending {
            background: #fef3c7;
            color: #d97706;
        }

        .bg-failed {
            background: #fee2e2;
            color: #dc2626;
        }
    </style>
</head>

<body>

    <div class="header">
        <div class="header-top">
            <div>
                <h1>📊 Laporan Keuangan Skilloka</h1>
                <p>Periode: {{ $startDate->format('d M Y') }} — {{ now()->format('d M Y') }}</p>
            </div>
            <div class="header-badge">Super Admin</div>
        </div>
    </div>

    <div class="summary">
        <div class="summary-card">
            <div class="num">{{ $totalTransaksi }}</div>
            <div class="lbl">Total Transaksi</div>
        </div>
        <div class="summary-card green">
            <div class="num">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
            <div class="lbl">Total Revenue</div>
        </div>
        <div class="summary-card yellow">
            <div class="num">Rp {{ number_format($totalPending, 0, ',', '.') }}</div>
            <div class="lbl">Pending Payment</div>
        </div>
    </div>

    <div class="section">
        <div style="font-weight:bold; margin-bottom:10px; border-left:3px solid #4f46e5; padding-left:10px;">DETAIL
            TRANSAKSI</div>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Tanggal</th>
                    <th>Student</th>
                    <th>LPK</th>
                    <th>Course</th>
                    <th>Amount</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payments as $i => $payment)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $payment->created_at->format('d M Y') }}</td>
                        <td>{{ $payment->user->name ?? '-' }}</td>
                        <td>{{ $payment->tenant->name ?? '-' }}</td>
                        <td>{{ $payment->booking->schedule->course->name ?? 'N/A' }}</td>
                        <td>Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                        <td>
                            @php 
                                                    $stat = strtolower($payment->status);
                                $class = ($stat == 'success') ? 'bg-success' : (($stat == 'failed') ? 'bg-failed' : 'bg-pending');
                            @endphp
                            <span class="badge {{ $class }}">{{ ucfirst($payment->status) }}</span>

                        </td>

                                    </tr>
                @empty
                    <tr><td colspan="7" style="text-align:center;">Tidak ada data.</td></tr>
                @endforelse
        </tbody>
        </table>
    </div>
    
<div class="footer">
    <sp
an>Dicetak: {{ now()->format('d M Y, H:i') }} | Skilloka System</span>
</div>

</body>
</html>