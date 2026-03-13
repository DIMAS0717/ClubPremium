<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>

<link rel="stylesheet" href="assets/css/header.css">
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&family=Poppins:wght@400;600&display=swap" rel="stylesheet">

<header class="header-full">
    <div class="header-container">

        <div class="header-logo">
    <a href="index.php" class="header-logo-link">
        <img src="assets/img/icon/logofoter.png" alt="Logo Club Santiago">
        <span>Villas Eureka</span>
    </a>
</div>

        <nav class="header-nav">
            <a href="index.php" class="<?= $current_page === 'index.php' ? 'active' : ''; ?>">Inicio</a>
            <a href="renta.php" class="<?= $current_page === 'renta.php' ? 'active' : ''; ?>">Propiedades en renta</a>
            <a href="venta.php" class="<?= $current_page === 'venta.php' ? 'active' : ''; ?>">Propiedades en venta</a>
            <a href="villas.php" class="<?= $current_page === 'villas.php' ? 'active' : ''; ?>">Nuestras villas</a>
            <a href="alrededores.php" class="<?= $current_page === 'alrededores.php' ? 'active' : ''; ?>">Alrededores</a>
            <a href="contacto.php" class="<?= $current_page === 'contacto.php' ? 'active' : ''; ?>">Contáctanos</a>
        </nav>

        <div class="header-actions">
            <button id="themeToggle" class="toggle-theme" type="button" aria-label="Cambiar tema">🌙</button>
            <a href="admin/login.php" class="admin-dot" title="Administración" aria-label="Administración"></a>
        </div>

    </div>
</header>

<script>
window.addEventListener('scroll', function () {
    var header = document.querySelector('.header-full');
    if (!header) return;

    if (window.scrollY > 50) {
        header.classList.add('scrolled');
    } else {
        header.classList.remove('scrolled');
    }
});
</script>