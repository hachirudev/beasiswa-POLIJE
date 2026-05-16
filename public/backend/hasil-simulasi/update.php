<?php
declare(strict_types=1);
require_once '../../config/app.php';
require_once HELPERS_PATH . 'Session.php';
require_once HELPERS_PATH . 'Response.php';
require_once HELPERS_PATH . 'Validator.php';
require_once CONFIG_PATH  . 'Database.php';
require_once CLASSES_PATH . 'HasilSimulasi.php';

Session::start();
Session::requireLogin();
Session::requireRole('admin');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    Response::redirectTo(BASE_URL . '/frontend/admin/review-simulasi.php');
}

$db = Database::getInstance()->getConnection();

$id_simulasi     = (int) ($_POST['id_simulasi']     ?? 0);
$skor            = (float) ($_POST['skor']           ?? 0);
$catatan_admin   = trim($_POST['catatan_admin']      ?? '');
$status_simulasi = trim($_POST['status_simulasi']    ?? '');

// Validasi
$v = new Validator();
$v->required('id_simulasi', $id_simulasi === 0 ? '' : (string) $id_simulasi)
  ->required('status_simulasi', $status_simulasi)
  ->inArray('status_simulasi', $status_simulasi, ['lulus', 'tidak_lulus']);

if ($v->fails()) {
    $errors = $v->getErrors();
    $firstError = reset($errors);
    Session::setFlash('error', $firstError[0]);
    Response::redirectTo(BASE_URL . '/frontend/admin/review-simulasi.php');
}

// Validasi skor 0-100
if ($skor < 0 || $skor > 100) {
    Session::setFlash('error', 'Skor harus di antara 0 dan 100.');
    Response::redirectTo(BASE_URL . '/frontend/admin/detail-review.php?id=' . $id_simulasi);
}

$data = [
    'skor'             => $skor,
    'catatan_admin'    => $catatan_admin,
    'status_simulasi'  => $status_simulasi,
    'id_admin'         => Session::getId(),
];

$hasilSimulasi = new HasilSimulasi($db);
$success = $hasilSimulasi->update($id_simulasi, $data);

if ($success) {
    Session::setFlash('success', 'Review simulasi berhasil disimpan.');
} else {
    Session::setFlash('error', 'Gagal menyimpan review simulasi.');
}

Response::redirectTo(BASE_URL . '/frontend/admin/review-simulasi.php');
