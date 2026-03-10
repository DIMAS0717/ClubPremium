<div class="content-section">
    <h2>Editar perfil</h2>

    <form method="post" enctype="multipart/form-data" class="biografia-form">

        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']); ?>">
        <input type="hidden" name="action" value="update_profile">

        <!-- FOTO ACTUAL -->
        <div class="form-group" style="text-align:center;margin-bottom:20px;">
            <?php
            $rutaFoto = !empty($admin['foto']) ? (__DIR__ . '/../../' . $admin['foto']) : '';
            ?>

            <?php if (!empty($admin['foto']) && is_file($rutaFoto)): ?>
                <img 
                    src="../<?= htmlspecialchars($admin['foto']); ?>" 
                    alt="Foto de perfil"
                    style="width:120px;height:120px;border-radius:50%;object-fit:cover;margin-bottom:10px;"
                >
            <?php else: ?>
                <div class="avatar-initial" style="width:120px;height:120px;border-radius:50%;margin:auto;font-size:40px;">
                    <?= strtoupper(substr($admin['nombre'] ?? 'A',0,1)); ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- NOMBRE -->
        <div class="form-group">
            <label>Nombre</label>
            <input 
                type="text" 
                name="nombre"
                required
                value="<?= htmlspecialchars($admin['nombre'] ?? ''); ?>"
                style="width:100%;padding:10px;border-radius:12px;border:1px solid #ddd;"
            >
        </div>

        <!-- CORREO -->
        <div class="form-group" style="margin-top:15px;">
            <label>Correo</label>
            <input 
                type="email" 
                name="correo"
                required
                value="<?= htmlspecialchars($admin['correo'] ?? ''); ?>"
                style="width:100%;padding:10px;border-radius:12px;border:1px solid #ddd;"
            >
        </div>

        <!-- PAIS / ESTADO -->
        <div class="form-group" style="margin-top:15px;display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:15px;">
            <div>
                <label>País</label>
                <input 
                    type="text" 
                    name="pais"
                    value="<?= htmlspecialchars($admin['pais'] ?? ''); ?>"
                    style="width:100%;padding:10px;border-radius:12px;border:1px solid #ddd;"
                >
            </div>

            <div>
                <label>Estado</label>
                <input 
                    type="text" 
                    name="estado"
                    value="<?= htmlspecialchars($admin['estado'] ?? ''); ?>"
                    style="width:100%;padding:10px;border-radius:12px;border:1px solid #ddd;"
                >
            </div>
        </div>

        <!-- FOTO -->
<div class="form-group" style="margin-top:15px;">
    <label>Cambiar foto de perfil</label>

    <!-- Vista previa -->
    <div style="margin-bottom:10px;">
        <img 
            id="previewFoto"
            src="#" 
            alt="Vista previa"
            style="display:none;width:120px;height:120px;object-fit:cover;border-radius:12px;border:1px solid #ddd;"
        >
    </div>

    <input 
        type="file" 
        name="foto"
        id="fotoInput"
        accept="image/*"
        style="width:100%;padding:10px;border-radius:12px;border:1px solid #ddd;background:#f8fafc;"
    >

    <small style="color:#666;">
        Formatos permitidos: JPG, PNG, WEBP. Máx 5MB.
    </small>
</div>

        <!-- BOTON -->
        <button type="submit" class="btn btn-primary" style="margin-top:20px;">
            <i class="fas fa-save"></i> Guardar perfil
        </button>

    </form>
</div>
<script>
document.getElementById("fotoInput").addEventListener("change", function(event){

    const file = event.target.files[0];

    if(!file) return;

    const reader = new FileReader();

    reader.onload = function(e){

        const preview = document.getElementById("previewFoto");

        preview.src = e.target.result;
        preview.style.display = "block";
    };

    reader.readAsDataURL(file);
});
</script>