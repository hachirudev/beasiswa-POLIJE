<!-- ========== SECTION: PUSTAKA ========== -->
<section id="pustaka" class="py-5" style="background: var(--color-lighter);">
    <div class="container">
        <h2 class="text-center"
            style="font-weight: 800; font-size: 2rem; color: var(--color-dark); margin-bottom: 2rem;">Pustaka</h2>

        <div class="row g-4" id="pustaka-container">
            <?php if (!empty($listPustaka)): ?>
                <?php foreach ($listPustaka as $idx => $p): ?>
                    <div class="col-lg-6 pustaka-item" data-page="<?= floor($idx / 4) + 1 ?>"
                        style="<?= ($idx >= 4) ? 'display: none;' : '' ?>">
                        <div class="pustaka-card">
                            <div class="pustaka-img-container">
                                <?php $img = !empty($p['preview_dokumen']) ? BASE_URL . '/uploads/pustaka/' . $p['preview_dokumen'] : BASE_URL . '/assets/img/cv-preview.png'; ?>
                                <img src="<?= htmlspecialchars($img) ?>" alt="Preview Dokumen" class="pustaka-img">
                            </div>
                            <div class="pustaka-body">
                                <h3 class="pustaka-title"><?= htmlspecialchars($p['nama_dokumen'] ?? '') ?></h3>
                                <p class="pustaka-desc"><?= htmlspecialchars($p['deskripsi_dokumen'] ?? '') ?></p>
                                <a href="<?= BASE_URL ?>/uploads/pustaka/<?= htmlspecialchars($p['file_path'] ?? '') ?>"
                                    target="_blank" class="btn-download"><i
                                        class="bi bi-cloud-arrow-down-fill me-2"></i>Download File</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center text-muted py-4">Belum ada pustaka dokumen yang tersedia.</div>
            <?php endif; ?>
        </div>

        <?php
        $totalPustaka = count($listPustaka ?? []);
        $totalPagesPustaka = ceil($totalPustaka / 4);
        if ($totalPagesPustaka > 1):
            ?>
            <nav class="mt-4 d-flex justify-content-center">
                <ul class="pagination" id="pagination-pustaka">
                    <li class="page-item disabled" id="prev-pustaka">
                        <a class="page-link" href="#pustaka"><i class="bi bi-chevron-left"></i></a>
                    </li>
                    <?php for ($i = 1; $i <= $totalPagesPustaka; $i++): ?>
                        <li class="page-item <?= $i === 1 ? 'active' : '' ?> page-number-pustaka" data-page="<?= $i ?>">
                            <a class="page-link" href="#pustaka"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                    <li class="page-item" id="next-pustaka">
                        <a class="page-link" href="#pustaka"><i class="bi bi-chevron-right"></i></a>
                    </li>
                </ul>
            </nav>

            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const items = document.querySelectorAll('.pustaka-item');
                    const pageNumbers = document.querySelectorAll('.page-number-pustaka');
                    const prevBtn = document.getElementById('prev-pustaka');
                    const nextBtn = document.getElementById('next-pustaka');
                    const totalPages = <?= $totalPagesPustaka ?>;
                    let currentPage = 1;

                    function showPage(page) {
                        items.forEach(item => {
                            if (parseInt(item.getAttribute('data-page')) === page) {
                                item.style.display = 'block';
                            } else {
                                item.style.display = 'none';
                            }
                        });

                        pageNumbers.forEach(num => {
                            if (parseInt(num.getAttribute('data-page')) === page) {
                                num.classList.add('active');
                            } else {
                                num.classList.remove('active');
                            }
                        });

                        if (prevBtn) {
                            if (page === 1) prevBtn.classList.add('disabled');
                            else prevBtn.classList.remove('disabled');
                        }

                        if (nextBtn) {
                            if (page === totalPages) nextBtn.classList.add('disabled');
                            else nextBtn.classList.remove('disabled');
                        }
                    }

                    pageNumbers.forEach(num => {
                        num.addEventListener('click', function (e) {
                            e.preventDefault();
                            currentPage = parseInt(this.getAttribute('data-page'));
                            showPage(currentPage);
                        });
                    });

                    if (prevBtn) {
                        prevBtn.addEventListener('click', function (e) {
                            e.preventDefault();
                            if (currentPage > 1) {
                                currentPage--;
                                showPage(currentPage);
                            }
                        });
                    }

                    if (nextBtn) {
                        nextBtn.addEventListener('click', function (e) {
                            e.preventDefault();
                            if (currentPage < totalPages) {
                                currentPage++;
                                showPage(currentPage);
                            }
                        });
                    }
                });
            </script>
        <?php endif; ?>
    </div>
</section>
<!-- ========== END SECTION ========== -->