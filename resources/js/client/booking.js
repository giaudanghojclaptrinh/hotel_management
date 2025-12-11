document.addEventListener('DOMContentLoaded', () => {
    console.log('Booking Logic Loaded');

    // Kiểm tra Axios
    if (typeof axios === 'undefined') {
        console.warn('Axios is not loaded. AJAX requests may fail.');
    }

    // ==========================================
    // 1. XỬ LÝ MÃ KHUYẾN MÃI (PROMO CODE)
    // ==========================================
    const promoInput = document.getElementById('promotion-code-input');
    const applyBtn = document.getElementById('apply-promo-btn');
    const promoMessage = document.getElementById('promo-message');

    // Các phần tử hiển thị giá tiền
    const originalTotalSpan = document.getElementById('original-total');
    const discountDisplay = document.getElementById('discount-display');
    const finalTotalDisplay = document.getElementById('final-total-display');
    const promoCodeDisplay = document.getElementById('promo-code-display');

    // Hidden inputs để gửi form
    const discountAmountInput = document.getElementById('discount-amount-input');
    const promoCodeHidden = document.getElementById('promotion-code-hidden');

    if (applyBtn && originalTotalSpan) {
        // Lấy giá gốc từ data-attribute (được render từ server)
        const originalTotal = parseFloat(originalTotalSpan.dataset.originalPrice || 0);

        // Helper: Format tiền tệ VNĐ
        const formatCurrency = (amount) => {
            return new Intl.NumberFormat('vi-VN', {
                style: 'currency',
                currency: 'VND',
                minimumFractionDigits: 0
            }).format(amount);
        };

        // Helper: Cập nhật giao diện sau khi check mã
        const updateSummary = (discount, finalTotal, message, code = '—') => {
            // Update hiển thị
            if (discountDisplay) discountDisplay.textContent = '- ' + formatCurrency(discount);
            if (finalTotalDisplay) finalTotalDisplay.textContent = formatCurrency(finalTotal);
            if (promoCodeDisplay) promoCodeDisplay.textContent = code;

            // Update hidden inputs
            if (discountAmountInput) discountAmountInput.value = discount;
            if (promoCodeHidden) promoCodeHidden.value = (code !== '—') ? code : '';

            // Update thông báo
            if (message && promoMessage) {
                promoMessage.textContent = message;
                promoMessage.className = `text-sm mt-2 font-medium ${discount > 0 ? 'text-green-500' : 'text-red-500'}`;
            } else if (promoMessage) {
                promoMessage.textContent = '';
            }

            // Cập nhật giá tiền cho modal VNPay (thông qua Alpine store hoặc DOM trực tiếp)
            // Cách đơn giản: Tìm element hiển thị giá trong modal và update text
            const modalPrice = document.querySelector('.final-price-modal'); // Cần thêm class này vào view nếu muốn update dynamic
            if (modalPrice) modalPrice.textContent = formatCurrency(finalTotal);

            // Cập nhật AlpineJS data nếu có
            const alpineEl = document.querySelector('[x-data]');
            if (alpineEl && alpineEl.__x) {
                alpineEl.__x.$data.finalTotalText = formatCurrency(finalTotal);
            }
        };

        // Sự kiện click nút Áp dụng
        applyBtn.addEventListener('click', async() => {
            const code = promoInput.value.trim().toUpperCase();

            if (!code) {
                updateSummary(0, originalTotal, 'Vui lòng nhập mã khuyến mãi.', '—');
                return;
            }

            // Loading state
            applyBtn.disabled = true;
            applyBtn.textContent = 'Đang ktra...';
            if (promoMessage) promoMessage.textContent = '';

            try {
                // Gọi API kiểm tra mã (Route cần được định nghĩa trong Laravel api.php hoặc web.php)
                // Lưu ý: Đảm bảo route 'api.check.promo' trả về JSON đúng cấu trúc
                const response = await axios.post('/api/check-promo', {
                    code: code,
                    original_total: originalTotal
                });

                const data = response.data;

                if (data.success) {
                    updateSummary(data.discount_amount, data.final_total, data.message, code);
                } else {
                    updateSummary(0, originalTotal, data.message, '—');
                }
            } catch (error) {
                console.error("Promo Check Error:", error);
                let msg = 'Lỗi hệ thống khi kiểm tra mã.';
                if (error.response && error.response.data && error.response.data.message) {
                    msg = error.response.data.message;
                }
                updateSummary(0, originalTotal, msg, '—');
            } finally {
                applyBtn.disabled = false;
                applyBtn.textContent = 'Áp dụng';
            }
        });

        // Reset khi người dùng thay đổi input mã
        promoInput.addEventListener('input', () => {
            // Nếu input thay đổi, có thể reset trạng thái giảm giá về 0 để tránh hiểu lầm
            // Hoặc giữ nguyên, tùy UX. Ở đây ta reset message lỗi thôi.
            if (promoMessage && promoMessage.classList.contains('text-red-500')) {
                promoMessage.textContent = '';
            }
        });
    }
});