import './bootstrap';

// Log kiểm tra
console.log('Client Assets Loaded via Vite!');

document.addEventListener('DOMContentLoaded', () => {

    // ==========================================
    // 1. XỬ LÝ HEADER TRONG SUỐT -> CÓ NỀN (Sticky Header)
    // ==========================================
    const header = document.querySelector('.header-wrapper');

    if (header) {
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                // Thêm class .is-scrolled (đã định nghĩa trong CSS: nền trắng mờ, bóng đổ)
                header.classList.add('is-scrolled');
            } else {
                // Gỡ class để trở về trạng thái trong suốt
                header.classList.remove('is-scrolled');
            }
        });
    }

    // ==========================================
    // 2. XỬ LÝ MENU MOBILE (Toggle) - Phần còn thiếu
    // ==========================================
    const mobileToggleBtn = document.querySelector('.mobile-toggle');
    const mobileMenu = document.querySelector('.mobile-menu-wrapper');

    if (mobileToggleBtn && mobileMenu) {
        mobileToggleBtn.addEventListener('click', (e) => {
            e.preventDefault();
            // Toggle class 'is-open' để hiện/ẩn menu
            mobileMenu.classList.toggle('is-open');

            // Đổi icon từ 3 gạch sang dấu X (nếu dùng FontAwesome)
            const icon = mobileToggleBtn.querySelector('i');
            if (icon) {
                if (mobileMenu.classList.contains('is-open')) {
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
    // 3. TỰ ĐỘNG ẨN FLASH MESSAGE (Thông báo thành công/lỗi)
    // ==========================================
    const flashMessages = document.querySelectorAll('.flash-success, .flash-error');

    if (flashMessages.length > 0) {
        flashMessages.forEach(msg => {
            // Đợi 5 giây (5000ms) rồi bắt đầu mờ dần
            setTimeout(() => {
                msg.style.transition = "opacity 0.5s ease";
                msg.style.opacity = "0";

                // Sau khi mờ hẳn thì xóa khỏi DOM
                setTimeout(() => msg.remove(), 500);
            }, 5000);

            // Nếu người dùng bấm nút tắt (dấu X) thủ công
            const closeBtn = msg.querySelector('.btn-close-success, .btn-close-error');
            if (closeBtn) {
                closeBtn.addEventListener('click', () => {
                    msg.style.display = 'none';
                });
            }
        });
    }

});