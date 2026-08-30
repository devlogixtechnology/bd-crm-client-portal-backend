<?php
/**
 * Activity Logs table.
 * Records actions/events performed against a lead and/or its contact
 * (e.g. call, email, note, status change). Both foreign keys are
 * nullable since a log entry may relate to only a lead, only a
 * contact, or both.
 */
class CreateActivityLogsTable extends Migration
{
    public function up(PDO $pdo)
    {
        $sql = "CREATE TABLE IF NOT EXISTS activity_logs (
            id INT AUTO_INCREMENT PRIMARY KEY,
            lead_id INT NULL,
            contact_id INT NULL,
            activity_type VARCHAR(100) NOT NULL,
            description TEXT NULL,
            performed_by VARCHAR(150) NULL,
            created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            CONSTRAINT fk_activity_logs_lead_id FOREIGN KEY (lead_id) REFERENCES leads(id)
                ON DELETE CASCADE ON UPDATE CASCADE,
            CONSTRAINT fk_activity_logs_contact_id FOREIGN KEY (contact_id) REFERENCES contacts(id)
                ON DELETE CASCADE ON UPDATE CASCADE,
            INDEX idx_activity_logs_lead_id (lead_id),
            INDEX idx_activity_logs_contact_id (contact_id),
            INDEX idx_activity_logs_type (activity_type)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

        $pdo->exec($sql);
    }
}
