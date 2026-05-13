<?php
declare(strict_types=1);
require_once '../../../config/app.php';
require_once HELPERS_PATH . 'Session.php';
require_once HELPERS_PATH . 'Response.php';
require_once HELPERS_PATH . 'Validator.php';
require_once CONFIG_PATH  . 'Database.php';
require_once CLASSES_PATH . 'User.php';
require_once CLASSES_PATH . 'Mitra.php';

Session::start();
Session::requireLogin();
Session::requireRole('admin');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    Response::redirectTo(BASE_URL . '/frontend/admin/kelola-mitra.php');
}

$db = Database::getInstance()->getConnection();

$nama_mitra    = trim($_POST['nama_mitra']    ?? '');
$bidang_usaha  = trim($_POST['bidang_usaha']  ?? '');
$telepon       = trim($_POST['telepon']       ?? '');
$website       = trim($_POST['website']       ?? '');
$email         = trim($_POST['email']         ?? '');
$password      = $_POST['password']           ?? '';

// Validasi
$v = new Validator();
$v->required('nama_mitra', $nama_mitra)
  ->required('email', $email)
  ->email('email', $email)
  ->required('password', $password)
  ->minLength('password', $password, 8);

if ($v->fails()) {
    $errors = $v->getErrors();
    $firstError = reset($errors);
    Session::setFlash('error', $firstError[0]);
    Response::redirectTo(BASE_URL . '/frontend/admin/kelola-mitra.php');
}

$data = [
    'nama_mitra'    => $nama_mitra,
    'bidang_usaha'  => $bidang_usaha,
    'telepon'       => $telepon,
    'website'       => $website,
    'email'         => $email,
    'password'      => $password,
];

$mitra = new Mitra($db);
$success = $mitra->create($data);

if ($success) {
    Session::setFlash('success', 'Akun mitra berhasil dibuat.');
} else {
    Session::setFlash('error', 'Gagal membuat akun mitra. Email mungkin sudah terdaftar.');
}

Response::redirectTo(BASE_URL . '/frontend/admin/kelola-mitra.php');
