<?php

/**
 * import_db.php
 * - Reads connection values from env or CLI args
 * - Adds --dry-run and --show-password options
 * - Keeps existing behavior but safe defaults and visibility
 */

// Long options: sql-file:, host:, port:, user:, password:, database:, dry-run, show-password, help
$opts = getopt('', ['sql-file:', 'host:', 'port:', 'user:', 'password:', 'database:', 'dry-run', 'show-password', 'help']);

if (isset($opts['help'])) {
    echo "Usage: php import_db.php [--sql-file=path] [--host=HOST] [--port=PORT] [--user=USER] [--password=PWD] [--database=DB] [--dry-run] [--show-password]\n";
    exit(0);
}

$sqlFile = isset($opts['sql-file']) ? $opts['sql-file'] : __DIR__ . '/database/legends.sql';

// Read from env vars first falling back to existing constants
$host = getenv('DB_HOST') ?: getenv('MYSQLHOST') ?: (isset($opts['host']) ? $opts['host'] : 'shortline.proxy.rlwy.net');
$port = getenv('DB_PORT') ?: (isset($opts['port']) ? $opts['port'] : 56871);
$database = getenv('DB_DATABASE') ?: (isset($opts['database']) ? $opts['database'] : 'railway');
$username = getenv('DB_USERNAME') ?: getenv('MYSQLUSER') ?: (isset($opts['user']) ? $opts['user'] : 'root');
$password = getenv('DB_PASSWORD') ?: (isset($opts['password']) ? $opts['password'] : 'SUTzMXEzjpZDJzCrNGOUqfXCQPMLffwn');

$isDryRun = isset($opts['dry-run']);
$showPassword = isset($opts['show-password']);

if (!file_exists($sqlFile)) {
    fwrite(STDERR, "SQL file not found: $sqlFile\n");
    exit(1);
}

// Prepare a mysql CLI command to show the user on dry-run
$maskPwd = $password && !$showPassword ? str_repeat('*', max(4, min(12, strlen($password)))) : $password;
$mysqlCliCmd = sprintf(
    "mysql -h %s -P %s -u %s %s %s < %s",
    escapeshellarg($host),
    escapeshellarg($port),
    escapeshellarg($username),
    $password ? ('-p' . ($showPassword ? $password : $maskPwd)) : '',
    escapeshellarg($database),
    escapeshellarg($sqlFile)
);

if ($isDryRun) {
    echo "DRY RUN - prepared MySQL command (password masked unless --show-password):\n" . $mysqlCliCmd . "\n";
    echo "PDO DSN: mysql:host=$host;port=$port;dbname=$database;charset=utf8mb4\n";
    // Also print first 200 chars of the file so user knows what's inside
    $preview = substr(trim(file_get_contents($sqlFile)), 0, 200);
    echo "SQL file preview (first 200 chars):\n" . $preview . (strlen($preview) === 200 ? "...\n" : "\n");
    exit(0);
}

// Connect with PDO and execute statements (keeps original statement-by-statement logic)
try {
    $pdo = new PDO(
        "mysql:host={$host};port={$port};dbname={$database};charset=utf8mb4",
        $username,
        $password,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    echo "Connected to MySQL successfully!\n";

    $sql = file_get_contents($sqlFile);
    if ($sql === false) {
        die("Error reading SQL file.\n");
    }

    echo "Importing SQL file...\n";
    $lines = explode("\n", $sql);
    $tempLine = '';
    $statements = [];

    foreach ($lines as $line) {
        $trim = trim($line);
        // Skip comments and empty lines
        if (substr($trim, 0, 2) === '--' || $trim === '' || substr($trim, 0, 2) === '/*') {
            continue;
        }
        $tempLine .= $line . "\n";
        if (substr($trim, -1) === ';') {
            $statements[] = $tempLine;
            $tempLine = '';
        }
    }

    $count = 0;
    foreach ($statements as $statement) {
        $statement = trim($statement);
        if (!empty($statement)) {
            try {
                $pdo->exec($statement);
                $count++;
                echo ".";
            } catch (PDOException $e) {
                echo "\nError on statement: " . substr($statement, 0, 120) . "...\n";
                echo "Error: " . $e->getMessage() . "\n";
            }
        }
    }

    echo "\nSuccessfully imported $count SQL statements!\n";
} catch (PDOException $e) {
    fwrite(STDERR, "Error: " . $e->getMessage() . "\n");
    exit(1);
}
