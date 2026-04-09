@extends('layouts.super_admin')

@section('title','Users')

@section('content')

<div class="bg-gray-800 p-6 rounded">

<table class="w-full">

<thead>

<tr>

<th class="p-3 text-left">
Nama
</th>

<th class="p-3 text-left">
Email
</th>

<th class="p-3 text-left">
Role
</th>

</tr>

</thead>



<tbody>

@foreach($users as $user)

<tr class="border-b border-gray-700">

<td class="p-3">

{{ $user->name }}

</td>



<td class="p-3">

{{ $user->email }}

</td>



<td class="p-3">

{{ $user->role }}

</td>

</tr>

@endforeach


</tbody>

</table>

</div>

@endsection