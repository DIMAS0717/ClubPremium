<?php

require_once __DIR__ . '/../core/ImageUploader.php';

class PropertyController
{
    public static function handle(PDO $conn, string $action): void
    {
        if (!defined('ACCESS')) {
            die('Acceso directo no permitido');
        }

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['admin_id'])) {
            die('Acceso no autorizado');
        }

        if (
            !isset($_POST['csrf_token'], $_SESSION['csrf_token']) ||
            !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
        ) {
            die('Token CSRF inválido');
        }

        $baseDir = __DIR__ . '/../../uploads/propiedades';

        if (!is_dir($baseDir)) {
            mkdir($baseDir, 0755, true);
        }

        switch ($action) {
            case 'save_property':
                self::saveProperty($conn, $baseDir);
                break;

            case 'delete_property':
                self::deleteProperty($conn);
                break;

            case 'delete_photo':
                self::deletePhoto($conn);
                break;

            case 'delete_multiple_photos':
                self::deleteMultiplePhotos($conn);
                break;
        }
    }

    private static function saveProperty(PDO $conn, string $baseDir): void
    {
        $id_prop = (int)($_POST['id_prop'] ?? 0);

        $nombreProp      = trim($_POST['nombre'] ?? '');
        $capacidad       = (int)($_POST['capacidad'] ?? 0);
        $recamaras       = (int)($_POST['recamaras'] ?? 0);
        $banos           = (int)($_POST['banos'] ?? 0);
        $estacionamiento = trim($_POST['estacionamiento'] ?? '');
        $desc_corta      = trim($_POST['descripcion_corta'] ?? '');
        $desc_larga      = trim($_POST['descripcion_larga'] ?? '');
        $ubicacion       = trim($_POST['ubicacion'] ?? '');
        $distancia       = trim($_POST['distancia_mar'] ?? '');
        $servicios       = trim($_POST['servicios'] ?? '');
        $indicaciones    = trim($_POST['indicaciones'] ?? '');
        $enlace_drive    = trim($_POST['enlace_drive'] ?? '');
        $datos_contacto  = trim($_POST['datos_contacto'] ?? '');
        $estado_base     = trim($_POST['estado_base'] ?? 'disponible');
        $categoria       = trim($_POST['categoria'] ?? 'renta');

        if ($nombreProp === '' || $capacidad <= 0) {
            $_SESSION['flash_error'] = 'Completa correctamente los campos obligatorios.';
            header('Location: panel.php?view=casas');
            exit;
        }

        $foto_principal = $_POST['foto_principal_actual'] ?? '';

        try {
            if (!empty($_FILES['foto_principal']['name'])) {
                $nombreArchivo = ImageUploader::upload(
                    $_FILES['foto_principal'],
                    $baseDir,
                    'MAIN_'
                );

                if ($nombreArchivo) {
                    if (!empty($foto_principal)) {
                        $rutaAnterior = __DIR__ . '/../../' . $foto_principal;
                        if (is_file($rutaAnterior)) {
                            @unlink($rutaAnterior);
                        }
                    }

                    $foto_principal = 'uploads/propiedades/' . $nombreArchivo;
                }
            }

            if ($id_prop > 0) {
                $sqlUpdate = "UPDATE properties
                    SET nombre=?, capacidad=?, recamaras=?, banos=?, estacionamiento=?,
                        descripcion_corta=?, descripcion_larga=?, ubicacion=?, distancia_mar=?,
                        servicios=?, indicaciones=?, enlace_drive=?, datos_contacto=?,
                        foto_principal=?, estado_base=?, categoria=?
                    WHERE id=?";

                $stmt = $conn->prepare($sqlUpdate);
                $stmt->execute([
                    $nombreProp, $capacidad, $recamaras, $banos, $estacionamiento,
                    $desc_corta, $desc_larga, $ubicacion, $distancia,
                    $servicios, $indicaciones, $enlace_drive, $datos_contacto,
                    $foto_principal, $estado_base, $categoria,
                    $id_prop
                ]);

                if (!empty($_FILES['galeria']['name'][0])) {
                    self::subirGaleria($conn, $id_prop, $_FILES['galeria'], 'slider', 8, $baseDir, 'SL_');
                }

                if (!empty($_FILES['galeria4']['name'][0])) {
                    self::subirGaleria($conn, $id_prop, $_FILES['galeria4'], 'galeria', 4, $baseDir, 'G4_');
                }

                $_SESSION['flash_success'] = 'Propiedad actualizada correctamente.';
                header('Location: panel.php?view=casas');
                exit;
            }

            $sqlInsert = "INSERT INTO properties
                (nombre, capacidad, recamaras, banos, estacionamiento,
                 descripcion_corta, descripcion_larga, ubicacion, distancia_mar,
                 servicios, indicaciones, enlace_drive, datos_contacto,
                 foto_principal, estado_base, categoria)
                VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

            $stmt = $conn->prepare($sqlInsert);
            $stmt->execute([
                $nombreProp, $capacidad, $recamaras, $banos, $estacionamiento,
                $desc_corta, $desc_larga, $ubicacion, $distancia,
                $servicios, $indicaciones, $enlace_drive, $datos_contacto,
                $foto_principal, $estado_base, $categoria
            ]);

            $id_prop = (int)$conn->lastInsertId();

            if (!empty($_FILES['galeria']['name'][0])) {
                self::subirGaleria($conn, $id_prop, $_FILES['galeria'], 'slider', 8, $baseDir, 'SL_');
            }

            if (!empty($_FILES['galeria4']['name'][0])) {
                self::subirGaleria($conn, $id_prop, $_FILES['galeria4'], 'galeria', 4, $baseDir, 'G4_');
            }

            $_SESSION['flash_success'] = 'Propiedad creada correctamente.';
            header('Location: panel.php?view=casas');
            exit;

        } catch (Exception $e) {
            $_SESSION['flash_error'] = $e->getMessage();
            header('Location: panel.php?view=casas');
            exit;
        }
    }

    private static function deleteProperty(PDO $conn): void
    {
        $id_prop_del = (int)($_POST['id'] ?? 0);

        if ($id_prop_del <= 0) {
            $_SESSION['flash_error'] = 'ID de propiedad inválido.';
            header('Location: panel.php?view=casas');
            exit;
        }

        try {
            $conn->beginTransaction();

            $stmt = $conn->prepare("SELECT foto_principal FROM properties WHERE id = ?");
            $stmt->execute([$id_prop_del]);
            $property = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!empty($property['foto_principal'])) {
                $path = __DIR__ . '/../../' . $property['foto_principal'];
                if (is_file($path)) {
                    @unlink($path);
                }
            }

            $stmt = $conn->prepare("SELECT archivo FROM property_photos WHERE property_id = ?");
            $stmt->execute([$id_prop_del]);

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                if (!empty($row['archivo'])) {
                    $path = __DIR__ . '/../../' . $row['archivo'];
                    if (is_file($path)) {
                        @unlink($path);
                    }
                }
            }

            $stmt = $conn->prepare("DELETE FROM property_photos WHERE property_id = ?");
            $stmt->execute([$id_prop_del]);

            $stmt = $conn->prepare("DELETE FROM property_calendar WHERE property_id = ?");
            $stmt->execute([$id_prop_del]);

            $stmt = $conn->prepare("DELETE FROM properties WHERE id = ?");
            $stmt->execute([$id_prop_del]);

            $conn->commit();

            $_SESSION['flash_success'] = 'Propiedad eliminada completamente.';
        } catch (Exception $e) {
            $conn->rollBack();
            $_SESSION['flash_error'] = 'Error al eliminar propiedad.';
        }

        header('Location: panel.php?view=casas');
        exit;
    }

    private static function deletePhoto(PDO $conn): void
    {
        $photoId = (int)($_POST['id'] ?? 0);

        if ($photoId <= 0) {
            $_SESSION['flash_error'] = 'Foto inválida.';
            header('Location: panel.php?view=casas');
            exit;
        }

        try {
            $stmt = $conn->prepare("SELECT archivo FROM property_photos WHERE id = ?");
            $stmt->execute([$photoId]);
            $photo = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($photo && !empty($photo['archivo'])) {
                $path = __DIR__ . '/../../' . $photo['archivo'];
                if (is_file($path)) {
                    @unlink($path);
                }
            }

            $stmt = $conn->prepare("DELETE FROM property_photos WHERE id = ?");
            $stmt->execute([$photoId]);

            $_SESSION['flash_success'] = 'Foto eliminada correctamente.';
        } catch (Exception $e) {
            $_SESSION['flash_error'] = 'Error al eliminar foto.';
        }

        header('Location: panel.php?view=casas');
        exit;
    }

    private static function deleteMultiplePhotos(PDO $conn): void
    {
        $deleteIds = $_POST['delete_ids'] ?? [];

        if (!is_array($deleteIds) || empty($deleteIds)) {
            $_SESSION['flash_error'] = 'No seleccionaste fotos.';
            header('Location: panel.php?view=casas');
            exit;
        }

        try {
            $conn->beginTransaction();

            foreach ($deleteIds as $photoId) {
                $photoId = (int)$photoId;

                if ($photoId <= 0) {
                    continue;
                }

                $stmt = $conn->prepare("SELECT archivo FROM property_photos WHERE id = ?");
                $stmt->execute([$photoId]);
                $photo = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($photo && !empty($photo['archivo'])) {
                    $path = __DIR__ . '/../../' . $photo['archivo'];
                    if (is_file($path)) {
                        @unlink($path);
                    }
                }

                $stmt = $conn->prepare("DELETE FROM property_photos WHERE id = ?");
                $stmt->execute([$photoId]);
            }

            $conn->commit();
            $_SESSION['flash_success'] = 'Fotos eliminadas correctamente.';
        } catch (Exception $e) {
            $conn->rollBack();
            $_SESSION['flash_error'] = 'Error al eliminar fotos.';
        }

        header('Location: panel.php?view=casas');
        exit;
    }

    private static function subirGaleria(
        PDO $conn,
        int $propertyId,
        array $files,
        string $tipo,
        int $maxFiles,
        string $baseDir,
        string $prefix
    ): void {
        $total = count($files['name']);

        for ($i = 0; $i < $total && $i < $maxFiles; $i++) {
            if (($files['error'][$i] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
                continue;
            }

            $file = [
                'name'     => $files['name'][$i],
                'tmp_name' => $files['tmp_name'][$i],
                'error'    => $files['error'][$i],
                'size'     => $files['size'][$i],
            ];

            $nombreArchivo = ImageUploader::upload(
                $file,
                $baseDir,
                $prefix . $i . '_'
            );

            if ($nombreArchivo) {
                $archivo = 'uploads/propiedades/' . $nombreArchivo;

                $stmt = $conn->prepare("
                    INSERT INTO property_photos (property_id, archivo, tipo)
                    VALUES (?, ?, ?)
                ");
                $stmt->execute([$propertyId, $archivo, $tipo]);
            }
        }
    }
}