document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('contact-form');
    if (!form) return;

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const btn = document.getElementById('contact-submit');
        btn.disabled = true;
        btn.textContent = 'Đang gửi...';

        // Simple client-side validation
        const name = document.getElementById('contact-name').value.trim();
        const email = document.getElementById('contact-email').value.trim();
        const message = document.getElementById('contact-message').value.trim();

        if (!name || !email || !message) {
            alert('Vui lòng điền đầy đủ thông tin.');
            btn.disabled = false;
            btn.textContent = 'Gửi liên hệ';
            return;
        }

        // For now we'll open mail client as fallback
        const subject = encodeURIComponent('Liên hệ từ trang web');
        const body = encodeURIComponent('Tên: ' + name + '\nEmail: ' + email + '\n\n' + message);
        window.location.href = 'mailto:booking@luxurystay.com?subject=' + subject + '&body=' + body;
        // Re-enable after a short delay in case mail client doesn't open
        setTimeout(() => { btn.disabled = false;
            btn.textContent = 'Gửi liên hệ'; }, 1000);
    });
});