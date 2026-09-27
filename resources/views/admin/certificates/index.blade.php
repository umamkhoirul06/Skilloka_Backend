@extends('layouts.admin')

@section('title', 'Penerbitan Sertifikat Siswa')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold font-outfit text-gray-900">Sertifikat Kelulusan Pelatihan</h1>
            <p class="text-xs text-gray-500 mt-1">Terbitkan dan kelola sertifikat kompetensi resmi dengan nomor registrasi unik untuk peserta yang telah menyelesaikan pelatihan.</p>
        </div>
        <a href="{{ route('admin.certificates.create') }}" class="shiny-btn shiny-btn-emerald px-4 py-2.5 text-white rounded-xl text-xs font-semibold shadow-md flex items-center gap-2">
            <i class="fa-solid fa-award text-xs"></i>
            <span>Terbitkan Sertifikat Baru</span>
        </a>
    </div>

    @if(session('success'))
        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-medium">
            {{ session('success') }}
        </div>
    @endif

    <!-- ANTREAN SISWA YANG SIAP DITERBITKAN SERTIFIKAT (PRD Section 9.4: "Queue/list penerbitan sertifikat") -->
    <div class="bg-gradient-to-r from-amber-50 to-orange-50 p-6 rounded-2xl border border-amber-200/80 shadow-sm space-y-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <span class="w-8 h-8 rounded-lg bg-amber-500 text-white flex items-center justify-center text-sm shadow">
                    <i class="fa-solid fa-hourglass-half"></i>
                </span>
                <div>
                    <h3 class="font-outfit font-bold text-sm text-amber-950">Antrean Penerbitan Sertifikat</h3>
                    <p class="text-[11px] text-amber-700">Daftar siswa yang telah menyelesaikan sesi kursus dan menunggu penerbitan sertifikat resmi.</p>
                </div>
            </div>
            <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-amber-200/60 text-amber-900">
                {{ $eligibleBookings->count() }} Siswa Menunggu
            </span>
        </div>

        @if($eligibleBookings->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 pt-2">
                @foreach($eligibleBookings->take(6) as $booking)
                    <div class="bg-white p-4 rounded-xl border border-amber-200 shadow-xs flex items-center justify-between gap-3">
                        <div class="truncate">
                            <h4 class="font-bold text-xs text-gray-900 truncate">{{ $booking->user->name ?? 'Siswa' }}</h4>
                            <p class="text-[11px] text-gray-500 truncate">{{ $booking->course->title ?? '-' }}</p>
                            <span class="text-[10px] text-amber-600 font-medium">Status: {{ ucfirst($booking->status_belajar ?? 'Belajar') }}</span>
                        </div>
                        <a href="{{ route('admin.certificates.create', ['booking_id' => $booking->id]) }}" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold whitespace-nowrap shadow-xs transition">
                            Terbitkan
                        </a>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-xs text-amber-800 italic">Semua siswa yang menyelesaikan pelatihan telah diterbitkan sertifikatnya.</p>
        @endif
    </div>

    <!-- LIST SERTIFIKAT YANG TELAH DITERBITKAN -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-outfit font-bold text-sm text-gray-900">Daftar Sertifikat Diterbitkan</h3>
            <span class="text-xs text-gray-400">Total {{ $certificates->total() }} Sertifikat</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-gray-600">
                <thead class="bg-gray-50/80 text-gray-700 font-semibold border-b border-gray-200 uppercase tracking-wider text-[11px]">
                    <tr>
                        <th class="py-3.5 px-6">Nomor Sertifikat</th>
                        <th class="py-3.5 px-6">Nama Siswa</th>
                        <th class="py-3.5 px-6">Program Kursus</th>
                        <th class="py-3.5 px-6">Tanggal Terbit</th>
                        <th class="py-3.5 px-6">Masa Berlaku</th>
                        <th class="py-3.5 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($certificates as $cert)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="py-3.5 px-6 font-mono font-bold text-indigo-700 text-xs">
                                {{ $cert->number }}
                            </td>
                            <td class="py-3.5 px-6">
                                <div class="font-bold text-gray-900 text-sm">{{ $cert->user->name ?? 'Peserta' }}</div>
                                <div class="text-[10px] text-gray-400 font-mono">{{ $cert->user->email ?? '-' }}</div>
                            </td>
                            <td class="py-3.5 px-6 font-medium text-gray-800">{{ $cert->course->title ?? '-' }}</td>
                            <td class="py-3.5 px-6 font-mono text-gray-500">
                                {{ \Carbon\Carbon::parse($cert->issue_date)->translatedFormat('d M Y') }}
                            </td>
                            <td class="py-3.5 px-6">
                                @if($cert->expiry_date)
                                    <span class="font-mono text-gray-500">{{ \Carbon\Carbon::parse($cert->expiry_date)->translatedFormat('d M Y') }}</span>
                                @else
                                    <span class="text-emerald-600 font-medium">Seumur Hidup</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-6 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.certificates.show', $cert->id) }}" target="_blank" class="px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-xs font-semibold transition" title="Lihat / Cetak Sertifikat">
                                        <i class="fa-solid fa-print mr-1"></i> Cetak
                                    </a>
                                    <form action="{{ route('admin.certificates.destroy', $cert->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin mencabut sertifikat ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-2.5 py-1.5 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg text-xs font-semibold transition" title="Cabut Sertifikat">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-gray-400">
                                <i class="fa-solid fa-certificate text-3xl text-gray-300 mb-2"></i>
                                <p>Belum ada sertifikat yang diterbitkan.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-gray-100">
            {{ $certificates->links() }}
        </div>
    </div>
</div>
@endsection
