@extends('layouts.admin')

@section('title', 'Payment Detail')

@section('content')

<div class="max-w-6xl mx-auto space-y-6">

    <!-- HEADER -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

        <div>

            <h1 class="text-2xl font-bold text-gray-800">
                Payment Detail
            </h1>

            <p class="text-gray-500 mt-1">
                Detail pembayaran booking siswa
            </p>

        </div>



        <a href="{{ route('admin.payments.index') }}"
           class="px-4 py-2 border border-gray-200 rounded-xl text-sm hover:bg-gray-50">

            Kembali

        </a>

    </div>



    <!-- ALERT -->
    @if(session('success'))

    <div class="p-4 rounded-xl bg-green-50 border border-green-200 text-green-700">

        {{ session('success') }}

    </div>

    @endif



    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- LEFT -->
        <div class="lg:col-span-2 space-y-6">

            <!-- PAYMENT INFO -->
            <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-6">

                <div class="flex items-center justify-between mb-6">

                    <h2 class="text-lg font-semibold text-gray-800">
                        Payment Information
                    </h2>



                    @php

                    $colors = [

                        'pending' => 'bg-yellow-100 text-yellow-700',

                        'success' => 'bg-green-100 text-green-700',

                        'failed' => 'bg-red-100 text-red-700',

                    ];

                    @endphp



                    <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $colors[$payment->status] ?? 'bg-gray-100 text-gray-700' }}">

                        {{ ucfirst($payment->status) }}

                    </span>

                </div>



                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <div>

                        <p class="text-sm text-gray-500">
                            Booking Code
                        </p>

                        <p class="font-semibold text-gray-800 mt-1">
                            {{ $payment->booking->code ?? '-' }}
                        </p>

                    </div>



                    <div>

                        <p class="text-sm text-gray-500">
                            Amount
                        </p>

                        <p class="font-bold text-gray-900 mt-1 text-lg">

                            Rp {{ number_format($payment->amount,0,',','.') }}

                        </p>

                    </div>



                    <div>

                        <p class="text-sm text-gray-500">
                            Payment Method
                        </p>

                        <p class="font-medium text-gray-800 mt-1">

                            {{ ucfirst($payment->method) }}

                        </p>

                    </div>



                    <div>

                        <p class="text-sm text-gray-500">
                            Provider
                        </p>

                        <p class="font-medium text-gray-800 mt-1">

                            {{ ucfirst($payment->provider) }}

                        </p>

                    </div>



                    <div>

                        <p class="text-sm text-gray-500">
                            Payment Date
                        </p>

                        <p class="font-medium text-gray-800 mt-1">

                            {{ $payment->paid_at ? $payment->paid_at->format('d M Y H:i') : '-' }}

                        </p>

                    </div>



                    <div>

                        <p class="text-sm text-gray-500">
                            Verified At
                        </p>

                        <p class="font-medium text-gray-800 mt-1">

                            {{ $payment->verified_at ? $payment->verified_at->format('d M Y H:i') : '-' }}

                        </p>

                    </div>

                </div>

            </div>



            <!-- BOOKING INFO -->
            <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-6">

                <h2 class="text-lg font-semibold text-gray-800 mb-6">
                    Booking Information
                </h2>



                <div class="space-y-5">

                    <div>

                        <p class="text-sm text-gray-500">
                            Course
                        </p>

                        <p class="font-semibold text-gray-800 mt-1">

                            {{ $payment->booking->schedule->course->title ?? '-' }}

                        </p>

                    </div>



                    <div>

                        <p class="text-sm text-gray-500">
                            Schedule
                        </p>

                        <p class="font-medium text-gray-800 mt-1">

                            @if($payment->booking->schedule)

                            {{ \Carbon\Carbon::parse($payment->booking->schedule->start_date)->format('d M Y') }}

                            -

                            {{ \Carbon\Carbon::parse($payment->booking->schedule->end_date)->format('d M Y') }}

                            @else

                            -

                            @endif

                        </p>

                    </div>



                    <div>

                        <p class="text-sm text-gray-500">
                            Booking Status
                        </p>

                        <p class="font-medium text-gray-800 mt-1">

                            {{ ucfirst($payment->booking->status ?? '-') }}

                        </p>

                    </div>



                    <div>

                        <p class="text-sm text-gray-500">
                            Payment Status
                        </p>

                        <p class="font-medium text-gray-800 mt-1">

                            {{ ucfirst($payment->booking->payment_status ?? '-') }}

                        </p>

                    </div>

                </div>

            </div>

        </div>



        <!-- RIGHT -->
        <div class="space-y-6">

            <!-- STUDENT -->
            <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-6">

                <h2 class="text-lg font-semibold text-gray-800 mb-6">
                    Student
                </h2>



                <div class="flex flex-col items-center text-center">

                    <div class="w-20 h-20 rounded-full bg-gray-200 flex items-center justify-center text-2xl font-bold text-gray-700">

                        {{ strtoupper(substr($payment->booking->user->name ?? 'U',0,1)) }}

                    </div>



                    <h3 class="mt-4 text-lg font-semibold text-gray-800">

                        {{ $payment->booking->user->name ?? '-' }}

                    </h3>



                    <p class="text-sm text-gray-500 mt-1">

                        {{ $payment->booking->user->email ?? '-' }}

                    </p>



                    <p class="text-sm text-gray-500 mt-2">

                        {{ $payment->booking->user->phone ?? '-' }}

                    </p>

                </div>

            </div>



            <!-- PAYMENT PROOF -->
            <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-6">

                <div class="flex items-center justify-between mb-6">

                    <h2 class="text-lg font-semibold text-gray-800">
                        Payment Proof
                    </h2>

                </div>



                @if($payment->proof)

                <a href="{{ asset('storage/'.$payment->proof) }}"
                   target="_blank">

                    <img
                        src="{{ asset('storage/'.$payment->proof) }}"
                        class="w-full rounded-xl border border-gray-200 hover:opacity-90 transition">

                </a>

                @else

                <div class="border-2 border-dashed border-gray-200 rounded-2xl p-10 text-center">

                    <p class="text-sm text-gray-400">

                        Belum ada bukti pembayaran

                    </p>

                </div>

                @endif

            </div>



            <!-- VERIFY -->
            <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-6">

                <h2 class="text-lg font-semibold text-gray-800 mb-6">
                    Verify Payment
                </h2>



                <div class="space-y-3">

                    @foreach(['pending','success','failed'] as $status)

                    <form
                        action="{{ route('admin.payments.status', $payment) }}"
                        method="POST">

                        @csrf
                        @method('PATCH')

                        <input
                            type="hidden"
                            name="status"
                            value="{{ $status }}">

                        <button
                            type="submit"
                            class="w-full px-4 py-3 rounded-xl text-sm font-semibold transition

                            @if($status == 'success')
                                bg-green-600 hover:bg-green-700 text-white
                            @elseif($status == 'failed')
                                bg-red-600 hover:bg-red-700 text-white
                            @else
                                bg-yellow-500 hover:bg-yellow-600 text-white
                            @endif">

                            {{ ucfirst($status) }}

                        </button>

                    </form>

                    @endforeach

                </div>

            </div>

        </div>

    </div>

</div>

@endsection