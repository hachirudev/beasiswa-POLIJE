/* ============================================
   BEASISWA POLIJE — Data Dummy
   Siap diganti dengan fetch API ke backend
   ============================================ */

const beasiswaData = [
  {
    id: 1,
    judul: "Beasiswa Pendidikan Prestasi Kita BATCH #5",
    perusahaan: "Prestasi Kita Foundation",
    deskripsiSingkat: "Beasiswa pendidikan kategori Beasiswa Prestasi, Ekonomi, Yatim, Hafidz Al Qur'an, dan umum",
    poster: "assets/images/poster-beasiswa.png",
    tags: ["D3", "D4", "S1", "S2", "Swasta"],
    jenjang: ["D3", "D4", "S1", "S2"],
    tipePendanaan: "Fully Funded",
    tipeBeasiswa: "Company/Institution Scholarship",
    mulai: "2024-02-12",
    deadline: "2024-05-12",
    bulan: 2,
    tahun: 2024,
    statusPendaftaran: "Dibuka",
    linkExternal: "https://prestasikita.com/daftar",
    deskripsiLengkap: "Beasiswa Unggulan adalah program bantuan dana pendidikan dari Kementerian Pendidikan Dasar dan Menengah (Kemendikdasmen) RI untuk masyarakat berprestasi, penyandang disabilitas, dan pegawai Kemendikdasmen. Beasiswa ini mencakup biaya kuliah (UKT/SPP), biaya hidup, dan biaya buku",
    persyaratan: [
      "Warga Negara Indonesia (WNI).",
      "Belum pernah menempuh pendidikan pada jenjang yang sama.",
      "Diterima di perguruan tinggi terakreditasi minimal Baik Sekali/B, atau diakui untuk luar negeri.",
      "Bukan dosen, guru, tenaga kependidikan, atau pelaku budaya (khusus jalur Masyarakat Berprestasi).",
      "Tidak sedang menerima beasiswa sejenis dari sumber lain."
    ],
    keuntungan: [
      "Biaya Pendidikan Penuh: Menanggung seluruh SPP/UKT hingga masa studi selesai.",
      "Biaya Hidup: Bantuan uang saku bulanan, dengan estimasi mencapai Rp1,4 juta per bulan.",
      "Biaya Buku: Dana tambahan untuk pembelian buku atau bahan ajar.",
      "Dana Penelitian: Khusus jenjang S2, S3, atau kategori disabilitas, terdapat bantuan biaya penelitian.",
      "Khusus Penyandang Disabilitas: Selain biaya kuliah, tersedia bantuan untuk biaya hidup pendamping.",
      "Khusus Luar Negeri: Mendapatkan tiket pesawat, biaya dokumen perjalanan, dan asuransi kesehatan."
    ],
    prestasi: true,
    pemerintah: false,
    ipkMin: 3.0,
    semester: [1, 2, 3, 4, 5, 6, 7, 8],
    sktm: false
  },
  {
    id: 2,
    judul: "Beasiswa KIP Kuliah 2024",
    perusahaan: "Kemendikbudristek",
    deskripsiSingkat: "Beasiswa bagi mahasiswa dari keluarga kurang mampu untuk membiayai pendidikan di perguruan tinggi negeri maupun swasta.",
    poster: "assets/images/poster-beasiswa.png",
    tags: ["D3", "D4", "S1", "Pemerintah"],
    jenjang: ["D3", "D4", "S1"],
    tipePendanaan: "Fully Funded",
    tipeBeasiswa: "Government Scholarship",
    mulai: "2024-01-15",
    deadline: "2024-03-31",
    bulan: 1,
    tahun: 2024,
    statusPendaftaran: "Ditutup",
    linkExternal: "https://kip-kuliah.kemdikbud.go.id",
    deskripsiLengkap: "KIP Kuliah adalah bantuan biaya pendidikan dari pemerintah bagi lulusan SMA/SMK/sederajat yang memiliki potensi akademik baik tetapi memiliki keterbatasan ekonomi.",
    persyaratan: [
      "Siswa SMA/SMK/sederajat yang akan lulus pada tahun berjalan.",
      "Memiliki potensi akademik baik tetapi memiliki keterbatasan ekonomi.",
      "Lulus seleksi penerimaan mahasiswa baru.",
      "Memiliki NISN, NPSN, dan NIK yang valid."
    ],
    keuntungan: [
      "Pembebasan biaya pendaftaran seleksi masuk perguruan tinggi.",
      "Pembebasan biaya kuliah/UKT.",
      "Bantuan biaya hidup Rp 700.000/bulan."
    ],
    prestasi: false,
    pemerintah: true,
    ipkMin: 0,
    semester: [1, 2],
    sktm: true
  },
  {
    id: 3,
    judul: "Beasiswa Djarum Foundation",
    perusahaan: "PT Djarum",
    deskripsiSingkat: "Beasiswa bagi mahasiswa berprestasi semester 4 ke atas dari perguruan tinggi terpilih untuk pengembangan soft skills dan kepemimpinan.",
    poster: "assets/images/poster-beasiswa.png",
    tags: ["S1", "Swasta"],
    jenjang: ["S1"],
    tipePendanaan: "Partially Funded",
    tipeBeasiswa: "Company/Institution Scholarship",
    mulai: "2024-04-01",
    deadline: "2024-06-30",
    bulan: 4,
    tahun: 2024,
    statusPendaftaran: "Dibuka",
    linkExternal: "https://djarumbeasiswaplus.org",
    deskripsiLengkap: "Djarum Beasiswa Plus memberikan kesempatan bagi mahasiswa semester 4 untuk mengembangkan soft skills melalui berbagai pelatihan dan kegiatan.",
    persyaratan: [
      "Mahasiswa aktif semester 4 ke atas.",
      "IPK minimal 3.20.",
      "Aktif dalam kegiatan organisasi.",
      "Bersedia mengikuti kegiatan pengembangan soft skills."
    ],
    keuntungan: [
      "Dana beasiswa Rp 1.000.000/bulan selama 1 tahun.",
      "Pelatihan Nation Building, Leadership, dan Communication Skills.",
      "Jaringan alumni tersebar di seluruh Indonesia."
    ],
    prestasi: true,
    pemerintah: false,
    ipkMin: 3.2,
    semester: [4, 5, 6, 7, 8],
    sktm: false
  },
  {
    id: 4,
    judul: "Beasiswa Bank Indonesia",
    perusahaan: "Bank Indonesia",
    deskripsiSingkat: "Beasiswa untuk mahasiswa berprestasi aktif di organisasi dengan prioritas dari keluarga kurang mampu.",
    poster: "assets/images/poster-beasiswa.png",
    tags: ["D3", "D4", "S1", "Pemerintah"],
    jenjang: ["D3", "D4", "S1"],
    tipePendanaan: "Partially Funded",
    tipeBeasiswa: "Government Scholarship",
    mulai: "2024-03-01",
    deadline: "2024-05-31",
    bulan: 3,
    tahun: 2024,
    statusPendaftaran: "Dibuka",
    linkExternal: "https://www.bi.go.id/id/institute/beasiswa",
    deskripsiLengkap: "Program beasiswa Bank Indonesia ditujukan untuk mahasiswa S1/D4/D3 semester 3 ke atas yang memiliki prestasi akademik dan aktif berorganisasi.",
    persyaratan: [
      "Mahasiswa aktif minimal semester 3.",
      "IPK minimal 3.00.",
      "Aktif berorganisasi di kampus atau masyarakat.",
      "Diutamakan dari keluarga kurang mampu."
    ],
    keuntungan: [
      "Dana pendidikan per semester.",
      "Pelatihan dan workshop dari Bank Indonesia.",
      "Sertifikat dan networking."
    ],
    prestasi: true,
    pemerintah: true,
    ipkMin: 3.0,
    semester: [3, 4, 5, 6, 7, 8],
    sktm: true
  },
  {
    id: 5,
    judul: "Beasiswa Unggulan Kemendikbud",
    perusahaan: "Kemendikbud",
    deskripsiSingkat: "Program beasiswa unggulan untuk mahasiswa berprestasi di bidang akademik, seni, olahraga, dan penelitian.",
    poster: "assets/images/poster-beasiswa.png",
    tags: ["S1", "S2", "S3", "Pemerintah"],
    jenjang: ["S1", "S2", "S3"],
    tipePendanaan: "Fully Funded",
    tipeBeasiswa: "Government Scholarship",
    mulai: "2024-05-01",
    deadline: "2024-07-31",
    bulan: 5,
    tahun: 2024,
    statusPendaftaran: "Dibuka",
    linkExternal: "https://beasiswaunggulan.kemdikbud.go.id",
    deskripsiLengkap: "Beasiswa Unggulan Kemendikbud diberikan kepada calon mahasiswa dan mahasiswa yang berprestasi di bidang akademik, teknologi, seni, budaya, dan olahraga.",
    persyaratan: [
      "Warga Negara Indonesia.",
      "Memiliki prestasi di bidang akademik/non-akademik.",
      "Diterima di perguruan tinggi yang terakreditasi.",
      "IPK minimal 3.25 untuk mahasiswa ongoing."
    ],
    keuntungan: [
      "Biaya pendidikan penuh (UKT/SPP).",
      "Biaya hidup bulanan.",
      "Biaya buku dan penelitian.",
      "Tiket pesawat untuk yang kuliah di luar kota."
    ],
    prestasi: true,
    pemerintah: true,
    ipkMin: 3.25,
    semester: [1, 2, 3, 4, 5, 6],
    sktm: false
  },
  {
    id: 6,
    judul: "Beasiswa Tanoto Foundation",
    perusahaan: "Tanoto Foundation",
    deskripsiSingkat: "Beasiswa TELADAN untuk mahasiswa aktif semester 2 yang memiliki semangat kepemimpinan dan kepedulian sosial.",
    poster: "assets/images/poster-beasiswa.png",
    tags: ["S1", "Swasta"],
    jenjang: ["S1"],
    tipePendanaan: "Partially Funded",
    tipeBeasiswa: "Company/Institution Scholarship",
    mulai: "2024-06-01",
    deadline: "2024-08-15",
    bulan: 6,
    tahun: 2024,
    statusPendaftaran: "Dibuka",
    linkExternal: "https://www.tanotofoundation.org",
    deskripsiLengkap: "Program TELADAN Tanoto Foundation berfokus pada pengembangan pemimpin masa depan yang memiliki kepedulian sosial dan kemampuan memimpin komunitas.",
    persyaratan: [
      "Mahasiswa S1 semester 2.",
      "IPK minimal 3.30.",
      "Aktif dalam kegiatan sosial atau organisasi.",
      "Memiliki jiwa kepemimpinan."
    ],
    keuntungan: [
      "Dana pendidikan setiap semester.",
      "Program pengembangan kepemimpinan.",
      "Magang di perusahaan Tanoto.",
      "Jaringan alumni global."
    ],
    prestasi: true,
    pemerintah: false,
    ipkMin: 3.3,
    semester: [2, 3, 4],
    sktm: false
  },
  {
    id: 7,
    judul: "Beasiswa Polije Internal",
    perusahaan: "Politeknik Negeri Jember",
    deskripsiSingkat: "Beasiswa internal Polije untuk mahasiswa berprestasi dan kurang mampu dari semua program studi.",
    poster: "assets/images/poster-beasiswa.png",
    tags: ["D2", "D3", "D4", "Universitas"],
    jenjang: ["D2", "D3", "D4"],
    tipePendanaan: "Partially Funded",
    tipeBeasiswa: "University Scholarship",
    mulai: "2024-07-01",
    deadline: "2024-09-30",
    bulan: 7,
    tahun: 2024,
    statusPendaftaran: "Dibuka",
    linkExternal: "https://polije.ac.id/beasiswa",
    deskripsiLengkap: "Beasiswa internal yang disediakan Politeknik Negeri Jember bagi mahasiswa aktif yang memenuhi kriteria prestasi atau ekonomi.",
    persyaratan: [
      "Mahasiswa aktif Polije minimal semester 2.",
      "IPK minimal 3.00.",
      "Tidak sedang menerima beasiswa lain.",
      "Mendapatkan rekomendasi dari dosen wali."
    ],
    keuntungan: [
      "Pembebasan UKT sebagian atau penuh.",
      "Sertifikat penghargaan."
    ],
    prestasi: false,
    pemerintah: false,
    ipkMin: 3.0,
    semester: [2, 3, 4, 5, 6, 7, 8],
    sktm: true
  },
  {
    id: 8,
    judul: "Beasiswa LPDP 2024",
    perusahaan: "Kemenkeu RI",
    deskripsiSingkat: "Beasiswa penuh dari pemerintah untuk program magister dan doktoral di dalam dan luar negeri.",
    poster: "assets/images/poster-beasiswa.png",
    tags: ["S2", "S3", "Pemerintah"],
    jenjang: ["S2", "S3"],
    tipePendanaan: "Fully Funded",
    tipeBeasiswa: "Government Scholarship",
    mulai: "2024-01-02",
    deadline: "2024-02-28",
    bulan: 1,
    tahun: 2024,
    statusPendaftaran: "Ditutup",
    linkExternal: "https://lpdp.kemenkeu.go.id",
    deskripsiLengkap: "LPDP adalah beasiswa penuh dari Kementerian Keuangan RI untuk program S2/S3 di universitas terbaik dalam dan luar negeri.",
    persyaratan: [
      "Warga Negara Indonesia.",
      "Memiliki Letter of Acceptance (LoA) atau akan mendaftar ke universitas tujuan.",
      "IPK S1 minimal 3.00.",
      "Skor TOEFL/IELTS memenuhi persyaratan."
    ],
    keuntungan: [
      "Biaya kuliah penuh.",
      "Biaya hidup bulanan.",
      "Biaya buku dan tesis/disertasi.",
      "Tiket pesawat PP.",
      "Asuransi kesehatan.",
      "Dana darurat."
    ],
    prestasi: true,
    pemerintah: true,
    ipkMin: 3.0,
    semester: [1, 2, 3, 4],
    sktm: false
  }
];

