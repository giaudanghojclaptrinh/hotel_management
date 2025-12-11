document.addEventListener('DOMContentLoaded', () => {
    console.log('Profile Logic Loaded');

    // ==========================================
    // 1. PREVIEW ẢNH ĐẠI DIỆN (Nếu có chức năng upload)
    // ==========================================
    const avatarInput = document.getElementById('avatar-upload');
    const avatarPreview = document.getElementById('avatar-preview');

    if (avatarInput && avatarPreview) {
        avatarInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    avatarPreview.src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        });
    }

    // ==========================================
    // 2. CONFIRM LOGOUT
    // ==========================================
    const logoutForms = document.querySelectorAll('form[action*="logout"]');
    logoutForms.forEach(form => {
        form.addEventListener('submit', (e) => {
            if (!confirm('Bạn có chắc chắn muốn đăng xuất?')) {
                e.preventDefault();
            }
        });
    });
});