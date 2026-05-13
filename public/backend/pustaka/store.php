<?php
declare(strict_types=1);
require_once '../../../config/app.php';
require_once HELPERS_PATH . 'Session.php';
require_once HELPERS_PATH . 'Response.php';
require_once HELPERS_PATH . 'Validator.php';
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

$nama_dokumen      = trim($_POST['nama_dokumen']      ?? '');
$deskripsi_dokumen = trim($_POST['deskripsi_dokumen'] ?? '');
$preview_dokumen   = trim($_POST['preview_dokumen']   ?? '');

// Validasi
$v = new Validator();
$v->required('nama_dokumen', $nama_dokumen)
  ->required('deskripsi_dokumen', $deskripsi_dokumen);

if ($v->fails()) {
    $errors = $v->getErrors();
    $firstError = reset($errors);
    Session::setFlash('error', $firstError[0]);
    Response::redirectTo(BASE_URL . '/frontend/admin/kelola-pustaka.php');
}

// Upload file PDF ke uploads/pustaka/
if (empty($_FILES['file_pustaka']) || $_FILES['file_pustaka']['error'] === UPLOAD_ERR_NO_FILE) {
    Session::setFlash('error', 'File dokumen wajib diunggah.');
    Response::redirectTo(BASE_URL . '/frontend/admin/kelola-pustaka.php');
}

try {
    $uploadDir = UPLOAD_PATH . 'pustaka' . DIRECTORY_SEPARATOR;
    $uploader  = new FileUploader($uploadDir);
    $filePath  = $uploader->upload($_FILES['file_pustaka']);
} catch (Exception $e) {
    Session::setFlash('error', $e->getMessage());
    Response::redirectTo(BASE_URL . '/frontend/admin/kelola-pustaka.php');
}

$data = [
    'id_admin'          => Session::getId(),
    'nama_dokumen'      => $nama_dokumen,
    'deskripsi_dokumen' => $deskripsi_dokumen,
    'preview_dokumen'   => $preview_dokumen,
    'file_path'         => $filePath,
];

$pustaka = new Pustaka($db);
$success = $pustaka->insert($data);

if ($success) {
    Session::setFlash('success', 'Dokumen pustaka berhasil ditambahkan.');
} else {
    Session::setFlash('error', 'Gagal menambahkan dokumen pustaka.');
}

Response::redirectTo(BASE_URL . '/frontend/admin/kelola-pustaka.php');
