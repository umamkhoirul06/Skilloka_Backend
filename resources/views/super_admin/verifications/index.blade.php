@extends('layouts.admin')

@section('title', 'Verifikasi LPK')

@section('content')
    <style>
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
            border-bottom: 1px solid #f1f5f9;
        }

        .badge {
            padding: 4px 12px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 700;
        }

        .pending {
            background: #fef3c7;
            color: #92400e;
        }

        .approved {
            background: #dcfce7;
            color: #166534;
        }

        .rejected {
            background: #fee2e2;
            color: #991b1b;
        }

        .btn {
            padding: 6px 16px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            border: none;
            cursor: pointer;
        }

        .btn-approve {
            background: #22c55e;
            color: white;
        }

        .btn-reject {
            background: #ef4444;
            color: white;
        }
    </style>

    <div class="card">
        <h2 class="text-lg font-semibold mb-6">Verifikasi Pendaftaran LPK</h2>

        @if(session('success'))
            <div style="background: #dcfce7; color: #166534; padding: 12px; border-radius: 8px; margin-bottom: 20px;">
        {{ session('success') }}</div> @endif

        <table class="table w-full">
            <thead>
                <tr>
                    <th class="p-3 text-left">Nama LPK</th>
                    <th class="p-3 text-left">Email Admin</th>
                    <th class="p-3 text-left">Status</th>
                    <th class="p-3 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tenants as $tenant)
                    @php
                        $lpk = \App\Models\Lpk::where('tenant_id', $tenant->id)->first();
                        $status = $lpk ? $lpk->status_verifikasi : 'pending';
                    @endphp
                    <tr style="border-bottom: 1px solid #f8fafc;">
                        <td class="p-3">{{ $tenant->lpk_name }}</td>
                        <td class="p-3 text-gray-500">{{ $tenant->users->first()->email ?? '-' }}</td>
                        <td class="p-3">
                            <span class="badge {{ $status }}">
                                {{ strtoupper($status) }}
                            </span>
                        </td>
                        <td class="p-3 flex gap-2">
                            <form method="POST" action="{{ route('super.verifications.approve', $tenant->id) }}"> @csrf
                                <button class="btn btn-approve">Approve & Aktifkan</button>
                            </form>
                            <form method="POST" action="{{ route('super.verifications.reject', $tenant->id) }}"> @csrf
                                <button class="btn btn-reject">Reject</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection