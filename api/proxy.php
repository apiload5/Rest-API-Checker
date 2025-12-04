<?php
// Simple API Proxy without external dependencies
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, PATCH, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Get request data
$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['url']) || empty($input['url'])) {
    http_response_code(400);
    echo json_encode(['error' => 'URL is required']);
    exit();
}

$url = $input['url'];
$method = strtoupper($input['method'] ?? 'GET');
$headers = $input['headers'] ?? [];
$body = $input['body'] ?? null;
$timeout = 8; // 8 seconds for Vercel safety

// Prepare headers array for cURL
$curlHeaders = [];
foreach ($headers as $key => $value) {
    $curlHeaders[] = "$key: $value";
}

// Initialize cURL
$ch = curl_init();

// Set basic options
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

// Set headers if any
if (!empty($curlHeaders)) {
    curl_setopt($ch, CURLOPT_HTTPHEADER, $curlHeaders);
}

// Set request body for POST, PUT, PATCH
if (in_array($method, ['POST', 'PUT', 'PATCH', 'DELETE']) && $body !== null) {
    curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
}

// Execute request
$startTime = microtime(true);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$totalTime = microtime(true) - $startTime;

if (curl_errno($ch)) {
    $error = curl_error($ch);
    curl_close($ch);
    
    http_response_code(500);
    echo json_encode([
        'error' => 'cURL Error: ' . $error,
        'time' => round($totalTime * 1000) . 'ms'
    ]);
    exit();
}

curl_close($ch);

// Return successful response
http_response_code(200);
echo json_encode([
    'status' => $httpCode,
    'body' => $response,
    'headers' => [], // Can add header capture if needed
    'time' => round($totalTime * 1000) . 'ms',
    'size' => strlen($response)
]);
?>
