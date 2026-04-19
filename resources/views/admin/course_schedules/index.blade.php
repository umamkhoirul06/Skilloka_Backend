@extends('layouts.admin')

@section('title','Course Schedule')

@section('content')

<div class="bg-white p-6 rounded shadow">

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px">

<h2 style="font-size:18px;font-weight:600">
Course Schedule
</h2>

<a href="{{ route('admin.course-schedules.create') }}"
style="background:#7c3aed;color:white;padding:8px 14px;border-radius:6px;text-decoration:none;font-size:14px">
+ Tambah Schedule
</a>

</div>



<table style="width:100%;border-collapse:collapse">

<thead style="background:#f3f4f6">

<tr>

<th style="padding:10px;border:1px solid #e5e7eb">Course</th>

<th style="padding:10px;border:1px solid #e5e7eb">Tanggal</th>

<th style="padding:10px;border:1px solid #e5e7eb">Hari</th>

<th style="padding:10px;border:1px solid #e5e7eb">Jam</th>

<th style="padding:10px;border:1px solid #e5e7eb">Kuota</th>

</tr>

</thead>



<tbody>

@forelse($schedules as $schedule)

<tr>

<td style="padding:10px;border:1px solid #e5e7eb">

{{ $schedule->course->title ?? '-' }}

</td>



<td style="padding:10px;border:1px solid #e5e7eb">

{{ \Carbon\Carbon::parse($schedule->start_date)->format('d M Y') }}

-

{{ \Carbon\Carbon::parse($schedule->end_date)->format('d M Y') }}

</td>



<td style="padding:10px;border:1px solid #e5e7eb">

{{ $schedule->days_of_week }}

</td>



<td style="padding:10px;border:1px solid #e5e7eb">

{{ substr($schedule->daily_start,0,5) }}

-

{{ substr($schedule->daily_end,0,5) }}

</td>



<td style="padding:10px;border:1px solid #e5e7eb">

{{ $schedule->max_capacity }}

orang

</td>

</tr>

@empty

<tr>

<td colspan="5"
style="padding:20px;text-align:center;color:#6b7280">

Belum ada jadwal

</td>

</tr>

@endforelse

</tbody>

</table>

</div>

@endsection