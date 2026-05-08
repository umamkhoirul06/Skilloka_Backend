@extends('layouts.super_admin')

@section('title','Settings')

@section('content')

<style>

.card{
background:white;
border-radius:12px;
border:1px solid #e5e7eb;
padding:22px;
margin-bottom:20px;
}

.card-title{
font-size:15px;
font-weight:600;
margin-bottom:14px;
color:#111827;
}

.input{
width:100%;
padding:10px 12px;
border-radius:8px;
border:1px solid #e5e7eb;
font-size:14px;
}

.input:focus{
outline:none;
border-color:#6366f1;
box-shadow:0 0 0 2px rgba(99,102,241,.1);
}

.label{
font-size:13px;
color:#6b7280;
margin-bottom:6px;
display:block;
}

.btn-save{
background:linear-gradient(135deg,#667eea,#764ba2);
color:white;
padding:10px 18px;
border-radius:8px;
font-size:14px;
font-weight:500;
transition:.2s;
}

.btn-save:hover{
opacity:.9;
}

.grid-2{
display:grid;
grid-template-columns:1fr 1fr;
gap:16px;
}

.alert-success{
background:#dcfce7;
color:#166534;
padding:12px;
border-radius:8px;
margin-bottom:20px;
font-size:14px;
}

</style>



@if(session('success'))

<div class="alert-success">

{{ session('success') }}

</div>

@endif



<form method="POST"
action="{{ route('super.settings.update') }}">

@csrf



<!-- platform setting -->
<div class="card">

<div class="card-title">
Pengaturan Platform
</div>



<div class="grid-2">

<div>

<label class="label">
Nama Platform
</label>

<input
type="text"
name="platform_name"
class="input"
value="{{ $setting->platform_name }}">

</div>



<div>

<label class="label">
Email Support
</label>

<input
type="text"
name="support_email"
class="input"
value="{{ $setting->support_email }}">

</div>



<div>

<label class="label">
Timezone
</label>

<select
name="timezone"
class="input">

<option value="Asia/Jakarta"
{{ $setting->timezone == 'Asia/Jakarta' ? 'selected' : '' }}>
Asia/Jakarta
</option>

</select>

</div>



<div>

<label class="label">
Bahasa
</label>

<select
name="language"
class="input">

<option value="Indonesia"
{{ $setting->language == 'Indonesia' ? 'selected' : '' }}>
Indonesia
</option>

<option value="English"
{{ $setting->language == 'English' ? 'selected' : '' }}>
English
</option>

</select>

</div>



</div>

</div>





<!-- pembayaran -->
<div class="card">

<div class="card-title">
Pengaturan Pembayaran
</div>



<div class="grid-2">

<div>

<label class="label">
Fee Platform (%)
</label>

<input
type="number"
name="platform_fee"
class="input"
value="{{ $setting->platform_fee }}">

</div>



<div>

<label class="label">
Metode Pembayaran
</label>

<select
name="payment_method"
class="input">

<option value="Transfer Bank"
{{ $setting->payment_method == 'Transfer Bank' ? 'selected' : '' }}>
Transfer Bank
</option>

<option value="E-Wallet"
{{ $setting->payment_method == 'E-Wallet' ? 'selected' : '' }}>
E-Wallet
</option>

</select>

</div>

</div>

</div>




<button class="btn-save">

Simpan Pengaturan

</button>



</form>



@endsection