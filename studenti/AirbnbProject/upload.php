<?php
session_start();
//doar useri logati
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Publică Anunț — HomeEverywhere</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="index.css">
    
    <style>
        body { background-color: #f7f7f7; }
        
        .upload-card {
            background: white;
            max-width: 600px;
            margin: 3rem auto;
            padding: 2.5rem;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }

        .upload-card h1 {
            text-align: center; margin-top: 0; margin-bottom: 2rem; color: #222;
        }

        .form-group { margin-bottom: 1.5rem; }
        .form-group label { display: block; margin-bottom: 0.5rem; font-weight: 600; color: #333; }
        
        .form-group input[type="text"], 
        .form-group input[type="number"], 
        .form-group textarea {
            width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px;
            font-family: 'Inter', sans-serif; font-size: 1rem; box-sizing: border-box;
        }

        /* Stiluri pentru Checkbox-uri (Facilități) */
        .facilities-grid {
            display: grid;
            grid-template-columns: 1fr 1fr; /* Două coloane */
            gap: 10px;
        }
        
        .checkbox-item {
            display: flex;
            align-items: center;
            background: #f9f9f9;
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #eee;
            cursor: pointer;
        }
        
        .checkbox-item input {
            margin-right: 10px;
            width: 18px;
            height: 18px;
            accent-color: #ff385c; /* Culoarea bifei */
        }
    </style>
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

    <div class="upload-card">
        <h1>Publică un anunț nou</h1>

        <?php if (isset($_SESSION['error'])): ?>
            <div style="background:#fff5f5; color:#c53030; padding:10px; border-radius:6px; margin-bottom:1rem;">
                <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <form action="upload_process.php" method="POST" enctype="multipart/form-data">
            
            <div class="form-group">
                <label>Titlu Anunț *</label>
                <input type="text" name="title" placeholder="Ex: Cabană liniștită la munte" required>
            </div>

            <div style="display:flex; gap:15px;">
                <div class="form-group" style="flex:1;">
                    <label>Oraș *</label>
                    <input type="text" name="city" placeholder="Ex: Brașov" required>
                </div>
                <div class="form-group" style="flex:1;">
                    <label>Capacitate</label>
                    <input type="number" name="guests" value="2" min="1" max="20">
                </div>
            </div>

            <div class="form-group">
                <label>Preț pe noapte (RON) *</label>
                <input type="number" name="price" placeholder="Ex: 250" required>
            </div>

            <div class="form-group">
                <label>Selectează Facilitățile:</label>
                <div class="facilities-grid">
                    <label class="checkbox-item">
                        <input type="checkbox" name="facilities[]" value="Wifi"> Wifi Gratuit
                    </label>
                    <label class="checkbox-item">
                        <input type="checkbox" name="facilities[]" value="Parcare"> Parcare
                    </label>
                    <label class="checkbox-item">
                        <input type="checkbox" name="facilities[]" value="Piscina"> Piscină
                    </label>
                    <label class="checkbox-item">
                        <input type="checkbox" name="facilities[]" value="Aer Conditionat"> Aer Condiționat
                    </label>
                    <label class="checkbox-item">
                        <input type="checkbox" name="facilities[]" value="Bucatarie"> Bucătărie
                    </label>
                    <label class="checkbox-item">
                        <input type="checkbox" name="facilities[]" value="Balcon"> Balcon / Terasă
                    </label>
                </div>
            </div>

            <div class="form-group">
                <label>Descriere Detaliată</label>
                <textarea name="description" rows="4" placeholder="Alte detalii..."></textarea>
            </div>

            <div class="form-group" style="background:#f9f9f9; padding:15px; border-radius:8px; border:1px dashed #ccc;">
    <label style="font-weight:700; margin-bottom:8px; display:block;">Galerie Foto (Poți selecta mai multe) *</label>
    
    <input type="file" name="images[]" multiple accept="image/*" required style="background:white; padding:10px;">
    
    <small style="display:block; color:#666; margin-top:8px;">
        💡 
    </small>
</div>

            <button type="submit" class="btn primary" style="width:100%; padding:14px; background:#ff385c; color:white; border:none; border-radius:8px; font-weight:600; cursor:pointer;">
                Publică Anunțul
            </button>
        </form>
    </div>

</body>
</html>