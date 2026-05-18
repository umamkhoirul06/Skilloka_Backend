{{-- resources/views/admin/courses/edit.blade.php --}}

@extends('layouts.admin')

@section('title','Edit Course')

@section('content')

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-100">

        <div class="flex items-center justify-between mb-6 border-b border-gray-100 pb-4">
            <h2 class="text-xl font-semibold text-gray-800">
                Edit Course: {{ $course->title }}
            </h2>
            <a href="{{ route('admin.courses.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                &larr; Kembali ke Daftar
            </a>
        </div>

        {{-- ERROR VALIDATION --}}
        @if ($errors->any())
            <div class="bg-rose-50 text-rose-800 p-4 rounded-lg mb-6 border border-rose-100">
                <ul class="list-disc pl-5 m-0 space-y-1 text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- SUCCESS --}}
        @if(session('success'))
            <div class="bg-emerald-50 text-emerald-800 p-4 rounded-lg mb-6 border border-emerald-100">
                <p class="text-sm font-medium">{{ session('success') }}</p>
            </div>
        @endif

        {{-- FORM --}}
        <form method="POST"
              action="{{ route('admin.courses.update', $course->id) }}"
              enctype="multipart/form-data" class="space-y-6">

            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- TITLE --}}
                <div class="col-span-1 md:col-span-2">
                    <label class="block text-sm font-medium text-gray-900 mb-1">Title <span class="text-rose-500">*</span></label>
                    <input type="text"
                           name="title"
                           value="{{ old('title', $course->title) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                           required>
                </div>

                {{-- CATEGORY --}}
                <div>
                    <label class="block text-sm font-medium text-gray-900 mb-1">Category <span class="text-rose-500">*</span></label>
                    <select name="category_id"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                            required>
                        <option value="">-- Pilih Category --</option>
                        @foreach($categories ?? [] as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $course->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- LEVEL --}}
                <div>
                    <label class="block text-sm font-medium text-gray-900 mb-1">Level <span class="text-rose-500">*</span></label>
                    <select name="level"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                        <option value="beginner" {{ old('level', $course->level) == 'beginner' ? 'selected' : '' }}>Beginner</option>
                        <option value="intermediate" {{ old('level', $course->level) == 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                        <option value="advanced" {{ old('level', $course->level) == 'advanced' ? 'selected' : '' }}>Advanced</option>
                    </select>
                </div>

                {{-- PRICE --}}
                <div>
                    <label class="block text-sm font-medium text-gray-900 mb-1">Price (Rp) <span class="text-rose-500">*</span></label>
                    <input type="number"
                           name="price"
                           value="{{ old('price', (int)$course->price) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                           required>
                </div>

                {{-- DURATION --}}
                <div>
                    <label class="block text-sm font-medium text-gray-900 mb-1">Duration Hours <span class="text-rose-500">*</span></label>
                    <input type="number"
                           name="duration_hours"
                           value="{{ old('duration_hours', $course->duration_hours) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                           required>
                </div>

                {{-- CERT TYPE --}}
                <div>
                    <label class="block text-sm font-medium text-gray-900 mb-1">Certificate Type</label>
                    <input type="text"
                           name="cert_type"
                           value="{{ old('cert_type', $course->cert_type) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                </div>

                {{-- MAX PARTICIPANTS --}}
                <div>
                    <label class="block text-sm font-medium text-gray-900 mb-1">Max Participants</label>
                    <input type="number"
                           name="max_participants"
                           value="{{ old('max_participants', $course->max_participants) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                </div>

                {{-- DESCRIPTION --}}
                <div class="col-span-1 md:col-span-2">
                    <label class="block text-sm font-medium text-gray-900 mb-1">Description</label>
                    <textarea name="description"
                              rows="4"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">{{ old('description', $course->description) }}</textarea>
                </div>

                {{-- SYLLABUS --}}
                <div class="col-span-1 md:col-span-2">
                    <label class="block text-sm font-medium text-gray-900 mb-1">Syllabus</label>
                    <textarea name="syllabus"
                              rows="4"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">{{ old('syllabus', $course->syllabus) }}</textarea>
                </div>
                
                {{-- IS ACTIVE --}}
                <div class="col-span-1 md:col-span-2 mt-2">
                    <label class="flex items-center gap-3 bg-gray-50 p-4 rounded-lg border border-gray-200 cursor-pointer">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $course->is_active) ? 'checked' : '' }} class="w-5 h-5 text-indigo-600 rounded focus:ring-indigo-500 border-gray-300">
                        <span class="text-sm font-medium text-gray-800">Course Aktif (Tampil di Mobile App)</span>
                    </label>
                </div>

                {{-- FACILITIES --}}
                <div class="col-span-1 md:col-span-2">
                    <label class="block text-sm font-medium text-gray-900 mb-3">Facilities</label>
                    
                    @php
                        $availableFacilities = ['Ruang Ber-AC', 'Modul Belajar', 'WiFi', 'Sertifikat', 'Makan Siang'];
                        $selectedFacilities = old('facilities', $course->facilities ?? []);
                    @endphp
                    
                    <div class="flex flex-wrap gap-4">
                        @foreach($availableFacilities as $facility)
                        <label class="flex items-center gap-2 bg-white px-4 py-2 rounded-full border border-gray-200 cursor-pointer hover:bg-gray-50 transition-colors">
                            <input type="checkbox" name="facilities[]" value="{{ $facility }}" {{ in_array($facility, $selectedFacilities) ? 'checked' : '' }} class="text-indigo-600 rounded focus:ring-indigo-500 border-gray-300">
                            <span class="text-sm text-gray-700">{{ $facility }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                {{-- IMAGE UPLOAD --}}
                <div class="col-span-1 md:col-span-2">
                    <label class="block text-sm font-medium text-gray-900 mb-2">Course Images</label>
                    
                    @if(!empty($course->images) && is_array($course->images) && count($course->images) > 0)
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 mb-4">
                            <p class="text-xs text-gray-500 mb-3">Gambar saat ini:</p>
                            <div class="flex flex-wrap gap-4">
                                @foreach($course->images as $img)
                                <div class="relative group rounded-lg overflow-hidden border border-gray-200 shadow-sm">
                                    <img src="{{ asset('storage/'.$img) }}" class="w-24 h-24 object-cover" alt="Course Image">
                                </div>
                                @endforeach
                            </div>
                            <p class="text-xs text-rose-500 mt-3 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Info: Mengunggah gambar baru akan menimpa semua gambar yang sudah ada. Biarkan kosong jika tidak ingin mengubah.
                            </p>
                        </div>
                    @endif
                    
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-indigo-500 transition-colors bg-gray-50">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-600 justify-center">
                                <label for="images" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500 px-2 py-1">
                                    <span>Upload file gambar</span>
                                    <input id="images" name="images[]" type="file" multiple accept="image/*" class="sr-only">
                                </label>
                                <p class="pl-1 pt-1">atau drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500">
                                PNG, JPG, JPEG sampai 5MB (Bisa pilih multiple file)
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- BUTTON --}}
            <div class="flex justify-end pt-6 border-t border-gray-100 mt-8">
                <button type="submit"
                        class="inline-flex justify-center py-2.5 px-6 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                    Simpan Perubahan
                </button>
            </div>

        </form>

    </div>

</div>

@endsection