@extends('layouts.app')
@section('title', 'Xác nhận đặt phòng')

@section('content')
{{-- Khai báo Alpine.js để quản lý trạng thái modal --}}
<div class="bg-gray-50 py-16 min-h-screen" x-data="{ onlinePaymentSelected: false, showVnpayModal: false, finalTotalText: '{{ number_format($totalPrice, 0, ',', '.') }}đ' }">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center mb-10">
            <h1 class="font-serif text-3xl md:text-4xl font-bold text-gray-900 mb-2">Hoàn tất đặt phòng</h1>
            <p class="text-gray-500">Vui lòng kiểm tra lại thông tin và áp dụng ưu đãi trước khi xác nhận.</p>
        </div>

        {{-- FORM GỐC CHO PAY AT HOTEL --}}
        <form action="{{ route('booking.store') }}" method="POST" class="grid grid-cols-1 lg:grid-cols-3 gap-8" id="booking-form">
            @csrf
            <!-- Dữ liệu ẩn quan trọng -->
            <input type="hidden" name="room_id" value="{{ $roomType->id }}">
            <input type="hidden" name="checkin" value="{{ $checkIn }}">
            <input type="hidden" name="checkout" value="{{ $checkOut }}">
            
            <!-- Hidden inputs cho logic giảm giá -->
            <input type="hidden" name="discount_amount" id="discount-amount-input" value="0">
            <input type="hidden" name="promotion_code" id="promotion-code-hidden" value="">

            <!-- CỘT TRÁI: Thông tin khách & Thanh toán -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Box 1: Thông tin đăng ký -->
                <div class="bg-white p-6 md:p-8 rounded-2xl shadow-sm border border-gray-100">
                    <h3 class="font-bold text-lg mb-6 flex items-center text-brand-900 pb-4 border-b border-gray-100">
                        <span class="w-8 h-8 rounded-full bg-brand-900 text-white flex items-center justify-center text-sm mr-3 font-mono">1</span>
                        Thông tin đăng ký
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
                        <div class="group">
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Họ và tên</label>
                            <span class="block bg-gray-50 border border-gray-200 rounded-lg px-4 py-3 text-gray-700 font-medium">{{ Auth::user()->name }}</span>
                        </div>
                        <div class="group">
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Số điện thoại</label>
                            <span class="block bg-gray-50 border border-gray-200 rounded-lg px-4 py-3 text-gray-700 font-medium">{{ Auth::user()->phone ?? 'Chưa cập nhật' }}</span>
                        </div>
                        <div class="col-span-full mt-2">
                             <p class="text-xs text-gray-500 italic">
                                Nếu cần thay đổi, bạn có thể <a href="{{ route('profile.edit') }}" class="underline font-bold text-brand-gold hover:text-brand-900">cập nhật hồ sơ</a>.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Box 2: Mã khuyến mãi (AJAX) -->
                <div class="bg-white p-6 md:p-8 rounded-2xl shadow-sm border border-gray-100">
                    <h3 class="font-bold text-lg mb-6 flex items-center text-brand-900 pb-4 border-b border-gray-100">
                        <span class="w-8 h-8 rounded-full bg-brand-900 text-white flex items-center justify-center text-sm mr-3 font-mono">2</span>
                        Mã khuyến mãi
                    </h3>
                    <div class="flex gap-3">
                        <input type="text" 
                               name="promotion_code_display" 
                               id="promotion-code-input"
                               value="{{ old('promotion_code') }}"
                               placeholder="Nhập mã giảm giá (VD: SUMMER2025)..." 
                               class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm uppercase">
                        
                        <button type="button" id="apply-promo-btn" class="px-4 py-2 bg-brand-gold text-brand-900 rounded-md font-bold text-sm hover:bg-opacity-90 transition disabled:opacity-50">
                            Áp dụng
                        </button>
                    </div>
                    <p id="promo-message" class="text-sm mt-2"></p>
                </div>
                
                <!-- Box 3: Lựa chọn thanh toán (SỬ DỤNG ALPINE.JS) -->
                <div class="bg-white p-6 md:p-8 rounded-2xl shadow-sm border border-gray-100">
                    <h3 class="font-bold text-lg mb-6 flex items-center text-brand-900 pb-4 border-b border-gray-100">
                        <span class="w-8 h-8 rounded-full bg-brand-900 text-white flex items-center justify-center text-sm mr-3 font-mono">3</span>
                        Lựa chọn thanh toán
                    </h3>
                    
                    <div class="space-y-4">
                        <!-- Option 1: Thanh toán tại khách sạn -->
                        <label class="relative flex items-start p-5 border-2 rounded-xl cursor-pointer transition-all bg-brand-50 shadow-sm hover:shadow-md"
                               :class="{'border-brand-gold': !onlinePaymentSelected, 'border-gray-200': onlinePaymentSelected}">
                            <div class="flex items-center h-6">
                                <input type="radio" name="payment_method" value="pay_at_hotel" 
                                       :checked="!onlinePaymentSelected" 
                                       @click="onlinePaymentSelected = false"
                                       class="w-5 h-5 text-brand-900 border-gray-300 focus:ring-brand-900">
                            </div>
                            <div class="ml-4">
                                <span class="block font-bold text-gray-900 text-lg">Thanh toán tại khách sạn</span>
                                <span class="block text-sm text-gray-600 mt-1">Bạn thanh toán khi làm thủ tục nhận phòng (Check-in). Admin sẽ duyệt đơn thủ công.</span>
                            </div>
                            <i class="fa-solid fa-hotel absolute top-5 right-5 text-2xl text-brand-900/10"></i>
                        </label>

                        <!-- Option 2: Thanh toán Online -->
                        <label class="relative flex items-start p-5 border rounded-xl cursor-pointer transition-all hover:border-brand-gold"
                               :class="{'border-brand-gold': onlinePaymentSelected, 'border-gray-200': !onlinePaymentSelected}">
                             <div class="flex items-center h-6">
                                <input type="radio" name="payment_method" value="online" 
                                       :checked="onlinePaymentSelected" 
                                       @click="onlinePaymentSelected = true"
                                       class="w-5 h-5 text-brand-900 border-gray-300 focus:ring-brand-900">
                            </div>
                            <div class="ml-4">
                                <span class="block font-bold text-gray-900 text-lg">Thanh toán Online (VNPay/Momo)</span>
                                <span class="block text-sm text-gray-600 mt-1">Đơn sẽ được **tự động duyệt ngay lập tức** sau khi thanh toán thành công.</span>
                            </div>
                            <i class="fa-brands fa-cc-paypal absolute top-5 right-5 text-2xl text-brand-900/10"></i>
                        </label>
                    </div>
                    @error('payment_method') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                
                 <!-- Box 4: Ghi chú (ĐÃ THÊM) -->
                <div class="bg-white p-6 md:p-8 rounded-2xl shadow-sm border border-gray-100">
                    <h3 class="font-bold text-lg mb-4 flex items-center text-brand-900 pb-4 border-b border-gray-100">
                        <span class="w-8 h-8 rounded-full bg-brand-900 text-white flex items-center justify-center text-sm mr-3 font-mono">4</span>
                        Ghi chú (Tùy chọn)
                    </h3>
                    <textarea name="ghi_chu" rows="3" placeholder="Nhập yêu cầu đặc biệt hoặc ghi chú của bạn..." 
                              class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"></textarea>
                </div>
            </div>

            <!-- CỘT PHẢẢI: Tóm tắt đơn hàng (Sticky) -->
            <div class="lg:col-span-1">
                <div class="bg-white p-6 md:p-8 rounded-2xl shadow-lg border border-gray-100 sticky top-24" id="summary-card">
                    <h3 class="font-bold text-gray-900 mb-6 pb-4 border-b border-gray-100 text-center uppercase tracking-wider text-xs">Chi tiết đơn đặt</h3>
                    
                    <!-- Thông tin phòng -->
                    <div class="flex gap-4 mb-6">
                        <img src="{{ $roomType->hinh_anh ? asset($roomType->hinh_anh) : asset('uploads/home/phongdefault.png') }}" 
                             class="w-20 h-20 object-cover rounded-lg shadow-sm border border-gray-200">
                        <div>
                            <h4 class="font-bold text-brand-900 leading-tight mb-1">{{ $roomType->ten_loai_phong }}</h4>
                            <p class="text-xs text-gray-500"><i class="fa-solid fa-user mr-1"></i> {{ $roomType->so_nguoi }} Khách</p>
                        </div>
                    </div>

                    <!-- Thời gian -->
                    <div class="space-y-4 text-sm text-gray-600 mb-6 bg-gray-50 p-4 rounded-xl border border-gray-100">
                        <div class="flex justify-between items-center">
                            <div class="text-left">
                                <span class="block text-[10px] text-gray-400 uppercase font-bold">Nhận phòng</span>
                                <span class="font-bold text-gray-900 text-base">{{ \Carbon\Carbon::parse($checkIn)->format('d/m/Y') }}</span>
                            </div>
                            <div class="text-gray-300"><i class="fa-solid fa-arrow-right-long"></i></div>
                            <div class="text-right">
                                <span class="block text-[10px] text-gray-400 uppercase font-bold">Trả phòng</span>
                                <span class="font-bold text-gray-900 text-base">{{ \Carbon\Carbon::parse($checkOut)->format('d/m/Y') }}</span>
                            </div>
                        </div>
                        <div class="border-t border-gray-200 pt-3 text-center">
                            <span class="inline-block bg-white px-3 py-1 rounded-full text-xs font-bold shadow-sm border border-gray-200 text-brand-gold">
                                <i class="fa-regular fa-clock mr-1"></i> {{ $days }} Đêm lưu trú
                            </span>
                        </div>
                    </div>

                    <!-- Chi phí -->
                    <div class="space-y-3 text-sm mb-8">
                        <div class="flex justify-between text-gray-600">
                            <span>Giá gốc ({{ number_format($roomType->gia) }}đ x {{ $days }} đêm)</span>
                            <span id="original-total" data-original-price="{{ $totalPrice }}">{{ number_format($totalPrice, 0, ',', '.') }}đ</span>
                        </div>
                        
                        <div class="flex justify-between text-red-500 font-medium">
                            <span>Giảm giá (<span id="promo-code-display">—</span>)</span>
                            <span id="discount-display">- 0đ</span>
                        </div>
                        
                        <div class="border-t border-dashed border-gray-200 my-2"></div>
                        
                        <div class="flex justify-between items-center pt-1">
                            <span class="font-bold text-lg text-gray-800">TỔNG CỘNG</span>
                            <span class="font-bold text-2xl text-brand-gold" id="final-total-display" x-text="finalTotalText">{{ number_format($totalPrice, 0, ',', '.') }}đ</span>
                        </div>
                        <p class="text-xs text-gray-500 mt-2">Tổng tiền này là số tiền phải trả cuối cùng.</p>
                    </div>

                    {{-- BUTTON SUBMIT TÙY THUỘC VÀO LỰA CHỌN THANH TOÁN --}}
                    <button type="submit" 
                            x-on:click.prevent="if (onlinePaymentSelected) { showVnpayModal = true; } else { document.getElementById('booking-form').submit(); }"
                            class="w-full bg-brand-900 text-white font-bold py-4 rounded-xl hover:bg-brand-800 transition duration-300 shadow-xl shadow-brand-900/20 flex items-center justify-center gap-2 group">
                        <span x-text="onlinePaymentSelected ? 'THANH TOÁN VNPay' : 'GỬI YÊU CẦU ĐẶT PHÒNG'">GỬI YÊU CẦU ĐẶT PHÒNG</span>
                        <i class="fa-solid fa-arrow-right group-hover:scale-125 transition-transform"></i>
                    </button>
                    
                    <p class="text-[10px] text-center text-gray-400 mt-4 leading-normal px-2">
                        Bằng việc xác nhận, bạn đồng ý với <a href="#" class="underline hover:text-brand-gold">Điều khoản & Chính sách</a> của Luxury Stay.
                    </p>
                </div>
            </div>
        </form>
    </div>

    {{-- MODAL VNPAY QR (GIẢ LẬP - Đã sửa theo yêu cầu) --}}
    <div x-show="showVnpayModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-black bg-opacity-60 z-[999] flex items-center justify-center p-4" x-cloak>
        
        <div @click.away="showVnpayModal = false" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="bg-white rounded-xl max-w-sm w-full p-6 md:p-8 shadow-2xl relative text-center">
            
            <button @click="showVnpayModal = false" class="absolute top-4 right-4 text-gray-400 hover:text-gray-700 transition">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>

            <div class="mb-6">
                <h3 class="font-bold text-xl text-brand-900 mb-1">Quét mã QR để thanh toán</h3>
                <p class="text-sm text-gray-500">Sử dụng ứng dụng ngân hàng của bạn.</p>
            </div>

            {{-- Form này sẽ POST dữ liệu đặt phòng + tùy chọn VNPay đến Controller --}}
            <form action="{{ route('booking.vnpay.create') }}" method="POST" id="vnpay-form" class="space-y-4">
                @csrf
                
                {{-- Dữ liệu Ẩn (Copy từ form chính) --}}
                <input type="hidden" name="room_id" value="{{ $roomType->id }}">
                <input type="hidden" name="checkin" value="{{ $checkIn }}">
                <input type="hidden" name="checkout" value="{{ $checkOut }}">
                <input type="hidden" name="discount_amount" x-bind:value="document.getElementById('discount-amount-input').value">
                <input type="hidden" name="promotion_code" x-bind:value="document.getElementById('promotion-code-hidden').value">
                <input type="hidden" name="payment_method" value="online">
                
                {{-- Dữ liệu giả định VNPay --}}
                <input type="hidden" name="vnp_BankCode" value="VNPAYQR">
                <input type="hidden" name="vnp_Locale" value="vn">
                <input type="hidden" name="vnp_OrderInfo" value="Thanh toan don dat phong">

                {{-- HÌNH ẢNH QR CODE (GIẢ LẬP) --}}
                <div class="flex justify-center py-2">
                    <div class="bg-white p-2 border-2 border-brand-gold rounded-lg">
                        {{-- Dùng Google Chart API để tạo QR Code thật từ text --}}
                        <img src="https://chart.googleapis.com/chart?chs=200x200&cht=qr&chl=VNPAY_DEMO_PAYMENT_SUCCESS&choe=UTF-8" alt="QR Code" class="w-48 h-48">
                    </div>
                </div>

                <div class="mb-4 text-center">
                    <span class="text-xs text-gray-500 block uppercase">Số tiền</span>
                    <strong class="font-bold text-2xl text-red-600" x-text="finalTotalText"></strong>
                </div>

                <div>
                    {{-- NÚT XÁC NHẬN (Khi bấm sẽ submit form này -> Controller -> Success) --}}
                    <button type="submit" 
                            class="w-full bg-green-600 text-white font-bold py-3 rounded-lg hover:bg-green-700 transition flex items-center justify-center gap-2"
                            onclick="return confirm('Xác nhận bạn đã chuyển khoản thành công? Hệ thống sẽ duyệt đơn ngay lập tức.')">
                        <i class="fa-solid fa-check-double mr-2"></i> XÁC NHẬN ĐÃ THANH TOÁN
                    </button>
                    <p class="text-[10px] text-gray-400 mt-2">Nhấn nút trên sau khi đã quét mã thành công.</p>
                </div>
            </form>
        </div>
    </div>
    
