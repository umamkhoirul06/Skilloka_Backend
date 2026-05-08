@extends('layouts.admin')

@section('title', 'Create Booking')

@section('content')

<div class="max-w-6xl mx-auto space-y-6">

    <!-- HEADER -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

        <div>

            <h1 class="text-3xl font-bold text-gray-800">
                Create Booking
            </h1>

            <p class="text-sm text-gray-500 mt-1">
                Registrasikan siswa ke jadwal kursus secara manual
            </p>

        </div>

        <a href="{{ route('admin.bookings.index') }}"
           class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 transition">

            ← Kembali

        </a>

    </div>



    <!-- ERROR -->
    @if ($errors->any())

    <div class="rounded-2xl border border-red-200 bg-red-50 p-5">

        <div class="font-semibold text-red-700 mb-3">
            Terjadi Kesalahan
        </div>

        <ul class="space-y-1 text-sm text-red-600">

            @foreach ($errors->all() as $error)

                <li>
                    • {{ $error }}
                </li>

            @endforeach

        </ul>

    </div>

    @endif



    <!-- WARNING -->
    @if($students->isEmpty())

    <div class="rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-700">

        Belum ada student aktif di tenant ini.

    </div>

    @endif



    <!-- CONTENT -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- LEFT -->
        <div class="lg:col-span-2">

            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">

                <!-- TOP -->
                <div class="px-6 py-5 border-b border-gray-100 bg-gray-50">

                    <h3 class="text-lg font-semibold text-gray-800">
                        Booking Information
                    </h3>

                    <p class="text-sm text-gray-500 mt-1">
                        Isi data booking siswa dan pilih jadwal kursus
                    </p>

                </div>



                <!-- FORM -->
                <form
                    action="{{ route('admin.bookings.store') }}"
                    method="POST"
                    class="p-6 space-y-6">

                    @csrf



                    <!-- STUDENT -->
                    <div>

                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Select Student
                        </label>

                        <select
                            name="user_id"
                            required
                            class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100">

                            <option value="">
                                -- pilih student --
                            </option>

                            @foreach($students as $student)

                            <option
                                value="{{ $student->id }}"
                                {{ old('user_id') == $student->id ? 'selected' : '' }}>

                                {{ $student->name }}

                                @if($student->email)
                                    - {{ $student->email }}
                                @endif

                            </option>

                            @endforeach

                        </select>

                    </div>



                    <!-- SCHEDULE -->
                    <div>

                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Select Schedule
                        </label>

                        <select
                            name="schedule_id"
                            required
                            class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100">

                            <option value="">
                                -- pilih jadwal --
                            </option>

                            @foreach($schedules as $schedule)

                            <option
                                value="{{ $schedule->id }}"
                                {{ old('schedule_id') == $schedule->id ? 'selected' : '' }}>

                                {{ $schedule->course->title }}
                                |
                                {{ \Carbon\Carbon::parse($schedule->start_date)->format('d M Y') }}
                                -
                                {{ \Carbon\Carbon::parse($schedule->end_date)->format('d M Y') }}
                                |
                                {{ substr($schedule->daily_start,0,5) }}
                                -
                                {{ substr($schedule->daily_end,0,5) }}
                                |
                                Rp {{ number_format($schedule->course->price,0,',','.') }}

                            </option>

                            @endforeach

                        </select>

                    </div>



                    <!-- STATUS -->
                    <div>

                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Booking Status
                        </label>

                        <select
                            name="status"
                            class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100">

                            <option value="pending">
                                Pending
                            </option>

                            <option value="paid">
                                Paid
                            </option>

                            <option value="cancelled">
                                Cancelled
                            </option>

                            <option value="completed">
                                Completed
                            </option>

                        </select>

                    </div>



                    <!-- NOTES -->
                    <div>

                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Notes (Optional)
                        </label>

                        <textarea
                            name="notes"
                            rows="4"
                            placeholder="Tambahkan catatan booking..."
                            class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm resize-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">{{ old('notes') }}</textarea>

                    </div>



                    <!-- ACTION -->
                    <div class="flex items-center justify-end gap-3 pt-5 border-t border-gray-100">

                        <a href="{{ route('admin.bookings.index') }}"
                           class="px-5 py-2.5 rounded-xl border border-gray-300 text-sm text-gray-700 hover:bg-gray-50 transition">

                            Cancel

                        </a>

                        <button
                            type="submit"
                            class="px-6 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium transition shadow-sm">

                            Create Booking

                        </button>

                    </div>

                </form>

            </div>

        </div>



        <!-- RIGHT -->
        <div class="space-y-6">

            <!-- BOOKING FLOW -->
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">

                <h3 class="text-lg font-semibold text-gray-800 mb-6">
                    Booking Flow
                </h3>

                <div class="space-y-6">

                    <div class="flex items-start gap-4">

                        <div class="w-9 h-9 rounded-full bg-gray-900 text-white flex items-center justify-center text-sm font-semibold">
                            1
                        </div>

                        <div>

                            <p class="font-semibold text-gray-800">
                                Booking Dibuat
                            </p>

                            <p class="text-sm text-gray-500 mt-1">
                                Admin membuat booking manual untuk siswa
                            </p>

                        </div>

                    </div>

                    <div class="flex items-start gap-4">

                        <div class="w-9 h-9 rounded-full bg-yellow-500 text-white flex items-center justify-center text-sm font-semibold">
                            2
                        </div>

                        <div>

                            <p class="font-semibold text-gray-800">
                                Payment Pending
                            </p>

                            <p class="text-sm text-gray-500 mt-1">
                                Payment otomatis dibuat dengan status pending
                            </p>

                        </div>

                    </div>

                    <div class="flex items-start gap-4">

                        <div class="w-9 h-9 rounded-full bg-green-600 text-white flex items-center justify-center text-sm font-semibold">
                            3
                        </div>

                        <div>

                            <p class="font-semibold text-gray-800">
                                Verifikasi Payment
                            </p>

                            <p class="text-sm text-gray-500 mt-1">
                                Admin dapat approve atau reject pembayaran
                            </p>

                        </div>

                    </div>

                </div>

            </div>



            <!-- PAYMENT INFO -->
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">

                <h3 class="text-lg font-semibold text-gray-800 mb-5">
                    Payment Information
                </h3>

                <div class="space-y-4 text-sm">

                    <div class="flex items-center justify-between">

                        <span class="text-gray-500">
                            Source
                        </span>

                        <span class="font-medium text-gray-800">
                            Admin Booking
                        </span>

                    </div>

                    <div class="flex items-center justify-between">

                        <span class="text-gray-500">
                            Payment Status
                        </span>

                        <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs font-semibold">
                            Unpaid
                        </span>

                    </div>

                    <div class="flex items-center justify-between">

                        <span class="text-gray-500">
                            Payment Method
                        </span>

                        <span class="font-medium text-gray-800">
                            Manual
                        </span>

                    </div>

                    <div class="flex items-center justify-between">

                        <span class="text-gray-500">
                            Expires
                        </span>

                        <span class="font-medium text-gray-800">
                            24 Hours
                        </span>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection