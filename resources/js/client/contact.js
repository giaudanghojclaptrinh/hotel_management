import '../bootstrap';

document.addEventListener('DOMContentLoaded', function() {
    console.log('Contact Logic Loaded');

    const contactForm = document.getElementById('contact-form');
    const submitBtn = document.getElementById('contact-submit');

    if (contactForm && submitBtn) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();

            // 1. Lấy dữ liệu
            const name = document.getElementById('contact-name').value.trim();
            const email = document.getElementById('contact-email').value.trim();
            const message = document.getElementById('contact-message').value.trim();

            // 2. Validate đơn giản
            if (!name || !email || !message) {
                alert('Vui lòng điền đầy đủ thông tin: Họ tên, Email và Nội dung.');
                return;
            }

            // 3. Hiệu ứng Loading
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i> Đang gửi...';

            // 4. Gửi request đến backend (POST) với CSRF token
            const formData = new FormData(contactForm);
            const csrfMeta = document.querySelector('meta[name="csrf-token"]');
            const csrf = csrfMeta ? csrfMeta.getAttribute('content') : (formData.get('_token') || '');

            fetch(contactForm.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrf,
                    'Accept': 'application/json'
                },
                body: formData,
            }).then(async(res) => {
                if (res.ok) {
                    // Try to parse json message
                    let msg = 'Cảm ơn bạn đã liên hệ! Chúng tôi sẽ phản hồi sớm nhất.';
                    try { const j = await res.json(); if (j.message) msg = j.message; } catch (e) {}
                    contactForm.reset();
                    alert(msg);
                } else if (res.status === 422) {
                    // validation errors
                    const j = await res.json();
                    const errs = j.errors ? Object.values(j.errors).flat().join('\n') : 'Có lỗi xác thực.';
                    alert(errs);
                } else {
                    alert('Lỗi khi gửi phản hồi. Vui lòng thử lại sau.');
                }
            }).catch((err) => {
                console.error('Contact submit error', err);
                alert('Lỗi khi gửi phản hồi. Vui lòng thử lại sau.');
            }).finally(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            });
        });
    }
});