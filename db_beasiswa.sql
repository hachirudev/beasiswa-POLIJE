-- ============================================
-- DATABASE: db_beasiswa
-- Sistem Informasi Beasiswa - Politeknik Negeri Jember
-- Engine: InnoDB | Charset: utf8mb4
-- ============================================

SET FOREIGN_KEY_CHECKS = 0;

DROP DATABASE IF EXISTS db_beasiswa;
CREATE DATABASE db_beasiswa CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE db_beasiswa;

-- ============================================
-- 1. TABEL PRODI
-- ============================================
CREATE TABLE prodi (
  id_prodi INT AUTO_INCREMENT PRIMARY KEY,
  nama_prodi VARCHAR(100) NOT NULL,
  nama_jurusan VARCHAR(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- 2. TABEL MAHASISWA
-- ============================================
CREATE TABLE mahasiswa (
  id_mahasiswa INT AUTO_INCREMENT PRIMARY KEY,
  NIM VARCHAR(20) NOT NULL UNIQUE,
  nama VARCHAR(100) NOT NULL,
  id_prodi INT NOT NULL,
  semester TINYINT UNSIGNED NOT NULL,
  angkatan YEAR NOT NULL,
  IPK DECIMAL(3,2) NOT NULL,
  jenis_kelamin ENUM('L','P') NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role ENUM('mahasiswa') NOT NULL DEFAULT 'mahasiswa',
  CONSTRAINT fk_mahasiswa_prodi FOREIGN KEY (id_prodi) REFERENCES prodi(id_prodi) ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- 3. TABEL MITRA
-- ============================================
CREATE TABLE mitra (
  id_mitra INT AUTO_INCREMENT PRIMARY KEY,
  nama_mitra VARCHAR(150) NOT NULL,
  bidang_usaha VARCHAR(100) NOT NULL,
  telepon VARCHAR(20) NOT NULL,
  website VARCHAR(255) NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role ENUM('mitra') NOT NULL DEFAULT 'mitra'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- 4. TABEL ADMIN
-- ============================================
CREATE TABLE admin (
  id_admin INT AUTO_INCREMENT PRIMARY KEY,
  nama_admin VARCHAR(100) NOT NULL,
  jabatan VARCHAR(100) NOT NULL,
  telepon VARCHAR(20) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role ENUM('admin') NOT NULL DEFAULT 'admin'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- 5. TABEL TAG
-- ============================================
CREATE TABLE tag (
  id_tag INT AUTO_INCREMENT PRIMARY KEY,
  nama_tag VARCHAR(100) NOT NULL,
  kategori_tag ENUM('Jenjang','Tipe Pendanaan','Tipe Beasiswa','Prestasi','SKTM','IPK','Semester') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- 6. TABEL BEASISWA
-- CHECK CONSTRAINT dihapus karena konflik dengan FK referential action di InnoDB.
-- Validasi digantikan oleh TRIGGER di bawah.
-- ============================================
CREATE TABLE beasiswa (
  id_beasiswa INT AUTO_INCREMENT PRIMARY KEY,
  nama_beasiswa VARCHAR(200) NOT NULL,
  nama_penyelenggara VARCHAR(150) NOT NULL,
  deskripsi_singkat VARCHAR(500) NOT NULL,
  deskripsi_lengkap TEXT NOT NULL,
  informasi_beasiswa TEXT NOT NULL,
  link_pendaftaran VARCHAR(500) NOT NULL,
  poster_url VARCHAR(500) NOT NULL,
  tgl_buka DATE NOT NULL,
  tgl_tutup DATE NOT NULL,
  status_pendaftaran ENUM('belum_dibuka','dibuka','ditutup') NOT NULL,
  status_verifikasi ENUM('pending','terverifikasi','ditolak') NOT NULL DEFAULT 'pending',
  upload_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  id_mitra INT NULL,
  id_admin INT NULL,
  CONSTRAINT fk_beasiswa_mitra FOREIGN KEY (id_mitra) REFERENCES mitra(id_mitra) ON UPDATE CASCADE ON DELETE SET NULL,
  CONSTRAINT fk_beasiswa_admin FOREIGN KEY (id_admin) REFERENCES admin(id_admin) ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- TRIGGER PENGGANTI CHECK CONSTRAINT
-- Memastikan tepat satu dari id_mitra atau id_admin berisi nilai
-- ============================================
DELIMITER $$

CREATE TRIGGER trg_beasiswa_uploader_insert
BEFORE INSERT ON beasiswa
FOR EACH ROW
BEGIN
  IF NOT (
    (NEW.id_mitra IS NOT NULL AND NEW.id_admin IS NULL) OR
    (NEW.id_mitra IS NULL AND NEW.id_admin IS NOT NULL)
  ) THEN
    SIGNAL SQLSTATE '45000'
    SET MESSAGE_TEXT = 'Tepat satu dari id_mitra atau id_admin harus berisi nilai.';
  END IF;
END$$

CREATE TRIGGER trg_beasiswa_uploader_update
BEFORE UPDATE ON beasiswa
FOR EACH ROW
BEGIN
  IF NOT (
    (NEW.id_mitra IS NOT NULL AND NEW.id_admin IS NULL) OR
    (NEW.id_mitra IS NULL AND NEW.id_admin IS NOT NULL)
  ) THEN
    SIGNAL SQLSTATE '45000'
    SET MESSAGE_TEXT = 'Tepat satu dari id_mitra atau id_admin harus berisi nilai.';
  END IF;
END$$

DELIMITER ;

-- ============================================
-- 7. TABEL BEASISWA_TAG (Junction)
-- ============================================
CREATE TABLE beasiswa_tag (
  id_beasiswa INT NOT NULL,
  id_tag INT NOT NULL,
  PRIMARY KEY (id_beasiswa, id_tag),
  CONSTRAINT fk_bt_beasiswa FOREIGN KEY (id_beasiswa) REFERENCES beasiswa(id_beasiswa) ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT fk_bt_tag FOREIGN KEY (id_tag) REFERENCES tag(id_tag) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- 8. TABEL SIMULASI
-- ============================================
CREATE TABLE simulasi (
  id_simulasi INT AUTO_INCREMENT PRIMARY KEY,
  id_mahasiswa INT NOT NULL,
  id_beasiswa INT NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  tgl_submit TIMESTAMP NULL,
  prestasi TEXT NULL,
  motivasi TEXT NULL,
  ikut_organisasi BOOLEAN NOT NULL DEFAULT FALSE,
  status_beasiswa_lain BOOLEAN NOT NULL DEFAULT FALSE,
  aktif_kuliah BOOLEAN NOT NULL DEFAULT TRUE,
  CONSTRAINT fk_simulasi_mahasiswa FOREIGN KEY (id_mahasiswa) REFERENCES mahasiswa(id_mahasiswa) ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT fk_simulasi_beasiswa FOREIGN KEY (id_beasiswa) REFERENCES beasiswa(id_beasiswa) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- 9. TABEL FILE_SIMULASI
-- ============================================
CREATE TABLE file_simulasi (
  id_file INT AUTO_INCREMENT PRIMARY KEY,
  id_simulasi INT NOT NULL,
  nama_file VARCHAR(255) NOT NULL,
  file_path VARCHAR(500) NOT NULL,
  upload_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_file_simulasi FOREIGN KEY (id_simulasi) REFERENCES simulasi(id_simulasi) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- 10. TABEL DATA_ORANG_TUA
-- ============================================
CREATE TABLE data_orang_tua (
  id_ortu INT AUTO_INCREMENT PRIMARY KEY,
  id_simulasi INT NOT NULL UNIQUE,
  nama_ortu VARCHAR(100) NOT NULL,
  penghasilan_ortu DECIMAL(15,2) NOT NULL,
  pekerjaan_ortu VARCHAR(100) NOT NULL,
  jml_tanggungan TINYINT UNSIGNED NOT NULL,
  sktm BOOLEAN NOT NULL DEFAULT FALSE,
  CONSTRAINT fk_ortu_simulasi FOREIGN KEY (id_simulasi) REFERENCES simulasi(id_simulasi) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- 11. TABEL HASIL_SIMULASI
-- ============================================
CREATE TABLE hasil_simulasi (
  id_hasil_simulasi INT AUTO_INCREMENT PRIMARY KEY,
  id_simulasi INT NOT NULL UNIQUE,
  id_admin INT NULL,
  tgl_review TIMESTAMP NULL,
  status_simulasi ENUM('pending','lulus','tidak_lulus') NOT NULL DEFAULT 'pending',
  skor DECIMAL(5,2) NULL,
  catatan_admin TEXT NULL,
  is_read BOOLEAN NOT NULL DEFAULT FALSE,
  CONSTRAINT fk_hasil_simulasi FOREIGN KEY (id_simulasi) REFERENCES simulasi(id_simulasi) ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT fk_hasil_admin FOREIGN KEY (id_admin) REFERENCES admin(id_admin) ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- 12. TABEL PUSTAKA
-- ============================================
CREATE TABLE pustaka (
  id_pustaka INT AUTO_INCREMENT PRIMARY KEY,
  id_admin INT NOT NULL,
  nama_dokumen VARCHAR(200) NOT NULL,
  deskripsi_dokumen TEXT NULL,
  preview_dokumen VARCHAR(500) NULL,
  file_path VARCHAR(500) NOT NULL,
  upload_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_pustaka_admin FOREIGN KEY (id_admin) REFERENCES admin(id_admin) ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- 13. TABEL FAQ
-- ============================================
CREATE TABLE faq (
  id_pertanyaan INT AUTO_INCREMENT PRIMARY KEY,
  id_admin INT NOT NULL,
  pertanyaan TEXT NOT NULL,
  jawaban TEXT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_faq_admin FOREIGN KEY (id_admin) REFERENCES admin(id_admin) ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- ============================================
-- DATA DUMMY
-- ============================================

-- PRODI (5 data)
INSERT INTO prodi (nama_prodi, nama_jurusan) VALUES
('Teknik Informatika', 'Teknologi Informasi'),
('Manajemen Informatika', 'Teknologi Informasi'),
('Teknik Energi Terbarukan', 'Teknik'),
('Akuntansi', 'Ekonomi'),
('Manajemen Agribisnis', 'Pertanian');

-- ADMIN (1 data)
-- Password default: password
INSERT INTO admin (nama_admin, jabatan, telepon, email, password) VALUES
('Admin Beasiswa Polije', 'Koordinator Beasiswa', '+62 8222 3333 444', 'admin@beasiswa.polije.ac.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- MITRA (2 data)
-- Password default: password
INSERT INTO mitra (nama_mitra, bidang_usaha, telepon, website, email, password) VALUES
('Prestasi Kita Foundation', 'Pendidikan & Sosial', '+62 812 3456 7890', 'https://prestasikita.com', 'admin@prestasikita.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('PT Djarum', 'FMCG & Pendidikan', '+62 21 5555 6666', 'https://djarumbeasiswaplus.org', 'beasiswa@djarum.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- MAHASISWA (3 data)
-- Password default: password
INSERT INTO mahasiswa (NIM, nama, id_prodi, semester, angkatan, IPK, jenis_kelamin, email, password) VALUES
('E41212345', 'Ahmad Rizki Pratama', 1, 5, 2022, 3.65, 'L', 'ahmad.rizki@student.polije.ac.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('E41212678', 'Siti Nurhaliza', 4, 3, 2023, 3.80, 'P', 'siti.nurhaliza@student.polije.ac.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('E41211999', 'Reza Fahlevi', 5, 6, 2021, 3.45, 'L', 'reza.fahlevi@student.polije.ac.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- TAG (10 data, semua kategori tercakup)
INSERT INTO tag (nama_tag, kategori_tag) VALUES
('D3', 'Jenjang'),
('D4', 'Jenjang'),
('S1', 'Jenjang'),
('S2', 'Jenjang'),
('Fully Funded', 'Tipe Pendanaan'),
('Partially Funded', 'Tipe Pendanaan'),
('Company/Institution Scholarship', 'Tipe Beasiswa'),
('Government Scholarship', 'Tipe Beasiswa'),
('Prestasi', 'Prestasi'),
('SKTM', 'SKTM');

-- BEASISWA (5 data, variasi status)
INSERT INTO beasiswa (nama_beasiswa, nama_penyelenggara, deskripsi_singkat, deskripsi_lengkap, informasi_beasiswa, link_pendaftaran, poster_url, tgl_buka, tgl_tutup, status_pendaftaran, status_verifikasi, id_mitra, id_admin) VALUES
(
  'Beasiswa Pendidikan Prestasi Kita BATCH #5',
  'Prestasi Kita Foundation',
  'Beasiswa pendidikan kategori Prestasi, Ekonomi, Yatim, Hafidz Al Quran, dan umum.',
  'Beasiswa Prestasi Kita memberikan bantuan dana pendidikan bagi mahasiswa berprestasi dan kurang mampu. Program ini telah berjalan selama 5 batch dan telah membantu ribuan mahasiswa di seluruh Indonesia.',
  '<h5>Persyaratan</h5><ul><li>WNI</li><li>IPK minimal 3.00</li><li>Aktif kuliah</li></ul><h5>Benefit</h5><ul><li>Biaya pendidikan penuh</li><li>Uang saku bulanan</li></ul>',
  'https://prestasikita.com/daftar',
  'assets/images/poster-beasiswa.png',
  '2024-02-12', '2024-05-12', 'dibuka', 'terverifikasi', 1, NULL
),
(
  'Beasiswa KIP Kuliah 2024',
  'Kemendikbudristek',
  'Beasiswa bagi mahasiswa dari keluarga kurang mampu untuk membiayai pendidikan di perguruan tinggi.',
  'KIP Kuliah adalah bantuan biaya pendidikan dari pemerintah bagi lulusan SMA/SMK/sederajat yang memiliki potensi akademik baik tetapi memiliki keterbatasan ekonomi.',
  '<h5>Persyaratan</h5><ul><li>Siswa SMA/SMK sederajat</li><li>Keterbatasan ekonomi</li><li>NISN, NPSN, NIK valid</li></ul><h5>Benefit</h5><ul><li>Bebas biaya kuliah</li><li>Biaya hidup Rp 700.000/bulan</li></ul>',
  'https://kip-kuliah.kemdikbud.go.id',
  'assets/images/poster-beasiswa.png',
  '2024-01-15', '2024-03-31', 'ditutup', 'terverifikasi', NULL, 1
),
(
  'Beasiswa Djarum Foundation',
  'PT Djarum',
  'Beasiswa bagi mahasiswa berprestasi semester 4 ke atas untuk pengembangan soft skills dan kepemimpinan.',
  'Djarum Beasiswa Plus memberikan kesempatan bagi mahasiswa semester 4 untuk mengembangkan soft skills melalui berbagai pelatihan dan kegiatan.',
  '<h5>Persyaratan</h5><ul><li>Semester 4 ke atas</li><li>IPK minimal 3.20</li><li>Aktif organisasi</li></ul><h5>Benefit</h5><ul><li>Dana Rp 1.000.000/bulan</li><li>Pelatihan leadership</li></ul>',
  'https://djarumbeasiswaplus.org',
  'assets/images/poster-beasiswa.png',
  '2024-04-01', '2024-06-30', 'dibuka', 'terverifikasi', 2, NULL
),
(
  'Beasiswa Bank Indonesia',
  'Bank Indonesia',
  'Beasiswa untuk mahasiswa berprestasi aktif di organisasi dengan prioritas keluarga kurang mampu.',
  'Program beasiswa Bank Indonesia ditujukan untuk mahasiswa S1/D4/D3 semester 3 ke atas yang memiliki prestasi akademik dan aktif berorganisasi.',
  '<h5>Persyaratan</h5><ul><li>Semester 3 ke atas</li><li>IPK minimal 3.00</li><li>Aktif organisasi</li></ul><h5>Benefit</h5><ul><li>Dana pendidikan per semester</li><li>Workshop dan sertifikat</li></ul>',
  'https://www.bi.go.id/id/institute/beasiswa',
  'assets/images/poster-beasiswa.png',
  '2024-03-01', '2024-05-31', 'dibuka', 'pending', NULL, 1
),
(
  'Beasiswa Tanoto Foundation',
  'Tanoto Foundation',
  'Beasiswa TELADAN untuk mahasiswa aktif semester 2 yang memiliki semangat kepemimpinan.',
  'Program TELADAN Tanoto Foundation berfokus pada pengembangan pemimpin masa depan yang memiliki kepedulian sosial dan kemampuan memimpin komunitas.',
  '<h5>Persyaratan</h5><ul><li>Mahasiswa S1 semester 2</li><li>IPK minimal 3.30</li><li>Jiwa kepemimpinan</li></ul><h5>Benefit</h5><ul><li>Dana pendidikan</li><li>Magang di perusahaan Tanoto</li></ul>',
  'https://www.tanotofoundation.org',
  'assets/images/poster-beasiswa.png',
  '2024-06-01', '2024-08-15', 'belum_dibuka', 'ditolak', 1, NULL
);

-- BEASISWA_TAG (relasi beasiswa dengan tag)
INSERT INTO beasiswa_tag (id_beasiswa, id_tag) VALUES
-- Beasiswa 1 (Prestasi Kita): D3, D4, S1, S2, Fully Funded, Company
(1, 1), (1, 2), (1, 3), (1, 4), (1, 5), (1, 7),
-- Beasiswa 2 (KIP): D3, D4, S1, Fully Funded, Government, SKTM
(2, 1), (2, 2), (2, 3), (2, 5), (2, 8), (2, 10),
-- Beasiswa 3 (Djarum): S1, Partially Funded, Company, Prestasi
(3, 3), (3, 6), (3, 7), (3, 9),
-- Beasiswa 4 (BI): D3, D4, S1, Partially Funded, Government, Prestasi, SKTM
(4, 1), (4, 2), (4, 3), (4, 6), (4, 8), (4, 9), (4, 10),
-- Beasiswa 5 (Tanoto): S1, Partially Funded, Company, Prestasi
(5, 3), (5, 6), (5, 7), (5, 9);

-- SIMULASI (3 data)
INSERT INTO simulasi (id_mahasiswa, id_beasiswa, tgl_submit, prestasi, motivasi, ikut_organisasi, status_beasiswa_lain, aktif_kuliah) VALUES
(1, 1, '2024-03-15 10:30:00', 'Juara 2 Lomba Web Development Tingkat Nasional 2023', 'Saya ingin meringankan beban orang tua dalam membiayai pendidikan saya. Dengan beasiswa ini, saya dapat fokus pada pengembangan diri dan berkontribusi lebih banyak untuk kampus dan masyarakat.', TRUE, FALSE, TRUE),
(2, 2, '2024-03-18 14:20:00', 'Peraih IPK Tertinggi Prodi Akuntansi Semester 2', 'Berasal dari keluarga kurang mampu, saya berharap beasiswa ini dapat membantu melanjutkan studi agar bisa mewujudkan cita-cita menjadi akuntan profesional.', TRUE, FALSE, TRUE),
(3, 3, '2024-04-02 08:45:00', 'Finalis Business Plan Competition Jatim 2023', 'Saya ingin mengembangkan kemampuan leadership dan soft skills melalui program Djarum Beasiswa Plus sambil berkontribusi untuk pengembangan agribisnis di Jember.', TRUE, FALSE, TRUE);

-- DATA_ORANG_TUA (3 data, 1 per simulasi)
INSERT INTO data_orang_tua (id_simulasi, nama_ortu, penghasilan_ortu, pekerjaan_ortu, jml_tanggungan, sktm) VALUES
(1, 'Budi Santoso', 4000000.00, 'Wiraswasta', 3, FALSE),
(2, 'Slamet Riyadi', 1200000.00, 'Petani', 5, TRUE),
(3, 'Hasan Basri', 6500000.00, 'PNS', 2, FALSE);

-- FILE_SIMULASI (4 data)
INSERT INTO file_simulasi (id_simulasi, nama_file, file_path) VALUES
(1, 'berkas_ahmad_rizki.pdf', 'uploads/simulasi/1/berkas_ahmad_rizki.pdf'),
(1, 'sertifikat_lomba.pdf', 'uploads/simulasi/1/sertifikat_lomba.pdf'),
(2, 'berkas_siti_nurhaliza.pdf', 'uploads/simulasi/2/berkas_siti_nurhaliza.pdf'),
(3, 'berkas_reza_fahlevi.pdf', 'uploads/simulasi/3/berkas_reza_fahlevi.pdf');

-- HASIL_SIMULASI (3 data, variasi status & is_read)
INSERT INTO hasil_simulasi (id_simulasi, id_admin, tgl_review, status_simulasi, skor, catatan_admin, is_read) VALUES
(1, NULL, NULL, 'pending', NULL, NULL, FALSE),
(2, 1, '2024-03-20 09:15:00', 'lulus', 92.00, 'Berkas lengkap dan memenuhi semua persyaratan. IPK sangat baik, aktif berorganisasi, dan memiliki latar belakang ekonomi yang sesuai dengan kriteria KIP Kuliah. Sangat direkomendasikan.', TRUE),
(3, 1, '2024-04-08 11:30:00', 'tidak_lulus', 58.50, 'IPK memenuhi syarat minimal, namun tidak ada prestasi dan organisasi yang menjadi nilai tambah. Disarankan untuk aktif berorganisasi dan meningkatkan portfolio prestasi sebelum mendaftar kembali.', FALSE);

-- PUSTAKA (4 data)
INSERT INTO pustaka (id_admin, nama_dokumen, deskripsi_dokumen, preview_dokumen, file_path) VALUES
(1, 'Curriculum Vitae (CV)', 'Curriculum Vitae (CV) adalah dokumen yang berisi ringkasan data diri, riwayat pendidikan, pengalaman organisasi atau kerja, prestasi, keterampilan, serta informasi pendukung lainnya.', 'assets/images/cv-preview.png', 'uploads/pustaka/template_cv.pdf'),
(1, 'Surat Pernyataan Beasiswa', 'Surat pernyataan yang wajib dilampirkan jika ingin mendaftar beasiswa. Berisi komitmen tertulis calon penerima beasiswa.', 'assets/images/cv-preview.png', 'uploads/pustaka/surat_pernyataan.pdf'),
(1, 'Surat Rekomendasi Dosen', 'Contoh surat rekomendasi dari dosen pembimbing untuk pendaftaran beasiswa. Merekomendasikan mahasiswa berdasarkan prestasi dan karakter.', 'assets/images/cv-preview.png', 'uploads/pustaka/surat_rekomendasi.pdf'),
(1, 'Template Essay Motivasi', 'Template dan panduan penulisan essay motivasi untuk pendaftaran beasiswa. Mencakup struktur penulisan dan tips.', 'assets/images/cv-preview.png', 'uploads/pustaka/template_essay.pdf');

-- FAQ (5 data)
INSERT INTO faq (id_admin, pertanyaan, jawaban) VALUES
(1, 'Apa ada contoh dokumen yang sering jadi persyaratan pendaftaran beasiswa?', 'Ya, beberapa dokumen yang sering dibutuhkan antara lain: Curriculum Vitae (CV), Surat Pernyataan, Transkrip Nilai, Surat Rekomendasi dari dosen, dan Sertifikat prestasi. Anda bisa melihat contoh-contohnya di halaman Pustaka kami.'),
(1, 'Apakah gap year (jeda kuliah) diperbolehkan?', 'Kebijakan mengenai gap year berbeda-beda tergantung program beasiswa. Beberapa beasiswa mengizinkan gap year sementara yang lain mengharuskan mahasiswa aktif tanpa jeda. Silakan cek persyaratan spesifik setiap beasiswa.'),
(1, 'Apakah anak PNS/ASN boleh mendaftar?', 'Untuk sebagian besar beasiswa, anak PNS/ASN tetap boleh mendaftar. Namun, untuk beasiswa berbasis kebutuhan ekonomi seperti KIP Kuliah, ada ketentuan khusus terkait penghasilan orang tua.'),
(1, 'Apa batasan nilai IPK minimum untuk mendaftar?', 'Batasan IPK minimum berbeda setiap beasiswa. Umumnya berkisar antara 3.00 - 3.50. Beberapa beasiswa pemerintah mungkin menerima IPK lebih rendah jika disertai bukti keterbatasan ekonomi.'),
(1, 'Apa saja tahapan dan jadwal seleksi beasiswa?', 'Umumnya tahapan seleksi beasiswa meliputi: 1) Pendaftaran online, 2) Seleksi administrasi/berkas, 3) Tes tertulis (jika ada), 4) Wawancara, 5) Pengumuman hasil. Jadwal spesifik dapat dilihat di detail masing-masing beasiswa.');

SET FOREIGN_KEY_CHECKS = 1;

-- ============================================
-- SELESAI - File siap diimport ke phpMyAdmin
-- ============================================