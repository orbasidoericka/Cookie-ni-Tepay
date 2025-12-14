<?php
// Print runtime session driver using Laravel helper if available
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
// Boot the app minimal
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "SESSION_DRIVER: " . config('session.driver') . PHP_EOL;
echo "ENV SESSION_DRIVER: " . (getenv('SESSION_DRIVER') ?: 'not set') . PHP_EOL;

// Also print session table name
echo "SESSION_TABLE: " . config('session.table') . PHP_EOL;

?>