import '../bootstrap';

document.addEventListener('DOMContentLoaded', () => {
    console.log('Promo Logic Loaded');

    // ==========================================
    // 1. COPY TO CLIPBOARD
    // ==========================================
    const copyButtons = document.querySelectorAll('.btn-copy');

    copyButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();

            const code = this.getAttribute('data-code');

            if (code) {
                // Copy text
                navigator.clipboard.writeText(code).then(() => {
                    // Feedback UI
                    const originalHTML = this.innerHTML;

                    this.innerHTML = '<i class="fa-solid fa-check"></i> Đã chép';
                    this.classList.add('copied');

                    // Reset sau 2s
                    setTimeout(() => {
                        this.innerHTML = originalHTML;
                        this.classList.remove('copied');
                    }, 2000);

                    // (Optional) Toast message nếu muốn
                    // alert('Đã sao chép mã: ' + code);
                }).catch(err => {
                    console.error('Không thể sao chép: ', err);
                });
            }
        });
    });

    // ==========================================
    // 2. SCROLL ANIMATION (Tái sử dụng nếu cần)
    // ==========================================
    const fadeElements = document.querySelectorAll('.voucher-card');

    if (fadeElements.length > 0) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });

        fadeElements.forEach((el, index) => {
            // Set init style
            el.style.opacity = '0';
            el.style.transform = 'translateY(30px)';
            el.style.transition = `all 0.6s ease ${index * 0.1}s`; // Stagger effect

            observer.observe(el);
        });
    }
});