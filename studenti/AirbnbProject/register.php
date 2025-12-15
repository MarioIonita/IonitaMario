<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>Înregistrare - HomeEverywhere</title>
    <link rel="stylesheet" href="style.css"> 
    <style>
        .auth-container { max-width: 400px; margin: 50px auto; padding: 20px; border: 1px solid #ccc; border-radius: 8px; text-align: center; }
        input { width: 90%; padding: 10px; margin: 10px 0; border: 1px solid #ddd; border-radius: 4px; }
        button { width: 100%; padding: 10px; background-color: #ff385c; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: bold; }
        button:hover { background-color: #d9324e; }
    </style>
</head>
<body>

<div class="auth-container">
    <h2>Creează Cont 🏡</h2>
    <form action="register_process.php" method="POST">
        <input type="text" name="username" placeholder="Nume utilizator" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Parola" required>
        <label for="tip_cont" style="display:block; margin-top:10px; text-align:left; font-weight:bold;">Tip cont:</label>
        
<select name="tip_cont" style="width: 95%; padding: 10px; margin-bottom: 15px; border-radius: 4px; border: 1px solid #ddd;">
    <option value="turist">Turist (Vreau să închiriez)</option>
    <option value="proprietar">Proprietar (Vreau să postez cazări)</option>
</select>
        <button type="submit">Înregistrează-te</button>
    </form>
    <p>Ai deja cont? <a href="login.php">Loghează-te</a></p>
    <p><a href="index.php">Înapoi acasă</a></p>
</div>

</body>
</html>