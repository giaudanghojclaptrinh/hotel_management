<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $f = \App\Models\Feedback::create([
        'name' => 'Automated Test',
        'email' => 'auto-test@example.com',
        'message' => 'This is a test feedback created by script.',
    ]);
    echo "Inserted ID: " . $f->id . "\n";
    echo "COUNT: " . \App\Models\Feedback::count() . "\n";
} catch (Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
