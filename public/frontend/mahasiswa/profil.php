<?php
/**
 * Profil Mahasiswa — Pengaturan akun dan ganti password
 */
declare(strict_types=1);
require_once '../../config/app.php';
require_once CONFIG_PATH . 'Database.php';
require_once HELPERS_PATH . 'Session.php';
require_once HELPERS_PATH . 'Response.php';
require_once CLASSES_PATH . 'Mahasiswa.php';
require_once CLASSES_PATH . 'Prodi.php';

Session::start();
Session::requireLogin();
Session::requireRole('mahasiswa');

$db = Database::getInstance()->getConnection();
$mahasiswa = (new Mahasiswa($db))->getById(Session::getId());
$listProdi = (new Prodi($db))->getAll();

$pageTitle = 'Profil | ' . APP_NAME;
$pageDescription = 'Lihat dan kelola informasi akun mahasiswa Anda.';
$activePage = 'profil';

// Header
require_once __DIR__ . '/../layout/header.php';

// Navbar Mahasiswa
require_once __DIR__ . '/../layout/navbar-mahasiswa.php';
?>

<!-- ========== KONTEN UTAMA ========== -->
<div class="container py-5" style="max-width: 1000px;">
    <h1 class="page-title d-flex align-items-center gap-2">
        <i class="bi bi-gear"></i> Pengaturan Akun
    </h1>

    <!-- flash message -->
    <?php if ($msg = Session::getFlash('success')): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?= htmlspecialchars($msg) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    <?php if ($msg = Session::getFlash('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <?= htmlspecialchars($msg) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Form Profil -->
    <form action="<?= BASE_URL ?>/backend/akun/update-profil.php" method="POST">
        <input type="hidden" name="action" value="update_profil">

        <!-- Card Informasi Umum -->
        <div class="profile-card p-4 p-md-5 mb-4">
            <h5 class="fw-bold mb-3 d-flex align-items-center gap-2" style="color: var(--color-primary-dark);">
                <i class="bi bi-person-vcard"></i> Informasi Umum
            </h5>
            <hr class="mb-4 mt-0" style="border-top: 2px solid var(--color-light); opacity: 1;">

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-bold">Nama Lengkap</label>
                    <input type="text" class="form-control" name="nama"
                        value="<?= htmlspecialchars($mahasiswa['nama']) ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Email</label>
                    <input type="email" class="form-control bg-light" name="email"
                        value="<?= htmlspecialchars($mahasiswa['email']) ?>" readonly>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">NIM</label>
                    <input type="text" class="form-control"
                        value="<?= htmlspecialchars((string) ($mahasiswa['NIM'] ?? '')) ?>" disabled>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Jenis Kelamin</label>
                    <select class="form-select" name="jenis_kelamin" required>
                        <option value="L" <?= $mahasiswa['jenis_kelamin'] === 'L' ? 'selected' : '' ?>>
                            Laki-laki</option>
                        <option value="P" <?= $mahasiswa['jenis_kelamin'] === 'P' ? 'selected' : '' ?>>
                            Perempuan</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Card Informasi Akademik -->
        <div class="profile-card p-4 p-md-5 mb-4">
            <h5 class="fw-bold mb-3 d-flex align-items-center gap-2" style="color: var(--color-primary-dark);">
                <i class="bi bi-mortarboard"></i> Informasi Akademik
            </h5>
            <hr class="mb-4 mt-0" style="border-top: 2px solid var(--color-light); opacity: 1;">

            <div class="row g-3">
                <div class="col-md-12">
                    <label class="form-label fw-bold">Program Studi</label>
                    <select class="form-select" name="id_prodi" required>
                        <option value="" disabled <?= empty($mahasiswa['id_prodi']) ? 'selected' : '' ?>>Pilih Program
                            Studi</option>
                        <?php foreach ($listProdi as $prodi): ?>
                            <option value="<?= $prodi['id_prodi'] ?>" <?= $prodi['id_prodi'] == $mahasiswa['id_prodi'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($prodi['nama_prodi'] . ' - ' . $prodi['nama_jurusan']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">Angkatan</label>
                    <input type="number" class="form-control" name="angkatan"
                        value="<?= htmlspecialchars((string) $mahasiswa['angkatan']) ?>" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">Semester</label>
                    <input type="number" class="form-control" name="semester"
                        value="<?= htmlspecialchars((string) $mahasiswa['semester']) ?>" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">IPK Terakhir</label>
                    <input type="number" step="0.01" class="form-control" name="IPK"
                        value="<?= htmlspecialchars((string) ($mahasiswa['IPK'] ?? '')) ?>" required>
                </div>
                <div class="col-12 mt-4 text-end">
                    <button type="submit" class="btn btn-primary fw-semibold px-4"><i class="bi bi-save me-2"></i>Simpan
                        Perubahan</button>
                </div>
            </div>
        </div>
    </form>

    <!-- Card Ganti Password -->
    <div class="profile-card p-4 p-md-5 mb-4">
        <h5 class="fw-bold mb-3 d-flex align-items-center gap-2" style="color: var(--color-primary-dark);">
            <i class="bi bi-shield-lock"></i> Ganti Password
        </h5>
        <hr class="mb-4 mt-0" style="border-top: 2px solid var(--color-light); opacity: 1;">

        <form action="<?= BASE_URL ?>/backend/akun/update-profil.php" method="POST">
            <input type="hidden" name="action" value="ganti_password">
            <div class="row g-3">
                <div class="col-md-12">
                    <label for="password_lama" class="form-label">Password Lama</label>
                    <input type="password" class="form-control" id="password_lama" name="password_lama" required>
                </div>
                <div class="col-md-6">
                    <label for="password_baru" class="form-label">Password Baru</label>
                    <input type="password" class="form-control" id="password_baru" name="password_baru" minlength="8"
                        required>
                    <div class="form-text">Password minimal 8 karakter.</div>
                </div>
                <div class="col-md-6">
                    <label for="password_konfirmasi" class="form-label">Konfirmasi Password Baru</label>
                    <input type="password" class="form-control" id="password_konfirmasi" name="password_konfirmasi"
                        minlength="8" required>
                </div>
                <div class="col-12 mt-3 text-end">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-key-fill me-2"></i>Perbarui
                        Password</button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- ========== END KONTEN ========== -->

<?php
// Footer
require_once __DIR__ . '/../layout/footer.php';
?>