<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Preluăm datele TEXT
    $title = htmlspecialchars($_POST['title']);
    $city = htmlspecialchars($_POST['city']);
    $price = $_POST['price'];
    $guests = $_POST['guests']; 
    $desc = htmlspecialchars($_POST['description']);
    $user_id = $_SESSION['user_id'];
    
    // facilitati 
    $facility_string = isset($_POST['facilities']) ? implode(", ", $_POST['facilities']) : "";

    try {
        // incepem o tranzactie  
        $pdo->beginTransaction();

        // inserare date
        $sql = "INSERT INTO listings (user_id, title, city, price, max_guests, description, facility, image_url) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$user_id, $title, $city, $price, $guests, $desc, $facility_string, '']);
        
        // Luam ID-ul anuntului creat
        $listing_id = $pdo->lastInsertId();

        // procesare imagini 
        $first_image_path = ""; // prima poza = coperta 
        
        if (isset($_FILES['images'])) {
            $total_files = count($_FILES['images']['name']);
            
            for ($i = 0; $i < $total_files; $i++) {
                // daca fisierul a fost uploadat
                if ($_FILES['images']['error'][$i] == 0) {
                    $tmp_name = $_FILES['images']['tmp_name'][$i];
                    $name = basename($_FILES['images']['name'][$i]);
                    
                    // generare nume unic : uploads/65a..._vacanta.jpg
                    $new_name = uniqid() . "_" . $name;
                    $target_path = "uploads/" . $new_name;
                    
                    if (move_uploaded_file($tmp_name, $target_path)) {
                        // Inserare in tabelul secundar listing_images
                        $stmt_img = $pdo->prepare("INSERT INTO listing_images (listing_id, image_url) VALUES (?, ?)");
                        $stmt_img->execute([$listing_id, $target_path]);

                        // Prima poza coperta 
                        if ($first_image_path == "") {
                            $first_image_path = $target_path;
                        }
                    }
                }
            }
        }

        // ACtualizam tabelul cu prima poza coperta 
        if ($first_image_path != "") {
            $update = $pdo->prepare("UPDATE listings SET image_url = ? WHERE id = ?");
            $update->execute([$first_image_path, $listing_id]);
        }

        $pdo->commit(); // Salvare
        header("Location: listings.php"); // redirect
        exit();

    } catch (Exception $e) {
        $pdo->rollBack(); // Anulam dacă apare eroare
        $_SESSION['error'] = "Eroare: " . $e->getMessage();
        header("Location: upload.php");
        exit();
    }
}
?>