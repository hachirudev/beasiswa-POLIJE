<?php
/**
 * Beranda Mitra — Halaman utama satu halaman panjang
 */
declare(strict_types=1);
require_once '../../config/app.php';
require_once CONFIG_PATH . 'Database.php';
require_once HELPERS_PATH . 'Session.php';
require_once HELPERS_PATH . 'Response.php';
require_once CLASSES_PATH . 'Beasiswa.php';
require_once CLASSES_PATH . 'Tag.php';
require_once CLASSES_PATH . 'Pustaka.php';
require_once CLASSES_PATH . 'Faq.php';

Session::start();
Session::requireLogin();
Session::requireRole('mitra');

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

$pageTitle = 'Beranda | ' . APP_NAME;
$pageDescription = 'Temukan berbagai program beasiswa yang tersedia di Politeknik Negeri Jember.';
$activePage = 'beranda';

// Header
require_once __DIR__ . '/../layout/header.php';

// Navbar Mitra
require_once __DIR__ . '/../layout/navbar-mitra.php';
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
