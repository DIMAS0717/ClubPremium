<?php
require_once __DIR__ . '/admin/core/Database.php';
require_once __DIR__ . '/includes/property_helpers.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

date_default_timezone_set('America/Mexico_City');

try {
    $db = Database::getConnection();

    function assetImg(?string $img): string
    {
        $base = '/clubpremium/';
        $img = trim((string)$img);

        if ($img === '') {
            return $base . 'assets/img/no-image.jpg';
        }

        if (strpos($img, 'http://') === 0 || strpos($img, 'https://') === 0) {
            return $img;
        }

        if (strpos($img, '/clubpremium/') === 0) {
            return $img;
        }

        if (strpos($img, 'assets/') === 0) {
            return $base . ltrim($img, '/');
        }

        if (strpos($img, 'uploads/') === 0) {
            return $base . ltrim($img, '/');
        }

        return $base . 'assets/img/' . ltrim($img, '/');
    }

    function buildPaginationUrl(int $numPagina): string
    {
        $query = $_GET;
        $query['pagina'] = $numPagina;
        return '?' . http_build_query($query);
    }

    /* =========================================================
       FILTROS
    ========================================================= */
    $entrada     = $_GET['entrada'] ?? '';
    $salida      = $_GET['salida'] ?? '';
    $personas    = $_GET['personas'] ?? '';
    $tipo_playa  = $_GET['tipo_playa'] ?? [];
    $estado      = $_GET['estado'] ?? '';

    if (!is_array($tipo_playa)) {
        $tipo_playa = [];
    }

    $pieSeleccionado   = in_array('pie', $tipo_playa, true);
    $cercaSeleccionado = in_array('cerca', $tipo_playa, true);

    $hoy = date('Y-m-d');
    $hayFechas = false;
    $errorFechas = '';
    $textoBusqueda = '';
    $entradaBusqueda = '';
    $salidaBusqueda = '';

    if ($entrada !== '' && $salida !== '') {
        if ($entrada < $hoy) {
            $errorFechas = 'La fecha de entrada no puede ser menor a hoy.';
        } elseif ($salida < $entrada) {
            $errorFechas = 'La fecha de salida no puede ser menor que la fecha de entrada.';
        } else {
            $hayFechas = true;
            $entradaBusqueda = $entrada;
            $salidaBusqueda = $salida;
            $textoBusqueda = 'Disponibilidad del: ' . date('d/m', strtotime($entradaBusqueda)) . ' al ' . date('d/m', strtotime($salidaBusqueda));
        }
    } elseif ($entrada !== '' && $salida === '') {
        if ($entrada < $hoy) {
            $errorFechas = 'La fecha de entrada no puede ser menor a hoy.';
        } else {
            $hayFechas = true;
            $entradaBusqueda = $entrada;
            $salidaBusqueda = $entrada; // un solo día
            $textoBusqueda = 'Disponibilidad para el día: ' . date('d/m', strtotime($entradaBusqueda));
        }
    } elseif ($entrada === '' && $salida !== '') {
        $errorFechas = 'Debes seleccionar una fecha de entrada.';
    }

    /* =========================================================
       PAGINACIÓN
    ========================================================= */
    $porPagina = 10;
    $pagina = isset($_GET['pagina']) ? max(1, (int)$_GET['pagina']) : 1;
    $offset = ($pagina - 1) * $porPagina;

    /* =========================================================
       ESTADO ACTUAL / ESTADO SEGÚN BÚSQUEDA
    ========================================================= */
    if ($hayFechas) {
        $estadoActualSQL = "
            CASE
                WHEN EXISTS (
                    SELECT 1
                    FROM property_calendar pc
                    WHERE pc.property_id = p.id
                      AND pc.estado = 'no_disponible'
                      AND pc.fecha_inicio <= :estado_salida
                      AND pc.fecha_fin >= :estado_entrada
                ) THEN 'no_disponible'

                WHEN p.estado_base = 'no_disponible' THEN 'no_disponible'
                ELSE 'disponible'
            END AS estado_actual
        ";
    } else {
        $estadoActualSQL = "
            CASE
                WHEN EXISTS (
                    SELECT 1
                    FROM property_calendar pc
                    WHERE pc.property_id = p.id
                      AND pc.estado = 'no_disponible'
                      AND CURDATE() BETWEEN pc.fecha_inicio AND pc.fecha_fin
                ) THEN 'no_disponible'

                WHEN p.estado_base = 'no_disponible' THEN 'no_disponible'
                ELSE 'disponible'
            END AS estado_actual
        ";
    }

    /* =========================================================
       BASE SQL
    ========================================================= */
    $selectBase = "
        SELECT 
            p.id,
            p.nombre,
            p.capacidad,
            p.distancia_mar,
            p.descripcion_corta,
            p.foto_principal,
            p.categoria,
            p.estado_base,
            p.es_pie_playa,
            p.created_at,
            $estadoActualSQL
        FROM properties p
        WHERE p.categoria = 'renta'
    ";

    $countBase = "
        SELECT COUNT(*)
        FROM properties p
        WHERE p.categoria = 'renta'
    ";

    $where = [];
    $params = [];

    if ($hayFechas) {
        $params[':estado_entrada'] = $entradaBusqueda;
        $params[':estado_salida']  = $salidaBusqueda;
    }

    /* =========================================================
       FILTRO TIPO PLAYA
    ========================================================= */
    if ($pieSeleccionado && !$cercaSeleccionado) {
        $where[] = "p.es_pie_playa = 1";
    }

    if ($cercaSeleccionado && !$pieSeleccionado) {
        $where[] = "p.es_pie_playa = 0";
    }

    /* =========================================================
       FILTRO PERSONAS
    ========================================================= */
    if ($personas !== '') {
        $personasInt = (int)$personas;

        if ($personasInt > 0) {
            $where[] = "p.capacidad >= :personas";
            $params[':personas'] = $personasInt;
        }
    }

    /* =========================================================
       FILTRO ESTADO
    ========================================================= */
    if ($estado !== '') {
        if ($hayFechas) {
            if ($estado === 'disponible') {
                $where[] = "NOT EXISTS (
                    SELECT 1
                    FROM property_calendar pc
                    WHERE pc.property_id = p.id
                      AND pc.estado = 'no_disponible'
                      AND pc.fecha_inicio <= :filtro_salida
                      AND pc.fecha_fin >= :filtro_entrada
                )";
                $where[] = "(p.estado_base IS NULL OR p.estado_base <> 'no_disponible')";
                $params[':filtro_entrada'] = $entradaBusqueda;
                $params[':filtro_salida']  = $salidaBusqueda;
            }

            if ($estado === 'no_disponible') {
                $where[] = "(
                    EXISTS (
                        SELECT 1
                        FROM property_calendar pc
                        WHERE pc.property_id = p.id
                          AND pc.estado = 'no_disponible'
                          AND pc.fecha_inicio <= :filtro_salida
                          AND pc.fecha_fin >= :filtro_entrada
                    )
                    OR p.estado_base = 'no_disponible'
                )";
                $params[':filtro_entrada'] = $entradaBusqueda;
                $params[':filtro_salida']  = $salidaBusqueda;
            }
        } else {
            if ($estado === 'disponible') {
                $where[] = "NOT EXISTS (
                    SELECT 1
                    FROM property_calendar pc
                    WHERE pc.property_id = p.id
                      AND pc.estado = 'no_disponible'
                      AND CURDATE() BETWEEN pc.fecha_inicio AND pc.fecha_fin
                )";
                $where[] = "(p.estado_base IS NULL OR p.estado_base <> 'no_disponible')";
            }

            if ($estado === 'no_disponible') {
                $where[] = "(
                    EXISTS (
                        SELECT 1
                        FROM property_calendar pc
                        WHERE pc.property_id = p.id
                          AND pc.estado = 'no_disponible'
                          AND CURDATE() BETWEEN pc.fecha_inicio AND pc.fecha_fin
                    )
                    OR p.estado_base = 'no_disponible'
                )";
            }
        }
    }

    /* =========================================================
       ARMADO FINAL SQL
    ========================================================= */
    if (!empty($where)) {
        $selectBase .= " AND " . implode(" AND ", $where);
        $countBase  .= " AND " . implode(" AND ", $where);
    }

    if ($personas !== '') {
        $selectBase .= " ORDER BY p.capacidad ASC, p.created_at DESC ";
    } else {
        $selectBase .= " ORDER BY p.created_at DESC ";
    }

    $selectBase .= " LIMIT :limit OFFSET :offset";

    /* =========================================================
       COUNT
    ========================================================= */
    $stmtCount = $db->prepare($countBase);

    foreach ($params as $key => $value) {
        if (
            $key !== ':estado_entrada' &&
            $key !== ':estado_salida'
        ) {
            $stmtCount->bindValue($key, $value);
        }
    }

    $stmtCount->execute();
    $totalRegistros = (int)$stmtCount->fetchColumn();
    $totalPaginas = max(1, (int)ceil($totalRegistros / $porPagina));

    if ($pagina > $totalPaginas) {
        $pagina = $totalPaginas;
        $offset = ($pagina - 1) * $porPagina;
    }

    /* =========================================================
       SELECT
    ========================================================= */
    $stmt = $db->prepare($selectBase);

    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }

    $stmt->bindValue(':limit', $porPagina, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

    $stmt->execute();
    $propiedades = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (Throwable $e) {
    echo '<pre style="background:#300;color:#fff;padding:12px;border-radius:8px;">';
    echo 'ERROR EN RENTA.PHP' . "\n\n";
    echo $e->getMessage();
    echo '</pre>';
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Propiedades en renta - Casas Club Santiago</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="assets/css/renta.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>

<?php include __DIR__ . '/includes/header.php'; ?>

<main class="page-main page-centered">

    <section class="section">
        <h1>Propiedades en renta</h1>
        <p class="muted-text">Casas disponibles para renta en Club Santiago.</p>
    </section>

    <form class="booking-bar" method="GET" id="formBusqueda">

        <div class="booking-field">
            <span class="booking-label">Registro de entrada</span>
            <div class="booking-input">
                <input type="date" id="entrada" name="entrada" value="<?= e($entrada) ?>">
            </div>
        </div>

        <div class="booking-field">
            <span class="booking-label">Registro de salida</span>
            <div class="booking-input">
                <input type="date" id="salida" name="salida" value="<?= e($_GET['salida'] ?? '') ?>">
            </div>
        </div>

        <div class="booking-field">
            <span class="booking-label">Huéspedes</span>
            <div class="booking-input">
                <select name="personas">
                    <option value="">Seleccionar</option>
                    <option value="2"  <?= $personas === '2' ? 'selected' : '' ?>>2 personas</option>
                    <option value="4"  <?= $personas === '4' ? 'selected' : '' ?>>4 personas</option>
                    <option value="8"  <?= $personas === '8' ? 'selected' : '' ?>>8 personas</option>
                    <option value="10" <?= $personas === '10' ? 'selected' : '' ?>>10 personas</option>
                    <option value="11" <?= $personas === '11' ? 'selected' : '' ?>>11 personas</option>
                    <option value="12" <?= $personas === '12' ? 'selected' : '' ?>>12 personas</option>
                    <option value="14" <?= $personas === '14' ? 'selected' : '' ?>>14 personas</option>
                    <option value="16" <?= $personas === '16' ? 'selected' : '' ?>>16 personas</option>
                    <option value="20" <?= $personas === '20' ? 'selected' : '' ?>>20+ personas</option>
                </select>
            </div>
        </div>

        <div class="booking-field booking-dropdown">
            <span class="booking-label">Tipo</span>

            <div class="booking-input booking-dropdown-toggle" id="tipoToggle" role="button" tabindex="0">
                <span id="tipoTexto">Seleccionar</span>
                <svg width="16" height="16" viewBox="0 0 20 20" aria-hidden="true">
                    <path d="M5 7l5 5 5-5" fill="none" stroke="currentColor" stroke-width="2"/>
                </svg>
            </div>

            <div class="booking-dropdown-menu" id="tipoMenu">

                <div class="booking-dropdown-group">
                    <div class="booking-dropdown-title">Ubicación</div>

                    <label class="booking-option">
                        <input type="checkbox" name="tipo_playa[]" value="pie" <?= $pieSeleccionado ? 'checked' : '' ?>>
                        <span>Casa a pie de playa</span>
                    </label>

                    <label class="booking-option">
                        <input type="checkbox" name="tipo_playa[]" value="cerca" <?= $cercaSeleccionado ? 'checked' : '' ?>>
                        <span>Cerca de la playa</span>
                    </label>
                </div>

                <div class="booking-dropdown-divider"></div>

                <div class="booking-dropdown-group">
                    <div class="booking-dropdown-title">Estado</div>

                    <label class="booking-option">
                        <input type="radio" name="estado" value="disponible" <?= $estado === 'disponible' ? 'checked' : '' ?>>
                        <span>Casas disponibles</span>
                    </label>

                    <label class="booking-option">
                        <input type="radio" name="estado" value="no_disponible" <?= $estado === 'no_disponible' ? 'checked' : '' ?>>
                        <span>Casas no disponibles</span>
                    </label>
                </div>

                <div class="booking-dropdown-actions">
                    <button type="button" class="booking-clear-mini" id="tipoClearBtn">Limpiar tipo</button>
                    <button type="button" class="booking-apply-mini" id="tipoCloseBtn">Listo</button>
                </div>
            </div>
        </div>

        <div class="booking-actions">
            <button type="submit" class="booking-btn">Buscar</button>
            <a href="renta.php" class="booking-reset-btn">Borrar</a>
        </div>
    </form>

    <?php if ($errorFechas !== ''): ?>
        <div class="search-feedback search-feedback-error">
            <?= e($errorFechas) ?>
        </div>
    <?php elseif ($textoBusqueda !== ''): ?>
        <div class="search-feedback search-feedback-success">
            <?= e($textoBusqueda) ?>
        </div>
    <?php endif; ?>

    <section class="section">
        <?php if (!empty($propiedades)): ?>
            <div class="home-property-grid">
                <?php foreach ($propiedades as $p): ?>
                    <?php
                    $estadoFinal = ($p['estado_actual'] ?? 'disponible') === 'no_disponible'
                        ? 'no_disponible'
                        : 'disponible';

                    $estadoLabel = $estadoFinal === 'no_disponible' ? 'No disponible' : 'Disponible';
                    $estadoClass = $estadoFinal === 'no_disponible'
                        ? 'card-status-no-disponible'
                        : 'card-status-disponible';

                    $fotoPrincipal = assetImg($p['foto_principal'] ?? '');
                    ?>

                    <article class="home-property-card">
                        <div class="home-property-img-wrapper">
                            <a href="propiedad.php?id=<?= (int)$p['id'] ?>" class="property-link">

                                <img
                                    src="<?= e($fotoPrincipal) ?>"
                                    alt="<?= e($p['nombre']) ?>"
                                    class="home-property-img img-front <?= $estadoFinal === 'no_disponible' ? 'img-no-disponible' : '' ?>"
                                    loading="lazy"
                                >

                                <span class="card-status-chip <?= e($estadoClass) ?>">
                                    <?= e($estadoLabel) ?>
                                </span>

                                <?php if (!empty($p['capacidad'])): ?>
                                    <div class="capacity-circle">
                                        <svg class="capacity-circle-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M16 11c1.66 0 2.99-1.79 2.99-4S17.66 3 16 3s-3 1.79-3 4 1.34 4 3 4Zm-8 0c1.66 0 2.99-1.79 2.99-4S9.66 3 8 3 5 4.79 5 7s1.34 4 3 4Zm0 2c-2.33 0-7 1.17-7 3.5V20h14v-3.5C15 14.17 10.33 13 8 13Zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.98 1.97 3.45V20h6v-3.5c0-2.33-4.67-3.5-7-3.5Z"/>
                                        </svg>
                                        <span class="capacity-circle-number"><?= (int)$p['capacidad'] ?></span>
                                    </div>
                                <?php endif; ?>

                            </a>
                        </div>

                        <div class="home-property-body">
                            <h3 class="home-property-title"><?= e($p['nombre']) ?></h3>

                            <?php if (!empty($p['descripcion_corta'])): ?>
                                <p class="home-property-text"><?= e($p['descripcion_corta']) ?></p>
                            <?php endif; ?>

                            <a href="propiedad.php?id=<?= (int)$p['id'] ?>" class="btn-primary home-property-btn">
                                Ver detalles
                            </a>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>

            <?php if ($totalPaginas > 1): ?>
                <div class="pagination">
                    <?php if ($pagina > 1): ?>
                        <a href="<?= e(buildPaginationUrl($pagina - 1)) ?>" class="btn-primary" style="text-decoration:none;">«</a>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                        <a
                            href="<?= e(buildPaginationUrl($i)) ?>"
                            class="btn-primary"
                            style="text-decoration:none; <?= $i === $pagina ? 'opacity:1; pointer-events:none;' : 'opacity:.85;' ?>"
                        >
                            <?= $i ?>
                        </a>
                    <?php endfor; ?>

                    <?php if ($pagina < $totalPaginas): ?>
                        <a href="<?= e(buildPaginationUrl($pagina + 1)) ?>" class="btn-primary" style="text-decoration:none;">»</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

        <?php else: ?>
            <?php if ($errorFechas !== ''): ?>
                <p class="muted-text">Corrige las fechas para realizar la búsqueda.</p>
            <?php elseif ($hayFechas || $personas !== '' || !empty($tipo_playa) || $estado !== ''): ?>
                <p class="muted-text">No se encontraron propiedades con esos filtros.</p>
            <?php else: ?>
                <p class="muted-text">Aún no hay propiedades registradas en renta.</p>
            <?php endif; ?>
        <?php endif; ?>
    </section>
</main>

<?php include __DIR__ . '/includes/footer.php'; ?>

<script src="assets/js/app.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const toggle = document.getElementById('tipoToggle');
    const menu = document.getElementById('tipoMenu');
    const texto = document.getElementById('tipoTexto');
    const clearBtn = document.getElementById('tipoClearBtn');
    const closeBtn = document.getElementById('tipoCloseBtn');

    const form = document.getElementById('formBusqueda');
    const entradaInput = document.getElementById('entrada');
    const salidaInput = document.getElementById('salida');

    const hoy = new Date();
    const year = hoy.getFullYear();
    const month = String(hoy.getMonth() + 1).padStart(2, '0');
    const day = String(hoy.getDate()).padStart(2, '0');
    const fechaHoy = `${year}-${month}-${day}`;

    if (entradaInput) {
        entradaInput.min = fechaHoy;
    }

    if (salidaInput) {
        salidaInput.min = entradaInput && entradaInput.value ? entradaInput.value : fechaHoy;
    }

    if (entradaInput && salidaInput) {
        const actualizarMinSalida = () => {
            salidaInput.min = entradaInput.value || fechaHoy;

            if (salidaInput.value && entradaInput.value && salidaInput.value < entradaInput.value) {
                salidaInput.value = '';
            }
        };

        entradaInput.addEventListener('change', actualizarMinSalida);
        actualizarMinSalida();
    }

    if (form && entradaInput && salidaInput) {
        form.addEventListener('submit', (e) => {
            const entrada = entradaInput.value;
            const salida = salidaInput.value;

            if (entrada && entrada < fechaHoy) {
                e.preventDefault();
                alert('La fecha de entrada no puede ser menor a hoy.');
                return;
            }

            if (!entrada && salida) {
                e.preventDefault();
                alert('Debes seleccionar una fecha de entrada.');
                return;
            }

            if (entrada && salida && salida < entrada) {
                e.preventDefault();
                alert('La fecha de salida no puede ser menor que la fecha de entrada.');
                return;
            }
        });
    }

    if (!toggle || !menu || !texto) return;

    const inputs = Array.from(menu.querySelectorAll('input'));

    const updateTexto = () => {
        const seleccionados = inputs
            .filter(input => input.checked)
            .map(input => input.parentNode.textContent.trim());

        texto.textContent = seleccionados.length
            ? seleccionados.join(', ')
            : 'Seleccionar';
    };

    const closeMenu = () => menu.classList.remove('show');

    toggle.addEventListener('click', function (e) {
        e.preventDefault();
        e.stopPropagation();
        menu.classList.toggle('show');
    });

    toggle.addEventListener('keydown', function (e) {
        if (e.key === 'Enter' || e.key === ' ') {
            e.preventDefault();
            menu.classList.toggle('show');
        }
    });

    document.addEventListener('click', function () {
        closeMenu();
    });

    menu.addEventListener('click', function (e) {
        e.stopPropagation();
    });

    inputs.forEach(input => {
        if (input.type === 'radio') {
            input.addEventListener('click', function () {
                if (this.dataset.wasChecked === 'true') {
                    this.checked = false;
                    this.dataset.wasChecked = 'false';
                } else {
                    menu.querySelectorAll('input[type="radio"][name="' + this.name + '"]').forEach(r => {
                        r.dataset.wasChecked = 'false';
                    });
                    this.dataset.wasChecked = this.checked ? 'true' : 'false';
                }
                updateTexto();
            });
        } else {
            input.addEventListener('change', updateTexto);
        }
    });

    menu.querySelectorAll('input[type="radio"]').forEach(radio => {
        radio.dataset.wasChecked = radio.checked ? 'true' : 'false';
    });

    if (clearBtn) {
        clearBtn.addEventListener('click', function () {
            inputs.forEach(input => {
                input.checked = false;
                if (input.type === 'radio') {
                    input.dataset.wasChecked = 'false';
                }
            });
            updateTexto();
        });
    }

    if (closeBtn) {
        closeBtn.addEventListener('click', function () {
            closeMenu();
        });
    }

    updateTexto();
});
</script>

</body>
</html>