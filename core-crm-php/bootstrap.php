<?php
/**
 * Application bootstrap.
 * Loads environment variables and registers a simple autoloader
 * for the core/ classes. Core PHP only — no Composer / frameworks.
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

define('BASE_PATH', __DIR__);

// Simple autoloader for core/ classes
spl_autoload_register(function ($class) {
    $path = BASE_PATH . '/core/' . $class . '.php';
    if (file_exists($path)) {
        require_once $path;
    }
});

// Load environment variables from .env
Env::load(BASE_PATH . '/.env');
