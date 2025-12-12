<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "CONFIG mail.mailers.smtp:\n";
print_r(config('mail.mailers.smtp'));

echo "\nENV VARS:\n";
$keys = ['MAIL_MAILER','MAIL_HOST','MAIL_PORT','MAIL_ENCRYPTION','MAIL_SCHEME','MAIL_USERNAME','MAIL_PASSWORD','MAIL_FROM_ADDRESS','APP_URL'];
foreach ($keys as $k) {
    echo $k . ' => ' . (getenv($k) ?: 'NULL') . "\n";
}

echo "\nPHP openssl extension: " . (extension_loaded('openssl') ? 'loaded' : 'missing') . "\n";
