<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>

<link rel="stylesheet" href="assets/css/header.css">
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

<header class="header-full" id="siteHeader">
    <div class="header-container">

        <!-- LOGO -->
        <div class="header-logo" data-no-translate>
            <a href="index.php" class="header-logo-link" id="hiddenAdminTrigger">
                <img src="assets/img/icon/logofoter.png" alt="Logo Club Santiago">
                <span>Villas Eureka</span>
            </a>
        </div>

        <!-- NAV DESKTOP -->
        <nav class="header-nav">
            <a href="index.php" class="<?= $current_page === 'index.php' ? 'active' : ''; ?>">Inicio</a>
            <a href="renta.php" class="<?= $current_page === 'renta.php' ? 'active' : ''; ?>">Propiedades en renta</a>
            <a href="venta.php" class="<?= $current_page === 'venta.php' ? 'active' : ''; ?>">Propiedades en venta</a>
            <a href="villas.php" class="<?= $current_page === 'villas.php' ? 'active' : ''; ?>">Nuestras villas</a>
            <a href="alrededores.php" class="<?= $current_page === 'alrededores.php' ? 'active' : ''; ?>">Alrededores</a>
            <a href="contacto.php" class="<?= $current_page === 'contacto.php' ? 'active' : ''; ?>">Contáctanos</a>
        </nav>

        <!-- ACCIONES -->
        <div class="header-actions">

            <button
                id="langToggle"
                class="lang-toggle"
                type="button"
                aria-label="Cambiar idioma"
                data-current-lang="es"
            >
                ES | EN
            </button>

            <button
                id="themeToggle"
                class="toggle-theme"
                type="button"
                aria-label="Cambiar tema"
                data-no-translate
            >
                🌙
            </button>

            <button
                id="menuToggle"
                class="menu-toggle"
                type="button"
                aria-label="Abrir menú"
                aria-expanded="false"
                data-no-translate
            >
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>
    </div>

    <!-- MENÚ MÓVIL -->
    <div class="mobile-menu" id="mobileMenu">
        <nav class="mobile-nav">
            <a href="index.php" class="<?= $current_page === 'index.php' ? 'active' : ''; ?>">Inicio</a>
            <a href="renta.php" class="<?= $current_page === 'renta.php' ? 'active' : ''; ?>">Propiedades en renta</a>
            <a href="venta.php" class="<?= $current_page === 'venta.php' ? 'active' : ''; ?>">Propiedades en venta</a>
            <a href="villas.php" class="<?= $current_page === 'villas.php' ? 'active' : ''; ?>">Nuestras villas</a>
            <a href="alrededores.php" class="<?= $current_page === 'alrededores.php' ? 'active' : ''; ?>">Alrededores</a>
            <a href="contacto.php" class="<?= $current_page === 'contacto.php' ? 'active' : ''; ?>">Contáctanos</a>
        </nav>
    </div>
</header>

<script src="./assets/js/header.js"></script>

<script>
    window.APP_BASE_URL = <?= json_encode(rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\')) ?>;
</script>

<script src="./assets/js/translator.js"></script>