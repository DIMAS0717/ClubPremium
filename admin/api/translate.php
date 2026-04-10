<?php
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'ok' => false,
        'message' => 'Método no permitido'
    ]);
    exit;
}

$configFile = __DIR__ . '/../config/deepl_config.php';

if (!file_exists($configFile)) {
    http_response_code(500);
    echo json_encode([
        'ok' => false,
        'message' => 'No existe admin/config/deepl_config.php'
    ]);
    exit;
}

$config = require $configFile;

$apiKey  = trim((string)($config['api_key'] ?? $config['deepl_api_key'] ?? ''));
$apiBase = rtrim((string)($config['deepl_api_base'] ?? 'https://api-free.deepl.com'), '/');

if ($apiKey === '') {
    http_response_code(500);
    echo json_encode([
        'ok' => false,
        'message' => 'API KEY no configurada'
    ]);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

$texts = $data['texts'] ?? [];
$targetLang = strtoupper(trim((string)($data['target'] ?? $data['target_lang'] ?? 'EN-US')));
$sourceLang = strtoupper(trim((string)($data['source'] ?? 'ES')));

if (!is_array($texts) || empty($texts)) {
    echo json_encode([
        'ok' => false,
        'message' => 'No se recibieron textos para traducir'
    ]);
    exit;
}

$cacheDir = __DIR__ . '/../../storage/translation_cache/';

if (!is_dir($cacheDir)) {
    if (!mkdir($cacheDir, 0777, true)) {
        echo json_encode([
            'ok' => false,
            'message' => 'No se pudo crear la carpeta storage/translation_cache'
        ]);
        exit;
    }
}

function translateWithDeepL(array $texts, string $sourceLang, string $targetLang, string $apiKey, string $apiBase): array
{
    $url = $apiBase . '/v2/translate';

    $payload = [
        'text' => array_values($texts),
        'source_lang' => $sourceLang,
        'target_lang' => $targetLang
    ];

    $ch = curl_init($url);

    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            'Authorization: DeepL-Auth-Key ' . $apiKey,
            'Content-Type: application/json'
        ],
        CURLOPT_POSTFIELDS => json_encode($payload, JSON_UNESCAPED_UNICODE),
        CURLOPT_TIMEOUT => 30,

        // SOLO LOCAL
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => 0,
    ]);

    $response = curl_exec($ch);

    if ($response === false) {
        $error = curl_error($ch);
        curl_close($ch);
        throw new RuntimeException('Error cURL: ' . $error);
    }

    $httpCode = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $result = json_decode($response, true);

    if ($httpCode >= 400) {
        $deepLMessage = $result['message'] ?? $result['detail'] ?? ('HTTP ' . $httpCode . ' en DeepL');
        throw new RuntimeException('DeepL respondió: ' . $deepLMessage);
    }

    if (!isset($result['translations']) || !is_array($result['translations'])) {
        throw new RuntimeException('Respuesta inválida de DeepL: ' . $response);
    }

    $translated = [];
    foreach ($result['translations'] as $item) {
        $translated[] = (string)($item['text'] ?? '');
    }

    return $translated;
}

try {
    $results = [];
    $pendingIndexes = [];
    $pendingTexts = [];

    foreach ($texts as $index => $rawText) {
        $text = trim((string)$rawText);

        if ($text === '') {
            $results[$index] = '';
            continue;
        }

        $cacheKey = md5($text . '|' . $sourceLang . '|' . $targetLang);
        $cacheFile = $cacheDir . $cacheKey . '.txt';

        if (file_exists($cacheFile)) {
            $results[$index] = (string)file_get_contents($cacheFile);
        } else {
            $pendingIndexes[] = $index;
            $pendingTexts[] = $text;
        }
    }

    if (!empty($pendingTexts)) {
        $translatedBatch = translateWithDeepL($pendingTexts, $sourceLang, $targetLang, $apiKey, $apiBase);

        foreach ($translatedBatch as $i => $translatedText) {
            $originalIndex = $pendingIndexes[$i];
            $originalText = trim((string)$texts[$originalIndex]);

            $results[$originalIndex] = $translatedText;

            $cacheKey = md5($originalText . '|' . $sourceLang . '|' . $targetLang);
            $cacheFile = $cacheDir . $cacheKey . '.txt';
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