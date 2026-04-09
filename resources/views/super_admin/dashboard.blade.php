@extends('layouts.super_admin')

@section('title','Global Dashboard')

@section('content')

<div class="grid grid-cols-4 gap-6 mb-8">

<div class="bg-gray-800 p-5 rounded shadow">

<h4>Total LPK</h4>

<p class="text-3xl text-blue-400">

{{ $totalLpk }}

</p>

</div>



<div class="bg-gray-800 p-5 rounded shadow">

<h4>Total Courses</h4>

<p class="text-3xl text-green-400">

{{ $totalCourses }}

</p>

</div>



<div class="bg-gray-800 p-5 rounded shadow">

<h4>Total Users</h4>

<p class="text-3xl text-purple-400">

{{ $totalUsers }}

</p>

</div>



<div class="bg-gray-800 p-5 rounded shadow">

<h4>Pending Verifications</h4>

<p class="text-3xl text-yellow-400">

{{ $pendingVerifications }}

</p>

</div>

</div>




<div class="bg-gray-800 p-6 rounded shadow mb-8">

<h3 class="mb-4">

Revenue Statistik

</h3>



<canvas id="chart"></canvas>


</div>




<div class="bg-gray-800 p-6 rounded shadow">

<h3 class="mb-4">

LPK Pending Verification

</h3>



<table class="w-full">

<thead>

<tr>

<th>LPK</th>

<th>Status</th>

</tr>

</thead>



<tbody>

@foreach($pendingLpks as $lpk)

<tr>

<td>

{{ $lpk->nama_lpk }}

</td>

<td>

{{ $lpk->status_verifikasi }}

</td>

</tr>

@endforeach


</tbody>


</table>

</div>




<script>

new Chart(

document.getElementById('chart'),

{

type:'bar',

data:{

labels:['LPK','Courses','Users'],

datasets:[{

label:'Total',

data:[

{{ $totalLpk }},

{{ $totalCourses }},

{{ $totalUsers }}

]

}]

}

}

)

</script>

@endsection