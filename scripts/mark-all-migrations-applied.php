<?php
$host = getenv('DB_HOST') ?: getenv('MYSQLHOST');
$port = getenv('DB_PORT') ?: getenv('MYSQLPORT') ?: 3306;
$db = getenv('DB_DATABASE') ?: getenv('MYSQLDATABASE');
$user = getenv('DB_USERNAME') ?: getenv('MYSQLUSER');
$pass = getenv('DB_PASSWORD') ?: getenv('MYSQLPASSWORD');
$dsn = "mysql:host={$host};port={$port};dbname={$db};charset=utf8mb4";

try {
    $pdo = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
} catch (PDOException $e) {
    fwrite(STDERR, "Connection error: " . $e->getMessage() . "\n");
    exit(1);
}

$migrationsDir = __DIR__ . '/../database/migrations';
$files = glob($migrationsDir . '/*.php');

$existing = $pdo->query('SELECT migration FROM migrations')->fetchAll(PDO::FETCH_COLUMN);
$existing = $existing ?: [];

$maxBatchRow = $pdo->query('SELECT max(batch) as b FROM migrations')->fetch(PDO::FETCH_ASSOC);
$nextBatch = $maxBatchRow && $maxBatchRow['b'] ? (int)$maxBatchRow['b'] + 1 : 1;

$insert = $pdo->prepare('INSERT INTO migrations (migration, batch) VALUES (?, ?)');
$added = 0;
foreach ($files as $file) {
    $name = basename($file, '.php');
    if (in_array($name, $existing)) {
        continue;
    }
    $insert->execute([$name, $nextBatch]);
    echo "Marked migration as applied: $name (batch $nextBatch)\n";
    $added++;
}

if ($added === 0) {
    echo "No migrations were marked as applied. All migrations are already recorded.\n";
} else {
    echo "Marked $added migrations as applied.\n";
}

?>
