<?php
declare(strict_types=1);
require_once '../../../config/app.php';
require_once HELPERS_PATH . 'Session.php';
require_once HELPERS_PATH . 'Response.php';
require_once HELPERS_PATH . 'Validator.php';
require_once CONFIG_PATH  . 'Database.php';
require_once CLASSES_PATH . 'Faq.php';

Session::start();
Session::requireLogin();
Session::requireRole('admin');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    Response::redirectTo(BASE_URL . '/frontend/admin/kelola-faq.php');
}

$db = Database::getInstance()->getConnection();

$pertanyaan = trim($_POST['pertanyaan'] ?? '');
$jawaban    = trim($_POST['jawaban']    ?? '');

// Validasi
$v = new Validator();
$v->required('pertanyaan', $pertanyaan)
  ->required('jawaban', $jawaban);

if ($v->fails()) {
    $errors = $v->getErrors();
    $firstError = reset($errors);
    Session::setFlash('error', $firstError[0]);
    Response::redirectTo(BASE_URL . '/frontend/admin/kelola-faq.php');
}

$data = [
    'id_admin'   => Session::getId(),
    'pertanyaan' => $pertanyaan,
    'jawaban'    => $jawaban,
];

$faq = new Faq($db);
$success = $faq->insert($data);

if ($success) {
    Session::setFlash('success', 'FAQ berhasil ditambahkan.');
} else {
    Session::setFlash('error', 'Gagal menambahkan FAQ.');
}

Response::redirectTo(BASE_URL . '/frontend/admin/kelola-faq.php');
