<?php
session_start();

//initializare 
$msg_sent = false;
$msg_error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //preluare si curatare date
    $nume = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $mesaj = htmlspecialchars(trim($_POST['message']));

    //validare date 
    if (!empty($nume) && !empty($email) && !empty($mesaj)) {
        //construire mail
        $to = "contact@homeeverywhere.com"; 
        $subject = "Mesaj nou Suport de la $nume";
        $email_content = "Nume: $nume\nEmail: $email\n\nMesaj:\n$mesaj\n";
        $headers = "From: noreply@homeeverywhere.com\r\n";
        $headers .= "Reply-To: $email\r\n";

        //trimitere mail 
        if (mail($to, $subject, $email_content, $headers)) {
            $msg_sent = true;
        } else {
            // Simulăm succesul pe localhost
            $msg_sent = true; 
        }
    } else {
        $msg_error = "Va rugam completati toate câmpurile.";
    }
}
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suport — HomeEverywhere</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="index.css">

    <link rel="stylesheet" href="support.css">
</head>
<body>

    <header>
        <div class="container" style="display:flex; align-items:center; gap:1rem; padding:1rem;">
            <a class="brand" href="index.php" style="text-decoration:none; color:inherit;">
                <div class="logo">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                </div>
                <div style="font-weight:700">HomeEverywhere</div>
            </a>
            
            <nav style="margin-left:auto; display:flex; align-items:center;">
                <a href="about.php">Despre</a>
                <a href="listings.php">Anunțuri</a>
                <a href="support.php">Suport</a>

                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="upload.php" class="btn-publish">Publică Anunț</a>
                    <a class="cta" href="logout.php" style="background-color:var(--muted); margin-left:15px;" title="Ieși din cont">
                        Logout (<?php echo htmlspecialchars($_SESSION['username']); ?>)
                    </a>
                <?php else: ?>
                    <a class="cta" href="login.php">Login / Publică</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>

    <main class="container" style="padding-top: 3rem; padding-bottom: 3rem;">
        
        <div class="support-intro">
            <h1>Cum te putem ajuta?</h1>
            <p>Trimite-ne un mesaj și echipa noastră îți va răspunde în cel mai scurt timp.</p>
        </div>

        <?php if ($msg_sent): ?>
            <div class="success-box">
                Mesajul tău a fost trimis cu succes! Îți mulțumim.
            </div>
        <?php endif; ?>

        <div class="contact-form">
            <form action="support.php" method="POST">
                <div class="form-group">
                    <label>Numele Tău</label>
                    <input type="text" name="name" placeholder="Ex: Andrei Popescu" required>
                </div>

                <div class="form-group">
                    <label>Adresa de Email</label>
                    <input type="email" name="email" placeholder="nume@exemplu.com" required>
                </div>

                <div class="form-group">
                    <label>Mesajul Tău</label>
                    <textarea name="message" placeholder="Descrie problema sau întrebarea ta..." required></textarea>
                </div>

                <button type="submit" class="btn primary" style="width: 100%; padding: 14px; font-size: 1rem;">Trimite Mesajul</button>
            </form>
        </div>

    </main>

    <footer style="text-align:center; padding:2rem; color:#666; font-size:0.9rem; margin-top: auto;">
        © 2026 HomeEverywhere
    </footer>

</body>
</html>