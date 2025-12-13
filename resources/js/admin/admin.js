// Import thư viện (Đảm bảo bạn đã chạy 'npm install alpinejs @alpinejs/collapse axios')
import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';
import axios from 'axios';

// 1. Cấu hình Alpine
window.Alpine = Alpine;
Alpine.plugin(collapse);
Alpine.start();

// 2. Cấu hình Axios & AJAX Helper
window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Lấy CSRF Token từ thẻ meta
const token = document.head.querySelector('meta[name="csrf-token"]');
if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

// 3. Logic xử lý AJAX chung (Xóa, Confirm...)
document.addEventListener('DOMContentLoaded', () => {
    // Xử lý confirm cho nút xóa/tác vụ
    document.querySelectorAll('[data-confirm]').forEach(el => {
        el.addEventListener('click', e => {
            if (!confirm(el.getAttribute('data-confirm'))) {
                e.preventDefault();
                e.stopImmediatePropagation();
            }
        });
    });
});