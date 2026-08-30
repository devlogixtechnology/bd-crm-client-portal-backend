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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $agreement_id = (int)($data['agreement_id'] ?? 0);
    $signature = trim($data['signature'] ?? '');

    if (!$agreement_id || !$signature) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Agreement ID and signature are required']);
        exit;
    }

    try {
        $pdo->beginTransaction();

        // Verify agreement belongs to client and is pending
        $stmt = $pdo->prepare("SELECT id, status FROM agreements WHERE id = ? AND client_id = ?");
        $stmt->execute([$agreement_id, $client_id]);
        $agreement = $stmt->fetch();

        if (!$agreement) {
            $pdo->rollBack();
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Forbidden: Agreement not found or does not belong to you']);
            exit;
        }

        if ($agreement['status'] === 'Signed') {
            $pdo->rollBack();
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Agreement is already signed']);
            exit;
        }

        // Update agreement status
        $updateStmt = $pdo->prepare("UPDATE agreements SET status = 'Signed' WHERE id = ?");
        $updateStmt->execute([$agreement_id]);

        // Save signature
        $sigStmt = $pdo->prepare("INSERT INTO agreement_signatures (agreement_id, client_id, signature_name) VALUES (?, ?, ?)");
        $sigStmt->execute([$agreement_id, $client_id, $signature]);

        $pdo->commit();
        echo json_encode(['success' => true, 'message' => 'Agreement signed successfully.']);
    } catch (Exception $e) {
        $pdo->rollBack();
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Server error occurred']);
    }
} else {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
}