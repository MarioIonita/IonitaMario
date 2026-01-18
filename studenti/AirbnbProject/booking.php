<?php
session_start();
require 'db.php';

// Verificăm dacă userul e logat
if (!isset($_SESSION['user_id'])) {
    // Îl trimitem la login, dar ținem minte unde voia să ajungă (UX Trick)
    $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
    header("Location: login.php");
    exit();
}

// Preluăm datele din URL
$listing_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$check_in = isset($_GET['check_in']) ? $_GET['check_in'] : date('Y-m-d');
$check_out = isset($_GET['check_out']) ? $_GET['check_out'] : date('Y-m-d', strtotime('+1 day'));
$guests = isset($_GET['guests']) ? (int)$_GET['guests'] : 1;

// Luăm detaliile cazării din DB
$stmt = $pdo->prepare("SELECT * FROM listings WHERE id = ?");
$stmt->execute([$listing_id]);
$listing = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$listing) {
    die("Eroare: Anunțul nu a fost găsit.");
}

$date1 = new DateTime($check_in);
$date2 = new DateTime($check_out);
$interval = $date1->diff($date2);
$nights = $interval->days;

// Validare simplă: Minim o noapte
if ($nights < 1) { $nights = 1; }

$total_price = $listing['price'] * $nights;
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>Confirmă Rezervarea</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="index.css">
</head>
<body style="background-color:#f7f7f7;">

    <header style="background:white; border-bottom:1px solid #eee;">
        <div class="container" style="padding:1rem;">
            <a href="index.php" style="font-weight:700; text-decoration:none; color:#000;">← Înapoi</a>
        </div>
    </header>

    <main class="container" style="margin-top:3rem; max-width:900px;">
        <h1 style="margin-bottom:2rem;">Confirmă și plătește</h1>
        
        <div style="display:flex; gap:3rem; flex-wrap:wrap;">
            
            <div style="flex:1; min-width:300px;">
                <div style="background:white; padding:2rem; border-radius:12px; box-shadow:0 2px 10px rgba(0,0,0,0.05);">
                    <h3>Călătoria ta</h3>
                    
                    <div style="margin-bottom:1.5rem;">
                        <strong>Perioada:</strong><br>
                        <?php echo $check_in; ?> — <?php echo $check_out; ?> 
                        <span style="color:#666;">(<?php echo $nights; ?> nopți)</span>
                    </div>

                    <div style="margin-bottom:1.5rem;">
                        <strong>Oaspeți:</strong><br>
                        <?php echo $guests; ?> persoane
                    </div>

                    <hr style="border:0; border-top:1px solid #eee; margin:1.5rem 0;">

                    <form action="booking_process.php" method="POST">
                        <input type="hidden" name="listing_id" value="<?php echo $listing_id; ?>">
                        <input type="hidden" name="check_in" value="<?php echo $check_in; ?>">
                        <input type="hidden" name="check_out" value="<?php echo $check_out; ?>">
                        <input type="hidden" name="guests" value="<?php echo $guests; ?>">
                        <input type="hidden" name="total_price" value="<?php echo $total_price; ?>">
                        
                        <button type="submit" class="btn primary" style="width:100%; padding:15px; font-size:1.1rem; background:#ff385c; color:white; border:none; border-radius:8px; cursor:pointer;">
                            Confirmă Rezervarea
                        </button>
                    </form>
                </div>
            </div>

            <div style="width:350px;">
                <div style="background:white; padding:1.5rem; border-radius:12px; border:1px solid #ddd;">
                    <div style="display:flex; gap:15px; margin-bottom:1rem;">
                        <img src="<?php echo htmlspecialchars($listing['image_url']); ?>" style="width:100px; height:80px; object-fit:cover; border-radius:8px;">
                        <div>
                            <div style="font-size:0.9rem; color:#666;"><?php echo htmlspecialchars($listing['city']); ?></div>
                            <div style="font-weight:600; font-size:0.95rem;"><?php echo htmlspecialchars($listing['title']); ?></div>
                        </div>
                    </div>
                    
                    <hr style="border:0; border-top:1px solid #eee; margin:1rem 0;">
                    
                    <h3 style="margin-top:0;">Detalii preț</h3>
                    <div style="display:flex; justify-content:space-between; margin-bottom:0.5rem;">
                        <span><?php echo $listing['price']; ?> RON x <?php echo $nights; ?> nopți</span>
                        <span><?php echo $listing['price'] * $nights; ?> RON</span>
                    </div>
                    
                    <hr style="border:0; border-top:1px solid #eee; margin:1rem 0;">
                    
                    <div style="display:flex; justify-content:space-between; font-weight:700; font-size:1.2rem;">
                        <span>Total</span>
                        <span><?php echo $total_price; ?> RON</span>
                    </div>
                </div>
            </div>

        </div>
    </main>
</body>
</html>