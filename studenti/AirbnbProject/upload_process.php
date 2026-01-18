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
    $guests = $_POST['guests']; // Asigură-te că ai schimbat în max_guests în SQL dacă așa a rămas
    $desc = htmlspecialchars($_POST['description']);
    $user_id = $_SESSION['user_id'];
    
    // Facilități
    $facility_string = isset($_POST['facilities']) ? implode(", ", $_POST['facilities']) : "";

    try {
        // Începem o tranzacție (ca să fim siguri că totul se salvează corect)
        $pdo->beginTransaction();

        // 2. Inserăm anunțul (Fără poză momentan, o actualizăm imediat)
        // NOTĂ: Dacă în listings ai coloana 'max_guests', schimbă 'guests' cu 'max_guests' mai jos
        $sql = "INSERT INTO listings (user_id, title, city, price, max_guests, description, facility, image_url) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$user_id, $title, $city, $price, $guests, $desc, $facility_string, '']);
        
        // Luăm ID-ul anunțului tocmai creat
        $listing_id = $pdo->lastInsertId();

        // 3. Procesăm IMAGINILE (Loop prin fișiere)
        $first_image_path = ""; // Vom păstra prima poză ca "Copertă"
        
        if (isset($_FILES['images'])) {
            $total_files = count($_FILES['images']['name']);
            
            for ($i = 0; $i < $total_files; $i++) {
                // Verificăm dacă fișierul a fost uploadat
                if ($_FILES['images']['error'][$i] == 0) {
                    $tmp_name = $_FILES['images']['tmp_name'][$i];
                    $name = basename($_FILES['images']['name'][$i]);
                    
                    // Generăm nume unic: uploads/65a..._vacanta.jpg
                    $new_name = uniqid() . "_" . $name;
                    $target_path = "uploads/" . $new_name;
                    
                    if (move_uploaded_file($tmp_name, $target_path)) {
                        // a) Inserăm în tabelul secundar listing_images
                        $stmt_img = $pdo->prepare("INSERT INTO listing_images (listing_id, image_url) VALUES (?, ?)");
                        $stmt_img->execute([$listing_id, $target_path]);

                        // b) Reținem prima poză pentru copertă
                        if ($first_image_path == "") {
                            $first_image_path = $target_path;
                        }
                    }
                }
            }
        }

        // 4. Actualizăm tabelul listings cu poza de copertă
        if ($first_image_path != "") {
            $update = $pdo->prepare("UPDATE listings SET image_url = ? WHERE id = ?");
            $update->execute([$first_image_path, $listing_id]);
        }

        $pdo->commit(); // Salvăm totul
        header("Location: listings.php");
        exit();

    } catch (Exception $e) {
        $pdo->rollBack(); // Anulăm dacă apare eroare
        $_SESSION['error'] = "Eroare: " . $e->getMessage();
        header("Location: upload.php");
        exit();
    }
}
?>