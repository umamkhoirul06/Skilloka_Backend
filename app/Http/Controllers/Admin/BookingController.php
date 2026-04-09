<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Course;
use App\Models\CourseSchedule;
use App\Models\User;

class BookingController extends Controller
{
    /**
     * Display a listing of the bookings.
     */
    public function index()
    {
        $user = auth('web')->user();

        // Get bookings for the current tenant
        $bookings = Booking::with(['user', 'schedule.course'])
            ->when($user->tenant_id, function ($query) use ($user) {
                return $query->where('tenant_id', $user->tenant_id);
            })
            ->latest()
            ->paginate(10);

        return view('admin.bookings.index', compact('bookings'));
    }

    /**
     * Show the form for creating a new booking.
     */
    public function create()
    {
        $user = auth('web')->user();

        $schedules = CourseSchedule::with('course')
            ->whereHas('course', function ($q) use ($user) {
                $q->when($user->tenant_id, function ($query) use ($user) {
                    return $query->where('tenant_id', $user->tenant_id);
                });
            })->get();

        $students = User::role('student')
            ->when($user->tenant_id, function ($query) use ($user) {
                return $query->where('tenant_id', $user->tenant_id);
            })->get();

        return view('admin.bookings.create', compact('schedules', 'students'));
    }

    /**
     * Store a newly created booking in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'schedule_id' => 'required|exists:course_schedules,id',
            'status' => 'required|in:pending,paid,cancelled,completed',
        ]);

        $user = auth('web')->user();
        $schedule = CourseSchedule::with('course')->findOrFail($validated['schedule_id']);

        $validated['tenant_id'] = $user->tenant_id;
        $validated['amount'] = $schedule->course->price ?? 0;

        Booking::create($validated);

        return redirect()->route('admin.bookings.index')
            ->with('success', 'Booking created successfully!');
    }

    /**
     * Display the specified booking.
     */
    public function show(Booking $booking)
    {
        $booking->load(['user', 'schedule.course']);
        return view('admin.bookings.show', compact('booking'));
    }

    /**
     * Update booking status.
     */
    public function updateStatus(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,paid,cancelled,completed',
        ]);

        $booking->update($validated);

        return redirect()->route('admin.bookings.index')
            ->with('success', 'Booking status updated successfully!');
    }

    /**
     * Remove the specified booking from storage.
     */
    public function destroy(Booking $booking)
    {
        $booking->delete();

        return redirect()->route('admin.bookings.index')
            ->with('success', 'Booking deleted successfully!');
    }
}
