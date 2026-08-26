<?php
/**
 * Authentication + Role-Based Access Middleware
 * Include this file at the top of any protected endpoint.
 *
 * Usage:
 *   require_once __DIR__ . '/../middleware/middleware.php';
 *   $authUser = authenticate();          // verifies token, returns payload
 *   authorize($authUser, ['internal_bd']); // restrict to specific roles
 */

require_once __DIR__ . '/../helpers/jwt_helper.php';

/**
 * Extract Bearer token from Authorization header.
 */
function getBearerToken(): ?string
{
    $headers = null;

    if (isset($_SERVER['Authorization'])) {
        $headers = trim($_SERVER['Authorization']);
    } elseif (function_exists('apache_request_headers')) {
        $requestHeaders = apache_request_headers();
        $requestHeaders = array_combine(
            array_map('ucwords', array_keys($requestHeaders)),
            array_values($requestHeaders)
        );
        if (isset($requestHeaders['Authorization'])) {
            $headers = trim($requestHeaders['Authorization']);
        }
    }

    if (!empty($headers) && preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
        return $matches[1];
    }

    return null;
}

/**
 * Verifies the JWT token from the request.
 * Halts execution with 401 if missing/invalid/expired.
 * Returns decoded payload (contains user_id, role, etc.) on success.
 */
function authenticate(): array
{
    $token = getBearerToken();

    if (!$token) {
        respondUnauthorized('Missing authentication token');
    }

    $payload = JWTHelper::verifyToken($token);

    if (!$payload) {
        respondUnauthorized('Invalid or expired token');
    }

    if (isTokenBlacklisted($token)) {
        respondUnauthorized('Token has been logged out');
    }

    return $payload;
}

/**
 * Checks whether a token was invalidated via logout.
 * Requires $pdo to be available (include config/db.php before this middleware).
 */
function isTokenBlacklisted(string $token): bool
{
    global $pdo;

    if (!isset($pdo)) {
        return false; // DB not loaded in this context; skip check
    }

    $stmt = $pdo->prepare('SELECT 1 FROM token_blacklist WHERE token = :token LIMIT 1');
    $stmt->execute(['token' => $token]);

    return (bool) $stmt->fetchColumn();
}

/**
 * Restrict access to specific roles.
 * Halts execution with 403 if role not allowed.
 *
 * @param array $authUser   Decoded JWT payload (must contain 'role')
 * @param array $allowedRoles e.g. ['internal_bd'] or ['external_client']
 */
function authorize(array $authUser, array $allowedRoles): void
{
    if (!isset($authUser['role']) || !in_array($authUser['role'], $allowedRoles, true)) {
        respondForbidden('You do not have permission to access this resource');
    }
}

function respondUnauthorized(string $message): void
{
    http_response_code(401);
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => $message]);
    exit;
}

function respondForbidden(string $message): void
{
    http_response_code(403);
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => $message]);
    exit;
}
