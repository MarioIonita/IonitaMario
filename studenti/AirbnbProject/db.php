<?php

$host = 'mysql'; 
$port = 3306;

$db   = getenv('DB_NAME');
$user = getenv('DB_USER');
$pass = getenv('DB_PASSWORD');

$charset = 'utf8mb4';
// data source name 
$dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";
// Setari pdo 
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,   // erorile devin exceptii - try/catch
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,     // query urile returneaza arrayuri asociative 
    PDO::ATTR_EMULATE_PREPARES   => false,              // prepared statements reale 
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    http_response_code(500);
    echo "Eroare conectare DB: " . $e->getMessage();
    exit();
}
