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
$uploadDir = UPLOAD_PATH . 'pustaka' . DIRECTORY_SEPARATOR;

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

// Upload gambar preview jika ada
$previewPath = '';
if (!empty($_FILES['preview_dokumen']) && $_FILES['preview_dokumen']['error'] !== UPLOAD_ERR_NO_FILE) {
    try {
        $previewUploader = new FileUploader($uploadDir);
        $previewUploader->setAllowedTypes(['image/jpeg', 'image/png']);
        $previewUploader->setMaxSize(2 * 1024 * 1024); // 2MB
        $previewPath = $previewUploader->upload($_FILES['preview_dokumen']);
    } catch (Exception $e) {
        Session::setFlash('error', 'Preview Gambar: ' . $e->getMessage());
        Response::redirectTo(BASE_URL . '/frontend/admin/kelola-pustaka.php');
    }
}

// Upload file PDF ke uploads/pustaka/
if (empty($_FILES['file_pustaka']) || $_FILES['file_pustaka']['error'] === UPLOAD_ERR_NO_FILE) {
    Session::setFlash('error', 'File dokumen wajib diunggah.');
    Response::redirectTo(BASE_URL . '/frontend/admin/kelola-pustaka.php');
}

$filePath = '';
try {
    $uploader  = new FileUploader($uploadDir);
    // default pdf and 5MB
    $filePath  = $uploader->upload($_FILES['file_pustaka']);
} catch (Exception $e) {
    Session::setFlash('error', 'Dokumen PDF: ' . $e->getMessage());
    Response::redirectTo(BASE_URL . '/frontend/admin/kelola-pustaka.php');
}

$data = [
    'id_admin'          => Session::getId(),
    'nama_dokumen'      => $nama_dokumen,
    'deskripsi_dokumen' => $deskripsi_dokumen,
    'preview_dokumen'   => $previewPath,
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
