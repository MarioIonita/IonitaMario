<?php
session_start();
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['user_id'])) {  // verificare de securitate : doar post requesturi &  userii trebuie sa fie logati
 //preluare date din formular
    $user_id = $_SESSION['user_id'];
    $listing_id = $_POST['listing_id'];
    $check_in = $_POST['check_in'];
    $check_out = $_POST['check_out'];
    $guests = $_POST['guests'];
    $total_price = $_POST['total_price'];
    $status = 'confirmed'; // Implicit o confirmăm

    try { // inserare in baza de date
        $sql = "INSERT INTO bookings (user_id, listing_id, check_in, check_out, guests, total_price, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
                // securizarea inserarii ( prepared statements )
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$user_id, $listing_id, $check_in, $check_out, $guests, $total_price, $status]); // executa query ul si inlocuieste ? cu user_id,, listing_id, etc 

        // Redirect la Index cu mesaj de succes 
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