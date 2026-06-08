@extends('layouts.admin')

@section('header', 'Detail Booking')

@section('content')

    <div class="max-w-7xl mx-auto p-6">

        <!-- HEADER -->
        <div class="flex items-center justify-between mb-6">

            <div>
                <h1 class="text-2xl font-bold text-gray-800">
                    Detail Booking
                </h1>

                <p class="text-sm text-gray-500 mt-1">
                    Detail booking kursus siswa
                </p>
            </div>

            <a href="{{ route('admin.bookings.index') }}"
                class="inline-flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-100 transition">

                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">

                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />

                </svg>

                Kembali

            </a>

        </div>

        <!-- MAIN GRID -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">

            <!-- LEFT -->
            <div class="lg:col-span-2 space-y-6">

                <!-- BOOKING -->
                <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">

                    <div class="px-6 py-5 border-b border-gray-200 bg-gray-50 flex items-center justify-between">

                        <div>

                            <p class="text-sm text-gray-500">
                                Booking Code
                            </p>

                            <h2 class="text-xl font-bold text-gray-800 mt-1">
                                {{ $booking->code }}
                            </h2>

                        </div>

                        <span @class([
                            'px-4 py-1 rounded-full text-xs font-semibold',
                            'bg-green-100 text-green-700' => $booking->status === 'approved',
                            'bg-yellow-100 text-yellow-700' => $booking->status !== 'approved',
                        ])>

                            {{ ucfirst($booking->status) }}

                        </span>

                    </div>

                    <div class="p-6">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            <div>

                                <p class="text-sm text-gray-500 mb-1">
                                    Tanggal Booking
                                </p>

                                <p class="font-semibold text-gray-800">
                                    {{ $booking->created_at->format('d M Y, H:i') }}
                                </p>

                            </div>

                            <div>

                                <p class="text-sm text-gray-500 mb-1">
                                    Kedaluwarsa
                                </p>

                                <p class="font-semibold text-gray-800">
                                    {{ optional($booking->expires_at)->format('d M Y, H:i') ?? '-' }}
                                </p>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- SISWA -->
                <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">

                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">

                        <h3 class="text-lg font-bold text-gray-800">
                            Informasi Siswa
                        </h3>

                    </div>

                    <div class="p-6">

                        <div class="flex items-center gap-4">

                            <div
                                class="w-14 h-14 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-bold text-lg">

                                {{ strtoupper(substr($booking->user->name ?? 'U', 0, 2)) }}

                            </div>

                            <div>

                                <p class="font-semibold text-gray-800 text-base">
                                    {{ $booking->user->name }}
                                </p>

                                <p class="text-sm text-gray-500 mt-1">
                                    {{ $booking->user->email }}
                                </p>

                                <p class="text-sm text-gray-500">
                                    {{ $booking->user->phone }}
                                </p>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- KURSUS -->
                <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">

                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">

                        <h3 class="text-lg font-bold text-gray-800">
                            Informasi Kursus
                        </h3>

                    </div>

                    <div class="p-6 space-y-6">

                        <div>

                            <p class="text-sm text-gray-500 mb-1">
                                Nama Kursus
                            </p>

                            <p class="font-semibold text-gray-800">
                                {{ $booking->schedule->course->title }}
                            </p>

                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            <div>

                                <p class="text-sm text-gray-500 mb-1">
                                    Tanggal Mulai
                                </p>

                                <p class="font-semibold text-gray-800">
                                    {{ $booking->schedule->start_date }}
                                </p>

                            </div>

                            <div>

                                <p class="text-sm text-gray-500 mb-1">
                                    Tanggal Selesai
                                </p>

                                <p class="font-semibold text-gray-800">
                                    {{ $booking->schedule->end_date }}
                                </p>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

            <!-- RIGHT -->
            <div class="space-y-6">

                <!-- PAYMENT -->
                <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-visible">

                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">

                        <h3 class="text-lg font-bold text-gray-800">
                            Pembayaran
                        </h3>

                    </div>

                    <div class="p-6 space-y-5">

                        <div>

                            <p class="text-sm text-gray-500 mb-1">
                                Total Tagihan
                            </p>

                            <h2 class="text-3xl font-bold text-gray-800 break-words">
                                Rp {{ number_format($booking->amount, 0, ',', '.') }}
                            </h2>

                        </div>

                        <div class="flex items-center justify-between pt-4 border-t border-gray-200">

                            <span class="text-sm text-gray-500">
                                Status
                            </span>

                            <span @class([
                                'px-4 py-1 rounded-full text-xs font-semibold',
                                'bg-green-100 text-green-700' => $booking->payment_status === 'paid',
                                'bg-red-100 text-red-700' => $booking->payment_status !== 'paid',
                            ])>

                                {{ ucfirst($booking->payment_status ?? 'Unpaid') }}

                            </span>

                        </div>

                        @if($booking->payment)

                            <div>

                                <p class="text-sm text-gray-500 mb-1">
                                    Metode
                                </p>

                                <p class="font-semibold text-gray-800">
                                    {{ ucfirst($booking->payment->method) }}
                                </p>

                            </div>

                        @endif

                    </div>

                </div>

                <!-- ACTION -->
                <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-visible">

                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">

                        <h3 class="text-lg font-bold text-gray-800">
                            Tindakan
                        </h3>

                    </div>

                    <div class="p-6 flex flex-col gap-4">

                        {{-- 🔥 NOTIFIKASI ERROR: Menampilkan alasan utama mengapa proses ACC gagal di VPS --}}
                        @if($errors->any())
                            <div
                                style="background-color: #ffeeee !important; color: #cc0000 !important; padding: 16px; border-radius: 8px; font-weight: bold; border-left: 5px solid #cc0000; margin-bottom: 12px; font-size: 14px;">
                                ⚠️ Gagal Memproses: {{ $errors->first() }}
                            </div>
                        @endif

                        {{-- 1. JIKA STATUS MASIH "Menunggu" / "pending" --}}
                        @if(in_array(trim(strtolower($booking->status)), ['menunggu', 'pending']))
                            <div class="flex flex-col gap-3 w-full">
                                <form action="{{ route('admin.bookings.status', $booking->id) }}" method="POST" class="w-full">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="confirmed">
                                    <button type="submit"
                                        style="background-color: #10b981 !important; color: #ffffff !important; padding: 14px; border-radius: 8px; width: 100%; font-weight: bold; border: none; cursor: pointer; display: block; text-align: center; font-size: 16px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);">
                                        Setujui Pendaftaran (ACC)
                                    </button>
                                </form>

                                <form action="{{ route('admin.bookings.status', $booking->id) }}" method="POST" class="w-full">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="cancelled">
                                    <button type="submit"
                                        style="background-color: #ef4444 !important; color: #ffffff !important; padding: 14px; border-radius: 8px; width: 100%; font-weight: bold; border: none; cursor: pointer; display: block; text-align: center; font-size: 16px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);">
                                        Tolak Pendaftaran
                                    </button>
                                </form>
                            </div>
                        @endif

                        {{-- 2. JIKA BOOKING SUDAH DI-ACC (Sekarang pakai kata 'selesai') --}}
                        @if(trim(strtolower($booking->status)) == 'selesai')
                            @if($booking->payment && trim(strtolower($booking->payment->status)) == 'pending')
                                <form action="{{ route('admin.payments.status', $booking->payment->id) }}" method="POST"
                                    class="w-full">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="paid">
                                    <button type="submit"
                                        style="background-color: #2563eb !important; color: #ffffff !important; padding: 14px; border-radius: 8px; width: 100%; font-weight: bold; border: none; cursor: pointer; display: block; text-align: center; font-size: 16px;">
                                        Konfirmasi Pembayaran Lunas
                                    </button>
                                </form>
                            @else
                                <div
                                    style="background-color: #d1fae5 !important; color: #065f46 !important; padding: 16px; border-radius: 8px; font-weight: bold; text-align: center; font-size: 15px;">
                                    ✅ Pendaftaran Disetujui & Lunas
                                </div>
                            @endif
                        @endif

                        {{-- 3. JIKA BOOKING DITOLAK (Sekarang pakai kata 'dibatalkan') --}}
                        @if(trim(strtolower($booking->status)) == 'dibatalkan')
                            <div
                                style="background-color: #fee2e2 !important; color: #991b1b !important; padding: 16px; border-radius: 8px; font-weight: bold; text-align: center; font-size: 15px;">
                                ❌ Pendaftaran ini Telah Dibatalkan
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

@endsection