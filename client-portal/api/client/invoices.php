<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['client_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

require_once '../../config/database.php';
$client_id = $_SESSION['client_id'];

try {
    $stmt = $pdo->prepare("SELECT id, invoice_number, date, due_date, amount, status, download_url FROM invoices WHERE client_id = ? ORDER BY date DESC");
    $stmt->execute([$client_id]);
    $invoices = $stmt->fetchAll();

    echo json_encode(['success' => true, 'invoices' => $invoices]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error']);
}