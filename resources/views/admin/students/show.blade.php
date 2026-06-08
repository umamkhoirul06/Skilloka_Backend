@extends('layouts.admin')

@section('header', 'Student Details')

@section('content')
    <div class="max-w-4xl mx-auto">
        {{-- CARD PROFIL --}}
        <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <h3 class="font-semibold text-gray-800">Student Profile</h3>
                <a href="{{ route('admin.students.edit', $student) }}"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700">Edit</a>
            </div>
            <div class="p-6">
                <div class="flex items-center mb-6">
                    <img class="w-20 h-20 rounded-full border-4 border-gray-200"
                        src="https://ui-avatars.com/api/?name={{ urlencode($student->name) }}&background=random&size=200"
                        alt="{{ $student->name }}">
                    <div class="ml-4">
                        <h2 class="text-2xl font-bold text-gray-800">{{ $student->name }}</h2>
                        <p class="text-gray-500">Email: {{ $student->email }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- 🔥 TABEL RIWAYAT KURSUS & STATUS BELAJAR --}}
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-800">Riwayat Kursus & Status</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-500">
                        <tr>
                            <th class="px-6 py-3">Kursus</th>
                            <th class="px-6 py-3">Status Belajar</th>
                            <th class="px-6 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($student->bookings as $booking)
                            <tr>
                                <td class="px-6 py-4 font-medium text-gray-800">{{ $booking->course->title ?? 'N/A' }}</td>
                                <td class="px-6 py-4">
                                    <span
                                        class="px-2 py-1 text-xs rounded-full 
                                            {{ $booking->status_belajar == 'lulus' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                        {{ str_replace('_', ' ', strtoupper($booking->status_belajar)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    {{-- FORM UPDATE STATUS --}}
                                    <form action="{{ route('admin.students.updateStatusBelajar', $booking->id) }}"
                                        method="POST">
                                        @csrf @method('PATCH')
                                        <select name="status_belajar" onchange="this.form.submit()"
                                            class="text-sm border-gray-300 rounded-md">
                                            <option value="sedang_belajar" {{ $booking->status_belajar == 'sedang_belajar' ? 'selected' : '' }}>Sedang Belajar</option>
                                            <option value="lulus" {{ $booking->status_belajar == 'lulus' ? 'selected' : '' }}>
                                                Lulus</option>
                                            <option value="magang" {{ $booking->status_belajar == 'magang' ? 'selected' : '' }}>
                                                Magang</option>
                                        </select>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-4 text-center text-gray-500">Belum ada riwayat kursus</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection