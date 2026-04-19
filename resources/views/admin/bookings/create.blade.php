@extends('layouts.admin')

@section('title','Create Booking')

@section('content')

<div class="max-w-2xl">

<div class="bg-white rounded-xl shadow-sm overflow-hidden">

<div class="px-6 py-4 border-b">

<h3 class="font-semibold text-gray-800">
Booking Information
</h3>

<p class="text-sm text-gray-500">
Register student ke jadwal course
</p>

</div>


<form action="{{ route('admin.bookings.store') }}" method="POST" class="p-6 space-y-6">

@csrf


{{-- STUDENT --}}
<div>

<label class="block text-sm font-medium mb-2">

Select Student

</label>

<select name="user_id" required class="w-full border rounded-lg px-3 py-2">

<option value="">-- pilih student --</option>

@foreach($students as $student)

<option value="{{ $student->id }}">

{{ $student->name }}

</option>

@endforeach

</select>


@if($students->isEmpty())

<p class="text-sm text-red-500 mt-2">

belum ada student

</p>

@endif

</div>



{{-- SCHEDULE --}}
<div>

<label class="block text-sm font-medium mb-2">

Select Schedule

</label>

<select name="schedule_id" required class="w-full border rounded-lg px-3 py-2">

<option value="">-- pilih jadwal --</option>

@foreach($schedules as $schedule)

<option value="{{ $schedule->id }}">

{{ $schedule->course->title }}

|

{{ $schedule->start_date->format('d M Y') }}

-

{{ $schedule->end_date->format('d M Y') }}

|

{{ substr($schedule->daily_start,0,5) }}

-

{{ substr($schedule->daily_end,0,5) }}

|

Rp {{ number_format($schedule->course->price,0,',','.') }}

</option>

@endforeach

</select>


@if($schedules->isEmpty())

<p class="text-sm text-red-500 mt-2">

belum ada schedule

</p>

@endif

</div>



{{-- STATUS --}}
<div>

<label class="block text-sm font-medium mb-2">

Status

</label>

<select name="status" class="w-full border rounded-lg px-3 py-2">

<option value="pending">
Pending
</option>

<option value="paid">
Paid
</option>

</select>

</div>



<div class="flex justify-end gap-3 pt-4">

<a href="{{ route('admin.bookings.index') }}"
class="px-4 py-2 border rounded-lg">

Cancel

</a>

<button class="px-5 py-2 bg-purple-600 text-white rounded-lg">

Create Booking

</button>

</div>


</form>

</div>

</div>

@endsection