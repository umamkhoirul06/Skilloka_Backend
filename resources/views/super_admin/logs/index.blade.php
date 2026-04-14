@extends('layouts.admin')

@section('title','System Logs')

@section('content')

<style>

.card{
background:white;
border-radius:12px;
border:1px solid #e5e7eb;
padding:20px;
}

.log-box{
background:#0f172a;
color:#e2e8f0;
padding:16px;
border-radius:10px;
font-size:13px;
font-family:monospace;
height:420px;
overflow-y:auto;
line-height:1.6;
}

.log-line{
border-bottom:1px solid rgba(255,255,255,0.05);
padding:4px 0;
}

.empty{
text-align:center;
padding:40px;
color:#94a3b8;
}

</style>



<div class="card">

<h2 class="text-lg font-semibold mb-4">

System Logs

</h2>



@if(isset($logs) && count($logs))

<div class="log-box">

@foreach($logs as $log)

<div class="log-line">

{{ $log }}

</div>

@endforeach

</div>

@else

<div class="empty">

Belum ada log sistem

</div>

@endif


</div>



@endsection