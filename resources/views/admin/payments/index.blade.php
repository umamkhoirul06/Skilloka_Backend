@extends('layouts.admin')

@section('title', 'Payments')

@section('content')

<div class="space-y-6">

    <!-- HEADER -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

        <div>

            <h1 class="text-2xl font-bold text-gray-800">
                Payment Management
            </h1>

            <p class="text-gray-500 mt-1">
                Kelola seluruh pembayaran booking siswa
            </p>

        </div>

    </div>



    <!-- ALERT -->
    @if(session('success'))

    <div class="p-4 rounded-xl bg-green-50 border border-green-200 text-green-700">

        {{ session('success') }}

    </div>

    @endif



    <!-- TABLE -->
    <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">

        <div class="overflow-x-auto">

            <table class="w-full">

                <thead class="bg-gray-50 border-b border-gray-100">

                    <tr class="text-left text-sm text-gray-500">

                        <th class="px-6 py-4">
                            Payment
                        </th>

                        <th class="px-6 py-4">
                            Student
                        </th>

                        <th class="px-6 py-4">
                            Course
                        </th>

                        <th class="px-6 py-4">
                            Amount
                        </th>

                        <th class="px-6 py-4">
                            Method
                        </th>

                        <th class="px-6 py-4">
                            Proof
                        </th>

                        <th class="px-6 py-4">
                            Status
                        </th>

                        <th class="px-6 py-4 text-right">
                            Action
                        </th>

                    </tr>

                </thead>



                <tbody class="divide-y divide-gray-100">

                    @forelse($payments as $payment)

                    <tr class="hover:bg-gray-50 transition">

                        <!-- PAYMENT -->
                        <td class="px-6 py-5">

                            <div class="flex flex-col">

                                <span class="font-semibold text-gray-800">

                                    {{ $payment->booking->code ?? '-' }}

                                </span>

                                <span class="text-xs text-gray-400 mt-1">

                                    {{ $payment->created_at->format('d M Y H:i') }}

                                </span>

                            </div>

                        </td>



                        <!-- STUDENT -->
                        <td class="px-6 py-5">

                            <div class="flex items-center gap-3">

                                <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center text-sm font-bold text-gray-700">

                                    {{ strtoupper(substr($payment->booking->user->name ?? 'U',0,1)) }}

                                </div>

                                <div>

                                    <div class="font-medium text-gray-800">

                                        {{ $payment->booking->user->name ?? '-' }}

                                    </div>

                                    <div class="text-sm text-gray-500">

                                        {{ $payment->booking->user->email ?? '-' }}

                                    </div>

                                </div>

                            </div>

                        </td>



                        <!-- COURSE -->
                        <td class="px-6 py-5">

                            <div class="font-medium text-gray-800">

                                {{ $payment->booking->schedule->course->title ?? '-' }}

                            </div>

                        </td>



                        <!-- AMOUNT -->
                        <td class="px-6 py-5">

                            <div class="font-semibold text-gray-900">

                                Rp {{ number_format($payment->amount,0,',','.') }}

                            </div>

                        </td>



                        <!-- METHOD -->
                        <td class="px-6 py-5">

                            <span class="px-3 py-1 rounded-full bg-gray-100 text-gray-700 text-xs font-semibold">

                                {{ ucfirst($payment->method) }}

                            </span>

                        </td>



                        <!-- PROOF -->
                        <td class="px-6 py-5">

                            @if($payment->proof)

                            <a href="{{ asset('storage/'.$payment->proof) }}"
                               target="_blank"
                               class="text-blue-600 text-sm font-medium hover:underline">

                                Lihat Bukti

                            </a>

                            @else

                            <span class="text-gray-400 text-sm">

                                Belum upload

                            </span>

                            @endif

                        </td>



                        <!-- STATUS -->
                        <td class="px-6 py-5">

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

                        </td>



                        <!-- ACTION -->
                        <td class="px-6 py-5">

                            <div class="flex items-center justify-end gap-2">

                                <!-- DETAIL -->
                                <a href="{{ route('admin.payments.show', $payment) }}"
                                   class="px-3 py-2 rounded-lg border border-gray-200 text-sm hover:bg-gray-50">

                                    Detail

                                </a>



                                <!-- STATUS -->
                                <div class="relative" x-data="{ open: false }">

                                    <button
                                        @click="open = !open"
                                        class="px-3 py-2 bg-gray-900 text-white rounded-lg text-sm">

                                        Verify

                                    </button>



                                    <div
                                        x-show="open"
                                        @click.away="open = false"
                                        class="absolute right-0 mt-2 w-44 bg-white border border-gray-100 rounded-xl shadow-xl overflow-hidden z-50">

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
                                                class="w-full text-left px-4 py-3 text-sm hover:bg-gray-50">

                                                {{ ucfirst($status) }}

                                            </button>

                                        </form>

                                        @endforeach

                                    </div>

                                </div>

                            </div>

                        </td>

                    </tr>

                    @empty

                    <tr>

                        <td colspan="8"
                            class="px-6 py-14 text-center text-gray-400">

                            Belum ada payment

                        </td>

                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>



        <!-- PAGINATION -->
        @if($payments->hasPages())

        <div class="px-6 py-4 border-t border-gray-100">

            {{ $payments->links() }}

        </div>

        @endif

    </div>

</div>

@endsection