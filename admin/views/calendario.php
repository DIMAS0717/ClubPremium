<?php
// Cargar lista de propiedades
$sql_prop = "SELECT id, nombre FROM properties ORDER BY nombre ASC";
$stmt_prop = $conn->prepare($sql_prop);
$stmt_prop->execute();
$lista_propiedades = $stmt_prop->fetchAll(PDO::FETCH_ASSOC);

// Cargar rangos registrados
$sql = "SELECT c.id, p.nombre, c.fecha_inicio, c.fecha_fin, c.estado
        FROM property_calendar c
        JOIN properties p ON p.id = c.property_id
        ORDER BY c.fecha_inicio DESC";

$stmt = $conn->prepare($sql);
$stmt->execute();
$registros = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="content-section">
    <h2>Gestionar calendario de ocupación</h2>

    <form method="post" class="biografia-form">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']); ?>">
        <input type="hidden" name="action" value="save_calendar">

        <div class="form-group">
            <label>Casa</label>
            <select
                name="property_id"
                required
                style="width:100%;padding:10px;border-radius:12px;border:1px solid #ddd;"
            >
                <option value="">Selecciona una casa</option>

                <?php foreach ($lista_propiedades as $p): ?>
                    <option value="<?= (int)$p['id']; ?>">
                        <?= htmlspecialchars($p['nombre']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group" style="margin-top:15px;display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:15px;">
            <div>
                <label>Fecha inicio</label>
                <input
                    type="date"
                    name="fecha_inicio"
                    required
                    style="width:100%;padding:10px;border-radius:12px;border:1px solid #ddd;"
                >
            </div>

            <div>
                <label>Fecha fin</label>
                <input
                    type="date"
                    name="fecha_fin"
                    required
                    style="width:100%;padding:10px;border-radius:12px;border:1px solid #ddd;"
                >
            </div>
        </div>

        <div class="form-group" style="margin-top:15px;">
            <label>Estado de la casa en ese periodo</label>
            <select
                name="estado"
                style="width:100%;padding:10px;border-radius:12px;border:1px solid #ddd;"
            >
                <option value="no_disponible">No disponible</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary" style="margin-top:20px;">
            Guardar rango
        </button>
    </form>
</div>

<div class="content-section">
    <h2>Rangos registrados</h2>

    <div class="articles-container">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Casa</th>
                    <th>Del</th>
                    <th>Al</th>
                    <th>Estado</th>
                    <th>Acción</th>
                </tr>
            </thead>

            <tbody>
                <?php if (!empty($registros)): ?>
                    <?php foreach ($registros as $r): ?>
                        <tr>
                            <td><?= htmlspecialchars($r['nombre']); ?></td>
                            <td><?= htmlspecialchars($r['fecha_inicio']); ?></td>
                            <td><?= htmlspecialchars($r['fecha_fin']); ?></td>
                            <td><?= htmlspecialchars($r['estado']); ?></td>
                            <td>
                                <form
                                    method="post"
                                    class="inline-form"
                                    style="display:inline;"
                                    onsubmit="return confirm('¿Eliminar este rango?');"
                                >
                                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']); ?>">
                                    <input type="hidden" name="action" value="delete_calendar">
                                    <input type="hidden" name="id" value="<?= (int)$r['id']; ?>">

                                    <button
                                        type="submit"
                                        class="small-link danger"
                                        style="border:none;background:none;"
                                    >
                                        Borrar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">No hay rangos registrados.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<footer style="margin-top:30px;text-align:center;color:#e5e7eb;font-size:0.85rem;">
    &copy; <?= date('Y'); ?> Club Santiago. Panel de administración.
</footer>