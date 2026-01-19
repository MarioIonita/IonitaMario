<?php
session_start();
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") { 
    //preluare input (trim pentru spatii ( previne erori))
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validări simple
    if (empty($username) || empty($email) || empty($password)) {
        $_SESSION['error'] = "Toate câmpurile sunt obligatorii!";
        header("Location: register.php");
        exit();
    }

    if ($password !== $confirm_password) {
        $_SESSION['error'] = "Parolele nu coincid!";
        header("Location: register.php");
        exit();
    }

    // Verificăm dacă există deja email-ul
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        $_SESSION['error'] = "Acest email este deja folosit.";
        header("Location: register.php");
        exit();
    }
 // hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $default_role = 2;  // standard role = user 

    try { // inserare in baza de date 
        $sql = "INSERT INTO users (username, email, password_hash, role_id) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$username, $email, $hashed_password, $default_role]);

        $_SESSION['success'] = "Cont creat cu succes! Te poți loga.";
        header("Location: login.php"); // redirect 
        exit();
    } catch (Exception $e) {
        $_SESSION['error'] = "Eroare tehnică: " . $e->getMessage();
        header("Location: register.php");
        exit();
    }
}
?>