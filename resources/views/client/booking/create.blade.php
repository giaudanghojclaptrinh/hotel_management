@extends('layouts.app')
@section('title', 'Xác nhận đặt phòng')

@vite(['resources/css/client/booking.css', 'resources/js/client/booking.js'])

@section('content')
{{-- Khai báo Alpine.js --}}
<div class="booking-page-wrapper" 
     x-data="{ onlinePaymentSelected: false, showVnpayModal: false, finalTotalText: '{{ number_format($totalWithVat, 0, ',', '.') }}đ', vnpLocale: 'vn', vnpOrderInfo: 'Thanh toan don dat phong #{{ $roomType->id }}' }"
     x-on:open-vnpay-modal.window="showVnpayModal = true">
    
    <div class="container">
        
        <!-- Page Header -->
        <div class="page-header-center">
            <h1 class="page-title">Hoàn tất đặt phòng</h1>
            <p class="page-desc">Vui lòng kiểm tra lại thông tin và áp dụng ưu đãi trước khi xác nhận.</p>
        </div>

        {{-- FORM CHÍNH --}}
        <form action="{{ route('booking.store') }}" 
              method="POST" 
              class="booking-layout" 
              id="booking-form"
              data-route-vnpay="{{ route('booking.vnpay.create') }}">
            @csrf
            <!-- Dữ liệu ẩn quan trọng -->
            <input type="hidden" name="room_id" value="{{ $roomType->id }}">
            <input type="hidden" name="checkin" value="{{ $checkIn }}">
            <input type="hidden" name="checkout" value="{{ $checkOut }}">
            
            <!-- Hidden inputs cho logic giảm giá (JS sẽ cập nhật value) -->
            <input type="hidden" name="discount_amount" id="discount-amount-input" value="0">
            <input type="hidden" name="promotion_code" id="promotion-code-hidden" value="">
            
            <!-- Input ẩn để gửi payment_method lên server -->
            <input type="hidden" name="payment_method" :value="onlinePaymentSelected ? 'online' : 'pay_at_hotel'">

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
                        
                        {{-- [QUAN TRỌNG] Thêm data-route-check-promo để JS lấy URL chính xác --}}
                        <button type="button" id="apply-promo-btn" 
                                data-route-check-promo="{{ route('api.check.promo') }}"
                                class="btn-apply">Áp dụng</button>
                    </div>
                    <p id="promo-message" class="promo-msg text-sm font-medium mt-2"></p>
                </div>
                
                <!-- 3. Thanh toán -->
                <div class="booking-card">
                    <div class="step-header">
                        <div class="step-number">3</div>
                        <h3 class="step-title">Lựa chọn thanh toán</h3>
                    </div>
                    
                    <label class="payment-option" :class="{'selected': !onlinePaymentSelected}">
                        <input type="radio" name="payment_method_radio" value="pay_at_hotel" 
                               :checked="!onlinePaymentSelected" 
                               @click="onlinePaymentSelected = false"
                               class="radio-custom">
                        <div class="payment-info">
                            <span class="payment-title">Thanh toán tại khách sạn</span>
                            <span class="payment-desc">Thanh toán khi làm thủ tục nhận phòng (Check-in). Admin sẽ duyệt đơn thủ công.</span>
                        </div>
                        <div class="payment-icon"><i class="fa-solid fa-hotel"></i></div>
                    </label>

                    <label class="payment-option" :class="{'selected': onlinePaymentSelected}">
                        <input type="radio" name="payment_method_radio" value="online" 
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
                              class="form-control" style="background: rgba(255,255,255,0.05); border: 1px solid var(--border-light); color: var(--white); outline: none; padding: 10px; border-radius: 8px; width: 100%;"></textarea>
                </div>
            </div>

            <!-- CỘT PHẢI: Summary Sticky -->
            <div class="booking-sidebar">
                <div class="summary-card" id="summary-card">
                    <h3 class="summary-title">Chi tiết đơn đặt</h3>
                    
                    <div class="summary-room-info">
                        <img src="{{ $roomType->hinh_anh ? asset($roomType->hinh_anh) : asset('uploads/home/phongdefault.png') }}" class="summary-thumb">
                        <div>
                            <h4 class="summary-room-name">{{ $roomType->ten_loai_phong }}</h4>
                            <p class="summary-room-guest"><i class="fa-solid fa-user"></i> {{ $roomType->so_nguoi }} Khách</p>
                        </div>
                    </div>

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

                    <div class="summary-totals">
                        <div class="total-row">
                            <span>Giá gốc ({{ $days }} đêm)</span>
                            {{-- [QUAN TRỌNG] Thêm data-original-price --}}
                            <span id="original-total" data-original-price="{{ $totalPrice }}">{{ number_format($totalPrice, 0, ',', '.') }}đ</span>
                        </div>
                        <div class="total-row discount">
                            <span>Giảm giá (<span id="promo-code-display">—</span>)</span>
                            <span id="discount-display">- 0đ</span>
                        </div>
                        <div class="total-row" style="color: #10b981;">
                            <span>Thuế VAT (8%)</span>
                            <span id="vat-display" data-vat-rate="0.08">{{ number_format($vatAmount, 0, ',', '.') }}đ</span>
                        </div>
                        <div class="divider-dashed"></div>
                        <div class="final-total">
                            <span>TỔNG THANH TOÁN</span>
                            <span class="final-price" id="final-total-display" x-text="finalTotalText">{{ number_format($totalWithVat, 0, ',', '.') }}đ</span>
                        </div>
                    </div>

                    {{-- NÚT SUBMIT --}}
                    <button type="submit" 
                            x-on:click.prevent="if (onlinePaymentSelected) { showVnpayModal = true; } else { document.getElementById('booking-form').submit(); }"
                            class="btn-confirm-booking">
                        <span x-text="onlinePaymentSelected ? 'THANH TOÁN QR NGAY' : 'GỬI YÊU CẦU ĐẶT PHÒNG'">GỬI YÊU CẦU ĐẶT PHÒNG</span>
                        <i class="fa-solid fa-arrow-right"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- MODAL VNPAY QR (MÀN HÌNH XÁC NHẬN DEMO) --}}
    <div x-show="showVnpayModal" class="modal-overlay" x-cloak>
        <div class="modal-content" @click.away="showVnpayModal = false">
            <button @click="showVnpayModal = false" class="modal-close"><i class="fa-solid fa-xmark"></i></button>

            <h3 class="filter-title" style="margin-bottom: 0.5rem; color: #fff;">Quét mã thanh toán</h3>
            <p class="text-muted" style="font-size: 0.9rem;">Sử dụng App Ngân hàng hoặc Ví VNPAY</p>

            {{-- Form này chỉ để hiển thị hoặc chứa input ẩn cho JS clone --}}
            {{-- [FIXED] Form này sẽ được JS submitVnPay() sử dụng --}}
            <form id="vnpay-form" method="POST" action="{{ route('booking.vnpay.create') }}" class="space-y-4"> 
                @csrf
                {{-- Các input ẩn cần thiết cho Controller --}}
                <input type="hidden" name="room_id" value="{{ $roomType->id }}">
                <input type="hidden" name="checkin" value="{{ $checkIn }}">
                <input type="hidden" name="checkout" value="{{ $checkOut }}">
                
                {{-- [QUAN TRỌNG] Các input này sẽ được JS cập nhật khi áp mã --}}
                <input type="hidden" name="discount_amount" value="0">
                <input type="hidden" name="promotion_code" value="">

                <input type="hidden" name="vnp_BankCode" value="VNPAYQR">
                {{-- Các input này sẽ được cập nhật từ Alpine --}}
                <input type="hidden" name="vnp_Locale" x-model="vnpLocale">
                <input type="hidden" name="vnp_OrderInfo" x-model="vnpOrderInfo">

                <div class="qr-container" style="background: white; padding: 10px; border-radius: 8px; margin: 15px auto; width: fit-content;">
                    <img src="{{ asset('uploads/QR/QR.jpg') }}" 
                        alt="QR Code" 
                        style="width: 200px; height: 200px;">
                </div>

                <div class="mb-4">
                    <span class="text-muted text-sm block uppercase">Số tiền cần thanh toán</span>
                    <strong class="final-price" style="color: #ef4444; font-size: 1.5rem;" x-text="finalTotalText"></strong>
                </div>

                <div>
                    {{-- NÚT NÀY GỌI HÀM JS submitVnPay() --}}
                    <button type="button" 
                            onclick="submitVnPay()"
                            class="btn-confirm-booking" style="background: #10b981; color: white;">
                        <i class="fa-solid fa-check-double" style="margin-right: 5px;"></i> XÁC NHẬN ĐÃ THANH TOÁN
                    </button>
                    <p class="text-[11px] text-gray-400 mt-2">Nhấn nút trên sau khi đã quét mã thành công.</p>
                </div>
            </form>
        </div>
    </div>
    
</div>
@endsection