<?php
/**
 * Kelola Beasiswa Mitra — Daftar beasiswa yang diunggah mitra
 */
declare(strict_types=1);
require_once '../../../config/app.php';
require_once CONFIG_PATH . 'Database.php';
require_once HELPERS_PATH . 'Session.php';
require_once HELPERS_PATH . 'Response.php';
require_once CLASSES_PATH . 'Beasiswa.php';
require_once CLASSES_PATH . 'Simulasi.php';

Session::start();
Session::requireLogin();
Session::requireRole('mitra');

$db = Database::getInstance()->getConnection();
$idMitra = Session::getId();
$beasiswaObj = new Beasiswa($db);
$simulasiObj = new Simulasi($db);

$listBeasiswa = $beasiswaObj->getByMitra($idMitra);

// Hitung statistik
$totalDiunggah = count($listBeasiswa);
$pendingVerifikasi = count(array_filter($listBeasiswa, fn($b) => $b['status_verifikasi'] === 'pending'));
$totalSimulasi = $simulasiObj->countByMitra($idMitra);

$pageTitle = 'Kelola Beasiswa | ' . APP_NAME;
$pageDescription = 'Kelola beasiswa yang telah Anda unggah sebagai mitra.';
$activePage = 'kelola-beasiswa';

// Header
require_once __DIR__ . '/../layout/header.php';

// Navbar Mitra
require_once __DIR__ . '/../layout/navbar-mitra.php';
?>

<!-- ========== KONTEN UTAMA ========== -->
<div class="container py-5">
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

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="page-title mb-0" style="font-size: 2rem;">Kelola Beasiswa</h1>
        <a href="<?= BASE_URL ?>/frontend/mitra/unggah-beasiswa.php" class="btn btn-primary" style="background-color: var(--color-primary); border-color: var(--color-primary); font-weight: 600;"><i class="bi bi-upload me-2"></i>Unggah Beasiswa</a>
    </div>

    <div class="row g-4 mb-5">
        <!-- Indikator 1 -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4" style="background: var(--color-white);">
                <div class="card-body p-4 d-flex align-items-center gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px; background: var(--color-lighter); color: var(--color-primary); font-size: 1.8rem;">
                        <i class="bi bi-file-earmark-text"></i>
                    </div>
                    <div>
                        <div class="text-muted" style="font-size: 0.9rem; font-weight: 600;">Total Diunggah</div>
                        <div class="fs-3 fw-bold" style="color: var(--color-dark);"><?= $totalDiunggah ?></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Indikator 2 -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4" style="background: var(--color-white);">
                <div class="card-body p-4 d-flex align-items-center gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px; background: #fff3cd; color: #ffc107; font-size: 1.8rem;">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                    <div>
                        <div class="text-muted" style="font-size: 0.9rem; font-weight: 600;">Pending Verifikasi</div>
                        <div class="fs-3 fw-bold" style="color: var(--color-dark);"><?= $pendingVerifikasi ?></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Indikator 3 -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4" style="background: var(--color-white);">
                <div class="card-body p-4 d-flex align-items-center gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px; background: #e8f5e9; color: #28a745; font-size: 1.8rem;">
                        <i class="bi bi-people"></i>
                    </div>
                    <div>
                        <div class="text-muted" style="font-size: 0.9rem; font-weight: 600;">Total Simulasi</div>
                        <div class="fs-3 fw-bold" style="color: var(--color-dark);"><?= $totalSimulasi ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead style="background: var(--color-lighter);">
                        <tr>
                            <th class="py-3 px-4" style="color: var(--color-dark); font-weight: 600; border-bottom: none;">Nama Beasiswa</th>
                            <th class="py-3 px-4" style="color: var(--color-dark); font-weight: 600; border-bottom: none;">Periode</th>
                            <th class="py-3 px-4" style="color: var(--color-dark); font-weight: 600; border-bottom: none;">Status Verifikasi</th>
                            <th class="py-3 px-4 text-end" style="color: var(--color-dark); font-weight: 600; border-bottom: none;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($listBeasiswa)): ?>
                            <tr><td colspan="4" class="text-center py-4">Belum ada beasiswa yang diunggah.</td></tr>
                        <?php else: ?>
                            <?php foreach ($listBeasiswa as $b): ?>
                            <tr>
                                <td class="py-3 px-4">
                                    <div class="fw-bold" style="color: var(--color-text);"><?= htmlspecialchars($b['nama_beasiswa']) ?></div>
                                </td>
                                <td class="py-3 px-4">
                                    <div style="font-size: 0.9rem; color: var(--color-text);"><?= date('d M Y', strtotime($b['tgl_buka'] ?? 'now')) ?> - <?= date('d M Y', strtotime($b['tgl_tutup'] ?? 'now')) ?></div>
                                </td>
                                <td class="py-3 px-4">
                                    <?php if ($b['status_verifikasi'] === 'terverifikasi'): ?>
                                        <span class="badge" style="background: #e8f5e9; color: #28a745; padding: 0.5em 0.8em;">Terverifikasi</span>
                                    <?php elseif ($b['status_verifikasi'] === 'ditolak'): ?>
                                        <span class="badge" style="background: #f8d7da; color: #721c24; padding: 0.5em 0.8em;">Ditolak</span>
                                        <div class="text-muted mt-1" style="font-size: 0.75rem;">Alasan: <?= htmlspecialchars($b['alasan_penolakan'] ?? '') ?></div>
                                    <?php else: ?>
                                        <span class="badge" style="background: #fff3cd; color: #856404; padding: 0.5em 0.8em;">Pending</span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-3 px-4">
                                    <div class="d-flex justify-content-end gap-1">
                                        <form action="<?= BASE_URL ?>/backend/beasiswa/delete.php" method="POST" class="m-0" onsubmit="return confirm('Yakin ingin menghapus beasiswa ini?');">
                                            <input type="hidden" name="id_beasiswa" value="<?= $b['id_beasiswa'] ?>">
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </div>
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
<!-- ========== END KONTEN ========== -->

<?php
// Footer
require_once __DIR__ . '/../layout/footer.php';
?>
