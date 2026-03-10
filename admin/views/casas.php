<?php
$conn = Database::getConnection();

/* =============================
   CARGAR PROPIEDAD A EDITAR
============================= */
$prop_editar = null;

if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    $stmt = $conn->prepare("SELECT * FROM properties WHERE id = ?");
    $stmt->execute([$_GET['edit']]);
    $prop_editar = $stmt->fetch(PDO::FETCH_ASSOC);
}

/* =============================
   CARGAR FOTOS EXISTENTES
============================= */
$fotos_slider = [];
$fotos_galeria = [];

if (!empty($prop_editar)) {
    $stmtFotos = $conn->prepare("
        SELECT id, archivo, tipo 
        FROM property_photos 
        WHERE property_id = ? 
        ORDER BY id DESC
    ");
    $stmtFotos->execute([$prop_editar['id']]);
    $fotos = $stmtFotos->fetchAll(PDO::FETCH_ASSOC);

    foreach ($fotos as $foto) {
        if (($foto['tipo'] ?? '') === 'galeria') {
            $fotos_galeria[] = $foto;
        } else {
            $fotos_slider[] = $foto;
        }
    }
}

/* =============================
   LISTADO DE PROPIEDADES
============================= */
$stmt = $conn->query("
    SELECT id, nombre, capacidad, estado_base, categoria
    FROM properties
    ORDER BY id DESC
");
$propiedades = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="content-section">
    <h2>Subir / editar casas</h2>

    <form method="post" enctype="multipart/form-data" class="biografia-form">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']); ?>">
        <input type="hidden" name="action" value="save_property">
        <input type="hidden" name="id_prop" value="<?= $prop_editar['id'] ?? 0; ?>">
        <input type="hidden" name="foto_principal_actual" value="<?= htmlspecialchars($prop_editar['foto_principal'] ?? ''); ?>">

        <div class="form-group">
            <label>Nombre de la casa</label>
            <input type="text" name="nombre"
                   value="<?= htmlspecialchars($prop_editar['nombre'] ?? ''); ?>"
                   required
                   style="width:100%;padding:10px;border-radius:12px;border:1px solid #ddd;">
        </div>

        <div class="form-group" style="margin-top:15px;display:grid;grid-template-columns:repeat(auto-fit,minmax(170px,1fr));gap:15px;">
            <div>
                <label>Capacidad</label>
                <input type="number" name="capacidad"
                       value="<?= htmlspecialchars($prop_editar['capacidad'] ?? 8); ?>"
                       required
                       style="width:100%;padding:10px;border-radius:12px;border:1px solid #ddd;">
            </div>
            <div>
                <label>Recámaras</label>
                <input type="number" name="recamaras"
                       value="<?= htmlspecialchars($prop_editar['recamaras'] ?? ''); ?>"
                       style="width:100%;padding:10px;border-radius:12px;border:1px solid #ddd;">
            </div>
            <div>
                <label>Baños</label>
                <input type="number" name="banos"
                       value="<?= htmlspecialchars($prop_editar['banos'] ?? ''); ?>"
                       style="width:100%;padding:10px;border-radius:12px;border:1px solid #ddd;">
            </div>
        </div>

        <div class="form-group" style="margin-top:15px;">
            <label>Estacionamiento</label>
            <input type="text" name="estacionamiento"
                   value="<?= htmlspecialchars($prop_editar['estacionamiento'] ?? ''); ?>"
                   style="width:100%;padding:10px;border-radius:12px;border:1px solid #ddd;">
        </div>

        <div class="form-group" style="margin-top:15px;">
            <label>Descripción corta</label>
            <input type="text" name="descripcion_corta"
                   value="<?= htmlspecialchars($prop_editar['descripcion_corta'] ?? ''); ?>"
                   required
                   style="width:100%;padding:10px;border-radius:12px;border:1px solid #ddd;">
        </div>

        <div class="form-group" style="margin-top:15px;">
            <label>Descripción larga</label>
            <textarea name="descripcion_larga" rows="4"
                      style="width:100%;padding:12px;border-radius:12px;border:1px solid #ddd;"><?= htmlspecialchars($prop_editar['descripcion_larga'] ?? ''); ?></textarea>
        </div>

        <div class="form-group" style="margin-top:15px;">
            <label>Ubicación</label>
            <input type="text" name="ubicacion"
                   value="<?= htmlspecialchars($prop_editar['ubicacion'] ?? ''); ?>"
                   style="width:100%;padding:10px;border-radius:12px;border:1px solid #ddd;">
        </div>

        <div class="form-group" style="margin-top:15px;">
            <label>Distancia al mar</label>
            <input type="text" name="distancia_mar"
                   value="<?= htmlspecialchars($prop_editar['distancia_mar'] ?? ''); ?>"
                   style="width:100%;padding:10px;border-radius:12px;border:1px solid #ddd;">
        </div>

        <div class="form-group" style="margin-top:15px;">
            <label>Servicios</label>
            <textarea name="servicios" rows="3"
                      style="width:100%;padding:12px;border-radius:12px;border:1px solid #ddd;"><?= htmlspecialchars($prop_editar['servicios'] ?? ''); ?></textarea>
        </div>

        <div class="form-group" style="margin-top:15px;">
            <label>Indicaciones</label>
            <textarea name="indicaciones" rows="3"
                      style="width:100%;padding:12px;border-radius:12px;border:1px solid #ddd;"><?= htmlspecialchars($prop_editar['indicaciones'] ?? ''); ?></textarea>
        </div>

        <div class="form-group" style="margin-top:15px;">
            <label>Enlace Drive</label>
            <input type="url" name="enlace_drive"
                   value="<?= htmlspecialchars($prop_editar['enlace_drive'] ?? ''); ?>"
                   style="width:100%;padding:10px;border-radius:12px;border:1px solid #ddd;">
        </div>

        <div class="form-group" style="margin-top:15px;">
            <label>Datos de contacto</label>
            <input type="text" name="datos_contacto"
                   value="<?= htmlspecialchars($prop_editar['datos_contacto'] ?? ''); ?>"
                   style="width:100%;padding:10px;border-radius:12px;border:1px solid #ddd;">
        </div>

        <div class="form-group" style="margin-top:15px;">
            <label>Foto principal</label>
            <input type="file" name="foto_principal" accept="image/*"
                   style="width:100%;padding:10px;border-radius:12px;border:1px solid #ddd;background:#f8fafc;">
        </div>

        <div class="form-group" style="margin-top:15px;">
            <label>Galería slider (máx. 8 fotos)</label>
            <input type="file" name="galeria[]" accept="image/*" multiple
                   style="width:100%;padding:10px;border-radius:12px;border:1px solid #ddd;background:#f8fafc;">
        </div>

        <div class="form-group" style="margin-top:15px;">
            <label>Galería especial (máx. 4 fotos)</label>
            <input type="file" name="galeria4[]" accept="image/*" multiple
                   style="width:100%;padding:10px;border-radius:12px;border:1px solid #ddd;background:#f8fafc;">
        </div>

        <div class="form-group" style="margin-top:15px;display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:15px;">
            <div>
                <label>Estado base</label>
                <?php $estado_base = $prop_editar['estado_base'] ?? 'disponible'; ?>
                <select name="estado_base" style="width:100%;padding:10px;border-radius:12px;border:1px solid #ddd;">
                    <option value="disponible" <?= $estado_base === 'disponible' ? 'selected' : ''; ?>>Disponible</option>
                    <option value="no_disponible" <?= $estado_base === 'no_disponible' ? 'selected' : ''; ?>>No disponible</option>
                </select>
            </div>

            <div>
                <label>Categoría</label>
                <?php $cat = $prop_editar['categoria'] ?? 'renta'; ?>
                <select name="categoria" style="width:100%;padding:10px;border-radius:12px;border:1px solid #ddd;">
                    <option value="renta" <?= $cat === 'renta' ? 'selected' : ''; ?>>Renta</option>
                    <option value="venta" <?= $cat === 'venta' ? 'selected' : ''; ?>>Venta</option>
                    <option value="villa" <?= $cat === 'villa' ? 'selected' : ''; ?>>Villa</option>
                </select>
            </div>
        </div>

        <button type="submit" class="btn btn-primary" style="margin-top:20px;">
            <?= !empty($prop_editar) ? 'Guardar cambios' : 'Crear propiedad'; ?>
        </button>
    </form>
</div>

<?php if (!empty($prop_editar) && (!empty($fotos_slider) || !empty($fotos_galeria))): ?>
<div class="content-section">
    <h2>Galería actual</h2>

    <form method="post" onsubmit="return confirm('¿Eliminar las fotos seleccionadas?');">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']); ?>">
        <input type="hidden" name="action" value="delete_multiple_photos">

        <?php if (!empty($fotos_slider)): ?>
            <h3 style="margin-bottom:15px;">Fotos slider</h3>
            <div class="stats-container" style="grid-template-columns:repeat(auto-fill,minmax(180px,1fr)); margin-bottom:20px;">
                <?php foreach ($fotos_slider as $foto): ?>
                    <div class="stat-item" style="position:relative;">
                        <input type="checkbox"
                               name="delete_ids[]"
                               value="<?= $foto['id']; ?>"
                               style="position:absolute;top:10px;left:10px;transform:scale(1.4);cursor:pointer;">
                        <img src="../<?= htmlspecialchars($foto['archivo']); ?>" alt=""
                             style="width:100%;height:140px;object-fit:cover;border-radius:12px;margin-bottom:10px;">
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($fotos_galeria)): ?>
            <h3 style="margin-bottom:15px;">Galería especial</h3>
            <div class="stats-container" style="grid-template-columns:repeat(auto-fill,minmax(180px,1fr)); margin-bottom:20px;">
                <?php foreach ($fotos_galeria as $foto): ?>
                    <div class="stat-item" style="position:relative;">
                        <input type="checkbox"
                               name="delete_ids[]"
                               value="<?= $foto['id']; ?>"
                               style="position:absolute;top:10px;left:10px;transform:scale(1.4);cursor:pointer;">
                        <img src="../<?= htmlspecialchars($foto['archivo']); ?>" alt=""
                             style="width:100%;height:140px;object-fit:cover;border-radius:12px;margin-bottom:10px;">
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <button type="submit" class="btn btn-secondary danger" style="padding:10px 18px;border-radius:10px;">
            Borrar fotos seleccionadas
        </button>
    </form>
</div>
<?php endif; ?>

<div class="content-section">
    <h2>Listado de propiedades</h2>
    <div class="articles-container">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Capacidad</th>
                    <th>Categoría</th>
                    <th>Estado base</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($propiedades): ?>
                    <?php foreach ($propiedades as $p): ?>
                        <tr>
                            <td><?= $p['id']; ?></td>
                            <td><?= htmlspecialchars($p['nombre']); ?></td>
                            <td><?= (int)$p['capacidad']; ?></td>
                            <td><?= htmlspecialchars($p['categoria']); ?></td>
                            <td><?= htmlspecialchars($p['estado_base']); ?></td>
                            <td>
                                <a href="panel.php?view=casas&edit=<?= $p['id']; ?>" class="small-link">Editar</a>
                                ·
                                <a href="../propiedad.php?id=<?= $p['id']; ?>" target="_blank" class="small-link">Ver página</a>
                                <form method="post" class="inline-form" style="display:inline;" onsubmit="return confirm('¿Eliminar esta casa?');">
                                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']); ?>">
                                    <input type="hidden" name="action" value="delete_property">
                                    <input type="hidden" name="id" value="<?= $p['id']; ?>">
                                    <button type="submit" class="small-link danger" style="border:none;background:none;">
                                        Borrar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">Aún no hay propiedades.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>