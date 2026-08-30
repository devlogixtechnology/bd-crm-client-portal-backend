<?php
session_start();

// Security Check: Agar admin login nahi hai to login page par bhej do
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

require_once '../config/database.php';

// Dashboard ke liye stats fetch karna
try {
    $totalClients = $pdo->query("SELECT COUNT(*) FROM clients WHERE role = 'client'")->fetchColumn();
    $totalProjects = $pdo->query("SELECT COUNT(*) FROM projects")->fetchColumn();
    $totalInvoices = $pdo->query("SELECT COUNT(*) FROM invoices")->fetchColumn();
    $pendingInvoices = $pdo->query("SELECT COUNT(*) FROM invoices WHERE status = 'Pending'")->fetchColumn();
} catch (PDOException $e) {
    $totalClients = 0; $totalProjects = 0; $totalInvoices = 0; $pendingInvoices = 0;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Client Portal</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="app-container">
        <?php include 'sidebar.php'; ?>
        
        <main class="main-content">
            <header class="top-bar">
                <h1>Admin Dashboard</h1>
                <div class="user-profile">
                    <span>Welcome, <?= htmlspecialchars($_SESSION['admin_name']) ?></span>
                    <i class="fas fa-user-circle"></i>
                </div>
            </header>
            
            <div class="content-area">
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon blue"><i class="fas fa-users"></i></div>
                        <div class="stat-info">
                            <h3><?= $totalClients ?></h3>
                            <p>Total Clients</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon purple"><i class="fas fa-folder-open"></i></div>
                        <div class="stat-info">
                            <h3><?= $totalProjects ?></h3>
                            <p>Active Projects</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon yellow"><i class="fas fa-file-invoice-dollar"></i></div>
                        <div class="stat-info">
                            <h3><?= $totalInvoices ?></h3>
                            <p>Total Invoices</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon red"><i class="fas fa-exclamation-circle"></i></div>
                        <div class="stat-info">
                            <h3><?= $pendingInvoices ?></h3>
                            <p>Pending Payments</p>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <h3>Quick Actions</h3>
                    <p style="color: var(--text-muted); margin-bottom: 1rem;">Manage your portal efficiently using the sidebar.</p>
                    <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                        <a href="clients.php" class="btn btn-primary"><i class="fas fa-user-plus"></i> Add New Client</a>
                        <a href="invoices.php" class="btn btn-primary"><i class="fas fa-file-invoice"></i> Create Invoice</a>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>