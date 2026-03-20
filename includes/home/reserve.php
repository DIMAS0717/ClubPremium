<section class="booking-hero">
  <div class="booking-overlay"></div>

  <div class="booking-content">
    <div class="booking-header">
      <h2>¡ES TIEMPO DE UNAS<br>VACACIONES!</h2>
      <p>Paraíso tropical rodeado de jardines</p>
      <h3>¡RESERVA HOY!</h3>
    </div>

    <form class="booking-form" action="#" method="POST">
      <div class="booking-field">
        <input type="text" name="nombre" placeholder="Nombre" required>
      </div>

      <div class="booking-field">
        <input type="email" name="email" placeholder="Email" required>
      </div>

      <div class="booking-field">
        <input type="tel" name="telefono" placeholder="Teléfono" required>
      </div>

      <div class="booking-field">
        <select name="personas" required>
          <option value="" selected disabled>No. de personas</option>
          <option value="1">1 persona</option>
          <option value="2">2 personas</option>
          <option value="3">3 personas</option>
          <option value="4">4 personas</option>
          <option value="5">5 personas</option>
          <option value="6">6 personas</option>
          <option value="7">7 personas</option>
          <option value="8">8 personas</option>
        </select>
      </div>

      <div class="booking-field">
        <input type="date" name="llegada" required>
      </div>

      <div class="booking-field">
        <input type="date" name="salida" required>
      </div>

      <div class="booking-actions">
        <button type="submit">Reservar</button>
      </div>
    </form>
  </div>
</section>