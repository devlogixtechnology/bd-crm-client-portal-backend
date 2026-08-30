<?php
/**
 * Contacts table.
 * A contact is a person associated with a lead (1 lead -> many contacts).
 */
class CreateContactsTable extends Migration
{
    public function up(PDO $pdo)
    {
        $sql = "CREATE TABLE IF NOT EXISTS contacts (
            id INT AUTO_INCREMENT PRIMARY KEY,
            lead_id INT NOT NULL,
            name VARCHAR(150) NOT NULL,
            email VARCHAR(150) NULL,
            phone VARCHAR(30) NULL,
            designation VARCHAR(100) NULL,
            created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            CONSTRAINT fk_contacts_lead_id FOREIGN KEY (lead_id) REFERENCES leads(id)
                ON DELETE CASCADE ON UPDATE CASCADE,
            INDEX idx_contacts_lead_id (lead_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

        $pdo->exec($sql);
    }
}
