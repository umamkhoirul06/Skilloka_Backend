@extends('layouts.admin')

@section('header', 'Manage Bookings')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <p class="text-gray-600">Kelola semua pendaftaran kursus</p>
        <a href="{{ route('admin.bookings.create') }}" 
           class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-medium rounded-lg transition-colors shadow-sm">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            New Booking
        </a>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-100 border border-green-200 text-green-700 rounded-lg flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50 text-xs uppercase font-medium text-gray-500">
                    <tr>
                        <th class="px-6 py-4">Booking Code</th>
                        <th class="px-6 py-4">Student</th>
                        <th class="px-6 py-4">Course</th>
                        <th class="px-6 py-4">Amount</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Booked At</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($bookings as $booking)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <span class="font-mono text-sm font-semibold text-purple-600">{{ $booking->code }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <img class="w-10 h-10 rounded-full object-cover border-2 border-gray-200" 
                                         src="https://ui-avatars.com/api/?name={{ urlencode($booking->user->name ?? 'Unknown') }}&background=random" 
                                         alt="">
                                    <div class="ml-3">
                                        <p class="font-semibold text-gray-800">{{ $booking->user->name ?? 'Unknown' }}</p>
                                        <p class="text-sm text-gray-500">{{ $booking->user->email ?? '-' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-medium text-gray-800">{{ $booking->schedule->course->title ?? 'Unknown Course' }}</p>
                                <p class="text-sm text-gray-500">
                                    {{ $booking->schedule ? $booking->schedule->start_date->format('d M Y') : '-' }}
                                </p>
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-semibold text-gray-800">Rp {{ number_format($booking->amount ?? 0, 0, ',', '.') }}</span>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $statusColors = [
                                        'pending' => 'bg-yellow-100 text-yellow-700',
                                        'paid' => 'bg-green-100 text-green-700',
                                        'cancelled' => 'bg-red-100 text-red-700',
                                        'completed' => 'bg-blue-100 text-blue-700',
                                    ];
                                @endphp
                                <span class="inline-flex px-2.5 py-1 text-xs font-semibold rounded-full {{ $statusColors[$booking->status] ?? 'bg-gray-100 text-gray-700' }}">
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-600">{{ $booking->created_at->format('d M Y, H:i') }}</td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end space-x-2">
                                    <!-- Status Update Dropdown -->
                                    <div x-data="{ open: false }" class="relative">
                                        <button @click="open = !open" 
                                                class="p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors"
                                                title="Update Status">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                                            </svg>
                                        </button>
                                        <div x-show="open" @click.away="open = false"
                                             class="absolute right-0 mt-2 w-40 bg-white rounded-lg shadow-lg border border-gray-100 py-1 z-50"
                                             style="display: none;">
                                            @foreach(['pending', 'paid', 'cancelled', 'completed'] as $status)
                                                <form action="{{ route('admin.bookings.status', $booking) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="{{ $status }}">
                                                    <button type="submit" 
                                                            class="w-full text-left px-4 py-2 text-sm hover:bg-gray-50 {{ $booking->status === $status ? 'bg-gray-50 font-semibold' : '' }}">
                                                        {{ ucfirst($status) }}
                                                    </button>
                                                </form>
                                            @endforeach
                                        </div>
                                    </div>
                                    
                                    <form action="{{ route('admin.bookings.destroy', $booking) }}" method="POST" class="inline"
                                          onsubmit="return confirm('Are you sure you want to delete this booking?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                                title="Delete">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mb-4">
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <p class="text-gray-500 mb-4">Belum ada booking.</p>
                                    <a href="{{ route('admin.bookings.create') }}" 
                                       class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-medium rounded-lg transition-colors">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                        Create First Booking
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($bookings->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $bookings->links() }}
            </div>
        @endif
    </div>
@endsection