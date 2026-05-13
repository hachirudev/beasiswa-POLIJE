<!-- ========== SECTION: PUSTAKA ========== -->
<section id="pustaka" class="py-5" style="background: var(--color-white);">
    <div class="container">
        <h2 class="text-center" style="font-weight: 800; font-size: 2rem; color: var(--color-dark); margin-bottom: 2rem;">Pustaka</h2>

        <div class="row g-4">
            <?php if (!empty($listPustaka)): ?>
                <?php foreach ($listPustaka as $p): ?>
                <div class="col-lg-6">
                    <div class="pustaka-card">
                        <div class="pustaka-img-container">
                            <?php $img = !empty($p['preview_dokumen']) ? BASE_URL . '/uploads/pustaka/' . $p['preview_dokumen'] : BASE_URL . '/assets/img/cv-preview.png'; ?>
                            <img src="<?= htmlspecialchars($img) ?>" alt="Preview Dokumen" class="pustaka-img">
                        </div>
                        <div class="pustaka-body">
                            <h3 class="pustaka-title"><?= htmlspecialchars($p['nama_dokumen'] ?? '') ?></h3>
                            <p class="pustaka-desc"><?= htmlspecialchars($p['deskripsi_dokumen'] ?? '') ?></p>
                            <a href="<?= BASE_URL ?>/uploads/pustaka/<?= htmlspecialchars($p['file_path'] ?? '') ?>" target="_blank" class="btn-download"><i class="bi bi-cloud-arrow-down-fill me-2"></i>Download File</a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center text-muted py-4">Belum ada pustaka dokumen yang tersedia.</div>
            <?php endif; ?>
        </div>
    </div>
</section>
<!-- ========== END SECTION ========== -->
