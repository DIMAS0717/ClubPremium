<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

ini_set('display_errors', 1);
error_reporting(E_ALL);

define('ACCESS', true);

require_once __DIR__ . '/core/Database.php';
require_once __DIR__ . '/core/Auth.php';

$conn = Database::getConnection();
Auth::check();

/* =============================
   TOKEN CSRF GLOBAL
============================= */
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

/* =============================
   VISTAS PERMITIDAS
============================= */
$allowed_views = [
    'dashboard',
    'casas',
    'calendario',
    'perfil',
    'password'
];

$view = $_GET['view'] ?? 'dashboard';

if (!in_array($view, $allowed_views, true)) {
    $view = 'dashboard';
}

/* =============================
   PROCESAR POST
============================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $action = $_POST['action'] ?? '';

    switch ($action) {

        /* =============================
           PROPIEDADES
        ============================== */
        case 'save_property':
        case 'delete_property':
        case 'delete_photo':
        case 'delete_multiple_photos':
        require_once __DIR__ . '/controllers/PropertyController.php';
        PropertyController::handle($conn, $action);
        exit;


        /* =============================
           CALENDARIO
        ============================== */
        case 'save_calendar':
        case 'delete_calendar':

            require_once __DIR__ . '/controllers/CalendarController.php';
            CalendarController::handle($conn, $action);
            exit;


        /* =============================
           PERFIL
        ============================== */
        case 'update_profile':

            require_once __DIR__ . '/controllers/AuthController.php';
            AuthController::updateProfile($conn);
            exit;


        case 'update_avatar':

            require_once __DIR__ . '/controllers/AuthController.php';
            AuthController::updateAvatar($conn);
            exit;


        case 'change_password':

            require_once __DIR__ . '/controllers/AuthController.php';
            AuthController::changePassword($conn);
            exit;
    }
}

/* =============================
   CARGAR ADMIN
============================= */
$admin = [];
$movimientos = [];

$adminId = $_SESSION['admin_id'] ?? 0;

if ($adminId > 0) {

    $stmt = $conn->prepare("
        SELECT id, username, nombre, correo, foto, pais, estado, created_at
        FROM admins
        WHERE id = ?
        LIMIT 1
    ");

    $stmt->execute([$adminId]);

    $admin = $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
}

/* =============================
   MOVIMIENTOS DASHBOARD
============================= */
if ($view === 'dashboard') {

    $sqlMov = "

        (SELECT
            'Propiedad' AS tipo,
            nombre AS detalle,
            created_at AS fecha
        FROM properties)

        UNION ALL

        (SELECT
            'Calendario' AS tipo,
            CONCAT(p.nombre, ' (', c.estado, ')') AS detalle,
            c.fecha_inicio AS fecha
        FROM property_calendar c
        INNER JOIN properties p ON p.id = c.property_id)

        ORDER BY fecha DESC
        LIMIT 8
    ";

    $stmtMov = $conn->query($sqlMov);

    $movimientos = $stmtMov ? $stmtMov->fetchAll(PDO::FETCH_ASSOC) : [];
}

/* =============================
   MENSAJES FLASH
============================= */
$flash_success = $_SESSION['flash_success'] ?? '';
$flash_error   = $_SESSION['flash_error'] ?? '';

unset($_SESSION['flash_success'], $_SESSION['flash_error']);
?>

<!DOCTYPE html>
<html lang="es">
<head>

<meta charset="UTF-8">
<title>Panel Administrativo</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link rel="stylesheet" href="../assets/css/admin/panel.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

</head>
<body>


<!-- ================= SIDEBAR ================= -->

<div class="left-side">

<h1>Centro de Administración</h1>


<div class="profile-avatar">

<?php
$rutaFoto = !empty($admin['foto']) ? (__DIR__ . '/../' . $admin['foto']) : '';
?>

<?php if (!empty($admin['foto']) && is_file($rutaFoto)): ?>

<img src="../<?= htmlspecialchars($admin['foto']) ?>" alt="Foto">

<?php else: ?>

<div class="avatar-initial">

<?php
$nombreMostrar = ($admin['nombre'] ?? '') ?: ($_SESSION['nombre'] ?? 'A');
echo strtoupper(substr($nombreMostrar, 0, 1));
?>

</div>

<?php endif; ?>

</div>


<h2 class="profile-name">
<?= htmlspecialchars($_SESSION['nombre'] ?? 'Usuario'); ?>
</h2>


<p class="profile-role">
<?= htmlspecialchars($_SESSION['rol'] ?? 'Administrador'); ?>
</p>


<div class="sidebar-nav">

<ul>

<li class="<?= $view === 'dashboard' ? 'active' : '' ?>">
<a href="panel.php?view=dashboard">
<i class="fas fa-user"></i> Mi Panel
</a>
</li>

<li class="<?= $view === 'casas' ? 'active' : '' ?>">
<a href="panel.php?view=casas">
<i class="fas fa-house-user"></i> Casas
</a>
</li>

<li class="<?= $view === 'calendario' ? 'active' : '' ?>">
<a href="panel.php?view=calendario">
<i class="fas fa-calendar-alt"></i> Calendario
</a>
</li>

<li class="<?= $view === 'perfil' ? 'active' : '' ?>">
<a href="panel.php?view=perfil">
<i class="fas fa-id-card"></i> Editar perfil
</a>
</li>

<li class="<?= $view === 'password' ? 'active' : '' ?>">
<a href="panel.php?view=password">
<i class="fas fa-lock"></i> Cambiar contraseña
</a>
</li>

<li>
<a href="../index.php">
<i class="fas fa-home"></i> Volver al inicio
</a>
</li>

<li>
<a href="logout.php">
<i class="fas fa-sign-out-alt"></i> Cerrar sesión
</a>
</li>

</ul>

</div>

</div>



<!-- ================= CONTENIDO ================= -->

<div class="right-side">

<header>
<div class="access-info">
<i class="fas fa-shield-alt"></i>
Acceso seguro
</div>
</header>


<div class="rectangle">

<div class="rectangle-text">

<h1>
¡Bienvenido a tu panel, <?= htmlspecialchars($_SESSION['nombre'] ?? 'Admin'); ?>!
</h1>

<p>
Aquí puedes administrar las casas, gestionar el calendario y actualizar tu perfil.
</p>

</div>

<img src="../assets/img/fondos/icon_admin.png" class="hand-header" alt="">

</div>


<?php if (!empty($flash_success)): ?>
    <div class="alert success">
        <?= htmlspecialchars($flash_success); ?>
    </div>
<?php endif; ?>

<?php if (!empty($flash_error)): ?>
    <div class="alert error">
        <?= htmlspecialchars($flash_error); ?>
    </div>
<?php endif; ?>

<?php

$viewFile = __DIR__ . "/views/{$view}.php";

if (is_file($viewFile)) {
    include $viewFile;
} else {
    include __DIR__ . '/views/dashboard.php';
}

?>

</div>


</body>
</html>