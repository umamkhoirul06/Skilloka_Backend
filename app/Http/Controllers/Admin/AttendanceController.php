<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Course;
use App\Models\CourseSchedule;
use App\Models\Booking;
use App\Models\Attendance;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $courses = Course::where('tenant_id', $user->tenant_id)
            ->withCount(['bookings' => function ($q) {
                $q->whereIn('status', ['paid', 'confirmed', 'ongoing', 'completed']);
            }])
            ->latest()
            ->get();

        $recentAttendances = Attendance::whereIn('course_id', $courses->pluck('id'))
            ->with(['course', 'user', 'booking'])
            ->latest('session_date')
            ->take(15)
            ->get();

        return view('admin.attendance.index', compact('courses', 'recentAttendances'));
    }

    public function session(Request $request)
    {
        $user = Auth::user();
        $courses = Course::where('tenant_id', $user->tenant_id)->get();

        $selectedCourseId = $request->query('course_id', $courses->first()->id ?? null);
        $course = $selectedCourseId ? Course::with('schedules')->where('tenant_id', $user->tenant_id)->findOrFail($selectedCourseId) : null;

        $sessionDate = $request->query('session_date', date('Y-m-d'));
        $sessionName = $request->query('session_name', 'Pertemuan 1');
        $scheduleId = $request->query('schedule_id', ($course && $course->schedules->count() > 0) ? $course->schedules->first()->id : null);

        $students = [];
        if ($course) {
            $bookings = Booking::with('user')
                ->where('course_id', $course->id)
                ->whereIn('status', ['paid', 'confirmed', 'ongoing', 'completed'])
                ->get();

            // Fetch existing attendance for this session if exists
            $existingRecords = Attendance::where('course_id', $course->id)
                ->where('session_name', $sessionName)
                ->where('session_date', $sessionDate)
                ->get()
                ->keyBy('booking_id');

            foreach ($bookings as $b) {
                $status = 'present';
                $notes = '';
                if ($existingRecords->has($b->id)) {
                    $status = $existingRecords->get($b->id)->status;
                    $notes = $existingRecords->get($b->id)->notes;
                }

                $students[] = [
                    'booking' => $b,
                    'user'    => $b->user,
                    'status'  => $status,
                    'notes'   => $notes,
                ];
            }
        }

        return view('admin.attendance.session', compact('courses', 'course', 'students', 'sessionDate', 'sessionName', 'scheduleId'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'course_id'    => 'required|uuid|exists:courses,id',
            'session_date' => 'required|date',
            'session_name' => 'required|string|max:100',
            'schedule_id'  => 'nullable|uuid',
            'attendance'   => 'required|array',
        ]);

        $course = Course::where('id', $request->course_id)
            ->where('tenant_id', $user->tenant_id)
            ->firstOrFail();

        DB::beginTransaction();
        try {
            foreach ($request->attendance as $bookingId => $item) {
                $booking = Booking::findOrFail($bookingId);

                Attendance::updateOrCreate(
                    [
                        'booking_id'   => $bookingId,
                        'session_name' => $request->session_name,
                        'session_date' => $request->session_date,
                    ],
                    [
                        'course_id'    => $course->id,
                        'schedule_id'  => $request->schedule_id,
                        'user_id'      => $booking->user_id,
                        'status'       => $item['status'] ?? 'present',
                        'notes'        => $item['notes'] ?? null,
                    ]
                );
            }

            DB::commit();

            return redirect()
                ->route('admin.attendance.session', [
                    'course_id'    => $course->id,
                    'session_name' => $request->session_name,
                    'session_date' => $request->session_date,
                    'schedule_id'  => $request->schedule_id,
                ])
                ->with('success', 'Presensi kehadiran sesi berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal menyimpan absensi: ' . $e->getMessage()]);
        }
    }

    public function recap($courseId)
    {
        $user = Auth::user();
        $course = Course::where('tenant_id', $user->tenant_id)->findOrFail($courseId);

        $bookings = Booking::with('user')
            ->where('course_id', $course->id)
            ->whereIn('status', ['paid', 'confirmed', 'ongoing', 'completed'])
            ->get();

        $recap = [];
        foreach ($bookings as $b) {
            $attendances = Attendance::where('booking_id', $b->id)->get();
            $total = $attendances->count();
            $present = $attendances->where('status', 'present')->count();
            $sick = $attendances->where('status', 'sick')->count();
            $excused = $attendances->where('status', 'excused')->count();
            $absent = $attendances->where('status', 'absent')->count();

            $percentage = $total > 0 ? round(($present / $total) * 100) : 0;

            $recap[] = [
                'booking'    => $b,
                'user'       => $b->user,
                'total'      => $total,
                'present'    => $present,
                'sick'       => $sick,
                'excused'    => $excused,
                'absent'     => $absent,
                'percentage' => $percentage,
            ];
        }

        return view('admin.attendance.recap', compact('course', 'recap'));
    }
}
