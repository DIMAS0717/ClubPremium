<?php

function e($value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function split_items($text): array
{
    $text = trim((string)$text);

    if ($text === '') {
        return [];
    }

    $text = str_replace(["\r\n", "\r"], "\n", $text);
    $parts = preg_split('/[\n,]+/', $text);

    $items = [];

    foreach ($parts as $part) {
        $part = trim($part);
        $part = ltrim($part, "-• \t");

        if ($part !== '') {
            $items[] = $part;
        }
    }

    return $items;
}

function obtenerPropiedad(PDO $db, int $id): ?array
{
    $sql = "SELECT * FROM properties WHERE id = :id LIMIT 1";
    $stmt = $db->prepare($sql);
    $stmt->execute([':id' => $id]);

    $prop = $stmt->fetch(PDO::FETCH_ASSOC);

    return $prop ?: null;
}

function obtenerEstadoCasa(PDO $db, int $propertyId, ?string $estadoBase): string
{
    $hoy = date('Y-m-d');

    $sql = "
        SELECT estado
        FROM property_calendar
        WHERE property_id = :property_id
          AND :hoy BETWEEN fecha_inicio AND fecha_fin
        ORDER BY fecha_fin DESC
        LIMIT 1
    ";

    $stmt = $db->prepare($sql);
    $stmt->execute([
        ':property_id' => $propertyId,
        ':hoy' => $hoy
    ]);

    $estado = $stmt->fetchColumn();

    if ($estado === 'no_disponible') {
        return 'no_disponible';
    }

    if ($estadoBase === 'no_disponible') {
        return 'no_disponible';
    }

    return 'disponible';
}

function obtenerFotosPorTipo(PDO $db, int $propertyId, string $tipo): array
{
    $sql = "
        SELECT archivo, titulo
        FROM property_photos
        WHERE property_id = :property_id
          AND tipo = :tipo
        ORDER BY orden ASC, id ASC
    ";

    $stmt = $db->prepare($sql);
    $stmt->execute([
        ':property_id' => $propertyId,
        ':tipo' => $tipo
    ]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function obtenerTodasLasFotos(PDO $db, int $propertyId): array
{
    $sql = "
        SELECT archivo, titulo, tipo
        FROM property_photos
        WHERE property_id = :property_id
        ORDER BY orden ASC, id ASC
    ";

    $stmt = $db->prepare($sql);
    $stmt->execute([
        ':property_id' => $propertyId
    ]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}