// Data Pustaka
const pustakaData = [
  {
    id: 1,
    nama: "Curriculum Vitae (CV)",
    deskripsiSingkat: "Curriculum Vitae (CV) adalah dokumen yang berisi ringkasan data diri, riwayat pendidikan, pengalaman organisasi atau kerja, prestasi, keterampilan, serta informasi pendukung lainnya yang menggambarkan kualitas dan kemampuan seseorang.",
    deskripsiLengkap: "Curriculum Vitae (CV) adalah dokumen yang berisi ringkasan data diri, riwayat pendidikan, pengalaman organisasi atau kerja, prestasi, keterampilan, serta informasi pendukung lainnya yang menggambarkan kualitas dan kemampuan seseorang. Dalam pendaftaran beasiswa, CV berfungsi sebagai alat untuk memperkenalkan diri secara profesional kepada pihak seleksi, sehingga mereka dapat menilai potensi akademik, pengalaman, dan pencapaian pelamar. Melalui CV yang jelas dan terstruktur, panitia beasiswa dapat melihat kesesuaian antara kualifikasi pelamar dengan kriteria beasiswa yang ditawarkan.",
    gambar: "assets/images/cv-preview.png",
    file: "#"
  },
  {
    id: 2,
    nama: "Surat Pernyataan 3",
    deskripsiSingkat: "Surat pernyataan yang wajib dilampirkan jika ingin mendaftar beasiswa",
    deskripsiLengkap: "Surat pernyataan merupakan dokumen resmi yang harus ditandatangani oleh calon penerima beasiswa sebagai bentuk komitmen tertulis. Dokumen ini berisi pernyataan bahwa pemohon bersedia memenuhi persyaratan dan ketentuan yang berlaku dalam program beasiswa.",
    gambar: "assets/images/cv-preview.png",
    file: "#"
  },
  {
    id: 3,
    nama: "Surat Pernyataan 4",
    deskripsiSingkat: "Surat pernyataan yang wajib dilampirkan jika ingin mendaftar beasiswa",
    deskripsiLengkap: "Surat pernyataan tambahan yang diperlukan untuk melengkapi berkas pendaftaran beasiswa. Dokumen ini mencakup komitmen akademik dan kepatuhan terhadap regulasi program beasiswa.",
    gambar: "assets/images/cv-preview.png",
    file: "#"
  },
  {
    id: 4,
    nama: "Surat Rekomendasi",
    deskripsiSingkat: "Contoh surat rekomendasi dari dosen pembimbing untuk pendaftaran beasiswa",
    deskripsiLengkap: "Surat rekomendasi adalah dokumen yang ditulis oleh dosen, atasan, atau pihak berwenang lainnya yang merekomendasikan seorang mahasiswa untuk menerima beasiswa berdasarkan prestasi, karakter, dan potensinya.",
    gambar: "assets/images/cv-preview.png",
    file: "#"
  }
];

