<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['client_id'])) {
    header('Location: login.php');
    exit();
}

require_once '../config/database.php';

$client_id = $_SESSION['client_id'];
$client_name = $_SESSION['client_name'];

// Fetch user stats
try {
    // Active Projects
    $projStmt = $pdo->prepare("SELECT COUNT(*) as count FROM projects WHERE client_id = ?");
    $projStmt->execute([$client_id]);
    $activeProjects = $projStmt->fetch()['count'];

    // Pending Invoices
    $invStmt = $pdo->prepare("SELECT COUNT(*) as count FROM invoices WHERE client_id = ? AND status = 'Pending'");
    $invStmt->execute([$client_id]);
    $pendingInvoices = $invStmt->fetch()['count'];

    // Pending Agreements
    $agrStmt = $pdo->prepare("SELECT COUNT(*) as count FROM agreements WHERE client_id = ? AND status = 'Pending'");
    $agrStmt->execute([$client_id]);
    $pendingAgreements = $agrStmt->fetch()['count'];

    // Recent Invoice
    $recentInvStmt = $pdo->prepare("SELECT invoice_number, amount, status FROM invoices WHERE client_id = ? ORDER BY date DESC LIMIT 1");
    $recentInvStmt->execute([$client_id]);
    $recentInvoice = $recentInvStmt->fetch();
} catch (PDOException $e) {
    $activeProjects = 0;
    $pendingInvoices = 0;
    $pendingAgreements = 0;
    $recentInvoice = null;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Client Portal</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="app-container">
        <?php include 'sidebar.php'; ?>
        
        <main class="main-content">
            <header class="top-bar">
                <h1>Dashboard</h1>
                <div class="user-profile">
                    <span><?= htmlspecialchars($client_name) ?></span>
                    <i class="fas fa-user-circle"></i>
                </div>
            </header>
            
            <div class="content-area">
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon blue"><i class="fas fa-folder-open"></i></div>
                        <div class="stat-info">
                            <h3><?= $activeProjects ?></h3>
                            <p>Active Projects</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon yellow"><i class="fas fa-file-invoice-dollar"></i></div>
                        <div class="stat-info">
                            <h3><?= $pendingInvoices ?></h3>
                            <p>Pending Invoices</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon purple"><i class="fas fa-file-signature"></i></div>
                        <div class="stat-info">
                            <h3><?= $pendingAgreements ?></h3>
                            <p>Agreements to Sign</p>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <h3>Welcome, <?= htmlspecialchars($client_name) ?>!</h3>
                    <p>You have successfully logged in to the client portal.</p>
                    
                    <?php if ($recentInvoice): ?>
                        <div style="margin-top: 1rem; padding: 1rem; background: #f8fafc; border-radius: 6px;">
                            <h4>Recent Invoice</h4>
                            <p><strong><?= htmlspecialchars($recentInvoice['invoice_number']) ?></strong> - $<?= number_format($recentInvoice['amount'], 2) ?></p>
                            <span class="badge badge-<?= strtolower($recentInvoice['status']) ?>"><?= $recentInvoice['status'] ?></span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>
</body>
</html>