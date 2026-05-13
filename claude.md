# Beasiswa POLIJE — Panduan Proyek untuk Claude Code

## Gambaran Proyek
Sistem Informasi Beasiswa untuk mahasiswa Politeknik Negeri Jember (Polije).
Proyek kuliah semester 2. Deadline 2 minggu.

## Stack Teknologi
- Frontend: HTML, Bootstrap 5, JavaScript (semua native, CSS dan JS dipisah)
- Backend: PHP Native OOP
- Database: MySQL — nama database `db_beasiswa`
- Hosting: InfinityFree (PHP + MySQL), repository di GitHub

## Tiga Role Pengguna
1. **Mahasiswa** — mencari dan mensimulasikan pendaftaran beasiswa
2. **Mitra** — mengunggah informasi beasiswa (akun dibuat oleh admin)
3. **Admin** — mengelola seluruh sistem

## Struktur Folder — WAJIB DIIKUTI
beasiswa-polije/
├── classes/
├── config/
│   ├── Database.php        ← SUDAH ADA, jangan diubah
│   └── app.php
├── helpers/
├── public/
│   ├── auth/
│   │   ├── login-mahasiswa.php
│   │   ├── login-mitra.php
│   │   ├── login-admin.php
│   │   ├── register-mahasiswa.php
│   │   └── logout.php
│   ├── backend/
│   │   ├── beasiswa/
│   │   ├── simulasi/
│   │   ├── hasil-simulasi/
│   │   ├── pustaka/
│   │   ├── faq/
│   │   └── akun/
│   ├── frontend/
│   │   ├── mahasiswa/
│   │   ├── mitra/
│   │   ├── admin/
│   │   └── layout/
│   ├── assets/
│   │   ├── css/style.css
│   │   ├── js/main.js
│   │   └── img/
│   ├── uploads/
│   │   ├── simulasi/
│   │   └── pustaka/
│   └── index.php
└── db_beasiswa.sql         ← SUDAH ADA, jangan diubah

## Database — 13 Tabel (sudah final, jangan diubah)
- prodi, mahasiswa, mitra, admin
- tag, beasiswa, beasiswa_tag
- simulasi, file_simulasi, data_orang_tua, hasil_simulasi
- pustaka, faq

## Catatan Penting Database
- `status_pendaftaran` di tabel beasiswa TIDAK disimpan di database
- Dihitung otomatis via query CASE WHEN berdasarkan tgl_buka dan tgl_tutup
- Tabel beasiswa punya trigger: tepat satu dari id_mitra atau id_admin harus berisi nilai

## Konvensi Kode PHP

### Konstanta Path (selalu gunakan ini, bukan path relatif manual)
```php
// sudah didefinisikan di config/app.php
define('BASE_PATH', dirname(__DIR__));
define('CLASSES_PATH', BASE_PATH . '/classes/');
define('HELPERS_PATH', BASE_PATH . '/helpers/');
define('CONFIG_PATH', BASE_PATH . '/config/');
define('BASE_URL', '/beasiswa-polije/public');
```

### Pola Setiap Halaman Frontend
```php
<?php
require_once '../../../config/app.php';
require_once HELPERS_PATH . 'Session.php';
require_once CLASSES_PATH . 'NamaClass.php';

Session::start();
Session::requireLogin();
Session::requireRole('mahasiswa');

$db = Database::getInstance()->getConnection();
$obj = new NamaClass($db);
$data = $obj->method();
?>
<?php require_once '../layout/header.php'; ?>
<?php require_once '../layout/navbar-mahasiswa.php'; ?>

<!-- Konten halaman -->

<?php require_once '../layout/footer.php'; ?>
```

### Pola Setiap File Backend (handler POST)
```php
<?php
require_once '../../../config/app.php';
require_once HELPERS_PATH . 'Session.php';
require_once HELPERS_PATH . 'Response.php';
require_once HELPERS_PATH . 'Validator.php';

Session::start();
Session::requireLogin();
Session::requireRole('mahasiswa');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    Response::redirectTo(BASE_URL . '/frontend/mahasiswa/beranda.php');
}
// proses → flash message → redirect
```

## Aturan yang TIDAK BOLEH Dilanggar
1. JANGAN mengubah `config/Database.php` yang sudah ada
2. JANGAN mengubah `db_beasiswa.sql` yang sudah ada
3. JANGAN membuat ulang file frontend yang sudah ada — hanya sisipkan PHP
4. JANGAN menggunakan path relatif manual (../../) — gunakan konstanta PATH
5. JANGAN menambahkan library CSS/JS selain Bootstrap 5
6. JANGAN membuat controller terpisah — panggil class model langsung dari halaman
7. SELALU gunakan prepared statement MySQLi — tidak ada query string concatenation
8. SELALU cek session dan role di baris pertama setiap halaman, sebelum HTML apapun
9. SELALU gunakan transaksi MySQL untuk operasi yang melibatkan lebih dari satu tabel

