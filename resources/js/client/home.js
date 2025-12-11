import '../bootstrap'; // Giữ lại nếu bạn dùng axios/lodash từ bootstrap.js

document.addEventListener('DOMContentLoaded', () => {
    console.log('Client Core Loaded');

    // ==========================================
    // 1. XỬ LÝ HEADER STICKY (Cuộn trang đổi màu nền)
    // ==========================================
    const header = document.querySelector('.header-wrapper');
    if (header) {
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                header.classList.add('is-scrolled'); // Thêm class CSS nền mờ
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
            // Toggle class 'is-open' để hiện/ẩn menu (CSS cần hỗ trợ class này)
            // Lưu ý: Trong CSS mới ta dùng x-show của Alpine, nhưng nếu dùng thuần JS thì dùng class này
            // Nếu bạn dùng AlpineJS hoàn toàn cho menu, đoạn này có thể bỏ hoặc giữ để fallback
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