<?php
require_once __DIR__ . '/includes/property_helpers.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$mensajeExito = '';
$mensajeError = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre   = trim($_POST['nombre'] ?? '');
    $apellido = trim($_POST['apellido'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $mensaje  = trim($_POST['mensaje'] ?? '');

    if ($nombre === '' || $apellido === '' || $email === '' || $mensaje === '') {
        $mensajeError = 'Completa todos los campos.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $mensajeError = 'Ingresa un correo electrónico válido.';
    } else {
        $mensajeExito = 'Tu mensaje fue recibido correctamente. Pronto nos pondremos en contacto contigo.';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Contáctanos - Casas Club Santiago</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="assets/styles.css">
    <link rel="stylesheet" href="assets/contact.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>

<?php include __DIR__ . '/includes/header.php'; ?>

<main class="page-main">

    <section class="cs-contact-section">

        <div class="cs-title-wrap">
            <img src="assets/images/iconoeureka.png" class="cs-title-deco" alt="">
            <h1 class="cs-title">Contáctanos</h1>
            <img src="assets/images/iconoeureka.png" class="cs-title-deco" alt="">
        </div>

        <span class="cs-title-underline"></span>

        <div class="cs-contact-box">

            <div class="cs-left-panel">

                <div class="cs-info-block">
                    <div class="cs-icon"><i class="fas fa-envelope"></i></div>
                    <div>
                        <p class="cs-label">Email</p>
                        <p class="cs-detail">goyorealestate@gmail.com</p>
                    </div>
                </div>

                <div class="cs-info-block">
                    <div class="cs-icon"><i class="fas fa-phone"></i></div>
                    <div>
                        <p class="cs-label">Teléfonos</p>
                        <p class="cs-detail">(314) 355 0525<br>(314) 355 0026</p>
                    </div>
                </div>

                <div class="cs-info-block">
                    <div class="cs-icon"><i class="fas fa-map-marker-alt"></i></div>
                    <div>
                        <p class="cs-label">Ubicación</p>
                        <p class="cs-detail">
                            Calle Delfin D-05, Fracc. Club Santiago,<br>
                            Manzanillo, Colima, MX. C.P. 28868
                        </p>
                    </div>
                </div>

                <div class="cs-socials">
                    <p class="cs-label">Síguenos en Redes Sociales</p>
                    <div class="cs-icons">
                        <a class="cs-social facebook" href="https://www.facebook.com/villaseurekamanzanillo/" target="_blank" rel="noopener noreferrer">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a class="cs-social instagram" href="https://www.instagram.com/villaseurekamanzanillo?igsh=bXR2c3o0Ync2cjF4" target="_blank" rel="noopener noreferrer">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a class="cs-social whatsapp" href="https://wa.me/523141600430?text=Hola,%20me%20gustaría%20más%20información." target="_blank" rel="noopener noreferrer">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                        <a class="cs-social tiktok" href="https://www.tiktok.com/@villas.eureka" target="_blank" rel="noopener noreferrer">
                            <i class="fab fa-tiktok"></i>
                        </a>
                    </div>
                </div>

            </div>

            <form class="cs-right-panel" method="post" action="">
                <h3 style="margin-top: 5px">Envíanos un Mensaje</h3>

                <?php if ($mensajeExito !== ''): ?>
                    <p class="cs-message-success"><?php echo e($mensajeExito); ?></p>
                <?php endif; ?>

                <?php if ($mensajeError !== ''): ?>
                    <p class="cs-message-error"><?php echo e($mensajeError); ?></p>
                <?php endif; ?>

                <div class="cs-form-row">
                    <input
                        type="text"
                        name="nombre"
                        placeholder="Nombre"
                        class="cs-input"
                        required
                        value="<?php echo e($_POST['nombre'] ?? ''); ?>"
                    >

                    <input
                        type="text"
                        name="apellido"
                        placeholder="Apellido"
                        class="cs-input"
                        required
                        value="<?php echo e($_POST['apellido'] ?? ''); ?>"
                    >
                </div>

                <input
                    type="email"
                    name="email"
                    placeholder="Correo electrónico"
                    class="cs-input"
                    required
                    value="<?php echo e($_POST['email'] ?? ''); ?>"
                >

                <textarea
                    name="mensaje"
                    placeholder="Mensaje / Fechas / Propiedad de interés.."
                    class="cs-input"
                    required
                ><?php echo e($_POST['mensaje'] ?? ''); ?></textarea>

                <button type="submit" class="cs-submit">Enviar Mensaje</button>
            </form>

        </div>
    </section>

</main>

<?php include __DIR__ . '/includes/footer.php'; ?>

</body>
</html>