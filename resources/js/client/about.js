import '../bootstrap'; // Giữ lại nếu cần axios/lodash từ bootstrap.js

document.addEventListener('DOMContentLoaded', () => {
    console.log('About Logic Loaded');

    // ==========================================
    // 1. SCROLL ANIMATION (Hiệu ứng trượt lên khi cuộn)
    // ==========================================
    const fadeElements = document.querySelectorAll('.fade-up');

    // Chỉ thực hiện nếu có phần tử cần animate
    if (fadeElements.length > 0) {
        const observerOptions = {
            root: null,
            rootMargin: '0px',
            threshold: 0.1 // Kích hoạt khi thấy 10% phần tử
        };

        const observer = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    observer.unobserve(entry.target); // Chỉ chạy 1 lần
                }
            });
        }, observerOptions);

        fadeElements.forEach(el => observer.observe(el));
    }

    // ==========================================
    // 2. COUNTER UP (Hiệu ứng đếm số)
    // ==========================================
    const statNumbers = document.querySelectorAll('.stat-number');
    let hasCounted = false;

    const statsSection = document.querySelector('.section-stats');

    if (statsSection && statNumbers.length > 0) {
        const statsObserver = new IntersectionObserver((entries) => {
            if (entries[0].isIntersecting && !hasCounted) {
                startCounting();
                hasCounted = true;
                // Có thể unobserve để tiết kiệm tài nguyên
                // statsObserver.unobserve(statsSection);
            }
        }, { threshold: 0.5 });

        statsObserver.observe(statsSection);
    }

    function startCounting() {
        statNumbers.forEach(stat => {
            const target = +stat.getAttribute('data-target');
            const duration = 2000; // 2 giây
            const increment = target / (duration / 20); // Update mỗi 20ms

            let current = 0;
            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    stat.textContent = target;
                    clearInterval(timer);
                } else {
                    stat.textContent = Math.ceil(current);
                }
            }, 20);
        });
    }
});