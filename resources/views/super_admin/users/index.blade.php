@extends('layouts.admin')

@section('title','Users')

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

.table td{
font-size:14px;
}

.table tr{
border-bottom:1px solid #f1f5f9;
}



/* badge role */

.badge{
padding:4px 10px;
border-radius:6px;
font-size:12px;
font-weight:500;
}

.role-user{
background:#e0f2fe;
color:#0369a1;
}

.role-admin{
background:#ede9fe;
color:#5b21b6;
}

.role-super{
background:#dcfce7;
color:#166534;
}



/* search */

.search{
width:250px;
padding:8px 12px;
border-radius:8px;
border:1px solid #e5e7eb;
font-size:14px;
}

</style>




<div class="card">

<div class="flex justify-between items-center mb-4">

<h2 class="text-lg font-semibold">

Users

</h2>



<input
type="text"
placeholder="Cari user..."
class="search">

</div>





<table class="table w-full">

<thead>

<tr>

<th class="p-2 text-left">
Nama
</th>

<th class="p-2 text-left">
Email
</th>

<th class="p-2 text-left">
Role
</th>

</tr>

</thead>



<tbody>

@forelse($users as $user)

<tr>

<td class="p-2 font-medium">

{{ $user->name }}

</td>


<td class="p-2 text-gray-600">

{{ $user->email }}

</td>


<td class="p-2">

@if($user->role == 'user')

<span class="badge role-user">

User

</span>

@endif



@if($user->role == 'admin_lpk')

<span class="badge role-admin">

Admin LPK

</span>

@endif



@if($user->role == 'super_admin')

<span class="badge role-super">

Super Admin

</span>

@endif


</td>

</tr>

@empty

<tr>

<td colspan="3"
class="text-center p-4 text-gray-400">

Belum ada user

</td>

</tr>

@endforelse


</tbody>

</table>



</div>



@endsection