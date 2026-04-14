<?php

namespace App\Http\Controllers\Admin\SuperAdmin;

use App\Http\Controllers\Controller;

class LogController extends Controller
{

public function index()
{

$logFile = storage_path('logs/laravel.log');

$logs = [];

if(file_exists($logFile)){

$logs = array_slice(
file($logFile),
-100
);

}

return view(
'super_admin.logs.index',
compact('logs')
);

}

}