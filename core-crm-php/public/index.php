<?php
/**
 * Front controller / entry point.
 * Placeholder boilerplate — routing and authentication will be added
 * in upcoming tasks. For now it just confirms the app and DB connection
 * are working.
 */
require_once __DIR__ . '/../bootstrap.php';

header('Content-Type: application/json');

try {
    $pdo = Database::getInstance();
    $pdo->query('SELECT 1');

    echo json_encode([
        'app'    => 'Core CRM (Core PHP + MySQL)',
        'status' => 'ok',
        'db'     => 'connected',
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'status'  => 'error',
        'message' => $e->getMessage(),
    ]);
}
