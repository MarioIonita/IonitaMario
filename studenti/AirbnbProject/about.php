<?php session_start(); ?>
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Despre noi — HomeEverywhere</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="index.css">
    
    <link rel="stylesheet" href="about.css"> 
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

    <main class="container" style="padding-top: 2rem;">
        <div class="about-card" style="background:white; padding:3rem; border-radius:12px; box-shadow:0 4px 12px rgba(0,0,0,0.05); text-align:center; max-width:700px; margin:0 auto;">
            
            <h1 style="margin-top:0;">Despre HomeEverywhere</h1>
            <p style="color:#666; line-height:1.6;">
               HomeEverywhere este o platforma dedicată tuturor celor care caută cazări accesibile și experiențe autentice.
            </p>

            <h3 style="margin-top:2rem;">Contact</h3>
            <ul style="list-style:none; padding:0; line-height:1.8;">
                <li>📧 <strong>Email:</strong> contact@homeeverywhere.com</li>
                <li>📞 <strong>Telefon:</strong> +40 722 123 456</li>
            </ul>

            <div style="margin-top:2rem; padding-top:2rem; border-top:1px solid #eee; color:#999; font-size:0.9rem;">
                Page created: October 13, 2025
            </div>

            <a href="index.php" class="btn primary" style="margin-top:2rem; display:inline-block;">← Înapoi