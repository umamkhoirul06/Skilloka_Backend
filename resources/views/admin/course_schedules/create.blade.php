@extends('layouts.admin')

@section('title','Tambah Schedule')

@section('content')

<style>

.card{
background:white;
border-radius:12px;
border:1px solid #e5e7eb;
padding:20px;
max-width:650px;
}

.input{
width:100%;
border:1px solid #e5e7eb;
border-radius:8px;
padding:8px 12px;
margin-top:4px;
margin-bottom:14px;
}

.label{
font-size:13px;
color:#374151;
display:block;
margin-top:10px;
}

.btn{
background:#7c3aed;
color:white;
padding:10px 18px;
border-radius:8px;
font-size:14px;
border:none;
cursor:pointer;
}

</style>


<div class="card">

<h2 class="text-lg font-semibold mb-4">
Tambah Jadwal Course
</h2>


<form method="POST"
action="{{ route('admin.course-schedules.store') }}">

@csrf


<label class="label">
Course
</label>

<select
name="course_id"
class="input"
required>

<option value="">
-- pilih course --
</option>

@foreach($courses as $course)

<option value="{{ $course->id }}">
{{ $course->title }}
</option>

@endforeach

</select>



<label class="label">
Tanggal Mulai
</label>

<input
type="date"
name="start_date"
class="input"
required>



<label class="label">
Tanggal Selesai
</label>

<input
type="date"
name="end_date"
class="input"
required>



<label class="label">
Hari Belajar
</label>

<select
name="days_of_week"
class="input"
required>

<option value="">
-- pilih hari --
</option>

<option value="Senin">
Senin
</option>

<option value="Selasa">
Selasa
</option>

<option value="Rabu">
Rabu
</option>

<option value="Kamis">
Kamis
</option>

<option value="Jumat">
Jumat
</option>

<option value="Sabtu">
Sabtu
</option>

<option value="Minggu">
Minggu
</option>

<option value="Senin-Rabu-Jumat">
Senin - Rabu - Jumat
</option>

<option value="Senin-Jumat">
Senin - Jumat
</option>

</select>



<label class="label">
Jam Mulai
</label>

<input
type="time"
name="daily_start"
class="input"
required>



<label class="label">
Jam Selesai
</label>

<input
type="time"
name="daily_end"
class="input"
required>



<label class="label">
Kuota Peserta
</label>

<input
type="number"
name="max_capacity"
class="input"
placeholder="contoh: 20"
required>



<button class="btn">
Simpan Schedule
</button>


</form>

</div>

@endsection