@extends('layouts.admin')

@section('header', 'Edit Course')

@section('content')
<div class="max-w-2xl">

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">

        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="font-semibold text-gray-800">
                Edit Course Information
            </h3>

            <p class="text-sm text-gray-500">
                Update the details of this training course
            </p>
        </div>


        {{-- ERROR VALIDATION --}}
        @if ($errors->any())
        <div class="mx-6 mt-4 p-4 bg-red-50 border border-red-200 rounded-lg">
            <ul class="text-sm text-red-600 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>• {{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif


        <form
            action="{{ route('admin.courses.update', $course) }}"
            method="POST"
            class="p-6 space-y-6"
        >

            @csrf
            @method('PUT')


            {{-- TITLE --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Course Title *
                </label>

                <input
                    type="text"
                    name="title"
                    value="{{ old('title',$course->title) }}"
                    required
                    class="w-full px-4 py-3 border rounded-lg"
                >
            </div>


            {{-- CATEGORY --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Category *
                </label>

                <select
                    name="category_id"
                    required
                    class="w-full px-4 py-3 border rounded-lg"
                >

                    <option value="">
                        -- Select Category --
                    </option>

                    @foreach($categories as $category)

                        <option
                            value="{{ $category->id }}"
                            {{ old('category_id',$course->category_id)==$category->id?'selected':'' }}
                        >
                            {{ $category->name }}
                        </option>

                    @endforeach

                </select>
            </div>


            {{-- LEVEL (WAJIB ADA) --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Level *
                </label>

                <select
                    name="level"
                    required
                    class="w-full px-4 py-3 border rounded-lg"
                >

                    <option value="">
                        -- Select Level --
                    </option>

                    <option
                        value="beginner"
                        {{ old('level',$course->level)=='beginner'?'selected':'' }}
                    >
                        Beginner
                    </option>

                    <option
                        value="intermediate"
                        {{ old('level',$course->level)=='intermediate'?'selected':'' }}
                    >
                        Intermediate
                    </option>

                    <option
                        value="advanced"
                        {{ old('level',$course->level)=='advanced'?'selected':'' }}
                    >
                        Advanced
                    </option>

                </select>
            </div>


            {{-- DESCRIPTION --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Description
                </label>

                <textarea
                    name="description"
                    rows="4"
                    class="w-full px-4 py-3 border rounded-lg"
                >{{ old('description',$course->description) }}</textarea>
            </div>



            {{-- PRICE & DURATION --}}
            <div class="grid grid-cols-2 gap-6">

                <div>

                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Price *
                    </label>

                    <input
                        type="number"
                        name="price"
                        value="{{ old('price',$course->price) }}"
                        required
                        min="0"
                        class="w-full px-4 py-3 border rounded-lg"
                    >

                </div>


                <div>

                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Duration Hours *
                    </label>

                    <input
                        type="number"
                        name="duration_hours"
                        value="{{ old('duration_hours',$course->duration_hours) }}"
                        required
                        min="1"
                        class="w-full px-4 py-3 border rounded-lg"
                    >

                </div>

            </div>



            {{-- MAX PARTICIPANTS --}}
            <div>

                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Maximum Participants *
                </label>

                <input
                    type="number"
                    name="max_participants"
                    value="{{ old('max_participants',$course->max_participants) }}"
                    required
                    min="1"
                    class="w-full px-4 py-3 border rounded-lg"
                >

            </div>



            {{-- ACTIVE --}}
            <div class="flex items-center">

                <input
                    type="hidden"
                    name="is_active"
                    value="0"
                >

                <input
                    type="checkbox"
                    name="is_active"
                    value="1"

                    {{ old('is_active',$course->is_active)?'checked':'' }}
                >

                <span class="ml-2">
                    Active course
                </span>

            </div>



            {{-- BUTTON --}}
            <div class="flex justify-end gap-3 pt-4 border-t">

                <a
                    href="{{ route('admin.courses.index') }}"
                    class="px-5 py-2 border rounded-lg"
                >
                    Cancel
                </a>


                <button
                    type="submit"
                    class="px-6 py-2 bg-blue-600 text-white rounded-lg"
                >
                    Update Course
                </button>

            </div>

        </form>

    </div>

</div>
@endsection