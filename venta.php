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

            <!-- HERO -->
            <section class="lvp-hero">
                <div class="lvp-hero-overlay"></div>

                <div class="lvp-hero-content">
                    <span class="lvp-eyebrow">OPORTUNIDAD DE INVERSIÓN</span>

                    <h1 class="lvp-title">
                        Lotes en venta en <span>Miramar</span>
                    </h1>

                    <div class="lvp-divider"></div>

                    <p class="lvp-lead">
                        Terrenos con ubicación estratégica y alto potencial para desarrollo
                        residencial o inversión patrimonial en Manzanillo, Colima.
                    </p>

                    <div class="lvp-hero-actions">
                        <a href="#video-recorrido" class="lvp-btn-primary">Ver recorrido</a>
                        <a href="https://maps.google.com" target="_blank" rel="noopener noreferrer" class="lvp-btn-secondary">
                            Ver ubicación
                        </a>
                    </div>
                </div>
            </section>

            <!-- STATS -->
            <section class="lvp-stats">
                <article class="lvp-stat-card">
                    <span class="lvp-stat-label">Precio desde</span>
                    <strong class="lvp-stat-value">$5,000</strong>
                    <span class="lvp-stat-note">por m²</span>
                </article>

                <article class="lvp-stat-card">
                    <span class="lvp-stat-label">Disponibilidad</span>
                    <strong class="lvp-stat-value">14</strong>
                    <span class="lvp-stat-note">terrenos disponibles</span>
                </article>

                <article class="lvp-stat-card">
                    <span class="lvp-stat-label">Distancia al mar</span>
                    <strong class="lvp-stat-value">5 min</strong>
                    <span class="lvp-stat-note">aproximadamente</span>
                </article>

                <article class="lvp-stat-card">
                    <span class="lvp-stat-label">Entorno</span>
                    <strong class="lvp-stat-value">Alta</strong>
                    <span class="lvp-stat-note">proyección de crecimiento</span>
                </article>
            </section>

            <!-- INTRO -->
            <section class="lvp-intro">
                <div class="lvp-intro-copy">
                    <span class="lvp-section-kicker">DESARROLLO Y PLUSVALÍA</span>
                    <h2>Una ubicación pensada para crecer con valor</h2>

                    <p>
                        Se venden 14 terrenos de oportunidad para desarrolladoras o inversionistas
                        dentro de la Colonia Miramar, Maravillas del Campo, Manzanillo, Colima.
                    </p>

                    <p>
                        Están ubicados entre la calle Juárez y calle Miguel Domínguez, a tan solo
                        media cuadra de la carretera con dirección Manzanillo - Cihuatlán y Puerto Vallarta.
                        La zona cuenta con rápida conectividad y cercanía a servicios clave.
                    </p>
                </div>

                <div class="lvp-intro-list">
                    <div class="lvp-feature-item">
                        <span class="lvp-feature-icon">✓</span>
                        <div>
                            <h3>Ubicación estratégica</h3>
                            <p>Ideal para proyectos con visión comercial, turística o patrimonial.</p>
                        </div>
                    </div>

                    <div class="lvp-feature-item">
                        <span class="lvp-feature-icon">✓</span>
                        <div>
                            <h3>Conectividad inmediata</h3>
                            <p>Acceso ágil a vialidades principales y puntos clave de Manzanillo.</p>
                        </div>
                    </div>

                    <div class="lvp-feature-item">
                        <span class="lvp-feature-icon">✓</span>
                        <div>
                            <h3>Entorno consolidado</h3>
                            <p>Cercanía con hospitales, supermercados, playa y zonas de servicio.</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- MEDIA -->
            <section class="lvp-media-grid" id="video-recorrido">

                <article class="lvp-video-card">
                    <div class="lvp-card-head">
                        <span class="lvp-card-kicker">RECORRIDO AÉREO</span>
                        <h3>Visualiza el terreno y su contexto</h3>
                    </div>

                    <div class="lvp-video-wrapper">
                        <iframe
                            src="https://www.youtube.com/embed/hWKKNF4gU74?rel=0&modestbranding=1"
                            title="Video Lotes en Venta"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen>
                        </iframe>
                    </div>
                </article>

                <article class="lvp-location-card">
                    <div class="lvp-location-image">
                        <img src="/assets/img/fondos/fondoventa.png" alt="Ubicación de lotes">
                    </div>

                    <div class="lvp-location-copy">
                        <span class="lvp-card-kicker">UBICACIÓN Y ENTORNO</span>
                        <h3>Revisa la zona antes de invertir</h3>

                        <p>
                            Consulta la ubicación geográfica y analiza el entorno para visualizar
                            mejor el potencial del proyecto y su cercanía con playa, hospitales,
                            supermercados y vialidades principales.
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
                </article>
            </section>

            <!-- BENEFITS -->
            <section class="lvp-benefits">
                <article class="lvp-benefit-card">
                    <span class="lvp-benefit-number">01</span>
                    <h4>Acceso privilegiado</h4>
                    <p>
                        Conexión rápida a vías principales para facilitar movilidad, logística y plusvalía.
                    </p>
                </article>

                <article class="lvp-benefit-card">
                    <span class="lvp-benefit-number">02</span>
                    <h4>Zona con servicios</h4>
                    <p>
                        Cercanía con hospitales, supermercados, playa y puntos urbanos importantes.
                    </p>
                </article>

                <article class="lvp-benefit-card">
                    <span class="lvp-benefit-number">03</span>
                    <h4>Alta proyección</h4>
                    <p>
                        Terrenos con perfil ideal para desarrollos que buscan crecimiento patrimonial.
                    </p>
                </article>
            </section>

            <!-- CTA FINAL -->
            <section class="lvp-cta">
                <div class="lvp-cta-copy">
                    <span class="lvp-section-kicker">INVERSIÓN CON VISIÓN</span>
                    <h2>Descubre una oportunidad con ubicación, proyección y valor</h2>
                    <p>
                        Agenda una visita, solicita más información o conoce el entorno del proyecto.
                    </p>
                </div>

                <div class="lvp-cta-actions">
                    <a href="contacto.php" class="lvp-btn-primary">Solicitar información</a>
                    <a href="https://maps.google.com" target="_blank" rel="noopener noreferrer" class="lvp-btn-secondary">
                        Explorar ubicación
                    </a>
                </div>
            </section>

        </div>
    </section>

</main>

<?php include __DIR__ . '/includes/footer.php'; ?>

<script src="assets/js/app.js"></script>
</body>
</html>