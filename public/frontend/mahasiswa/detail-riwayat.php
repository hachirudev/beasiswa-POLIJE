<?php
/**
 * Detail Riwayat Simulasi — View untuk mahasiswa melihat data yang telah disubmit
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
Session::requireRole('mahasiswa');

$idSimulasi = $_GET['id'] ?? null;
if (!$idSimulasi) {
    Session::setFlash('error', 'ID simulasi tidak valid.');
    Response::redirectTo(BASE_URL . '/frontend/mahasiswa/pesan.php');
}

$db = Database::getInstance()->getConnection();
$simulasiObj = new Simulasi($db);

$simulasi = $simulasiObj->getDetailLengkap((int) $idSimulasi);

// Pastikan simulasi ini milik mahasiswa yang sedang login
if (!$simulasi || (int) $simulasi['id_mahasiswa'] !== Session::getId()) {
    Session::setFlash('error', 'Data simulasi tidak ditemukan atau Anda tidak memiliki akses.');
    Response::redirectTo(BASE_URL . '/frontend/mahasiswa/pesan.php');
}

$hasilStatus = $simulasi['hasil']['status_simulasi'] ?? 'pending';

$pageTitle = 'Detail Data Simulasi | ' . APP_NAME;
$pageDescription = 'Detail data pengajuan simulasi beasiswa Anda.';
$activePage = 'pesan';

require_once __DIR__ . '/../layout/header.php';
require_once __DIR__ . '/../layout/navbar-mahasiswa.php';
?>

<div class="container py-4 detail-riwayat-page" style="max-width: 900px;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="page-title mb-1" style="font-size: 1.75rem; font-weight: 800;">Detail Data Pengajuan</h1>
            <div class="d-flex align-items-center gap-2 mt-2">
                <span class="status-pill <?= $hasilStatus ?>">
                    <i
                        class="bi <?= $hasilStatus === 'lulus' ? 'bi-check-circle-fill' : ($hasilStatus === 'tidak_lulus' ? 'bi-x-circle-fill' : 'bi-clock-fill') ?>"></i>
                    <?= $hasilStatus === 'lulus' ? 'Lulus Kualifikasi' : ($hasilStatus === 'tidak_lulus' ? 'Tidak Memenuhi' : 'Menunggu Review') ?>
                </span>
                <span class="text-muted" style="font-size:.85rem;">
                    Submit: <?= date('d M Y, H:i', strtotime($simulasi['tgl_submit'] ?? $simulasi['created_at'])) ?> WIB
                </span>
            </div>
        </div>
        <a href="<?= BASE_URL ?>/frontend/mahasiswa/pesan.php" class="btn btn-outline-secondary fw-semibold">
            <i class="bi bi-arrow-left me-1"></i>Kembali
        </a>
    </div>

    <div class="row g-4">
        <!-- ═══ LEFT COLUMN ═══ -->
        <div class="col-lg-6">
            <!-- Beasiswa yang Dipilih -->
            <div class="card detail-card mb-4">
                <div class="card-header-custom">
                    <h6><i class="bi bi-award me-2"></i>Beasiswa yang Dituju</h6>
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
                </div>
            </div>

            <!-- Profil Akademik Saat Pengajuan -->
            <div class="card detail-card mb-4">
                <div class="card-header-custom">
                    <h6><i class="bi bi-person-vcard me-2"></i>Profil Akademik Saat Pengajuan</h6>
                </div>
                <div class="card-body px-4 py-3">
                    <div class="info-row">
                        <span class="info-label">Program Studi</span>
                        <span class="info-value"><?= htmlspecialchars($simulasi['nama_prodi']) ?></span>
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
                            <span class="info-label">Dokumen SKTM</span>
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
                    <h6><i class="bi bi-file-text me-2"></i>Data Isian Pengajuan</h6>
                </div>
                <div class="card-body px-4 py-3">
                    <div class="data-block">
                        <div class="data-block-label"><i class="bi bi-trophy me-1"></i> Prestasi</div>
                        <div class="data-block-value"><?= nl2br(htmlspecialchars($simulasi['prestasi'] ?? '-')) ?></div>
                    </div>
                    <div class="data-block">
                        <div class="data-block-label"><i class="bi bi-chat-quote me-1"></i> Motivasi</div>
                        <div class="data-block-value"><?= nl2br(htmlspecialchars($simulasi['motivasi'] ?? '-')) ?></div>
                    </div>
                    <div class="d-flex gap-3 mt-3">
                        <div class="flex-fill">
                            <div class="data-block-label mb-1">Organisasi</div>
                            <?php if ($simulasi['ikut_organisasi']): ?>
                                <span class="bool-badge yes"><i class="bi bi-check-lg"></i> Aktif</span>
                            <?php else: ?>
                                <span class="bool-badge no"><i class="bi bi-x-lg"></i> Tidak</span>
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
                                <span class="bool-badge yes"><i class="bi bi-check-lg"></i> Ya</span>
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
                    <h6><i class="bi bi-paperclip me-2"></i>Berkas Yang Diunggah</h6>
                </div>
                <div class="card-body px-4 py-3">
                    <?php if (empty($simulasi['files'])): ?>
                        <div class="text-center py-3">
                            <i class="bi bi-folder2-open text-muted" style="font-size:2rem;"></i>
                            <p class="text-muted mb-0 mt-2" style="font-size:.85rem;">Tidak ada berkas yang diunggah.</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($simulasi['files'] as $file): ?>
                            <div class="file-item">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-file-earmark-pdf-fill file-icon"></i>
                                    <span class="file-name"><?= htmlspecialchars($file['nama_file']) ?></span>
                                </div>
                                <a href="<?= UPLOAD_URL ?>simulasi/<?= htmlspecialchars(basename($file['file_path'])) ?>" download="<?= htmlspecialchars($file['nama_file']) ?>"
                                    class="btn btn-sm btn-outline-primary" title="Download File Anda">
                                    <i class="bi bi-download"></i>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>