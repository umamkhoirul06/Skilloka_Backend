@extends('layouts.admin')

@section('header', 'Manage Students')

@section('content')

<div class="mb-6 flex justify-between items-center">
    <p class="text-gray-600">
        Kelola data siswa dari booking
    </p>

    <a href="{{ route('admin.bookings.index') }}"
        class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition shadow-sm">
        Lihat Booking
    </a>
</div>

@if(session('success'))
    <div class="mb-6 p-4 bg-green-100 border border-green-200 text-green-700 rounded-lg">
        {{ session('success') }}
    </div>
@endif

<div class="bg-white rounded-xl shadow-sm">

    <div class="overflow-x-auto">

        <table class="w-full text-left">

            <thead class="bg-gray-50 text-xs uppercase font-medium text-gray-500">
                <tr>
                    <th class="px-6 py-4">Student</th>
                    <th class="px-6 py-4">Email</th>
                    <th class="px-6 py-4">Phone</th>
                    <th class="px-6 py-4">Booking</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-100">

                @forelse($students as $student)

                    <tr class="hover:bg-gray-50 transition">

                        <td class="px-6 py-4">

                            <div class="flex items-center">

                                <img
                                    class="w-10 h-10 rounded-full border"
                                    src="https://ui-avatars.com/api/?name={{ urlencode($student->name) }}&background=random"
                                >

                                <div class="ml-3">

                                    <p class="font-semibold text-gray-800">
                                        {{ $student->name }}
                                    </p>

                                    <p class="text-xs text-gray-500">
                                        ID: {{ $student->id }}
                                    </p>

                                </div>

                            </div>

                        </td>

                        <td class="px-6 py-4">
                            {{ $student->email }}
                        </td>

                        <td class="px-6 py-4">
                            {{ $student->phone ?? $student->phone_number ?? '-' }}
                        </td>

                        <td class="px-6 py-4">

                            <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs">
                                {{ $student->bookings_count }} booking
                            </span>

                        </td>

                        <td class="px-6 py-4 text-right">

                            <div class="flex justify-end gap-2">

                                <a href="{{ route('admin.students.show', $student) }}"
                                    class="px-3 py-1 text-xs bg-gray-100 hover:bg-gray-200 rounded">
                                    Detail
                                </a>

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="5"
                            class="text-center py-12 text-gray-500">

                            Belum ada student (belum ada booking)

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    @if($students->hasPages())

        <div class="p-4">
            {{ $students->links() }}
        </div>

    @endif

</div>

@endsection