@extends('layouts.app')
@section('title', 'Xác nhận đặt phòng')

<!-- Gọi CSS -->
@vite(['resources/css/client/home.css', 'resources/css/client/booking.css'])

@section('content')
<div class="booking-page-wrapper" x-data="{ onlinePaymentSelected: false, showVnpayModal: false, finalTotalText: '{{ number_format($totalPrice, 0, ',', '.') }}đ' }">
    <div class="container">
        
        <!-- Page Header -->
        <div class="page-header-center">
            <h1 class="page-title">Hoàn tất đặt phòng</h1>
            <p class="page-desc">Vui lòng kiểm tra lại thông tin và áp dụng ưu đãi trước khi xác nhận.</p>
        </div>

        <form action="{{ route('booking.store') }}" method="POST" class="booking-layout" id="booking-form">
            @csrf
            <input type="hidden" name="room_id" value="{{ $roomType->id }}">
            <input type="hidden" name="checkin" value="{{ $checkIn }}">
            <input type="hidden" name="checkout" value="{{ $checkOut }}">
            <input type="hidden" name="discount_amount" id="discount-amount-input" value="0">
            <input type="hidden" name="promotion_code" id="promotion-code-hidden" value="">

            <!-- CỘT TRÁI -->
            <div class="booking-form-area">
                
                <!-- 1. Thông tin đăng ký -->
                <div class="booking-card">
                    <div class="step-header">
                        <div class="step-number">1</div>
                        <h3 class="step-title">Thông tin đăng ký</h3>
                    </div>
                    
                    <div class="info-grid">
                        <div class="info-group">
                            <label class="info-label">Họ và tên</label>
                            <div class="info-value-box">{{ Auth::user()->name }}</div>
                        </div>
                        <div class="info-group">
                            <label class="info-label">Số điện thoại</label>
                            <div class="info-value-box">{{ Auth::user()->phone ?? 'Chưa cập nhật' }}</div>
                        </div>
                    </div>
                    <div class="info-note">
                        Nếu cần thay đổi, bạn có thể <a href="{{ route('profile.edit') }}">cập nhật hồ sơ</a>.
                    </div>
                </div>

                <!-- 2. Mã khuyến mãi -->
                <div class="booking-card">
                    <div class="step-header">
                        <div class="step-number">2</div>
                        <h3 class="step-title">Mã khuyến mãi</h3>
                    </div>
                    <div class="promo-input-group">
                        <input type="text" name="promotion_code_display" id="promotion-code-input"
                               placeholder="NHẬP MÃ (VD: SUMMER2025)..." class="promo-input">
                        <button type="button" id="apply-promo-btn" class="btn-apply">Áp dụng</button>
                    </div>
                    <p id="promo-message" class="promo-msg"></p>
                </div>
                
                <!-- 3. Thanh toán -->
                <div class="booking-card">
                    <div class="step-header">
                        <div class="step-number">3</div>
                        <h3 class="step-title">Lựa chọn thanh toán</h3>
                    </div>
                    
                    <!-- Option 1: Pay at hotel -->
                    <label class="payment-option" :class="{'selected': !onlinePaymentSelected}">
                        <input type="radio" name="payment_method" value="pay_at_hotel" 
                               :checked="!onlinePaymentSelected" 
                               @click="onlinePaymentSelected = false"
                               class="radio-custom">
                        <div class="payment-info">
                            <span class="payment-title">Thanh toán tại khách sạn</span>
                            <span class="payment-desc">Thanh toán khi làm thủ tục nhận phòng (Check-in). Admin sẽ duyệt đơn thủ công.</span>
                        </div>
                        <div class="payment-icon"><i class="fa-solid fa-hotel"></i></div>
                    </label>

                    <!-- Option 2: Online -->
                    <label class="payment-option" :class="{'selected': onlinePaymentSelected}">
                        <input type="radio" name="payment_method" value="online" 
                               :checked="onlinePaymentSelected" 
                               @click="onlinePaymentSelected = true"
                               class="radio-custom">
                        <div class="payment-info">
                            <span class="payment-title">Thanh toán Online (VNPay/Momo)</span>
                            <span class="payment-desc">Đơn sẽ được <strong>tự động duyệt</strong> ngay sau khi thanh toán.</span>
                        </div>
                        <div class="payment-icon"><i class="fa-brands fa-cc-visa"></i></div>
                    </label>
                    
                    @error('payment_method') <p class="promo-msg error">{{ $message }}</p> @enderror
                </div>

                <!-- 4. Ghi chú -->
                <div class="booking-card">
                    <div class="step-header">
                        <div class="step-number">4</div>
                        <h3 class="step-title">Ghi chú (Tùy chọn)</h3>
                    </div>
                    <textarea name="ghi_chu" rows="3" placeholder="Nhập yêu cầu đặc biệt..." 
                              class="form-control" style="background: rgba(255,255,255,0.05); border: 1px solid var(--border-light); color: var(--white);"></textarea>
                </div>
            </div>

            <!-- CỘT PHẢI: Summary Sticky -->
            <div class="booking-sidebar">
                <div class="summary-card" id="summary-card">
                    <h3 class="summary-title">Chi tiết đơn đặt</h3>
                    
                    <!-- Info Phòng -->
                    <div class="summary-room-info">
                        <img src="{{ $roomType->hinh_anh ? asset($roomType->hinh_anh) : asset('uploads/home/phongdefault.png') }}" class="summary-thumb">
                        <div>
                            <h4 class="summary-room-name">{{ $roomType->ten_loai_phong }}</h4>
                            <p class="summary-room-guest"><i class="fa-solid fa-user"></i> {{ $roomType->so_nguoi }} Khách</p>
                        </div>
                    </div>

                    <!-- Ngày -->
                    <div class="summary-dates">
                        <div class="date-row">
                            <div class="date-col">
                                <span class="date-label">Nhận phòng</span>
                                <span class="date-value">{{ \Carbon\Carbon::parse($checkIn)->format('d/m/Y') }}</span>
                            </div>
                            <div class="date-arrow"><i class="fa-solid fa-arrow-right-long"></i></div>
                            <div class="date-col right">
                                <span class="date-label">Trả phòng</span>
                                <span class="date-value">{{ \Carbon\Carbon::parse($checkOut)->format('d/m/Y') }}</span>
                            </div>
                        </div>
                        <span class="stay-duration"><i class="fa-regular fa-clock"></i> {{ $days }} Đêm lưu trú</span>
                    </div>

                    <!-- Tiền -->
                    <div class="summary-totals">
                        <div class="total-row">
                            <span>Giá gốc</span>
                            <span id="original-total" data-original-price="{{ $totalPrice }}">{{ number_format($totalPrice, 0, ',', '.') }}đ</span>
                        </div>
                        <div class="total-row discount">
                            <span>Giảm giá (<span id="promo-code-display">—</span>)</span>
                            <span id="discount-display">- 0đ</span>
                        </div>
                        <div class="divider-dashed"></div>
                        <div class="final-total">
                            <span>TỔNG CỘNG</span>
                            <span class="final-price" id="final-total-display" x-text="finalTotalText">{{ number_format($totalPrice, 0, ',', '.') }}đ</span>
                        </div>
                    </div>

                    <!-- Nút xác nhận -->
                    <button type="submit" 
                            x-on:click.prevent="if (onlinePaymentSelected) { showVnpayModal = true; } else { document.getElementById('booking-form').submit(); }"
                            class="btn-confirm-booking">
                        <span x-text="onlinePaymentSelected ? 'THANH TOÁN NGAY' : 'GỬI YÊU CẦU'">GỬI YÊU CẦU</span>
                        <i class="fa-solid fa-arrow-right"></i>
                    </button>
                    
                    <p class="terms-text">
                        Bằng việc xác nhận, bạn đồng ý với <a href="#">Điều khoản</a> của Luxury Stay.
                    </p>
                </div>
            </div>
        </form>
    </div>

    {{-- MODAL VNPAY QR (Giả lập) --}}
    <div x-show="showVnpayModal" class="modal-overlay" x-cloak>
        <div class="modal-content" @click.away="showVnpayModal = false">
            <button @click="showVnpayModal = false" class="modal-close"><i class="fa-solid fa-xmark"></i></button>

            <h3 class="filter-title" style="margin-bottom: 0.5rem;">Quét mã thanh toán</h3>
            <p class="text-muted" style="font-size: 0.9rem;">Sử dụng App Ngân hàng hoặc Ví VNPAY</p>

            <form action="{{ route('booking.vnpay.create') }}" method="POST" id="vnpay-form">
                @csrf
                <input type="hidden" name="room_id" value="{{ $roomType->id }}">
                <input type="hidden" name="checkin" value="{{ $checkIn }}">
                <input type="hidden" name="checkout" value="{{ $checkOut }}">
                <input type="hidden" name="discount_amount" x-bind:value="document.getElementById('discount-amount-input').value">
                <input type="hidden" name="promotion_code" x-bind:value="document.getElementById('promotion-code-hidden').value">
                <input type="hidden" name="payment_method" value="online">
                
                <div class="qr-container">
                    <img src="https://chart.googleapis.com/chart?chs=200x200&cht=qr&chl=VNPAY_TEST_PAYMENT&choe=UTF-8" alt="QR Code" class="w-full">
                </div>

                <div class="mb-4">
                    <span class="text-muted text-sm block uppercase">Số tiền cần thanh toán</span>
                    <strong class="final-price" x-text="finalTotalText"></strong>
                </div>

                <button type="submit" class="btn-confirm-booking" style="background: #10b981; color: white;"
                        onclick="return confirm('Xác nhận đã chuyển khoản?')">
                    <i class="fa-solid fa-check-double"></i> XÁC NHẬN ĐÃ THANH TOÁN
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    if (typeof axios === 'undefined') { console.error('Axios is not loaded.'); }
    
    document.addEventListener('DOMContentLoaded', function() {
        const promoInput = document.getElementById('promotion-code-input');
        const applyBtn = document.getElementById('apply-promo-btn');
        const originalTotalSpan = document.getElementById('original-total');
        const discountDisplay = document.getElementById('discount-display');
        const finalTotalDisplay = document.getElementById('final-total-display');
        const discountAmountInput = document.getElementById('discount-amount-input');
        const promoMessage = document.getElementById('promo-message');
        const promoCodeDisplay = document.getElementById('promo-code-display');
        const promoCodeHidden = document.getElementById('promotion-code-hidden');
        
        const alpineElement = document.querySelector('[x-data]');
        let alpineData = (alpineElement && alpineElement.__x) ? alpineElement.__x.$data : null;

        const originalTotal = parseFloat(originalTotalSpan.dataset.originalPrice);

        function formatCurrency(amount) {
            return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND', minimumFractionDigits: 0 }).format(amount);
        }

        function updateSummary(discount, finalTotal, message, code = '—') {
            discountDisplay.textContent = '- ' + formatCurrency(discount);
            discountAmountInput.value = discount;
            finalTotalDisplay.textContent = formatCurrency(finalTotal);
            promoCodeDisplay.textContent = code;
            promoCodeHidden.value = code !== '—' ? code : '';
            
            if (alpineData) { alpineData.finalTotalText = formatCurrency(finalTotal); }

            if (message) {
                promoMessage.textContent = message;
                promoMessage.className = `promo-msg ${discount > 0 ? 'success' : 'error'}`;
            } else {
                promoMessage.textContent = '';
            }
        }

        applyBtn.addEventListener('click', async function() {
            const code = promoInput.value.trim().toUpperCase();
            if (!code) { updateSummary(0, originalTotal, 'Vui lòng nhập mã khuyến mãi.', '—'); return; }

            applyBtn.disabled = true;
            promoMessage.textContent = 'Đang kiểm tra mã...'; promoMessage.className = 'promo-msg';

            try {
                const response = await axios.post('{{ route('api.check.promo') }}', { code: code, original_total: originalTotal });
                const data = response.data;
                if (data.success) {
                    updateSummary(data.discount_amount, data.final_total, data.message, code);
                } else {
                    updateSummary(0, originalTotal, data.message, '—');
                }
            } catch (error) {
                updateSummary(0, originalTotal, 'Lỗi hệ thống khi kiểm tra mã.', '—');
            } finally {
                applyBtn.disabled = false;
            }
        });
        
        promoInput.addEventListener('input', function() {
            if (promoMessage.textContent !== '' || discountAmountInput.value !== '0') {
                updateSummary(0, originalTotal, null, '—');
            }
        });
    });
</script>
@endpush