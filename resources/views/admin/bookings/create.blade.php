@extends('layouts.admin')

@section('header', 'Create New Booking')

@section('content')
    <div class="max-w-2xl">
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-800">Booking Information</h3>
                <p class="text-sm text-gray-500">Register a student for a course schedule</p>
            </div>
            
            <form action="{{ route('admin.bookings.store') }}" method="POST" class="p-6 space-y-6">
                @csrf
                
                <div>
                    <label for="user_id" class="block text-sm font-medium text-gray-700 mb-2">Select Student *</label>
                    <select name="user_id" id="user_id" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors">
                        <option value="">-- Choose a Student --</option>
                        @foreach($students as $student)
                            <option value="{{ $student->id }}" {{ old('user_id') == $student->id ? 'selected' : '' }}>
                                {{ $student->name }} ({{ $student->email }})
                            </option>
                        @endforeach
                    </select>
                    @error('user_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    
                    @if($students->isEmpty())
                        <p class="mt-2 text-sm text-yellow-600">
                            No students found. <a href="{{ route('admin.students.create') }}" class="underline">Add a student first</a>.
                        </p>
                    @endif
                </div>
                
                <div>
                    <label for="schedule_id" class="block text-sm font-medium text-gray-700 mb-2">Select Course Schedule *</label>
                    <select name="schedule_id" id="schedule_id" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors">
                        <option value="">-- Choose a Schedule --</option>
                        @foreach($schedules as $schedule)
                            <option value="{{ $schedule->id }}" {{ old('schedule_id') == $schedule->id ? 'selected' : '' }}>
                                {{ $schedule->course->title ?? 'Unknown Course' }} 
                                ({{ $schedule->start_date ? $schedule->start_date->format('d M Y') : 'TBD' }})
                                - Rp {{ number_format($schedule->course->price ?? 0, 0, ',', '.') }}
                            </option>
                        @endforeach
                    </select>
                    @error('schedule_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    
                    @if($schedules->isEmpty())
                        <p class="mt-2 text-sm text-yellow-600">
                            No course schedules found. Add a course with schedule first.
                        </p>
                    @endif
                </div>
                
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Initial Status *</label>
                    <select name="status" id="status" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors">
                        <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="paid" {{ old('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="flex items-center justify-end space-x-4 pt-4 border-t border-gray-100">
                    <a href="{{ route('admin.bookings.index') }}" 
                       class="px-6 py-2.5 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-6 py-2.5 bg-purple-600 hover:bg-purple-700 text-white font-medium rounded-lg transition-colors shadow-sm"
                            {{ $students->isEmpty() || $schedules->isEmpty() ? 'disabled' : '' }}>
                        Create Booking
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection