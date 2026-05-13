<?php
declare(strict_types=1);
require_once '../../../config/app.php';
require_once HELPERS_PATH . 'Session.php';
require_once HELPERS_PATH . 'Response.php';
require_once HELPERS_PATH . 'FileUploader.php';
require_once CONFIG_PATH  . 'Database.php';
require_once CLASSES_PATH . 'Pustaka.php';

Session::start();
Session::requireLogin();
Session::requireRole('admin');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    Response::redirectTo(BASE_URL . '/frontend/admin/kelola-pustaka.php');
}

$db = Database::getInstance()->getConnection();

$id_pustaka = (int) ($_POST['id_pustaka'] ?? 0);

if ($id_pustaka === 0) {
    Session::setFlash('error', 'ID pustaka tidak valid.');
    Response::redirectTo(BASE_URL . '/frontend/admin/kelola-pustaka.php');
}

$pustaka  = new Pustaka($db);
$existing = $pustaka->getById($id_pustaka);

if (!$existing) {
    Session::setFlash('error', 'Data pustaka tidak ditemukan.');
    Response::redirectTo(BASE_URL . '/frontend/admin/kelola-pustaka.php');
}

// Hapus file via FileUploader
if (!empty($existing['file_path'])) {
    $uploadDir = UPLOAD_PATH . 'pustaka' . DIRECTORY_SEPARATOR;
    $uploader  = new FileUploader($uploadDir);
    $uploader->delete($existing['file_path']);
}

$success = $pustaka->delete($id_pustaka);

if ($success) {
    Session::setFlash('success', 'Dokumen pustaka berhasil dihapus.');
} else {
    Session::setFlash('error', 'Gagal menghapus dokumen pustaka.');
}

Response::redirectTo(BASE_URL . '/frontend/admin/kelola-pustaka.php');
