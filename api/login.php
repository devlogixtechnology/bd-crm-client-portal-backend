<?php
/**
 * POST /api/login.php
 * Body (JSON): { "email": "user@example.com", "password": "secret123" }
 *
 * Validates credentials and returns a JWT on success.
 */

header('Content-Type: application/json');

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../helpers/jwt_helper.php';

$input = json_decode(file_get_contents('php://input'), true);

$email    = $input['email'] ?? '';
$password = $input['password'] ?? '';

if (empty($email) || empty($password)) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Email and password are required']);
    exit;
}

try {
    $stmt = $pdo->prepare('SELECT id, name, email, password, role FROM users WHERE email = :email LIMIT 1');
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($password, $user['password'])) {
        http_response_code(401);
        echo json_encode(['status' => 'error', 'message' => 'Invalid email or password']);
        exit;
    }

    // role must be either 'internal_bd' or 'external_client'
    $token = JWTHelper::generateToken([
        'user_id' => $user['id'],
        'email'   => $user['email'],
        'role'    => $user['role'],
    ]);

    http_response_code(200);
    echo json_encode([
        'status'  => 'success',
        'message' => 'Login successful',
        'token'   => $token,
        'user'    => [
            'id'    => $user['id'],
            'name'  => $user['name'],
            'email' => $user['email'],
            'role'  => $user['role'],
        ],
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Server error, please try again later']);
}
