<aside class="sidebar">
    <div class="sidebar-header">
        <h2><i class="fas fa-cube"></i> ClientPortal</h2>
    </div>
    <nav class="sidebar-nav">
        <a href="dashboard.php" class="<?= basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : '' ?>">
            <i class="fas fa-home"></i> Dashboard
        </a>
        <a href="timeline.php" class="<?= basename($_SERVER['PHP_SELF']) == 'timeline.php' ? 'active' : '' ?>">
            <i class="fas fa-stream"></i> Project Timeline
        </a>
        <a href="invoices.php" class="<?= basename($_SERVER['PHP_SELF']) == 'invoices.php' ? 'active' : '' ?>">
            <i class="fas fa-file-invoice"></i> Invoices
        </a>
        <a href="agreements.php" class="<?= basename($_SERVER['PHP_SELF']) == 'agreements.php' ? 'active' : '' ?>">
            <i class="fas fa-file-signature"></i> Agreements
        </a>
    </nav>
    <div class="sidebar-footer">
        <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>
</aside>