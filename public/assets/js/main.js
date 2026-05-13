/**
 * Beasiswa POLIJE — Global JavaScript
 * Semua custom JS dari file desain digabung di sini.
 */

// ============================================================
// Navbar scroll shadow (Mahasiswa & Mitra)
// ============================================================
(function () {
    var navbar = document.getElementById('navbar-mahasiswa') || document.getElementById('navbar-mitra');
    if (!navbar) return;

    window.addEventListener('scroll', function () {
        if (window.scrollY > 10) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    });
})();

// ============================================================
// Toggle password visibility (login & register)
// ============================================================
(function () {
    // Single button (login page)
    var btnToggle = document.getElementById('btn-toggle-password');
    var inputPassword = document.getElementById('input-password');

    if (btnToggle && inputPassword) {
        btnToggle.addEventListener('click', function () {
            var isPassword = inputPassword.type === 'password';
            inputPassword.type = isPassword ? 'text' : 'password';
            btnToggle.querySelector('i').className = isPassword ? 'bi bi-eye-slash' : 'bi bi-eye';
        });
    }

    // Multiple buttons (register page)
    document.querySelectorAll('.btn-toggle-password[data-target]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var targetId = btn.getAttribute('data-target');
            var input = document.getElementById(targetId);
            if (!input) return;

            var isPassword = input.type === 'password';
            input.type = isPassword ? 'text' : 'password';
            btn.querySelector('i').className = isPassword ? 'bi bi-eye-slash' : 'bi bi-eye';
        });
    });
})();

// ============================================================
// Role selector (login page)
// ============================================================
(function () {
    var options = document.querySelectorAll('.role-option');
    if (!options.length) return;

    options.forEach(function (option) {
        option.addEventListener('click', function () {
            options.forEach(function (o) { o.classList.remove('active'); });
            option.classList.add('active');
            option.querySelector('input[type="radio"]').checked = true;
        });
    });
})();

// ============================================================
// Client-side password match validation (register page)
// ============================================================
(function () {
    var form = document.getElementById('form-register');
    if (!form) return;

    form.addEventListener('submit', function (e) {
        var password = document.getElementById('input-password').value;
        var confirm = document.getElementById('input-password-confirm').value;

        if (password !== confirm) {
            e.preventDefault();
            alert('Password dan konfirmasi password tidak cocok.');
            document.getElementById('input-password-confirm').focus();
        }
    });
})();

