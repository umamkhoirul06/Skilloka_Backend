<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Keuangan Platform Skilloka</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; color: #333; margin: 20px; }
        .header { text-align: center; margin-bottom: 25px; border-bottom: 2px solid #2563eb; padding-bottom: 15px; }
        .title { font-size: 18px; font-weight: bold; color: #1e293b; }
        .subtitle { font-size: 12px; color: #64748b; margin-top: 4px; }
        .summary-box { width: 100%; margin-bottom: 20px; border-collapse: collapse; }
        .summary-box td { padding: 10px; border: 1px solid #e2e8f0; background: #f8fafc; }
        .summary-title { font-size: 10px; color: #64748b; text-transform: uppercase; font-weight: bold; }
        .summary-value { font-size: 14px; font-weight: bold; color: #0f172a; margin-top: 4px; }
        table.data { width: 100%; border-collapse: collapse; margin-top: 15px; }
        table.data th { background: #f1f5f9; padding: 8px 6px; text-align: left; font-size: 10px; border: 1px solid #cbd5e1; }
        table.data td { padding: 8px 6px; border: 1px solid #e2e8f0; font-size: 10px; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .footer { margin-top: 30px; text-align: right; font-size: 10px; color: #94a3b8; }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">SKILLOKA &bull; LAPORAN ARUS KAS PLATFORM</div>
        <div class="subtitle">Rekapitulasi Transaksi Pembayaran Pelatihan Vokasi &bull; Kabupaten Indramayu</div>
        <div style="font-size: 9px; color: #94a3b8; margin-top: 6px;">Dicetak pada: {{ now()->translatedFormat('d F Y, H:i') }} WIB</div>
    </div>

    <table class="summary-box">
        <tr>
            <td>
                <div class="summary-title">Total Gross Volume (GMV)</div>
                <div class="summary-value">Rp {{ number_format($totalGrossVolume, 0, ',', '.') }}</div>
            </td>
            <td>
                <div class="summary-title">Platform Fee ({{ $platformFeeRate }}%)</div>
                <div class="summary-value" style="color: #2563eb;">Rp {{ number_format($platformFeeTotal, 0, ',', '.') }}</div>
            </td>
            <td>
                <div class="summary-title">Hak Mitra LPK ({{ 100 - $platformFeeRate }}%)</div>
                <div class="summary-value" style="color: #059669;">Rp {{ number_format($lpkPayoutTotal, 0, ',', '.') }}</div>
            </td>
        </tr>
    </table>

    <table class="data">
        <thead>
            <tr>
                <th class="text-center" style="width: 25px;">No</th>
                <th>Kode / Ref ID</th>
                <th>Siswa / Peserta</th>
                <th>Mitra LPK</th>
                <th>Program Pelatihan</th>
                <th>Metode Pembayaran</th>
                <th class="text-right">Nominal (Rp)</th>
                <th class="text-center">Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @forelse($payments as $index => $p)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td style="font-family: monospace;">{{ $p->external_id ?? substr($p->id, 0, 8) }}</td>
                    <td>{{ $p->user->name ?? 'Siswa' }}</td>
                    <td>{{ $p->tenant->name ?? ($p->tenant->lpk->name ?? '-') }}</td>
                    <td>{{ $p->booking->course->title ?? '-' }}</td>
                    <td>{{ $p->provider ?? ucfirst($p->method) }}</td>
                    <td class="text-right" style="font-weight: bold;">{{ number_format($p->amount, 0, ',', '.') }}</td>
                    <td class="text-center">{{ $p->paid_at ? date('d/m/Y', strtotime($p->paid_at)) : date('d/m/Y', strtotime($p->created_at)) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center">Tidak ada transaksi yang tercatat.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Skilloka Platform Official Financial Ledger &bull; Dokumen sah dihasilkan oleh sistem terpusat.
    </div>
</body>
</html>
