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

$simulasi = $simulasiObj->getDetailLengkap((int) $idSimulasi);
if (!$simulasi) {
    Session::setFlash('error', 'Data simulasi tidak ditemukan.');
    Response::redirectTo(BASE_URL . '/frontend/admin/review-simulasi.php');
}

// Sidebar counts
$beasiswaObj = new Beasiswa($db);
$pendingBeasiswa = count(array_filter($beasiswaObj->getAll(), fn($b) => ($b['status_verifikasi'] ?? '') === 'pending'));
$pendingSimulasi = count(array_filter($simulasiObj->getAll(), fn($s) => ($s['status_simulasi'] ?? '') === 'pending'));

// Determine current review status for visual indicators
$hasilStatus = $simulasi['hasil']['status_simulasi'] ?? 'pending';
$isReviewed = $hasilStatus !== 'pending';

$pageTitle = 'Detail Review Simulasi | Admin ' . APP_NAME;
$pageDescription = 'Detail dan penilaian simulasi pendaftaran beasiswa mahasiswa.';
require_once __DIR__ . '/../layout/header.php';
require_once __DIR__ . '/../layout/sidebar-admin.php';
?>


<div class="main-content-admin review-detail-page">
    <div class="topbar-admin">
        <div>
            <h1 class="page-title mb-1">Detail Review Simulasi</h1>
            <div class="d-flex align-items-center gap-2">
                <span class="status-pill <?= $hasilStatus ?>" id="current-status">
                    <i
                        class="bi <?= $hasilStatus === 'lulus' ? 'bi-check-circle-fill' : ($hasilStatus === 'tidak_lulus' ? 'bi-x-circle-fill' : 'bi-clock-fill') ?>"></i>
                    <?= $hasilStatus === 'lulus' ? 'Lulus' : ($hasilStatus === 'tidak_lulus' ? 'Tidak Lulus' : 'Menunggu Review') ?>
                </span>
                <span class="text-muted" style="font-size:.82rem;">
                    Submit: <?= date('d M Y, H:i', strtotime($simulasi['tgl_submit'] ?? $simulasi['created_at'])) ?> WIB
                </span>
            </div>
        </div>
        <a href="<?= BASE_URL ?>/frontend/admin/review-simulasi.php"
            class="btn btn-sm btn-outline-secondary fw-semibold">
            <i class="bi bi-arrow-left me-1"></i>Kembali
        </a>
    </div>

    <div class="p-4">
        <div class="row g-4">
            <!-- ═══ LEFT COLUMN ═══ -->
            <div class="col-lg-6">

                <!-- Informasi Mahasiswa -->
                <div class="card detail-card mb-4">
                    <div class="card-header-custom">
                        <h6><i class="bi bi-person-vcard me-2"></i>Informasi Mahasiswa</h6>
                    </div>
                    <div class="card-body px-4 py-3">
                        <div class="info-row">
                            <span class="info-label">Nama Lengkap</span>
                            <span class="info-value"><?= htmlspecialchars($simulasi['nama']) ?></span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">NIM</span>
                            <span class="info-value"><?= htmlspecialchars($simulasi['NIM']) ?></span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Program Studi</span>
                            <span class="info-value"><?= htmlspecialchars($simulasi['nama_prodi']) ?></span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Jurusan</span>
                            <span class="info-value"><?= htmlspecialchars($simulasi['nama_jurusan']) ?></span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Semester</span>
                            <span class="info-value"><?= htmlspecialchars((string) $simulasi['semester']) ?></span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">IPK</span>
                            <span class="info-value">
                                <span class="bool-badge <?= (float) $simulasi['IPK'] >= 3.0 ? 'yes' : 'no' ?>">
                                    <?= htmlspecialchars((string) $simulasi['IPK']) ?>
                                </span>
                            </span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Email</span>
                            <span class="info-value"><?= htmlspecialchars($simulasi['email_mahasiswa']) ?></span>
                        </div>
                    </div>
                </div>

                <!-- Beasiswa yang Dipilih -->
                <div class="card detail-card mb-4">
                    <div class="card-header-custom">
                        <h6><i class="bi bi-award me-2"></i>Beasiswa yang Dipilih</h6>
                    </div>
                    <div class="card-body px-4 py-3">
                        <div class="info-row">
                            <span class="info-label">Nama Beasiswa</span>
                            <span class="info-value"><?= htmlspecialchars($simulasi['nama_beasiswa']) ?></span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Penyelenggara</span>
                            <span class="info-value"><?= htmlspecialchars($simulasi['nama_penyelenggara']) ?></span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Tanggal Submit</span>
                            <span
                                class="info-value"><?= date('d M Y, H:i', strtotime($simulasi['tgl_submit'] ?? $simulasi['created_at'])) ?>
                                WIB</span>
                        </div>
                    </div>
                </div>

                <!-- Data Orang Tua -->
                <div class="card detail-card mb-4">
                    <div class="card-header-custom">
                        <h6><i class="bi bi-people me-2"></i>Data Orang Tua</h6>
                    </div>
                    <div class="card-body px-4 py-3">
                        <?php if (!empty($simulasi['orang_tua'])): ?>
                            <div class="info-row">
                                <span class="info-label">Nama Orang Tua</span>
                                <span
                                    class="info-value"><?= htmlspecialchars($simulasi['orang_tua']['nama_ortu'] ?? '-') ?></span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Pekerjaan</span>
                                <span
                                    class="info-value"><?= htmlspecialchars($simulasi['orang_tua']['pekerjaan_ortu'] ?? '-') ?></span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Penghasilan</span>
                                <span class="info-value">Rp
                                    <?= number_format((float) ($simulasi['orang_tua']['penghasilan_ortu'] ?? 0), 0, ',', '.') ?>/bulan</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Jml. Tanggungan</span>
                                <span
                                    class="info-value"><?= htmlspecialchars((string) ($simulasi['orang_tua']['jml_tanggungan'] ?? 0)) ?>
                                    orang</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">SKTM</span>
                                <span class="info-value">
                                    <?php if (!empty($simulasi['orang_tua']['sktm'])): ?>
                                        <span class="bool-badge yes"><i class="bi bi-check-lg"></i> Memiliki</span>
                                    <?php else: ?>
                                        <span class="bool-badge no"><i class="bi bi-x-lg"></i> Tidak Ada</span>
                                    <?php endif; ?>
                                </span>
                            </div>
                        <?php else: ?>
                            <p class="text-muted mb-0">Data orang tua tidak tersedia.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- ═══ RIGHT COLUMN ═══ -->
            <div class="col-lg-6">

                <!-- Data Tambahan Simulasi -->
                <div class="card detail-card mb-4">
                    <div class="card-header-custom">
                        <h6><i class="bi bi-file-text me-2"></i>Data Tambahan Simulasi</h6>
                    </div>
                    <div class="card-body px-4 py-3">
                        <div class="data-block">
                            <div class="data-block-label"><i class="bi bi-trophy me-1"></i> Prestasi</div>
                            <div class="data-block-value"><?= nl2br(htmlspecialchars($simulasi['prestasi'] ?? '-')) ?>
                            </div>
                        </div>
                        <div class="data-block">
                            <div class="data-block-label"><i class="bi bi-chat-quote me-1"></i> Motivasi</div>
                            <div class="data-block-value"><?= nl2br(htmlspecialchars($simulasi['motivasi'] ?? '-')) ?>
                            </div>
                        </div>
                        <div class="d-flex gap-3 mt-3">
                            <div class="flex-fill">
                                <div class="data-block-label mb-1">Organisasi</div>
                                <?php if ($simulasi['ikut_organisasi']): ?>
                                    <span class="bool-badge yes"><i class="bi bi-check-lg"></i> Aktif</span>
                                <?php else: ?>
                                    <span class="bool-badge no"><i class="bi bi-x-lg"></i> Tidak Ikut</span>
                                <?php endif; ?>
                            </div>
                            <div class="flex-fill">
                                <div class="data-block-label mb-1">Beasiswa Lain</div>
                                <?php if ($simulasi['status_beasiswa_lain']): ?>
                                    <span class="bool-badge no"><i class="bi bi-exclamation-triangle"></i> Menerima</span>
                                <?php else: ?>
                                    <span class="bool-badge yes"><i class="bi bi-check-lg"></i> Tidak</span>
                                <?php endif; ?>
                            </div>
                            <div class="flex-fill">
                                <div class="data-block-label mb-1">Aktif Kuliah</div>
                                <?php if ($simulasi['aktif_kuliah']): ?>
                                    <span class="bool-badge yes"><i class="bi bi-check-lg"></i> Aktif</span>
                                <?php else: ?>
                                    <span class="bool-badge no"><i class="bi bi-x-lg"></i> Tidak</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Dokumen Upload -->
                <div class="card detail-card mb-4">
                    <div class="card-header-custom">
                        <h6><i class="bi bi-paperclip me-2"></i>Dokumen yang Diunggah</h6>
                    </div>
                    <div class="card-body px-4 py-3">
                        <?php if (empty($simulasi['files'])): ?>
                            <div class="text-center py-3">
                                <i class="bi bi-folder2-open text-muted" style="font-size:2rem;"></i>
                                <p class="text-muted mb-0 mt-2" style="font-size:.85rem;">Tidak ada dokumen yang diupload.
                                </p>
                            </div>
                        <?php else: ?>
                            <?php foreach ($simulasi['files'] as $file): ?>
                                <div class="file-item">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-file-earmark-pdf-fill file-icon"></i>
                                        <span class="file-name"><?= htmlspecialchars($file['nama_file']) ?></span>
                                    </div>
                                    <a href="<?= UPLOAD_URL ?>simulasi/<?= htmlspecialchars(basename($file['file_path'])) ?>" download="<?= htmlspecialchars($file['nama_file']) ?>"
                                        class="btn btn-sm btn-outline-primary" title="Download">
                                        <i class="bi bi-download"></i>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- ═══ REVIEW FORM ═══ -->
                <div class="card review-form-card">
                    <div class="review-form-header">
                        <h6><i class="bi bi-pencil-square me-2"></i>Form Review Admin</h6>
                    </div>
                    <div class="card-body p-4">
                        <form action="<?= BASE_URL ?>/backend/hasil-simulasi/update.php" method="POST" id="form-review">
                            <input type="hidden" name="id_simulasi" value="<?= $simulasi['id_simulasi'] ?>">

                            <!-- Skor -->
                            <div class="mb-3">
                                <label class="form-label fw-bold">Skor Penilaian</label>
                                <div class="skor-display">
                                    <input type="number" name="skor" id="skor-input" class="form-control" min="0"
                                        max="100" step="0.5"
                                        value="<?= htmlspecialchars((string) ($simulasi['hasil']['skor'] ?? '')) ?>"
                                        placeholder="0" required>
                                    <input type="range" id="skor-range" min="0" max="100" step="0.5"
                                        value="<?= (float) ($simulasi['hasil']['skor'] ?? 0) ?>">
                                    <span style="font-size:.85rem;font-weight:600;color:var(--color-text);">/ 100</span>
                                </div>
                                <div class="skor-label-text">Geser slider atau ketik langsung untuk menentukan skor
                                    kelayakan.</div>
                            </div>

                            <!-- Catatan -->
                            <div class="mb-3">
                                <label class="form-label fw-bold">Catatan Review</label>
                                <textarea class="form-control" name="catatan_admin" rows="4"
                                    placeholder="Tuliskan catatan atau feedback untuk mahasiswa..."
                                    required><?= htmlspecialchars($simulasi['hasil']['catatan_admin'] ?? '') ?></textarea>
                            </div>

                            <!-- Status -->
                            <div class="mb-4">
                                <label class="form-label fw-bold">Keputusan Status</label>
                                <select class="form-select" name="status_simulasi" id="status-select" required>
                                    <option value="" disabled <?= $hasilStatus === 'pending' ? 'selected' : '' ?>>— Pilih
                                        Status —</option>
                                    <option value="lulus" <?= $hasilStatus === 'lulus' ? 'selected' : '' ?>>✅ Lulus
                                        Kualifikasi</option>
                                    <option value="tidak_lulus" <?= $hasilStatus === 'tidak_lulus' ? 'selected' : '' ?>>❌
                                        Tidak Lulus</option>
                                </select>
                            </div>

                            <!-- Buttons -->
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-submit-review flex-fill">
                                    <i class="bi bi-check-circle-fill me-1"></i>Simpan Review
                                </button>
                                <a href="<?= BASE_URL ?>/frontend/admin/review-simulasi.php"
                                    class="btn btn-light fw-semibold px-4">Batal</a>
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
<script>
    // Sync skor slider <-> number input
    (function () {
        var numInput = document.getElementById('skor-input');
        var rangeInput = document.getElementById('skor-range');
        if (!numInput || !rangeInput) return;

        numInput.addEventListener('input', function () {
            rangeInput.value = this.value || 0;
        });
        rangeInput.addEventListener('input', function () {
            numInput.value = this.value;
        });
    })();
</script>
</body>

</html>