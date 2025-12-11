document.addEventListener('DOMContentLoaded', function() {
    const forms = document.querySelectorAll('.login-form-content');

    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            // Ngăn chặn submit nhiều lần
            if (form.getAttribute('data-submitting') === 'true') {
                e.preventDefault();
                return;
            }

            const btn = form.querySelector('.btn-submit');
            if (btn) {
                // Đánh dấu form đang submit
                form.setAttribute('data-submitting', 'true');

                const btnText = btn.querySelector('.btn-text');
                const btnIcon = btn.querySelector('.btn-icon-wrapper');

                // Lưu text gốc để phòng trường hợp lỗi
                const originalText = btnText ? btnText.textContent : '';

                // Hiển thị trạng thái loading
                if (btnText) btnText.textContent = 'ĐANG XỬ LÝ...';
                if (btnIcon) {
                    btnIcon.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i>';
                }

                // Thêm class opacity hoặc disable để người dùng biết
                btn.style.opacity = '0.8';
                btn.style.cursor = 'wait';
            }
        });
    });

    // Fade in effect cho các input (Optional polish)
    const inputs = document.querySelectorAll('.form-input');
    inputs.forEach((input, index) => {
        input.style.opacity = '0';
        input.style.animation = `fadeInUp 0.5s ease forwards ${0.3 + (index * 0.1)}s`;
    });
});