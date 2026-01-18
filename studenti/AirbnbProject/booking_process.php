<?php
session_start();
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $listing_id = $_POST['listing_id'];
    $check_in = $_POST['check_in'];
    $check_out = $_POST['check_out'];
    $guests = $_POST['guests'];
    $total_price = $_POST['total_price'];
    $status = 'confirmed'; // Implicit o confirmăm

    try {
        $sql = "INSERT INTO bookings (user_id, listing_id, check_in, check_out, guests, total_price, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$user_id, $listing_id, $check_in, $check_out, $guests, $total_price, $status]);

        // Redirect la Index cu mesaj de succes (îl putem prinde cu JS sau sesiune)
        // Poți crea și o pagină "success.php"
        echo "<script>
            alert('Rezervare realizată cu succes! Te așteptăm.');
            window.location.href='index.php';
        </script>";

    } catch (Exception $e) {
        die("Eroare la rezervare: " . $e->getMessage());
    }
} else {
    header("Location: index.php");
    exit();
}
?>