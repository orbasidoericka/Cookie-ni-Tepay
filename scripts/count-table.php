<?php
$host = getenv('DB_HOST') ?: getenv('MYSQLHOST');
$port = getenv('DB_PORT') ?: getenv('MYSQLPORT') ?: 3306;
$db = getenv('DB_DATABASE') ?: getenv('MYSQLDATABASE');
$user = getenv('DB_USERNAME') ?: getenv('MYSQLUSER');
$pass = getenv('DB_PASSWORD') ?: getenv('MYSQLPASSWORD');
$table = $argv[1] ?? null;
if (!$table) { echo "Usage: php count-table.php TABLE\n"; exit(1);} 
$dsn = "mysql:host={$host};port={$port};dbname={$db};charset=utf8mb4";
try {
    $pdo = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
} catch (PDOException $e) {
    fwrite(STDERR, "Connection error: " . $e->getMessage() . "\n");
    exit(1);
}
$cnt = $pdo->query("SELECT COUNT(*) FROM `{$table}`")->fetchColumn();
echo $cnt . PHP_EOL;