// Data FAQ
const faqData = [
  {
    id: 1,
    pertanyaan: "Apa ada contoh dokumen yang sering jadi persyaratan pendaftaran beasiswa?",
    jawaban: "Ya, beberapa dokumen yang sering dibutuhkan antara lain: Curriculum Vitae (CV), Surat Pernyataan, Transkrip Nilai, Surat Rekomendasi dari dosen, dan Sertifikat prestasi. Anda bisa melihat contoh-contohnya di halaman Pustaka kami."
  },
  {
    id: 2,
    pertanyaan: "Apakah gap year (jeda kuliah) diperbolehkan?",
    jawaban: "Kebijakan mengenai gap year berbeda-beda tergantung program beasiswa. Beberapa beasiswa mengizinkan gap year sementara yang lain mengharuskan mahasiswa aktif tanpa jeda. Silakan cek persyaratan spesifik setiap beasiswa."
  },
  {
    id: 3,
    pertanyaan: "Apakah anak PNS/ASN boleh mendaftar?",
    jawaban: "Untuk sebagian besar beasiswa, anak PNS/ASN tetap boleh mendaftar. Namun, untuk beasiswa yang berbasis kebutuhan ekonomi seperti KIP Kuliah, ada ketentuan khusus terkait penghasilan orang tua."
  },
  {
    id: 4,
    pertanyaan: "Apa batasan nilai rapor/IPK minimum untuk mendaftar?",
    jawaban: "Batasan IPK minimum berbeda setiap beasiswa. Umumnya berkisar antara 3.00 - 3.50. Beberapa beasiswa pemerintah mungkin menerima IPK lebih rendah jika disertai bukti keterbatasan ekonomi."
  },
  {
    id: 5,
    pertanyaan: "Apakah boleh menggunakan surat rekomendasi dari tahun sebelumnya?",
    jawaban: "Sebaiknya gunakan surat rekomendasi terbaru yang dikeluarkan dalam kurun 6 bulan terakhir. Surat rekomendasi lama mungkin tidak diterima karena dianggap tidak mencerminkan kondisi terkini."
  },
  {
    id: 6,
    pertanyaan: "Apa saja tahapan dan jadwal seleksi beasiswa?",
    jawaban: "Umumnya tahapan seleksi beasiswa meliputi: 1) Pendaftaran online, 2) Seleksi administrasi/berkas, 3) Tes tertulis (jika ada), 4) Wawancara, 5) Pengumuman hasil. Jadwal spesifik dapat dilihat di detail masing-masing beasiswa."
  },
  {
    id: 7,
    pertanyaan: "Apa yang harus dipersiapkan untuk wawancara?",
    jawaban: "Persiapkan: 1) Pengetahuan tentang program beasiswa, 2) Motivasi yang kuat, 3) Portfolio prestasi, 4) Rencana studi/karir, 5) Kemampuan presentasi diri. Latihan mock interview juga sangat membantu."
  },
  {
    id: 8,
    pertanyaan: "Apakah beasiswa ini mengikat kontrak dengan mitra setelah lulus?",
    jawaban: "Ya, contohnya beasiswa BCA meminta penerimanya untuk bekerja di BCA setelah lulus dari perkuliahan. Setiap beasiswa memiliki ketentuan yang berbeda mengenai ikatan dinas setelah lulus."
  }
];

