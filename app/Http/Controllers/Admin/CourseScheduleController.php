<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

use App\Models\Course;
use App\Models\CourseSchedule;

class CourseScheduleController extends Controller
{

    public function index()
{

$schedules = CourseSchedule::latest()->get();

return view('admin.course_schedules.index', compact('schedules'));

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
            'max_participants' => 'required|integer|min:1',
        ]);


        $user = Auth::user();


        CourseSchedule::create([
            'id' => Str::uuid(),
            'tenant_id' => $user->tenant_id,
            'course_id' => $request->course_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'max_participants' => $request->max_participants,
            'is_active' => true
        ]);


        return redirect()
            ->route('admin.course-schedules.index')
            ->with('success', 'Schedule berhasil dibuat');
    }


    public function show(CourseSchedule $courseSchedule)
    {
        return view(
            'admin.course_schedules.show',
            compact('courseSchedule')
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
            'max_participants' => 'required|integer|min:1'
        ]);


        $courseSchedule->update([
            'course_id' => $request->course_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'max_participants' => $request->max_participants,
        ]);


        return redirect()
            ->route('admin.course-schedules.index')
            ->with('success', 'Schedule berhasil diupdate');
    }


    public function destroy(CourseSchedule $courseSchedule)
    {

        $courseSchedule->delete();

        return redirect()
            ->route('admin.course-schedules.index')
            ->with('success', 'Schedule berhasil dihapus');
    }

}