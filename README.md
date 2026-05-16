# Beasiswa POLIJE

Sistem Informasi Beasiswa berbasis Web untuk mahasiswa Politeknik Negeri Jember (Polije). Sistem ini memfasilitasi mahasiswa untuk mencari dan melakukan simulasi pendaftaran beasiswa, serta memungkinkan mitra dan admin untuk mengelola informasi beasiswa.

## 🚀 Fitur Utama

Aplikasi ini memiliki tiga role pengguna utama:

1. **Mahasiswa**
   - Melihat daftar beasiswa yang tersedia.
   - Melakukan simulasi pendaftaran beasiswa dengan mengunggah dokumen.
   - Menerima notifikasi dan hasil review simulasi (Lulus/Tidak Lulus).
   - Membaca FAQ dan Pustaka (panduan/dokumen).

2. **Mitra**
   - Mengunggah informasi beasiswa baru (status pending, menunggu verifikasi Admin).
   - Mengelola beasiswa yang telah diunggah.
   *(Catatan: Akun mitra dibuatkan oleh Admin)*

3. **Admin**
   - Mengelola seluruh sistem dan master data.
   - Memverifikasi beasiswa yang diunggah oleh Mitra.
   - Mengunggah beasiswa secara langsung (otomatis terverifikasi).
   - Melakukan review terhadap simulasi pendaftaran mahasiswa.
   - Mengelola akun Mitra, Pustaka, dan FAQ.

## 🛠️ Stack Teknologi

- **Frontend:** HTML5, CSS3, JavaScript (Native), Bootstrap 5
- **Backend:** PHP Native (Pendekatan OOP)
- **Database:** MySQL
- **Desain UI/UX:** Diadaptasi dari Figma ke HTML Statis (folder `desain/`)

## 📁 Struktur Direktori

```text
beasiswa-polije/
├── desain/           # File statis HTML (Referensi Desain Awal)
├── public/           # Root Document Web (Unggah isi folder ini ke htdocs/)
│   ├── api/          # Endpoint API sederhana (contoh: pencarian)
│   ├── assets/       # File CSS, JS, dan Gambar (css/style.css, js/main.js)
│   ├── auth/         # File untuk proses Login, Register, dan Logout
│   ├── backend/      # File handler PHP untuk memproses form (POST)
│   ├── classes/      # Class Model PHP OOP (User, Beasiswa, Simulasi, dll)
│   ├── config/       # Konfigurasi Database dan Aplikasi (Konstanta URL/Path)
│   ├── frontend/     # File antarmuka / tampilan per role (Admin, Mahasiswa, Mitra)
│   ├── helpers/      # Class Helper (Session, Validator, Response, FileUploader)
│   ├── uploads/      # Folder unggahan dokumen (Simulasi & Pustaka)
│   ├── .htaccess     # Aturan server (Mencegah akses folder classes, config, helpers)
│   └── index.php     # Halaman Utama (Landing Page)
└── db_beasiswa.sql   # Skema Database & Data Dummy
```

## ⚙️ Panduan Instalasi (Localhost)

1. **Clone Repository**
   Pastikan Anda telah menginstal Git, lalu clone repository ini ke folder lokal server Anda (misal `htdocs` untuk XAMPP atau `www` untuk Laragon).

2. **Setup Database**
   - Buat database baru di MySQL dengan nama `db_beasiswa`.
   - Import file `db_beasiswa.sql` yang ada di root direktori ke dalam database tersebut. File ini sudah berisi tabel, trigger, dan beberapa data dummy.

3. **Konfigurasi Database**
   - Buka file `public/config/Database.php`.
   - Sesuaikan konfigurasi kredensial database (host, user, password, dbname) jika diperlukan. Secara default:
     ```php
     private $host = "localhost";
     private $user = "root";
     private $pass = ""; // kosongkan jika tanpa password
     private $dbname = "db_beasiswa";
     ```

4. **Menjalankan Aplikasi**
   - Akses aplikasi di browser melalui URL direktori public Anda, contoh:
     `http://localhost/beasiswa-polije/public/`
   - Pastikan pengaturan konstanta `BASE_URL` pada file `public/config/app.php` sesuai dengan path/environment lokal Anda.

## 📄 Catatan Pengembangan
- Proyek ini menggunakan **PHP Native OOP**, tidak menggunakan framework seperti Laravel/CodeIgniter.
- Pattern kode memisahkan file tampilan (di `public/frontend/`) dan file pemroses logic/database (di `public/backend/`).
- Akses dan Role checking selalu divalidasi pada baris pertama setiap file PHP menggunakan class `Session`.
- Aplikasi ini disiapkan untuk di-hosting di server PHP standar (seperti InfinityFree).

---
*Dikembangkan untuk memenuhi proyek mata kuliah di Politeknik Negeri Jember.*
