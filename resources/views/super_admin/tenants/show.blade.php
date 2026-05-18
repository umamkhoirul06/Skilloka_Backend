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

            <!-- STATISTIK PENDAFTAR -->
            <div class="section" style="margin-top:40px;">
                <div class="section-title" style="display:flex; align-items:center; gap:8px;">
                    <svg style="width:20px; height:20px; color:#4f46e5;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    Statistik Pendaftar per Sesi (Room)
                </div>
                
                @if($lpk && $lpk->courses && count($lpk->courses) > 0)
                    <div style="display:flex; flex-direction:column; gap:16px; margin-top:16px;">
                        @foreach($lpk->courses as $course)
                            @php
                                $max = $course->max_participants > 0 ? $course->max_participants : 1;
                                $count = $course->bookings_count ?? 0;
                                $percentage = min(100, round(($count / $max) * 100));
                                
                                $statusColor = '#10b981'; // Emerald (Aman)
                                $statusText = 'Tersedia';
                                $statusBg = '#dcfce7';
                                
                                if($percentage >= 100) {
                                    $statusColor = '#ef4444'; // Red (Penuh)
                                    $statusText = 'Penuh';
                                    $statusBg = '#fee2e2';
                                } elseif($percentage >= 80) {
                                    $statusColor = '#f59e0b'; // Amber (Hampir Penuh)
                                    $statusText = 'Hampir Penuh';
                                    $statusBg = '#fef3c7';
                                }
                            @endphp
                            
                            <div style="background:rgba(249, 250, 251, 0.8); backdrop-filter:blur(10px); border:1px solid #eef2f7; border-radius:14px; padding:18px; transition:all 0.2s;">
                                <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:12px;">
                                    <div>
                                        <div style="font-weight:600; color:#1f2937; font-size:15px; margin-bottom:4px;">{{ $course->title }}</div>
                                        <div style="font-size:13px; color:#6b7280;">
                                            <span style="display:inline-block; margin-right:12px;">👤 Pendaftar: <strong>{{ $count }}</strong> / {{ $course->max_participants ?? '∞' }}</span>
                                            <span>💳 Harga: Rp {{ number_format($course->price, 0, ',', '.') }}</span>
                                        </div>
                                    </div>
                                    <span style="background:{{ $statusBg }}; color:{{ $statusColor }}; padding:4px 10px; border-radius:999px; font-size:11px; font-weight:700; white-space:nowrap;">
                                        {{ $statusText }}
                                    </span>
                                </div>
                                
                                <!-- Progress Bar -->
                                <div style="width:100%; background:#e5e7eb; border-radius:999px; height:8px; overflow:hidden;">
                                    <div style="width:{{ $percentage }}%; background:{{ $statusColor }}; height:100%; border-radius:999px; transition:width 0.5s ease-in-out;"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div style="background:#f9fafb; border:1px dashed #d1d5db; border-radius:12px; padding:24px; text-align:center; margin-top:16px;">
                        <span style="color:#6b7280; font-size:14px;">Belum ada sesi/course yang dibuat oleh LPK ini.</span>
                    </div>
                @endif
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