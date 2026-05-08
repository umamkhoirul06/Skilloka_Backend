<?php

namespace App\Http\Controllers\Admin\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {

        $users = User::with('roles')

            ->where(function ($query) {

                /*
                |--------------------------------------------------------------------------
                | SUPER ADMIN
                |--------------------------------------------------------------------------
                */
                $query->role('super_admin')



                /*
                |--------------------------------------------------------------------------
                | ADMIN LPK ACTIVE
                |--------------------------------------------------------------------------
                */
                ->orWhere(function ($q) {

                    $q->role('admin_lpk')

                        ->where('status', 'active');

                })



                /*
                |--------------------------------------------------------------------------
                | USER MOBILE ACTIVE
                |--------------------------------------------------------------------------
                */
                ->orWhere(function ($q) {

                    $q->role('user')

                        ->where('status', 'active');

                });

            })

            ->latest()

            ->get();



        return view(

            'super_admin.users.index',

            compact('users')

        );
    }
}