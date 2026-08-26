-- Users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,           -- store password_hash() output
    role ENUM('internal_bd', 'external_client') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Token blacklist table (for logout / token invalidation)
CREATE TABLE IF NOT EXISTS token_blacklist (
    id INT AUTO_INCREMENT PRIMARY KEY,
    token VARCHAR(500) NOT NULL,
    expires_at DATETIME NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Sample seed data (password = "Password123", hashed with password_hash)
-- Replace hash below with output of: password_hash('Password123', PASSWORD_DEFAULT)
INSERT INTO users (name, email, password, role) VALUES
('Areesha (BD Team)', 'bd@example.com', '$2y$10$fC5jMLBG.qDSUpmmTSkc2uB9QFzY2Dx4CT6FEwAMu.faPZC41.c8G', 'internal_bd'),
('Client User', 'client@example.com', '$2y$10$fC5jMLBG.qDSUpmmTSkc2uB9QFzY2Dx4CT6FEwAMu.faPZC41.c8G', 'external_client');
