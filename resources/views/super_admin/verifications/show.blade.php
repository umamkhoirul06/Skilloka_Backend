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
$latestVerification = $lpk ? $lpk->verifications->last() : null;
@endphp

@if(session('success'))
<div class="alert-success">
    {{ session('success') }}
</div>
@endif

@if($errors->any())
<div style="background:#fee2e2; color:#991b1b; padding:14px 16px; border-radius:12px; margin-bottom:20px; font-size:14px; font-weight:600; border:1px solid #fecaca;">
    {{ $errors->first() }}
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

            @if(($lpk->status_verifikasi ?? '') == 'rejected' && $latestVerification && $latestVerification->notes)
            <div style="margin-top:20px; padding:16px; background:#fff1f2; border:1px solid #fecdd3; border-radius:12px;">
                <div style="font-size:12px; font-weight:700; color:#9f1239; text-transform:uppercase; letter-spacing:0.5px; margin-bottom:4px;">
                    <i class="fa-solid fa-triangle-exclamation mr-1"></i> Alasan Penolakan:
                </div>
                <div style="font-size:14px; color:#881337; line-height:1.5;">
                    {{ $latestVerification->notes }}
                </div>
            </div>
            @endif

            <!-- GRID -->
            <div class="grid">
                <div>
                    <div class="label">NIB</div>
                    <div class="value">{{ $lpk->nib ?? '-' }}</div>
                </div>

                <div>
                    <div class="label">Kota / Wilayah</div>
                    <div class="value">{{ $tenant->city ?? 'Indramayu' }}</div>
                </div>

                <div>
                    <div class="label">Nomor Telepon</div>
                    <div class="value">{{ $tenant->phone ?? '-' }}</div>
                </div>

                <div>
                    <div class="label">Email LPK</div>
                    <div class="value">{{ $tenant->email ?? '-' }}</div>
                </div>
            </div>

            <!-- DESKRIPSI -->
            <div class="section">
                <div class="section-title">Deskripsi LPK</div>
                <div class="desc">{{ $lpk->description ?? 'Belum ada deskripsi.' }}</div>
            </div>

            <!-- ALAMAT -->
            <div class="section">
                <div class="section-title">Alamat Lengkap</div>
                <div class="desc">{{ $lpk->address ?? '-' }}</div>
            </div>

            <!-- FASILITAS -->
            <div class="section">
                <div class="section-title">Fasilitas LPK</div>
                <div class="desc">{{ $lpk->facilities ?? 'Belum ada fasilitas.' }}</div>
            </div>
        </div>
    </div>

    <!-- RIGHT -->
    <div class="card sidebar">
        <div class="section-title">Informasi Akun Admin</div>

        <div class="admin-card">
            <div class="label">Nama Admin</div>
            <div class="value">{{ $user->name ?? '-' }}</div>

            <div style="margin-top:20px">
                <div class="label">Email</div>
                <div class="value">{{ $user->email ?? '-' }}</div>
            </div>

            <div style="margin-top:20px">
                <div class="label">WhatsApp</div>
                <div class="value">{{ $user->phone ?? '-' }}</div>
            </div>
        </div>

        <!-- ACTION -->
        <div style="margin-top:24px">
            @if(($lpk->status_verifikasi ?? 'pending') == 'pending')
                <form action="{{ route('super.verifications.approve', $tenant->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin MENYETUJUI dan mengaktifkan akun LPK ini?')">
                    @csrf
                    <button type="submit" class="btn btn-approve">
                        Setujui (Approve) LPK
                    </button>
                </form>

                <button type="button" onclick="openRejectModal()" class="btn btn-reject">
                    Tolak (Reject) LPK
                </button>
            @elseif(($lpk->status_verifikasi ?? '') == 'approved')
                <div style="padding:14px; background:#f0fdf4; border:1px solid #bbf7d0; border-radius:12px; color:#166534; font-size:13px; font-weight:600; text-align:center; margin-bottom:12px;">
                    <i class="fa-solid fa-circle-check"></i> LPK ini telah disetujui & aktif.
                </div>
            @elseif(($lpk->status_verifikasi ?? '') == 'rejected')
                <div style="padding:14px; background:#fff1f2; border:1px solid #fecdd3; border-radius:12px; color:#9f1239; font-size:13px; font-weight:600; text-align:center; margin-bottom:12px;">
                    <i class="fa-solid fa-circle-xmark"></i> Pengajuan LPK ini telah ditolak.
                </div>
            @endif

            @if($user && $user->phone)
            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $user->phone) }}" target="_blank" class="btn btn-wa">
                Hubungi via WhatsApp
            </a>
            @endif

            <a href="{{ route('super.verifications') }}" class="btn" style="background:#f3f4f6; color:#4b5563; margin-top:10px;">
                Kembali ke Antrean
            </a>
        </div>
    </div>
