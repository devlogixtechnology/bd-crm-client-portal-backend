<?php
/**
 * GET /api/crm_pipeline.php
 * Header: Authorization: Bearer <token>
 *
 * Accessible ONLY to role = 'internal_bd'
 * Example of a protected CRM endpoint using the middleware.
 */

header('Content-Type: application/json');

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../middleware/middleware.php';

$authUser = authenticate();
authorize($authUser, ['internal_bd']);

// --- Role check passed, proceed with actual endpoint logic ---
http_response_code(200);
echo json_encode([
    'status'  => 'success',
    'message' => 'CRM pipeline data accessed successfully',
    'role'    => $authUser['role'],
    'data'    => [
        // Replace with real query, e.g. SELECT * FROM leads / deals
    ],
]);
