<?php
session_start();
require 'db.php';

// Validare ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: listings.php"); exit;
}
$id = $_GET['id'];

try {
    // se ia anuntul din baza de date 
    $stmt = $pdo->prepare("SELECT * FROM listings WHERE id = ?");
    $stmt->execute([$id]);
    $listing = $stmt->fetch();
    // daca id ul nu exista 
    if (!$listing) die("Anunț inexistent.");

    
    // Căutăm toate rezervările care nu s-au terminat încă
    $stmt_b = $pdo->prepare("SELECT check_in, check_out FROM bookings WHERE listing_id = ?");
    $stmt_b->execute([$id]);
    $existing_bookings = $stmt_b->fetchAll(PDO::FETCH_ASSOC);

    // Formăm lista pentru Calendar (Array de obiecte {from, to})
    $blocked_dates = [];
    foreach ($existing_bookings as $b) {
        $blocked_dates[] = [
            'from' => $b['check_in'],
            'to'   => $b['check_out']
        ];
    }

} catch (Exception $e) { die("Eroare SQL: " . $e->getMessage()); }
// pregatire descriere
$desc = $listing['description'] ?: "Descriere indisponibilă momentan.";
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($listing['title']); ?></title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <link rel="stylesheet" href="index.css">
    <link rel="stylesheet" href="details.css"> 

   
</head>
<body>

    <header>
        <div class="container" style="display:flex;align-items:center;gap:1rem; padding:1rem;">
            <a class="brand" href="index.php" style="text-decoration:none; color:inherit;">
                <div class="logo"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg></div>
                <div style="font-weight:700">HomeEverywhere</div>
            </a>
            <nav style="margin-left:auto; display:flex; align-items:center;">
                <a href="about.php">Despre</a>
                <a href="listings.php">Anunțuri</a>
                <a href="support.php">Suport</a> 
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="upload.php" class="btn-publish">Publică Anunț</a>
                    <a class="cta" href="logout.php" style="background-color:var(--muted); margin-left:15px;">Logout (<?php echo htmlspecialchars($_SESSION['username']); ?>)</a>
                <?php else: ?>
                    <a class="cta" href="login.php">Login / Publică</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>

    <main class="container" style="padding-top:1rem;">
        
        <h1 style="margin-bottom:0.5rem;"><?php echo htmlspecialchars($listing['title']); ?></h1>
        <p style="color:var(--muted); margin-top:0;">
    📍 <?php echo htmlspecialchars($listing['city']); ?> • 
    <strong>
        <?php  // afisare rating 
        if ($listing['review_count'] > 0) {
            echo "★ " . $listing['rating'] . " (" . $listing['review_count'] . " recenzii)";
        } else {
            echo "★ Nou (Fără recenzii)";
        }
        ?>
    </strong>
