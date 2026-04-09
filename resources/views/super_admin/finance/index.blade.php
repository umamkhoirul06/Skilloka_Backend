@extends('layouts.super_admin')

@section('title','Finance')

@section('content')

<div class="grid grid-cols-2 gap-6 mb-6">

<div class="bg-gray-800 p-5 rounded">

<h4>Total Transaksi</h4>

<p class="text-2xl text-blue-400">

{{ $totalPayments }}

</p>

</div>



<div class="bg-gray-800 p-5 rounded">

<h4>Total Revenue</h4>

<p class="text-2xl text-green-400">

Rp {{ number_format($totalRevenue) }}

</p>

</div>

</div>



<div class="bg-gray-800 p-5 rounded">

<h4 class="mb-4">

Transaksi Terbaru

</h4>

<table class="w-full">

<thead>

<tr>

<th>ID</th>

<th>Amount</th>

</tr>

</thead>



<tbody>

@foreach($recentPayments as $payment)

<tr>

<td>

{{ $payment->id }}

</td>

<td>

Rp {{ number_format($payment->amount) }}

</td>

</tr>

@endforeach


</tbody>

</table>

</div>

@endsection