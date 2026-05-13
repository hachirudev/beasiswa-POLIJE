<!-- ========== SIDEBAR ADMIN ========== -->
<?php
$adminName = $_SESSION['nama'] ?? 'Administrator';
$stats = $stats ?? ['pending_verifikasi' => 0, 'pending_review' => 0];
?>
<aside class="sidebar-admin" id="sidebar-admin">
    <!-- Brand -->
    <a href="<?= BASE_URL ?>/frontend/admin/dashboard.php" class="sidebar-brand" style="gap: 12px; padding: 1rem 1.5rem;">
        <img src="<?= BASE_URL ?>/assets/img/logo polije.png" alt="Logo" style="height: 45px; width: auto; object-fit: contain;">
        <div>
            <div class="brand-text">Beasiswa POLIJE</div>
            <div class="brand-subtitle">Admin Panel</div>
        </div>
    </a>

    <!-- Main Navigation -->
    <div class="sidebar-label">Menu Utama</div>
    <ul class="sidebar-nav">
        <li class="nav-item">
            <a class="nav-link <?= ($activePage ?? '') === 'dashboard' ? 'active' : '' ?>" href="<?= BASE_URL ?>/frontend/admin/dashboard.php">
                <i class="bi bi-grid-1x2-fill"></i> Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= ($activePage ?? '') === 'kelola-beasiswa' ? 'active' : '' ?>" href="<?= BASE_URL ?>/frontend/admin/kelola-beasiswa.php">
                <i class="bi bi-award-fill"></i> Kelola Beasiswa
                <?php if (($stats['pending_verifikasi'] ?? 0) > 0): ?>
                <span class="sidebar-badge"><?= $stats['pending_verifikasi'] ?></span>
                <?php endif; ?>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= ($activePage ?? '') === 'review-simulasi' ? 'active' : '' ?>" href="<?= BASE_URL ?>/frontend/admin/review-simulasi.php">
                <i class="bi bi-clipboard2-check-fill"></i> Review Simulasi
                <?php if (($stats['pending_review'] ?? 0) > 0): ?>
                <span class="sidebar-badge"><?= $stats['pending_review'] ?></span>
                <?php endif; ?>
            </a>
        </li>
    </ul>

    <!-- Data Management -->
    <div class="sidebar-label">Kelola Data</div>
    <ul class="sidebar-nav">
        <li class="nav-item">
            <a class="nav-link <?= ($activePage ?? '') === 'kelola-mitra' ? 'active' : '' ?>" href="<?= BASE_URL ?>/frontend/admin/kelola-mitra.php">
                <i class="bi bi-building"></i> Kelola Mitra
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= ($activePage ?? '') === 'kelola-pustaka' ? 'active' : '' ?>" href="<?= BASE_URL ?>/frontend/admin/kelola-pustaka.php">
                <i class="bi bi-journal-bookmark-fill"></i> Kelola Pustaka
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= ($activePage ?? '') === 'kelola-faq' ? 'active' : '' ?>" href="<?= BASE_URL ?>/frontend/admin/kelola-faq.php">
                <i class="bi bi-question-circle-fill"></i> Kelola FAQ
            </a>
        </li>
    </ul>

    <!-- Footer -->
    <div class="sidebar-footer">
        <div class="admin-info">
            <div class="admin-avatar"><i class="bi bi-shield-lock-fill"></i></div>
            <div>
                <div class="admin-name"><?= htmlspecialchars($adminName) ?></div>
                <div class="admin-role">Super Admin</div>
            </div>
            <a href="<?= BASE_URL ?>/auth/logout.php" class="btn-logout" title="Logout">
                <i class="bi bi-box-arrow-right"></i>
            </a>
        </div>
    </div>
</aside>
<!-- ========== END SIDEBAR ========== -->
