<?php
/**
 * Kelola Pustaka Admin — Manajemen dokumen/panduan
 */
declare(strict_types=1);
require_once '../../../config/app.php';
require_once CONFIG_PATH . 'Database.php';
require_once HELPERS_PATH . 'Session.php';
require_once HELPERS_PATH . 'Response.php';
require_once CLASSES_PATH . 'Pustaka.php';

Session::start();
Session::requireLogin();
Session::requireRole('admin');

$db = Database::getInstance()->getConnection();
$pustakaObj = new Pustaka($db);
$listPustaka = $pustakaObj->getAll();

require_once CLASSES_PATH . 'Beasiswa.php';
require_once CLASSES_PATH . 'Simulasi.php';
$pendingBeasiswa = count(array_filter((new Beasiswa($db))->getAll(), fn($b) => $b['status_verifikasi'] === 'pending'));
$pendingSimulasi = count(array_filter((new Simulasi($db))->getAll(), fn($s) => $s['status_simulasi'] === 'pending'));

$pageTitle = 'Kelola Pustaka | Admin ' . APP_NAME;
$pageDescription = 'Kelola dokumen panduan dan informasi untuk mahasiswa.';
$activePage = 'kelola-pustaka';

require_once __DIR__ . '/../layout/header.php';
require_once __DIR__ . '/../layout/sidebar-admin.php';
?>
<div class="main-content-admin">
    <div class="topbar-admin">
        <h1 class="page-title">Kelola Pustaka</h1>
        <button class="btn btn-sm fw-semibold" style="background-color:var(--color-primary);border-color:var(--color-primary);color:#fff;" data-bs-toggle="modal" data-bs-target="#modalTambahPustaka"><i class="bi bi-plus-lg me-1"></i>Tambah Pustaka</button>
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
                            <th class="py-3 px-4" style="font-weight:600; border-bottom:none;">Judul Dokumen</th>
                            <th class="py-3 px-4" style="font-weight:600; border-bottom:none;">Deskripsi</th>
                            <th class="py-3 px-4" style="font-weight:600; border-bottom:none;">File</th>
                            <th class="py-3 px-4 text-center" style="font-weight:600; border-bottom:none;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($listPustaka)): ?>
                            <tr><td colspan="5" class="text-center py-4">Belum ada pustaka.</td></tr>
                        <?php else: ?>
                            <?php foreach ($listPustaka as $i => $p): ?>
                            <tr>
                                <td class="py-3 px-4 text-muted"><?= $i + 1 ?></td>
                                <td class="py-3 px-4 fw-semibold"><?= htmlspecialchars($p['nama_dokumen']) ?></td>
                                <td class="py-3 px-4 text-muted" style="max-width:300px;"><?= htmlspecialchars($p['deskripsi_dokumen']) ?></td>
                                <td class="py-3 px-4">
                                    <a href="<?= BASE_URL ?>/uploads/pustaka/<?= htmlspecialchars($p['file_path']) ?>" download class="text-decoration-none text-muted" style="font-size:0.85rem;">
                                        <i class="bi bi-file-earmark-pdf-fill text-danger me-1"></i><?= htmlspecialchars($p['file_path']) ?>
                                    </a>
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <form action="<?= BASE_URL ?>/backend/pustaka/delete.php" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus dokumen pustaka ini?');">
                                        <input type="hidden" name="id_pustaka" value="<?= $p['id_pustaka'] ?>">
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

<!-- Modal Tambah Pustaka -->
<div class="modal fade" id="modalTambahPustaka" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background:var(--color-lighter); border-bottom:1px solid var(--color-light);">
                <h5 class="modal-title fw-bold"><i class="bi bi-plus-circle me-2" style="color:var(--color-primary);"></i>Tambah Pustaka</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= BASE_URL ?>/backend/pustaka/store.php" method="POST" enctype="multipart/form-data">
                <div class="modal-body p-4">
                    <div class="mb-3"><label class="form-label fw-bold">Judul Dokumen</label><input type="text" name="nama_dokumen" class="form-control" placeholder="Contoh: Curriculum Vitae (CV)" required></div>
                    <div class="mb-3"><label class="form-label fw-bold">Deskripsi</label><textarea class="form-control" name="deskripsi_dokumen" rows="3" placeholder="Penjelasan singkat tentang dokumen ini" required></textarea></div>
                    <div class="mb-3"><label class="form-label fw-bold">Gambar Preview</label><input type="file" name="preview_dokumen" class="form-control" accept="image/jpeg,image/png,image/jpg"><div class="form-text">Opsional. Format: JPG, PNG. Maks 2MB.</div></div>
                    <div class="mb-3"><label class="form-label fw-bold">Upload File Dokumen</label><input type="file" name="file_pustaka" class="form-control" accept=".pdf" required><div class="form-text">Format: PDF. Maks 5MB.</div></div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn fw-semibold" style="background-color:var(--color-primary);border-color:var(--color-primary);color:#fff;">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Hapus Pustaka -->
<!-- (Dihapus karena konfirmasi hapus dipindah ke JS d-inline form) -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= BASE_URL ?>/assets/js/main.js"></script>
</body>
</html>
