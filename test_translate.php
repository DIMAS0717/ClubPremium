<?php
$payload = [
    'source' => 'ES',
    'target' => 'EN-US',
    'texts'  => [
        'Hola, esta es una prueba',
        'Propiedades en venta',
        'Contáctanos'
    ]
];

$ch = curl_init('http://localhost/CLUBPREMIUM/api/translate.php');

curl_setopt_array($ch, [
    CURLOPT_POST => true,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
    CURLOPT_POSTFIELDS => json_encode($payload, JSON_UNESCAPED_UNICODE),
]);

$response = curl_exec($ch);
$error = curl_error($ch);
curl_close($ch);

echo '<pre>';
echo $error ? $error : $response;
echo '</pre>';