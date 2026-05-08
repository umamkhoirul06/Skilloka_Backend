@extends('layouts.super_admin')

@section('title', 'Detail Verifikasi')

@section('content')

<style>

.page{
display:grid;
grid-template-columns:2fr 1fr;
gap:24px;
}

.card{
background:white;
border-radius:18px;
border:1px solid #e5e7eb;
overflow:hidden;
box-shadow:0 4px 18px rgba(15,23,42,.04);
}



/* HEADER */
.banner{
height:170px;
background:#f8fafc;
border-bottom:1px solid #eef2f7;
position:relative;
}



/* AVATAR */
.logo{
width:100px;
height:100px;
border-radius:22px;
background:#111827;

display:flex;
align-items:center;
justify-content:center;

font-size:36px;
font-weight:700;
color:white;

position:absolute;
left:32px;
bottom:-50px;

border:5px solid white;

box-shadow:0 8px 20px rgba(0,0,0,.08);
}



/* CONTENT */
.content{
padding:75px 32px 32px;
}



/* TITLE */
.title{
font-size:28px;
font-weight:700;
color:#111827;
letter-spacing:-.3px;
}



/* BADGE */
.badge{
display:inline-flex;
align-items:center;
padding:6px 14px;
border-radius:999px;
font-size:12px;
font-weight:600;
margin-top:12px;
}

.pending{
background:#fef3c7;
color:#92400e;
}

.approved{
background:#dcfce7;
color:#166534;
}

.rejected{
background:#fee2e2;
color:#991b1b;
}



/* GRID */
.grid{
display:grid;
grid-template-columns:1fr 1fr;
gap:26px;
margin-top:34px;
}



/* LABEL */
.label{
font-size:12px;
font-weight:500;
color:#6b7280;
margin-bottom:6px;
}



/* VALUE */
.value{
font-size:15px;
font-weight:600;
color:#111827;
line-height:1.5;
}



/* SECTION */
.section{
margin-top:34px;
}

.section-title{
font-size:17px;
font-weight:700;
color:#111827;
margin-bottom:12px;
}

.desc{
font-size:14px;
line-height:1.9;
color:#4b5563;
}



/* SIDEBAR */
.sidebar{
padding:24px;
}



/* ADMIN CARD */
.admin-card{
background:#f9fafb;
border:1px solid #eef2f7;
border-radius:16px;
padding:22px;
}



/* BUTTON */
.btn{
display:flex;
align-items:center;
justify-content:center;

width:100%;

padding:13px;
border:none;
border-radius:12px;

font-size:14px;
font-weight:600;

cursor:pointer;
transition:.2s;

text-decoration:none;
}

.btn:hover{
transform:translateY(-1px);
}



/* APPROVE */
.btn-approve{
background:#16a34a;
color:white;
}

.btn-approve:hover{
background:#15803d;
}



/* REJECT */
.btn-reject{
background:#dc2626;
color:white;
margin-top:12px;
}

.btn-reject:hover{
background:#b91c1c;
}



/* WA */
.btn-wa{
background:#111827;
color:white;
margin-top:12px;
}

.btn-wa:hover{
background:#1f2937;
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



/* MOBILE */
@media(max-width:1024px){

.page{
grid-template-columns:1fr;
}

}

</style>



@php

$lpk = $tenant->lpk;

$user = $tenant->users->first();

@endphp



@if(session('success'))

<div class="alert-success">

    {{ session('success') }}

</div>

@endif



<div class="page">

    <!-- LEFT -->
    <div class="card">

        <!-- HEADER -->
        <div class="banner">

            <!-- AVATAR -->
            <div class="logo">

                {{ strtoupper(substr($lpk->name ?? 'L', 0, 1)) }}

            </div>

        </div>



        <!-- CONTENT -->
        <div class="content">

            <!-- TITLE -->
            <div class="title">

                {{ $lpk->name ?? '-' }}

            </div>



            <!-- STATUS -->
            <div class="badge

                @if(($lpk->status_verifikasi ?? 'pending') == 'approved')

                    approved

                @elseif(($lpk->status_verifikasi ?? 'pending') == 'rejected')

                    rejected

                @else

                    pending

                @endif

            ">

                {{ ucfirst($lpk->status_verifikasi ?? 'pending') }}

            </div>



            <!-- GRID -->
            <div class="grid">

                <div>

                    <div class="label">
                        NIB
                    </div>

                    <div class="value">
                        {{ $lpk->nib ?? '-' }}
                    </div>

                </div>



                <div>

                    <div class="label">
                        Kota
                    </div>

                    <div class="value">
                        {{ $tenant->city ?? 'Indramayu' }}
                    </div>

                </div>



                <div>

                    <div class="label">
                        Nomor Telepon
                    </div>

                    <div class="value">
                        {{ $tenant->phone ?? '-' }}
                    </div>

                </div>



                <div>

                    <div class="label">
                        Email LPK
                    </div>

                    <div class="value">
                        {{ $tenant->email ?? '-' }}
                    </div>

                </div>

            </div>



            <!-- DESKRIPSI -->
            <div class="section">

                <div class="section-title">
                    Deskripsi LPK
                </div>

                <div class="desc">
                    {{ $lpk->description ?? 'Belum ada deskripsi.' }}
                </div>

            </div>



            <!-- ALAMAT -->
            <div class="section">

                <div class="section-title">
                    Alamat Lengkap
                </div>

                <div class="desc">
                    {{ $lpk->address ?? '-' }}
                </div>

            </div>



            <!-- FASILITAS -->
            <div class="section">

                <div class="section-title">
                    Fasilitas LPK
                </div>

                <div class="desc">
                    {{ $lpk->facilities ?? 'Belum ada fasilitas.' }}
                </div>

            </div>

        </div>

    </div>



    <!-- RIGHT -->
    <div class="card sidebar">

        <div class="section-title">
            Informasi Admin
        </div>



        <div class="admin-card">

            <div class="label">
                Nama Admin
            </div>

            <div class="value">
                {{ $user->name ?? '-' }}
            </div>



            <div style="margin-top:20px">

                <div class="label">
                    Email
                </div>

                <div class="value">
                    {{ $user->email ?? '-' }}
                </div>

            </div>



            <div style="margin-top:20px">

                <div class="label">
                    WhatsApp
                </div>

                <div class="value">
                    {{ $user->phone ?? '-' }}
                </div>

            </div>

        </div>



        <!-- ACTION -->
        <div style="margin-top:24px">

            <form
                action="{{ route('super.verifications.approve', $tenant->id) }}"
                method="POST">

                @csrf

                <button
                    type="submit"
                    class="btn btn-approve">

                    Approve LPK

                </button>

            </form>



            <form
                action="{{ route('super.verifications.reject', $tenant->id) }}"
                method="POST">

                @csrf

                <button
                    type="submit"
                    class="btn btn-reject">

                    Reject LPK

                </button>

            </form>



            @if($user && $user->phone)

            <a
                href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $user->phone) }}"
                target="_blank"
                class="btn btn-wa">

                Hubungi WhatsApp

            </a>

            @endif

        </div>

    </div>

</div>

@endsection