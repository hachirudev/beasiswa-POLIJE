<?php
/**
 * Detail Review Simulasi — Review individual mahasiswa
 */
declare(strict_types=1);
require_once '../../../config/app.php';
require_once CONFIG_PATH . 'Database.php';
require_once HELPERS_PATH . 'Session.php';
require_once HELPERS_PATH . 'Response.php';
require_once CLASSES_PATH . 'Simulasi.php';
require_once CLASSES_PATH . 'Beasiswa.php';

Session::start();
Session::requireLogin();
Session::requireRole('admin');

$idSimulasi = $_GET['id'] ?? null;
if (!$idSimulasi) {
    Session::setFlash('error', 'ID simulasi tidak valid.');
    Response::redirectTo(BASE_URL . '/frontend/admin/review-simulasi.php');
}

$db = Database::getInstance()->getConnection();
$simulasiObj = new Simulasi($db);

$simulasi = $simulasiObj->getDetailLengkap((int)$idSimulasi);
if (!$simulasi) {
    Session::setFlash('error', 'Data simulasi tidak ditemukan.');
    Response::redirectTo(BASE_URL . '/frontend/admin/review-simulasi.php');
}

// Sidebar counts
$beasiswaObj = new Beasiswa($db);
$pendingBeasiswa = count(array_filter($beasiswaObj->getAll(), fn($b) => $b['status'] === 'pending'));
$pendingSimulasi = count(array_filter($simulasiObj->getAll(), fn($s) => $s['status_simulasi'] === 'pending'));

