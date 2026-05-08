<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    /**
     * LIST STUDENT
     * (user yang pernah booking di tenant admin ini)
     */
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $tenantId = $user->tenant_id;

        $students = User::withCount('bookings')
            ->whereHas('bookings', function ($q) use ($tenantId) {

                $q->where('tenant_id', $tenantId);

            })
            ->latest()
            ->paginate(10);

        return view(
            'admin.students.index',
            compact('students')
        );
    }



    /**
     * CREATE TIDAK DIPAKAI
     */
    public function create()
    {
        abort(404);
    }



    /**
     * STORE TIDAK DIPAKAI
     */
    public function store(Request $request)
    {
        abort(404);
    }



    /**
     * DETAIL STUDENT
     */
    public function show(User $student)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $tenantId = $user->tenant_id;

        // hanya booking dari tenant admin ini
        $student->load([

            'bookings' => function ($q) use ($tenantId) {

                $q->where('tenant_id', $tenantId)
                    ->latest();

            }

        ]);

        return view(
            'admin.students.show',
            compact('student')
        );
    }



    /**
     * FORM EDIT STUDENT
     */
    public function edit(User $student)
    {
        return view(
            'admin.students.edit',
            compact('student')
        );
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


        // update password kalau diisi
        if ($request->filled('password')) {

            $request->validate([

                'password' => 'required|string|min:8|confirmed'

            ]);

            $validated['password'] = Hash::make(
                $request->password
            );
        }


        $student->update($validated);


        return redirect()
            ->route('admin.students.show', $student)
            ->with(
                'success',
                'Data student berhasil diperbarui'
            );
    }



    /**
     * DELETE STUDENT
     */
    public function destroy(User $student)
    {
        $student->delete();

        return redirect()
            ->route('admin.students.index')
            ->with(
                'success',
                'Student berhasil dihapus'
            );
    }
}