<?php
$page_title = 'Alrededores - Villas Eureka';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= $page_title; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <link rel="stylesheet" href="assets/css/alrededores.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800&display=swap" rel="stylesheet">
</head>
<body>

<?php include __DIR__ . '/includes/header.php'; ?>

<main class="page-main alrededores-page">

    <!-- HERO / TÍTULO + MAPA -->
    <section class="around-hero-container">

        <div class="section around-header">
            <div class="around-title-wrap">
                <img src="assets/img/icon/iconoeureka.png" class="around-title-deco" alt="">
                <h1 class="around-title">Alrededores</h1>
                <img src="assets/img/icon/iconoeureka.png" class="around-title-deco" alt="">
            </div>

            <div class="around-title-underline gold-gradient-line"></div>

            <p class="around-subtitle">
                Descubre los servicios, actividades y experiencias que puedes disfrutar
                cerca de nuestras casas en Club Santiago.
            </p>
        </div>

        <div class="section around-map-section">
            <div class="around-map-card">

                <div class="around-map-info">
                    <h1 class="around-map-title">
                        Mapa de Servicios<br>
                        <span>Puntos de Interés Cercanos</span>
                    </h1>

                    <div class="around-map-divider"></div>

                    <ul class="around-map-list">
                        <li>
                            <svg class="around-map-icon" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path fill="currentColor" d="M10 2h4v6h6v4h-6v6h-4v-6H4V8h6z"/>
                            </svg>
                            <span>Hospital</span>
                            <span class="around-map-time">5 min</span>
                        </li>

                        <li>
                            <svg class="around-map-icon" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path fill="currentColor" d="M7 18c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm10 0c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zM7.2 14.3h9.4c.75 0 1.4-.4 1.7-1l3.6-6.5L20.4 4H5.2L4.3 2H1v2h2l3.6 7.6-1.4 2.4C4.5 15.4 5.5 17 7 17h12v-2H7.4c-.15 0-.25-.1-.25-.25z"/>
                            </svg>
                            <span>Supermercado</span>
                            <span class="around-map-time">4 min</span>
                        </li>

                        <li>
                            <svg class="around-map-icon" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg">
                                <path fill="currentColor" d="M1110 5113 c-214 -37 -400 -226 -430 -438 -6 -44 -10 -783 -10 -1963 l0 -1892 -28 -11 c-109 -40 -214 -137 -254 -236 -21 -54 -23 -71 -23 -264 l0 -206 27 -35 c56 -74 -76 -69 1759 -66 l1646 3 36 28 c66 50 69 64 65 291 l-3 202 -33 67 c-52 105 -143 184 -254 221 -17 5 -18 27 -18 279 l0 274 198 6 c164 5 213 10 293 30 338 87 595 359 664 704 14 68 15 204 13 1114 l-3 1035 -134 265 c-162 321 -182 353 -236 384 -153 90 -335 -14 -335 -193 0 -54 7 -72 101 -256 55 -109 99 -199 97 -200 -1 -1 -41 -21 -89 -44 -145 -71 -258 -184 -328 -326 -72 -147 -74 -159 -78 -558 -5 -400 -4 -403 65 -456 l35 -27 229 -3 228 -3 0 -319 c0 -359 -4 -388 -75 -494 -47 -72 -104 -121 -180 -157 -78 -37 -159 -49 -327 -49 l-138 0 -2 1488 c-3 1483 -3 1487 -24 1538 -47 115 -133 202 -241 246 l-58 23 -1065 1 c-586 1 -1076 0 -1090 -3z"/>
                            </svg>
                            <span>Gasolinera</span>
                            <span class="around-map-time">4 min</span>
                        </li>

                        <li>
                            <svg class="around-map-icon" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg">
                                <path fill="currentColor" d="M0 0 C8.88902486 -1.31029578 18.05920749 -2.50891983 25.80846298 2.84432602 C30.91580591 6.99546265 35.43919782 11.72764855 39.99925232 16.46044922 C41.80293304 18.25352878 43.61121972 20.04198494 45.42366081 21.82620907 C49.34147013 25.70389304 53.22472649 29.61170088 57.08086967 33.5506382 C62.81465099 39.40717965 68.60810543 45.20189347 74.41489132 50.98594544 C84.18461207 60.72085304 93.90476212 70.50386289 103.59716606 80.31570435 C113.19367462 90.03020137 122.81131607 99.72294469 132.45796394 109.38766479 C133.05871051 109.98956465 133.65945708 110.5914645 134.27840809 111.21160374 C137.32978301 114.26862845 140.38152787 117.32528356 143.43340827 120.38180363 C165.03380776 142.01650896 186.58440556 163.70020539 208.10668945 185.41259766 Z"/>
                            </svg>
                            <span>Restaurantes</span>
                            <span class="around-map-time">2–7 min</span>
                        </li>

                        <li>
                            <svg class="around-map-icon" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg">
                                <path fill="currentColor" d="M0 0 C14.52 0 29.04 0 44 0 C44 3.96 44 7.92 44 12 C45.959375 12.061875 47.91875 12.12375 49.9375 12.1875 C103.4026246 15.21532212 157.15116673 41.91179417 195.48925781 78.55664062 C196.51252583 79.53426838 197.54069027 80.50679224 198.57324219 81.47460938 C207.83550106 90.17944355 215.60013051 99.67811846 223 110 C223.46567383 110.64759277 223.93134766 111.29518555 224.41113281 111.96240234 C230.96413142 121.17414115 236.25278051 130.75115793 241 141 C241.53367187 142.12792969 242.06734375 143.25585938 242.6171875 144.41796875 C249.16215702 158.88133405 253.42162685 174.35658678 256 190 C256.13148438 190.79019531 256.26296875 191.58039063 256.3984375 192.39453125 C257.38898929 199.60949629 257 206.64917399 257 214 C242.48 214 227.96 214 213 214 Z"/>
                            </svg>
                            <span>Playas</span>
                            <span class="around-map-time">2 min</span>
                        </li>
                    </ul>
                </div>

                <div class="around-map-image">
                    <img id="openMap" src="assets/img/mapa.png" alt="Mapa de servicios cercanos">
                </div>

            </div>
        </div>

    </section>

    <!-- MODAL MAPA -->
    <div id="mapModal" class="map-modal">
        <span class="map-close">&times;</span>
        <img class="map-modal-content" id="mapModalImg" alt="Mapa ampliado">
    </div>

    <div class="section-spacer" style="height: 300px;"></div>

    <!-- DEPORTES -->
    <section class="around-activities-section">
        <div class="section-header">
            <span>ACTIVIDADES AL AIRE LIBRE</span>
            <h2 class="around-section-title">Deportes</h2>
        </div>

        <div class="around-activities-grid">

            <article class="around-activity-card around-activity-hiking">
                <div class="around-activity-badge badge-green">Deportes</div>
                <div class="around-activity-overlay">
                    <h3>Senderismo</h3>
                    <p>Rutas para disfrutar de la naturaleza y miradores espectaculares.</p>
                    <a target="_blank" rel="noopener noreferrer" href="https://www.alltrails.com/es-mx/mexico/colima/manzanillo" class="around-btn">Ver más →</a>
                </div>
            </article>

            <article class="around-activity-card around-activity-golf">
                <div class="around-activity-badge badge-green">Deporte</div>
                <div class="around-activity-overlay">
                    <h3>Golf</h3>
                    <p>Campo de golf cercano ideal para practicar o disfrutar con amigos.</p>
                    <a target="_blank" rel="noopener noreferrer" href="https://www.santiagoclubdegolf.com/" class="around-btn">Ver más →</a>
                </div>
            </article>

            <article class="around-activity-card around-activity-cycling">
                <div class="around-activity-badge badge-green">Deportes</div>
                <div class="around-activity-overlay">
                    <h3>Ciclismo</h3>
                    <p>Rutas seguras para recorrer en bicicleta y disfrutar el paisaje.</p>
                    <a target="_blank" rel="noopener noreferrer" href="https://www.alltrails.com/es-mx/mexico/colima/manzanillo/mountain-biking" class="around-btn">Ver más →</a>
                </div>
            </article>

        </div>
    </section>

    <!-- EXPERIENCIAS -->
    <section class="section around-activities-section">
        <div class="section-header">
            <h2 class="around-section-title">Experiencias recomendadas</h2>
        </div>

        <div class="around-activities-grid">

            <article class="around-activity-card experience-banana">
                <div class="around-activity-badge">Diversión</div>
                <div class="around-activity-overlay">
                    <h3>Playas</h3>
                    <p>Actividad divertida para grupos y familias con vista al mar.</p>
                    <a target="_blank" rel="noopener noreferrer" href="https://www.tripadvisor.com.mx/Attractions-g150791-Activities-c61-t52-Manzanillo_Pacific_Coast.html" class="around-btn">Ver más →</a>
                </div>
            </article>

            <article class="around-activity-card experience-sunset">
                <div class="around-activity-badge">Relax</div>
                <div class="around-activity-overlay">
                    <h3>Ver el atardecer</h3>
                    <p>Fotografías increíbles y momentos únicos en la bahía.</p>
                    <a href="#" class="around-btn">Ver más →</a>
                </div>
            </article>

            <article class="around-activity-card experience-food">
                <div class="around-activity-badge">Sabor</div>
                <div class="around-activity-overlay">
                    <h3>Comidas típicas</h3>
                    <p>Platillos tradicionales y mariscos frescos frente al mar.</p>
                    <a href="#" class="around-btn">Ver más →</a>
                </div>
            </article>

        </div>
    </section>

    <!-- RESTAURANTES -->
    <section class="section around-activities-section">
        <div class="section-header">
            <h2 class="around-section-title">Restaurantes</h2>
        </div>

        <div class="around-activities-grid">

            <article class="around-activity-card restaurant-oasis">
                <div class="around-activity-badge">Gastronomía</div>
                <div class="around-activity-overlay">
                    <h3>Restaurante OASIS</h3>
                    <p>Ambiente agradable y comida fresca con vista al mar.</p>
                    <a href="#" class="around-btn">Ver más →</a>
                </div>
            </article>

            <article class="around-activity-card restaurant-delfos">
                <div class="around-activity-badge">Mariscos</div>
                <div class="around-activity-overlay">
                    <h3>Restaurante Delfos</h3>
                    <p>Especialidades del mar, pescados y mariscos de calidad.</p>
                    <a href="#" class="around-btn">Ver más →</a>
                </div>
            </article>

            <article class="around-activity-card restaurant-eureka">
                <div class="around-activity-badge">Local</div>
                <div class="around-activity-overlay">
                    <h3>Tienda Eureka</h3>
                    <p>Tienda local con artesanías y productos típicos de la región.</p>
                    <a href="#" class="around-btn">Ver más →</a>
                </div>
            </article>

        </div>
    </section>

    <!-- GALERÍA -->
    <section class="gallery-section">
        <div class="gallery-header">
            <h2>Galería de Club Santiago</h2>
            <p>Un vistazo a lo que te espera en este paraíso frente al mar</p>
        </div>

        <div class="gallery-grid">
            <div class="gallery-item large" style="background-image:url('assets/images/ciclismo.jpg')">
                <span>Playa La Boquita</span>
            </div>

            <div class="gallery-item medium" style="background-image:url('assets/images/golf.jpg')">
                <span>Campo de Golf</span>
            </div>

            <div class="gallery-item medium" style="background-image:url('assets/images/playa.jpg')">
                <span>Atardeceres Mágicos</span>
            </div>

            <div class="gallery-item wide" style="background-image:url('assets/images/senderismo.png')">
                <span>Marina</span>
            </div>
        </div>

        <div class="gallery-cta">
            <a href="#" class="btn-gallery">Ver galería completa</a>
        </div>
    </section>

</main>

<?php include __DIR__ . '/includes/footer.php'; ?>

<script src="assets/js/app.js"></script>


</body>
</html>