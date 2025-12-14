<?php
$vendor = __DIR__ . '/../vendor/autoload.php';
if (!file_exists($vendor)) {
    // vendor path fallback to project root
    $vendor = __DIR__ . '/vendor/autoload.php';
}
require $vendor;
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $count = \App\Models\Feedback::count();
    echo "COUNT: $count\n";
    if ($count > 0) {
        $latest = \App\Models\Feedback::latest()->first()->toArray();
        echo "LATEST:\n";
        print_r($latest);
    }
} catch (Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
