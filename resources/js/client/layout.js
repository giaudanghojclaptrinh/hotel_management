// ==========================================
// LAYOUT JS - HEADER, FOOTER, NAVIGATION
// Shared across all pages
// ==========================================

document.addEventListener('DOMContentLoaded', () => {
    console.log('Layout JS Loaded');

    // ==========================================
    // 1. XỬ LÝ HEADER STICKY (Cuộn trang đổi màu nền)
    // ==========================================
    const header = document.querySelector('.header-wrapper');
    if (header) {
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                header.classList.add('is-scrolled');
            } else {
                header.classList.remove('is-scrolled');
            }
        });
    }

    // ==========================================
    // 2. XỬ LÝ MENU MOBILE (Toggle)
    // ==========================================
    const mobileToggleBtn = document.querySelector('.mobile-toggle');
    const mobileMenu = document.querySelector('.mobile-menu-wrapper');

    if (mobileToggleBtn && mobileMenu) {
        mobileToggleBtn.addEventListener('click', (e) => {
            e.preventDefault();
            mobileMenu.classList.toggle('active');

            // Đổi icon hamburger <-> xmark
            const icon = mobileToggleBtn.querySelector('i');
            if (icon) {
                if (mobileMenu.classList.contains('active')) {
                    icon.classList.remove('fa-bars');
                    icon.classList.add('fa-xmark');
                } else {
                    icon.classList.remove('fa-xmark');
                    icon.classList.add('fa-bars');
                }
            }
        });
    }

    // ==========================================
    // 3. TỰ ĐỘNG ẨN FLASH MESSAGE
    // ==========================================
    const flashMessages = document.querySelectorAll('.flash-success, .flash-error');
    if (flashMessages.length > 0) {
        flashMessages.forEach(msg => {
            // Tự động tắt sau 5s
            setTimeout(() => {
                msg.style.transition = "opacity 0.5s ease";
                msg.style.opacity = "0";
                setTimeout(() => msg.remove(), 500);
            }, 5000);

            // Nút tắt thủ công
            const closeBtn = msg.querySelector('.btn-close-success, .btn-close-error');
            if (closeBtn) {
                closeBtn.addEventListener('click', () => {
                    msg.remove();
                });
            }
        });
    }
});