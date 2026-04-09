@extends('layouts.admin')

@section('header', 'Add New Course')

@section('content')
    <div class="max-w-2xl">
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-800">Course Information</h3>
                <p class="text-sm text-gray-500">Fill in the details of your new training course</p>
            </div>

            <form action="{{ route('admin.courses.store') }}" method="POST" class="p-6 space-y-6">
                @csrf

                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Course Title *</label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="e.g., Welding Certification Basic">

                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>


                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">Category *</label>

                    <select name="category_id" id="category_id" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">

                        <option value="">-- Select Category --</option>

                        @foreach($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach

                    </select>

                    @error('category_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>


                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>

                    <textarea name="description" id="description" rows="4"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Describe what students will learn in this course...">{{ old('description') }}</textarea>

                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>


                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700 mb-2">Price (Rp) *</label>

                        <input type="number" name="price" id="price" value="{{ old('price') }}" required min="0"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="1500000">

                        @error('price')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>


                    <div>
                        <label for="duration_hours" class="block text-sm font-medium text-gray-700 mb-2">
                            Duration (Hours) *
                        </label>

                        <input type="number" name="duration_hours" id="duration_hours"
                            value="{{ old('duration_hours') }}" required min="1"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="40">

                        @error('duration_hours')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                </div>


                <div>
                    <label for="level" class="block text-sm font-medium text-gray-700 mb-2">
                        Course Level *
                    </label>

                    <select name="level" id="level" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">

                        <option value="">-- Select Level --</option>

                        <option value="beginner"
                            {{ old('level')=='beginner' ? 'selected' : '' }}>
                            Beginner
                        </option>

                        <option value="intermediate"
                            {{ old('level')=='intermediate' ? 'selected' : '' }}>
                            Intermediate
                        </option>

                        <option value="advanced"
                            {{ old('level')=='advanced' ? 'selected' : '' }}>
                            Advanced
                        </option>

                    </select>

                    @error('level')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>


                <div>
                    <label for="max_participants" class="block text-sm font-medium text-gray-700 mb-2">
                        Maximum Participants *
                    </label>

                    <input type="number" name="max_participants" id="max_participants"
                        value="{{ old('max_participants', 20) }}" required min="1"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="20">

                    @error('max_participants')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>


                <div class="flex items-center justify-end space-x-4 pt-4 border-t border-gray-100">

                    <a href="{{ route('admin.courses.index') }}"
                        class="px-6 py-2.5 text-gray-700 hover:bg-gray-100 rounded-lg">
                        Cancel
                    </a>

                    <button type="submit"
                        class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg">
                        Create Course
                    </button>

                </div>

            </form>
        </div>
    </div>
@endsection