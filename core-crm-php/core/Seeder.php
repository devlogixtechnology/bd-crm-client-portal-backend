<?php
/**
 * Base class every seeder file must extend.
 */
abstract class Seeder
{
    /**
     * Insert dummy data.
     *
     * @param PDO $pdo
     */
    abstract public function run(PDO $pdo);
}
