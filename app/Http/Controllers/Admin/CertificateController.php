<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Course;
use App\Models\Booking;
use App\Models\Certificate;
use App\Models\Lpk;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class CertificateController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $courses = Course::where('tenant_id', $user->tenant_id)->get();
        $courseIds = $courses->pluck('id');

        // Issued certificates
        $certificates = Certificate::whereIn('course_id', $courseIds)
            ->with(['course', 'user', 'booking'])
            ->latest('issue_date')
            ->paginate(15);

        // Queue of eligible students who don't have a certificate yet
        $eligibleBookings = Booking::whereIn('course_id', $courseIds)
            ->whereIn('status', ['paid', 'confirmed', 'ongoing', 'completed'])
            ->whereDoesntHave('certificate')
            ->with(['course', 'user'])
            ->latest()
            ->get();

        return view('admin.certificates.index', compact('certificates', 'eligibleBookings', 'courses'));
    }

    public function create(Request $request)
    {
        $user = Auth::user();
        $courses = Course::where('tenant_id', $user->tenant_id)->get();
        $lpk = Lpk::where('tenant_id', $user->tenant_id)->first();

        $selectedBookingId = $request->query('booking_id');
        $selectedBooking = $selectedBookingId 
            ? Booking::whereIn('course_id', $courses->pluck('id'))->with(['course', 'user'])->findOrFail($selectedBookingId)
            : null;

        // Auto-generate official certificate number
        $lpkCode = strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $lpk->name ?? 'LPK'), 0, 4));
        $suggestedNumber = 'SKL/' . $lpkCode . '/' . date('Y') . '/' . strtoupper(Str::random(6));

        return view('admin.certificates.create', compact('courses', 'selectedBooking', 'suggestedNumber'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $courses = Course::where('tenant_id', $user->tenant_id)->get();

        $request->validate([
            'booking_id'  => 'required|uuid|exists:bookings,id',
            'number'      => 'required|string|max:100|unique:certificates,number',
            'issue_date'  => 'required|date',
            'expiry_date' => 'nullable|date|after_or_equal:issue_date',
            'file'        => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:10240',
        ]);

        $booking = Booking::whereIn('course_id', $courses->pluck('id'))
            ->findOrFail($request->booking_id);

        $fileUrl = 'certificates/' . Str::slug($request->number) . '.pdf';
        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('certificates', 'public');
            $fileUrl = $path;
        }

        Certificate::create([
            'user_id'     => $booking->user_id,
            'course_id'   => $booking->course_id,
            'booking_id'  => $booking->id,
            'number'      => $request->number,
            'file_url'    => $fileUrl,
            'issue_date'  => $request->issue_date,
            'expiry_date' => $request->expiry_date,
        ]);

        // Update booking learning status to 'selesai' / 'completed'
        $booking->update([
            'status_belajar' => 'selesai',
            'status'         => 'completed',
        ]);

        return redirect()
            ->route('admin.certificates.index')
            ->with('success', 'Sertifikat resmi berhasil diterbitkan untuk ' . ($booking->user->name ?? 'peserta') . '.');
    }

    public function show($id)
    {
        $user = Auth::user();
        $courses = Course::where('tenant_id', $user->tenant_id)->get();

        $certificate = Certificate::whereIn('course_id', $courses->pluck('id'))
            ->with(['course.lpk', 'user', 'booking'])
            ->findOrFail($id);

        return view('admin.certificates.show', compact('certificate'));
    }

    public function destroy($id)
    {
        $user = Auth::user();
        $courses = Course::where('tenant_id', $user->tenant_id)->get();

        $certificate = Certificate::whereIn('course_id', $courses->pluck('id'))
            ->findOrFail($id);

        if ($certificate->file_url && Storage::disk('public')->exists($certificate->file_url)) {
            Storage::disk('public')->delete($certificate->file_url);
        }

        $certificate->delete();

        return redirect()
            ->route('admin.certificates.index')
            ->with('success', 'Sertifikat berhasil dicabut / dihapus.');
    }
}
