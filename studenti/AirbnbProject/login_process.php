<?php
session_start();
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") { // verificare metoda http ( doar prin post)
    //preluare date din form 
    $email = $_POST['email'];
    $password = $_POST['password'];
    // cautarea userului in DB 
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
// verificare parola 
    if ($user && password_verify($password, $user['password_hash'])) {
        // creare date de login 
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role_id'] = $user['role_id'];
        header("Location: index.php"); // redirect 
    } else {
        echo "Email sau parola gresita. <a href='login.php'>Incearca din nou</a>";
    }
}
?>