<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Resolved services.google.redirect: " . config('services.google.redirect') . PHP_EOL;
