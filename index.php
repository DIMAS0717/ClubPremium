<?php
require_once __DIR__ . '/admin/core/Database.php';
require_once __DIR__ . '/includes/property_helpers.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

try {
    $db = Database::getConnection();

    $hoy = date('Y-m-d');

    $sql = "
        SELECT p.*
        FROM properties p
        WHERE p.categoria = :categoria
          AND p.id NOT IN (
              SELECT c.property_id
              FROM property_calendar c
              WHERE :hoy BETWEEN c.fecha_inicio AND c.fecha_fin
                AND c.estado = 'no_disponible'
          )
        ORDER BY RAND()
        LIMIT 3
    ";

    $stmt = $db->prepare($sql);
    $stmt->execute([
        ':categoria' => 'renta',
        ':hoy' => $hoy
    ]);

    $propiedadesDestacadas = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (Throwable $e) {
    echo '<pre style="background:#300;color:#fff;padding:12px;border-radius:8px;">';
    echo 'ERROR EN INDEX.PHP' . "\n\n";
    echo $e->getMessage();
    echo '</pre>';
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inicio - Casas Club Santiago</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="assets/css/index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>

<?php include __DIR__ . '/includes/header.php'; ?>

<?php include __DIR__ . '/includes/home/hero.php'; ?>

<main class="page-main villas-page">
    <?php include __DIR__ . '/includes/home/destacadas.php'; ?>
    <?php include __DIR__ . '/includes/home/about.php'; ?>
    <?php include __DIR__ . '/includes/home/beneficios.php'; ?>
    <?php include __DIR__ . '/includes/home/villas.php'; ?>
</main>

<?php include __DIR__ . '/includes/footer.php'; ?>

<script src="assets/app.js"></script>
</body>
</html>