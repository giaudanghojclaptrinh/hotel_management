document.addEventListener('DOMContentLoaded', function() {
    const registerForm = document.getElementById('register-form');
    const submitBtn = document.getElementById('btn-register');
    const btnText = submitBtn.querySelector('.btn-text');
    const btnIcon = submitBtn.querySelector('.btn-icon-wrapper');
    const btnLoader = submitBtn.querySelector('.btn-loader');

    if (registerForm) {
        registerForm.addEventListener('submit', function(e) {
            // Hiệu ứng Visual trước khi gửi form
            submitBtn.disabled = true;
            submitBtn.style.cursor = 'not-allowed';
            submitBtn.style.opacity = '0.8';

            // Đổi text và icon
            btnText.textContent = 'ĐANG ĐĂNG KÝ...';
            if (btnIcon) btnIcon.style.display = 'none';
            if (btnLoader) btnLoader.classList.remove('hidden');
        });
    }

    // Auto-select text on focus
    const inputs = document.querySelectorAll('.form-input');
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.select();
        });
    });
});