CREATE DATABASE IF NOT EXISTS crm_pipeline
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE crm_pipeline;

CREATE TABLE IF NOT EXISTS leads (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    phone VARCHAR(30) NULL,
    company VARCHAR(150) NULL,
    source VARCHAR(50) NULL,
    stage ENUM('new','contacted','qualified','proposal','won','lost') NOT NULL DEFAULT 'new',
    value DECIMAL(12,2) NOT NULL DEFAULT 0.00,
    notes TEXT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_stage (stage),
    INDEX idx_created_at (created_at)
);
