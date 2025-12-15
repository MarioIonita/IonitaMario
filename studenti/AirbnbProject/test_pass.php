<?php
$parola = 'qaedfghjk';
$hash = password_hash($parola, PASSWORD_DEFAULT);

echo "Parola: " . $parola . "<br>";
echo "Hash pentru DB: " . $hash;
?>