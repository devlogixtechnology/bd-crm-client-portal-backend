<?php
require_once __DIR__ . '/../config/database.php';

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Methods: PUT, OPTIONS');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

function responseJson(bool $success, string $message, $data = null, int $status = 200): never {
    http_response_code($status);
    echo json_encode([
        'success' => $success,
        'message' => $message,
        'data' => $data
    ], JSON_PRETTY_PRINT);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
    responseJson(false, 'Method not allowed.', null, 405);
}

$id = (int)($_GET['id'] ?? 0);
$body = json_decode(file_get_contents('php://input'), true);
$stage = trim((string)($body['stage'] ?? ''));
$allowedStages = ['new','contacted','qualified','proposal','won','lost'];

if ($id <= 0) {
    responseJson(false, 'A valid lead id is required.', null, 422);
}
if (!in_array($stage, $allowedStages, true)) {
    responseJson(false, 'Invalid pipeline stage.', null, 422);
}

$check = $pdo->prepare('SELECT id FROM leads WHERE id = ?');
$check->execute([$id]);
if (!$check->fetch()) {
    responseJson(false, 'Lead not found.', null, 404);
}

$stmt = $pdo->prepare('UPDATE leads SET stage = ? WHERE id = ?');
$stmt->execute([$stage, $id]);

$get = $pdo->prepare('SELECT * FROM leads WHERE id = ?');
$get->execute([$id]);

responseJson(true, 'Lead stage updated successfully.', $get->fetch());
