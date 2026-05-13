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
$pageTitle = APP_NAME . ' — Sistem Informasi Beasiswa Politeknik Negeri Jember';
$pageDescription = 'Portal Beasiswa Resmi Politeknik Negeri Jember. Platform untuk memfasilitasi mahasiswa dalam mengakses berbagai peluang pembiayaan pendidikan.';
?>
<?php require_once '../desain/tahap-b/index.html'; ?>