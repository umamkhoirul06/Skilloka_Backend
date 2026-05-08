{{-- resources/views/admin/courses/index.blade.php --}}

@extends('layouts.admin')

@section('title', 'Daftar Course')

@section('content')

<div class="bg-white p-6 rounded shadow">

    <div style="
        display:flex;
        justify-content:space-between;
        align-items:center;
        margin-bottom:20px
    ">

        <h2 style="
            font-size:20px;
            font-weight:600
        ">
            Daftar Course
        </h2>

        <a href="{{ route('admin.courses.create') }}"
           style="
                background:#7c3aed;
                color:white;
                padding:10px 16px;
                border-radius:6px;
                text-decoration:none
           ">

            + Tambah Course

        </a>

    </div>


    {{-- SUCCESS MESSAGE --}}
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


    <table style="
        width:100%;
        border-collapse:collapse
    ">

        <thead style="background:#f3f4f6">

            <tr>

                <th style="
                    padding:12px;
                    border:1px solid #e5e7eb
                ">
                    Title
                </th>

                <th style="
                    padding:12px;
                    border:1px solid #e5e7eb
                ">
                    Category
                </th>

                <th style="
                    padding:12px;
                    border:1px solid #e5e7eb
                ">
                    Level
                </th>

                <th style="
                    padding:12px;
                    border:1px solid #e5e7eb
                ">
                    Price
                </th>

                <th style="
                    padding:12px;
                    border:1px solid #e5e7eb
                ">
                    Status
                </th>

                <th style="
                    padding:12px;
                    border:1px solid #e5e7eb
                ">
                    Action
                </th>

            </tr>

        </thead>


        <tbody>

        @forelse($courses as $course)

            <tr>

                <td style="
                    padding:12px;
                    border:1px solid #e5e7eb
                ">
                    {{ $course->title }}
                </td>


                <td style="
                    padding:12px;
                    border:1px solid #e5e7eb
                ">
                    {{ $course->category->name ?? '-' }}
                </td>


                <td style="
                    padding:12px;
                    border:1px solid #e5e7eb;
                    text-transform:capitalize
                ">
                    {{ $course->level }}
                </td>


                <td style="
                    padding:12px;
                    border:1px solid #e5e7eb
                ">
                    Rp {{ number_format($course->price,0,',','.') }}
                </td>


                <td style="
                    padding:12px;
                    border:1px solid #e5e7eb
                ">

                    @if($course->is_active)

                        <span style="
                            background:#dcfce7;
                            color:#166534;
                            padding:4px 8px;
                            border-radius:6px;
                            font-size:12px
                        ">
                            Active
                        </span>

                    @else

                        <span style="
                            background:#fee2e2;
                            color:#991b1b;
                            padding:4px 8px;
                            border-radius:6px;
                            font-size:12px
                        ">
                            Inactive
                        </span>

                    @endif

                </td>


                <td style="
                    padding:12px;
                    border:1px solid #e5e7eb
                ">

                    <div style="
                        display:flex;
                        gap:8px
                    ">

                        {{-- EDIT --}}
                        <a href="{{ route('admin.courses.edit', $course->id) }}"
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
                        <form action="{{ route('admin.courses.destroy', $course->id) }}"
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
                                        cursor:pointer;
                                        font-size:13px
                                    ">

                                Hapus

                            </button>

                        </form>

                    </div>

                </td>

            </tr>

        @empty

            <tr>

                <td colspan="6"
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


    {{-- PAGINATION --}}
    <div style="margin-top:20px">

        {{ $courses->links() }}

    </div>

</div>

@endsection