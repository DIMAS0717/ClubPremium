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
    <link rel="stylesheet" href="assets/css/animation.css">
    <link rel="stylesheet" href="assets/css/drk_mode.css">
    <link rel="stylesheet" href="assets/css/responsive.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800&display=swap" rel="stylesheet">
</head>
<body>

<?php include __DIR__ . '/includes/header.php'; ?>

<main class="page-main alrededores-page">

    <!-- HERO / TÍTULO -->
    <section class="around-hero-container">
        <div class="around-header">
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
    </section>

    <!-- MAPA FLOTANTE -->
        <section class="around-map-section">
            <div class="around-map-card">

                <div class="around-map-info">
                    <h2 class="around-map-title">
                        Mapa de Servicios<br>
                        <span>Puntos de Interés Cercanos</span>
                    </h2>

                    <div class="around-map-divider"></div>

                    <ul class="around-map-list">
                        <li>
                            <svg class="around-map-icon" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <path fill="currentColor" d="M10 2h4v6h6v4h-6v6h-4v-6H4V8h6z"/>
                            </svg>
                            <span class="around-map-label">Hospital</span>
                            <span class="around-map-time">5 min</span>
                        </li>

                        <li>
                            <svg class="around-map-icon" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <path fill="currentColor" d="M7 18c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm10 0c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zM7.2 14.3h9.4c.75 0 1.4-.4 1.7-1l3.6-6.5L20.4 4H5.2L4.3 2H1v2h2l3.6 7.6-1.4 2.4C4.5 15.4 5.5 17 7 17h12v-2H7.4c-.15 0-.25-.1-.25-.25z"/>
                            </svg>
                            <span class="around-map-label">Supermercado</span>
                            <span class="around-map-time">4 min</span>
                        </li>

                        <li>
                            <svg class="around-map-icon" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <path fill="currentColor" d="M7 2h7a2 2 0 0 1 2 2v14h1a1 1 0 0 1 1 1v1H6v-1a1 1 0 0 1 1-1h1V4a2 2 0 0 1 2-2zm2 4v3h6V6zm0 5v7h6v-7zM18 6h1a2 2 0 0 1 2 2v4h-3z"/>
                            </svg>
                            <span class="around-map-label">Gasolinera</span>
                            <span class="around-map-time">4 min</span>
                        </li>

                        <li>
                            <svg class="around-map-icon" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <path fill="currentColor" d="M11 2h2v9l2.2-2.2 1.4 1.4L13 14l3.6 3.8-1.4 1.4L13 17v5h-2v-5l-2.2 2.2-1.4-1.4L11 14 7.4 10.2l1.4-1.4L11 11z"/>
                            </svg>
                            <span class="around-map-label">Restaurantes</span>
                            <span class="around-map-time">2 min</span>
                        </li>

                        <li>
                            <svg class="around-map-icon" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <path fill="currentColor" d="M12 2a7 7 0 0 1 7 7c0 3.2-2.3 5.8-5.2 6.4L13 16v6h-2v-6l-.8-.6C7.3 14.8 5 12.2 5 9a7 7 0 0 1 7-7z"/>
                            </svg>
                            <span class="around-map-label">Playas</span>
                            <span class="around-map-time">2 min</span>
                        </li>
                    </ul>
                </div>

                <div class="around-map-image">
                    <img id="openMap" src="assets/img/mapa.png" alt="Mapa de servicios cercanos">
                </div>

            </div>
        </section>

    <!-- MODAL MAPA -->
    <div id="mapModal" class="map-modal">
        <span class="map-close">&times;</span>
        <img class="map-modal-content" id="mapModalImg" alt="Mapa ampliado">
    </div>

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
    <section class="around-activities-section">
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
    <section class="around-activities-section">
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

</main>

<?php include __DIR__ . '/includes/footer.php'; ?>

<script src="assets/js/app.js"></script>

</body>
</html>