<?php

require_once __DIR__ . '/../models/Admin.php';
require_once __DIR__ . '/../core/ImageUploader.php';

class AuthController
{
    public static function login(string $username, string $password): bool
    {
        $admin = Admin::findByUsername($username);

        if (!$admin) {
            return false;
        }

        if (!password_verify($password, $admin['password_hash'])) {
            return false;
        }

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        session_regenerate_id(true);

        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['nombre']   = $admin['nombre'];
        $_SESSION['email']    = $admin['correo'];
        $_SESSION['rol']      = 'Administrador';

        return true;
    }

    public static function logout(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();

            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        session_destroy();
    }

    public static function updateProfile(PDO $conn): void
    {
        self::startSessionIfNeeded();
        self::validateCsrf();

        $adminId = $_SESSION['admin_id'] ?? 0;

        if ($adminId <= 0) {
            die('Acceso no autorizado');
        }

        $nombre = trim($_POST['nombre'] ?? '');
        $correo = trim($_POST['correo'] ?? '');
        $pais   = trim($_POST['pais'] ?? '');
        $estado = trim($_POST['estado'] ?? '');

        if ($nombre === '' || $correo === '') {
            $_SESSION['flash_error'] = 'Nombre y correo son obligatorios.';
            header('Location: panel.php?view=perfil');
            exit;
        }

        $stmt = $conn->prepare("SELECT foto FROM admins WHERE id = ?");
        $stmt->execute([$adminId]);
        $adminActual = $stmt->fetch(PDO::FETCH_ASSOC);

        $foto = $adminActual['foto'] ?? '';

        try {
            if (!empty($_FILES['foto']['name'])) {
                $uploadDir = __DIR__ . '/../../uploads/admins';

                $nombreArchivo = ImageUploader::upload(
                    $_FILES['foto'],
                    $uploadDir,
                    'ADMIN_'
                );

                if ($nombreArchivo) {
                    if (!empty($foto)) {
                        $rutaAnterior = __DIR__ . '/../../' . $foto;
                        if (is_file($rutaAnterior)) {
                            @unlink($rutaAnterior);
                        }
                    }

                    $foto = 'uploads/admins/' . $nombreArchivo;
                }
            }
        } catch (Exception $e) {
            $_SESSION['flash_error'] = $e->getMessage();
            header('Location: panel.php?view=perfil');
            exit;
        }

        $stmt = $conn->prepare("
            UPDATE admins
            SET nombre = ?, correo = ?, pais = ?, estado = ?, foto = ?
            WHERE id = ?
        ");
        $stmt->execute([$nombre, $correo, $pais, $estado, $foto, $adminId]);

        $_SESSION['nombre'] = $nombre;
        $_SESSION['email']  = $correo;

        $_SESSION['flash_success'] = 'Perfil actualizado correctamente.';
        header('Location: panel.php?view=perfil');
        exit;
    }

    public static function updateAvatar(PDO $conn): void
    {
        self::startSessionIfNeeded();
        self::validateCsrf();

        $adminId = $_SESSION['admin_id'] ?? 0;

        if ($adminId <= 0) {
            die('Acceso no autorizado');
        }

        if (empty($_FILES['foto']['name'])) {
            $_SESSION['flash_error'] = 'Selecciona una imagen.';
            header('Location: panel.php?view=dashboard');
            exit;
        }

        $stmt = $conn->prepare("SELECT foto FROM admins WHERE id = ?");
        $stmt->execute([$adminId]);
        $adminActual = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$adminActual) {
            $_SESSION['flash_error'] = 'Administrador no encontrado.';
            header('Location: panel.php?view=dashboard');
            exit;
        }

        $fotoActual = $adminActual['foto'] ?? '';

        try {
            $uploadDir = __DIR__ . '/../../uploads/admins';

            $nombreArchivo = ImageUploader::upload(
                $_FILES['foto'],
                $uploadDir,
                'ADMIN_'
            );

            if (!$nombreArchivo) {
                $_SESSION['flash_error'] = 'No se pudo subir la imagen.';
                header('Location: panel.php?view=dashboard');
                exit;
            }

            if (!empty($fotoActual)) {
                $rutaAnterior = __DIR__ . '/../../' . $fotoActual;
                if (is_file($rutaAnterior)) {
                    @unlink($rutaAnterior);
                }
            }

            $fotoNueva = 'uploads/admins/' . $nombreArchivo;

            $stmt = $conn->prepare("UPDATE admins SET foto = ? WHERE id = ?");
            $stmt->execute([$fotoNueva, $adminId]);

            $_SESSION['flash_success'] = 'Foto actualizada correctamente.';
            header('Location: panel.php?view=dashboard');
            exit;

        } catch (Exception $e) {
            $_SESSION['flash_error'] = $e->getMessage();
            header('Location: panel.php?view=dashboard');
            exit;
        }
    }

    public static function changePassword(PDO $conn): void
    {
        self::startSessionIfNeeded();
        self::validateCsrf();

        $adminId = $_SESSION['admin_id'] ?? 0;

        if ($adminId <= 0) {
            die('Acceso no autorizado');
        }

        $actual = $_POST['password_actual'] ?? '';
        $nueva1 = $_POST['password_nueva'] ?? '';
        $nueva2 = $_POST['password_nueva2'] ?? '';

        $stmt = $conn->prepare("SELECT password_hash FROM admins WHERE id = ?");
        $stmt->execute([$adminId]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$admin || !password_verify($actual, $admin['password_hash'])) {
            $_SESSION['flash_error'] = 'La contraseña actual no es correcta.';
            header('Location: panel.php?view=password');
            exit;
        }

        if ($nueva1 !== $nueva2) {
            $_SESSION['flash_error'] = 'Las contraseñas nuevas no coinciden.';
            header('Location: panel.php?view=password');
            exit;
        }

        if (
            strlen($nueva1) < 8 ||
            !preg_match('/[a-z]/', $nueva1) ||
            !preg_match('/[A-Z]/', $nueva1) ||
            !preg_match('/\d/', $nueva1)
        ) {
            $_SESSION['flash_error'] = 'La nueva contraseña debe tener mínimo 8 caracteres, mayúscula, minúscula y número.';
            header('Location: panel.php?view=password');
            exit;
        }

        $nuevoHash = password_hash($nueva1, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("UPDATE admins SET password_hash = ? WHERE id = ?");
        $stmt->execute([$nuevoHash, $adminId]);

        $_SESSION['flash_success'] = 'Contraseña actualizada correctamente.';
        header('Location: panel.php?view=password');
        exit;
    }

    private static function startSessionIfNeeded(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    private static function validateCsrf(): void
    {
        if (
            !isset($_POST['csrf_token'], $_SESSION['csrf_token']) ||
            !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
        ) {
            die('Token CSRF inválido');
        }
    }
}