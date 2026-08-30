<?php
/**
 * Core PHP seeder runner.
 * Runs seeder classes in a fixed order (dependency order matters:
 * Leads -> Contacts -> Pipelines -> Activity Logs).
 */
class SeederRunner
{
    private $pdo;
    private $seedersPath;

    public function __construct(PDO $pdo, $seedersPath)
    {
        $this->pdo = $pdo;
        $this->seedersPath = rtrim($seedersPath, '/');
    }

    public function run(array $seederClasses)
    {
        foreach ($seederClasses as $className) {
            $file = $this->seedersPath . '/' . $className . '.php';

            if (!file_exists($file)) {
                echo "ERROR   {$className}: file not found ({$file})" . PHP_EOL;
                continue;
            }

            require_once $file;

            if (!class_exists($className)) {
                echo "ERROR   {$className}: class not found in file" . PHP_EOL;
                continue;
            }

            $seeder = new $className();

            if (!($seeder instanceof Seeder)) {
                echo "ERROR   {$className}: must extend Seeder" . PHP_EOL;
                continue;
            }

            try {
                $seeder->run($this->pdo);
                echo "OK      {$className} seeded" . PHP_EOL;
            } catch (Exception $e) {
                echo "ERROR   {$className}: " . $e->getMessage() . PHP_EOL;
            }
        }
    }
}
