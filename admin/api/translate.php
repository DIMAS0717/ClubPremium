<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'ok' => false,
        'message' => 'Método no permitido'
    ]);
    exit;
}

$configFile = dirname(__DIR__) . '/config/deepl_config.php';

if (!file_exists($configFile)) {
    http_response_code(500);
    echo json_encode([
        'ok' => false,
        'message' => 'No existe admin/config/deepl_config.php'
    ]);
    exit;
}

$config = require $configFile;

$apiKey  = trim((string)($config['deepl_api_key'] ?? ''));
$apiBase = rtrim((string)($config['deepl_api_base'] ?? ''), '/');

if ($apiKey === '' || $apiBase === '') {
    http_response_code(500);
    echo json_encode([
        'ok' => false,
        'message' => 'Falta deepl_api_key o deepl_api_base en la configuración'
    ]);
    exit;
}

$rawInput = file_get_contents('php://input');
$input = json_decode($rawInput, true);

if (!is_array($input)) {
    http_response_code(400);
    echo json_encode([
        'ok' => false,
        'message' => 'JSON inválido'
    ]);
    exit;
}

$texts  = $input['texts'] ?? [];
$source = strtoupper(trim((string)($input['source'] ?? 'ES')));
$target = strtoupper(trim((string)($input['target'] ?? 'EN-US')));

if (!is_array($texts)) {
    http_response_code(400);
    echo json_encode([
        'ok' => false,
        'message' => 'texts debe ser un arreglo'
    ]);
    exit;
}

if ($source === '' || $target === '') {
    http_response_code(400);
    echo json_encode([
        'ok' => false,
        'message' => 'source y target son obligatorios'
    ]);
    exit;
}

/* =========================================================
   RUTAS FIJAS DEL PROYECTO
   ========================================================= */

$projectRoot = dirname(__DIR__, 2);
$storageRoot = $projectRoot . DIRECTORY_SEPARATOR . 'storage';
$cacheDir    = $storageRoot . DIRECTORY_SEPARATOR . 'translation_cache';

file_put_contents(__DIR__ . '/debug_cache_path.txt', $cacheDir);

if (!is_dir($storageRoot)) {
    http_response_code(500);
    echo json_encode([
        'ok' => false,
        'message' => 'La carpeta storage no existe'
    ]);
    exit;
}

if (!is_dir($cacheDir)) {
    http_response_code(500);
    echo json_encode([
        'ok' => false,
        'message' => 'La carpeta storage/translation_cache no existe'
    ]);
    exit;
}

if (!is_writable($cacheDir)) {
    http_response_code(500);
    echo json_encode([
        'ok' => false,
        'message' => 'La carpeta storage/translation_cache no tiene permisos de escritura'
    ]);
    exit;
}

$testFile = $cacheDir . DIRECTORY_SEPARATOR . 'write_test.txt';
$writeOk = @file_put_contents($testFile, 'test');

if ($writeOk === false) {
    http_response_code(500);
    echo json_encode([
        'ok' => false,
        'message' => 'PHP no puede escribir dentro de storage/translation_cache'
    ]);
    exit;
}

@unlink($testFile);

/* =========================================================
   FUNCIONES
   ========================================================= */

function buildCacheFilePath(string $cacheDir, string $source, string $target, string $text): string
{
    $hash = md5($source . '|' . $target . '|' . $text);
    return $cacheDir . DIRECTORY_SEPARATOR . $hash . '.txt';
}

function splitIntoBatches(array $indexedTexts, int $maxItems = 20, int $maxChars = 7000): array
{
    $batches = [];
    $currentBatch = [];
    $currentChars = 0;

    foreach ($indexedTexts as $index => $text) {
        $length = mb_strlen($text, 'UTF-8');

        if (
            !empty($currentBatch) &&
            (count($currentBatch) >= $maxItems || ($currentChars + $length) > $maxChars)
        ) {
            $batches[] = $currentBatch;
            $currentBatch = [];
            $currentChars = 0;
        }

        $currentBatch[$index] = $text;
        $currentChars += $length;
    }

    if (!empty($currentBatch)) {
        $batches[] = $currentBatch;
    }

    return $batches;
}

function translateBatchDeepL(array $texts, string $source, string $target, string $apiKey, string $apiBase): array
{
    $url = $apiBase . '/v2/translate';

    $payload = [
        'text'        => array_values($texts),
        'source_lang' => $source,
        'target_lang' => $target
    ];

    $ch = curl_init($url);

    curl_setopt_array($ch, [
        CURLOPT_POST           => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER     => [
            'Authorization: DeepL-Auth-Key ' . $apiKey,
            'Content-Type: application/json'
        ],
        CURLOPT_POSTFIELDS     => json_encode($payload, JSON_UNESCAPED_UNICODE),
        CURLOPT_TIMEOUT        => 30,
    ]);

    $response = curl_exec($ch);

    if ($response === false) {
        $error = curl_error($ch);
        curl_close($ch);
        throw new RuntimeException('Error cURL: ' . $error);
    }

    $httpCode = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $data = json_decode($response, true);

    if ($httpCode >= 400) {
        $message = $data['message'] ?? 'Error de DeepL';
        throw new RuntimeException($message);
    }

    if (!isset($data['translations']) || !is_array($data['translations'])) {
        throw new RuntimeException('Respuesta inválida de DeepL');
    }

    $translated = [];
    foreach ($data['translations'] as $item) {
        $translated[] = (string)($item['text'] ?? '');
    }

    return $translated;
}

/* =========================================================
   PROCESO
   ========================================================= */

try {
    $results = [];
    $pending = [];

    foreach ($texts as $i => $rawText) {
        $text = trim((string)$rawText);

        if ($text === '') {
            $results[$i] = '';
            continue;
        }

        $cacheFile = buildCacheFilePath($cacheDir, $source, $target, $text);

        if (is_file($cacheFile)) {
            $cached = file_get_contents($cacheFile);
            $results[$i] = $cached !== false ? (string)$cached : '';
        } else {
            $pending[$i] = $text;
        }
    }

    $batches = splitIntoBatches($pending);

    foreach ($batches as $batch) {
        $translations = translateBatchDeepL($batch, $source, $target, $apiKey, $apiBase);
        $indexes = array_keys($batch);

        foreach ($translations as $offset => $translatedText) {
            $originalIndex = $indexes[$offset];
            $originalText  = $batch[$originalIndex];

            $results[$originalIndex] = $translatedText;

            $cacheFile = buildCacheFilePath($cacheDir, $source, $target, $originalText);
            @file_put_contents($cacheFile, $translatedText);
        }
    }

    ksort($results);

    echo json_encode([
        'ok' => true,
        'translations' => array_values($results)
    ]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'ok' => false,
        'message' => $e->getMessage()
    ]);
}