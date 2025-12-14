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
$migration = '2025_12_14_140000_create_sessions_table';
$exists = $pdo->prepare('SELECT COUNT(*) FROM migrations WHERE migration = ?');
$exists->execute([$migration]);
if ($exists->fetchColumn() > 0) {
    echo "Migration already recorded: $migration\n";
    exit(0);
}
// Determine current highest batch
$batchRow = $pdo->query('SELECT max(batch) as b FROM migrations')->fetch(PDO::FETCH_ASSOC);
$batch = $batchRow && $batchRow['b'] ? (int)$batchRow['b'] : 1;
// insert with same batch
$insert = $pdo->prepare('INSERT INTO migrations (migration, batch) VALUES (?, ?)');
$insert->execute([$migration, $batch]);
echo "Inserted migration record ($migration) batch $batch\n";
