<?php
$hoy = date('Y-m-d');
$errorCalendario = '';

// Validación backend
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'save_calendar') {
    $fechaInicioPost = $_POST['fecha_inicio'] ?? '';
    $fechaFinPost    = $_POST['fecha_fin'] ?? '';

    if ($fechaInicioPost !== '' && $fechaInicioPost < $hoy) {
        $errorCalendario = 'La fecha de inicio no puede ser menor a hoy.';
    } elseif ($fechaFinPost !== '' && $fechaFinPost < $hoy) {
        $errorCalendario = 'La fecha fin no puede ser menor a hoy.';
    } elseif ($fechaInicioPost !== '' && $fechaFinPost !== '' && $fechaFinPost < $fechaInicioPost) {
        $errorCalendario = 'La fecha fin no puede ser menor que la fecha de inicio.';
    }
}

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

    <?php if ($errorCalendario !== ''): ?>
        <div class="alert error" style="margin-bottom:18px;">
            <?= htmlspecialchars($errorCalendario); ?>
        </div>
    <?php endif; ?>

    <form method="post" class="biografia-form" id="calendarForm">
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
                <label for="fecha_inicio">Fecha inicio</label>
                <input
                    type="date"
                    id="fecha_inicio"
                    name="fecha_inicio"
                    required
                    min="<?= htmlspecialchars($hoy); ?>"
                    value="<?= htmlspecialchars($_POST['fecha_inicio'] ?? ''); ?>"
                    style="width:100%;padding:10px;border-radius:12px;border:1px solid #ddd;"
                >
            </div>

            <div>
                <label for="fecha_fin">Fecha fin</label>
                <input
                    type="date"
                    id="fecha_fin"
                    name="fecha_fin"
                    required
                    min="<?= htmlspecialchars($hoy); ?>"
                    value="<?= htmlspecialchars($_POST['fecha_fin'] ?? ''); ?>"
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

    <div class="table-responsive rangos-wrap">
        <table class="admin-table rangos-table">
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
                                    class="inline-form delete-inline-form"
                                    onsubmit="return confirm('¿Eliminar este rango?');"
                                >
                                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']); ?>">
                                    <input type="hidden" name="action" value="delete_calendar">
                                    <input type="hidden" name="id" value="<?= (int)$r['id']; ?>">

                                    <button type="submit" class="small-link danger small-link-btn">
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

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('calendarForm');
    const fechaInicio = document.getElementById('fecha_inicio');
    const fechaFin = document.getElementById('fecha_fin');

    const hoy = new Date();
    const year = hoy.getFullYear();
    const month = String(hoy.getMonth() + 1).padStart(2, '0');
    const day = String(hoy.getDate()).padStart(2, '0');
    const fechaHoy = `${year}-${month}-${day}`;

    if (fechaInicio) {
        fechaInicio.min = fechaHoy;
    }

    if (fechaFin) {
        fechaFin.min = fechaInicio && fechaInicio.value ? fechaInicio.value : fechaHoy;
    }

    if (fechaInicio && fechaFin) {
        const actualizarFechaFin = () => {
            fechaFin.min = fechaInicio.value || fechaHoy;

            if (fechaFin.value && fechaInicio.value && fechaFin.value < fechaInicio.value) {
                fechaFin.value = '';
            }
        };

        fechaInicio.addEventListener('change', actualizarFechaFin);
        actualizarFechaFin();
    }

    if (form && fechaInicio && fechaFin) {
        form.addEventListener('submit', function (e) {
            const inicio = fechaInicio.value;
            const fin = fechaFin.value;

            if (inicio && inicio < fechaHoy) {
                e.preventDefault();
                alert('La fecha de inicio no puede ser menor a hoy.');
                fechaInicio.focus();
                return;
            }

            if (fin && fin < fechaHoy) {
                e.preventDefault();
                alert('La fecha fin no puede ser menor a hoy.');
                fechaFin.focus();
                return;
            }

            if (inicio && fin && fin < inicio) {
                e.preventDefault();
                alert('La fecha fin no puede ser menor que la fecha de inicio.');
                fechaFin.focus();
            }
        });
    }
});
</script>