</div>

<!-- MODAL REJECT -->
<div id="rejectModal" style="display:none; position:fixed; inset:0; background:rgba(15,23,42,0.6); z-index:9999; align-items:center; justify-content:center; padding:16px;">
    <div style="background:white; border-radius:20px; max-width:520px; width:100%; padding:28px; box-shadow:0 20px 40px rgba(0,0,0,0.2);">
        <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:16px;">
            <div>
                <h3 style="font-size:18px; font-weight:800; color:#111827; margin:0 0 4px;">Tolak Verifikasi LPK</h3>
                <p style="font-size:13px; color:#6b7280; margin:0;">Berikan alasan penolakan yang jelas agar mitra dapat memperbaikinya.</p>
            </div>
            <button type="button" onclick="closeRejectModal()" style="border:none; background:none; font-size:20px; color:#9ca3af; cursor:pointer;">&times;</button>
        </div>

        <form action="{{ route('super.verifications.reject', $tenant->id) }}" method="POST">
            @csrf
            
            <div style="margin-bottom:14px;">
                <label style="font-size:12px; font-weight:700; color:#374151; display:block; margin-bottom:6px;">Pilih Alasan Umum (Klik untuk mengisi):</label>
                <div style="display:flex; flex-direction:column; gap:6px;">
                    <button type="button" onclick="setReason('Nomor Induk Berusaha (NIB) tidak valid atau tidak terdaftar di OSS untuk KBLI pelatihan kerja.')" style="text-align:left; background:#f9fafb; border:1px solid #e5e7eb; border-radius:8px; padding:8px 12px; font-size:12px; color:#4b5563; cursor:pointer;">
                        • NIB tidak valid / tidak sesuai OSS
                    </button>
                    <button type="button" onclick="setReason('Alamat operasional LPK berada di luar wilayah Kabupaten Indramayu.')" style="text-align:left; background:#f9fafb; border:1px solid #e5e7eb; border-radius:8px; padding:8px 12px; font-size:12px; color:#4b5563; cursor:pointer;">
                        • Lokasi bukan di wilayah operasional
                    </button>
                    <button type="button" onclick="setReason('Nomor kontak penanggung jawab tidak dapat dihubungi saat proses konfirmasi.')" style="text-align:left; background:#f9fafb; border:1px solid #e5e7eb; border-radius:8px; padding:8px 12px; font-size:12px; color:#4b5563; cursor:pointer;">
                        • Kontak penanggung jawab tidak aktif
                    </button>
                </div>
            </div>

            <div style="margin-bottom:20px;">
                <label for="reasonInput" style="font-size:12px; font-weight:700; color:#374151; display:block; margin-bottom:6px;">Detail Alasan Penolakan <span style="color:#ef4444;">*</span></label>
                <textarea id="reasonInput" name="reason" rows="4" required style="width:100%; border:1px solid #d1d5db; border-radius:10px; padding:10px 12px; font-size:13px; outline:none; resize:vertical; font-family:inherit;" placeholder="Tuliskan catatan detail penolakan di sini..."></textarea>
            </div>

            <div style="display:flex; gap:10px; justify-content:flex-end;">
                <button type="button" onclick="closeRejectModal()" style="padding:10px 18px; border-radius:10px; border:1px solid #d1d5db; background:white; color:#374151; font-weight:600; font-size:13px; cursor:pointer;">
                    Batal
                </button>
                <button type="submit" style="padding:10px 20px; border-radius:10px; border:none; background:#dc2626; color:white; font-weight:700; font-size:13px; cursor:pointer;">
                    Kirim Penolakan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openRejectModal() {
    document.getElementById('rejectModal').style.display = 'flex';
}
function closeRejectModal() {
    document.getElementById('rejectModal').style.display = 'none';
}
function setReason(text) {
    document.getElementById('reasonInput').value = text;
}
</script>

@endsection