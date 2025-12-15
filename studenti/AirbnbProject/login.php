<!doctype html>
<html lang="ro">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Login — HomeEverywhere</title>
  <link rel="stylesheet" href="login.css" />
</head>
<body>

  <main class="login-container">
    <header class="login-header">
      <a class="brand" href="index.php">
        <div class="logo"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: white;">
  <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
  <polyline points="9 22 9 12 15 12 15 22"></polyline>
</svg></div>
        <div style="font-weight:700">HomeEverywhere</div>
      </a>
      <h1>Autentificare</h1>
      <p class="lead">Intră în cont pentru a-ți gestiona rezervările.</p>
    </header>

    <form id="loginForm" class="login-form" action="login_process.php" method="POST">
      
      <?php if (isset($_GET['error'])): ?>
          <p style="color: red; text-align: center;">Email sau parolă greșită!</p>
      <?php endif; ?>

      <label>
        Email
        <input type="email" id="email" name="email" placeholder="adresa@exemplu.com" required>
      </label>

      <label>
        Parolă
        <input type="password" id="password" name="password" placeholder="••••••••" required>
      </label>

      <div class="form-actions">
        <button type="submit" class="btn primary">Intră în cont</button>
        <a href="index.php" class="btn outline">Anulează</a>
      </div>

      <p class="muted-link">Nu ai cont? <a href="register.php">Înregistrează-te</a></p>
</form>
  </main>

</body>
</html>