// Data Simulasi (dummy submissions)
const simulasiData = [
  {
    id: 1,
    namaBeasiswa: "Beasiswa Pendidikan Prestasi Kita BATCH #5",
    namaMahasiswa: "Ahmad Rizki Pratama",
    nim: "E41212345",
    prodi: "Teknik Informatika",
    angkatan: 2022,
    semester: 5,
    ipk: 3.65,
    jenisKelamin: "Laki-laki",
    namaOrtu: "Budi Santoso",
    pekerjaanOrtu: "Wiraswasta",
    penghasilan: "Rp 3.000.000 - Rp 5.000.000",
    tanggungan: 3,
    sktm: true,
    aktifKuliah: true,
    statusBeasiswaLain: false,
    organisasi: "BEM Polije - Ketua Divisi Pendidikan",
    prestasi: "Juara 2 Lomba Web Development Tingkat Nasional 2023",
    motivasi: "Saya ingin meringankan beban orang tua dalam membiayai pendidikan saya. Dengan beasiswa ini, saya dapat fokus pada pengembangan diri dan berkontribusi lebih banyak untuk kampus dan masyarakat.",
    dokumen: "berkas_ahmad_rizki.pdf",
    tanggalSubmit: "2024-03-15T10:30:00",
    status: "pending",
    hasilReview: null
  },
  {
    id: 2,
    namaBeasiswa: "Beasiswa KIP Kuliah 2024",
    namaMahasiswa: "Siti Nurhaliza",
    nim: "E41212678",
    prodi: "Akuntansi",
    angkatan: 2023,
    semester: 3,
    ipk: 3.80,
    jenisKelamin: "Perempuan",
    namaOrtu: "Slamet Riyadi",
    pekerjaanOrtu: "Petani",
    penghasilan: "< Rp 1.500.000",
    tanggungan: 5,
    sktm: true,
    aktifKuliah: true,
    statusBeasiswaLain: false,
    organisasi: "HMPS Akuntansi - Sekretaris",
    prestasi: "Peraih IPK Tertinggi Prodi Akuntansi Semester 2",
    motivasi: "Berasal dari keluarga kurang mampu, saya berharap beasiswa ini dapat membantu melanjutkan studi agar bisa mewujudkan cita-cita menjadi akuntan profesional dan membantu perekonomian keluarga.",
    dokumen: "berkas_siti_nurhaliza.pdf",
    tanggalSubmit: "2024-03-18T14:20:00",
    status: "reviewed",
    hasilReview: {
      hasilSimulasi: "Berpotensi Lulus",
      tanggalReview: "2024-03-20T09:15:00",
      skor: 92,
      komentar: "Berkas lengkap dan memenuhi semua persyaratan. IPK sangat baik, aktif berorganisasi, dan memiliki latar belakang ekonomi yang sesuai dengan kriteria KIP Kuliah. Sangat direkomendasikan."
    }
  },
  {
    id: 3,
    namaBeasiswa: "Beasiswa Djarum Foundation",
    namaMahasiswa: "Reza Fahlevi",
    nim: "E41211999",
    prodi: "Manajemen Agribisnis",
    angkatan: 2021,
    semester: 6,
    ipk: 3.45,
    jenisKelamin: "Laki-laki",
    namaOrtu: "Hasan Basri",
    pekerjaanOrtu: "PNS",
    penghasilan: "Rp 5.000.000 - Rp 8.000.000",
    tanggungan: 2,
    sktm: false,
    aktifKuliah: true,
    statusBeasiswaLain: false,
    organisasi: "UKM Kewirausahaan - Anggota Aktif",
    prestasi: "Finalis Business Plan Competition Jatim 2023",
    motivasi: "Saya ingin mengembangkan kemampuan leadership dan soft skills melalui program Djarum Beasiswa Plus sambil berkontribusi untuk pengembangan agribisnis di Jember.",
    dokumen: "berkas_reza_fahlevi.pdf",
    tanggalSubmit: "2024-04-02T08:45:00",
    status: "pending",
    hasilReview: null
  },
  {
    id: 4,
    namaBeasiswa: "Beasiswa Bank Indonesia",
    namaMahasiswa: "Dewi Ayu Lestari",
    nim: "E41212456",
    prodi: "Teknik Energi Terbarukan",
    angkatan: 2022,
    semester: 4,
    ipk: 3.55,
    jenisKelamin: "Perempuan",
    namaOrtu: "Suparman",
    pekerjaanOrtu: "Pedagang",
    penghasilan: "Rp 2.000.000 - Rp 3.000.000",
    tanggungan: 4,
    sktm: true,
    aktifKuliah: true,
    statusBeasiswaLain: false,
    organisasi: "Tidak ada",
    prestasi: "Tidak ada",
    motivasi: "Saya berharap beasiswa ini dapat meringankan biaya pendidikan saya. Saya berkomitmen untuk menyelesaikan studi tepat waktu dan berkontribusi di bidang energi terbarukan.",
    dokumen: "berkas_dewi_ayu.pdf",
    tanggalSubmit: "2024-04-05T16:10:00",
    status: "reviewed",
    hasilReview: {
      hasilSimulasi: "Belum Memenuhi Syarat",
      tanggalReview: "2024-04-08T11:30:00",
      skor: 58,
      komentar: "IPK memenuhi syarat minimal, namun tidak ada prestasi dan organisasi yang menjadi nilai tambah. Disarankan untuk aktif berorganisasi dan meningkatkan portfolio prestasi sebelum mendaftar kembali."
    }
  },
  {
    id: 5,
    namaBeasiswa: "Beasiswa Unggulan Kemendikbud",
    namaMahasiswa: "Fajar Nur Hidayat",
    nim: "E41213001",
    prodi: "Teknologi Informasi",
    angkatan: 2023,
    semester: 3,
    ipk: 3.90,
    jenisKelamin: "Laki-laki",
    namaOrtu: "Agus Widodo",
    pekerjaanOrtu: "Guru",
    penghasilan: "Rp 3.000.000 - Rp 5.000.000",
    tanggungan: 3,
    sktm: false,
    aktifKuliah: true,
    statusBeasiswaLain: false,
    organisasi: "Google Developer Student Clubs Lead - Polije",
    prestasi: "Juara 1 Hackathon Nasional IoT 2023, Delegasi Indonesia di ASEAN Tech Summit",
    motivasi: "Sebagai mahasiswa yang passionate di bidang teknologi, saya ingin beasiswa ini untuk mendukung riset dan pengembangan proyek IoT untuk pertanian yang saya kerjakan bersama tim.",
    dokumen: "berkas_fajar_nur.pdf",
    tanggalSubmit: "2024-05-10T09:00:00",
    status: "pending",
    hasilReview: null
  }
];

