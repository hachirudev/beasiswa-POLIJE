document.addEventListener('DOMContentLoaded', () => {
    const hamburger = document.querySelector('.hamburger');
    const navMenu = document.querySelector('.nav-menu');

    // Menambahkan event listener untuk tombol hamburger pada tampilan mobile
    hamburger.addEventListener('click', () => {
        navMenu.classList.toggle('active');

        // Animasi icon hamburger berubah menjadi tanda X (opsional, jika ingin menambahkan)
        const icon = hamburger.querySelector('i');
        if (navMenu.classList.contains('active')) {
            icon.classList.remove('fa-bars');
            icon.classList.add('fa-xmark');
        } else {
            icon.classList.remove('fa-xmark');
            icon.classList.add('fa-bars');
        }
    });

    // Menutup menu mobile saat link diklik
    const navLinks = document.querySelectorAll('.nav-link');
    navLinks.forEach(link => {
        link.addEventListener('click', () => {
            if (navMenu.classList.contains('active')) {
                navMenu.classList.remove('active');
                const icon = hamburger.querySelector('i');
                icon.classList.remove('fa-xmark');
                icon.classList.add('fa-bars');
            }
        });
    });

    // Fitur interaktif untuk pemilih bulan (Month Selector)
    const monthButtons = document.querySelectorAll('.months button');
    monthButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            // Hapus class active dari semua tombol
            monthButtons.forEach(b => b.classList.remove('active'));
            // Tambahkan class active ke tombol yang diklik
            btn.classList.add('active');
        });
    });

    // Animasi sederhana untuk halaman detail (Fade In Card)
    const detailCard = document.querySelector('.detail-card');
    if (detailCard) {
        detailCard.style.opacity = '0';
        detailCard.style.transform = 'translateY(20px)';
        detailCard.style.transition = 'opacity 0.6s ease-out, transform 0.6s ease-out';

        setTimeout(() => {
            detailCard.style.opacity = '1';
            detailCard.style.transform = 'translateY(0)';
        }, 100);
    }

    // Fitur interaktif untuk Pagination
    const pageButtons = document.querySelectorAll('.page-btn');
    const scholarshipGrid = document.querySelector('.scholarship-grid');

    pageButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            // Hapus class active dari semua tombol pagination
            pageButtons.forEach(b => b.classList.remove('active'));
            // Tambahkan class active ke tombol yang diklik
            btn.classList.add('active');

            // Simulasi pergantian data (animasi fade out & fade in)
            if (scholarshipGrid) {
                scholarshipGrid.style.opacity = '0';
                scholarshipGrid.style.transform = 'translateY(10px)';
                scholarshipGrid.style.transition = 'opacity 0.3s ease-out, transform 0.3s ease-out';

                setTimeout(() => {
                    // Di sini Anda bisa menambahkan logika fetch data baru jika ada backend
                    // Untuk sekarang kita hanya memberi efek animasi saja
                    scholarshipGrid.style.opacity = '1';
                    scholarshipGrid.style.transform = 'translateY(0)';
                }, 300);
            }
        });
    });
});

// FAQ Accordion
const faqItems = document.querySelectorAll('.faq-item');

faqItems.forEach(item => {
    const questionBtn = item.querySelector('.faq-question');
    if (questionBtn) {
        questionBtn.addEventListener('click', () => {
            const isActive = item.classList.contains('active');

            // Jika ingin membuat sistem akordeon di mana hanya 1 yang terbuka, aktifkan kode di bawah
            /*
            faqItems.forEach(otherItem => {
                otherItem.classList.remove('active');
                const icon = otherItem.querySelector('.faq-icon i');
                const arrow = otherItem.querySelector('.faq-arrow i');
                if (icon) icon.classList.replace('fa-minus', 'fa-plus');
                if (arrow) arrow.classList.replace('fa-chevron-down', 'fa-chevron-right');
            });
            */

            const icon = item.querySelector('.faq-icon i');
            const arrow = item.querySelector('.faq-arrow i');

            if (isActive) {
                item.classList.remove('active');
                if (icon) icon.classList.replace('fa-minus', 'fa-plus');
                if (arrow) arrow.classList.replace('fa-chevron-down', 'fa-chevron-right');
            } else {
                item.classList.add('active');
                if (icon) icon.classList.replace('fa-plus', 'fa-minus');
                if (arrow) arrow.classList.replace('fa-chevron-right', 'fa-chevron-down');
            }
        });
    }
});
