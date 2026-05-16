-- MySQL dump 10.13  Distrib 8.4.3, for Win64 (x86_64)
--
-- Host: localhost    Database: db_beasiswa
-- ------------------------------------------------------
-- Server version	8.4.3

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin` (
  `id_admin` int NOT NULL AUTO_INCREMENT,
  `nama_admin` varchar(100) NOT NULL,
  `jabatan` varchar(100) NOT NULL,
  `telepon` varchar(20) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin') NOT NULL DEFAULT 'admin',
  PRIMARY KEY (`id_admin`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin`
--

LOCK TABLES `admin` WRITE;
/*!40000 ALTER TABLE `admin` DISABLE KEYS */;
INSERT INTO `admin` VALUES (1,'Admin Beasiswa Polije','Koordinator Beasiswa','+62 8222 3333 444','admin@beasiswa.polije.ac.id','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','admin');
/*!40000 ALTER TABLE `admin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `beasiswa`
--

DROP TABLE IF EXISTS `beasiswa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `beasiswa` (
  `id_beasiswa` int NOT NULL AUTO_INCREMENT,
  `nama_beasiswa` varchar(200) NOT NULL,
  `nama_penyelenggara` varchar(150) NOT NULL,
  `deskripsi_singkat` varchar(500) NOT NULL,
  `deskripsi_lengkap` text NOT NULL,
  `informasi_beasiswa` text NOT NULL,
  `link_pendaftaran` varchar(500) NOT NULL,
  `poster_url` varchar(500) NOT NULL,
  `tgl_buka` date NOT NULL,
  `tgl_tutup` date NOT NULL,
  `status_pendaftaran` enum('belum_dibuka','dibuka','ditutup') NOT NULL,
  `status_verifikasi` enum('pending','terverifikasi','ditolak') NOT NULL DEFAULT 'pending',
  `upload_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `id_mitra` int DEFAULT NULL,
  `id_admin` int DEFAULT NULL,
  `alasan_penolakan` text,
  PRIMARY KEY (`id_beasiswa`),
  KEY `fk_beasiswa_mitra` (`id_mitra`),
  KEY `fk_beasiswa_admin` (`id_admin`),
  CONSTRAINT `fk_beasiswa_admin` FOREIGN KEY (`id_admin`) REFERENCES `admin` (`id_admin`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_beasiswa_mitra` FOREIGN KEY (`id_mitra`) REFERENCES `mitra` (`id_mitra`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `beasiswa`
--

LOCK TABLES `beasiswa` WRITE;
/*!40000 ALTER TABLE `beasiswa` DISABLE KEYS */;
INSERT INTO `beasiswa` VALUES (7,'Beasiswa Sobat Bumi 2026','Pertamina Foundation','Beasiswa bagi mahasiswa S1/D4/D3 yang berprestasi dan peduli lingkungan.','Program ini mengajak generasi muda menjadi pelopor keberlanjutan, memberikan bantuan UKT/SPP, biaya hidup, serta pelatihan kapasitas dan karakter. Bertujuan mendukung misi pemerintah menuju Indonesia Emas 2045.','Jenjang: S1/D4 (semester 2-6), D3 (semester 2-4). IPK: > 3,00. Persyaratan: WNI, aktif organisasi/lingkungan, buat karya tulis & motivation letter. Prioritas: daerah operasi Pertamina.','https://www.pertaminafoundation.org/news','poster_1778637873_6e0455a0.jpeg','2026-05-31','2026-06-13','belum_dibuka','terverifikasi','2026-05-13 02:04:33',NULL,1,NULL),(8,'Beasiswa Astra1st 2026','PT Astra International Tbk','Program pengembangan diri untuk mahasiswa S1 yang memadukan beasiswa dan pengalaman langsung di industri.','Sebagai Astra Ambassador, peserta mendapat pembelajaran bisnis, pengembangan diri, dan mengerjakan proyek nyata di grup Astra selama 5 bulan. Peserta terlibat langsung dalam pembuatan solusi dan strategi perusahaan.','Jenjang: S1 (semester 4 atau 6). IPK: > 3,00. Persyaratan: WNI, punya prestasi non-akademis (organisasi, magang), tidak terima beasiswa sejenis lain.','https://daftarbeasiswa.id/beasiswa-astra-1st/','poster_1778638165_2e40d654.png','2026-05-01','2026-05-31','belum_dibuka','terverifikasi','2026-05-13 02:09:25',NULL,1,NULL),(9,'Beasiswa Indonesia Bangkit (BIB) 2026','Kementerian Agama, Republik Indonesia','Beasiswa dari pemerintah untuk memperluas akses pendidikan tinggi di lingkungan pendidikan keagamaan.','Program ini memberikan kesempatan melanjutkan pendidikan S1, S2, hingga S3. Tujuan utamanya adalah mengejar ketertinggalan di bidang sains dan teknologi di lingkungan Kementerian Agama, melahirkan generasi berkompetensi keagamaan sekaligus iptek.','Jenjang: S1, S2, S3. Jalur: unggulan keagamaan, akselerasi, double degree, pesantren, targeted, partnership. Proses seleksi: administrasi, skolastik, wawancara.','https://beasiswa.kemenag.go.id/','poster_1778638420_79bc275b.jpg','2026-04-01','2026-05-31','belum_dibuka','terverifikasi','2026-05-13 02:13:40',NULL,1,NULL),(10,'Beasiswa Pemprov Kepri 2026','Pemerintah Provinsi Kepulauan Riau','Bantuan stimulan satu tahun sekali untuk mahasiswa D3, S1, dan S2 asal atau kuliah di Kepri.','Bertujuan meringankan beban biaya pendidikan dengan total anggaran Rp3 miliar. Beasiswa ini diberikan berdasarkan prestasi akademik dan status kurang mampu, serta bersifat bantuan stimulan yang disesuaikan dengan anggaran tahunan daerah.','Jenjang: D3, S1, S2. Nominal: D3/S1 = Rp2,5 juta; S2 = Rp4 juta. Persyaratan: D3 (sem 3-6), S1 (sem 3-8), S2 (sem 3-6). Proses seleksi selesai Juni 2026.','https://beasiswa.kepriprov.go.id/','poster_1778638590_79e09e33.jpg','2026-04-06','2026-05-06','belum_dibuka','terverifikasi','2026-05-13 02:16:30',NULL,1,NULL),(13,'BSI Scholarship 2026 (Jalur Unggulan)','Prestasi Kita Foundation','Beasiswa penuh UKT bagi mahasiswa baru dari PTN top 10, fokus pada ekonomi syariah.','Bekerja sama dengan Danantara Indonesia, program ini menawarkan UKT penuh, uang saku Rp1,5 juta per bulan, laptop, serta pembinaan leadership, character building, dan kesempatan magang. Program ini bertujuan mencetak generasi unggul yang siap berkontribusi di sektor ekonomi syariah.','Jenjang: S1.Persyaratan: mahasiswa baru jalur undangan PTN top 10, berasal dari keluarga kurang mampu (SKTM/KIP), tidak terima beasiswa lain.','https://bsischolarship.id/','poster_1778641424_759dd9ec.png','2026-04-02','2026-04-12','belum_dibuka','terverifikasi','2026-05-13 03:03:44',1,NULL,NULL),(15,'Bantuan Pendidikan Kebanksentralan Bank Indonesia 2026','Bank Indonesia','Beasiswa untuk mahasiswa S1/D4/D3 dari keluarga kurang mampu yang berkomitmen pada literasi kebanksentralan.','Penerima diharapkan menjadi agen perubahan literasi kebanksentralan dan bergabung dalam komunitas Generasi Baru Indonesia (GenBI). Bank Indonesia membuka program ini untuk mendukung peningkatan kualitas SDM unggul dan berdaya saing.','Jenjang: S1/D4 (maks. 23 tahun), D3 (maks. 22 tahun). IPK: > 3,00 (skala 4). Persyaratan: S3, IPK min 3,00, diutamakan memiliki SKTM.','https://bisik.id/read/bantuan-pendidikan-kebanksentralan-bi-2026-dibuka-1774344253106#:~:text=Bantuan%20Pendidikan%20Kebanksentralan%20BI%202026%20kini%20resmi%20dibuka.,dana%20bulanan%20dan%20diharapkan%20aktif%20mendukung%20literasi%20kebanksentralan.','poster_1778642236_ded57140.jpg','2026-04-27','2026-05-05','belum_dibuka','terverifikasi','2026-05-13 03:17:16',NULL,1,NULL),(17,'Beasiswa Garuda Sarjana 2026','Kementerian Pendidikan Tinggi, Sains, dan Teknologi (Kemdiktisaintek)','Program beasiswa S1 untuk menyiapkan talenta terbaik Indonesia yang mampu bersaing di kancah global.','Pemerintah memperluas skema studi melalui program joint degree dan double degree dengan perguruan tinggi luar negeri. Seleksi tahun ini didasarkan pada peringkat program studi (prodi) terbaik dunia (QS World University Rankings by Subject), bukan universitas, serta difokuskan pada 10 bidang prioritas nasional.','Jenjang: S1.Persyaratan: talenta terbaik bangsa, seleksi berdasarkan peringkat prodi top dunia.','https://beasiswagaruda.kemdiktisaintek.go.id/','poster_1778642427_05958aa1.jpg','2026-05-13','2026-06-27','belum_dibuka','terverifikasi','2026-05-13 03:20:27',NULL,1,NULL),(18,'GREAT Scholarship 2026','Pemerintah Inggris, British Council, & Universitas Mitra','Beasiswa S-2 di berbagai kampus unggulan di Inggris Raya.','Program kerja sama bergengsi untuk mahasiswa internasional dari Indonesia yang ingin melanjutkan studi selama 1 tahun. Selain bantuan biaya kuliah, penerima dapat membangun jaringan profesional dan mendapatkan izin tinggal hingga 2 tahun setelah lulus (Graduate Visa).','Jenjang: S2.Nominal: hingga ┬ú10.000. Persyaratan: WNI, diterima di universitas mitra, rencana studi 1 tahun.','https://www.beritasatu.com/nasional/2992948/daftar-beasiswa-22-kampus-inggris-dan-irlandia-2026','poster_1778663624_6309135c.jpg','2026-06-01','2026-06-30','belum_dibuka','terverifikasi','2026-05-13 09:13:44',NULL,1,NULL);
/*!40000 ALTER TABLE `beasiswa` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `trg_beasiswa_uploader_insert` BEFORE INSERT ON `beasiswa` FOR EACH ROW BEGIN

  IF NOT (

    (NEW.id_mitra IS NOT NULL AND NEW.id_admin IS NULL) OR

    (NEW.id_mitra IS NULL AND NEW.id_admin IS NOT NULL)

  ) THEN

    SIGNAL SQLSTATE '45000'

    SET MESSAGE_TEXT = 'Tepat satu dari id_mitra atau id_admin harus berisi nilai.';

  END IF;

END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `trg_beasiswa_uploader_update` BEFORE UPDATE ON `beasiswa` FOR EACH ROW BEGIN

  IF NOT (

    (NEW.id_mitra IS NOT NULL AND NEW.id_admin IS NULL) OR

    (NEW.id_mitra IS NULL AND NEW.id_admin IS NOT NULL)

  ) THEN

    SIGNAL SQLSTATE '45000'

    SET MESSAGE_TEXT = 'Tepat satu dari id_mitra atau id_admin harus berisi nilai.';

  END IF;

END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `beasiswa_tag`
--

DROP TABLE IF EXISTS `beasiswa_tag`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `beasiswa_tag` (
  `id_beasiswa` int NOT NULL,
  `id_tag` int NOT NULL,
  PRIMARY KEY (`id_beasiswa`,`id_tag`),
  KEY `fk_bt_tag` (`id_tag`),
  CONSTRAINT `fk_bt_beasiswa` FOREIGN KEY (`id_beasiswa`) REFERENCES `beasiswa` (`id_beasiswa`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_bt_tag` FOREIGN KEY (`id_tag`) REFERENCES `tag` (`id_tag`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `beasiswa_tag`
--

LOCK TABLES `beasiswa_tag` WRITE;
/*!40000 ALTER TABLE `beasiswa_tag` DISABLE KEYS */;
INSERT INTO `beasiswa_tag` VALUES (7,1),(10,1),(15,1),(7,2),(15,2),(7,3),(8,3),(9,3),(10,3),(13,3),(15,3),(17,3),(9,4),(10,4),(18,4),(9,5),(13,5),(17,5),(7,6),(8,6),(10,6),(15,6),(18,6),(7,7),(8,7),(13,7),(9,8),(10,8),(15,8),(17,8),(18,8),(7,9),(8,9),(10,9),(13,9),(15,9),(17,9),(18,9),(10,10),(13,10),(15,10);
/*!40000 ALTER TABLE `beasiswa_tag` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `data_orang_tua`
--

DROP TABLE IF EXISTS `data_orang_tua`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `data_orang_tua` (
  `id_ortu` int NOT NULL AUTO_INCREMENT,
  `id_simulasi` int NOT NULL,
  `nama_ortu` varchar(100) NOT NULL,
  `penghasilan_ortu` decimal(15,2) NOT NULL,
  `pekerjaan_ortu` varchar(100) NOT NULL,
  `jml_tanggungan` tinyint unsigned NOT NULL,
  `sktm` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_ortu`),
  UNIQUE KEY `id_simulasi` (`id_simulasi`),
  CONSTRAINT `fk_ortu_simulasi` FOREIGN KEY (`id_simulasi`) REFERENCES `simulasi` (`id_simulasi`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `data_orang_tua`
--

LOCK TABLES `data_orang_tua` WRITE;
/*!40000 ALTER TABLE `data_orang_tua` DISABLE KEYS */;
INSERT INTO `data_orang_tua` VALUES (4,4,'Tio',500000.00,'Programmer',0,1),(5,5,'Tio',500000.00,'Programmer',0,1),(6,6,'Tio',500000.00,'Programmer',1,1),(7,7,'ada',500000.00,'ada',3,1),(8,8,'test',500000.00,'test',1,1);
/*!40000 ALTER TABLE `data_orang_tua` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `faq`
--

DROP TABLE IF EXISTS `faq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `faq` (
  `id_pertanyaan` int NOT NULL AUTO_INCREMENT,
  `id_admin` int NOT NULL,
  `pertanyaan` text NOT NULL,
  `jawaban` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_pertanyaan`),
  KEY `fk_faq_admin` (`id_admin`),
  CONSTRAINT `fk_faq_admin` FOREIGN KEY (`id_admin`) REFERENCES `admin` (`id_admin`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `faq`
--

LOCK TABLES `faq` WRITE;
/*!40000 ALTER TABLE `faq` DISABLE KEYS */;
INSERT INTO `faq` VALUES (1,1,'Apa ada contoh dokumen yang sering jadi persyaratan pendaftaran beasiswa?','Ya, beberapa dokumen yang sering dibutuhkan antara lain: Curriculum Vitae (CV), Surat Pernyataan, Transkrip Nilai, Surat Rekomendasi dari dosen, dan Sertifikat prestasi. Anda bisa melihat contoh-contohnya di halaman Pustaka kami.','2026-05-12 06:53:39'),(2,1,'Apakah gap year (jeda kuliah) diperbolehkan?','Kebijakan mengenai gap year berbeda-beda tergantung program beasiswa. Beberapa beasiswa mengizinkan gap year sementara yang lain mengharuskan mahasiswa aktif tanpa jeda. Silakan cek persyaratan spesifik setiap beasiswa.','2026-05-12 06:53:39'),(3,1,'Apakah anak PNS/ASN boleh mendaftar?','Untuk sebagian besar beasiswa, anak PNS/ASN tetap boleh mendaftar. Namun, untuk beasiswa berbasis kebutuhan ekonomi seperti KIP Kuliah, ada ketentuan khusus terkait penghasilan orang tua.','2026-05-12 06:53:39'),(4,1,'Apa batasan nilai IPK minimum untuk mendaftar?','Batasan IPK minimum berbeda setiap beasiswa. Umumnya berkisar antara 3.00 - 3.50. Beberapa beasiswa pemerintah mungkin menerima IPK lebih rendah jika disertai bukti keterbatasan ekonomi.','2026-05-12 06:53:39'),(5,1,'Apa saja tahapan dan jadwal seleksi beasiswa?','Umumnya tahapan seleksi beasiswa meliputi: 1) Pendaftaran online, 2) Seleksi administrasi/berkas, 3) Tes tertulis (jika ada), 4) Wawancara, 5) Pengumuman hasil. Jadwal spesifik dapat dilihat di detail masing-masing beasiswa.','2026-05-12 06:53:39');
/*!40000 ALTER TABLE `faq` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `file_simulasi`
--

DROP TABLE IF EXISTS `file_simulasi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `file_simulasi` (
  `id_file` int NOT NULL AUTO_INCREMENT,
  `id_simulasi` int NOT NULL,
  `nama_file` varchar(255) NOT NULL,
  `file_path` varchar(500) NOT NULL,
  `upload_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_file`),
  KEY `fk_file_simulasi` (`id_simulasi`),
  CONSTRAINT `fk_file_simulasi` FOREIGN KEY (`id_simulasi`) REFERENCES `simulasi` (`id_simulasi`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `file_simulasi`
--

LOCK TABLES `file_simulasi` WRITE;
/*!40000 ALTER TABLE `file_simulasi` DISABLE KEYS */;
INSERT INTO `file_simulasi` VALUES (5,6,'Dokumen tanpa judul (4).pdf','6a043efb4d5616.64309436_Dokumen_tanpa_judul_4.pdf','2026-05-13 09:06:03'),(6,7,'tugas_kutipan_apa.pdf','6a07d6e1b0a8c5.73797773_tugas_kutipan_apa.pdf','2026-05-16 02:30:57'),(7,7,'Dokumen tanpa judul (4).pdf','6a07d6e1b15857.98508049_Dokumen_tanpa_judul_4.pdf','2026-05-16 02:30:57'),(8,8,'Dokumen tanpa judul (4).pdf','6a07eebb258c03.51419524_Dokumen_tanpa_judul_4.pdf','2026-05-16 04:12:43'),(9,8,'Dokumen tanpa judul (3).pdf','6a07eebb267cf7.20440339_Dokumen_tanpa_judul_3.pdf','2026-05-16 04:12:43');
/*!40000 ALTER TABLE `file_simulasi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hasil_simulasi`
--

DROP TABLE IF EXISTS `hasil_simulasi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `hasil_simulasi` (
  `id_hasil_simulasi` int NOT NULL AUTO_INCREMENT,
  `id_simulasi` int NOT NULL,
  `id_admin` int DEFAULT NULL,
  `tgl_review` timestamp NULL DEFAULT NULL,
  `status_simulasi` enum('pending','lulus','tidak_lulus') NOT NULL DEFAULT 'pending',
  `skor` decimal(5,2) DEFAULT NULL,
  `catatan_admin` text,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_hasil_simulasi`),
  UNIQUE KEY `id_simulasi` (`id_simulasi`),
  KEY `fk_hasil_admin` (`id_admin`),
  CONSTRAINT `fk_hasil_admin` FOREIGN KEY (`id_admin`) REFERENCES `admin` (`id_admin`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_hasil_simulasi` FOREIGN KEY (`id_simulasi`) REFERENCES `simulasi` (`id_simulasi`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hasil_simulasi`
--

LOCK TABLES `hasil_simulasi` WRITE;
/*!40000 ALTER TABLE `hasil_simulasi` DISABLE KEYS */;
INSERT INTO `hasil_simulasi` VALUES (4,4,1,'2026-05-13 08:23:44','tidak_lulus',70.50,'jelek',1),(5,5,1,'2026-05-13 08:23:56','lulus',100.00,'Keren',1),(6,6,1,'2026-05-13 09:08:10','tidak_lulus',55.00,'Dokumen kurang lengkap',1),(7,7,1,'2026-05-16 02:32:12','lulus',100.00,'ada',1),(8,8,1,'2026-05-16 04:13:10','lulus',100.00,'bagus',1);
/*!40000 ALTER TABLE `hasil_simulasi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mahasiswa`
--

DROP TABLE IF EXISTS `mahasiswa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mahasiswa` (
  `id_mahasiswa` int NOT NULL AUTO_INCREMENT,
  `NIM` varchar(20) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `id_prodi` int NOT NULL,
  `semester` tinyint unsigned NOT NULL,
  `angkatan` year NOT NULL,
  `IPK` decimal(3,2) NOT NULL,
  `jenis_kelamin` enum('L','P') NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('mahasiswa') NOT NULL DEFAULT 'mahasiswa',
  PRIMARY KEY (`id_mahasiswa`),
  UNIQUE KEY `NIM` (`NIM`),
  UNIQUE KEY `email` (`email`),
  KEY `fk_mahasiswa_prodi` (`id_prodi`),
  CONSTRAINT `fk_mahasiswa_prodi` FOREIGN KEY (`id_prodi`) REFERENCES `prodi` (`id_prodi`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mahasiswa`
--

LOCK TABLES `mahasiswa` WRITE;
/*!40000 ALTER TABLE `mahasiswa` DISABLE KEYS */;
INSERT INTO `mahasiswa` VALUES (1,'E41212345','Ahmad Rizki Pratama',1,5,2022,3.65,'L','ahmad.rizki@student.polije.ac.id','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','mahasiswa'),(2,'E41212678','Siti Nurhaliza',4,3,2023,3.80,'P','siti.nurhaliza@student.polije.ac.id','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','mahasiswa'),(3,'E41211999','Reza Fahlevi',5,6,2021,3.45,'L','reza.fahlevi@student.polije.ac.id','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','mahasiswa'),(6,'E41256567','Daffa Rahman',1,2,2022,4.00,'L','bantahwarnamerah@gmail.com','$2y$10$m/bG5p0GXiDE3kg0KDKQi.Yu8iY0Q5J.KZnGUu8tnh/Dj2Ymqb8hq','mahasiswa'),(8,'E41252525','Rendy Pranata',1,1,2026,0.00,'L','mahasiswa1@gmail.com','$2y$10$mVkbxUvXel7LSJ6Ngj61A.ULWVtMFFDc89ZLK19TSxW8jDyFfYzMm','mahasiswa'),(9,'E41252526','Rendy Pratama',2,1,2026,0.00,'L','mahasiswa2@gmail.com','$2y$10$nANToZWzEPU40bPOdMgrNu0WMhmxeMapQgWQF4OWWntwV0I8Xjx4q','mahasiswa'),(10,'E41252570','Fathir Yusufa',1,1,2026,0.00,'L','mahasiswa3@gmail.com','$2y$10$YX1BQr8ihyopED876QMJnOH9D4SiZa4vg35j7BWGHRNUuXEsSfXTu','mahasiswa');
/*!40000 ALTER TABLE `mahasiswa` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mitra`
--

DROP TABLE IF EXISTS `mitra`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mitra` (
  `id_mitra` int NOT NULL AUTO_INCREMENT,
  `nama_mitra` varchar(150) NOT NULL,
  `bidang_usaha` varchar(100) NOT NULL,
  `telepon` varchar(20) NOT NULL,
  `website` varchar(255) DEFAULT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('mitra') NOT NULL DEFAULT 'mitra',
  PRIMARY KEY (`id_mitra`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mitra`
--

LOCK TABLES `mitra` WRITE;
/*!40000 ALTER TABLE `mitra` DISABLE KEYS */;
INSERT INTO `mitra` VALUES (1,'Prestasi Kita Foundation','Pendidikan & Sosial','+62 812 3456 7890','https://prestasikita.com','admin@prestasikita.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','mitra'),(2,'PT Djarum','FMCG & Pendidikan','+62 21 5555 6666','https://djarumbeasiswaplus.org','beasiswa@djarum.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','mitra');
/*!40000 ALTER TABLE `mitra` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `prodi`
--

DROP TABLE IF EXISTS `prodi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `prodi` (
  `id_prodi` int NOT NULL AUTO_INCREMENT,
  `nama_prodi` varchar(100) NOT NULL,
  `nama_jurusan` varchar(100) NOT NULL,
  PRIMARY KEY (`id_prodi`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `prodi`
--

LOCK TABLES `prodi` WRITE;
/*!40000 ALTER TABLE `prodi` DISABLE KEYS */;
INSERT INTO `prodi` VALUES (1,'Teknik Informatika','Teknologi Informasi'),(2,'Manajemen Informatika','Teknologi Informasi'),(3,'Teknik Komputer','Teknologi Informasi'),(4,'Teknologi Rekayasa Komputer','Teknologi Informasi'),(5,'Teknologi Rekayasa Perangkat Lunak','Teknologi Informasi');
/*!40000 ALTER TABLE `prodi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pustaka`
--

DROP TABLE IF EXISTS `pustaka`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pustaka` (
  `id_pustaka` int NOT NULL AUTO_INCREMENT,
  `id_admin` int NOT NULL,
  `nama_dokumen` varchar(200) NOT NULL,
  `deskripsi_dokumen` text,
  `preview_dokumen` varchar(500) DEFAULT NULL,
  `file_path` varchar(500) NOT NULL,
  `upload_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_pustaka`),
  KEY `fk_pustaka_admin` (`id_admin`),
  CONSTRAINT `fk_pustaka_admin` FOREIGN KEY (`id_admin`) REFERENCES `admin` (`id_admin`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pustaka`
--

LOCK TABLES `pustaka` WRITE;
/*!40000 ALTER TABLE `pustaka` DISABLE KEYS */;
INSERT INTO `pustaka` VALUES (5,1,'Curriculum Vitae','Curriculum Vitae (CV) adalah dokumen yang berisi ringkasan data diri, riwayat pendidikan, pengalaman organisasi atau kerja, prestasi, keterampilan, serta informasi pendukung lainnya.','','6a03cecce118d7.28267195_Dokumen_tanpa_judul_4.pdf','2026-05-13 01:07:24'),(6,1,'Surat Pernyataan Beasiswa','Surat pernyataan yang wajib dilampirkan jika ingin mendaftar beasiswa. Berisi komitmen tertulis calon penerima beasiswa.','','6a03cefac539e2.13329981_Dokumen_tanpa_judul_4.pdf','2026-05-13 01:08:10'),(7,1,'Surat Rekomendasi Dosen','Contoh surat rekomendasi dari dosen pembimbing untuk pendaftaran beasiswa. Merekomendasikan mahasiswa berdasarkan prestasi dan karakter.','','6a03cf1b615732.11215109_Dokumen_tanpa_judul_4.pdf','2026-05-13 01:08:43'),(8,1,'Essay','Template dan panduan penulisan essay motivasi untuk pendaftaran beasiswa. Mencakup struktur penulisan dan tips.','','6a03cf42a26d17.33557068_Dokumen_tanpa_judul_4.pdf','2026-05-13 01:09:22'),(9,1,'Sertifikat Prestasi','Contoh surat rekomendasi dari dosen pembimbing untuk pendaftaran beasiswa. Merekomendasikan mahasiswa berdasarkan prestasi dan karakter.','','6a0441ff47f825.59649695_Dokumen_tanpa_judul_4.pdf','2026-05-13 09:18:55'),(10,1,'SKTM','Surat keterangan tidak mampu','','6a07d269bda662.19668835_Fathir_Yusufa_s_CV.pdf','2026-05-16 02:11:53'),(11,1,'test','test','6a07de50ea5cf6.61477372_IMG_1611_JPG.jpeg','6a07de50ead840.95847789_Dokumen_tanpa_judul_4.pdf','2026-05-16 03:02:40');
/*!40000 ALTER TABLE `pustaka` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `simulasi`
--

DROP TABLE IF EXISTS `simulasi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `simulasi` (
  `id_simulasi` int NOT NULL AUTO_INCREMENT,
  `id_mahasiswa` int NOT NULL,
  `id_beasiswa` int NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `tgl_submit` timestamp NULL DEFAULT NULL,
  `prestasi` text,
  `motivasi` text,
  `ikut_organisasi` text,
  `status_beasiswa_lain` tinyint(1) NOT NULL DEFAULT '0',
  `aktif_kuliah` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_simulasi`),
  KEY `fk_simulasi_mahasiswa` (`id_mahasiswa`),
  KEY `fk_simulasi_beasiswa` (`id_beasiswa`),
  CONSTRAINT `fk_simulasi_beasiswa` FOREIGN KEY (`id_beasiswa`) REFERENCES `beasiswa` (`id_beasiswa`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_simulasi_mahasiswa` FOREIGN KEY (`id_mahasiswa`) REFERENCES `mahasiswa` (`id_mahasiswa`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `simulasi`
--

LOCK TABLES `simulasi` WRITE;
/*!40000 ALTER TABLE `simulasi` DISABLE KEYS */;
INSERT INTO `simulasi` VALUES (4,6,17,'2026-05-13 08:07:44','2026-05-13 08:07:44','Tidak ada','Mau beasiswa','0',0,1),(5,6,17,'2026-05-13 08:07:44','2026-05-13 08:07:44','Tidak ada','Mau beasiswa','0',0,1),(6,8,17,'2026-05-13 09:06:03','2026-05-13 09:06:03','Tidak ada','Mau beasiswa','0',0,1),(7,6,18,'2026-05-16 02:30:57','2026-05-16 02:30:57','ada','ada','1',1,1),(8,6,9,'2026-05-16 04:12:43','2026-05-16 04:12:43','ada','ada','1',1,1);
/*!40000 ALTER TABLE `simulasi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tag`
--

DROP TABLE IF EXISTS `tag`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tag` (
  `id_tag` int NOT NULL AUTO_INCREMENT,
  `nama_tag` varchar(100) NOT NULL,
  `kategori_tag` enum('Jenjang','Tipe Pendanaan','Tipe Beasiswa','Prestasi','SKTM','IPK','Semester') NOT NULL,
  PRIMARY KEY (`id_tag`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tag`
--

LOCK TABLES `tag` WRITE;
/*!40000 ALTER TABLE `tag` DISABLE KEYS */;
INSERT INTO `tag` VALUES (1,'D3','Jenjang'),(2,'D4','Jenjang'),(3,'S1','Jenjang'),(4,'S2','Jenjang'),(5,'Fully Funded','Tipe Pendanaan'),(6,'Partially Funded','Tipe Pendanaan'),(7,'Company/Institution Scholarship','Tipe Beasiswa'),(8,'Government Scholarship','Tipe Beasiswa'),(9,'Prestasi','Prestasi'),(10,'SKTM','SKTM'),(11,'2.50','IPK'),(12,'2.75','IPK'),(13,'3.00','IPK'),(14,'3.25','IPK'),(15,'3.50','IPK'),(16,'3.75','IPK'),(17,'1','Semester'),(18,'2','Semester'),(19,'3','Semester'),(20,'4','Semester'),(21,'5','Semester'),(22,'6','Semester'),(23,'7','Semester'),(24,'8','Semester');
/*!40000 ALTER TABLE `tag` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-05-16 13:29:50
