/* ============================================
   BEASISWA POLIJE — Main Application Logic
   ============================================ */

// ---- State ----
let currentYear = 2025;
let currentMonth = 3; // March
let currentPage = 1;
const itemsPerPage = 6;
let filteredBeasiswa = [...beasiswaData];

// ---- Initialize ----
document.addEventListener('DOMContentLoaded', () => {
  initApp();
});

function initApp() {
  // Load user info into sidebar
  loadUserInfo();

  // Render all sections
  renderBeasiswa();
  renderPustaka();
  renderFAQ();

  // Set up scroll spy for navbar
  setupScrollSpy();
}

// ---- User Info / Auth ----
function loadUserInfo() {
  const role = localStorage.getItem('userRole') || 'mahasiswa';
  const name = localStorage.getItem('userName') || usersData[role]?.nama || 'Pengguna';

  const sidebarName = document.getElementById('sidebarName');
  const sidebarRole = document.getElementById('sidebarRole');

  if (sidebarName) sidebarName.textContent = name;
  if (sidebarRole) sidebarRole.textContent = role.charAt(0).toUpperCase() + role.slice(1);
}

function handleLogout() {
  localStorage.removeItem('isLoggedIn');
  localStorage.removeItem('userRole');
  localStorage.removeItem('userEmail');
  localStorage.removeItem('userName');
  window.location.href = 'index.html';
}

function shareWebsite() {
  if (navigator.share) {
    navigator.share({
      title: 'Beasiswa POLIJE',
      text: 'Portal Beasiswa Resmi Politeknik Negeri Jember',
      url: window.location.href
    });
  } else {
    navigator.clipboard.writeText(window.location.href).then(() => {
      alert('Link telah disalin ke clipboard!');
    });
  }
}

// ---- Scroll Spy ----
function setupScrollSpy() {
  const sections = document.querySelectorAll('section[id]');
  const navLinks = document.querySelectorAll('#mainNav .nav-link');

  window.addEventListener('scroll', () => {
    let current = '';
    sections.forEach(section => {
      const sectionTop = section.offsetTop - 100;
      if (scrollY >= sectionTop) {
        current = section.getAttribute('id');
      }
    });

    navLinks.forEach(link => {
      link.classList.remove('active');
      if (link.getAttribute('href') === '#' + current) {
        link.classList.add('active');
      }
    });
  });
}

// ---- Beasiswa Rendering ----
function renderBeasiswa() {
  applyFilters();
}

function applyFilters() {
  const searchQuery = document.getElementById('searchInput')?.value.toLowerCase() || '';
  const jenjangFilter = document.querySelector('input[name="jenjang"]:checked')?.value || '';
  const pendanaanFilter = document.querySelector('input[name="pendanaan"]:checked')?.value || '';
  const tipeFilter = document.querySelector('input[name="tipeBeasiswa"]:checked')?.value || '';
  const prestasiFilter = document.getElementById('filterPrestasi')?.checked || false;
  const pemerintahFilter = document.getElementById('filterPemerintah')?.checked || false;
  const sktmFilter = document.getElementById('filterSKTM')?.checked || false;
  const ipkFilter = parseFloat(document.getElementById('filterIPK')?.value) || 0;
  const semesterFilter = document.getElementById('filterSemester')?.value || '';

  filteredBeasiswa = beasiswaData.filter(b => {
    // Search
    if (searchQuery && !b.judul.toLowerCase().includes(searchQuery)) return false;

    // Jenjang
    if (jenjangFilter && !b.jenjang.includes(jenjangFilter)) return false;

    // Pendanaan
    if (pendanaanFilter && b.tipePendanaan !== pendanaanFilter) return false;

    // Tipe
    if (tipeFilter && b.tipeBeasiswa !== tipeFilter) return false;

    // Prestasi
    if (prestasiFilter && !b.prestasi) return false;

    // Pemerintah
    if (pemerintahFilter && !b.pemerintah) return false;

    // SKTM
    if (sktmFilter && !b.sktm) return false;

    // IPK
    if (ipkFilter > 0 && b.ipkMin > ipkFilter) return false;

    // Semester
    if (semesterFilter && !b.semester.includes(parseInt(semesterFilter))) return false;

    return true;
  });

  currentPage = 1;
  renderBeasiswaGrid();
  renderPagination();
}

