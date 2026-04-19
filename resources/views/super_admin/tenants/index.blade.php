@extends('layouts.admin')

@section('title','Data LPK')

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
}

.table tr{
border-bottom:1px solid #f1f5f9;
}

.badge{
padding:4px 8px;
border-radius:6px;
font-size:12px;
}

.approved{
background:#dcfce7;
color:#166534;
}

.pending{
background:#fef3c7;
color:#92400e;
}

.rejected{
background:#fee2e2;
color:#991b1b;
}

</style>



<div class="card">

<h2 class="text-lg font-semibold mb-4">
Daftar LPK
</h2>



<table class="table w-full">

<thead>

<tr>

<th class="p-2 text-left">Nama</th>

<th class="p-2 text-left">Email</th>

<th class="p-2 text-left">Status</th>

</tr>

</thead>



<tbody>

@forelse($tenants as $tenant)

<tr>

<td class="p-2">
{{ $tenant->lpk_name ?? '-' }}
</td>


<td class="p-2">
{{ $tenant->users->first()->email ?? '-' }}
</td>


<td class="p-2">

<span class="badge 
@if($tenant->status_verification == 'approved') approved
@elseif($tenant->status_verification == 'rejected') rejected
@else pending
@endif
">

{{ $tenant->status_verification ?? 'pending' }}

</span>

</td>

</tr>

@empty

<tr>

<td colspan="3" class="p-3 text-center text-gray-400">

Belum ada data

</td>

</tr>

@endforelse


</tbody>

</table>

</div>

@endsection