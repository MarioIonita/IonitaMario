<?php
session_start();
require 'db.php';
?>
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listings — HomeEverywhere</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="listings.css">
    <link rel="stylesheet" href="index.css"> 
</head>
<body>
    <header>
        <div class="container" style="display:flex;align-items:center;gap:1rem">
            <a class="brand" href="index.php">
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

                            <nav style="margin-left:auto; display:flex; align-items:center;">
                                <a href="about.php">Despre</a>
                                <a href="listings.php">Anunțuri</a>
                                <a href="support.php">Suport</a> <?php if (isset($_SESSION['user_id'])): ?>
                                    
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

    <main role="main" class="container">
        <h1 style="text-align:center;margin-top:2rem;">Toate Anunțurile</h1>

        <div class="listing-controls" style="text-align:center; margin-bottom:2rem;">
            <button class="btn" id="filterToggleBtn">Filtrează</button>
            <button class="btn" id="sortAscBtn">Sortează (Preț Cresc.)</button>
            <button class="btn" id="sortDescBtn">Sortează (Preț Desc.)</button>
        </div>

        <section id="filterFormContainer" class="filter-container" style="display: none; background: #fff; padding: 1rem; border-radius: 8px; margin-bottom: 2rem; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
            <form id="filterForm" class="filter-form" style="display:flex; gap:1rem; flex-wrap:wrap; justify-content:center; align-items: flex-end;">
                <div>
                    <label for="minPrice" style="display:block; margin-bottom:0.5rem; font-size:0.9rem;">Preț Minim</label>
                    <input type="number" id="minPrice" placeholder="0" style="padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                </div>
                <div>
                    <label for="maxPrice" style="display:block; margin-bottom:0.5rem; font-size:0.9rem;">Preț Maxim</label>
                    <input type="number" id="maxPrice" placeholder="1000" style="padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                </div>
                <div>
                    <label for="citySelect" style="display:block; margin-bottom:0.5rem; font-size:0.9rem;">Oraș</label>
                    <select id="citySelect" style="padding: 8px; border: 1px solid #ddd; border-radius: 4px; min-width: 150px;">
                        <option value="">Toate orașele</option>
                    </select>
                </div>
                <div class="filter-actions">
                    <button type="button" id="applyFilterBtn" class="btn primary">Aplică</button>
                    <button type="button" id="resetFilterBtn" class="btn">Reset</button>
                </div>
            </form>
        </section>

        <section class="listings grid" id="listingsContainer">
            <?php
            // Logică SQL
            $sql = "SELECT * FROM listings WHERE is_active = 1";
            if (isset($_SESSION['role_id']) && $_SESSION['role_id'] == 1) {
                $sql = "SELECT * FROM listings";
            }

            try {
                $stmt = $pdo->query($sql);
                $all_listings = $stmt->fetchAll();
            } catch (Exception $e) {
                $all_listings = [];
                echo "<p style='text-align:center'>Eroare conexiune: " . $e->getMessage() . "</p>";
            }

            if (empty($all_listings)) {
                echo "<p style='text-align:center'>Nu există anunțuri.</p>";
            }

            foreach ($all_listings as $row): 
                $isActive = $row['is_active']; 
                $cssClass = ($isActive == 0) ? 'card deactivated' : 'card';
                $btnText = ($isActive == 0) ? 'Activează' : 'Dezactivează';
            ?>
                <article class="<?php echo $cssClass; ?>" 
                         data-id="<?php echo $row['id']; ?>" 
                         data-city="<?php echo htmlspecialchars($row['city']); ?>" 
                         data-price="<?php echo $row['price']; ?>"
                         data-title="<?php echo htmlspecialchars($row['title']); ?>">
                    
                    <div class="img-ratio-box">
                        <img src="<?php echo htmlspecialchars($row['image_url']); ?>" alt="Cazare" class="standard-img" />
                    </div>
                    
                    <div class="info">
                        <div class="title"><?php echo htmlspecialchars($row['title']); ?></div>
                        <div class="meta">
                            <span><?php echo htmlspecialchars($row['city']); ?></span>
                            <span <?php echo number_format($row['rating'], 1); ?></span>
                        </div>
                        
                        <div style="display:flex;justify-content:space-between;align-items:center">
                            <div class="price"><?php echo $row['price']; ?> RON/noapte</div>
                            <div class="actions">
                                <a href="details.php?id=<?php echo $row['id']; ?>" class="btn">Detalii</a>
                                <a href="details.php?id=<?php echo $row['id']; ?>#rezerva" class="btn primary">
    Rezervă
