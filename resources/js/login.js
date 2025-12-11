document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('login-form');
    const submitBtn = document.getElementById('btn-login');
    const btnText = submitBtn.querySelector('.btn-text');
    const btnIcon = submitBtn.querySelector('.btn-icon-wrapper');
    const btnLoader = submitBtn.querySelector('.btn-loader');

    // Xử lý hiệu ứng khi Submit Form
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            // Lưu ý: Không dùng e.preventDefault() vì đây là submit form thường lên server Laravel
            // Chúng ta chỉ thêm hiệu ứng visual trước khi trình duyệt chuyển trang

            // 1. Vô hiệu hóa nút để tránh double click
            submitBtn.disabled = true;
            submitBtn.style.cursor = 'not-allowed';
            submitBtn.style.opacity = '0.8';

            // 2. Ẩn text và icon cũ, hiện loader
            btnText.textContent = 'ĐANG XỬ LÝ...';
            if (btnIcon) btnIcon.style.display = 'none';
            if (btnLoader) btnLoader.classList.remove('hidden');
        });
    }

    // Hiệu ứng Visual thêm cho các input (Optional - vì CSS đã xử lý phần lớn)
    const inputs = document.querySelectorAll('.form-input');
    inputs.forEach(input => {
        // Tự động bôi đen nội dung khi click vào input
        input.addEventListener('focus', function() {
            this.select();
        });
    });
});