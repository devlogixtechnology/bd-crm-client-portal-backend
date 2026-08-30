<?php
/**
 * Pipelines table.
 * Tracks a lead's progress through the sales pipeline (1 lead -> many
 * pipeline stage records over time).
 */
class CreatePipelinesTable extends Migration
{
    public function up(PDO $pdo)
    {
        $sql = "CREATE TABLE IF NOT EXISTS pipelines (
            id INT AUTO_INCREMENT PRIMARY KEY,
            lead_id INT NOT NULL,
            stage ENUM('prospecting', 'proposal', 'negotiation', 'won', 'lost') NOT NULL DEFAULT 'prospecting',
            amount DECIMAL(12,2) NOT NULL DEFAULT 0.00,
            probability TINYINT UNSIGNED NOT NULL DEFAULT 0,
            expected_close_date DATE NULL,
            created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            CONSTRAINT fk_pipelines_lead_id FOREIGN KEY (lead_id) REFERENCES leads(id)
                ON DELETE CASCADE ON UPDATE CASCADE,
            INDEX idx_pipelines_lead_id (lead_id),
            INDEX idx_pipelines_stage (stage)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

        $pdo->exec($sql);
    }
}
