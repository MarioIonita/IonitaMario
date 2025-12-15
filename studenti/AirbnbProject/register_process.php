<?php
session_start();
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = $_POST['username'];
    $email = $_POST['email'];
    $pass = $_POST['password'];
    
    // Verificăm dacă mailul există deja
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    
    if ($stmt->rowCount() > 0) {
        die("Eroare: Acest email este deja folosit. <a href='register.php'>Încearcă din nou</a>");
    }

    // Hashuim parola
    $hash = password_hash($pass, PASSWORD_DEFAULT);

    if (isset($_POST['tip_cont']) && $_POST['tip_cont'] === 'proprietar') {
    $role_id = 3; // ID-ul pentru Proprietar
} else {
    $role_id = 2; // Default: User simplu (Turist)
}

    $sql = "INSERT INTO users (username, email, password_hash, role_id, created_at) VALUES (?, ?, ?, ?, NOW())";
    $stmt = $pdo->prepare($sql);
    
    if ($stmt->execute([$user, $email, $hash, $role_id])) {
        // Logam userul direct
        $_SESSION['user_id'] = $pdo->lastInsertId();
        $_SESSION['username'] = $user;
        $_SESSION['role_id'] = $role_id;
        header("Location: index.php");
        exit();
    } else {
        echo "Ceva nu a mers.";
    }
}
?>