</p>

        <div class="swiper mySwiper">
            <div class="swiper-wrapper">
               <?php
                // Lista de imagini 
                $all_slides = [];

                // 1. Punem PRIMA dată poza principală (cea sigură, de pe Index)
                if (!empty($listing['image_url'])) {
                    $all_slides[] = $listing['image_url'];
                }

                // 2. Căutăm restul pozelor în galeria nouă
                $stmt_imgs = $pdo->prepare("SELECT image_url FROM listing_images WHERE listing_id = ?");
                $stmt_imgs->execute([$listing['id']]);
                $gallery = $stmt_imgs->fetchAll(PDO::FETCH_ASSOC);

                foreach ($gallery as $img) {
                    // Le adăugăm în listă
                    $all_slides[] = $img['image_url'];
                }

                // 3. Eliminăm duplicatele (ca să nu apară aceeași poză de 2 ori)
                $unique_slides = array_unique($all_slides);

                // 4. AFIȘAREA FINALĂ
                if (count($unique_slides) > 0) {
                    foreach ($unique_slides as $url) {
                        echo '<div class="swiper-slide">';
                        echo '<img src="' . htmlspecialchars($url) . '" alt="Imagine Cazare">';
                        echo '</div>';
                    }
                } else {
                    // Caz extrem: Nicio poză nicăieri -> Punem una standard
                    echo '<div class="swiper-slide">';
                    echo '<img src="https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?q=80&w=2070&auto=format&fit=crop" alt="Demo">';
                    echo '</div>';
                }
                ?>
            </div>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
            <div class="swiper-pagination"></div>
        </div>

        <div class="details-container">
            
            <section style="flex: 2;"> 
                <div style="display:flex; justify-content:space-between; border-bottom:1px solid #eee; padding-bottom:1rem; margin-bottom:1rem;">
                    <h3>Gazdă: <?php echo htmlspecialchars($_SESSION['username'] ?? 'Superhost'); ?></h3>
                    <span>Max <?php echo $listing['max_guests'] ?? 2; ?> Oaspeți</span>
                </div>
                
                <p style="line-height:1.6; color:#333; font-size:1.05rem;">
                    <?php echo nl2br(htmlspecialchars($desc)); ?>
                </p>

                <div style="margin-top:2rem;">
                    <h3>Ce oferă acest loc</h3>
                    <div style="display:flex; flex-wrap:wrap; gap:10px; margin-top:15px;">
                        <?php 
                        $facilities_data = isset($listing['facility']) ? $listing['facility'] : '';
                        $facilities_array = explode(", ", $facilities_data);
                        $icons_map = [ 
                            "Wifi"=>"📶 Wi-Fi", "Parcare"=>"🅿️ Parcare", "Piscina"=>"🏊 Piscină", 
                            "Aer Conditionat"=>"❄️ AC", "Bucatarie"=>"🍳 Bucătărie", "Balcon"=>"🌅 Balcon" 
                        ];
                        
                        $found_any = false;
                        foreach ($facilities_array as $item) {
                            $item = trim($item);
                            if (array_key_exists($item, $icons_map)) {
                                $found_any = true;
                                echo '<div class="tag">' . $icons_map[$item] . '</div>';
                            }
                        }
                        if (!$found_any) echo '<span style="color:#888; font-style:italic">Nu sunt specificate facilități.</span>';
                        ?>
                    </div>
                </div>
                <div style="margin-top:4rem; border-top:1px solid #eee; padding-top:2rem;">
    <h2>Recenzii</h2>

    <?php if (isset($_SESSION['user_id'])): ?>
        <div style="background:#f9f9f9; padding:20px; border-radius:12px; margin-bottom:2rem;">
            <h4>Lasă o recenzie</h4>
            <form action="submit_review.php" method="POST">
                <input type="hidden" name="listing_id" value="<?php echo $listing['id']; ?>">
                
                <div style="margin-bottom:10px;">
                    <label>Nota ta:</label>
                    <select name="rating" required style="padding:5px; border-radius:5px;">
                        <option value="5">★★★★★ (5 - Excelent)</option>
                        <option value="4">★★★★☆ (4 - Foarte bun)</option>
                        <option value="3">★★★☆☆ (3 - Ok)</option>
                        <option value="2">★★☆☆☆ (2 - Slab)</option>
                        <option value="1">★☆☆☆☆ (1 - Groaznic)</option>
                    </select>
                </div>

                <div style="margin-bottom:10px;">
                    <textarea name="comment" rows="3" placeholder="Cum a fost șederea ta?" required style="width:100%; padding:10px; border:1px solid #ddd; border-radius:8px;"></textarea>
                </div>

                <button type="submit" class="btn primary" style="background:#000; color:fff; border:none; padding:10px 20px; border-radius:8px; cursor:pointer;">
                    Publică Recenzia
                </button>
            </form>
        </div>
    <?php else: ?>
        <p><em>Trebuie să fii <a href="login.php" style="color:#ff385c;">logat</a> pentru a lăsa o recenzie.</em></p>
    <?php endif; ?>

    <div class="reviews-list">
        <?php
        // Luăm recenziile din DB + Numele utilizatorului
        $stmt_r = $pdo->prepare("
            SELECT r.*, u.username 
            FROM reviews r 
            JOIN users u ON r.user_id = u.id 
            WHERE r.listing_id = ? 
            ORDER BY r.created_at DESC
        ");
        $stmt_r->execute([$listing['id']]);
        $reviews = $stmt_r->fetchAll(PDO::FETCH_ASSOC);

        if (count($reviews) > 0) {
            foreach ($reviews as $rev) {
                // display stelute in functie de rating 
                $stars = str_repeat("★", $rev['rating']) . str_repeat("☆", 5 - $rev['rating']);
                
                echo '
                <div style="margin-bottom:1.5rem;">
                    <div style="font-weight:700;">' . htmlspecialchars($rev['username']) . '</div>
                    <div style="font-size:0.85rem; color:#666; margin-bottom:5px;">
                        ' . date('d M Y', strtotime($rev['created_at'])) . '
                    </div>
                    <div style="color:#ff385c; margin-bottom:5px;">' . $stars . '</div>
                    <div style="color:#333;">' . nl2br(htmlspecialchars($rev['comment'])) . '</div>
                </div>
                <hr style="border:0; border-top:1px solid #eee; margin:1rem 0;">
                ';
            }
        } else {
            echo '<p style="color:#666;">Încă nu există recenzii pentru această proprietate. Fii primul!</p>';
        }
        ?>
    </div>
</div>
            </section>

            <aside style="flex: 1; position: sticky; top: 20px;"> 
                <div class="booking-card" style="background:white; padding:2rem; border-radius:12px; box-shadow:0 6px 16px rgba(0,0,0,0.12); border:1px solid #ddd;">
                    
                    <div style="display:flex; justify-content:space-between; align-items:baseline; margin-bottom:1.5rem;">
                        <div>
                            <span style="font-size:1.5rem; font-weight:700; color:#222;">
                                <?php echo $listing['price']; ?> RON
                            </span>
                            <span style="color:#666;"> / noapte</span>
                        </div>
                    </div>

                    <form action="booking.php" method="GET" id="bookingForm">
                        <input type="hidden" name="id" value="<?php echo $listing['id']; ?>">
                        <input type="hidden" name="check_in" id="realCheckIn" required>
                        <input type="hidden" name="check_out" id="realCheckOut" required>

                        <div style="border:1px solid #ccc; border-radius:8px; margin-bottom:1rem; padding:5px;">
                            <input id="datePicker" type="text" placeholder="Alege perioada" 
                                   style="width:100%; border:none; outline:none; text-align:center; padding:10px; cursor:pointer;" readonly>
                        </div>

                        <div style="margin-bottom:1rem; border:1px solid #ccc; border-radius:8px; padding:10px;">
                            <label style="font-size:0.7rem; font-weight:700; display:block;">OASPEȚI</label>
                            <select name="guests" style="width:100%; border:none; outline:none; background:white;">
                                <?php 
                                $max = isset($listing['max_guests']) ? $listing['max_guests'] : 2;
                                for($i=1; $i<=$max; $i++): 
                                ?>
                                    <option value="<?php echo $i; ?>"><?php echo $i; ?> Oaspeți</option>
                                <?php endfor; ?>
                            </select>
                        </div>

                        <button type="submit" class="btn primary" style="width:100%; padding:14px; background-color:#ff385c; color:white; border:none; border-radius:8px; font-weight:600; cursor:pointer;">
                            Rezervă acum
                        </button>
                    </form>
                </div>
            </aside>    
        </div>
    </main>

    <footer style="text-align:center; padding:2rem; color:#666; font-size:0.9rem; margin-top:auto;">
        © 2026 HomeEverywhere
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

   <script>
    document.addEventListener('DOMContentLoaded', function() {
        
        // 1. SlideShow
        var swiper = new Swiper(".mySwiper", {
            loop: true,
            effect: "fade",
            navigation: { nextEl: ".swiper-button-next", prevEl: ".swiper-button-prev" },
            pagination: { el: ".swiper-pagination", clickable: true },
            autoplay: { delay: 4500, disableOnInteraction: false }
        });

        // 2. CALENDAR 
        const blockedDates = <?php echo json_encode($blocked_dates); ?>;

        // Salvăm calendarul 
        const fp = flatpickr("#datePicker", {
            mode: "range", 
            minDate: "today",
            dateFormat: "Y-m-d",
            altInput: true,
            altFormat: "d F, Y",
            disable: blockedDates,
            
            onChange: function(selectedDates, dateStr, instance) {
                if (selectedDates.length === 2) {
                    document.getElementById('realCheckIn').value = instance.formatDate(selectedDates[0], "Y-m-d");
                    document.getElementById('realCheckOut').value = instance.formatDate(selectedDates[1], "Y-m-d");
                }
            }
        });

        // daca se apasa "Rezerva" 
        if (window.location.hash === '#rezerva') {
            // Așteptăm 500ms să se randeze pagina complet
            setTimeout(function() {
                // Ducem userul la formular 
                document.getElementById('bookingForm').scrollIntoView({ behavior: 'smooth' });
                // Și deschidem calendarul automat
                fp.open(); 
            }, 500);
        }

        // 3. VALIDARE
        document.getElementById('bookingForm').addEventListener('submit', function(e) {
            const checkIn = document.getElementById('realCheckIn').value;
            const checkOut = document.getElementById('realCheckOut').value;
            if (!checkIn || !checkOut) {
                e.preventDefault();
                alert("Selectează perioada!");
                // Dacă uită să selecteze, redeschidem calendarul
                fp.open();
            }
        });
    });
</script>
</body>
</html>