// Data Users Dummy
const usersData = {
  mahasiswa: {
    nama: "Ahmad Rizki Pratama",
    email: "ahmad.rizki@student.polije.ac.id",
    nim: "E41212345",
    prodi: "Teknik Informatika",
    jurusan: "Teknologi Informasi",
    angkatan: 2022,
    semester: 5,
    ipk: 3.65,
    jenisKelamin: "Laki-laki",
    role: "mahasiswa"
  },
  mitra: {
    nama: "Prestasi Kita Foundation",
    email: "admin@prestasikita.com",
    namaPerusahaan: "Prestasi Kita Foundation",
    bidang: "Pendidikan & Sosial",
    alamat: "Jakarta Selatan, DKI Jakarta",
    telepon: "+62 812 3456 7890",
    website: "https://prestasikita.com",
    role: "mitra"
  },
  admin: {
    nama: "Admin Beasiswa Polije",
    email: "admin@beasiswa.polije.ac.id",
    nip: "198501152010011001",
    jabatan: "Koordinator Beasiswa",
    unit: "Bagian Kemahasiswaan",
    telepon: "+62 8222 3333 444",
    role: "admin"
  }
};

// Program studi Polije
const prodiList = [
  "Teknik Informatika",
  "Teknologi Informasi",
  "Manajemen Informatika",
  "Teknik Energi Terbarukan",
  "Mesin Otomotif",
  "Teknik Mesin",
  "Akuntansi",
  "Manajemen Agribisnis",
  "Produksi Pertanian",
  "Budidaya Tanaman Perkebunan",
  "Produksi Tanaman Hortikultura",
  "Manajemen Agroindustri",
  "Teknologi Pengolahan Hasil Ternak",
  "Kesehatan Hewan",
  "Bahasa Inggris Terapan"
];
