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
            font-family: sans-serif;
            font-size: 11px;
            color: #1e293b;
            background: #fff;
        }

        .header {
            background: #4f46e5;
            color: white;
            padding: 20px 30px;
            margin-bottom: 20px;
        }

        .header h1 {
            font-size: 18px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        thead tr {
            background: #4f46e5;
            color: white;
        }

        th,
        td {
            padding: 8px;
            border: 1px solid #e2e8f0;
            text-align: left;
        }

        /* 🔥 FIX: Mapping warna badge sesuai status DB kamu */
        .badge {
            padding: 3px 8px;
            border-radius: 4px;
            font-weight: bold;
        }

        .bg-menunggu {
            background: #fef3c7;
            color: #d97706;
        }

        .bg-selesai {
            background: #d1fae5;
            color: #059669;
        }

        .bg-dibatalkan {
            background: #fee2e2;
            color: #dc2626;
        }
    </style>
</head>

<body>

    <div class="header">
        <h1>Laporan Finansial Skilloka</h1>
        <p>Periode: {{ now()->format('d M Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Kode</th>
                <th>Student</th>
                <th>Course</th>
                <th>Biaya</th>
                <th>Status Booking</th>
            </tr>
        </thead>
        <tbody>
            @forelse($bookings as $i => $booking)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $booking->code }}</td>
                    <td>{{ $booking->user->name ?? '-' }}</td>
                    <td>{{ $booking->schedule->course->title ?? '-' }}</td>
                    <td>Rp {{ number_format($booking->amount, 0, ',', '.') }}</td>
                    <td>
                        {{-- 🔥 FIX: Mapping status manual ke CSS class --}}
                        @php 
                                        $statusClass = strtolower($booking->status) == 'selesai' ? 'bg-selesai' :
                            (strtolower($booking->status) == 'dibatalkan' ? 'bg-dibatalkan' : 'bg-menunggu');
                        @endphp
                        <span class="badge {{ $statusClass }}">{{ $booking->status }}</span>

                    </td>

                            </tr>
            @empty
                <tr><td colspan="6" style="text-align:center;">Data tidak ditemukan</td></tr>
            @endforelse
    </t
body>
</table>

</body>
</html>