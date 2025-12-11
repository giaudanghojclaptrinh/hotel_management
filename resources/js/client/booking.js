document.addEventListener('DOMContentLoaded', () => {
    console.log('Booking Logic Loaded');

    // Kiểm tra Axios
    if (typeof axios === 'undefined') {
        console.warn('Axios is not loaded. AJAX requests may fail.');
    }

    // ==========================================
    // 1. XỬ LÝ MÃ KHUYẾN MÃI (PROMO CODE) - Giữ nguyên logic cũ
    // ==========================================
    const promoInput = document.getElementById('promotion-code-input');
    const applyBtn = document.getElementById('apply-promo-btn');
    const promoMessage = document.getElementById('promo-message');
    const originalTotalSpan = document.getElementById('original-total');
    const discountDisplay = document.getElementById('discount-display');
    const finalTotalDisplay = document.getElementById('final-total-display');
    const promoCodeDisplay = document.getElementById('promo-code-display');
    const discountAmountInput = document.getElementById('discount-amount-input');
    const promoCodeHidden = document.getElementById('promotion-code-hidden');

    // Helper function để lấy Alpine data an toàn
    const getAlpineData = () => {
        const alpineEl = document.querySelector('[x-data]');
        return (alpineEl && alpineEl.__x) ? alpineEl.__x.$data : null;
    };

    if (applyBtn && originalTotalSpan) {
        const originalTotal = parseFloat(originalTotalSpan.dataset.originalPrice || 0);

        const formatCurrency = (amount) => {
            return new Intl.NumberFormat('vi-VN', {
                style: 'currency',
                currency: 'VND',
                minimumFractionDigits: 0
            }).format(amount);
        };

        const updateSummary = (discount, finalTotal, message, code = '—') => {
            if (discountDisplay) discountDisplay.textContent = '- ' + formatCurrency(discount);
            if (finalTotalDisplay) finalTotalDisplay.textContent = formatCurrency(finalTotal);
            if (promoCodeDisplay) promoCodeDisplay.textContent = code;

            if (discountAmountInput) discountAmountInput.value = discount;
            if (promoCodeHidden) promoCodeHidden.value = (code !== '—') ? code : '';

            // Update AlpineJS data
            const alpineData = getAlpineData();
            if (alpineData) {
                alpineData.finalTotalText = formatCurrency(finalTotal);
            }

            if (message && promoMessage) {
                promoMessage.textContent = message;
                promoMessage.className = `promo-msg ${discount > 0 ? 'success' : 'error'}`;
            } else if (promoMessage) {
                promoMessage.textContent = '';
            }
        };

        applyBtn.addEventListener('click', async() => {
            const code = promoInput.value.trim().toUpperCase();

            if (!code) {
                updateSummary(0, originalTotal, 'Vui lòng nhập mã khuyến mãi.', '—');
                return;
            }

            applyBtn.disabled = true;
            applyBtn.textContent = '...';
            if (promoMessage) {
                promoMessage.textContent = 'Đang kiểm tra...';
                promoMessage.className = 'promo-msg';
            }

            try {
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

        promoInput.addEventListener('input', () => {
            if (promoMessage && promoMessage.classList.contains('error')) {
                promoMessage.textContent = '';
            }
        });
    }

    // ==========================================
    // 2. XỬ LÝ SUBMIT FORM CHÍNH (Chọn phương thức)
    // ==========================================
    const mainSubmitBtn = document.getElementById('btn-main-submit');

    if (mainSubmitBtn) {
        mainSubmitBtn.addEventListener('click', function(e) {
            // Lấy radio button đang được chọn
            const paymentMethodRadio = document.querySelector('input[name="payment_method_radio"]:checked');

            let isOnline = false;

            if (paymentMethodRadio) {
                isOnline = (paymentMethodRadio.value === 'online');
            } else {
                // Fallback: Check state Alpine nếu radio chưa tìm thấy trong DOM
                const alpineData = getAlpineData();
                if (alpineData) {
                    isOnline = alpineData.onlinePaymentSelected;
                }
            }

            if (isOnline) {
                // Nếu chọn Online: Chặn submit form gốc, mở Modal VNPay
                e.preventDefault();

                // Dispatch sự kiện custom để mở modal (tương tác tốt hơn với Alpine)
                window.dispatchEvent(new CustomEvent('open-vnpay-modal'));
            } else {
                // Nếu chọn Pay at Hotel: Để form tự submit (nút type="submit")
                // Không cần làm gì thêm
            }
        });
    }

    // ==========================================
    // 3. XỬ LÝ NÚT XÁC NHẬN THANH TOÁN QR (Trong Modal)
    // ==========================================
    // Phần này chủ yếu để đảm bảo form submit đúng route khi nhấn nút trong modal
    // Mặc định HTML form đã có action đúng, nút submit sẽ tự động gửi đi.
    // Logic confirm() đã được xử lý inline trong blade onclick="return confirm(...)"

    const vnpayForm = document.getElementById('vnpay-form');
    // Không cần can thiệp thêm nếu form HTML đã chuẩn
});