<?php
session_start();
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['user_id'])) {
    $listing_id = $_POST['listing_id'];
    $user_id = $_SESSION['user_id'];
    $rating = (int)$_POST['rating'];
    $comment = htmlspecialchars($_POST['comment']);

    try {
        // 1. Inserăm recenzia nouă
        $stmt = $pdo->prepare("INSERT INTO reviews (listing_id, user_id, rating, comment) VALUES (?, ?, ?, ?)");
        $stmt->execute([$listing_id, $user_id, $rating, $comment]);

        // 2. MATEMATICĂ: Calculăm noua medie și numărul total de recenzii
        $stmt_calc = $pdo->prepare("
            SELECT COUNT(*) as total, AVG(rating) as media 
            FROM reviews 
            WHERE listing_id = ?
        ");
        $stmt_calc->execute([$listing_id]);
        $stats = $stmt_calc->fetch(PDO::FETCH_ASSOC);

        $new_count = $stats['total'];
        $new_rating = number_format($stats['media'], 1); // Rotunjim la 1 zecimală (ex: 4.7)

        // 3. Actualizăm tabelul listings (Cache)
        $update = $pdo->prepare("UPDATE listings SET rating = ?, review_count = ? WHERE id = ?");
        $update->execute([$new_rating, $new_count, $listing_id]);

        header("Location: details.php?id=" . $listing_id . "&success=1");
        exit();

    } catch (Exception $e) {
        die("Eroare: " . $e->getMessage());
    }
} else {
    header("Location: index.php");
    exit();
}
?>