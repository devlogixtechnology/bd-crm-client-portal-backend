USE client_portal;

-- 1. Clients Table (Admin aur Client ke liye)
CREATE TABLE clients (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'client') DEFAULT 'client',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 2. Projects Table
CREATE TABLE projects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_id INT NOT NULL,
    name VARCHAR(150) NOT NULL,
    status VARCHAR(50) DEFAULT 'In Progress',
    progress INT DEFAULT 0,
    FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE CASCADE
);

-- 3. Project Timeline Table
CREATE TABLE project_timeline (
    id INT AUTO_INCREMENT PRIMARY KEY,
    project_id INT NOT NULL,
    title VARCHAR(150) NOT NULL,
    status ENUM('pending', 'in_progress', 'completed') DEFAULT 'pending',
    description TEXT,
    date DATE,
    FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE
);

-- 4. Invoices Table
CREATE TABLE invoices (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_id INT NOT NULL,
    invoice_number VARCHAR(50) UNIQUE NOT NULL,
    date DATE NOT NULL,
    due_date DATE NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    status ENUM('Paid', 'Pending', 'Overdue') DEFAULT 'Pending',
    download_url VARCHAR(255),
    FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE CASCADE
);

-- 5. Agreements Table
CREATE TABLE agreements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_id INT NOT NULL,
    title VARCHAR(200) NOT NULL,
    content TEXT NOT NULL,
    status ENUM('Pending', 'Signed') DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE CASCADE
);

-- 6. Agreement Signatures Table
CREATE TABLE agreement_signatures (
    id INT AUTO_INCREMENT PRIMARY KEY,
    agreement_id INT NOT NULL,
    client_id INT NOT NULL,
    signature_name VARCHAR(100) NOT NULL,
    signed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (agreement_id) REFERENCES agreements(id) ON DELETE CASCADE,
    FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE CASCADE
);

-- ==========================================
-- SAMPLE DATA (Testing ke liye)
-- ==========================================

-- Admin User (Password: 123456)
INSERT INTO clients (name, email, password, role) VALUES 
(' Admin', 'admin@portal.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

-- Client User (Password: 123456)
INSERT INTO clients (name, email, password, role) VALUES 
(1, 'John Doe', 'john@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2026-08-01 10:00:00'),
(2, 'Jack', 'jack@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2026-08-01 10:00:00'),
(3, 'Jane Smith', 'jane@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2026-08-05 11:30:00'),
(4, 'Michael Johnson', 'michael@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2026-08-10 09:15:00'),
(5, 'Emily Davis', 'emily@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2026-08-15 14:45:00'),
(6, 'David Wilson', 'david@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2026-08-20 16:20:00');
-- Sample Project for Client (ID 2)
INSERT INTO projects (client_id, name, status, progress) VALUES (2, 'Website Redesign', 'In Progress', 65);

-- Sample Timeline
INSERT INTO project_timeline (project_id, title, status, description, date) VALUES 
(1, 'Project Started', 'completed', 'Requirements gathered.', '2026-08-01'),
(1, 'Development', 'in_progress', 'Coding phase.', '2026-08-15'),
(1, 'Testing', 'pending', 'QA Testing.', '2026-09-01');

-- Sample Invoice
INSERT INTO invoices (client_id, invoice_number, date, due_date, amount, status, download_url) VALUES 
(2, 'INV-001', '2026-08-01', '2026-08-15', 5000.00, 'Pending', '#');

-- Sample Agreement
INSERT INTO agreements (client_id, title, content, status) VALUES 
(2, 'Service Agreement', 'Terms and conditions for the project...', 'Pending');