function renderBeasiswaGrid() {
  const grid = document.getElementById('beasiswaGrid');
  if (!grid) return;

  const start = (currentPage - 1) * itemsPerPage;
  const end = start + itemsPerPage;
  const pageItems = filteredBeasiswa.slice(start, end);

  if (pageItems.length === 0) {
    grid.innerHTML = `
      <div style="grid-column: 1/-1; text-align: center; padding: 60px 20px;">
        <i class="bi bi-search" style="font-size: 3rem; color: #ccc;"></i>
        <p style="margin-top: 15px; color: #888; font-size: 1.1rem;">Tidak ada beasiswa yang ditemukan</p>
        <p style="color: #aaa; font-size: 0.9rem;">Coba ubah filter atau kata kunci pencarian</p>
      </div>
    `;
    return;
  }

  grid.innerHTML = pageItems.map(b => {
    const statusClass = b.statusPendaftaran === 'Dibuka' ? 'open' : 'closed';
    const statusText = b.statusPendaftaran === 'Dibuka' ? 'Pendaftaran Dibuka' : 'Pendaftaran Ditutup';
    const mulaiFormatted = formatDate(b.mulai);
    const deadlineFormatted = formatDate(b.deadline);

    const tagsHTML = b.tags.map(tag => {
      let tagClass = 'beasiswa-tag';
      if (tag === 'Swasta') tagClass += ' tag-swasta';
      if (tag === 'Pemerintah') tagClass += ' tag-pemerintah';
      return `<span class="${tagClass}">${tag}</span>`;
    }).join('');

    return `
      <div class="beasiswa-card animate-fade-in-up">
        <img src="${b.poster}" alt="${b.judul}" class="beasiswa-poster" onerror="this.src='https://via.placeholder.com/400x260/00BCD4/fff?text=Beasiswa'">
        <div class="beasiswa-card-body">
          <h5>${b.judul}</h5>
          <p class="beasiswa-desc">${b.deskripsiSingkat}</p>
          <div class="beasiswa-tags">${tagsHTML}</div>
          <div class="beasiswa-dates">
            <div class="date-col">
              <strong>Mulai</strong>
              <span>${mulaiFormatted}</span>
            </div>
            <div class="date-col" style="text-align: right;">
              <strong>Deadline</strong>
              <span>${deadlineFormatted}</span>
            </div>
          </div>
          <div class="beasiswa-status ${statusClass}">${statusText}</div>
          <a href="detail-beasiswa.html?id=${b.id}" class="btn-see-program">See Program Beasiswa</a>
        </div>
      </div>
    `;
  }).join('');
}

function renderPagination() {
  const pagination = document.getElementById('pagination');
  if (!pagination) return;

  const totalPages = Math.ceil(filteredBeasiswa.length / itemsPerPage);
  if (totalPages <= 1) {
    pagination.innerHTML = '';
    return;
  }

  let html = '';

  // Prev
  html += `<div class="page-item ${currentPage === 1 ? 'disabled' : ''}" onclick="goToPage(${currentPage - 1})"><i class="bi bi-chevron-left"></i></div>`;

  // Pages
  for (let i = 1; i <= totalPages; i++) {
    if (i <= 3 || i === totalPages || Math.abs(i - currentPage) <= 1) {
      html += `<div class="page-item ${i === currentPage ? 'active' : ''}" onclick="goToPage(${i})">${i}</div>`;
    } else if (i === 4 && totalPages > 5) {
      html += `<span class="page-dots">···</span>`;
    }
  }

  // Next
  html += `<div class="page-item ${currentPage === totalPages ? 'disabled' : ''}" onclick="goToPage(${currentPage + 1})"><i class="bi bi-chevron-right"></i></div>`;

  pagination.innerHTML = html;
}

