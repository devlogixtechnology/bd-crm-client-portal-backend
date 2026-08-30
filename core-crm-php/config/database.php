<?php
/**
 * Database configuration.
 * Values are pulled from the .env file (see .env.example) with safe defaults.
 */
return [
    'host'    => Env::get('DB_HOST', '127.0.0.1'),
    'port'    => Env::get('DB_PORT', '3306'),
    'database'=> Env::get('DB_DATABASE', 'core_crm'),
    'username'=> Env::get('DB_USERNAME', 'root'),
    'password'=> Env::get('DB_PASSWORD', ''),
    'charset' => Env::get('DB_CHARSET', 'utf8mb4'),
];
