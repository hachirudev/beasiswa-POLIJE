<?php
declare(strict_types=1);
require_once '../../config/app.php';
require_once HELPERS_PATH . 'Session.php';
require_once HELPERS_PATH . 'Response.php';
require_once HELPERS_PATH . 'Validator.php';
require_once CONFIG_PATH  . 'Database.php';
require_once CLASSES_PATH . 'Beasiswa.php';

Session::start();
Session::requireLogin();
Session::requireRole('admin');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    Response::redirectTo(BASE_URL . '/frontend/admin/kelola-beasiswa.php');
}

$db = Database::getInstance()->getConnection();

$id_beasiswa = (int) ($_POST['id_beasiswa'] ?? 0);
$status      = trim($_POST['status']        ?? '');

// Validasi
$v = new Validator();
$v->required('id_beasiswa', $id_beasiswa === 0 ? '' : (string) $id_beasiswa)
  ->required('status', $status)
  ->inArray('status', $status, ['terverifikasi', 'ditolak']);

if ($v->fails()) {
    $errors = $v->getErrors();
    $firstError = reset($errors);
    Session::setFlash('error', $firstError[0]);
    Response::redirectTo(BASE_URL . '/frontend/admin/kelola-beasiswa.php');
}

$alasan = trim($_POST['alasan'] ?? '');

$beasiswa = new Beasiswa($db);
$success = $beasiswa->updateStatusVerifikasi($id_beasiswa, $status, $alasan);

if ($success) {
    $label = $status === 'terverifikasi' ? 'diverifikasi' : 'ditolak';
    Session::setFlash('success', "Beasiswa berhasil {$label}.");
} else {
    Session::setFlash('error', 'Gagal memperbarui status verifikasi.');
}

Response::redirectTo(BASE_URL . '/frontend/admin/kelola-beasiswa.php');
