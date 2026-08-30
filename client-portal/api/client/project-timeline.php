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
    $stmt = $pdo->prepare("SELECT id, name, status, progress FROM projects WHERE client_id = ?");
    $stmt->execute([$client_id]);
    $project = $stmt->fetch();

    if (!$project) {
        echo json_encode(['success' => true, 'project' => null, 'timeline' => []]);
        exit;
    }

    $stmt = $pdo->prepare("SELECT title, status, description, date FROM project_timeline WHERE project_id = ? ORDER BY id ASC");
    $stmt->execute([$project['id']]);
    $timeline = $stmt->fetchAll();

    echo json_encode(['success' => true, 'project' => $project, 'timeline' => $timeline]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error']);
}