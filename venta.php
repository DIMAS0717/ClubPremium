<?php
require_once __DIR__ . '/admin/core/Database.php';
require_once __DIR__ . '/includes/property_helpers.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

try {
    $db = Database::getConnection();

    $sql = "
        SELECT
            id,
            nombre,
            descripcion_corta,
            foto_principal,
            categoria
        FROM properties
        WHERE categoria = :categoria
        ORDER BY created_at DESC
    ";

    $stmt = $db->prepare($sql);
    $stmt->execute([
        ':categoria' => 'venta'
    ]);

    $propiedadesVenta = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (Throwable $e) {
    echo '<pre style="background:#300;color:#fff;padding:12px;border-radius:8px;">';
    echo 'ERROR EN VENTA.PHP' . "\n\n";
    echo $e->getMessage();
    echo '</pre>';
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Propiedades en venta - Casas Club Santiago</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="assets/styles.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>

<?php include __DIR__ . '/includes/header.php'; ?>

<main class="page-main">
    <section class="section">
        <h1>Propiedades en venta</h1>
        <p class="muted-text">Casas y propiedades disponibles para compra.</p>
    </section>

    <section class="section">
        <?php if (!empty($propiedadesVenta)): ?>
            <?php foreach ($propiedadesVenta as $p): ?>
                <article class="home-property-card">
                    <?php if (!empty($p['foto_principal'])): ?>
                        <a href="propiedad.php?id=<?php echo (int)$p['id']; ?>">
                            <img
                                src="<?php echo e($p['foto_principal']); ?>"
                                alt="<?php echo e($p['nombre']); ?>"
                                class="home-property-img"
                            >
                        </a>
                    <?php endif; ?>

                    <div class="home-property-body">
                        <h3><?php echo e($p['nombre']); ?></h3>

                        <?php if (!empty($p['descripcion_corta'])): ?>
                            <p class="muted-text">
                                <?php echo e($p['descripcion_corta']); ?>
                            </p>
                        <?php endif; ?>

                        <a href="propiedad.php?id=<?php echo (int)$p['id']; ?>" class="btn-primary">
                            Ver detalles
                        </a>
                    </div>
                </article>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="muted-text">Aún no hay propiedades registradas en venta.</p>
        <?php endif; ?>
    </section>
</main>

<?php include __DIR__ . '/includes/footer.php'; ?>

<script src="assets/app.js"></script>
</body>
</html>