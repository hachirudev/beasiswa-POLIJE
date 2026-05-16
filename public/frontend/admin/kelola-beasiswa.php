<?php
/**
 * Kelola Beasiswa Admin — Manajemen beasiswa (verifikasi, edit, hapus)
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
Session::requireRole('admin');

$db = Database::getInstance()->getConnection();
$beasiswaObj = new Beasiswa($db);
$simulasiObj = new Simulasi($db);

$listBeasiswa = $beasiswaObj->getAll();
$listSimulasi = $simulasiObj->getAll();

$pendingBeasiswa = count(array_filter($listBeasiswa, fn($b) => $b['status_verifikasi'] === 'pending'));
$pendingSimulasi = count(array_filter($listSimulasi, fn($s) => $s['status_simulasi'] === 'pending'));

$pageTitle = 'Kelola Beasiswa | Admin ' . APP_NAME;
$pageDescription = 'Kelola dan verifikasi beasiswa yang diunggah mitra.';
$activePage = 'kelola-beasiswa';

require_once __DIR__ . '/../layout/header.php';
require_once __DIR__ . '/../layout/sidebar-admin.php';
?>

<div class="main-content-admin">
    <div class="topbar-admin">
        <h1 class="page-title">Kelola Beasiswa</h1>
        <a href="<?= BASE_URL ?>/frontend/admin/unggah-beasiswa.php" class="btn btn-sm fw-semibold" style="background-color: var(--color-primary); border-color: var(--color-primary); color: #fff;"><i class="bi bi-plus-lg me-1"></i>Unggah Beasiswa</a>
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
                            <th class="py-3 px-4" style="font-weight:600; border-bottom:none;">Nama Beasiswa</th>
                            <th class="py-3 px-4" style="font-weight:600; border-bottom:none;">Penyelenggara</th>
                            <th class="py-3 px-4" style="font-weight:600; border-bottom:none;">Status Pendaftaran</th>
                            <th class="py-3 px-4" style="font-weight:600; border-bottom:none;">Status Verifikasi</th>
                            <th class="py-3 px-4" style="font-weight:600; border-bottom:none;">Tanggal Upload</th>
                            <th class="py-3 px-4 text-center" style="font-weight:600; border-bottom:none;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($listBeasiswa)): ?>
                            <tr><td colspan="6" class="text-center py-4">Belum ada beasiswa.</td></tr>
                        <?php else: ?>
                            <?php foreach ($listBeasiswa as $i => $b): ?>
                            <tr <?= $b['status_verifikasi'] === 'pending' ? 'style="background: #fffef5;"' : '' ?>>
                                <td class="py-3 px-4 fw-semibold"><?= htmlspecialchars($b['nama_beasiswa']) ?></td>
                                <td class="py-3 px-4 text-muted"><?= htmlspecialchars($b['nama_penyelenggara'] ?? 'Penyelenggara') ?></td>
                                <td class="py-3 px-4">
                                    <?php if ($b['status_pendaftaran_computed'] === 'dibuka'): ?>
                                        <span class="badge badge-status" style="background:#e8f5e9;color:#28a745;">Pendaftaran Dibuka</span>
                                    <?php elseif ($b['status_pendaftaran_computed'] === 'ditutup'): ?>
                                        <span class="badge badge-status" style="background:#f8d7da;color:#721c24;">Pendaftaran Ditutup</span>
                                    <?php else: ?>
                                        <span class="badge badge-status" style="background:#fff3cd;color:#856404;">Pendaftaran Belum Dibuka</span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-3 px-4">
                                    <?php if ($b['status_verifikasi'] === 'terverifikasi'): ?>
                                        <span class="badge badge-verifikasi" style="background:#e8f5e9;color:#28a745;">Terverifikasi</span>
                                    <?php elseif ($b['status_verifikasi'] === 'ditolak'): ?>
                                        <span class="badge badge-verifikasi" style="background:#f8d7da;color:#721c24;">Ditolak</span>
                                    <?php else: ?>
                                        <span class="badge badge-verifikasi" style="background:#fff3cd;color:#856404;">Pending</span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-3 px-4 text-muted"><?= date('d M Y', strtotime($b['upload_at'] ?? 'now')) ?></td>
                                <td class="py-3 px-4">
                                    <div class="d-flex justify-content-center gap-1">
                                        <?php if ($b['status_verifikasi'] === 'pending'): ?>
                                            <form action="<?= BASE_URL ?>/backend/beasiswa/verifikasi.php" method="POST" class="m-0">
                                                <input type="hidden" name="id_beasiswa" value="<?= $b['id_beasiswa'] ?>">
                                                <input type="hidden" name="status" value="terverifikasi">
                                                <button type="submit" class="btn btn-sm btn-success" title="Verifikasi"><i class="bi bi-check-lg"></i></button>
                                            </form>
                                            <button class="btn btn-sm btn-warning btn-tolak" data-id="<?= $b['id_beasiswa'] ?>" title="Tolak" data-bs-toggle="modal" data-bs-target="#modalTolak"><i class="bi bi-x-lg"></i></button>
                                        <?php endif; ?>
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

<!-- Modal Tolak -->
<div class="modal fade" id="modalTolak" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form action="<?= BASE_URL ?>/backend/beasiswa/verifikasi.php" method="POST" class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">Penolakan Beasiswa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id_beasiswa" id="tolak_id">
                <input type="hidden" name="status" value="ditolak">
                <div class="mb-3">
                    <label class="form-label fw-bold">Alasan Penolakan</label>
                    <textarea class="form-control" name="alasan" rows="3" required></textarea>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-warning">Tolak</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tolakBtns = document.querySelectorAll('.btn-tolak');
    const tolakId = document.getElementById('tolak_id');
    tolakBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            tolakId.value = btn.getAttribute('data-id');
        });
    });
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= BASE_URL ?>/assets/js/main.js"></script>
</body>
</html>
