<!-- ========== SECTION: BERANDA ========== -->
<section id="beranda" class="py-4" style="background: var(--color-lighter);">
    <div class="container">
        <div class="row g-4">
            <!-- Sidebar Filter -->
            <div class="col-lg-3">
                <div class="filter-sidebar">
                    <div class="filter-title">Filter Beasiswa</div>

                    <div class="filter-section">
                        <div class="filter-heading" data-bs-toggle="collapse" data-bs-target="#fJenjang">Jenjang Beasiswa <i class="bi bi-chevron-up"></i></div>
                        <div class="collapse show filter-options" id="fJenjang">
                            <?php foreach ($listTag as $t): if ($t['kategori_tag'] === 'Jenjang'): ?>
                            <div class="form-check"><input class="form-check-input" type="checkbox" name="jenjang[]" id="j<?= $t['id_tag'] ?>" value="<?= $t['id_tag'] ?>"><label class="form-check-label" for="j<?= $t['id_tag'] ?>"><?= htmlspecialchars($t['nama_tag']) ?></label></div>
                            <?php endif; endforeach; ?>
                        </div>
                    </div>

                    <div class="filter-section">
                        <div class="filter-heading" data-bs-toggle="collapse" data-bs-target="#fPendanaan">Tipe Pendanaan Beasiswa <i class="bi bi-chevron-up"></i></div>
                        <div class="collapse show filter-options" id="fPendanaan">
                            <?php foreach ($listTag as $t): if ($t['kategori_tag'] === 'Tipe Pendanaan'): ?>
                            <div class="form-check"><input class="form-check-input" type="checkbox" name="pendanaan[]" id="p<?= $t['id_tag'] ?>" value="<?= $t['id_tag'] ?>"><label class="form-check-label" for="p<?= $t['id_tag'] ?>"><?= htmlspecialchars($t['nama_tag']) ?></label></div>
                            <?php endif; endforeach; ?>
                        </div>
                    </div>

                    <div class="filter-section">
                        <div class="filter-heading" data-bs-toggle="collapse" data-bs-target="#fTipe">Tipe Beasiswa <i class="bi bi-chevron-up"></i></div>
                        <div class="collapse show filter-options" id="fTipe">
                            <?php foreach ($listTag as $t): if ($t['kategori_tag'] === 'Tipe Beasiswa'): ?>
                            <div class="form-check"><input class="form-check-input" type="checkbox" name="tipe[]" id="t<?= $t['id_tag'] ?>" value="<?= $t['id_tag'] ?>"><label class="form-check-label" for="t<?= $t['id_tag'] ?>"><?= htmlspecialchars($t['nama_tag']) ?></label></div>
                            <?php endif; endforeach; ?>
                        </div>
                    </div>

                    <div class="filter-section">
                        <div class="filter-heading" data-bs-toggle="collapse" data-bs-target="#fTambahan">Filter Tambahan <i class="bi bi-chevron-up"></i></div>
                        <div class="collapse show filter-options" id="fTambahan">
                            <?php foreach ($listTag as $t): if (in_array($t['kategori_tag'], ['Prestasi', 'SKTM'])): ?>
                            <div class="form-check"><input class="form-check-input" type="checkbox" name="tambahan[]" id="x<?= $t['id_tag'] ?>" value="<?= $t['id_tag'] ?>"><label class="form-check-label" for="x<?= $t['id_tag'] ?>"><?= htmlspecialchars($t['nama_tag']) ?></label></div>
                            <?php endif; endforeach; ?>
                        </div>
                    </div>

                    <div class="filter-section">
                        <div class="filter-heading" data-bs-toggle="collapse" data-bs-target="#fIpk">IPK Minimum <i class="bi bi-chevron-up"></i></div>
                        <div class="collapse show filter-options" id="fIpk">
                            <div class="filter-input">
                                <label for="filter-ipk">Tampilkan beasiswa dengan IPK min ≤</label>
                                <input type="number" class="form-control" id="filter-ipk" placeholder="Contoh: 3.00" min="0" max="4" step="0.01">
                            </div>
                        </div>
                    </div>

                    <div class="filter-section">
                        <div class="filter-heading" data-bs-toggle="collapse" data-bs-target="#fSemester">Semester <i class="bi bi-chevron-up"></i></div>
                        <div class="collapse show filter-options" id="fSemester">
                            <div class="filter-input">
                                <label for="filter-semester">Tampilkan beasiswa untuk semester</label>
                                <select class="form-select" id="filter-semester">
                                    <option value="">Semua Semester</option>
                                    <option value="1">Semester 1</option>
                                    <option value="2">Semester 2</option>
                                    <option value="3">Semester 3</option>
                                    <option value="4">Semester 4</option>
                                    <option value="5">Semester 5</option>
                                    <option value="6">Semester 6</option>
                                    <option value="7">Semester 7</option>
                                    <option value="8">Semester 8</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Beasiswa Cards -->
            <div class="col-lg-9">
                <!-- Search -->
                <div class="search-bar mb-3">
                    <input type="text" placeholder="Cari Beasiswa" id="search-beasiswa">
                    <button type="button"><i class="bi bi-search"></i></button>
                </div>

                <!-- Year & Month -->
                <div class="year-nav">
                    <span class="year-label" id="year-label"><?= date('Y') ?></span>
                    <button id="btn-prev-year"><i class="bi bi-chevron-left"></i></button>
                    <button id="btn-next-year"><i class="bi bi-chevron-right"></i></button>
                </div>
                <div class="month-bar" id="month-bar">
                    <button class="month-btn" data-month="1">JAN</button><button class="month-btn" data-month="2">FEB</button>
                    <button class="month-btn" data-month="3">MAR</button><button class="month-btn" data-month="4">APR</button>
                    <button class="month-btn" data-month="5">MEI</button><button class="month-btn" data-month="6">JUN</button>
                    <button class="month-btn" data-month="7">JUL</button><button class="month-btn" data-month="8">AGU</button>
                    <button class="month-btn" data-month="9">SEP</button><button class="month-btn" data-month="10">OKT</button>
                    <button class="month-btn" data-month="11">NOV</button><button class="month-btn" data-month="12">DES</button>
                </div>

                <div class="row g-3" id="beasiswa-list">
                    <?php if (!empty($listBeasiswa)): ?>
                        <?php foreach ($listBeasiswa as $b): ?>
                        <div class="col-md-6">
                            <div class="card-beasiswa">
                                <?php $poster = !empty($b['poster_url']) ? BASE_URL . '/uploads/poster/' . $b['poster_url'] : BASE_URL . '/assets/img/poster-beasiswa.png'; ?>
                                <img src="<?= htmlspecialchars($poster) ?>" class="poster" alt="Poster Beasiswa">
                                <div class="card-body">
                                    <h5 class="beasiswa-title"><?= htmlspecialchars($b['nama_beasiswa']) ?></h5>
                                    <p class="beasiswa-penyelenggara"><i class="bi bi-building me-1"></i><?= htmlspecialchars($b['nama_penyelenggara'] ?? 'Penyelenggara') ?></p>
                                    <p class="beasiswa-desc"><?= htmlspecialchars($b['deskripsi_singkat']) ?></p>
                                    <div>
                                        <?php if (!empty($b['tag_names'])): ?>
                                            <?php foreach (explode(',', $b['tag_names']) as $tag): ?>
                                                <span class="tag-badge tag-jenjang"><?= htmlspecialchars(trim($tag)) ?></span>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </div>
                                    <div class="date-row"><span>Mulai<br><strong><?= date('d M Y', strtotime($b['tgl_buka'] ?? 'now')) ?></strong></span><span class="text-end">Deadline<br><strong><?= date('d M Y', strtotime($b['tgl_tutup'] ?? 'now')) ?></strong></span></div>
                                    <?php 
                                        $statusClass = 'status-dibuka';
                                        $statusLabel = 'Pendaftaran Dibuka';
                                        if (strtolower($b['status_pendaftaran']) === 'belum dibuka') {
                                            $statusClass = 'status-belum';
                                            $statusLabel = 'Pendaftaran Belum Dibuka';
                                        } elseif (strtolower($b['status_pendaftaran']) === 'ditutup') {
                                            $statusClass = 'status-ditutup';
                                            $statusLabel = 'Pendaftaran Ditutup';
                                        }
                                    ?>
                                    <div class="status-badge <?= $statusClass ?>"><?= htmlspecialchars($statusLabel) ?></div>
                                    <a href="<?= BASE_URL ?>/frontend/mahasiswa/detail-beasiswa.php?id=<?= $b['id_beasiswa'] ?>" class="btn-see">See Program Beasiswa</a>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-12 text-center py-5">
                            <p class="text-muted">Belum ada beasiswa yang tersedia saat ini.</p>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Pagination -->
                <nav class="mt-4 d-flex justify-content-center">
                    <ul class="pagination" id="pagination-container">
                        <?php if (isset($totalPages) && $totalPages > 0): ?>
                            <li class="page-item disabled"><a class="page-link" href="#" data-page="prev"><i class="bi bi-chevron-left"></i></a></li>
                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <li class="page-item <?= $i === 1 ? 'active' : '' ?>"><a class="page-link" href="#" data-page="<?= $i ?>"><?= $i ?></a></li>
                            <?php endfor; ?>
                            <li class="page-item <?= $totalPages <= 1 ? 'disabled' : '' ?>"><a class="page-link" href="#" data-page="next"><i class="bi bi-chevron-right"></i></a></li>
                        <?php endif; ?>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</section>
<!-- ========== END SECTION ========== -->
