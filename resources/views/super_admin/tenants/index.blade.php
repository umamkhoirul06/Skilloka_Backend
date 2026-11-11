@extends('layouts.super_admin')

@section('title', 'Mitra LPK')

@section('content')
<div class="space-y-6">

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="font-outfit text-2xl font-bold text-slate-900 tracking-tight">Daftar Mitra LPK Aktif</h1>
            <p class="text-xs text-slate-500 mt-1">Kelola seluruh Lembaga Pelatihan Kerja (LPK) aktif yang telah terverifikasi resmi di platform Skilloka.</p>
        </div>
        
        <div class="flex flex-wrap items-center gap-3">
            <div class="relative">
                <select id="lpkPeriod" class="px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 shadow-2xs">
                    <option value="month">Bulan Ini</option>
                    <option value="3months">3 Bulan Terakhir</option>
                    <option value="6months">6 Bulan Terakhir</option>
                    <option value="year">Tahun Ini</option>
                </select>
            </div>
            <button onclick="downloadLpkPdf()" class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-semibold shadow-sm transition flex items-center gap-2">
                <i class="fa-solid fa-file-pdf text-xs"></i>
                <span>Unduh Daftar LPK (PDF)</span>
            </button>
        </div>
    </div>

    <!-- ═══════════════════════════════════════
         SUMMARY METRICS (3 CARDS)
    ═══════════════════════════════════════ -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="p-5 rounded-2xl bg-white border border-slate-200/90 shadow-2xs flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Mitra LPK Aktif</span>
                <div class="font-outfit text-2xl font-extrabold text-slate-900 mt-1">
                    {{ number_format($stats['total_lpk'] ?? 0) }}
                </div>
                <div class="text-[11px] font-semibold text-emerald-600 mt-1 flex items-center gap-1">
                    <i class="fa-solid fa-circle-check text-[10px]"></i>
                    <span>Telah beroperasi resmi</span>
                </div>
            </div>
            <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg">
                <i class="fa-solid fa-building-columns"></i>
            </div>
        </div>

        <div class="p-5 rounded-2xl bg-white border border-slate-200/90 shadow-2xs flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Verifikasi Dokumen NIB</span>
                <div class="font-outfit text-2xl font-extrabold text-slate-900 mt-1">
                    {{ number_format($stats['verified'] ?? 0) }}
                </div>
                <div class="text-[11px] font-semibold text-indigo-600 mt-1 flex items-center gap-1">
                    <i class="fa-solid fa-certificate text-[10px]"></i>
                    <span>Legalitas NIB tervalidasi</span>
                </div>
            </div>
            <div class="w-11 h-11 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-lg">
                <i class="fa-solid fa-shield-halved"></i>
            </div>
        </div>

        <div class="p-5 rounded-2xl bg-white border border-slate-200/90 shadow-2xs flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Program Kursus Dibuka</span>
                <div class="font-outfit text-2xl font-extrabold text-slate-900 mt-1">
                    {{ number_format($stats['courses'] ?? 0) }}
                </div>
                <div class="text-[11px] text-slate-400 mt-1 flex items-center gap-1">
                    <i class="fa-solid fa-layer-group text-[10px]"></i>
                    <span>Pelatihan di seluruh mitra</span>
                </div>
            </div>
            <div class="w-11 h-11 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg">
                <i class="fa-solid fa-graduation-cap"></i>
            </div>
        </div>
    </div>

    <!-- ═══════════════════════════════════════
         TABEL DATA MITRA LPK
    ═══════════════════════════════════════ -->
    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-2xs overflow-hidden">
        
        <!-- Toolbar & Filter -->
        <div class="p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <h3 class="font-outfit font-bold text-base text-slate-900">Daftar Lembaga Pelatihan</h3>
                <p class="text-xs text-slate-500 mt-0.5">Seluruh LPK terdaftar yang memiliki izin pelatihan aktif di platform.</p>
            </div>

            <!-- Form Pencarian -->
            <form method="GET" action="{{ route('super.tenants') }}" class="flex items-center gap-2 w-full sm:w-auto">
                <div class="relative flex-1 sm:w-64">
                    <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                    <input type="text" 
                           name="search" 
                           value="{{ $search ?? '' }}" 
                           placeholder="Cari LPK, NIB, atau admin..." 
                           class="w-full pl-9 pr-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-indigo-500 focus:bg-white transition">
                </div>
                @if(!empty($search))
                    <a href="{{ route('super.tenants') }}" class="p-2 rounded-xl text-slate-400 hover:text-slate-600 hover:bg-slate-100 text-xs" title="Reset Pencarian">
                        <i class="fa-solid fa-xmark"></i>
                    </a>
                @endif
                <button type="submit" class="px-3.5 py-2 bg-slate-800 hover:bg-slate-900 text-white rounded-xl text-xs font-semibold transition">
                    Cari
                </button>
            </form>
        </div>

        <!-- Tabel LPK -->
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50/80 text-slate-700 font-semibold border-b border-slate-200 uppercase tracking-wider text-[11px]">
                    <tr>
                        <th class="py-3.5 px-6">Nama LPK & Alamat</th>
                        <th class="py-3.5 px-6">Penanggung Jawab</th>
                        <th class="py-3.5 px-6">Kontak Lembaga</th>
                        <th class="py-3.5 px-6">Status</th>
                        <th class="py-3.5 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($tenants as $tenant)
                        @php
                            $lpk = $tenant->lpk;
                            $user = $tenant->users->first();
                            $courseCount = $lpk?->courses?->count() ?? 0;
                        @endphp
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <!-- LPK Profile -->
                            <td class="py-4 px-6">
                                <div class="flex items-start gap-3.5">
                                    <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-indigo-50 to-indigo-100/80 border border-indigo-200/60 text-indigo-700 flex items-center justify-center font-outfit font-black text-base shrink-0 shadow-2xs">
                                        {{ strtoupper(substr($lpk->name ?? $tenant->name ?? 'L', 0, 1)) }}
                                    </div>
                                    <div class="space-y-1 max-w-sm">
                                        <div class="flex items-center gap-2">
                                            <a href="{{ route('super.tenants.show', $tenant->id) }}" class="font-outfit font-bold text-sm text-slate-900 hover:text-indigo-600 transition">
                                                {{ $lpk->name ?? $tenant->name ?? '-' }}
                                            </a>
                                            @if($lpk && $lpk->nib)
                                                <span class="px-2 py-0.5 rounded-md text-[10px] font-mono font-bold bg-slate-100 text-slate-600 border border-slate-200">
                                                    NIB: {{ $lpk->nib }}
                                                </span>
                                            @endif
                                        </div>
                                        <div class="text-[11px] text-slate-500 flex items-center gap-1.5 line-clamp-1">
                                            <i class="fa-solid fa-location-dot text-slate-400 text-[10px]"></i>
                                            <span>{{ $tenant->city ?? 'Indramayu' }}</span>
                                            @if($lpk && $lpk->address)
                                                <span>•</span>
                                                <span class="truncate">{{ $lpk->address }}</span>
                                            @endif
                                        </div>
                                        <div class="text-[10px] text-indigo-600 font-semibold flex items-center gap-1">
                                            <i class="fa-solid fa-graduation-cap text-[9px]"></i>
                                            <span>{{ $courseCount }} Program Kursus Tersedia</span>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Penanggung Jawab (Admin) -->
                            <td class="py-4 px-6 whitespace-nowrap">
                                <div class="space-y-0.5">
                                    <div class="font-semibold text-slate-900 text-xs flex items-center gap-1.5">
                                        <i class="fa-solid fa-user-tie text-slate-400 text-[11px]"></i>
                                        <span>{{ $user->name ?? 'Admin LPK' }}</span>
                                    </div>
                                    <div class="text-[11px] text-slate-500">
                                        {{ $user->email ?? '-' }}
                                    </div>
                                    <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-600">
                                        Administrator
                                    </span>
                                </div>
                            </td>

                            <!-- Kontak -->
                            <td class="py-4 px-6 whitespace-nowrap">
                                <div class="space-y-1">
                                    <div class="text-xs font-mono font-medium text-slate-800 flex items-center gap-1.5">
                                        <i class="fa-solid fa-phone text-slate-400 text-[10px]"></i>
                                        <span>{{ $tenant->phone ?? $user->phone ?? '-' }}</span>
                                    </div>
                                    <div class="text-[11px] text-slate-500 flex items-center gap-1.5">
                                        <i class="fa-solid fa-envelope text-slate-400 text-[10px]"></i>
                                        <span>{{ $tenant->email ?? $lpk->email ?? '-' }}</span>
                                    </div>
                                </div>
                            </td>

                            <!-- Status -->
                            <td class="py-4 px-6 whitespace-nowrap">
                                @if($lpk && $lpk->status == 'active')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                        <span>Aktif</span>
                                    </span>
                                @elseif($lpk && $lpk->status == 'rejected')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                        <span>Ditolak</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                        <span>Menunggu</span>
                                    </span>
                                @endif
                            </td>

                            <!-- Aksi -->
                            <td class="py-4 px-6 whitespace-nowrap text-right">
                                <div class="inline-flex items-center gap-1.5">
                                    <a href="{{ route('super.tenants.show', $tenant->id) }}"
                                       class="px-3 py-1.5 rounded-xl bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-xs font-semibold transition flex items-center gap-1.5"
                                       title="Lihat Detail Profil LPK">
                                        <i class="fa-solid fa-eye text-[11px]"></i>
                                        <span>Detail</span>
                                    </a>

                                    <form method="POST" action="{{ route('super.tenants.delete', $tenant->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data LPK ini? Semua kursus dan data akun terkait akan dihapus.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="p-1.5 rounded-xl bg-slate-50 hover:bg-rose-50 text-slate-400 hover:text-rose-600 border border-slate-200 hover:border-rose-200 text-xs transition"
                                                title="Hapus LPK">
                                            <i class="fa-solid fa-trash-can text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-slate-400">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <div class="w-12 h-12 rounded-2xl bg-slate-100 flex items-center justify-center text-slate-400 text-lg">
                                        <i class="fa-solid fa-building-circle-exclamation"></i>
                                    </div>
                                    <p class="font-medium text-slate-700">Tidak ada data Mitra LPK aktif.</p>
                                    <p class="text-xs text-slate-400">Silakan periksa halaman Verifikasi LPK untuk menyetujui pendaftaran baru.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(method_exists($tenants, 'hasPages') && $tenants->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $tenants->links() }}
            </div>
        @endif
    </div>
</div>

<script>
function downloadLpkPdf() {
    const period = document.getElementById('lpkPeriod').value;
    window.open(`/super-admin/tenants/report/pdf?period=${period}`, '_blank');
}
</script>
@endsection
