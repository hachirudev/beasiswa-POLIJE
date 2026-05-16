<?php
declare(strict_types=1);
require_once '../../../config/app.php';
require_once HELPERS_PATH . 'Session.php';
require_once HELPERS_PATH . 'Response.php';
require_once HELPERS_PATH . 'Validator.php';
require_once HELPERS_PATH . 'FileUploader.php';
require_once CONFIG_PATH  . 'Database.php';
require_once CLASSES_PATH . 'Simulasi.php';

Session::start();
Session::requireLogin();
Session::requireRole('mahasiswa');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    Response::redirectTo(BASE_URL . '/frontend/mahasiswa/beranda.php');
}

$db = Database::getInstance()->getConnection();

// Ambil data simulasi
$id_beasiswa        = (int) ($_POST['id_beasiswa']        ?? 0);
$prestasi           = trim($_POST['prestasi']             ?? '');
$motivasi           = trim($_POST['motivasi']             ?? '');
$ikut_organisasi    = (int) ($_POST['ikut_organisasi']    ?? 0);
$status_beasiswa_lain = (int) ($_POST['status_beasiswa_lain'] ?? 0);
$aktif_kuliah       = (int) ($_POST['aktif_kuliah']       ?? 0);

// Ambil data orang tua
$nama_ortu          = trim($_POST['nama_ortu']            ?? '');
$penghasilan_ortu   = (float) ($_POST['penghasilan_ortu'] ?? 0);
$pekerjaan_ortu     = trim($_POST['pekerjaan_ortu']       ?? '');
$jml_tanggungan     = (int) ($_POST['tanggungan_ortu']    ?? 0);
$sktm               = (int) ($_POST['sktm']               ?? 0);

// Validasi
$v = new Validator();
$v->required('id_beasiswa', $id_beasiswa === 0 ? '' : (string) $id_beasiswa)
  ->required('prestasi', $prestasi)
  ->required('motivasi', $motivasi)
  ->required('nama_ortu', $nama_ortu)
  ->required('pekerjaan_ortu', $pekerjaan_ortu);

if ($v->fails()) {
    $errors = $v->getErrors();
    $firstError = reset($errors);
    Session::setFlash('error', $firstError[0]);
    Response::redirectTo(BASE_URL . '/frontend/mahasiswa/simulasi.php?id_beasiswa=' . $id_beasiswa);
}

// Upload file (PDF, maks 5MB)
$uploadedFiles = [];
if (!empty($_FILES['file_simulasi']) && $_FILES['file_simulasi']['error'][0] !== UPLOAD_ERR_NO_FILE) {
    try {
        $uploadDir = UPLOAD_PATH . 'simulasi' . DIRECTORY_SEPARATOR;
        $uploader  = new FileUploader($uploadDir);
        $paths     = $uploader->uploadMultiple($_FILES['file_simulasi']);

        foreach ($paths as $i => $path) {
            $uploadedFiles[] = [
                'nama_file'  => $_FILES['file_simulasi']['name'][$i],
                'file_path'  => $path,
            ];
        }
    } catch (Exception $e) {
        Session::setFlash('error', $e->getMessage());
        Response::redirectTo(BASE_URL . '/frontend/mahasiswa/simulasi.php?id_beasiswa=' . $id_beasiswa);
    }
}

// Siapkan data
$simulasiData = [
    'id_mahasiswa'        => Session::getId(),
    'id_beasiswa'         => $id_beasiswa,
    'prestasi'            => $prestasi,
    'motivasi'            => $motivasi,
    'ikut_organisasi'     => $ikut_organisasi,
    'status_beasiswa_lain' => $status_beasiswa_lain,
    'aktif_kuliah'        => $aktif_kuliah,
];

$ortuData = [
    'nama_ortu'        => $nama_ortu,
    'penghasilan_ortu' => $penghasilan_ortu,
    'pekerjaan_ortu'   => $pekerjaan_ortu,
    'jml_tanggungan'   => $jml_tanggungan,
    'sktm'             => $sktm,
];

// Panggil Simulasi::store() — sudah pakai transaksi internal
$simulasi = new Simulasi($db);
$id_simulasi = $simulasi->store($simulasiData, $ortuData, $uploadedFiles);

if ($id_simulasi !== false) {
    Session::setFlash('success', 'Simulasi berhasil dikirim! Pantau hasilnya di halaman Pesan.');
    Response::redirectTo(BASE_URL . '/frontend/mahasiswa/pesan.php');
} else {
    Session::setFlash('error', 'Gagal mengirim simulasi. Silakan coba lagi.');
    Response::redirectTo(BASE_URL . '/frontend/mahasiswa/simulasi.php?id_beasiswa=' . $id_beasiswa);
}
