<?php

namespace App\Http\Controllers\Admin\SuperAdmin;

use App\Http\Controllers\Controller;

class LogController extends Controller
{

    public function index()
    {

        $logFile = storage_path('logs/laravel.log');

        if (!file_exists($logFile)) {

            $logs = [];

        } else {

            $logs = array_reverse(
                array_slice(
                    file($logFile),
                    -50
                )
            );

        }

        return view(
            'super_admin.logs.index',
            compact('logs')
        );

    }

}