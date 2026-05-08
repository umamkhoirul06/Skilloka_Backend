<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use App\Models\User;
use App\Models\Tenant;
use App\Models\Lpk;
use App\Models\LpkVerification;

class AdminAuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | FORM LOGIN
    |--------------------------------------------------------------------------
    */

    public function showLogin()
    {
        return view('admin.auth.login');
    }



    /*
    |--------------------------------------------------------------------------
    | FORM REGISTER
    |--------------------------------------------------------------------------
    */

    public function showRegister()
    {
        return view('admin.auth.register');
    }



    /*
    |--------------------------------------------------------------------------
    | LOGIN
    |--------------------------------------------------------------------------
    */

    public function login(Request $request)
    {
        $credentials = $request->validate([

            'email' => ['required', 'email'],

            'password' => ['required']

        ]);



        if (!Auth::attempt($credentials)) {

            throw ValidationException::withMessages([

                'email' => 'Email atau password salah.'

            ]);
        }



        $request->session()->regenerate();

        $user = Auth::user();



        /*
        |--------------------------------------------------------------------------
        | CEK STATUS AKUN
        |--------------------------------------------------------------------------
        */

        if ($user->status !== 'active') {

            Auth::logout();



            if ($user->status === 'pending') {

                return back()->withErrors([

                    'email' => 'Akun Anda masih menunggu verifikasi dari Skilloka.'

                ]);
            }



            if ($user->status === 'rejected') {

                return back()->withErrors([

                    'email' => 'Pendaftaran LPK Anda ditolak oleh Skilloka.'

                ]);
            }



            return back()->withErrors([

                'email' => 'Akun tidak aktif.'

            ]);
        }



        /*
        |--------------------------------------------------------------------------
        | REDIRECT ROLE
        |--------------------------------------------------------------------------
        */

        if ($user->hasRole('super_admin')) {

            return redirect()->route('super.dashboard');
        }



        if ($user->hasRole('admin_lpk')) {

            return redirect()->route('admin.dashboard');
        }



        Auth::logout();



        return back()->withErrors([

            'email' => 'Akun ini tidak memiliki akses admin.'

        ]);
    }



    /*
    |--------------------------------------------------------------------------
    | REGISTER ADMIN LPK
    |--------------------------------------------------------------------------
    */

    public function register(Request $request)
    {
        $request->validate([

            /*
            |--------------------------------------------------------------------------
            | ADMIN
            |--------------------------------------------------------------------------
            */

            'name' => [
                'required',
                'string',
                'max:255'
            ],

            'email' => [
                'required',
                'email',
                'unique:users,email'
            ],

            'phone' => [
                'required',
                'string',
                'max:20',
                'unique:users,phone'
            ],

            'password' => [
                'required',
                'confirmed',
                'min:8'
            ],



            /*
            |--------------------------------------------------------------------------
            | LPK
            |--------------------------------------------------------------------------
            */

            'lpk_name' => [
                'required',
                'string',
                'max:255'
            ],

            'legal_name' => [
                'required',
                'string',
                'max:255'
            ],

            'nib' => [
                'required',
                'string',
                'unique:lpks,nib'
            ],

            'address' => [
                'required',
                'string'
            ],

            'city' => [
                'required',
                'string',
                'max:100'
            ],

            'description' => [
                'required',
                'string',
                'max:1000'
            ],

            'instagram' => [
                'nullable',
                'string',
                'max:255'
            ],

            'lpk_email' => [
                'nullable',
                'email'
            ],

            'facilities' => [
                'nullable',
                'string'
            ],

        ]);



        DB::beginTransaction();

        try {

            /*
            |--------------------------------------------------------------------------
            | CREATE TENANT
            |--------------------------------------------------------------------------
            */

            $tenant = Tenant::create([

                'domain' => Str::slug($request->lpk_name)
                    . '-' .
                    Str::random(5),

                'name' => $request->lpk_name,

                'lpk_name' => $request->lpk_name,

                'legal_name' => $request->legal_name,

                'nib' => $request->nib,

                'description' => $request->description,

                'phone' => $request->phone,

                'email' => $request->lpk_email
                    ?? $request->email,

                'instagram' => $request->instagram,

                'city' => $request->city,

                'address' => $request->address,

                'facilities' => $request->facilities,

                'is_active' => false,

            ]);



            /*
            |--------------------------------------------------------------------------
            | CREATE USER
            |--------------------------------------------------------------------------
            */

            $user = User::create([

                'tenant_id' => $tenant->id,

                'name' => $request->name,

                'email' => $request->email,

                'phone' => $request->phone,

                'password' => Hash::make(
                    $request->password
                ),

                'status' => 'pending',

            ]);



            /*
            |--------------------------------------------------------------------------
            | ROLE ADMIN LPK
            |--------------------------------------------------------------------------
            */

            if (!$user->hasRole('admin_lpk')) {

                $user->assignRole('admin_lpk');
            }



            /*
            |--------------------------------------------------------------------------
            | CREATE LPK
            |--------------------------------------------------------------------------
            */

            $lpk = Lpk::create([

                'tenant_id' => $tenant->id,

                'name' => $request->lpk_name,

                'legal_name' => $request->legal_name,

                'nib' => $request->nib,

                'address' => $request->address,

                'description' => $request->description,

                'facilities' => $request->facilities,

                'contact_info' => json_encode([

                    'phone' => $request->phone,

                    'email' => $request->lpk_email
                        ?? $request->email,

                    'instagram' => $request->instagram,

                ]),

                'is_verified' => false,

                'status' => 'pending',

                'status_verifikasi' => 'pending',

            ]);



            /*
            |--------------------------------------------------------------------------
            | CREATE VERIFICATION
            |--------------------------------------------------------------------------
            */

            LpkVerification::create([

                'lpk_id' => $lpk->id,

                'status' => 'pending',

            ]);



            DB::commit();



            return redirect()

                ->route('admin.login')

                ->with(

                    'success',

                    'Pendaftaran berhasil! Tim Skilloka akan memverifikasi LPK Anda terlebih dahulu.'

                );

        } catch (\Exception $e) {

            DB::rollBack();



            return back()

                ->withInput()

                ->withErrors([

                    'error' => $e->getMessage()

                ]);
        }
    }



    /*
    |--------------------------------------------------------------------------
    | LOGOUT
    |--------------------------------------------------------------------------
    */

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}