@extends('layouts.super_admin')

@section('title','Verifikasi LPK')

@section('content')

<div class="bg-gray-800 rounded shadow">

<table class="w-full">

<thead class="border-b border-gray-700">

<tr>

<th class="p-3 text-left">
Nama LPK
</th>

<th class="p-3 text-left">
Email
</th>

<th class="p-3 text-left">
Status
</th>

<th class="p-3 text-left">
Aksi
</th>

</tr>

</thead>



<tbody>

@foreach($lpks as $lpk)

<tr class="border-b border-gray-700">

<td class="p-3">

{{ $lpk->nama_lpk }}

</td>



<td class="p-3">

{{ $lpk->email }}

</td>



<td class="p-3">

@if($lpk->status_verifikasi == 'pending')

<span class="text-yellow-400">

Pending

</span>

@endif



@if($lpk->status_verifikasi == 'approved')

<span class="text-green-400">

Approved

</span>

@endif



@if($lpk->status_verifikasi == 'rejected')

<span class="text-red-400">

Rejected

</span>

@endif

</td>



<td class="p-3 flex gap-2">


<form method="POST"
action="{{ route('super.verifications.approve',$lpk->id) }}">

@csrf

<button
class="bg-green-500 px-3 py-1 rounded">

Approve

</button>

</form>



<form method="POST"
action="{{ route('super.verifications.reject',$lpk->id) }}">

@csrf

<button
class="bg-red-500 px-3 py-1 rounded">

Reject

</button>

</form>


</td>


</tr>

@endforeach


</tbody>


</table>


</div>

@endsection