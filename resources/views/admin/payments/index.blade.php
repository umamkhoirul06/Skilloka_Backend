@extends('layouts.admin')

@section('title', 'Payments')

@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- HEADER -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Payment Management</h2>
            <p class="text-sm text-gray-500 mt-1">Kelola seluruh pembayaran booking siswa.</p>
        </div>
    </div>

    <!-- ALERT -->
    @if(session('success'))
        <div class="mb-6 rounded-lg bg-emerald-50 p-4 border border-emerald-100 flex items-center">
            <svg class="h-5 w-5 text-emerald-400 mr-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
            <p class="text-sm font-medium text-emerald-800">{{ session('success') }}</p>
        </div>
    @endif

    <!-- TABLE CARD -->
    <div class="bg-white/80 backdrop-blur-sm rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50/50">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Payment</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Student</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Course</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Amount</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Method</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Proof</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($payments as $payment)
                        <tr class="hover:bg-gray-50/50 transition-colors duration-150">
                            <!-- PAYMENT -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-semibold text-gray-900">{{ $payment->booking->code ?? '-' }}</div>
                                <div class="text-xs text-gray-500 mt-0.5">{{ $payment->created_at->format('d M Y H:i') }}</div>
                            </td>
                            <!-- STUDENT -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <img class="h-10 w-10 rounded-full border border-gray-200 shadow-sm" src="https://ui-avatars.com/api/?name={{ urlencode($payment->booking->user->name ?? 'U') }}&background=random" alt="Avatar">
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-semibold text-gray-900">{{ $payment->booking->user->name ?? '-' }}</div>
                                        <div class="text-xs text-gray-500 mt-0.5">{{ $payment->booking->user->email ?? '-' }}</div>
                                    </div>
                                </div>
                            </td>
                            <!-- COURSE -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-700">{{ Str::limit($payment->booking->schedule->course->title ?? '-', 25) }}</div>
                            </td>
                            <!-- AMOUNT -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-semibold text-gray-900">Rp {{ number_format($payment->amount,0,',','.') }}</div>
                            </td>
                            <!-- METHOD -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2.5 py-1 rounded-md bg-gray-100 text-gray-700 text-xs font-semibold border border-gray-200">
                                    {{ ucfirst($payment->method) }}
                                </span>
                            </td>
                            <!-- PROOF -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($payment->proof)
                                    <a href="{{ asset('storage/'.$payment->proof) }}" target="_blank" class="inline-flex items-center text-indigo-600 hover:text-indigo-800 text-sm font-medium transition-colors">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        Lihat
                                    </a>
                                @else
                                    <span class="text-gray-400 text-xs">Belum upload</span>
                                @endif
                            </td>
                            <!-- STATUS -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($payment->status === 'success')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                                        <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full mr-1.5"></span>
                                        Success
                                    </span>
                                @elseif($payment->status === 'pending')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                        <span class="w-1.5 h-1.5 bg-amber-500 rounded-full mr-1.5"></span>
                                        Pending
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-rose-100 text-rose-800">
                                        <span class="w-1.5 h-1.5 bg-rose-500 rounded-full mr-1.5"></span>
                                        Failed
                                    </span>
                                @endif
                            </td>
                            <!-- ACTION -->
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.payments.show', $payment) }}" class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors" title="Detail">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    </a>
                                    
                                    <!-- Alpine Dropdown for Verify -->
                                    <div class="relative" x-data="{ open: false }">
                                        <button @click="open = !open" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none transition-colors">
                                            Verify
                                            <svg class="ml-1.5 -mr-0.5 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                        </button>
                                        <div x-show="open" @click.away="open = false" style="display:none;" class="absolute right-0 mt-2 w-36 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50">
                                            <div class="py-1" role="menu" aria-orientation="vertical" aria-labelledby="options-menu">
                                                @foreach(['pending','success','failed'] as $status)
                                                <form action="{{ route('admin.payments.status', $payment) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="{{ $status }}">
                                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 capitalize" role="menuitem">
                                                        {{ $status }}
                                                    </button>
                                                </form>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-500">
                                    <svg class="w-12 h-12 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                                    <p class="text-base font-medium text-gray-900">Belum ada payment</p>
                                    <p class="text-sm mt-1">Belum ada data pembayaran masuk.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($payments->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                {{ $payments->links() }}
            </div>
        @endif
    </div>
</div>

@endsection