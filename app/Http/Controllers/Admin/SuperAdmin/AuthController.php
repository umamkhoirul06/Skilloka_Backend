<?php

namespace App\Http\Controllers\Admin\SuperAdmin;

use App\Http\Controllers\Controller;

class AuthController extends Controller
{

    public function login()
    {

        return redirect()->route('admin.login');

    }

}