$pageTitle = 'Detail Review Simulasi — Admin ' . APP_NAME;
$pageDescription = 'Detail dan penilaian simulasi pendaftaran beasiswa mahasiswa.';
require_once __DIR__ . '/../layout/header.php';
require_once __DIR__ . '/../layout/sidebar-admin.php';
?>
<div class="main-content-admin">
    <div class="topbar-admin">
        <h1 class="page-title">Detail Review Simulasi</h1>
        <a href="<?= BASE_URL ?>/frontend/admin/review-simulasi.php" class="btn btn-sm btn-outline-secondary fw-semibold"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
    </div>
    <div class="p-4">
        <div class="row g-4">
            <div class="col-lg-6">
                <div class="card detail-card mb-4">
                    <div class="card-header-custom"><h6><i class="bi bi-person-vcard me-2"></i>Informasi Mahasiswa</h6></div>
                    <div class="card-body px-4 py-3">
                        <div class="info-row"><span class="info-label">Nama Lengkap</span><span class="info-value"><?= htmlspecialchars($simulasi['nama']) ?></span></div>
                        <div class="info-row"><span class="info-label">NIM</span><span class="info-value"><?= htmlspecialchars($simulasi['NIM']) ?></span></div>
                        <div class="info-row"><span class="info-label">Program Studi</span><span class="info-value"><?= htmlspecialchars($simulasi['nama_prodi']) ?></span></div>
                        <div class="info-row"><span class="info-label">Semester</span><span class="info-value"><?= htmlspecialchars((string)$simulasi['semester']) ?></span></div>
                        <div class="info-row"><span class="info-label">IPK</span><span class="info-value"><?= htmlspecialchars((string)$simulasi['IPK']) ?></span></div>
                        <div class="info-row"><span class="info-label">Email</span><span class="info-value"><?= htmlspecialchars($simulasi['email_mahasiswa']) ?></span></div>
                    </div>
                </div>
                <div class="card detail-card mb-4">
                    <div class="card-header-custom"><h6><i class="bi bi-award me-2"></i>Beasiswa yang Dipilih</h6></div>
                    <div class="card-body px-4 py-3">
                        <div class="info-row"><span class="info-label">Nama Beasiswa</span><span class="info-value"><?= htmlspecialchars($simulasi['nama_beasiswa']) ?></span></div>
                        <div class="info-row"><span class="info-label">Penyelenggara</span><span class="info-value"><?= htmlspecialchars($simulasi['nama_penyelenggara']) ?></span></div>
                        <div class="info-row"><span class="info-label">Tanggal Submit</span><span class="info-value"><?= date('d M Y, H:i', strtotime($simulasi['tgl_submit'])) ?> WIB</span></div>
                    </div>
                </div>
                <div class="card detail-card mb-4">
                    <div class="card-header-custom"><h6><i class="bi bi-people me-2"></i>Data Orang Tua</h6></div>
                    <div class="card-body px-4 py-3">
                        <div class="info-row"><span class="info-label">Nama Ayah</span><span class="info-value"><?= htmlspecialchars(explode(' | ', $simulasi['orang_tua']['nama_ortu'])[0] ?? '-') ?></span></div>
                        <div class="info-row"><span class="info-label">Pekerjaan Ayah</span><span class="info-value"><?= htmlspecialchars(explode(' | ', $simulasi['orang_tua']['pekerjaan_ortu'])[0] ?? '-') ?></span></div>
                        <div class="info-row"><span class="info-label">Penghasilan Ortu</span><span class="info-value">Rp <?= number_format((float)$simulasi['orang_tua']['penghasilan_ortu'], 0, ',', '.') ?>/bulan</span></div>
                        <div class="info-row"><span class="info-label">Nama Ibu</span><span class="info-value"><?= htmlspecialchars(explode(' | ', $simulasi['orang_tua']['nama_ortu'])[1] ?? '-') ?></span></div>
                        <div class="info-row"><span class="info-label">Pekerjaan Ibu</span><span class="info-value"><?= htmlspecialchars(explode(' | ', $simulasi['orang_tua']['pekerjaan_ortu'])[1] ?? '-') ?></span></div>
                        <div class="info-row"><span class="info-label">Tanggungan</span><span class="info-value"><?= htmlspecialchars((string)$simulasi['orang_tua']['jml_tanggungan']) ?> orang</span></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card detail-card mb-4">
                    <div class="card-header-custom"><h6><i class="bi bi-file-text me-2"></i>Data Tambahan Simulasi</h6></div>
                    <div class="card-body px-4 py-3">
                        <div class="info-row"><span class="info-label">Prestasi</span><span class="info-value"><?= nl2br(htmlspecialchars($simulasi['prestasi'])) ?></span></div>
                        <div class="info-row"><span class="info-label">Organisasi</span><span class="info-value"><?= nl2br(htmlspecialchars($simulasi['ikut_organisasi'] ? 'Pernah / Sedang ikut' : 'Tidak ikut')) ?></span></div>
                        <div class="info-row"><span class="info-label">Motivasi</span><span class="info-value"><?= nl2br(htmlspecialchars($simulasi['motivasi'])) ?></span></div>
                        <div class="info-row"><span class="info-label">Status Beasiswa Lain</span><span class="info-value"><?= $simulasi['status_beasiswa_lain'] ? 'Menerima' : 'Tidak Menerima' ?></span></div>
                    </div>
                </div>
                <div class="card detail-card mb-4">
                    <div class="card-header-custom"><h6><i class="bi bi-paperclip me-2"></i>Dokumen yang Diupload</h6></div>
                    <div class="card-body px-4 py-3">
                        <?php if (empty($simulasi['files'])): ?>
                            <p class="text-muted mb-0">Tidak ada dokumen.</p>
                        <?php else: ?>
                            <?php foreach ($simulasi['files'] as $file): ?>
                            <div class="d-flex align-items-center justify-content-between py-2 border-bottom">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bi bi-file-earmark-pdf-fill text-danger" style="font-size:1.3rem;"></i>
                                    <div><div class="fw-semibold" style="font-size:0.9rem;"><?= htmlspecialchars($file['nama_file']) ?></div></div>
                                </div>
                                <a href="<?= BASE_URL ?>/uploads/dokumen/<?= htmlspecialchars($file['file_path']) ?>" download class="btn btn-sm btn-outline-secondary"><i class="bi bi-download"></i></a>
                            </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="card detail-card border-2" style="border-color: var(--color-primary) !important;">
                    <div class="card-header-custom" style="background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark));">
                        <h6 style="color:#fff;"><i class="bi bi-pencil-square me-2"></i>Form Review Admin</h6>
                    </div>
                    <div class="card-body p-4">
                        <form action="<?= BASE_URL ?>/backend/hasil-simulasi/update.php" method="POST">
                            <input type="hidden" name="id" value="<?= $simulasi['hasil']['id_hasil'] ?>">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Skor Penilaian (0 - 100)</label>
                                <input type="number" name="skor" class="form-control" min="0" max="100" placeholder="Masukkan skor penilaian" value="<?= htmlspecialchars((string)($simulasi['hasil']['skor'] ?? '')) ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Catatan Review</label>
                                <textarea class="form-control" name="catatan" rows="4" placeholder="Tuliskan catatan atau feedback untuk mahasiswa..." required><?= htmlspecialchars($simulasi['hasil']['catatan'] ?? '') ?></textarea>
                            </div>
                            <div class="mb-4">
                                <label class="form-label fw-bold">Keputusan Status</label>
                                <select class="form-select" name="status" required>
                                    <option value="" disabled <?= empty($simulasi['hasil']['status_simulasi']) || $simulasi['hasil']['status_simulasi'] === 'pending' ? 'selected' : '' ?>>— Pilih Status —</option>
                                    <option value="lulus" <?= ($simulasi['hasil']['status_simulasi'] ?? '') === 'lulus' ? 'selected' : '' ?>>Lulus</option>
                                    <option value="tidak_lulus" <?= ($simulasi['hasil']['status_simulasi'] ?? '') === 'tidak_lulus' ? 'selected' : '' ?>>Tidak Lulus</option>
                                </select>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn fw-semibold flex-fill" style="background-color:var(--color-primary);border-color:var(--color-primary);color:#fff;">
                                    <i class="bi bi-check-circle-fill me-1"></i>Simpan Review
                                </button>
                                <a href="<?= BASE_URL ?>/frontend/admin/review-simulasi.php" class="btn btn-light fw-semibold px-4">Batal</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= BASE_URL ?>/assets/js/main.js"></script>
</body>
</html>