## Aturan Upload File
- Hanya PDF, maksimal 5MB per file
- Folder uploads/ dibuat otomatis oleh FileUploader jika belum ada
- Path disimpan relatif dari root public: `uploads/simulasi/id_simulasi/namafile.pdf`

## Alur Bisnis Utama

### Alur Beasiswa
1. Mitra unggah beasiswa → status_verifikasi = pending, id_mitra diisi, id_admin NULL
2. Admin verifikasi → status_verifikasi = terverifikasi atau ditolak
3. Admin unggah beasiswa → status_verifikasi = terverifikasi otomatis, id_admin diisi, id_mitra NULL
4. Beasiswa tampil ke mahasiswa hanya jika status_verifikasi = terverifikasi

### Alur Simulasi
1. Mahasiswa submit simulasi → INSERT ke simulasi + data_orang_tua + file_simulasi + hasil_simulasi dalam satu transaksi
2. hasil_simulasi dibuat dengan status_simulasi = pending, is_read = FALSE
3. Notifikasi merah muncul di menu Pesan jika ada is_read = FALSE
4. Admin review → update skor, catatan, status menjadi lulus/tidak_lulus
5. Mahasiswa buka halaman Pesan → semua is_read di-update TRUE → notifikasi hilang

### Login
- Tiga halaman login terpisah: login-mahasiswa.php, login-mitra.php, login-admin.php
- Setelah login berhasil simpan session: id, role, nama
- Redirect ke dashboard sesuai role

### Akun Mitra
- Hanya admin yang bisa membuat akun mitra (tidak ada register mitra publik)

## Pembuatan Akun

### Mahasiswa
- Mendaftar sendiri lewat halaman register publik (`public/auth/register.php`)

### Mitra  
- Tidak ada register publik
- Akun dibuat oleh admin lewat halaman `admin/kelola-mitra.php`
- Admin mengisi form lalu sistem INSERT ke tabel mitra

### Admin
- Tidak ada halaman pembuatan akun di aplikasi
- Akun dibuat langsung via data dummy di db_beasiswa.sql
- atau INSERT manual lewat phpMyAdmin

## Halaman Login
- Satu halaman login untuk semua role: `public/auth/login.php`
- Terdapat selector role (dropdown atau radio button)
- Setelah login redirect ke dashboard sesuai role yang dipilih

## Desain Frontend
- Bootstrap 5 murni, tidak ada CSS framework lain
- CSS kustom di public/assets/css/style.css
- JS kustom di public/assets/js/main.js
- Halaman mahasiswa: mengikuti desain Figma (HTML sudah dibuat terpisah)
- Halaman mitra: konsisten dengan gaya mahasiswa, tambahan navbar "Kelola Beasiswa"
- Halaman admin: menggunakan sidebar Bootstrap statis (selalu tampil)
- Flash message: alert Bootstrap dengan auto-dismiss
- Konfirmasi hapus: modal Bootstrap

## Status Pengerjaan
- [x] db_beasiswa.sql
- [x] config/Database.php
- [x] config/app.php
- [x] helpers/Session.php
- [x] helpers/Validator.php
- [x] helpers/FileUploader.php
- [x] helpers/Response.php
- [x] classes/User.php
- [x] classes/Mahasiswa.php
- [x] classes/Mitra.php
- [x] classes/Admin.php
- [x] classes/Prodi.php
- [x] classes/Tag.php
- [x] classes/Beasiswa.php
- [x] classes/BeasiswaTag.php
- [x] classes/Simulasi.php
- [x] classes/FileSimulasi.php
- [x] classes/DataOrangTua.php
- [x] classes/HasilSimulasi.php
- [x] classes/Pustaka.php
- [x] classes/Faq.php
- [x] desain/tahap-a/ (layout & komponen)
- [x] desain/tahap-b/ (halaman publik)
- [x] desain/tahap-c/ (halaman mahasiswa utama)
- [x] desain/tahap-d/ (halaman mahasiswa pendukung)
- [x] desain/tahap-e/ (halaman mitra)
- [x] desain/tahap-f/ (halaman admin)
- [ ] public/assets/css/style.css
- [ ] public/assets/js/main.js
- [ ] public/auth/
- [ ] public/backend/
- [ ] public/frontend/
- [ ] public/index.php

## Aturan Frontend — HTML Statis

### Struktur Komentar Wajib di Setiap File HTML
Setiap file HTML harus menggunakan komentar pembatas ini:
```html
<!-- ========== HEAD ========== -->
<!-- ========== NAVBAR ========== -->
<!-- ========== KONTEN UTAMA ========== -->
<!-- ========== FOOTER ========== -->
```

