<?php
declare(strict_types=1);
require_once '../../config/app.php';
require_once HELPERS_PATH . 'Session.php';
require_once HELPERS_PATH . 'Response.php';
require_once CONFIG_PATH  . 'Database.php';
require_once CLASSES_PATH . 'Faq.php';

Session::start();
Session::requireLogin();
Session::requireRole('admin');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    Response::redirectTo(BASE_URL . '/frontend/admin/kelola-faq.php');
}

$db = Database::getInstance()->getConnection();

$id_pertanyaan = (int) ($_POST['id_pertanyaan'] ?? 0);

if ($id_pertanyaan === 0) {
    Session::setFlash('error', 'ID FAQ tidak valid.');
    Response::redirectTo(BASE_URL . '/frontend/admin/kelola-faq.php');
}

$faq = new Faq($db);
$success = $faq->delete($id_pertanyaan);

if ($success) {
    Session::setFlash('success', 'FAQ berhasil dihapus.');
} else {
    Session::setFlash('error', 'Gagal menghapus FAQ.');
}

Response::redirectTo(BASE_URL . '/frontend/admin/kelola-faq.php');
