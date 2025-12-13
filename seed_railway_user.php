<?php

$host = 'shortline.proxy.rlwy.net';
$port = 56871;
$database = 'railway';
$username = 'root';
$password = 'SUTzMXEzjpZDJzCrNGOUqfXCQPMLffwn';

$email = 'test@railway.local';
$name = 'Railway Tester';
$plainPassword = 'secret123';

try {
    $pdo = new PDO(
        "mysql:host=$host;port=$port;dbname=$database;charset=utf8mb4",
        $username,
        $password,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    echo "Connected to Railway MySQL!\n";

    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $exists = (bool) $stmt->fetchColumn();

    if ($exists) {
        echo "User with email $email already exists.\n";
        exit;
    }

    $hashed = password_hash($plainPassword, PASSWORD_BCRYPT);
    $insert = $pdo->prepare("INSERT INTO users (name, email, password, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())");
    $insert->execute([$name, $email, $hashed]);

    echo "Created user $email with password $plainPassword\n";
} catch (PDOException $e) {
    die("Error: " . $e->getMessage() . "\n");
}
