<?php

class CalendarController
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

        switch ($action) {
            case 'save_calendar':
                self::saveCalendar($conn);
                break;

            case 'delete_calendar':
                self::deleteCalendar($conn);
                break;
        }
    }

    private static function saveCalendar(PDO $conn): void
    {
        $propertyId   = (int)($_POST['property_id'] ?? 0);
        $fechaInicio  = trim($_POST['fecha_inicio'] ?? '');
        $fechaFin     = trim($_POST['fecha_fin'] ?? '');
        $estado       = trim($_POST['estado'] ?? 'no_disponible');

        if ($propertyId <= 0 || $fechaInicio === '' || $fechaFin === '') {
            $_SESSION['flash_error'] = 'Completa todos los campos del calendario.';
            header('Location: panel.php?view=calendario');
            exit;
        }

        if ($fechaInicio > $fechaFin) {
            $_SESSION['flash_error'] = 'La fecha de inicio no puede ser mayor que la fecha final.';
            header('Location: panel.php?view=calendario');
            exit;
        }

        try {
            $stmt = $conn->prepare("
                INSERT INTO property_calendar (property_id, fecha_inicio, fecha_fin, estado)
                VALUES (?, ?, ?, ?)
            ");
            $stmt->execute([$propertyId, $fechaInicio, $fechaFin, $estado]);

            $_SESSION['flash_success'] = 'Rango de calendario guardado correctamente.';
        } catch (Exception $e) {
            $_SESSION['flash_error'] = 'Error al guardar el rango del calendario.';
        }

        header('Location: panel.php?view=calendario');
        exit;
    }

    private static function deleteCalendar(PDO $conn): void
    {
        $id = (int)($_POST['id'] ?? 0);

        if ($id <= 0) {
            $_SESSION['flash_error'] = 'ID de rango inválido.';
            header('Location: panel.php?view=calendario');
            exit;
        }

        try {
            $stmt = $conn->prepare("DELETE FROM property_calendar WHERE id = ?");
            $stmt->execute([$id]);

            $_SESSION['flash_success'] = 'Rango eliminado correctamente.';
        } catch (Exception $e) {
            $_SESSION['flash_error'] = 'Error al eliminar el rango.';
        }

        header('Location: panel.php?view=calendario');
        exit;
    }
}