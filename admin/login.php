<?php
session_start();

require_once __DIR__ . '/core/Database.php';
require_once __DIR__ . '/controllers/AuthController.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // CSRF básico
    if (!isset($_POST['csrf_token']) ||
        $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Token inválido.");
    }

    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (AuthController::login($username, $password)) {
        header("Location: panel.php");
        exit;
    } else {
        $error = "Usuario o contraseña incorrectos.";
    }
}

// Generar token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Login admin - Club Santiago</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../assets/css/log.css">
</head>
<body class="admin-body">

  <img src="../assets/images/fondo_login.jpg" alt="Fondo" class="bg-image-tag">

  <a href="../index.php" class="boton-inicio">
    <img src="../assets/images/logofoter.png" alt="Ir a Inicio">
  </a>

  <div class="bg-overlay"></div>

  <div class="admin-login-wrapper">
    <form class="admin-login-card" method="post">

      <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">

      <div class="card-header">
        <h1>Iniciar Sesión</h1>
        <p style="color: #8c0606;">Acceso seguro</p>
      </div>

      <?php if ($error): ?>
        <p class="form-error"><?= htmlspecialchars($error); ?></p>
      <?php endif; ?>

      <div class="input-group">
        <input type="text" name="username" placeholder="Nombre de usuario" required>
      </div>

      <div class="input-group">
        <input type="password" name="password" placeholder="Contraseña" required>
      </div>

      <button type="submit" class="btn-submit">Entrar</button>

      <div class="card-footer">
        <a href="../../index.php" class="link-back">Volver al sitio principal</a>
      </div>

    </form>
  </div>

</body>
</html>