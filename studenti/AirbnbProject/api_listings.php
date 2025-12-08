<?php
header('Content-Type: application/json');

ini_set('display_errors', 1);
error_reporting(E_ALL);

require 'db.php'; 

try {
    $stmt = $pdo->query("SELECT * FROM listings");
    $anunturi = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($anunturi, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

} catch (PDOException $e) {
    echo json_encode([
        "error" => "Eroare SQL: " . $e->getMessage()
    ]);
}
