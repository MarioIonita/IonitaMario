<?php

$host = 'mysql'; 

$db   = getenv('DB_NAME');

$user = getenv('DB_USER');
$pass = getenv('DB_PASSWORD'); 



$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die(json_encode(["error" => "Conexiune eșuată: " . $conn->connect_error]));
}
$conn->set_charset("utf8mb4"); // diacritice 
?>