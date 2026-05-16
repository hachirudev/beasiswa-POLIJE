<?php
declare(strict_types=1);
require_once '../../config/app.php';
require_once HELPERS_PATH . 'Session.php';
require_once HELPERS_PATH . 'Response.php';
require_once CONFIG_PATH  . 'Database.php';
require_once CLASSES_PATH . 'Mitra.php';

Session::start();
Session::requireLogin();
Session::requireRole('admin');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    Response::redirectTo(BASE_URL . '/frontend/admin/kelola-mitra.php');
}

$db = Database::getInstance()->getConnection();

$id_mitra = (int) ($_POST['id_mitra'] ?? 0);

if ($id_mitra === 0) {
    Session::setFlash('error', 'ID Mitra tidak valid.');
    Response::redirectTo(BASE_URL . '/frontend/admin/kelola-mitra.php');
}

$mitra = new Mitra($db);
$success = $mitra->delete($id_mitra);

if ($success) {
    Session::setFlash('success', 'Akun mitra berhasil dihapus.');
} else {
    Session::setFlash('error', 'Gagal menghapus akun mitra.');
}

Response::redirectTo(BASE_URL . '/frontend/admin/kelola-mitra.php');
