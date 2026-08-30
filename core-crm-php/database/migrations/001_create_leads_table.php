<?php
/**
 * Leads table.
 * A lead is a potential customer captured before conversion.
 */
class CreateLeadsTable extends Migration
{
    public function up(PDO $pdo)
    {
        $sql = "CREATE TABLE IF NOT EXISTS leads (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(150) NOT NULL,
            email VARCHAR(150) NULL,
            phone VARCHAR(30) NULL,
            company VARCHAR(150) NULL,
            source VARCHAR(100) NULL,
            status ENUM('new', 'contacted', 'qualified', 'lost', 'converted') NOT NULL DEFAULT 'new',
            assigned_to VARCHAR(150) NULL,
            created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_leads_status (status),
            INDEX idx_leads_email (email)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

        $pdo->exec($sql);
    }
}
