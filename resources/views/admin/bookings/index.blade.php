@extends('layouts.admin')

@section('header', 'Booking Siswa')

@section('content')

<div class="space-y-4">

    <!-- HEADER -->
    <div class="flex items-center justify-between">

        <div>
            <h2 class="text-xl font-semibold text-gray-800">
                Booking Siswa
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Kelola seluruh booking kursus siswa
            </p>
        </div>

        <a href="{{ route('admin.bookings.create') }}"
           class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-md transition">

            <svg class="w-4 h-4 mr-2"
                 fill="none"
                 stroke="currentColor"
                 viewBox="0 0 24 24">

                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M12 4v16m8-8H4">
                </path>

            </svg>

            Booking Manual

        </a>

    </div>

    <!-- SUMMARY -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">

        <!-- CARD -->
        <div class="bg-white border border-gray-200 rounded-md p-4">

            <div class="flex items-center justify-between">

                <div>
                    <p class="text-sm text-gray-500">
                        Total Booking
                    </p>

                    <h3 class="text-2xl font-semibold text-gray-800 mt-1">
                        {{ $bookings->total() }}
                    </h3>
                </div>

                <div class="w-10 h-10 rounded-md bg-blue-100 flex items-center justify-center">

                    <svg class="w-5 h-5 text-blue-600"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-2">
                        </path>

                    </svg>

                </div>

            </div>

        </div>

        <!-- PAID -->
        <div class="bg-white border border-gray-200 rounded-md p-4">

            <div class="flex items-center justify-between">

                <div>
                    <p class="text-sm text-gray-500">
                        Paid
                    </p>

                    <h3 class="text-2xl font-semibold text-green-600 mt-1">
                        {{ $bookings->where('payment_status','paid')->count() }}
                    </h3>
                </div>

                <div class="w-10 h-10 rounded-md bg-green-100 flex items-center justify-center">

                    <svg class="w-5 h-5 text-green-600"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M5 13l4 4L19 7">
                        </path>

                    </svg>

                </div>

            </div>

        </div>

        <!-- PENDING -->
        <div class="bg-white border border-gray-200 rounded-md p-4">

            <div class="flex items-center justify-between">

                <div>
                    <p class="text-sm text-gray-500">
                        Pending
                    </p>

                    <h3 class="text-2xl font-semibold text-yellow-500 mt-1">
                        {{ $bookings->where('payment_status','pending')->count() }}
                    </h3>
                </div>

                <div class="w-10 h-10 rounded-md bg-yellow-100 flex items-center justify-center">

                    <svg class="w-5 h-5 text-yellow-600"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M12 8v4l3 3">
                        </path>

                    </svg>

                </div>

            </div>

        </div>

        <!-- REVENUE -->
        <div class="bg-white border border-gray-200 rounded-md p-4">

            <div class="flex items-center justify-between">

                <div>
                    <p class="text-sm text-gray-500">
                        Revenue
                    </p>

                    <h3 class="text-lg font-semibold text-purple-600 mt-1">
                        Rp {{ number_format($bookings->where('payment_status','paid')->sum('amount'),0,',','.') }}
                    </h3>
                </div>

                <div class="w-10 h-10 rounded-md bg-purple-100 flex items-center justify-center">

                    <svg class="w-5 h-5 text-purple-600"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M12 8c-1.657 0-3 1.343-3 3s1.343 3 3 3 3 1.343 3 3-1.343 3-3 3m0-12V4m0 8v8">
                        </path>

                    </svg>

                </div>

            </div>

        </div>

    </div>

    <!-- TABLE -->
    <div class="bg-white border border-gray-200 rounded-md overflow-hidden">

        <div class="overflow-x-auto">

            <table class="w-full text-sm">

                <!-- HEAD -->
                <thead class="bg-gray-50 border-b border-gray-200">

                    <tr class="text-gray-600">

                        <th class="px-4 py-3 text-left font-semibold">
                            Booking
                        </th>

                        <th class="px-4 py-3 text-left font-semibold">
                            Siswa
                        </th>

                        <th class="px-4 py-3 text-left font-semibold">
                            Kursus
                        </th>

                        <th class="px-4 py-3 text-left font-semibold">
                            Amount
                        </th>

                        <th class="px-4 py-3 text-left font-semibold">
                            Payment
                        </th>

                        <th class="px-4 py-3 text-left font-semibold">
                            Tanggal
                        </th>

                        <th class="px-4 py-3 text-right font-semibold">
                            Action
                        </th>

                    </tr>

                </thead>

                <!-- BODY -->
                <tbody class="divide-y divide-gray-100">

                    @forelse($bookings as $booking)

                    <tr class="hover:bg-gray-50">

                        <!-- BOOKING -->
                        <td class="px-4 py-3">

                            <div class="font-medium text-gray-800">
                                {{ $booking->code }}
                            </div>

                            <div class="text-xs text-gray-400">
                                #{{ substr($booking->id,0,8) }}
                            </div>

                        </td>

                        <!-- USER -->
                        <td class="px-4 py-3">

                            <div class="font-medium text-gray-800">
                                {{ $booking->user->name ?? '-' }}
                            </div>

                            <div class="text-xs text-gray-500">
                                {{ $booking->user->email ?? '-' }}
                            </div>

                        </td>

                        <!-- COURSE -->
                        <td class="px-4 py-3 text-gray-700">

                            {{ Str::limit($booking->schedule->course->title ?? '-', 35) }}

                        </td>

                        <!-- AMOUNT -->
                        <td class="px-4 py-3 font-medium text-gray-800">

                            Rp {{ number_format($booking->amount,0,',','.') }}

                        </td>

                        <!-- PAYMENT -->
                        <td class="px-4 py-3">

                            @php
                            $paymentColor = match($booking->payment_status){
                                'paid' => 'bg-green-100 text-green-700',
                                'pending' => 'bg-yellow-100 text-yellow-700',
                                default => 'bg-red-100 text-red-700'
                            };
                            @endphp

                            <span class="px-2 py-1 rounded text-xs font-medium {{ $paymentColor }}">
                                {{ ucfirst($booking->payment_status ?? 'unpaid') }}
                            </span>

                        </td>

                        <!-- DATE -->
                        <td class="px-4 py-3 text-gray-600">

                            {{ $booking->created_at->format('d M Y') }}

                        </td>

                        <!-- ACTION -->
                        <td class="px-4 py-3 text-right">

                            <a href="{{ route('admin.bookings.show', $booking) }}"
                               class="inline-flex items-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-md transition">

                                Detail

                            </a>

                        </td>

                    </tr>

                    @empty

                    <tr>

                        <td colspan="7" class="py-12 text-center text-gray-500">

                            Belum ada booking siswa

                        </td>

                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        <!-- PAGINATION -->
        @if($bookings->hasPages())

        <div class="px-4 py-3 border-t border-gray-200">

            {{ $bookings->links() }}

        </div>

        @endif

    </div>

</div>

@endsection