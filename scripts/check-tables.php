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

$stmt = $pdo->query('SHOW TABLES');
$rows = $stmt->fetchAll(PDO::FETCH_NUM);
if (empty($rows)) {
    echo "No tables found in database $db\n";
    exit(0);
}
foreach ($rows as $r) {
    echo $r[0] . PHP_EOL;
}
