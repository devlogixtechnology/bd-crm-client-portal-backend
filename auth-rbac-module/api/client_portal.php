<?php
/**
 * GET /api/client_portal.php
 * Header: Authorization: Bearer <token>
 *
 * Accessible ONLY to role = 'external_client'
 * Example of a protected Client Portal endpoint using the middleware.
 */

header('Content-Type: application/json');

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../middleware/middleware.php';

$authUser = authenticate();
authorize($authUser, ['external_client']);

// --- Role check passed, proceed with actual endpoint logic ---
http_response_code(200);
echo json_encode([
    'status'  => 'success',
    'message' => 'Client portal data accessed successfully',
    'role'    => $authUser['role'],
    'data'    => [
        // Replace with real query, e.g. SELECT * FROM client_projects
    ],
]);
