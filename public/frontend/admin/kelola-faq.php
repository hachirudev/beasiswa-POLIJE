<?php
/**
 * Kelola FAQ Admin — CRUD Frequently Asked Questions
 */
declare(strict_types=1);
require_once '../../../config/app.php';
require_once CONFIG_PATH . 'Database.php';
require_once HELPERS_PATH . 'Session.php';
require_once HELPERS_PATH . 'Response.php';
require_once CLASSES_PATH . 'Faq.php';

Session::start();
Session::requireLogin();
Session::requireRole('admin');

$db = Database::getInstance()->getConnection();
$faqObj = new Faq($db);
$listFaq = $faqObj->getAll();

require_once CLASSES_PATH . 'Beasiswa.php';
require_once CLASSES_PATH . 'Simulasi.php';
$pendingBeasiswa = count(array_filter((new Beasiswa($db))->getAll(), fn($b) => $b['status_verifikasi'] === 'pending'));
$pendingSimulasi = count(array_filter((new Simulasi($db))->getAll(), fn($s) => $s['status_simulasi'] === 'pending'));

$pageTitle = 'Kelola FAQ | Admin ' . APP_NAME;
$pageDescription = 'Kelola daftar pertanyaan yang sering diajukan.';
$activePage = 'kelola-faq';

require_once __DIR__ . '/../layout/header.php';
require_once __DIR__ . '/../layout/sidebar-admin.php';
?>
<div class="main-content-admin">
    <div class="topbar-admin">
        <h1 class="page-title">Kelola FAQ</h1>
        <button class="btn btn-sm fw-semibold" style="background-color:var(--color-primary);border-color:var(--color-primary);color:#fff;" data-bs-toggle="modal" data-bs-target="#modalTambahFaq"><i class="bi bi-plus-lg me-1"></i>Tambah FAQ</button>
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
                            <th class="py-3 px-4" style="font-weight:600; border-bottom:none; width: 50px;">No</th>
                            <th class="py-3 px-4" style="font-weight:600; border-bottom:none;">Pertanyaan</th>
                            <th class="py-3 px-4" style="font-weight:600; border-bottom:none;">Jawaban</th>
                            <th class="py-3 px-4 text-center" style="font-weight:600; border-bottom:none;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($listFaq)): ?>
                            <tr><td colspan="4" class="text-center py-4">Belum ada FAQ.</td></tr>
                        <?php else: ?>
                            <?php foreach ($listFaq as $i => $f): ?>
                            <tr>
                                <td class="py-3 px-4 text-muted"><?= $i + 1 ?></td>
                                <td class="py-3 px-4 fw-semibold" style="max-width:280px;"><?= htmlspecialchars($f['pertanyaan']) ?></td>
                                <td class="py-3 px-4 text-muted" style="max-width:350px;"><?= nl2br(htmlspecialchars($f['jawaban'])) ?></td>
                                <td class="py-3 px-4 text-center">
                                    <form action="<?= BASE_URL ?>/backend/faq/delete.php" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus FAQ ini?');">
                                        <input type="hidden" name="id_pertanyaan" value="<?= $f['id_pertanyaan'] ?>">
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus"><i class="bi bi-trash"></i></button>
                                    </form>
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

<!-- Modal Tambah FAQ -->
<div class="modal fade" id="modalTambahFaq" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background:var(--color-lighter); border-bottom:1px solid var(--color-light);">
                <h5 class="modal-title fw-bold"><i class="bi bi-plus-circle me-2" style="color:var(--color-primary);"></i>Tambah FAQ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= BASE_URL ?>/backend/faq/store.php" method="POST">
                <div class="modal-body p-4">
                    <div class="mb-3"><label class="form-label fw-bold">Pertanyaan</label><input type="text" name="pertanyaan" class="form-control" placeholder="Masukkan pertanyaan FAQ" required></div>
                    <div class="mb-3"><label class="form-label fw-bold">Jawaban</label><textarea class="form-control" name="jawaban" rows="5" placeholder="Masukkan jawaban FAQ" required></textarea></div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn fw-semibold" style="background-color:var(--color-primary);border-color:var(--color-primary);color:#fff;">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Hapus FAQ (dipindah ke js / d-inline) -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= BASE_URL ?>/assets/js/main.js"></script>
</body>
</html>
