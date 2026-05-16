<?php
/**
 * Detail Beasiswa — Halaman detail informasi beasiswa
 */
declare(strict_types=1);
require_once '../../config/app.php';
require_once CONFIG_PATH . 'Database.php';
require_once HELPERS_PATH . 'Session.php';
require_once HELPERS_PATH . 'Response.php';
require_once CLASSES_PATH . 'Beasiswa.php';

Session::start();
Session::requireLogin();
Session::requireRole('mahasiswa');

$db = Database::getInstance()->getConnection();
$id = (int) ($_GET['id'] ?? 0);
$beasiswa = (new Beasiswa($db))->getWithTags($id);

if (!$beasiswa) {
    Response::redirectTo(BASE_URL . '/frontend/mahasiswa/beranda.php');
}

$pageTitle = htmlspecialchars($beasiswa['nama_beasiswa']) . ' | ' . APP_NAME;
$pageDescription = 'Detail informasi beasiswa yang tersedia di Politeknik Negeri Jember.';
$activePage = 'beranda';

// Header
require_once __DIR__ . '/../layout/header.php';

// Navbar Mahasiswa
require_once __DIR__ . '/../layout/navbar-mahasiswa.php';
?>

<!-- ========== KONTEN UTAMA ========== -->
<div class="container py-4">
    <!-- Combined detail card -->
    <div class="detail-card">
        <!-- Top: poster + info -->
        <div class="detail-top">
            <div class="detail-poster">
                <?php $poster = !empty($beasiswa['poster_url']) ? BASE_URL . '/uploads/poster/' . $beasiswa['poster_url'] : BASE_URL . '/assets/img/poster-beasiswa.png'; ?>
                <img src="<?= htmlspecialchars($poster) ?>" alt="Poster Beasiswa">
            </div>
            <div class="detail-info">
                <h1><?= htmlspecialchars($beasiswa['nama_beasiswa']) ?></h1>
                <p class="penyelenggara"><i
                        class="bi bi-building me-1"></i><?= htmlspecialchars($beasiswa['nama_penyelenggara'] ?? 'Penyelenggara') ?>
                </p>
                <p class="desc"><?= nl2br(htmlspecialchars($beasiswa['deskripsi_singkat'])) ?></p>
                <div>
                    <?php if (!empty($beasiswa['tags'])): ?>
                        <?php foreach ($beasiswa['tags'] as $tag): ?>
                            <span class="tag-badge tag-jenjang"><?= htmlspecialchars($tag['nama_tag']) ?></span>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <p class="date-range"><i
                        class="bi bi-calendar3 me-1"></i><?= date('d M Y', strtotime($beasiswa['tgl_buka'] ?? 'now')) ?>
                    &ndash; <?= date('d M Y', strtotime($beasiswa['tgl_tutup'] ?? 'now')) ?></p>
            </div>
        </div>

        <!-- Persyaratan & Keuntungan — digabung dalam satu card -->
        <div class="section-divider"></div>
        <div class="row g-4 justify-content-center">
            <div class="col-md-12">
                <h5 class="fw-bold mb-3">Deskripsi Lengkap</h5>
                <div class="desc mb-4 text-dark"><?= $beasiswa['deskripsi_lengkap'] ?? '' ?></div>

                <h5 class="fw-bold mb-3">Informasi Beasiswa</h5>
                <div class="desc text-dark"><?= $beasiswa['informasi_beasiswa'] ?? '' ?></div>
            </div>
        </div>

        <!-- Action buttons -->
        <div class="section-divider"></div>
        <div class="action-buttons">
            <a href="<?= BASE_URL ?>/frontend/mahasiswa/simulasi.php?id_beasiswa=<?= $beasiswa['id_beasiswa'] ?>"
                class="btn-simulasi" id="btn-simulasi">
                <i class="bi bi-clipboard2-check me-1"></i>Simulasi Pendaftaran Beasiswa
            </a>
            <?php if (!empty($beasiswa['link_pendaftaran'])): ?>
                <a href="<?= htmlspecialchars($beasiswa['link_pendaftaran']) ?>" class="btn-daftar" id="btn-daftar-beasiswa"
                    target="_blank">
                    <i class="bi bi-box-arrow-up-right me-1"></i>Daftar Beasiswa
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>
<!-- ========== END KONTEN ========== -->

<?php
// Footer
require_once __DIR__ . '/../layout/footer.php';
?>