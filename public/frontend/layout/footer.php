<!-- ========== FOOTER ========== -->
<footer class="footer-beasiswa" id="footer-beasiswa">
    <div class="container">
        <div class="row g-4">
            <!-- Kolom 1: Tentang -->
            <div class="col-lg-3 col-md-6">
                <div class="footer-brand">Beasiswa POLIJE</div>
                <p class="footer-description">
                    Website yang menyediakan informasi terpusat yang tersedia di Politeknik Negeri Jember (Polije),
                    mengenai berbagai program beasiswa. Sistem menampilkan daftar beasiswa, persyaratan, jadwal
                    pendaftaran, serta pengumuman, agar informasi mudah diakses, jelas, dan terorganisir.
                </p>
                <div class="footer-social">
                    <a href="#" title="Facebook" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
                    <a href="#" title="TikTok" aria-label="TikTok"><i class="bi bi-tiktok"></i></a>
                    <a href="#" title="Instagram" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
                </div>
            </div>

            <!-- Kolom 2: Link navigasi -->
            <div class="col-lg-3 col-md-6">
                <h5 class="footer-heading">Tentang Beasiswa.POLIJE</h5>
                <ul class="footer-links">
                    <li><a href="<?= BASE_URL ?>/frontend/mahasiswa/beranda.php#beranda">Beranda</a></li>
                    <li><a href="<?= BASE_URL ?>/frontend/mahasiswa/beranda.php#beranda">Beasiswa</a></li>
                    <li><a href="<?= BASE_URL ?>/frontend/mahasiswa/beranda.php#tentang-kami">Tentang Kami</a></li>
                    <li><a href="<?= BASE_URL ?>/frontend/mahasiswa/beranda.php#jenis-beasiswa">Jenis Beasiswa</a></li>
                    <li><a href="<?= BASE_URL ?>/frontend/mahasiswa/beranda.php#alur-beasiswa">Alur Beasiswa</a></li>
                    <li><a href="<?= BASE_URL ?>/frontend/mahasiswa/beranda.php#pustaka">Pustaka</a></li>
                </ul>
            </div>

            <!-- Kolom 3: Hubungi Kami -->
            <div class="col-lg-3 col-md-6">
                <h5 class="footer-heading">Hubungi Kami</h5>
                <div class="contact-item">
                    <i class="bi bi-telephone-fill"></i>
                    <span>+62 8222 3333 444</span>
                </div>
                <div class="contact-item">
                    <i class="bi bi-envelope-fill"></i>
                    <span>beasiswa@polije.ac.id</span>
                </div>
                <div class="contact-item">
                    <i class="bi bi-geo-alt-fill"></i>
                    <span>Gedung A3 (Asah Asih Asuh), Jl. Mastrip, Kotak Pos 164, Jember 68101, Jawa Timur,
                        Indonesia.</span>
                </div>
            </div>

            <!-- Kolom 4: Partnership -->
            <div class="col-lg-3 col-md-6">
                <h5 class="footer-heading">Partnership</h5>
                <div class="partner-grid">
                    <div class="partner-slot">Partner</div>
                    <div class="partner-slot">Partner</div>
                    <div class="partner-slot">Partner</div>
                    <div class="partner-slot">Partner</div>
                    <div class="partner-slot">Partner</div>
                    <div class="partner-slot">Partner</div>
                    <div class="partner-slot">Partner</div>
                    <div class="partner-slot">Partner</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Copyright -->
    <div class="footer-copyright">
        Copyright &copy; <?= date('Y') ?> Beasiswa POLIJE
    </div>
</footer>
<!-- ========== END FOOTER ========== -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= BASE_URL ?>/assets/js/main.js"></script>
</body>
</html>
