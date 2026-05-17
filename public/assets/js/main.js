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
    var filterIpk = document.getElementById('filter-ipk');
    var filterSemester = document.getElementById('filter-semester');

    var now = new Date();
    var currentYear = now.getFullYear();
    var currentMonth = now.getMonth() + 1; // 1-12
    var currentPage = 1;
    var searchTimer = null;

    // Set initial year label to current year
    if (yearLabel) {
        yearLabel.textContent = currentYear;
    }

    // Set current month button as active
    monthBtns.forEach(function (btn) {
        if (parseInt(btn.getAttribute('data-month')) === currentMonth) {
            btn.classList.add('active');
        }
    });

    function fetchBeasiswa() {
        var q = searchInput ? searchInput.value.trim() : '';
        var tags = Array.from(checkboxes).filter(cb => cb.checked).map(cb => cb.value);

        var url = new URL(window.location.origin + window.BASE_URL + '/api/beasiswa/search.php');
        url.searchParams.append('q', q);
        url.searchParams.append('year', currentYear);
        if (currentMonth > 0) {
            url.searchParams.append('month', currentMonth);
        }
        url.searchParams.append('page', currentPage);
        tags.forEach(tag => url.searchParams.append('tags[]', tag));

        // IPK and semester filters
        if (filterIpk && filterIpk.value) {
            url.searchParams.append('ipk', filterIpk.value);
        }
        if (filterSemester && filterSemester.value) {
            url.searchParams.append('semester', filterSemester.value);
        }

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
            var poster = b.poster_url ? window.BASE_URL + '/uploads/poster/' + b.poster_url : window.BASE_URL + '/assets/img/poster-beasiswa.png';
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
                        <a href="${window.BASE_URL}${window.ROLE_DETAIL_PATH || '/frontend/mahasiswa/detail-beasiswa.php'}?id=${b.id_beasiswa}" class="btn-see">See Program Beasiswa</a>
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
        return text.replace(/[&<>"']/g, function (m) { return map[m]; });
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
                return; // Tidak bisa diklik lagi (wajib memilih satu bulan)
            }
            monthBtns.forEach(function (b) { b.classList.remove('active'); });
            btn.classList.add('active');
            currentMonth = parseInt(btn.getAttribute('data-month'));

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

    // IPK filter with debounce
    if (filterIpk) {
        var ipkTimer = null;
        filterIpk.addEventListener('input', function () {
            clearTimeout(ipkTimer);
            ipkTimer = setTimeout(function () {
                currentPage = 1;
                fetchBeasiswa();
            }, 600);
        });
    }

    // Semester filter
    if (filterSemester) {
        filterSemester.addEventListener('change', function () {
            currentPage = 1;
            fetchBeasiswa();
        });
    }

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
// Simulasi — File upload preview with actual filenames
// ============================================================
(function () {
    var fileInput = document.getElementById('file-upload');
    var previewContainer = document.getElementById('file-preview-container');
    if (!fileInput || !previewContainer) return;

    // Track selected files using DataTransfer
    var selectedFiles = new DataTransfer();

    fileInput.addEventListener('change', function () {
        // Add new files to the accumulated list
        for (var i = 0; i < this.files.length; i++) {
            selectedFiles.items.add(this.files[i]);
        }
        // Sync the input's files with our accumulated list
        fileInput.files = selectedFiles.files;
        renderFilePreview();
    });

    function renderFilePreview() {
        previewContainer.innerHTML = '';

        if (selectedFiles.files.length === 0) {
            previewContainer.classList.add('d-none');
            return;
        }

        previewContainer.classList.remove('d-none');

        for (var i = 0; i < selectedFiles.files.length; i++) {
            (function (index) {
                var file = selectedFiles.files[index];
                var sizeKB = (file.size / 1024).toFixed(1);

                var row = document.createElement('div');
                row.className = 'alert alert-secondary py-2 px-3 mb-2 d-flex justify-content-between align-items-center';
                row.innerHTML = '<span class="small"><i class="bi bi-file-earmark-pdf me-2 text-danger"></i>' +
                    escapeHtmlSimulasi(file.name) + ' <span class="text-muted">(' + sizeKB + ' KB)</span></span>';

                var btnClose = document.createElement('button');
                btnClose.type = 'button';
                btnClose.className = 'btn-close';
                btnClose.style.fontSize = '.65rem';
                btnClose.addEventListener('click', function () {
                    // Remove file at index
                    var newDt = new DataTransfer();
                    for (var j = 0; j < selectedFiles.files.length; j++) {
                        if (j !== index) {
                            newDt.items.add(selectedFiles.files[j]);
                        }
                    }
                    selectedFiles = newDt;
                    fileInput.files = selectedFiles.files;
                    renderFilePreview();
                });

                row.appendChild(btnClose);
                previewContainer.appendChild(row);
            })(i);
        }
    }

    function escapeHtmlSimulasi(text) {
        if (!text) return '';
        var map = { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' };
        return text.replace(/[&<>"']/g, function (m) { return map[m]; });
    }
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

// ============================================================
// Intersection Observer for Scroll Animations
// ============================================================
(function () {
    var observerOptions = {
        root: null,
        rootMargin: '0px 0px -50px 0px',
        threshold: 0.15
    };

    var observer = new IntersectionObserver(function (entries, observer) {
        entries.forEach(function (entry) {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-show');
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    var animSelectors = [
        'h2.text-center',
        '.type-card',
        '.alur-step',
        '.pustaka-card',
        '.accordion-item',
        '#tentang-kami .col-lg-6 > h2',
        '#tentang-kami .col-lg-6 > h5',
        '#tentang-kami .col-lg-6 > p',
        '#tentang-kami .col-lg-6 > div',
        '#tentang-kami img.img-fluid'
    ];

    var elements = document.querySelectorAll(animSelectors.join(', '));
    elements.forEach(function (el, index) {
        if (el.classList.contains('pustaka-card') || el.classList.contains('accordion-item')) {
            var parentContainer = el.closest('.row') || el.closest('.accordion');
            var siblingSelector = el.classList.contains('pustaka-card') ? '.pustaka-card' : '.accordion-item';
            var siblingIndex = parentContainer ? Array.from(parentContainer.querySelectorAll(siblingSelector)).indexOf(el) : index;
            if (siblingIndex % 2 === 0) {
                el.classList.add('animate-from-left');
            } else {
                el.classList.add('animate-from-right');
            }
        } else {
            el.classList.add('animate-on-scroll');
        }

        // Add staggered delays for grids
        var parentGrid = el.closest('.row');
        if (parentGrid) {
            var siblings = Array.from(parentGrid.querySelectorAll(animSelectors.join(', ')));
            var siblingIndex = siblings.indexOf(el);
            if (siblingIndex > 0) {
                var delay = (siblingIndex % 4) * 100;
                if (delay > 0) el.classList.add('delay-' + delay);
            }
        }

        observer.observe(el);
    });
})();

// ============================================================
// Smooth Scrolling for Anchor Links
// ============================================================
(function () {
    document.querySelectorAll('a[href^="#"]').forEach(function (anchor) {
        anchor.addEventListener('click', function (e) {
            var href = this.getAttribute('href');
            if (href.length > 1) {
                var target = document.querySelector(href);
                if (target) {
                    e.preventDefault();
                    var offset = 80; // height of navbar
                    var targetPosition = target.getBoundingClientRect().top + window.scrollY - offset;
                    window.scrollTo({
                        top: targetPosition,
                        behavior: 'smooth'
                    });
                }
            }
        });
    });
})();


// ============================================================
// Back to Top Button
// ============================================================
(function () {
    var btn = document.createElement('button');
    btn.innerHTML = '<i class="bi bi-arrow-up"></i>';
    btn.className = 'btn-back-to-top';
    btn.setAttribute('aria-label', 'Kembali ke atas');
    document.body.appendChild(btn);

    window.addEventListener('scroll', function () {
        if (window.scrollY > 300) {
            btn.classList.add('show');
        } else {
            btn.classList.remove('show');
        }
    });

    btn.addEventListener('click', function () {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
})();
