<?php
require_once __DIR__ . '/admin/core/Database.php';
require_once __DIR__ . '/includes/property_helpers.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

try {
    $db = Database::getConnection();

    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

    if ($id <= 0) {
        throw new Exception('ID inválido');
    }

    $prop = obtenerPropiedad($db, $id);

    if (!$prop) {
        throw new Exception('La propiedad no existe en la base de datos');
    }

    $estadoActual = obtenerEstadoCasa($db, $id, $prop['estado_base'] ?? 'disponible');

    if ($estadoActual === 'no_disponible') {
        $estadoLabel = 'No disponible';
    } else {
        $estadoLabel = 'Disponible';
    }

    $sliderFotos = obtenerFotosPorTipo($db, $id, 'slider');
    $galeriaFotos = obtenerFotosPorTipo($db, $id, 'galeria');
    $serviciosItems = split_items($prop['servicios'] ?? '');
    $indicacionesItems = split_items($prop['indicaciones'] ?? '');

    $recamaras       = isset($prop['recamaras']) ? $prop['recamaras'] : null;
    $banos           = isset($prop['banos']) ? $prop['banos'] : null;
    $estacionamiento = isset($prop['estacionamiento']) ? $prop['estacionamiento'] : null;

} catch (Throwable $e) {
    echo '<pre style="background:#300;color:#fff;padding:12px;border-radius:8px;">';
    echo 'ERROR EN PROPIEDAD.PHP' . "\n\n";
    echo $e->getMessage();
    echo '</pre>';
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?php echo e($prop['nombre']); ?> - Club Santiago</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="assets/css/propiedades.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>

<?php include __DIR__ . '/includes/header.php'; ?>

<main class="property-wrapper">

    <section class="property-hero">

        <?php if (!empty($sliderFotos)): ?>
            <div class="property-slider-card" data-slider="property">
                <div class="slider-main">
                    <div class="slider-window">
                        <div class="slider-track" data-slider-track>
                            <?php foreach ($sliderFotos as $foto): ?>
                                <div class="slide" data-slide>
                                    <img
                                        class="slider-img"
                                        src="<?php echo e($foto['archivo']); ?>"
                                        alt="<?php echo e(!empty($foto['titulo']) ? $foto['titulo'] : $prop['nombre']); ?>"
                                    >
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <?php if (count($sliderFotos) > 1): ?>
                        <button type="button" class="slider-arrow" data-prev>‹</button>
                        <button type="button" class="slider-arrow" data-next>›</button>
                    <?php endif; ?>
                </div>
            </div>
        <?php else: ?>
            <div class="property-slider-card">
                <div class="slide">
                    <span style="padding:16px; color:var(--text-muted); font-size:13px;">
                        Próximamente fotos de esta casa.
                    </span>
                </div>
            </div>
        <?php endif; ?>

        <aside class="property-summary-card">
            <div class="property-status-row">
                <span class="status-badge status-<?php echo e($estadoActual); ?>">
                    <?php echo e($estadoLabel); ?>
                </span>

                <?php if (!empty($prop['capacidad'])): ?>
                    <span class="status-badge status-soft">
                        Capacidad: <?php echo (int)$prop['capacidad']; ?> personas
                    </span>
                <?php endif; ?>
            </div>

            <h1 class="property-title"><?php echo e($prop['nombre']); ?></h1>

            <?php if (!empty($prop['descripcion_corta'])): ?>
                <p class="property-short">
                    <?php echo e($prop['descripcion_corta']); ?>
                </p>
            <?php endif; ?>

            <div class="btn-row">
                <a href="#ubicacion" class="btn-primary">Ver ubicación</a>
                <a href="#" class="btn-primary-outline">Solicitar cotización</a>
            </div>

            <?php if ($recamaras || $banos || $estacionamiento): ?>
                <div class="property-mini-stats">
                    <?php if ($recamaras): ?>
                        <div class="mini-stat">
                            <span class="mini-label">Recámaras</span>
                            <span class="mini-value"><?php echo e($recamaras); ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if ($banos): ?>
                        <div class="mini-stat">
                            <span class="mini-label">Baños</span>
                            <span class="mini-value"><?php echo e($banos); ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if ($estacionamiento): ?>
                        <div class="mini-stat">
                            <span class="mini-label">Estacionamiento</span>
                            <span class="mini-value"><?php echo e($estacionamiento); ?></span>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </aside>
    </section>

    <section class="property-info-grid">

        <section class="property-card property-desc-card">
            <h2 class="section-title">Descripción general</h2>

            <p class="desc-text">
                <?php
                if (!empty($prop['descripcion_larga'])) {
                    echo nl2br(e($prop['descripcion_larga']));
                } elseif (!empty($prop['descripcion_corta'])) {
                    echo e($prop['descripcion_corta']);
                } else {
                    echo 'Casa en Club Santiago con excelente ubicación y todas las comodidades para tu estancia.';
                }
                ?>
            </p>
        </section>

        <aside class="property-card property-services-card">
            <h2 class="section-title">Contamos con...</h2>

            <?php if (!empty($serviciosItems)): ?>
                <div class="services-list">
                    <?php foreach ($serviciosItems as $item): ?>
                        <button type="button" class="chip chip-service">
                            <?php echo e($item); ?>
                        </button>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="muted-text" style="margin-top:6px;">
                    Agrega los servicios en el panel de administración para mostrarlos aquí.
                </p>
            <?php endif; ?>

            <p class="aviso-temporada">"Precios cambian en temporada alta"</p>
        </aside>

        <section class="property-card property-rules-card">
            <h3 class="section-subtitle">
                Indicaciones y reglas
            </h3>

            <?php if (!empty($indicacionesItems)): ?>
                <ul class="rules-list">
                    <?php foreach ($indicacionesItems as $item): ?>
                        <li><?php echo e($item); ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p class="muted-text">
                    Puedes añadir indicaciones y reglas desde el panel de administración.
                </p>
            <?php endif; ?>
        </section>
    </section>

    <section class="property-card property-gallery-card">
        <h2 class="section-title">Galería</h2>

        <?php if (!empty($galeriaFotos)): ?>
            <div class="gallery-grid">
                <?php foreach ($galeriaFotos as $foto): ?>
                    <figure class="gallery-item">
                        <img
                            class="gallery-img"
                            src="<?php echo e($foto['archivo']); ?>"
                            alt="<?php echo e(!empty($foto['titulo']) ? $foto['titulo'] : $prop['nombre']); ?>"
                        >
                    </figure>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="muted-text" style="margin-top:8px;">
                Aún no hay fotos adicionales para esta casa.
            </p>
        <?php endif; ?>
    </section>

    <section id="ubicacion" class="property-card property-location-card">
        <h2 class="section-title">Ubicación</h2>

        <div class="location-rows">
            <?php if (!empty($prop['ubicacion'])): ?>
                <div class="location-row">
                    <span class="location-label">Dirección</span>
                    <span class="location-value"><?php echo e($prop['ubicacion']); ?></span>
                </div>
            <?php endif; ?>

            <?php if (!empty($prop['distancia_mar'])): ?>
                <div class="location-row">
                    <span class="location-label">Distancia al mar</span>
                    <span class="location-value"><?php echo e($prop['distancia_mar']); ?></span>
                </div>
            <?php endif; ?>

            <?php if (!empty($prop['enlace_drive'])): ?>
                <div class="location-row">
                    <span class="location-label">Más imágenes / mapa</span>
                    <span class="location-value">
                        <a href="<?php echo e($prop['enlace_drive']); ?>" target="_blank" rel="noopener noreferrer" class="small-link">
                            Ver en Drive
                        </a>
                    </span>
                </div>
            <?php endif; ?>

            <?php if (!empty($prop['datos_contacto'])): ?>
                <div class="location-row">
                    <span class="location-label">Contacto</span>
                    <span class="location-value"><?php echo e($prop['datos_contacto']); ?></span>
                </div>
            <?php endif; ?>
        </div>
    </section>

</main>

<?php include __DIR__ . '/includes/footer.php'; ?>

<script src="assets/app.js"></script>
</body>
</html>