<?php
/**
 * Dashboard Admin — Halaman utama admin
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

// Hitung statistik
$totalBeasiswa = count($listBeasiswa);
$dibuka = count(array_filter($listBeasiswa, fn($b) => $b['status_pendaftaran_computed'] === 'dibuka'));
$belumDibuka = count(array_filter($listBeasiswa, fn($b) => $b['status_pendaftaran_computed'] === 'belum_dibuka'));
$ditutup = count(array_filter($listBeasiswa, fn($b) => $b['status_pendaftaran_computed'] === 'ditutup'));

$pendingBeasiswa = count(array_filter($listBeasiswa, fn($b) => $b['status_verifikasi'] === 'pending'));
$pendingSimulasi = count(array_filter($listSimulasi, fn($s) => $s['status_simulasi'] === 'pending'));

$latestBeasiswa = array_slice($listBeasiswa, 0, 5);

$pageTitle = 'Dashboard Admin | ' . APP_NAME;
$pageDescription = 'Panel kontrol administratif ' . APP_NAME . '.';
$activePage = 'dashboard';

// Header (DOCTYPE, head, body open)
require_once __DIR__ . '/../layout/header.php';

// Sidebar Admin
require_once __DIR__ . '/../layout/sidebar-admin.php';
?>

<!-- ========== KONTEN UTAMA ========== -->
<div class="main-content-admin">
    <div class="topbar-admin">
        <h1 class="page-title">Dashboard</h1>
        <span class="text-muted" style="font-size: 0.85rem"><i class="bi bi-calendar3 me-1"></i><?= date('d M Y') ?></span>
    </div>

    <div class="p-4">
        <!-- Baris 1: Statistik Beasiswa -->
        <h6 class="fw-bold text-uppercase mb-3" style="font-size: 0.78rem; letter-spacing: 0.05em; color: var(--color-text-muted);">Statistik Beasiswa</h6>
        <div class="row g-3 mb-4">
            <div class="col-md-6 col-lg-3">
                <div class="card stat-card shadow-sm">
                    <div class="card-body d-flex align-items-center gap-3 p-3">
                        <div class="stat-icon" style="background: var(--color-lighter); color: var(--color-primary);"><i class="bi bi-award-fill"></i></div>
                        <div>
                            <div class="stat-value"><?= $totalBeasiswa ?></div>
                            <div class="stat-label">Total Beasiswa</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="card stat-card shadow-sm">
                    <div class="card-body d-flex align-items-center gap-3 p-3">
                        <div class="stat-icon" style="background: #e8f5e9; color: #28a745;"><i class="bi bi-door-open-fill"></i></div>
                        <div>
                            <div class="stat-value"><?= $dibuka ?></div>
                            <div class="stat-label">Pendaftaran Dibuka</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="card stat-card shadow-sm">
                    <div class="card-body d-flex align-items-center gap-3 p-3">
                        <div class="stat-icon" style="background: #fff3cd; color: #ffc107;"><i class="bi bi-clock-fill"></i></div>
                        <div>
                            <div class="stat-value"><?= $belumDibuka ?></div>
                            <div class="stat-label">Pendaftaran Belum Dibuka</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="card stat-card shadow-sm">
                    <div class="card-body d-flex align-items-center gap-3 p-3">
                        <div class="stat-icon" style="background: #f8d7da; color: #dc3545;"><i class="bi bi-door-closed-fill"></i></div>
                        <div>
                            <div class="stat-value"><?= $ditutup ?></div>
                            <div class="stat-label">Pendaftaran Ditutup</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Baris 2: Tindakan Pending -->
        <h6 class="fw-bold text-uppercase mb-3" style="font-size: 0.78rem; letter-spacing: 0.05em; color: var(--color-text-muted);">Memerlukan Tindakan</h6>
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <div class="card stat-card shadow-sm border-start border-4" style="border-color: #ffc107 !important;">
                    <div class="card-body d-flex align-items-center gap-3 p-3">
                        <div class="stat-icon" style="background: #fff3cd; color: #ffc107;"><i class="bi bi-hourglass-split"></i></div>
                        <div>
                            <div class="stat-value"><?= $pendingBeasiswa ?></div>
                            <div class="stat-label">Beasiswa Pending Verifikasi</div>
                        </div>
                        <a href="<?= BASE_URL ?>/frontend/admin/kelola-beasiswa.php" class="btn btn-sm btn-outline-warning ms-auto fw-semibold">Lihat</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card stat-card shadow-sm border-start border-4" style="border-color: #dc3545 !important;">
                    <div class="card-body d-flex align-items-center gap-3 p-3">
                        <div class="stat-icon" style="background: #f8d7da; color: #dc3545;"><i class="bi bi-clipboard2-check-fill"></i></div>
                        <div>
                            <div class="stat-value"><?= $pendingSimulasi ?></div>
                            <div class="stat-label">Simulasi Pending Review</div>
                        </div>
                        <a href="<?= BASE_URL ?>/frontend/admin/review-simulasi.php" class="btn btn-sm btn-outline-danger ms-auto fw-semibold">Lihat</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel Beasiswa Terbaru -->
        <h6 class="fw-bold text-uppercase mb-3" style="font-size: 0.78rem; letter-spacing: 0.05em; color: var(--color-text-muted);">Beasiswa Terbaru</h6>
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead style="background: var(--color-lighter);">
                        <tr>
                            <th class="py-3 px-4" style="font-weight:600; border-bottom:none;">Nama Beasiswa</th>
                            <th class="py-3 px-4" style="font-weight:600; border-bottom:none;">Penyelenggara</th>
                            <th class="py-3 px-4" style="font-weight:600; border-bottom:none;">Status Verifikasi</th>
                            <th class="py-3 px-4" style="font-weight:600; border-bottom:none;">Tanggal Upload</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($latestBeasiswa)): ?>
                            <tr><td colspan="4" class="text-center py-4">Belum ada beasiswa.</td></tr>
                        <?php else: ?>
                            <?php foreach ($latestBeasiswa as $b): ?>
                            <tr>
                                <td class="py-3 px-4 fw-semibold"><?= htmlspecialchars($b['nama_beasiswa']) ?></td>
                                <td class="py-3 px-4 text-muted"><?= htmlspecialchars($b['nama_penyelenggara'] ?? 'Penyelenggara') ?></td>
                                <td class="py-3 px-4">
                                    <?php if ($b['status_verifikasi'] === 'terverifikasi'): ?>
                                        <span class="badge" style="background:#e8f5e9;color:#28a745;padding:.45em .7em;">Terverifikasi</span>
                                    <?php elseif ($b['status_verifikasi'] === 'ditolak'): ?>
                                        <span class="badge" style="background:#f8d7da;color:#721c24;padding:.45em .7em;">Ditolak</span>
                                    <?php else: ?>
                                        <span class="badge" style="background:#fff3cd;color:#856404;padding:.45em .7em;">Pending</span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-3 px-4 text-muted"><?= date('d M Y', strtotime($b['upload_at'] ?? 'now')) ?></td>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= BASE_URL ?>/assets/js/main.js"></script>
</body>
</html>
