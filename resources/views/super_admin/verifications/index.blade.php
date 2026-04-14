@extends('layouts.admin')

@section('title','Verifikasi LPK')

@section('content')

<style>

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

.badge{
padding:4px 10px;
border-radius:6px;
font-size:12px;
}

.pending{
background:#fef3c7;
color:#92400e;
}

.approved{
background:#dcfce7;
color:#166534;
}

.rejected{
background:#fee2e2;
color:#991b1b;
}

.btn{
padding:6px 12px;
border-radius:8px;
font-size:13px;
font-weight:500;
}

.btn-approve{
background:#22c55e;
color:white;
}

.btn-reject{
background:#ef4444;
color:white;
}

</style>



<div class="card">

<h2 class="text-lg font-semibold mb-4">
Verifikasi LPK
</h2>



<table class="table w-full">

<thead>

<tr>

<th class="p-2 text-left">
Nama LPK
</th>

<th class="p-2 text-left">
Email
</th>

<th class="p-2 text-left">
Status
</th>

<th class="p-2 text-left">
Aksi
</th>

</tr>

</thead>



<tbody>

@forelse($lpks as $lpk)

<tr>

<td class="p-2">
{{ $lpk->nama }}
</td>


<td class="p-2">
{{ $lpk->email }}
</td>


<td class="p-2">

<span class="badge {{ $lpk->status_verifikasi }}">

{{ ucfirst($lpk->status_verifikasi) }}

</span>

</td>


<td class="p-2 flex gap-2">

<form method="POST"
action="{{ route('super.verifications.approve',$lpk->id) }}">

@csrf

<button class="btn btn-approve">

Approve

</button>

</form>



<form method="POST"
action="{{ route('super.verifications.reject',$lpk->id) }}">

@csrf

<button class="btn btn-reject">

Reject

</button>

</form>

</td>

</tr>

@empty

<tr>

<td colspan="4"
class="text-center p-4 text-gray-400">

Belum ada data verifikasi

</td>

</tr>

@endforelse


</tbody>

</table>

</div>


@endsection