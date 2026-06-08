@extends('layouts.super_admin')

@section('title','Finance')

@section('content')
<div class="card mb-6">
    <h3 class="text-lg font-bold mb-4">Dashboard Finansial (Arsip Transaksi)</h3>
    <div class="grid md:grid-cols-2 gap-6">
        <div class="stat">
            <div>
                <p class="stat-title">Total Transaksi</p>
                <p class="stat-number">{{ $totalPayments }}</p>
            </div>
            <div class="icon i1">💳</div>
        </div>
        <div class="stat">
            <div>
                <p class="stat-title">Total Revenue (Lunas)</p>
                <p class="stat-number text-emerald-600">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
            </div>
            <div class="icon i2">💰</div>
        </div>
    </div>
</div>

<div class="card">
    <h3 class="text-sm font-semibold mb-4">Semua Riwayat Transaksi</h3>
    <table class="table w-full">
        <thead>
            <tr>
                <th class="p-2 text-left">ID</th>
                <th class="p-2 text-left">User</th>
                <th class="p-2 text-left">Amount</th>
                <th class="p-2 text-left">Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @forelse($recentPayments as $payment)
            <tr>
                <td class="p-2">#{{ substr($payment->id, 0, 8) }}</td>
                <td class="p-2">{{ $payment->user->name ?? 'User Tidak Dikenal' }}</td>
                <td class="p-2 text-emerald-600 font-medium">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                <td class="p-2 text-gray-500">{{ $payment->created_at->format('d M Y') }}</td>
            </tr>
            @empty
            <tr><td colspan="4" class="text-center p-4 text-gray-400">Belum ada data transaksi</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection