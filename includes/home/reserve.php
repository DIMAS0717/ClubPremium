<?php
$mensaje_reserva = "";
$tipo_mensaje = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre   = trim($_POST["nombre"] ?? "");
    $email    = trim($_POST["email"] ?? "");
    $telefono = trim($_POST["telefono"] ?? "");
    $personas = trim($_POST["personas"] ?? "");
    $entrada  = trim($_POST["entrada"] ?? "");
    $salida   = trim($_POST["salida"] ?? "");

    $errores = [];

    date_default_timezone_set('America/Mexico_City');
    $hoy = date('Y-m-d');

    if ($nombre === "") {
        $errores[] = "El nombre es obligatorio.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errores[] = "Ingresa un correo válido.";
    }

    if ($telefono === "" || !preg_match('/^[0-9]{10,15}$/', $telefono)) {
        $errores[] = "Ingresa un teléfono válido de 10 a 15 dígitos.";
    }

    if ($personas === "" || !ctype_digit($personas) || (int)$personas < 1) {
        $errores[] = "Ingresa una cantidad válida de personas.";
    }

    if ($entrada === "") {
        $errores[] = "La fecha de entrada es obligatoria.";
    }

    if ($salida === "") {
        $errores[] = "La fecha de salida es obligatoria.";
    }

    if ($entrada !== "" && $entrada < $hoy) {
        $errores[] = "La fecha de entrada no puede ser anterior a hoy.";
    }

    if ($salida !== "" && $salida < $hoy) {
        $errores[] = "La fecha de salida no puede ser anterior a hoy.";
    }

    if ($entrada !== "" && $salida !== "" && $salida < $entrada) {
        $errores[] = "La fecha de salida no puede ser menor que la fecha de entrada.";
    }

    if (empty($errores)) {
        $fechaEntradaFormateada = date("d/m/Y", strtotime($entrada));
        $fechaSalidaFormateada  = date("d/m/Y", strtotime($salida));

        $destinatario = "tu_correo@dominio.com"; // <-- CAMBIA ESTE CORREO
        $asunto = "Nueva solicitud de reserva";

        $contenido = "Nombre: " . $nombre . "\n";
        $contenido .= "Correo: " . $email . "\n";
        $contenido .= "Numero: " . $telefono . "\n";
        $contenido .= "Cuantas personas: " . $personas . "\n";
        $contenido .= "Fecha de entrada: " . $fechaEntradaFormateada . "\n";
        $contenido .= "Fecha de salida: " . $fechaSalidaFormateada . "\n";

        $headers = "From: noreply@tudominio.com\r\n";
        $headers .= "Reply-To: " . $email . "\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

        if (mail($destinatario, $asunto, $contenido, $headers)) {
            $mensaje_reserva = "Tu solicitud fue enviada correctamente.";
            $tipo_mensaje = "exito";
        } else {
            $mensaje_reserva = "No se pudo enviar la solicitud. Intenta nuevamente.";
            $tipo_mensaje = "error";
        }
    } else {
        $mensaje_reserva = implode("<br>", $errores);
        $tipo_mensaje = "error";
    }
}
?>

<section class="booking-hero">
  <div class="booking-overlay"></div>

  <div class="booking-content">
    <div class="booking-header">
      <h2>¡ES TIEMPO DE UNAS VACACIONES!</h2>
      <p>Paraíso tropical rodeado de jardines</p>
      <h3>¡RESERVA HOY!</h3>
    </div>

    <?php if (!empty($mensaje_reserva)): ?>
      <div class="booking-alert <?php echo $tipo_mensaje; ?>">
        <?php echo $mensaje_reserva; ?>
      </div>
    <?php endif; ?>

    <form class="booking-form" action="" method="POST">
      <div class="booking-field">
        <input
          type="text"
          name="nombre"
          placeholder="Nombre"
          required
          value="<?php echo htmlspecialchars($_POST['nombre'] ?? ''); ?>"
        >
      </div>

      <div class="booking-field">
        <input
          type="email"
          name="email"
          placeholder="Correo electrónico"
          required
          value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
        >
      </div>

      <div class="booking-field">
        <input
          type="tel"
          name="telefono"
          placeholder="Teléfono"
          required
          inputmode="numeric"
          pattern="[0-9]{10,15}"
          maxlength="15"
          value="<?php echo htmlspecialchars($_POST['telefono'] ?? ''); ?>"
        >
      </div>

      <div class="booking-field">
        <input
          type="number"
          name="personas"
          placeholder="No. de personas"
          required
          min="1"
          step="1"
          value="<?php echo htmlspecialchars($_POST['personas'] ?? ''); ?>"
        >
      </div>

      <div class="booking-field">
        <input
          type="date"
          name="entrada"
          required
          min="<?php echo date('Y-m-d'); ?>"
          value="<?php echo htmlspecialchars($_POST['entrada'] ?? ''); ?>"
        >
        <span class="booking-date-label">Fecha de entrada</span>
      </div>

      <div class="booking-field">
        <input
          type="date"
          name="salida"
          required
          min="<?php echo date('Y-m-d'); ?>"
          value="<?php echo htmlspecialchars($_POST['salida'] ?? ''); ?>"
        >
        <span class="booking-date-label">Fecha de salida</span>
      </div>

      <div class="booking-actions">
        <button type="submit">Reservar</button>
      </div>
    </form>
  </div>
</section>

<script>
document.addEventListener("DOMContentLoaded", function () {
  const entrada = document.querySelector('input[name="entrada"]');
  const salida = document.querySelector('input[name="salida"]');

  if (entrada && salida) {
    entrada.addEventListener("change", function () {
      salida.min = this.value;
      if (salida.value && salida.value < this.value) {
        salida.value = this.value;
      }
    });
  }
});
</script>