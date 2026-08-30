<?php
session_start();
if (!isset($_SESSION['admin_id'])) { header('Location: login.php'); exit(); }
require_once '../config/database.php';

$clients = $pdo->query("SELECT id, name, email, created_at FROM clients WHERE role = 'client' ORDER BY id DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Clients - Admin Panel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="app-container">
        <?php include 'sidebar.php'; ?>
        <main class="main-content">
            <header class="top-bar"><h1>Manage Clients</h1></header>
            <div class="content-area">
                <div class="card">
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
                        <h3>All Registered Clients</h3>
                        <button class="btn btn-primary"><i class="fas fa-plus"></i> Add New Client</button>
                    </div>
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr><th>ID</th><th>Name</th><th>Email</th><th>Registered On</th><th>Actions</th></tr>
                            </thead>
                            <tbody>
                                <?php foreach ($clients as $client): ?>
                                <tr>
                                    <td><?= $client['id'] ?></td>
                                    <td><?= htmlspecialchars($client['name']) ?></td>
                                    <td><?= htmlspecialchars($client['email']) ?></td>
                                    <td><?= date('M d, Y', strtotime($client['created_at'])) ?></td>
                                    <td>
                                        <button class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></button>
                                        <button class="btn btn-sm" style="background:#ef4444; color:white;"><i class="fas fa-trash"></i></button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                                <?php if (empty($clients)): ?>
                                    <tr><td colspan="5" style="text-align:center;">No clients found.</td></tr>
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