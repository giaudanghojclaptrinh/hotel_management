<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $to = config('mail.from.address') ?: getenv('MAIL_FROM_ADDRESS');
    echo "Sending test mail to: {$to}\n";
    \Mail::raw('SMTP test message from app', function ($m) use ($to) {
        $m->to($to)->subject('SMTP test');
    });
    echo "Mail sent (no exception thrown).\n";
} catch (Throwable $e) {
    echo "Exception: " . get_class($e) . "\n";
    echo "Message: " . $e->getMessage() . "\n";
    echo "Trace: \n" . $e->getTraceAsString() . "\n";
}