### Bagian yang Akan Dijadikan require_once
Tandai bagian layout dengan:
```html
<!-- [REQUIRE_ONCE: navbar-mahasiswa.php] -->
...isi navbar...
<!-- [END REQUIRE_ONCE] -->
```

### Aturan CSS dan JS
- CSS kustom ditulis di `public/assets/css/style.css`
- JS kustom ditulis di `public/assets/js/main.js`
- Tidak ada inline style kecuali terpaksa
- Tidak ada library selain Bootstrap 5

### Placeholder Gambar
- Gunakan `https://placehold.co/[ukuran]`

### Bahasa
- Seluruh konten dalam Bahasa Indonesia
- Data dummy harus realistis (nama beasiswa, tanggal, dsb.)

### Halaman yang Punya Desain Figma
- Semua halaman mahasiswa
- index.php (landing page)
- Halaman login dan register

### Halaman yang Dirancang Sendiri (tidak ada desain Figma)
- Semua halaman mitra — konsisten dengan gaya halaman mahasiswa
- Semua halaman admin — menggunakan sidebar Bootstrap statis

## Folder Desain HTML Statis

Folder `desain/` berisi seluruh file HTML statis hasil konversi Figma.
Folder ini bersifat sementara — hanya sebagai referensi saat memecah HTML menjadi PHP.
JANGAN hapus folder ini selama proses pengerjaan berlangsung.

beasiswa-polije/
└── desain/
    ├── tahap-a/
    │   ├── navbar-mahasiswa.html
    │   ├── navbar-mitra.html
    │   ├── sidebar-admin.html
    │   └── footer.html
    │
    ├── tahap-b/
    │   ├── index.html
    │   ├── login.html
    │   ├── register-mahasiswa.html
    │
    ├── tahap-c/
    │   ├── beranda.html
    │   ├── detail-beasiswa.html
    │   ├── simulasi.html
    │   └── pesan.html
    │
    ├── tahap-d/
    │   ├── pustaka.html
    │   ├── faq.html
    │   ├── tentang.html
    │   ├── alur-beasiswa.html
    │   ├── jenis-beasiswa.html
    │   └── profil.html
    │
    ├── tahap-e/
    │   ├── kelola-beasiswa.html
    │   ├── unggah-beasiswa.html
    │   └── profil.html
    │
    └── tahap-f/
        ├── dashboard.html
        ├── kelola-beasiswa.html
        ├── unggah-beasiswa.html
        ├── review-simulasi.html
        ├── detail-review.html
        ├── kelola-pustaka.html
        ├── kelola-faq.html
        └── kelola-mitra.html

## Fitur Per Role — Tambahan

### Navbar Mahasiswa (kanan atas)
- Ikon notifikasi 🔔 dengan badge merah → mengarah ke pesan.php
- Badge menampilkan COUNT is_read = FALSE dari hasil_simulasi milik mahasiswa
- Dropdown akun: nama mahasiswa → Profil, Logout

### Navbar Mitra (kanan atas)
- Dropdown akun: nama mitra → Profil, Logout
- Tidak ada ikon notifikasi — mitra tidak memiliki fitur Pesan

### Fitur Pesan
- HANYA untuk mahasiswa
- Mitra tidak memiliki fitur Pesan
- Notifikasi is_read hanya berlaku untuk role mahasiswa

## Warna Website
:root {
    --color-primary:      #00B4D8;   /* Biru utama — brand, nav links, aksen */
    --color-primary-dark: #0096B4;   /* Biru gelap — hover states */
    --color-secondary:    #007A94;   /* Biru lebih gelap — ikon kontak, aksen kuat */
    --color-light:        #90E0EF;   /* Biru muda — background footer, border navbar */
    --color-lighter:      #CAF0F8;   /* Biru sangat muda — hover dropdown */
    --color-text:         #333333;   /* Teks utama — heading, body */
    --color-text-muted:   #6c757d;   /* Teks sekunder — deskripsi, subtitle */
    --color-white:        #ffffff;   /* Background putih — navbar, kartu */
    --color-dark:         #03045E;   /* Navy gelap — sidebar admin */
}

## Struktur Folder desain/ — Final
desain/
├── tahap-a/    ← layout bersama (navbar, sidebar, footer)
├── tahap-b/    ← halaman publik (index, login, register)
├── tahap-c/    ← halaman mahasiswa utama
├── tahap-d/    ← sections bersama + profil mahasiswa
├── tahap-e/    ← halaman khusus mitra (kelola & unggah beasiswa, profil)
└── tahap-f/    ← halaman admin

## Pemetaan File desain/ ke public/ — Final