// ============================================================
// Beranda — Beasiswa AJAX Filter & Pagination
// ============================================================
(function () {
    var beasiswaList = document.getElementById('beasiswa-list');
    if (!beasiswaList) return; // Hanya jalankan di halaman yang punya beasiswa-list

    var searchInput = document.getElementById('search-beasiswa');
    var yearLabel = document.getElementById('year-label');
    var btnPrevYear = document.getElementById('btn-prev-year');
    var btnNextYear = document.getElementById('btn-next-year');
    var monthBtns = document.querySelectorAll('.month-btn');
    var checkboxes = document.querySelectorAll('.filter-sidebar input[type="checkbox"]');
    var paginationContainer = document.getElementById('pagination-container');

    var currentYear = parseInt(yearLabel.textContent) || new Date().getFullYear();
    var currentMonth = 0;
    var currentPage = 1;
    var searchTimer = null;

    function fetchBeasiswa() {
        var q = searchInput ? searchInput.value.trim() : '';
        var tags = Array.from(checkboxes).filter(cb => cb.checked).map(cb => cb.value);

        var url = new URL(window.location.origin + '/beasiswa-polije-finale/public/api/beasiswa/search.php');
        url.searchParams.append('q', q);
        url.searchParams.append('year', currentYear);
        if (currentMonth > 0) {
            url.searchParams.append('month', currentMonth);
        }
        url.searchParams.append('page', currentPage);
        tags.forEach(tag => url.searchParams.append('tags[]', tag));

        // Loading state
        beasiswaList.innerHTML = '<div class="col-12 text-center py-5"><div class="spinner-border text-primary" role="status"></div><p class="mt-2 text-muted">Memuat data...</p></div>';

        fetch(url)
            .then(res => res.json())
            .then(response => {
                if (response.status === 'success') {
                    renderBeasiswa(response.data);
                    renderPagination(response.pagination);
                } else {
                    beasiswaList.innerHTML = '<div class="col-12 text-center py-5"><p class="text-danger">' + response.message + '</p></div>';
                }
            })
            .catch(err => {
                beasiswaList.innerHTML = '<div class="col-12 text-center py-5"><p class="text-danger">Terjadi kesalahan koneksi.</p></div>';
                console.error(err);
            });
    }

    function renderBeasiswa(data) {
        if (!data || data.length === 0) {
            beasiswaList.innerHTML = '<div class="col-12 text-center py-5"><p class="text-muted">Belum ada beasiswa yang tersedia saat ini.</p></div>';
            return;
        }

        var html = '';
        data.forEach(b => {
            var poster = b.poster_url ? '/beasiswa-polije-finale/public/uploads/poster/' + b.poster_url : '/beasiswa-polije-finale/public/assets/img/poster-beasiswa.png';
            var penyelenggara = b.nama_penyelenggara ? b.nama_penyelenggara : 'Penyelenggara';
            
            var tagsHtml = '';
            if (b.tag_names) {
                b.tag_names.split(',').forEach(tag => {
                    tagsHtml += '<span class="tag-badge tag-jenjang">' + escapeHtml(tag.trim()) + '</span> ';
                });
            }

            var tglBuka = new Date(b.tgl_buka).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });
            var tglTutup = new Date(b.tgl_tutup).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });

            var statusClass = 'status-dibuka';
            var statusLabel = 'Pendaftaran Dibuka';
            var computedStatus = b.status_pendaftaran_computed ? b.status_pendaftaran_computed.toLowerCase() : '';
            if (computedStatus === 'belum_dibuka') {
                statusClass = 'status-belum';
                statusLabel = 'Pendaftaran Belum Dibuka';
            } else if (computedStatus === 'ditutup') {
                statusClass = 'status-ditutup';
                statusLabel = 'Pendaftaran Ditutup';
            }

            html += `
            <div class="col-md-6">
                <div class="card-beasiswa">
                    <img src="${escapeHtml(poster)}" class="poster" alt="Poster Beasiswa">
                    <div class="card-body">
                        <h5 class="beasiswa-title">${escapeHtml(b.nama_beasiswa)}</h5>
                        <p class="beasiswa-penyelenggara"><i class="bi bi-building me-1"></i>${escapeHtml(penyelenggara)}</p>
                        <p class="beasiswa-desc">${escapeHtml(b.deskripsi_singkat)}</p>
                        <div>${tagsHtml}</div>
                        <div class="date-row">
                            <span>Mulai<br><strong>${tglBuka}</strong></span>
                            <span class="text-end">Deadline<br><strong>${tglTutup}</strong></span>
                        </div>
                        <div class="status-badge ${statusClass}">${statusLabel}</div>
                        <a href="/beasiswa-polije-finale/public/frontend/mahasiswa/detail-beasiswa.php?id=${b.id_beasiswa}" class="btn-see">See Program Beasiswa</a>
                    </div>
                </div>
            </div>`;
        });

        beasiswaList.innerHTML = html;
    }

    function renderPagination(p) {
        if (!paginationContainer) return;
        
        if (p.total_pages <= 0) {
            paginationContainer.innerHTML = '';
            return;
        }

        var html = '';
        var prevDisabled = p.current_page === 1 ? 'disabled' : '';
        html += '<li class="page-item ' + prevDisabled + '"><a class="page-link" href="#" data-page="' + (p.current_page - 1) + '"><i class="bi bi-chevron-left"></i></a></li>';

        for (var i = 1; i <= p.total_pages; i++) {
            var activeClass = i === p.current_page ? 'active' : '';
            html += '<li class="page-item ' + activeClass + '"><a class="page-link" href="#" data-page="' + i + '">' + i + '</a></li>';
        }

        var nextDisabled = p.current_page === p.total_pages ? 'disabled' : '';
        html += '<li class="page-item ' + nextDisabled + '"><a class="page-link" href="#" data-page="' + (p.current_page + 1) + '"><i class="bi bi-chevron-right"></i></a></li>';

        paginationContainer.innerHTML = html;
    }

    function escapeHtml(text) {
        if (!text) return '';
        var map = { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' };
        return text.replace(/[&<>"']/g, function(m) { return map[m]; });
    }

    // Event Listeners
    if (searchInput) {
        searchInput.addEventListener('input', function () {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(function () {
                currentPage = 1;
                fetchBeasiswa();
            }, 500);
        });
    }

    if (btnPrevYear && btnNextYear && yearLabel) {
        btnPrevYear.addEventListener('click', function () {
            currentYear--;
            yearLabel.textContent = currentYear;
            currentPage = 1;
            fetchBeasiswa();
        });
        btnNextYear.addEventListener('click', function () {
            currentYear++;
            yearLabel.textContent = currentYear;
            currentPage = 1;
            fetchBeasiswa();
        });
    }

    monthBtns.forEach(function (btn) {
        btn.addEventListener('click', function () {
            if (btn.classList.contains('active')) {
                btn.classList.remove('active');
                currentMonth = 0;
            } else {
                monthBtns.forEach(function (b) { b.classList.remove('active'); });
                btn.classList.add('active');
                currentMonth = parseInt(btn.getAttribute('data-month'));
            }
            currentPage = 1;
            fetchBeasiswa();
        });
    });

    checkboxes.forEach(function (cb) {
        cb.addEventListener('change', function () {
            currentPage = 1;
            fetchBeasiswa();
        });
    });

    if (paginationContainer) {
        paginationContainer.addEventListener('click', function (e) {
            e.preventDefault();
            var target = e.target.closest('a.page-link');
            if (!target) return;

            var li = target.closest('li.page-item');
            if (li && li.classList.contains('disabled')) return;

            var page = parseInt(target.getAttribute('data-page'));
            if (page && page !== currentPage) {
                currentPage = page;
                fetchBeasiswa();
                window.scrollTo({ top: document.getElementById('beranda').offsetTop - 80, behavior: 'smooth' });
            }
        });
    }

})();

// ============================================================
// Simulasi — File upload preview
// ============================================================
(function () {
    var fileInput = document.getElementById('file-upload');
    var previewContainer = document.getElementById('file-preview-container');
    if (!fileInput || !previewContainer) return;

    fileInput.addEventListener('change', function () {
        if (this.files.length > 0) {
            previewContainer.classList.remove('d-none');
        } else {
            previewContainer.classList.add('d-none');
        }
    });
})();

// ============================================================
// Pesan — Remove unread status on click
// ============================================================
(function () {
    document.querySelectorAll('.pesan-card.unread').forEach(function (card) {
        card.addEventListener('click', function () {
            this.classList.remove('unread');
            var badge = this.querySelector('.badge-new');
            if (badge) badge.remove();

            if (document.querySelectorAll('.pesan-card.unread').length === 0) {
                var navBadge = document.querySelector('.notif-badge-nav');
                if (navBadge) navBadge.style.display = 'none';
            }
        });
    });
})();

// ============================================================
// Flash message auto-dismiss (5 detik)
// ============================================================
(function () {
    document.querySelectorAll('.flash-alert').forEach(function (alert) {
        setTimeout(function () {
            var bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
            if (bsAlert) bsAlert.close();
        }, 5000);
    });
})();
