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
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700;800&family=Inter:wght@400;500;600;700&family=Montserrat:wght@600;700;800&display=swap" rel="stylesheet">
</head>
<body>

<?php include __DIR__ . '/includes/header.php'; ?>

<main class="page-main lotes-page">

    <section class="lvp-section">
        <div class="lvp-container">

            <div class="lvp-grid">

                <!-- COPY -->
                <div class="lvp-copy">
                    <span class="lvp-eyebrow">OPORTUNIDAD DE INVERSIÓN</span>

                    <h1 class="lvp-title">
                        Lotes en venta para
                        <span>desarrollo en Miramar</span>
                    </h1>

                    <div class="lvp-divider"></div>

                    <p class="lvp-lead">
                        Se venden 14 terrenos de oportunidad para desarrolladoras o inversionistas
                        dentro de la Colonia Miramar, Maravillas del Campo, Manzanillo, Colima.
                    </p>

                    <p class="lvp-text">
                        Están ubicados entre la calle Juárez y calle Miguel Domínguez, a tan solo
                        media cuadra de la carretera con dirección Manzanillo - Cihuatlán y Puerto Vallarta.
                        Las lotificaciones se encuentran a 15 minutos de centros comerciales como Soriana,
                        La Comercial Mexicana y Walmart, además de hospitales generales y particulares.
                        La distancia al mar es de solo 5 minutos.
                    </p>

                    <div class="lvp-tags">
                        <span class="lvp-tag">14 terrenos disponibles</span>
                        <span class="lvp-tag">A 5 min del mar</span>
                        <span class="lvp-tag">Zona de crecimiento</span>
                    </div>

                    <div class="lvp-list">
                        <div class="lvp-list-item">
                            <span class="lvp-check">✓</span>
                            <span>Ubicación estratégica para desarrollo</span>
                        </div>

                        <div class="lvp-list-item">
                            <span class="lvp-check">✓</span>
                            <span>Conectividad rápida con vialidades principales</span>
                        </div>

                        <div class="lvp-list-item">
                            <span class="lvp-check">✓</span>
                            <span>Cercanía con hospitales, supermercados y playa</span>
                        </div>
                    </div>

                    <div class="lvp-price-card">
                        <span class="lvp-price-label">PRECIO POR m²</span>
                        <strong class="lvp-price-value">$5,000</strong>
                        <p class="lvp-price-note">
                            Precio competitivo en una zona con alto potencial de inversión.
                        </p>
                    </div>
                </div>

                <!-- MEDIA -->
                <div class="lvp-media">

                    <div class="lvp-video-card">
                        <span class="lvp-video-badge">RECORRIDO AÉREO</span>

                        <div class="lvp-video-wrapper">
                            <iframe
                                src="https://www.youtube.com/embed/hWKKNF4gU74?rel=0&modestbranding=1"
                                title="Video Lotes en Venta"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen>
                            </iframe>
                        </div>
                    </div>

                    <div class="lvp-location-card">
                        <div class="lvp-location-image">
                            <img src="assets/img/lotes/lotes-preview.jpg" alt="Ubicación de lotes">
                        </div>

                        <div class="lvp-location-copy">
                            <span class="lvp-location-kicker">UBICACIÓN Y ENTORNO</span>
                            <h3>Revisa la zona antes de invertir</h3>
                            <p>
                                Consulta la ubicación geográfica y analiza el entorno para
                                visualizar mejor el potencial del proyecto.
                            </p>

                            <a
                                href="https://maps.google.com"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="lvp-btn-primary"
                            >
                                Ver en Google Maps
                            </a>
                        </div>
                    </div>

                </div>

            </div>

            <!-- BLOQUES DE VALOR -->
            <div class="lvp-benefits">
                <article class="lvp-benefit-card">
                    <span class="lvp-benefit-number">01</span>
                    <h4>Acceso privilegiado</h4>
                    <p>
                        Ubicación cercana a vialidades principales con excelente movilidad.
                    </p>
                </article>

                <article class="lvp-benefit-card">
                    <span class="lvp-benefit-number">02</span>
                    <h4>Entorno consolidado</h4>
                    <p>
                        Zona con servicios cercanos, hospitales, tiendas y puntos clave.
                    </p>
                </article>

                <article class="lvp-benefit-card">
                    <span class="lvp-benefit-number">03</span>
                    <h4>Alta proyección</h4>
                    <p>
                        Terrenos ideales para desarrollos con visión comercial y patrimonial.
                    </p>
                </article>
            </div>

            <div class="lvp-color-bar">
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