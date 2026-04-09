@extends('layouts.super_admin')

@section('title','Logs')

@section('content')

<div class="bg-gray-800 p-5 rounded">

<h4 class="mb-4">

System Logs

</h4>

<div class="text-sm space-y-2 max-h-[500px] overflow-y-auto">

@forelse($logs as $log)

<div class="border-b border-gray-700 pb-2">

{{ $log }}

</div>

@empty

Tidak ada log

@endforelse

</div>

</div>

@endsection