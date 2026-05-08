@extends('layouts.super_admin')

@section('title','Verifikasi LPK')

@section('content')

<style>

.wrapper{
max-width:100%;
}

.card{
background:white;
border-radius:20px;
border:1px solid #e5e7eb;
overflow:hidden;
box-shadow:0 10px 25px rgba(0,0,0,.03);
}

.card-header{
padding:24px;
border-bottom:1px solid #f1f5f9;
display:flex;
justify-content:space-between;
align-items:center;
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

.logo{
width:52px;
height:52px;
border-radius:14px;
object-fit:cover;
border:1px solid #e5e7eb;
background:#eef2ff;
display:flex;
align-items:center;
justify-content:center;
font-size:18px;
font-weight:700;
color:#4338ca;
flex-shrink:0;
}

.lpk-name{
font-weight:700;
font-size:14px;
color:#111827;
}

.detail{
font-size:12px;
color:#6b7280;
margin-top:3px;
}

.badge{
padding:6px 12px;
border-radius:999px;
font-size:12px;
font-weight:600;
display:inline-flex;
align-items:center;
gap:6px;
}

.pending{
background:#fef3c7;
color:#92400e;
}

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

.btn-detail{
background:#eef2ff;
color:#4338ca;
}

.btn-detail:hover{
background:#e0e7ff;
}

.btn-approve{
background:#22c55e;
color:white;
}

.btn-approve:hover{
background:#16a34a;
}

.btn-reject{
background:#ef4444;
color:white;
}

.btn-reject:hover{
background:#dc2626;
}

.empty{
padding:50px 20px;
text-align:center;
color:#9ca3af;
font-size:14px;
}

.action-group{
display:flex;
gap:8px;
flex-wrap:wrap;
align-items:center;
}

.alert-success{
background:#dcfce7;
color:#166534;
padding:14px 18px;
border-radius:12px;
margin-bottom:20px;
font-size:14px;
font-weight:600;
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

        <div>

            <div class="card-title">
                Verifikasi Mitra LPK
            </div>

            <div class="card-subtitle">
                Kelola dan verifikasi LPK terpercaya di Skilloka
            </div>

        </div>

    </div>



    <!-- TABLE -->
    <table class="table w-full">

        <thead>

            <tr>

                <th class="text-left">
                    LPK
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

                        <div class="flex gap-4">

                            <!-- AVATAR -->
                            <div class="logo">

                                {{ strtoupper(substr($lpk->name ?? 'L', 0, 1)) }}

                            </div>



                            <div>

                                <div class="lpk-name">

                                    {{ $lpk->name ?? '-' }}

                                </div>

                                <div class="detail">

                                    {{ $tenant->city ?? 'Indramayu' }}

                                </div>

                                <div class="detail">

                                    {{ $lpk->address ?? '-' }}

                                </div>

                            </div>

                        </div>

                    </td>



                    <!-- ADMIN -->
                    <td>

                        <div class="font-medium text-gray-800">

                            {{ $user->name ?? '-' }}

                        </div>

                        <div class="detail">

                            {{ $user->email ?? '-' }}

                        </div>

                    </td>



                    <!-- KONTAK -->
                    <td>

                        <div class="font-medium text-gray-800">

                            {{ $tenant->phone ?? '-' }}

                        </div>

                        <div class="detail">

                            {{ $tenant->email ?? '-' }}

                        </div>

                    </td>



                    <!-- STATUS -->
                    <td>

                        <span class="badge pending">

                            ⏳ Pending

                        </span>

                    </td>



                    <!-- AKSI -->
                    <td>

                        <div class="action-group">

                            <!-- DETAIL -->
                            <a
                                href="{{ route('super.verifications.show', $tenant->id) }}"
                                class="btn btn-detail">

                                Detail

                            </a>



                            <!-- APPROVE -->
                            <form
                                method="POST"
                                action="{{ route('super.verifications.approve', $tenant->id) }}">

                                @csrf

                                <button
                                    type="submit"
                                    class="btn btn-approve">

                                    Approve

                                </button>

                            </form>



                            <!-- REJECT -->
                            <form
                                method="POST"
                                action="{{ route('super.verifications.reject', $tenant->id) }}">

                                @csrf

                                <button
                                    type="submit"
                                    class="btn btn-reject">

                                    Reject

                                </button>

                            </form>

                        </div>

                    </td>

                </tr>

            @empty

                <tr>

                    <td colspan="5">

                        <div class="empty">

                            Belum ada pengajuan verifikasi LPK

                        </div>

                    </td>

                </tr>

            @endforelse

        </tbody>

    </table>

</div>

</div>

@endsection