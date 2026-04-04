<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'message' => 'Método no permitido.']);
    exit;
}

$configFile = __DIR__ . '/../config/translate_config.php';
if (!file_exists($configFile)) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'message' => 'No existe config/translate_config.php']);
    exit;
}

$config = require $configFile;
$apiKey = trim((string)($config['google_api_key'] ?? ''));

if ($apiKey === '') {
    http_response_code(500);
    echo json_encode(['ok' => false, 'message' => 'La API key está vacía.']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

if (!is_array($input)) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'message' => 'JSON inválido.']);
    exit;
}

$texts  = $input['texts'] ?? [];
$source = strtolower(trim((string)($input['source'] ?? 'es')));
$target = strtolower(trim((string)($input['target'] ?? 'en')));

if (!is_array($texts)) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'message' => 'texts debe ser un arreglo.']);
    exit;
}

if (!in_array($source, ['es', 'en'], true) || !in_array($target, ['es', 'en'], true)) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'message' => 'Idiomas no permitidos.']);
    exit;
}

if ($source === $target) {
    echo json_encode([
        'ok' => true,
        'translations' => array_map(fn($t) => (string)$t, $texts)
    ]);
    exit;
}

$cacheDir = __DIR__ . '/../storage/translation_cache';
if (!is_dir($cacheDir) && !mkdir($cacheDir, 0775, true) && !is_dir($cacheDir)) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'message' => 'No se pudo crear la carpeta de caché.']);
    exit;
}

function cache_file_path(string $cacheDir, string $source, string $target, string $text): string
{
    $hash = md5($source . '|' . $target . '|' . $text);
    return $cacheDir . '/' . $hash . '.txt';
}

function translate_batch_google(array $texts, string $source, string $target, string $apiKey): array
{
    $url = 'https://translation.googleapis.com/language/translate/v2';

    $postFields = 'source=' . urlencode($source)
        . '&target=' . urlencode($target)
        . '&format=text'
        . '&key=' . urlencode($apiKey);

    foreach ($texts as $text) {
        $postFields .= '&q=' . urlencode($text);
    }

    $ch = curl_init($url);

    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/x-www-form-urlencoded; charset=UTF-8'
        ],
        CURLOPT_POSTFIELDS => $postFields,
        CURLOPT_TIMEOUT => 30,
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
        $message = $data['error']['message'] ?? 'Error desconocido de Google Translate.';
        throw new RuntimeException($message);
    }

    if (
        !isset($data['data']['translations']) ||
        !is_array($data['data']['translations'])
    ) {
        throw new RuntimeException('Respuesta inválida de Google Translate.');
    }

    $out = [];
    foreach ($data['data']['translations'] as $item) {
        $translated = (string)($item['translatedText'] ?? '');
        $out[] = html_entity_decode($translated, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    return $out;
}

function split_into_batches(array $indexedTexts, int $maxItems = 10, int $maxChars = 4500): array
{
    $batches = [];
    $current = [];
    $currentChars = 0;

    foreach ($indexedTexts as $index => $text) {
        $len = mb_strlen($text, 'UTF-8');

        if (
            !empty($current) &&
            (count($current) >= $maxItems || ($currentChars + $len) > $maxChars)
        ) {
            $batches[] = $current;
            $current = [];
            $currentChars = 0;
        }

        $current[$index] = $text;
        $currentChars += $len;
    }

    if (!empty($current)) {
        $batches[] = $current;
    }

    return $batches;
}

try {
    $results = [];
    $pending = [];

    foreach ($texts as $i => $rawText) {
        $text = trim((string)$rawText);

        if ($text === '') {
            $results[$i] = '';
            continue;
        }

        $cacheFile = cache_file_path($cacheDir, $source, $target, $text);

        if (is_file($cacheFile)) {
            $results[$i] = file_get_contents($cacheFile) ?: '';
        } else {
            $pending[$i] = $text;
        }
    }

    $batches = split_into_batches($pending);

    foreach ($batches as $batch) {
        $translatedBatch = translate_batch_google(array_values($batch), $source, $target, $apiKey);
        $indexes = array_keys($batch);

        foreach ($translatedBatch as $offset => $translatedText) {
            $originalIndex = $indexes[$offset];
            $originalText  = $batch[$originalIndex];

            $results[$originalIndex] = $translatedText;

            $cacheFile = cache_file_path($cacheDir, $source, $target, $originalText);
            file_put_contents($cacheFile, $translatedText);
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