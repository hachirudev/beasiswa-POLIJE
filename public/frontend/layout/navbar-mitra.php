<!-- ========== NAVBAR MITRA ========== -->
<?php
$mitraName = $_SESSION['nama'] ?? 'Mitra';
?>
<nav class="navbar navbar-expand-lg navbar-beasiswa sticky-top" id="navbar-mitra">
    <div class="container">
        <!-- Brand -->
        <a class="navbar-brand d-flex align-items-center gap-2" href="<?= BASE_URL ?>/frontend/mitra/beranda.php#beranda" style="padding: 0;">
            <img src="<?= BASE_URL ?>/assets/img/logo polije.png" alt="Logo" style="height: 40px; width: auto; object-fit: contain;">
            <span style="font-weight: 800; color: var(--color-primary); font-size: 1.25rem; letter-spacing: -0.5px;">Beasiswa POLIJE</span>
        </a>

        <!-- Mobile toggler -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMitra"
            aria-controls="navbarMitra" aria-expanded="false" aria-label="Toggle navigasi">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Nav links -->
        <div class="collapse navbar-collapse" id="navbarMitra">
            <ul class="navbar-nav ms-auto me-3 align-items-lg-center">
                <li class="nav-item">
                    <a class="nav-link <?= ($activePage ?? '') === 'beranda' ? 'active' : '' ?>" href="<?= BASE_URL ?>/frontend/mitra/beranda.php#beranda">Beranda</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL ?>/frontend/mitra/beranda.php#tentang-kami">Tentang Kami</a>
                </li>
                <!-- Menu khusus Mitra -->
                <li class="nav-item">
                    <a class="nav-link <?= ($activePage ?? '') === 'kelola-beasiswa' ? 'active' : '' ?>" href="<?= BASE_URL ?>/frontend/mitra/kelola-beasiswa.php">
                        <i class="bi bi-folder2-open me-1"></i>Kelola Beasiswa
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        Panduan
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="<?= BASE_URL ?>/frontend/mitra/beranda.php#jenis-beasiswa">Jenis Beasiswa</a></li>
                        <li><a class="dropdown-item" href="<?= BASE_URL ?>/frontend/mitra/beranda.php#alur-beasiswa">Alur Beasiswa</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL ?>/frontend/mitra/beranda.php#pustaka">Pustaka</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL ?>/frontend/mitra/beranda.php#faq">FAQ</a>
                </li>
            </ul>

            <!-- Profile -->
            <div class="d-flex align-items-center">
                <div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle profile-link d-flex align-items-center gap-2" href="#"
                        role="button" data-bs-toggle="dropdown" aria-expanded="false"
                        style="padding: 0 !important;">
                        <div class="btn-profile" style="width: 35px; height: 35px; font-size: 1.5rem;">
                            <i class="bi bi-person-fill"></i>
                        </div>
                        <span class="d-none d-lg-inline-block fw-semibold"
                            style="color: var(--color-text); margin-left: 0.5rem;"><?= htmlspecialchars($mitraName) ?></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end mt-2">
                        <li><a class="dropdown-item" href="<?= BASE_URL ?>/frontend/mitra/profil.php"><i class="bi bi-person me-2"></i>Profil</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="<?= BASE_URL ?>/auth/logout.php"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</nav>
<!-- ========== END NAVBAR ========== -->
