<?php
session_start();
if (!isset($_SESSION['admin_id'])) { header('Location: login.php'); exit(); }
require_once '../config/database.php';

$invoices = $pdo->query("SELECT inv.*, c.name as client_name FROM invoices inv JOIN clients c ON inv.client_id = c.id ORDER BY inv.id DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Invoices - Admin Panel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="app-container">
        <?php include 'sidebar.php'; ?>
        <main class="main-content">
            <header class="top-bar"><h1>Manage Invoices</h1></header>
            <div class="content-area">
                <div class="card">
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
                        <h3>All Invoices</h3>
                        <button class="btn btn-primary"><i class="fas fa-plus"></i> Create Invoice</button>
                    </div>
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr><th>Invoice #</th><th>Client</th><th>Date</th><th>Due Date</th><th>Amount</th><th>Status</th><th>Actions</th></tr>
                            </thead>
                            <tbody>
                                <?php foreach ($invoices as $inv): ?>
                                <tr>
                                    <td><strong><?= htmlspecialchars($inv['invoice_number']) ?></strong></td>
                                    <td><?= htmlspecialchars($inv['client_name']) ?></td>
                                    <td><?= $inv['date'] ?></td>
                                    <td><?= $inv['due_date'] ?></td>
                                    <td>$<?= number_format($inv['amount'], 2) ?></td>
                                    <td><span class="badge badge-<?= strtolower($inv['status']) ?>"><?= $inv['status'] ?></span></td>
                                    <td>
                                        <button class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                                <?php if (empty($invoices)): ?>
                                    <tr><td colspan="7" style="text-align:center;">No invoices found.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>