<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$controller = new \App\Http\Controllers\Admin\SuperAdmin\FinanceController();
$res = $controller->exportPdf(request());
echo 'PDF Export Status: ' . $res->getStatusCode() . PHP_EOL;
echo 'Content-Type: ' . $res->headers->get('content-type') . PHP_EOL;
