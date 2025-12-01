<?php
header('Content-Type: application/json');

// (Opțional) Activăm raportarea erorilor ca să vedem dacă greșim ceva la cod
ini_set('display_errors', 1);
error_reporting(E_ALL);

// 2. Includem fișierul de conectare pe care l-ai făcut deja
// Acesta deschide "ușa" către baza de date
require 'db_connect.php';

// 3. Scriem comanda SQL
// Selectăm toate coloanele (*) din tabelul listings
$sql = "SELECT * FROM listings";

// 4. Trimitem comanda la baza de date
$result = $conn->query($sql);

$anunturi = [];

// 5. Verificăm dacă am primit rezultate
if ($result) {
    if ($result->num_rows > 0) {
        // Luăm fiecare rând din baza de date și îl punem în lista noastră
        while($row = $result->fetch_assoc()) {
            $anunturi[] = $row;
        }
    }
} else {
    // Dacă interogarea a eșuat (ex: tabelul nu există), trimitem eroarea
    echo json_encode(["error" => "Eroare SQL: " . $conn->error]);
    exit;
}

// 6. Trimitem lista finală ca JSON către JavaScript
echo json_encode($anunturi, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

// 7. Închidem conexiunea
$conn->close();
?>