<?php
declare(strict_types=1);
require_once '../../../config/app.php';
require_once HELPERS_PATH . 'Session.php';
require_once HELPERS_PATH . 'Response.php';
require_once HELPERS_PATH . 'Validator.php';
require_once CONFIG_PATH  . 'Database.php';
require_once CLASSES_PATH . 'User.php';
require_once CLASSES_PATH . 'Mahasiswa.php';
require_once CLASSES_PATH . 'Mitra.php';

Session::start();
Session::requireLogin();

$role = Session::getRole();
if (!in_array($role, ['mahasiswa', 'mitra'], true)) {
    Response::redirectTo(BASE_URL . '/auth/login.php');
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $redirect = $role === 'mahasiswa'
        ? '/frontend/mahasiswa/profil.php'
        : '/frontend/mitra/profil.php';
    Response::redirectTo(BASE_URL . $redirect);
}

$db = Database::getInstance()->getConnection();

$action = trim($_POST['action'] ?? '');

// Redirect target berdasarkan role
$profilUrl = $role === 'mahasiswa'
    ? '/frontend/mahasiswa/profil.php'
    : '/frontend/mitra/profil.php';

// Inisialisasi class sesuai role
$tabel = $role; // 'mahasiswa' atau 'mitra'
if ($role === 'mahasiswa') {
    $obj = new Mahasiswa($db);
} else {
    $obj = new Mitra($db);
}

// ========== ACTION: update_profil ==========
if ($action === 'update_profil') {
    if ($role === 'mahasiswa') {
        $data = [
            'nama'           => trim($_POST['nama']           ?? ''),
            'id_prodi'       => (int) ($_POST['id_prodi']     ?? 0),
            'semester'       => (int) ($_POST['semester']      ?? 0),
            'angkatan'       => (int) ($_POST['angkatan']      ?? 0),
            'IPK'            => (float) ($_POST['IPK']         ?? 0),
            'jenis_kelamin'  => trim($_POST['jenis_kelamin']  ?? ''),
            'email'          => trim($_POST['email']          ?? ''),
        ];

        $v = new Validator();
        $v->required('nama', $data['nama'])
          ->required('email', $data['email'])
          ->email('email', $data['email']);

        if ($v->fails()) {
            $errors = $v->getErrors();
            $firstError = reset($errors);
            Session::setFlash('error', $firstError[0]);
            Response::redirectTo(BASE_URL . $profilUrl);
        }

        $success = $obj->update(Session::getId(), $data);
    } else {
        // Mitra
        $data = [
            'nama_mitra'    => trim($_POST['nama_mitra']    ?? ''),
            'bidang_usaha'  => trim($_POST['bidang_usaha']  ?? ''),
            'telepon'       => trim($_POST['telepon']       ?? ''),
            'website'       => trim($_POST['website']       ?? ''),
            'email'         => trim($_POST['email']         ?? ''),
        ];

        $v = new Validator();
        $v->required('nama_mitra', $data['nama_mitra'])
          ->required('email', $data['email'])
          ->email('email', $data['email']);

        if ($v->fails()) {
            $errors = $v->getErrors();
            $firstError = reset($errors);
            Session::setFlash('error', $firstError[0]);
            Response::redirectTo(BASE_URL . $profilUrl);
        }

        $success = $obj->update(Session::getId(), $data);
    }

    if ($success) {
        Session::setFlash('success', 'Profil berhasil diperbarui.');
    } else {
        Session::setFlash('error', 'Gagal memperbarui profil.');
    }

    Response::redirectTo(BASE_URL . $profilUrl);
}

// ========== ACTION: ganti_password ==========
if ($action === 'ganti_password') {
    if ($role !== 'mahasiswa') {
        Session::setFlash('error', 'Aksi tidak diizinkan untuk akun Anda.');
        Response::redirectTo(BASE_URL . $profilUrl);
    }

    $passwordLama  = $_POST['password_lama']  ?? '';
    $passwordBaru  = $_POST['password_baru']  ?? '';
    $passwordKonf  = $_POST['password_konfirmasi'] ?? '';

    // Validasi
    $v = new Validator();
    $v->required('password_lama', $passwordLama)
      ->required('password_baru', $passwordBaru)
      ->minLength('password_baru', $passwordBaru, 8);

    if ($v->fails()) {
        $errors = $v->getErrors();
        $firstError = reset($errors);
        Session::setFlash('error', $firstError[0]);
        Response::redirectTo(BASE_URL . $profilUrl);
    }

    if ($passwordBaru !== $passwordKonf) {
        Session::setFlash('error', 'Password baru dan konfirmasi tidak cocok.');
        Response::redirectTo(BASE_URL . $profilUrl);
    }

    // Verifikasi password lama
    $user = $obj->getById(Session::getId());
    if (!$user || !$obj->verifyPassword($passwordLama, $user['password'])) {
        Session::setFlash('error', 'Password lama salah.');
        Response::redirectTo(BASE_URL . $profilUrl);
    }

    $success = $obj->updatePassword($tabel, Session::getId(), $passwordBaru);

    if ($success) {
        Session::setFlash('success', 'Password berhasil diubah.');
    } else {
        Session::setFlash('error', 'Gagal mengubah password.');
    }

    Response::redirectTo(BASE_URL . $profilUrl);
}

// Action tidak dikenal
Session::setFlash('error', 'Aksi tidak valid.');
Response::redirectTo(BASE_URL . $profilUrl);
