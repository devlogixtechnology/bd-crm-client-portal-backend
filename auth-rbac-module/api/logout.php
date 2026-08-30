<?php
/**
 * POST /api/logout.php
 * Header: Authorization: Bearer <token>
 *
 * Since JWTs are stateless, logout is handled by blacklisting the token
 * until its natural expiry (prevents reuse of a "logged out" token).
 */

header('Content-Type: application/json');

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../middleware/middleware.php';

$authUser = authenticate(); // ensures token is valid before logging out
$token    = getBearerToken();

try {
    // Requires a `token_blacklist` table (see sql/schema.sql)
    $stmt = $pdo->prepare(
        'INSERT INTO token_blacklist (token, expires_at) VALUES (:token, FROM_UNIXTIME(:expires_at))'
    );
    $stmt->execute([
        'token'      => $token,
        'expires_at' => $authUser['exp'],
    ]);

    http_response_code(200);
    echo json_encode(['status' => 'success', 'message' => 'Logged out successfully']);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Server error, please try again later']);
}
