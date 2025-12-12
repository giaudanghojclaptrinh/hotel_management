import '../bootstrap';

document.addEventListener('DOMContentLoaded', () => {
    console.log('Booking Logic Loaded');

    // 0. CẤU HÌNH AXIOS & CSRF TOKEN
    if (typeof axios === 'undefined') {
        console.warn('Axios is not loaded.');
    } else {
        const token = document.querySelector('meta[name="csrf-token"]');
        if (token) {
            axios.defaults.headers.common['X-CSRF-TOKEN'] = token.getAttribute('content');
        } else {
            console.error('CSRF token not found');
        }
    }

    // ==========================================
    // 1. XỬ LÝ MÃ KHUYẾN MÃI (PROMO CODE)
    // ==========================================
    const applyBtn = document.getElementById('apply-promo-btn');
    const promoInput = document.getElementById('promotion-code-input');
    const promoMessage = document.getElementById('promo-message');

    // Các phần tử hiển thị giá
    const originalTotalSpan = document.getElementById('original-total');
    const discountDisplay = document.getElementById('discount-display');
    const finalTotalDisplay = document.getElementById('final-total-display');
    const promoCodeDisplay = document.getElementById('promo-code-display');

    // Inputs ẩn (Form Chính)
    const discountAmountInput = document.getElementById('discount-amount-input');
    const promoCodeHidden = document.getElementById('promotion-code-hidden');

    // Inputs ẩn trong Modal (để cập nhật giá trị khi submit modal)
    const modalDiscountInput = document.querySelector('#vnpay-form input[name="discount_amount"]');
    const modalPromoInput = document.querySelector('#vnpay-form input[name="promotion_code"]');

    // AlpineJS Helper (Để cập nhật số tiền trong Modal QR)
    const getAlpineData = () => {
        const alpineEl = document.querySelector('[x-data]');
        return (alpineEl && alpineEl.__x) ? alpineEl.__x.$data : null;
    };

    if (applyBtn && originalTotalSpan) {
        // Lấy giá trị gốc từ data-attribute
        const rawPrice = originalTotalSpan.dataset.originalPrice || '0';
        const originalTotal = parseFloat(rawPrice);

        const formatCurrency = (amount) => {
            return new Intl.NumberFormat('vi-VN', {
                style: 'currency',
                currency: 'VND',
                minimumFractionDigits: 0
            }).format(amount);
        };

        const updateSummary = (discount, finalTotal, message, code = '—') => {
            // 1. Cập nhật giao diện text
            if (discountDisplay) discountDisplay.textContent = '- ' + formatCurrency(discount);
            if (finalTotalDisplay) finalTotalDisplay.textContent = formatCurrency(finalTotal);
            if (promoCodeDisplay) promoCodeDisplay.textContent = code;

            // 2. Cập nhật Input ẩn (Form Chính)
            if (discountAmountInput) discountAmountInput.value = discount;
            if (promoCodeHidden) promoCodeHidden.value = (code !== '—') ? code : '';

            // 3. Cập nhật Input ẩn (Form Modal) - Quan trọng để thanh toán đúng giá
            if (modalDiscountInput) modalDiscountInput.value = discount;
            if (modalPromoInput) modalPromoInput.value = (code !== '—') ? code : '';

            // 4. Cập nhật Alpine Data (Để hiển thị số tiền trong Modal QR)
            const alpineData = getAlpineData();
            if (alpineData) {
                alpineData.finalTotalText = formatCurrency(finalTotal);
            }

            // 5. Hiển thị thông báo
            if (message && promoMessage) {
                promoMessage.textContent = message;
                promoMessage.className = 'promo-msg text-sm mt-2 font-medium';

                if (discount > 0) {
                    promoMessage.classList.add('text-green-600');
                    promoMessage.style.color = '#10b981';
                } else {
                    promoMessage.classList.add('text-red-600');
                    promoMessage.style.color = '#ef4444';
                }
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

            // Loading state
            applyBtn.disabled = true;
            const originalBtnText = applyBtn.textContent;
            applyBtn.textContent = '...';

            if (promoMessage) {
                promoMessage.textContent = 'Đang kiểm tra...';
                promoMessage.style.color = '#6b7280';
            }

            try {
                // [FIXED] Lấy URL từ data-attribute (đã được Blade truyền)
                const checkUrl = applyBtn.dataset.routeCheckPromo;

                if (!checkUrl) {
                    throw new Error("Không tìm thấy đường dẫn API (data-route-check-promo thiếu).");
                }

                const response = await axios.post(checkUrl, {
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
                if (error.response) {
                    if (error.response.status === 404) {
                        msg = 'Không tìm thấy API (404). Kiểm tra route.';
                    } else if (error.response.status === 419) {
                        msg = 'Phiên làm việc hết hạn. Vui lòng tải lại trang.';
                    } else if (error.response.data && error.response.data.message) {
                        // Lấy thông báo lỗi từ server (Ví dụ: Mã hết hạn, mã đã dùng)
                        msg = error.response.data.message;
                    }
                }

                updateSummary(0, originalTotal, msg, '—');
            } finally {
                applyBtn.disabled = false;
                applyBtn.textContent = originalBtnText;
            }
        });

        // Reset message khi user nhập lại
        if (promoInput) {
            promoInput.addEventListener('input', () => {
                if (promoMessage) {
                    promoMessage.textContent = '';
                }
            });
        }
    }
});

// LƯU Ý: Hàm submitVnPay (logic xử lý nút Xác nhận thanh toán) đã được chuyển
// sang script nội tuyến trong create.blade.php.