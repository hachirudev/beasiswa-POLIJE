<?php
/**
 * Review Simulasi Admin — Daftar simulasi pending review
 */
declare(strict_types=1);
require_once '../../config/app.php';
require_once CONFIG_PATH . 'Database.php';
require_once HELPERS_PATH . 'Session.php';
require_once HELPERS_PATH . 'Response.php';
require_once CLASSES_PATH . 'Simulasi.php';
require_once CLASSES_PATH . 'Beasiswa.php';

Session::start();
Session::requireLogin();
Session::requireRole('admin');

$db = Database::getInstance()->getConnection();
$simulasiObj = new Simulasi($db);
$beasiswaObj = new Beasiswa($db);

$listSimulasi = $simulasiObj->getAll();

$pendingBeasiswa = count(array_filter($beasiswaObj->getAll(), fn($b) => $b['status_verifikasi'] === 'pending'));
$pendingSimulasi = count(array_filter($listSimulasi, fn($s) => $s['status_simulasi'] === 'pending'));

$pageTitle = 'Review Simulasi | Admin ' . APP_NAME;
$pageDescription = 'Review simulasi pendaftaran beasiswa mahasiswa.';
$activePage = 'review-simulasi';

require_once __DIR__ . '/../layout/header.php';
require_once __DIR__ . '/../layout/sidebar-admin.php';
?>
<div class="main-content-admin">
    <div class="topbar-admin">
        <h1 class="page-title">Review Simulasi</h1>
        <span class="text-muted" style="font-size:0.85rem;"><i class="bi bi-funnel me-1"></i>Menampilkan: Semua Status</span>
    </div>

    <!-- flash message -->
    <?php if ($msg = Session::getFlash('success')): ?>
    <div class="alert alert-success alert-dismissible fade show m-4 mb-0">
        <?= htmlspecialchars($msg) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>
    <?php if ($msg = Session::getFlash('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show m-4 mb-0">
        <?= htmlspecialchars($msg) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>
    <div class="p-4">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead style="background: var(--color-lighter);">
                        <tr>
                            <th class="py-3 px-4" style="font-weight:600; border-bottom:none;">Nama Mahasiswa</th>
                            <th class="py-3 px-4" style="font-weight:600; border-bottom:none;">Beasiswa</th>
                            <th class="py-3 px-4" style="font-weight:600; border-bottom:none;">Tanggal Submit</th>
                            <th class="py-3 px-4" style="font-weight:600; border-bottom:none;">Status</th>
                            <th class="py-3 px-4 text-center" style="font-weight:600; border-bottom:none;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($listSimulasi)): ?>
                            <tr><td colspan="5" class="text-center py-4">Belum ada simulasi.</td></tr>
                        <?php else: ?>
                            <?php foreach ($listSimulasi as $s): ?>
                            <tr <?= $s['status_simulasi'] === 'pending' ? 'style="background:#fffef5;"' : '' ?>>
                                <td class="py-3 px-4">
                                    <div class="fw-semibold"><?= htmlspecialchars($s['nama']) ?></div>
                                    <div class="text-muted" style="font-size:0.8rem;"><?= htmlspecialchars((string)($s['NIM'] ?? '')) ?> • <?= htmlspecialchars($s['nama_prodi'] ?? 'Prodi') ?></div>
                                </td>
                                <td class="py-3 px-4 text-muted"><?= htmlspecialchars($s['nama_beasiswa'] ?? 'Beasiswa') ?></td>
                                <td class="py-3 px-4 text-muted"><?= date('d M Y', strtotime($s['created_at'] ?? 'now')) ?></td>
                                <td class="py-3 px-4">
                                    <?php if ($s['status_simulasi'] === 'lulus'): ?>
                                        <span class="badge" style="background:#e8f5e9;color:#28a745;padding:.45em .7em;">Lulus</span>
                                    <?php elseif ($s['status_simulasi'] === 'tidak_lulus'): ?>
                                        <span class="badge" style="background:#f8d7da;color:#721c24;padding:.45em .7em;">Tidak Lulus</span>
                                    <?php else: ?>
                                        <span class="badge" style="background:#fff3cd;color:#856404;padding:.45em .7em;">Pending</span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <a href="<?= BASE_URL ?>/frontend/admin/detail-review.php?id=<?= $s['id_simulasi'] ?>" class="btn btn-sm <?= $s['status_simulasi'] === 'pending' ? 'fw-semibold' : 'btn-outline-secondary fw-semibold' ?>" <?= $s['status_simulasi'] === 'pending' ? 'style="background-color:var(--color-primary);border-color:var(--color-primary);color:#fff;"' : '' ?>>
                                        <i class="bi bi-eye me-1"></i><?= $s['status_simulasi'] === 'pending' ? 'Review' : 'Detail' ?>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= BASE_URL ?>/assets/js/main.js"></script>
</body>
</html>
