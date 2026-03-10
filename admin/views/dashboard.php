<div class="content-section" id="mi-perfil">
    <h2>Información Personal</h2>

    <div class="profile-info">
        <div class="info-item">
            <span class="info-label">Nombre Completo</span>
            <span class="info-value">
                <?= htmlspecialchars(($admin['nombre'] ?? '') ?: ($admin['username'] ?? 'Administrador')); ?>
            </span>
        </div>

        <div class="info-item">
            <span class="info-label">Correo Electrónico</span>
            <span class="info-value">
                <?= htmlspecialchars($admin['correo'] ?? ''); ?>
            </span>
        </div>

        <div class="info-item">
            <span class="info-label">Rol</span>
            <span class="info-value">
                <span class="role-badge admin">Administrador</span>
            </span>
        </div>

        <div class="info-item">
            <span class="info-label">Fecha de Registro</span>
            <span class="info-value">
                <?=
                    !empty($admin['created_at'])
                    ? date('d/m/Y', strtotime($admin['created_at']))
                    : '—';
                ?>
            </span>
        </div>
    </div>
</div>
<div class="content-section">
    <h2>Foto de Perfil</h2>

    <div class="avatar-container">
        <div class="avatar-preview">
            <?php
            $rutaFoto = !empty($admin['foto']) ? (__DIR__ . '/../../' . $admin['foto']) : '';
            ?>

            <?php if (!empty($admin['foto']) && is_file($rutaFoto)): ?>
                <img src="../<?= htmlspecialchars($admin['foto']); ?>" alt="Foto de perfil">
            <?php else: ?>
                <div class="avatar-initial">
                    <?php
                    $nombreMostrar = ($admin['nombre'] ?? '') ?: ($admin['username'] ?? 'A');
                    echo strtoupper(substr($nombreMostrar, 0, 1));
                    ?>
                </div>
            <?php endif; ?>
        </div>

        <form method="post" enctype="multipart/form-data" class="avatar-form">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']); ?>">
            <input type="hidden" name="action" value="update_avatar">

            <div class="form-group file-input-wrapper">
                <label for="avatar" class="file-input-label">
                    <i class="fas fa-camera"></i> Cambiar foto
                </label>

                <input
                    type="file"
                    id="avatar"
                    name="foto"
                    class="file-input"
                    accept="image/*"
                >

                <div id="file-name-display">No se ha seleccionado ningún archivo</div>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Guardar foto
            </button>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const avatarInput = document.getElementById('avatar');
    const fileNameDisplay = document.getElementById('file-name-display');

    if (avatarInput && fileNameDisplay) {
        avatarInput.addEventListener('change', function () {
            if (this.files && this.files[0]) {
                fileNameDisplay.textContent = this.files[0].name;
            } else {
                fileNameDisplay.textContent = 'No se ha seleccionado ningún archivo';
            }
        });
    }
});
</script>

<div class="content-section">
    <h2>Últimos movimientos</h2>

    <?php if (!empty($movimientos)): ?>
        <div class="articles-container">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Tipo</th>
                        <th>Detalle</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($movimientos as $mov): ?>
                        <tr>
                            <td>
                                <?= !empty($mov['fecha']) ? date('d/m/Y H:i', strtotime($mov['fecha'])) : '—'; ?>
                            </td>
                            <td><?= htmlspecialchars($mov['tipo'] ?? ''); ?></td>
                            <td><?= htmlspecialchars($mov['detalle'] ?? ''); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p class="no-items">
            <i class="fas fa-info-circle"></i>
            No hay movimientos recientes todavía.
        </p>
    <?php endif; ?>
</div>