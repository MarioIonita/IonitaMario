<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listings — AirbnbProject</title>
    <link rel="stylesheet" href="listings.css">
</head>
<body>
    <main role="main">
        <h1 style="text-align:center;margin-top:2rem;">Listings</h1>

        <div class="listing-controls">
            <button class="btn" id="filterToggleBtn">Filtrează</button>
            <button class="btn" id="sortAscBtn">Sortează (Preț Cresc.)</button>
            <button class="btn" id="sortDescBtn">Sortează (Preț Desc.)</button>
        </div>

        <section id="filterFormContainer" class="filter-container" style="display: none;">
            <form id="filterForm" class_="filter-form">
                <div>
                    <label for="minPrice">Preț Minim (RON)</label>
                    <input type="number" id="minPrice" placeholder="0">
                </div>
                <div>
                    <label for="maxPrice">Preț Maxim (RON)</label>
                    <input type="number" id="maxPrice" placeholder="1000">
                </div>
                <div>
                    <label for="citySelect">Oraș</label>
                    <select id="citySelect">
                        <option value="">Toate orașele</option>
                        </select>
                </div>
                <div class="filter-actions">
                    <button type="button" id="applyFilterBtn" class="btn primary">Aplică Filtre</button>
                    <button type="button" id="resetFilterBtn" class="btn">Resetează</button>
                </div>
            </form>
        </section>

        <section class="listings">
            <article class="card" data-city="Brașov" data-price="220">
                <img src="https://www.romanian-adventures.ro/uploads/images/casa_clementina_brasov_2.jpeg" alt="Casa din munte" />
                <div class="info">
                    <div class="title">Casa cu grădină</div>
                    <div class="meta"><span>Brașov</span><span style="color:var(--muted)">4.9 ★</span></div>
                    <div style="display:flex;justify-content:space-between;align-items:center">
                        <div class="price">220 RON/noapte</div>
                        <div class="actions">
                            <button class="btn">Detalii</button>
                            <button class="btn primary">Rezervă</button>
                            <button class="btn btn-deactivate" style="display:none;">Dezactivează</button>
                        </div>
                    </div>
                </div>
            </article>

            <article class="card" data-city="București" data-price="320">
                <img src="https://www.nobili-interior-design.ro/storage/posts/418/900_design_interior_apartament_modern_in_bucuresti_2.jpg" alt="Apartament modern" />
                <div class="info">
                    <div class="title">Apartament modern în București</div>
                    <div class="meta"><span>București</span><span>4.8 ★</span></div>
                    <div style="display:flex;justify-content:space-between;align-items:center">
                        <div class="price">320 RON/noapte</div>
                        <div class="actions">
                            <button class="btn">Detalii</button>
                            <button class="btn primary">Rezervă</button>
                            <button class="btn btn-deactivate" style="display:none;">Dezactivează</button>
                        </div>
                    </div>
                </div>
            </article>

            <article class="card" data-city="Cluj" data-price="280">
                <img src="https://hotnews.ro/wp-content/uploads/2024/04/image-2022-02-18-25374024-41-garsoniera-11.jpg" alt="Loft" />
                <div class="info">
                    <div class="title">Garsoniera centrală</div>
                    <div class="meta"><span>Cluj</span><span>4.7 ★</span></div>
                    <div style="display:flex;justify-content:space-between;align-items:center">
                        <div class="price">280 RON/noapte</div>
                        <div class="actions">
                            <button class="btn">Detalii</button>
                            <button class="btn primary">Rezervă</button>
                            <button class="btn btn-deactivate" style="display:none;">Dezactivează</button>
                        </div>
                    </div>
                </div>
            </article>
            
            <article class="card" data-city="Brașov" data-price="450">
                <img src="https://cf.bstatic.com/xdata/images/hotel/max1024x768/186728929.jpg?k=3e1399683302c9b89480a9e61da76eb6f5d07ecaa6142fac74b47c3525c29ea1&o=&hp=1" alt="Studio" />
                <div class="info">
                    <div class="title">Studio de lux</div>
                    <div class="meta"><span>Brașov</span><span>5.0 ★</span></div>
                    <div style="display:flex;justify-content:space-between;align-items:center">
                        <div class="price">450 RON/noapte</div>
                        <div class="actions">
                            <button class="btn">Detalii</button>
                            <button class="btn primary">Rezervă</button>
                            <button class="btn btn-deactivate" style="display:none;">Dezactivează</button>
                        </div>
                    </div>
                </div>
            </article>

        </section>

        <p style="text-align:center;">
            <a href="index.php" class="button-home" rel="noopener">Home</a>
        </p>
    </main>

    <script>
    // --- Funcție nouă: Handle Deactivate ---
    function handleDeactivation(event) {
        // Găsește cel mai apropiat părinte cu clasa 'card'
        const card = event.target.closest('.card'); 
        
        if (card) {
            // Toggle-ul stării
            const isDeactivated = card.classList.toggle('deactivated');
            
            // Actualizarea textului butonului
            event.target.textContent = isDeactivated ? 'Activează' : 'Dezactivează';

            // Setăm starea în DOM (important pentru persistență la reload/filtrare)
            card.dataset.status = isDeactivated ? 'deactivated' : '';
            
            // Opțional: alertă de feedback
            alert(`Anunțul '${card.querySelector('.title').textContent}' a fost ${isDeactivated ? 'dezactivat (soft deleted)' : 'reactivat'}.`);
        }
    }
    
    document.addEventListener('DOMContentLoaded', () => {
        
        // --- Variabile și Inițializare ---
        const listingsContainer = document.querySelector('.listings');
        // Selectăm TOATE cardurile de la început
        const allCards = Array.from(document.querySelectorAll('.listings .card'));
        
        // Creăm un array de obiecte cu datele, pentru a sorta mai ușor
        let cardData = allCards.map(card => {
            return {
                element: card, // Referința la Nodul DOM
                city: card.dataset.city,
                price: parseInt(card.dataset.price, 10), // Convertim prețul în număr
                status: card.dataset.status || ''
            };
        });

        // Elementele din formular
        const filterToggleBtn = document.getElementById('filterToggleBtn');
        const filterFormContainer = document.getElementById('filterFormContainer');
        const applyFilterBtn = document.getElementById('applyFilterBtn');
        const resetFilterBtn = document.getElementById('resetFilterBtn');
        const citySelect = document.getElementById('citySelect');
        const minPriceInput = document.getElementById('minPrice');
        const maxPriceInput = document.getElementById('maxPrice');
        
        // Elementele de sortare
        const sortAscBtn = document.getElementById('sortAscBtn');
        const sortDescBtn = document.getElementById('sortDescBtn');

        // --- Funcții ---

        // 1. Populează dinamic filtrul de orașe
        function populateCityFilter() {
            // Găsește orașe unice din cardurile existente
            const cities = [...new Set(cardData.map(c => c.city))];
            cities.sort(); // Sortează alfabetic
            cities.forEach(city => {
                const option = document.createElement('option');
                option.value = city;
                option.textContent = city;
                citySelect.appendChild(option);
            });
        }

        // 2. Funcția de aplicare a filtrelor
        function applyFilters() {
            const minPrice = parseInt(minPriceInput.value, 10) || 0; // 0 dacă e gol
            const maxPrice = parseInt(maxPriceInput.value, 10) || Infinity; // Infinit dacă e gol
            const selectedCity = citySelect.value;

            // Parcurge array-ul de date și arată/ascunde elementele
            cardData.forEach(card => {
                let isVisible = true;

                // Verifică prețul
                if (card.price < minPrice || card.price > maxPrice) {
                    isVisible = false;
                }

                // Verifică orașul (dacă e selectat unul)
                if (selectedCity && card.city !== selectedCity) {
                    isVisible = false;
                }

                // Aplică vizibilitatea
                card.element.style.display = isVisible ? '' : 'none';
            });
        }
        
        // 3. Funcția de sortare
        function sortListings(direction) {
            // Sortează array-ul de date
            cardData.sort((a, b) => {
                if (direction === 'asc') {
                    return a.price - b.price; // Crescător
                } else {
                    return b.price - a.price; // Descrescător
                }
            });

            // Golește containerul
            listingsContainer.innerHTML = '';

            // Re-adaugă elementele în container în ordinea sortată
            cardData.forEach(card => {
                listingsContainer.appendChild(card.element);
            });
        }
        
        // 4. Resetare filtre
        function resetFilters() {
            minPriceInput.value = '';
            maxPriceInput.value = '';
            citySelect.value = '';
            applyFilters(); // Re-aplică filtrele (care acum sunt goale)
        }


        // --- Logica de Admin (Spre deosebire de index.php, aici doar atașăm funcția) ---
        const userRole = localStorage.getItem('userRole');

        if (userRole === 'admin') {
            // Selectează TOATE butoanele de dezactivare
            const deactivateButtons = document.querySelectorAll('.btn-deactivate');
            
            deactivateButtons.forEach(button => {
                // 1. Fă butoanele vizibile
                button.style.display = 'inline-flex';
                // 2. ATAȘEAZĂ EVENT LISTENERUL NOU
                button.addEventListener('click', handleDeactivation); 
            });
        }

        // --- Inițializare și Event Listeners ---
        
        populateCityFilter(); // Populează orașele la încărcarea paginii

        // Afișează/ascunde formularul de filtru
        filterToggleBtn.addEventListener('click', () => {
            const isHidden = filterFormContainer.style.display === 'none';
            filterFormContainer.style.display = isHidden ? 'block' : 'none';
            filterToggleBtn.classList.toggle('active', isHidden);
        });

        // Butoane formular
        applyFilterBtn.addEventListener('click', applyFilters);
        resetFilterBtn.addEventListener('click', resetFilters);
        
        // Butoane sortare
        sortAscBtn.addEventListener('click', () => sortListings('asc'));
        sortDescBtn.addEventListener('click', () => sortListings('desc'));

    });
</script>
</body>
</html> 