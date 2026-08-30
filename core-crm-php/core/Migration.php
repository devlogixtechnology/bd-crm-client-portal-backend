<?php
/**
 * Base class every migration file must extend.
 */
abstract class Migration
{
    /**
     * Run the migration (create/alter table).
     *
     * @param PDO $pdo
     */
    abstract public function up(PDO $pdo);
}
