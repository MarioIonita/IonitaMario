<?php
session_start();
require 'db.php';
// control de acces / autorizare 
if (!isset($_SESSION['role_id']) || $_SESSION['role_id'] != 1) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}
//citirea datelor din fetch (index.php)
$data = json_decode(file_get_contents('php://input'), true);
$listing_id = $data['id'];
// string to int 
$new_status = $data['status'] === 'deactivated' ? 0 : 1; // 0 = Inactiv, 1 = Activ

try {
    //update in DB 
    $stmt = $pdo->prepare("UPDATE listings SET is_active = ? WHERE id = ?");
    $stmt->execute([$new_status, $listing_id]);
    //raspuns JSON
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>