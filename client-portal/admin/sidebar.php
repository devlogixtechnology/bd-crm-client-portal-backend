<aside class="sidebar">
    <div class="sidebar-header">
        <h2><i class="fas fa-user-shield"></i> Admin Panel</h2>
    </div>
    <nav class="sidebar-nav">
        <a href="dashboard.php" class="<?= basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : '' ?>">
            <i class="fas fa-home"></i> Dashboard
        </a>
        <a href="clients.php" class="<?= basename($_SERVER['PHP_SELF']) == 'clients.php' ? 'active' : '' ?>">
            <i class="fas fa-users"></i> Manage Clients
        </a>
        <a href="projects.php" class="<?= basename($_SERVER['PHP_SELF']) == 'projects.php' ? 'active' : '' ?>">
            <i class="fas fa-folder-open"></i> Manage Projects
        </a>
        <a href="invoices.php" class="<?= basename($_SERVER['PHP_SELF']) == 'invoices.php' ? 'active' : '' ?>">
            <i class="fas fa-file-invoice-dollar"></i> Manage Invoices
        </a>
        <a href="agreements.php" class="<?= basename($_SERVER['PHP_SELF']) == 'agreements.php' ? 'active' : '' ?>">
            <i class="fas fa-file-signature"></i> Agreements
        </a>
    </nav>
    <div class="sidebar-footer">
        <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>
</aside>