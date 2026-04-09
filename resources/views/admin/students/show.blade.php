@extends('layouts.admin')

@section('header', 'Student Details')

@section('content')
    <div class="max-w-2xl">
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <div>
                    <h3 class="font-semibold text-gray-800">Student Profile</h3>
                    <p class="text-sm text-gray-500">Detailed information about this student</p>
                </div>
                <a href="{{ route('admin.students.edit', $student) }}"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors text-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                        </path>
                    </svg>
                    Edit
                </a>
            </div>

            <div class="p-6">
                <div class="flex items-center mb-6">
                    <img class="w-20 h-20 rounded-full object-cover border-4 border-gray-200"
                        src="https://ui-avatars.com/api/?name={{ urlencode($student->name) }}&background=random&size=200"
                        alt="{{ $student->name }}">
                    <div class="ml-4">
                        <h2 class="text-2xl font-bold text-gray-800">{{ $student->name }}</h2>
                        <p class="text-gray-500">Student ID: {{ $student->id }}</p>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="p-4 bg-gray-50 rounded-lg">
                            <p class="text-sm text-gray-500 mb-1">Email Address</p>
                            <p class="font-medium text-gray-800">{{ $student->email }}</p>
                        </div>
                        <div class="p-4 bg-gray-50 rounded-lg">
                            <p class="text-sm text-gray-500 mb-1">Phone Number</p>
                            <p class="font-medium text-gray-800">{{ $student->phone ?? 'Not provided' }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="p-4 bg-gray-50 rounded-lg">
                            <p class="text-sm text-gray-500 mb-1">Registered On</p>
                            <p class="font-medium text-gray-800">{{ $student->created_at->format('d M Y, H:i') }}</p>
                        </div>
                        <div class="p-4 bg-gray-50 rounded-lg">
                            <p class="text-sm text-gray-500 mb-1">Last Updated</p>
                            <p class="font-medium text-gray-800">{{ $student->updated_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 border-t border-gray-100">
                <a href="{{ route('admin.students.index') }}"
                    class="inline-flex items-center text-gray-600 hover:text-gray-800">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Students List
                </a>
            </div>
        </div>
    </div>
@endsection