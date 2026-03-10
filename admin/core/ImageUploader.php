<?php

class ImageUploader
{
    public static function upload(array $file, string $folder, string $prefix = '', int $maxSize = 5000000): ?string
    {
        if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
            return null;
        }

        if (($file['size'] ?? 0) > $maxSize) {
            throw new Exception('Imagen demasiado pesada.');
        }

        $allowed = ['image/jpeg', 'image/png', 'image/webp'];

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime  = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!in_array($mime, $allowed, true)) {
            throw new Exception('Tipo de imagen no permitido.');
        }

        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $safeName = $prefix . bin2hex(random_bytes(8)) . '.' . $ext;

        if (!is_dir($folder)) {
            mkdir($folder, 0755, true);
        }

        $destination = $folder . '/' . $safeName;

        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            throw new Exception('Error al mover imagen.');
        }

        return $safeName;
    }
}