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
     * Hanya menampilkan siswa yang memiliki booking
     * dengan pembayaran berstatus success pada LPK terkait.
     */
    public function index()
    {
        $tenantId = Auth::user()->tenant_id;

        $students = User::whereHas('bookings', function ($q) use ($tenantId) {
            $q->where('tenant_id', $tenantId)
                ->whereHas('payment', function ($paymentQuery) {
                    $paymentQuery->where('status', 'success');
                });
        })
            ->withCount([
                'bookings' => function ($q) use ($tenantId) {
                    $q->where('tenant_id', $tenantId)
                        ->whereHas('payment', function ($paymentQuery) {
                            $paymentQuery->where('status', 'success');
                        });
                }
            ])
            ->latest()
            ->paginate(10);

        return view('admin.students.index', compact('students'));
    }

    /**
     * DETAIL STUDENT
     * Hanya dapat diakses jika siswa memiliki booking
     * yang sudah dibayar pada LPK terkait.
     */
    public function show(User $student)
    {
        $tenantId = Auth::user()->tenant_id;

        $hasPaidBooking = $student->bookings()
            ->where('tenant_id', $tenantId)
            ->whereHas('payment', function ($q) {
                $q->where('status', 'success');
            })
            ->exists();

        if (!$hasPaidBooking) {
            return redirect()
                ->route('admin.students.index')
                ->with('error', 'Siswa tidak ditemukan.');
        }

        $student->load([
            'bookings' => function ($q) use ($tenantId) {
                $q->where('tenant_id', $tenantId)
                    ->whereHas('payment', function ($paymentQuery) {
                        $paymentQuery->where('status', 'success');
                    })
                    ->latest();
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
            $request->validate([
                'password' => 'required|string|min:8|confirmed',
            ]);

            $validated['password'] = Hash::make($request->password);
        }

        $student->update($validated);

        return redirect()
            ->route('admin.students.show', $student)
            ->with('success', 'Data siswa berhasil diperbarui.');
    }

    /**
     * UPDATE STATUS BELAJAR
     */
    public function updateStatusBelajar(Request $request, $bookingId)
    {
        $request->validate([
            'status_belajar' => 'required|in:sedang_belajar,lulus,magang',
        ]);

        $booking = Booking::where('id', $bookingId)
            ->where('tenant_id', Auth::user()->tenant_id)
            ->whereHas('payment', function ($q) {
                $q->where('status', 'success');
            })
            ->firstOrFail();

        $booking->update([
            'status_belajar' => $request->status_belajar,
        ]);

        return back()->with(
            'success',
            'Status belajar siswa berhasil diperbarui!'
        );
    }

    /**
     * DELETE STUDENT
     */
    public function destroy(User $student)
    {
        // Menghapus akun siswa tetap mengikuti mekanisme SoftDeletes
        // yang sudah digunakan pada model User.
        $student->delete();

        return redirect()
            ->route('admin.students.index')
            ->with('success', 'Data siswa berhasil dihapus.');
    }
}