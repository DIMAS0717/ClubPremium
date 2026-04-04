<?php
$page_title = 'Lotes en Venta - Villas Eureka';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($page_title); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="assets/css/lotes.css">
    <link rel="stylesheet" href="assets/css/responsive.css">
    <link rel="stylesheet" href="assets/css/drk_mode.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>

<?php include __DIR__ . '/includes/header.php'; ?>

<main class="page-main lotes-page">

    <section class="lotes-premium-section">
        <div class="lotes-premium-shell">

            <div class="lotes-deco lotes-deco-1"></div>
            <div class="lotes-deco lotes-deco-2"></div>

            <div class="lotes-premium-grid">

                <!-- IZQUIERDA -->
                <div class="lotes-copy-column">

                    <span class="lotes-kicker">OPORTUNIDAD DE INVERSIÓN</span>

                    <h1 class="lotes-main-title">
                        Lotes en venta en
                        <span class="lotes-main-title-accent">Colonia Miramar</span>
                    </h1>

                    <div class="lotes-title-divider"></div>

                    <p class="lotes-lead">
                        Se venden 14 terrenos de oportunidad para desarrolladoras o inversionistas
                        dentro de la Colonia Miramar, Maravillas del Campo, Manzanillo, Colima.
                    </p>

                    <p class="lotes-body-text">
                        Están ubicados entre la calle Juárez y calle Miguel Domínguez, a tan solo
                        media cuadra de la carretera con dirección Manzanillo - Cihuatlán y Puerto Vallarta.
                        Las lotificaciones se encuentran a 15 minutos de centros comerciales como Soriana,
                        La Comercial Mexicana y Walmart, además de hospitales generales y particulares.
                        La distancia al mar es de solo 5 minutos.
                    </p>

                    <div class="lotes-points">
                        <div class="lotes-point">
                            <span class="lotes-point-icon">✓</span>
                            <span>Ubicación estratégica para desarrollo</span>
                        </div>

                        <div class="lotes-point">
                            <span class="lotes-point-icon">✓</span>
                            <span>A solo 5 minutos del mar</span>
                        </div>

                        <div class="lotes-point">
                            <span class="lotes-point-icon">✓</span>
                            <span>Cerca de hospitales y supermercados</span>
                        </div>

                        <div class="lotes-point">
                            <span class="lotes-point-icon">✓</span>
                            <span>Ideal para inversión y lotificación</span>
                        </div>
                    </div>

                    <div class="lotes-price-card">
                        <div class="lotes-price-card-top">
                            <span class="lotes-price-label">PRECIO POR m²</span>
                            <span class="lotes-price-chip">Zona con potencial</span>
                        </div>

                        <div class="lotes-price-value">$5,000</div>

                        <p class="lotes-price-note">
                            Precio competitivo en una zona con crecimiento y excelente conectividad.
                        </p>
                    </div>
                </div>

                <!-- DERECHA -->
                <div class="lotes-media-column">

                    <div class="lotes-video-frame">
                        <div class="lotes-video-glow"></div>

                        <div class="lotes-video-card">
                            <div class="lotes-video-wrapper">
                                <iframe
                                    src="https://www.youtube.com/embed/hWKKNF4gU74?rel=0&modestbranding=1"
                                    title="Video Lotes en Venta"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                    allowfullscreen>
                                </iframe>
                            </div>
                        </div>
                    </div>

                    <div class="lotes-location-card">
                        <div class="lotes-location-thumb">
                            <img src="assets/img/lotes/lotes-preview.jpg" alt="Ubicación de lotes">
                        </div>

                        <div class="lotes-location-info">
                            <span class="lotes-location-tag">UBICACIÓN Y ENTORNO</span>
                            <h3>Consulta la zona antes de invertir</h3>
                            <p>
                                Visualiza el entorno y revisa la ubicación geográfica para conocer mejor
                                el contexto del desarrollo.
                            </p>

                            <a
                                href="https://maps.google.com"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="lotes-maps-btn"
                            >
                                Ver en Google Maps
                            </a>
                        </div>
                    </div>

                </div>
            </div>

            <div class="lotes-color-bar">
                <span class="c1"></span>
                <span class="c2"></span>
                <span class="c3"></span>
                <span class="c4"></span>
                <span class="c5"></span>
                <span class="c6"></span>
            </div>

        </div>
    </section>

</main>

<?php include __DIR__ . '/includes/footer.php'; ?>

<script src="assets/js/app.js"></script>
</body>
</html>