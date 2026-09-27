@extends('layouts.super_admin')

@section('title', 'Manajemen Pengguna Platform')

@section('content')
<div class="space-y-6">

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="font-outfit text-2xl font-bold text-slate-900 tracking-tight">Manajemen Pengguna</h2>
            <p class="text-xs text-slate-500 mt-1">Daftar seluruh akun terdaftar di ekosistem Skilloka (Super Admin, Mitra LPK, dan Peserta Pelatihan).</p>
        </div>
        <div class="flex items-center gap-2">
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-emerald-50 border border-emerald-200 text-xs font-semibold text-emerald-800">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>Database Terintegrasi</span>
            </span>
        </div>
    </div>

    <!-- Summary Statistics Grid -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <!-- Total Users -->
        <a href="{{ route('super.users') }}" 
           class="p-4 rounded-2xl border transition-all {{ empty($roleFilter) ? 'bg-brand-50/60 border-brand-300 ring-2 ring-brand-500/20 shadow-xs' : 'bg-white border-slate-200 hover:border-slate-300' }}">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Akun</span>
                <i class="fa-solid fa-users text-slate-400 text-xs"></i>
            </div>
            <div class="font-outfit text-2xl font-extrabold text-slate-900 mt-2">{{ number_format($counts['total']) }}</div>
            <div class="text-[11px] text-slate-500 mt-0.5">Semua entitas</div>
        </a>

        <!-- Super Admin -->
        <a href="{{ route('super.users', ['role' => 'super_admin']) }}" 
           class="p-4 rounded-2xl border transition-all {{ $roleFilter === 'super_admin' ? 'bg-emerald-50/60 border-emerald-300 ring-2 ring-emerald-500/20 shadow-xs' : 'bg-white border-slate-200 hover:border-slate-300' }}">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-emerald-600">Super Admin</span>
                <i class="fa-solid fa-crown text-emerald-500 text-xs"></i>
            </div>
            <div class="font-outfit text-2xl font-extrabold text-emerald-700 mt-2">{{ number_format($counts['super_admin']) }}</div>
            <div class="text-[11px] text-slate-500 mt-0.5">Pengelola Pusat</div>
        </a>

        <!-- Admin LPK -->
        <a href="{{ route('super.users', ['role' => 'admin_lpk']) }}" 
           class="p-4 rounded-2xl border transition-all {{ $roleFilter === 'admin_lpk' ? 'bg-brand-50/60 border-brand-300 ring-2 ring-brand-500/20 shadow-xs' : 'bg-white border-slate-200 hover:border-slate-300' }}">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-brand-600">Admin Mitra</span>
                <i class="fa-solid fa-building-user text-brand-500 text-xs"></i>
            </div>
            <div class="font-outfit text-2xl font-extrabold text-brand-700 mt-2">{{ number_format($counts['admin_lpk']) }}</div>
            <div class="text-[11px] text-slate-500 mt-0.5">Pengurus LPK</div>
        </a>

        <!-- Siswa / Mobile User -->
        <a href="{{ route('super.users', ['role' => 'user']) }}" 
           class="p-4 rounded-2xl border transition-all {{ $roleFilter === 'user' ? 'bg-purple-50/60 border-purple-300 ring-2 ring-purple-500/20 shadow-xs' : 'bg-white border-slate-200 hover:border-slate-300' }}">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-purple-600">Peserta / Siswa</span>
                <i class="fa-solid fa-user-graduate text-purple-500 text-xs"></i>
            </div>
            <div class="font-outfit text-2xl font-extrabold text-purple-700 mt-2">{{ number_format($counts['user']) }}</div>
            <div class="text-[11px] text-slate-500 mt-0.5">Aplikasi Mobile</div>
        </a>
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs flex flex-col sm:flex-row items-center justify-between gap-3">
        <!-- Search Input -->
        <form action="{{ route('super.users') }}" method="GET" class="relative w-full sm:w-80">
            @if($roleFilter)
                <input type="hidden" name="role" value="{{ $roleFilter }}">
            @endif
            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                <i class="fa-solid fa-magnifying-glass text-xs"></i>
            </span>
            <input type="text" name="search" value="{{ $search ?? '' }}"
                   placeholder="Cari nama, email, nomor HP..." 
                   class="w-full pl-9 pr-4 py-2 rounded-xl border border-slate-200 bg-slate-50 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:bg-white focus:border-brand-500 transition">
        </form>

        <!-- Role Filter Buttons -->
        <div class="flex items-center gap-1.5 w-full sm:w-auto overflow-x-auto pb-1 sm:pb-0">
            <a href="{{ route('super.users', array_merge(request()->except('role'), ['role' => ''])) }}" 
               class="px-3 py-1.5 rounded-lg text-xs font-semibold whitespace-nowrap transition {{ empty($roleFilter) ? 'bg-slate-900 text-white' : 'bg-slate-100 hover:bg-slate-200 text-slate-600' }}">
                Semua ({{ $counts['total'] }})
            </a>
            <a href="{{ route('super.users', array_merge(request()->except('role'), ['role' => 'super_admin'])) }}" 
               class="px-3 py-1.5 rounded-lg text-xs font-semibold whitespace-nowrap transition {{ $roleFilter === 'super_admin' ? 'bg-emerald-600 text-white' : 'bg-slate-100 hover:bg-slate-200 text-slate-600' }}">
                Super Admin
            </a>
            <a href="{{ route('super.users', array_merge(request()->except('role'), ['role' => 'admin_lpk'])) }}" 
               class="px-3 py-1.5 rounded-lg text-xs font-semibold whitespace-nowrap transition {{ $roleFilter === 'admin_lpk' ? 'bg-brand-600 text-white' : 'bg-slate-100 hover:bg-slate-200 text-slate-600' }}">
                Admin LPK
            </a>
            <a href="{{ route('super.users', array_merge(request()->except('role'), ['role' => 'user'])) }}" 
               class="px-3 py-1.5 rounded-lg text-xs font-semibold whitespace-nowrap transition {{ $roleFilter === 'user' ? 'bg-purple-600 text-white' : 'bg-slate-100 hover:bg-slate-200 text-slate-600' }}">
                Siswa / Peserta
            </a>
            @if($search || $roleFilter)
                <a href="{{ route('super.users') }}" class="px-2.5 py-1.5 text-xs text-rose-600 hover:underline font-semibold ml-1">
                    Reset
                </a>
            @endif
        </div>
    </div>

    <!-- Users Table Card -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600 divide-y divide-slate-100">
                <thead class="bg-slate-50/80 text-slate-500 uppercase tracking-wider text-[11px] font-bold">
                    <tr>
                        <th class="py-3.5 px-6">Pengguna</th>
                        <th class="py-3.5 px-6">Kontak & Telepon</th>
                        <th class="py-3.5 px-6">Peran / Hak Akses</th>
                        <th class="py-3.5 px-6">Lembaga Terkait</th>
                        <th class="py-3.5 px-6">Status Akun</th>
                        <th class="py-3.5 px-6 text-right">Terdaftar</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($users as $user)
                        @php
                            $isSuper = $user->hasRole('super_admin') || $user->hasRole('super-admin');
                            $isAdminLpk = $user->hasRole('admin_lpk') || $user->hasRole('lpk-admin') || $user->hasRole('admin-lpk');
                            $isStudent = $user->hasRole('student') || $user->hasRole('user');
                        @endphp
                        <tr class="hover:bg-slate-50/60 transition">
                            <!-- Name & Avatar -->
                            <td class="py-3.5 px-6 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl flex items-center justify-center font-bold text-xs shadow-xs
                                        {{ $isSuper ? 'bg-emerald-100 text-emerald-700' : ($isAdminLpk ? 'bg-brand-100 text-brand-700' : 'bg-purple-100 text-purple-700') }}">
                                        {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-slate-900">{{ $user->name }}</div>
                                        <div class="text-[11px] text-slate-400 font-mono">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>

                            <!-- Contact -->
                            <td class="py-3.5 px-6 whitespace-nowrap">
                                @if($user->phone)
                                    <span class="font-mono text-slate-700">{{ $user->phone }}</span>
                                @else
                                    <span class="text-slate-400 italic">-</span>
                                @endif
                            </td>

                            <!-- Role Badge -->
                            <td class="py-3.5 px-6 whitespace-nowrap">
                                @if($isSuper)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <i class="fa-solid fa-crown text-[9px]"></i> Super Admin
                                    </span>
                                @elseif($isAdminLpk)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-brand-50 text-brand-700 border border-brand-200">
                                        <i class="fa-solid fa-building text-[9px]"></i> Admin Mitra LPK
                                    </span>
                                @elseif($isStudent)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-purple-50 text-purple-700 border border-purple-200">
                                        <i class="fa-solid fa-graduation-cap text-[9px]"></i> Siswa / Peserta
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-slate-100 text-slate-600">
                                        {{ $user->roles->pluck('name')->implode(', ') ?: 'User' }}
                                    </span>
                                @endif
                            </td>

                            <!-- Associated LPK -->
                            <td class="py-3.5 px-6 whitespace-nowrap">
                                @if($user->lpk)
                                    <div class="font-semibold text-slate-800">{{ $user->lpk->name }}</div>
                                    <div class="text-[10px] text-slate-400">NIB: {{ $user->lpk->nib ?? '-' }}</div>
                                @elseif($user->tenant)
                                    <div class="font-semibold text-slate-800">{{ $user->tenant->name }}</div>
                                @else
                                    <span class="text-slate-400 italic">Platform Pusat</span>
                                @endif
                            </td>

                            <!-- Status -->
                            <td class="py-3.5 px-6 whitespace-nowrap">
                                @if($user->status === 'active')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>
                                        Aktif
                                    </span>
                                @elseif($user->status === 'pending')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                        <span class="w-1.5 h-1.5 bg-amber-500 rounded-full animate-ping"></span>
                                        Menunggu Review
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-rose-50 text-rose-700 border border-rose-200">
                                        <span class="w-1.5 h-1.5 bg-rose-500 rounded-full"></span>
                                        Nonaktif
                                    </span>
                                @endif
                            </td>

                            <!-- Created Date -->
                            <td class="py-3.5 px-6 whitespace-nowrap text-right text-slate-500 font-mono">
                                {{ $user->created_at ? $user->created_at->translatedFormat('d M Y') : '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-slate-400">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-12 h-12 rounded-2xl bg-slate-100 flex items-center justify-center text-xl text-slate-400 mb-3">
                                        <i class="fa-solid fa-user-slash"></i>
                                    </div>
                                    <p class="text-sm font-bold text-slate-700">Tidak Ada Pengguna Ditemukan</p>
                                    <p class="text-xs text-slate-400 mt-1">Coba sesuaikan kata kunci pencarian atau filter peran yang dipilih.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                {{ $users->links() }}
            </div>
        @endif
    </div>

</div>
@endsection