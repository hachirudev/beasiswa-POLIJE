<?php
/**
 * Riwayat & Pesan Simulasi — Menampilkan status simulasi dan review admin
 */
declare(strict_types=1);
require_once '../../../config/app.php';
require_once CONFIG_PATH . 'Database.php';
require_once HELPERS_PATH . 'Session.php';
require_once HELPERS_PATH . 'Response.php';
require_once CLASSES_PATH . 'Simulasi.php';
require_once CLASSES_PATH . 'HasilSimulasi.php';

Session::start();
Session::requireLogin();
Session::requireRole('mahasiswa');

$db = Database::getInstance()->getConnection();
$hasilObj = new HasilSimulasi($db);
$simulasiList = (new Simulasi($db))->getByMahasiswa(Session::getId());
$hasilObj->markAllAsRead(Session::getId());

$pageTitle = 'Riwayat & Pesan Simulasi | ' . APP_NAME;
$pageDescription = 'Pantau status pengajuan simulasi beasiswa Anda dan lihat hasil review.';
$activePage = 'pesan';

// Header
require_once __DIR__ . '/../layout/header.php';

// Navbar Mahasiswa
require_once __DIR__ . '/../layout/navbar-mahasiswa.php';
?>

<!-- ========== KONTEN UTAMA ========== -->
<div class="container py-4" style="max-width: 800px;">

    <div class="page-header">
        <h1 class="page-title">Riwayat & Pesan Simulasi</h1>
        <p class="page-subtitle">Pantau status pengajuan simulasi beasiswa Anda dan lihat hasil review dari admin.
        </p>
    </div>

    <!-- List Pesan -->
    <div class="pesan-list">
        <?php if (!empty($simulasiList)): ?>
            <?php foreach ($simulasiList as $s): ?>
                <?php
                $statusClass = 'status-pending';
                $statusIcon = 'bi-clock-fill';
                $statusText = 'Menunggu Review';
                $simStatus = $s['status_simulasi'] ?? 'pending';

                if ($simStatus === 'lulus') {
                    $statusClass = 'status-lulus';
                    $statusIcon = 'bi-check-circle-fill';
                    $statusText = 'Lulus Kualifikasi';
                } elseif ($simStatus === 'tidak_lulus') {
                    $statusClass = 'status-tidak';
                    $statusIcon = 'bi-x-circle-fill';
                    $statusText = 'Tidak Memenuhi';
                }
                ?>
                <div class="pesan-card">
                    <div class="pesan-top">
                        <div class="pesan-info">
                            <h3><?= htmlspecialchars($s['nama_beasiswa']) ?></h3>
                            <div class="date"><i class="bi bi-calendar3 me-1"></i> Diajukan:
                                <?= date('d M Y', strtotime($s['tgl_submit'] ?? $s['created_at'] ?? 'now')) ?>
                                <?php if (!empty($s['tgl_review'])): ?>
                                    • Direview: <?= date('d M Y', strtotime($s['tgl_review'])) ?>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="status-badge <?= $statusClass ?>">
                            <i class="bi <?= $statusIcon ?>"></i> <?= $statusText ?>
                        </div>
                    </div>
                    <div class="pesan-body">
                        <?php if ($simStatus === 'pending'): ?>
                            <p class="text-muted mb-0">Pengajuan simulasi Anda sedang dalam antrean dan akan segera direview oleh
                                admin. Silakan cek kembali secara berkala.</p>
                        <?php else: ?>
                            <?php if ($simStatus === 'lulus'): ?>
                                <p>Selamat! Berdasarkan hasil simulasi, profil Anda memenuhi standar untuk mendaftar beasiswa ini.
                                    Silakan lanjutkan pendaftaran resmi melalui website penyelenggara.</p>
                            <?php else: ?>
                                <p>Mohon maaf, berdasarkan hasil simulasi, profil Anda belum memenuhi standar kualifikasi untuk beasiswa
                                    ini. Jangan menyerah dan coba beasiswa lainnya!</p>
                            <?php endif; ?>

                            <div class="review-box">
                                <div class="review-score <?= $simStatus === 'tidak_lulus' ? 'text-danger' : '' ?>">
                                    <?= htmlspecialchars((string) ($s['skor'] ?? '0')) ?> / 100
                                </div>
                                <div class="review-score-label <?= $simStatus === 'tidak_lulus' ? 'text-danger' : '' ?>">Skor
                                    Kelayakan</div>
                                <p class="review-note">"<?= nl2br(htmlspecialchars($s['catatan_admin'] ?? '')) ?>" - Admin</p>
                            </div>
                        <?php endif; ?>

                        <div class="mt-3">
                            <a href="<?= BASE_URL ?>/frontend/mahasiswa/detail-riwayat.php?id=<?= $s['id_simulasi'] ?>" class="btn btn-outline-primary btn-sm fw-semibold">
                                <i class="bi bi-file-earmark-text me-1"></i> Lihat Formulir yang Diisi
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
                <p class="text-muted mt-3">Belum ada riwayat simulasi beasiswa.</p>
            </div>
        <?php endif; ?>
    </div>

</div>
<!-- ========== END KONTEN ========== -->

<?php
// Footer
require_once __DIR__ . '/../layout/footer.php';
?>