<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Password;

try {
    $to = config('mail.from.address') ?: getenv('MAIL_FROM_ADDRESS');
    if (! $to) {
        echo "No recipient email configured (MAIL_FROM_ADDRESS).", PHP_EOL;
        exit(1);
    }

    echo "Sending password reset link to: {$to}\n";

    $response = Password::broker()->sendResetLink(['email' => $to]);

    if ($response === Password::RESET_LINK_SENT) {
        echo "Password reset link sent (broker response: RESET_LINK_SENT).\n";
    } else {
        echo "Password broker response: " . (string) $response . "\n";
    }
} catch (Throwable $e) {
    echo "Exception: " . get_class($e) . PHP_EOL;
    echo "Message: " . $e->getMessage() . PHP_EOL;
    echo "Trace:\n" . $e->getTraceAsString() . PHP_EOL;
}
