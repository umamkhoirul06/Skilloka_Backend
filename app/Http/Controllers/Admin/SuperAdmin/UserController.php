<?php

namespace App\Http\Controllers\Admin\SuperAdmin;

use App\Http\Controllers\Controller;

use App\Models\User;

class UserController extends Controller
{

public function index()
{

$users = User::latest()->get();

return view(
'super_admin.users.index',
compact('users')
);

}

}