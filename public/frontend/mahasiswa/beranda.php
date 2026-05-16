<?php
/**
 * Beranda Mahasiswa
 */
declare(strict_types=1);
require_once '../../../config/app.php';
require_once CONFIG_PATH . 'Database.php';
require_once HELPERS_PATH . 'Session.php';
require_once HELPERS_PATH . 'Response.php';
require_once CLASSES_PATH . 'Beasiswa.php';
require_once CLASSES_PATH . 'Tag.php';
require_once CLASSES_PATH . 'Pustaka.php';
require_once CLASSES_PATH . 'Faq.php';
require_once CLASSES_PATH . 'HasilSimulasi.php';

Session::start();
Session::requireLogin();
Session::requireRole('mahasiswa');

$db = Database::getInstance()->getConnection();
$searchResult = (new Beasiswa($db))->searchAdvanced([
    'year' => (int) date('Y'),
    'month' => (int) date('n')
]);
$listBeasiswa = $searchResult['data'];
$totalPages   = $searchResult['total_pages'];
$listTag      = (new Tag($db))->getAll();
$listPustaka  = (new Pustaka($db))->getAll();
$listFaq      = (new Faq($db))->getAll();
$jumlahNotif  = (new HasilSimulasi($db))->countUnread(Session::getId());

$pageTitle = 'Beranda | ' . APP_NAME;
$pageDescription = 'Temukan berbagai program beasiswa yang tersedia di Politeknik Negeri Jember.';
$activePage = 'beranda';

// Header
require_once __DIR__ . '/../layout/header.php';

// Navbar Mahasiswa
require_once __DIR__ . '/../layout/navbar-mahasiswa.php';
?>
<!-- flash message -->
<?php if ($msg = Session::getFlash('success')): ?>
<div class="alert alert-success alert-dismissible fade show m-3">
    <?= htmlspecialchars($msg) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>
<?php if ($msg = Session::getFlash('error')): ?>
<div class="alert alert-danger alert-dismissible fade show m-3">
    <?= htmlspecialchars($msg) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>
<?php if ($msg = Session::getFlash('info_profil')): ?>
<div class="alert alert-info alert-dismissible fade show m-3 d-flex align-items-center gap-2">
    <i class="bi bi-info-circle-fill flex-shrink-0" style="font-size: 1.25rem;"></i>
    <div>
        <?= htmlspecialchars($msg) ?>
        <a href="<?= BASE_URL ?>/frontend/mahasiswa/profil.php" class="alert-link ms-1 text-decoration-underline">Atur Profil Sekarang <i class="bi bi-arrow-right"></i></a>
    </div>
    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>
<!-- ===== SECTIONS ===== -->
<?php require_once __DIR__ . '/../sections/section-beranda.php'; ?>
<?php require_once __DIR__ . '/../sections/section-tentang.php'; ?>
<?php require_once __DIR__ . '/../sections/section-jenis-beasiswa.php'; ?>
<?php require_once __DIR__ . '/../sections/section-alur-beasiswa.php'; ?>
<?php require_once __DIR__ . '/../sections/section-pustaka.php'; ?>
<?php require_once __DIR__ . '/../sections/section-faq.php'; ?>

<?php
// Footer
require_once __DIR__ . '/../layout/footer.php';
?>