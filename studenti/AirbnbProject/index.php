<?php
session_start(); 
?>
<!doctype html>
<html lang="ro">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>HomeEverywhere — Cazări și experiențe</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="index.css"> 
</head>
<body>
    <header>
        <div class="container" style="display:flex;align-items:center;gap:1rem">
            <a class="brand" href="#">
                <div class="logo">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: white;">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                        <polyline points="9 22 9 12 15 12 15 22"></polyline>
                    </svg>
                </div>
                <div>
                    <div style="font-weight:700">HomeEverywhere</div>
                    <small style="color:var(--muted)">Cazări & experiențe</small>
                </div>
            </a>

            <nav style="margin-left:auto">
                <a href="about.php">Despre</a>
                <a href="listings.php">Anunturi</a>
                <a href="support.php">Suport</a>
                
                <?php if (isset($_SESSION['user_id'])): ?>
                    <span style="font-weight:600; margin-right:10px;">Salut, <?php echo htmlspecialchars($_SESSION['username']); ?>!</span>
                    <a class="cta" href="logout.php" style="background-color:var(--muted);">Logout</a>
                <?php else: ?>
                    <a class="cta" href="login.php">Login / Publică</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>

    <main class="container">
        <section class="search-hero">
            <div class="search-hero-card">
                <div>
                    <h1>Găsește locul perfect pentru următoarea ta călătorie</h1>
                    <p class="lead">Caută după oraș, tip de locuință sau experiență — rezervări rapide și gazde de încredere.</p>
                </div>

                <form id="searchForm" class="search-bar large" role="search" aria-label="Caută cazări">
                    <input id="searchInput" type="search" placeholder="Căutați oraș, adresă sau titlu (ex: Brașov, apartament central)" />
                    <button type="submit" aria-label="Caută">Caută</button>
                </form>

                <div style="display:flex;gap:.6rem;flex-wrap:wrap;margin-top:.6rem">
                    <button class="btn" onclick="filterQuick('Brașov')">Brașov</button>
                    <button class="btn" onclick="filterQuick('București')">București</button>
                    <button class="btn" onclick="filterQuick('Cluj')">Cluj</button>
                </div>
            </div>
        </section>

        <section class="section">
            <h2 style="margin:0">Cazări populare</h2>
            <p style="color:var(--muted);margin:.4rem 0 0 0">Selecție aleasă manual de gazdele noastre</p>

            <div id="listings" class="grid">
                <?php
                require 'db.php'; 

                // 1. Query Default (User normal vede doar active)
                $sql = "SELECT * FROM listings WHERE is_active = 1"; 

                // 2. Query Admin (Admin vede tot)
                if (isset($_SESSION['role_id']) && $_SESSION['role_id'] == 1) {
                    $sql = "SELECT * FROM listings"; 
                }

                try {
                    $stmt = $pdo->query($sql);
                    $listings = $stmt->fetchAll();
                } catch (Exception $e) {
                    echo "<p>Eroare SQL: " . $e->getMessage() . "</p>";
                    $listings = []; 
                }

                if (empty($listings)) {
                    echo "<p>Nu există anunțuri de afișat.</p>";
                } else {
                    // Folosim { } in loc de endforeach pentru a evita erorile de sintaxa
                    foreach ($listings as $row) {
                        $isActive = $row['is_active']; 
                        $cssClass = ($isActive == 0) ? 'card deactivated' : 'card';
                        $btnText = ($isActive == 0) ? 'Activează' : 'Dezactivează';
                        
                        // HTML-ul pentru Card
                        ?>
                        <article class="<?php echo $cssClass; ?>" 
                                 data-id="<?php echo $row['id']; ?>" 
                                 data-title="<?php echo htmlspecialchars($row['title']); ?>" 
                                 data-city="<?php echo htmlspecialchars($row['city']); ?>">
                            
                            <img src="<?php echo htmlspecialchars($row['image_url']); ?>" alt="Cazare" />
                            
                            <div class="info">
                                <div class="title"><?php echo htmlspecialchars($row['title']); ?></div>
                                <div class="meta">
                                    <span><?php echo htmlspecialchars($row['city']); ?></span>
                                    <span style="color:var(--muted)">4.9 ★</span>
                                </div>
                                
                                <div style="display:flex;justify-content:space-between;align-items:center">
                                    <div class="price"><?php echo htmlspecialchars($row['price']); ?> RON/noapte</div>
                                    <div class="actions">
                                        <button class="btn">Detalii</button>
                                        <button class="btn primary">Rezervă</button>
                                        
                                        <?php if (isset($_SESSION['role_id']) && $_SESSION['role_id'] == 1): ?>
                                            <button class="btn btn-deactivate"><?php echo $btnText; ?></button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </article>
                        <?php 
                    } // Inchidere foreach cu acolada
                } // Inchidere else cu acolada
                ?>
            </div>

            <div id="noResults" class="empty" style="display:none;margin-top:1rem">
                Nu s-au găsit rezultate. Încercați un alt cuvânt cheie.
            </div>
        </section>

        <section class="section">
            <h2 style="margin:0">Ce spun călătorii</h2>
            <div class="testimonials" style="margin-top:.8rem">
                <div class="testimonial">
                    <strong>Maria</strong>
                    <p style="margin:.4rem 0 .2rem 0;color:var(--muted)">"Gazda a fost foarte primitoare."</p>
                    <small>— București</small>
                </div>
            </div>
        </section>

        <footer>
            <div class="foot-grid">
                <div>
                    <strong>HomeEverywhere</strong>
                    <div style="margin-top:.4rem"><small>© <span id="year"></span> Toate drepturile rezervate</small></div>
                </div>
                <div style="display:flex;gap:1rem;align-items:center">
                    <a href="#" style="color:var(--muted);text-decoration:none">Contact</a>
                </div>
            </div>
        </footer>
    </main>

   <script>
    // 1. Functia de Dezactivare
    function handleDeactivation(event) {
        const card = event.target.closest('.card');
        if (!card) return;

        const listingId = card.getAttribute('data-id'); 
        const isCurrentlyDeactivated = card.classList.contains('deactivated');
        const newState = isCurrentlyDeactivated ? 'active' : 'deactivated'; 

        // Apelam fisierul PHP creat la Pasul 1
        fetch('toggle_listing.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id: listingId, status: newState })
        })
        .then(response => {
            // Verificam daca raspunsul e valid JSON
            if(!response.ok) throw new Error("Network response was not ok");
            return response.json();
        })
        .then(data => {
            if (data.success) {
                const isDeactivated = card.classList.toggle('deactivated');
                event.target.textContent = isDeactivated ? 'Activează' : 'Dezactivează';
                card.dataset.status = isDeactivated ? 'deactivated' : '';
                alert('Status actualizat cu succes!');
            } else {
                alert('Eroare: ' + (data.message || 'Nu s-a putut salva modificarea.'));
            }
        })
        .catch(err => {
            console.error(err);
            alert("Eroare tehnica: Verifică consola (F12) pentru detalii.");
        });
    }

    // 2. Initializare Butoane Admin
    document.addEventListener('DOMContentLoaded', () => {
        const deactivateButtons = document.querySelectorAll('.btn-deactivate');
        if (deactivateButtons.length > 0) {
            deactivateButtons.forEach(button => {
                button.addEventListener('click', handleDeactivation);
            });
        }
        
        // Setare an curent
        const yearEl = document.getElementById('year');
        if(yearEl) yearEl.textContent = new Date().getFullYear();
    });

    // 3. Logica de Căutare
    const form = document.getElementById('searchForm');
    const input = document.getElementById('searchInput');
    const listings = Array.from(document.querySelectorAll('#listings .card'));
    const noResults = document.getElementById('noResults');

    if(form) {
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            filterListings(input.value.trim().toLowerCase());
        });
    }

    function filterQuick(city){
        input.value = city;
        filterListings(city.toLowerCase());
    }

    function filterListings(q){
        let visible = 0;
        listings.forEach(card => {
            const title = card.getAttribute('data-title')?.toLowerCase() || '';
            const city = card.getAttribute('data-city')?.toLowerCase() || '';
            if(!q || title.includes(q) || city.includes(q)){
                card.style.display = '';
                visible++;
            } else {
                card.style.display = 'none';
            }
        });
        if(noResults) noResults.style.display = visible ? 'none' : 'block';
    }
</script>
</body>
</html>