</div>
@endsection

@push('scripts')
<script>
    // Khai báo Axios nếu chưa có (Thường đã có trong app.js, nhưng cần thiết cho Blade)
    if (typeof axios === 'undefined') {
        console.error('Axios is not loaded. Please ensure resources/js/bootstrap.js is correctly configured and loaded.');
    }
    
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
        
        // Lấy biến Alpine.js ra scope JS
        const alpineElement = document.querySelector('[x-data]');
        let alpineData;
        if (alpineElement && alpineElement.__x && alpineElement.__x.$data) {
            alpineData = alpineElement.__x.$data;
        }


        // Lấy tổng tiền gốc từ data attribute
        const originalTotal = parseFloat(originalTotalSpan.dataset.originalPrice);

        // Hàm định dạng tiền tệ
        function formatCurrency(amount) {
            return new Intl.NumberFormat('vi-VN', { 
                style: 'currency', 
                currency: 'VND',
                minimumFractionDigits: 0
            }).format(amount);
        }

        // Cập nhật hiển thị tổng tiền và Alpine state
        function updateSummary(discount, finalTotal, message, code = '—') {
            discountDisplay.textContent = '- ' + formatCurrency(discount);
            discountAmountInput.value = discount;
            finalTotalDisplay.textContent = formatCurrency(finalTotal);
            promoCodeDisplay.textContent = code;
            promoCodeHidden.value = code !== '—' ? code : ''; // Lưu mã vào hidden field
            
            // Cập nhật state Alpine để modal hiển thị đúng tổng tiền
            if (alpineData) {
                alpineData.finalTotalText = formatCurrency(finalTotal);
            }


            if (message) {
                promoMessage.textContent = message;
                promoMessage.className = `text-sm mt-2 font-medium ${discount > 0 ? 'text-green-600' : 'text-red-600'}`;
            } else {
                promoMessage.textContent = '';
            }
        }

        // Xử lý sự kiện khi nhấn nút Áp dụng
        applyBtn.addEventListener('click', async function() {
            const code = promoInput.value.trim().toUpperCase();
            
            if (!code) {
                updateSummary(0, originalTotal, 'Vui lòng nhập mã khuyến mãi.', '—');
                return;
            }

            applyBtn.disabled = true;
            promoMessage.textContent = 'Đang kiểm tra mã...';
            promoMessage.className = 'text-sm mt-2 text-gray-500';

            try {
                const response = await axios.post('{{ route('api.check.promo') }}', {
                    code: code,
                    original_total: originalTotal
                });

                const data = response.data;

                if (data.success) {
                    updateSummary(
                        data.discount_amount,
                        data.final_total,
                        data.message,
                        code
                    );
                } else {
                    updateSummary(0, originalTotal, data.message, '—');
                }
            } catch (error) {
                console.error("Lỗi AJAX:", error);
                updateSummary(0, originalTotal, 'Lỗi hệ thống khi kiểm tra mã.', '—');
                // Gỡ lỗi Alpine: Kiểm tra nếu Alpine chưa sẵn sàng
                if (!alpineData) {
                    console.warn("Alpine Data is not ready. Ensure Alpine.js is loaded correctly.");
                }
            } finally {
                applyBtn.disabled = false;
            }
        });
        
        // Reset khuyến mãi khi thay đổi mã
        promoInput.addEventListener('input', function() {
            if (promoMessage.textContent !== '' || discountAmountInput.value !== '0') {
                updateSummary(0, originalTotal, null, '—');
            }
        });
    });
</script>
@endpush