<?php
declare(strict_types=1);
require_once '../config/app.php';
require_once HELPERS_PATH . 'Session.php';
require_once HELPERS_PATH . 'Response.php';
require_once HELPERS_PATH . 'Validator.php';
require_once CONFIG_PATH . 'Database.php';
require_once CLASSES_PATH . 'User.php';
require_once CLASSES_PATH . 'Mahasiswa.php';
require_once CLASSES_PATH . 'Prodi.php';

Session::start();

// Sudah login → redirect ke dashboard
if (Session::isLoggedIn()) {
    $role = Session::getRole();
    if ($role === 'mahasiswa')
        Response::redirectTo(BASE_URL . '/frontend/mahasiswa/beranda.php');
    if ($role === 'mitra')
        Response::redirectTo(BASE_URL . '/frontend/mitra/beranda.php');
    if ($role === 'admin')
        Response::redirectTo(BASE_URL . '/frontend/admin/dashboard.php');
}

$db = Database::getInstance()->getConnection();

// ========== Proses POST ==========
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $nim = trim($_POST['NIM'] ?? '');
    $id_prodi = (int) ($_POST['id_prodi'] ?? 0);
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';

    // Validasi menggunakan Validator
    $v = new Validator();
    $v->required('nama', $nama)
        ->required('email', $email)
        ->email('email', $email)
        ->required('NIM', $nim)
        ->required('id_prodi', $id_prodi === 0 ? '' : (string) $id_prodi)
        ->required('password', $password)
        ->minLength('password', $password, 8);

    if ($v->fails()) {
        $errors = $v->getErrors();
        $firstError = reset($errors);
        Session::setFlash('error', $firstError[0]);
        Response::redirectTo(BASE_URL . '/auth/register.php');
    }

    // Cek konfirmasi password cocok
    if ($password !== $password_confirm) {
        Session::setFlash('error', 'Password dan konfirmasi password tidak cocok.');
        Response::redirectTo(BASE_URL . '/auth/register.php');
    }

    // Register mahasiswa
    $mahasiswa = new Mahasiswa($db);

    // Cek email sudah terdaftar
    $existing = $mahasiswa->findByNIM($nim);
    if ($existing) {
        Session::setFlash('error', 'NIM sudah terdaftar di sistem.');
        Response::redirectTo(BASE_URL . '/auth/register.php');
    }

    $data = [
        'NIM' => $nim,
        'nama' => $nama,
        'id_prodi' => $id_prodi,
        'semester' => 1,
        'angkatan' => (int) date('Y'),
        'IPK' => 0.00,
        'jenis_kelamin' => 'L',
        'email' => $email,
        'password' => $password,
    ];

    $success = $mahasiswa->register($data);

    if ($success) {
        Session::setFlash('success', 'Registrasi berhasil! Silakan masuk dengan akun Anda.');
        Response::redirectTo(BASE_URL . '/auth/login.php');
    } else {
        Session::setFlash('error', 'Registrasi gagal. Email mungkin sudah terdaftar.');
        Response::redirectTo(BASE_URL . '/auth/register.php');
    }
}

// Query prodi untuk dropdown
$listProdi = (new Prodi($db))->getAll();

$pageTitle = 'Buat Akun | ' . APP_NAME;
$pageDescription = 'Daftar akun mahasiswa untuk mengakses layanan Beasiswa POLIJE.';
?>
<?php require_once '../frontend/layout/header.php'; ?>

<!-- ========== KONTEN UTAMA ========== -->
<div class="auth-page" id="register-page">
    <div class="auth-card">
        <!-- Brand -->
        <a href="<?= BASE_URL ?>/" class="auth-brand">
            <img src="<?= BASE_URL ?>/assets/img/logo polije.png" alt="Logo"
                style="height: 40px; width: auto; object-fit: contain;">
            <span
                style="font-weight: 800; color: var(--color-primary); font-size: 1.25rem; letter-spacing: -0.5px;">Beasiswa
                POLIJE</span>
        </a>

        <h1 class="auth-title">Buat Akun</h1>
        <p class="auth-subtitle">Daftar untuk mengakses layanan beasiswa</p>

        <!-- Flash message -->
        <?php $flashError = Session::getFlash('error'); ?>
        <?php if ($flashError): ?>
            <div class="alert alert-danger auth-alert" role="alert"><?= htmlspecialchars($flashError) ?></div>
        <?php endif; ?>

        <form id="form-register" method="POST" action="<?= BASE_URL ?>/auth/register.php">
            <!-- Nama Lengkap -->
            <div class="mb-3">
                <label for="input-nama" class="form-label">Nama Lengkap</label>
                <input type="text" class="form-control" id="input-nama" name="nama" placeholder="Masukkan nama lengkap"
                    required>
            </div>

            <!-- Email -->
            <div class="mb-3">
                <label for="input-email" class="form-label">Email</label>
                <input type="email" class="form-control" id="input-email" name="email" placeholder="contoh@email.com"
                    required>
            </div>

            <!-- NIM -->
            <div class="mb-3">
                <label for="input-nim" class="form-label">NIM</label>
                <input type="text" class="form-control" id="input-nim" name="NIM" placeholder="Masukkan NIM" required>
            </div>

            <!-- Program Studi -->
            <div class="mb-3">
                <label for="select-prodi" class="form-label">Program Studi</label>
                <select class="form-select" id="select-prodi" name="id_prodi" required>
                    <option value="" disabled selected>Pilih Program Studi</option>
                    <?php foreach ($listProdi as $prodi): ?>
                        <option value="<?= $prodi['id_prodi'] ?>">
                            <?= htmlspecialchars($prodi['nama_prodi'] . ' - ' . $prodi['nama_jurusan']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Password -->
            <div class="mb-3">
                <label for="input-password" class="form-label">Password</label>
                <div class="input-password-wrapper">
                    <input type="password" class="form-control" id="input-password" name="password"
                        placeholder="Minimal 8 karakter" minlength="8" required>
                    <button type="button" class="btn-toggle-password" data-target="input-password"
                        aria-label="Tampilkan password">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
            </div>

            <!-- Konfirmasi Password -->
            <div class="mb-4">
                <label for="input-password-confirm" class="form-label">Konfirmasi Password</label>
                <div class="input-password-wrapper">
                    <input type="password" class="form-control" id="input-password-confirm" name="password_confirm"
                        placeholder="Ulangi password" minlength="8" required>
                    <button type="button" class="btn-toggle-password" data-target="input-password-confirm"
                        aria-label="Tampilkan konfirmasi password">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
            </div>

            <!-- Submit -->
            <button type="submit" class="btn btn-auth-submit" id="btn-register-submit">Daftar</button>
        </form>

        <!-- Login link -->
        <p class="auth-footer-text">
            Sudah punya akun? <a href="<?= BASE_URL ?>/auth/login.php" id="link-masuk">Masuk</a>
        </p>
    </div>
</div>
<!-- ========== END KONTEN ========== -->

<!-- ========== FOOTER ========== -->
<footer class="footer-landing" id="footer-landing" style="background-color: var(--color-light);">
    <div class="container">
        Copyright &copy; 2026 Beasiswa POLIJE
    </div>
</footer>
<!-- ========== END FOOTER ========== -->