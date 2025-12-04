<?php
// Simple proxy without external dependencies
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: *");
header("Access-Control-Allow-Headers: *");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

$input = json_decode(file_get_contents('php://input'), true);

if (empty($input['url'])) {
    http_response_code(400);
    echo json_encode(['error' => 'URL required']);
    exit;
}

// Use file_get_contents for simple GET requests
$context = stream_context_create([
    'http' => [
        'method' => $input['method'] ?? 'GET',
        'header' => implode("\r\n", $input['headers'] ?? []),
        'content' => $input['body'] ?? null,
        'timeout' => 9,
        'ignore_errors' => true
    ],
    'ssl' => [
        'verify_peer' => false,
        'verify_peer_name' => false
    ]
]);

$response = @file_get_contents($input['url'], false, $context);

if ($response === false) {
    http_response_code(500);
    echo json_encode(['error' => 'Request failed']);
    exit;
}

// Get HTTP status
preg_match('/HTTP\/\d\.\d\s+(\d+)/', $http_response_header[0] ?? '', $matches);
$status = $matches[1] ?? 200;

http_response_code(200);
echo json_encode([
    'status' => (int)$status,
    'body' => $response,
    'headers' => $http_response_header
]);
?>
