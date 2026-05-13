<!-- ========== SECTION: FAQ ========== -->
<section id="faq" class="py-5" style="background: var(--color-lighter);">
    <div class="container" style="max-width: 900px;">
        <h2 class="text-center" style="font-weight: 800; font-size: 2.2rem; color: var(--color-dark); margin-bottom: 2.5rem;">Pertanyaan yang Sering Diajukan</h2>

        <div class="accordion faq-accordion" id="accordionFAQ">
            <?php if (!empty($listFaq)): ?>
                <?php foreach ($listFaq as $index => $f): ?>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq<?= $f['id_pertanyaan'] ?>">
                            <span class="faq-icon"><i class="bi bi-plus-lg"></i><i class="bi bi-dash-lg"></i></span>
                            <?= htmlspecialchars($f['pertanyaan']) ?>
                        </button>
                    </h2>
                    <div id="faq<?= $f['id_pertanyaan'] ?>" class="accordion-collapse collapse" data-bs-parent="#accordionFAQ">
                        <div class="accordion-body">
                            <?= nl2br(htmlspecialchars($f['jawaban'] ?? '')) ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="text-center text-muted py-4">Belum ada FAQ yang tersedia.</div>
            <?php endif; ?>
        </div>
    </div>
</section>
<!-- ========== END SECTION ========== -->
