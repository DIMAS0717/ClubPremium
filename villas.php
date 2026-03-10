<?php
$page_title = 'Nuestras Villas - Villas Eureka';

$villas = [
    [
        'clase' => '',
        'badge' => 'Económica',
        'badge_class' => '',
        'imagen' => 'assets/images/plantabaja.jpg',
        'alt' => 'Villa Planta Baja',
        'titulo' => 'Villa Planta Baja',
        'capacidad' => '4 personas (incluye niños)',
        'features' => [
            '1, 2 y 3 recámaras',
            'Baño completo',
            'Terraza con jardín',
            'Alberca semi-olímpica'
        ],
        'opciones' => [
            ['href' => 'propiedad.php?id=26', 'titulo' => '1 Recámara', 'subtitulo' => 'Hasta 2 personas'],
            ['href' => 'propiedad.php?id=27', 'titulo' => '2 Recámaras', 'subtitulo' => 'Hasta 8 personas'],
            ['href' => 'propiedad.php?id=28', 'titulo' => '3 Recámaras', 'subtitulo' => 'Hasta 10 personas'],
        ],
        'detalle' => 'propiedad.php?id=26'
    ],
    [
        'clase' => 'featured',
        'badge' => 'Más popular',
        'badge_class' => '',
        'imagen' => 'assets/images/plantaalta.jpg',
        'alt' => 'Villa Planta Alta',
        'titulo' => 'Villa Planta Alta',
        'capacidad' => '6-8 personas (incluye niños)',
        'features' => [
            '1, 2 y 3 recámaras',
            'Baños completos',
            'Terraza con vista (P/A)',
            'Alberca semi-olímpica'
        ],
        'opciones' => [
            ['href' => 'propiedad.php?id=29', 'titulo' => '1 Recámara', 'subtitulo' => 'Hasta 4 personas'],
            ['href' => 'propiedad.php?id=30', 'titulo' => '2 Recámaras', 'subtitulo' => 'Hasta 8 personas'],
            ['href' => 'propiedad.php?id=31', 'titulo' => '3 Recámaras', 'subtitulo' => 'Hasta 12 personas'],
        ],
        'detalle' => 'propiedad.php?id=29'
    ],
    [
        'clase' => '',
        'badge' => 'Para grupos',
        'badge_class' => 'group',
        'imagen' => 'assets/images/villa8personas.jpg',
        'alt' => 'Villa 8 Personas',
        'titulo' => 'Villa 8 Personas',
        'capacidad' => '8 personas (incluye niños)',
        'features' => [
            '3 recámaras',
            '2 baños completos',
            'Terraza con jardín',
            '2 albercas'
        ],
        'opciones' => [
            ['href' => 'propiedad.php?id=32', 'titulo' => '1 Recámara', 'subtitulo' => 'Hasta 4 personas'],
            ['href' => 'propiedad.php?id=33', 'titulo' => '2 Recámaras', 'subtitulo' => 'Hasta 8 personas'],
            ['href' => 'propiedad.php?id=34', 'titulo' => '3 Recámaras', 'subtitulo' => 'Hasta 12 personas'],
        ],
        'detalle' => 'propiedad.php?id=32'
    ]
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($page_title); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    
    <link rel="stylesheet" href="assets/css/villas.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800&display=swap" rel="stylesheet">
</head>
<body>

<?php include __DIR__ . '/includes/header.php'; ?>

<main class="page-main villas-page">

    <!-- HERO -->
    <section class="villas-hero-container">
        <div class="villas-header">
            <div class="around-title-wrap">
                <img src="assets/images/iconoeureka.png" class="title-deco" alt="">
                <h1 class="around-title">Nuestras Villas</h1>
                <img src="assets/images/iconoeureka.png" class="title-deco" alt="">
            </div>

            <div class="around-title-underline"></div>

            <p class="around-subtitle">
                Espacios diseñados para ofrecer privacidad, confort
                y una experiencia exclusiva en Club Santiago.
            </p>
        </div>
    </section>

    <!-- VIDEO -->
    <section class="villas-video-section">
        <div class="villas-video-card">
            <div class="villas-video-wrapper">
                <iframe
                    src="https://www.youtube.com/embed/ePB90-NmYPU?rel=0&modestbranding=1"
                    title="Video Villas Eureka"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen>
                </iframe>
            </div>
        </div>
    </section>

    <!-- TIPOS DE VILLAS -->
    <section class="villa-types-section">
        <div class="container">
            <h2>Nuestros Tipos de Villas</h2>
            <p class="section-subtitle">
                Espacios diseñados para diferentes tamaños de grupo y necesidades
            </p>

            <div class="villa-cards-container">
                <?php foreach ($villas as $villa): ?>
                    <div class="villa-card <?= htmlspecialchars($villa['clase']); ?>">
                        <div class="villa-badge <?= htmlspecialchars($villa['badge_class']); ?>">
                            <?= htmlspecialchars($villa['badge']); ?>
                        </div>

                        <div class="villa-image">
                            <img src="<?= htmlspecialchars($villa['imagen']); ?>" alt="<?= htmlspecialchars($villa['alt']); ?>" loading="lazy">
                        </div>

                        <div class="villa-content">
                            <h3><?= htmlspecialchars($villa['titulo']); ?></h3>
                            <p class="villa-capacity"><?= htmlspecialchars($villa['capacidad']); ?></p>

                            <div class="villa-features">
                                <?php foreach ($villa['features'] as $feature): ?>
                                    <div class="feature">
                                        <div class="feature-icon">✓</div>
                                        <span><?= htmlspecialchars($feature); ?></span>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                            <div class="bed-distribution">
                                <h4>Selecciona tu tamaño ideal:</h4>

                                <div class="villa-options-group">
                                    <?php foreach ($villa['opciones'] as $opcion): ?>
                                        <a href="<?= htmlspecialchars($opcion['href']); ?>" class="villa-option-link">
                                            <div class="villa-icon-wrapper">🏡</div>
                                            <div class="villa-text-wrapper">
                                                <span class="villa-title">
                                                    <?= htmlspecialchars($opcion['titulo']); ?>
                                                    <small><?= htmlspecialchars($opcion['subtitulo']); ?></small>
                                                </span>
                                                <span class="villa-arrow">→</span>
                                            </div>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <button class="btn-details" onclick="window.location.href='<?= htmlspecialchars($villa['detalle']); ?>'">
                                Ver detalles completos
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

</main>

<?php include __DIR__ . '/includes/footer.php'; ?>

<script src="assets/js/app.js"></script>

</body>
</html>