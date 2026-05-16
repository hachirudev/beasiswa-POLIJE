<!-- ========== SECTION: TENTANG KAMI ========== -->
<section id="tentang-kami" class="py-5" style="background: var(--color-lighter);">
    <div class="container">
        <div class="row align-items-center g-5">
            <!-- Teks -->
            <div class="col-lg-6">
                <h2 style="color: var(--color-primary); font-weight: 800; font-size: 2.5rem; margin-bottom: 1.5rem;">
                    Beasiswa POLIJE</h2>

                <div class="d-inline-flex align-items-center bg-white rounded-3 shadow-sm px-3 py-2 mb-4">
                    <img src="<?= BASE_URL ?>/assets/img/logo polije.png"
                        alt="Logo Polije" style="width: 40px; height: 40px; margin-right: 15px; object-fit: contain;">
                    <span
                        style="color: var(--color-primary); font-weight: 800; font-size: 1.1rem; line-height: 1.2;">Beasiswa<br>POLIJE</span>
                </div>

                <h5 style="font-weight: 700; color: var(--color-text); margin-bottom: 1rem;">Selamat datang di Beasiswa
                    Polije.</h5>

                <p style="color: var(--color-text-muted); line-height: 1.7; margin-bottom: 2rem;">
                    Penyediaan informasi terpusat yang tersedia di Politeknik Negeri Jember (Polije), mengenai berbagai
                    program beasiswa. Sistem menampilkan daftar beasiswa, persyaratan, jadwal pendaftaran, serta
                    pengumuman, agar informasi mudah diakses, jelas, dan terorganisir.
                </p>

                <div class="d-flex flex-column align-items-start gap-3">
                    <button class="btn" onclick="shareWebsite()"
                        style="background: #222; color: #fff; font-weight: 600; border-radius: 2rem; padding: 0.6rem 1.5rem; display: flex; align-items: center; gap: 0.5rem; transition: transform 0.2s;">
                        <i class="bi bi-share-fill"></i> BAGIKAN
                    </button>
                    <a href="https://wa.me/6282223333444" target="_blank" class="btn text-decoration-none"
                        style="background: #4caf50; color: #fff; font-weight: 600; border-radius: 2rem; padding: 0.6rem 1.5rem; display: flex; align-items: center; gap: 0.5rem; transition: transform 0.2s;">
                        <i class="bi bi-whatsapp"></i> +62 8222 3333 444
                    </a>
                </div>
            </div>

            <!-- Gambar -->
            <div class="col-lg-6 text-center">
                <img src="<?= BASE_URL ?>/assets/img/tugu square.png" alt="Monumen Polije"
                    class="img-fluid rounded-4 shadow-lg"
                    style="object-fit: cover; width: 100%; max-width: 500px; height: 500px;">
            </div>
        </div>
    </div>
</section>

<script>
function shareWebsite() {
    const shareData = {
        title: 'Beasiswa POLIJE',
        text: 'Temukan informasi beasiswa terbaru di Politeknik Negeri Jember!',
        url: 'https://beasiswa-polije.infinityfreeapp.com/'
    };

    if (navigator.share) {
        navigator.share(shareData).catch((error) => console.log('Error sharing:', error));
    } else {
        // Fallback untuk browser PC yang tidak mensupport Web Share API
        navigator.clipboard.writeText(shareData.url).then(() => {
            alert('Tautan halaman berhasil disalin ke clipboard!');
        }).catch(err => {
            console.error('Gagal menyalin tautan: ', err);
            alert('Gagal menyalin tautan.');
        });
    }
}
</script>
<!-- ========== END SECTION ========== -->
