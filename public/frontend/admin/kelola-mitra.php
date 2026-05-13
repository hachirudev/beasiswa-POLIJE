<?php
/**
 * Kelola Mitra Admin — CRUD Mitra
 */
declare(strict_types=1);
require_once '../../../config/app.php';
require_once CONFIG_PATH . 'Database.php';
require_once HELPERS_PATH . 'Session.php';
require_once HELPERS_PATH . 'Response.php';
require_once CLASSES_PATH . 'Mitra.php';

Session::start();
Session::requireLogin();
Session::requireRole('admin');

$db = Database::getInstance()->getConnection();
$mitraObj = new Mitra($db);
$listMitra = $mitraObj->getAll();

require_once CLASSES_PATH . 'Beasiswa.php';
require_once CLASSES_PATH . 'Simulasi.php';
$pendingBeasiswa = count(array_filter((new Beasiswa($db))->getAll(), fn($b) => $b['status_verifikasi'] === 'pending'));
$pendingSimulasi = count(array_filter((new Simulasi($db))->getAll(), fn($s) => $s['status_simulasi'] === 'pending'));

$pageTitle = 'Kelola Mitra — Admin ' . APP_NAME;
$pageDescription = 'Kelola daftar akun mitra penyelenggara beasiswa.';
$activePage = 'kelola-mitra';

require_once __DIR__ . '/../layout/header.php';
require_once __DIR__ . '/../layout/sidebar-admin.php';
?>
<div class="main-content-admin">
    <div class="topbar-admin">
        <h1 class="page-title">Kelola Mitra</h1>
        <button class="btn btn-sm fw-semibold" style="background-color:var(--color-primary);border-color:var(--color-primary);color:#fff;" data-bs-toggle="modal" data-bs-target="#modalTambahMitra"><i class="bi bi-plus-lg me-1"></i>Tambah Mitra</button>
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
                            <th class="py-3 px-4" style="font-weight:600; border-bottom:none;">Nama Mitra</th>
                            <th class="py-3 px-4" style="font-weight:600; border-bottom:none;">Bidang Usaha</th>
                            <th class="py-3 px-4" style="font-weight:600; border-bottom:none;">Email</th>
                            <th class="py-3 px-4" style="font-weight:600; border-bottom:none;">Telepon</th>
                            <th class="py-3 px-4" style="font-weight:600; border-bottom:none;">Website</th>
                            <th class="py-3 px-4 text-center" style="font-weight:600; border-bottom:none;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($listMitra)): ?>
                            <tr><td colspan="7" class="text-center py-4">Belum ada mitra terdaftar.</td></tr>
                        <?php else: ?>
                            <?php foreach ($listMitra as $i => $m): ?>
                            <tr>
                                <td class="py-3 px-4 text-muted"><?= $i + 1 ?></td>
                                <td class="py-3 px-4">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width:34px;height:34px;background:var(--color-lighter);color:var(--color-primary);font-size:0.9rem;flex-shrink:0;"><i class="bi bi-building"></i></div>
                                        <span class="fw-semibold"><?= htmlspecialchars($m['nama_mitra']) ?></span>
                                    </div>
                                </td>
                                <td class="py-3 px-4 text-muted"><?= htmlspecialchars($m['bidang_usaha']) ?></td>
                                <td class="py-3 px-4 text-muted"><?= htmlspecialchars($m['email']) ?></td>
                                <td class="py-3 px-4 text-muted"><?= htmlspecialchars($m['no_telepon'] ?? '-') ?></td>
                                <td class="py-3 px-4">
                                    <?php if ($m['website']): ?>
                                    <a href="<?= htmlspecialchars($m['website']) ?>" target="_blank" style="color:var(--color-primary);text-decoration:none;font-size:0.9rem;">
                                        <?= htmlspecialchars(parse_url($m['website'], PHP_URL_HOST) ?? $m['website']) ?>
                                    </a>
                                    <?php else: ?>
                                    -
                                    <?php endif; ?>
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <form action="<?= BASE_URL ?>/backend/akun/delete-mitra.php" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun mitra ini?\n\nPerhatian: Semua beasiswa yang diunggah oleh mitra ini juga akan terpengaruh. Tindakan ini tidak dapat dibatalkan.');">
                                        <input type="hidden" name="id_mitra" value="<?= $m['id_mitra'] ?>">
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

<!-- Modal Tambah Mitra -->
<div class="modal fade" id="modalTambahMitra" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background:var(--color-lighter); border-bottom:1px solid var(--color-light);">
                <h5 class="modal-title fw-bold"><i class="bi bi-plus-circle me-2" style="color:var(--color-primary);"></i>Tambah Akun Mitra</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= BASE_URL ?>/backend/akun/store-mitra.php" method="POST">
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6"><label class="form-label fw-bold">Nama Mitra</label><input type="text" name="nama_mitra" class="form-control" placeholder="Contoh: PT Djarum" required></div>
                        <div class="col-md-6"><label class="form-label fw-bold">Bidang Usaha</label><input type="text" name="bidang_usaha" class="form-control" placeholder="Contoh: Manufaktur / FMCG" required></div>
                        <div class="col-md-6"><label class="form-label fw-bold">Email</label><input type="email" name="email" class="form-control" placeholder="Contoh: info@perusahaan.com" required></div>
                        <div class="col-md-6"><label class="form-label fw-bold">Telepon</label><input type="tel" name="no_telepon" class="form-control" placeholder="Contoh: +62 21 1234 5678" required></div>
                        <div class="col-md-6"><label class="form-label fw-bold">Website</label><input type="url" name="website" class="form-control" placeholder="Contoh: https://perusahaan.com"></div>
                        <div class="col-md-6"><label class="form-label fw-bold">Password</label><input type="password" name="password" class="form-control" placeholder="Minimal 8 karakter" required><div class="form-text">Password awal yang akan digunakan mitra untuk login.</div></div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn fw-semibold" style="background-color:var(--color-primary);border-color:var(--color-primary);color:#fff;"><i class="bi bi-person-plus-fill me-1"></i>Buat Akun Mitra</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Hapus Mitra (dipindah ke js confirm) -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= BASE_URL ?>/assets/js/main.js"></script>
</body>
</html>
