<?php
require_once __DIR__ . '/../config/database.php';

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

function jsonBody(): array {
    $raw = file_get_contents('php://input');
    $data = json_decode($raw, true);
    return is_array($data) ? $data : [];
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

$method = $_SERVER['REQUEST_METHOD'];

// POST /leads - Create a new lead
if ($method === 'POST') {
    $input = jsonBody();

    $name = trim((string)($input['name'] ?? ''));
    $email = trim((string)($input['email'] ?? ''));
    $phone = trim((string)($input['phone'] ?? ''));
    $company = trim((string)($input['company'] ?? ''));
    $source = trim((string)($input['source'] ?? ''));
    $stage = trim((string)($input['stage'] ?? 'new'));
    $value = (float)($input['value'] ?? 0);
    $notes = trim((string)($input['notes'] ?? ''));

    $allowedStages = ['new','contacted','qualified','proposal','won','lost'];

    if ($name === '' || $email === '') {
        responseJson(false, 'name and email are required.', null, 422);
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        responseJson(false, 'Please provide a valid email address.', null, 422);
    }
    if (!in_array($stage, $allowedStages, true)) {
        responseJson(false, 'Invalid pipeline stage.', null, 422);
    }

    $stmt = $pdo->prepare(
        'INSERT INTO leads (name, email, phone, company, source, stage, value, notes)
         VALUES (:name, :email, :phone, :company, :source, :stage, :value, :notes)'
    );
    $stmt->execute([
        ':name' => $name,
        ':email' => $email,
        ':phone' => $phone ?: null,
        ':company' => $company ?: null,
        ':source' => $source ?: null,
        ':stage' => $stage,
        ':value' => $value,
        ':notes' => $notes ?: null,
    ]);

    $id = (int)$pdo->lastInsertId();
    $get = $pdo->prepare('SELECT * FROM leads WHERE id = ?');
    $get->execute([$id]);
    responseJson(true, 'Lead created successfully.', $get->fetch(), 201);
}

// GET /leads - Fetch all leads grouped by pipeline stage
if ($method === 'GET') {
    $stmt = $pdo->query('SELECT * FROM leads ORDER BY created_at DESC, id DESC');
    $rows = $stmt->fetchAll();

    $grouped = [
        'new' => [],
        'contacted' => [],
        'qualified' => [],
        'proposal' => [],
        'won' => [],
        'lost' => []
    ];

    foreach ($rows as $lead) {
        $grouped[$lead['stage']][] = $lead;
    }

    responseJson(true, 'Leads fetched and grouped by pipeline stage.', $grouped);
}

responseJson(false, 'Method not allowed.', null, 405);
