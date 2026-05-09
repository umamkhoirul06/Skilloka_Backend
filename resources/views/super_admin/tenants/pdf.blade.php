<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<style>
    * { margin:0; padding:0; box-sizing:border-box; }
    body { font-family:'DejaVu Sans',sans-serif; font-size:10px; color:#1e293b; }

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
        padding: 6px 16px; border-radius:999px;
        font-size:11px; font-weight:600;
    }

    .summary { display:flex; gap:14px; margin:0 28px 20px; }
    .summary-card {
        flex:1; background:#f8fafc;
        border:1px solid #e2e8f0;
        border-radius:10px; padding:14px 16px; text-align:center;
    }
    .summary-card .num { font-size:22px; font-weight:800; color:#4f46e5; }
    .summary-card .lbl { font-size:9px; color:#94a3b8; font-weight:600; text-transform:uppercase; margin-top:3px; }

    .section { margin:0 28px 22px; }
    .section-title {
        font-size:12px; font-weight:700; color:#4f46e5;
        border-left:3px solid #4f46e5;
        padding-left:10px; margin-bottom:10px;
        text-transform:uppercase; letter-spacing:.5px;
    }

    table { width:100%; border-collapse:collapse; }
    thead tr { background:#1e1b4b; color:white; }
    thead th { padding:9px 11px; text-align:left; font-size:9px; font-weight:600; text-transform:uppercase; letter-spacing:.5px; }
    tbody tr:nth-child(even) { background:#f8fafc; }
    tbody td { padding:9px 11px; border-bottom:1px solid #f1f5f9; font-size:10px; color:#475569; }

    .badge { padding:2px 9px; border-radius:20px; font-size:9px; font-weight:700; display:inline-block; }
    .badge-active   { background:#d1fae5; color:#059669; }
    .badge-pending  { background:#fef3c7; color:#d97706; }
    .badge-rejected { background:#fee2e2; color:#dc2626; }

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
            <h1>🏫 Daftar LPK Aktif Skilloka</h1>
            <p>
                Periode:
                @if($period=='month') Bulan Ini ({{ \Carbon\Carbon::now()->format('M Y') }})
                @elseif($period=='3months') 3 Bulan Terakhir
                @elseif($period=='6months') 6 Bulan Terakhir
                @else Tahun Ini ({{ \Carbon\Carbon::now()->year }})
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
        <div class="num">{{ $tenants->count() }}</div>
        <div class="lbl">Total LPK Periode Ini</div>
    </div>
    <div class="summary-card">
        <div class="num">{{ $tenants->sum(fn($t) => $t->users->count()) }}</div>
        <div class="lbl">Total Admin</div>
    </div>
    <div class="summary-card">
        <div class="num">{{ $tenants->where('is_active', true)->count() }}</div>
        <div class="lbl">LPK Aktif</div>
    </div>
    <div class="summary-card">
        <div class="num">{{ \Carbon\Carbon::now()->format('M Y') }}</div>
        <div class="lbl">Bulan Laporan</div>
    </div>
</div>

{{-- TABLE --}}
<div class="section">
    <div class="section-title">Detail Daftar LPK</div>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Nama LPK</th>
                <th>Nama Legal</th>
                <th>NIB</th>
                <th>Kota</th>
                <th>Admin</th>
                <th>Email Admin</th>
                <th>No. HP</th>
                <th>Status</th>
                <th>Terdaftar</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tenants as $i => $tenant)
            @php $user = $tenant->users->first(); $lpk = $tenant->lpk; @endphp
            <tr>
                <td>{{ $i + 1 }}</td>
                <td><strong>{{ $lpk->name ?? $tenant->lpk_name ?? '-' }}</strong></td>
                <td>{{ $lpk->legal_name ?? '-' }}</td>
                <td>{{ $lpk->nib ?? '-' }}</td>
                <td>{{ $tenant->city ?? 'Indramayu' }}</td>
                <td>{{ $user->name ?? '-' }}</td>
                <td>{{ $user->email ?? '-' }}</td>
                <td>{{ $user->phone ?? '-' }}</td>
                <td>
                    <span class="badge badge-{{ $lpk->status ?? 'active' }}">
                        {{ ucfirst($lpk->status ?? 'active') }}
                    </span>
                </td>
                <td>{{ \Carbon\Carbon::parse($tenant->created_at)->format('d M Y') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="10" style="text-align:center;padding:20px;color:#94a3b8;">
                    Tidak ada LPK yang terdaftar pada periode ini.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- FOOTER --}}
<div class="footer">
    <span>Dicetak: {{ now()->format('d M Y, H:i') }} &nbsp;|&nbsp; System Overlord - Skilloka</span>
    <span>Skilloka LPK Report &copy; {{ now()->year }}</span>
</div>

</body>
</html>