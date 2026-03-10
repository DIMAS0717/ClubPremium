<div class="content-section">
    <h2>Cambiar contraseña</h2>

    <form method="post" class="biografia-form">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']); ?>">
        <input type="hidden" name="action" value="change_password">

        <div class="form-group">
            <label>Contraseña actual</label>
            <input type="password" name="password_actual" required
                   style="width:100%;padding:10px;border-radius:12px;border:1px solid #ddd;">
        </div>

        <div class="form-group" style="margin-top:15px;">
            <label>Nueva contraseña</label>
            <input type="password" name="password_nueva" required
                   style="width:100%;padding:10px;border-radius:12px;border:1px solid #ddd;">
            <small class="hint">Mínimo 8 caracteres, mayúscula, minúscula y número.</small>
        </div>

        <div class="form-group" style="margin-top:15px;">
            <label>Repetir nueva contraseña</label>
            <input type="password" name="password_nueva2" required
                   style="width:100%;padding:10px;border-radius:12px;border:1px solid #ddd;">
        </div>

        <button type="submit" class="btn btn-primary" style="margin-top:20px;">
            <i class="fas fa-save"></i> Actualizar contraseña
        </button>
    </form>
</div>