</a>
                                
                                <?php if (isset($_SESSION['role_id']) && $_SESSION['role_id'] == 1): ?>
                                    <button class="btn btn-deactivate"><?php echo $btnText; ?></button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </section>

        <p style="text-align:center; margin-top: 2rem;">
            <a href="index.php" class="btn">Înapoi</a>
        </p>
    </main>

    <footer style="text-align:center; padding:2rem; color:#666; font-size:0.9rem; margin-top:auto;">
    © 2026 HomeEverywhere
</footer>

    <script>
    // 1. Funcția de Dezactivare 
    function handleDeactivation(event) {
        const card = event.target.closest('.card'); //selectare card (care trebuie dezactivat)
        if (!card) return;

        const listingId = card.getAttribute('data-id'); 
        const isCurrentlyDeactivated = card.classList.contains('deactivated');
        const newState = isCurrentlyDeactivated ? 'active' : 'deactivated';  // inversare stare
        
        // se trimit datele catre backend in format json
        fetch('toggle_listing.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id: listingId, status: newState })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) { // daca DB a fost modificata corect se modifica DOM ul 
                const isDeactivated = card.classList.toggle('deactivated');
                event.target.textContent = isDeactivated ? 'Activează' : 'Dezactivează';
                alert('Status actualizat permanent!');
            } else {
                alert('Eroare la server.');
            }
        })
        .catch(err => console.error('Eroare:', err));
    }

    document.addEventListener('DOMContentLoaded', () => { // sincronizare DOM 
        const listingsContainer = document.getElementById('listingsContainer');
        const allCards = Array.from(document.querySelectorAll('.card'));
        
        // Data mapping ( DOM -> date)
        let cardData = allCards.map(card => ({
            element: card,
            city: card.dataset.city,
            price: parseInt(card.dataset.price, 10),
            title: card.dataset.title
        }));

        // Activare butoane Admin
        document.querySelectorAll('.btn-deactivate').forEach(btn => {
            btn.addEventListener('click', handleDeactivation); // legare buton de handleDeactivation
        });

        // Setare An Footer
        const yearEl = document.getElementById('year');
        if(yearEl) yearEl.textContent = new Date().getFullYear();

        // --- Logica Filtrare ---
        const citySelect = document.getElementById('citySelect');
        const cities = [...new Set(cardData.map(c => c.city))].sort();
        cities.forEach(city => {
            const opt = document.createElement('option');
            opt.value = city;
            opt.textContent = city;
            citySelect.appendChild(opt);
        });

        document.getElementById('applyFilterBtn').addEventListener('click', () => { // preluarea criteriilor 
            const min = parseInt(document.getElementById('minPrice').value) || 0;
            const max = parseInt(document.getElementById('maxPrice').value) || Infinity;
            const city = citySelect.value;

            cardData.forEach(card => { // verificam fiecare card in parte 
                const matchPrice = card.price >= min && card.price <= max;
                const matchCity = !city || card.city === city;
                card.element.style.display = (matchPrice && matchCity) ? '' : 'none';
            });
        });

        document.getElementById('resetFilterBtn').addEventListener('click', () => {
            document.getElementById('filterForm').reset();
            cardData.forEach(c => c.element.style.display = ''); // new order display
        });

        // --- Logica Sortare ---
        function sortListings(dir) {
            cardData.sort((a, b) => (dir === 'asc' ? a.price - b.price : b.price - a.price)); // asc mic mare / desc mare mic 
            listingsContainer.innerHTML = '';
            cardData.forEach(c => listingsContainer.appendChild(c.element)); // reordonarea elementelor 
        }

        document.getElementById('sortAscBtn').addEventListener('click', () => sortListings('asc'));
        document.getElementById('sortDescBtn').addEventListener('click', () => sortListings('desc'));

        document.getElementById('filterToggleBtn').addEventListener('click', () => {
            const container = document.getElementById('filterFormContainer');
            container.style.display = container.style.display === 'none' ? 'block' : 'none';
        });
    });
    </script>
</body>
</html>