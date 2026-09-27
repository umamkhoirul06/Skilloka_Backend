<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Spatie\Permission\Models\Role;

echo "=== ROLES IN DATABASE ===" . PHP_EOL;
foreach (Role::all() as $role) {
    echo "- ID: {$role->id}, Name: {$role->name}, Guard: {$role->guard_name}" . PHP_EOL;
}

echo PHP_EOL . "=== USERS IN DATABASE ===" . PHP_EOL;
foreach (User::all() as $user) {
    $roles = $user->roles->pluck('name')->implode(', ');
    echo "Email: {$user->email} | Status: {$user->status} | Roles: [{$roles}] | Tenant: {$user->tenant_id}" . PHP_EOL;
}
