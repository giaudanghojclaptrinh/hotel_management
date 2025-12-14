import '../bootstrap';

// booking.js — clean, single implementation for booking-related client pages
document.addEventListener('DOMContentLoaded', () => {
    if (typeof axios !== 'undefined') {
        const token = document.querySelector('meta[name="csrf-token"]');
        if (token) axios.defaults.headers.common['X-CSRF-TOKEN'] = token.getAttribute('content');
        axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
    }

    const form = document.getElementById('booking-form');
    const applyBtn = document.getElementById('apply-promo-btn');
    const promoInput = document.getElementById('promotion-code-input');
    const promoMessage = document.getElementById('promo-message');
    const originalTotalSpan = document.getElementById('original-total');

    const discountDisplay = document.getElementById('discount-display');
    const finalTotalDisplay = document.getElementById('final-total-display');
    const promoCodeDisplay = document.getElementById('promo-code-display');
    const discountAmountInput = document.getElementById('discount-amount-input');
    const promoCodeHidden = document.getElementById('promotion-code-hidden');
    const vatDisplay = document.getElementById('vat-display');

    let originalTotal = 0;
    let vatRate = 0.08; // VAT 8%

    if (originalTotalSpan) {
        const ds = originalTotalSpan.dataset || {};
        originalTotal = parseFloat(ds.originalPrice || ds.originalprice || originalTotalSpan.getAttribute('data-original-price') || 0) || 0;
    }

    if (vatDisplay) {
        const vatRateData = vatDisplay.dataset.vatRate || vatDisplay.getAttribute('data-vat-rate');
        if (vatRateData) {
            vatRate = parseFloat(vatRateData);
        }
    }

    const formatCurrency = (amount) => new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND', minimumFractionDigits: 0 }).format(amount);

    const updateSummary = (discount, finalTotal, message, code = '—') => {
        // Tính subtotal sau giảm giá (trước VAT)
        const subtotal = originalTotal - discount;

        // Tính VAT 8% trên subtotal
        const vatAmount = subtotal * vatRate;

        // Tổng cuối cùng = subtotal + VAT
        const totalWithVat = subtotal + vatAmount;

        if (discountDisplay) discountDisplay.textContent = '- ' + formatCurrency(discount);
        if (discountAmountInput) discountAmountInput.value = discount;
        if (vatDisplay) vatDisplay.textContent = formatCurrency(vatAmount);
        if (finalTotalDisplay) finalTotalDisplay.textContent = formatCurrency(totalWithVat);
        if (promoCodeDisplay) promoCodeDisplay.textContent = code;
        if (promoCodeHidden) promoCodeHidden.value = code !== '—' ? code : '';

        const alpineEl = document.querySelector('[x-data]');
        if (alpineEl && alpineEl.__x) {
            try { alpineEl.__x.$data.finalTotalText = formatCurrency(totalWithVat); } catch (e) {}
        }

        if (promoMessage) {
            promoMessage.textContent = message || '';
            promoMessage.className = `promo-msg ${discount > 0 ? 'success' : 'error'}`;
        }
    };

    if (applyBtn && form) {
        try { applyBtn.type = 'button'; } catch (e) {}

        applyBtn.addEventListener('click', async(e) => {
            if (e && typeof e.preventDefault === 'function') e.preventDefault();
            const code = (promoInput && promoInput.value ? promoInput.value : '').trim().toUpperCase();
            if (!code) return updateSummary(0, originalTotal, 'Vui lòng nhập mã.', '—');

            applyBtn.disabled = true;
            const originalBtnText = applyBtn.textContent;
            applyBtn.textContent = '...';
            if (promoMessage) {
                promoMessage.textContent = 'Đang kiểm tra...';
                promoMessage.style.color = '#6b7280';
            }

            try {
                const checkUrl = applyBtn.dataset.routeCheckPromo;
                const response = await axios.post(checkUrl, { code: code, original_total: originalTotal }, { headers: { 'X-No-Reload': '1' } });
                const data = response.data || {};
                if (data.success) {
                    // Tính lại với VAT thay vì dùng final_total từ server
                    updateSummary(data.discount_amount || 0, originalTotal, data.message || 'Áp dụng mã thành công', code);
                } else {
                    updateSummary(0, originalTotal, data.message || 'Mã không hợp lệ', '—');
                }
            } catch (err) {
                console.error('Promo Check Error:', err);
                let msg = 'Lỗi hệ thống khi kiểm tra mã.';
                if (err.response) {
                    if (err.response.status === 404) msg = 'Không tìm thấy API (404). Kiểm tra route.';
                    else if (err.response.status === 419) msg = 'Phiên làm việc hết hạn. Vui lòng tải lại trang.';
                    else if (err.response.data && err.response.data.message) msg = err.response.data.message;
                }
                updateSummary(0, originalTotal, msg, '—');
            } finally {
                applyBtn.disabled = false;
                applyBtn.textContent = originalBtnText;
            }
        });

        if (promoInput) {
            promoInput.addEventListener('input', () => { if (promoMessage) promoMessage.textContent = ''; });
            promoInput.addEventListener('keydown', (ev) => {
                if (ev.key === 'Enter') {
                    ev.preventDefault();
                    applyBtn.click();
                }
            });
        }
    }

    document.querySelectorAll('.btn-copy').forEach(btn => {
        btn.addEventListener('click', () => {
            const code = btn.getAttribute('data-code') || btn.textContent || '';
            if (navigator.clipboard) {
                const prev = btn.innerHTML;
                navigator.clipboard.writeText(code).then(() => {
                    btn.textContent = 'Đã sao chép';
                    setTimeout(() => { btn.innerHTML = prev; }, 1200);
                }).catch(() => alert('Không thể sao chép. Hãy thử thủ công.'));
            } else {
                alert('Trình duyệt không hỗ trợ clipboard API.');
            }
        });
    });

    window.submitVnPay = function() {
        if (!form) return console.error('Form booking not found');
        if (!confirm('Xác nhận bạn đã chuyển khoản thành công? Hệ thống sẽ tạo đơn và duyệt ngay.')) return;

        const vnpOrderInfoEl = document.querySelector('#vnpay-form [name="vnp_OrderInfo"]');
        const roomEl = document.querySelector('[name="room_id"]');
        const roomId = (roomEl && roomEl.value) || 'unknown';
        const vnpOrderInfo = (vnpOrderInfoEl && vnpOrderInfoEl.value) || `Thanh toan don dat phong #${roomId}`;

        const addHiddenInput = (name, value) => {
            let input = form.querySelector(`input[name="${name}"]`);
            if (!input) {
                input = document.createElement('input');
                input.type = 'hidden';
                input.name = name;
                form.appendChild(input);
            }
            input.value = value;
        };

        addHiddenInput('payment_method', 'online');
        addHiddenInput('vnp_BankCode', 'VNPAYQR');
        addHiddenInput('vnp_Locale', 'vn');
        addHiddenInput('vnp_OrderInfo', vnpOrderInfo);

        if (form.dataset && form.dataset.routeVnpay) form.action = form.dataset.routeVnpay;
        form.submit();
    };

});