function goToPage(page) {
  const totalPages = Math.ceil(filteredBeasiswa.length / itemsPerPage);
  if (page < 1 || page > totalPages) return;
  currentPage = page;
  renderBeasiswaGrid();
  renderPagination();
  document.getElementById('beranda').scrollIntoView({ behavior: 'smooth' });
}

// ---- Time Filter ----
function changeYear(delta) {
  currentYear += delta;
  document.getElementById('yearDisplay').textContent = currentYear;
}

function selectMonth(month) {
  currentMonth = month;
  document.querySelectorAll('.month-pill').forEach(pill => {
    pill.classList.toggle('active', parseInt(pill.dataset.month) === month);
  });
}

// ---- Filter Sidebar Toggle ----
function toggleFilterSection(header) {
  const options = header.nextElementSibling;
  const icon = header.querySelector('i');

  if (options.style.display === 'none') {
    options.style.display = 'block';
    icon.classList.replace('bi-chevron-down', 'bi-chevron-up');
  } else {
    options.style.display = 'none';
    icon.classList.replace('bi-chevron-up', 'bi-chevron-down');
  }
}

// ---- Pustaka Rendering ----
function renderPustaka() {
  const grid = document.getElementById('pustakaGrid');
  if (!grid) return;

  grid.innerHTML = pustakaData.map(p => `
    <div class="pustaka-card">
      <div class="pustaka-preview">
        <img src="${p.gambar}" alt="${p.nama}" onerror="this.src='https://via.placeholder.com/200x240/f5f5f5/333?text=Dokumen'">
      </div>
      <div class="pustaka-info">
        <h5>${p.nama}</h5>
        <p>${p.deskripsiSingkat}</p>
        <div class="pustaka-actions">
          <a href="detail-pustaka.html?id=${p.id}" class="btn-see-file">See File</a>
          <a href="${p.file}" class="btn-download-file" download>Download File</a>
        </div>
      </div>
    </div>
  `).join('');
}

// ---- FAQ Rendering ----
function renderFAQ() {
  const list = document.getElementById('faqList');
  if (!list) return;

  list.innerHTML = faqData.map((faq, index) => `
    <div class="faq-item ${index === faqData.length - 1 ? 'active' : ''}" onclick="toggleFAQ(this)">
      <div class="faq-question">
        <span class="faq-icon">${index === faqData.length - 1 ? '−' : '+'}</span>
        <span style="flex:1;">${faq.pertanyaan}</span>
        <i class="bi bi-chevron-right faq-chevron"></i>
      </div>
      <div class="faq-answer">
        ${faq.jawaban}
      </div>
    </div>
  `).join('');
}

function toggleFAQ(item) {
  const isActive = item.classList.contains('active');

  // Close all
  document.querySelectorAll('.faq-item').forEach(fi => {
    fi.classList.remove('active');
    fi.querySelector('.faq-icon').textContent = '+';
  });

  // Toggle current
  if (!isActive) {
    item.classList.add('active');
    item.querySelector('.faq-icon').textContent = '−';
  }
}

// ---- Utility ----
function formatDate(dateStr) {
  const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
    'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
  const date = new Date(dateStr);
  return `${date.getDate()} ${months[date.getMonth()]} ${date.getFullYear()}`;
}

function formatDateTime(dateStr) {
  const date = new Date(dateStr);
  const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
  return `${date.getDate()} ${months[date.getMonth()]} ${date.getFullYear()}, ${String(date.getHours()).padStart(2, '0')}:${String(date.getMinutes()).padStart(2, '0')}`;
}

function getUrlParam(param) {
  const urlParams = new URLSearchParams(window.location.search);
  return urlParams.get(param);
}
