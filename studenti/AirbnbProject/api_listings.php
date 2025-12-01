<?php
header('Content-Type: application/json');

ini_set('display_errors', 1);
error_reporting(E_ALL);


require 'db_connect.php';

$sql = "SELECT * FROM listings";

$result = $conn->query($sql);

$anunturi = [];

if ($result) {
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $anunturi[] = $row;
        }
    }
} else {
    echo json_encode(["error" => "Eroare SQL: " . $conn->error]);
    exit;
}

echo json_encode($anunturi, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

$conn->close();
?>