@extends('layouts.admin')

@section('title','Admin LPK Dashboard')

@section('content')

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>

/* background sama */
body{
background:#f3f4f6;
font-family:Inter,sans-serif;
}


/* statistik card */

.stat{
background:white;
border-radius:14px;
padding:16px 18px;
border:1px solid #e5e7eb;
display:flex;
align-items:center;
justify-content:space-between;
}


.stat-title{
font-size:12px;
color:#6b7280;
margin-bottom:4px;
}


.stat-number{
font-size:20px;
font-weight:600;
color:#111827;
}


/* icon soft box */

.icon{
width:38px;
height:38px;
border-radius:10px;
display:flex;
align-items:center;
justify-content:center;
font-size:16px;
}


.i1{background:#eef2ff;color:#6366f1;}
.i2{background:#ecfeff;color:#06b6d4;}
.i3{background:#f5f3ff;color:#8b5cf6;}
.i4{background:#fff7ed;color:#f59e0b;}



/* card section */

.card{
background:white;
border-radius:14px;
border:1px solid #e5e7eb;
padding:20px;
}


/* table */

.table th{
font-size:13px;
color:#6b7280;
font-weight:500;
}


.table tr{
border-bottom:1px solid #f1f5f9;
}

</style>




<!-- ======================
STATISTIK
====================== -->

<div class="grid md:grid-cols-4 gap-5 mb-6">


<div class="stat">

<div>

<p class="stat-title">
Total Students
</p>

<p class="stat-number">
{{ $totalStudents }}
</p>

</div>


<div class="icon i1">
👨‍🎓
</div>


</div>





<div class="stat">

<div>

<p class="stat-title">
Total Courses
</p>

<p class="stat-number">
{{ $totalCourses }}
</p>

</div>


<div class="icon i2">
📚
</div>


</div>






<div class="stat">

<div>

<p class="stat-title">
Upcoming Classes
</p>

<p class="stat-number">
{{ $upcomingClasses }}
</p>

</div>


<div class="icon i3">
📅
</div>


</div>






<div class="stat">

<div>

<p class="stat-title">
Pending Booking
</p>

<p class="stat-number">
{{ $pendingBookings }}
</p>

</div>


<div class="icon i4">
📝
</div>


</div>



</div>






<!-- ======================
CHART
====================== -->

<div class="grid lg:grid-cols-2 gap-6 mb-6">


<div class="card">

<p class="text-sm text-gray-500 mb-3">
Statistik Aktivitas
</p>


<div style="height:280px">

<canvas id="chartLine"></canvas>

</div>


</div>





<div class="card">

<p class="text-sm text-gray-500 mb-3">
Ringkasan Data
</p>


<div style="height:280px" class="flex items-center justify-center">

<canvas id="chartPie"></canvas>

</div>


</div>



</div>






<!-- ======================
BOOKING TERBARU
====================== -->

<div class="card">


<p class="text-sm text-gray-500 mb-3">
Booking Terbaru
</p>



<table class="table w-full">

<thead>

<tr>

<th class="p-2 text-left">
ID
</th>


<th class="p-2 text-left">
Status
</th>


</tr>


</thead>



<tbody>


@foreach($recentBookings as $booking)

<tr>

<td class="p-2">
{{ $booking->id }}
</td>



<td class="p-2">

<span class="px-2 py-1 rounded bg-gray-100 text-xs">

{{ $booking->status }}

</span>


</td>


</tr>


@endforeach


</tbody>


</table>



</div>








<script>


new Chart(document.getElementById('chartLine'),{

type:'line',

data:{


labels:[
'Jan','Feb','Mar','Apr','May','Jun'
],


datasets:[


{
label:'Students',

data:[10,20,15,30,22,35],

tension:0.4

},



{
label:'Courses',

data:[5,12,8,15,18,20],

tension:0.4

}



]


},


options:{


maintainAspectRatio:false,


plugins:{


legend:{


position:'bottom'


}


}


}


})






new Chart(document.getElementById('chartPie'),{


type:'doughnut',


data:{


labels:[
'Students',
'Courses',
'Bookings'
],


datasets:[{


data:[

{{ $totalStudents }},
{{ $totalCourses }},
{{ $pendingBookings }}

]


}]


},


options:{


maintainAspectRatio:false,


plugins:{


legend:{


position:'bottom'


}


}


}


})


</script>



@endsection