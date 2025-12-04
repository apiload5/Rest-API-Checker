<?php
// CORS headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Handle preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Get request data
$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['url'])) {
    http_response_code(400);
    echo json_encode(['error' => 'URL is required']);
    exit();
}

$url = $input['url'];
$method = $input['method'] ?? 'GET';
$headers = $input['headers'] ?? [];
$body = $input['body'] ?? null;
$timeout = $input['timeout'] ?? 10;

// Prepare cURL request
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

// Set method
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($method));

// Set headers
if (!empty($headers)) {
    $curlHeaders = [];
    foreach ($headers as $key => $value) {
        $curlHeaders[] = "$key: $value";
    }
    curl_setopt($ch, CURLOPT_HTTPHEADER, $curlHeaders);
}

// Set body
if ($body && in_array($method, ['POST', 'PUT', 'PATCH'])) {
    curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
}

// Execute request
$startTime = microtime(true);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$totalTime = microtime(true) - $startTime;

if (curl_errno($ch)) {
    http_response_code(500);
    echo json_encode([
        'error' => curl_error($ch),
        'time' => round($totalTime * 1000) . 'ms'
    ]);
    curl_close($ch);
    exit();
}

curl_close($ch);

// Return response
http_response_code(200);
echo json_encode([
    'status' => $httpCode,
    'headers' => [], // Can capture headers if needed
    'body' => $response,
    'time' => round($totalTime * 1000) . 'ms',
    'size' => strlen($response)
]);
?>
