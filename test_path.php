<?php
include 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$path = storage_path('app/firebase-credentials.json');
echo "Storage path: " . $path . PHP_EOL;
echo "Base path of storage path: " . base_path($path) . PHP_EOL;
echo "File exists (original): " . (file_exists($path) ? 'Yes' : 'No') . PHP_EOL;
echo "File exists (base_path wrap): " . (file_exists(base_path($path)) ? 'Yes' : 'No') . PHP_EOL;
