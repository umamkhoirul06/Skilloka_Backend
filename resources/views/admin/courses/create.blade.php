{{-- resources/views/admin/courses/create.blade.php --}}

@extends('layouts.admin')

@section('title','Manage Courses')

@section('content')

<div style="display:grid;grid-template-columns:1fr 1fr;gap:24px">

    {{-- FORM TAMBAH --}}
    <div class="bg-white p-6 rounded shadow">

        <h2 style="font-size:18px;font-weight:600;margin-bottom:20px">
            Tambah Course
        </h2>

        {{-- ERROR VALIDATION --}}
        @if ($errors->any())

            <div style="
                background:#fee2e2;
                color:#991b1b;
                padding:12px;
                border-radius:8px;
                margin-bottom:16px
            ">

                <ul style="margin:0;padding-left:18px">

                    @foreach ($errors->all() as $error)

                        <li>{{ $error }}</li>

                    @endforeach

                </ul>

            </div>

        @endif


        {{-- SUCCESS --}}
        @if(session('success'))

            <div style="
                background:#dcfce7;
                color:#166534;
                padding:12px;
                border-radius:8px;
                margin-bottom:16px
            ">

                {{ session('success') }}

            </div>

        @endif


        {{-- FORM --}}
        <form method="POST"
              action="{{ route('admin.courses.store') }}"
              enctype="multipart/form-data">

            @csrf


            {{-- TITLE --}}
            <div style="margin-bottom:16px">
                <label class="block text-sm font-medium text-gray-900 mb-1">Title</label>
                <input type="text"
                       name="title"
                       value="{{ old('title') }}"
                       class="text-gray-900 placeholder-gray-400"
                       style="
                            width:100%;
                            padding:10px;
                            border:1px solid #ddd;
                            border-radius:6px
                       "
                       required>
            </div>


            {{-- DESCRIPTION --}}
            <div style="margin-bottom:16px">
                <label class="block text-sm font-medium text-gray-900 mb-1">Description</label>
                <textarea name="description"
                          rows="4"
                          class="text-gray-900 placeholder-gray-400"
                          style="
                                width:100%;
                                padding:10px;
                                border:1px solid #ddd;
                                border-radius:6px
                          ">{{ old('description') }}</textarea>
            </div>


            {{-- SYLLABUS --}}
            <div style="margin-bottom:16px">
                <label class="block text-sm font-medium text-gray-900 mb-1">Syllabus</label>
                <textarea name="syllabus"
                          rows="4"
                          class="text-gray-900 placeholder-gray-400"
                          style="
                                width:100%;
                                padding:10px;
                                border:1px solid #ddd;
                                border-radius:6px
                          ">{{ old('syllabus') }}</textarea>
            </div>


            {{-- PRICE --}}
            <div style="margin-bottom:16px">
                <label class="block text-sm font-medium text-gray-900 mb-1">Price</label>
                <input type="number"
                       name="price"
                       value="{{ old('price') }}"
                       class="text-gray-900 placeholder-gray-400"
                       style="
                            width:100%;
                            padding:10px;
                            border:1px solid #ddd;
                            border-radius:6px
                       "
                       required>
            </div>


            {{-- DURATION --}}
            <div style="margin-bottom:16px">
                <label class="block text-sm font-medium text-gray-900 mb-1">Duration Hours</label>
                <input type="number"
                       name="duration_hours"
                       value="{{ old('duration_hours') }}"
                       class="text-gray-900 placeholder-gray-400"
                       style="
                            width:100%;
                            padding:10px;
                            border:1px solid #ddd;
                            border-radius:6px
                       "
                       required>
            </div>


            {{-- CATEGORY --}}
            <div style="margin-bottom:16px">
                <label class="block text-sm font-medium text-gray-900 mb-1">Category</label>
                <select name="category_id"
                        class="text-gray-900"
                        style="
                            width:100%;
                            padding:10px;
                            border:1px solid #ddd;
                            border-radius:6px
                        "
                        required>

                    <option value="">
                        -- Pilih Category --
                    </option>

                    @foreach($categories ?? [] as $category)

                        <option value="{{ $category->id }}"
                            {{ old('category_id') == $category->id ? 'selected' : '' }}>

                            {{ $category->name }}

                        </option>

                    @endforeach

                </select>

            </div>


            {{-- LEVEL --}}
            <div style="margin-bottom:16px">
                <label class="block text-sm font-medium text-gray-900 mb-1">Level</label>
                <select name="level"
                        class="text-gray-900"
                        style="
                            width:100%;
                            padding:10px;
                            border:1px solid #ddd;
                            border-radius:6px
                        ">

                    <option value="beginner">Beginner</option>

                    <option value="intermediate">Intermediate</option>

                    <option value="advanced">Advanced</option>

                </select>

            </div>


            {{-- CERT TYPE --}}
            <div style="margin-bottom:16px">
                <label class="block text-sm font-medium text-gray-900 mb-1">Certificate Type</label>
                <input type="text"
                       name="cert_type"
                       value="{{ old('cert_type') }}"
                       class="text-gray-900 placeholder-gray-400"
                       style="
                            width:100%;
                            padding:10px;
                            border:1px solid #ddd;
                            border-radius:6px
                       ">
            </div>


            {{-- MAX PARTICIPANTS --}}
            <div style="margin-bottom:20px">
                <label class="block text-sm font-medium text-gray-900 mb-1">Max Participants</label>
                <input type="number"
                       name="max_participants"
                       value="{{ old('max_participants') }}"
                       class="text-gray-900 placeholder-gray-400"
                       style="
                            width:100%;
                            padding:10px;
                            border:1px solid #ddd;
                            border-radius:6px
                       ">
            </div>

            {{-- IMAGE UPLOAD --}}
            <div style="margin-bottom:16px">
                <label class="block text-sm font-medium text-gray-900 mb-1">Course Images (Opsional, bisa pilih banyak)</label>
                <input type="file"
                       name="images[]"
                       multiple
                       accept="image/*"
                       class="text-gray-900"
                       style="
                            width:100%;
                            padding:10px;
                            border:1px solid #ddd;
                            border-radius:6px
                       ">
                <small style="color:gray;">Maksimal 5MB per gambar (JPG, JPEG, PNG)</small>
            </div>

            {{-- FACILITIES --}}
            <div style="margin-bottom:20px">
                <label class="block text-sm font-medium text-gray-900 mb-3">Facilities</label>
                
                @php
                    $availableFacilities = ['Ruang Ber-AC', 'Modul Belajar', 'WiFi', 'Sertifikat', 'Makan Siang'];
                @endphp
                
                <div style="display:flex; gap:16px; flex-wrap:wrap;">
                    @foreach($availableFacilities as $facility)
                    <label style="display:flex; align-items:center; gap:6px;" class="text-sm text-gray-900">
                        <input type="checkbox" name="facilities[]" value="{{ $facility }}">
                        {{ $facility }}
                    </label>
                    @endforeach
                </div>
            </div>


            {{-- BUTTON --}}
            <button type="submit"
                    style="
                        background:#7c3aed;
                        color:white;
                        padding:10px 16px;
                        border:none;
                        border-radius:6px;
                        cursor:pointer
                    ">

                Simpan

            </button>

        </form>

    </div>



    {{-- LIST COURSE --}}
    <div class="bg-white p-6 rounded shadow">

        <h2 style="
            font-size:18px;
            font-weight:600;
            margin-bottom:20px
        ">
            Daftar Course
        </h2>


        <table style="
            width:100%;
            border-collapse:collapse
        ">

            <thead style="background:#f3f4f6">

                <tr>

                    <th style="
                        padding:10px;
                        border:1px solid #e5e7eb
                    ">
                        Title
                    </th>

                    <th style="
                        padding:10px;
                        border:1px solid #e5e7eb
                    ">
                        Category
                    </th>

                    <th style="
                        padding:10px;
                        border:1px solid #e5e7eb
                    ">
                        Price
                    </th>

                    <th style="
                        padding:10px;
                        border:1px solid #e5e7eb
                    ">
                        Action
                    </th>

                </tr>

            </thead>


            <tbody>

            {{-- FIX ERROR UNDEFINED VARIABLE --}}
            @forelse($courses ?? [] as $course)

                <tr>

                    <td style="
                        padding:10px;
                        border:1px solid #e5e7eb
                    ">
                        {{ $course->title }}
                    </td>


                    <td style="
                        padding:10px;
                        border:1px solid #e5e7eb
                    ">
                        {{ $course->category->name ?? '-' }}
                    </td>


                    <td style="
                        padding:10px;
                        border:1px solid #e5e7eb
                    ">
                        Rp {{ number_format($course->price,0,',','.') }}
                    </td>


                    <td style="
                        padding:10px;
                        border:1px solid #e5e7eb
                    ">

                        <div style="
                            display:flex;
                            gap:8px
                        ">

                            {{-- EDIT --}}
                            <a href="{{ route('admin.courses.edit',$course->id) }}"
                               style="
                                    background:#f59e0b;
                                    color:white;
                                    padding:6px 10px;
                                    border-radius:6px;
                                    text-decoration:none;
                                    font-size:13px
                               ">

                                Edit

                            </a>


                            {{-- DELETE --}}
                            <form action="{{ route('admin.courses.destroy',$course->id) }}"
                                  method="POST"
                                  onsubmit="return confirm('Hapus course ini?')">

                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                        style="
                                            background:#dc2626;
                                            color:white;
                                            padding:6px 10px;
                                            border:none;
                                            border-radius:6px;
                                            cursor:pointer
                                        ">

                                    Hapus

                                </button>

                            </form>

                        </div>

                    </td>

                </tr>

            @empty

                <tr>

                    <td colspan="4"
                        style="
                            padding:20px;
                            text-align:center
                        ">

                        Belum ada course

                    </td>

                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection