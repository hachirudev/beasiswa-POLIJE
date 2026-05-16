<!-- ========== NAVBAR MAHASISWA ========== -->
<?php
if (!isset($db)) {
    require_once CONFIG_PATH . 'Database.php';
    $db = Database::getInstance()->getConnection();
}
if (!class_exists('HasilSimulasi')) {
    require_once CLASSES_PATH . 'HasilSimulasi.php';
}
$jumlahNotif = (new HasilSimulasi($db))->countUnread((int)($_SESSION['id'] ?? 0));
$userName = $_SESSION['nama'] ?? 'Mahasiswa';
?>
<nav class="navbar navbar-expand-lg navbar-beasiswa sticky-top" id="navbar-mahasiswa">
    <div class="container">
        <!-- Brand -->
        <a class="navbar-brand d-flex align-items-center gap-2" href="<?= BASE_URL ?>/frontend/mahasiswa/beranda.php#beranda" style="padding: 0;">
            <img src="<?= BASE_URL ?>/assets/img/logo polije.png" alt="Logo" style="height: 40px; width: auto; object-fit: contain;">
            <span style="font-weight: 800; color: var(--color-primary); font-size: 1.25rem; letter-spacing: -0.5px;">Beasiswa POLIJE</span>
        </a>

        <!-- Mobile toggler -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMahasiswa"
            aria-controls="navbarMahasiswa" aria-expanded="false" aria-label="Toggle navigasi">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Nav links -->
        <div class="collapse navbar-collapse" id="navbarMahasiswa">
            <ul class="navbar-nav ms-auto me-3 align-items-lg-center">
                <li class="nav-item">
                    <a class="nav-link <?= ($activePage ?? '') === 'beranda' ? 'active' : '' ?>" href="<?= BASE_URL ?>/frontend/mahasiswa/beranda.php#beranda">Beranda</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL ?>/frontend/mahasiswa/beranda.php#tentang-kami">Tentang Kami</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        Panduan
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="<?= BASE_URL ?>/frontend/mahasiswa/beranda.php#jenis-beasiswa">Jenis Beasiswa</a></li>
                        <li><a class="dropdown-item" href="<?= BASE_URL ?>/frontend/mahasiswa/beranda.php#alur-beasiswa">Alur Beasiswa</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL ?>/frontend/mahasiswa/beranda.php#pustaka">Pustaka</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL ?>/frontend/mahasiswa/beranda.php#faq">FAQ</a>
                </li>
            </ul>

            <!-- Profile & Notif -->
            <div class="d-flex align-items-center gap-4">
                <!-- Notifikasi -->
                <a href="<?= BASE_URL ?>/frontend/mahasiswa/pesan.php" class="text-decoration-none position-relative"
                    style="color: var(--color-primary); font-size: 1.25rem;">
                    <i class="bi bi-bell-fill"></i>
                    <?php if (($jumlahNotif ?? 0) > 0): ?>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                        style="font-size: 0.6rem; padding: 0.25em 0.4em;">
                        <?= $jumlahNotif ?> <span class="visually-hidden">pesan belum dibaca</span>
                    </span>
                    <?php endif; ?>
                </a>

                <!-- Dropdown Akun -->
                <div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle profile-link d-flex align-items-center gap-2" href="#"
                        role="button" data-bs-toggle="dropdown" aria-expanded="false"
                        style="padding: 0 !important;">
                        <div class="btn-profile" style="width: 35px; height: 35px; font-size: 1.5rem;">
                            <i class="bi bi-person-fill"></i>
                        </div>
                        <span class="d-none d-lg-inline-block fw-semibold"
                            style="color: var(--color-text); margin-left: 0.5rem;"><?= htmlspecialchars($userName) ?></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end mt-2">
                        <li><a class="dropdown-item" href="<?= BASE_URL ?>/frontend/mahasiswa/profil.php"><i class="bi bi-person me-2"></i>Profil</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="<?= BASE_URL ?>/auth/logout.php"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</nav>
<!-- ========== END NAVBAR ========== -->
