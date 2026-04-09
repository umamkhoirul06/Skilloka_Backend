@extends('layouts.super_admin')

@section('title','LPK')

@section('content')

<div class="bg-gray-800 p-6 rounded">

<h3 class="text-lg mb-4">
Daftar LPK
</h3>

<table class="w-full">

<thead>

<tr class="border-b border-gray-700">

<th class="py-2">Nama</th>
<th>Email</th>
<th>Status</th>

</tr>

</thead>

<tbody>

@foreach($lpks as $lpk)

<tr class="border-b border-gray-700">

<td class="py-2">

{{ $lpk->nama ?? '-' }}

</td>

<td>

{{ $lpk->email ?? '-' }}

</td>

<td>

{{ $lpk->status_verifikasi ?? 'pending' }}

</td>

</tr>

@endforeach

</tbody>

</table>

</div>

@endsection