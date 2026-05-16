<?php
declare(strict_types=1);
require_once '../config/app.php';
require_once HELPERS_PATH . 'Session.php';
require_once HELPERS_PATH . 'Response.php';

Session::start();
if (Session::isLoggedIn()) {
    $role = Session::getRole();
    if ($role === 'mahasiswa')
        Response::redirectTo(BASE_URL . '/frontend/mahasiswa/beranda.php');
    if ($role === 'mitra')
        Response::redirectTo(BASE_URL . '/frontend/mitra/beranda.php');
    if ($role === 'admin')
        Response::redirectTo(BASE_URL . '/frontend/admin/dashboard.php');
}
$pageTitle = APP_NAME . ' | Sistem Informasi Beasiswa Politeknik Negeri Jember';
$pageDescription = 'Portal Beasiswa Resmi Politeknik Negeri Jember. Platform untuk memfasilitasi mahasiswa dalam mengakses berbagai peluang pembiayaan pendidikan.';
?>

<?php require_once 'frontend/layout/header.php'; ?>

<!-- ========== NAVBAR ========== -->
<!-- [END REQUIRE_ONCE] -->
<nav class="navbar navbar-landing" id="navbar-landing">
    <div class="container">
        <a class="navbar-brand" href="#">
            <img src="<?= BASE_URL ?>/assets/img/logo polije.png" alt="Logo"
                style="height: 40px; width: auto; object-fit: contain;">
            <span
                style="font-weight: 800; color: var(--color-primary); font-size: 1.25rem; letter-spacing: -0.5px;">Beasiswa
                POLIJE</span>
        </a>
        <div class="d-flex gap-2">
            <a href="../public/auth/login.php" class="btn btn-login-landing" id="btn-login">Log in</a>
            <a href="../public/auth/register.php" class="btn btn-signup-landing" id="btn-signup">Sign up</a>
        </div>
    </div>
</nav>
<!-- ========== END NAVBAR ========== -->

<!-- ========== KONTEN UTAMA ========== -->
<section class="hero-section" id="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <!-- Teks hero -->
            <div class="col-lg-6 mb-4 mb-lg-0">
                <h1 class="hero-title">Beasiswa POLIJE</h1>
                <p class="hero-description">
                    Selamat datang di Portal Beasiswa Resmi Polije. Platform ini dirancang khusus untuk
                    memfasilitasi
                    mahasiswa dalam mengakses berbagai peluang pembiayaan pendidikan, mulai dari kemitraan industri
                    hingga bantuan internal kampus.
                </p>
                <a href="../public/auth/register.php" class="btn-mulai" id="btn-mulai-disini">
                    <span class="icon-circle"><i class="bi bi-arrow-right"></i></span>
                    Mulai Disini
                </a>
            </div>

            <!-- Gambar tugu Polije -->
            <div class="col-lg-6 hero-image-wrapper">
                <div class="hero-image-circle">
                    <img src="../public/assets/img/tugu circle.png" alt="Tugu Politeknik Negeri Jember"
                        id="img-tugu-polije">
                </div>
            </div>
        </div>
    </div>

    <!-- Wave divider -->
    <div class="hero-wave">
        <svg viewBox="0 0 1440 80" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M0,40 C360,80 720,0 1080,40 C1260,60 1380,50 1440,40 L1440,80 L0,80 Z" fill="var(--color-white)" />
        </svg>
    </div>
</section>
<!-- ========== END KONTEN ========== -->

<!-- ========== FOOTER ========== -->
<footer class="footer-landing" id="footer-landing">
    <div class="container">
        Copyright &copy; 2026 Beasiswa POLIJE
    </div>
</footer>
<!-- ========== END FOOTER ========== -->