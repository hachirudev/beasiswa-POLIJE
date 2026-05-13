<?php
/**
 * Unggah Beasiswa Admin — Form unggah beasiswa oleh admin
 */
declare(strict_types=1);
require_once '../../../config/app.php';
require_once CONFIG_PATH . 'Database.php';
require_once HELPERS_PATH . 'Session.php';
require_once HELPERS_PATH . 'Response.php';
require_once CLASSES_PATH . 'Tag.php';

Session::start();
Session::requireLogin();
Session::requireRole('admin');

$db = Database::getInstance()->getConnection();
$listTag = (new Tag($db))->getAll();

// Group tags
$tagsByKategori = [];
foreach ($listTag as $t) {
    $tagsByKategori[$t['kategori_tag']][] = $t;
}

$pageTitle = 'Unggah Beasiswa — Admin ' . APP_NAME;
$pageDescription = 'Unggah beasiswa baru langsung oleh admin.';
$activePage = 'kelola-beasiswa';

require_once __DIR__ . '/../layout/header.php';
require_once __DIR__ . '/../layout/sidebar-admin.php';
?>

<div class="main-content-admin">
    <div class="topbar-admin">
        <h1 class="page-title">Unggah Beasiswa</h1>
        <a href="<?= BASE_URL ?>/frontend/admin/kelola-beasiswa.php" class="btn btn-sm btn-outline-secondary fw-semibold"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
    </div>

    <div class="p-4">
        <div class="card border-0 shadow-sm rounded-4" style="max-width: 850px;">
            <div class="card-body p-4 p-md-5">
                <form action="<?= BASE_URL ?>/backend/beasiswa/store.php" method="POST" enctype="multipart/form-data">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Nama Beasiswa</label>
                            <input type="text" name="nama_beasiswa" class="form-control" placeholder="Contoh: Beasiswa KIP Kuliah 2024" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Nama Penyelenggara</label>
                            <input type="text" name="nama_penyelenggara" class="form-control" placeholder="Contoh: Kemendikbudristek" required>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold">Deskripsi Singkat</label>
                            <input type="text" name="deskripsi_singkat" class="form-control" placeholder="Penjelasan singkat 1-2 kalimat (Maks 150 karakter)" maxlength="150" required>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold">Deskripsi Lengkap</label>
                            <textarea class="form-control" name="deskripsi_lengkap" rows="5" placeholder="Penjelasan lengkap mengenai program beasiswa ini, latar belakang, dan tujuan program." required></textarea>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold">Informasi Beasiswa (Persyaratan)</label>
                            <textarea class="form-control" name="informasi_beasiswa" rows="5" placeholder="Sebutkan persyaratan pendaftar, fasilitas yang didapat, tahapan seleksi, dll." required></textarea>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Tanggal Dibuka</label>
                            <input type="date" name="tgl_buka" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Tanggal Ditutup</label>
                            <input type="date" name="tgl_tutup" class="form-control" required>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold">Link Pendaftaran Eksternal (Opsional)</label>
                            <input type="url" name="link_pendaftaran" class="form-control" placeholder="https://...">
                            <div class="form-text">Isi jika mahasiswa harus mendaftar ke website eksternal penyelenggara.</div>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold">Upload Poster</label>
                            <input type="file" name="poster" class="form-control" accept="image/jpeg,image/png,image/jpg" required>
                            <div class="form-text">Format: JPG, JPEG, PNG. Maks 2MB. Resolusi disarankan 600x300 pixel.</div>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold d-block mb-3">Pilih Tag Beasiswa</label>
                            <div class="row g-3">
                                <?php foreach ($tagsByKategori as $kategori => $tags): ?>
                                <div class="col-md-4">
                                    <div class="fw-semibold mb-2" style="font-size:0.9rem;color:var(--color-primary);"><?= htmlspecialchars(ucwords(str_replace('_', ' ', $kategori))) ?></div>
                                    <?php foreach ($tags as $tag): ?>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="tags[]" value="<?= $tag['id_tag'] ?>" id="tag_<?= $tag['id_tag'] ?>">
                                        <label class="form-check-label" for="tag_<?= $tag['id_tag'] ?>"><?= htmlspecialchars($tag['nama_tag']) ?></label>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <div class="col-12 text-end mt-4">
                            <a href="<?= BASE_URL ?>/frontend/admin/kelola-beasiswa.php" class="btn btn-light px-4 me-2">Batal</a>
                            <button type="submit" class="btn px-4 fw-semibold" style="background-color:var(--color-primary);border-color:var(--color-primary);color:#fff;">
                                <i class="bi bi-cloud-arrow-up-fill me-2"></i>Simpan & Unggah
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= BASE_URL ?>/assets/js/main.js"></script>
</body>
</html>