### Layout (dari tahap-a/)
| File desain | Tujuan |
|---|---|
| navbar-mahasiswa.html | public/frontend/layout/navbar-mahasiswa.php |
| navbar-mitra.html | public/frontend/layout/navbar-mitra.php |
| sidebar-admin.html | public/frontend/layout/sidebar-admin.php |
| footer.html | public/frontend/layout/footer.php |

### Halaman Publik (dari tahap-b/)
| File desain | Tujuan |
|---|---|
| index.html | public/index.php |
| login.html | public/auth/login.php |
| register-mahasiswa.html | public/auth/register.php |

### Sections Bersama (dari tahap-d/)
Dipakai oleh beranda mahasiswa DAN mitra via require_once.
| File desain | Tujuan |
|---|---|
| beranda.html | public/frontend/sections/section-beranda.php |
| tentang.html | public/frontend/sections/section-tentang.php |
| jenis-beasiswa.html | public/frontend/sections/section-jenis-beasiswa.php |
| alur-beasiswa.html | public/frontend/sections/section-alur-beasiswa.php |
| pustaka.html | public/frontend/sections/section-pustaka.php |
| faq.html | public/frontend/sections/section-faq.php |

### Halaman Mahasiswa (dari tahap-c/ dan tahap-d/)
| File desain | Tujuan |
|---|---|
y| tahap-c/detail-beasiswa.html | public/frontend/mahasiswa/detail-beasiswa.php |
| tahap-c/simulasi.html | public/frontend/mahasiswa/simulasi.php |
| tahap-c/pesan.html | public/frontend/mahasiswa/pesan.php |
| tahap-d/profil.html | public/frontend/mahasiswa/profil.php |

### Halaman Mitra (dari tahap-e/)
| File desain | Tujuan |
|---|---|
| tahap-e/kelola-beasiswa.html | public/frontend/mitra/kelola-beasiswa.php |
| tahap-e/unggah-beasiswa.html | public/frontend/mitra/unggah-beasiswa.php |
| tahap-e/profil.html | public/frontend/mitra/profil.php |

### Halaman Admin (dari tahap-f/)
| File desain | Tujuan |
|---|---|
| dashboard.html | public/frontend/admin/dashboard.php |
| kelola-beasiswa.html | public/frontend/admin/kelola-beasiswa.php |
| unggah-beasiswa.html | public/frontend/admin/unggah-beasiswa.php |
| review-simulasi.html | public/frontend/admin/review-simulasi.php |
| detail-review.html | public/frontend/admin/detail-review.php |
| kelola-pustaka.html | public/frontend/admin/kelola-pustaka.php |
| kelola-faq.html | public/frontend/admin/kelola-faq.php |
| kelola-mitra.html | public/frontend/admin/kelola-mitra.php |

## Struktur public/ — Final
public/
├── api/
│   └── beasiswa/
│       └── search.php
├── auth/
│   ├── login.php
│   ├── register.php
│   └── logout.php
├── backend/
│   ├── beasiswa/
│   │   ├── store.php
│   │   └── delete.php
│   ├── simulasi/
│   │   └── store.php
│   ├── hasil-simulasi/
│   │   └── update.php
│   ├── pustaka/
│   │   ├── store.php
│   │   ├── update.php
│   │   └── delete.php
│   ├── faq/
│   │   ├── store.php
│   │   ├── update.php
│   │   └── delete.php
│   └── akun/
│       ├── store-mitra.php
│       └── update-profil.php
├── frontend/
│   ├── layout/
│   │   ├── header.php
│   │   ├── footer.php
│   │   ├── navbar-mahasiswa.php
│   │   ├── navbar-mitra.php
│   │   └── sidebar-admin.php
│   ├── sections/
│   │   ├── section-beranda.php
│   │   ├── section-tentang.php
│   │   ├── section-jenis-beasiswa.php
│   │   ├── section-alur-beasiswa.php
│   │   ├── section-pustaka.php
│   │   └── section-faq.php
│   ├── mahasiswa/
│   │   ├── beranda.php        ← include semua sections
│   │   ├── detail-beasiswa.php
│   │   ├── simulasi.php
│   │   ├── pesan.php
│   │   └── profil.php
│   ├── mitra/
│   │   ├── beranda.php        ← include semua sections
│   │   ├── kelola-beasiswa.php
│   │   ├── unggah-beasiswa.php
│   │   └── profil.php
│   └── admin/
│       ├── dashboard.php
│       ├── kelola-beasiswa.php
│       ├── unggah-beasiswa.php
│       ├── review-simulasi.php
│       ├── detail-review.php
│       ├── kelola-pustaka.php
│       ├── kelola-faq.php
│       └── kelola-mitra.php
├── assets/
│   ├── css/
│   │   └── style.css
│   ├── js/
│   │   └── main.js
│   └── img/
├── uploads/
│   ├── simulasi/
│   │   └── .gitkeep
│   └── pustaka/
│       └── .gitkeep
└── index.php