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

$id_pustaka        = (int) ($_POST['id_pustaka']      ?? 0);
$nama_dokumen      = trim($_POST['nama_dokumen']      ?? '');
$deskripsi_dokumen = trim($_POST['deskripsi_dokumen'] ?? '');
$preview_dokumen   = trim($_POST['preview_dokumen']   ?? '');

// Validasi
$v = new Validator();
$v->required('id_pustaka', $id_pustaka === 0 ? '' : (string) $id_pustaka)
  ->required('nama_dokumen', $nama_dokumen)
  ->required('deskripsi_dokumen', $deskripsi_dokumen);

if ($v->fails()) {
    $errors = $v->getErrors();
    $firstError = reset($errors);
    Session::setFlash('error', $firstError[0]);
    Response::redirectTo(BASE_URL . '/frontend/admin/kelola-pustaka.php');
}

$pustaka = new Pustaka($db);
$existing = $pustaka->getById($id_pustaka);

if (!$existing) {
    Session::setFlash('error', 'Data pustaka tidak ditemukan.');
    Response::redirectTo(BASE_URL . '/frontend/admin/kelola-pustaka.php');
}

$uploadDir = UPLOAD_PATH . 'pustaka' . DIRECTORY_SEPARATOR;

// Jika ada file baru: hapus lama, upload baru
$filePath = $existing['file_path'];

if (!empty($_FILES['file_pustaka']) && $_FILES['file_pustaka']['error'] === UPLOAD_ERR_OK) {
    try {
        $uploader  = new FileUploader($uploadDir);

        // Hapus file lama
        if (!empty($existing['file_path'])) {
            $uploader->delete($existing['file_path']);
        }

        // Upload file baru
        $filePath = $uploader->upload($_FILES['file_pustaka']);
    } catch (Exception $e) {
        Session::setFlash('error', $e->getMessage());
        Response::redirectTo(BASE_URL . '/frontend/admin/kelola-pustaka.php');
    }
}

$previewPath = $existing['preview_dokumen'];
if (!empty($_FILES['preview_dokumen']) && $_FILES['preview_dokumen']['error'] === UPLOAD_ERR_OK) {
    try {
        $previewUploader  = new FileUploader($uploadDir);
        $previewUploader->setAllowedTypes(['image/jpeg', 'image/png']);
        $previewUploader->setMaxSize(2 * 1024 * 1024);

        if (!empty($existing['preview_dokumen'])) {
            $previewUploader->delete($existing['preview_dokumen']);
        }

        $previewPath = $previewUploader->upload($_FILES['preview_dokumen']);
    } catch (Exception $e) {
        Session::setFlash('error', 'Preview Gambar: ' . $e->getMessage());
        Response::redirectTo(BASE_URL . '/frontend/admin/kelola-pustaka.php');
    }
}

$data = [
    'nama_dokumen'      => $nama_dokumen,
    'deskripsi_dokumen' => $deskripsi_dokumen,
    'preview_dokumen'   => $previewPath,
    'file_path'         => $filePath,
];

$success = $pustaka->update($id_pustaka, $data);

if ($success) {
    Session::setFlash('success', 'Dokumen pustaka berhasil diperbarui.');
} else {
    Session::setFlash('error', 'Gagal memperbarui dokumen pustaka.');
}

Response::redirectTo(BASE_URL . '/frontend/admin/kelola-pustaka.php');
