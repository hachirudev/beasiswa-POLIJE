<?php
declare(strict_types=1);
require_once '../../../config/app.php';
require_once HELPERS_PATH . 'Session.php';
require_once HELPERS_PATH . 'Response.php';
require_once CONFIG_PATH  . 'Database.php';
require_once CLASSES_PATH . 'Beasiswa.php';

Session::start();
Session::requireLogin();

$role = Session::getRole();
if (!in_array($role, ['mitra', 'admin'], true)) {
    Response::redirectTo(BASE_URL . '/auth/login.php');
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $redirect = $role === 'mitra'
        ? '/frontend/mitra/kelola-beasiswa.php'
        : '/frontend/admin/kelola-beasiswa.php';
    Response::redirectTo(BASE_URL . $redirect);
}

$db = Database::getInstance()->getConnection();

$id_beasiswa = (int) ($_POST['id_beasiswa'] ?? 0);

if ($id_beasiswa === 0) {
    Session::setFlash('error', 'ID beasiswa tidak valid.');
    $redirect = $role === 'mitra'
        ? '/frontend/mitra/kelola-beasiswa.php'
        : '/frontend/admin/kelola-beasiswa.php';
    Response::redirectTo(BASE_URL . $redirect);
}

$beasiswa = new Beasiswa($db);

// Jika mitra: pastikan beasiswa miliknya
if ($role === 'mitra') {
    $data = $beasiswa->getById($id_beasiswa);
    if (!$data || (int) $data['id_mitra'] !== Session::getId()) {
        Session::setFlash('error', 'Anda tidak memiliki akses untuk menghapus beasiswa ini.');
        Response::redirectTo(BASE_URL . '/frontend/mitra/kelola-beasiswa.php');
    }
}

$success = $beasiswa->delete($id_beasiswa);

if ($success) {
    Session::setFlash('success', 'Beasiswa berhasil dihapus.');
} else {
    Session::setFlash('error', 'Gagal menghapus beasiswa.');
}

$redirect = $role === 'mitra'
    ? '/frontend/mitra/kelola-beasiswa.php'
    : '/frontend/admin/kelola-beasiswa.php';
Response::redirectTo(BASE_URL . $redirect);
