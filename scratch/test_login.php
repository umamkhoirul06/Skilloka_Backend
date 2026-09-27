<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Auth;

// Test Super Admin Auth
$superCreds = ['email' => 'superadmin@skilloka.com', 'password' => 'password123'];
if (Auth::attempt($superCreds)) {
    $u = Auth::user();
    echo "Super Admin Login: SUCCESS!" . PHP_EOL;
    echo "Roles: " . $u->roles->pluck('name')->implode(', ') . PHP_EOL;
    echo "Is Super Admin: " . ($u->hasRole('super_admin') ? 'YES' : 'NO') . PHP_EOL;
    Auth::logout();
} else {
    echo "Super Admin Login: FAILED!" . PHP_EOL;
}

echo PHP_EOL;

// Test Admin LPK Auth
$lpkCreds = ['email' => 'admin@merahputih.com', 'password' => 'password123'];
if (Auth::attempt($lpkCreds)) {
    $u = Auth::user();
    echo "Admin LPK Login: SUCCESS!" . PHP_EOL;
    echo "Roles: " . $u->roles->pluck('name')->implode(', ') . PHP_EOL;
    echo "Is Admin LPK: " . ($u->hasRole('admin_lpk') ? 'YES' : 'NO') . PHP_EOL;
    echo "LPK Name: " . ($u->lpk ? $u->lpk->name : 'NONE') . PHP_EOL;
    Auth::logout();
} else {
    echo "Admin LPK Login: FAILED!" . PHP_EOL;
}
