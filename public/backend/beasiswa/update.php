<?php
declare(strict_types=1);
require_once '../../../config/app.php';
require_once HELPERS_PATH . 'Session.php';
require_once HELPERS_PATH . 'Response.php';
require_once HELPERS_PATH . 'Validator.php';
require_once CONFIG_PATH  . 'Database.php';
require_once CLASSES_PATH . 'Beasiswa.php';
require_once CLASSES_PATH . 'BeasiswaTag.php';

Session::start();
Session::requireLogin();
Session::requireRole('admin');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    Response::redirectTo(BASE_URL . '/frontend/admin/kelola-beasiswa.php');
}

$db = Database::getInstance()->getConnection();

// Ambil data dari form
$id_beasiswa         = (int) ($_POST['id_beasiswa']       ?? 0);
$nama_beasiswa       = trim($_POST['nama_beasiswa']       ?? '');
$nama_penyelenggara  = trim($_POST['nama_penyelenggara']  ?? '');
$deskripsi_singkat   = trim($_POST['deskripsi_singkat']   ?? '');
$deskripsi_lengkap   = trim($_POST['deskripsi_lengkap']   ?? '');
$informasi_beasiswa  = trim($_POST['informasi_beasiswa']  ?? '');
$link_pendaftaran    = trim($_POST['link_pendaftaran']    ?? '');
$tgl_buka            = trim($_POST['tgl_buka']            ?? '');
$tgl_tutup           = trim($_POST['tgl_tutup']           ?? '');
$status_pendaftaran  = trim($_POST['status_pendaftaran']  ?? 'belum_dibuka');
$tag_ids             = array_map('intval', $_POST['tags'] ?? []);

// Validasi
$v = new Validator();
$v->required('id_beasiswa', $id_beasiswa === 0 ? '' : (string) $id_beasiswa)
  ->required('nama_beasiswa', $nama_beasiswa)
  ->required('nama_penyelenggara', $nama_penyelenggara)
  ->required('deskripsi_singkat', $deskripsi_singkat)
  ->required('tgl_buka', $tgl_buka)
  ->required('tgl_tutup', $tgl_tutup);

if ($v->fails()) {
    $errors = $v->getErrors();
    $firstError = reset($errors);
    Session::setFlash('error', $firstError[0]);
    Response::redirectTo(BASE_URL . '/frontend/admin/kelola-beasiswa.php');
}

// Ambil data lama untuk poster existing
$beasiswa = new Beasiswa($db);
$existing = $beasiswa->getById($id_beasiswa);
$poster_url = $existing['poster_url'] ?? '';

// Upload poster baru (jika ada)
if (!empty($_FILES['poster']) && $_FILES['poster']['error'] === UPLOAD_ERR_OK) {
    $allowedMimes = ['image/jpeg', 'image/png', 'image/jpg'];
    $maxSize = 2 * 1024 * 1024; // 2MB
    $fileMime = mime_content_type($_FILES['poster']['tmp_name']);
    $fileSize = $_FILES['poster']['size'];

    if (!in_array($fileMime, $allowedMimes, true)) {
        Session::setFlash('error', 'Format poster harus JPG, JPEG, atau PNG.');
        Response::redirectTo(BASE_URL . '/frontend/admin/kelola-beasiswa.php');
    }
    if ($fileSize > $maxSize) {
        Session::setFlash('error', 'Ukuran poster maksimal 2MB.');
        Response::redirectTo(BASE_URL . '/frontend/admin/kelola-beasiswa.php');
    }

    $ext = pathinfo($_FILES['poster']['name'], PATHINFO_EXTENSION);
    $filename = 'poster_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
    $uploadDir = UPLOAD_PATH . 'poster/';

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    if (move_uploaded_file($_FILES['poster']['tmp_name'], $uploadDir . $filename)) {
        // Hapus poster lama jika ada
        if (!empty($poster_url) && file_exists($uploadDir . $poster_url)) {
            unlink($uploadDir . $poster_url);
        }
        $poster_url = $filename;
    }
}

$data = [
    'nama_beasiswa'      => $nama_beasiswa,
    'nama_penyelenggara' => $nama_penyelenggara,
    'deskripsi_singkat'   => $deskripsi_singkat,
    'deskripsi_lengkap'   => $deskripsi_lengkap,
    'informasi_beasiswa'  => $informasi_beasiswa,
    'link_pendaftaran'    => $link_pendaftaran,
    'poster_url'          => $poster_url,
    'tgl_buka'            => $tgl_buka,
    'tgl_tutup'           => $tgl_tutup,
    'status_pendaftaran'  => $status_pendaftaran,
];

// Transaksi: UPDATE beasiswa + hapus tag lama + insert tag baru
$db->begin_transaction();

try {
    $beasiswa = new Beasiswa($db);
    $beasiswa->update($id_beasiswa, $data);

    $beasiswaTag = new BeasiswaTag($db);
    $beasiswaTag->deleteByBeasiswa($id_beasiswa);

    if (!empty($tag_ids)) {
        $beasiswaTag->insertBulk($id_beasiswa, $tag_ids);
    }

    $db->commit();
    Session::setFlash('success', 'Beasiswa berhasil diperbarui.');
} catch (Exception $e) {
    $db->rollback();
    Session::setFlash('error', 'Gagal memperbarui beasiswa: ' . $e->getMessage());
}

Response::redirectTo(BASE_URL . '/frontend/admin/kelola-beasiswa.php');
