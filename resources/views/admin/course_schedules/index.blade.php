@extends('layouts.admin')

@section('title','Course Schedule')

@section('content')

<div class="bg-white p-6 rounded shadow">

<h2 class="text-lg font-semibold mb-4">

Course Schedule

</h2>

<table class="w-full border">

<thead class="bg-gray-100">

<tr>

<th class="p-2 border">ID</th>
<th class="p-2 border">Course</th>
<th class="p-2 border">Date</th>
<th class="p-2 border">Time</th>

</tr>

</thead>

<tbody>

@forelse($courseSchedules ?? [] as $schedule)

<tr>

<td class="p-2 border">
{{ $schedule->id }}
</td>

<td class="p-2 border">
{{ $schedule->course->title ?? '-' }}
</td>

<td class="p-2 border">
{{ $schedule->date ?? '-' }}
</td>

<td class="p-2 border">
{{ $schedule->time ?? '-' }}
</td>

</tr>

@empty

<tr>

<td colspan="4" class="p-4 text-center">

Belum ada jadwal

</td>

</tr>

@endforelse

</tbody>

</table>

</div>

@endsection