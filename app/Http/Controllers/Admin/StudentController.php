<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Booking;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    /**
     * LIST STUDENT
     */
    public function index()
    {
        $tenantId = Auth::user()->tenant_id;

        $students = User::whereHas('bookings', function ($q) use ($tenantId) {
            $q->where('tenant_id', $tenantId);
        })
            ->withCount([
                'bookings' => function ($q) use ($tenantId) {
                    $q->where('tenant_id', $tenantId);
                }
            ])
            ->latest()
            ->paginate(10);

        return view('admin.students.index', compact('students'));
    }

    /**
     * DETAIL STUDENT
     */
    public function show(User $student)
    {
        $tenantId = Auth::user()->tenant_id;

        // Security Check: Pastikan user ini terdaftar di LPK ini
        if (!$student->bookings()->where('tenant_id', $tenantId)->exists()) {
            return redirect()->route('admin.students.index')->with('error', 'Siswa tidak ditemukan.');
        }

        $student->load([
            'bookings' => function ($q) use ($tenantId) {
                $q->where('tenant_id', $tenantId)->latest();
            }
        ]);

        return view('admin.students.show', compact('student'));
    }

    /**
     * UPDATE STUDENT
     */
    public function update(Request $request, User $student)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $student->id,
            'phone' => 'nullable|string|max:20',
        ]);

        if ($request->filled('password')) {
            $request->validate(['password' => 'required|string|min:8|confirmed']);
            $validated['password'] = Hash::make($request->password);
        }

        $student->update($validated);

        return redirect()
            ->route('admin.students.show', $student)
            ->with('success', 'Data siswa berhasil diperbarui');
    }

    /**
     * 🔥 FITUR BARU: UPDATE STATUS BELAJAR
     * Dipanggil via form/dropdown di blade
     */
    public function updateStatusBelajar(Request $request, $bookingId)
    {
        $request->validate([
            'status_belajar' => 'required|in:sedang_belajar,lulus,magang',
        ]);

        $booking = Booking::where('id', $bookingId)
            ->where('tenant_id', Auth::user()->tenant_id)
            ->firstOrFail();

        $booking->update(['status_belajar' => $request->status_belajar]);

        return back()->with('success', 'Status belajar siswa berhasil diperbarui!');
    }

    /**
     * DELETE STUDENT
     */
    public function destroy(User $student)
    {
        // Pastikan admin hanya bisa menghapus jika user tsb tidak memiliki booking aktif
        $student->delete();

        return redirect()
            ->route('admin.students.index')
            ->with('success', 'Data siswa berhasil dihapus');
    }
}