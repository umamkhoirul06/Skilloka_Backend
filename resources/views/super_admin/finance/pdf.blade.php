<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<style>
    * { margin:0; padding:0; box-sizing:border-box; }
    body { font-family:'DejaVu Sans',sans-serif; font-size:10px; color:#1e293b; }

    /* HEADER */
    .header {
        background: #1e1b4b;
        color: white;
        padding: 20px 28px;
        margin-bottom: 20px;
    }
    .header-top { display:flex; justify-content:space-between; align-items:center; }
    .header h1  { font-size:20px; font-weight:700; margin-bottom:4px; }
    .header p   { font-size:11px; opacity:.8; }
    .header-badge {
        background: rgba(255,255,255,.12);
        border: 1px solid rgba(255,255,255,.2);
        padding: 6px 16px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 600;
    }

    /* SUMMARY */
    .summary { display:flex; gap:14px; margin:0 28px 20px; }
    .summary-card {
        flex:1; background:#f8fafc;
        border:1px solid #e2e8f0;
        border-radius:10px; padding:14px 16px; text-align:center;
    }
    .summary-card .num { font-size:20px; font-weight:800; color:#4f46e5; }
    .summary-card .lbl { font-size:9px; color:#94a3b8; font-weight:600; text-transform:uppercase; margin-top:3px; }
    .summary-card.green .num { color:#059669; }
    .summary-card.yellow .num { color:#d97706; }
    .summary-card.blue .num { color:#2563eb; }

    /* SECTION */
    .section { margin:0 28px 22px; }
    .section-title {
        font-size:12px; font-weight:700; color:#4f46e5;
        border-left:3px solid #4f46e5;
        padding-left:10px; margin-bottom:10px;
        text-transform:uppercase; letter-spacing:.5px;
    }

    /* TABLE */
    table { width:100%; border-collapse:collapse; }
    thead tr { background:#1e1b4b; color:white; }
    thead th { padding:9px 11px; text-align:left; font-size:9px; font-weight:600; text-transform:uppercase; letter-spacing:.5px; }
    tbody tr:nth-child(even) { background:#f8fafc; }
    tbody td { padding:9px 11px; border-bottom:1px solid #f1f5f9; font-size:10px; color:#475569; }

    /* BADGE */
    .badge { padding:2px 9px; border-radius:20px; font-size:9px; font-weight:700; display:inline-block; }
    .badge-success  { background:#d1fae5; color:#059669; }
    .badge-pending  { background:#fef3c7; color:#d97706; }
    .badge-rejected { background:#fee2e2; color:#dc2626; }
    .badge-failed   { background:#fee2e2; color:#dc2626; }

    /* LPK TABLE */
    .lpk-grid { display:flex; gap:12px; flex-wrap:wrap; margin:0 28px 20px; }
    .lpk-card {
        flex:1; min-width:180px;
        background:#f8fafc; border:1px solid #e2e8f0;
        border-radius:10px; padding:12px 14px;
    }
    .lpk-card .lpk-name { font-size:11px; font-weight:700; color:#1e293b; margin-bottom:4px; }
    .lpk-card .lpk-info { font-size:9px; color:#94a3b8; }

    /* DIVIDER */
    .divider { border:none; border-top:2px solid #e2e8f0; margin:0 28px 20px; }

    /* FOOTER */
    .footer {
        margin:20px 28px 0;
        padding-top:12px;
        border-top:1px solid #e2e8f0;
        display:flex;
        justify-content:space-between;
        font-size:9px; color:#94a3b8;
    }
</style>
</head>
<body>

{{-- HEADER --}}
<div class="header">
    <div class="header-top">
        <div>
            <h1>📊 Laporan Keuangan Skilloka</h1>
            <p>
                Periode:
                @if($period=='month') Bulan Ini ({{ Carbon\Carbon::now()->format('M Y') }})
                @elseif($period=='3months') 3 Bulan Terakhir
                @elseif($period=='6months') 6 Bulan Terakhir
                @else Tahun Ini ({{ Carbon\Carbon::now()->year }})
                @endif
                &nbsp;|&nbsp; {{ $startDate->format('d M Y') }} — {{ now()->format('d M Y') }}
            </p>
        </div>
        <div class="header-badge">Super Admin Report</div>
    </div>
</div>

{{-- SUMMARY --}}
<div class="summary">
    <div class="summary-card">
        <div class="num">{{ $totalTransaksi }}</div>
        <div class="lbl">Total Transaksi</div>
    </div>
    <div class="summary-card green">
        <div class="num">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
        <div class="lbl">Total Revenue (Success)</div>
    </div>
    <div class="summary-card yellow">
        <div class="num">Rp {{ number_format($totalPending, 0, ',', '.') }}</div>
        <div class="lbl">Pending Payment</div>
    </div>
    <div class="summary-card blue">
        <div class="num">{{ $payments->where('status','success')->count() }}</div>
        <div class="lbl">Transaksi Sukses</div>
    </div>
</div>

{{-- LPK AKTIF --}}
@if($lpkStats->count() > 0)
<div class="section">
    <div class="section-title">LPK Aktif ({{ $lpkStats->count() }})</div>
    <div class="lpk-grid">
        @foreach($lpkStats as $lpk)
        <div class="lpk-card">
            <div class="lpk-name">{{ $lpk->name }}</div>
            <div class="lpk-info">{{ $lpk->courses_count }} Course &nbsp;|&nbsp; {{ $lpk->address ?? 'Indramayu' }}</div>
        </div>
        @endforeach
    </div>
</div>
@endif

<hr class="divider">

{{-- DETAIL TRANSAKSI --}}
<div class="section">
    <div class="section-title">Detail Transaksi Pembayaran</div>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Tanggal</th>
                <th>User / Student</th>
                <th>LPK</th>
                <th>Course</th>
                <th>Metode</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Dibayar</th>
            </tr>
        </thead>
        <tbody>
            @forelse($payments as $i => $payment)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $payment->created_at->format('d M Y') }}</td>
                <td>
                    <strong>{{ $payment->user->name ?? '-' }}</strong><br>
                    <span style="font-size:9px;color:#94a3b8;">{{ $payment->user->phone ?? '' }}</span>
                </td>
                <td>{{ $payment->tenant->name ?? '-' }}</td>
                <td>{{ $payment->booking->schedule->course->name ?? '-' }}</td>
                <td>{{ ucfirst($payment->method ?? '-') }}</td>
                <td><strong>Rp {{ number_format($payment->amount, 0, ',', '.') }}</strong></td>
                <td>
                    <span class="badge badge-{{ strtolower($payment->status) }}">
                        {{ ucfirst($payment->status) }}
                    </span>
                </td>
                <td>{{ $payment->paid_at ? $payment->paid_at->format('d M Y') : '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="9" style="text-align:center;padding:20px;color:#94a3b8;">
                    Tidak ada transaksi pada periode ini.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- FOOTER --}}
<div class="footer">
    <span>Dicetak: {{ now()->format('d M Y, H:i') }} &nbsp;|&nbsp; System Overlord - Skilloka</span>
    <span>Skilloka Finance Report &copy; {{ now()->year }}</span>
</div>

</body>
</html>