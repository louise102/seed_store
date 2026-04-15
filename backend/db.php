<?php
$env_file = __DIR__ . '/.env';
$env = file_exists($env_file) ? parse_ini_file($env_file) : [];
$host = $env['DB_HOST'] ?? 'sql113.infinityfree.com';
$db   = $env['DB_NAME'] ?? 'if0_41631401_seeds_db';
$user = $env['DB_USER'] ?? 'if0_41631401';
$pass = $env['DB_PASS'] ?? 'KFaZCv72ShD';
$dsn  = "mysql:host=$host;dbname=$db;charset=utf8mb4";

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    die("DB Connection failed: " . $e->getMessage());
}

