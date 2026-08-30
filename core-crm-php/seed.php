<?php
/**
 * Run this from the command line to insert dummy data:
 *   php seed.php
 *
 * Seeders run in this fixed order because of foreign key dependencies:
 * Leads -> Contacts -> Pipelines -> Activity Logs
 */
require_once __DIR__ . '/bootstrap.php';

$pdo = Database::getInstance();

echo '== Running seeders ==' . PHP_EOL;

$runner = new SeederRunner($pdo, __DIR__ . '/database/seeders');
$runner->run([
    'LeadsSeeder',
    'ContactsSeeder',
    'PipelinesSeeder',
    'ActivityLogsSeeder',
]);

echo '== Done ==' . PHP_EOL;
