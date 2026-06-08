@extends('layouts.admin')

@section('header', 'Booking Siswa')

@section('content')

<div class="w-full px-4 sm:px-6 lg:px-8 py-8">

    {{-- ═══════════════════════════════════════════
         HEADER
    ═══════════════════════════════════════════ --}}
    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Booking Siswa</h2>
            <p class="text-sm text-gray-500 mt-1">Kelola seluruh booking kursus siswa.</p>
        </div>
        <a href="{{ route('admin.bookings.create') }}"
            class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg shadow transition">
            {{-- Icon: Plus --}}
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Buat Booking
        </a>
    </div>

    {{-- ═══════════════════════════════════════════
         SUMMARY CARDS
         ✅ FIX: Semua pakai variabel dari controller, bukan ->where() di view
    ═══════════════════════════════════════════ --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-5 mb-8">

        {{-- Total Booking --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Total Booking</p>
                <h3 class="text-3xl font-bold text-gray-800">{{ $totalBookings }}</h3>
            </div>
            <div class="w-12 h-12 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-center flex-shrink-0">
                {{-- Icon: Clipboard list --}}
                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2M9 12h6M9 16h4"/>
                </svg>
            </div>
        </div>

        {{-- Paid --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Lunas</p>
                <h3 class="text-3xl font-bold text-emerald-600">{{ $paidCount }}</h3>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center justify-center flex-shrink-0">
                {{-- Icon: Badge check --}}
                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                </svg>
            </div>
        </div>

        {{-- Pending --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Menunggu</p>
                <h3 class="text-3xl font-bold text-amber-500">{{ $pendingCount }}</h3>
            </div>
            <div class="w-12 h-12 rounded-xl bg-amber-50 border border-amber-100 flex items-center justify-center flex-shrink-0">
                {{-- Icon: Clock --}}
                <svg class="w-6 h-6 text-amber-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>

        {{-- Revenue --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Revenue</p>
                <h3 class="text-lg font-bold text-purple-600 mt-1 leading-tight">
                    Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                </h3>
            </div>
            <div class="w-12 h-12 rounded-xl bg-purple-50 border border-purple-100 flex items-center justify-center flex-shrink-0">
                {{-- Icon: Currency dollar / cash --}}
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
        </div>

    </div>

    {{-- ═══════════════════════════════════════════
         SUCCESS / ERROR FLASH
    ═══════════════════════════════════════════ --}}
    @if(session('success'))
        <div class="mb-6 rounded-xl bg-emerald-50 border border-emerald-200 px-5 py-4 text-sm text-emerald-700 font-medium flex items-center gap-3">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- ═══════════════════════════════════════════
         TABLE
    ═══════════════════════════════════════════ --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full min-w-full divide-y divide-gray-100">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Booking</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Siswa</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Kursus</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Payment</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($bookings as $booking)
                        @php
                            $paymentStatus = strtolower($booking->payment->status ?? 'unpaid');
                            $isPaid        = in_array($paymentStatus, ['paid', 'verified', 'success']);
                            $isPending     = $paymentStatus === 'pending';
                        @endphp
                        <tr class="hover:bg-gray-50/60 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="font-mono text-sm font-semibold text-gray-700">
                                    #{{ substr($booking->id, 0, 8) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center text-xs font-bold flex-shrink-0">
                                        {{ strtoupper(substr($booking->user->name ?? 'U', 0, 2)) }}
                                    </div>
                                    <div>
                                        <div class="text-sm font-semibold text-gray-800">{{ $booking->user->name ?? '-' }}</div>
                                        <div class="text-xs text-gray-400">{{ $booking->user->email ?? $booking->user->phone ?? '' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-700 max-w-[200px] truncate">
                                    {{ $booking->schedule->course->title ?? '-' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-semibold text-gray-800">
                                    Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($isPaid)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Lunas
                                    </span>
                                @elseif($isPending)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span> Menunggu
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-100 text-rose-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-400"></span> Belum Bayar
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $bookingStatus = trim(strtolower($booking->status));
                                    $statusLabel = match($bookingStatus) {
                                        'menunggu'   => ['label' => 'Menunggu',   'class' => 'bg-yellow-100 text-yellow-700'],
                                        'selesai'    => ['label' => 'Disetujui',  'class' => 'bg-blue-100 text-blue-700'],
                                        'dibatalkan' => ['label' => 'Dibatalkan', 'class' => 'bg-red-100 text-red-700'],
                                        default      => ['label' => ucfirst($booking->status), 'class' => 'bg-gray-100 text-gray-600'],
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $statusLabel['class'] }}">
                                    {{ $statusLabel['label'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $booking->created_at->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <a href="{{ route('admin.bookings.show', $booking) }}"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-indigo-50 hover:bg-indigo-100 text-indigo-600 text-xs font-semibold transition"
                                    title="Lihat Detail">
                                    {{-- Icon: Eye --}}
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center gap-3 text-gray-400">
                                    <svg class="w-14 h-14 text-gray-200" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                    <p class="text-base font-semibold text-gray-500">Belum ada booking</p>
                                    <p class="text-sm text-gray-400">Data booking siswa akan muncul di sini.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($bookings->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                {{ $bookings->links() }}
            </div>
        @endif
    </div>

</div>

@endsection