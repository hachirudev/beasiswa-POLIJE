<?php
declare(strict_types=1);
require_once '../../config/app.php';
require_once HELPERS_PATH . 'Session.php';
require_once HELPERS_PATH . 'Response.php';
require_once CONFIG_PATH  . 'Database.php';
require_once CLASSES_PATH . 'User.php';
require_once CLASSES_PATH . 'Mahasiswa.php';
require_once CLASSES_PATH . 'Mitra.php';
require_once CLASSES_PATH . 'Admin.php';

Session::start();

// Sudah login → redirect ke dashboard
if (Session::isLoggedIn()) {
    $role = Session::getRole();
    if ($role === 'mahasiswa') Response::redirectTo(BASE_URL . '/frontend/mahasiswa/beranda.php');
    if ($role === 'mitra')     Response::redirectTo(BASE_URL . '/frontend/mitra/beranda.php');
    if ($role === 'admin')     Response::redirectTo(BASE_URL . '/frontend/admin/dashboard.php');
}

// ========== Proses POST ==========
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $role     = trim($_POST['role']     ?? '');
    $email    = trim($_POST['email']    ?? '');
    $password = $_POST['password']      ?? '';

    // Validasi tidak boleh kosong
    if ($email === '' || $password === '' || $role === '') {
        Session::setFlash('error', 'Email, password, dan role wajib diisi.');
        Response::redirectTo(BASE_URL . '/auth/login.php');
    }

    // Validasi role
    if (!in_array($role, ['mahasiswa', 'mitra', 'admin'], true)) {
        Session::setFlash('error', 'Role tidak valid.');
        Response::redirectTo(BASE_URL . '/auth/login.php');
    }

    $db = Database::getInstance()->getConnection();

    // Inisialisasi class sesuai role
    $userObj = match ($role) {
        'mahasiswa' => new Mahasiswa($db),
        'mitra'     => new Mitra($db),
        'admin'     => new Admin($db),
    };

    $user = $userObj->login($email, $password);

    if ($user) {
        // Simpan session — nama field sesuai kolom database
        switch ($role) {
            case 'mahasiswa':
                Session::set('id', $user['id_mahasiswa']);
                Session::set('nama', $user['nama']);
                break;
            case 'mitra':
                Session::set('id', $user['id_mitra']);
                Session::set('nama', $user['nama_mitra']);
                break;
            case 'admin':
                Session::set('id', $user['id_admin']);
                Session::set('nama', $user['nama_admin']);
                break;
        }
        Session::set('role', $role);

        // Redirect ke dashboard sesuai role
        $dashboards = [
            'mahasiswa' => '/frontend/mahasiswa/beranda.php',
            'mitra'     => '/frontend/mitra/beranda.php',
            'admin'     => '/frontend/admin/dashboard.php',
        ];
        Response::redirectTo(BASE_URL . $dashboards[$role]);
    } else {
        Session::setFlash('error', 'Email atau password salah.');
        Response::redirectTo(BASE_URL . '/auth/login.php');
    }
}

$pageTitle = 'Login — ' . APP_NAME;
$pageDescription = 'Masuk ke akun Beasiswa POLIJE untuk mengakses layanan beasiswa.';
?>
<?php require_once '../frontend/layout/header.php'; ?>

<!-- ========== KONTEN UTAMA ========== -->
<div class="auth-page" id="login-page">
    <div class="auth-card">
        <!-- Brand -->
        <a href="<?= BASE_URL ?>/" class="auth-brand">
            <span class="brand-icon">
                <i class="bi bi-mortarboard-fill"></i>
            </span>
            Beasiswa POLIJE
        </a>

        <h1 class="auth-title">Masuk</h1>
        <p class="auth-subtitle">Masuk ke akun Anda untuk melanjutkan</p>

        <!-- Flash message -->
        <?php $flashError = Session::getFlash('error'); ?>
        <?php if ($flashError): ?>
        <div class="alert alert-danger auth-alert" role="alert"><?= htmlspecialchars($flashError) ?></div>
        <?php endif; ?>

        <?php $flashSuccess = Session::getFlash('success'); ?>
        <?php if ($flashSuccess): ?>
        <div class="alert alert-success auth-alert" role="alert"><?= htmlspecialchars($flashSuccess) ?></div>
        <?php endif; ?>

        <form id="form-login" method="POST" action="<?= BASE_URL ?>/auth/login.php">
            <!-- Email -->
            <div class="mb-3">
                <label for="input-email" class="form-label">Email</label>
                <input type="email" class="form-control" id="input-email" name="email"
                       placeholder="contoh@email.com" required>
            </div>

            <!-- Password -->
            <div class="mb-3">
                <label for="input-password" class="form-label">Password</label>
                <div class="input-password-wrapper">
                    <input type="password" class="form-control" id="input-password" name="password"
                           placeholder="Masukkan password" required>
                    <button type="button" class="btn-toggle-password" id="btn-toggle-password"
                            aria-label="Tampilkan password">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
            </div>

            <!-- Role selector -->
            <div class="mb-3">
                <label class="form-label">Masuk sebagai</label>
                <div class="role-selector" id="role-selector">
                    <label class="role-option active" data-role="mahasiswa">
                        <input type="radio" name="role" value="mahasiswa" checked>
                        <i class="bi bi-mortarboard role-icon"></i>
                        <span class="role-label">Mahasiswa</span>
                    </label>
                    <label class="role-option" data-role="mitra">
                        <input type="radio" name="role" value="mitra">
                        <i class="bi bi-building role-icon"></i>
                        <span class="role-label">Mitra</span>
                    </label>
                    <label class="role-option" data-role="admin">
                        <input type="radio" name="role" value="admin">
                        <i class="bi bi-shield-check role-icon"></i>
                        <span class="role-label">Admin</span>
                    </label>
                </div>
            </div>

            <!-- Submit -->
            <button type="submit" class="btn btn-auth-submit" id="btn-login-submit">Masuk</button>
        </form>

        <!-- Register link -->
        <p class="auth-footer-text">
            Belum punya akun? <a href="<?= BASE_URL ?>/auth/register.php" id="link-daftar">Daftar Sekarang</a>
        </p>
    </div>
</div>
<!-- ========== END KONTEN ========== -->

<?php require_once '../frontend/layout/footer.php'; ?>
