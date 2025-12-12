<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Carbon\Carbon;

$email = 'niemvui2233@gmail.com';

try {
    $user = User::where('email', $email)->first();
    if ($user) {
        echo "User already exists: ID {$user->id}\n";
        exit(0);
    }

    $user = User::create([
        'name' => 'Test User',
        'email' => $email,
        'password' => bcrypt('TempPass123'),
        'email_verified_at' => Carbon::now(),
    ]);

    echo "Created user ID {$user->id} with email {$email}\n";
} catch (Throwable $e) {
    echo "Exception: " . get_class($e) . PHP_EOL;
    echo "Message: " . $e->getMessage() . PHP_EOL;
    echo "Trace:\n" . $e->getTraceAsString() . PHP_EOL;
    exit(1);
}
