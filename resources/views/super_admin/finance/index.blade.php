@extends('layouts.admin')

@section('title','Finance')

@section('content')

<style>

.stat{
background:white;
border-radius:12px;
padding:18px;
border:1px solid #e5e7eb;
display:flex;
align-items:center;
justify-content:space-between;
}

.stat-title{
font-size:13px;
color:#6b7280;
}

.stat-number{
font-size:22px;
font-weight:600;
}

.icon{
width:42px;
height:42px;
border-radius:10px;
display:flex;
align-items:center;
justify-content:center;
}

.i1{
background:#eef2ff;
color:#4f46e5;
}

.i2{
background:#ecfdf5;
color:#059669;
}

.card{
background:white;
border-radius:12px;
border:1px solid #e5e7eb;
padding:20px;
}

.table th{
font-size:13px;
color:#6b7280;
font-weight:500;
}

.table tr{
border-bottom:1px solid #f1f5f9;
}

</style>



<!-- statistik -->
<div class="grid md:grid-cols-2 gap-6 mb-6">

<div class="stat">

<div>

<p class="stat-title">
Total Transaksi
</p>

<p class="stat-number">
{{ $totalPayments }}
</p>

</div>

<div class="icon i1">
💳
</div>

</div>



<div class="stat">

<div>

<p class="stat-title">
Total Revenue
</p>

<p class="stat-number text-emerald-600">
Rp {{ number_format($totalRevenue) }}
</p>

</div>

<div class="icon i2">
💰
</div>

</div>

</div>





<!-- tabel transaksi -->
<div class="card">

<h3 class="text-sm font-semibold mb-4">
Transaksi Terbaru
</h3>


<table class="table w-full">

<thead>

<tr>

<th class="p-2 text-left">
ID
</th>

<th class="p-2 text-left">
User
</th>

<th class="p-2 text-left">
Amount
</th>

<th class="p-2 text-left">
Tanggal
</th>

</tr>

</thead>



<tbody>

@forelse($recentPayments as $payment)

<tr>

<td class="p-2">
#{{ $payment->id }}
</td>

<td class="p-2">
{{ $payment->user->name ?? '-' }}
</td>

<td class="p-2 text-emerald-600 font-medium">
Rp {{ number_format($payment->amount) }}
</td>

<td class="p-2 text-gray-500">
{{ $payment->created_at->format('d M Y') }}
</td>

</tr>

@empty

<tr>

<td colspan="4"
class="text-center p-4 text-gray-400">

Belum ada transaksi

</td>

</tr>

@endforelse


</tbody>

</table>

</div>



@endsection