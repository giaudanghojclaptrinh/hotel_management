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

            // 4. Giả lập gửi (hoặc gọi API thật nếu có backend)
            // Ở đây dùng mailto như yêu cầu cũ
            setTimeout(() => {
                const subject = encodeURIComponent(`Liên hệ mới từ ${name}`);
                const body = encodeURIComponent(`Họ tên: ${name}\nEmail: ${email}\n\nNội dung:\n${message}`);

                // Mở trình gửi mail mặc định
                window.location.href = `mailto:booking@luxurystay.com?subject=${subject}&body=${body}`;

                // Reset form
                contactForm.reset();
                alert('Cảm ơn bạn đã liên hệ! Chúng tôi sẽ phản hồi sớm nhất.');

                // Khôi phục nút
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }, 1500);
        });
    }
});