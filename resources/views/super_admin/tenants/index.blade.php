@extends('layouts.super_admin')

@section('title','Data LPK')

@section('content')

<style>

.wrapper{
max-width:100%;
}

.card{
background:white;
border-radius:18px;
border:1px solid #e5e7eb;
overflow:hidden;
box-shadow:0 4px 18px rgba(15,23,42,.04);
}

.card-header{
padding:24px;
border-bottom:1px solid #f1f5f9;
}

.card-title{
font-size:20px;
font-weight:700;
color:#111827;
}

.card-subtitle{
font-size:14px;
color:#6b7280;
margin-top:4px;
}



/* TABLE */
.table th{
font-size:13px;
font-weight:600;
color:#6b7280;
background:#f9fafb;
padding:16px;
}

.table td{
padding:18px 16px;
font-size:14px;
vertical-align:top;
}

.table tr{
border-bottom:1px solid #f1f5f9;
transition:.2s;
}

.table tr:hover{
background:#fafafa;
}



/* LPK */
.lpk-box{
display:flex;
align-items:flex-start;
gap:14px;
}

.logo{
width:50px;
height:50px;
border-radius:14px;
background:#111827;

display:flex;
align-items:center;
justify-content:center;

font-size:18px;
font-weight:700;
color:white;

flex-shrink:0;
}

.lpk-name{
font-size:15px;
font-weight:700;
color:#111827;
}

.lpk-detail{
font-size:12px;
color:#6b7280;
margin-top:4px;
}



/* BADGE */
.badge{
padding:6px 12px;
border-radius:999px;
font-size:12px;
font-weight:600;
display:inline-flex;
align-items:center;
justify-content:center;
}

.active{
background:#dcfce7;
color:#166534;
}

.pending{
background:#fef3c7;
color:#92400e;
}

.rejected{
background:#fee2e2;
color:#991b1b;
}



/* BUTTON */
.btn{
padding:9px 14px;
border-radius:10px;
font-size:13px;
font-weight:600;
border:none;
cursor:pointer;
transition:.2s;
text-decoration:none;

display:inline-flex;
align-items:center;
justify-content:center;
}

.btn:hover{
transform:translateY(-1px);
}



/* DETAIL */
.btn-detail{
background:#eef2ff;
color:#4338ca;
}



/* DELETE */
.btn-delete{
background:#ef4444;
color:white;
}



/* ACTION */
.action-group{
display:flex;
align-items:center;
gap:8px;
flex-wrap:wrap;
}



/* EMPTY */
.empty{
padding:50px 20px;
text-align:center;
font-size:14px;
color:#9ca3af;
}



/* ALERT */
.alert-success{
background:#dcfce7;
color:#166534;
padding:14px 16px;
border-radius:12px;
margin-bottom:20px;
font-size:14px;
font-weight:600;
border:1px solid #bbf7d0;
}

</style>



@if(session('success'))

<div class="alert-success">

    {{ session('success') }}

</div>

@endif



<div class="wrapper">

<div class="card">

    <!-- HEADER -->
    <div class="card-header">

        <div class="card-title">
            Daftar LPK Aktif
        </div>

        <div class="card-subtitle">
            Kelola seluruh LPK aktif yang telah diverifikasi Skilloka
        </div>

    </div>


<div style="display:flex; align-items:center; gap:10px; margin-bottom:20px;">
    <select id="lpkPeriod" style="padding:8px 14px; border:1px solid #e2e8f0; border-radius:10px; font-size:13px;">
        <option value="month">Bulan Ini</option>
        <option value="3months">3 Bulan</option>
        <option value="6months">6 Bulan</option>
        <option value="year">Tahun Ini</option>
    </select>
    <a href="#" onclick="downloadLpkPdf()"
       style="background:linear-gradient(135deg,#4f46e5,#7c3aed); color:white; padding:10px 20px; border-radius:10px; font-size:13px; font-weight:600; text-decoration:none;">
        📥 Download Daftar LPK (PDF)
    </a>
</div>

<script>
function downloadLpkPdf() {
    const period = document.getElementById('lpkPeriod').value;
    window.open(`/super-admin/tenants/report/pdf?period=${period}`, '_blank');
}
</script>
    <!-- TABLE -->
    <table class="table w-full">

        <thead>

            <tr>

                <th class="text-left">
                    Nama LPK
                </th>

                <th class="text-left">
                    Admin
                </th>

                <th class="text-left">
                    Kontak
                </th>

                <th class="text-left">
                    Status
                </th>

                <th class="text-left">
                    Aksi
                </th>

            </tr>

        </thead>



        <tbody>

        @forelse($tenants as $tenant)

            @php

                $lpk = $tenant->lpk;

                $user = $tenant->users->first();

            @endphp



            <tr>

                <!-- LPK -->
                <td>

                    <div class="lpk-box">

                        <!-- AVATAR -->
                        <div class="logo">

                            {{ strtoupper(substr($lpk->name ?? 'L',0,1)) }}

                        </div>



                        <!-- INFO -->
                        <div>

                            <div class="lpk-name">

                                {{ $lpk->name ?? '-' }}

                            </div>

                            <div class="lpk-detail">

                                {{ $tenant->city ?? 'Indramayu' }}

                            </div>

                            <div class="lpk-detail">

                                {{ $lpk->address ?? '-' }}

                            </div>

                        </div>

                    </div>

                </td>



                <!-- ADMIN -->
                <td>

                    <div class="font-semibold text-gray-800">

                        {{ $user->name ?? '-' }}

                    </div>

                    <div class="lpk-detail">

                        {{ $user->email ?? '-' }}

                    </div>

                </td>



                <!-- KONTAK -->
                <td>

                    <div class="font-semibold text-gray-800">

                        {{ $tenant->phone ?? '-' }}

                    </div>

                    <div class="lpk-detail">

                        {{ $tenant->email ?? '-' }}

                    </div>

                </td>



                <!-- STATUS -->
                <td>

                    @if($lpk && $lpk->status == 'active')

                        <span class="badge active">

                            Active

                        </span>

                    @elseif($lpk && $lpk->status == 'rejected')

                        <span class="badge rejected">

                            Rejected

                        </span>

                    @else

                        <span class="badge pending">

                            Pending

                        </span>

                    @endif

                </td>



                <!-- AKSI -->
                <td>

                    <div class="action-group">

                        <!-- DETAIL -->
                        <a
                            href="{{ route('super.tenants.show', $tenant->id) }}"
                            class="btn btn-detail">

                            Detail

                        </a>



                        <!-- DELETE -->
                        <form
                            method="POST"
                            action="{{ route('super.tenants.delete', $tenant->id) }}">

                            @csrf
                            @method('DELETE')

                            <button
                                class="btn btn-delete"
                                onclick="return confirm('Yakin ingin menghapus LPK ini?')">

                                Hapus

                            </button>

                        </form>

                    </div>

                </td>

            </tr>

        @empty

            <tr>

                <td colspan="5">

                    <div class="empty">

                        Belum ada data LPK aktif

                    </div>

                </td>

            </tr>

        @endforelse

        </tbody>

    </table>

</div>

</div>

@endsection