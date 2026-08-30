<?php
session_start();
if (!isset($_SESSION['admin_id'])) { header('Location: login.php'); exit(); }
require_once '../config/database.php';

$projects = $pdo->query("SELECT p.*, c.name as client_name FROM projects p JOIN clients c ON p.client_id = c.id ORDER BY p.id DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Projects - Admin Panel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="app-container">
        <?php include 'sidebar.php'; ?>
        <main class="main-content">
            <header class="top-bar"><h1>Manage Projects</h1></header>
            <div class="content-area">
                <div class="card">
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
                        <h3>All Active Projects</h3>
                        <button class="btn btn-primary"><i class="fas fa-plus"></i> Create Project</button>
                    </div>
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr><th>ID</th><th>Project Name</th><th>Client</th><th>Status</th><th>Progress</th><th>Actions</th></tr>
                            </thead>
                            <tbody>
                                <?php foreach ($projects as $proj): ?>
                                <tr>
                                    <td><?= $proj['id'] ?></td>
                                    <td><?= htmlspecialchars($proj['name']) ?></td>
                                    <td><?= htmlspecialchars($proj['client_name']) ?></td>
                                    <td><span class="badge badge-<?= strtolower(str_replace(' ', '_', $proj['status'])) ?>"><?= $proj['status'] ?></span></td>
                                    <td>
                                        <div class="progress-bar-bg" style="width: 100px; display:inline-block; vertical-align:middle;">
                                            <div class="progress-bar-fill" style="width: <?= $proj['progress'] ?>%"></div>
                                        </div> <?= $proj['progress'] ?>%
                                    </td>
                                    <td>
                                        <button class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                                <?php if (empty($projects)): ?>
                                    <tr><td colspan="6" style="text-align:center;">No projects found.</td></tr>
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