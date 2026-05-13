<?php
/**
 * Profil Mitra — Pengaturan akun dan ganti password
 */
declare(strict_types=1);
require_once '../../../config/app.php';
require_once CONFIG_PATH . 'Database.php';
require_once HELPERS_PATH . 'Session.php';
require_once HELPERS_PATH . 'Response.php';
require_once CLASSES_PATH . 'Mitra.php';

Session::start();
Session::requireLogin();
Session::requireRole('mitra');

$db = Database::getInstance()->getConnection();
$mitra = (new Mitra($db))->getById(Session::getId());

$pageTitle = 'Profil Mitra — ' . APP_NAME;
$pageDescription = 'Lihat dan kelola informasi akun mitra Anda.';
$activePage = 'profil';

// Header
require_once __DIR__ . '/../layout/header.php';

// Navbar Mitra
require_once __DIR__ . '/../layout/navbar-mitra.php';
?>

<!-- ========== KONTEN UTAMA ========== -->
<div class="container py-5" style="max-width: 1000px;">
    <h1 class="page-title d-flex align-items-center gap-2">
        <i class="bi bi-gear"></i> Pengaturan Akun
    </h1>

    <!-- flash message -->
    <?php if ($msg = Session::getFlash('success')): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <?= htmlspecialchars($msg) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>
    <?php if ($msg = Session::getFlash('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <?= htmlspecialchars($msg) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>

    <!-- Card Informasi Mitra -->
    <div class="profile-card p-4 p-md-5 mb-4">
        <h5 class="fw-bold mb-3 d-flex align-items-center gap-2" style="color: var(--color-primary-dark);">
            <i class="bi bi-building"></i> Informasi Mitra
        </h5>
        <hr class="mb-2 mt-0" style="border-top: 2px solid var(--color-light); opacity: 1;">

        <div class="row py-3 border-bottom">
            <div class="col-md-4 fw-bold text-dark">Nama Mitra</div>
            <div class="col-md-8 text-muted"><?= htmlspecialchars($mitra['nama_mitra'] ?? '') ?></div>
        </div>
        <div class="row py-3 border-bottom">
            <div class="col-md-4 fw-bold text-dark">Email</div>
            <div class="col-md-8 text-muted"><?= htmlspecialchars($mitra['email']) ?></div>
        </div>
        <div class="row py-3 border-bottom">
            <div class="col-md-4 fw-bold text-dark">Bidang Usaha</div>
            <div class="col-md-8 text-muted"><?= htmlspecialchars($mitra['bidang_usaha']) ?></div>
        </div>
        <div class="row py-3 border-bottom">
            <div class="col-md-4 fw-bold text-dark">Telepon</div>
            <div class="col-md-8 text-muted"><?= htmlspecialchars($mitra['telepon']) ?></div>
        </div>
        <div class="row py-3 border-bottom">
            <div class="col-md-4 fw-bold text-dark">Website</div>
            <div class="col-md-8 text-muted">
                <?php if (!empty($mitra['website'])): ?>
                <a href="<?= htmlspecialchars($mitra['website']) ?>" target="_blank" style="color: var(--color-primary); text-decoration: none;"><?= htmlspecialchars($mitra['website']) ?></a>
                <?php else: ?>
                -
                <?php endif; ?>
            </div>
        </div>

    </div>

    <!-- Card Ganti Password -->
    <div class="profile-card p-4 p-md-5 mb-4">
        <h5 class="fw-bold mb-3 d-flex align-items-center gap-2" style="color: var(--color-primary-dark);">
            <i class="bi bi-shield-lock"></i> Ganti Password
        </h5>
        <hr class="mb-4 mt-0" style="border-top: 2px solid var(--color-light); opacity: 1;">

        <form action="<?= BASE_URL ?>/backend/akun/update-profil.php" method="POST">
            <div class="row g-3">
                <div class="col-md-12">
                    <label for="passwordLama" class="form-label">Password Lama</label>
                    <input type="password" class="form-control" id="passwordLama" name="passwordLama" required>
                </div>
                <div class="col-md-6">
                    <label for="passwordBaru" class="form-label">Password Baru</label>
                    <input type="password" class="form-control" id="passwordBaru" name="passwordBaru" required>
                    <div class="form-text">Gunakan minimal 8 karakter kombinasi huruf & angka.</div>
                </div>
                <div class="col-md-6">
                    <label for="konfirmasiPassword" class="form-label">Konfirmasi Password Baru</label>
                    <input type="password" class="form-control" id="konfirmasiPassword" name="konfirmasiPassword" required>
                </div>
                <div class="col-12 mt-3 text-end">
                    <button type="submit" class="btn btn-primary-custom"><i class="bi bi-key-fill me-2"></i>Perbarui Password</button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- ========== END KONTEN ========== -->

<?php
// Footer
require_once __DIR__ . '/../layout/footer.php';
?>
