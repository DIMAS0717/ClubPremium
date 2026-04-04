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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800&display=swap" rel="stylesheet">
</head>
<body>

<?php include __DIR__ . '/includes/header.php'; ?>

<main class="page-main lotes-page">

    <!-- HERO -->
    <section class="lotes-hero-container">
        <div class="lotes-hero-bg"></div>
        <div class="lotes-hero-overlay"></div>

        <div class="lotes-header">
            <div class="lotes-title-wrap">
                <img src="assets/img/icon/iconoeureka.png" class="lotes-title-deco" alt="">
                <h1 class="lotes-title">LOTES EN VENTA</h1>
                <img src="assets/img/icon/iconoeureka.png" class="lotes-title-deco" alt="">
            </div>

            <div class="lotes-title-underline"></div>

            <p class="lotes-subtitle">
                Oportunidad de inversión en una excelente ubicación dentro de la
                Colonia Miramar, Maravillas del Campo, Manzanillo, Colima.
            </p>
        </div>
    </section>

    <!-- BLOQUE PRINCIPAL -->
    <section class="lotes-video-section">
        <div class="lotes-video-card">

            <div class="lotes-top-content">
                <div class="lotes-left">
                    <h2>
                        Se venden 14 terrenos de oportunidad para desarrolladoras o inversionistas
                        dentro de la Colonia Miramar, Maravillas del Campo, Manzanillo, Colima.
                    </h2>

                    <p>
                        Están ubicados entre la calle Juárez y calle Miguel Domínguez, a tan solo
                        media cuadra de la carretera con dirección Manzanillo - Cihuatlán y Puerto Vallarta.
                        Las lotificaciones se encuentran a 15 minutos de los centros comerciales como lo son:
                        Soriana, La Comercial Mexicana y Walmart. Además de los hospitales generales y
                        particulares. Cabe mencionar que la distancia al mar es de solo 5 minutos.
                    </p>

                    <div class="lotes-price-box">
                        <span class="lotes-price-label">PRECIO</span>
                        <strong>$5,000</strong>
                        <small>por metro cuadrado</small>
                    </div>
                </div>

                <div class="lotes-right">
                    <div class="lotes-map-preview">
                        <img src="assets/img/lotes/lotes-preview.jpg" alt="Vista previa lotes">
                    </div>

                    <p class="lotes-map-text">
                        En el siguiente link podrá encontrar la ubicación geográfica en la cual usted podrá
                        manipular el entorno en un ángulo 360°.
                    </p>

                    <a
                        href="https://maps.google.com"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="lotes-map-link"
                    >
                        <span class="map-pin">📍</span>
                        <span>Google Maps</span>
                    </a>
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

            <div class="lotes-video-wrapper">
                <iframe
                    src="https://www.youtube.com/embed/TU_VIDEO_ID?rel=0&modestbranding=1"
                    title="Video Lotes en Venta"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen>
                </iframe>
            </div>

        </div>
    </section>

</main>

<?php include __DIR__ . '/includes/footer.php'; ?>

<script src="assets/js/app.js"></script>
</body>
</html>