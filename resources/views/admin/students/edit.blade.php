@extends('layouts.admin')

@section('header', 'Edit Student')

@section('content')

<div class="max-w-3xl">

    <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-100">

        <!-- header -->
        <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">

            <div>

                <h3 class="text-lg font-semibold text-gray-800">
                    Edit Data Siswa
                </h3>

                <p class="text-sm text-gray-500 mt-1">
                    Perbarui informasi student
                </p>

            </div>

            <div class="text-right">

                <p class="text-xs text-gray-400">
                    Total Booking
                </p>

                <p class="text-lg font-bold text-green-600">
                    {{ $student->bookings()->count() }}
                </p>

            </div>

        </div>



        <!-- form -->
        <form
            action="{{ route('admin.students.update', $student) }}"
            method="POST"
            class="p-6 space-y-6">

            @csrf
            @method('PUT')



            <!-- nama -->
            <div>

                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Nama Lengkap
                </label>

                <input
                    type="text"
                    name="name"
                    value="{{ old('name', $student->name) }}"
                    required
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500">

                @error('name')

                    <p class="mt-1 text-sm text-red-500">
                        {{ $message }}
                    </p>

                @enderror

            </div>



            <!-- email -->
            <div>

                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Email
                </label>

                <input
                    type="email"
                    name="email"
                    value="{{ old('email', $student->email) }}"
                    required
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500">

                @error('email')

                    <p class="mt-1 text-sm text-red-500">
                        {{ $message }}
                    </p>

                @enderror

            </div>



            <!-- phone -->
            <div>

                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Nomor HP
                </label>

                <input
                    type="text"
                    name="phone"
                    value="{{ old('phone', $student->phone) }}"
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500">

                @error('phone')

                    <p class="mt-1 text-sm text-red-500">
                        {{ $message }}
                    </p>

                @enderror

            </div>



            <!-- password -->
            <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-5">

                <p class="text-sm text-yellow-700 mb-4">
                    Kosongkan password jika tidak ingin mengganti password student.
                </p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    <div>

                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Password Baru
                        </label>

                        <input
                            type="password"
                            name="password"
                            id="password"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                            placeholder="Minimal 8 karakter">

                    </div>



                    <div>

                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Konfirmasi Password
                        </label>

                        <input
                            type="password"
                            name="password_confirmation"
                            id="password_confirmation"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                            placeholder="Ulangi password">

                    </div>

                </div>

                @error('password')

                    <p class="mt-3 text-sm text-red-500">
                        {{ $message }}
                    </p>

                @enderror

            </div>



            <!-- action -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">

                <a
                    href="{{ route('admin.students.show', $student) }}"
                    class="px-5 py-2.5 rounded-xl border border-gray-300 text-gray-700 hover:bg-gray-50">

                    Kembali

                </a>



                <button
                    type="submit"
                    class="px-6 py-2.5 bg-purple-600 hover:bg-purple-700 text-white rounded-xl shadow-sm">

                    Simpan Perubahan

                </button>

            </div>

        </form>

    </div>

</div>

@endsection