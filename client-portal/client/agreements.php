<?php
session_start();
if (!isset($_SESSION['client_id'])) { header('Location: index.php'); exit; }
require_once '../config/database.php';
$client_id = $_SESSION['client_id'];

$stmt = $pdo->prepare("SELECT * FROM agreements WHERE client_id = ? ORDER BY created_at DESC LIMIT 1");
$stmt->execute([$client_id]);
$agreement = $stmt->fetch();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agreements - Client Portal</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="app-container">
        <?php include 'sidebar.php'; ?>
        <main class="main-content">
            <header class="top-bar"><h1>Agreements</h1></header>
            <div class="content-area" id="agreements-container">
                <?php if ($agreement): ?>
                    <div class="card agreement-card" id="agreement-<?= $agreement['id'] ?>">
                        <div class="agreement-header">
                            <h3><?= htmlspecialchars($agreement['title']) ?></h3>
                            <span class="badge badge-<?= strtolower($agreement['status']) ?>" id="status-badge-<?= $agreement['id'] ?>">
                                <?= $agreement['status'] ?>
                            </span>
                        </div>
                        <div class="agreement-content">
                            <p><?= nl2br(htmlspecialchars($agreement['content'])) ?></p>
                        </div>
                        
                        <?php if ($agreement['status'] === 'Pending'): ?>
                            <form class="signature-form" data-agreement-id="<?= $agreement['id'] ?>">
                                <div class="form-group">
                                    <label>Digital Signature (Type your full name)</label>
                                    <input type="text" name="signature" required placeholder="John Doe">
                                </div>
                                <div class="form-group checkbox-group">
                                    <input type="checkbox" id="confirm-terms" required>
                                    <label for="confirm-terms">I confirm that I agree to the terms of this agreement.</label>
                                </div>
                                <button type="submit" class="btn btn-primary">Sign Agreement</button>
                            </form>
                        <?php else: ?>
                            <div class="alert alert-success">This agreement has been successfully signed.</div>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <div class="card"><p class="empty-state">No agreements found.</p></div>
                <?php endif; ?>
            </div>
        </main>
    </div>
    <script src="../assets/js/app.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.querySelector('.signature-form');
            if (form) {
                form.addEventListener('submit', (e) => handleAgreementSign(e, form));
            }
        });
    </script>
</body>
</html>