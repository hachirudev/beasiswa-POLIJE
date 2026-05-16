<?php
declare(strict_types=1);
require_once '../../config/app.php';
require_once HELPERS_PATH . 'Session.php';
require_once HELPERS_PATH . 'Response.php';
require_once HELPERS_PATH . 'Validator.php';
require_once CONFIG_PATH  . 'Database.php';
require_once CLASSES_PATH . 'Beasiswa.php';
require_once CLASSES_PATH . 'BeasiswaTag.php';

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

// Ambil data dari form
$nama_beasiswa      = trim($_POST['nama_beasiswa']      ?? '');
$nama_penyelenggara = trim($_POST['nama_penyelenggara'] ?? '');
// Untuk mitra: ambil nama penyelenggara dari profil jika tidak ada di POST
if ($role === 'mitra' && $nama_penyelenggara === '') {
    require_once CLASSES_PATH . 'Mitra.php';
    $mitraData = (new Mitra($db))->getById(Session::getId());
    $nama_penyelenggara = $mitraData['nama_mitra'] ?? '';
}
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
$v->required('nama_beasiswa', $nama_beasiswa)
  ->required('nama_penyelenggara', $nama_penyelenggara)
  ->required('deskripsi_singkat', $deskripsi_singkat)
  ->required('tgl_buka', $tgl_buka)
  ->required('tgl_tutup', $tgl_tutup);

// Upload poster (file)
$poster_url = '';
if (!empty($_FILES['poster']) && $_FILES['poster']['error'] === UPLOAD_ERR_OK) {
    $allowedMimes = ['image/jpeg', 'image/png', 'image/jpg'];
    $maxSize = 2 * 1024 * 1024; // 2MB
    $fileMime = mime_content_type($_FILES['poster']['tmp_name']);
    $fileSize = $_FILES['poster']['size'];

    if (!in_array($fileMime, $allowedMimes, true)) {
        Session::setFlash('error', 'Format poster harus JPG, JPEG, atau PNG.');
        $redirect = $role === 'mitra'
            ? '/frontend/mitra/unggah-beasiswa.php'
            : '/frontend/admin/unggah-beasiswa.php';
        Response::redirectTo(BASE_URL . $redirect);
    }
    if ($fileSize > $maxSize) {
        Session::setFlash('error', 'Ukuran poster maksimal 2MB.');
        $redirect = $role === 'mitra'
            ? '/frontend/mitra/unggah-beasiswa.php'
            : '/frontend/admin/unggah-beasiswa.php';
        Response::redirectTo(BASE_URL . $redirect);
    }

    $ext = pathinfo($_FILES['poster']['name'], PATHINFO_EXTENSION);
    $filename = 'poster_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
    $uploadDir = UPLOAD_PATH . 'poster/';

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    if (!move_uploaded_file($_FILES['poster']['tmp_name'], $uploadDir . $filename)) {
        Session::setFlash('error', 'Gagal mengunggah file poster.');
        $redirect = $role === 'mitra'
            ? '/frontend/mitra/unggah-beasiswa.php'
            : '/frontend/admin/unggah-beasiswa.php';
        Response::redirectTo(BASE_URL . $redirect);
    }

    $poster_url = $filename;
}

if ($v->fails()) {
    $errors = $v->getErrors();
    $firstError = reset($errors);
    Session::setFlash('error', $firstError[0]);
    $redirect = $role === 'mitra'
        ? '/frontend/mitra/unggah-beasiswa.php'
        : '/frontend/admin/unggah-beasiswa.php';
    Response::redirectTo(BASE_URL . $redirect);
}

// Tentukan status & owner berdasarkan role
if ($role === 'mitra') {
    $status_verifikasi = 'pending';
    $id_mitra = Session::getId();
    $id_admin = null;
} else {
    $status_verifikasi = 'terverifikasi';
    $id_admin = Session::getId();
    $id_mitra = null;
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
    'status_verifikasi'   => $status_verifikasi,
    'id_mitra'            => $id_mitra,
    'id_admin'            => $id_admin,
];

// Transaksi: INSERT beasiswa + tags
$db->begin_transaction();

try {
    $beasiswa = new Beasiswa($db);
    $id_beasiswa = $beasiswa->insert($data);

    if ($id_beasiswa === false) {
        throw new Exception('Gagal menyimpan data beasiswa.');
    }

    if (!empty($tag_ids)) {
        $beasiswaTag = new BeasiswaTag($db);
        $beasiswaTag->insertBulk($id_beasiswa, $tag_ids);
    }

    $db->commit();
    Session::setFlash('success', 'Beasiswa berhasil ditambahkan.');
} catch (Exception $e) {
    $db->rollback();
    Session::setFlash('error', 'Gagal menyimpan beasiswa: ' . $e->getMessage());
}

$redirect = $role === 'mitra'
    ? '/frontend/mitra/kelola-beasiswa.php'
    : '/frontend/admin/kelola-beasiswa.php';
Response::redirectTo(BASE_URL . $redirect);
