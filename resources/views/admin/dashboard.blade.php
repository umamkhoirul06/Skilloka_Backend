@extends('layouts.admin')

@section('header', 'Dashboard Overview')

@section('content')

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

    <!-- total siswa -->
    <div class="bg-white rounded-lg shadow p-5 border-l-4 border-blue-500">
        <div class="flex items-center">

            <div class="flex-shrink-0 bg-blue-100 p-3 rounded-full">

                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                    </path>

                </svg>

            </div>

            <div class="ml-4">

                <h4 class="text-gray-500 text-sm font-medium">
                    Total Students
                </h4>

                <p class="text-2xl font-bold text-gray-800">

                    {{ $totalStudents }}

                </p>

            </div>

        </div>
    </div>



    <!-- total kursus -->
    <div class="bg-white rounded-lg shadow p-5 border-l-4 border-green-500">
        <div class="flex items-center">

            <div class="flex-shrink-0 bg-green-100 p-3 rounded-full">

                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                    </path>

                </svg>

            </div>

            <div class="ml-4">

                <h4 class="text-gray-500 text-sm font-medium">
                    Total Courses
                </h4>

                <p class="text-2xl font-bold text-gray-800">

                    {{ $totalCourses }}

                </p>

            </div>

        </div>
    </div>



    <!-- jadwal -->
    <div class="bg-white rounded-lg shadow p-5 border-l-4 border-purple-500">
        <div class="flex items-center">

            <div class="flex-shrink-0 bg-purple-100 p-3 rounded-full">

                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                    </path>

                </svg>

            </div>

            <div class="ml-4">

                <h4 class="text-gray-500 text-sm font-medium">
                    Upcoming Classes
                </h4>

                <p class="text-2xl font-bold text-gray-800">

                    {{ $upcomingClasses }}

                </p>

            </div>

        </div>
    </div>



    <!-- pending -->
    <div class="bg-white rounded-lg shadow p-5 border-l-4 border-orange-500">
        <div class="flex items-center">

            <div class="flex-shrink-0 bg-orange-100 p-3 rounded-full">

                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M5 13l4 4L19 7"></path>

                </svg>

            </div>

            <div class="ml-4">

                <h4 class="text-gray-500 text-sm font-medium">
                    Pending Bookings
                </h4>

                <p class="text-2xl font-bold text-gray-800">

                    {{ $pendingBookings }}

                </p>

            </div>

        </div>
    </div>

</div>



<!-- quick actions -->
<div class="bg-white rounded-lg shadow mb-8">

    <div class="px-6 py-4 border-b">

        <h3 class="font-semibold">
            Quick Actions
        </h3>

    </div>


    <div class="p-6 grid grid-cols-2 gap-4">

        <a href="{{ route('admin.courses.create') }}"
           class="flex flex-col items-center justify-center p-4 bg-blue-50 hover:bg-blue-100 rounded-lg">

            Tambah Kursus

        </a>



        <a href="{{ route('admin.bookings.index') }}"
           class="flex flex-col items-center justify-center p-4 bg-green-50 hover:bg-green-100 rounded-lg">

            Lihat Booking

        </a>

    </div>

</div>



<!-- recent booking -->
<div class="bg-white rounded-lg shadow">

    <div class="px-6 py-4 border-b">

        <h3 class="font-semibold">
            Recent Bookings
        </h3>

    </div>


    <table class="w-full">

        <thead>

            <tr class="border-b">

                <th class="p-3 text-left">
                    Booking ID
                </th>

                <th class="p-3 text-left">
                    Status
                </th>

            </tr>

        </thead>


        <tbody>

        @foreach($recentBookings as $booking)

            <tr class="border-b">

                <td class="p-3">

                    {{ $booking->id }}

                </td>

                <td class="p-3">

                    {{ $booking->status }}

                </td>

            </tr>

        @endforeach

        </tbody>

    </table>

</div>

@endsection