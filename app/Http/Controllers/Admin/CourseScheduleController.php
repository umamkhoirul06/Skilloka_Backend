<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Course;
use App\Models\CourseSchedule;

class CourseScheduleController extends Controller
{

    public function index()
    {

        $user = Auth::user();

        $schedules = CourseSchedule::with('course')
            ->whereHas('course', function ($q) use ($user) {
                $q->where('tenant_id', $user->tenant_id);
            })
            ->latest()
            ->get();

        return view(
            'admin.course_schedules.index',
            compact('schedules')
        );

    }



    public function create()
    {

        $user = Auth::user();

        $courses = Course::where(
            'tenant_id',
            $user->tenant_id
        )->get();

        return view(
            'admin.course_schedules.create',
            compact('courses')
        );

    }



    public function store(Request $request)
    {

        $request->validate([

            'course_id' => 'required|exists:courses,id',

            'start_date' => 'required|date',

            'end_date' => 'required|date|after_or_equal:start_date',

            'days_of_week' => 'required',

            'daily_start' => 'required',

            'daily_end' => 'required',

            'max_capacity' => 'required|integer|min:1',

        ]);


        CourseSchedule::create([

            'course_id' => $request->course_id,

            'start_date' => $request->start_date,

            'end_date' => $request->end_date,

            'days_of_week' => $request->days_of_week,

            'daily_start' => $request->daily_start,

            'daily_end' => $request->daily_end,

            'max_capacity' => $request->max_capacity

        ]);


        return redirect()
            ->route('admin.course-schedules.index')
            ->with(
                'success',
                'Schedule berhasil dibuat'
            );

    }



    public function edit(CourseSchedule $courseSchedule)
    {

        $user = Auth::user();

        $courses = Course::where(
            'tenant_id',
            $user->tenant_id
        )->get();

        return view(
            'admin.course_schedules.edit',
            compact(
                'courseSchedule',
                'courses'
            )
        );

    }



    public function update(Request $request, CourseSchedule $courseSchedule)
    {

        $request->validate([

            'course_id' => 'required|exists:courses,id',

            'start_date' => 'required|date',

            'end_date' => 'required|date|after_or_equal:start_date',

            'days_of_week' => 'required',

            'daily_start' => 'required',

            'daily_end' => 'required',

            'max_capacity' => 'required|integer|min:1'

        ]);


        $courseSchedule->update([

            'course_id' => $request->course_id,

            'start_date' => $request->start_date,

            'end_date' => $request->end_date,

            'days_of_week' => $request->days_of_week,

            'daily_start' => $request->daily_start,

            'daily_end' => $request->daily_end,

            'max_capacity' => $request->max_capacity,

        ]);


        return redirect()
            ->route('admin.course-schedules.index')
            ->with(
                'success',
                'Schedule berhasil diupdate'
            );

    }



    public function destroy(CourseSchedule $courseSchedule)
    {

        $courseSchedule->delete();

        return redirect()
            ->route('admin.course-schedules.index')
            ->with(
                'success',
                'Schedule berhasil dihapus'
            );

    }

}