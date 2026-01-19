<?php
session_start();

$_SESSION = array(); // se sterg toate datele din sesiune 
//stergerea session cookies 
if (ini_get("session.use_cookies")) { // if exists
    $params = session_get_cookie_params(); // get params 
    setcookie(session_name(), '', time() - 42000, // setare in trecut : browserul il va sterge
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

session_destroy();

header("Location: index.php"); // redirect 
exit();
?>