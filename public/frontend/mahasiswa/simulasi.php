<?php
/**
 * Simulasi Beasiswa — Form simulasi pendaftaran beasiswa
 */
declare(strict_types=1);
require_once '../../../config/app.php';
require_once CONFIG_PATH . 'Database.php';
require_once HELPERS_PATH . 'Session.php';
require_once HELPERS_PATH . 'Response.php';
require_once CLASSES_PATH . 'Beasiswa.php';
require_once CLASSES_PATH . 'Mahasiswa.php';
require_once CLASSES_PATH . 'Prodi.php';

Session::start();
Session::requireLogin();
Session::requireRole('mahasiswa');

$db = Database::getInstance()->getConnection();
$id_beasiswa = (int) ($_GET['id_beasiswa'] ?? 0);
$beasiswa = (new Beasiswa($db))->getById($id_beasiswa);

if (!$beasiswa) {
    Response::redirectTo(BASE_URL . '/frontend/mahasiswa/beranda.php');
}

$mahasiswa = (new Mahasiswa($db))->getById(Session::getId());
$listProdi = (new Prodi($db))->getAll();

$pageTitle = 'Simulasi Beasiswa | ' . APP_NAME;
$pageDescription = 'Simulasi pendaftaran beasiswa untuk mengetahui kelayakan Anda.';
$activePage = 'beranda';

// Header
require_once __DIR__ . '/../layout/header.php';

// Navbar Mahasiswa
require_once __DIR__ . '/../layout/navbar-mahasiswa.php';
?>

