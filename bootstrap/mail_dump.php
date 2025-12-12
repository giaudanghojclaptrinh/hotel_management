<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
print_r(config('mail.mailers.smtp'));
echo PHP_EOL;
echo 'ENV MAIL_USERNAME=' . getenv('MAIL_USERNAME') . PHP_EOL;
