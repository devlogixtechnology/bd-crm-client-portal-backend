<?php
session_start();
if (!isset($_SESSION['admin_id'])) { header('Location: login.php'); exit(); }
require_once '../config/database.php';

$agreements = $pdo->query("SELECT ag.*, c.name as client_name FROM agreements ag JOIN clients c ON ag.client_id = c.id ORDER BY ag.id DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Agreements - Admin Panel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="app-container">
        <?php include 'sidebar.php'; ?>
        <main class="main-content">
            <header class="top-bar"><h1>Manage Agreements</h1></header>
            <div class="content-area">
                <div class="card">
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
                        <h3>All Agreements</h3>
                        <button class="btn btn-primary"><i class="fas fa-plus"></i> Create Agreement</button>
                    </div>
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr><th>ID</th><th>Title</th><th>Client</th><th>Status</th><th>Created At</th><th>Actions</th></tr>
                            </thead>
                            <tbody>
                                <?php foreach ($agreements as $agr): ?>
                                <tr>
                                    <td><?= $agr['id'] ?></td>
                                    <td><?= htmlspecialchars($agr['title']) ?></td>
                                    <td><?= htmlspecialchars($agr['client_name']) ?></td>
                                    <td><span class="badge badge-<?= strtolower($agr['status']) ?>"><?= $agr['status'] ?></span></td>
                                    <td><?= date('M d, Y', strtotime($agr['created_at'])) ?></td>
                                    <td>
                                        <button class="btn btn-primary btn-sm"><i class="fas fa-eye"></i></button>
                                        <button class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                                <?php if (empty($agreements)): ?>
                                    <tr><td colspan="6" style="text-align:center;">No agreements found.</td></tr>
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