<!-- ========== KONTEN UTAMA ========== -->
<div class="container py-4" style="max-width: 800px;">
    <div class="simulasi-header">
        <h2><i class="bi bi-bar-chart-steps"></i> Simulasi Pendaftaran Beasiswa</h2>
        <p><?= htmlspecialchars($beasiswa['nama_beasiswa']) ?></p>
    </div>

    <form action="<?= BASE_URL ?>/backend/simulasi/store.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id_beasiswa" value="<?= $beasiswa['id_beasiswa'] ?>">

        <!-- Informasi Pribadi -->
        <div class="form-section">
            <div class="section-title"><i class="bi bi-person-vcard"></i> Informasi Pribadi</div>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" class="form-control" value="<?= htmlspecialchars($mahasiswa['nama']) ?>"
                        readonly>
                </div>
                <div class="col-md-6">
                    <label class="form-label">NIM</label>
                    <input type="text" class="form-control"
                        value="<?= htmlspecialchars((string) ($mahasiswa['NIM'] ?? '')) ?>" readonly>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Program Studi</label>
                    <?php
                    $namaProdi = 'Tidak diketahui';
                    foreach ($listProdi as $p) {
                        if ($p['id_prodi'] == $mahasiswa['id_prodi']) {
                            $namaProdi = $p['nama_prodi'] . ' - ' . ($p['nama_jurusan'] ?? '');
                            break;
                        }
                    }
                    ?>
                    <input type="text" class="form-control" value="<?= htmlspecialchars($namaProdi) ?>" readonly>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Angkatan</label>
                    <input type="text" class="form-control"
                        value="<?= htmlspecialchars((string) ($mahasiswa['angkatan'] ?? '-')) ?>" readonly>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Semester</label>
                    <input type="text" class="form-control"
                        value="<?= htmlspecialchars((string) ($mahasiswa['semester'] ?? '-')) ?>" readonly>
                </div>
                <div class="col-md-6">
                    <label class="form-label">IPK Terakhir</label>
                    <input type="text" class="form-control"
                        value="<?= htmlspecialchars((string) ($mahasiswa['IPK'] ?? '-')) ?>" readonly>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Jenis Kelamin</label>
                    <input type="text" class="form-control"
                        value="<?= htmlspecialchars(ucfirst($mahasiswa['jenis_kelamin'] ?? '-')) ?>" readonly>
                </div>
                <small class="text-danger">* Data diambil dari profile mahasiswa</small>
            </div>
        </div>

        <!-- Informasi Orang Tua -->
        <div class="form-section">
            <div class="section-title"><i class="bi bi-people"></i> Informasi Orang Tua / Wali</div>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Nama Orang Tua / Wali</label>
                    <input type="text" name="nama_ortu" class="form-control" placeholder="Masukkan nama orang tua/wali"
                        required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Pekerjaan Orang Tua / Wali</label>
                    <input type="text" name="pekerjaan_ortu" class="form-control"
                        placeholder="Masukkan pekerjaan orang tua/wali" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Perkiraan Penghasilan / Bulan</label>
                    <select class="form-select" name="penghasilan_ortu" required>
                        <option value="500000">&lt; Rp 1.000.000</option>
                        <option value="2000000">Rp 1.000.000 - Rp 3.000.000</option>
                        <option value="4000000">Rp 3.000.000 - Rp 5.000.000</option>
                        <option value="7500000">&gt; Rp 5.000.000</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Jumlah Tanggungan Keluarga</label>
                    <input type="number" class="form-control" name="tanggungan_ortu" placeholder="Contoh: 3" min="1"
                        required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">SKTM (Surat Keterangan Tidak Mampu)</label>
                    <select class="form-select" name="sktm" required>
                        <option value="" disabled selected>Pilih</option>
                        <option value="1">Ada</option>
                        <option value="0">Tidak Ada</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Informasi Lainnya -->
        <div class="form-section">
            <div class="section-title"><i class="bi bi-info-circle"></i> Informasi Lainnya</div>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Aktif Kuliah</label>
                    <select class="form-select" name="aktif_kuliah" required>
                        <option value="" disabled selected>Pilih Status</option>
                        <option value="1">Ya, Mahasiswa Aktif</option>
                        <option value="0">Tidak Aktif / Cuti</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Status Beasiswa Lain</label>
                    <select class="form-select" name="status_beasiswa_lain" required>
                        <option value="" disabled selected>Pilih Status</option>
                        <option value="1">Sedang Menerima Beasiswa Lain</option>
                        <option value="0">Tidak Menerima Beasiswa Lain</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Pengalaman Organisasi</label>
                    <select class="form-select" name="ikut_organisasi" required>
                        <option value="" disabled selected>Pilih Status</option>
                        <option value="1">Ya, Aktif Berorganisasi</option>
                        <option value="0">Tidak Ikut Organisasi</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Prestasi Akademik / Non-Akademik</label>
                    <textarea class="form-control" rows="2" name="prestasi"
                        placeholder="Tuliskan prestasi atau 'Tidak ada'"></textarea>
                </div>
            </div>
        </div>

        <!-- Motivasi -->
        <div class="form-section">
            <div class="section-title"><i class="bi bi-chat-quote"></i> Motivasi</div>
            <div class="mb-3">
                <label class="form-label">Mengapa Anda mendaftar beasiswa ini?</label>
                <textarea class="form-control" name="motivasi" rows="4"
                    placeholder="Tuliskan motivasi Anda mendaftar beasiswa ini dengan jelas!" required></textarea>
            </div>
        </div>

        <!-- Upload Dokumen -->
        <div class="form-section">
            <div class="section-title"><i class="bi bi-cloud-arrow-up"></i> Upload Dokumen</div>
            <div class="upload-area" onclick="document.getElementById('file-upload').click()">
                <i class="bi bi-cloud-arrow-up-fill upload-icon"></i>
                <div class="upload-text">Klik atau seret file ke sini untuk upload dokumen</div>
                <div class="upload-hint">Format: PDF (Max 5MB per file). Anda dapat memilih beberapa file.</div>
                <input type="file" id="file-upload" name="file_simulasi[]" accept=".pdf" multiple style="display: none;"
                    required>
            </div>
            <!-- File list preview -->
            <div class="mt-3 d-none" id="file-preview-container">
                <div class="alert alert-secondary py-2 px-3 mb-2 d-flex justify-content-between align-items-center">
                    <span class="small"><i class="bi bi-file-earmark-pdf me-2 text-danger"></i>dokumen.pdf</span>
                    <button type="button" class="btn-close" style="font-size: .65rem;"></button>
                </div>
            </div>
        </div>

        <button type="submit" class="btn-submit">
            <i class="bi bi-send-fill"></i> Kirim Simulasi Pendaftaran
        </button>
    </form>
</div>
<!-- ========== END KONTEN ========== -->

<?php
// Footer
require_once __DIR__ . '/../layout/footer.php';
?>