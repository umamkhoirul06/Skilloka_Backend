@extends('layouts.super_admin')

@section('title','Detail LPK')

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

.banner{
height:170px;
background:#f8fafc;
border-bottom:1px solid #eef2f7;
position:relative;
}

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
}

.content{
padding:75px 32px 32px;
}

.title{
font-size:28px;
font-weight:700;
color:#111827;
}

.badge{
display:inline-flex;
align-items:center;
padding:6px 14px;
border-radius:999px;
font-size:12px;
font-weight:600;
margin-top:12px;
}

.active{
background:#dcfce7;
color:#166534;
}

.grid{
display:grid;
grid-template-columns:1fr 1fr;
gap:26px;
margin-top:34px;
}

.label{
font-size:12px;
font-weight:500;
color:#6b7280;
margin-bottom:6px;
}

.value{
font-size:15px;
font-weight:600;
color:#111827;
line-height:1.5;
}

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

.sidebar{
padding:24px;
}

.admin-card{
background:#f9fafb;
border:1px solid #eef2f7;
border-radius:16px;
padding:22px;
}

</style>

@php

$lpk = $tenant->lpk;
$user = $tenant->users->first();

@endphp

<div class="page">

    <!-- LEFT -->
    <div class="card">

        <div class="banner">

            <div class="logo">

                {{ strtoupper(substr($lpk->name ?? 'L',0,1)) }}

            </div>

        </div>



        <div class="content">

            <div class="title">

                {{ $lpk->name ?? '-' }}

            </div>



            <span class="badge active">

                Active

            </span>



            <div class="grid">

                <div>
                    <div class="label">NIB</div>
                    <div class="value">
                        {{ $lpk->nib ?? '-' }}
                    </div>
                </div>

                <div>
                    <div class="label">Kota</div>
                    <div class="value">
                        {{ $tenant->city ?? '-' }}
                    </div>
                </div>

                <div>
                    <div class="label">Telepon</div>
                    <div class="value">
                        {{ $tenant->phone ?? '-' }}
                    </div>
                </div>

                <div>
                    <div class="label">Email</div>
                    <div class="value">
                        {{ $tenant->email ?? '-' }}
                    </div>
                </div>

            </div>



            <div class="section">

                <div class="section-title">
                    Deskripsi
                </div>

                <div class="desc">
                    {{ $lpk->description ?? '-' }}
                </div>

            </div>



            <div class="section">

                <div class="section-title">
                    Alamat
                </div>

                <div class="desc">
                    {{ $lpk->address ?? '-' }}
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

    </div>

</div>

@endsection