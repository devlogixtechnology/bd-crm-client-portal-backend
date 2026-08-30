<?php
session_start();
if (!isset($_SESSION['client_id'])) { header('Location: index.php'); exit; }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoices - Client Portal</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="app-container">
        <?php include 'sidebar.php'; ?>
        <main class="main-content">
            <header class="top-bar"><h1>Invoices</h1></header>
            <div class="content-area" id="invoices-container">
                <div class="loading-spinner"><i class="fas fa-circle-notch fa-spin"></i> Loading invoices...</div>
            </div>
        </main>
    </div>
    <script src="../assets/js/app.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => loadInvoices());
    </script>
</body>
</html>