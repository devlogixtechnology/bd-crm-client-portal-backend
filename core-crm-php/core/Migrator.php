<?php
/**
 * Core PHP migration runner.
 *
 * - Scans database/migrations/*.php in filename order.
 * - Tracks already-executed migrations in a `migrations` table.
 * - Runs only pending migrations, in order.
 */
class Migrator
{
    private $pdo;
    private $migrationsPath;

    public function __construct(PDO $pdo, $migrationsPath)
    {
        $this->pdo = $pdo;
        $this->migrationsPath = rtrim($migrationsPath, '/');
        $this->ensureMigrationsTable();
    }

    private function ensureMigrationsTable()
    {
        $sql = "CREATE TABLE IF NOT EXISTS migrations (
            id INT AUTO_INCREMENT PRIMARY KEY,
            migration VARCHAR(255) NOT NULL UNIQUE,
            executed_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

        $this->pdo->exec($sql);
    }

    private function getExecutedMigrations()
    {
        $stmt = $this->pdo->query('SELECT migration FROM migrations');
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    private function getMigrationFiles()
    {
        $files = glob($this->migrationsPath . '/*.php');
        sort($files); // filename prefix (001_, 002_, ...) controls execution order
        return $files;
    }

    /**
     * Convert a migration filename into its expected class name.
     * e.g. 001_create_leads_table.php -> CreateLeadsTable
     */
    private function classNameFromFile($file)
    {
        $base = basename($file, '.php');
        $base = preg_replace('/^[0-9]+_/', '', $base); // strip numeric prefix
        $parts = explode('_', $base);
        $parts = array_map('ucfirst', $parts);
        return implode('', $parts);
    }

    public function run()
    {
        $executed = $this->getExecutedMigrations();
        $files = $this->getMigrationFiles();
        $ranAny = false;

        foreach ($files as $file) {
            $migrationName = basename($file, '.php');

            if (in_array($migrationName, $executed)) {
                echo "SKIP    {$migrationName} (already run)" . PHP_EOL;
                continue;
            }

            require_once $file;
            $className = $this->classNameFromFile($file);

            if (!class_exists($className)) {
                echo "ERROR   {$migrationName}: class {$className} not found" . PHP_EOL;
                continue;
            }

            $migration = new $className();

            if (!($migration instanceof Migration)) {
                echo "ERROR   {$migrationName}: {$className} must extend Migration" . PHP_EOL;
                continue;
            }

            try {
                $migration->up($this->pdo);

                $stmt = $this->pdo->prepare('INSERT INTO migrations (migration) VALUES (:migration)');
                $stmt->execute(['migration' => $migrationName]);

                echo "OK      {$migrationName} migrated" . PHP_EOL;
                $ranAny = true;
            } catch (Exception $e) {
                echo "ERROR   {$migrationName}: " . $e->getMessage() . PHP_EOL;
            }
        }

        if (!$ranAny) {
            echo 'Nothing to migrate. Database is up to date.' . PHP_EOL;
        }
    }
}
