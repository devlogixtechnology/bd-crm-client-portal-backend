<?php
/**
 * Run this from the command line to execute pending migrations:
 *   php migrate.php
 */
require_once __DIR__ . '/bootstrap.php';

$pdo = Database::getInstance();

echo '== Running migrations ==' . PHP_EOL;

$migrator = new Migrator($pdo, __DIR__ . '/database/migrations');
$migrator->run();

echo '== Done ==' . PHP_EOL;
